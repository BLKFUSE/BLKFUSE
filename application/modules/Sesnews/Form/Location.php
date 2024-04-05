<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Location.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Location extends Engine_Form {

  public function init() {

    $this->setMethod('POST')
	->setAction($_SERVER['REQUEST_URI'])
	->setAttrib('class', 'global_form_popup')
	->setAttrib('id', 'location-form');

    $this->addElement('Text', 'location', array(
        'label' => 'Location',
        'id' => 'locationSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
    $this->addElement('Text', 'lat', array(
        'label' => 'Lat',
        'id' => 'latSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
    $this->addElement('dummy', 'map-canvas', array());
    $this->addElement('dummy', 'ses_location', array('content'));

    $this->addElement('Text', 'lng', array(
        'label' => 'Lng',
        'id' => 'lngSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
    $this->addElement('Button', 'execute', array(
        'label' => 'Save Changes',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
        'type' => 'submit'
    ));

    $this->addElement('Cancel', 'cancel', array(
        'prependText' => '<span id="or_content"> or </span>',
        'label' => 'cancel',
        'link' => true,
        'href' => '',
        'onclick' => 'parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        ),
    ));

    $this->addDisplayGroup(array(
        'execute',
        'cancel'
            ), 'buttons');
  }

}
