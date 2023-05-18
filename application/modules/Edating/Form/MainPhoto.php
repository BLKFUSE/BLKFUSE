<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: MainPhoto.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_MainPhoto extends Engine_Form {

	public function init() {
	
		$this->setTitle('Make Main Photo')
			->setDescription(Zend_Registry::get('Zend_Translate')->_("Are you sure to make this as main photo?"))
			->setAttrib('class', 'global_form_popup');
		
		$this->addElement('Button', 'submit', array(
			'type' => 'submit',
			'label' => Zend_Registry::get('Zend_Translate')->_("Yes"),
			'decorators' => array('ViewHelper')
		));

		$this->addElement('Cancel', 'cancel', array(
			'label' =>Zend_Registry::get('Zend_Translate')->_("No"),
			'link' => 'true',
			'prependText' => Zend_Registry::get('Zend_Translate')->_(' or '),
			'href' => '',
			'onclick' => 'parent.Smoothbox.close();',
			'decorators' => array('ViewHelper')
		));

		$this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
	}
}
