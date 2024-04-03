<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Form_Admin_Create extends Engine_Form {

  public function init() {

    $tutorial_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tutorial_id');
    
    $askquestion_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('askquestion_id');
    $askquestion = Engine_Api::_()->getItem('sestutorial_askquestion', $askquestion_id);
    
    $this->setTitle('Add New Tutorial')
            ->setDescription('Here, you can add new Tutorial for your website using the WYSIWYG editor. Below, you can choose a visibility, photo and add tags for the Tutorial.')
            ->setAttrib('id', 'sestutorial_create_form')
            ->setMethod('POST');

    if($askquestion_id) {
      $this->addElement('Text', "title", array(
        'label' => 'Title (Question)',
        'description'=>'Enter the title (question) of this Tutorial.',
        'allowEmpty' => false,
        'required' => true,
        'value' => $askquestion->description,
      ));
    } else {
      $this->addElement('Text', "title", array(
        'label' => 'Title (Question)',
        'description' => 'Enter the title (question) of this Tutorial.',
        'allowEmpty' => false,
        'required' => true,
      ));
    }
    
    //UPLOAD PHOTO URL
    $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sestutorial', 'controller' => 'manage', 'action' => "upload-image"), 'admin_default', true);

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';
    $editorOptions = array(
        'upload_url' => $upload_url,
        'html' => (bool) $allowed_html,
    );
    if (!empty($upload_url)) {
      $editorOptions['plugins'] = array(
          'table', 'fullscreen', 'media', 'preview', 'paste',
          'code', 'image', 'textcolor', 'jbimages', 'link'
      );
      $editorOptions['toolbar1'] = array(
          'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
          'media', 'image', 'jbimages', 'link', 'fullscreen',
          'preview'
      );
    }

    $this->addElement('TinyMce', 'description', array(
        'label' => 'Description (Answer)',
        'description' => 'Enter the description (answer) of this Tutorial.',
        'editorOptions' => $editorOptions,
    ));

    //Category
    $categories = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getCategoriesAssoc();
    $tutorial_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tutorial_id', 0);
    if (engine_count($categories) > 0) {
      $setting = Engine_Api::_()->getApi('settings', 'core');
      $categorieEnable = $setting->getSetting('sestutorial.category.enable', '1');
      if ($categorieEnable == 1) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      $categories = array('' => 'Choose Category') + $categories;
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'description' => 'Choose a category of this Tutorial.',
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
   
    $this->addElement('File', 'photo_id', array(
        'label' => 'Tutorial Photo',
        'description' => "Choose a photo for this Tutorial.",
    ));
    $this->photo_id->addValidator('Extension', false, 'jpg,jpeg,png,gif,webp');
    $tutorial_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('tutorial_id', null);
    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
    $photo_id = 0;
    if (isset($tutorial->photo_id))
      $photo_id = $tutorial->photo_id;
    if ($photo_id && $tutorial) {
      $path = Engine_Api::_()->storage()->get($photo_id, '')->getPhotoUrl();
      if (!empty($path)) {
        $this->addElement('Image', 'profile_photo_preview', array(
            'label' => 'Tutorials Photo Preview',
            'src' => $path,
            'width' => 100,
            'height' => 100,
        ));
      }
    }
    if ($photo_id) {
      $this->addElement('Checkbox', 'remove_profilecover', array(
          'label' => 'Yes, remove tutorial photo.'
      ));
    }

    
    //Search options
    $this->addElement('Text', 'tags',array(
      'label' => 'Keywords',
      'autocomplete' => 'off',
      'description' => 'Separate keywords with commas.',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");
    
    //Level Work
		$levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
		foreach ($levels as $level) {
			$levels_prepared[$level->getIdentity()] = $level->getTitle();
			$levels_preparedVal[] = $level->getIdentity();
		}
		
    $this->addElement('Multiselect', 'memberlevels', array(
        'label' => 'Member Levels',
        'description' => 'Choose the Member Levels to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific member levels.',
        'multiOptions' => $levels_prepared,
        'value' => $levels_preparedVal,
    ));

    //Make Network List
    $table = Engine_Api::_()->getDbtable('networks', 'network');
    $select = $table->select()
            ->from($table->info('name'), array('network_id', 'title'))
            ->order('title');
    $result = $table->fetchAll($select);
    foreach ($result as $value) {
      $networksOptions[$value->network_id] = $value->title;
      $networkvalue[] = $value->network_id;
    }
    $networkvalue = $networkvalue; //unserialize($networks);
    if (engine_count($networksOptions) > 0) {
      $this->addElement('Multiselect', 'networks', array(
          'label' => 'Networks',
          'description' => 'Choose the Networks to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific networks.',
          'multiOptions' => $networksOptions,
          'value' => $networkvalue,
      ));
    }
    
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if (engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
      $profileTypeField = $topStructure[0]->getChild();
      $options = $profileTypeField->getOptions();
      if (engine_count($options) > 1) {
        $options = $profileTypeField->getElementParams('user');
        unset($options['options']['order']);
        unset($options['options']['multiOptions']['']);
        $optionValues = array();
        foreach ($options['options']['multiOptions'] as $key => $option) {
          $optionValues[] = $key;
        }

        $this->addElement('multiselect', 'profile_types', array(
            'label' => 'Profile Types',
            'multiOptions' => $options['options']['multiOptions'],
            'description' => 'Choose the Profile Types to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific profile types.',
            'value' => $optionValues
        ));
      } else if (engine_count($options) == 1) {
        $this->addElement('Hidden', 'profile_types', array(
            'value' => $options[0]->option_id
        ));
      }
    }

    
//     $this->addElement('Select', 'status', array(
//         'label' => 'Status',
//         'description' => 'If this entry is published, it cannot be switched back to draft mode.',
//         'multiOptions' => array(
//             1 => 'Published',
//             0 => 'Draft',
//         ),
//         'value' => 1,
//     ));
    
    // Search
    $this->addElement('Checkbox', 'search', array(
      'value' => True,
      'label' => 'Yes, show this Tutorial in search results.',
      'description' => 'Show In Search',
    ));
    
    $this->addElement('Checkbox', 'status', array(
      'value' => True,
      'label' => 'Yes, enable this Tutorial.',
      'description' => 'Enable This Tutorial',
    ));
    
    //Add Element: Submit
    $this->addElement('Button', 'button', array(
        'label' => 'Submit',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sestutorial', 'controller' => 'manage', 'action' => 'index'), 'admin_default', true),
        'onclick' => '',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('button', 'cancel'), 'buttons');
  }
}
