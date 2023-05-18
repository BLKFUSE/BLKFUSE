<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    // Get navigation
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_main', array());
    $sesmember_browsemembers = Zend_Registry::isRegistered('sesmember_browsemembers') ? Zend_Registry::get('sesmember_browsemembers') : null;
    if (empty($sesmember_browsemembers))
      return $this->setNoRender();
    $this->view->max = $this->_getParam('sesmember_taboptions', 6);
  }
}
