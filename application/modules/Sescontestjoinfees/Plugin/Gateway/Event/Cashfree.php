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
class Sescontestjoinfees_Plugin_Gateway_Event_Cashfree extends Engine_Payment_Plugin_Abstract {
    protected $_gatewayInfo;
    protected $_gateway;
    // General
    /**
    * Constructor
    */
    public function __construct(Zend_Db_Table_Row_Abstract $gatewayInfo)
    {
        $this->_gatewayInfo = $gatewayInfo;
    }
    /**
    * Get the service API
    *
    * @return Engine_Service_PayPal
    */
    public function getService()
    {
        return $this->getGateway()->getService();
    }
    /**
    * Get the gateway object
    *
    * @return Engine_Payment_Gateway
    */
    public function getGateway()
    {
        if( null === $this->_gateway ) {
            $class = 'Ecashfree_Gateways_Cashfree';
            Engine_Loader::loadClass($class);
            $gateway = new $class(array(
                'config' => (array) $this->_gatewayInfo->config,
                'testMode' => $this->_gatewayInfo->config['test_mode'],
            ));
            if( !($gateway instanceof Engine_Payment_Gateway) ) {
                throw new Engine_Exception('Plugin class not instance of Engine_Payment_Gateway');
            }
            $this->_gateway = $gateway;
        }
        return $this->_gateway;
    }
    // Actions
    /**
    * Create a transaction object from specified parameters
    *
    * @return Engine_Payment_Transaction
    */
    public function createTransaction(array $params)
    {
        $transaction = new Engine_Payment_Transaction($params);
        $transaction->process($this->getGateway($params['moduleName']));
        return $transaction;
    }
    /**
    * Create an ipn object from specified parameters
    *
    * @return Engine_Payment_Ipn
    */
    public function createIpn(array $params)
    {
        $ipn = new Engine_Payment_Ipn($params);
        $ipn->process($this->getGateway());
        return $ipn;
    }
    // SEv4 Specific
    /**
    * Create a transaction for a subscription
    *
    * @param User_Model_User $user
    * @param Zend_Db_Table_Row_Abstract $subscription
    * @param Zend_Db_Table_Row_Abstract $package
    * @param array $params
    * @return Engine_Payment_Gateway_Transaction
    */
	public function createSubscriptionTransaction(User_Model_User $user, Zend_Db_Table_Row_Abstract $user_order, Payment_Model_Package $package, array $params = array()){}
  public function createOrderTransaction($order,$event,array $params = array()) { 
    $params['currency'] = Engine_Api::_()->payment()->defaultCurrency();   
    $secretKey = $this->_gatewayInfo->config['ecashfree_secretkey'];
    $params['appId'] = $this->_gatewayInfo->config['ecashfree_appid'];
    $params['orderAmount'] = round($order->release_amount, 2);
    $params['orderCurrency'] = $params['currency'];
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

  public function orderTransactionReturn($order,$params) {
    // Get related info
    $user = $order->getUser();
    $orderPayment = $order->getSource();
    $module_name = 'user';
    $viewer = Engine_Api::_()->user()->getViewer();
    
    //payment currency
    $currentCurrency = Engine_Api::_()->payment()->getCurrentCurrency();
    $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $currencyValue = 1;
    if($currentCurrency != $defaultCurrency){
        $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
    }

    $paymentStatus = null;
    $orderStatus = null;
    switch($params['txStatus']) {
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
        'gateway_id' =>2,
        'timestamp' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'type' => 'payment',
        'state' => $paymentStatus,
        'gateway_transaction_id' => $params['referenceId'],
        'amount' => $params['orderAmount'], // @todo use this or gross (-fee)?
        'currency' => $currentCurrency,
    ));
    // Get benefit setting
    $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')->getBenefitStatus($user);
    // Check payment status
    if( $paymentStatus == 'okay' || ($paymentStatus == 'pending' && $giveBenefit) ) {
        // Update order table info
        $orderPayment->gateway_id = $transaction->metadata->gateway_id;
        $orderPayment->gateway_transaction_id = $params['referenceId'];
        $orderPayment->currency_symbol = $currentCurrency;
        $orderPayment->release_date = date('Y-m-d H:i:s');
        $orderPayment->gateway_type = "Cashfree";
        $orderPayment->save();
      	$tableRemaining = Engine_Api::_()->getDbtable('remainingpayments', 'sescontestjoinfees');
        $tableName = $tableRemaining->info('name');
        $select = $tableRemaining->select()->from($tableName)->where('contest_id =?',$orderPayment->contest_id);
        $select = $tableRemaining->fetchAll($select);
        $remainingAmt = $select[0]['remaining_payment'] - $params['orderAmount'];
        if($remainingAmt < 0)
          $orderAmount = 0;
        else
          $orderAmount = $remainingAmt;
          $tableRemaining->update(array('remaining_payment' => $remainingAmt),array('contest_id =?'=>$orderPayment->contest_id));
        // Payment success
        $orderPayment->onOrderComplete();
        // send notification
        if( $orderPayment->state == 'complete' ) {
          /*Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
          ));*/
        }
        return 'active';
    }
    else if( $paymentStatus == 'pending' ) {
        // Update order  info
        $orderPayment->gateway_id = $this->_gatewayInfo->gateway_id;
        $orderPayment->gateway_profile_id = $params['referenceId'];
                $orderPayment->save();
        // Order pending
        $orderPayment->onOrderPending();
        return 'pending';
    }
    else if( $paymentStatus == 'failed' ) {
        // Cancel order and subscription?
        $order->onFailure();
        $orderPayment->onOrderFailure();
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
    return 'active';
  }

    /**
    * Process return of subscription transaction
    *
    * @param Payment_Model_Order $order
    * @param array $params
    */
    public function onSubscriptionTransactionReturn(Payment_Model_Order $order, array $params = array()) {}

	public function onOrderTicketTransactionIpn(Payment_Model_Order $order, Engine_Payment_Ipn $ipn) {

	}
  /**
   * Process ipn of subscription transaction
   *
   * @param Payment_Model_Order $order
   * @param Engine_Payment_Ipn $ipn
   */
  public function onSubscriptionTransactionIpn(
      Payment_Model_Order $order,
      Engine_Payment_Ipn $ipn)
  {}

  public function cancelSubscription($transactionId, $note = null)
  {}
  /**
   * Generate href to a page detailing the order
   *
   * @param string $transactionId
   * @return string
   */
  public function getOrderDetailLink($orderId)
  {

  }
  /**
   * Generate href to a page detailing the transaction
   *
   * @param string $transactionId
   * @return string
   */
  public function getTransactionDetailLink($transactionId)
  {

  }
  /**
   * Get raw data about an order or recurring payment profile
   *
   * @param string $orderId
   * @return array
   */
  public function getOrderDetails($orderId)
  {
    // We don't know if this is a recurring payment profile or a transaction id,
    // so try both
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
  /**
   * Get raw data about a transaction
   *
   * @param $transactionId
   * @return array
   */
  public function getTransactionDetails($transactionId)
  {
    return $this->getService()->detailTransaction($transactionId);
  }
  // IPN
  /**
   * Process an IPN
   *
   * @param Engine_Payment_Ipn $ipn
   * @return Engine_Payment_Plugin_Abstract
   */
  public function onIpn(Engine_Payment_Ipn $ipn)
  { }
 function getSupportedCurrencies(){
      return array('INR'=>'INR');
 }


  // Forms
  /**
   * Get the admin form for editing the gateway info
   *
   * @return Engine_Form
   */
  public function getAdminGatewayForm()
  {
    return new Sesbasic_Form_Admin_Gateway_PayPal();
  }
  public function processAdminGatewayForm(array $values)
  {
    return $values;
  }
}
