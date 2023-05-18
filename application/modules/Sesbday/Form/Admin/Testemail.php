<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Testemail.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbday_Form_Admin_Testemail extends Engine_Form {

  public function init() {
  
    $this->setTitle("Send Test Birthday Email")
            ->setDescription("")
            ->setMethod('post')
            ->setAttrib('class', 'global_form_box')
            ->setAttrib('id', 'send_test_email');

    $this->addElement('Text', 'email', array(
        'label' => 'Email',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
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
