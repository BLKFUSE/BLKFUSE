<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: PayPal.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Epaidcontent_Plugin_Gateway_PayPal extends Engine_Payment_Plugin_Abstract
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

    // @todo
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
      $class = 'Engine_Payment_Gateway_PayPal';
      Engine_Loader::loadClass($class);
      $gateway = new $class(array(
        'config' => (array) $this->_gatewayInfo->config,
        'testMode' => $this->_gatewayInfo->test_mode,
        'currency' => Engine_Api::_()->epaidcontent()->getCurrentCurrency(),
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
    $transaction->process($this->getGateway());
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
	/**
   * Create a transaction for a user order
   *
   * @param User_Model_User $user
   * @param Zend_Db_Table_Row_Abstract $order
   * @param Zend_Db_Table_Row_Abstract $page
   * @param array $params
   * 
	*/
  public function createOrderTransaction($viewer,$order,$package,array $params = array()) {
  
    // description
    $description = $package->getTitle();
    if( strlen($description) > 128 ) {
      $description = substr($description, 0, 125) . '...';
    } else if( !$description || !strlen($description) ) {
      $description = 'N/A';
    }
    if( function_exists('iconv') && strlen($description) != iconv_strlen($description) ) {
      // PayPal requires that DESC be single-byte characters
      $description = @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $description);
    }
		$currentCurrency = Engine_Api::_()->epaidcontent()->getCurrentCurrency();
		$defaultCurrency = Engine_Api::_()->epaidcontent()->defaultCurrency();
		$settings = Engine_Api::_()->getApi('settings', 'core');
		$currencyValue = 1;
		if($currentCurrency != $defaultCurrency){
				$currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
		}
			
    $price = $priceTotal = @round($params['amount']*$currencyValue,2);
    $ticket_order[] = array(
      'NAME' => $package->getTitle(),
      'AMT' => $price,
      'QTY' => 1,
    );
    $order->currency_symbol = Engine_Api::_()->epaidcontent()->getCurrentCurrency();
		$order->total_amount = @round($price/$currencyValue,2);
		$order->change_rate = $currencyValue;
		$order->creation_date	= date('Y-m-d H:i:s');
		$order->gateway_type = 'Paypal';
    
    //Admin commission
    $commissionType = Engine_Api::_()->authorization()->getPermission($viewer,'epaidcontent','epaidcontent_admcosn');
    $commissionTypeValue = Engine_Api::_()->authorization()->getPermission($viewer,'epaidcontent','epaidcontent_commival');
    
		//%age wise
		if($commissionType == 1 && $commissionTypeValue > 0) {
      $order->commission_amount = round(($priceTotal/$currencyValue) * ($commissionTypeValue/100),2);
		} else if($commissionType == 2 && $commissionTypeValue > 0) {
      $order->commission_amount = $commissionTypeValue;
		}
		
		$order->save();
		$params['driverSpecificParams']['PayPal'] = array(
			'AMT' => @round($priceTotal, 2),
			'ITEMAMT' => @round($priceTotal, 2),
			'SELLERID' => '1',
			'DESC'=>$description,
			'SHIPPINGAMT' => 0,
			'TAXAMT'=>0,
			'ITEMS' => $ticket_order,
			'SOLUTIONTYPE' => 'sole',
    );
    
    // Should fix some issues with GiroPay
    if( !empty($params['return_url']) ) {
      $params['driverSpecificParams']['PayPal']['GIROPAYSUCCESSURL'] = $params['return_url']
        . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
      $params['driverSpecificParams']['PayPal']['BANKTXNPENDINGURL'] = $params['return_url']
        . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
    }
    if( !empty($params['cancel_url']) ) {
      $params['driverSpecificParams']['PayPal']['GIROPAYCANCELURL'] = $params['cancel_url']
        . ( false === strpos($params['return_url'], '?') ? '?' : '&' ) . 'giropay=1';
    }
    // Create transaction
    $transaction = $this->createTransaction($params);
	
    return $transaction;
  }
  
	public function orderTicketTransactionReturn($order,$params = array()){
    // Check that gateways match
    if( $order->gateway_id != $this->_gatewayInfo->gateway_id ) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }    
    // Get related info
    $user = $order->getUser();
    $orderPaidContent = $order->getSource();
    if ($orderPaidContent->state == 'pending') 
    {
      return 'pending';
    }
    // Check for cancel state - the user cancelled the transaction
    if( $params['state'] == 'cancel' ) {
      // Cancel order and subscription?
      $order->onCancel();
      // Error
      throw new Payment_Model_Exception('Your payment has been cancelled and ' .
          'not been charged. If this is not correct, please try again later.');
    }
    // Check params
    if( empty($params['token']) ) {
      // Cancel order and subscription?
      $order->onFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
          'transaction. Please try again later.');
    }
    // Get details
    try {
      $data = $this->getService()->detailExpressCheckout($params['token']);
    } catch( Exception $e ) {
      // Cancel order and subscription?
      $order->onFailure();
      // This is a sanity error and cannot produce information a user could use
      // to correct the problem.
      throw new Payment_Model_Exception('There was an error processing your ' .
          'transaction. Please try again later.');
    }
    // Let's log it
    $this->getGateway()->getLog()->log('ExpressCheckoutDetail: '
        . print_r($data, true), Zend_Log::INFO);
		//payment currency
		$currentCurrency = Engine_Api::_()->epaidcontent()->getCurrentCurrency();
		$defaultCurrency = Engine_Api::_()->epaidcontent()->defaultCurrency();
		$settings = Engine_Api::_()->getApi('settings', 'core');
		$currencyValue = 1;
		if($currentCurrency != $defaultCurrency){
				$currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
		}
      // Do payment
      try {
        $rdata = $this->getService()->doExpressCheckoutPayment($params['token'],
         $params['PayerID'], array(
          'PAYMENTACTION' => 'Sale',
          'AMT' => $data['AMT'],
          'CURRENCYCODE' => $this->getGateway()->getCurrency(),
        ));
      } catch( Exception $e ) {
        // Log the error
        $this->getGateway()->getLog()->log('DoExpressCheckoutPaymentError: '
            . $e->__toString(), Zend_Log::ERR);   
        // Cancel order and subscription?
        $order->onFailure();
				//update ticket state
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
      switch( strtolower($rdata['PAYMENTINFO'][0]['PAYMENTSTATUS']) ) {
        case 'created':
        case 'pending':
          $paymentStatus = 'pending';
          $orderStatus = 'complete';
          break;
        case 'completed':
        case 'processed':
        case 'canceled_reversal': // Probably doesn't apply
          $paymentStatus = 'okay';
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
      $order->state = $orderStatus;
      $order->gateway_transaction_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
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
        'gateway_transaction_id' => $rdata['PAYMENTINFO'][0]['TRANSACTIONID'],
        'amount' => $rdata['AMT'], // @todo use this or gross (-fee)?
        'currency' => $rdata['PAYMENTINFO'][0]['CURRENCYCODE'],
      ));
      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user); 
      // Check payment status
      if( $paymentStatus == 'okay' ||
          ($paymentStatus == 'pending' && $giveBenefit) ) {
        // Update order table info
        $orderPaidContent->gateway_id = $this->_gatewayInfo->gateway_id;
        $orderPaidContent->gateway_transaction_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
				$orderPaidContent->currency_symbol = $rdata['PAYMENTINFO'][0]['CURRENCYCODE'];
				$orderPaidContent->change_rate = $currencyValue;
				$orderPaidContent->save();
				$orderAmount = round($orderPaidContent->total_amount,2);
				$commissionValue = round($orderPaidContent->commission_amount,2);
				if(isset($commissionValue) && $orderAmount > $commissionValue){
					$orderAmount = $orderAmount - $commissionValue;	
				}else{
					$orderPaidContent->commission_amount = 0;
				}
				
				//Update content OWNER REMAINING amount
				$tableRemaining = Engine_Api::_()->getDbtable('remainingpayments', 'epaidcontent');
				$tableName = $tableRemaining->info('name');
				$select = $tableRemaining->select()->from($tableName)->where('user_id =?',$orderPaidContent->package_owner_id);
				$select = $tableRemaining->fetchAll($select);
				if(engine_count($select)){
					$tableRemaining->update(array('remaining_payment' => new Zend_Db_Expr("remaining_payment + $orderAmount")),array('user_id =?'=>$orderPaidContent->package_owner_id));
				} else {
					$tableRemaining->insert(array(
						'remaining_payment' => $orderAmount,
						'user_id' => $orderPaidContent->package_owner_id,
					));
				}
				
        //For Coupon
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')){
           $orderPaidContent->ordercoupon_id = Engine_Api::_()->ecoupon()->setAppliedCouponDetails($params['couponSessionCode']);
           $orderPaidContent->save();
        }
        
        //For Credit 
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit') && isset($params['creditCode'])) {
          $sessionCredit = new Zend_Session_Namespace($params['creditCode']);
          $orderPaidContent->credit_point = $sessionCredit->credit_value;  
          $orderPaidContent->credit_value =  $sessionCredit->purchaseValue;
          $transaction->save();
          $userCreditDetailTable = Engine_Api::_()->getDbTable('details', 'sescredit');
          $userCreditDetailTable->update(array('total_credit' => new Zend_Db_Expr('total_credit - ' . $sessionCredit->credit_value)), array('owner_id =?' => $order->user_id));
        }
        // Payment success
        $orderPaidContent->onOrderComplete();
        $orderPaidContent->save();
        
        // send notification
        if( $order->state == 'complete' ) {
        
          //Cancelled all previous order of current user
          $getViewerAllOrders = Engine_Api::_()->getDbTable('orders', 'epaidcontent')->getViewerAllOrders(array('order_id' => $orderPaidContent->order_id, 'owner_id' => $user->user_id, 'noCondition' => 1, 'package_owner_id' => $orderPaidContent->package_owner_id));
          if(engine_count($getViewerAllOrders) > 0) {
            foreach($getViewerAllOrders as $getViewerAllOrder) {
              $getViewerAllOrder->state = 'cancelled';
              $getViewerAllOrder->save();
            }
            $order->state = 'complete';
            $order->save();
          }
          
		      //Notification Work
		      $package = Engine_Api::_()->getItem('epaidcontent_package', $orderPaidContent->package_id);
					$owner = Engine_Api::_()->getItem('user',$package->user_id);
          $totalAmounts = '[';
		      $totalAmounts .= 'Total:';
          $totalAmount = @round($orderPaidContent->total_amount,2);
          $totalAmounts .= Engine_Api::_()->epaidcontent()->getCurrencyPrice(@round($totalAmount,2),$orderPaidContent->currency_symbol, $orderPaidContent->change_rate);
          $totalAmounts .= ']';
          $body .= '<table style="background-color:#f9f9f9;border:#ececec solid 1px;width:100%;"><tr><td><div style="margin:0 auto;width:600px;font:normal 13px Arial,Helvetica,sans-serif;padding:20px;"><div style="margin-bottom:10px;overflow:hidden;"><div style="float:left;"><b>Order Id: #' . $orderPaidContent->order_id . '</b></div><div style="float:right;"><b>'.$totalAmounts.'</b></div></div><table style="background-color:#fff;border:#ececec solid 1px;margin-bottom:20px;" cellpadding="0" cellspacing="0" width="100%"><tr valign="top" style="width:50%;"><td><div style="border-bottom:#ececec solid 1px;padding:20px;"><b style="display:block;margin-bottom:5px;">Ordered For</b><span style="display:block;margin-bottom:5px;">'.$package->getTitle().'</span></div><div style="padding:20px;border-bottom:#ececec solid 1px;"> <b style="display:block;margin-bottom:5px;">Ordered By</b><span style="display:block;margin-bottom:5px;"><a href="'.( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] .$orderPaidContent->getOwner()->getHref().'" style="color:#39F;text-decoration:none;">'.$orderPaidContent->getOwner()->getTitle().'</a></span><span style="display:block;margin-bottom:5px;">'.$orderPaidContent->getOwner()->email.'</span></div><div style="padding:20px;"><b style="display:block;margin-bottom:5px;">Payment Information</b><span style="display:block;margin-bottom:5px;">Payment Method: '.$orderPaidContent->gateway_type.'</span></div></td><td style="border-left:#ececec solid 1px;width:50%;"><div style="padding:20px;"><b style="display:block;margin-bottom:5px;">Order Information</b><span style="display:block;margin-bottom:5px;">Ordered Date: '.$orderPaidContent->creation_date.'</span>';			   
			    $body .= '</div>';
			   
			    $body .= '</td></tr></table><div style="margin-bottom:10px;"><b class="bold">Order Details</b></div><div style="background-color:#fff;border:1px solid #ececec;padding:10px;"><div style="margin-bottom:5px;overflow:hidden;"><span style="float:left;">Sub Total</span><span style="float:right;">'.$totalAmounts.'</span> </div>';
			    $body .= '<div style="margin-bottom:5px;overflow:hidden;"><span style="float:left;"><b>Grand Total</b></span><span style="float:right;"><b>'.$totalAmounts.'</b></span></div></div></div> </td></tr></table>';

          //Send to package owner
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($owner, $user, $package, 'epaidcontent_order', array('package_name' => $package->title));
          
          //invoice mail to buyer
			    Engine_Api::_()->getApi('mail', 'core')->sendSystem($orderPaidContent->getOwner(), 'epaidcontent_orderinvoice_buyer', array('invoice_body' => $body, 'host' => $_SERVER['HTTP_HOST']));
			    
			    //Mail to package Owner
			    Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner, 'epaidcontent_orderpurchased_paidcontentowner', array('object_link' => $owner->getHref(), 'package_title' => $package->title, 'buyer_name' => $user->getTitle(), 'host' => $_SERVER['HTTP_HOST']));
        }
        return 'active';
      }
      else if( $paymentStatus == 'pending' ) {
        // Update order  info
        $order->gateway_id = $this->_gatewayInfo->gateway_id;
        $order->gateway_profile_id = $rdata['PAYMENTINFO'][0]['TRANSACTIONID'];
				$order->save();
        // Order pending
        $orderPaidContent->onOrderPending();
        //Send Mail
        $package = Engine_Api::_()->getItem('epaidcontent_package', $orderPaidContent->package_id);
        
				Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'epaidcontent_payment_order_pending', array('package_title' => $package->title, 'package_description' => $package->description, 'host' => $_SERVER['HTTP_HOST']));
        
        return 'pending';
      }
      else if( $paymentStatus == 'failed' ) {
        // Cancel order and subscription?
        $orderPaidContent->onOrderFailure();
				//update ticket state
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
  /**
   * Process return of subscription transaction
   *
   * @param Payment_Model_Order $order
   * @param array $params
   */
  public function onSubscriptionTransactionReturn(
      Payment_Model_Order $order, array $params = array())
  {}
	public function onOrderTicketTransactionIpn(
      Payment_Model_Order $order,
      Engine_Payment_Ipn $ipn
		){
		
    // Check that gateways match
    if( $order->gateway_id != $this->_gatewayInfo->gateway_id ) {
      throw new Engine_Payment_Plugin_Exception('Gateways do not match');
    }
    // Get related info
    $user = $order->getUser();
    $orderPaidContent = $order->getSource();
    // Get IPN data
    $rawData = $ipn->getRawData();
    // Get tx table
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
    // Chargeback --------------------------------------------------------------
    if( !empty($rawData['case_type']) && $rawData['case_type'] == 'chargeback' ) {
      $orderPaidContent->onOrderFailure(); // or should we use pending?
			//update ticket state
    }
    // Transaction Type --------------------------------------------------------
    else if( !empty($rawData['txn_type']) ) {
      switch( $rawData['txn_type'] ) {
        // @todo see if the following types need to be processed:
        // — adjustment express_checkout new_case
        case 'express_checkout':
          // Only allowed for one-time
            switch( $rawData['payment_status'] ) {
              case 'Created': // Not sure about this one
              case 'Pending':
                // @todo this might be redundant
                // Get benefit setting
                $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')->getBenefitStatus($user);
                if( $giveBenefit ) {
                  $orderPaidContent->onOrderSuccess();
									//update ticket state
                } else {
                  $orderPaidContent->onOrderPending();
									//update ticket state
                }
                break;
              case 'Completed':
              case 'Processed':
              case 'Canceled_Reversal': // Not sure about this one
                $orderPaidContent->onOrderSuccess();
								//update ticket state
                break;
              case 'Denied':
              case 'Failed':
              case 'Voided':
              case 'Reversed':
                $orderPaidContent->onOrderFailure();
								//update ticket state
                break;
              case 'Refunded':
                $orderPaidContent->onOrderRefund();
								//update ticket state
                break;
              case 'Expired': // Not sure about this one
                break;
              default:
                throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
                    'payment status %1$s', $rawData['payment_status']));
                break;
            }
          
          break;
        // What is this?
        default:
          throw new Engine_Payment_Plugin_Exception(sprintf('Unknown IPN ' .
              'type %1$s', $rawData['txn_type']));
          break;
      }
    }
    // Payment Status ----------------------------------------------------------
    else if( !empty($rawData['payment_status']) ) {
      switch( $rawData['payment_status'] ) {
        case 'Created': // Not sure about this one
        case 'Pending':
          // Get benefit setting
          $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')->getBenefitStatus($user);
          if( $giveBenefit ) {
                  $orderPaidContent->onOrderSuccess();
									//update ticket state
                } else {
                  $orderPaidContent->onOrderPending();
									//update ticket state
                }
          break;
        case 'Completed':
        case 'Processed':
        case 'Canceled_Reversal': // Not sure about this one
          $orderPaidContent->onOrderSuccess();
					//update ticket state
          break;
        case 'Denied':
        case 'Failed':
        case 'Voided':
        case 'Reversed':
           $orderPaidContent->onOrderFailure();
					//update ticket state

          break;
        case 'Refunded':
         $orderPaidContent->onOrderRefund();
					//update ticket state
					break;
        case 'Expired': // Not sure about this one
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
   * Process ipn of subscription transaction
   *
   * @param Payment_Model_Order $order
   * @param Engine_Payment_Ipn $ipn
   */
  public function onSubscriptionTransactionIpn(
      Payment_Model_Order $order,
      Engine_Payment_Ipn $ipn)
  {}
  /**
   * Cancel a subscription (i.e. disable the recurring payment profile)
   *
   * @params $transactionId
   * @return Engine_Payment_Plugin_Abstract
   */
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
    else if( is_string($transactionId) ) {
      $profileId = $transactionId;
    }
    else {
      // Should we throw?
      return $this;
    }
    try {
      $r = $this->getService()->cancelRecurringPaymentsProfile($profileId, $note);
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
    // @todo make sure this is correct
    // I don't think this works
    if( $this->getGateway()->getTestMode() ) {
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
  public function getTransactionDetailLink($transactionId)
  {
    // @todo make sure this is correct
    if( $this->getGateway()->getTestMode() ) {
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
  {
    $rawData = $ipn->getRawData();
    $ordersTable = Engine_Api::_()->getDbtable('orders', 'payment');
    $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
    // Find transactions -------------------------------------------------------
    $transactionId = null;
    $parentTransactionId = null;
    $transaction = null;
    $parentTransaction = null;
    // Fetch by txn_id
    if( !empty($rawData['txn_id']) ) {
      $transaction = $transactionsTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_transaction_id = ?' => $rawData['txn_id'],
      ));
      $parentTransaction = $transactionsTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_parent_transaction_id = ?' => $rawData['txn_id'],
      ));
    }
    // Fetch by parent_txn_id
    if( !empty($rawData['parent_txn_id']) ) {
      if( !$transaction ) {
        $parentTransaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_parent_transaction_id = ?' => $rawData['parent_txn_id'],
        ));
      }
      if( !$parentTransaction ) {
        $parentTransaction = $transactionsTable->fetchRow(array(
          'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
          'gateway_transaction_id = ?' => $rawData['parent_txn_id'],
        ));
      }
    }
    // Fetch by transaction->gateway_parent_transaction_id
    if( $transaction && !$parentTransaction &&
        !empty($transaction->gateway_parent_transaction_id) ) {
      $parentTransaction = $transactionsTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_parent_transaction_id = ?' => $transaction->gateway_parent_transaction_id,
      ));
    }
    // Fetch by parentTransaction->gateway_transaction_id
    if( $parentTransaction && !$transaction &&
        !empty($parentTransaction->gateway_transaction_id) ) {
      $transaction = $transactionsTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_parent_transaction_id = ?' => $parentTransaction->gateway_transaction_id,
      ));
    }
    // Get transaction id
    if( $transaction ) {
      $transactionId = $transaction->gateway_transaction_id;
    } else if( !empty($rawData['txn_id']) ) {
      $transactionId = $rawData['txn_id'];
    }
    // Get parent transaction id
    if( $parentTransaction ) {
      $parentTransactionId = $parentTransaction->gateway_transaction_id;
    } else if( $transaction && !empty($transaction->gateway_parent_transaction_id) ) {
      $parentTransactionId = $transaction->gateway_parent_transaction_id;
    } else if( !empty($rawData['parent_txn_id']) ) {
      $parentTransactionId = $rawData['parent_txn_id'];
    }
    // Fetch order -------------------------------------------------------------
    $order = null;
    // Transaction IPN - get order by invoice
    if( !$order && !empty($rawData['invoice']) ) {
      $order = $ordersTable->find($rawData['invoice'])->current();
    }
    // Subscription IPN - get order by recurring_payment_id
    if( !$order && !empty($rawData['recurring_payment_id']) ) {
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
    if( !$order && $parentTransactionId ) {
      $order = $ordersTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_transaction_id = ?' => $parentTransactionId,
      ));
    }
    // Transaction IPN - get order by txn_id
    if( !$order && $transactionId ) {
      $order = $ordersTable->fetchRow(array(
        'gateway_id = ?' => $this->_gatewayInfo->gateway_id,
        'gateway_transaction_id = ?' => $transactionId,
      ));
    }
    // Transaction IPN - get order through transaction
    if( !$order && !empty($transaction->order_id) ) {
      $order = $ordersTable->find($parentTransaction->order_id)->current();
    }
    // Transaction IPN - get order through parent transaction
    if( !$order && !empty($parentTransaction->order_id) ) {
      $order = $ordersTable->find($parentTransaction->order_id)->current();
    }
    // Process generic IPN data ------------------------------------------------
    // Build transaction info
    if( !empty($rawData['txn_id']) ) {
      $transactionData = array(
        'gateway_id' => $this->_gatewayInfo->gateway_id,
      );
      // Get timestamp
      if( !empty($rawData['payment_date']) ) {
        $transactionData['timestamp'] = date('Y-m-d H:i:s', strtotime($rawData['payment_date']));
      } else {
        $transactionData['timestamp'] = new Zend_Db_Expr('NOW()');
      }
      // Get amount
      if( !empty($rawData['mc_gross']) ) {
        $transactionData['amount'] = $rawData['mc_gross'];
      }
      // Get currency
      if( !empty($rawData['mc_currency']) ) {
        $transactionData['currency'] = $rawData['mc_currency'];
      }
      // Get order/user
      if( $order ) {
        $transactionData['user_id'] = $order->user_id;
        $transactionData['order_id'] = $order->order_id;
      }
      // Get transactions
      if( $transactionId ) {
        $transactionData['gateway_transaction_id'] = $transactionId;
      }
      if( $parentTransactionId ) {
        $transactionData['gateway_parent_transaction_id'] = $parentTransactionId;
      }
      // Get payment_status
      switch( $rawData['payment_status'] ) {
        case 'Canceled_Reversal': // @todo make sure this works
        case 'Completed':
        case 'Created':
        case 'Processed':
          $transactionData['type'] = 'payment';
          $transactionData['state'] = 'okay';
					
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
      if( !$transaction ) {
        $transactionsTable->insert($transactionData);
      }
      // Update transaction
      else {
        unset($transactionData['timestamp']);
        $transaction->setFromArray($transactionData);
        $transaction->save();
      }
      // Update parent transaction on refund?
      if( $parentTransaction && engine_in_array($transactionData['type'], array('refund','reversal')) ) {
        $parentTransaction->state = $transactionData['state'];
        $parentTransaction->save();
      }
    }
    // Process specific IPN data -----------------------------------------------
    if( $order ) {
      $ipnProcessed = false;
      // Subscription IPN
      if( $order->source_type == 'epaidcontent_order' ) {
        $this->onOrderTicketTransactionIpn($order, $ipn);
        $ipnProcessed = true;
      }
    }
    // Missing order
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
  public function getAdminGatewayForm()
  {
    return new Epaidcontent_Form_Admin_Gateway_PayPal();
  }
  public function processAdminGatewayForm(array $values)
  {
    return $values;
  }
}
