<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Location.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesalbum_Form_Photo_Location extends Engine_Form {

  public function init() {
    $this
            ->setTitle('Edit Location')
            ->setMethod('POST')
            ->setAction($_SERVER['REQUEST_URI'])
            ->setAttrib('class', 'global_form_popup')
						->setAttrib('id', 'location-form')
    ;
    if((Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
            $this->addElement('Text', 'location', array(
                'label' => 'Location',
        				'id' =>'locationSesList',
                'filters' => array(
                    new Engine_Filter_Censor(),
                    new Engine_Filter_HtmlSpecialChars(),
                ),
            ));
        		$this->addElement('Text', 'lat', array(
                'label' => 'Lat',
        				'id' =>'latSesList',
                'filters' => array(
                    new Engine_Filter_Censor(),
                    new Engine_Filter_HtmlSpecialChars(),
                ),
            ));
        		$this->addElement('dummy', 'map-canvas', array());
        		$this->addElement('dummy', 'ses_location', array('content'));
        		
        		$this->addElement('Text', 'lng', array(
                'label' => 'Lng',
        				'id' =>'lngSesList',
                'filters' => array(
                    new Engine_Filter_Censor(),
                    new Engine_Filter_HtmlSpecialChars(),
                ),
            ));
        }
    else if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
      $this->addElement('Text', 'location', array(
                'label' => 'Location',
                'filters' => array(
                    new Engine_Filter_Censor(),
                    new Engine_Filter_HtmlSpecialChars(),
                ),
            ));
        $optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion',''));
        if(!empty($optionsenableglotion)) {
      if(engine_in_array('country', $optionsenableglotion)) {
       $this->addElement('Text', 'country', array(
          'label' => 'Country',
          'maxlength' => '255',
          'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '63')),
          )
        ));
     }
     if(engine_in_array('state', $optionsenableglotion)) {
       $this->addElement('Text', 'state', array(
          'label' => 'State',
          'maxlength' => '255',
          'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '63')),
          )
        ));
     }
     if(engine_in_array('city', $optionsenableglotion)) {
       $this->addElement('Text', 'city', array(
          'label' => 'City',
          'maxlength' => '255',
          'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '63')),
          )
        ));
     }
     if(engine_in_array('zip', $optionsenableglotion)) {
       $this->addElement('Text', 'zip', array(
          'label' => 'Zip',
          'maxlength' => '6',
          'minlength' => '5',
          'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
        ));
     }
     if(engine_in_array('lat', $optionsenableglotion)) {
       $this->addElement('Text', 'latValue', array(
          'label' => 'Latitude',
          'maxlength' => '20',
          'validators' => array(
              array('Float', true),
              array('GreaterThan', true, array(0)),
          )
        ));
     }
     if(engine_in_array('lng', $optionsenableglotion)) {
       $this->addElement('Text', 'lngValue', array(
          'label' => 'Longitude',
           'maxlength' => '20',
            'validators' => array(
              array('Float', true),
              array('GreaterThan', true, array(0)),
          )
        ));
     }
    }
    }
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
