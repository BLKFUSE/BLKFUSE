<?php

class Egifts_Model_DbTable_Userpayrequests extends Engine_Db_Table {

  protected $_name = 'egifts_userpayrequests';
  protected $_rowClass = "Egifts_Model_Userpayrequest";

  public function getEgiftsRequests($params = array()) {
  
    $tabeleName = $this->info('name');
    $select = $this->select()->from($tabeleName);
    
    if (isset($params['owner_id']))
      $select->where('owner_id =?', $params['owner_id']);
      
		if(isset($params['isPending']) && $params['isPending']){
			$select->where('state =?', 'pending');
		}	else {
		if (isset($params['state']) && $params['state'] == 'complete')
			$select->where('state =?', $params['state']);
		}
		
		$select->order('userpayrequest_id DESC');
    $select->where('is_delete	= ?', '0');

    return $this->fetchAll($select);
  }
  
  public function adminTotalCommission() {
		$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $count = $this->select()->from($this->info('name'), array("SUM(total_commission_amount)"))->where('state =?', "complete")->where('owner_id =?', $viewer_id);
    return $count->query()->fetchColumn();
  }
}
