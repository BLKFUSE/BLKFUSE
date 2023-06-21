<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Ecashfree
 * @package    Ecashfree
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Cashfree.php  2019-04-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
include_once APPLICATION_PATH . "/application/modules/Ecashfree/Api/Cashfree.php";
class Sescredit_Plugin_Gateway_Cashfree extends Engine_Payment_Plugin_Abstract
{
  protected $_gatewayInfo;
  protected $_gateway;
  public function __construct(Zend_Db_Table_Row_Abstract $gatewayInfo)
  {
      $this->_gatewayInfo = $gatewayInfo;
  }

  public function getService()
  {
    return $this->getGateway()->getService();
  }

  public function getGateway()
  {
    if( null === $this->_gateway ) {
        $class = 'Ecashfree_Gateways_Cashfree';
        Engine_Loader::loadClass($class);
        $gateway = new $class(array(
        'config' => (array) $this->_gatewayInfo->config,
        'testMode' => $this->_gatewayInfo->test_mode,
        'currency' => Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'),
      ));
      if( !($gateway instanceof Engine_Payment_Gateway) ) {
        throw new Engine_Exception('Plugin class not instance of Engine_Payment_Gateway');
      }
      $this->_gateway = $gateway;
    }
    return $this->_gateway;
  }

  public function createTransaction(array $params)
  {
    $transaction = new Engine_Payment_Transaction($params);
    $transaction->process($this->getGateway());
    return $transaction;
  }

  public function createIpn(array $params)
  {
    $ipn = new Engine_Payment_Ipn($params);
    $ipn->process($this->getGateway());
    return $ipn;
  }

