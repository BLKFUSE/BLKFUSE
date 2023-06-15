<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Contactinformation.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Dashboard_Contactinformation extends Engine_Form {
	 public function init() {
			$this->setTitle('Information')
                    ->setDescription('Here, you can add your Contact Details which will be visible to the users at your Contest View Page in "Contact Info Section".')
					->setAttrib('id', 'sescontest_ajax_form_submit')
					->setMethod("POST")
					->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));		
		// Name
    $this->addElement('Text', 'contest_contact_name', array(
        'label' => 'Name',
        'allowEmpty' => false,
        'required' => true,
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', false, array(1, 64)),
        ),
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
    ));
		// Email
    $this->addElement('Text', 'contest_contact_email', array(
        'label' => 'Email',
    ));
		// Phone
    $this->addElement('Text', 'contest_contact_phone', array(
        'label' => 'Phone',
    ));
		// Facebook
    $this->addElement('Text', 'contest_contact_facebook', array(
        'label' => 'Facebook URL',
    ));
		// Linkedin
    $this->addElement('Text', 'contest_contact_linkedin', array(
        'label' => 'Linkedin URL',
    ));
			// twitter
    $this->addElement('Text', 'contest_contact_twitter', array(
        'label' => 'Twitter URL',
    ));
			// Website
    $this->addElement('Text', 'contest_contact_website', array(
        'label' => 'Website URL',
    ));			 
		 $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
	 }
}