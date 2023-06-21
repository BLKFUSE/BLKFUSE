<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: FilterOrders.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Form_Admin_FilterOrders extends Engine_Form {

  public function init() {
    $this->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('Form')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
            ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));
    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');
		$this->addElement('Text', 'owner_name', array(
        'label' => 'Entry Owner Name',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    $subformAmount = new Engine_Form(array(
        'description' => 'Order Amount',
        'elementsBelongTo'=> 'amount',
        'decorators' => array(
            'FormElements',
            array('Description', array('placement' => 'PREPEND', 'tag' => 'label', 'class' => 'form-label')),
            array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'id' =>'integer-wrapper'))
        )
    ));
    $subformAmount->addElement('Text', 'order_min', array('placeholder'=>'min'));
    $subformAmount->addElement('Text', 'order_max', array('placeholder'=>'max'));
    $this->addSubForm($subformAmount, 'amount');
    
//     $this->addElement('Select', 'status', array(
//         'label' => 'Status',
//         'multiOptions' => array(
//             ''=>'',
//           'approval_pending'=>'Approval Pending',
//             'pending'=>'Payment Pending',
//             'prcessing'=>'Processing',
//             'fraud'=>'Fraud',
//             'complete'=>'Completed',
//             'cancelled'=>'Cancelled',
// 
//         ),
//         'decorators' => array(
//             'ViewHelper',
//             array('Label', array('tag' => null, 'placement' => 'PREPEND')),
//             array('HtmlTag', array('tag' => 'div'))
//         ),
//     ));

    $this->addElement('Button', 'search', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
    ));

    $this->addElement('Hidden', 'order', array(
        'order' => 10004,
    ));
    $this->addElement('Hidden', 'order_direction', array(
        'order' => 10002,
    ));

    $this->addElement('Hidden', 'order_id', array(
        'order' => 10003,
    ));

    //Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }

}
