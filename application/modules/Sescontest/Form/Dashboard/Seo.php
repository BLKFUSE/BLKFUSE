<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Seo.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Dashboard_Seo extends Engine_Form {
	 public function init() {
			$this->setTitle('Add SEO')
					//->setAttrib('id', 'sescontest_ajax_form_submit')
					->setMethod("POST")
					->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));		
		// Contest Contact Name
    $this->addElement('Text', 'seo_title', array(
        'label' => 'Contest SEO Title',
        'allowEmpty' => true,
        'required' => false,
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', false, array(1, 64)),
        ),
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
    ));
		// Contest Contact Email
    $this->addElement('Text', 'seo_keywords', array(
				'description'=>'Enter list of keywords seperated by a comma (,)',
        'label' => 'Contest SEO Keywords',
    ));
		// Contest Contact Phone
    $this->addElement('Textarea', 'seo_description', array(
        'label' => 'Contest SEO Description',
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
