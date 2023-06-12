<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_IndexController extends Core_Controller_Action_Standard {

  public function nearestMemberAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer)
      return $this->_forward('notfound', 'error', 'core');
    else {
      $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($viewer->getType(), $viewer->getIdentity());
      if (!$getLoggedInuserLocation)
        return $this->_forward('notfound', 'error', 'core');
    }
    $sesmember_nearestmembers = Zend_Registry::isRegistered('sesmember_nearestmembers') ? Zend_Registry::get('sesmember_nearestmembers') : null;
    if (empty($sesmember_nearestmembers)) {
      return $this->_forward('notfound', 'error', 'core');
    }
    //Render
    $this->_helper->content->setEnabled();
  }

  public function alphabeticMembersSearchAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function pinboradViewMembersAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function topMembersAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function profiletypeAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;
    $homepage_id = $this->_getParam('profiletype_id', null);

    // Render
    $this->_helper->content
            ->setContentName("sesmember_index_$homepage_id")
            ->setEnabled();
  }

  public function editormembersAction() {

    $require_check = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.browse', 1);
    if (!$require_check) {
      if (!$this->_helper->requireUser()->isValid())
        return;
    }

    // Render
    $this->_helper->content->setEnabled();
  }

  public function browseAction() {

    $require_check = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.browse', 1);
    if (!$require_check) {
      if (!$this->_helper->requireUser()->isValid())
        return;
    }

    // Render
    $this->_helper->content->setEnabled();
  }

  public function homeAction() {

    // check public settings
    $require_check = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.portal', 1);
    if (!$require_check) {
      if (!$this->_helper->requireUser()->isValid())
        return;
    }

    if (!Engine_Api::_()->user()->getViewer()->getIdentity()) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }

    $viewer = Engine_Api::_()->user()->getViewer();
    $homepageId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($viewer->level_id, '0', 'home');

    // Render
    $this->_helper->content
            ->setContentName("sesmember_index_$homepageId")
            ->setEnabled();
  }

  public function getFriendsAction() {
    $this->view->user_id = $user_id = $this->_getParam('user_id', 0);
    if (!$user_id)
      return $this->_forward('notfound', 'error', 'core');

    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $subject = Engine_Api::_()->getItem('user', $user_id);
    $this->view->titlePage = Zend_Registry::get('Zend_Translate')->_('Get Friends');
    $this->view->urlpage = 'sesmember/index/get-friends/user_id';
    // Multiple friend mode
    $select = $subject->membership()->getMembersOfSelect();

    $this->view->paginator = $friends = $paginator = Zend_Paginator::factory($select);
    $this->view->checkFriend = true;
    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
    // Get stuff
    $ids = array();
    foreach ($friends as $friend) {
      $ids[] = $friend->resource_id;
    }
    $this->view->friendIds = $ids;

    // Get the items
    $friendUsers = array();
    foreach (Engine_Api::_()->getItemTable('user')->find($ids) as $friendUser) {
      $friendUsers[$friendUser->getIdentity()] = $friendUser;
    }
    $this->view->friendUsers = $friendUsers;
  }

  public function getMutualFriendsAction() {
    $this->view->user_id = $user_id = $this->_getParam('user_id', 0);
    if (!$user_id)
      return $this->_forward('notfound', 'error', 'core');

    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $this->view->titlePage = Zend_Registry::get('Zend_Translate')->_('Get Mutual Friend');
    $this->view->urlpage = 'sesmember/index/get-mutual-friends/user_id';

    $viewer = Engine_Api::_()->user()->getViewer();
    $friendsTable = Engine_Api::_()->getDbtable('membership', 'user');
    $friendsName = $friendsTable->info('name');
    $col1 = 'resource_id';
    $col2 = 'user_id';
    $select = new Zend_Db_Select($friendsTable->getAdapter());
    $select
            ->from($friendsName, $col1)
            ->join($friendsName, "`{$friendsName}`.`{$col1}`=`{$friendsName}_2`.{$col1}", null)
            ->where("`{$friendsName}`.{$col2} = ?", $viewer->getIdentity())
            ->where("`{$friendsName}_2`.{$col2} = ?", $user_id)
            ->where("`{$friendsName}`.active = ?", 1)
            ->where("`{$friendsName}_2`.active = ?", 1)
    ;
    // Now get all common friends
    $uids = array();
    $this->view->item = $item = Zend_Paginator::factory($select);
    // Set item count per page and current page number
    $item->setItemCountPerPage(20);
    $item->setCurrentPageNumber($page);
    foreach ($item as $data) {
      $uids[] = $data[$col1];
    }
    // Do not render if nothing to show
    if (engine_count($uids) <= 0) {
      return 0;
    }
    // Get paginator
    $usersTable = Engine_Api::_()->getItemTable('user');
    $select = $usersTable->select()->from($usersTable->info('name'))->where('user_id IN(?)', $uids);
    $this->view->paginator = Zend_Paginator::factory($select);
    $item->setItemCountPerPage(20);
    $item->setCurrentPageNumber($page);
  }

  public function reviewUserRatingAction() {
    $this->view->rating = $rating = $this->_getParam('rating_id', 0);
    $this->view->user_id = $user_id = $this->_getParam('user_id', 0);
    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('reviews', 'sesmember')->getUserRatingWithStar(array('user_id' => $user_id, 'rating' => $rating));
    $paginator->setItemCountPerPage(10);
    $paginator->setCurrentPageNumber($page);
  }

  public function reviewStatsAction() {
    $user_id = $this->_getParam('user_id', 0);
    if (!$user_id && !isset($_SERVER['HTTP_REFERER']))
      return $this->_forward('notfound', 'error', 'core');

    $this->view->subject = $subject = Engine_Api::_()->getItem('user', $user_id);
    if (!$subject)
      return $this->_forward('notfound', 'error', 'core');

    $this->view->ratingStats = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getUserRatingStats(array('user_id' => $user_id));
  }

  public function locationsAction() {
    //Render
    $this->_helper->content->setEnabled();
  }


  public function acceptAction()
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
    $item_id = $this->_getParam('user_id');
    $follow_id = $this->_getParam('follow_id', 0);
    $follow = Engine_Api::_()->getItem('sesmember_follow', $follow_id);

    // Make form
    $this->view->form = $form = new Sesmember_Form_Follow_Accept(array('user' => $user));

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
    $db = Engine_Api::_()->getDbtable('follows', 'sesmember')->getAdapter();
    $db->beginTransaction();

    try {

      $follow->user_approved = 1;
      $follow->save();
      $db->commit();

      Engine_Api::_()->getItemTable('sesmember_userinfo')->update(array('follow_count' => new Zend_Db_Expr('follow_count + 1')), array('user_id = ?' => $item_id));

      //Send notification
      $subject = $user;
      $owner = $subject->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {

        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sesmember_follow_requestaccept', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));

        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesmember_follow_requestaccept');
      }


      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('You accept follow request.');
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array($this->view->message)
      ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function rejectAction()
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
    $item_id = $this->_getParam('user_id');
    $follow_id = $this->_getParam('follow_id', 0);
    $follow = Engine_Api::_()->getItem('sesmember_follow', $follow_id);
    // Make form
    $this->view->form = $form = new Sesmember_Form_Follow_Reject(array('user' => $user));

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
    $db = Engine_Api::_()->getDbtable('follows', 'sesmember')->getAdapter();
    $db->beginTransaction();
    $itemTable = Engine_Api::_()->getItemTable('sesmember_userinfo');
    try {
      $follow->delete();
      $itemTable->update(array('follow_count' => new Zend_Db_Expr('follow_count - 1')), array('user_id = ?' => $item_id));
      $db->commit();

      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('You ignored a friend request from %s', $user);
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array($this->view->message)
      ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
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
    $item_id = $this->_getParam('user_id');
    $follow_id = $this->_getParam('follow_id', 0);
    $follow = Engine_Api::_()->getItem('sesmember_follow', $follow_id);

    // Make form
    $this->view->form = $form = new Sesmember_Form_Follow_Remove(array('user' => $user));

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
    $itemTable = Engine_Api::_()->getItemTable('user');
    // Process
    $db = Engine_Api::_()->getDbtable('follows', 'sesmember')->getAdapter();
    $db->beginTransaction();

    try {
      $follow->delete();
      $itemTable->update(array('follow_count' => new Zend_Db_Expr('follow_count - 1')), array('user_id = ?' => $item_id));
      $db->commit();

      //Send notification
      $subject = $user;
      $owner = $subject->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {

        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sesmember_unfollow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));

        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesmember_unfollow');
      }

      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('You have successfully unfollow this member.');
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array($this->view->message)
      ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  function followAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $itemTable = Engine_Api::_()->getItemTable('user');
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($item_id);
    $tableFollow = Engine_Api::_()->getDbtable('follows', 'sesmember');
    $tableMainFollow = $tableFollow->info('name');

    $select = $tableFollow->select()
            ->from($tableMainFollow)
            ->where('resource_id = ?', $viewer_id)
            ->where('user_id = ?', $item_id);
    $result = $tableFollow->fetchRow($select);
    $member = Engine_Api::_()->getItem('user', $item_id);
    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();

        if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
            $userInfoItem->follow_count--;
            $userInfoItem->save();

            //$userInfoItem->update(array('follow_count' => new Zend_Db_Expr('follow_count - 1')), array('user_id = ?' => $item_id));

            $user = Engine_Api::_()->getItem('user', $item_id);
            //Unfollow notification Work: Delete follow notification and feed
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmember_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));
            Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => "sesmember_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));
        }

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
        $selectUser = $itemTable->select()->where('user_id =?', $item_id);
        $user = $itemTable->fetchRow($selectUser);
        echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $userInfoItem->follow_count, 'autofollow' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)));
        die;
      } else {
        $showData = $this->view->partial('_followmembers.tpl', 'sesmember', array('subject' => $member));
        echo Zend_Json::encode(array('status' => 'true', 'error' => '', 'message' => $showData, 'autofollow' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)));
        exit();
      }
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('follows', 'sesmember')->getAdapter();
      $db->beginTransaction();
      try {
        $follow = $tableFollow->createRow();
        $follow->resource_id = $viewer_id;
        $follow->user_id = $item_id;
        $follow->resource_approved = 1;
        $follow->save();

        if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
            $userInfoItem->follow_count++;
            $userInfoItem->save();
            $follow->user_approved = 1;
            $follow->save();

            //$userInfoItem->update(array('follow_count' => new Zend_Db_Expr('follow_count + 1')), array('user_id = ?' => $item_id));
        } else {
            $follow->user_approved = 0;
            $follow->save();
        }
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
        //Send notification and activity feed work.
        $selectUser = $itemTable->select()->where('user_id =?', $item_id);
        $item = $itemTable->fetchRow($selectUser);
        $subject = $item;
        $owner = $subject->getOwner();
        if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {
            $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sesmember_follow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesmember_follow');
            $result = $activityTable->fetchRow(array('type =?' => 'sesmember_follow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
            if (!$result) {
            $action = $activityTable->addActivity($viewer, $subject, 'sesmember_follow');
            }

            //follow mail to another user
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($subject->email, 'sesmember_follow', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        }
        echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $userInfoItem->follow_count, 'autofollow' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)));
        die;
      } else {
        $subject = $member;
        $owner = $subject->getOwner();
        if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sesmember_follow_request', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesmember_follow_request');
        }
        $showData = $this->view->partial('_followmembers.tpl', 'sesmember', array('subject' => $member));
        echo Zend_Json::encode(array('status' => 'true', 'error' => '', 'message' => $showData, 'autofollow' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)));
        exit();
      }

    }
  }

  public function addFriendAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if (null == ($user_id = $this->_getParam('user_id')) ||
            null == ($user = Engine_Api::_()->getItem('user', $user_id))) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // check that user is not trying to befriend 'self'
    if ($viewer->isSelf($user)) {
      echo Zend_Json::encode(array('status' => 1, 'message' => array(Zend_Registry::get('Zend_Translate')->_('You cannot befriend yourself.'))));
      exit();
    }

    // check that user is already friends with the member
    if ($user->membership()->isMember($viewer)) {
      echo Zend_Json::encode(array('status' => 1, 'message' => array(Zend_Registry::get('Zend_Translate')->_('You are already friends with this member.'))));
      exit();
    }

    // check that user has not blocked the member
    if ($viewer->isBlocked($user)) {
      echo Zend_Json::encode(array('status' => 1, 'message' => array(Zend_Registry::get('Zend_Translate')->_('Friendship request was not sent because you blocked this member.'))));
      exit();
    }

    // Make form
    $this->view->form = $form = new User_Form_Friends_Add();

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
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

      if (!$viewer->membership()->isUserApprovalRequired() && !$viewer->membership()->isReciprocal()) {
        // if one way friendship and verification not required
        // Add activity
        Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($viewer, $user, 'friends_follow', '{item:$subject} is now following {item:$object}.');

        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($user, $viewer, $viewer, 'friend_follow');

        $message = Zend_Registry::get('Zend_Translate')->_("You are now following this member.");
      } else if (!$viewer->membership()->isUserApprovalRequired() && $viewer->membership()->isReciprocal()) {
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
      } else if (!$user->membership()->isReciprocal()) {
        // if one way friendship and verification required
        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($user, $viewer, $user, 'friend_follow_request');

        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
      } else if ($user->membership()->isReciprocal()) {
        // if two way friendship and verification required
        // Add notification
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($user, $viewer, $user, 'friend_request');

        $message = Zend_Registry::get('Zend_Translate')->_("Your friend request has been sent.");
      }

      $db->commit();
      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your friend request has been sent.');
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $showData = $this->view->partial('tooltip/member-data.tpl', 'sesbasic', array('subject' => $user, 'globalEnableTip' => $settings->getSetting('sesbasic_settings_tooltip', array('title', 'mainphoto', 'coverphoto')), 'moduleEnableTip' => $settings->getSetting($user->getType() . '_settings_tooltip', array('title', 'mainphoto', 'coverphoto', 'category', 'socialshare', 'location', 'hostedby', 'startendtime', 'buybutton'))));
      echo Zend_Json::encode(array('status' => 1, 'message' => $showData));
      exit();
    } catch (Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
      echo Zend_Json::encode(array('status' => 0, 'message' => $this->view->error));
      die;
      return;
    }
  }

  public function cancelFriendAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if (null == ($user_id = $this->_getParam('user_id')) ||
            null == ($user = Engine_Api::_()->getItem('user', $user_id))) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // Make form
    $this->view->form = $form = new User_Form_Friends_Cancel();

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
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
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($user, $viewer, 'friend_follow_request');
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }

      $db->commit();
      $user = Engine_Api::_()->getItem('user', $user_id);
      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your friend request has been cancelled.');
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $showData = $this->view->partial('tooltip/member-data.tpl', 'sesbasic', array('subject' => $user, 'globalEnableTip' => $settings->getSetting('sesbasic_settings_tooltip', array('title', 'mainphoto', 'coverphoto')), 'moduleEnableTip' => $settings->getSetting($user->getType() . '_settings_tooltip', array('title', 'mainphoto', 'coverphoto', 'category', 'socialshare', 'location', 'hostedby', 'startendtime', 'buybutton'))));
      echo Zend_Json::encode(array('status' => 1, 'message' => $showData));
      exit();
    } catch (Exception $e) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      echo Zend_Json::encode(array('status' => 0, 'message' => $this->view->error));
      die;
      $this->view->exception = $e->__toString();
    }
  }

  public function removeFriendAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if (null == ($user_id = $this->_getParam('user_id')) ||
            null == ($user = Engine_Api::_()->getItem('user', $user_id))) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    // Make form
    $this->view->form = $form = new User_Form_Friends_Remove();

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('membership', 'user')->getAdapter();
    $db->beginTransaction();

    try {
      if ($this->_getParam('rev')) {
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
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }

      $db->commit();

      $message = Zend_Registry::get('Zend_Translate')->_('This person has been removed from your friends.');

      $this->view->status = true;
      $this->view->message = $message;
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $showData = $this->view->partial('tooltip/member-data.tpl', 'sesbasic', array('subject' => $user, 'globalEnableTip' => $settings->getSetting('sesbasic_settings_tooltip', array('title', 'mainphoto', 'coverphoto')), 'moduleEnableTip' => $settings->getSetting($user->getType() . '_settings_tooltip', array('title', 'mainphoto', 'coverphoto', 'category', 'socialshare', 'location', 'hostedby', 'startendtime', 'buybutton'))));
      echo Zend_Json::encode(array('status' => 1, 'message' => $showData));
      exit();
    } catch (Exception $e) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      echo Zend_Json::encode(array('status' => 0, 'message' => $this->view->error));
      die;
      $this->view->exception = $e->__toString();
    }
  }

  public function addLocationAction() {

    $user = Engine_Api::_()->getItem('user', $this->_getParam('id', null));
    $this->view->form = $form = new Sesmember_Form_Location();
    $this->view->locationLatLng = $locationLatLng = Engine_Api::_()->getDbtable('locations', 'sesbasic')->getLocationData($user->getType(), $user->getIdentity());
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($user->getIdentity());
    if ($locationLatLng && $user->location) {
      $form->populate(array('ses_location' => $user->location,
          'ses_lat' => $locationLatLng['lat'],
          'ses_lng' => $locationLatLng['lng'],
          'ses_zip' => $locationLatLng['zip'],
          'ses_city' => $locationLatLng['city'],
          'ses_state' => $locationLatLng['state'],
          'ses_country' => $locationLatLng['country']));
      $form->setTitle('Edit Location');
      $form->submit->setLabel('Update Location');
    }
    if ($this->getRequest()->getPost()) {
        $userInfoItem->location = $_POST['ses_location'];
        $userInfoItem->save();

//       Engine_Api::_()->getItemTable('user')->update(array(
//           'location' => $_POST['ses_location'],
//               ), array(
//           'user_id = ?' => $user->getIdentity(),
//       ));
      if (isset($_POST['ses_lat']) && isset($_POST['ses_lng']) && $_POST['ses_lat'] != '' && $_POST['ses_lng'] != '' && !empty($_POST['ses_location'])) {
        Engine_Db_Table::getDefaultAdapter()->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng ,city,state,zip,country, resource_type) VALUES ("' . $user->user_id . '", "' . $_POST['ses_lat'] . '","' . $_POST['ses_lng'] . '","' . $_POST['ses_city'] . '","' . $_POST['ses_state'] . '","' . $_POST['ses_zip'] . '","' . $_POST['ses_country'] . '",  "user")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['ses_lat'] . '" , lng = "' . $_POST['ses_lng'] . '",city = "' . $_POST['ses_city'] . '", state = "' . $_POST['ses_state'] . '", country = "' . $_POST['ses_country'] . '", zip = "' . $_POST['ses_zip'] . '"');
        return $this->_forward('success', 'utility', 'core', array(
                    'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'edit-location', 'id' => $user->getIdentity()), 'sesmember_general', true),
                    'messages' => array(Zend_Registry::get('Zend_Translate')->_('Location has been added successfully.'))
        ));
      } else {
        Engine_Api::_()->getItemTable('sesbasic_location')->delete(
                array(
                    'resource_id = ?' => $user->getIdentity(),
                    'resource_type' => 'user'
        ));
        $profileAddress = null;
        if (isset($user->username) && '' != trim($user->username)) {
          $profileAddress = $user->username;
        } else if (isset($user->user_id) && $user->user_id > 0) {
          $profileAddress = $user->user_id;
        }
        return $this->_forward('success', 'utility', 'core', array(
                    'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('id' => $profileAddress), 'user_profile', true),
                    'messages' => array(Zend_Registry::get('Zend_Translate')->_('Location has been added successfully.'))
        ));
      }
    }
  }

  public function acceptFriendAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;
    // Get viewer and other user
    $viewer = Engine_Api::_()->user()->getViewer();
    if (null == ($user_id = $this->_getParam('user_id')) ||
            null == ($user = Engine_Api::_()->getItem('user', $user_id))) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }
    // Make form
    $this->view->form = $form = new User_Form_Friends_Confirm();
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No action taken');
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }
    $friendship = $viewer->membership()->getRow($user);
    if ($friendship->active) {
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
      if (!$user->membership()->isReciprocal()) {
        Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($user, $viewer, 'friends_follow', '{item:$subject} is now following {item:$object}.');
      } else {
        Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($user, $viewer, 'friends', '{item:$object} is now friends with {item:$subject}.');
        Engine_Api::_()->getDbtable('actions', 'activity')
                ->addActivity($viewer, $user, 'friends', '{item:$object} is now friends with {item:$subject}.');
      }
      // Add notification
      if (!$user->membership()->isReciprocal()) {
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($user, $viewer, $user, 'friend_follow_accepted');
      } else {
        Engine_Api::_()->getDbtable('notifications', 'activity')
                ->addNotification($user, $viewer, $user, 'friend_accepted');
      }
      // Set the requests as handled
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_request');
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      $notification = Engine_Api::_()->getDbtable('notifications', 'activity')
              ->getNotificationBySubjectAndType($viewer, $user, 'friend_follow_request');
      if ($notification) {
        $notification->mitigated = true;
        $notification->read = 1;
        $notification->save();
      }
      // Increment friends counter
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('user.friendships');
      $db->commit();
      $message = Zend_Registry::get('Zend_Translate')->_('You are now friends with %s');
      $message = sprintf($message, $user->__toString());
      $this->view->status = true;
      $this->view->message = $message;
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $showData = $this->view->partial('tooltip/member-data.tpl', 'sesbasic', array('subject' => $user, 'globalEnableTip' => $settings->getSetting('sesbasic_settings_tooltip', array('title', 'mainphoto', 'coverphoto')), 'moduleEnableTip' => $settings->getSetting($user->getType() . '_settings_tooltip', array('title', 'mainphoto', 'coverphoto', 'category', 'socialshare', 'location', 'hostedby', 'startendtime', 'buybutton'))));
      echo Zend_Json::encode(array('status' => 1, 'message' => $showData));
      exit();
    } catch (Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
      echo Zend_Json::encode(array('status' => 0, 'message' => $this->view->error));
      die;
    }
  }

  public function editLocationAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();


    if (!Engine_Api::_()->core()->hasSubject()) {
      // Can specifiy custom id
      $id = $this->_getParam('id', null);
      $subject = null;
      if (null === $id) {
        $subject = Engine_Api::_()->user()->getViewer();
        Engine_Api::_()->core()->setSubject($subject);
      } else {
        $subject = Engine_Api::_()->getItem('user', $id);
        Engine_Api::_()->core()->setSubject($subject);
      }
    }
    if ($id) {
      $params = array('id' => $id);
    } else {
      $params = array();
    }
    $this->view->user = $user = Engine_Api::_()->getItem('user', $subject->getIdentity());
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($subject->getIdentity());

    $userLocation = $userInfoItem->location;
    if (!$userLocation)
      return $this->_forward('notfound', 'error', 'core');

    // Set up navigation
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('user_edit', array('params' => $params));

    $this->view->locationLatLng = $locationLatLng = Engine_Api::_()->getDbtable('locations', 'sesbasic')->getLocationData($user->getType(), $user->getIdentity());
    if (!$locationLatLng) {
      return $this->_forward('notfound', 'error', 'core');
    }

    $this->view->form = $form = new Sesmember_Form_Location();
    $form->populate(array(
        'ses_edit_location' => $userLocation,
        'ses_lat' => $locationLatLng['lat'],
        'ses_lng' => $locationLatLng['lng'],
        'ses_zip' => $locationLatLng['zip'],
        'ses_city' => $locationLatLng['city'],
        'ses_state' => $locationLatLng['state'],
        'ses_country' => $locationLatLng['country'],
    ));
    // Check if post
    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Not post');
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $this->view->status = false;
      $this->view->error =  Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }
    if ($this->getRequest()->getPost()) {
        $userInfoItem->location = $_POST['ses_edit_location'];
        $userInfoItem->save();

//       Engine_Api::_()->getItemTable('user')->update(array(
//           'location' => $_POST['ses_edit_location'],
//               ), array(
//           'user_id = ?' => $user->getIdentity(),
//       ));
      if (isset($_POST['ses_lat']) && isset($_POST['ses_lng']) && $_POST['ses_lat'] != '' && $_POST['ses_lng'] != '' && !empty($_POST['ses_edit_location'])) {
        Engine_Db_Table::getDefaultAdapter()->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng ,city,state,zip,country, resource_type) VALUES ("' . $user->user_id . '", "' . $_POST['ses_lat'] . '","' . $_POST['ses_lng'] . '","' . $_POST['ses_city'] . '","' . $_POST['ses_state'] . '","' . $_POST['ses_zip'] . '","' . $_POST['ses_country'] . '",  "user")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['ses_lat'] . '" , lng = "' . $_POST['ses_lng'] . '",city = "' . $_POST['ses_city'] . '", state = "' . $_POST['ses_state'] . '", country = "' . $_POST['ses_country'] . '", zip = "' . $_POST['ses_zip'] . '"');
      }
      return $this->_helper->redirector->gotoRoute(array());
    }
    //Render
  }

  public function getReviewAction() {
    $sesdata = array();
    $userTable = Engine_Api::_()->getItemTable('sesmember_review');
    $selectUserTable = $userTable->select()->where('title LIKE "%' . $this->_getParam('text', '') . '%"');
    $users = $userTable->fetchAll($selectUserTable);
    foreach ($users as $user) {
      $userItem = $user->getOwner();
      $user_icon = $this->view->itemPhoto($userItem, 'thumb.icon');
      $sesdata[] = array(
          'id' => $user->review_id,
          'user_id' => $user->user_id,
          'label' => $user->title,
          'photo' => $user_icon
      );
    }
    return $this->_helper->json($sesdata);
  }

  public function getMemberAction() {

    $sesdata = array();
    $userTable = Engine_Api::_()->getItemTable('user');
    $selectUserTable = $userTable->select()->where('displayname LIKE "%' . $this->_getParam('text', '') . '%"');
    $users = $userTable->fetchAll($selectUserTable);
    foreach ($users as $user) {
      $user_icon = $this->view->itemPhoto($user, 'thumb.icon');
      $sesdata[] = array(
          'id' => $user->user_id,
          'user_id' => $user->user_id,
          'label' => $user->getTitle(),
          'photo' => $user_icon
      );
    }
    return $this->_helper->json($sesdata);
  }

  function reviewVotesAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
    $item_id = $this->_getParam('id');
    $type = $this->_getParam('type');
    if (intval($item_id) == 0 || ($type != 1 && $type != 2 && $type != 3)) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $result = array();
    $itemTable = Engine_Api::_()->getItemTable('sesmember_review');
    $tableVotes = Engine_Api::_()->getDbtable('reviewvotes', 'sesmember');
    $tableMainVotes = $tableVotes->info('name');

    $select = $tableVotes->select()
            ->from($tableMainVotes)
            ->where('review_id = ?', $item_id)
            ->where('user_id = ?', $viewer_id)
            ->where('type =?', $type);
    $result = $tableVotes->fetchRow($select);
    if ($type == 1)
      $votesTitle = 'useful_count';
    else if ($type == 2)
      $votesTitle = 'funny_count';
    else
      $votesTitle = 'cool_count';

    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();
        $itemTable->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' - 1')), array('review_id = ?' => $item_id));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $selectReview = $itemTable->select()->where('review_id =?', $item_id);
      $review = $itemTable->fetchRow($selectReview);

      //get review owner
      $owner = $review->owner_id;
      $user = Engine_Api::_()->getItemTable('sesmember_review');
      $user->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' - 1')), array('user_id = ?' => $owner));

      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $review->{$votesTitle}));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('reviewvotes', 'sesmember')->getAdapter();
      $db->beginTransaction();
      try {
        $votereview = $tableVotes->createRow();
        $votereview->user_id = $viewer_id;
        $votereview->review_id = $item_id;
        $votereview->type = $type;
        $votereview->save();
        $itemTable->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' + 1')), array('review_id = ?' => $item_id));
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      //Send notification and activity feed work.
      $selectReview = $itemTable->select()->where('review_id =?', $item_id);
      $review = $itemTable->fetchRow($selectReview);

      //get review owner
      $owner = $review->owner_id;
      $user = Engine_Api::_()->getItemTable('sesmember_review');
      $user->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' + 1')), array('user_id = ?' => $owner));

      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $review->{$votesTitle}));
      die;
    }
  }


  function likeAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $itemTable = Engine_Api::_()->getItemTable('user');
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $result = array();
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($item_id);
    $select = $tableLike->select()
            ->from($tableMainLike)
            ->where('resource_type = ?', 'user')
            ->where('poster_id = ?', $viewer_id)
            ->where('poster_type = ?', 'user')
            ->where('resource_id = ?', $item_id);
    $result = $tableLike->fetchRow($select);

    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();
        Engine_Api::_()->getItemTable('user')->update(array(
          'like_count' => $item--,
              ), array(
          'user_id = ?' => $item_id,
        ));
        //$itemTable->update(array('like_count' => new Zend_Db_Expr('like_count - 1')), array('user_id = ?' => $item_id));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $selectUser = $itemTable->select()->where('user_id =?', $item_id);
      $user = $itemTable->fetchRow($selectUser);
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $user->like_count, 'user_verified' => $userInfoItem->user_verified));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = $viewer_id;
        $like->resource_type = 'user';
        $like->resource_id = $item_id;
        $like->poster_type = 'user';
        $like->save();
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array('user_id = ?' => $item_id));
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      //Send notification and activity feed work.
      $selectUser = $itemTable->select()->where('user_id =?', $item_id);
      $item = $itemTable->fetchRow($selectUser);
      $subject = $item;
      $owner = $subject->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {
        $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sesmember_member_likes', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'sesmember_member_likes');

        $result = $activityTable->fetchRow(array('type =?' => 'liked', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        if (!$result) {
          $action = $activityTable->addActivity($viewer, $subject, 'liked');
          if ($action)
            $activityTable->attachActivity($action, $subject);
        }
      }
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->like_count, 'user_verified' => $userInfoItem->user_verified));
      die;
    }
  }

  public function featuredBlockAction() {

    if (!$this->_helper->requireUser->isValid())
      return;

    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) {
      $this->_helper->requireAuth()->forward();
    }

    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();

    $isEdit = $this->_getParam('featured', 0);
    if ($isEdit)
      $this->view->photos = Engine_Api::_()->getDbTable('members', 'sesmember')->getFeaturedPhotos($viewerId);
    else
      $this->view->photos = array();
  }

  public function existingAlbumPhotosAction() {

    if (!$this->_helper->requireUser->isValid())
      return;

    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) {
      $this->_helper->requireAuth()->forward();
    }

    $page = $this->_getParam('page', 1);
    $this->view->album_id = $album_id = isset($_POST['id']) ? $_POST['id'] : 0;
    if ($album_id == 0) {
      echo "";
      die;
    }
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('members', 'sesmember')->getPhotoSelect(array('album_id' => $album_id, 'pagNator' => true));
    $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    $this->view->page = $page;
  }

  public function existingPhotosAction() {

    if (!$this->_helper->requireUser->isValid())
      return;

    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) {
      $this->_helper->requireAuth()->forward();
    }
    $page = $this->_getParam('page', 1);
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('members', 'sesmember')->getUserAlbum();
    $this->view->limit = $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
  }

  public function featuredPhotosAction() {
    if (!$this->_helper->requireUser->isValid())
      return;
    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album') && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) {
      $this->_helper->requireAuth()->forward();
    }
    $result = array();
    $table = Engine_Api::_()->getDbTable('featuredphotos', 'sesmember');
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $table->delete(array('user_id =?' => $viewer_id));
    foreach ($_POST as $key => $photo_id) {
      if ($key == 'format')
        continue;
      if ($photo_id != '') {
        $select = $table->select()->where('user_id =?', $viewer_id)->where('photo_id =?', $photo_id);
        $result = $table->fetchRow($select);
        if (!engine_count($result)) {
          $db = $table->getAdapter();
          $db->beginTransaction();
          try {
            $row = $table->createRow();
            $row->user_id = $viewer_id;
            $row->photo_id = $photo_id;
            $row->save();
            //Commit
            $db->commit();
          } catch (Exception $e) {
            $db->rollBack();
            throw $e;
          }
        }
      }
    }
    $this->view->photos = Engine_Api::_()->getDbTable('members', 'sesmember')->getFeaturedPhotos($viewer_id);
  }

}
