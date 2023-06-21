<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Favourites.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_DbTable_Favourites extends Engine_Db_Table {
  protected $_rowClass = "Egifts_Model_Favourite";
  public function isFavourite($params = array()) {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $status = $this->select()
            ->where('resource_type = ?', $params['resource_type'])
            ->where('resource_id = ?', $params['resource_id'])
            ->where('owner_id = ?', $viewer_id)
            ->query()
            ->fetchColumn();
    if ($status)
      return 1;
    else
      return 0;
  }
  public function getAllFavMembers($resourceId) {
    $select = $this->select()
            ->from($this->info('name'), 'owner_id')
            ->where('resource_id = ?', $resourceId);
    return $this->fetchAll($select);
  }
  public function getItemfav($resource_type, $itemId) {
    $tableMainFav = $this->info('name');
    $select = $this->select()->from($tableMainFav)->where('resource_type =?', $resource_type)->where('owner_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('resource_id =?', $itemId);
    return $this->fetchRow($select);
  }
  public function getFavourites($params = array()) {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $select = $this->select()
            ->from($this->info('name'))
            ->where('resource_type =?', $params['resource_type'])
            ->where('owner_id =?', $viewer_id);
    return Zend_Paginator::factory($select);
  }

}
