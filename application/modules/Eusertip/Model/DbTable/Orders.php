<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Orders.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_Model_DbTable_Orders extends Engine_Db_Table {

  protected $_rowClass = "Eusertip_Model_Order";
  
 	public function getViewerOrder($params = array()) {

		$tableName = $this->info('name');
		$select = $this->select()
                  ->from($tableName)
                  ->where('owner_id =?', $params['owner_id'])
                  ->where('tip_owner_id =?', $params['tip_owner_id'])
                  ->limit(1);
		if(empty($params['noCondition'])) {
			$select->where('state = "pending" || state = "complete" || state = "okay" || state = "active" ');	
		} else if(!empty($params['noCondition'])) {
      $select->where('state = "complete" || state = "okay" || state = "active"');
		}
		return $this->fetchRow($select);
	}
	
 	public function getViewerAllOrders($params = array()) {

		$tableName = $this->info('name');
		$select = $this->select()
                  ->from($tableName)
                  ->where('order_id <> ?', $params['order_id'])
                  ->where('tip_owner_id = ?', $params['tip_owner_id'])
                  ->where('owner_id = ?', $params['owner_id']);
		if(empty($params['noCondition'])) {
			$select->where('state = "pending" || state = "complete" || state = "okay" || state = "active" ');	
		} else if(!empty($params['noCondition'])) {
      $select->where('state = "complete" || state = "okay" || state = "active"');
		}
		return $this->fetchAll($select);
	}
  
	public function getSaleStats($params = array()) {
	
    $select = $this->select()
                  ->from($this->info('name'), array('total_amount'=>new Zend_Db_Expr("sum(total_amount)"),'totalAmountSale' => new Zend_Db_Expr("(sum(total_amount))")))
                  ->where("tip_owner_id =?", $params['tip_owner_id'])
                  ->where("state = 'complete'");
		if ($params['stats'] == 'month')
      $select->where("YEAR(creation_date) = YEAR(NOW()) AND MONTH(creation_date) = MONTH(NOW())");
    if ($params['stats'] == 'week')
      $select->where("YEARWEEK(creation_date) = YEARWEEK(CURRENT_DATE)");
		if ($params['stats'] == 'today')
      $select->where("DATE(creation_date) = DATE(NOW())");
    return $select->query()->fetchColumn();
	}
	
	public function tipStatsSale($params = array()) {
	
    $select = $this->select()
                  ->from($this->info('name'), array('totalOrder'=> new Zend_Db_Expr("COUNT(order_id)"),"commission_amount" => new Zend_Db_Expr("SUM(commission_amount)"), 'totalAmountSale' => new Zend_Db_Expr("(sum(total_amount))")))
                  ->where('tip_owner_id =?',$params['tip_owner_id'])
                  ->where("state = 'complete'");
		return $select->query()->fetch();
	}
	
	public function manageOrders($params = array()) {
    
    $userTableName = Engine_Api::_()->getItemTable('user')->info('name');
		$orderTableName = $this->info('name');
		
		$select = $this->select()
                  ->setIntegrityCheck(false)
                  ->from($this->info('name'),array('*',"(total_amount) AS totalAmountSale"))
                  ->joinLeft($userTableName, "$orderTableName.owner_id = $userTableName.user_id", array())
                  ->where("state = 'complete'");
    
    if(isset($params['action']) && $params['action'] == 'myorders') {
      $select->where('owner_id =?',$params['user_id']);
    } else {
      $select->where('tip_owner_id =?',$params['user_id']);
    }

		if (!empty($params['order_id']))
				$select->where($orderTableName . '.order_id =?', $params['order_id']);
				
		if (!empty($params['order_max']))
				$select->having("totalAmountSale <=?", $params['order_max']);
				
		if (!empty($params['order_min']))
				$select->having("totalAmountSale >=?", $params['order_min']);
				
		if (!empty($params['commision_min']))
				$select->where("$orderTableName.commission_amount >=?", $params['commision_min']);
				
		if (!empty($params['commision_max']))
				$select->where("$orderTableName.commission_amount <=?", $params['commision_max']);
				
		if (!empty($params['gateway']))
				$select->where($orderTableName . '.gateway_type = ? ', $params['gateway']);
				
		if (!empty($params['email']))
				$select->where($userTableName . '.email  LIKE ?', '%' . $params['email'] . '%');
				
		if (!empty($params['buyer_name']))
				$select->where($userTableName . '.displayname  LIKE ?', '%' . $params['buyer_name'] . '%');
				
    if (!empty($params['owner_name']))
				$select->where($userTableName . '.displayname  LIKE ?', '%' . $params['owner_name'] . '%');
				
		if(!empty($params['date_to']) && !empty($params['date_from'])) {
			$select->where("DATE($orderTableName.creation_date) BETWEEN '".$params['date_to']."' AND '".$params['date_from']."'");
    } else {
			if (!empty($params['date_to']))
        $select->where("DATE($orderTableName.creation_date) >=?", $params['date_to']);
			if (!empty($params['date_from']))
        $select->where("DATE($orderTableName.creation_date) <=?", $params['date_from']);	
		}
		
		$select->order('order_id DESC');
		return $select;
	}
	
	public function getReportData($params = array()) {
	
		$orderTableName = $this->info('name');
		$select = $this->select()->from($orderTableName,array('totalAmountSale' => new Zend_Db_Expr("sum($orderTableName.total_amount)"),'total_orders' => new Zend_Db_Expr("SUM(1)"),"$orderTableName.creation_date"));

		if(isset($params['tip_owner_id']))
      $select->where($orderTableName.'.tip_owner_id =?',$params['tip_owner_id']);
    
    $select->where($orderTableName.'.state =?','complete');
    
		if(isset($params['type'])) {
			if($params['type'] == 'month') {
				$select->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m') <= ?", $params['enddate'])
              ->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m') >= ?", $params['startdate'])
              ->group("$orderTableName.tip_owner_id")
              ->group("YEAR($orderTableName.creation_date)")
              ->group("MONTH($orderTableName.creation_date)");
			} else {
				$select->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m-%d') <= ?", $params['enddate'])
              ->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m-%d') >= ?", $params['startdate'])
              ->group("$orderTableName.tip_owner_id")
              ->group("YEAR($orderTableName.creation_date)")
              ->group("MONTH($orderTableName.creation_date)")
              ->group("DAY($orderTableName.creation_date)");
			}
		}
		return $this->fetchAll($select);
	}
}
