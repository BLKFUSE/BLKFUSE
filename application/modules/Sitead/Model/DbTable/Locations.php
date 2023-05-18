<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitead
 * @copyright  Copyright 2010-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Locations.php 2011-05-05 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_DbTable_Locations extends Engine_Db_Table {

  protected $_rowClass = "Sitead_Model_Location";

  /**
   * Get location
   *
   * @param array $params
   * @return object
   */
  public function getLocation($params=array()) {

    $locationFieldEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.location', 0);
    if ($locationFieldEnable) {
   
      $locationName = $this->info('name');

      $select = $this->select();
      
      if (isset($params['id'])) {
        $select->where('userad_id = ?', $params['id']);
        
        return $this->fetchRow($select);
      } 
    }
  }
  
   /**
   * Get location id
   */
  public function getLocationId ($page_id, $location) {

		$locationName = $this->info('name');
		$select = $this->select()->from($locationName, 'location_id');
		$location_id = $select->where('page_id = ?', $page_id)->where('location = ?', $location)->query()
												->fetchColumn();
		return $location_id;

  }
  
  /**
   * Get location of user ad
   */
  public function getUserAdLocation ($id) {
    $select = $this->select()
            ->where('userad_id = ?', $id);

    return $this->fetchRow($select);
  }
}