<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminPaymentController.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Epaidcontent_AdminPaymentController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('epaidcontent_admin_main', array(), 'epaidcontent_admin_main_paymentrequest');
    
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('epaidcontent_admin_main_paymentr', array(), 'epaidcontent_admin_main_paymentrequestsub');
    
    $this->view->formFilter = $formFilter = new Epaidcontent_Form_Admin_Filterpaymentorder();
    
		$values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
      
    $userpayrequestTable = Engine_Api::_()->getItemTable('epaidcontent_userpayrequest');
		$userpayrequestTableName = $userpayrequestTable->info('name');
		
		$packageTableName = Engine_Api::_()->getItemTable('epaidcontent_package')->info('name');
		$tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
		
    $select = $userpayrequestTable->select()
            ->setIntegrityCheck(false)
            ->from($userpayrequestTableName)
						->where('state =?','pending')
						->joinLeft($tableUserName, "$userpayrequestTableName.owner_id = $tableUserName.user_id", 'username')
            ->order('creation_date DESC');
		
		if (!empty($_GET['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');
      
		if (!empty($_GET['creation_date']))
      $select->where($userpayrequestTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

		if (!empty($_GET['amount']))
      $select->where($userpayrequestTableName . '.requested_amount LIKE ?', '%' . $_GET['amount'] . '%');
		
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(50);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
  
	public function approveAction() {
	
    $this->view->user = $user = Engine_Api::_()->getItem('user', $this->getRequest()->getParam('user_id'));
    
    $paymnetReq = Engine_Api::_()->getItem('epaidcontent_userpayrequest', $this->getRequest()->getParam('id'));

    $this->_helper->layout->setLayout('admin-simple');
    
    $gateway_enable = Engine_Api::_()->getDbtable('usergateways', 'epaidcontent')->getUserGateway(array('user_id'=>$user->user_id));
    if(empty($gateway_enable)) {
      $this->view->disable_gateway = true;		
    } else {
      $this->view->disable_gateway = false;	
 
      $this->view->form = $form = new Epaidcontent_Form_Admin_Payment_Approve(array('userId' => $user->user_id));
      $defaultCurrency = Engine_Api::_()->epaidcontent()->defaultCurrency();
      $remainingAmount  =  Engine_Api::_()->getDbtable('remainingpayments', 'epaidcontent')->getRemainingAmount(array('user_id' => $user->user_id));
      $orderDetails  =  Engine_Api::_()->getDbtable('orders', 'epaidcontent')->packageStatsSale(array('package_owner_id' => $user->user_id));
      $value = array();
      $value['total_amount'] = Engine_Api::_()->epaidcontent()->getCurrencyPrice($orderDetails['totalAmountSale'],$defaultCurrency);
      $value['total_tax_amount'] = Engine_Api::_()->epaidcontent()->getCurrencyPrice($orderDetails['totalTaxAmount'],$defaultCurrency);
      $value['total_commission_amount'] = Engine_Api::_()->epaidcontent()->getCurrencyPrice($orderDetails['commission_amount'],$defaultCurrency);
      $value['remaining_amount'] = Engine_Api::_()->epaidcontent()->getCurrencyPrice($remainingAmount->remaining_payment,$defaultCurrency);
      //set value to form
      if($this->_getParam('id',false)){
        $item = Engine_Api::_()->getItem('epaidcontent_userpayrequest', $this->_getParam('id'));
        if($item) {
          $itemValue = $item->toArray();
          $value = array_merge($itemValue,$value);
          $value['requested_amount'] = Engine_Api::_()->epaidcontent()->getCurrencyPrice($itemValue['requested_amount'],$defaultCurrency);
          $value['release_amount'] = $itemValue['requested_amount'];
        } else {
          return $this->_forward('requireauth', 'error', 'core');	
        }
      }
      
      if(empty($_POST))
        $form->populate($value);
      
      if (!$this->getRequest()->isPost())
        return;
        
      if (!$form->isValid($this->getRequest()->getPost()))
        return;
        
      if($item->requested_amount < @round($_POST['release_amount'],2)){
        $form->addError('Release amount must be less than or equal to requested amount.');
        return;
      }
      
      $db = Engine_Api::_()->getDbtable('userpayrequests', 'epaidcontent')->getAdapter();
      $db->beginTransaction();
      
      try {
        $tableOrder = Engine_Api::_()->getDbtable('userpayrequests', 'epaidcontent');
        $order = $item;
        $order->release_amount = @round($_POST['release_amount'],2);
        $order->admin_message = $_POST['admin_message'];
        $order->release_date	 = date('Y-m-d h:i:s');
        $order->save();
        
        //Notification work
        $viewer = Engine_Api::_()->user()->getViewer();
        $owner = $user->getOwner();
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $user, 'epaidcontent_adminpayaprov', array());
        
        //Payment approve mail send to event owner
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner, 'epaidcontent_payment_adminrequestapproved', array('object_link' => $user->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        
        $db->commit();
        $session = new Zend_Session_Namespace();
        $session->payment_request_id = $order->userpayrequest_id;
        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Processing...');
        return $this->_forward('success', 'utility', 'core', array(
          'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('route' => 'default','module' => 'epaidcontent', 'controller' => 'payment', 'action' => 'process','gateway_id'=>$_POST['gateway_id']),'admin_default',true),
          'messages' => array($this->view->message)
        ));
      }catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
	}
	
	public function processAction() {
	
		$session = new Zend_Session_Namespace();
		$viewer = Engine_Api::_()->user()->getViewer();		
		if(!$session->payment_request_id)
			return $this->_forward('requireauth', 'error', 'core');
			
		$item = Engine_Api::_()->getItem('epaidcontent_userpayrequest', $session->payment_request_id);
		
		$user = Engine_Api::_()->getItem('user', $item->owner_id);
		
    // Get gateway
    $gatewayId = $this->_getParam('gateway_id', null); 
		$gateway = Engine_Api::_()->getItem('epaidcontent_usergateway',$gatewayId);
		if( !$gatewayId ||
        !($gateway) ||
        !($gateway->enabled) ) {
       return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    }
    
    $this->view->gateway = $gateway;
		$this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
		$plugin = $gateway->getPlugin();
		$ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
    // Process
    $ordersTable->insert(array(
        'user_id' => $viewer->getIdentity(),
        'gateway_id' => $gateway->usergateway_id,
        'state' => 'pending',
        'creation_date' => new Zend_Db_Expr('NOW()'),
        'source_type' => 'epaidcontent_userpayrequest',
        'source_id' => $item->userpayrequest_id,
    ));
		$session = new Zend_Session_Namespace();
    $session->epaidcontent_order_id = $order_id = $ordersTable->getAdapter()->lastInsertId(); 
		$session->epaidcontent_item_id = $item->getIdentity();    
    // Prepare host info
    $schema = 'http://';
    if( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"])){
      $schema = 'https://';
    }
    $host = $_SERVER['HTTP_HOST'];
    //Prepare transaction
    $params = array();
    $params['language'] = $viewer->language;
    $localeParts = explode('_', $viewer->language);
		if(engine_count($localeParts) > 1){
			$params['region'] = $localeParts[1];
		}
    $params['vendor_order_id'] = $order_id;
    $params['return_url'] = $schema . $host
      .  $this->view->url(array('action' => 'return', 'controller' => 'payment', 'module' => 'epaidcontent'), 'admin_default', true)
      . '/?state=' . 'return&order_id=' . $order_id;
    $params['cancel_url'] = $schema . $host
      .  $this->view->url(array('action' => 'return', 'controller' => 'payment', 'module' => 'epaidcontent'), 'admin_default', true)
      . '/?state=' . 'cancel&order_id=' . $order_id;
    $params['ipn_url'] = $schema . $host
      .  $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'payment'), 'admin_default', true).'&order_id=' . $order_id;
      
    //Process transaction
    if($gateway->plugin == "Epaidcontent_Plugin_Gateway_Event_Stripe") {
        $params['currency'] = Engine_Api::_()->epaidcontent()->getCurrentCurrency();
        $this->view->publishKey = $gateway->config['sesadvpmnt_stripe_publish']; 
        $this->view->session =  $plugin->createOrderTransaction($item,$user,$params);  
        $this->renderScript('/application/modules/Sesadvpmnt/views/scripts/payment/index.tpl');
    } elseif($gateway->plugin  == "Epaidcontent_Plugin_Gateway_Event_Paytm") {
        $paytmParams = $plugin->createOrderTransaction($item,$user,$params);
        $secretKey  = $gateway->config['paytm_secret_key'];
        $this->view->paytmParams = $paytmParams;
        $this->view->checksum = getChecksumFromArray($paytmParams, $secretKey);
        if($gateway->test_mode){
          $this->view->url = "https://securegw-stage.paytm.in/order/process";
        } else {
          $this->view->url = "https://securegw.paytm.in/merchant-status/getTxnStatus";
        }
         $this->renderScript('/application/modules/Epaytm/views/scripts/payment/index.tpl');
    } else {
        $transaction = $plugin->createOrderTransaction($item,$user,$params);	
        // Pull transaction params
        $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
        $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
        $this->view->transactionData = $transactionData = $transaction->getData();
    }
    
    // Handle redirection
    if($transactionMethod == 'GET'){
     $transactionUrl .= '?' . http_build_query($transactionData);
     return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
    }
    // Post will be handled by the view script
	}
	public function returnAction() {
	
		$session = new Zend_Session_Namespace();
		
    // Get order
		$orderId = $this->_getParam('order_id', null);
		$orderPaymentId = $session->epaidcontent_order_id;
		$orderPayment = Engine_Api::_()->getItem('payment_order', $orderPaymentId);
		$item_id = $session->epaidcontent_item_id ;
		$item = Engine_Api::_()->getItem('epaidcontent_userpayrequest', $item_id);
    if (!$orderPayment || ($orderId != $orderPaymentId) ||
			 ($orderPayment->source_type != 'epaidcontent_userpayrequest') ||
			 !($user_order = $orderPayment->getSource()) ) {
			return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    }
    
    $gateway = Engine_Api::_()->getItem('epaidcontent_usergateway', $orderPayment->gateway_id); 
		if( !$gateway )
      return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    // Get gateway plugin
    $plugin = $gateway->getPlugin();
    unset($session->errorMessage);
    try {
      // Stripe plugin Integration work
      //get all params 
      $params = $this->_getAllParams();
      $status = $plugin->orderTransactionReturn($orderPayment, $params,$item);
      
    } catch (Payment_Model_Exception $e) {
      $status = 'failure';
      $session->errorMessage = $e->getMessage();
    }
    return $this->_finishPayment($status,$orderPayment->source_id);
  }
  
  protected function _finishPayment($state = 'active') {
  
		$session = new Zend_Session_Namespace();
    // Clear session
    $errorMessage = $session->errorMessage;
    $session->errorMessage = $errorMessage;
    // Redirect
    if ($state == 'free') {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    } else {
			 return $this->_helper->redirector->gotoRoute(array('action' => 'finish', 'state' => $state));
    }
  }
  
  public function finishAction() {
    $session = new Zend_Session_Namespace();
    if (!empty($session->epaidcontent_order_id))
      $session->epaidcontent_order_id = '';
		
    $orderTrabsactionDetails = array('state' => $this->_getParam('state'), 'errorMessage' => $session->errorMessage);
    $session->epaidcontent_order_details = $orderTrabsactionDetails;
		$state = $this->_getParam('state');
	  if(!$state)
	 	 return $this->_forward('notfound', 'error', 'core');
		$this->view->error = $error =  $session->errorMessage;
		$session->unsetAll();
  }

	public function cancelAction() {
	
		$user = Engine_Api::_()->getItem('user', $this->getRequest()->getParam('user_id'));
	  $paymnetReq = Engine_Api::_()->getItem('epaidcontent_userpayrequest', $this->getRequest()->getParam('id'));  

    $this->_helper->layout->setLayout('default-simple');

    // Make form
    $this->view->form = $form = new Sesbasic_Form_Delete();
    $form->setTitle('Reject Payment Request');
    $form->setDescription('Are you sure that you want to reject this payment request?');
    $form->submit->setLabel('Reject');
		 
    if (!$paymnetReq) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to cancel");
      return;
    }

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $db = $paymnetReq->getTable()->getAdapter();
    $db->beginTransaction();

    try {
    
      $paymnetReq->state = 'cancelled';
			$paymnetReq->save();
      $db->commit();
      
      //Notification work
      $viewer = Engine_Api::_()->user()->getViewer();
			$owner = $user->getOwner();
			Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $user, 'epaidcontent_adminpaycancl', array());
			
      //Payment cancel mail send to page owner
			//Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner, 'epaidcontent_payment_adminrequestcancel', array('user_title' => $user->getTitle(), 'object_link' => $user->getHref(), 'host' => $_SERVER['HTTP_HOST']));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Payment Request has been cancelled.');
    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh' => 10,
      'messages' => array($this->view->message)
    ));	
	}
}
