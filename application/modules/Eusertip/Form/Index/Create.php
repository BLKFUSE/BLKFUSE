<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Create.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_Form_Index_Create extends Engine_Form {

  public function init() {
  
    $this->setTitle('Create Tip');
        //->setDescription('Please note that payment parameters (Price, Recurrence, Duration) cannot be edited after creation. If you wish to change these, you will have to create a new tip and disable the current one.');

    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        'StringTrim',
      ),
    ));

//     $this->addElement('Textarea', 'description', array(
//       'label' => 'Description',
//       'validators' => array(
//         array('StringLength', true, array(0, 250)),
//       )
//     ));

    // Element: price
    $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
    $this->addElement('Text', 'price', array(
      'label' => 'Price',
      'description' => 'The amount to charge the member. This will be charged once for one-time tips, and each billing cycle for recurring tips.',
      'required' => true,
      'allowEmpty' => false,
      'validators' => array(
        new Engine_Validate_AtLeast(1),
      ),
    ));

    // Element: recurrence
//     $this->addElement('Duration', 'recurrence', array(
//       'label' => 'Billing Cycle',
//       'description' => 'How often should members in this tip be billed?',
//       'required' => true,
//       'allowEmpty' => false,
//       //'validators' => array(
//         //array('Int', true),
//         //array('GreaterThan', true, array(0)),
//       //),
//       'value' => array(1, 'month'),
//     ));
    //unset($this->getElement('recurrence')->options['day']);
    //$this->getElement('recurrence')->options['forever'] = 'One-time';

    // Element: duration
//     $this->addElement('Duration', 'duration', array(
//       'label' => 'Billing Duration',
//       'description' => 'When should this tip expire? For one-time ' .
//         'tips, the tip will expire after the period of time set here. For ' .
//         'recurring tips, the user will be billed at the above billing cycle ' .
//         'for the period of time specified here.',
//       'required' => true,
//       'allowEmpty' => false,
//       //'validators' => array(
//       //  array('Int', true),
//       //  array('GreaterThan', true, array(0)),
//       //),
//       'value' => array('0', 'forever'),
//     ));
    //unset($this->getElement('duration')->options['day']);


    // Element: enabled
    $this->addElement('Radio', 'enabled', array(
      'label' => 'Enabled?',
      'description' => 'Can members choose this tip? Please note that disabling this tip will <a href="https://en.wikipedia.org/wiki/Grandfather_clause" target="_blank">grandfather</a> in existing tip members until they pick a new tip.',
      'multiOptions' => array(
        '1' => 'Yes, members may select this tip.',
        '0' => 'No, members may not select this tip.',
      ),
      'value' => 1,
    ));
    $this->getElement('enabled')->getDecorator('description')->setOption('escape', false);

    // Element: execute
    $this->addElement('Button', 'execute', array(
      'label' => 'Create Tip',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
    ));

    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'prependText' => ' or ',
      'ignore' => true,
      'link' => true,
      'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage-tips', 'tip_id' => null)),
      'decorators' => array('ViewHelper'),
    ));

    // DisplayGroup: buttons
    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      )
    ));
  }
}
