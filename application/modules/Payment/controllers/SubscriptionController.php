<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: SubscriptionController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
 
include_once APPLICATION_PATH . "/application/libraries/Engine/Service/Stripe/init.php";

class Payment_SubscriptionController extends Core_Controller_Action_Standard
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
  protected $_subscription;

  /**
   * @var Payment_Model_Package
   */
  protected $_package;

  public function init()
  {
    // If there are no enabled gateways or packages, disable
    //|| Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledNonFreePackageCount() <= 0
    if( Engine_Api::_()->getDbtable('gateways', 'payment')->getEnabledGatewayCount() <= 0) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }

    // Get user and session
    $this->_user = Engine_Api::_()->user()->getViewer();
    $this->_session = new Zend_Session_Namespace('Payment_Subscription');

    // Check viewer and user
    if( !$this->_user || !$this->_user->getIdentity() ) {
      if( !empty($this->_session->user_id) ) {
        $this->_user = Engine_Api::_()->getItem('user', $this->_session->user_id);
      }
      // If no user, redirect to home?
      if( !$this->_user || !$this->_user->getIdentity() ) {
        $this->_session->unsetAll();
        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
      }
    }
  }

  public function indexAction()
  {
    return $this->_forward('choose');
  }

  public function chooseAction()
  {
    // Check subscription status
    //if( $this->_checkSubscriptionStatus() ) {
    //  return;
    //}

    // Unset certain keys
    unset($this->_session->package_id);
    unset($this->_session->subscription_id);
    unset($this->_session->gateway_id);
    unset($this->_session->order_id);
    unset($this->_session->errorMessage);

    // Check for default plan
    $this->_checkDefaultPaymentPlan();

    // Make form
    $this->view->form = $form = new Payment_Form_Signup_Subscription(array(
      'isSignup' => false,
      'action' => $this->view->url(),
    ));
    
    // Process
    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
    $this->view->user = $user = $this->_user;
    if($user) {
      $this->view->currentSubscription = $currentSubscription = $subscriptionsTable->fetchRow(array(
        'user_id = ?' => $user->getIdentity(),
        'active = ?' => true,
      ));
      
      // Get current package
      if( $currentSubscription ) {
        $packagesTable = Engine_Api::_()->getDbtable('packages', 'payment');
        $this->view->currentPackage = $currentPackage = $packagesTable->fetchRow(array(
          'package_id = ?' => $currentSubscription->package_id,
        ));
      }
      
      // Get packages
      $this->view->packages = $packages = Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledAfterSignupPackageCount();
      
    } else {
      // Get packages
      $this->view->packages = $packages = Engine_Api::_()->getDbtable('packages', 'payment')->getEnabledPackageCount();
    }
    if($packages == 0) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }

    // Check method/valid
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Get package
    if( !($packageId = $this->_getParam('package_id', $this->_session->package_id)) ||
        !($package = Engine_Api::_()->getItem('payment_package', $packageId)) ) {
      return;
    }
    $this->view->package = $package;


    // Cancel any other existing subscriptions
    Engine_Api::_()->getDbtable('subscriptions', 'payment')
      ->cancelAll($user, 'User cancelled the subscription.', $currentSubscription);

    // Insert the new temporary subscription
    $db = $subscriptionsTable->getAdapter();
    $db->beginTransaction();

    try {
      $subscription = $subscriptionsTable->createRow();
      $subscription->setFromArray(array(
        'package_id' => $package->package_id,
        'user_id' => $user->getIdentity(),
        'status' => 'initial',
        'active' => false, // Will set to active on payment success
        'creation_date' => new Zend_Db_Expr('NOW()'),
      ));
      $subscription->save();

      // If the package is free, let's set it active now and cancel the other
      if( $package->isFree() ) {
        $subscription->setActive(true);
        $subscription->onPaymentSuccess();
        if( $currentSubscription ) {
          $currentSubscription->cancel();
        }
      }

      $subscription_id = $subscription->subscription_id;

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    $this->_session->subscription_id = $subscription_id;

    // Check if the user is good (this will happen if they choose a free plan)
    $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
    if( $package->isFree() && $subscriptionsTable->check($this->_user) ) {
      return $this->_finishPayment($package->isFree() ? 'free' : 'active');
    }

    // Otherwise redirect to the payment page
    return $this->_helper->redirector->gotoRoute(array('action' => 'gateway'));
  }

  public function gatewayAction()
  {
    // Get subscription
    $subscriptionId = $this->_getParam('subscription_id', $this->_session->subscription_id);
    if( !$subscriptionId ||
        !($subscription = Engine_Api::_()->getItem('payment_subscription', $subscriptionId))  ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
    }
    $this->view->subscription = $subscription;

    // Check subscription status
    if( $this->_checkSubscriptionStatus($subscription) ) {
      return;
    }

    // Get subscription
    if( !$this->_user ||
        !($subscriptionId = $this->_getParam('subscription_id', $this->_session->subscription_id)) ||
        !($subscription = Engine_Api::_()->getItem('payment_subscription', $subscriptionId)) ||
        $subscription->user_id != $this->_user->getIdentity() ||
        !($package = Engine_Api::_()->getItem('payment_package', $subscription->package_id)) ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
    }
    $this->view->subscription = $subscription;
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
          continue;
        }
      }
      $gatewayPlugins[] = array(
        'gateway' => $gateway,
        'plugin' => $gateway->getGateway(),
      );
    }
    $this->view->gateways = $gatewayPlugins;
  }

  public function processAction()
  {
    // Get gateway
    $gatewayId = $this->_getParam('gateway_id', $this->_session->gateway_id);
    if( !$gatewayId ||
        !($gateway = Engine_Api::_()->getItem('payment_gateway', $gatewayId)) ||
        !($gateway->enabled) ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'gateway'));
    }
    $this->view->gateway = $gateway;

    // Get subscription
    $subscriptionId = $this->_getParam('subscription_id', $this->_session->subscription_id);

    if( !$subscriptionId ||
        !($subscription = Engine_Api::_()->getItem('payment_subscription', $subscriptionId))  ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
    }
    $this->view->subscription = $subscription;

    // Get package
    $package = $subscription->getPackage();
    if( !$package || $package->isFree() ) {
      return $this->_helper->redirector->gotoRoute(array('action' => 'choose'));
    }
    $this->view->package = $package;

    // Check subscription?
