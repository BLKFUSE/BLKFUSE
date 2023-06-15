<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Dashboards.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Model_DbTable_Dashboards extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Dashboard";
  
  public function getDashboardsItems($params = array()) {
    
    $select = $this->select()
							    ->from($this->info('name'));
		if(isset($params['type'])) {
			$select = $select->where('type =?', $params['type']);
		
	    return $this->fetchRow($select);
	    
	    }  else {
	    return $this->fetchAll($select);
	    }
  }
}