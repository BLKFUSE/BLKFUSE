<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('edating_main', array(), '');
  }
}