//     if( $this->_checkSubscriptionStatus($subscription) ) {
//       return;
//     }

    // Process
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
      'source_type' => 'payment_subscription',
      'source_id' => $subscription->subscription_id,
    ));
    $this->_session->order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();
    $this->_session->current_currency = $currentCurrency = Engine_Api::_()->payment()->getCurrentCurrency();
    $currencyData = Engine_Api::_()->getDbTable('currencies', 'payment')->getCurrency($currentCurrency);
    $this->_session->change_rate = $currencyData->change_rate;

    // Unset certain keys
    unset($this->_session->package_id);
    unset($this->_session->subscription_id);
    unset($this->_session->gateway_id);


    // Get gateway plugin
    $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
    $plugin = $gateway->getPlugin();

    // Prepare host info
    $schema = _ENGINE_SSL ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];

    // Prepare transaction
    $params = array();
    $params['language'] = $this->_user->language;
    $localeParts = explode('_', $this->_user->language);
    if( engine_count($localeParts) > 1 ) {
      $params['region'] = $localeParts[1];
    }
    $params['vendor_order_id'] = $order_id;
    
    $action = 'index';
    if($gateway->plugin == "Payment_Plugin_Gateway_Stripe") {
      $action = 'stripe';
    }
    
    $this->view->returnUrl = $params['return_url'] = $schema . $host
      . $this->view->url(array('action' => 'return'))
      . '?order_id=' . $order_id
      //. '?gateway_id=' . $this->_gateway->gateway_id
      //. '&subscription_id=' . $this->_subscription->subscription_id
      . '&state=' . 'return';
    $params['cancel_url'] = $schema . $host
      . $this->view->url(array('action' => 'return'))
      . '?order_id=' . $order_id
      //. '?gateway_id=' . $this->_gateway->gateway_id
      //. '&subscription_id=' . $this->_subscription->subscription_id
      . '&state=' . 'cancel';
    $params['ipn_url'] = $schema . $host
      . $this->view->url(array('action' => $action, 'controller' => 'ipn'))
      . '?order_id=' . $order_id;
      //. '?gateway_id=' . $this->_gateway->gateway_id
      //. '&subscription_id=' . $this->_subscription->subscription_id;
    
    $this->view->gateway_id = $gateway_id = $gateway->getIdentity();
    
    if($gateway->plugin == "Payment_Plugin_Gateway_Stripe") {
    
      $params['order_id'] = $order_id;
      $params['amount'] = $package->price;
      $params['type'] = 'user';
      $params['currency'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
      
      $this->view->publishKey = $publishKey = $gateway->config['publish'];

      $secretKey = $gateway->config['secret'];
      \Stripe\Stripe::setApiKey($secretKey);

      $this->view->session = $plugin->createSubscriptionTransaction($this->_user, $subscription, $package, $params);
    } else {

      // Process transaction
      $transaction = $plugin->createSubscriptionTransaction($this->_user,
          $subscription, $package, $params);

      // Pull transaction params
      $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
      $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
      $this->view->transactionData = $transactionData = $transaction->getData();

      // Handle redirection
      if( $transactionMethod == 'GET' ) {
        $transactionUrl .= '?' . http_build_query($transactionData);
        return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
      }
    }

    // Post will be handled by the view script
  }

  public function returnAction()
  {
    // Get order
    if( !$this->_user ||
        !($orderId = $this->_getParam('order_id', $this->_session->order_id)) ||
        !($order = Engine_Api::_()->getItem('payment_order', $orderId)) ||
        $order->user_id != $this->_user->getIdentity() ||
        $order->source_type != 'payment_subscription' ||
        !($subscription = $order->getSource()) ||
        !($package = $subscription->getPackage()) ||
        !($gateway = Engine_Api::_()->getItem('payment_gateway', $order->gateway_id)) ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }
    $this->_subscription = $subscription;
    // Get gateway plugin
    $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
    $plugin = $gateway->getPlugin();

    // Process return
    unset($this->_session->errorMessage);
    try {
        $status = $plugin->onSubscriptionTransactionReturn($order, $this->_getAllParams());
        if(($status == 'active' || $status == 'free')) {
            $admins = Engine_Api::_()->user()->getSuperAdmins();
            foreach($admins as $admin){
                Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin,'payment_subscription_transaction', array(
                    'gateway_type' => $gateway->title,
                    'object_link' => 'http://' . $_SERVER['HTTP_HOST'] .
                        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module'=>'payment'), 'admin_default', true),
                ));
            }
        }
    } catch( Payment_Model_Exception $e ) {
      $status = 'failure';
      $this->_session->errorMessage = $e->getMessage();
    }

    return $this->_finishPayment($status);
  }

  public function finishAction()
  {
    $this->view->status = $status = $this->_getParam('state');
    $this->view->error = $this->_session->errorMessage;
    $this->view->url = $this->view->url(array(), 'default', true);
    // If user's member level changed then redirect to edit profile page.
    if (Engine_Api::_()->getDbtable('values', 'authorization')->changeUsersProfileType($this->_user)) {
      Engine_Api::_()->getDbtable('values', 'authorization')->resetProfileValues($this->_user);
      $this->view->url = $this->view->url(array('action' => 'profile', 'controller' => 'edit'), 'user_extended');
    }
  }

  protected function _checkSubscriptionStatus(
      Zend_Db_Table_Row_Abstract $subscription = null)
  {
    if( !$this->_user ) {
      return false;
    }

    if( null === $subscription ) {
      $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
      $subscription = $subscriptionsTable->fetchRow(array(
        'user_id = ?' => $this->_user->getIdentity(),
        'active = ?' => true,
      ));
    }

    if( !$subscription ) {
      return false;
    }

    if( $subscription->status == 'active' ||
        $subscription->status == 'trial' ) {
      if( !$subscription->getPackage()->isFree() ) {
        $this->_finishPayment('active');
      } else {
        $this->_finishPayment('free');
      }
      return true;
    } else if( $subscription->status == 'pending' ) {
      $this->_finishPayment('pending');
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
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }

    if( null === $this->_subscription ) {
      $subscriptionsTable = Engine_Api::_()->getDbtable('subscriptions', 'payment');
      $this->_subscription = $subscriptionsTable->fetchRow(array(
        'user_id = ?' => $this->_user->getIdentity(),
        'active = ?' => true,
      ));
    }

    // Log the user in, if they aren't already
    if( ($state == 'active' || $state == 'free' || ($this->_subscription && $this->_subscription->status == 'active')) &&
        $this->_user &&
        !$this->_user->isSelf($viewer) &&
        !$viewer->getIdentity() ) {
      Zend_Auth::getInstance()->getStorage()->write($this->_user->getIdentity());
      Engine_Api::_()->user()->setViewer();
      $viewer = $this->_user;
    }

    // Handle email verification or pending approval
    if( $viewer->getIdentity() && !$viewer->enabled ) {
      Engine_Api::_()->user()->setViewer(null);
      Engine_Api::_()->user()->getAuth()->getStorage()->clear();

      $confirmSession = new Zend_Session_Namespace('Signup_Confirm');
      $confirmSession->approved = $viewer->approved;
      $confirmSession->verified = $viewer->verified;
      $confirmSession->enabled  = $viewer->enabled;
      return $this->_helper->_redirector->gotoRoute(array('action' => 'confirm'), 'user_signup', true);
    }

    // Clear session
    $errorMessage = $this->_session->errorMessage;
    $userIdentity = $this->_session->user_id;
    $this->_session->unsetAll();
    $this->_session->user_id = $userIdentity;
    $this->_session->errorMessage = $errorMessage;

    // Redirect
    if( $state == 'free' ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    } else {
      return $this->_helper->redirector->gotoRoute(array('action' => 'finish', 'state' => $state));
    }
  }

  protected function _checkDefaultPaymentPlan()
  {
    // No user?
    if( !$this->_user ) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
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
