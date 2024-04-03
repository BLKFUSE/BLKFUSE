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


class Eticktokclone_Widget_MemberProfileUserInfoController extends Engine_Content_Widget_Abstract {

    public function indexAction() {
    
      // Don't render this if not authorized 
      $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer(); 
      if( !Engine_Api::_()->core()->hasSubject() ) {  
        return $this->setNoRender(); 
      }
      $this->view->viewer_id = $viewer->getIdentity();

      // Get subject and check auth 
      $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user'); 
      // if( !$subject->authorization()->isAllowed($viewer, 'view') ) { 
      //   return $this->setNoRender(); 
      // }
      $viewer = Engine_Api::_()->user()->getViewer();
      $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
      $videoTable = $video->info('name');
      $this->view->canFollow = true;
      $showStats = true;
      if($subject->user_id != $viewer->getIdentity()){
        $isBlocked = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->anyOneBlocked(array("user_id"=>$subject->getIdentity()));
        if($isBlocked){
          if($viewer->getIdentity() == $isBlocked->user_id){
            $this->view->canFollow = false;
            $showStats = false;
          }
        }
      }
      if($showStats){
      $select = $video->select()->from($videoTable, new Zend_Db_Expr('SUM(like_count)'));
      $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
      WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
      $select->where($videoTable . '.status = ?', 1);
      $select->where($videoTable . '.approve = ?', 1);
      $select->where($videoTable.'.is_tickvideo = 1');
      // $select->where($videoTable.'.video_id IN (SELECT resource_id from engine4_core_likes WHERE resource_type = "video" AND poster_id = '.$subject->getIdentity().')');
      $select->where($videoTable.'.owner_id = ?',$subject->getIdentity());
      $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
      $select->group('owner_id');
      // echo $select;die;
      $this->view->like_count = $select->query()->fetchColumn();

      $following = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->following(array('user_id' => $subject->getIdentity(), 'paginator' => true));
      $this->view->followingCount = Engine_Api::_()->eticktokclone()->number_format_short($following->getTotalItemCount());
      $this->view->followCount = Engine_Api::_()->eticktokclone()->number_format_short(Engine_Api::_()->eticktokclone()->getFollowCount($subject->getIdentity()));
      }else{
        $this->view->followingCount = $this->view->followCount = $this->view->like_count = 0;
      }
      
  
      // Can't block self or if not logged in
      if( $viewer->getIdentity() && $viewer->getGuid() != $subject->getGuid() ) {
        $this->view->allowBlock = true;
        $block = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$subject->getIdentity()));
        $this->view->isBlock = $block;
      }
  
      


    }
}
