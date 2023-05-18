<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Packages.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Model_DbTable_Packages extends Engine_Db_Table {

  protected $_rowClass = 'Epaidcontent_Model_Package';

  public function getEnabledPackages($user_id = null) {
  
    $select = $this->select()->where('enabled = ?', true);
    
    if(!empty($user_id))
      $select->where('user_id =?', $user_id);
    return $this->fetchAll($select);
  }
}
