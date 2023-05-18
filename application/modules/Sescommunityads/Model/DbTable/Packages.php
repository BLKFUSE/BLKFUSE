<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Packages.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Model_DbTable_Packages extends Engine_Db_Table
{
  protected $_rowClass = 'Sescommunityads_Model_Package';
  public function getDefaultPackage(){
    return $this->select()
      ->from($this, 'package_id')
			->where('`default` =?',1)
      ->query()
      ->fetchColumn()
      ;
	}
  public function getPackage($params = array()){
		$tablename = $this->info('name');
		$select = $this->select()->from($tablename);
		//if(empty($params['default']))
			//$select->where('`default` =?',0);
		if(empty($params['enabled']))
			$select->where('enabled =?',1);
		if(isset($params['member_level']))
      $select->where('concat(",",REPLACE(REPLACE(level_id,"[",""),"]",""),",") LIKE \'%,"'.$params['member_level'].'",%\'');
		if(isset($params['show_upgrade']))
			$select->where('show_upgrade =?',1);
		if(isset($params['not_in_id']))
			$select->where('package_id !=?',$params['not_in_id']);
		if(isset($params['package_id']))
			$select->where('package_id =?',$params['package_id']);
    if(isset($params['action_id']))
			$select->where('boost_post =?',1);
		$select->where('enabled =?',1);

		//Rented Package Work
		if(isset($params['param']) && $params['param'] == 1) {
            $select->where('rentpackage <> ?', 1);
		} elseif(isset($params['param']) && $params['param'] == 2) {
            $select->where('rentpackage <> ?', 0);
		}

		return $this->fetchAll($select);
	}
  public function getEnabledPackageCount()
  {
    return $this->select()
      ->from($this, new Zend_Db_Expr('COUNT(*)'))
      ->where('enabled = ?', 1)
      ->query()
      ->fetchColumn()
      ;
  }

  public function getEnabledNonFreePackageCount()
  {
    return $this->select()
      ->from($this, new Zend_Db_Expr('COUNT(*)'))
      ->where('enabled = ?', 1)
      ->where('price > ?', 0)
			->where('`default` =?',0)
      ->query()
      ->fetchColumn()
      ;
  }
  public function cancelSubscription($params = array()) {
    $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    $packageId = $params['package_id'];
    //Start Delete package Related Data
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->query("DELETE FROM engine4_sescomunityads_orderspackages WHERE package_id = $packageId && owner_id = " . $viewerId);
    $db->query("DELETE FROM engine4_sescommunityads_transactions WHERE package_id = $packageId && owner_id = " . $viewerId);
    //End Work of Package Related Data

    Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads')->update(array('is_approved' => 0,'status'=>0), array('package_id =?' => $packageId, 'user_id =?' => $viewerId));

    $transactionTable = Engine_Api::_()->getDbTable('transactions', 'sescommunityads');
    $select = $transactionTable->select()
            ->from($transactionTable->info('name'))
            ->where('package_id =?', $packageId)
            ->where('owner_id =?', $viewerId);
    $transaction = $transactionTable->fetchRow($select);
    if (!empty($transaction->gateway_id) && !empty($transaction->gateway_profile_id) && empty($transaction->gateway_transaction_id)) {
      try {
        $gateway = Engine_Api::_()->getItem('sescommunityads_gateway', $transaction->gateway_id);
        if ($gateway) {
          $gatewayPlugin = $gateway->getPlugin();
          if (method_exists($gatewayPlugin, 'cancelAds')) {
            $gatewayPlugin->cancelAds($transaction->gateway_profile_id);
          }
        }
      } catch (Exception $e) {
        // Silence?
      }
    }
  }

}