  public function createSubscriptionTransaction(User_Model_User $user,
      Zend_Db_Table_Row_Abstract $subscription,
      Payment_Model_Package $package,
      array $params = array()){}
  public function createPageTransaction(User_Model_User $user, array $params = array()) {
    $secretKey = $this->_gatewayInfo->config['ecashfree_secretkey'];
    $params['appId'] = $this->_gatewayInfo->config['ecashfree_appid'];
    $params['orderAmount'] = $params['amount'];
    $params['orderCurrency'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
    ksort($params);
    $signatureData = "";
    foreach ($params as $key => $value){
      $signatureData .= $key.$value;
    }    
    $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
    $params['signature'] = base64_encode($signature);
    return $params;
  }

  public function customerInfo(User_Model_User $user,array $params = array()){
    $form = new Ecashfree_Form_Gateway_Cashfree();
    $form->orderId->setValue($params['vendor_order_id']);
    $form->returnUrl->setValue($params['return_url']);
    $form->notifyUrl->setValue($params['ipn_url']);
    $form->setAction($params['process_url']);
    return $form;
  }
  public function onPageTransactionReturn(Payment_Model_Order $order, $params) {
    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    $user = $order->getUser();
    $item = $order->getSource();
    // Check subscription state
    $paymentStatus = null;
    $orderStatus = null;
    switch($params['txStatus']) {
      case 'PENDING':
        $paymentStatus = 'pending';
        $orderStatus = 'complete';
        break;
      case 'SUCCESS':
        $paymentStatus = 'active';
        $orderStatus = 'complete';
        break;
      case 'FAILED': // Probably doesn't apply
      case 'CANCELLED': // Probably doesn't apply
      default: // No idea what's going on here
        $paymentStatus = 'failed';
        $orderStatus = 'failed'; // This should probably be 'failed'
        break;
    }
   // Update order with profile info and complete status?
    $order->state = $orderStatus;
    $order->gateway_transaction_id = $params['referenceId'];
    $order->save();
    $session = new Zend_Session_Namespace('Payment_Sescredit');
    $currency = $session->currency;
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sescredit');
    $transactionsTable->insert(array(
        'owner_id' => $order->user_id,
        'gateway_id' => $this->_gatewayInfo->gateway_id,
        'gateway_transaction_id' => $params['referenceId'],
        'creation_date' => new Zend_Db_Expr('NOW()'),
        'modified_date' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'state' => $paymentStatus,
        'gateway_type' => 'Cashfree',
        'total_amount' => $params['orderAmount'],
        'currency_symbol' => Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
    ));
    $transaction_id = $transactionsTable->getAdapter()->lastInsertId();
    $item->transaction_id = $transaction_id;
    $item->save();
    $transaction = Engine_Api::_()->getItem('sescredit_transaction', $transaction_id);
    // Get benefit setting
    $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'sescredit')
            ->getBenefitStatus($user);
    //For Coupon
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')){
      $transaction->ordercoupon_id = Engine_Api::_()->ecoupon()->setAppliedCouponDetails($params['couponSessionCode']);
    }
    // Check payment status
    if ($paymentStatus == 'okay' || $paymentStatus == 'active' ||
            ($paymentStatus == 'pending' && $giveBenefit)) {
      //Update subscription info
      $transaction->gateway_id = $this->_gatewayInfo->gateway_id;
      $transaction->save();
      // Payment success
      $transaction = $item->onPaymentSuccess();
      return 'active';
    } else if ($paymentStatus == 'pending') {
      // Update subscription info
      $transaction->gateway_id = $this->_gatewayInfo->gateway_id;
      $transaction->save();
      // Payment pending
      $item->onPaymentPending();
      return 'pending';
    } else if ($paymentStatus == 'failed') {
      // Cancel order and subscription?
      $order->onFailure();
      $item->onPaymentFailure();
      //Send to user for refunded
      // Payment failed
      throw new Payment_Model_Exception('Your payment could not be ' .
      'completed. Please ensure there are sufficient available funds ' .
      'in your account.');
    } else {
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
      'transaction. Please try again later.');
    }
  }

  public function onSubscriptionTransactionReturn(Payment_Model_Order $order,array $params = array()){
   // Check that gateways match
    if( $order->gateway_id != $this->_gatewayInfo->gateway_id ) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }

    // Get related info
    $user = $order->getUser();
    $subscription = $order->getSource();
    $package = $subscription->getPackage();

    // Check subscription state
    if($subscription->status == 'trial') {
      return 'active';
    } else if( $subscription->status == 'pending' ) {
      return 'pending';
    }
    // One-time
    if( $package->isOneTime() ) {
      // Get payment state
      $paymentStatus = null;
      $orderStatus = null;
      switch( strtolower($params['txStatus']) ) {
        case 'PENDING':
          $paymentStatus = 'pending';
          $orderStatus = 'complete';
          break;

        case 'SUCCESS':
          $paymentStatus = 'okay';
          $orderStatus = 'complete';
          break;
        case 'FAILED': // Probably doesn't apply
        case 'CANCELLED': // Probably doesn't apply
        default: // No idea what's going on here
          $paymentStatus = 'failed';
          $orderStatus = 'failed'; // This should probably be 'failed'
          break;
      }

      // Update order with profile info and complete status?
      $order->state = $orderStatus;
      $order->gateway_transaction_id = $params['referenceId'];
      $order->save();

      // Insert transaction
      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
      $transactionsTable->insert(array(
        'user_id' => $order->user_id,
        'gateway_id' => $this->_gatewayInfo->gateway_id,
        'timestamp' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'type' => 'payment',
        'state' => $paymentStatus,
        'gateway_transaction_id' => $params['referenceId'],
        'amount' => $params['orderAmount'], // @todo use this or gross (-fee)?
        'currency' => Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'),
      ));

      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user);

      // Check payment status
      if( $paymentStatus == 'okay' ||
          ($paymentStatus == 'pending' && $giveBenefit) ) {

        // Update subscription info
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $params['referenceId'];

        // Payment success
        $subscription->onPaymentSuccess();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }

        return 'active';
      }
      else if( $paymentStatus == 'pending' ) {

        // Update subscription info
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['referenceId'];

        // Payment pending
        $subscription->onPaymentPending();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_pending', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }

        return 'pending';
      }
      else if( $paymentStatus == 'failed' ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // Payment failed
        throw new Payment_Model_Exception('Your payment could not be ' .
            'completed. Please ensure there are sufficient available funds ' .
            'in your account.');
      }
      else {
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
    }
    // Recurring
    else {


      // Let's log it
      $this->getGateway()->getLog()->log('CreateRecurringPaymentsProfile: '
          . print_r($rdata, true), Zend_Log::INFO);

      // Check returned profile id
      if( empty($rdata['PROFILEID']) ) {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
      $profileId = $rdata['PROFILEID'];

      // Update order with profile info and complete status?
      $order->state = 'complete';
      $order->gateway_order_id = $profileId;
      $order->save();

      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user);

      // Check profile status
      if( $rdata['PROFILESTATUS'] == 'ActiveProfile' ||
          ($rdata['PROFILESTATUS'] == 'PendingProfile' && $giveBenefit) ) {
        // Enable now
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PROFILEID'];
        $subscription->onPaymentSuccess();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }

        return 'active';

      } else if( $rdata['PROFILESTATUS'] == 'PendingProfile' ) {
        // Enable later
        $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
        $subscription->gateway_profile_id = $rdata['PROFILEID'];
        $subscription->onPaymentPending();

        // send notification
        if( $subscription->didStatusChange() ) {
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_pending', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));
        }

        return 'pending';

      } else {
        // Cancel order and subscription?
        $order->onFailure();
        $subscription->onPaymentFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
            'transaction. Please try again later.');
      }
    }
  }
  public function onSubscriptionTransactionIpn(Payment_Model_Order $order,Engine_Payment_Ipn $ipn){}
  public function cancelSubscription($transactionId, $note = null)
  {
    return $this;
  }
  /**
   * Generate href to a page detailing the order
   *
   * @param string $transactionId
   * @return string
   */
  public function getOrderDetailLink($orderId)
  {
    if( $this->getGateway()->getTestMode() ) {
      // Note: it doesn't work in test mode
      return 'https://www.cashfree.com/test/search?query' . $orderId;
    } else {
      return 'https://www.cashfree.com/search?query' . $orderId;
    }
  }

  public function getTransactionDetailLink($transactionId)
  {
    if( $this->getGateway()->getTestMode() ) {
      // Note: it doesn't work in test mode
      return 'https://www.cashfree.com/test/search?query' . $transactionId;
    } else {
      return 'https://www.cashfree.com/search?query' . $transactionId;
    }
  }

  public function getOrderDetails($orderId)
  {
    try {
      return $this->getService()->detailRecurringPaymentsProfile($orderId);
    } catch( Exception $e ) {
      echo $e;
    }

    try {
      return $this->getTransactionDetails($orderId);
    } catch( Exception $e ) {
      echo $e;
    }

    return false;
  }

  public function getTransactionDetails($transactionId)
  {
    return $this->getService()->detailTransaction($transactionId);
  }

  public function createOrderTransaction($params = array()) {
  }
  public function createOrderTransactionReturn($order,$transaction) {

    return 'active';
  }
  function getSupportedCurrencies(){
      return array('INR'=>'INR');
 }
  public function getAdminGatewayForm(){
    return new Ecashfree_Form_Admin_Settings_Cashfree();
  }

  public function processAdminGatewayForm(array $values){
    return $values;
  }
  public function getGatewayUrl(){
  }
  function getSupportedBillingCycles(){
    return array(0=>'Day',1=>'Week',2=>'Month',3=>'Year');
  }
  // IPN

  /**
   * Process an IPN
   *
   * @param Engine_Payment_Ipn $ipn
   * @return Engine_Payment_Plugin_Abstract
   */
   public function onIpn(Engine_Payment_Ipn $ipn)
  {
  }
  public function cancelResourcePackage($transactionId, $note = null) {
  }

  public function onIpnTransaction($rawData){}
  public function onTransactionIpn(Payment_Model_Order $order,  $rawData) {
    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
        throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    // Get related info
    $user = $order->getUser();
    $source = $order->getSource();
    $package = $source->getPackage();
    return $this;
  }
  public function setConfig(){}
  public function test(){}

  /*public function getGatewayUserForm(){
    $form = new Ecashfree_Form_Gateway_Cashfree(array('settings'=>$this->_gatewayInfo->config));
    $form->populate((array) $this->_gatewayInfo->config);
    return $form;
  } */
}
