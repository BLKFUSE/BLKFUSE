<?php

class Sescontestpackage_PaymentController extends Core_Controller_Action_Standard
{
  /**
   * @var User_Model_User
   */
  protected $_user;
  
  /**
   * @var Zend_Session_Namespace
   */
  protected $_session;

  /**
   * @var Payment_Model_Order
   */
  protected $_order;

  /**
   * @var Payment_Model_Gateway
   */
  protected $_gateway;

  /**
   * @var Payment_Model_Subscription
   */
  protected $_item;

  /**
   * @var Payment_Model_Package
   */
  protected $_package;
  
  public function init()
  {
    // If there are no enabled gateways or packages, disable
    if( Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() <= 0 ||
        Engine_Api::_()->getDbtable('packages', 'sescontestpackage')->getEnabledNonFreePackageCount() <= 0 ) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
    }
    
    // Get user and session
    $this->_user = Engine_Api::_()->user()->getViewer();
    $this->_session = new Zend_Session_Namespace('Payment_Sescontestpackage');
		$this->_session->gateway_id = $this->_getParam('gateway_id',0);
    $this->_item = Engine_Api::_()->getItem('contest',$this->_getParam('contest_id'));
		if(!$this->_item)
			 return $this->_helper->redirector->gotoRoute(array('action'=>'create'), 'sescontest_general', true);
    // Check viewer and user
    if( !$this->_user || !$this->_user->getIdentity() ) {
      if( !empty($this->_session->user_id) ) {
        $this->_user = Engine_Api::_()->getItem('user', $this->_session->user_id);
      }
      // If no user, redirect to home?
      if( !$this->_user || !$this->_user->getIdentity() ) {
        $this->_session->unsetAll();
        return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
      }
    }
  }

  public function indexAction()
  {
    return $this->_forward('gateway');
  }

  public function gatewayAction()
  {
		
		$item = $this->_item;
    if(!($item)  ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'sescontest_general',true);
    }
    $this->view->item = $item;
    
    // Check subscription status
    if( $this->_checkItemStatus($item) ) {
      return;
    }

    // Get package
    if( !$this->_user ||
        $item->user_id != $this->_user->getIdentity() ||
        !($package = Engine_Api::_()->getItem('sescontestpackage_package', $item->package_id)) ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'sescontest_general',true);
    }
    $this->view->package = $package;

    // Unset certain keys
    unset($this->_session->gateway_id);
    unset($this->_session->order_id);

    // Gateways
    $gatewayTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    $gatewaySelect = $gatewayTable->select()
      ->where('enabled = ?', 1)
      ;
    $gateways = $gatewayTable->fetchAll($gatewaySelect);

    $gatewayPlugins = array();
    foreach( $gateways as $gateway ) {
      // Check billing cycle support
      if( !$package->isOneTime() ) {
        $sbc = $gateway->getGateway()->getSupportedBillingCycles();
        if( !engine_in_array($package->recurrence_type, array_map('strtolower', $sbc)) ) {
          //continue;
        }
      }
      $gatewayPlugins[] = array(
        'gateway' => $gateway,
        'plugin' => $gateway->getGateway(),
      );
    }
    
    // For Coupon 
    $this->view->couponSessionCode = $package->getType().'-'.$package->package_id.'-'.$this->_item->getType().'-'.$this->_item->contest_id.'-1';
    $this->view->itemPrice = @isset($_SESSION[$this->view->couponSessionCode]) ? round($package->price - $_SESSION[$this->view->couponSessionCode]['discount_amount']) : $package->price;
    
    $this->view->gateways = $gatewayPlugins;
		$this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescontest_main');
  }

  public function processAction()
  {
    // Get gateway
    $gatewayId = $this->_getParam('gateway_id', $this->_session->gateway_id);
    
    if( !$gatewayId ||
        !($gateway = Engine_Api::_()->getDbtable('gateways', 'sescontestpackage')->find($gatewayId)->current()) ||
        !($gateway->enabled) ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'gateway'));
    }
    $this->view->gateway = $gateway;

    // Get package
    if( !$gatewayId ||
        !($package = $this->_item->getPackage())  ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }

    // Get package
    if( !$package || $package->isFree() ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
    $this->view->package = $package;

    // Check package?
    if( $this->_checkItemStatus($this->_item) ) {
      return;
    }
    // Process
    
     // For Coupon 
    $couponSessionCode = $package->getType().'-'.$package->package_id.'-'.$this->_item->getType().'-'.$this->_item->contest_id.'-1';
    
    // Create order
    $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
    if( !empty($this->_session->order_id) ) {
      $previousOrder = $ordersTable->find($this->_session->order_id)->current();
      if( $previousOrder && $previousOrder->state == 'pending' ) {
        $previousOrder->state = 'incomplete';
        $previousOrder->save();
      }
    }
    $ordersTable->insert(array(
      'user_id' => $this->_user->getIdentity(),
      'gateway_id' => $gateway->gateway_id,
      'state' => 'pending',
      'creation_date' => new Zend_Db_Expr('NOW()'),
      'source_type' => 'contest',
      'source_id' => $this->_item->getIdentity(),
    ));
    $this->_session->order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();
		$this->_session->currency = $currentCurrency = Engine_Api::_()->payment()->getCurrentCurrency();
		$settings = Engine_Api::_()->getApi('settings', 'core');
		$currencyData = Engine_Api::_()->getDbTable('currencies', 'payment')->getCurrency($currentCurrency);
    $this->_session->change_rate = $currencyData->change_rate;
    // Unset certain keys
    unset($this->_session->package_id);
    unset($this->_session->contest_id);
    unset($this->_session->gateway_id);
    // Get gateway plugin
    $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
    $plugin = $gateway->getPlugin();
    // Prepare host info
    $schema =  _ENGINE_SSL ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
  
    // Prepare transaction
    $params = array();
    $params['language'] = $this->_user->language;
    $localeParts = explode('_', $this->_user->language);
    if( engine_count($localeParts) > 1 ) {
      $params['region'] = $localeParts[1];
    }
    $params['vendor_order_id'] = $order_id;
    $params['return_url'] = $schema . $host
      . $this->view->url(array('action' => 'return'))
      . '?order_id=' . $order_id
      . '&state=' . 'return';
    $params['cancel_url'] = $schema . $host
      . $this->view->url(array('action' => 'return'))
      . '?order_id=' . $order_id
      . '&state=' . 'cancel';
    $params['ipn_url'] = $schema . $host . $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'sescontestpackage'), 'default') . '?order_id=' . $order_id.'&gateway_id='.$gatewayId;
    $params['amount'] = $this->getPrice($this->_item,$package,$package->price);
     // Process transaction
    if($gateway->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe") {
          $params['currency'] =  $currentCurrency = Engine_Api::_()->payment()->getCurrentCurrency();
          $this->view->publishKey = $gateway->config['sesadvpmnt_stripe_publish'];
          $this->view->session = $plugin->createContestTransaction($this->_user,
            $this->_item, $package, $params); 
          $this->renderScript('/application/modules/Sesadvpmnt/views/scripts/payment/index.tpl');
    } elseif($gateway->plugin  == "Epaytm_Plugin_Gateway_Paytm") {
        $paytmParams = $plugin->createContestTransaction($this->_user,
        $this->_item, $package, $params);
        $secretKey  = $gateway->config['paytm_secret_key'];
        $this->view->paytmParams = $paytmParams;
        //echo "<pre>";print_r($paytmParams);die;
        // Pull transaction params
        $this->view->checksum = getChecksumFromArray($paytmParams, $secretKey);
        if($gateway->test_mode){
          $this->view->url = "https://securegw-stage.paytm.in/order/process";
        } else {
          $this->view->url = "https://securegw.paytm.in/merchant-status/getTxnStatus";
        }
         $this->renderScript('/application/modules/Epaytm/views/scripts/payment/index.tpl');
    } else if($gateway->plugin == "Ecashfree_Plugin_Gateway_Cashfree"){
      $params['process_url'] = $schema.$host. $this->view->url(array('action' => 'charge')). '?order_id=' . $order_id;
      $this->view->form = $plugin->customerInfo($this->_user, $params);
      $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/process.tpl');
    } else {
        // Process transaction
        $transaction = $plugin->createContestTransaction($this->_user,
            $this->_item, $package, $params);
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
      $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/charge.tpl');
      return;
    }
    if(!$form->isValid($this->getRequest()->getPost())){
      $form->populate($this->getRequest()->getPost());
      $this->view->error = 1;
      $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/charge.tpl');
      return;
    }
    try { 
      // Get order
      if( !$this->_user ||
          !($orderId = $this->_getParam('order_id', $this->_session->order_id)) ||
          !($order = Engine_Api::_()->getItem('payment_order', $orderId)) ||
          $order->user_id != $this->_user->getIdentity() ||
          $order->source_type != 'contest' ||
          !($item = $order->getSource()) ||
          !($package = $this->_item->getPackage()) ||
          !($gateway = Engine_Api::_()->getItem('sescontestpackage_gateway', $order->gateway_id)) ) {
        return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
      }
      $package = $this->_item->getPackage();
      $postData = $form->getValues();
      $postData['amount'] = $this->getPrice($this->_item,$package,$package->price);

      $viewer = Engine_Api::_()->user()->getViewer();
      $gatewayPlugin = $gateway->getGateway($gateway->plugin);
      $plugin = $gateway->getPlugin($gateway->plugin);
     
      $this->view->error = 0;
      $this->view->transactionUrl = $gatewayPlugin->getGatewayUrl();
      $this->view->transactionMethod = $gatewayPlugin->getGatewayMethod();
      $this->view->transactionData = $plugin->createContestTransaction($this->_user,
        $this->_item,$package,$postData);
    } catch(Exception $e){ throw $e;}
    $this->renderScript('/application/modules/Ecashfree/views/scripts/payment/charge.tpl');
  }

  public function getPrice($item,$package,$amount){
    //For Coupon
    $couponSessionCode = $this->getCouponSessionCode($item,$package);
    $amount = @isset($_SESSION[$couponSessionCode]) ? round($amount - $_SESSION[$couponSessionCode]['discount_amount']) : $amount;
  
    //For Credit integration
    $creditCode =  $this->getCreditCode($item,$package);
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0) { 
      $amount = $sessionCredit->total_amount;
    }
    return $amount;
  }
  public function getCreditCode($item,$package){
    return 'credit'.'-sescommunityads-'.$package->package_id.'-'.$item->contest_id;
  }
  public function getCouponSessionCode($item,$package){
    return $package->getType().'-'.$package->package_id.'-'.$item->getType().'-'.$item->contest_id.'-1';
  }

  public function returnAction()
  {
    // Get order
    if( !$this->_user ||
        !($orderId = $this->_getParam('order_id', $this->_session->order_id)) ||
        !($order = Engine_Api::_()->getItem('payment_order', $orderId)) ||
        $order->user_id != $this->_user->getIdentity() ||
        $order->source_type != 'contest' ||
        !($item = $order->getSource()) ||
        !($package = $this->_item->getPackage()) ||
        !($gateway = Engine_Api::_()->getItem('sescontestpackage_gateway', $order->gateway_id)) ) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
    }
    // Get gateway plugin
    $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
    $plugin = $gateway->getPlugin();
    
     $params  = array();
  
    $params['creditCode'] = $this->getCreditCode($this->_item,$package);
    $params['couponSessionCode'] = $this->getCouponSessionCode($this->_item,$package);
    $params['amount'] = $this->getPrice($this->_item,$package,$package->price);
    
    // Process return
    unset($this->_session->errorMessage);
    try {
        $status = $plugin->onContestTransactionReturn($order, array_merge($this->_getAllParams(),$params));
    } catch(Payment_Model_Exception $e ) {
      $status = 'failure';
      $this->_session->errorMessage = $e->getMessage();
    }
    return $this->_finishPayment($status);
  }
  public function finishAction()
  {
    $this->view->status = $status = $this->_getParam('state');
    $this->view->error = $this->_session->errorMessage;
		$this->view->contest_id = $contest_id = $this->_getParam('contest_id');
		if(!$contest_id)
			return $this->_forward('notfound', 'error', 'core');
  }



  protected function _checkItemStatus(
      Zend_Db_Table_Row_Abstract $item = null)
  {
		
    if( !$this->_user ) {
      return false;
    }

    if($item->getPackage()->isFree() ) {
        $this->_finishPayment('active');
				return true;
     }
    return false;
  }

  protected function _finishPayment($state = 'active')
  {
		
    $viewer = Engine_Api::_()->user()->getViewer();
    $user = $this->_user;

    // No user?
    if( !$this->_user ) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
    }

    // Redirect
    if( $state == 'free' ) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
    } else {
      return $this->_helper->redirector->gotoRoute(array('action' => 'finish', 'state' => $state));
    }
  }
  
  protected function _checkDefaultPaymentPlan()
  {
    // No user?
    if( !$this->_user ) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage'), 'sescontest_general', true);
    }
    
    // Handle default payment plan
    try {
      $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
      if( $subscriptionsTable ) {
        $subscription = $subscriptionsTable->activateDefaultPlan($this->_user);
        if( $subscription ) {
          return $this->_finishPayment('free');
        }
      }
    } catch( Exception $e ) {
      // Silence
    }
    
    // Fall-through
  }
}
