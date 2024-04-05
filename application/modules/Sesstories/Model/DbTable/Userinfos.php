<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Userinfos.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_Model_DbTable_Userinfos extends Core_Model_Item_DbTable_Abstract {
  protected $_rowClass = "Sesstories_Model_Userinfo";
  protected $_name = "sesstories_userinfos";
  
  public function getAllUserHaveStories($params = array()) {

    // Get an array of friend ids
    $viewer = Engine_Api::_()->user()->getViewer();
    $userTable = Engine_Api::_()->getItemTable('user');
    $selectFriend = $viewer->membership()->getMembersSelect('user_id');
    $friends = $userTable->fetchAll($selectFriend);
    // Get stuff
    $ids = array();
    foreach( $friends as $friend ) {
      $ids[] = $friend->user_id;
    }
    
    $storiesTable = Engine_Api::_()->getDbTable('stories', 'sesstories');
    $storiesTableName = $storiesTable->info('name');
    
    $tableName = $this->info('name');
    $days = Engine_Api::_()->getApi('settings', 'core')->getSetting("sesstories.dayslimit",1);

    $select = $this->select()
                  ->setIntegrityCheck(false)
                  ->from($tableName)
                  ->joinRight($storiesTableName, $storiesTableName . '.owner_id =' . $tableName . '.owner_id', null)
                  ->where($tableName.'.owner_id <> ?',$params['user_id'])
                  ->where($tableName.'.view_privacy != ?','owner')
                  ->where($storiesTableName.'.creation_date >= now() - INTERVAL '.$days.' DAY')
                  ->group($storiesTableName.'.owner_id');

    //If current user have friends then friends and registered member story see
    if(engine_count($ids) > 0) {
      $select->where($tableName.'.view_privacy IN (?)', array('registered'));
      $select->orwhere($tableName.'.owner_id IN (?)', $ids);
      
    } 
    //If current user have not any friends then only registered member story see
    else {
      $select->where($tableName.'.view_privacy = ?', 'registered');
    }
    return $this->fetchAll($select);
  }
  
  public function isPrivacyExist($user_id) {

    $userinfoTableName = $this->info('name');
    return $this->select()
                ->from($userinfoTableName, 'view_privacy')
                ->where('owner_id = ?', $user_id)
                ->query()
                ->fetchColumn();
     
  }
}
