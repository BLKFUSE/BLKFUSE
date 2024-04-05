<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Creategifts.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Form_Admin_Creategift extends Engine_Form {
    public function init() {
      $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
        $settings = Engine_Api::_()->getApi('settings', 'core');
        $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
	    $this->setTitle('Create Gift')
		    ->setDescription('Here you can create gifts.')
	      ->setAttrib('class', 'global_form_popup');
      
      if($egifts_user) {
	    $this->addElement('text','title',array(
		    'label'=>'Title',
		    'placeholder'=>'Title',
		    'required'=>true,
		    'allowEmpty' => false,
	    ));

	    $this->addElement('text','price',array(
		    'label'=>'Price ('.$currency.')',
		    'placeholder'=>'Price',
		    'onkeypress'=>'return allowOnlyNumbers(event);',
		    'required'=>true,
		    'allowEmpty' => false,
	    ));

	   	$this->addElement('textarea','description',array(
		    'label'=>'Description',
		    'description'=>'Enter the description for this gift.',
		    'placeholder'=>'Description',
	    ));


	    $this->addElement('file','file',array(
		    'label'=>'Icon / Image',
		    'description'=>'Upload Icon / Image for the gift. (Recommended size: 400*400 pixels)',
		    'required'=>true,
		    'accept'=>'image/*',
		    'allowEmpty' => false,
	    ));
	    }
	    $this->addElement('Button', 'execute', array(
		    'type' => 'submit',
		    'label' => 'Submit',
		    'decorators' => array(
			    'ViewHelper',
		    ),
	    ));
	    $this->addElement('Cancel', 'cancel', array(
		    'link' => true,
		    'prependText' => ' or ',
		    'label' => 'cancel',
		    'href' => 'javascript:parent.Smoothbox.close();',
		    'decorators' => array(
			    'ViewHelper',
		    ),
	    ));
	    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons');
    }
}

?>
