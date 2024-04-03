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

class Eticktokclone_Widget_TaggedVideosController extends Engine_Content_Widget_Abstract {
  public function indexAction()
  {
    
    $this->view->isAjax = $isAjax  = $this->_getParam('is_ajax',0);
    
    if(!$this->_getParam('tag')){
      $request = Zend_Controller_Front::getInstance()->getRequest();
      $params = $request->getParams();
      if(empty($params["tag"])){
        return $this->setNoRender();
      }
    }else{
      $params["tag"] = $this->_getParam('tag');
    }
    $tagmap = $this->view->tagmap = Engine_Api::_()->getItem('core_tag_map',$params["tag"]);
    if(!$tagmap){
      return $this->setNoRender();
    }
    
    $tag = $this->view->tag = Engine_Api::_()->getItem('core_tag',$tagmap->tag_id);
    if(!$tag){
      return $this->setNoRender();
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();

    $videoTable = Engine_Api::_()->getDbTable('videos','sesvideo');
    $tmTable = Engine_Api::_()->getDbtable('TagMaps', 'core');
    $tmName = $tmTable->info('name');
    $tableName = $videoTable->info('name');
    $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
    $tableLocationName = $tableLocation->info('name');
    $select = $videoTable->select()
            ->from($tableName)
            ->where($tableName . '.video_id != ?', '');
    $select->setIntegrityCheck(false);
    $select
      ->joinLeft($tmName, "$tmName.resource_id = $tableName.video_id", NULL)
      ->where($tmName . '.resource_type = ?', 'video')
      ->where($tmName . '.tag_id = ?', $tag->getIdentity());
    $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
    WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
    $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$tableName.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
    $select->where($tableName . '.status = ?', 1);
    $select->where($tableName . '.approve = ?', 1);
    $select->where($tableName.'.is_tickvideo = 1');
    $select->where($tableName.'.type = 3 OR '.$tableName.'.type = "upload"');
    $select = $select->order('video_id DESC');
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($this->_getParam('limit',10));
    $paginator->setCurrentPageNumber($this->_getParam('page',1));
    $this->view->page = $this->_getParam('page',1);
      // Do not render if nothing to show
      
    if ($paginator->getTotalItemCount() == 0){
      //   return $this->setNoRender();
    }

	}
}
