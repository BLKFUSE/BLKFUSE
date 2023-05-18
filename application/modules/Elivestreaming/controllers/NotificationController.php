<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: NotificationController.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_NotificationController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
    return $this->_forward('notfound', 'error', 'core');
  }

  public function sendAction()
  {
    return $this->_forward('notfound', 'error', 'core');
  }
}
