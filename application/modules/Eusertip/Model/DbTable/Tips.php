<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Tips.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_Model_DbTable_Tips extends Engine_Db_Table {

  protected $_rowClass = 'Eusertip_Model_Tip';

  public function getEnabledTips($user_id = null) {
  
    $select = $this->select()->where('enabled = ?', true);
    
    if(!empty($user_id))
      $select->where('user_id =?', $user_id);
    return $this->fetchAll($select);
  }
}
