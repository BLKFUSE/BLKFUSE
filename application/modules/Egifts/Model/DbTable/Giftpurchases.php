<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Giftpurchases.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
class Egifts_Model_DbTable_Giftpurchases extends Core_Model_Item_DbTable_Abstract {

  /**
   * Gets a select object for the user's datingad entries
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Db_Table_Select
   */
	protected $_rowClass = "Egifts_Model_Giftpurchase";

	public function manageOrders($params = array()) {
    
    $userTableName = Engine_Api::_()->getItemTable('user')->info('name');
    $giftorderTableName = Engine_Api::_()->getDbtAble('giftorders', 'egifts')->info('name');
    
		$orderTableName = $this->info('name');
		
		$select = $this->select()
                  ->setIntegrityCheck(false)
                  ->from($orderTableName,array('*',"(total_amount) AS totalAmountSale"))
                  ->joinLeft($userTableName, "$orderTableName.owner_id = $userTableName.user_id", array())
                  ->joinLeft($giftorderTableName, "$giftorderTableName.giftpurchase_id = $orderTableName.giftpurchase_id", array('gift_id'))
                  ->where("state IN (?)", array('complete', 'active'));
    
    if(isset($params['action']) && $params['action'] == 'myorders') {
      $select->where($orderTableName.'.owner_id =?',$params['user_id']);
    }

		if (!empty($params['order_id']))
				$select->where($orderTableName . '.giftpurchase_id =?', $params['order_id']);
				
		if (!empty($params['order_max']))
				$select->having("totalAmountSale <=?", $params['order_max']);
				
		if (!empty($params['order_min']))
				$select->having("totalAmountSale >=?", $params['order_min']);
				
		if(!empty($params['date_to']) && !empty($params['date_from'])) {
			$select->where("DATE($orderTableName.created_date) BETWEEN '".$params['date_to']."' AND '".$params['date_from']."'");
    } else {
			if (!empty($params['date_to']))
        $select->where("DATE($orderTableName.created_date) >=?", $params['date_to']);
			if (!empty($params['date_from']))
        $select->where("DATE($orderTableName.created_date) <=?", $params['date_from']);	
		}
		
		$select->order($orderTableName.'.giftpurchase_id DESC');
		return $select;
	}
}
