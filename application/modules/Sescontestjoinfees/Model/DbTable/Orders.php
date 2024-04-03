<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Order.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjoinfees_Model_DbTable_Orders extends Engine_Db_Table {
  protected $_rowClass = "Sescontestjoinfees_Model_Order";
	public function getOrder($params = array()){
		$select = $this->select()->where('owner_id =?',$params['owner_id'])->where('is_delete =?',0);
		return $this->fetchAll($select);
	}
	public function getOrderStatus($order_id = ''){
		return $this->select()
								->from($this->info('name'), new Zend_Db_Expr('COUNT(*)'))
								->where('state =?', 'complete')
								->where('order_id =?',$order_id)
								->query()
								->fetchColumn();
	}
	
	public function getOrders($params = array()){
		$orderTableName = $this->info('name');	
		$contestTableName = Engine_Api::_()->getItemTable('contest')->info('name');
		$select = $this->select()->from($orderTableName)->setIntegrityCheck(false)
		 						->joinLeft($contestTableName, "$orderTableName.contest_id = $contestTableName.contest_id", array());
		$select->where('state =?','complete');
		 if(isset($params['viewer_id']))
		 	$select->where('owner_id =?',$params['viewer_id']);
		 if(isset($params['contest_id']))
		 	$select->where($orderTableName.'.contest_id =?',$params['contest_id']);
		 if(isset($params['groupBy']))	
		 	$select->group($params['groupBy']);
			if (isset($params['view_type'])) {
				$now = date("Y-m-d H:i:s");
				if ($params['view_type'] == 'current')
						$select->where("$contestTableName.endtime >= '$now'");
			  else 
						$select->where("$contestTableName.endtime < ?", $now);
        $select->order('creation_date DESC');
			}
			$paginator = Zend_Paginator::factory($select);
			if (!empty($params['page']))
					$paginator->setCurrentPageNumber($params['page']);
			if (!empty($params['limit']))
					$paginator->setItemCountPerPage($params['limit']);
			return $paginator;
	}
	public function getTotalTicketSoldCount($params = array()){
		$orderTableName =  $this->info('name');
	  return $this->select()
					  ->from($orderTableName, new Zend_Db_Expr('SUM(total_tickets)'))
					  ->where('contest_id =?', $params['contest_id'])
						->where('state =?',$params['state'])
					  ->limit(1)
					  ->query()
					  ->fetchColumn();
	}
	public function getSaleStats($params = array()){
		 $select = $this->select()
                ->from($this->info('name'), array('total_amount'=>new Zend_Db_Expr("sum(total_amount)"),'totalAmountSale' => new Zend_Db_Expr("(sum(total_amount))")))
                ->where("contest_id =?", $params['contest_id'])
                ->where("state = 'complete'");
		if ($params['stats'] == 'month')
          $select->where("YEAR(creation_date) = YEAR(NOW()) AND MONTH(creation_date) = MONTH(NOW())");
    if ($params['stats'] == 'week')
          $select->where("YEARWEEK(creation_date) = YEARWEEK(CURRENT_DATE)");
		if ($params['stats'] == 'today')
          $select->where("DATE(creation_date) = DATE(NOW())");
    return $select->query()->fetchColumn();
	}
	public function getContestStats($params = array()) {
	 $select = $this->select()
		->from($this->info('name'), array('totalOrder'=> new Zend_Db_Expr("COUNT(order_id)"),"commission_amount" => new Zend_Db_Expr("SUM(commission_amount)"), 'totalAmountSale' => new Zend_Db_Expr("(sum(total_amount))")))
		->where('contest_id =?',$params['contest_id'])
		->where("state = 'complete'");
		return $select->query()->fetch();
	}
	public function manageOrders($params = array()){
		$orderTableName = $this->info('name');
		$select = $this->select()
		->from($this->info('name'),array('*',"(total_amount) AS totalAmountSale"))
		->where('contest_id =?',$params['contest_id'])
		->where("state = 'complete'");
		$userTableName = Engine_Api::_()->getItemTable('user')->info('name');
		$select ->setIntegrityCheck(false)->joinLeft($userTableName, "$orderTableName.owner_id = $userTableName.user_id", array());
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
		if(!empty($params['date_to']) && !empty($params['date_from']))
			$select->where("DATE($orderTableName.creation_date) BETWEEN '".$params['date_to']."' AND '".$params['date_from']."'");
		else{
			if (!empty($params['date_to']))
					$select->where("DATE($orderTableName.creation_date) >=?", $params['date_to']);
			if (!empty($params['date_from']))
					$select->where("DATE($orderTableName.creation_date) <=?", $params['date_from']);	
		}
		$select->order('order_id DESC');
		return $select;
	}
	public function getReportData($params = array()){
		$orderTableName = $this->info('name');
		$select = $this->select()->from($orderTableName,array('totalAmountSale' => new Zend_Db_Expr("sum($orderTableName.total_amount)"),'total_orders' => new Zend_Db_Expr("SUM(1)"),"$orderTableName.creation_date"));

		if(isset($params['contest_id']))	
			$select->where($orderTableName.'.contest_id =?',$params['contest_id']);
		$select->where($orderTableName.'.state =?','complete');
		if(isset($params['type'])){
			if($params['type'] == 'month'){
				$select->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m') <= ?", $params['enddate'])
							 ->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m') >= ?", $params['startdate'])
							 ->group("$orderTableName.contest_id")
							 ->group("YEAR($orderTableName.creation_date)")
							 ->group("MONTH($orderTableName.creation_date)");
			}else{
				$select->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m-%d') <= ?", $params['enddate'])
							 ->where("DATE_FORMAT(" . $orderTableName . " .creation_date, '%Y-%m-%d') >= ?", $params['startdate'])
							 ->group("$orderTableName.contest_id")
							 ->group("YEAR($orderTableName.creation_date)")
							 ->group("MONTH($orderTableName.creation_date)")
							 ->group("DAY($orderTableName.creation_date)");
			}
		}
		return $this->fetchAll($select);
	}
}