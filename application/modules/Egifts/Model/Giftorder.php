<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Giftorder.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Model_Giftorder extends Core_Model_Item_Abstract
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
}
