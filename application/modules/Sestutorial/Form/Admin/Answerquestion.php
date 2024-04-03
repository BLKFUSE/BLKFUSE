<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Answerquestion.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Form_Admin_Answerquestion extends Engine_Form {

  public function init() {
  
		$this->setTitle('Answer This Question')
        ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
		
		$askquestion_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('askquestion_id');
		$askquestion = Engine_Api::_()->getItem('sestutorial_askquestion', $askquestion_id);

		$this->addElement('dummy', 'description', array(
			'label' => 'Question Description',
			'description' => $askquestion->description,
		));

    if(empty($askquestion->reply)) { 
      $this->addElement('Textarea', 'body', array(
        'label' => 'Enter the Answer',
        'required' => true,
        'allowEmpty' => false,
        'attribs' => array('rows'=>20, 'cols'=>50),
        'filters' => array(
          new Engine_Filter_Html(),
          new Engine_Filter_Censor(),
        ),
      ));
		} else {
      $this->addElement('dummy', 'description1', array(
        'label' => 'You Answered:',
        'description' => $askquestion->answered,
      ));
		}
    
    if(empty($askquestion->reply)) { 
    $this->addElement('Button', 'submit', array(
      'label' => 'Answer',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array(
        'ViewHelper',
      ),
    ));
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
			'onclick'=> 'javascript:parent.Smoothbox.close()',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
      'decorators' => array(
        'FormElements',
        'DivDivDivWrapper',
      ),
    ));
    } else {
      $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'onclick'=> 'javascript:parent.Smoothbox.close()',
        'link' => true,
        'decorators' => array(
          'ViewHelper',
        ),
      ));
    }
  }
}
