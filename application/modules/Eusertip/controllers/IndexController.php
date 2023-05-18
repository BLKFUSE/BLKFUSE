<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_IndexController extends Core_Controller_Action_Standard {

  public function init() {
	 if (!$this->_helper->requireUser->isValid())
			return;
    $id = $this->_getParam('order_id', null);
    $order = Engine_Api::_()->getItem('eusertip_order', $id);
    if ($order) {
        Engine_Api::_()->core()->setSubject($order);
    }
	}
	
  //delete payment request
  public function deletePaymentAction() {

    $paymnetReq = Engine_Api::_()->getItem('eusertip_userpayrequest', $this->getRequest()->getParam('id'));
    $viewer = Engine_Api::_()->user()->getViewer();

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');

    // Make form
    $this->view->form = $form = new Sesbasic_Form_Delete();
    $form->setTitle('Delete Payment Request?');
    $form->setDescription('Are you sure that you want to delete this payment request? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if (!$paymnetReq) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to delete");
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
      $paymnetReq->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Payment Request has been deleted.');
    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh' => 10,
      'messages' => array($this->view->message)
    ));
  }

  public function detailPaymentAction() {
    
    $this->view->item = $paymnetReq = Engine_Api::_()->getItem('eusertip_userpayrequest', $this->getRequest()->getParam('id'));
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    if (!$paymnetReq) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to view.");
      return;
    }
  }
  
  public function paymentRequestAction() {

    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->thresholdAmount = $thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'eusertip', 'eusertip_threamt');

    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'eusertip')->getRemainingAmount(array('user_id' => $viewer->getIdentity()));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else {
      $this->view->remainingAmount = $remainingAmount->remaining_payment;
    }
    $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency();
    $orderDetails = Engine_Api::_()->getDbtable('orders', 'eusertip')->tipStatsSale(array('tip_owner_id' => $viewer->getIdentity()));
    $this->view->form = $form = new Eusertip_Form_Paymentrequest();
    $value = array();
    $value['total_amount'] = Engine_Api::_()->eusertip()->getCurrencyPrice($orderDetails['totalAmountSale'], $defaultCurrency);
    $value['total_commission_amount'] = Engine_Api::_()->eusertip()->getCurrencyPrice($orderDetails['commission_amount'], $defaultCurrency);
    $value['remaining_amount'] = Engine_Api::_()->eusertip()->getCurrencyPrice($remainingAmount->remaining_payment, $defaultCurrency);
    $value['requested_amount'] = round($remainingAmount->remaining_payment, 2);
    //set value to form
    if ($this->_getParam('id', false)) {
      $item = Engine_Api::_()->getItem('eusertip_userpayrequest', $this->_getParam('id'));
      if ($item) {
        $itemValue = $item->toArray();
        //unset($value['requested_amount']);
        $value = array_merge($itemValue, $value);
      } else {
        return $this->_forward('requireauth', 'error', 'core');
      }
    }
    if (empty($_POST))
      $form->populate($value);

    if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
    if (@round($thresholdAmount, 2) > @round($remainingAmount->remaining_payment, 2) && empty($_POST)) {
      $this->view->message = 'Remaining amount is less than Threshold amount.';
      $this->view->errorMessage = true;
      return;
    } else if (isset($_POST['requested_amount']) && @round($_POST['requested_amount'], 2) > @round($remainingAmount->remaining_payment, 2)) {
      $form->addError('Requested amount must be less than or equal to remaining amount.');
      return;
    } else if (isset($_POST['requested_amount']) && @round($thresholdAmount) > @round($_POST['requested_amount'], 2)) {
      $form->addError('Requested amount must be greater than or equal to threshold amount.');
      return;
    }

    $db = Engine_Api::_()->getDbtable('userpayrequests', 'eusertip')->getAdapter();
    $db->beginTransaction();
    try {
      $tableOrder = Engine_Api::_()->getDbtable('userpayrequests', 'eusertip');
      if (isset($itemValue))
        $order = $item;
      else
        $order = $tableOrder->createRow();
      $order->requested_amount = round($_POST['requested_amount'], 2);
      $order->user_message = $_POST['user_message'];
      $order->owner_id = $viewer->getIdentity();
      $order->user_message = $_POST['user_message'];
      $order->creation_date = date('Y-m-d h:i:s');
      $order->currency_symbol = $defaultCurrency;
      $order->save();
      $db->commit();

      //Notification work
      $owner_admin = Engine_Api::_()->getItem('user', 1);
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner_admin, $viewer, $viewer, 'eusertip_payrequest', array('requestAmount' => round($_POST['requested_amount'], 2)));
      
      //Payment request mail send to admin
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner_admin, 'eusertip_entrypayment_requestadmin', array('buyer_name' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));

      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your payment request has been successfully sent to Admin for approval.');
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 1000,
        'parentRefresh' => 1000,
        'messages' => array($this->view->message)
      ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }
	
  //get payment to admin information
  public function paymentRequestsAction() {
    
    $this->_helper->content->setEnabled();
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    
    $this->view->thresholdAmount = $thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'eusertip', 'eusertip_threamt');

    $this->view->userGateway = Engine_Api::_()->getDbtable('usergateways', 'eusertip')->getUserGateway(array('user_id' => $viewer_id));
    
    $this->view->orderDetails = Engine_Api::_()->getDbtable('orders', 'eusertip')->tipStatsSale(array('tip_owner_id' => $viewer_id));
    
    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'eusertip')->getRemainingAmount(array('user_id' => $viewer_id));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else
      $this->view->remainingAmount = $remainingAmount->remaining_payment;
      
    $this->view->isAlreadyRequests = Engine_Api::_()->getDbtable('userpayrequests', 'eusertip')->getPaymentRequests(array('owner_id' => $viewer_id, 'isPending' => true));
    
    $this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'eusertip')->getPaymentRequests(array('owner_id' => $viewer_id, 'isPending' => true));
  }
	
  public function paymentTransactionAction() {
    
    $this->_helper->content->setEnabled();
    
    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'eusertip')->getPaymentRequests(array('tip_owner_id' => $viewer->getIdentity(), 'state' => 'both'));
  }
  
  public function salesReportsAction() {
  
    $this->_helper->content->setEnabled();

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->form = $form = new Eusertip_Form_Searchsalereport();
    $value = array();
    if (isset($_GET['startdate']))
      $value['startdate'] = $value['start'] = date('Y-m-d', strtotime($_GET['startdate']));
    if (isset($_GET['enddate']))
      $value['enddate'] = $value['end'] = date('Y-m-d', strtotime($_GET['enddate']));
    if (isset($_GET['type']))
      $value['type'] = $_GET['type'];
    if (!engine_count($value)) {
      $value['enddate'] = date('Y-m-d', strtotime(date('Y-m-d')));
      $value['startdate'] = date('Y-m-d', strtotime('-30 days'));
      $value['type'] = $form->type->getValue();
    }
    if (isset($_GET['excel']) && $_GET['excel'] != '')
      $value['download'] = 'excel';
    if (isset($_GET['csv']) && $_GET['csv'] != '')
      $value['download'] = 'csv';
    $form->populate($value);
    $value['tip_owner_id'] = $viewer->getIdentity();
    $this->view->eventSaleData = $data = Engine_Api::_()->getDbTable('orders', 'eusertip')->getReportData($value);

    if (isset($value['download'])) {
      $name = str_replace(' ', '_', $viewer->getTitle()) . '_' . time();
      switch ($value["download"]) {
        case "excel" :
          // Submission from
          $filename = $name . ".xls";
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$filename\"");
          $this->exportFile($data);
          exit();
        case "csv" :
          // Submission from
          $filename = $name . ".csv";
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Content-type: text/csv");
          header("Content-Disposition: attachment; filename=\"$filename\"");
          header("Expires: 0");
          $this->exportCSVFile($data);
          exit();
        default :
          //silence
          break;
      }
    }
  }
	
  public function salesStatsAction() {
  
    $this->_helper->content->setEnabled();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $this->view->todaySale = Engine_Api::_()->getDbtable('orders', 'eusertip')->getSaleStats(array('stats' => 'today', 'tip_owner_id' => $viewer_id));
    $this->view->weekSale = Engine_Api::_()->getDbtable('orders', 'eusertip')->getSaleStats(array('stats' => 'week', 'tip_owner_id' => $viewer_id));
    $this->view->monthSale = Engine_Api::_()->getDbtable('orders', 'eusertip')->getSaleStats(array('stats' => 'month', 'tip_owner_id' => $viewer_id));

    $this->view->tipStatsSale = Engine_Api::_()->getDbtable('orders', 'eusertip')->tipStatsSale(array('tip_owner_id' => $viewer_id));
  }

  public function manageOrdersAction() {
    $this->_helper->content->setEnabled();
  }
  
  public function myOrdersAction() {
    $this->_helper->content->setEnabled();
  }
	
  public function showtipAction() {
    $tip_id = $this->_getParam('tip_id', null);
    $tip = Engine_Api::_()->getItem('eusertip_tip', $tip_id);
    $user_id = $tip->user_id;
    $this->view->enableTip = Engine_Api::_()->getDbtable('tips','eusertip')->getEnabledTips($user_id);
  }
  
  public function makepaymentAction() {

    $this->view->tip_id = $tip_id = $this->_getParam('tip_id', null);
    $this->view->tip = $tip = Engine_Api::_()->getItem('eusertip_tip', $tip_id);
    $user_id = $tip->user_id;
    $this->view->user = Engine_Api::_()->getItem('user', $user_id);

    $gatewayTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    $gatewaySelect = $gatewayTable->select()
                                  ->where('enabled = ?', 1);
    $gateways = $gatewayTable->fetchAll($gatewaySelect);
    $gatewayPlugins = array();
    foreach( $gateways as $gateway ) {
      $gatewayPlugins[] = array(
        'gateway' => $gateway,
        'plugin' => $gateway->getGateway(),
      );
    }
    
//     if ($page_id) {
//       $page = Engine_Api::_()->getItem('sespage_page', $page_id);
//       if ($page) {
//         $couponSessionCode = '-'.'-'.$page->getType().'-'.$page->page_id.'-0';
//         $currencyValue = 1;
//         if($currentCurrency != $defaultCurrency){
//             $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
//         }
//         $priceTotal = @round($page->entry_fees*$currencyValue,2);
//         $this->view->itemPrice = @isset($_SESSION[$couponSessionCode]) ? round($priceTotal - $_SESSION[$couponSessionCode]['discount_amount']) : $priceTotal;
//       } else
// 				return $this->_forward('requireauth', 'error', 'core');	
// 		}
		
    // For Coupon 
    $this->view->gateways = $gatewayPlugins;
  }
  
  public function processAction() {
  
    // Get gateway
    $gatewayId = $this->_getParam('gateway_id', null);

		$tip_id = $this->_getParam('tip_id', null);    
		$tip = null;
    if ($tip_id) {
      $tip = Engine_Api::_()->getItem('eusertip_tip', $tip_id);
      if ($tip) {
     	 $this->view->tip = $tip ;
      } else
				return $this->_forward('requireauth', 'error', 'core');	
		}
		if(!$tip)
			return $this->_forward('requireauth', 'error', 'core');
			
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();		
    if( !$gatewayId ||
        !($gateway = Engine_Api::_()->getItem('eusertip_gateway', $gatewayId)) ||
        !($gateway->enabled) ) {
      header("location:".$this->view->escape($tip->getHref()));
			die;
    }		
    
    $this->view->gateway = $gateway;		
		$this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
		 // Prepare host info
    $schema = 'http://';
    if( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]) ) {
      $schema = 'https://';
    }
    $host = $_SERVER['HTTP_HOST'];
    
    // For Coupon 
    $couponSessionCode = '-'.'-'.$tip->getType().'-'.$tip->tip_id.'-0';
    
    //create order
    $table = Engine_Api::_()->getDbTable('orders', 'eusertip');
    $order = $table->createRow();
    $order->tip_id = $tip_id;
    $order->tip_owner_id = $tip->user_id;
    $order->owner_id = $this->view->viewer()->getIdentity();
    $order->gateway_id = $gatewayId;
    $order->private = 1;
    $order->state = 'incomplete';
    $order->is_delete = 0;
    $order->save();
    
    // Prepare transaction
    $params = array();
    $params['language'] = $viewer->language;
    $localeParts = explode('_', $viewer->language);
		if( engine_count($localeParts) > 1 ) {
			$params['region'] = $localeParts[1];
		}
    $params['return_url'] = $schema . $host
      . $this->view->escape($this->view->url(array('action' => 'return','order_id'=>$order->getIdentity(),'tip_id'=>$order->tip_id)))
      . '/?state=' . 'return';
    $params['cancel_url'] = $this->view->escape($schema . $host
      . $this->view->url(array('action' => 'return','order_id'=>$order->getIdentity())))
      . '/?state=' . 'cancel';
    $params['ipn_url'] = $schema . $host
      . $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'payment'), 'default');
