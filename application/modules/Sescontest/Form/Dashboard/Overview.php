<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Overview.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Dashboard_Overview extends Engine_Form {

  public function init() {
    $this->setTitle('Change Contest Overview')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setMethod('POST');

    //UPLOAD PHOTO URL
    $editorOptions = array(
      'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );

    $this->addElement('TinyMce', 'overview', array(
        'label' => 'Detailed Overview',
        'description' => 'Enter detailed overview about the Contest.',
        'editorOptions' => $editorOptions,
    ));

    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
		$request = Zend_Controller_Front::getInstance()->getRequest();
    $controllerName = $request->getControllerName();
		if($controllerName != 'dashboard'){
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

}
