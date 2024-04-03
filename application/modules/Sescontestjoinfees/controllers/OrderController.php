<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: OrderController.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sescontestjoinfees_OrderController extends Core_Controller_Action_Standard {
	public function init() {
	 if (!$this->_helper->requireUser->isValid())
			return;
    $id = $this->_getParam('order_id', null);
    $order = Engine_Api::_()->getItem('sescontestjoinfees_order', $id);
    if ($order) {
        Engine_Api::_()->core()->setSubject($order);
    }else{
			 return $this->_forward('requireauth', 'error', 'core');	
		}
	}
	public function viewAction(){
		$order_id = $this->_getParam('order_id', null);
    $this->view->order = $order = Engine_Api::_()->core()->getSubject();
		if(!$order_id || !$order)
			return $this->_forward('notfound', 'error', 'core');
		$this->view->format = $this->_getParam('format','');
		$id = $order->contest_id;
		$contest = null;
    if ($id) {
      $contest = Engine_Api::_()->getItem('contest', $id);
      if ($contest) {
     	 $this->view->contest = $contest;
      }else
				return $this->_forward('notfound', 'error', 'core');
		}
		if(!$contest)
			return $this->_forward('notfound', 'error', 'core');	
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
	}
}
