<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Stories.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_Model_DbTable_Stories extends Core_Model_Item_DbTable_Abstract {
  protected $_rowClass = "Sesstories_Model_Story";
  protected $_name = "sesstories_stories";
  public function getUserStories($params = array()) {
    $select = $this->select()
							     ->from($this->info('name'),array('*','COUNT("story_id") as count'));
    if(!empty($params['owner_id']))
      $select->where('owner_id =?',$params['owner_id']);
    //get 24 hour previous posts only
    if(!empty($params['groupby']))
      $select->group('owner_id');

    $days = Engine_Api::_()->getApi('settings', 'core')->getSetting("sesstories.dayslimit",1);
    $select->where('creation_date >= now() - INTERVAL '.$days.' DAY');

    return $this->fetchAll($select);
  }

    public function getAllUserHaveStories($params = array()) {
    $param = array();
    $select = $this->select()
                  ->from($this->info('name'),array('*','COUNT("story_id") as count'))
                  ->where('owner_id <> ?',$params['user_id'])
                  ->group('owner_id');
    $getAllMutesMembers = Engine_Api::_()->getDbTable('mutes', 'sesstories')->getAllMutesMembers(array('user_id' => $params['user_id']));
    if(engine_count($getAllMutesMembers) > 0) {
      $select->where('owner_id NOT IN (?)', $getAllMutesMembers);
    }
    $days = Engine_Api::_()->getApi('settings', 'core')->getSetting("sesstories.dayslimit",1);
    //get 24 hour previous posts only
    $select->where('creation_date >= now() - INTERVAL '.$days.' DAY');
    $select = $this->getItemsSelect($param, $select);

    return $this->fetchAll($select);
  }

    public function getAllStories($user_id, $userarchivedstories = 0, $highlight = 0) {
        $param = array();
        $table = Engine_Api::_()->getDbtable('stories', 'sesstories');
        $tableName = $table->info('name');
        $days = Engine_Api::_()->getApi('settings', 'core')->getSetting("sesstories.dayslimit",1);
        $endTime = date('Y-m-d H:i:s', strtotime("-".$days." day"));
        $currentTime = date('Y-m-d H:i:s');
        $select = $table->select()
            ->from($tableName)->where($this->info('name').'.owner_id =?', $user_id)->where($this->info('name').'.status =?', 1);
        if(!empty($highlight)) {
            $select->where($this->info('name').'.highlight =?', 1);

        }

        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
            if((!defined('_SESAPI_VERSION_ANDROID') && !defined('_SESAPI_VERSION_IOS')) || (defined('_SESAPI_VERSION_IOS') && _SESAPI_VERSION_IOS >= 2.0 && _SESAPI_PLATFORM_SERVICE == 1 && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('eioslivestreaming')) || (defined('_SESAPI_VERSION_ANDROID') && _SESAPI_VERSION_ANDROID > 3.0 && _SESAPI_PLATFORM_SERVICE == 2 && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('eandlivestreaming'))) {

            }else{
                $hostsTableName = Engine_Api::_()->getDbTable('elivehosts', 'elivestreaming')->info('name');
                $select->setIntegrityCheck(false);
                $select->joinLeft($hostsTableName, $hostsTableName . '.story_id =' . $this->info('name') . '.story_id', null)
                    ->where($hostsTableName . '.story_id IS NULL');
            }
        }

        if(empty($userarchivedstories) && empty($highlight)) {
            $select->where($tableName.".creation_date between ('$endTime') and ('$currentTime')");
            //$select = $this->getItemsSelect($param, $select);
        } elseif(!empty($userarchivedstories)) {
          $days = Engine_Api::_()->getApi('settings', 'core')->getSetting("sesstories.dayslimit",1);
            $select->where($this->info('name').'.creation_date <= now() - INTERVAL '.$days.' DAY')->order($this->info('name').'.creation_date DESC');
        }
        $select->order($this->info('name').'.story_id DESC');
        if(empty($userarchivedstories)) {
            //$select = $this->getItemsSelect($param, $select);
            return $table->fetchAll($select);
        } else {
            return $select;
        }
    }
}
