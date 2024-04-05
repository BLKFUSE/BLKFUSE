<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjoinfees_IndexController extends Core_Controller_Action_Standard
{
  public function init() {
	 if (!$this->_helper->requireUser->isValid())
			return;
    $id = $this->_getParam('order_id', null);
    $order = Engine_Api::_()->getItem('sescontestjoinfees_order', $id);
    if ($order) {
        Engine_Api::_()->core()->setSubject($order);
    }
	}
  public function viewAction(){
      $this->_helper->content->setEnabled();
  }
  public function joinContestAction()
  {
    $this->view->contest_id = $contest_id =  $this->_getParam('contest_id',0);
    $this->view->popupDescription = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.entry.popupdescription', 'This is a Paid contest, so you will have to make the payment as mentioned by the contest owner. Click on "Make Payment" button below to proceed with the submission of your entry to this contest.');
    $this->view->popupTitle = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.entry.popupTitle', 'Join Contest');
    	 // Gateways
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
    if ($contest_id) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      if ($contest) {
              $couponSessionCode = '-'.'-'.$contest->getType().'-'.$contest->contest_id.'-0';
              $currencyValue = 1;
              if($currentCurrency != $defaultCurrency){
                  $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
              }
              $priceTotal = @round($contest->entry_fees*$currencyValue,2);
              $this->view->itemPrice = @isset($_SESSION[$couponSessionCode]) ? round($priceTotal - $_SESSION[$couponSessionCode]['discount_amount']) : $priceTotal;
      }else
				return $this->_forward('requireauth', 'error', 'core');	
		}
    // For Coupon 
    $this->view->gateways = $gatewayPlugins;
  }
  public function processAction(){
    // Get gateway
    $gatewayId = $this->_getParam('gateway_id', null);
		$contest_id = $this->_getParam('contest_id', null);    
		$contest = null;
    if ($contest_id) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      if ($contest) {
     	 $this->view->contest = $contest ;
      }else
				return $this->_forward('requireauth', 'error', 'core');	
		}
		if(!$contest)
			return $this->_forward('requireauth', 'error', 'core');		
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();		
    if( !$gatewayId ||
        !($gateway = Engine_Api::_()->getItem('sescontestjoinfees_gateway', $gatewayId)) ||
        !($gateway->enabled) ) {
      header("location:".$this->view->escape($contest->getHref()));
			die;
    }		
    $this->view->gateway = $gateway;		
		$this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
		 // Prepare host info
    $schema =  _ENGINE_SSL ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // For Coupon 
    $couponSessionCode = '-'.'-'.$contest->getType().'-'.$contest->contest_id.'-0';
    
    //create order
    $table = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees');
    $order = $table->createRow();
    $order->contest_id = $contest_id;
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
      . $this->view->escape($this->view->url(array('action' => 'return','order_id'=>$order->getIdentity(),'contest_id'=>$order->contest_id)))
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
        'source_type' => 'sescontestjoinfees_order',
        'source_id' => $order->order_id,
    ));
		$session = new Zend_Session_Namespace();
    $session->sescontest_order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();    
    $params['vendor_order_id'] = $order_id;
    
    $currencyValue = 1;
    if($currentCurrency != $defaultCurrency){
        $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
    }
    $priceTotal = @round($contest->entry_fees*$currencyValue,2);
    $params['amount'] = @isset($_SESSION[$couponSessionCode]) ? round($priceTotal - $_SESSION[$couponSessionCode]['discount_amount']) : $priceTotal;
    //For Credit integration
    $creditCode =  'credit'.'-sescontestjoinfees-'.$order->contest_id.'-'.$order->contest_id;
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0) { 
      $params['amount'] = $sessionCredit->total_amount;
    }
    if($gateway->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe") {
      $params['currency'] = Engine_Api::_()->payment()->getCurrentCurrency();
      $this->view->publishKey = $gateway->config['sesadvpmnt_stripe_publish']; 
      $this->view->session = $plugin->createOrderTransaction($viewer,$order,$contest,$params);
      $this->renderScript('/application/modules/Sesadvpmnt/views/scripts/payment/index.tpl');
    } elseif($gateway->plugin  == "Epaytm_Plugin_Gateway_Paytm") {
        $paytmParams = $plugin->createOrderTransaction($viewer,$order,$contest,$params);
        $secretKey  = $gateway->config['paytm_secret_key'];
        $this->view->paytmParams = $paytmParams;
        $this->view->checksum = getChecksumFromArray($paytmParams, $secretKey);
        if($gateway->test_mode){
          $this->view->url = "https://securegw-stage.paytm.in/order/process";
        } else {
          $this->view->url = "https://securegw.paytm.in/merchant-status/getTxnStatus";
        }
         $this->renderScript('/application/modules/Epaytm/views/scripts/payment/index.tpl');
    } else if($gateway->plugin == "Ecashfree_Plugin_Gateway_Cashfree"){
         $params['process_url'] = $schema.$host. $this->view->url(array('action' => 'charge')).'?order_id=' . $order_id;
        $this->view->form = $plugin->customerInfo($viewer, $params);
        $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/process.tpl');
    } else {
          // Process transaction
          $transaction = $plugin->createOrderTransaction($viewer,$order,$contest,$params);
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
  public function chargeAction(){
    $this->view->form = $form = new Ecashfree_Form_Gateway_Cashfree();
    if (!$this->getRequest()->isPost()) {
      $form->populate($this->getRequest()->getPost());
      $this->view->error = 1;
      return;
    }
    if(!$form->isValid($this->getRequest()->getPost())){
      $form->populate($this->getRequest()->getPost());
      $this->view->error = 1;
      return;
    }
    try {
      $viewer = Engine_Api::_()->user()->getViewer();   
      $session = new Zend_Session_Namespace();
      // Get order
      $orderPaymentId = $session->sescontest_order_id;
      $orderPayment = Engine_Api::_()->getItem('payment_order', $orderPaymentId);
      if (!$orderPayment ||
         ($orderPayment->source_type != 'sescontestjoinfees_order') ||
         !($user_order = $orderPayment->getSource()) ) {
        return $this->_helper->redirector->gotoRoute(array(), 'sescontest_general', true);
      }
      $gateway = Engine_Api::_()->getItem('sescontestjoinfees_gateway', $orderPayment->gateway_id);    
      if( !$gateway ){
        return $this->_helper->redirector->gotoRoute(array(), 'sescontest_general', true);
      }
      $contest = Engine_Api::_()->getItem('contest', $user_order->contest_id);
      $gatewayPlugin = $gateway->getGateway();
      $plugin = $gateway->getPlugin();
      $postData = $form->getValues();
      $couponSessionCode = '-'.'-'.$contest->getType().'-'.$contest->contest_id.'-0';
      $postData['amount'] = @isset($_SESSION[$couponSessionCode]) ? round($contest->entry_fees- $_SESSION[$couponSessionCode]['discount_amount']) : $contest->entry_fees;
      $postData['couponSessionCode'] = $couponSessionCode;
      //For Credit integration
      $creditCode =  'credit'.'-sescontestjoinfees-'.$user_order->contest_id.'-'.$user_order->contest_id;
      $sessionCredit = new Zend_Session_Namespace($creditCode);
      if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0) { 
        $postData['amount'] = $sessionCredit->total_amount;
        $postData['creditCode'] = $creditCode;
      }

      $this->view->error = 0;
      $this->view->transactionUrl = $gatewayPlugin->getGatewayUrl();
      $this->view->transactionMethod = $gatewayPlugin->getGatewayMethod();
      $this->view->transactionData = $plugin->createOrderTransaction($viewer,$user_order,$contest,$postData);
    } catch(Exception $e){ throw $e;}
    $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/charge.tpl');
  }

  public function returnAction(){ 
		$this->view->order = $order = Engine_Api::_()->core()->getSubject();
		//if($order->state == 'complete')
			//return $this->_forward('notfound', 'error', 'core');
		$session = new Zend_Session_Namespace();
    // Get order
		$orderId = $this->_getParam('order_id', null);
		$orderPaymentId = $session->sescontest_order_id;
		$orderPayment = Engine_Api::_()->getItem('payment_order', $orderPaymentId);
    if (!$orderPayment || ($orderId != $orderPayment->source_id) ||
			 ($orderPayment->source_type != 'sescontestjoinfees_order') ||
			 !($user_order = $orderPayment->getSource()) ) {
			return $this->_helper->redirector->gotoRoute(array(), 'sescontest_general', true);
    }
    $gateway = Engine_Api::_()->getItem('sescontestjoinfees_gateway', $orderPayment->gateway_id);    
    if( !$gateway )
      return $this->_helper->redirector->gotoRoute(array(), 'sescontest_general', true);
    $contest_id = $this->_getParam('contest_id', null);    
    $contest = null;
    if ($contest_id) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
    }
    $params  = array();
    //For Coupon
    $couponSessionCode = '-'.'-'.$contest->getType().'-'.$contest->contest_id.'-0';
    $params['amount'] = @isset($_SESSION[$couponSessionCode]) ? round($contest->entry_fees- $_SESSION[$couponSessionCode]['discount_amount']) : $contest->entry_fees;
    $params['couponSessionCode'] = $couponSessionCode;
    //For Credit integration
    $creditCode =  'credit'.'-sescontestjoinfees-'.$order->contest_id.'-'.$order->contest_id;
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
  protected function _finishPayment($state = 'active',$orderPaymentId) {
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

	public function successAction(){
		$session = new Zend_Session_Namespace();
		$order_id = $this->_getParam('order_id', null); 
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();	
    $this->view->order = $order = Engine_Api::_()->core()->getSubject(); 
		if (!$order || $order->owner_id != $viewer->getIdentity())
      return $this->_forward('notfound', 'error', 'core');
		if(!$order_id)
			return $this->_forward('notfound', 'error', 'core');
		$contest_id = $this->_getParam('contest_id', null);
		$contest = null;
    if ($contest_id) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      if ($contest) {
     	 $this->view->contest = $contest ;
      }else
				return $this->_forward('notfound', 'error', 'core');	
		}
		if(!$contest)
			return $this->_forward('notfound', 'error', 'core');	
		$state = $this->_getParam('state');
	  if(!$state)
      return $this->_forward('notfound', 'error', 'core');
		$this->view->error = $error =  $session->errorMessage;
		$session->unsetAll();
		$this->view->state = $state;
		
	}
  
}
