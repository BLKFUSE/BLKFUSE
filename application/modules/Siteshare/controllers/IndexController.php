<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_IndexController extends Core_Controller_Action_Standard
{
  public function shareAction()
  {
    // Make sure user exists
    if( !$this->_helper->requireUser()->isValid() )
      return;
    $type = $this->_getParam('type');
    $id = $this->_getParam('id');
    $parent_action_id = $this->_getparam('action_id', null);
    $memories = $this->_getparam('onthisday', null);

    if( null != $this->_helper->contextSwitch->getCurrentContext() ) {
      $this->_helper->layout->setLayout('default-simple');
    }

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->attachment = $attachment = Engine_Api::_()->getItem($type, $id);
    Engine_Api::_()->core()->setSubject($attachment);
    if( Engine_Api::_()->getApi('settings', 'core')->getSetting('siteshare.share.bookmarks.enabled', 1) ) {
      $this->view->social_navigation = $social_navigation = Engine_Api::_()->getApi('menus', 'core')
        ->getNavigation('siteshare_social_link');
    }
    $this->view->form = $form = new Siteshare_Form_Share();
    $form->body->setAttrib('placeholder', $this->view->translate( 'Write something ...' ) );

    $form->body->setAttribs(array('rows' => 1));
    if( !$attachment ) {
      // tell smoothbox to close
      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('You cannot share this item because it has been removed.');
      $this->view->smoothboxClose = true;
      return $this->render('deletedItem');
    }

    // hide facebook and twitter option if not logged in
    $Api_facebook = Engine_Api::_()->getApi('facebook_Facebookinvite', 'siteshare');
    $facebook_userfeed = $Api_facebook->getFBInstance();
    $fb_checkconnection = '';
    if( !empty($facebook_userfeed) )
      $fb_checkconnection = $Api_facebook->checkConnection(null, $facebook_userfeed);
    // $facebook = Siteshare_Api_Facebook_Facebookinvite();
    $Api_facebook->getFBInstance();
    if( !$Api_facebook || !$fb_checkconnection ) {
      $form->removeElement('post_to_facebook');
    }
    try {
      $Api_twitter = Engine_Api::_()->getApi('twitter_Api', 'siteshare');
      $twitterOauth = $twitter = $Api_twitter->getApi();
      if( $twitter && $Api_twitter->isConnected() ) {
        // @todo truncation?
        // @todo attachment
        //$twitter = $twitterTable->getApi();
        //$twitter->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
        $twitterData = (array) $twitterOauth->get(
            'statuses/home_timeline', array('count' => 1)
        );
        if( isset($twitterData['errors']) )
          $form->removeElement('post_to_twitter');
        //$logged_TwitterUserfeed = $twitter->statuses_homeTimeline(array('count' => 1));
      } else {
        $form->removeElement('post_to_twitter');
      }
    } catch( Exception $e ) {
      $form->removeElement('post_to_twitter');
      // Silence
    }

    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    $subject = Engine_Api::_()->core()->getSubject();
    // Process
    $db = Engine_Api::_()->getDbtable('actions', 'activity')->getAdapter();

    $db->beginTransaction();
    try {
      // Get body
      $body = $form->getValue('body');
      $data = $form->getValues();
      $notificationType = "shared";
      $notificationSubject = $viewer;
      // Set Params for Attachment
      $lable = $this->getMediaType($attachment);
      $suffix = "";
      if( $attachment->getType() == 'activity_action' ) {
        $suffix = "_no";
      }
      $params = array(
        'type' => '<a href="' . $attachment->getHref() . '" class="sea_add_tooltip_link' . $suffix . ' feed_' . $attachment->getType() . '_title"  rel="' . $attachment->getType() . ' ' . $attachment->getIdentity() . '" >' . $lable . '</a>',
      );
      if( $data['type'] == 'message' && !empty($data['title']) ) {

        $this->sendMessage($data['item_id'], $body, $viewer, $attachment, $lable);

        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Shared successfully.'))
        ));
      }

      if( $data['type'] == 'email' && !empty($data['title']) ) {
        $emails = explode(",", $data['title']);
        $this->sendEmail($emails, $viewer, $attachment, $body);
        return $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 1000,
            'messages' => array(Zend_Registry::get('Zend_Translate')->_('Shared successfully.'))
        ));
      }
      $shareActionType = 'share';
      if(!empty($memories)){
          $shareActionType = 'share_memories';
      }
      // Add activity
      $api = Engine_Api::_()->getDbtable('actions', 'activity');
      $itemObject = null;
      if( $data['type'] != 'timeline' && !empty($data['item_id']) ) {
        $itemType = stripos($data['type'], '_listingtype_') !== false ? 'sitereview_listing' : $data['type'];
        $itemId = $data['item_id'];
        $itemObject = Engine_Api::_()->getItem($itemType, $itemId);
        if( !$itemObject->authorization()->isAllowed($viewer, 'comment') ) {
          return $this->_forward('success', 'utility', 'core', array(
              'smoothboxClose' => 2000,
              'messages' => array(
                $this->view->translate('You cannot share this item on this: %s.', $itemObject->getTitle())
              )
          ));
        }
        
        if( $itemType != 'user' ) {
          $notificationType = 'shared_content';
        }
        $actionType = "share_on_" . $data['type'];
        $actionTypeData = Engine_Api::_()->getDbtable('actionTypes', 'activity')->getActionType($actionType);
        if( !$actionTypeData ) {
          $actionType = 'share_content';
        }
        if( $itemObject->getType() == "sitepage_page" && Engine_Api::_()->sitepage()->isPageOwner($itemObject) && Engine_Api::_()->sitepage()->isFeedTypePageEnable() ) {
          $actionType = 'sitepage_share_self';
          $notificationType = 'shared_content_self';
          $notificationSubject = $itemObject;
        }
        if( $itemObject->getType() == "sitegroup_group" && Engine_Api::_()->sitegroup()->isGroupOwner($itemObject) && Engine_Api::_()->sitegroup()->isFeedTypeGroupEnable() ) {

          $actionType = 'sitegroup_share_self';
          $notificationType = 'shared_content_self';
          $notificationSubject = $itemObject;
        }

        $action = $api->addActivity($viewer, $itemObject, $actionType, $body, $params);
      } else {
        $action = $api->addActivity($viewer, $attachment->getOwner(), $shareActionType, $body, $params);
      }
      if( $action ) {
        $api->attachActivity($action, $attachment);
        if( !empty($parent_action_id) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('advancedactivity') ) {
          $shareTable = Engine_Api::_()->getDbtable('shares', 'advancedactivity');
          $shareTable->insert(array(
            'resource_type' => (string) $type,
            'resource_id' => (int) $id,
            'parent_action_id' => $parent_action_id,
            'action_id' => $action->action_id,
          ));
        }
      }
      $db->commit();
      // Notifications
      $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
      $memberIdsToNotify = $this->getIdsToSendNotification($data, $action, $attachment, $viewer, $itemObject);

      // Add notification for owner of activity (if user and not viewer)
      $users = Engine_Api::_()->getItemMulti('user', $memberIdsToNotify);
      foreach( $users as $user ) {
        if( $user->getIdentity() != $viewer->getIdentity() ) {
          $tempNotificationType = ($user->getIdentity() == $attachment->getOwner()->getIdentity()) ? 'shared' : $notificationType;
          $tempNotificationType = ($itemObject && $itemObject->getGuid() == $user->getGuid() ) ? 'shared_user' : $tempNotificationType;

          $notifyApi->addNotification($user, $notificationSubject, $action, $tempNotificationType, array(
            'label' => $lable,
            'sharedOnLabel' => $itemObject ? $this->getMediaType($itemObject) : '',
            'sharedOnTitle' => $itemObject ? $itemObject->getTitle() : '',
            'attachment' => $attachment->getGuid(),
          ));
        }
      }
      // Preprocess attachment parameters
      $publishMessage = html_entity_decode($form->getValue('body'));
      $publishUrl = null;
      $publishName = null;
      $publishDesc = null;
      $publishPicUrl = null;
      // Add attachment
      if( $attachment ) {
        $publishUrl = $attachment->getHref();
        $publishName = $attachment->getTitle();
        $publishDesc = $attachment->getDescription();
        if( empty($publishName) ) {
          $publishName = ucwords($attachment->getShortType());
        }
        if( ($tmpPicUrl = $attachment->getPhotoUrl() ) ) {
          $publishPicUrl = $tmpPicUrl;
        }
        // prevents OAuthException: (#100) FBCDN image is not allowed in stream
        if( $publishPicUrl &&
          preg_match('/fbcdn.net$/i', parse_url($publishPicUrl, PHP_URL_HOST)) ) {
          $publishPicUrl = null;
        }
      } else {
        $publishUrl = $action->getHref();
      }
      // Check to ensure proto/host
      if( $publishUrl &&
        false === stripos($publishUrl, 'http://') &&
        false === stripos($publishUrl, 'https://') ) {
        $publishUrl = 'http://' . $_SERVER['HTTP_HOST'] . $publishUrl;
      }
      if( $publishPicUrl &&
        false === stripos($publishPicUrl, 'http://') &&
        false === stripos($publishPicUrl, 'https://') ) {
        $publishPicUrl = 'http://' . $_SERVER['HTTP_HOST'] . $publishPicUrl;
      }
      // Add site title
      if( $publishName ) {
        $publishName = Engine_Api::_()->getApi('settings', 'core')->core_general_site_title
          . ": " . $publishName;
      } else {
        $publishName = Engine_Api::_()->getApi('settings', 'core')->core_general_site_title;
      }
      // Publish to facebook, if checked & enabled
      if( $this->_getParam('post_to_facebook', false) &&
        'publish' == Engine_Api::_()->getApi('settings', 'core')->core_facebook_enable ) {
        try {
          $facebookApi = Siteshare_Api_Facebook_Facebookinvite::getFBInstance();
          $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
          $fb_uid = $facebookTable->find($viewer->getIdentity())->current();
          if( $facebookApi && Siteshare_Api_Facebook_Facebookinvite::checkConnection(null, $facebookApi)
          ) {
            $fb_data = array(
              'message' => $publishMessage,
            );
            if( $publishUrl ) {
              $fb_data['link'] = $publishUrl;
            }
            if( $publishName ) {
              $fb_data['name'] = $publishName;
            }
            if( $publishDesc ) {
              $fb_data['description'] = strip_tags($publishDesc);
            }
            if( $publishPicUrl ) {
              $fb_data['picture'] = $publishPicUrl;
            }
            if( isset($fb_data['link']) && !empty($fb_data['link']) ) {
              $appkey = Engine_Api::_()->getApi('settings', 'core')->getSetting('bitly.apikey');
              $appsecret = Engine_Api::_()->getApi('settings', 'core')->getSetting('bitly.secretkey');
              if( !empty($appkey) && !empty($appsecret) ) {
                $shortURL = Engine_Api::_()->getApi('Bitly', 'siteshare')->get_bitly_short_url($fb_data['link'], $appkey, $appsecret, $format = 'txt');
                $fb_data['link'] = $shortURL;
              }
            }
            $res = $facebookApi->api('/me/feed', 'POST', $fb_data);
            if( $subject && isset($subject->fbpage_id) && !empty($subject->fbpage_id) ) {
              $manages_pages = $facebookApi->api('/me/accounts', 'GET');
              //NOW GETTING THE PAGE ACCESS TOKEN TO WITH THIS SITE PAGE IS INTEGRATED:
              foreach( $manages_pages['data'] as $page ) {
                if( $page['id'] == $subject->fbpage_id ) {
                  $fb_data['access_token'] = $page['access_token'];
                  $res = $facebookApi->api('/' . $subject->fbpage_id . '/feed', 'POST', $fb_data);
                  break;
                }
              }
            }
          }
        } catch( Exception $e ) {
          // Silence
        }
      } // end Facebook
      // Publish to twitter, if checked & enabled
      if( $this->_getParam('post_to_twitter', false) &&
        'publish' == Engine_Api::_()->getApi('settings', 'core')->core_twitter_enable ) {
        try {
          $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
          if( $twitterTable->isConnected() ) {
            $twitterOauth = $twitter = $Api_twitter->getApi();
            $login = Engine_Api::_()->getApi('settings', 'core')->getSetting('bitly.apikey');
            $appkey = Engine_Api::_()->getApi('settings', 'core')->getSetting('bitly.secretkey');
            //TWITTER ONLY ACCEPT 140 CHARACTERS MAX..
            //IF BITLY IS CONFIGURED ON THE SITE..
            if( !empty($login) && !empty($appkey) ) {
              if( strlen(html_entity_decode($_POST['body'])) > 140 || $attachment ) {
                if( $attachment ) {
                  $shortURL = Engine_Api::_()->getApi('Bitly', 'siteshare')->get_bitly_short_url((_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $attachment->getHref(), $login, $appkey, $format = 'txt');
                  $BitlayLength = strlen($shortURL);
                } else {
                  $BitlayLength = 0;
                  $shortURL = '';
                }
                $twitterFeed = substr(html_entity_decode($_POST['body']), 0, (140 - ($BitlayLength + 1))) . ' ' . $shortURL;
              } else
                $twitterFeed = html_entity_decode($_POST['body']);
            }
            else {
              $twitterFeed = substr(html_entity_decode($_POST['body']), 0, 137) . '...';
            }

            $lastfeedobject = $twitterOauth->post(
              'statuses/update', array('status' => $twitterFeed)
            );
            //$twitter->statuses->update($message);
          }
        } catch( Exception $e ) {
          // Silence
        }
      }
      // Publish to janrain
      if( //$this->_getParam('post_to_janrain', false) &&
        'publish' == Engine_Api::_()->getApi('settings', 'core')->core_janrain_enable ) {
        try {
          $session = new Zend_Session_Namespace('JanrainActivity');
          $session->unsetAll();
          $session->message = $publishMessage;
          $session->url = $publishUrl ? $publishUrl : 'http://' . $_SERVER['HTTP_HOST'] . _ENGINE_R_BASE;
          $session->name = $publishName;
          $session->desc = $publishDesc;
          $session->picture = $publishPicUrl;
        } catch( Exception $e ) {
          // Silence
        }
      }
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e; // This should be caught by error handler
    }
    if(!empty($memories)){
            setcookie("shared_this", 1, time()+86400,"/");
    }
    // If we're here, we're done
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Success!');
    // Redirect if in normal context
    //CHECK IF SITEMOBILE PLUGIN IS ENABLED AND SITE IS IN MOBILE MODE:
    if( Engine_Api::_()->siteshare()->checkSitemobileMode() ) {
      if( null === $this->_helper->contextSwitch->getCurrentContext() ) {
        $return_url = $form->getValue('return_url', false);
        if( !$return_url ) {
          $return_url = $this->view->url(array(), 'default', true);
        }
        return $this->_helper->redirector->gotoUrl($return_url, array('prependBase' => false));
      } else if( 'smoothbox' == $this->_helper->contextSwitch->getCurrentContext() ) {
        $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 1000,
          'parentRefresh' => $this->_getParam('not_parent_refresh', 0) ? false : 10,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('Shared successfully.'))
        ));
      }
    } else {
      return $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 1000,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('Shared successfully.'))
      ));
    }
  }

  public function sendEmailAction()
  {
    $this->_helper->layout->setLayout('default-simple');
    //GET VIEWER
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewr_id = $viewer->getIdentity();
    $this->view->contentMedia = 'page';
    $this->view->contentUrl = $this->_getParam('link', null);
    $subject;
    if( !empty(Engine_Api::_()->core()->hasSubject()) ) {
      $subject = Engine_Api::_()->core()->getSubject();
      $this->view->contentMedia = $this->getMediaType($subject);
      $this->view->contentUrl = $subject->getHref();
    }
    //GET FORM
    $this->view->form = $form = new Siteshare_Form_TellAFriend();
    if( !empty($viewr_id) ) {
      $value['sender_email'] = $viewer->email;
      $value['sender_name'] = $viewer->displayname;
      $form->populate($value);
    }
    //FORM VALIDATION
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) ) {

      //GET FORM VALUES
      $values = $form->getValues();

      //EXPLODE EMAIL IDS
      $reciver_ids = explode(',', $values['reciver_emails']);
      $sender_email = $values['sender_email'];

      //CHECK VALID EMAIL ID FORMAT
      $validator = new Zend_Validate_EmailAddress();
      $validator->getHostnameValidator()->setValidateTld(false);

      if( !$validator->isValid($sender_email) ) {
        $form->addError(Zend_Registry::get('Zend_Translate')->_('Invalid sender email address value'));
        return;
      }

      foreach( $reciver_ids as $reciver_id ) {
        $reciver_id = trim($reciver_id, ' ');
        if( !$validator->isValid($reciver_id) ) {
          $form->addError(Zend_Registry::get('Zend_Translate')->_('Please enter correct email address of the receiver(s).'));
          return;
        }
      }
      if( !empty(Engine_Api::_()->core()->hasSubject()) ) {
      $subject = Engine_Api::_()->core()->getSubject();
      $this->view->contentMedia = $this->getMediaType($subject);
      $this->view->contentUrl = $this->view->serverUrl($subject->getHref());
    }
      $sender = $values['sender_name'];
      $message = $values['message'];
      $attachmentPhoto = $subject ? $subject->getPhotoUrl('thumb.icon') : '';
      if( $attachmentPhoto ) {
        $attachmentPhoto = $this->view->absoluteUrl($attachmentPhoto);
      }
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($reciver_ids, 'SITESHARE_ITEM_EMAIL', array(
        'host' => $this->view->getHelper('serverUrl')->getHost(),
        'email' => $sender,
        'date' => time(),
        'sender_name' => !empty($viewer) ? $viewer->getTitle() : '',
        'message' => $message,
        'item_media_type' => $this->view->contentMedia ,
        'object_title' => $subject ? $subject->getTitle() : $this->view->contentUrl,
        'object_photo' => $attachmentPhoto,
        'object_link' => $this->view->contentUrl,
        'object_description' => ($subject && $subject->getType() != 'activity_action') ? $subject->getDescription() : null,
        'queue' => true,
      ));
      $this->view->sucess = true;
    }
  }

  protected function sendEmail($emails, $viewer, $attachment, $message)
  {
    $lableMediaType = $this->getMediaType($attachment);
    $attachmentPhoto = $attachment->getPhotoUrl('thumb.icon');
    if( $attachmentPhoto ) {
      $attachmentPhoto = $this->view->absoluteUrl($attachmentPhoto);
    }

    Engine_Api::_()->getApi('mail', 'core')->sendSystem($emails, 'SITESHARE_ITEM_EMAIL', array(
      'host' => $this->view->getHelper('serverUrl')->getHost(),
      'email' => $emails,
      'date' => time(),
      'sender_name' => !empty($viewer) ? $viewer->getTitle() : '',
      'message' => $message,
      'item_media_type' => $lableMediaType,
      'object_title' => $attachment->getTitle(),
      'object_photo' => $attachmentPhoto,
      'object_link' => $this->view->serverUrl($attachment->getHref()),
      'object_description' => ($attachment->getType() != 'activity_action') ? $attachment->getDescription() : null,
      'queue' => false,
    ));
  }

  protected function sendMessage($id, $body, $viewer, $attachment, $lable)
  {

    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
    $db->beginTransaction();
    $recipients = Engine_Api::_()->getItem('user', $id);
    // Create conversation
    $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
      $viewer, $recipients, "has Shared " . $lable, $body, $attachment
    );

    // Send notifications
    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
      $recipients, $viewer, $conversation, 'message_new'
    );
    $db->commit();
    // Increment messages counter
    Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');
  }

  protected function getIdsToSendNotification($data, $action, $attachment, $viewer, $itemObject)
  {
    $memberIdsToNotify = array();
    $shareType = Engine_Api::_()->getDbTable('sharetypes', 'siteshare');
    $itemRow = $shareType->getItemTypeRow($data['type']);
    if( $itemRow->notification_allow == 'none' ) {
      return $memberIdsToNotify;
    }
    $itemParams = $itemRow->params;
    if( $action->subject_type == 'user' && $attachment->getOwner()->getIdentity() != $viewer->getIdentity() ) {
      $memberIdsToNotify[] = $attachment->getOwner()->getIdentity();
    }
    if( !$itemObject ) {
      return $memberIdsToNotify;
    }
    $memberIdsToNotify[] = $itemObject->getOwner()->getIdentity();

    if( !empty($itemParams['admin']) && $itemRow->notification_allow === 'admin' ) {
      $manageAdmins = array(
        'sitepage_page' => 'page_id',
        'sitegroup_group' => 'group_id',
        'sitestore_store' => 'store_id',
        'sitebusiness_business' => 'business_id',
      );
      if( in_array($itemRow->type, array_keys($manageAdmins)) ) {
        $manageadminTable = Engine_Api::_()->getDbtable('manageadmins', $itemRow->module_name);
        $select = $manageadminTable->select()
          ->from($manageadminTable->info('name'), 'user_id')
          ->where($manageAdmins[$itemRow->type] . ' = ?', $itemObject->getIdentity());
        $user_ids = $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
        $memberIdsToNotify = array_merge($memberIdsToNotify, $user_ids);
      } else {
        $adminList = Engine_Api::_()->getItemTable($itemRow->module_name . "_list");
        $listId = $adminList->select()->from($adminList->info('name'), array('list_id'))
          ->where('owner_id =?', $data['item_id'])
          ->limit(1)
          ->query()
          ->fetchColumn();

        if( !empty($listId) ) {
          $listItem = Engine_Api::_()->getItemTable($itemRow['module_name'] . "_list_item");
          $admins = $listItem->fetchAll($listItem->select()->from($listItem->info('name'), array('child_id'))
                ->where('list_id =?', $listId))->toArray();
          $memberIdsToNotify = array_merge($memberIdsToNotify, array_column($admins, 'child_id'));
        }
      }
    }
    if( !empty($itemParams['membership']) && $itemRow->notification_allow === 'member' ) {
      $members = Engine_Api::_()->getDbtable('membership', $itemRow['module_name'])->getMembers($itemObject);
      if( $members instanceof Zend_Paginator ) {
        foreach( $members as $member )
          $memberIdsToNotify = array_merge($memberIdsToNotify, array($member->user_id));
      } else
        $memberIdsToNotify = array_merge($memberIdsToNotify, array_column($members->toArray(), 'user_id'));
    }

    return array_unique($memberIdsToNotify);
  }

  public function socailShareAction()
  {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $coreSettings = Engine_Api::_()->getApi('settings', 'core');
    if( empty($viewer->getIdentity()) && !$coreSettings->getSetting('siteshare.share.public.enabled', 1) ) {
      return;
    }
    if( !empty(Engine_Api::_()->core()->hasSubject()) ) {
      $subject = Engine_Api::_()->core()->getSubject();
      $this->view->contentMedia = $this->getMediaType($subject);
    }

    if( $coreSettings->getSetting('siteshare.share.bookmarks.enabled', 1) ) {
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
        ->getNavigation('siteshare_social_link');
    }
  }

  public function socialServiceClickAction()
  {
    $socialShareHistories = Engine_Api::_()->getDbTable('socialShareHistories', 'siteshare');
    $socialShareHistories->insert(array(
      'share_url' => $this->_getParam('shareUrl'),
      'service_type' => $this->_getParam('serviceType'),
      'creation_date' => date('Y-m-d H:i:s')
    ));
  }

  public function getMediaType($item)
  {

    $lable = $item->getMediaType();

    if( $lable === 'item' ) {
      $lable = $item->getShortType();
    }
    return $lable;
  }

  public function suggestItemAction()
  {
    $itemType = $paramType = $this->_getParam('type', null);
    $value = $this->_getParam('text', '');
    $viewer = Engine_Api::_()->user()->getViewer();
    if( empty($itemType) || ($itemType == 'email') ) {
      return $this->_helper->json(0);
    }
    $title = 'title';
    $shareType = Engine_Api::_()->getDbtable('sharetypes', 'siteshare')->getItemTypeRow($itemType);
    if( !$shareType ) {
      return $this->_helper->json(0);
    }
    $itemType = stripos($itemType, '_listingtype_') !== false ? 'sitereview_listing' : $itemType;
    $params = $shareType->params;
    if( in_array($itemType, array('user', 'message')) ) {
      $itemType = 'user';
      $title = 'displayname';
    }

    $itemTable = Engine_Api::_()->getItemTable($itemType);
    $select = $itemTable->select();
    if( !empty($value) ) {
      $select->where("$title like ?", '%' . $value . '%');
    }
    if( $itemType == 'sitereview_listing' ) {
      $select->where("listingtype_id = ? ", str_replace('sitereview_listingtype_', '', $paramType));
    }
    $shareAllow = $shareType->share_allow;
    if( $itemType == 'user' ) {
      $usersAllowed = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('messages', $viewer->level_id, 'auth');
      if( $paramType === 'message' && $usersAllowed == 'everyone' ) {
        $select->where('user_id <> ?', $viewer->user_id);
      } else {
        $select = Engine_Api::_()->user()->getViewer()->membership()->getMembersObjectSelect();
        !empty($value) ? $select->where("$title like ?", '%' . $value . '%') : '';
      }
    } elseif( $shareAllow !== 'all' ) {
      $itemIds = array();
      $primaryInfo = $itemTable->info('primary');
      if( !empty($params['membership']) && $shareAllow === 'member' ) {
        $itemIds = Engine_Api::_()->getDbtable('membership', $shareType->module_name)->getMembershipsOfIds($viewer);
      } else if( !empty($params['admin']) && $shareAllow === 'admin' ) {
        $itemIds = $this->getAdminsContentIds($shareType);
      }
      if( !empty($itemIds) ) {
        $select->where("{$params['owner']} = " . $viewer->getIdentity() . " OR {$primaryInfo[1]} IN (?)", (array) $itemIds);
      } else {
        $select->where("{$params['owner']} = ?", $viewer->getIdentity());
      }
    } else if( !in_array('search', $itemTable->info('cols')) ) {
      $select->where("search = ?", 1);
    }

    $select->order($title);
    $select->limit(15);
    $contents = $itemTable->fetchAll($select);
    $data = array();
    foreach( $contents as $content ) {
      $data[] = array(
        'id' => $content->getIdentity(),
        'label' => $content->getTitle(),
        'photo' => $this->view->itemPhoto($content, 'thumb.icon'),
        'url' => $content->getPhotoUrl()
      );
    }

    return $this->_helper->json($data);
  }

  private function getAdminsContentIds($shareType)
  {
    $module = $shareType->module_name;
    $type = $shareType->type;
    $viewer = Engine_Api::_()->user()->getViewer();
    $manageAdmins = array(
      'sitepage_page' => 'getManageAdminPages',
      'sitegroup_group' => 'getManageAdminGroups',
      'sitestore_store' => 'getManageAdminStores',
      'sitebusiness_business' => 'getManageAdminBusinesses',
    );
    if( in_array($type, array_keys($manageAdmins)) ) {
      $table = Engine_Api::_()->getDbtable('manageadmins', $module);
      $result = call_user_func_array(array(
        $table, $manageAdmins[$type]), array($viewer->getIdentity()));
      $ids = array();
      foreach( $result as $row ) {
        $rowArray = $row->toArray();
        $ids[] = array_shift($rowArray);
      }
      return $ids;
    }
    if( !in_array($type, array('group', 'siteevent_event', 'forum')) ) {
      return array();
    }

    $listTable = Engine_Api::_()->getDbtable('lists', $module);
    $listTableName = $listTable->info('name');
    $list = $listTable->fetchNew();
    $listItemTableName = $list->getListItemTable()->info('name');
    $select = $list->select()
      ->from($listTableName)
      ->join($listItemTableName, "`{$listTableName}`.`list_id` = `{$listItemTableName}`.`list_id`", null)
      ->where('child_id = ?', $viewer->getIdentity());
    $result = $listTable->fetchAll($select);
    $ids = array();
    foreach( $result as $row ) {
      $ids[] = $row->owner_id;
    }
    return $ids;
  }

}
