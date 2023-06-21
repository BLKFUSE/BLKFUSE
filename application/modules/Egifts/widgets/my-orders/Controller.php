<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Widget_MyOrdersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $value = array();
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->user_id = $viewer_id = $viewer->getIdentity();
    
    $this->view->viewer_id = $value['user_id'] = $viewer_id;
    if (!$viewer_id)
      return $this->setNoRender();
      
    $this->view->is_search_ajax = $is_search_ajax = isset($_POST['is_search_ajax']) ? $_POST['is_search_ajax'] : false;
    if (!$is_search_ajax) {
      $this->view->searchForm = $searchForm = new Egifts_Form_SearchPurchasedOrder();
    }

    if (isset($_POST['searchParams']) && $_POST['searchParams']) {
			if(engine_in_array($_POST['searchParams']))
				$searchArray = $_POST['searchParams'];
			elseif(is_string($_POST['searchParams']))
				parse_str($_POST['searchParams'], $searchArray);
    }
    
    $value['order_id'] = isset($searchArray['order_id']) ? $searchArray['order_id'] : '';
    $value['date_from'] = isset($searchArray['date']['date_from']) ? date("Y-m-d", strtotime($searchArray['date']['date_from'])) : '';
    $value['date_to'] = isset($searchArray['date']['date_to']) ? date("Y-m-d", strtotime($searchArray['date']['date_to'])) : '';
    $value['order_min'] = isset($searchArray['order']['order_min']) ? $searchArray['order']['order_min'] : '';
    $value['order_max'] = isset($searchArray['order']['order_max']) ? $searchArray['order']['order_max'] : '';
    $value['action'] = 'myorders';
    
    if($value['date_from'] == '1970-01-01')
			unset($value['date_from']);
		if($value['date_to'] == '1970-01-01')
			unset($value['date_to']);
		
    $this->view->orders = $orders = Engine_Api::_()->getDbtable('giftpurchases', 'egifts')->manageOrders($value);
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->page = $page = $this->_getParam('page', 1);
    $this->view->paginator = $paginator = Zend_Paginator::factory($orders);

    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(10);
  }
}
