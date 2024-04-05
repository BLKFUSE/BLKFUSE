<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescredit_IndexController extends Core_Controller_Action_Standard {

  public function manageAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;
    // Render
    $this->_helper->content->setEnabled();
  }

  public function transactionAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Render
    $this->_helper->content->setEnabled();
  }

  public function earnCreditAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Render
    $this->_helper->content->setEnabled();
  }

  public function helpAction() {
    // Render
    $this->_helper->content->setEnabled();
  }

  public function badgesAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Render
    $this->_helper->content->setEnabled();
  }

  public function leaderboardAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Render
    $this->_helper->content->setEnabled();
  }

  public function inviteAction() {

    //Take Reference From SE Invite module
    $settings = Engine_Api::_()->getApi('settings', 'core');

    // Check if admins only
    if ($settings->getSetting('user.signup.inviteonly') == 1) {
      if (!$this->_helper->requireAdmin()->isValid()) {
        return;
      }
    }

    // Check for users only
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }

    $enableSignupReferral = $settings->getSetting('sescredit.affiliateforsingup', 1);
    if (!$enableSignupReferral) {
      return;
    }

    // Make form
    $this->view->form = $form = new Sescredit_Form_Invite();

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Process
    $values = $form->getValues();
    $viewer = Engine_Api::_()->user()->getViewer();
    $inviteTable = Engine_Api::_()->getDbtable('invites', 'invite');
    $db = $inviteTable->getAdapter();
    $db->beginTransaction();
    try {
      $emailsSent = Engine_Api::_()->getDbtable('invites', 'sescredit')->sendInvites($viewer, $values['recipients'], @$values['message'], $values['friendship']);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      if (APPLICATION_ENV == 'development') {
        throw $e;
      }
    }
    //$this->view->alreadyMembers = $alreadyMembers;
    $this->view->emails_sent = $emailsSent;

    return $this->render('sent');
  }

  public function signupAction() {
    // Psh, you're already signed up
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    if ($viewer && $viewerId) {
      return $this->_helper->redirector->gotoRoute(array(), 'default', true);
    }
    $affiliateCode = $this->_getParam('affiliate');
    if (!empty($affiliateCode)) {
      $affiliateTable = Engine_Api::_()->getDbTable('affiliates', 'sescredit');
      $userId = $affiliateTable->select()
              ->from($affiliateTable->info('name'), 'user_id')
              ->where('affiliate =?', $affiliateCode)
              ->query()
              ->fetchColumn();
      if ($userId) {
        $session = new Zend_Session_Namespace('sescredit_affiliate_signup');
        $session->user_id = $userId;
      }
    }
    // Get invite params
    $session = new Zend_Session_Namespace('invite');
    $session->invite_code = $this->_getParam('code');
    $session->invite_email = $this->_getParam('email');

    // Check code now if set
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if ($settings->getSetting('user.signup.inviteonly') > 0) {
      // Tsk tsk no code
      if (empty($session->invite_code)) {
        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
      }

      // Check code
      $inviteTable = Engine_Api::_()->getDbtable('invites', 'invite');
      $inviteSelect = $inviteTable->select()
              ->where('code = ?', $session->invite_code);

      // Check email
      if ($settings->getSetting('user.signup.checkemail')) {
        // Tsk tsk no email
        if (empty($session->invite_email)) {
          return $this->_helper->redirector->gotoRoute(array(), 'default', true);
        }
        $inviteSelect
                ->where('recipient = ?', $session->invite_email);
      }

      $inviteRow = $inviteTable->fetchRow($inviteSelect);

      // No invite or already signed up
      if (!$inviteRow || $inviteRow->new_user_id) {
        return $this->_helper->redirector->gotoRoute(array(), 'default', true);
      }
    }

    return $this->_helper->redirector->gotoRoute(array(), 'user_signup', true);
  }

  public function showDetailAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;
    $this->view->creditDetail = Engine_Api::_()->getItem('sescredit_credit', $this->_getParam('id'));
  }

  public function showMemberLevelAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;
    $this->view->levelInfo = Engine_Api::_()->getDbTable('levelpoints', 'sescredit')->getMemberLevel();
    if (!$this->getRequest()->isPost())
      return;
    $viewer = Engine_Api::_()->user()->getViewer();
    $upgradeUserTable = Engine_Api::_()->getDbTable('upgradeusers', 'sescredit');
    $db = $upgradeUserTable->getAdapter();
    $db->beginTransaction();
    try {
      $upgradeUser = $upgradeUserTable->createRow();
      $upgradeUser->owner_id = $viewer->getIdentity();
      $upgradeUser->level_id = $_POST['level'];
      $upgradeUser->save();

      //Start Mail Send Work
      $usersTable = Engine_Api::_()->getDbtable('users', 'user');
      $usersSelect = $usersTable->select()
              ->where('level_id = ?', 1)
              ->where('enabled >= ?', 1);
      $superAdmins = $usersTable->fetchAll($usersSelect);
      foreach ($superAdmins as $superAdmin) {
        $adminEmails[$superAdmin->getTitle()] = $superAdmin->email;
      }
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($adminEmails, 'sescredit_send_upgrade_request', array('new_member_level' => Engine_Api::_()->getItem('authorization_level', $_POST['level'])->title, 'owner_title' => $viewer->getTitle()));
      //End Mail SendWork
      $db->commit();
      echo json_encode(array('status' => 'true'));
      die;
      // Redirect
    } catch (Exception $e) {
      $db->rollBack();
    }
  }
  function applyCreditAction(){
    $credit_value = $this->_getParam('credit_value',0);
    $item_amount = $this->_getParam('item_amount',0);
    $moduleName = $this->_getParam('moduleName','');
    $id = $this->_getParam('id','');
    $item_amount = str_replace(',','',$item_amount);
    $item_id = $this->_getParam('item_id',0);
    $creditCode =  'credit'.'-'.$moduleName.'-'.$id.'-'.$item_id;
    $sessionCredit = new Zend_Session_Namespace($creditCode);
    $session = new Zend_Session_Namespace('sescredit_redeem_purchase');
    $status = 0;
    $purchaseValueOfPoints = 0;
    $purchaseValue = 0;
    if(!empty($credit_value)){
      if($item_amount > 0){
          $response = Engine_Api::_()->sescredit()->validateCreditPurchase($moduleName,$item_amount,$credit_value);
          if($response['status']){
              $sessionCredit->value = $credit_value;
              //get purchase value of redeem points
              $creditvalue = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.creditvalue',0); 
              if($creditvalue){
                  $purchaseValueOfPoints = (1/$creditvalue) * $credit_value;
                  $purchaseValue = $sessionCredit->purchaseValue = $purchaseValueOfPoints;
                  $status = 1;
              }
          }
      }
    } 
    $sessionCredit->item_amount = $item_amount;
    $sessionCredit->credit_value = $credit_value;
    $sessionCredit->credit_amount = round($purchaseValue,2);
    $sessionCredit->total_amount =  round(($item_amount-$purchaseValueOfPoints),2);
    echo json_encode(array('status'=>$status,'message'=>$session->error,'purchaseValue'=>Engine_Api::_()->payment()->getCurrencyPrice(round($purchaseValue,2)),'credit_amount'=>round($purchaseValue,2),'value'=>$sessionCredit->value,'item_amount'=>Engine_Api::_()->payment()->getCurrencyPrice(round($item_amount,2)),'total_amount'=>Engine_Api::_()->payment()->getCurrencyPrice(round(($item_amount-$purchaseValueOfPoints),2))));die;
  }
  


  public function paymentRequestsAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescredit_main', array(), 'sescredit_main_managecashcredit');

    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');

    $this->view->userGateway = Engine_Api::_()->getDbtable('usergateways', 'sescredit')->getUserGateway(array('owner_id' => $viewer->getIdentity()));

    $this->view->thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'sescredit', 'sescredit_threamt');
    
    $userpayrequestsTable = Engine_Api::_()->getDbTable('userpayrequests', 'sescredit');
    
    $this->view->adminTotalCommission = $userpayrequestsTable->adminTotalCommission();
		
    $this->view->isAlreadyRequests = $userpayrequestsTable->getSescreditRequests(array('owner_id' => $viewer->getIdentity(),'isPending'=>true));
    
    $this->view->paymentRequests = $userpayrequestsTable->getSescreditRequests(array('owner_id' => $viewer->getIdentity(),'isPending'=>true));

  }

	public function paymentTransactionAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');
			
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescredit_main', array(), 'sescredit_main_managecashcredit');

		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'sescredit')->getSescreditRequests(array('owner_id' => $viewer->getIdentity(), 'state' => 'complete'));
	}

	public function paymentRequestAction() {
		
		if (!$this->_helper->requireUser()->isValid())
      return;
      
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');

		$thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'sescredit', 'sescredit_threamt');
		
		$defaultCurrency = Engine_Api::_()->payment()->defaultCurrency();

		$this->view->form = $form = new Sescredit_Form_Paymentrequest();
		$value = array();
		
		$credit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'credit', 'type' => 'credit'));
		
		$debit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'deduction', 'type' => 'deduction'));
		
		$cashcredit_byowner_debit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'cashcredit_byowner', 'type' => 'deduction'));
		
		$credit = $credit - ($debit + $cashcredit_byowner_debit);
		
		$creditvalue = $credit / Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.creditvalue', '1000');

		$value['total_amount'] = Engine_Api::_()->payment()->getCurrencyPrice($creditvalue);

		if(($value['total_admin_amount'] == "$0.00"))
			$form->removeElement("total_admin_amount");
		
		//set value to form
		if ($this->_getParam('id', false)) {
			$item = Engine_Api::_()->getItem('sescredit_userpayrequest', $this->_getParam('id'));
			if ($item) {
				$itemValue = $item->toArray();
				//unset($value['requested_amount']);
				$value = array_merge($itemValue, $value);
			} else {
				return $this->_forward('requireauth', 'error', 'core');
			}
		}
		
		if (empty($_POST))
				$form->populate($value);

		if (!$this->getRequest()->isPost())
				return;
				
		if (!$form->isValid($this->getRequest()->getPost()))
				return;

		if (@round($thresholdAmount,2) > @round($creditvalue,2) && empty($_POST)) {
				$this->view->message = 'Remaining amount is less than Threshold amount.';
				$this->view->errorMessage = true;
				return;
		} else if (isset($_POST['requested_amount']) && @round($_POST['requested_amount'],2) > @round($creditvalue,2)) {
				$form->addError('Requested amount must be less than or equal to remaining amount.');
				return;
		} else if (isset($_POST['requested_amount']) && @round($thresholdAmount) > @round($_POST['requested_amount'],2)) {
				$form->addError('Requested amount must be greater than or equal to threshold amount.');
				return;
		}
		
		$tableUserpayrequests = Engine_Api::_()->getDbtable('userpayrequests', 'sescredit');
		
		$db = $tableUserpayrequests->getAdapter();
		$db->beginTransaction();
		try {
				if (isset($itemValue))
					$userpayrequest = $item;
				else
					$userpayrequest = $tableUserpayrequests->createRow();
					
				$userpayrequest->requested_amount = round($_POST['requested_amount'],2);
				$userpayrequest->user_message = $_POST['user_message'];
				$userpayrequest->credit_point = $_POST['requested_amount'] * Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.creditvalue', '1000');
				$userpayrequest->owner_id = $viewer->getIdentity();
				$userpayrequest->creation_date = date('Y-m-d h:i:s');
				$userpayrequest->currency_symbol = $defaultCurrency;

				//Admin commission
				$commissionType = Engine_Api::_()->authorization()->getPermission($viewer,'sescredit','sescredit_admcosn');
				$commissionTypeValue = Engine_Api::_()->authorization()->getPermission($viewer,'sescredit','sescredit_commival');
				
				//percentage wise
				if($commissionType == 1 && $commissionTypeValue > 0) {
					$userpayrequest->total_commission_amount = round(($_POST['requested_amount']/Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.creditvalue', '1000')) * ($commissionTypeValue/100),2);
				} else if($commissionType == 2 && $commissionTypeValue > 0) {
					$userpayrequest->total_commission_amount = $commissionTypeValue;
				}
				$userpayrequest->save();

				//Notification to super admin 
				$owner_admin = Engine_Api::_()->getItem('user', 1);
				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner_admin, $viewer, $viewer, 'sescredit_payrequest', array('requestAmount' => round($_POST['requested_amount'],2)));

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
		
		$this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescredit_main', array(), 'sescredit_main_managecashcredit');

		$gateway_type = $this->view->gateway_type = $this->_getParam('gateway_type', "paypal");
		
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');

		$userGateway = Engine_Api::_()->getDbtable('usergateways', 'sescredit')->getUserGateway(array('gateway_type'=>$gateway_type,'owner_id'=>$viewer->getIdentity()));
		
		$settings = Engine_Api::_()->getApi('settings', 'core');
		
		$userGatewayEnable = $settings->getSetting('payment.userGateway', 'paypal');
		
		$this->view->form = $form = new Sescredit_Form_PayPal();
		
		if($gateway_type == "paypal") {
			$userGatewayEnable = 'paypal';
			$this->view->form = $form = new Sescredit_Form_PayPal();
			$gatewayTitle = 'Paypal';
			$gatewayClass= 'Sescredit_Plugin_Gateway_PayPal';
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
		$userGatewayTable = Engine_Api::_()->getDbtable('usergateways', 'sescredit');
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
				$gatewayObject = Engine_Api::_()->getItem("sescredit_usergateway", $userGateway['usergateway_id']);
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
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');
		
		$paymnetReq = Engine_Api::_()->getItem('sescredit_userpayrequest', $this->getRequest()->getParam('id'));

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
	
    $viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return $this->_forward('requireauth', 'error', 'core');
			
		$this->view->item = $paymnetReq = Engine_Api::_()->getItem('sescredit_userpayrequest', $this->getRequest()->getParam('id'));
		$this->view->viewer = Engine_Api::_()->user()->getViewer();
		if (!$paymnetReq) {
			$this->view->status = false;
			$this->view->error = Zend_Registry::get('Zend_Translate')->_("Payment request doesn't exists or not authorized to delete");
			return;
		}
	}
}
