<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Tagcloudtutorial.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

 
class Sestutorial_Form_Admin_Tagcloudtutorial extends Engine_Form {

  public function init() {
  
    $headScript = new Zend_View_Helper_HeadScript();
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jquery.min.js');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $this->addElement('Text', "color", array(
      'label' => "Choose the Tag text color for Cloud view.",
      'class' => 'SEScolor',
      'value' => '#00f',
    ));
    
    $this->getElement('color')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    $this->addElement('Radio', "type", array(
      'label' => "Choose View Type.",
			'multiOptions' => array(
				'tab' => 'Tab View',
				'cloud' => 'Cloud View',
			),
			'value' => 'tab',
    ));
    
    $this->addElement('Text', "text_height", array(
      'label' => "Choose height of tag text in cloud view.",
      'value' => '15',
      'validators' => array(
        array('Int', true),
        array('GreaterThan', true, array(0)),
      )
    ));
		
    $this->addElement('Text', "height", array(
      'label' => "Choose height of tag container in cloud view (in pixels).",
      'value' => '300',
      'validators' => array(
        array('Int', true),
        array('GreaterThan', true, array(0)),
      )
    ));
	
    $this->addElement('Text', "itemCountPerPage", array(
      'label' => "Count (number of tags to show).",
      'value' => '50',
      'validators' => array(
        array('Int', true),
        array('GreaterThan', true, array(0)),
      )
    ));
  }
}