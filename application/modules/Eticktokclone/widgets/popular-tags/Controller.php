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

class Eticktokclone_Widget_PopularTagsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $tableName = Engine_Api::_()->getDbTable("tags",'core');
    $mapTableName = $tableName->getMapTable()->info('name');
    $select = $tableName->select()
        ->setIntegrityCheck(false)
        ->from($tableName->info('name'))
        ->join($mapTableName, $tableName->info('name') . '.tag_id=' . $mapTableName . '.tag_id', array('count(*) as tagmap_count','tagmap_id'))
        ->where("resource_type = ?",'video')
        ->order('tag_count desc')
        ->order('modified_date desc')
        ->group('tag_id')
        ->limit($limit);
    $this->view->hashtagMaps = $hashtagMaps = $tableName->fetchAll($select);
    if(engine_count($hashtagMaps) == 0)
      return $this->setNoRender();
  }
}
