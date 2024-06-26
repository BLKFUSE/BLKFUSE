<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Egifts/controllers/Checklicense.php';
  }
}
