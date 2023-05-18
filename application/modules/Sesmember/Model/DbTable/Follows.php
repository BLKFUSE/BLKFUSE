<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Follows.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Follows extends Engine_Db_Table {

  protected $_rowClass = "Sesmember_Model_Follow";
  protected $_name = "sesmember_follows";

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

}
