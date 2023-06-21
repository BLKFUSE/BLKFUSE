<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: PayPal.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Plugin_Gateway_PayPal extends Engine_Payment_Plugin_Abstract {

  protected $_gatewayInfo;
  protected $_gateway;

  // General

  /**
   * Constructor
   */
  public function __construct(Zend_Db_Table_Row_Abstract $gatewayInfo) {
    $this->_gatewayInfo = $gatewayInfo;

    // @todo
    // https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_profile-recurring-payments&encrypted_profile_id=
  }

  /**
   * Get the service API
   *
   * @return Engine_Service_PayPal
   */
  public function getService() {
    return $this->getGateway()->getService();
  }

  /**
   * Get the gateway object
   *
   * @return Engine_Payment_Gateway
   */
  public function getGateway() {
    if (null === $this->_gateway) {
      $class = 'Engine_Payment_Gateway_PayPal';
      Engine_Loader::loadClass($class);
      $gateway = new $class(array(
          'config' => (array) $this->_gatewayInfo->config,
          'testMode' => $this->_gatewayInfo->test_mode,
          'currency' =>Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency'),
      ));
      if (!($gateway instanceof Engine_Payment_Gateway)) {
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
  public function createTransaction(array $params) {
    $transaction = new Engine_Payment_Transaction($params);
    $transaction->process($this->getGateway());
    return $transaction;
  }
  /**
   * Create an ipn object from specified parameters
   *
   * @return Engine_Payment_Ipn
   */
  public function createIpn(array $params) {
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
  public function createSubscriptionTransaction(User_Model_User $user, Zend_Db_Table_Row_Abstract $subscription, Payment_Model_Package $package, array $params = array()) {

  }

  public function createOrderTransaction(User_Model_User $user, $giftOrder, array $params = array()) {
    // Process description
    $currentCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
    $defaultCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $currencyValue = 1;
    if ($currentCurrency != $defaultCurrency) {
      $currencyValue = $settings->getSetting('sesmultiplecurrency.' . $currentCurrency);
    }
    $totalprice = 0;
    $totalprice = $giftOrder->total_amount;
    // For credit 
    $creditCode =  'credit'.'-egifts-'.$giftOrder->getIdentity().'-'.$giftOrder->getIdentity();
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount > 0 && $sessionCredit->total_amoun < $giftOrder->total_amount) { 
      $totalprice = $sessionCredit->total_amount;
    }
    
    $gift = Engine_Api::_()->getItem('egifts_gift', $giftOrder->gift_id);
    
    $params['driverSpecificParams']['PayPal'] = array(
        'AMT' => @round($totalprice, 2),
        'ITEMAMT' => @round($totalprice, 2),
        'TAXAMT' => 0,
        'SHIPPINGAMT' => 0,
        'DESC' => $gift->description,
        'ITEMS' =>array(
					array(
            'NAME' => $gift->title,
            'DESC' => $gift->description,
            'AMT' => @round($totalprice, 2),
            //'NUMBER' => $subscription->subscription_id,
            //'QTY' => 1,
          ),
        ),
        'SELLERID' => '1',
    );
    // Should fix some issues with GiroPay
    if (!empty($params['return_url'])) {
      $params['driverSpecificParams']['PayPal']['GIROPAYSUCCESSURL'] = $params['return_url']
              . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
      $params['driverSpecificParams']['PayPal']['BANKTXNPENDINGURL'] = $params['return_url']
              . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
    }
    if (!empty($params['cancel_url'])) {
      $params['driverSpecificParams']['PayPal']['GIROPAYCANCELURL'] = $params['cancel_url']
              . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
    }
    //empty cart
    // Create transaction
    $transaction = $this->createTransaction($params);
    return $transaction;
  }

  /**
   * Process return of subscription transaction
   *
   * @param Payment_Model_Order $order
   * @param array $params
   */
  public function onSubscriptionTransactionReturn(
  Payment_Model_Order $order, array $params = array()) {

  }

  public function createOrderTransactionReturn(
  Payment_Model_Order $order, array $params = array()) {
    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }

    // Get related info
    $user = $order->getUser();
    $item = $order->getSource();

    $viewer = Engine_Api::_()->user()->getViewer();

    // Check subscription state
    if ($item && ($item->state == 'trial')) {
      return 'active';
    } else if ($item && $item->state == 'pending') {
      return 'pending';
    }

    // Check for cancel state - the user cancelled the transaction
    if ($params['state'] == 'cancel') {
      // Cancel order and item
        $item->onCancel();


      // Error
      throw new Payment_Model_Exception('Your payment has been cancelled and ' .
      'not been charged. If this is not correct, please try again later.');
    }

    // Check params
    if (empty($params['token'])) {
      // Cancel order and subscription?
        $item->onFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
      'transaction. Please try again later.');
    }


    // Get details
    try {
      $data = $this->getService()->detailExpressCheckout($params['token']);

    } catch (Exception $e) {
      // Cancel order and subscription?
      $item->onFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
      'transaction. Please try again later.');
    }


    // Let's log it
    $this->getGateway()->getLog()->log('ExpressCheckoutDetail: '
            . print_r($data, true), Zend_Log::INFO);


      // Do payment
      try {
	      $rdata = $this->getService()->doExpressCheckoutPayment($params['token'], $params['PayerID'], array(
            'PAYMENTACTION' => 'Sale',
            'AMT' => $data['AMT'],
            'CURRENCYCODE' => $this->getGateway()->getCurrency(),
        ));
      } catch (Exception $e) {
        // Log the error
        $this->getGateway()->getLog()->log('DoExpressCheckoutPaymentError: '
                . $e->__toString(), Zend_Log::ERR);

        // Cancel order and subscription?
        $order->onFailure();
        $item->onFailure();
        // This is a sanity error and cannot produce information a user could use
        // to correct the problem.
        throw new Payment_Model_Exception('There was an error processing your ' .
        'transaction. Please try again later.');
      }


      // Let's log it
      $this->getGateway()->getLog()->log('DoExpressCheckoutPayment: '
              . print_r($rdata, true), Zend_Log::INFO);

      // Get payment state
      $paymentStatus = null;
      $orderStatus = null;

      switch (strtolower($rdata['PAYMENTINFO'][0]['PAYMENTSTATUS'])) {
        case 'created':
        case 'pending':
          $paymentStatus = 'pending';
          $orderStatus = 'complete';
          break;

        case 'completed':
        case 'processed':
        case 'canceled_reversal': // Probably doesn't apply
          $paymentStatus = 'active';
          $orderStatus = 'complete';
          break;

        case 'denied':
        case 'failed':
        case 'voided': // Probably doesn't apply
        case 'reversed': // Probably doesn't apply
        case 'refunded': // Probably doesn't apply
        case 'expired':  // Probably doesn't apply
        default: // No idea what's going on here
          $paymentStatus = 'failed';
          $orderStatus = 'failed'; // This should probably be 'failed'
          break;
      }

      // Update order with profile info and complete status?
      $item->state = $orderStatus;
      $item->gateway_transaction_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
      $item->save();



      $session = new Zend_Session_Namespace('Payment_Courses');
      $currency = $session->currency;
      $rate = $session->change_rate;
      if (!$rate)
        $rate = 1;
      $defaultCurrency = Engine_Api::_()->egifts()->defaultCurrency();
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $currencyValue = 1;
      if ($currency != $defaultCurrency)
        $currencyValue = $settings->getSetting('sesmultiplecurrency.' . $currency);
      //Insert transaction


      //check product variations
/*      $orderTableName = Engine_Api::_()->getDbTable('orders','egifts');
      $select = $orderTableName->select()->where('order_id =?',$item->getIdentity());
      $order = $orderTableName->fetchRow($select);*/
      
      $totalPrice = 0;
      $order->state = $orderStatus;
      $order->save();
      
/*      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'egifts');
      $transactionsTable->insert(array(
          'owner_id' => $order->user_id,
          'gateway_id' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id' => $rdata['PAYMENTINFO'][0]['TRANSACTIONID'],
          'gateway_profile_id' => $rdata['PAYMENTINFO'][0]['TRANSACTIONID'],
          'creation_date' => new Zend_Db_Expr('NOW()'),
          'modified_date' => new Zend_Db_Expr('NOW()'),
          'order_id' => $order->order_id,
          'state' => 'processing',
          'item_count'=>$order->item_count,
          'total_amount' => $order->total_amount,
          'change_rate' => $rate,
          'gateway_type' => 'Paypal',
          'currency_symbol' => $currency,
          'ip_address' => $_SERVER['REMOTE_ADDR'],
      ));*/
     // $transaction_id = $transactionsTable->getAdapter()->lastInsertId();
     
      //get all order products
      $giftPurchaseTableName = Engine_Api::_()->getDbTable('giftpurchases','egifts');
      $select = $giftPurchaseTableName->select()->where('giftpurchase_id = ?', $item->giftpurchase_id);
      $egifts = $giftPurchaseTableName->fetchAll($select);

      $giftorderTableName = Engine_Api::_()->getDbTable('giftorders','egifts');
      $select = $giftorderTableName->select()->where('giftpurchase_id = ?', $item->giftpurchase_id);
      $giftorders = $giftorderTableName->fetchAll($select);
      // Get benefit setting

      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit') && isset($params['creditCode'])) {
          $sessionCredit = new Zend_Session_Namespace($params['creditCode']);
          if($sessionCredit->credit_value > 0 && $sessionCredit->purchaseValue > 0){
            $item->credit_point = $sessionCredit->credit_value;  
            $item->credit_value =  $sessionCredit->purchaseValue;
            $item->save();
            $userCreditDetailTable = Engine_Api::_()->getDbTable('details', 'sescredit');
            $userCreditDetailTable->update(array('total_credit' => new Zend_Db_Expr('total_credit - ' . $sessionCredit->credit_value)), array('owner_id =?' => $item->owner_id));

            $table = Engine_Api::_()->getDbTable('credits', 'sescredit');
            $creditRow = $table->createRow();
            $creditValues = array('type' => 'egifts_giftorder', 'owner_id' => $item->owner_id, 'action_id' => 0, 'object_id' => $item->getIdentity(), 'point_type' => 'po',  'credit' => $item->credit_point);

            $creditRow->setFromArray($creditValues);
            $creditRow->save();

          }
      }
      if ($paymentStatus == 'okay' || $paymentStatus == 'active' ||
              ($paymentStatus == 'pending')) {
        // Payment success
        
        $tableRemaining = Engine_Api::_()->getDbTable('remainingpayments', 'egifts');
        $tableName = $tableRemaining->info('name');
        
        try{
          foreach ($giftorders as $key => $egift) {
            $gift = Engine_Api::_()->getItem('egifts_gift', $egift->gift_id);
            $giftTitle = '<a href="'.$gift->getHref().'" >'.$gift->getTitle().'</a>';
            $receiver = Engine_Api::_()->getItem('user', $item->purchase_user_id);
            
						//update gift OWNER REMAINING amount
						$select = $tableRemaining->select()->from($tableName)->where('user_id =?', $receiver->user_id);
						$select = $tableRemaining->fetchAll($select);
						$orderAmount = $egift->gift_price;
						if (engine_count($select)) {
							$tableRemaining->update(array('remaining_payment' => new Zend_Db_Expr("remaining_payment + $orderAmount")), array('user_id =?' => $receiver->user_id));
						} else {
							$tableRemaining->insert(array(
								'remaining_payment' => $orderAmount,
								'user_id' => $receiver->user_id,
							));
						}

            $getAdminnSuperAdmins = Engine_Api::_()->egifts()->getAdminnSuperAdmins();
            foreach ($getAdminnSuperAdmins as $key => $getAdminnSuperAdmin) {
              $admin = Engine_Api::_()->getItem('user', $getAdminnSuperAdmin['user_id']);
              if(empty($user)){
                continue;
              }
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($admin,$viewer, $viewer,'egift_made_payment',array('giftitle'=>$giftTitle));
              Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin->email, 'egift_made_payment', array('host' => $_SERVER['HTTP_HOST'],'gift_title' => $giftTitle,'sender_title'=>$viewer->getTitle(),'object_link'=>$gift->getHref()));
            }
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($receiver,$viewer, $viewer,'egift_send_gift');
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($receiver->email, 'egift_send_gift', array('host' => $_SERVER['HTTP_HOST'],'gift_title' => $giftTitle,'sender_title'=>$receiver->getTitle(),'object_link'=>$gift->getHref()));
          }
        }catch(Exception $e){
          throw $e;

        }
        return 'active';
      } else if ($paymentStatus == 'pending') {

        // Update subscription info
        $item->gateway_id = $this->_gatewayInfo->gateway_id;
        $item->gateway_profile_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
        $item->save();
        // Payment pending
        $item->onOrderPending();

        //Send to user for refunded
        if(strtolower($rdata['PAYMENTINFO'][0]['PAYMENTSTATUS']) == 'pending') {

        }
        return 'pending';
      } else if ($paymentStatus == 'failed') {
        // Cancel order and subscription?
        $item->onFailure();

        //Send to user for refunded
        if(strtolower($rdata['PAYMENTINFO'][0]['PAYMENTSTATUS']) == 'refunded') {

        }

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

  /**
   * Process ipn of subscription transaction
   *
   * @param Payment_Model_Order $order
   * @param Engine_Payment_Ipn $ipn
   */
  public function onSubscriptionTransactionIpn(
  Payment_Model_Order $order, Engine_Payment_Ipn $ipn) {

  }

  public function onOrderTransactionIpn(
  Payment_Model_Order $order, Engine_Payment_Ipn $ipn) {

    // Check that gateways match
    if ($order->gateway_id != $this->_gatewayInfo->gateway_id) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }

    // Get related info
    $user = $order->getUser();
    $item = $order->getSource();
    // Get IPN data
    $rawData = $ipn->getRawData();

    // Chargeback --------------------------------------------------------------
    if (!empty($rawData['case_type']) && $rawData['case_type'] == 'chargeback') {
      $item->onFailure(); // or should we use pending?
    }

    // Transaction Type --------------------------------------------------------
    else if (!empty($rawData['txn_type'])) {
      switch ($rawData['txn_type']) {
        // @todo see if the following types need to be processed:
        // — adjustment express_checkout new_case
              case 'Created': // Not sure about this one
              case 'Pending':
                // @todo this might be redundant
                // Get benefit setting
                $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'egifts')->getBenefitStatus($user);
                if ($giveBenefit) {
                  $item->onOrderComplete();
                } else {
                  $item->onOrderPending();
                }
                break;

              case 'Completed':
              case 'Processed':
              case 'Canceled_Reversal': // Not sure about this one
                $item->onOrderComplete();
                // send notification
                /* if( $item->didStatusChange() ) {
                  Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
                  'subscription_title' => $package->title,
                  'subscription_description' => $package->description,
                  'subscription_terms' => $package->getPackageDescription(),
                  'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                  Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                  ));
                  } */
                break;

              case 'Denied':
              case 'Failed':
              case 'Voided':
              case 'Reversed':
                $item->onFailure();
                // send notification
                /* if( $item->didStatusChange() ) {
                  Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_overdue', array(
                  'subscription_title' => $package->title,
                  'subscription_description' => $package->description,
                  'subscription_terms' => $package->getPackageDescription(),
                  'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                  Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                  ));
                  } */
                break;

              case 'Refunded':
                $item->onOrderRefund();
                // send notification
                /* if( $item->didStatusChange() ) {
                  Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_refunded', array(
                  'subscription_title' => $package->title,
                  'subscription_description' => $package->description,
                  'subscription_terms' => $package->getPackageDescription(),
                  'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                  Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                  ));
                  } */
                break;

              case 'Expired': // Not sure about this one
                $item->onExpiration();


                // send notification
                /* if( $item->didStatusChange() ) {
                  Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_expired', array(
                  'subscription_title' => $package->title,
                  'subscription_description' => $package->description,
                  'subscription_terms' => $package->getPackageDescription(),
                  'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                  Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
                  ));
                  } */
                break;

              default:
                throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
                        'payment status %1$s', $rawData['payment_status']));
                break;
            }
    }

    // Payment Status ----------------------------------------------------------
    else if (!empty($rawData['payment_status'])) {
      switch ($rawData['payment_status']) {

        case 'Created': // Not sure about this one
        case 'Pending':
          // Get benefit setting
          $giveBenefit = 1;
          if ($giveBenefit) {
            $item->onOrderComplete();
          } else {
            $item->onOrderPending();
          }
          break;
        case 'Completed':
        case 'Processed':
        case 'Canceled_Reversal': // Not sure about this one
          $item->onOrderComplete();
          // send notification
          /* if( $item->didStatusChange() ) {
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_active', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
            ));
            } */
          break;

        case 'Denied':
        case 'Failed':
        case 'Voided':
        case 'Reversed':
          $item->onFailure();
          // send notification
          /* if( $item->didStatusChange() ) {
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_overdue', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
            ));
            } */
          break;

        case 'Refunded':
          $item->onOrderRefund();
          // send notification
          /* if( $item->didStatusChange() ) {
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_refunded', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
            ));
            } */
          break;

        case 'Expired': // Not sure about this one
          $item->onExpiration();
          // send notification
          /* if( $item->didStatusChange() ) {
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'payment_subscription_expired', array(
            'subscription_title' => $package->title,
            'subscription_description' => $package->description,
            'subscription_terms' => $package->getPackageDescription(),
            'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
            Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login', true),
            ));
            } */
          break;

        default:
          throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
                  'payment status %1$s', $rawData['payment_status']));
          break;
      }
    }

    // Unknown -----------------------------------------------------------------
    else {
      throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
              'data structure'));
    }

    return $this;
  }

  /**
   * Cancel a subscription (i.e. disable the recurring payment profile)
   *
   * @params $transactionId
   * @return Engine_Payment_Plugin_Abstract
   */
  public function cancelSubscription($transactionId, $note = null) {

  }

  /**
   * Generate href to a page detailing the order
   *
   * @param string $transactionId
   * @return string
   */
  public function getOrderDetailLink($orderId) {
    // @todo make sure this is correct
    // I don't think this works
    if ($this->getGateway()->getTestMode()) {
      // Note: it doesn't work in test mode
      return 'https://www.sandbox.paypal.com/vst/?id=' . $orderId;
    } else {
      return 'https://www.paypal.com/vst/?id=' . $orderId;
    }
  }

  /**
   * Generate href to a page detailing the transaction
   *
   * @param string $transactionId
   * @return string
  */
  public function getTransactionDetailLink($transactionId) {
    // @todo make sure this is correct
    if ($this->getGateway()->getTestMode()) {
      // Note: it doesn't work in test mode
      return 'https://www.sandbox.paypal.com/vst/?id=' . $transactionId;
    } else {
      return 'https://www.paypal.com/vst/?id=' . $transactionId;
    }
  }

  /**
   * Get raw data about an order or recurring payment profile
   *
   * @param string $orderId
   * @return array
   */
  public function getOrderDetails($orderId) {
    // We don't know if this is a recurring payment profile or a transaction id,
    // so try both
    try {
      return $this->getService()->detailRecurringPaymentsProfile($orderId);
    } catch (Exception $e) {
      echo $e;
    }

    try {
      return $this->getTransactionDetails($orderId);
    } catch (Exception $e) {
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
  public function getTransactionDetails($transactionId) {
    return $this->getService()->detailTransaction($transactionId);
  }

  // IPN

  /**
   * Process an IPN
   *
   * @param Engine_Payment_Ipn $ipn
   * @return Engine_Payment_Plugin_Abstract
   */
  public function onIpn(Engine_Payment_Ipn $ipn) {
    $rawData = $ipn->getRawData();

    $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'egifts');


    // Find transactions -------------------------------------------------------
    $transactionId = null;
    $parentTransactionId = null;
    $transaction = null;
    $parentTransaction = null;

    // Fetch by txn_id
    if (!empty($rawData['txn_id'])) {
      $transaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id = ?' => $rawData['txn_id'],
      ));

      if (!$transaction && !empty($rawData['recurring_payment_id'])) {
        $transaction = $transactionsTable->fetchRow(array(
            'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
            'gateway_profile_id = ?' => $rawData['recurring_payment_id'],
        ));
      }
      $parentTransaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_parent_transaction_id = ?' => $rawData['txn_id'],
      ));
    }
    // Fetch by parent_txn_id
    if (!empty($rawData['parent_txn_id'])) {
      if (!$transaction) {
        $parentTransaction = $transactionsTable->fetchRow(array(
            'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
            'gateway_parent_transaction_id = ?' => $rawData['parent_txn_id'],
        ));
      }
      if (!$parentTransaction) {
        $parentTransaction = $transactionsTable->fetchRow(array(
            'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
            'gateway_transaction_id = ?' => $rawData['parent_txn_id'],
        ));
      }
    }
    // Fetch by transaction->gateway_parent_transaction_id
    if ($transaction && !$parentTransaction &&
            !empty($transaction->gateway_parent_transaction_id)) {
      $parentTransaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_parent_transaction_id = ?' => $transaction->gateway_parent_transaction_id,
      ));
    }
    // Fetch by parentTransaction->gateway_transaction_id
    if ($parentTransaction && !$transaction &&
            !empty($parentTransaction->gateway_transaction_id)) {
      $transaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_parent_transaction_id = ?' => $parentTransaction->gateway_transaction_id,
      ));
    }
    // Get transaction id
    if ($transaction) {
      $transactionId = $transaction->gateway_transaction_id;
    } else if (!empty($rawData['txn_id'])) {
      $transactionId = $rawData['txn_id'];
    }
    // Get parent transaction id
    if ($parentTransaction) {
      $parentTransactionId = $parentTransaction->gateway_transaction_id;
    } else if ($transaction && !empty($transaction->gateway_parent_transaction_id)) {
      $parentTransactionId = $transaction->gateway_parent_transaction_id;
    } else if (!empty($rawData['parent_txn_id'])) {
      $parentTransactionId = $rawData['parent_txn_id'];
    }



    // Fetch order -------------------------------------------------------------
    $order = null;

    // Transaction IPN - get order by invoice
    if (!$order && !empty($rawData['invoice'])) {
      $order = $ordersTable->find($rawData['invoice'])->current();
    }

    // Subscription IPN - get order by recurring_payment_id
    if (!$order && !empty($rawData['recurring_payment_id'])) {
      // Get attached order
      $order = $ordersTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_order_id = ?' => $rawData['recurring_payment_id'],
      ));
    }

    // Subscription IPN - get order by rp_invoice_id
    //if( !$order && !empty($rawData['rp_invoice_id']) ) {
    //
    //}
    // Transaction IPN - get order by parent_txn_id
    if (!$order && $parentTransactionId) {
      $order = $ordersTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id = ?' => $parentTransactionId,
      ));
    }

    // Transaction IPN - get order by txn_id
    if (!$order && $transactionId) {
      $order = $ordersTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id = ?' => $transactionId,
      ));
    }

    // Transaction IPN - get order through transaction
    if (!$order && !empty($transaction->order_id)) {
      $order = $ordersTable->find($parentTransaction->order_id)->current();
    }

    // Transaction IPN - get order through parent transaction
    if (!$order && !empty($parentTransaction->order_id)) {
      $order = $ordersTable->find($parentTransaction->order_id)->current();
    }



    // Process generic IPN data ------------------------------------------------
    // Build transaction info
    if (!empty($rawData['txn_id'])) {
      $transactionData = array(
          'gateway_id' => $this->_gatewayInfo->gateway_id,
      );
      // Get timestamp
      if (!empty($rawData['payment_date'])) {
        $transactionData['creation_date'] = date('Y-m-d H:i:s', strtotime($rawData['payment_date']));
      } else {
        $transactionData['creation_date'] = new Zend_Db_Expr('NOW()');
      }
      // Get amount
      if (!empty($rawData['mc_gross'])) {
        $transactionData['total_amount'] = $rawData['mc_gross'];
      }
      // Get currency
      if (!empty($rawData['mc_currency'])) {
        $transactionData['currency_symbol'] = $rawData['mc_currency'];
      }
      // Get order/user
      if ($order) {
        $transactionData['owner_id'] = $order->user_id;
        $transactionData['order_id'] = $order->order_id;
      }
      // Get transactions
      if ($transactionId) {
        $transactionData['gateway_transaction_id'] = $transactionId;
      }
      if ($parentTransactionId) {
        $transactionData['gateway_parent_transaction_id'] = $parentTransactionId;
      }
      // Get payment_status
      switch ($rawData['payment_status']) {
        case 'Canceled_Reversal': // @todo make sure this works

        case 'Completed':
        case 'Created':
        case 'Processed':
          $transactionData['type'] = 'payment';
          $transactionData['state'] = 'active';
          break;

        case 'Denied':
        case 'Expired':
        case 'Failed':
        case 'Voided':
          $transactionData['type'] = 'payment';
          $transactionData['state'] = 'failed';
          break;

        case 'Pending':
          $transactionData['type'] = 'payment';
          $transactionData['state'] = 'pending';
          break;

        case 'Refunded':
          $transactionData['type'] = 'refund';
          $transactionData['state'] = 'refunded';
          break;
        case 'Reversed':
          $transactionData['type'] = 'reversal';
          $transactionData['state'] = 'reversed';
          break;

        default:
          $transactionData = 'unknown';
          break;
      }

      // Insert new transaction
      if (!$transaction) {
        $transactionsTable->insert($transactionData);
      }
      // Update transaction
      else {
        unset($transactionData['timestamp']);
        //$transaction->setFromArray($transactionData);
        $transaction->save();
      }

      // Update parent transaction on refund?
      if ($parentTransaction && engine_in_array($transactionData['type'], array('refund', 'reversal'))) {
        $parentTransaction->state = $transactionData['state'];
        $parentTransaction->save();
      }
    }


    // Process specific IPN data -----------------------------------------------
    if ($order) {
      $ipnProcessed = false;
      // Subscription IPN
      if ($order->source_type == 'egifts_order') {
        $this->onOrderTransactionIpn($order, $ipn);
        $ipnProcessed = true;
      }
      // Unknown IPN - could not be processed
      if (!$ipnProcessed) {
        throw new Engine_Payment_Plugin_Exception('Unknown order type for IPN');
      }
    }// Missing order
    else {
      throw new Engine_Payment_Plugin_Exception('Unknown or unsupported IPN ' .
      'type, or missing transaction or order ID');
    }

    return $this;
  }

  // Forms

  /**
   * Get the admin form for editing the gateway info
   *
   * @return Engine_Form
   */
  public function getAdminGatewayForm() {
    return new Payment_Form_Admin_Gateway_PayPal();
  }

  public function processAdminGatewayForm(array $values) {
    return $values;
  }

}
