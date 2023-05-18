<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: OrderController.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
class Epaidcontent_OrderController extends Core_Controller_Action_Standard {

	public function init() {
	
    if (!$this->_helper->requireUser->isValid())
      return;

    $id = $this->_getParam('order_id', null);
    $order = Engine_Api::_()->getItem('epaidcontent_order', $id);
    if ($order) {
      Engine_Api::_()->core()->setSubject($order);
    } else {
      return $this->_forward('requireauth', 'error', 'core');	
		}
	}
	
	public function viewAction() {
	
		$order_id = $this->_getParam('order_id', null);
    $this->view->order = $order = Engine_Api::_()->core()->getSubject();
		if(!$order_id || !$order)
			return $this->_forward('notfound', 'error', 'core');
			
		$this->view->format = $this->_getParam('format','');
		$id = $order->package_id;
		$package = null;
    if ($id) {
      $package = Engine_Api::_()->getItem('epaidcontent_package', $id);
      if ($package) {
     	 $this->view->package = $package;
      } else
        return $this->_forward('notfound', 'error', 'core');
		}
		if(!$package)
			return $this->_forward('notfound', 'error', 'core');	
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
	}
}
