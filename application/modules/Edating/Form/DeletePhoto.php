<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: DeletePhoto.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_DeletePhoto extends Engine_Form{

	public function init() {
	
		$this->setTitle('Delete Photo')
        ->setDescription(Zend_Registry::get('Zend_Translate')->_("Are you sure to delete this photo?"))
        ->setAttrib('class', 'global_form_popup');
		
		$this->addElement('Button', 'submit', array(
			'type' => 'submit',
			'label' => 'Delete Photo',
			'decorators' => array('ViewHelper')
		));

		$this->addElement('Cancel', 'cancel', array(
			'label' => "Cancel",
			'link' => 'true',
			'prependText' => Zend_Registry::get('Zend_Translate')->_(' or '),
			'href' => '',
			'onclick' => 'parent.Smoothbox.close();',
			'decorators' => array('ViewHelper')
		));

		$this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
	}
}
