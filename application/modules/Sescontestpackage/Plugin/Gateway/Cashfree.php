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
class Sescontestpackage_Plugin_Gateway_Cashfree extends Engine_Payment_Plugin_Abstract
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
  public function createSubscriptionTransaction(User_Model_User $user, Zend_Db_Table_Row_Abstract $subscription, Payment_Model_Package $package, array $params = array()) {
  }
  public function createContestTransaction(User_Model_User $user,
      Zend_Db_Table_Row_Abstract $subscription,
      Zend_Db_Table_Row_Abstract $package,
      array $params = array())
  {
    // Process description
    $desc = $package->getPackageDescription();
    
    if( strlen($desc) > 127 ) {
      $desc = substr($desc, 0, 124) . '...';
    } else if( !$desc || strlen($desc) <= 0 ) {
      $desc = 'N/A';
    }
    if(function_exists('iconv') && strlen($desc) != iconv_strlen($desc) ) {
      // PayPal requires that DESC be single-byte characters
      $desc = @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $desc);
    }
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $currency = $settings->getSetting('payment.currency', 'USD');
      if($package->isOneTime()) {
      $secretKey = $this->_gatewayInfo->config['ecashfree_secretkey'];
      $params['appId'] = $this->_gatewayInfo->config['ecashfree_appid'];
      $params['orderAmount'] = $params['amount'];
      unset($params['amount']);
      $params['orderCurrency'] = $currency;
      ksort($params);
      $signatureData = "";
      foreach ($params as $key => $value){
        $signatureData .= $key.$value;
      }
      $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
      $params['signature'] = base64_encode($signature);
      return $params;
    } else  { 
      $cashfree = new Cashfree($this->_gatewayInfo->config['ecashfree_appid'],$this->_gatewayInfo->config['ecashfree_secretkey']);
      $planId = $package->getType()."_".$package->package_id;
      $plan = $cashfree->createPlan([
        'planId'=>$planId,
        'planName'=>$package->getTitle(),
        'amount' => $params['amount'],
        'type' => 'PERIODIC',
        'intervalType'=> $package->recurrence_type,
        'intervals'=> $package->recurrence,
        'description'=>$desc,
      ]);  
      $subscriber = $cashfree->createSubscription([
        'subscriptionId' => $params['orderId'],
        'planId'=>$planId,
        'customerName'=>$params['customerName'],
        'customerEmail'=>$params['customerEmail'],
        'customerPhone'=>$params['customerPhone'],
        'authAmount'=>$params['amount'],
        'expiresOn'=> date('Y-m-d H:i:s',$package->getExpirationDate()),
        'returnUrl'=>$params['returnUrl'],
      ]);
      if($subscriber->status == "OK"){
        header("Location: ".$subscriber->authLink);
        exit();
      } else {
        throw new Engine_Payment_Plugin_Exception($subscriber->message);
      }
    }
  }
  public function customerInfo(User_Model_User $user,array $params = array()){
    $form = new Ecashfree_Form_Gateway_Cashfree();
    $form->orderId->setValue($params['vendor_order_id']);
    $form->returnUrl->setValue($params['return_url']);
    $form->notifyUrl->setValue($params['ipn_url']);
    $form->setAction($params['process_url']);
    return $form;
  }
  public function onSubscriptionReturn(Payment_Model_Order $order,$transaction){}
  public function onContestTransactionReturn(Payment_Model_Order $order, $params) {
    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    $user = $order->getUser();
    $item = $order->getSource();
    $package = $item->getPackage();
    $transaction = $item->getTransaction();
    $paymentStatus = null;
    $orderStatus = null;
    $subscriptionStatus = null;
    if ($package->isOneTime()) {
      $status = $params['txStatus'];
    } else {
      $status = $params['cf_status'];
    }
    switch($status) {
      case 'PENDING':
      case 'INITIALIZED':
      case 'BANK_APPROVAL_PENDING':
      case 'ON_HOLD':
        $paymentStatus = 'pending';
        $orderStatus = 'complete';
        $subscriptionStatus = 'pending';
        break;
      case 'SUCCESS':
      case 'COMPLETED':
      case 'ACTIVE':
        $paymentStatus = 'okay';
        $orderStatus = 'complete';
        $subscriptionStatus = 'active';
        break;
      case 'FAILED': // Probably doesn't apply
      case 'CANCELLED': // Probably doesn't apply
      default: // No idea what's going on here
        $paymentStatus = 'failed';
        $orderStatus = 'failed'; // This should probably be 'failed'
        $subscriptionStatus = 'failed';
        break;
    }
    // One-time
    if ($package->isOneTime()) {
      // Update order with profile info and complete status?
      $order->state = $orderStatus;
      $order->gateway_transaction_id = $params['referenceId'];
      $order->save();
      $orderPackageId = $item->existing_package_order ? $item->existing_package_order : false;
      $orderPackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $orderPackageId);
      if (!$orderPackageId || !$orderPackage) {
        $transactionsOrdersTable = Engine_Api::_()->getDbtable('orderspackages', 'sescontestpackage');
        $transactionsOrdersTable->insert(array(
            'owner_id' => $order->user_id,
            'item_count' => ($package->item_count - 1 ),
            'package_id' => $package->getIdentity(),
            'state' => $paymentStatus,
            'expiration_date' => $package->getExpirationDate(),
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'creation_date' => new Zend_Db_Expr('NOW()'),
            'modified_date' => new Zend_Db_Expr('NOW()'),
        ));
        $orderPackageId = $transactionsOrdersTable->getAdapter()->lastInsertId();
      } else {
        $orderPackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $orderPackageId);
        $orderPackage->item_count = $orderPackage->item_count--;
        $orderPackage->save();
        $orderPackageId = $orderPackage->getIdentity();
      }
      $session = new Zend_Session_Namespace('Payment_Sescontestpackage');
      $currency = $session->currency;
      $rate = $session->change_rate;
      if (!$rate)
        $rate = 1;
      $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $currencyValue = 1;
      if ($currency != $defaultCurrency)
        $currencyValue = $settings->getSetting('sesmultiplecurrency.' . $currency);
      $price = @round(($params['amount'] * $currencyValue), 2);
      //Insert transaction
      $daysLeft = 0;
      //check previous transaction if any for reniew
      if (!empty($transaction->expiration_date) && $transaction->expiration_date != '3000-00-00 00:00:00') {
        $expiration = $package->getExpirationDate();
        //check isonetime condition and renew exiration date if left
        if ($package->isOneTime()) {
          $datediff = strtotime($transaction->expiration_date) - time();
          $daysLeft = floor($datediff / (60 * 60 * 24));
        }
      }
      $oldOrderPackageId = $item->orderspackage_id;
      $tableContest = Engine_Api::_()->getDbTable('contests', 'sescontest');
      if (!empty($oldOrderPackageId)) {
        $select = $tableContest->select()->from($tableContest->info('name'))->where('orderspackage_id =?', $oldOrderPackageId);
        $totalItemCreated = engine_count($tableContest->fetchAll($select));
        if ($package->item_count >= $totalItemCreated && $package->item_count)
          $leftContest = $package->item_count - $totalItemCreated;
        else if (!$package->item_count)
          $leftContest = -1;
        else
          $leftContest = 0;
      } else
      $leftContest = $package->item_count - 1;
      $tableContest->update(array('orderspackage_id' => $orderPackageId), array('orderspackage_id' => $oldOrderPackageId));
      $packageOrder = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $orderPackageId);
      $packageOrder->item_count = $leftContest;
      $packageOrder->save();
      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sescontestpackage');
      $transactionsTable->insert(array(
          'owner_id' => $order->user_id,
          'package_id' => $item->package_id,
          'item_count' => $leftContest,
          'gateway_id' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id' => $params['referenceId'],
          'creation_date' => new Zend_Db_Expr('NOW()'),
          'modified_date' => new Zend_Db_Expr('NOW()'),
          'order_id' => $order->order_id,
          'orderspackage_id' => $orderPackageId,
          'state' => 'initial',
          'total_amount' => $params['orderAmount'],
          'change_rate' => $rate,
          'gateway_type' => 'Cashfree',
          'currency_symbol' => $currency,
          'ip_address' => $_SERVER['REMOTE_ADDR'],
      ));
      $transaction_id = $transactionsTable->getAdapter()->lastInsertId();
      $item->transaction_id = $transaction_id;
      $item->orderspackage_id = $orderPackageId;
      $item->existing_package_order = 0;
      $item->save();
      $transaction = Engine_Api::_()->getItem('sescontestpackage_transaction', $transaction_id);
      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'sescontestpackage')
              ->getBenefitStatus($user);
      // Check payment status
      if ($paymentStatus == 'okay' || $paymentStatus == 'active' ||
              ($paymentStatus == 'pending' && $giveBenefit)) {
        //Update subscription info
        $transaction->gateway_id = $this->_gatewayInfo->gateway_id;
        $transaction->gateway_profile_id = $params['referenceId'];
        $transaction->save();
        // Payment success
        $transaction = $item->onPaymentSuccess();
        if ($daysLeft >= 1) {
          $expiration_date = date('Y-m-d H:i:s', strtotime($transaction->expiration_date . '+ ' . $daysLeft . ' days'));
          $transaction->expiration_date = $expiration_date;
          $transaction->save();
          $orderpackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $orderPackageId);
          $orderpackage->expiration_date = $expiration_date;
          $orderpackage->save();
        }
        //For Coupon Plugin
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')){
          $transaction->ordercoupon_id = Engine_Api::_()->ecoupon()->setAppliedCouponDetails($params['couponSessionCode']);
          $transaction->save();
        }
        //For Credit 
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit') && isset($params['creditCode'])) {
          $sessionCredit = new Zend_Session_Namespace($params['creditCode']);
          $transaction->credit_point = $sessionCredit->credit_value;  
          $transaction->credit_value =  $sessionCredit->purchaseValue;
          $transaction->save();
          if($sessionCredit->credit_value){
            $userCreditDetailTable = Engine_Api::_()->getDbTable('details', 'sescredit');
            $userCreditDetailTable->update(array('total_credit' => new Zend_Db_Expr('total_credit - ' . $sessionCredit->credit_value)), array('owner_id =?' => $order->user_id));
          }
        }
        // send notification
      
        return 'active';
      } else if ($paymentStatus == 'pending') {
        // Update subscription info
        $transaction->gateway_id = $this->_gatewayInfo->gateway_id;
        $transaction->gateway_profile_id = $params['referenceId'];
        $transaction->save();
        // Payment pending
        $item->onPaymentPending();

        return 'pending';
      } else if ($paymentStatus == 'failed') {
        // Cancel order and subscription?
        $order->onFailure();
        $item->onPaymentFailure();
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
    // Recurring
    else {
      $isExistsOrderPackageId = $orderPackageId = $item->existing_package_order ? $item->existing_package_order : false;
      if (!$orderPackageId) {
        if (!$orderPackageId) {
          $transactionsOrdersTable = Engine_Api::_()->getDbtable('orderspackages', 'sescontestpackage');
          $transactionsOrdersTable->insert(array(
              'owner_id' => $order->user_id,
              'item_count' => ($package->item_count - 1 ),
              'state' => $subscriptionStatus,
              'package_id' => $package->getIdentity(),
              'expiration_date' => $package->getExpirationDate(),
              'ip_address' => $_SERVER['REMOTE_ADDR'],
              'creation_date' => new Zend_Db_Expr('NOW()'),
              'modified_date' => new Zend_Db_Expr('NOW()'),
          ));
          $orderPackageId = $transactionsOrdersTable->getAdapter()->lastInsertId();
        }
      } else {
        $orderPackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $orderPackageId);
        $orderPackage->item_count = $orderPackage->item_count--;
        $orderPackage->save();
      }
      $item->existing_package_order = 0;
      $item->save();
      $session = new Zend_Session_Namespace('Payment_Sescontestpackage');
      $currency = $session->currency;
      $rate = $session->change_rate;
      if (!$rate)
        $rate = 1;
      $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $currencyValue = 1;
      if ($currency != $defaultCurrency) {
        $currencyValue = $settings->getSetting('sesmultiplecurrency.' . $currency);
      }
      $price = @round(($params['amount'] * $currencyValue), 2);
      // Insert transaction
      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sescontestpackage');
      $transactionsTable->insert(array(
          'owner_id' => $order->user_id,
          'package_id' => $item->package_id,
          'item_count' => ($package->item_count - 1),
          'gateway_id' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id' => '',
          'orderspackage_id' => $orderPackageId,
          'creation_date' => new Zend_Db_Expr('NOW()'),
          'modified_date' => new Zend_Db_Expr('NOW()'),
          'order_id' => $order->order_id,
          'state' => 'initial',
          'total_amount' => $params['cf_authAmount'],
          'change_rate' => $rate,
          'gateway_type' => 'Cashfree',
          'currency_symbol' => $currency,
          'ip_address' => $_SERVER['REMOTE_ADDR'],
      ));
      $transaction_id = $transactionsTable->getAdapter()->lastInsertId();
      $item->transaction_id = $transaction_id;
      $item->orderspackage_id = $orderPackageId;
      $item->save();
      $transaction = Engine_Api::_()->getItem('sescontestpackage_transaction', $transaction_id);
      if (!$isExistsOrderPackageId) {
        // Update order with profile info and complete status?
        $order->state = 'complete';
        $order->gateway_order_id = $params['cf_referenceId'];
        $order->save();
        // Get benefit setting
        $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'sescontestpackage')
                ->getBenefitStatus($user);
        // Check profile status
        if ($paymentStatus == 'okay' || $paymentStatus == 'active' ||
              ($paymentStatus == 'pending' && $giveBenefit)) {
          // Enable now
          $transaction->gateway_id = $this->_gatewayInfo->gateway_id;
          $transaction->gateway_profile_id = $params['cf_subReferenceId'];
          $transaction->save();
          $item->onPaymentSuccess();
          
           //For Coupon Plugin
          if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')){
            $transaction->ordercoupon_id = Engine_Api::_()->ecoupon()->setAppliedCouponDetails($params['couponSessionCode']);
            $transaction->save();
          }
          //For Credit 
          if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit') && isset($params['creditCode'])) {
            $sessionCredit = new Zend_Session_Namespace($params['creditCode']);
            $transaction->credit_point = $sessionCredit->credit_value;  
            $transaction->credit_value =  $sessionCredit->purchaseValue;
            $transaction->save();
            if($sessionCredit->purchaseValue){
              $userCreditDetailTable = Engine_Api::_()->getDbTable('details', 'sescredit');
              $userCreditDetailTable->update(array('total_credit' => new Zend_Db_Expr('total_credit - ' . $sessionCredit->credit_value)), array('owner_id =?' => $order->user_id));
            }
          }
          return 'active';
        } else if ($paymentStatus == 'pending') {
          // Enable later
          //$subscription->gateway_id = $this->_gatewayInfo->gateway_id;
          // $subscription->gateway_profile_id = $rdata['PROFILEID'];
          $item->onPaymentPending();
          return 'pending';
        } else {
          // Cancel order and subscription?
          $order->onFailure();
          $item->onPaymentFailure();
          // This is a sanity error and cannot produce information a user could use
          // to correct the problem.
          throw new Payment_Model_Exception('There was an error processing your ' .
          'transaction. Please try again later.');
        }
      }
    }
  }

  
  public function onSubscriptionTransactionReturn(Payment_Model_Order $order,array $params = array()){}
  public function onSubscriptionTransactionIpn(Payment_Model_Order $order,Engine_Payment_Ipn $ipn){}
  public function cancelSubscription($transactionId, $note = null)
  {
    $profileId = null;
    if( $transactionId instanceof Payment_Model_Subscription ) {
      $package = $transactionId->getPackage();
      if( $package->isOneTime() ) {
        return $this;
      }
      $profileId = $transactionId->gateway_profile_id;
    }
    else if(is_string($transactionId) ) {
      $profileId = $transactionId;
    }
    else {
      // Should we throw?
      return $this;
    }
    try {
       $cashfree = new Cashfree($this->_gatewayInfo->config['ecashfree_appid'],$this->_gatewayInfo->config['ecashfree_secretkey']);
       $cashfree->cancelSubscription($profileId);       
    } catch( Exception $e ) {
      // throw?
    }
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
      return 'https://test.cashfree.com/merchant/pg#orders';
    } else {
      return 'https://cashfree.com/merchant/pg#orders';
    }
  }

  public function getTransactionDetailLink($transactionId)
  {
    if( $this->getGateway()->getTestMode() ) {
      // Note: it doesn't work in test mode
      return 'https://test.cashfree.com/merchant/pg#orders';
    } else {
      return 'https://cashfree.com/merchant/pg#orders';
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
  public function createOrderTransaction($params = array()) {  }
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
    return array(0=>'Month',1=>'Year');
  }
  // IPN

  /**
   * Process an IPN
   *
   * @param Engine_Payment_Ipn $ipn
   * @return Engine_Payment_Plugin_Abstract
   */
  public function onIpn(Engine_Payment_Ipn $ipn)
  {}
  public function cancelResourcePackage($transactionId, $note = null) {}
  public function onIpnTransaction($rawData){  }
  public function onTransactionIpn(Payment_Model_Order $order,  $rawData) {
      // Check that gateways match
      if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
          throw new Engine_Payment_Plugin_Exception('Gateways do not match');
      }
      // Get related info	
        $user = $order->getUser();
        $item = $order->getSource();
        $package = $item->getPackage();
        $transaction = $item->getTransaction();
        $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sescontestpackage');
      return $this;
  }
  function setConfig(){}
  function test(){}

}
