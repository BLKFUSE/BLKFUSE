<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Rules.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Dashboard_Rules extends Engine_Form {

  public function init() {
    $this->setTitle('Rules')
            ->setDescription('Below, you can enter rules for your contest.')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setMethod('POST');
    $settings = Engine_Api::_()->getApi('settings', 'core');

    //UPLOAD PHOTO URL
    $editorOptions = array(
      'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );
    
    if ($settings->getSetting('sescontest.rules.editor', 1)) {
      $this->addElement('TinyMce', 'rules', array(
          'label' => 'Rules',
          'allowEmpty' => false,
          'required' => true,
          'editorOptions' => $editorOptions,
      ));
    } else {
      $this->addElement('Textarea', 'rules', array(
          'label' => 'Rules',
          'allowEmpty' => false,
          'required' => true
      ));
    }


    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
  }

}
