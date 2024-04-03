<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_IndexController extends Core_Controller_Action_Standard {

  function taggedAction() {
    $this->_helper->content->setNoRender()->setEnabled();
  }
  
  function exploreAction() {
    $this->_helper->content->setNoRender()->setEnabled();
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
    $tableFollow = Engine_Api::_()->getDbtable('follows', 'eticktokclone');
    $tableMainFollow = $tableFollow->info('name');

    $select = $tableFollow->select()
            ->from($tableMainFollow)
            ->where('resource_id = ?', $viewer_id)
            ->where('user_id = ?', $item_id);
    $result = $tableFollow->fetchRow($select);
    $member = Engine_Api::_()->getItem('user', $item_id);
    $followCount = 0;
    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();

            $user = Engine_Api::_()->getItem('user', $item_id);
            //Unfollow notification Work: Delete follow notification and feed
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "eticktokclone_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));
            // Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => "tickvideo_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $selectUser = $itemTable->select()->where('user_id =?', $item_id);
        $user = $itemTable->fetchRow($selectUser);
        echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $followCount, 'autofollow' => 1));
        die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->getAdapter();
      $db->beginTransaction();
      try {
        $follow = $tableFollow->createRow();
        $follow->resource_id = $viewer_id;
        $follow->user_id = $item_id;
        $follow->resource_approved = 1;
        
        $follow->user_approved = 1;
        $follow->save();

       
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
           Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'eticktokclone_follow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
           Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'eticktokclone_follow');

           //follow mail to another user
           Engine_Api::_()->getApi('mail', 'core')->sendSystem($subject->email, 'eticktokclone_follow', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
       }
       echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $followCount, 'autofollow' => 1));
       die;
    }
  }

  function blockAction(){
    // Get id of friend to add
    $user_id = $this->_getParam('id', null);
    if( !$user_id ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No member specified');
      return;
    }

    $block = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$user_id));
    
    // Make form
    $this->view->form = $form = new Tickvideo_Form_Block();
    if($block) {
      $form->setTitle("Unblock Member");
      $form->setDescription("Do you want to unblock this member?");
      $form->getElement("submit")->setLabel("Unblock Member");
    }
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
    $db = Engine_Api::_()->getDbtable('blocks', 'eticktokclone')->getAdapter();
    $db->beginTransaction();
    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      $user = Engine_Api::_()->getItem('user', $user_id);
      if(!$block){
        $table = Engine_Api::_()->getDbTable("blocks",'eticktokclone');
        $row = $table->createRow();
        $row->user_id = $this->view->viewer()->getIdentity();
        $row->blocked_user_id = $user_id;
        $row->save();
      } else {
        Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$user_id,'remove'=>true));
      }
      $db->commit();

      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_($block ? 'Member Unblocked' : "Member Blocked");
      
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_($block ? 'Member Unblocked' : "Member Blocked"))
      ));
    } catch( Exception $e ) {
      $db->rollBack();

      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error has occurred.');
      $this->view->exception = $e->__toString();
    }
  }
}
