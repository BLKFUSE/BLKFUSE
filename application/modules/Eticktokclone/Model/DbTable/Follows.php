<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Follows.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Eticktokclone_Model_DbTable_Follows extends Engine_Db_Table {

  protected $_rowClass = "Eticktokclone_Model_Follow";
  protected $_name = "eticktokclone_follows";

  public function getFollowers($viewer_id) {

    $select = $this->select()
            ->from($this->info('name'), 'resource_id')
            ->where('user_id = ?', $viewer_id);
    return $this->fetchAll($select);
  }

  public function getFollowersForANF($viewer_id) {

    $select = $this->select()
            ->from($this->info('name'), 'user_id')
            ->where('resource_id = ?', $viewer_id);
    return $this->fetchAll($select);
  }
  public function following($params = array()) {
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');
    $tablenameFollow = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->info('name');
    $select = $table->select()
                ->from($memberTableName, array('user_id', 'photo_id', 'displayname', 'email'))
                ->setIntegrityCheck(false)
                ->joinLeft($tablenameFollow, $tablenameFollow . '.user_id = ' . $memberTableName . '.user_id AND ' . $tablenameFollow . '.resource_id =  ' . $params['user_id'], null)
                ->where('follow_id IS NOT NULL')
                ->where($tablenameFollow . '.user_id !=?', $params['user_id'])
                ->where($memberTableName . '.user_id IS NOT NULL');
    return Zend_Paginator::factory($select);
  }

}
