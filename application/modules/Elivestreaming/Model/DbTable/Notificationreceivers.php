<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Notificationreceivers.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Elivestreaming_Model_DbTable_Notificationreceivers extends Engine_Db_Table
{
  protected $_rowClass = "Elivestreaming_Model_Notificationreceiver";

  public function getAllnotificationReceivers($params = array())
  {
    $table = Engine_Api::_()->getItemTable('elivestreaming_notificationreceiver');
    $tableName = $table->info('name');

    $select = $table
      ->select()
      ->from($tableName)
      ->where('elivehost_id = ?', $params['elivehost_id']);
    return $select;
  }
}
