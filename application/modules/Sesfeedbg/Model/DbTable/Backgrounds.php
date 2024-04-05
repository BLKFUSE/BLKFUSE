<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedbg
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Backgrounds.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesfeedbg_Model_DbTable_Backgrounds extends Engine_Db_Table {

  protected $_rowClass = 'Sesfeedbg_Model_Background';
  public function getPaginator($params = array()) {
    return Zend_Paginator::factory($this->getBackgrounds($params));
  }
  
  public function getBackgrounds($params = array()) {

    $select = $this->select();
    
    if(!empty($params['admin'])) {
      $select->where('enabled =?', 1)
        ->where('starttime <= DATE(NOW())')
        ->where("(enableenddate = 0 || endtime IS NULL OR endtime = '0000-00-00' OR endtime >= DATE(NOW() )) ");
    }
    
    if(isset($params['featured']) && !empty($params['featured'])) {
      $select->where('featured =?', 1);
    }

    if(isset($params['featuredbgIds']) && !empty($params['featuredbgIds'])) {
      $select->where('background_id NOT IN (?)', $params['featuredbgIds']);
    }
    
    $select->order('order ASC');
    if(isset($params['sesfeedbg_limit_show']) && !empty($params['sesfeedbg_limit_show']))
      $select->limit($params['sesfeedbg_limit_show']);

    if(!empty($params['fetchAll']))
      return $this->fetchAll($select);
    return $select;
  }
}
