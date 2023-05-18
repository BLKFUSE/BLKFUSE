<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eioslivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2020-06-01  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eioslivestreaming_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Eioslivestreaming/controllers/Checklicense.php';
  }
}
