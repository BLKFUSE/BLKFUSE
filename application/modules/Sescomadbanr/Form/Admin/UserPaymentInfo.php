<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: UserPaymentInfo.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_Form_Admin_UserPaymentInfo extends Engine_Form {

  public function init() {

    $this->setTitle('Create New Payment Information')->setDescription('Here, you can enter user information of user that you want to receive direct payment.');
    $this->setMethod('post');

    $this->addElement('Text', 'member_name', array(
        'label' => 'Enter the name of member.',
        'allowEmpty' => false,
        'required' => true,
    ));
    $this->addElement('Text', 'email', array(
        'label' => 'Enter the email of member.',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Text', 'price', array(
        'label' => 'Enter the price (in USD) that you want to get from user by using direct link.',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Text', 'transaction_id', array(
        'label' => 'Enter transaction id after making payment by member.',
        'allowEmpty' => true,
        'required' => false,
    ));

    $this->addElement('Text', 'sescommunityad_id', array(
        'label' => 'Enter Comunity Ad Id that you have created for this member.',
        'allowEmpty' => true,
        'required' => false,
    ));

    $this->addElement('Select', 'status', array(
        'label' => 'Choose payment status.',
        'multiOptions' => array(
            '1' => 'In Progress',
            '2' => 'Completed',
        ),
        'allowEmpty' => false,
        'required' => true,
    ));

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Create',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => '',
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }
}