// 		if ($gatewayId == 1) {
// 				$gatewayPlugin->createProduct(array_merge($order->getGatewayParams(),array('approved_url'=>$params['return_url'])));
// 		}
		$plugin = $gateway->getPlugin();
		$ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
    // Process
    $ordersTable->insert(array(
        'user_id' => $viewer->getIdentity(),
        'gateway_id' => $gateway->gateway_id,
        'state' => 'pending',
        'creation_date' => new Zend_Db_Expr('NOW()'),
        'source_type' => 'eusertip_order',
        'source_id' => $order->order_id,
    ));
		$session = new Zend_Session_Namespace();
    $session->eusertip_order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();    
    $params['vendor_order_id'] = $order_id;
    
    $currencyValue = 1;
    if($currentCurrency != $defaultCurrency){
        $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
    }
    $priceTotal = @round($tip->price*$currencyValue,2);
    $params['amount'] = @isset($_SESSION[$couponSessionCode]) ? round($priceTotal - $_SESSION[$couponSessionCode]['discount_amount']) : $priceTotal;
    //For Credit integration
    $creditCode =  'credit'.'-eusertip-'.$order->tip_id.'-'.$order->tip_id;
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0) { 
      $params['amount'] = $sessionCredit->total_amount;
    }
    if($gateway->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe") {
      $params['currency'] = Engine_Api::_()->eusertip()->getCurrentCurrency();
      $this->view->publishKey = $gateway->config['sesadvpmnt_stripe_publish']; 
      $this->view->session = $plugin->createOrderTransaction($viewer,$order,$tip,$params);
      $this->renderScript('/application/modules/Sesadvpmnt/views/scripts/payment/index.tpl');
    } elseif($gateway->plugin  == "Epaytm_Plugin_Gateway_Paytm") {
        $paytmParams = $plugin->createOrderTransaction($viewer,$order,$tip,$params);
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
      // Process transaction
      $transaction = $plugin->createOrderTransaction($viewer,$order,$tip,$params);
      // Pull transaction params
      $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
      $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
      $this->view->transactionData = $transactionData = $transaction->getData();
    }
    // Handle redirection
    if( $transactionMethod == 'GET' ) {
     $transactionUrl .= '?' . http_build_query($transactionData);
     return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
    }
    // Post will be handled by the view script
  }
  
  public function returnAction() {
  
		$this->view->order = $order = Engine_Api::_()->core()->getSubject();
		//if($order->state == 'complete')
			//return $this->_forward('notfound', 'error', 'core');
		$session = new Zend_Session_Namespace();
    // Get order
		$orderId = $this->_getParam('order_id', null);
		$orderPaymentId = $session->eusertip_order_id;
		$orderPayment = Engine_Api::_()->getItem('payment_order', $orderPaymentId);
    if (!$orderPayment || ($orderId != $orderPayment->source_id) ||
			 ($orderPayment->source_type != 'eusertip_order') ||
			 !($user_order = $orderPayment->getSource()) ) {
			return $this->_helper->redirector->gotoRoute(array("action" => "my-orders"), 'eusertip_general', true);
    }
    $gateway = Engine_Api::_()->getItem('eusertip_gateway', $orderPayment->gateway_id);    
    if( !$gateway )
      return $this->_helper->redirector->gotoRoute(array(), 'eusertip_general', true);
    $tip_id = $this->_getParam('tip_id', null);    
    $tip = null;
    if ($tip_id) {
      $tip = Engine_Api::_()->getItem('eusertip_tip', $tip_id);
    }
    $params  = array();
    //For Coupon
    $couponSessionCode = '-'.'-'.$tip->getType().'-'.$tip->tip_id.'-0';
    $params['amount'] = @isset($_SESSION[$couponSessionCode]) ? round($tip->price- $_SESSION[$couponSessionCode]['discount_amount']) : $tip->price;
    $params['couponSessionCode'] = $couponSessionCode;
    //For Credit integration
    $creditCode =  'credit'.'-eusertip-'.$order->tip_id.'-'.$order->tip_id;
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0) { 
      $params['amount'] = $sessionCredit->total_amount;
      $params['creditCode'] = $creditCode;
    }
    // Get gateway plugin
    $plugin = $gateway->getPlugin();
    unset($session->errorMessage);
    try {
      // Stripe plugin Integration work
      if($params['state'] != 'cancel'){
        //get all params 
        $status = $plugin->orderTicketTransactionReturn($orderPayment, array_merge($this->_getAllParams(),$params));
      }else{
        $status = 'cancel';
        $session->errorMessage = $this->view->translate('Your payment has been cancelled and not been charged. If this is not correct, please try again later.');	
      }
    } catch (Payment_Model_Exception $e) {
      $status = 'failure';
      $session->errorMessage = $e->getMessage();
    }
    $sessionCredit->unsetAll();
    return $this->_finishPayment($status,$orderPayment->source_id);
  }
  protected function _finishPayment($state = 'active') {
		$session = new Zend_Session_Namespace();
    // Clear session
    $errorMessage = $session->errorMessage;
    $session->errorMessage = $errorMessage;
    // Redirect
    $url =  $this->view->escape($this->view->url(array('action' => 'finish', 'state' => $state)));
    header('location:'.$url);die;
  }
  public function finishAction() {
    $session = new Zend_Session_Namespace();
    $orderTrabsactionDetails = array('state' => $this->_getParam('state'), 'errorMessage' => $session->errorMessage);
    $session->sescontesy_order_details = $orderTrabsactionDetails;
		$url =  $this->view->escape($this->view->url(array('action' => 'success')));
    header('location:'.$url);die;
  } 

	public function successAction() {
		$session = new Zend_Session_Namespace();
		$order_id = $this->_getParam('order_id', null); 
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();	
    $this->view->order = $order = Engine_Api::_()->core()->getSubject(); 
		if (!$order || $order->owner_id != $viewer->getIdentity())
      return $this->_forward('notfound', 'error', 'core');
		if(!$order_id)
			return $this->_forward('notfound', 'error', 'core');
		$tip_id = $this->_getParam('tip_id', null);
		$tip = null;
    if ($tip_id) {
      $tip = Engine_Api::_()->getItem('eusertip_tip', $tip_id);
      if ($tip) {
     	 $this->view->tip = $tip ;
      }else
				return $this->_forward('notfound', 'error', 'core');	
		}
		if(!$tip)
			return $this->_forward('notfound', 'error', 'core');	
		$state = $this->_getParam('state');
	  if(!$state)
      return $this->_forward('notfound', 'error', 'core');
		$this->view->error = $error =  $session->errorMessage;
		$session->unsetAll();
		$this->view->state = $state;
		
	}

  public function createtipAction() {
  
    $this->_helper->content->setEnabled();
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
    if( !$this->_helper->requireAuth()->setAuthParams('eusertip', $viewer, 'create')->isValid())
      return;
    
    // Make form
    $this->view->form = $form = new Eusertip_Form_Index_Create();
    $locale = $this->view->locale()->getLocaleDefault();
    $defaultVal = $this->view->locale()->toNumber('0.00', array('default_locale' => true));
    $form->price->setValue($defaultVal)
      ->addValidator('float', true, array('locale' => $locale));

    // Get supported billing cycles
    $gateways = array();
    $supportedBillingCycles = array();
    $partiallySupportedBillingCycles = array();
    $fullySupportedBillingCycles = null;
    $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    foreach( $gatewaysTable->fetchAll(/*array('enabled = ?' => 1)*/) as $gateway ) {
      $gateways[$gateway->gateway_id] = $gateway;
      $supportedBillingCycles[$gateway->gateway_id] = $gateway->getGateway()->getSupportedBillingCycles();
      $partiallySupportedBillingCycles = array_merge($partiallySupportedBillingCycles, $supportedBillingCycles[$gateway->gateway_id]);
      if( null === $fullySupportedBillingCycles ) {
        $fullySupportedBillingCycles = $supportedBillingCycles[$gateway->gateway_id];
      } else {
        $fullySupportedBillingCycles = array_intersect($fullySupportedBillingCycles, $supportedBillingCycles[$gateway->gateway_id]);
      }
    }
    $partiallySupportedBillingCycles = array_diff($partiallySupportedBillingCycles, $fullySupportedBillingCycles);

    $multiOptions = /* array(
      'Fully Supported' =>*/ array_combine(array_map('strtolower', $fullySupportedBillingCycles), $fullySupportedBillingCycles)/*,
      'Partially Supported' => array_combine(array_map('strtolower', $partiallySupportedBillingCycles), $partiallySupportedBillingCycles),
    )*/;
//     $form->getElement('recurrence')
//       ->setMultiOptions($multiOptions)
//       //->setDescription('-')
//       ;
//     $form->getElement('recurrence')->options/*['Fully Supported']*/['forever'] = 'One-time';
// 
//     $form->getElement('duration')
//       ->setMultiOptions($multiOptions)
//       //->setDescription('-')
//       ;
//     $form->getElement('duration')->options/*['Fully Supported']*/['forever'] = 'Forever';

    /*
    $form->getElement('trial_duration')
      ->setMultiOptions($multiOptions)
      //->setDescription('-')
      ;
    $form->getElement('trial_duration')->options['Fully Supported']['forever'] = 'None';
    //$form->getElement('trial_duration')->setValue('0 forever');
     *
     */

    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
		if(in_array($_POST['duration'][1], array('year','month', 'week')) && $_POST['duration'][0] == 0) {
			$form->addError($this->view->translate("Please enter a number that is at least '1'. Please enter a valid decimal number in Billing Duration."));
			return;
		}
		
		if(in_array($_POST['recurrence'][1], array('year','month', 'week')) && $_POST['recurrence'][0] == 0) {
			$form->addError($this->view->translate("Please enter a number that is at least '1'. Please enter a valid decimal number in Billing Cycle."));
			return;
		}
		
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }


    // Process
    $values = $form->getValues();

    $tmp = $values['recurrence'];
    unset($values['recurrence']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['recurrence'] = (int) $tmp[0];
    $values['recurrence_type'] = $tmp[1];

    $tmp = $values['duration'];
    unset($values['duration']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['duration'] = (int) $tmp[0];
    $values['duration_type'] = $tmp[1];

    $values['reminder_email_type'] = 'day';
    
    if(!empty($values['modules'])) {
      $values['modules'] = json_encode($values['modules']);
    }

    /*
    $tmp = $values['trial_duration'];
    unset($values['trial_duration']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['trial_duration'] = (int) $tmp[0];
    $values['trial_duration_type'] = $tmp[1];
     *
     */

    if( !empty($values['default']) && (float) $values['price'] > 0 ) {
      return $form->addError('Only a free plan may be the default plan.');
    }


    $tipTable = Engine_Api::_()->getDbtable('tips', 'eusertip');
    $db = $tipTable->getAdapter();
    $db->beginTransaction();

    try {

      // Update default
      if( !empty($values['default']) ) {
        $tipTable->update(array(
          'default' => 0,
        ), array(
          '`default` = ?' => 1,
        ));
      }

      $values['price'] = Zend_Locale_Format::getNumber($values['price'], array('locale' => $locale));

      // Create tip
      $tip = $tipTable->createRow();
      $values['user_id'] = $viewer->getIdentity();
      $tip->setFromArray($values);
      $tip->save();

      // Create tip in gateways?
      if( !$tip->isFree() ) {
        $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
        foreach( $gatewaysTable->fetchAll(array('enabled = ?' => 1)) as $gateway ) {
          $gatewayPlugin = $gateway->getGateway();
          // Check billing cycle support
          if( !$tip->isOneTime() ) {
            $sbc = $gateway->getGateway()->getSupportedBillingCycles();
            if( !in_array($tip->recurrence_type, array_map('strtolower', $sbc)) ) {
              continue;
            }
          }
          if( method_exists($gatewayPlugin, 'createProduct') ) {
            $gatewayPlugin->createProduct($tip->getGatewayParams());
          }
        }
      }

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    // Redirect
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage-tips'));
  }
  

  public function edittipAction() {
    
    $this->_helper->content->setEnabled();
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
    if( !$this->_helper->requireAuth()->setAuthParams('eusertip', $viewer, 'create')->isValid() ) {
      return;
    }
    
    // Get tip
    if( null === ($tipIdentity = $this->_getParam('tip_id')) || !($tip = Engine_Api::_()->getDbtable('tips', 'eusertip')->find($tipIdentity)->current()) ) {
      throw new Engine_Exception('No tip found');
    }

    // Make form
    $this->view->form = $form = new Eusertip_Form_Index_Edit();

    // Populate form
    $values = $tip->toArray();
    $this->view->tip = $tip;

//     $values['recurrence'] = array($values['recurrence'], $values['recurrence_type']);
//     $values['duration'] = array($values['duration'], $values['duration_type']);
    //$values['trial_duration'] = array($values['trial_duration'], $values['trial_duration_type']);

    //unset($values['recurrence']);
//     unset($values['recurrence_type']);
//     //unset($values['duration']);
//     unset($values['duration_type']);

    unset($values['reminder_email_type']);
    //unset($values['trial_duration']);
    //unset($values['trial_duration_type']);

    $otherValues = array(
      'price' => $values['price'],
      //'recurrence' => $values['recurrence'],
      //'duration' => $values['duration'],
    );

    $values['price'] = $this->view->locale()->toNumber($values['price'], array('default_locale' => true));
    $values['modules'] = json_decode($values['modules']);
    $form->populate($values);

    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }
		
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Hack em up
    $form->populate($otherValues);

    // Process
    $values = $form->getValues();
    $values['modules'] = json_encode($values['modules']);

    /*
    $tmp = $values['recurrence'];
    unset($values['recurrence']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['recurrence'] = (int) $tmp[0];
    $values['recurrence_type'] = $tmp[1];

    $tmp = $values['duration'];
    unset($values['duration']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['duration'] = (int) $tmp[0];
    $values['duration_type'] = $tmp[1];

    $tmp = $values['trial_duration'];
    unset($values['trial_duration']);
    if( empty($tmp) || !is_array($tmp) ) {
      $tmp = array(null, null);
    }
    $values['trial_duration'] = (int) $tmp[0];
    $values['trial_duration_type'] = $tmp[1];
    */
    unset($values['price']);
    unset($values['recurrence']);
    unset($values['recurrence_type']);
    unset($values['duration']);
    unset($values['duration_type']);
    unset($values['trial_duration']);
    unset($values['trial_duration_type']);

    if( !empty($values['default']) && (float) $values['price'] > 0 ) {
      return $form->addError('Only a free plan may be the default plan.');
    }


    $tipTable = Engine_Api::_()->getDbtable('tips', 'eusertip');
    $db = $tipTable->getAdapter();
    $db->beginTransaction();

    try {

      // Update default
      if( !empty($values['default']) ) {
        $tipTable->update(array(
          'default' => 0,
        ), array(
          '`default` = ?' => 1,
        ));
      }

      // Update tip
      $tip->setFromArray($values);
      $tip->save();

      // Create tip in gateways?
      if( !$tip->isFree() ) {
        $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
        foreach( $gatewaysTable->fetchAll(array('enabled = ?' => 1)) as $gateway ) {
          $gatewayPlugin = $gateway->getGateway();
          // Check billing cycle support
          if( !$tip->isOneTime() ) {
            $sbc = $gateway->getGateway()->getSupportedBillingCycles();
            if( !in_array($tip->recurrence_type, array_map('strtolower', $sbc)) ) {
              continue;
            }
          }
          if( !method_exists($gatewayPlugin, 'createProduct') ||
              !method_exists($gatewayPlugin, 'editProduct') ||
              !method_exists($gatewayPlugin, 'detailVendorProduct') ) {
            continue;
          }
          // If it throws an exception, or returns empty, assume it doesn't exist?
          try {
            $info = $gatewayPlugin->detailVendorProduct($tip->getGatewayIdentity());
          } catch( Exception $e ) {
            $info = false;
          }
          // Create
          if( !$info ) {
            $gatewayPlugin->createProduct($tip->getGatewayParams());
          }
          // Edit
          else {
            $gatewayPlugin->editProduct($tip->getGatewayIdentity(), $tip->getGatewayParams());
          }
        }
      }

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    $form->addNotice('Your changes have been saved.');
    // Redirect
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage-tips'));
  }
  
  public function manageTipsAction() {
    
    $this->_helper->content->setEnabled();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $table = Engine_Api::_()->getDbtable('tips', 'eusertip');
    $select = $table->select()->where('user_id =?', $viewer->getIdentity());
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setCurrentPageNumber(1);
    $paginator->setItemCountPerPage(50);

  }

  public function accountDetailsAction() {
    
    $this->_helper->content->setEnabled();
    
    $this->view->user = $user = Engine_Api::_()->user()->getViewer();
    $viewer = Engine_Api::_()->user()->getViewer();

    $gateway_type = isset($_GET['gateway_type']) ? $_GET['gateway_type'] : "paypal";
    $userGateway = Engine_Api::_()->getDbtable('usergateways', 'eusertip')->getUserGateway(array('user_id' => $user->user_id, 'enabled' => true,'gateway_type'=>$gateway_type,));
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $userGatewayEnable = $settings->getSetting('sespage.userGateway', 'paypal');
    if($gateway_type == "paypal") {
        $userGatewayEnable = 'paypal';
        $this->view->form = $form = new Eusertip_Form_Index_PayPal();
        $gatewayTitle = 'Paypal';
        $gatewayClass= 'Eusertip_Plugin_Gateway_PayPal';
    } else if($gateway_type == "stripe") {
        $userGatewayEnable = 'stripe';
        $this->view->form = $form = new Sesadvpmnt_Form_Admin_Settings_Stripe();
        $gatewayTitle = 'Stripe';
        $gatewayClass= 'Eusertip_Plugin_Gateway_Event_Stripe';
    } else if($gateway_type == "paytm") {
        $userGatewayEnable = 'paytm';
        $this->view->form = $form = new Epaytm_Form_Admin_Settings_Paytm();
        $gatewayTitle = 'Paytm';
        $gatewayClass= 'Eusertip_Plugin_Gateway_Event_Paytm';
    }
    if (!empty($userGateway)) {
      $form->populate($userGateway->toArray());
      if (is_array($userGateway['config'])) {
        $form->populate($userGateway['config']);
      }
    }
    
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()) )
      return;
    // Process
    $values = $form->getValues();
    $enabled = (bool) $values['enabled'];
    unset($values['enabled']);
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $userGatewayTable = Engine_Api::_()->getDbtable('usergateways', 'eusertip');
    // insert data to table if not exists
    try {
      if (empty($userGateway)) {
        $gatewayObject = $userGatewayTable->createRow();
        $gatewayObject->user_id = $user->user_id;
        $gatewayObject->title = $gatewayTitle;
        $gatewayObject->plugin = $gatewayClass;
        $gatewayObject->gateway_type = $gateway_type;
        $gatewayObject->save();
      } else {
        $gatewayObject = Engine_Api::_()->getItem("eusertip_usergateway", $userGateway['usergateway_id']);
      }
      $db->commit();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    // Validate gateway config
    if ($enabled) {
      $gatewayObjectObj = $gatewayObject->getGateway();
      try {
        $gatewayObjectObj->setConfig($values);
        $response = $gatewayObjectObj->test();
      } catch (Exception $e) {
        $enabled = false;
        $form->populate(array('enabled' => false));
        $form->addError(sprintf('Gateway login failed. Please double check ' .
                        'your connection information. The gateway has been disabled. ' .
                        'The message was: [%2$d] %1$s', $e->getMessage(), $e->getCode()));
      }
    } else {
      $form->addError('Gateway is currently disabled.');
    }
    // Process
    $message = null;
    try {
      $values = $gatewayObject->getPlugin()->processAdminGatewayForm($values);
    } catch (Exception $e) {
      $message = $e->getMessage();
      $values = null;
    }
    if (null !== $values) {
      $gatewayObject->setFromArray(array(
          'enabled' => $enabled,
          'config' => $values,
      ));
      $gatewayObject->save();
      $form->addNotice('Changes saved.');
    } else {
      $form->addError($message);
    }
  }

}
