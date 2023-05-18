<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Api_Core extends Core_Api_Abstract {

  public function getUsersPaginator($params = array()) {

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = (int)$viewer->getIdentity();

    if(empty($params['users'])) {
      $params['users'][] = $viewer_id;
    }
    
    $searchTable = Engine_Api::_()->fields()->getTable('user', 'search');
    $searchTableName = $searchTable->info('name');
    
    $userTable = Engine_Api::_()->getDbTable('users', 'user');
    $userTableName = $userTable->info('name');

    $select = $userTable->select()
            ->from($userTableName)
            ->setIntegrityCheck(false)
            ->joinLeft($searchTableName, "`{$searchTableName}`.`item_id` = `{$userTableName}`.`user_id`", null)
            ->where("$userTableName.user_id <> ?", $viewer_id)
            ->order('RAND()');

    $getAlreadyLikedViewer = Engine_Api::_()->getDbTable('likes', 'edating')->getAlreadyLikedViewer();
    if(engine_count($getAlreadyLikedViewer) > 0) {
      $select->where("$userTableName.user_id NOT IN(?)", $getAlreadyLikedViewer);
    }
    
    if (!empty($params['users'])) {
      $select->where("$userTableName.user_id NOT IN(?)", $params['users']);
    }

    if (!empty($params['extra'])) {
      extract($params['extra']); // is_online, has_photo, submit
    }

    // Build the photo and is online part of query
    if (isset($has_photo) && !empty($has_photo)) {
      $select->where($userTableName . '.photo_id != ?', "0");
      $searchDefault = false;
    }
    
    $getHideSearchMemebrs = $this->getHideSearchMemebrs();
    if(engine_count($getHideSearchMemebrs) > 0) {
      $select->where("$userTableName.user_id NOT IN(?)", $getHideSearchMemebrs);
    }

    if (isset($is_online) && !empty($is_online)) {
      $select->joinRight("engine4_user_online", "engine4_user_online.user_id = `{$userTableName}`.user_id", null)
          ->group("engine4_user_online.user_id")
          ->where($userTableName . '.user_id != ?', "0");
      $searchDefault = false;
    }

    if (isset($params['displayname']) && !empty($params['displayname'])) {
      $select->where("(`{$userTableName}`.`username` LIKE ? || `{$userTableName}`.`displayname` LIKE ?)", "%{$params['displayname']}%");
      $searchDefault = false;
    }

    // Process options
    $tmp = array();
    $originalOptions = $params;
    foreach ($params as $k => $v) {
      if (null == $v || '' == $v || (is_array($v) && engine_count(array_filter($v)) == 0)) {
        continue;
      } elseif (false !== strpos($k, '_field_')) {
        list($null, $field) = explode('_field_', $k);
        $tmp['field_' . $field] = $v;
      } elseif (false !== strpos($k, '_alias_')) {
        list($null, $alias) = explode('_alias_', $k);
        $tmp[$alias] = $v;
      } else {
        $tmp[$k] = $v;
      }
    }
    $options = $tmp;

    // Build search part of query
    $searchParts = Engine_Api::_()->fields()->getSearchQuery('user', $options);
    foreach ($searchParts as $k => $v) {
      if (strpos($k, 'FIND_IN_SET') !== false) {
        $select->where("{$k}", $v);
        continue;
      }

      $select->where("`{$searchTableName}`.{$k}", $v);

      if (isset($v) && $v != "") {
        $searchDefault = false;
      }
    }

    return Zend_Paginator::factory($select);
  }

  public function getHideSearchMemebrs() {
  
    $settingTable = Engine_Api::_()->getDbTable('settings', 'edating');
    $select = $settingTable->select()
        ->from($settingTable, array('user_id'))
        ->where('is_search = ?', 0);
    $data = array();
    foreach( $select->query()->fetchAll() as $item ) {
      $data[] = $item['user_id'];
    }
    return $data;
  }
}
