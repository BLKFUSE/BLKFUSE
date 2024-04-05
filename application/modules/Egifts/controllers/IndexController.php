<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_IndexController extends Core_Controller_Action_Standard {

  public function paymentRequestsAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'egifts', 'egifts_cashgifts'))
			return $this->_forward('requireauth', 'error', 'core');
      
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_main', array(), 'egifts_main_managecashgifts');

    $this->view->userGateway = Engine_Api::_()->getDbTable('usergateways', 'egifts')->getUserGateway(array('owner_id' => $viewer->getIdentity()));

    $this->view->thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'egifts', 'egifts_threamt');
    
    $userpayrequestsTable = Engine_Api::_()->getDbTable('userpayrequests', 'egifts');
    
    $this->view->adminTotalCommission = $userpayrequestsTable->adminTotalCommission();
		
    $this->view->isAlreadyRequests = $userpayrequestsTable->getEgiftsRequests(array('owner_id' => $viewer->getIdentity(),'isPending'=>true));
    
    $this->view->paymentRequests = $userpayrequestsTable->getEgiftsRequests(array('owner_id' => $viewer->getIdentity(),'isPending'=>true));
    
    $this->view->orderDetails = Engine_Api::_()->getDbtable('giftpurchases', 'egifts')->getGiftStats(array('purchase_user_id' => $viewer->getIdentity()));
    
    //get ramaining amount
    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'egifts')->getGiftRemainingAmount(array('user_id' => $viewer->getIdentity()));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else
      $this->view->remainingAmount = $remainingAmount->remaining_payment;

  }

	public function paymentTransactionAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'egifts', 'egifts_cashgifts'))
			return $this->_forward('requireauth', 'error', 'core');
      
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_main', array(), 'egifts_main_managecashgifts');
		$this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'egifts')->getEgiftsRequests(array('owner_id' => $viewer->getIdentity(), 'state' => 'complete'));
	}

	public function paymentRequestAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'egifts', 'egifts_cashgifts'))
			return $this->_forward('requireauth', 'error', 'core');

		$thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'egifts', 'egifts_threamt');
		
		$defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();
		
    $orderDetails = Engine_Api::_()->getDbtable('giftpurchases', 'egifts')->getGiftStats(array('purchase_user_id' => $viewer->getIdentity()));
    
		$this->view->form = $form = new Egifts_Form_Paymentrequest();
		$value = array();
		
		//get remaining amount
    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'egifts')->getGiftRemainingAmount(array('user_id' => $viewer->getIdentity()));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else {
      $this->view->remainingAmount = $remainingAmount->remaining_payment;
    }
    
    $value['total_amount'] = Engine_Api::_()->payment()->getCurrencyPrice($orderDetails['totalAmountSale'], $defaultCurrency);
    //$value['total_commission_amount'] = Engine_Api::_()->payment()->getCurrencyPrice($orderDetails['commission_amount'], $defaultCurrency);
    $value['remaining_amount'] = Engine_Api::_()->payment()->getCurrencyPrice($remainingAmount->remaining_payment, $defaultCurrency);

    //set value to form
    if ($this->_getParam('id', false)) {
      $item = Engine_Api::_()->getItem('egifts_userpayrequest', $this->_getParam('id'));
      if ($item) {
        $itemValue = $item->toArray();
        //unset($value['requested_amount']);
        $value = array_merge($itemValue, $value);
      } else {
        return $this->_forward('requireauth', 'error', 'core');
      }
    } else {
			$value['requested_amount'] = round($remainingAmount->remaining_payment, 2);
    }
    
		if (empty($_POST))
				$form->populate($value);

		if (!$this->getRequest()->isPost())
				return;
				
		if (!$form->isValid($this->getRequest()->getPost()))
				return;
				
		if (@round($thresholdAmount, 2) > @round($remainingAmount->remaining_payment, 2) && empty($_POST)) {
      $this->view->message = 'Remaining amount is less than Threshold amount.';
      $this->view->errorMessage = true;
      return;
    } else if (isset($_POST['requested_amount']) && @round($_POST['requested_amount'], 2) > @round($remainingAmount->remaining_payment, 2)) {
      $form->addError('Requested amount must be less than or equal to remaining amount.');
      return;
    } else if (isset($_POST['requested_amount']) && @round($thresholdAmount) > @round($_POST['requested_amount'], 2)) {
      $form->addError('Requested amount must be greater than or equal to threshold amount.');
      return;
    }
		
		$tableUserpayrequests = Engine_Api::_()->getDbtable('userpayrequests', 'egifts');
		
		$db = $tableUserpayrequests->getAdapter();
		$db->beginTransaction();
		try {
				if (isset($itemValue))
					$userpayrequest = $item;
				else
					$userpayrequest = $tableUserpayrequests->createRow();
					
				$userpayrequest->requested_amount = round($_POST['requested_amount'],2);
				$userpayrequest->user_message = $_POST['user_message'];
				$userpayrequest->owner_id = $viewer->getIdentity();
				$userpayrequest->creation_date = date('Y-m-d h:i:s');
				$userpayrequest->currency_symbol = $defaultCurrency;

				//Admin commission
				$commissionType = Engine_Api::_()->authorization()->getPermission($viewer,'egifts','egifts_admcosn');
				$commissionTypeValue = Engine_Api::_()->authorization()->getPermission($viewer,'egifts','egifts_commival');
				
				//percentage wise
				if($commissionType == 1 && $commissionTypeValue > 0) {
					$userpayrequest->total_commission_amount = round($_POST['requested_amount'] * ($commissionTypeValue/100),2);
				} else if($commissionType == 2 && $commissionTypeValue > 0) {
					$userpayrequest->total_commission_amount = $commissionTypeValue;
				}
				$userpayrequest->save();

				//Notification to super admin 
				$owner_admin = Engine_Api::_()->getItem('user', 1);
				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner_admin, $viewer, $viewer, 'egifts_payrequest', array('requestAmount' => round($_POST['requested_amount'],2)));

				$db->commit();
				
				$this->view->status = true;
				$this->view->message = Zend_Registry::get('Zend_Translate')->_('Payment request send successfully.');
				return $this->_forward('success', 'utility', 'core', array(
					'smoothboxClose' => 10,
					'parentRefresh' => 10,
					'messages' => array($this->view->message)
				));
		} catch (Exception $e) {
				$db->rollBack();
				throw $e;
		}
	}

	public function accountDetailsAction() {
	
		if (!$this->_helper->requireUser()->isValid())
      return;
		
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_main', array(), 'egifts_main_managecashgifts');

		$gateway_type = $this->view->gateway_type = $this->_getParam('gateway_type', "paypal");
		$viewer = Engine_Api::_()->user()->getViewer();

		$userGateway = Engine_Api::_()->getDbtable('usergateways', 'egifts')->getUserGateway(array('gateway_type'=>$gateway_type,'owner_id'=>$viewer->getIdentity()));
		
		$settings = Engine_Api::_()->getApi('settings', 'core');
		
		$userGatewayEnable = $settings->getSetting('payment.userGateway', 'paypal');
		
		$this->view->form = $form = new Egifts_Form_PayPal();
		
		if($gateway_type == "paypal") {
			$userGatewayEnable = 'paypal';
			$this->view->form = $form = new Egifts_Form_PayPal();
			$gatewayTitle = 'Paypal';
			$gatewayClass= 'Egifts_Plugin_Gateway_PayPal';
		}
		
		if (!empty($userGateway)) {
			$form->populate($userGateway->toArray());
			if (is_array($userGateway['config'])) {
				$form->populate($userGateway['config']);
			}
		}
		
		if (!$this->getRequest()->isPost())
			return;
			
		// Not post/invalid
		if (!$this->getRequest()->isPost())
			return;
			
		if (!$form->isValid($this->getRequest()->getPost()))
			return;
			
		// Process
		$values = $form->getValues();
		$enabled = (bool) $values['enabled'];
		unset($values['enabled']);
		$db = Engine_Db_Table::getDefaultAdapter();
		$db->beginTransaction();
		$userGatewayTable = Engine_Api::_()->getDbtable('usergateways', 'egifts');
		// insert data to table if not exists
		try {
			if (empty($userGateway)) {
				$gatewayObject = $userGatewayTable->createRow();
				$gatewayObject->user_id = $viewer->getIdentity();
				$gatewayObject->title = $gatewayTitle;
				$gatewayObject->plugin = $gatewayClass;
				$gatewayObject->gateway_type = $gateway_type;
				$gatewayObject->save();
			} else {
				$gatewayObject = Engine_Api::_()->getItem("egifts_usergateway", $userGateway['usergateway_id']);
			}
			$db->commit();

		} catch (Exception $e) {
			echo $e->getMessage();
		}
		// Validate gateway config
		if ($enabled && !empty($userGateway->plugin)) {
			$gatewayObjectObj = $gatewayObject->getGateway($userGateway->plugin);
			try {
				$gatewayObjectObj->setConfig($values);
				$response = $gatewayObjectObj->test();
			} catch (Exception $e) {
				$enabled = false;
				$form->populate(array('enabled' => false));
				$form->addError(sprintf('Gateway login failed. Please double check ' .
												'your connection information. The gateway has been disabled. ' .
												'The message was: [%2$d] %1$s', $e->getMessage(), $e->getCode()));
			}
		} else {
			$form->addError('Gateway is currently disabled.');
		}
		// Process
		$message = null;
		try {
			$values = $gatewayObject->getPlugin($userGateway->plugin)->processAdminGatewayForm($values);
		} catch (Exception $e) {
			$message = $e->getMessage();
			$values = null;
		}
		if (null !== $values) {
			$gatewayObject->setFromArray(array(
					'enabled' => $enabled,
					'config' => $values,
			));
			$gatewayObject->save();
			$form->addNotice('Changes saved.');
		} else {
			$form->addError($message);
		}
	}
	
	public function deletePaymentAction() {

		$viewer = Engine_Api::_()->user()->getViewer();
		
		$paymnetReq = Engine_Api::_()->getItem('egifts_userpayrequest', $this->getRequest()->getParam('id'));

		$this->_helper->layout->setLayout('default-simple');

		$this->view->form = $form = new Sesbasic_Form_Delete();
		$form->setTitle('Delete Payment Request?');
		$form->setDescription('Are you sure that you want to delete this payment request? It will not be recoverable after being deleted.');
		$form->submit->setLabel('Delete');

		if (!$paymnetReq) {
			$this->view->status = false;
			$this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to delete");
			return;
		}

		if (!$this->getRequest()->isPost()) {
			$this->view->status = false;
			$this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
			return;
		}

		$db = $paymnetReq->getTable()->getAdapter();
		$db->beginTransaction();

		try {
			$paymnetReq->delete();
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw $e;
		}

		$this->view->status = true;
		$this->view->message = Zend_Registry::get('Zend_Translate')->_('Payment Request has been deleted.');
		return $this->_forward('success', 'utility', 'core', array(
			'smoothboxClose' => 10,
			'parentRefresh' => 10,
			'messages' => array($this->view->message)
		));
	}
	
	public function detailPaymentAction() {

		$this->view->item = $paymnetReq = Engine_Api::_()->getItem('egifts_userpayrequest', $this->getRequest()->getParam('id'));
		$this->view->viewer = Engine_Api::_()->user()->getViewer();
		if (!$paymnetReq) {
			$this->view->status = false;
			$this->view->error = Zend_Registry::get('Zend_Translate')->_("Payment request doesn't exists or not authorized to delete");
			return;
		}
	}
	
  public function indexAction()
  {
	  $db = Engine_Db_Table::getDefaultAdapter();
	  $this->view->giftlist=$db->select()->from('engine4_egifts_gifts')->where('status = 1')->order('created_date DESC')->query()->fetchAll();
	  $this->view->userid = $userid = isset($_GET['userid']) ? trim($_GET['userid']) : 0;
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
	  if($userid==0)
	  {
	  	return false;
	  } 
  }
  
  public function myOrdersAction() {
    $this->_helper->content->setEnabled();
  }
  
  public function sendGiftAction()
  {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->giftlist=$db->select()->from('engine4_egifts_gifts')->where('status = 1')->order('created_date DESC')->query()->fetchAll();
    $this->view->giftid = $giftid = $this->_getParam("gift_id",0);
    if($giftid==0)
    {
      return false;
    } 
  }
	public function manageAction() {
		if( !$this->_helper->requireUser()->isValid() ) return;
		// Render
		// $this->_helper->content
			//->setNoRender()
			//->setEnabled();
		// Prepare data
		if(isset($_SESSION['giftpurchase_id']) && !empty($_SESSION['giftpurchase_id']))
		{
			$viewer = Engine_Api::_()->user()->getViewer();
			$this->view->item = Engine_Api::_()->getItem('egifts_giftpurchase', $_SESSION['giftpurchase_id']);
			unset($_SESSION['giftpurchase_id']);
		}
		else
		{
			return false;
		}
	}
  public function purchasegiftAction()
  {
	  $viewer = Engine_Api::_()->user()->getViewer();
    if(!isset($_POST['giftid']) || empty($_POST['giftid'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please select gift it required.")));die;
    }
    if(!isset($_POST['userid']) || empty($_POST['userid'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please select a member for sending gift it required.")));die;
    }
    if(!isset($_POST['message']) || empty($_POST['message'])){
       echo json_encode(array('status'=>0,'message'=>$this->view->translate("Please Type your message it required")));die;
    }
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
  	if(isset($_POST['giftid']) && !empty($_POST['giftid']) && isset($_POST['message']) && $viewer->getIdentity())
	  {
     
      $db = Engine_Db_Table::getDefaultAdapter();
      $egiftordersTable = Engine_Api::_()->getDbTable('giftorders', 'egifts');
      $db->beginTransaction();
      $orderTotalAmount = 0;
      try{
   		  $gift_purchase = Engine_Api::_()->getDbtable('giftpurchases', 'egifts')->createRow();
        $gift_purchase->owner_id = $viewer->getIdentity();
        $gift_purchase->message = isset($_POST['message']) ? trim($_POST['message']) : null;
        $gift_purchase->purchase_user_id = isset($_POST['userid']) ? trim($_POST['userid']) : null;
        $gift_purchase->status = 1;
        $gift_purchase->is_private = $_POST['privacy'] ?? 0;
        $gift_purchase->created_date = date("Y-m-d H:i:s");
        $gift_purchase->gateway_transaction_id = null;
        $gift_purchase->transcation_status = 0;
        $gift_purchase->transcation_date = date("Y-m-d H:i:s");
        $gift_purchase->gift_id = $_POST['giftid'];
        $gift_purchase->save();
        foreach((array)$_POST['giftid'] as $giftid):
          $gift =  Engine_Api::_()->getItem('egifts_gift', $giftid);
          if(empty($gift))
            continue;
          $egiftorder = $egiftordersTable->createRow();
          $egiftorder->gift_id = $gift->gift_id;
          $egiftorder->status = 1;
          $egiftorder->gift_title = $gift->title;
          $egiftorder->gift_icon_id = $gift->icon_id;
          $egiftorder->gift_price = $gift->price;
          $egiftorder->owner_id = $viewer->getIdentity();
          $egiftorder->giftpurchase_id = $gift_purchase->giftpurchase_id;
          $egiftorder->save();
          $orderTotalAmount += $gift->price;
        endforeach;
        $gift_purchase->total_amount = $orderTotalAmount;
        $db->commit();
        if($gift_purchase->save())
        {
          $url = $this->view->url(array('module'=>'egifts','controller'=>'payment','action'=>'index','giftpurchase_id'=>$gift_purchase->giftpurchase_id),'default',false);
          echo json_encode(array('url'=>$url,'status'=>1));die;
          exit();
        } 
		  } catch(Exception $e) {
        echo json_encode(array('status'=>0));die;
        throw $e;
		  }
	  }
    echo json_encode(array('status'=>0));die;
    exit();
  }

  public function removegiftAction()
  {
	  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && ((isset($_POST['gift_id']) && !empty($_POST['gift_id']))))
	  {
		  $gift = Engine_Api::_()->getItem('egifts_giftorder',$_POST['gift_id']);
		  $gift->status = 0;
		  $gift->save();
		  echo 1;
		  exit();
	  }
	  exit();
  }
  public function browseAction() {
    $this->_helper->content->setEnabled();
  }
  public function myGiftsAction() {
    $this->_helper->content->setEnabled();
  }
  public function getUserAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $text = $this->_getParam('text', null);
    $userTable = Engine_Api::_()->getItemTable('user');
    $selectUser = $userTable->select()->where('displayname  LIKE ? ', '%' .$text. '%')
    ->where('user_id != ?',$viewer->getIdentity());
    $members = $userTable->fetchAll($selectUser);
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->_forward('notfound', 'error', 'core');
    foreach ($members as $member) {
      $member_icon_photo = $this->view->htmlLink($member->getHref(), $this->view->itemPhoto($member, 'thumb.icon'), array('title' => $member->getTitle(), 'target' => '_parent'));
      $sesdata[] = array(
      'id' => $member->user_id,
      'label' => $member->getTitle(),
      'image' => $member_icon_photo,
      'photo' => $this->view->itemPhoto($member, 'thumb.icon'),
      'title'=>$this->view->htmlLink($member->getHref(), $member->getTitle(), array('title' => $member->getTitle(), 'target' => '_parent'))
      );
    }
    return $this->_helper->json($sesdata);
  }
}
