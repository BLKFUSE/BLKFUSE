<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Recentlyviewitems.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Recentlyviewitems extends Engine_Db_Table {

  protected $_name = 'sescontest_recentlyviewitems';
  protected $_rowClass = 'Sescontest_Model_Recentlyviewitem';

  public function getitem($params = array()) {
    $itemTable = Engine_Api::_()->getItemTable('contest');
    $itemTableName = $itemTable->info('name');
    $recentViewTableName = $this->info('name');
    $fieldName = 'contest_id';
    $subquery = $this->select()->from($this->info('name'), array('*', 'MAX(creation_date) as maxcreadate'))->group($this->info('name') . ".resource_id")->where($this->info('name') . '.resource_type =?', $params['type']);
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
            ->from(array('engine4_sescontest_recentlyviewitems' => $subquery))
            ->where($recentViewTableName . '.resource_type = ?', $params['type'])
            ->setIntegrityCheck(false)
            ->order('maxcreadate DESC')
            ->where($itemTableName . '.contest_id != ?', '')
            ->group($this->info('name') . '.resource_id');

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.other.modulecontests', 1)) {
      $select->where($itemTableName . '.resource_type IS NULL')
              ->where($itemTableName . '.resource_id =?', 0);
    }
    $select->joinLeft($itemTableName, $itemTableName . ".$fieldName =  " . $this->info('name') . '.resource_id', array('*'));
    $select->where($itemTableName . '.' . $fieldName . ' != ?', '');
    $select->where($itemTableName . '.contest_id != ?', '');
    $select->where($itemTableName . '.search != ?', '0');
    if (isset($params['limit'])) {
      $select->limit($params['limit']);
    }
    return $this->fetchAll($select);
  }

}
