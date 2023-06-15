<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Stripe.php  2019-04-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
include_once APPLICATION_PATH . "/application/modules/Sesadvpmnt/Api/Stripe/init.php";
class Sescontestjoinfees_Plugin_Gateway_Stripe extends Engine_Payment_Plugin_Abstract
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
  public function createOrderTransaction($viewer,$order,$contest,array $params = array())
  {
   // description
    $description = $contest->getTitle();
    if( strlen($description) > 128 ) {
      $description = substr($description, 0, 125) . '...';
    } else if( !$description || !strlen($description) ) {
      $description = 'N/A';
    }
    if( function_exists('iconv') && strlen($description) != iconv_strlen($description) ) {
      // PayPal requires that DESC be single-byte characters
      $description = @iconv("UTF-8", "ISO-8859-1//TRANSLIT", $description);
    }
		$currentCurrency = Engine_Api::_()->sescontestjoinfees()->getCurrentCurrency();
		$defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
		$settings = Engine_Api::_()->getApi('settings', 'core');
		$currencyValue = 1;
		if($currentCurrency != $defaultCurrency){
				$currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
		}
			
    $price = $priceTotal = @round($params['amount']*$currencyValue,2);
    $order->currency_symbol = Engine_Api::_()->sescontestjoinfees()->getCurrentCurrency();
		$order->total_amount = @round($price/$currencyValue,2);
		$order->change_rate = $currencyValue;
		$order->creation_date	= date('Y-m-d H:i:s');
		$order->gateway_type = 'Paypal';
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)){
      $package = Engine_Api::_()->getItem('sescontestpackage_package',$contest->package_id);
      if($package){
        $paramsDecoded = json_decode($package->params,true);  
      }
      $commissionType = $paramsDecoded['sescontest_admin_commission'];
      $commissionTypeValue = $paramsDecoded['sescontest_commission_value'];
    }else{
      $commissionType = Engine_Api::_()->authorization()->getPermission($viewer,'contest','contest_admcosn');
		  $commissionTypeValue = Engine_Api::_()->authorization()->getPermission($viewer,'contest','contest_commival');
    }
		//%age wise
   
		if($commissionType == 1 && $commissionTypeValue > 0){
				$order->commission_amount = round(($priceTotal/$currencyValue) * ($commissionTypeValue/100),2);
		}else if($commissionType == 2 && $commissionTypeValue > 0){
				$order->commission_amount = $commissionTypeValue;
		}
		$order->save();
    $params['amount'] = @round($priceTotal, 2);
    $params['description'] = $description;
    return  $this->createContestOrderTransaction($params);
    // Create transaction
  }
  public function onSubscriptionReturn(
      Payment_Model_Order $order,$transaction){
  }
  public function orderTicketTransactionReturn($order,$params){
    // Check that gateways match
      if($order->gateway_id != $this->_gatewayInfo->gateway_id) {
        throw new Engine_Payment_Plugin_Exception('Gateways do not match');
      }    
      // Get related info
      $user = $order->getUser();
      $orderTicket = $order->getSource();

  		//payment currency
  		$currentCurrency = Engine_Api::_()->sescontestjoinfees()->getCurrentCurrency();
  		$defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
  		$settings = Engine_Api::_()->getApi('settings', 'core');
  		$currencyValue = 1;
  		if($currentCurrency != $defaultCurrency){
  				$currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
  		}

      $session = \Stripe\Checkout\Session::retrieve($params['session_id']);
      $paymentStatus = 'okay';
      $orderStatus = 'complete';
      $transaction = \Stripe\PaymentIntent::retrieve($session['payment_intent']);
      
      // Update order with profile info and complete status?
      $order->state = $orderStatus;
      $order->gateway_transaction_id = $transaction->id;
      $order->save();
      //For Coupon
      $transactionsTable = Engine_Api::_()->getDbtable('transactions', 'payment');
      $transactionsTable->insert(array(
        'user_id' => $order->user_id,
        'gateway_id' => $this->_gatewayInfo->gateway_id,
        'timestamp' => new Zend_Db_Expr('NOW()'),
        'order_id' => $order->order_id,
        'type' => 'payment',
        'state' => $paymentStatus,
        'gateway_transaction_id' => $transaction->id,
        'amount' => $transaction->amount/100, // @todo use this or gross (-fee)?
        'currency' => strtoupper($transaction->currency),
      ));
      // Get benefit setting
      $giveBenefit = Engine_Api::_()->getDbtable('transactions', 'payment')
          ->getBenefitStatus($user); 
      // Check payment status
      if( $paymentStatus == 'okay' ||
          ($paymentStatus == 'pending' && $giveBenefit) ) {
        // Update order table info
        $orderTicket->gateway_id = $this->_gatewayInfo->gateway_id;
        $orderTicket->gateway_transaction_id = $transaction->id;
				$orderTicket->currency_symbol = strtoupper($transaction->currency);
				$orderTicket->change_rate = $currencyValue;
				$orderTicket->save();
				$orderAmount = round($orderTicket->total_amount,2);
				$commissionValue = round($orderTicket->commission_amount,2);
				if(isset($commissionValue) && $orderAmount > $commissionValue){
					$orderAmount = $orderAmount - $commissionValue;	
				}else{
					$orderTicket->commission_amount = 0;
				}
				//update CONTEST OWNER REMAINING amount
				$tableRemaining = Engine_Api::_()->getDbtable('remainingpayments', 'sescontestjoinfees');
				$tableName = $tableRemaining->info('name');
				$select = $tableRemaining->select()->from($tableName)->where('contest_id =?',$orderTicket->contest_id);
				$select = $tableRemaining->fetchAll($select);
				if(engine_count($select)){
					$tableRemaining->update(array('remaining_payment' => new Zend_Db_Expr("remaining_payment + $orderAmount")),array('contest_id =?'=>$orderTicket->contest_id));
				}else{
					$tableRemaining->insert(array(
						'remaining_payment' => $orderAmount,
						'contest_id' => $orderTicket->contest_id,
					));
				}
        //For Coupon
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')){
           $orderTicket->ordercoupon_id = Engine_Api::_()->ecoupon()->setAppliedCouponDetails($params['couponSessionCode']);
           $orderTicket->save();
        }
        //For Credit 
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit') && isset($params['creditCode'])) {
          $sessionCredit = new Zend_Session_Namespace($params['creditCode']);
          $orderTicket->credit_point = $sessionCredit->credit_value;  
          $orderTicket->credit_value =  $sessionCredit->purchaseValue;
          $transaction->save();
          $userCreditDetailTable = Engine_Api::_()->getDbTable('details', 'sescredit');
          $userCreditDetailTable->update(array('total_credit' => new Zend_Db_Expr('total_credit - ' . $sessionCredit->credit_value)), array('owner_id =?' => $order->user_id));
        }
        // Payment success
        $orderTicket->onOrderComplete();
        $orderTicket->save();
        // send notification
        if( $order->state == 'complete' ) {          
		      //Notification Work
		      $contest = Engine_Api::_()->getItem('contest', $orderTicket->contest_id);
					$owner = $contest->getOwner();
          $totalAmounts = '[';
		      $totalAmounts .= 'Total:';
          $totalAmount = @round($orderTicket->total_amount,2);
          $totalAmounts .= Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice(@round($totalAmount,2),$orderTicket->currency_symbol, $orderTicket->change_rate);
          $totalAmounts .= ']';
          $body .= '<table style="background-color:#f9f9f9;border:#ececec solid 1px;width:100%;"><tr><td><div style="margin:0 auto;width:600px;font:normal 13px Arial,Helvetica,sans-serif;padding:20px;"><div style="margin-bottom:10px;overflow:hidden;"><div style="float:left;"><b>Order Id: #' . $orderTicket->order_id . '</b></div><div style="float:right;"><b>'.$totalAmounts.'</b></div></div><table style="background-color:#fff;border:#ececec solid 1px;margin-bottom:20px;" cellpadding="0" cellspacing="0" width="100%"><tr valign="top" style="width:50%;"><td><div style="border-bottom:#ececec solid 1px;padding:20px;"><b style="display:block;margin-bottom:5px;">Ordered For</b><span style="display:block;margin-bottom:5px;"><a href="'.( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] .$contest->getHref().'" style="color:#39F;text-decoration:none;">'.$contest->getTitle().'</a></span><span style="display:block;margin-bottom:5px;">'.$contest->starttime.' - '.$contest->endtime.'</span></div><div style="padding:20px;border-bottom:#ececec solid 1px;"> <b style="display:block;margin-bottom:5px;">Ordered By</b><span style="display:block;margin-bottom:5px;"><a href="'.( isset($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] .$orderTicket->getOwner()->getHref().'" style="color:#39F;text-decoration:none;">'.$orderTicket->getOwner()->getTitle().'</a></span><span style="display:block;margin-bottom:5px;">'.$orderTicket->getOwner()->email.'</span></div><div style="padding:20px;"><b style="display:block;margin-bottom:5px;">Payment Information</b><span style="display:block;margin-bottom:5px;">Payment Method: '.$orderTicket->gateway_type.'</span></div></td><td style="border-left:#ececec solid 1px;width:50%;"><div style="padding:20px;"><b style="display:block;margin-bottom:5px;">Order Information</b><span style="display:block;margin-bottom:5px;">Ordered Date: '.$orderTicket->creation_date.'</span>';			   
			    $body .= '</div>';
			   
			    $body .= '</td></tr></table><div style="margin-bottom:10px;"><b class="bold">Order Details</b></div><div style="background-color:#fff;border:1px solid #ececec;padding:10px;"><div style="margin-bottom:5px;overflow:hidden;"><span style="float:left;">Sub Total</span><span style="float:right;">'.$totalAmount.'</span> </div>';
			    $body .= '<div style="margin-bottom:5px;overflow:hidden;"><span style="float:left;"><b>Grand Total</b></span><span style="float:right;"><b>'.$totalAmount.'</b></span></div></div></div> </td></tr></table>';
          
				  //invoice mail to buyer
			    Engine_Api::_()->getApi('mail', 'core')->sendSystem($orderTicket->getOwner(), 'sescontestjoinfees_orderinvoice_buyer', array('invoice_body' => $body, 'host' => $_SERVER['HTTP_HOST']));
			    //Mail to Contest Owner
			    $contest_owner = $contest->getOwner();
			    Engine_Api::_()->getApi('mail', 'core')->sendSystem($contest_owner, 'sescontestjoinfees_orderpurchased_contestowner', array('contest_title' => $contest->title, 'object_link' => $contest->getHref(), 'buyer_name' => $user->getTitle(), 'host' => $_SERVER['HTTP_HOST']));
        }
        return 'active';
      }
      else if( $paymentStatus == 'pending' ) {
        // Update order  info
        $order->gateway_id = $this->_gatewayInfo->gateway_id;
        $order->gateway_profile_id = $transaction->id;
				$order->save();
        // Order pending
        $orderTicket->onOrderPending();
        //Send Mail
        $contest = Engine_Api::_()->getItem('contest', $orderTicket->contest_id);
				Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'sescontestjoinfees_payment_order_pending', array('contest_title' => $contest->title, 'contest_description' => $contest->description, 'object_link' => $contest->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        return 'pending';
      }
      else if( $paymentStatus == 'failed' ) {
        // Cancel order and subscription?
        $orderTicket->onOrderFailure();
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
  public function onSubscriptionTransactionReturn(Payment_Model_Order $order,array $params = array()){}
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
  public function createContestOrderTransaction($params = array()) {
    $currentCurrency = Engine_Api::_()->sescontestjoinfees()->getCurrentCurrency();
    $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $currencyValue = 1;
    if($currentCurrency != $defaultCurrency){
      $currencyValue = $settings->getSetting('sesmultiplecurrency.'.$currentCurrency);
    } 
    $logo = !empty($this->_gatewayInfo->config['sesadvpmnt_stripe_logo']) ? $this->_gatewayInfo->config['sesadvpmnt_stripe_logo'] : "https://";
    $logo = 'http://' . $_SERVER['HTTP_HOST'].$logo;
    return \Stripe\Checkout\Session::create([
      'payment_method_types' => ['card'],
      'line_items' => [[
        'name' => $this->_gatewayInfo->config['sesadvpmnt_stripe_title']." ",
        'description' => $this->_gatewayInfo->config['sesadvpmnt_stripe_description']." ",
        'images' => [$logo],
        'amount' => (int)$params['amount']*100,
        'currency' => $currentCurrency,
        'quantity' => 1,
      ]],
      'metadata'=>['order_id'=>$params['order_id'],'type'=>$params['type'],'change_rate'=>$currencyValue],
      'success_url' => $params['return_url'].'&session_id={CHECKOUT_SESSION_ID}',
      'cancel_url' => $params['cancel_url'],
    ]);
  }

  public function createOrderTransactionReturn($order,$transaction) {
      
    return 'active';
  }
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
  public function cancelSubscriptionOnExpiry($source, $package) {}
  public function onIpnTransaction($rawData){}
  public function onTransactionIpn(Payment_Model_Order $order,  $rawData) {}
  function setConfig(){}
  function test(){}

}
