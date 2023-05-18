<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Allure
 * @copyright  Copyright 2015-2016 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Add.php 2015-06-04 00:00:00Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitead_Form_SlideAds extends Engine_Form {

	protected $_number = 1;
	protected $_format = '';

	public function setNumber($number) {
		$this->_number = $number;
		return $this;
	}

	public function setFormat($id) {
    $this->_format = $id;
    return $this;
  }

	public function init() {

		$this->clearDecorators();
		$this->addDecorator('FormElements');

		//VALUE FOR LOGO PREVIEW.
		if($this->_format == 'carousel') {
		$this->addElement('Dummy', 'ad_slide', array(
			'label' => 'Slide ' . $this->_number . ':',
		));
	}

		$this->addElement('Hidden', 'enable', array(
           'value' => 0,
		));
		// Init name
		$site_name = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title');
		$changeLink =Zend_Registry::get('Zend_Translate')->_('Example: http://www.yourwebsite.com/');
        //ELEMENT CADS_URL
		$this->addElement('Text', 'cads_url' , array(
			'label' => 'Destination URL',
			'class' => 'ads_url',
			'description' => $changeLink,
			'value' => 'http://',
			'required' => true,
			'allowEmpty' => false,
			'validators' => array(
				array('NotEmpty', true),
			)));
		$this->cads_url->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
		$this->cads_url->getValidator('NotEmpty')->setMessage('Please enter a valid email address.', 'isEmpty');

		$this->addElement('Text', 'cads_title', array(
			'label' => 'Title',
			'maxlength' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25),
			'class' => 'ads_name',
			'required' => true,
			'allowEmpty' => false,
			'validators' => array(
				array('NotEmpty', true),
				array('StringLength', false, array(1, Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25))),
			),
			'filters' => array(
				new Engine_Filter_StringLength(array('max' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25))),
			),
		));

        //ELEMENT BODY
		$this->addElement('Textarea', 'cads_body', array(
			'label' => Zend_Registry::get('Zend_Translate')->_('Description'),
			'maxlength' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135),
			'style' => 'width: 20em; height: 6em;',
			'required' => true,
			'class' => 'ads_desc',
			'wrap' => "hard",
			'validators' => array(
				array('NotEmpty', true),
				array('StringLength', false, array(1, Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135))),
			),
			'filters' => array(
				new Engine_Filter_StringLength(array('max' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135))),
			),
		));

		if($this->_format == 'image' || $this->_format == 'carousel') {
			$this->addElement('File', 'Filedata_'. $this->_number, array(
				'label' => Zend_Registry::get('Zend_Translate')->_('Ad Image'),
				'required' => true,
				'accept' => 'image/*',
				'class' => 'ads_file',
				'description' => Zend_Registry::get('Zend_Translate')->_("Browse and choose an image for your ad. Max file size allowed : "). (int) ini_get('upload_max_filesize') . Zend_Registry::get('Zend_Translate')->_(" MB. File types allowed: jpg, jpeg, png."),
				'validators' => array(
					array('Extension', false, 'jpg,png,jpeg')
				),
				'onchange' => 'imageupload(event)',
			));
		}

		if($this->_format == 'video') {
			$this->addElement('File', 'Filedata_'.$this->_number, array(
				'label' => Zend_Registry::get('Zend_Translate')->_('Ad Video'),
				'required' => true,
				'accept' => 'video/mp4',
				'class' => 'ads_file',
				'description' => Zend_Registry::get('Zend_Translate')->_("Browse and choose a video for your ad. Max file size allowed : "). (int) ini_get('upload_max_filesize') . Zend_Registry::get('Zend_Translate')->_(" MB. File types allowed: mp4."),
				'validators' => array(
					array('Extension', false, 'mp4')
				),
				'onchange' => 'videoupload(event)',
			));
		}

		$categories = Engine_Api::_()->getDbtable('categories', 'sitead')->getCategoriesAssoc();
		$this->addElement('Select', 'cta_button', array(
			'label' => 'Call to Action',
			'class' => 'ads_cta',
			'multiOptions' => $categories,
			'onchange' => "calltoaction(event);"
		));
        
		$categories =  array('0' => 'No Overlay', 'Cash on Delivery' => 'Cash on Delivery', 'Home Delivery' => 'Home Delivery', 'Bank Transfer Available' => 'Bank Transfer Available');
		$this->addElement('Select', 'overlay', array(
			'label' => 'Overlay',
			'class' => 'ads_overlay',
			'multiOptions' => $categories,
			'onchange' => "setOverlay(event);"
		));			

		$this->addDisplayGroup(array(
			'enable',
			'ad_slide',
			'cads_title',
			'cads_body',
			'Filedata_'.$this->_number,
			'cads_url',
			'cta_button',
			'overlay',
		), 'sitead_display_'.$this->_number
	);
        $ad_group = $this->getDisplayGroup('sitead_display_'.$this->_number);
        $ad_group->setDecorators(array(
        'FormElements',
        'Fieldset',
        array('HtmlTag', array('id' => 'sitead_display_'.$this->_number, 'class' => 'sitead_crate', 'style' => 'display:none;'))
    ));
	}
}