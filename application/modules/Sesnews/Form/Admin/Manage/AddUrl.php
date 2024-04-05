<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AddUrl.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesnews_Form_Admin_Manage_AddUrl extends Engine_Form {

  public function init() {

    $type = Zend_Controller_Front::getInstance()->getRequest()->getParam('type', '');

    $this->setMethod('post');

    $this->addElement('Text', 'name', array(
      'allowEmpty' => false,
      'required' => true,
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Add',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }
}
