<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Orderspackages.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Model_DbTable_Orderspackages extends Engine_Db_Table
{
  protected $_rowClass = 'Sescommunityads_Model_Orderspackage';
	 public function getLastOrdersTransaction($params = array()){
		$select = $this->select()->from($this->info('name'))->order('orderspackage_id DESC')->limit(1);
		if(isset($params['owner_id']))
			$select->where('owner_id =?',$params['owner_id']);
		return $this->fetchRow($select);
	}
	
	public function getLeftPackages($params = array()){
		$select = $this->select()->from($this->info('name'))->order('orderspackage_id ASC');
    if(!empty($params['action_id'])){
      $select->where('package_id IN (SELECT package_id FROM engine4_sescommunityads_packages WHERE boost_post = 1)');  
    }
		$select->where('item_count != 0 || item_count > 0');
		if(isset($params['owner_id']))
			$select->where('owner_id =?',$params['owner_id']);
		return $this->fetchAll($select);
	}
	public function checkUserPackage($packageId = null, $ownerId = null) {
    $select = $this->select()->from($this->info('name'))
            ->where('owner_id =?', $ownerId)
            ->where('orderspackage_id =?', $packageId);
    $package = $this->fetchRow($select);
    if ($package && $package->item_count)
      return 1;
    else
      return 0;
  }
	
}