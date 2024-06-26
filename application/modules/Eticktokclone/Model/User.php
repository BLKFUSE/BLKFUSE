<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: User.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Model_User extends User_Model_User {
	
	protected $_searchTriggers = false;
	protected $_type = "eticktokclone_user";
	
	public function getHref($params = array()) {
	
		$profileAddress = null;
		if( isset($this->username) && '' != trim($this->username) ) {
			$profileAddress = $this->username;
		} else if( isset($this->user_id) && $this->user_id > 0 ) {
			$profileAddress = $this->user_id;
		} else {
			return 'javascript:void(0);';
		}
		
		$params = array_merge(array(
		'route' => 'eticktokclone_profile',
		'reset' => true,
		'id' => $profileAddress,
		), $params);
		$route = $params['route'];
		$reset = $params['reset'];
		unset($params['route']);
		unset($params['reset']);
		return Zend_Controller_Front::getInstance()->getRouter()->assemble($params, $route, $reset);
	}
}
