<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Testing.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Payment_Plugin_Gateway_Cheque extends Engine_Payment_Plugin_Abstract
{
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
   * @return Engine_Service_Testing
   */
  public function getService()
  {
    return $this->getGateway()->getService();
  }

  /**
   * Get the gateway object
   *
   * @return Engine_Payment_Gateway_Bank
   */
  public function getGateway()
  {
    if( null === $this->_gateway ) {
      $class = 'Engine_Payment_Gateway_Bank';
      Engine_Loader::loadClass($class);
      $gateway = new $class(array(
        'config' => (array) $this->_gatewayInfo->config,
        'testMode' =>  true, //$this->_gatewayInfo->test_mode,
        'currency' => Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'),
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
    @$transaction->process($this->getGateway());
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

  public function detectIpn(array $params)
  {
    return false; // Never detect this as an IPN, or it will break real IPNs
  }



  // SE Specific

  /**
   * Create a transaction for a subscription
   *
   * @param User_Model_User $user
   * @param Zend_Db_Table_Row_Abstract $subscription
   * @param Zend_Db_Table_Row_Abstract $package
   * @param array $params
   * @return Engine_Payment_Gateway_Transaction
   */
  public function createSubscriptionTransaction(User_Model_User $user,
      Zend_Db_Table_Row_Abstract $subscription,
      Payment_Model_Package $package,
      array $params = array())
  {
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
      Payment_Model_Order $order, array $params = array())
  {
    // Check that gateways match
    if( $order->gateway_id != $this->_gatewayInfo->gateway_id ) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }

    // Get related info
    $user = $order->getUser();
    $subscription = $order->getSource();
    $package = $subscription->getPackage();

    //Change rate according to default currency and selected currency by member
    $session = new Zend_Session_Namespace('Payment_Subscription');
    $current_currency = $session->current_currency;
    $currencyChangeRate = $session->change_rate;
    if (empty($currencyChangeRate))
      $currencyChangeRate = 1;
    $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
    if ($current_currency != $defaultCurrency) {
      $currencyData = Engine_Api::_()->getDbTable('currencies', 'payment')->getCurrency($current_currency);
      $currencyChangeRate = $currencyData->change_rate;
    }

    // Check subscription state
    if($subscription->status == 'trial') {
      return 'active';
    } else if( $subscription->status == 'pending' ) {
      return 'pending';
    }

    // Let's log it
    $this->getGateway()->getLog()->log('Return: '
        . print_r($params, true), Zend_Log::INFO);

    // Should we accept this?
    // Update order with profile info and complete status?
    $order->state = 'complete';
    $order->gateway_order_id = 0; // Hack
    $order->save();

      // Insert transaction
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
    $transactionsTable->insert(array(
      'user_id' => $order->user_id,
      'gateway_id' => $this->_gatewayInfo->gateway_id,
      'timestamp' => new Zend_Db_Expr('NOW()'),
      'order_id' => $order->order_id,
      'type' => 'payment',
      'state' => 'pending',
      'gateway_transaction_id' => crc32(microtime() . $order->order_id), // Hack
      'amount' => $package->price, // @todo use this or gross (-fee)?
      'currency' => Engine_Api::_()->payment()->defaultCurrency(), //this is default currency set by admin
      'change_rate' => $currencyChangeRate, //currency change rate according to default currency
      'current_currency' => $current_currency, //currency which is user paid
    ));
    $transaction_id = $transactionsTable->getAdapter()->lastInsertId();
    $transaction = Engine_Api::_()->getItem('payment_transaction', $transaction_id);
    if(isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
      $transaction->setPhoto($_FILES['file']);
    }

    // Get benefit setting
    $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
        ->getBenefitStatus($user);

    $giveBenefit = true; // Need this

    // Enable now
    if( $giveBenefit ) {
    
      //Send notification to super admin
      $getAllAdmin = Engine_Api::_()->getDbTable('users', 'user')->getAllAdmin();
      if(engine_count($getAllAdmin) > 0) {
        $translate = Zend_Registry::get('Zend_Translate');
        
        $adminLink = 'http://' . $_SERVER['HTTP_HOST'] . Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'payment', 'controller' => 'index', 'action' => 'index'), 'admin_default', true);
        $adminSideLink = '<a href="'.$adminLink.'" >'.$translate->translate("site").'</a>';
        foreach($getAllAdmin as $admin) {
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($admin, $user, $user, 'payment_manual_subscribe', array('payment_method' => 'Cheque','adminsidelink' => $adminSideLink));
          
          Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin, 'payment_manual_subscribe', array(
            'payment_method' => 'Cheque',
            'sender_name' => $user->getTitle(),
            'admin_link' => $adminLink,
          ));
        }
      }
      
      // Update subscription
      $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
      $subscription->gateway_profile_id = crc32(time() . $order->order_id); // Hack
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

    // Enable later
    else {

      // Update subscription
      $subscription->gateway_id = $this->_gatewayInfo->gateway_id;
      $subscription->gateway_profile_id = crc32(time() . $order->order_id); // Hack
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
  {
    throw new Engine_Payment_Plugin_Exception('Not implemented');
  }

  /**
   * Cancel a subscription (i.e. disable the recurring payment profile)
   *
   * @params $transactionId
   * @return Engine_Payment_Plugin_Abstract
   */
  public function cancelSubscription($transactionId)
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
    return false;
  }

  /**
   * Generate href to a page detailing the transaction
   *
   * @param string $transactionId
   * @return string
   */
  public function getTransactionDetailLink($transactionId)
  {
    return false;
  }

  /**
   * Get raw data about an order or recurring payment profile
   *
   * @param string $orderId
   * @return array
   */
  public function getOrderDetails($orderId)
  {
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
    return false;
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
    throw new Engine_Payment_Plugin_Exception('Not implemented');
  }

  public function getGatewayUserForm(){
    $form = new Payment_Form_Gateway_Cheque(array('settings'=>$this->_gatewayInfo->config));
    $form->populate((array) $this->_gatewayInfo->config);
    return $form;
  }

  // Forms
  /**
   * Get the admin form for editing the gateway info
   *
   * @return Engine_Form
   */
  public function getAdminGatewayForm()
  {
    return new Payment_Form_Admin_Gateway_Cheque();
  }
  public function processAdminGatewayForm(array $values)
  {
    return $values;
  }
}
