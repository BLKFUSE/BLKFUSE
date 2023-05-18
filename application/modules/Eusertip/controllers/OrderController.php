<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: OrderController.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
class Eusertip_OrderController extends Core_Controller_Action_Standard {

	public function init() {
	
    if (!$this->_helper->requireUser->isValid())
      return;

    $id = $this->_getParam('order_id', null);
    $order = Engine_Api::_()->getItem('eusertip_order', $id);
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
		$id = $order->tip_id;
		$tip = null;
    if ($id) {
      $tip = Engine_Api::_()->getItem('eusertip_tip', $id);
      if ($tip) {
     	 $this->view->tip = $tip;
      } else
        return $this->_forward('notfound', 'error', 'core');
		}
		if(!$tip)
			return $this->_forward('notfound', 'error', 'core');	
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
	}
}
