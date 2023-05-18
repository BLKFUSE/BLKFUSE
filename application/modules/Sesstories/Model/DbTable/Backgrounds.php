<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Backgrounds.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Model_DbTable_Backgrounds extends Engine_Db_Table {

  protected $_rowClass = 'Sesstories_Model_Background';
  public function getPaginator($params = array()) {
    return Zend_Paginator::factory($this->getBackgrounds($params));
  }
  
  public function getBackgrounds($params = array()) {

    $select = $this->select();
    
    if(!empty($params['admin'])) {
      $select->where('enabled =?', 1);
    }

    if(isset($params['featuredbgIds']) && !empty($params['featuredbgIds'])) {
      $select->where('background_id NOT IN (?)', $params['featuredbgIds']);
    }
    
    $select->order('order ASC');
      
    if(isset($params['sesstories_limit_show']) && !empty($params['sesstories_limit_show']))
      $select->limit($params['sesstories_limit_show']);

    if(!empty($params['fetchAll']))
      return $this->fetchAll($select);
    return $select;
  }
}
