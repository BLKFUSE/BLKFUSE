<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Notificationreceiver.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_Model_Notificationreceiver extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;
  protected function _delete()
  {
    if ($this->_disableHooks)
      return;
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->query("DELETE FROM engine4_activity_notifications WHERE notification_id = " . $this->notification_id);
  }
}
