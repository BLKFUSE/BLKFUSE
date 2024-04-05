<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Overview.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Chanel_Overview extends Engine_Form {

  public function init() {
    $this->setTitle('Change Channel Overview')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setMethod('POST');
    ;
		//UPLOAD PHOTO URL
    $editorOptions = array(
      'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );
    $this->addElement('TinyMce', 'overview', array(
        'label' => 'Detailed Overview',
        'description' => 'Enter detailed overview about the Channel.',
        'editorOptions' => $editorOptions,
    ));

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => '',
        'onclick' => 'parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');
  }

}
