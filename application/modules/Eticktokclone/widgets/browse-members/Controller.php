<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Widget_BrowseMembersController extends Engine_Content_Widget_Abstract {
  public function indexAction()
  {

    
    if(Engine_Api::_()->core()->hasSubject('user'))
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user'); 

    $this->view->type = $type  = $this->_getParam('type','');
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->isAjax = $isAjax  = $this->_getParam('is_ajax',0);
    $userTable = Engine_Api::_()->getDbtable('users', 'eticktokclone');
    $userTableName = $userTable->info('name');
    $select = $userTable->select();
    // $select->where('photo_id <> ?', 0);
    if($type){
      if($subject->user_id != $viewer->getIdentity()){
        $isBlocked = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->anyOneBlocked(array("user_id"=>$subject->getIdentity()));
        if($isBlocked){
          if($viewer->getIdentity() == $isBlocked->user_id){
          return $this->setNoRender();
          }
        }
      }
      if($type == "followings"){
        
        $select->where("engine4_users.user_id IN (SELECT user_id FROM engine4_eticktokclone_follows WHERE resource_id = ?)", $subject->getIdentity());
        $select->where("engine4_users.user_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")", $subject->getIdentity());
      }else{
        $select->where("engine4_users.user_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")", $subject->getIdentity());
        $select->where("engine4_users.user_id IN (SELECT resource_id FROM engine4_eticktokclone_follows WHERE user_id = ?)", $subject->getIdentity());
      }
    }else{
      // if($viewer->getIdentity()){
        $this->view->followVideos = true;
        // return;
      // }

      $select->where('user_id <> ?', $viewer->getIdentity());
      $select->where(" SELECT count(*) from engine4_sesvideo_videos where engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
      WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().") AND engine4_sesvideo_videos.owner_id = engine4_users.user_id AND engine4_sesvideo_videos.is_tickvideo = 1  > 0 ");
      $select->where("engine4_users.user_id NOT IN (SELECT user_id FROM engine4_eticktokclone_follows WHERE resource_id = ?)", $viewer->getIdentity());
    }
    // $select->order('');
    // if($type == "followings"){
    //   echo $select;die;
    // }
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(15);
    $paginator->setCurrentPageNumber($this->_getParam("page",1));
    $this->view->paginator = $paginator;
    $this->view->page = $this->_getParam("page",1);
    
      if($type && $paginator->getTotalItemCount() == 0)
        return $this->setNoRender();

	}
}
