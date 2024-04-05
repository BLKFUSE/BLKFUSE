<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Photo.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Edit_Photo extends Engine_Form {

  public function init() {
    $this
    ->setTitle('Edit Photo')
    ->setAttrib('enctype', 'multipart/form-data')
    ->setAttrib('name', 'EditPhoto');

    $this->addElement('Image', 'current', array(
      'label' => 'Current Photo',
      'ignore' => true,
      'decorators' => array(array('ViewScript', array(
	'viewScript' => '_formEditImage.tpl',
	'class'      => 'form element',
	'testing' => 'testing'
      )))
    ));
    Engine_Form::addDefaultDecorators($this->current);
		$mainPhotoEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.photo.mandatory', '1');
		if ($mainPhotoEnable == 1) {
			$required = true;
			$allowEmpty = false;
		} else {
			$required = false;
			$allowEmpty = true;
		}

    $this->addElement('File', 'Filedata', array(
      'label' => 'Choose New Photo',
    //  'destination' => APPLICATION_PATH.'/public/temporary/',
   //   'multiFile' => 1,
		'required'=>$required,
		'allowEmpty'=>$allowEmpty,
      'validators' => array(
	//array('Count', false, 1),
	// array('Size', false, 612000),
	array('Extension', false, 'jpg,jpeg,png,gif,webp'),
      ),
      //'onchange'=>'javascript:uploadSignupPhoto();'
      'data-function' => 'uploadSignupPhoto',
    ));
    
    $this->addElement('Hidden', 'coordinates', array(
      'filters' => array(
        'HtmlEntities',
      )
    ));

    $this->addElement('Button', 'done', array(
      'label' => 'Save Photo',
      'type' => 'submit',
      'decorators' => array(
	'ViewHelper'
      ),
    ));
		$remove = '';
	if(!$mainPhotoEnable){
			$this->addElement('Cancel', 'remove', array(
				'label' => 'Remove Photo',
				'link' => true,
				'prependText' => ' or ',
				'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'remove-photo')),
				'onclick' => null,
				'class' => 'smoothbox',
				'decorators' => array(
		'ViewHelper'
				),
			));
			$remove = 'remove';
}
    $this->addDisplayGroup(array('done', $remove), 'buttons');
  }
}
