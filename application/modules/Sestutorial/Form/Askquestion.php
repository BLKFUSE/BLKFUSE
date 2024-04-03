<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Askquestion.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Form_Askquestion extends Engine_Form {

  public function init() {
  
    $this->setTitle('Request for a Tutorial');
    $this->setDescription("If you still does not get answer of your query, please feel free to ask a question from our administrators. We will get back to you at our earliest.");
    $this->setAttrib('class', 'global_form')->setAttrib('id', 'sestutorial_ask_question');
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    
    //Category
    $categories = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getCategoriesAssoc();
    if (engine_count($categories) > 0) {
      $required = true;
      $allowEmpty = false;
      $categories = array('' => 'Choose Category') + $categories;
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories,
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'onchange' => "showSubCategory(this.value);",
      ));
      //Add Element: 2nd-level Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => array('0' => ''),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);"
      ));
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array('0' => ''),
      ));
    }
    
    if (empty($viewer_id)) {
      $this->addElement('Text', 'name', array(
        'label' => 'Name',
				'placeholder' => 'Name',
        'allowEmpty' => false,
        'required' => true,
      ));
      $this->addElement('Text', 'email', array(
        'label' => 'Email',
				'placeholder' => 'Email',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
            array('NotEmpty', true),
            array('EmailAddress', true))
      ));
      $this->email->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);
    }
    $this->addElement('Textarea', 'description', array(
      'label' => 'Question Description',
			'placeholder' => 'Question',
      'allowEmpty' => false,
      'required' => true,
    ));
    if (empty($viewer_id)) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions(array()));
    }
    $this->addElement('Button', 'submit', array(
      'label' => 'Submit Request',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick' => 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
  }
}
