<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Searchmyorder.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Form_Searchmyorder extends Engine_Form {

  public function init() {

    $this->setMethod('POST')
      ->setAction($_SERVER['REQUEST_URI'])
      ->setAttribs(array(
        'id' => 'manage_order_search_form',
        'class' => 'global_form_box',
    ));
    $this->addElement('Text', 'order_id', array(
        'label'=>'Order ID',
    ));
		$this->addElement('Text', 'owner_name', array(
        'label'=>'Package Owner Name',
    ));
// 		$this->addElement('Text', 'email', array(
//         'label'=>'Email',
//     ));
		//date
		$subform = new Engine_Form(array(
			'description' => 'Order Date Ex (yyyy-mm-dd)',
			'elementsBelongTo'=> 'date',
			'decorators' => array(
				'FormElements',
				array('Description', array('placement' => 'PREPEND', 'tag' => 'label', 'class' => 'form-label')),
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' =>'integer-wrapper'))
			)
		));
		
		$subform->addElement('Text', 'date_to', array('placeholder'=>'from'));
    $subform->addElement('Text', 'date_from', array('placeholder'=>'to'));
		$this->addSubForm($subform, 'date');
		
		//order total
		$orderform = new Engine_Form(array(
			'description' => 'Order Total',
			'elementsBelongTo'=> 'order',
			'decorators' => array(
				'FormElements',
				array('Description', array('placement' => 'PREPEND', 'tag' => 'label', 'class' => 'form-label')),
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' =>'integer-wrapper'))
			)
		));
		$orderform->addElement('Text', 'order_min', array('placeholder'=>'min'));
		$orderform->addElement('Text', 'order_max', array('placeholder'=>'max'));
		$this->addSubForm($orderform, 'order');
		
		//commission
		$subform = new Engine_Form(array(
			'description' => 'Commission',
			'elementsBelongTo'=> 'commision',
			'decorators' => array(
				'FormElements',
				array('Description', array('placement' => 'PREPEND', 'tag' => 'label', 'class' => 'form-label')),
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' =>'integer-wrapper'))
			)
		));
		$subform->addElement('Text', 'commision_min', array('placeholder'=>'min'));
		$subform->addElement('Text', 'commision_max', array('placeholder'=>'max'));
		$this->addSubForm($subform, 'commision');
    
    $gatewayTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    $gatewaySelect = $gatewayTable->select()
      ->where('enabled = ?', 1)
      ;
    $gateways = $gatewayTable->fetchAll($gatewaySelect);
    $gateway = array(''=>'');
    
    foreach($gateways as $gt){
      if($gt->title == "PayPal")  
        $gateway['Paypal'] = "Paypal";
      else if ($gt->title == "2Checkout")
        $gateway['2Checkout'] = "2Checkout";
    }
    
    
    
		$this->addElement('Select', 'gateway', array(
        'label'=>'Gateway',
				'MultiOptions'=>$gateway,
    ));
		$this->addElement('Button', 'search', array(
      'label' => 'Search',
      'type' => 'submit',
    ));
		$this->addElement('Dummy','loading-img-epaidcontent', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" id="epaidcontent-search-order-img" alt="Loading" />',
   ));
  }

}
