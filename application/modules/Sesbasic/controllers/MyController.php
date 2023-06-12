<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbasic_MyController extends Core_Controller_Action_Standard {
		//send message to site user function.
  public function messageAction() {
  
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('item_id');
    $type = Zend_Controller_Front::getInstance()->getRequest()->getParam('type');
    if (!$id || !$type)
      return;
    // Make form
    $this->view->form = $form = new Sesbasic_Form_Compose();
    // Get params
    $multi = $this->_getParam('multi');
    $to = $this->_getParam('to');
    $viewer = Engine_Api::_()->user()->getViewer();
    $toObject = null;

    // Build
    $isPopulated = false;
    if( !empty($to) && (empty($multi) || $multi == 'user') ) {
      $multi = null;
      // Prepopulate user
      $toUser = Engine_Api::_()->getItem('user', $to);
      $isMsgable = ( 'friends' != Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ||
          $viewer->membership()->isMember($toUser) );
      if( $toUser instanceof User_Model_User &&
          (!$viewer->isBlockedBy($toUser) && !$toUser->isBlockedBy($viewer)) &&
          isset($toUser->user_id) &&
          $isMsgable ) {
        $this->view->toObject = $toObject = $toUser;
        $form->toValues->setValue($toUser->getGuid());
        $isPopulated = true;
      } else {
        $multi = null;
        $to = null;
      }
    } else if( !empty($to) && !empty($multi) ) {
      // Prepopulate group/event/etc
      $item = Engine_Api::_()->getItem($multi, $to);
      // Potential point of failure if primary key column is something other
      // than $multi . '_id'
      $item_id = $multi . '_id';
      if( $item instanceof Core_Model_Item_Abstract &&
          isset($item->$item_id) && (
            $item->isOwner($viewer) ||
            $item->authorization()->isAllowed($viewer, 'edit')
          )) {
        $this->view->toObject = $toObject = $item;
        $form->toValues->setValue($item->getGuid());
        $isPopulated = true;
      } else {
        $multi = null;
        $to = null;
      }
    }
    
    //When share an item using send a message button
		$id = Zend_Controller_Front::getInstance()->getRequest()->getParam('item_id');
		$type = Zend_Controller_Front::getInstance()->getRequest()->getParam('type');
		if ($id) {
			$item = Engine_Api::_()->getItem($type, $id);
			$URL = ((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref(); 
			$form->body->setValue($URL);
		}
    
    $this->view->isPopulated = $isPopulated;

    // Build normal
    if( !$isPopulated ) {
      // Apparently this is using AJAX now?
//      $friends = $viewer->membership()->getMembers();
//      $data = array();
//      foreach( $friends as $friend ) {
//        $data[] = array(
//          'label' => $friend->getTitle(),
//          'id' => $friend->getIdentity(),
//          'photo' => $this->view->itemPhoto($friend, 'thumb.icon'),
//        );
//      }
//      $this->view->friends = Zend_Json::encode($data);
    }

    // Assign the composing stuff
    $composePartials = array();
    $prohibitedPartials = array('_composeTwitter.tpl', '_composeFacebook.tpl');
    foreach( Zend_Registry::get('Engine_Manifest') as $data ) {
      if( empty($data['composer']) ) {
        continue;
      }
      foreach( $data['composer'] as $type => $config ) {
        // is the current user has "create" privileges for the current plugin
        if ( isset($config['auth'], $config['auth'][0], $config['auth'][1]) ) {
          $isAllowed = Engine_Api::_()
            ->authorization()
            ->isAllowed($config['auth'][0], null, $config['auth'][1]);

          if ( !empty($config['auth']) && !$isAllowed ) {
            continue;
          }
        }
        if( !engine_in_array($config['script'][0], $prohibitedPartials) ) {
          $composePartials[] = $config['script'];
        }
      }
    }
    $this->view->composePartials = $composePartials;
    // $this->view->composePartials = $composePartials;

    // Get config
    $this->view->maxRecipients = $maxRecipients = 10;


    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }
		$itemFlood = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('user', $this->view->viewer()->level_id, 'messages_flood');

		if(!empty($itemFlood[0])){
				//get last activity
				$tableFlood = Engine_Api::_()->getDbTable("messages",'messages');
				$select = $tableFlood->select()->where("user_id = ?",$this->view->viewer()->getIdentity())->order("date DESC");
				if($itemFlood[1] == "minute"){
						$select->where("date >= DATE_SUB(NOW(),INTERVAL 1 MINUTE)");
				}else if($itemFlood[1] == "day"){
						$select->where("date >= DATE_SUB(NOW(),INTERVAL 1 DAY)");
				}else{
						$select->where("date >= DATE_SUB(NOW(),INTERVAL 1 HOUR)");
				}
				$floodItem = $tableFlood->fetchAll($select);
				if(engine_count($floodItem) && $itemFlood[0] <= engine_count($floodItem)){
						$message = Engine_Api::_()->core()->floodCheckMessage($itemFlood,$this->view);
						$form->addError($message);
						return;
				}
		}
    // Process
    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
    $db->beginTransaction();

    try {
      // Try attachment getting stuff
      $attachment = null;
      $attachmentData = $this->getRequest()->getParam('attachment');
      if( !empty($attachmentData) && !empty($attachmentData['type']) ) {
        $type = $attachmentData['type'];
        $config = null;
        foreach( Zend_Registry::get('Engine_Manifest') as $data )
        {
          if( !empty($data['composer'][$type]) )
          {
            $config = $data['composer'][$type];
          }
        }
        if( $config ) {
          $plugin = Engine_Api::_()->loadClass($config['plugin']);
          $method = 'onAttach'.ucfirst($type);
          if($type == 'photo') {
            $attachmentData['album_type'] = 'wall';
          }
          $attachment = $plugin->$method($attachmentData);
          $parent = $attachment->getParent();
          if($parent->getType() === 'user'){
            $attachment->search = 0;
            $attachment->save();
          }
          else {
            $parent->search = 0;
            $parent->save();
          }
        }
      }

      $viewer = Engine_Api::_()->user()->getViewer();
      $values = $form->getValues();

      // Prepopulated
      if( $toObject instanceof User_Model_User ) {
        $recipientsUsers = array($toObject);
        $recipients = $toObject;
        // Validate friends
        if( 'friends' == Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ) {
          if( !$viewer->membership()->isMember($recipients) ) {
            return $form->addError('One of the members specified is not in your friends list.');
          }
        }

      } else if( $toObject instanceof Core_Model_Item_Abstract &&
          method_exists($toObject, 'membership') ) {
        $recipientsUsers = $toObject->membership()->getMembers();
//        $recipients = array();
//        foreach( $recipientsUsers as $recipientsUser ) {
//          $recipients[] = $recipientsUser->getIdentity();
//        }
        $recipients = $toObject;
      }
      // Normal
      else {
        //$recipients = preg_split('/[,. ]+/', $values['toValues']);
        $recipients = explode(',', $values['to']);
        // clean the recipients for repeating ids
        // this can happen if recipient is selected and then a friend list is selected
        $recipients = array_unique($recipients);
        // Slice down to 10
        $recipients = array_slice($recipients, 0, $maxRecipients);
        
        // Get user objects
        $recipientsUsers = Engine_Api::_()->getItemMulti('user', $recipients);

        // Validate friends
        if( 'friends' == Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ) {
          foreach( $recipientsUsers as &$recipientUser ) {
            if( !$viewer->membership()->isMember($recipientUser) ) {
              return $form->addError('One of the members specified is not in your friends list.');
            }
          }
        }
      }

      // Create conversation
      $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
        $viewer,
        $recipients,
        $values['title'],
        $values['body'],
        $attachment
      );

      // Send notifications
      foreach( $recipientsUsers as $user ) {
        if( $user->getIdentity() == $viewer->getIdentity() ) {
          continue;
        }
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
          $user,
          $viewer,
          $conversation,
          'message_new'
        );
      }

      // Increment messages counter
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');

      // Commit
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    if ($this->getRequest()->getParam('format') == 'smoothbox') {
      return $this->_forward('success', 'utility', 'core', array(
                  'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your message has been sent successfully.')),
                  'smoothboxClose' => true,
      ));
    }
  
	}
}
