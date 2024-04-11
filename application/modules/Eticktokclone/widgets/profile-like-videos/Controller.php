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

class Eticktokclone_Widget_ProfileLikeVideosController extends Engine_Content_Widget_Abstract {
  public function indexAction()
  { 
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user'); 
    
    $this->view->isAjax = $isAjax  = $this->_getParam('is_ajax',0);
    $viewer = Engine_Api::_()->user()->getViewer();
    if($subject->user_id != $viewer->getIdentity()){
    $isBlocked = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->anyOneBlocked(array("user_id"=>$subject->getIdentity()));
      if($isBlocked){
        if($viewer->getIdentity() == $isBlocked->user_id){
        return $this->setNoRender();
        }
      }
    }
    $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
    $videoTable = $video->info('name');



    $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
    $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
    WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
    $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
    $select->where($videoTable . '.status = ?', 1);
    $select->where($videoTable . '.approve = ?', 1);
    $select->where($videoTable.'.is_tickvideo = 1');
    $select->where($videoTable.'.video_id IN (SELECT resource_id from engine4_core_likes WHERE resource_type = "video" AND poster_id = '.$subject->getIdentity().')');
    // $select->where($videoTable.'.owner_id = ?',$subject->getIdentity());
    $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
    $select = $select->order('video_id DESC');
    
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($this->_getParam('limit',10));
    $paginator->setCurrentPageNumber($this->_getParam('page',1));
    $this->view->page = $this->_getParam('page',1);
      // Do not render if nothing to show
    if ($paginator->getTotalItemCount() == 0){
        return $this->setNoRender();
    }

	}
}
