<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Banner.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_Form_Admin_Banner extends Engine_Form {

  public function init() {

    $this->setTitle('Create New Banner');
    $this->setMethod('post');

    $this->addElement('Text', 'banner_name', array(
        'label' => 'Enter the banner name.',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Text', 'width', array(
        'label' => 'Enter the width of banner (in px).',
        'allowEmpty' => false,
        'required' => true,
    ));

    $this->addElement('Text', 'height', array(
        'label' => 'Enter the height of banner (in px).',
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
