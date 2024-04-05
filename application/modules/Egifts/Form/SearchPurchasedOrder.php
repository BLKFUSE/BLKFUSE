<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Searchorder.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Form_SearchPurchasedOrder extends Engine_Form {

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

		//date
		$subform = new Engine_Form(array(
			'description' => 'Order Date',
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

		$this->addElement('Button', 'search', array(
      'label' => 'Search',
      'type' => 'submit',
    ));
		$this->addElement('Dummy','loading-img-egifts', array(
			'content' => '<img src="application/modules/Core/externals/images/loading.gif" id="egifts-search-order-img" alt="Loading" />',
   ));
  }
}
