<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egift
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Remainingpayments.php 2019-08-28 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Model_DbTable_Remainingpayments extends Engine_Db_Table {
	
	protected $_name = 'egifts_remainingpayments';
	
	public function getGiftRemainingAmount($params = array()){
    $tabeleName = $this->info('name');
    $select = $this->select()->from($tabeleName);
    if(isset($params['user_id']))
      $select->where('user_id =?',$params['user_id']);
    return $this->fetchRow($select);
	}
}
