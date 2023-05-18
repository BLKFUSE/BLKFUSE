<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Remainingpayments.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_Model_DbTable_Remainingpayments extends Engine_Db_Table {

  protected $_name = 'eusertip_remainingpayments';
  
	public function getRemainingAmount($params = array()) {
	
    $tabeleName = $this->info('name');
    $select = $this->select()->from($tabeleName);
    if(isset($params['user_id']))
      $select->where('user_id =?',$params['user_id']);	 
    return $this->FetchRow($select);
	}
}
