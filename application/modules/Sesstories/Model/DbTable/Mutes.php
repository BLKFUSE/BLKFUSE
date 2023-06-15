<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Mutes.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Model_DbTable_Mutes extends Engine_Db_Table {

  protected $_rowClass = "Sesstories_Model_Mute";

  public function getAllMutesMembers($params = array()) {
    $select = $this->select()
    ->from($this->info('name'),array('*'))
    ->where('user_id =?',$params['user_id'])
    ->group('resource_id');
    $results = $this->fetchAll($select);
    $mutesUserIds = array();
    if(engine_count($results) > 0) {
      foreach($results as $result) {
        $mutesUserIds[] = $result->resource_id;
      }
    }
    return $mutesUserIds;
  }

  public function getMuteStory($resource_id)
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $select = $this->select()
    ->from($this->info('name'),array('mute_id'))
    ->where('resource_id =?',$resource_id)
    ->where('user_id =?',$viewer_id);
    return $this->fetchRow($select);
  }
}
