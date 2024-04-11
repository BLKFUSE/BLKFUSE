<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: PaymentController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_PaymentController extends Core_Controller_Action_Standard
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
    // Get user and session
    $this->_user = Engine_Api::_()->user()->getViewer();
    $this->_session = new Zend_Session_Namespace('Payment_Egift');
    $this->_session->gateway_id = $gateway_id = $this->_getParam('gateway_id',0);
		// Check viewer and user

    $this->_item = Engine_Api::_()->getItem('egifts_giftpurchase', $this->_getParam('giftpurchase_id'));
    if (!$this->_item)
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'egifts_general',true);

    if( !$this->_user || !$this->_user->getIdentity() )
    {
      if( !empty($this->_session->user_id) )
      {
        $this->_user = Engine_Api::_()->getItem('user', $this->_session->user_id);
      }
    }

  }
  public function indexAction() {
    return $this->_forward('gateway');
  }

  public function gatewayAction() {
    if(!$this->_getParam('giftpurchase_id')) {
        return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'egifts_general',true);
    }
    $this->view->item = $this->_item;

    // Unset certain keys
    unset($this->_session->gateway_id);
    unset($this->_session->order_id);

    // Gateways
    $gatewayTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    $gatewaySelect = $gatewayTable->select()
            ->where('enabled = ?', 1)
            ->where('plugin = "Payment_Plugin_Gateway_PayPal"');
    $gateways = $gatewayTable->fetchAll($gatewaySelect);

   $gatewayPlugins = array();
    foreach ($gateways as $gateway) {
      $gatewayPlugins[] = array(
          'gateway' => $gateway,
          'plugin' => $gateway->getGateway(),
      );
    }
    $this->view->itemPrice = $this->_item->total_amount;
    // For Coupon 
    $this->view->gateways = $gatewayPlugins;
  }

  public function processAction()
  {
      // Get gateway
	    $giftpurchase_id = $this->_getParam('giftpurchase_id');
	    if(!isset($giftpurchase_id) || empty($giftpurchase_id))
	    {
	    	return false;
	    }
	   
      $_SESSION['giftpurchase_id'] = $giftpurchase_id;
      $gatewayId = $this->_getParam('gateway_id', $this->_session->gateway_id);
	    $giftOrder = Engine_Api::_()->getItem('egifts_giftpurchase', $giftpurchase_id);
      //check cheque and cod orders
      if($gatewayId == 21 || $gatewayId == 20){
          return $this->_finishPayment("processing");
      }
      if( !$gatewayId ||
          !($gateway = Engine_Api::_()->getDbtable('gateways', 'egifts')->find($gatewayId)->current()) ||
          !($gateway->enabled) || !$this->_getParam('giftpurchase_id')) {
          return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'egifts_general',true);
      }
      $this->view->gateway = $gateway;
      // Get package
      if( !$gatewayId ) {
          return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'egifts_general',true);
      }
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
          'source_type' => 'egifts_giftpurchase',
          'source_id' => $this->_getParam('giftpurchase_id'),
      ));
      $this->_session->order_id = $order_id = $ordersTable->getAdapter()->lastInsertId();
      $this->_session->currency = $currentCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $currencyData = Engine_Api::_()->getDbTable('currencies', 'payment')->getCurrency($currentCurrency);
      $this->_session->change_rate = $currencyData->change_rate;
      // Unset certain keys
      unset($this->_session->gateway_id);
      // Get gateway plugin
      $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
      $plugin = $gateway->getPlugin();
      // Prepare host info
      $schema = 'http://';
      if( !empty($_ENV["HTTPS"]) && 'on' == strtolower($_ENV["HTTPS"]))
          $schema = 'https://';

      $host = $_SERVER['HTTP_HOST'];

      // Prepare transaction
      $params = array();
      $params['language'] = $this->_user->getIdentity() ? $this->_user->language : Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US');
      $localeParts = explode('_', $this->_user->getIdentity() ? $this->_user->language : Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en_US'));
      if( engine_count($localeParts) > 1 ) {
          $params['region'] = $localeParts[1];
      }
      $params['vendor_order_id'] = $order_id;
      $params['return_url'] = $schema . $host
          . $this->view->url(array('action' => 'return','order_id'=>$this->_getParam('order_id')))
          . '?order_id=' . $order_id
          . '&state=' . 'return';
      $params['cancel_url'] = $schema . $host
          . $this->view->url(array('action' => 'return','order_id'=>$this->_getParam('order_id')))
          . '?order_id=' . $order_id
          . '&state=' . 'cancel';
      $params['ipn_url'] = $schema . $host . $this->view->url(array('action' => 'index', 'controller' => 'ipn', 'module' => 'egifts'), 'default') . '?order_id=' . $order_id.'&gateway_id='.$gatewayId;

      $totalprice = 0;
      $totalprice = $giftOrder->total_amount;
      // For credit 
      $creditCode =  'credit'.'-egifts-'.$giftOrder->getIdentity().'-'.$giftOrder->getIdentity();
      $sessionCredit = new Zend_Session_Namespace($creditCode);
      if(isset($sessionCredit->total_amount) && $sessionCredit->total_amount < $giftOrder->total_amount) { 
        $totalprice = $sessionCredit->total_amount;
      }
      // Process transaction
      if($totalprice > 0) {
        $transaction = $plugin->createOrderTransaction($this->_user,$giftOrder, $params);
      } else {
        $order = Engine_Api::_()->getItem('payment_order', $order_id);
        $user = $order->getUser();
        $item = $order->getSource();
        $order->state = 'complete';
        $item->state = 'complete';
        $item->save();
        $order->save();
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
        return $this->_finishPayment('active');
      }
      // Pull transaction params
      $this->view->transactionUrl = $transactionUrl = $gatewayPlugin->getGatewayUrl();
      $this->view->transactionMethod = $transactionMethod = $gatewayPlugin->getGatewayMethod();
      $this->view->transactionData = $transactionData = $transaction->getData();
      // Handle redirection
      $transactionUrl .= '?' . http_build_query($transactionData);
      return $this->_helper->redirector->gotoUrl($transactionUrl, array('prependBase' => false));
  }
  public function returnAction()
  {
    $orderId = $this->_getParam('order_id',$this->_session->order_id);
    $order = Engine_Api::_()->getItem('payment_order', $orderId);
    // Get order
    if( ((!$this->_user || $order->user_id != $this->_user->getIdentity())) ||
        !($orderId) ||
        !($order) ||
        $order->source_type != 'egifts_giftpurchase') {
        return $this->_helper->redirector->gotoRoute(array('action'=>'manage','id'=>$this->_session->order_id), 'egifts_general', true);
    }
    // Get gateway plugin
	  $gateway = Engine_Api::_()->getDbtable('gateways', 'egifts')->find($order->gateway_id)->current();
    $this->view->gatewayPlugin = $gatewayPlugin = $gateway->getGateway();
    if(($gateway->plugin == "Epaytm_Plugin_Gateway_Paytm") || ($gateway->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe")){
        return $this->_finishPayment($order->state);
		}
    $creditCode =  'credit'.'-egifts-'.$this->_item->getIdentity().'-'.$this->_item->getIdentity();
    $params = array();
    $params['creditCode'] = $creditCode;
    $plugin = $gateway->getPlugin();
    // Process return
    unset($this->_session->errorMessage);
    try {
      $status = $plugin->createOrderTransactionReturn($order, array_merge($this->_getAllParams(),$params));
    } catch( Payment_Model_Exception $e ) {
      $status = 'failure';
      $this->_session->errorMessage = $e->getMessage();
    }

    return $this->_finishPayment($status);
  }

  public function finishAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->status = $status = $this->_getParam('state');
    $this->view->error = $this->_session->errorMessage;
    //$this->_session->unsetAll();
    $order = Engine_Api::_()->getItem('egifts_giftpurchase',$this->_getParam('order_id',$this->_session->order_id));
    if(!empty($this->_session->order_id))
      unset($this->_session->order_id);
    //get all orders from parent order
    if(!$order)
    {
	    return $this->_helper->redirector->gotoRoute(array('action'=>'manage','id'=>$this->_session->order_id), 'egifts_general', true);
    }
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage','id'=>$this->_session->order_id), 'egifts_general', true);
    $this->view->order = $order;
    $this->view->status = $status = $this->_getParam('state');
    $this->view->error = $this->_session->errorMessage;
    $this->_session->unsetAll();
  }
  protected function _finishPayment($state = 'active')
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    //empty cart
	  $item = Engine_Api::_()->getItem('egifts_giftpurchase',$this->_session->order_id);

	  //Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($viewer,$viewer, $item, 'egifts_payment_donetouser',array('paymentstatus' => $this->_session->order_id));
	//  Engine_Api::_()->getApi('mail', 'core')->sendSystem($viewer, 'egifts_payment_donetouser', array('gift_title' => $item->getTitle(),'sender_title' => $viewer->getTitle(), 'object_link' => $item->getHref(), 'host' => $_SERVER['HTTP_HOST']));
	 // Engine_Api::_()->getApi('mail', 'core')->sendSystem($item->getOwner(), 'egifts_payment_done', array('gift_title' => $item->getTitle(),'sender_title' => $viewer->getTitle(),'object_link' => $item->getHref(), 'host' => $_SERVER['HTTP_HOST']));
	//  Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($item->getOwner(),$viewer, $item, 'egifts_payment_done',array('paymentstatus' => $this->_session->order_id));

    //end empty cart work
    // No user?
    if( !$viewer->getIdentity() && $_SERVER["REMOTE_ADDR"] != $this->_order->ip_address) {
      return $this->_helper->redirector->gotoRoute(array('action'=>'manage','id'=>$item->giftpurchase_id), 'egifts_general', true);
    }
    // Redirect
	  return $this->_helper->redirector->gotoRoute(array('action'=>'manage','id'=>$item->giftpurchase_id), 'egifts_general', true);
    //return $this->_helper->redirector->gotoRoute(array('action' => 'finish', 'state' => $state));
  }
}
