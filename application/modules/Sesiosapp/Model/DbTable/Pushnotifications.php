<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesiosapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Pushnotifications.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesiosapp_Model_DbTable_Pushnotifications extends Engine_Db_Table {
	
  public function getNotifications($param = array()) {
    $select = $this->select()
                   ->from($this->info('name'));    
    $select->order("pushnotification_id DESC");
    return $this->fetchAll($select);
  }
}