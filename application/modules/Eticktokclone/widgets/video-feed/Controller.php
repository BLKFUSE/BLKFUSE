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

class Eticktokclone_Widget_VideoFeedController extends Engine_Content_Widget_Abstract {

  public function indexAction()
  { 
	$this->view->randonNumber = $this->_getParam("randonNumber",false);
	$this->view->isAjax = $isAjax  = $this->_getParam('is_ajax',0);
	$this->view->limit_value  = $this->_getParam('limit_value',3);
	$this->view->limit_data = $value['limit'] = $this->_getParam('limit_data',5);
	$value['type'] = $this->_getParam('featured_sponsored_carosel','all');
	
	$this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
	$this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
	
	$viewer = Engine_Api::_()->user()->getViewer();
	$video = Engine_Api::_()->getDbTable("videos",'sesvideo');
	$videoTable = $video->info('name');


	$select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
	$select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
	WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
	$select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
	$select->where($videoTable . '.status = ?', 1);
	$select->where($videoTable . '.approve = ?', 1);
	$select->where($videoTable.'.is_tickvideo = 1');
	$select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
	$select = $select->order('video_id DESC');
	$followUserVideos = $this->view->followUserVideos = $this->_getParam("followUser");
	if(!empty($followUserVideos)){
		$select->where("engine4_sesvideo_videos.owner_id IN (SELECT user_id FROM engine4_eticktokclone_follows WHERE resource_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
	}

	$this->view->paginator = $paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage($this->_getParam('limit_data',10));
	$paginator->setCurrentPageNumber($this->_getParam('page',1));
	$this->view->page = $this->_getParam('page',1);
		 // Do not render if nothing to show
    if ($paginator->getTotalItemCount() == 0){
    //   return $this->setNoRender();
	}
	$this->view->widgetName = "video-feed";
  }
}
