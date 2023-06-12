<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Recentlyviewitmes.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_DbTable_Recentlyviewitems extends Engine_Db_Table {
  protected $_rowClass = 'Egifts_Model_Recentlyviewitem';

  public function getitem($params = array()) {
    $itemTable = Engine_Api::_()->getItemTable('egifts_gift');
    $fieldName = 'gift_id';
    $itemTableName = $itemTable->info('name');
    $recentViewTableName = $this->info('name');
    $subquery = $this->select()->from($this->info('name'), array('MAX(creation_date) as maxcreadate',"resource_type","resource_id"))->group($this->info('name') . ".resource_id")->where($this->info('name') . '.resource_type =?', $params['type']);
    
    if ($params['criteria'] == 'by_me') {
      $subquery->where($this->info('name') . '.owner_id =?', Engine_Api::_()->user()->getViewer()->getIdentity());
    } else if ($params['criteria'] == 'by_myfriend') {
      /* friends array */
      $friendIds = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();
      if (engine_count($friendIds) == 0)
        return array();
      $subquery->where($this->info('name') . ".owner_id IN ('" . implode(',', $friendIds) . "')");
    }
    $select = $this->select()
            ->from(array('engine4_egifts_recentlyviewitems' => $subquery))
            ->where($recentViewTableName . '.resource_type = ?', $params['type'])
            ->setIntegrityCheck(false)
            ->order('maxcreadate DESC')
            ->group($this->info('name') . '.resource_id');

    $select->joinLeft($itemTableName, $itemTableName . ".$fieldName =  " . $this->info('name') . '.resource_id', array('*'));
    $select->where($itemTableName . '.' . $fieldName . ' != ?', '');
    if (isset($params['limit'])) {
      $select->limit($params['limit']);
    }
    if(isset($params['showdefaultalbum']) && empty($params['showdefaultalbum'])) {
      $select->where($itemTableName.'.type IS NULL');
    }
    return $this->fetchAll($select);
  }
}
