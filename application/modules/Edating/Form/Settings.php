<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Settings.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_Settings extends Engine_Form {

	public function init() {
	
		$this->setTitle(Zend_Registry::get('Zend_Translate')->_("Your Dating Settings"))
        ->setMethod('post')
        ->setAttrib('class', 'global_form');

		$this->addElement("Textarea", 'description', array(
			'label' => Zend_Registry::get('Zend_Translate')->_("Description"),
			'placeholder' => 'Example: I am interesting in girl age 27-30.',
			'required' => false,
			'allowEmpty' => true,
		));
		
		$this->addElement("Checkbox", 'is_search', array(
			'label' => 'Visible your profile in dating search results?',
			'required' => false,
		));

		$this->addElement('Button', 'submit', array(
			'label' => Zend_Registry::get('Zend_Translate')->_("Save"),
			'type' => 'submit',
			'ignore' => true,
			'decorators' => array('ViewHelper')
		));
		$this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
	}
}
