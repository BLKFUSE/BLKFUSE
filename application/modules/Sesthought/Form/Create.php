<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Form_Create extends Engine_Form {

  public $_error = array();
  public function init() {
  
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if($settings->getSetting('sesthought.descriptionlimit', 0) == 0) {
      $descriptionlimit = 9999;
    } else {
      $descriptionlimit = $settings->getSetting('sesthought.descriptionlimit', 0);
    }

    $this->setTitle('Create New Thought')
        ->setMethod("POST")
        ->setDescription('Compose your new thought entry below, then click "Post Thought" to publish your thought.')
        ->setAttrib('name', 'sesthoughts_create')
        ->setAttrib('class', 'sesthought_create_form');
        
    $this->addElement('Text', 'thoughttitle', array(
      'placeholder' => 'Title',
      'label'=>'Title',
      'filters' => array(
        new Engine_Filter_Censor(),
        'StripTags',
        new Engine_Filter_StringLength(array('max' => '255'))
      ),
    ));

    $this->addElement('Textarea', 'title', array(
      'placeholder' => 'Thought',
      'label'=>'Thought',
      'allowEmpty' => false,
      'required' => true,
      'autofocus' => 'autofocus',
      'maxlength' => $descriptionlimit,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_StringLength(array('max' => $descriptionlimit)),
      ),
    ));
    
    $this->addElement('Text', 'source', array(
      'placeholder' => 'Source',
      'label'=>'Source',
      'filters' => array(
        new Engine_Filter_Censor(),
        'StripTags',
        new Engine_Filter_StringLength(array('max' => '255'))
      ),
    ));
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1)) {
    
      if($settings->getSetting('sesthought.categoryrequried', 0)) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }

      //Category
      $categories = Engine_Api::_()->getDbtable('categories', 'sesthought')->getCategoriesAssoc();
      if (engine_count($categories) > 0) {
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
    }

    // init to
    $this->addElement('Text', 'tags', array(
      'placeholder'=>'#tags',
      'label'=>'Tags',
      'autocomplete' => 'off',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");
    
    $thought_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('thought_id', 0);
    if($thought_id) {
      $thought = Engine_Api::_()->getItem('sesthought_thought', $thought_id);
    }
    if(empty($thought_id)) {
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('core.iframely.secretIframelyKey')) {
        $this->addElement('Radio', 'mediatype', array(
          'label' => "Choose Media Type",
          'multiOptions' => array('1' => 'Photo', '2' => "Video"),
          'value' => 1,
          'onchange' => "showMediaType(this.value);",
        ));
      }

      $this->addElement('File', 'photo', array(
        'label' => 'Photo',
        'accept'=>"image/*",
      ));
      
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('core.iframely.secretIframelyKey')) {
      
        $this->addElement('Text', 'video', array(
          'description'=>'',
          'label'=>'Paste web address of the video',
          'placeholder'=> 'Paste the web address of the video here.',
          'onblur' => "iframelyurl();",
        ));
      }
    }
    if(!empty($thought_id) && $thought->photo_id) {
      $this->addElement('File', 'photo', array(
        'label' => 'Photo',
        'accept'=>"image/*",
      ));
    }
    $this->addElement('Hash', 'token', array(
      'timeout' => 3600,
    ));
    
//     $this->addElement('Hidden', 'code', array(
//       'order' => 1
//     ));
    
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Post Thought',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'onclick' => 'javascript:sessmoothboxclose();',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');
  }
}
