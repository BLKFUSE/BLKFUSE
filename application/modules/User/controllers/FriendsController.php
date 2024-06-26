<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: FriendsController.php 10259 2014-06-04 21:43:01Z lucas $
 * @author     Sami
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_FriendsController extends Core_Controller_Action_User
{
  public function init()
  {
    // Try to set subject
    $user_id = $this->_getParam('user_id', null);
    if( $user_id && !Engine_Api::_()->core()->hasSubject() )
    {
      $user = Engine_Api::_()->getItem('user', $user_id);
      if( $user )
      {
        Engine_Api::_()->core()->setSubject($user);
      }
    }

    // Check if friendships are enabled
    if( $this->getRequest()->getActionName() !== 'suggest' &&
        !Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible ) {
      $this->_helper->requireAuth()->forward();
    }
  }
  
  public function listAddAction()
  {
    $list_id = (int) $this->_getParam('list_id');
    $friend_id = (int) $this->_getParam('friend_id');
    
    $user = Engine_Api::_()->user()->getViewer();
    $friend = Engine_Api::_()->getItem('user', $friend_id);

    // Check params
    if( !$user->getIdentity() || !$friend || !$list_id )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Check list
    $listTable = Engine_Api::_()->getItemTable('user_list');
    $list = $listTable->find($list_id)->current();
    if( !$list || $list->owner_id != $user->getIdentity() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Missing list/not authorized');
    }

    // Check if already target status
    if( $list->has($friend) )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Already in list');
      return;
    }

    $list->add($friend);
    
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Member added to list.');
    Engine_Api::_()->core()->setSubject($user);
  }

  public function listRemoveAction()
  {
    $list_id = (int) $this->_getParam('list_id');
    $friend_id = (int) $this->_getParam('friend_id');
    
    $user = Engine_Api::_()->user()->getViewer();
    $friend = Engine_Api::_()->getItem('user', $friend_id);

    // Check params
    if( !$user->getIdentity() || !$friend || !$list_id )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Check list
    $listTable = Engine_Api::_()->getItemTable('user_list');
    $list = $listTable->find($list_id)->current();
    if( !$list || $list->owner_id != $user->getIdentity() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Missing list/not authorized');
    }

    // Check if already target status
    if( !$list->has($friend) )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Already not in list');
      return;
    }

    $list->remove($friend);

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Member removed from list.');
    Engine_Api::_()->core()->setSubject($user);
  }

  public function listCreateAction()
  {
    $title = (string) $this->_getParam('title');
    $friend_id = (int) $this->_getParam('friend_id');
    $user = Engine_Api::_()->user()->getViewer();
    $friend = Engine_Api::_()->getItem('user', $friend_id);

    if( !$user->getIdentity() || !$title )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    $listTable = Engine_Api::_()->getItemTable('user_list');
    $list = $listTable->createRow();
    $list->owner_id = $user->getIdentity();
    $list->title = $title;
    $list->save();

    if( $friend && $friend->getIdentity() )
    {
      $list->add($friend);
    }

    $this->view->status = true;
    $this->view->message = 'List created.';
    $this->view->list_id = $list->list_id;
    Engine_Api::_()->core()->setSubject($user);
  }

  public function listDeleteAction()
  {
    $list_id = (int) $this->_getParam('list_id');
    $user = Engine_Api::_()->user()->getViewer();

    // Check params
    if( !$user->getIdentity() || !$list_id )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Check list
    $listTable = Engine_Api::_()->getItemTable('user_list');
    $list = $listTable->find($list_id)->current();
    if( !$list || $list->owner_id != $user->getIdentity() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Missing list/not authorized');
    }

    $list->delete();

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('List deleted');
  }
  
  public function addAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // check that user is not trying to befriend 'self'
    if( $viewer->isSelf($user) ) {
      return $this->_forwardSuccess('You cannot befriend yourself.');
    }

    // check that user is already friends with the member
    if( $user->membership()->isMember($viewer) ) {
      return $this->_forwardSuccess('You are already friends with this member.');
    }

    // check that user has not blocked the member
    if( $viewer->isBlocked($user) ) {
      return $this->_forwardSuccess('Friendship request was not sent because you blocked this member.');
    }
    
    // Make form
    $this->view->form = $form = new User_Form_Friends_Add(array('user' => $user));

    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      
      // send request
      $user->membership()
        ->addMember($viewer)
        ->setUserApproved($viewer);
      
      if( !$viewer->membership()->isUserApprovalRequired() && !$viewer->membership()->isReciprocal() ) {
        // if one way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends_follow', '{item:$subject} is now following {item:$object}.');

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $viewer, 'friend_follow');

        $message = Zend_Registry::get('Zend_Translate')->_("You are now following this member.");
        
      } else if( !$viewer->membership()->isUserApprovalRequired() && $viewer->membership()->isReciprocal() ){
        // if two way friendship and verification not required

        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_accepted');
        
        $message = Zend_Registry::get('Zend_Translate')->_("You are now friends with this member.");

      } else if( !$user->membership()->isReciprocal() ) {
        // if one way friendship and verification required

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_follow_request');
        
        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
        
      } else if( $user->membership()->isReciprocal() ) {
        // if two way friendship and verification required

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($user, $viewer, $user, 'friend_request');
        
        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
      }

      $db->commit();


      $this->view->status = true;
      return $this->_forwardSuccess('Your friend request has been sent.');
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
      return;
    }
  }

  public function cancelAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    
    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }
    
    // Make form
    $this->view->form = $form = new User_Form_Friends_Cancel(array('user' => $user));

    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }
    
    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      $user->membership()->removeMember($viewer);

      // Set the requests as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($user, $viewer, 'friend_request');
      if( $notification ) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($user, $viewer, 'friend_follow_request');
      if( $notification ) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }

      $db->commit();

      $this->view->status = true;
      return $this->_forwardSuccess('Your friend request has been cancelled.');
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }

  public function confirmAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    
    $format = $this->_getParam('format', null);

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    $friendship = $viewer->membership()->getRow($user);
    if( null == $friendship ) {
      return $this->_helper->requireAuth->forward();
    }
    // Make form
    $this->view->form = $form = new User_Form_Friends_Confirm(array('user' => $user));
    
    if(empty($format)) {
      if( !$this->getRequest()->isPost() ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
        return;
      }

      if( !$form->isValid($this->getRequest()->getPost()) ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
        return;
      }
    }

    if( $friendship->active ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Already friends');
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      $viewer->membership()->setResourceApproved($user);

      // Add activity
      if( !$user->membership()->isReciprocal() ) {
        Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $viewer, 'friends_follow', '{item:$subject} is now following {item:$object}.');
      } else {
        Engine_Api::_()->getDbtable('actions', 'activity')
          ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
        Engine_Api::_()->getDbtable('actions', 'activity')
          ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');
      }
      
      // Add notification
      if( !$user->membership()->isReciprocal() ) {
        Engine_Api::_()->getDbtable('notifications', 'activity')
          ->addNotification($user, $viewer, $user, 'friend_follow_accepted');
      } else {
        Engine_Api::_()->getDbtable('notifications', 'activity')
          ->addNotification($user, $viewer, $user, 'friend_accepted');
      }

      // Set the requests as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
      if( $notification ) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if( $notification ) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      
      // Increment friends counter
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('user.friendships');

      $db->commit();

      $this->view->status = true;
      return $this->_forwardSuccess('You are now friends with %s', $user);
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }

  public function rejectAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    
    $format = $this->_getParam('format', null);
    
    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // Make form
    $this->view->form = $form = new User_Form_Friends_Reject(array('user' => $user));
    
    if(empty($format)) {
      if( !$this->getRequest()->isPost() ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
        return;
      }

      if( !$form->isValid($this->getRequest()->getPost()) ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
        return;
      }
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      if ($viewer->membership()->isMember($user)) {
        $viewer->membership()->removeMember($user);
      }

      // Set the request as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
      if( $notification ) {
        $notification->delete();
      }
      
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if( $notification ) {
        $notification->delete();
      }
      
      $db->commit();

      $this->view->status = true;
      return $this->_forwardSuccess('You ignored a friend request from %s', $user);
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }
  
  public function ignoreAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }
    
    $format = $this->_getParam('format', null);

    // Make form
    $this->view->form = $form = new User_Form_Friends_Reject(array('user' => $user));
    
    if(empty($format)) {
      if( !$this->getRequest()->isPost() ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
        return;
      }

      if( !$form->isValid($this->getRequest()->getPost()) ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
        return;
      }
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      $viewer->membership()->removeMember($user);

      // Set the requests as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
      if( $notification ) {
        $notification->delete();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if( $notification ) {
        $notification->delete();
      }

      $db->commit();

      $this->view->status = true;
      $this->_forwardSuccess('You ignored %s\'s request to follow you', $user);
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }
  
  public function removeAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if( null == ($user_id = $this->_getParam('user_id')) ||
        null == ($user = Engine_Api::_()->getItem('user', $user_id)) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // Make form
    $this->view->form = $form = new User_Form_Friends_Remove(array('user' => $user));

    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      if( $this->_getParam('rev') ) {
        $viewer->membership()->removeMember($user);
      } else {
        $user->membership()->removeMember($viewer);
      }

      // Remove from lists?
      // @todo make sure this works with one-way friendships
      $user->lists()->removeFriendFromLists($viewer);
      $viewer->lists()->removeFriendFromLists($user);

      // Set the requests as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
        ->getNotificationBySubjectAndType($user, $viewer, 'friend_request');
      if( $notification ) {
        $notification->delete();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
          ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if( $notification ) {
        $notification->delete();
      }

      $db->commit();

      $this->view->status = true;
      return $this->_forwardSuccess('This person has been removed from your friends.');
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }

  public function suggestAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer->getIdentity() ) {
      $data = null;
    } else {
      $data = array();
      $table = Engine_Api::_()->getItemTable('user');
      
      $usersAllowed = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('messages', $viewer->level_id, 'auth');

      if(((bool)$this->_getParam('message') && $usersAllowed == "everyone") || !$this->_getParam('message',false)) {
        $select = Engine_Api::_()->getDbtable('users', 'user')->select();
        if(! $this->_getParam('includeSelf', false) ) {
          $select->where('user_id <> ?', $viewer->user_id);
        }
      }
      else {
        $select = Engine_Api::_()->user()->getViewer()->membership()->getMembersObjectSelect();          
      }
         
      // if( $this->_getParam('includeSelf', false) ) {
      //   $data[] = array(
      //     'type' => 'user',
      //     'id' => $viewer->getIdentity(),
      //     'guid' => $viewer->getGuid(),
      //     'label' => $viewer->getTitle() . ' (you)',
      //     'photo' => $this->view->itemPhoto($viewer, 'thumb.icon'),
      //     'url' => $viewer->getHref(),
      //   );
      // }
      $blockedUserIds = !$viewer->isAdmin() ? $viewer->getAllBlockedUserIds() : array();
      if( $blockedUserIds ) {
        $select->where('user_id NOT IN(?)', (array) $blockedUserIds);
      }
      if( 0 < ($limit = (int) $this->_getParam('limit', 20)) ) {
        $select->limit($limit);
      }

      if( null !== ($text = $this->_getParam('search', $this->_getParam('value'))) ) {
        $select->where('`'.$table->info('name').'`.`displayname` LIKE ?', '%'. $text .'%');
        $select->order("LENGTH(displayname)");
      }
      $ids = array();
      foreach( $select->getTable()->fetchAll($select) as $friend ) {
        if($friend->mention != 'registered'){
          if(!$friend->authorization()->isAllowed($viewer, 'mention') && !$viewer->isAdmin())
            continue;
        }
        $data[] = array(
          'type'  => 'user',
          'id'    => $friend->getIdentity(),
          'guid'  => $friend->getGuid(),
          'label' => $friend->getIdentity() == $viewer->getIdentity() ? $friend->getTitle() . ' (you)' : $friend->getTitle(),
          'title' => $friend->getIdentity() == $viewer->getIdentity() ? $friend->getTitle(false) . ' (you)' : $friend->getTitle(false),
          'photo' => $this->view->itemPhoto($friend, 'thumb.icon'),
          'url'   => $friend->getHref(),
        );
        $ids[] = $friend->getIdentity();
        $friend_data[$friend->getIdentity()] = $friend->getTitle();
      }
      
      // first get friend lists created by the user
      $listTable = Engine_Api::_()->getItemTable('user_list');
      $lists = $listTable->fetchAll($listTable->select()->where('owner_id = ?', $viewer->getIdentity()));
      $listIds = array();
      foreach( $lists as $list ) {
        $listIds[] = $list->list_id;
        $listArray[$list->list_id] = $list->title;
      }

      // check if user has friend lists
      if( $listIds ) {
        // get list of friend list + friends in the list
        $listItemTable = Engine_Api::_()->getItemTable('user_list_item');
        $uName = Engine_Api::_()->getDbtable('users', 'user')->info('name');
        $iName  = $listItemTable->info('name');

        $listItemSelect = $listItemTable->select()
          ->setIntegrityCheck(false)
          ->from($iName, array($iName.'.listitem_id', $iName.'.list_id', $iName.'.child_id',$uName.'.displayname'))
          ->joinLeft($uName, "$iName.child_id = $uName.user_id")
          //->group("$iName.child_id")
          ->where('list_id IN(?)', $listIds);

        $listItems = $listItemTable->fetchAll($listItemSelect);

        $listsByUser = array();
        $listsByUserIds = array();
        foreach( $listItems as $listItem ) {
          $listsByUser[$listItem->list_id][$listItem->user_id]= $listItem->displayname ;
          $listsByUserIds[$listItem->list_id] .= $listItem->user_id.',';
        }
        
        foreach ($listArray as $key => $value){
          if (!empty($listsByUser[$key])){
            $data[] = array(
              'type' => 'list',
              'id'    => $listsByUserIds[$key],
              'friends' => $listsByUser[$key],
              'label' => $value,
            );
          }
        }
      }
    }

    if( $this->_getParam('sendNow', true) ) {
      return $this->_helper->json($data);
    } else {
      $this->_helper->viewRenderer->setNoRender(true);
      $data = Zend_Json::encode($data);
      $this->getResponse()->setBody($data);
    }
  }

  
  public function requestFriendAction()
  {
    $this->view->notification = $notification = $this->_getParam('notification');
    $this->setTokenData();
  }

  public function requestFollowAction()
  {
    $this->view->notification = $notification = $this->_getParam('notification');
    $this->setTokenData();
  }

  private function setTokenData()
  {
    $this->view->tokenName = $tokenName = 'token_' . $this->view->notification->getSubject()->getGuid();
    $salt = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.secret');
    $this->view->tokenValue = $this->view->token(null, $tokenName, $salt);
  }

  private function _forwardSuccess($message = '', $user = null)
  {
    $message = Zend_Registry::get('Zend_Translate')->_($message);
    if ($user instanceof User_Model_User) {
      $message = sprintf($message, $user->__toString());
    }

    $this->view->message = $message;
    $params = array('messages' => array($message));
    if ($this->_helper->contextSwitch->getCurrentContext() === 'smoothbox') {
      $params = array_merge($params, array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
      ));
    }
    return $this->_forward('success', 'utility', 'core', $params);
  }
}
