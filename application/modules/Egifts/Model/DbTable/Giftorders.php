<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Giftorders.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_DbTable_Giftorders extends Core_Model_Item_DbTable_Abstract
{
	  protected $_rowClass = "Egifts_Model_Giftorder";
	  public function getGifts($giftpurchase_id) {
	    $select = $this->select()
	            ->from($this->info('name'),'*')
	            ->where('status = 1')
	            ->where('giftpurchase_id =?', $giftpurchase_id);
	    return $this->fetchAll($select);
	  }
	  
	  public function getGiftOrder($giftpurchase_id) {
	    $select = $this->select()
	            ->from($this->info('name'),'*')
	            ->where('status = 1')
	            ->where('giftpurchase_id =?', $giftpurchase_id);
	    return $this->fetchRow($select);
	  }
	  
  	public function getGiftOrderSelect($params = array())
	{	
		$giftPurchaseTable = Engine_Api::_()->getDbtable('giftpurchases', 'egifts');
		$giftPurchaseTableName = $giftPurchaseTable->info('name');

		$giftTable = Engine_Api::_()->getDbtable('gifts', 'egifts');
		$giftTableName = $giftTable->info('name');

		$giftOderTableName = $this->info('name');
		$select = $this->select()->setIntegrityCheck(false)
			->from($giftOderTableName)
			->joinLeft($giftPurchaseTableName,"$giftPurchaseTableName.giftpurchase_id =  $giftOderTableName.giftpurchase_id",array('owner_id as sender_id','message','total_amount','purchase_user_id','created_date','giftpurchase_id','is_private'));
		$select->joinLeft($giftTableName,"$giftTableName.gift_id =  $giftOderTableName.gift_id",null);
		if(isset($params['purchase_user_id'])){
			$select->where($giftPurchaseTableName.'.purchase_user_id = ?', $params['purchase_user_id']);
		}
		if(isset($params['owner_id'])){
			$select->where($giftPurchaseTableName.'.owner_id = ?', $params['owner_id']);
		}
		$select->where($giftPurchaseTableName.".gateway_transaction_id IS NOT NULL")
			->order($giftOderTableName.".giftpurchase_id DESC");
		return $select;
	}
	public function getGiftOrderPaginator($params = array())
	{
		$paginator = Zend_Paginator::factory($this->getGiftOrderSelect($params));
		if( !empty($params['page']) )
		{
			$paginator->setCurrentPageNumber($params['page']);
		}
		if( !empty($params['limit']) )
		{
			$paginator->setItemCountPerPage($params['limit']);
		}
		if( empty($params['limit']) )
		{
			$page = 20;
			$paginator->setItemCountPerPage($page);
		}
		return $paginator;
	}
}
