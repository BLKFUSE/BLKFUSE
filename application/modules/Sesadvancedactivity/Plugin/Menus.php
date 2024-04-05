<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Menus.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Plugin_Menus {
  public function enableonthisday() {
    $viewer = Engine_Api::_()->user()->getViewer();
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity_enableonthisday', 1) || !$viewer->getIdentity()){
			return false;	
		}    
    return true;
  }
}
