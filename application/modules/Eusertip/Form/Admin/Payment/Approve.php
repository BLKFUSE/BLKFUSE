<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Approve.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Eusertip_Form_Admin_Payment_Approve extends Engine_Form {

  protected $_userId;
  
  public function getUserId() {
    return $this->_userId;
  }
  
  public function setUserId($user_id) {
    $this->_userId = $user_id;
    return $this;
  }
  
  public function init() {
  
    //get current logged in user
    $user = Engine_Api::_()->user()->getViewer();
    $this->setTitle('Approve Payment Request')
            ->setAttrib('id','eusertip_ppayment_request')
						->setAttrib('description','Below, enter the payment to be release in response to this payment request.')
            ->setMethod("POST");
		
		$this->addElement('Text', 'total_amount', array(
          'label' => 'Total Amount',
					'readonly'=>'readonly',
    ));
		$this->addElement('Text', 'total_commission_amount', array(
          'label' => 'Total Commission Amount',
					'readonly'=>'readonly',
    ));
		$this->addElement('Text', 'remaining_amount', array(
          'label' => 'Total Remaining Amount',
					'readonly'=>'readonly',
    ));
		$this->addElement('Text', 'requested_amount', array(
          'label' => 'Requested Amount',
					'readonly'=>'readonly',
    ));
		$this->addElement('Textarea', 'user_message', array(
          'label' => 'Requested Message',
					'readonly'=>'readonly',
    ));
		$this->addElement('Text', 'release_amount', array(
          'label' => 'Amount to Release',
					'allowEmpty' => false,
					'required' => true,
					'validators' => array(
								array('GreaterThan', true, array(0)),
						)
    ));
		$this->addElement('Textarea', 'admin_message', array(
          'label' => 'Response Message',
    ));
    $givenSymbol = Engine_Api::_()->eusertip()->getCurrentCurrency();
    $gateways = Engine_Api::_()->getDbtable('usergateways', 'eusertip')->getUserGateway(array("enabled"=>true,'user_id'=>$this->getUserId(),'fetchAll'=>true));
    foreach($gateways as $gateway) {
      $gatewayObject = $gateway->getGateway();
      $supportedCurrencies = $gatewayObject->getSupportedCurrencies();
      if(!engine_in_array($givenSymbol,$supportedCurrencies))
          continue;
        $options[$gateway->usergateway_id] = $gateway->title;
    }
    if(!empty($options) && engine_count($options) > 0) {
      $this->addElement('Radio', 'gateway_id', array(
          'label' => 'Gateway Type',
          'required' => true,
          'multiOptions' => $options,
      ));
    }
		$this->addElement('Button', 'submit', array(
        'label' => 'Approve',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
				'onclick'=>'parent.Smoothbox.close();',
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
 }
}
