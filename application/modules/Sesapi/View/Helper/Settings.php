<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Settings.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesapi_View_Helper_Settings extends Zend_View_Helper_Abstract
{
  public function settings($key, $default = null)
  {
    return Engine_Api::_()->getApi('settings', 'sesapi')->getSetting($key, $default);
  }
}