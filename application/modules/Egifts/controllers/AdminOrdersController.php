<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminOrdersController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_AdminOrdersController extends Core_Controller_Action_Admin {

  public function indexAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_admin_main', array(), 'egifts_admin_main_manageorder');
    $this->view->formFilter = $formFilter = new Egifts_Form_Admin_FilterOrders();
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array('order' => $_GET['order'], 'order_direction' => $_GET['order_direction']), $values);
    $this->view->assign($values);
    if ($this->getRequest()->isPost()) {
        $orderValues = $this->getRequest()->getPost();
        foreach ($orderValues as $key => $giftpurchase_id) {
            if ($key == 'delete_' . $giftpurchase_id) {
                $order = Engine_Api::_()->getItem('egifts_giftorder', $giftpurchase_id);
                if($order)
                 $order->delete();
            }
        }
    }
    $ordersTable = Engine_Api::_()->getDbTable('giftpurchases', 'egifts');
    $ordersTableName = $ordersTable->info('name');
    $userName = Engine_Api::_()->getItemTable('user')->info('name');
    
    $select = $ordersTable->select()
            ->setIntegrityCheck(false)
            ->from($ordersTableName,array('giftpurchase_id','total_amount','state','owner_id'))
            ->joinLeft($userName, "$userName.user_id = $ordersTableName.owner_id", null)
            ->order($ordersTableName.'.'.(!empty($_GET['order']) ? $_GET['order'] : 'giftpurchase_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));
    if (!empty($_GET['owner_name']))
      $select->where($userName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');
    if (!empty($_GET['status'])) {
       $status = $_GET['status'];
				if($status == "pending"){
				$select ->where($ordersTableName.'.state = "pending"');
       }else if($status == "prcessing"){
           $select ->where($ordersTableName.'.state = "processing"');
       }else if($status == "hold"){
           $select ->where($ordersTableName.'.state = "hold"');
       }else if($status == "fraud"){
           $select ->where($ordersTableName.'.state = "fraud"');
       }else if($status == "complete"){
           $select ->where($ordersTableName.'.state = "complete"');
       }else if($status == "cancelled"){
           $select ->where($ordersTableName.'.state = "cancelled"');
       }
    }
    if (!empty($_GET['amount']['order_min']))
        $select->having($ordersTableName.".total_amount <=?", $_GET['amount']['order_max']);
    if (!empty($_GET['amount']['order_max']))
        $select->having($ordersTableName.".total_amount >=?", $_GET['amount']['order_min']);
    if (!empty($_GET['date']['date_from']))
        $select->having($ordersTableName . '.creation_date <=?', $_GET['date']['date_from']);
    if (!empty($_GET['date']['date_to']))
        $select->having($ordersTableName . '.creation_date >=?', $_GET['date']['date_to']);

    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
  
  public function printAction(){
		// In smoothbox
        $this->_helper->layout->setLayout('default-simple');
    $giftpurchase_id = $this->_getParam('giftpurchase_id', null); 
    $this->view->giftpurchase = Engine_Api::_()->getItem('egifts_giftpurchase', $giftpurchase_id);
    if(!$giftpurchase_id || !$this->view->giftpurchase)
      return $this->_forward('notfound', 'error', 'core');
    $this->view->format = $this->_getParam('format','');
    $giftorderTableName = Engine_Api::_()->getDbTable('giftorders','egifts');
    $select = $giftorderTableName->select()->where('giftpurchase_id = ?', $giftpurchase_id);
    $this->view->giftorders = $giftorderTableName->fetchAll($select);
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
  }
  
  public function viewAction() {
    $this->_helper->layout->setLayout('default-simple');
    $giftpurchase_id = $this->_getParam('giftpurchase_id', null); 
    $this->view->giftpurchase = Engine_Api::_()->getItem('egifts_giftpurchase', $giftpurchase_id);
    if(!$giftpurchase_id || !$this->view->giftpurchase)
      return $this->_forward('notfound', 'error', 'core');
    $this->view->format = $this->_getParam('format','');
    $giftorderTableName = Engine_Api::_()->getDbTable('giftorders','egifts');
    $select = $giftorderTableName->select()->where('giftpurchase_id = ?', $giftpurchase_id);
    $this->view->giftorders = $giftorderTableName->fetchAll($select);
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
  }
}
