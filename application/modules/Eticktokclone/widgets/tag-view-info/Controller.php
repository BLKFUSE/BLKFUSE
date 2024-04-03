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


class Eticktokclone_Widget_TagViewInfoController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $params = $request->getParams();
    if(empty($params["tag"])){
      return $this->setNoRender();
    }

    $tagmap = $this->view->tagmap = Engine_Api::_()->getItem('core_tag_map',$params["tag"]);
    if(!$tagmap){
      return $this->setNoRender();
    }
    
    $tag = $this->view->tag = Engine_Api::_()->getItem('core_tag',$tagmap->tag_id);
    if(!$tag){
      return $this->setNoRender();
    }


    // tag videos
    $videoTable = Engine_Api::_()->getDbTable('videos','sesvideo');
    $tmTable = Engine_Api::_()->getDbtable('TagMaps', 'core');
    $tmName = $tmTable->info('name');
    $tableName = $videoTable->info('name');
    $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
    $tableLocationName = $tableLocation->info('name');
    $select = $videoTable->select()
            ->from($tableName)
            ->where($tableName . '.video_id != ?', '');

    $select
      ->joinLeft($tmName, "$tmName.resource_id = $tableName.video_id", NULL)
      ->where($tmName . '.resource_type = ?', 'video')
      ->where($tmName . '.tag_id = ?', $tag->getIdentity());

      // echo $select;die;

      $paginator = $this->view->paginator = Zend_Paginator::factory($select);
      $paginator->setItemCountPerPage(10);
      $paginator->setCurrentPageNumber(1);

  }
}
