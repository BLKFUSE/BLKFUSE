<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: TagCloudContest.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_TagCloudContest extends Engine_Form {

  public function init() {

    $headScript = new Zend_View_Helper_HeadScript();
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $script = "var hashSign = '#';";
    $view->headScript()->appendScript($script);
    $this->addElement('Text', "color", array(
        'label' => 'Choose Tag Text Color',
        'value' => '#00f',
        'class' => 'SEScolor',
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
