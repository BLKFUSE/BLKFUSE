<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Userpayrequests.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Epaidcontent_Model_DbTable_Userpayrequests extends Engine_Db_Table {

  protected $_name = 'epaidcontent_userpayrequests';
  protected $_rowClass = "Epaidcontent_Model_Userpayrequest";

  public function getPaymentRequests($params = array()) {
  
    $tabeleName = $this->info('name');
    $select = $this->select()->from($tabeleName);
    if (isset($params['owner_id']))
      $select->where('owner_id =?', $params['owner_id']);
		if(isset($params['isPending']) && $params['isPending']){
			$select->where('state =?', 'pending');
		}else{
    if (isset($params['state']) && $params['state'] == 'complete')
      $select->where('state =?', $params['state']);
    else  if (isset($params['state']) && $params['state'] == 'both')
      $select->where('state = "complete" || state = "cancelled"');
		}
		$select->order('userpayrequest_id DESC');
    $select->where('is_delete	= ?', '0');
    return $this->fetchAll($select);
  }
}
