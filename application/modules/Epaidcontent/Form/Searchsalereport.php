<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Searchsalesreport.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Epaidcontent_Form_Searchsalereport extends Engine_Form {

  public function init() {
  
    $user = Engine_Api::_()->user()->getViewer();
    $this->setTitle('')
            ->setAttrib('id', 'epaidcontent_search_form_sale_report')
						->setAttrib('class', 'global_form_box')
            ->setMethod("GET")
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
		$this->addElement('Select', 'type', array(
          'label' => 'Duration',
          'multiOptions' => array('month'=>'Monthly','day'=>'Daily'),
					'value'=>'day',
    ));
		$this->addElement('Hidden', 'csv', array(
          'value'=>'',
					'order'=>10000
    ));
		$this->addElement('Hidden', 'excel', array(
          'value'=>'',
					'order'=>10001
    ));
		$this->addElement('Text', 'startdate', array(
        'label'=>'Start Date',
				'style'=>'width:70px;'
    ));
		$this->addElement('Text', 'enddate', array(
        'label'=>'End Date',
				'style'=>'width:70px;'
    ));
		// Buttons
    $this->addElement('Button', 'submit_form_sales_report', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true
    ));
 }
}
