<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Remainingpayments.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjoinfees_Model_DbTable_Remainingpayments extends Engine_Db_Table {
  protected $_name = 'sescontestjoinfees_remainingpayments';
	public function getEventRemainingAmount($params = array()){
		$tabeleName = $this->info('name');
	 $select = $this->select()->from($tabeleName);
	 if(isset($params['contest_id']))
	 	$select->where('contest_id =?',$params['contest_id']);	 
	 return $this->FetchRow($select);
	}
}
