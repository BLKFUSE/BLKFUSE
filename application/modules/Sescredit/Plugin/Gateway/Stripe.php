<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesadvpmnt
 * @package    Sesadvpmnt
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Stripe.php  2019-04-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
include_once APPLICATION_PATH . "/application/modules/Sesadvpmnt/Api/Stripe/init.php";
class Sescredit_Plugin_Gateway_Stripe extends Engine_Payment_Plugin_Abstract
{
  protected $_gatewayInfo;

  protected $_gateway;
  public function __construct(Zend_Db_Table_Row_Abstract $gatewayInfo)
  {
    $this->_gatewayInfo = $gatewayInfo;
    \Stripe\Stripe::setApiKey($this->_gatewayInfo->config['sesadvpmnt_stripe_secret']);
  }
  public function getService()
  {
    return $this->getGateway()->getService();
  }
  public function getGateway()
  {
    if( null === $this->_gateway ) {
        $class = 'Sesadvpmnt_Gateways_Stripe';
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
  public function createSubscriptionTransaction(User_Model_User $user, Zend_Db_Table_Row_Abstract $subscription, Payment_Model_Package $package, array $params = array()) {}
  public function createPageTransaction(User_Model_User $user, array $params = array()) {
    // Create transaction
    return $this->createOrderTransaction($params);
  }
  public function onSubscriptionReturn(
      Payment_Model_Order $order,$transaction){
  
  }
  public function onPageTransactionReturn(
    Payment_Model_Order $order, $params) {
    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    $user = $order->getUser();
    $item = $order->getSource();
    $session = \Stripe\Checkout\Session::retrieve($params['session_id']);
    // Check subscription state
    $paymentStatus = 'okay';
    $orderStatus = 'complete';
    $response = \Stripe\PaymentIntent::retrieve($session['payment_intent']);

   // Update order with profile info and complete status?
    $order->state = $orderStatus;
    $order->gateway_transaction_id = $response->id;
    $order->save();
    $session = new Zend_Session_Namespace('Payment_Sescredit');
    $currency = $session->currency;
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sescredit');
    $transactionsTable->insert(array(
        'owner_id' => $order->user_id,
        'gateway_id' => $this->_gatewayInfo->gateway_id,
        'gateway_transaction_id' => $response->id,
        'creation_date' => new Zend_Db_Expr('NOW()'),
        'modified_date' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'state' => 'initial',
        'gateway_type' => 'Stripe',
        'total_amount' => strtoupper($response->amount/100),
        'currency_symbol' => strtoupper($response->currency),
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
  public function onSubscriptionTransactionReturn(Payment_Model_Order $order,array $params = array()){}
  public function onSubscriptionTransactionIpn(Payment_Model_Order $order,Engine_Payment_Ipn $ipn){}
  public function cancelSubscription($transactionId, $note = null){return $this;}
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
      return 'https://dashboard.stripe.com/test/search?query' . $orderId;
    } else {
      return 'https://dashboard.stripe.com/search?query' . $orderId;
    }
  }
  public function getTransactionDetailLink($transactionId)
  {
    if( $this->getGateway()->getTestMode() ) {
      // Note: it doesn't work in test mode
      return 'https://dashboard.stripe.com/test/search?query' . $transactionId;
    } else {
      return 'https://dashboard.stripe.com/search?query' . $transactionId;
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
    $currencyValue = isset($params['change_rate']) ? $params['change_rate'] : 1;
    return \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'line_items' => [[
        'name' => "Credits",
        'description' => 'Credits'.$params['amount'],
        'images' => ['https://example.com/t-shirt.png'],
        'amount' => $params['amount']*100,
        'currency' => $params['currency'],
        'quantity' => 1,
      ]],
      'metadata'=>['order_id'=>$params['order_id'],'type'=>$params['type'],'change_rate'=>$currencyValue],
      'success_url' => $params['return_url'].'&session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => $params['cancel_url'],
    ]);
  }
  public function createOrderTransactionReturn($order,$transaction) {   return 'active'; }
  function getSupportedCurrencies(){
      return array('USD'=>'USD','AED'=>'AED','AFN'=>'AFN','ALL'=>'ALL','AMD'=>'AMD','ANG'=>'ANG','AOA'=>'AOA','ARS'=>'ARS','AUD'=>'AUD'
      ,'AWG'=>'AWG','AZN','BAM'=>'BAM','BBD'=>'BBD','BDT'=>'BDT','BGN'=>'BGN','BIF'=>'BIF','BMD'=>'BMD','BND'=>'BND','BOB'=>'BOB','BRL'=>'BRL',
      'BSD'=>'BSD','BWP'=>'BWP','BZD'=>'BZD','CAD'=>'CAD','CDF'=>'CDF','CHF'=>'CHF','CLP'=>'CLP','CNY'=>'CNY','COP'=>'COP','CRC'=>'CRC','CVE'=>'CVE',
      'CZK'=>'CZK','DJF'=>'DJF','DKK'=>'DKK','DOP'=>'DOP','DZD'=>'DZD','EGP'=>'EGP','ETB'=>'ETB','EUR'=>'EUR','FJD'=>'FJD','FKP'=>'FKP','GBP'=>'GBP',
      'GEL'=>'GEL','GIP'=>'GIP','GMD'=>'GMD','GNF'=>'GNF','GTQ'=>'GTQ','GYD'=>'GYD','HKD'=>'HKD','HNL'=>'HNL','HRK'=>'HRK','HTG'=>'HTG','HUF'=>'HUF',
      'IDR'=>'IDR','ILS'=>'ILS','INR'=>'INR','ISK'=>'ISK','JMD'=>'JMD','JPY'=>'JPY','KES'=>'KES','KGS'=>'KGS','KHR'=>'KHR','KMF'=>'KMF','KRW'=>'KRW',
      'KYD'=>'KYD','KZT'=>'KZT','LAK'=>'LAK','LBP'=>'LBP','LKR'=>'LKR','LRD'=>'LRD','LSL'=>'LSL','MAD'=>'MAD','MDL'=>'MDL','MGA'=>'MGA','MKD','MMK'=>'MMK',
      'MNT'=>'MNT','MOP'=>'MOP','MRO'=>'MRO','MUR'=>'MUR','MVR'=>'MVR','MWK'=>'MWK','MXN'=>'MXN','MYR'=>'MYR','MZN'=>'MZN','NAD'=>'NAD','NGN'=>'NGN','NIO'=>'NIO',
      'NOK'=>'NOK','NPR'=>'NPR','NZD'=>'NZD','PAB'=>'PAB','PEN'=>'PEN','PGK'=>'PGK','PHP'=>'PHP','PKR'=>'PKR','PLN'=>'PLN','PYG'=>'PYG','QAR'=>'QAR','RON'=>'RON',
      'RSD'=>'RSD','RUB'=>'RUB','RWF'=>'RWF','SAR'=>'SAR','SBD'=>'SBD','SCR'=>'SCR','SEK'=>'SEK','SGD'=>'SGD','SHP'=>'SHP','SLL'=>'SLL','SOS'=>'SOS','SRD'=>'SRD',
      'STD'=>'STD','SZL'=>'SZL','THB'=>'THB','TJS'=>'TJS','TOP'=>'TOP','TRY'=>'TRY','TTD'=>'TTD','TWD'=>'TWD','TZS'=>'TZS','UAH'=>'UAH','UGX'=>'UGX','UYU'=>'UYU','UZS'=>'UZS','VND'=>'VND','VUV'=>'VUV','WST'=>'WST','XAF'=>'XAF','XCD'=>'XCD','XOF'=>'XOF','XPF'=>'XPF','YER'=>'YER','ZAR'=>'ZAR','ZMW'=>'ZMW');
 }
  public function getAdminGatewayForm(){
    return new Sesadvpmnt_Form_Admin_Settings_Stripe();
  }

  public function processAdminGatewayForm(array $values){ return $values; }
  public function getGatewayUrl(){}
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
  public function onIpn(Engine_Payment_Ipn $ipn){}
  public function cancelResourcePackage($transactionId, $note = null) {}
  public function cancelSubscriptionOnExpiry($source, $package) {
      $this->view->secretKey = $secretKey = $this->_gatewayInfo->config['sesadvpmnt_stripe_secret'];
        if($package->duration_type != "forever"){
              $durationTime = (($package->duration > 1 || $package->duration == 0) ? ("+".$package->duration." ".$package->duration_type."s") : ("+".$package->duration." ".$package->duration_type));
                $subscriptionDate = strtotime($source->creation_date);
              $date = date($subscriptionDate,strtotime($durationTime));
        if(strtotime("now") >= $date ) {
          \Stripe\Stripe::setApiKey($secretKey);
          $sub = \Stripe\Subscription::retrieve($source->gateway_profile_id);
          $sub->cancel();
          echo "Subscription canceled";
        }
      }
      echo "Subscription Continue";
  }
  public function onIpnTransaction($rawData){
        $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
        $order = null;
        // Transaction IPN - get order by subscription_id
        if (!$order && !empty($rawData['data']['object']['subscription'])) {
            $gateway_order_id = $rawData['data']['object']['subscription'];
            $order = $ordersTable->fetchRow(array(
                'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
                'gateway_order_id = ?' => $gateway_order_id,
            ));
        }
        if ($order) {
            return $this->onTransactionIpn($order,$rawData);
        } else {
            throw new Engine_Payment_Plugin_Exception('Unknown or unsupported IPN type, or missing transaction or order ID');
        }
  }
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
        $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'sespagepackage');
      // switch message_type
      switch ($rawData['type']) {
          case 'account.updated':
          case 'account.application.deauthorized':
          case 'account.external_account.created':
          case 'account.external_account.deleted':
          case 'account.external_account.updated':
          case 'application_fee.created':
          case 'application_fee.refunded':
          case 'application_fee.refund.updated':
          case 'balance.available':
          case 'bitcoin.receiver.created':
          case 'bitcoin.receiver.filled':
          case 'bitcoin.receiver.updated':
          case 'bitcoin.receiver.transaction.created':
          case 'charge.captured':
          case 'charge.failed':return false; break;
          case 'charge.refunded':
              // Payment Refunded
              $item->onRefund();
              // send notification
              return true;
              break;
          case 'charge.succeeded':
          case 'charge.updated':
          case 'charge.dispute.closed':
          case 'charge.dispute.created':
          case 'charge.dispute.funds_reinstated':
          case 'charge.dispute.funds_withdrawn':
          case 'charge.dispute.updated':
          case 'coupon.created':
          case 'coupon.deleted':
          case 'coupon.updated':
          case 'customer.created':
          case 'customer.deleted':
          case 'customer.updated':
          case 'customer.bank_account.deleted':
          case 'customer.discount.created':
          case 'customer.discount.deleted':
          case 'customer.discount.updated':
          case 'customer.source.created':
          case 'customer.source.deleted':
          case 'customer.source.updated':
          case 'customer.subscription.created': return false; break;
          case 'customer.subscription.deleted':
              $item->onCancel();
              // send notification
                return true;
              break;
          case 'customer.subscription.trial_will_end':return false; break;
          case 'customer.subscription.updated':
              $item->onPaymentSuccess();
              $this->cancelSubscriptionOnExpiry($item, $package);
                return true;
              break;
          case 'invoice.created':break;
          case 'invoice.payment_failed':
              $item->onPaymentFailure();
              break;
          case 'invoice.payment_succeeded':
              $item->onPaymentSuccess();
              $this->cancelSubscriptionOnExpiry($item, $package);
              return true;
              break;
          case 'invoice.updated':
          case 'invoiceitem.created':
          case 'invoiceitem.deleted':
          case 'invoiceitem.updated':
          case 'plan.created':
          case 'plan.deleted':
          case 'plan.updated':
          case 'recipient.created':
          case 'recipient.deleted':
          case 'recipient.updated':
          case 'transfer.created':
          case 'transfer.failed':
          case 'transfer.paid':
          case 'transfer.reversed':
          case 'transfer.updated': return false; break;
          default:
          throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
              'type %1$s', $rawData['type']));
          break;
      }
      return $this;
  }
  function setConfig(){}
  function test(){}
}
