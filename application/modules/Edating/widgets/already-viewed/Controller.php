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

class Edating_Widget_alreadyViewedController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $page = Zend_Controller_Front::getInstance()->getRequest()->getParam('page', 1);
		$this->view->paginator = Engine_Api::_()->getDbTable('likes', 'edating')->getLikesPaginator(array('page' => $page, 'widgetname' => 'already-viewed', 'limit' => 10));
  }
}
