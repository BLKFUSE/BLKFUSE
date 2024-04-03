<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Searchorder.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjoinfees_Form_Searchorder extends Engine_Form {

  public function init() {

    $this
            ->setMethod('POST')
            ->setAction($_SERVER['REQUEST_URI'])
						 ->setAttribs(array(
                'id' => 'manage_order_search_form',
                'class' => 'global_form_box',
            ));
    $this->addElement('Text', 'order_id', array(
        'label'=>'Order ID',
    ));
		$this->addElement('Text', 'buyer_name', array(
        'label'=>'Buyer Name',
    ));
		$this->addElement('Text', 'email', array(
        'label'=>'Email',
    ));
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
		$this->addElement('Dummy','loading-img-sescontest', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" id="sescontest-search-order-img" alt="Loading" />',
   ));
  }

}
