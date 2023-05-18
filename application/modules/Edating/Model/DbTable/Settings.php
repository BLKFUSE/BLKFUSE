<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Settings.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Model_DbTable_Settings extends Engine_Db_Table{

	protected $_rowClass = 'Edating_Model_Setting';
	
	public function getViewerRow($user_id) {
		$select = $this->select()
                          ->from($this->info('name'))
                          ->where("user_id = ?", $user_id);
		return $this->fetchRow($select);
	}
}
