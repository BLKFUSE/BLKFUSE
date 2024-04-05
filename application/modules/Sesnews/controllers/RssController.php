<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: RssController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_rssController extends Core_Controller_Action_Standard {

    public function init() {
        // only show to member_level if authorized
        if( !$this->_helper->requireAuth()->setAuthParams('sesnews_rss', null, 'view')->isValid() ) return;

        $rss_id = $this->_getParam('rss_id', $this->_getParam('rss_id', null));

        //$news_id = Engine_Api::_()->getDbtable('news', 'sesnews')->getNewsId($id);
        if ($rss_id) {
            $rss = Engine_Api::_()->getItem('sesnews_rss', $rss_id);
            if ($rss) {
                Engine_Api::_()->core()->setSubject($rss);
            }
        }

        if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rss.enable', 1))
            return $this->_forward('notfound', 'error', 'core');

        if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '')))
            return $this->_forward('notfound', 'error', 'core');
    }

  public function viewAction() {

    $this->_helper->content->setEnabled();
  }

  public function subscriptionAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }

    if ($this->_getParam('type') == 'sesnews_rss') {
      $type = 'sesnews_rss';
      $dbTable = 'rss';
      $resorces_id = 'rss_id';
      $notificationType = 'sesnews_subscribedrss';
    }

    $type = $this->_getParam('type');
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }

    $viewer = Engine_Api::_()->user()->getViewer();

    $tableSubs = Engine_Api::_()->getDbtable('rsssubscriptions', 'sesnews');
    $tableMainLike = $tableSubs->info('name');

    $itemTable = Engine_Api::_()->getDbtable($dbTable, 'sesnews');

    $select = $tableSubs->select()->from($tableMainLike)->where('rss_id =?', $item_id)->where('subscriber_user_id =?', Engine_Api::_()->user()->getViewer()->getIdentity());
    $Like = $tableSubs->fetchRow($select);
    if (!empty($Like)) {
      //delete
      $db = $Like->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $Like->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $itemTable->update(array('subscriber_count' => new Zend_Db_Expr('subscriber_count - 1')), array($resorces_id . ' = ?' => $item_id));

      $item = Engine_Api::_()->getItem($type, $item_id);
      Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));

      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->subscriber_count));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('rsssubscriptions', 'sesnews')->getAdapter();
      $db->beginTransaction();
      try {
        $subscription = $tableSubs->createRow();
        $subscription->subscriber_user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $subscription->rss_id = $item_id;
        $subscription->save();
        $itemTable->update(array('subscriber_count' => new Zend_Db_Expr('subscriber_count + 1')), array($resorces_id . '= ?' => $item_id));

        //send notification and activity feed work.
        $item = Engine_Api::_()->getItem($type, $item_id);
        $subject = $item;
        $owner = $subject->getOwner();
        if ($subject->owner_id != $viewer->getIdentity()) {
            //Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
            Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesnews_subscribedrss');
        }
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->subscriber_count, 'subs_id' => $subscription->rsssubscription_id));
      die;
    }
  }


    public function importnewsAction() {

        $rss_id = $this->_getParam('rss_id', null);
        $rss = Engine_Api::_()->getItem('sesnews_rss', $rss_id);

        //$importNews = Zend_Feed::import($rss->rss_link);

        $rss2jsonApiKey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', '');
        $rss_url = $rss->rss_link;
        $url = 'https://api.rss2json.com/v1/api.json?rss_url='.urlencode($rss_url).'&api_key='.$rss2jsonApiKey;
        $importNews = json_decode(file_get_contents($url), true);

        $table = Engine_Api::_()->getDbtable('news', 'sesnews');
        $roleTable = Engine_Api::_()->getDbtable('roles', 'sesnews');

        // Auth
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

        $viewMax = array_search($rss->view_privacy, $roles);
        $commentMax = array_search($rss->comment_privacy, $roles);

        //rss owner is viewer
        $owner = Engine_Api::_()->getItem('user', $rss->owner_id);

        $news_count = 0;
        $allow_count = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.maxfetchnews', 10);

        // Get notification table
        $notificationTable = Engine_Api::_()->getDbtable('notifications', 'activity');

        $getAllSubscribers = Engine_Api::_()->getDbTable('rsssubscriptions', 'sesnews')->getAllSubscribers($rss_id);

        foreach($importNews['items'] as $importurls) {

            if($allow_count >= $news_count) {

                //$news_link = 'https://mercury.postlight.com/parser?url='.$importurls->link();
                $newsResult = $importurls; //(array) Engine_Api::_()->sesnews()->getApiResults($news_link);

                $news_title = $newsResult['title'];
                if(empty($news_title))
                    continue;

                $isNewsUrlExist = Engine_Api::_()->getDbTable('news', 'sesnews')->isNewsUrlExist($newsResult['link']);
                if(!empty($isNewsUrlExist))
                    continue;

								if(isset($newsResult['thumbnail']) && !empty($newsResult['thumbnail'])) {
									$image_url = $newsResult['thumbnail'];
								} elseif(isset($newsResult['enclosure']['thumbnail']) && !empty($newsResult['enclosure']['thumbnail'])) {
										$image_url = $newsResult['enclosure']['thumbnail'];
								} elseif(isset($newsResult['enclosure']['link']) && !empty($newsResult['enclosure']['link'])) {
										$image_url = $newsResult['enclosure']['link'];
								} else {
									$image_url = Engine_Api::_()->sesnews()->getImageURL($newsResult['link']);
								}

                $PhotoExtension = pathinfo($image_url, PATHINFO_EXTENSION);
								
								if(strpos(strtolower($image_url), '.jpg') === false && strpos(strtolower($image_url), '.png') === false && strpos(strtolower($image_url), '.gif') === false && strpos(strtolower($image_url), '.jpeg') === false && strpos(strtolower($image_url), '.webp') === false) {
									continue;
								}
//                 if(!exif_imagetype($image_url)) {
// 									continue;
//                 }
                //if(!in_array($PhotoExtension, array('jpg','png','gif','jpeg'))) continue;				

                $news_params['title'] = $newsResult['title'];
                $news_params['body'] = $newsResult['content'] ? $newsResult['content'] : $newsResult['description'];
                $news_params['rss_id'] = $rss_id;
                //$news_params['custom_url'] = $this->getSlug($news_title);
                $news_params['owner_type'] = 'user';
                $news_params['owner_id'] = $rss->owner_id;
                $news_params['category_id'] = $rss->category_id;
                $news_params['subcat_id'] = $rss->subcat_id;
                $news_params['subsubcat_id'] = $rss->subsubcat_id;
                $news_params['news_link'] = $newsResult['link'];
                $news_params['image_url'] = $image_url;



//                 $isNewsUrlExist = Engine_Api::_()->getDbTable('news', 'sesnews')->isNewsUrlExist($newsResult['url']);
//                 if(!empty($isNewsUrlExist))
//                     continue;
//
//                 $image_url = $newsResult['lead_image_url'];
//
//                 $news_params['title'] = $newsResult['title'];
//                 $news_params['body'] = $newsResult['content'];
//                 $news_params['rss_id'] = $rss_id;
//                 //$news_params['custom_url'] = $this->getSlug($news_title);
//                 $news_params['owner_type'] = 'user';
//                 $news_params['owner_id'] = $rss->owner_id;
//                 $news_params['category_id'] = $rss->category_id;
//                 $news_params['subcat_id'] = $rss->subcat_id;
//                 $news_params['subsubcat_id'] = $rss->subsubcat_id;
//                 $news_params['news_link'] = $newsResult['url'];

                //Create News
                $news = $table->createRow();
                $news->setFromArray($news_params);
                $news->save();
                //SET custom url
                $news->custom_url = $news->getSlug();
                $news->save();

                $newsroles = $roleTable->createRow();
                $newsroles->news_id = $news->news_id;
                $newsroles->user_id = $rss->owner_id;
                $newsroles->save();

                //Privacy of new news
                foreach( $roles as $i => $role ) {
                    $auth->setAllowed($news, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($news, $role, 'comment', ($i <= $commentMax));
                }

                //Photo import work
                if(!empty($image_url)) {
                    $photo_id = Engine_Api::_()->sesbasic()->setPhoto(current(explode('?',$image_url)), true,false,'sesnews','sesnews_news','',$news,true);
                    if(empty($photo_id)) {
											$news->delete();
											continue;
                    }
                    $news->photo_id = $photo_id;
                    $news->save();
                }

                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($owner, $news, 'sesnews_new');
                // make sure action exists before attaching the sesnews to the activity
                if( $action ) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $news);
                }

                // Send notifications to all subscribers
                foreach($getAllSubscribers as $user ) {
                    $user = Engine_Api::_()->getItem('user', $user);
                    $notificationTable->addNotification($user, $owner, $news, 'sesnews_subscribed_new');
                }

                $news_count++;
                $rss->news_count++;
                $rss->save();
            }
        }
        return $this->_forward('success' ,'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sesnews_generalrss', true),
            'messages' => Array("News imported successfully.")
        ));
    }

    //Check RSS URL iis valid or not
    public function checkurlAction() {
        $urlsubmit = $this->_getParam('urlsubmit', null);
        $getColumnValue = Engine_Api::_()->getDbTable('urls', 'sesnews')->getColumnValue(array('name' => $urlsubmit));
        if(empty($getColumnValue)) {
            $feedReader = Zend_Feed_Reader::import($urlsubmit);
            $title = $feedReader->getTitle();
            $desc = $feedReader->getDescription();
            try{
                echo Zend_Json::encode(array('status' => 'true', 'error' => '', 'title' => $title, 'description' => $desc, 'message' => "This is valid Url."));
                exit();
            } catch (Exception $e) {
                echo Zend_Json::encode(array('status' => 'false', 'error' => '', 'message' => "This is invalid Url."));
                exit();
            }
        } else {
            echo Zend_Json::encode(array('status' => 'false', 'error' => '', 'message' => "This is invalid Url."));
            exit();
        }
    }

  public function manageAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;

    // Render
    $this->_helper->content->setEnabled();
  }


  public function createAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('sesnews_rss', null, 'create')->isValid()) return;

    // Render
    $this->_helper->content->setEnabled();

    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['user_id'] = $viewer->getIdentity();

    $paginator = Engine_Api::_()->getItemTable('sesnews_rss')->getRssPaginator($values);

    $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sesnews_rss', 'max');

    $this->view->current_count = $paginator->getTotalItemCount();

    if (isset($sesnews->category_id) && $sesnews->category_id != 0) {
      $this->view->category_id = $sesnews->category_id;
    } else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
      $this->view->category_id = $_POST['category_id'];
    else
      $this->view->category_id = 0;
    if (isset($sesnews->subsubcat_id) && $sesnews->subsubcat_id != 0) {
      $this->view->subsubcat_id = $sesnews->subsubcat_id;
    } else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
      $this->view->subsubcat_id = $_POST['subsubcat_id'];
    else
      $this->view->subsubcat_id = 0;
    if (isset($sesnews->subcat_id) && $sesnews->subcat_id != 0) {
      $this->view->subcat_id = $sesnews->subcat_id;
    } else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
      $this->view->subcat_id = $_POST['subcat_id'];
    else
      $this->view->subcat_id = 0;

    // Prepare form
    $this->view->form = $form = new Sesnews_Form_CreateRss();

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }


    // Process
    $table = Engine_Api::_()->getItemTable('sesnews_rss');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
      // Create rss
      $viewer = Engine_Api::_()->user()->getViewer();
      $formValues = $form->getValues();


      if( empty($values['auth_view']) ) {
        $formValues['auth_view'] = 'everyone';
      }

      if( empty($values['auth_comment']) ) {
        $formValues['auth_comment'] = 'everyone';
      }

      $values = array_merge($formValues, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity(),
        'view_privacy' => $formValues['auth_view'],
        'comment_privacy' => $formValues['auth_comment'],
      ));

      $rss = $table->createRow();
      $rss->setFromArray($values);
      $rss->save();

      if( !empty($values['photo']) ) {
        $rss->setPhoto($form->photo);
      }

      $rss->is_approved = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesnews_rss', $viewer, 'rss_approve');
      $rss->save();

    if(isset($_POST['start_date']) && $_POST['start_date'] != ''){
        $starttime = isset($_POST['start_date']) ? date('Y-m-d H:i:s',strtotime($_POST['start_date'].' '.$_POST['start_time'])) : '';
        $rss->publish_date =$starttime;
    }

    if(isset($_POST['start_date']) && $viewer->timezone && $_POST['start_date'] != ''){
        //Convert Time Zone
        $oldTz = date_default_timezone_get();
        date_default_timezone_set($viewer->timezone);
        $start = strtotime($_POST['start_date'].' '.$_POST['start_time']);
        date_default_timezone_set($oldTz);
        $rss->publish_date = date('Y-m-d H:i:s', $start);
    }

      // Auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);

      foreach( $roles as $i => $role ) {
        $auth->setAllowed($rss, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($rss, $role, 'comment', ($i <= $commentMax));
      }

      // Add activity only if blog is published
//       if( $values['draft'] == 0 ) {
//         $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $rss, 'sesnews_rss_new');
//
//         // make sure action exists before attaching the blog to the activity
//         if( $action ) {
//           Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $rss);
//         }
//       }
//
//       // Send notifications for subscribers
//       Engine_Api::_()->getDbtable('subscriptions', 'blog')
//           ->sendNotifications($rss);

      // Commit
      $db->commit();
    } catch( Exception $e ) {
      return $this->exceptionWrapper($e, $form, $db);
    }

    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }


  public function editAction() {

    if( !$this->_helper->requireUser()->isValid() ) return;
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->news = $rss = Engine_Api::_()->core()->getSubject();
    if (isset($rss->category_id) && $rss->category_id != 0)
    $this->view->category_id = $rss->category_id;
    else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
    $this->view->category_id = $_POST['category_id'];
    else
    $this->view->category_id = 0;
    if (isset($rss->subsubcat_id) && $rss->subsubcat_id != 0)
    $this->view->subsubcat_id = $rss->subsubcat_id;
    else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
    $this->view->subsubcat_id = $_POST['subsubcat_id'];
    else
    $this->view->subsubcat_id = 0;
    if (isset($rss->subcat_id) && $rss->subcat_id != 0)
    $this->view->subcat_id = $rss->subcat_id;
    else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
    $this->view->subcat_id = $_POST['subcat_id'];
    else
    $this->view->subcat_id = 0;

    $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject('sesnews_rss') )
        Engine_Api::_()->core()->setSubject($rss);

    if( !$this->_helper->requireSubject()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('sesnews_rss', $viewer, 'edit')->isValid() ) return;

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sesnews_main');

    $this->view->categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc();

    // Prepare form
    $this->view->form = $form = new Sesnews_Form_EditRss();

    // Populate form
    $form->populate($rss->toArray());


    if($form->getElement('category_id'))
        $form->getElement('category_id')->setValue($rss->category_id);

    $auth = Engine_Api::_()->authorization()->context;
    $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

    foreach( $roles as $role ) {
      if ($form->auth_view){
        if( $auth->isAllowed($rss, $role, 'view') ) {
         $form->auth_view->setValue($role);
        }
      }

      if ($form->auth_comment){
        if( $auth->isAllowed($rss, $role, 'comment') ) {
          $form->auth_comment->setValue($role);
        }
      }
    }

    // hide status change if it has been already published
    if( $rss->draft == "0" )
        $form->removeElement('draft');


    // Check post/form
    if( !$this->getRequest()->isPost() ) return;
    if( !$form->isValid($this->getRequest()->getPost()) ) return;

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try
    {
        $values = $form->getValues();
        unset($values['rss_link']);
        $rss->setFromArray($values);
        $rss->modified_date = date('Y-m-d H:i:s');
        if(isset($_POST['start_date']) && $_POST['start_date'] != ''){
            $starttime = isset($_POST['start_date']) ? date('Y-m-d H:i:s',strtotime($_POST['start_date'].' '.$_POST['start_time'])) : '';
            $rss->publish_date =$starttime;
        }

        $rss->save();

        if(isset($values['draft']) && !$values['draft']) {
            $currentDate = date('Y-m-d H:i:s');
            if($rss->publish_date < $currentDate) {
                $rss->publish_date = $currentDate;
                $rss->save();
            }
        }

      // Auth
      if( empty($values['auth_view']) ) {
        $values['auth_view'] = 'everyone';
      }

      if( empty($values['auth_comment']) ) {
        $values['auth_comment'] = 'everyone';
      }

      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);
      foreach( $roles as $i => $role ) {
        $auth->setAllowed($rss, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($rss, $role, 'comment', ($i <= $commentMax));
      }

      if( !empty($values['photo']) ) {
        $rss->setPhoto($form->photo);
      }

      // insert new activity if sesnews is just getting published
//       $action = Engine_Api::_()->getDbtable('actions', 'activity')->getActionsByObject($rss);
//       if( engine_count($action->toArray()) <= 0 && $values['draft'] == '0' && (!$rss->publish_date || strtotime($rss->publish_date) <= time())) {
//         $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $rss, 'sesnews_new');
//           // make sure action exists before attaching the sesnews to the activity
//         if( $action != null ) {
//           Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $rss);
//         }
//         $rss->is_publish = 1;
//       	$rss->save();
//       }
      $db->commit();
    }
    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'));
  }


  public function browseAction() {
    // Render
    $this->_helper->content->setEnabled();
  }

  public function deleteAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $rss = Engine_Api::_()->getItem('sesnews_rss', $this->getRequest()->getParam('rss_id'));
    if( !$this->_helper->requireAuth()->setAuthParams($rss, null, 'delete')->isValid()) return;

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');

    $this->view->form = $form = new Sesnews_Form_DeleteRss();

    if( !$rss ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Rss entry doesn't exist or not authorized to delete");
      return;
    }

    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $db = $rss->getTable()->getAdapter();
    $db->beginTransaction();

    try {
        $allNews = Engine_Api::_()->getDbTable('news', 'sesnews')->getAllNews($rss->rss_id);
        foreach($allNews as $news) {
            Engine_Api::_()->sesnews()->deleteNews($news);
        }
        $rss->delete();
        $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your rss entry has been deleted.');
    return $this->_forward('success' ,'utility', 'core', array(
      'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sesnews_generalrss', true),
      'messages' => Array($this->view->message)
    ));
  }


}
