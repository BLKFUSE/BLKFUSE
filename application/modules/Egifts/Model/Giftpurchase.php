<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Giftpurchase.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_Giftpurchase extends Core_Model_Item_Abstract
{
  // Properties

 // protected $_parent_type = 'user';

  //protected $_owner_type = 'user';

 // protected $_parent_is_owner = true;

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   **/
	protected $_searchTriggers = false;
	protected $_statusChanged;
	protected $_modifiedTriggers = false;
	protected $_fromadmin = false;
	function fromAdmin(){
		$this->_fromadmin = true;
	}

	function getTransation($state = "pending"){
		$table = Engine_Api::_()->getDbTable('giftpurchases','egifts');
		if($this->_fromadmin) {
			$select = $table->select()->where('giftpurchase_id =?', $this->giftpurchase_id);
			$transaction = $table->fetchRow($select);
			return $transaction;
		}else{
			$this->state = $state;
		}
	}

	public function onFailure()
	{
		$this->_statusChanged = false;
		if( $this->state != 'pending' ) {
			$this->state = 'failed';
			$this->_statusChanged = true;
			if(!$this->_fromadmin)
				Engine_Api::_()->getDbTable('giftpurchases','egifts')->update(array('state' => 'failed'), array('giftpurchase_id = ?' => $this->giftpurchase_id));
			$transaction = $this->getTransation('failed');
//            if($transaction){
//                $transaction->state = '';
//                $transaction->save();
//            }
		}
		$this->save();
		return $this;
	}
	public function getTitle() {
		return $this->gift_title;
	}

}
