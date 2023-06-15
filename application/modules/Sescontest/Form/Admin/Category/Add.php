<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Add.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_Category_Add extends Engine_Form {

  public function init() {

    $category_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
    if ($category_id)
      $category = Engine_Api::_()->getItem('sescontest_category', $category_id);

    $this->setMethod('post');

    $this->addElement('Text', 'category_name', array(
        'label' => 'Name',
        'description' => 'The name is how it appears on your site.',
        'allowEmpty' => false,
        'required' => true,
    ));
    $this->addElement('Text', 'slug', array(
        'label' => 'Slug',
        'description' => 'The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
        'allowEmpty' => false,
        'required' => true,
    ));
    $this->addElement('Text', 'title', array(
        'label' => 'Title',
        'description' => 'Title will appear on the Category View Page of your site.',
        'allowEmpty' => true,
        'required' => false,
    ));
    $this->addElement('Text', 'color', array(
        'label' => 'Color',
        'description' => 'This will be category color & will appear in Grid View of events.',
        'class' => 'SEScolor',
        'allowEmpty' => true,
        'required' => false,
    ));
    $profiletype = array();
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('contest');
    if (engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
      $profileTypeField = $topStructure[0]->getChild();
      $options = $profileTypeField->getOptions();
      $options = $profileTypeField->getElementParams('contest');
      unset($options['options']['order']);
      unset($options['options']['multiOptions']['0']);
      $profiletype = $options['options']['multiOptions'];
    }

    if (isset($category) && $category->category_id != 0) {
      $this->addElement('Select', 'profile_type', array(
          'label' => 'Map Profile Type',
          'description' => 'Map this category with the profile type, so that questions belonging to the mapped profile type will appear to users while creating / editing their events when they choose the associated Category.',
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => $profiletype
      ));
    }


    $levelOptions = array();
    $levelValues = array();
    foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
      if($level->getTitle() == 'Public')
      continue;
      $levelOptions[$level->level_id] = $level->getTitle();
      $levelValues[] = $level->level_id;
    }
    // Select Member Levels
    $this->addElement('multiselect', 'member_levels', array(
        'label' => 'Member Levels',
        'multiOptions' => $levelOptions,
        'description' => 'Choose the Member Levels to which this category will be displayed.(Note: Hold down the CTRL key to select or de-select specific Member Levels.)',
        'value' => $levelValues,
    ));

    $this->addElement('Textarea', 'description', array(
        'label' => 'Description',
        'description' => 'Description will appear on the Category View Page of your site.',
        'allowEmpty' => true,
        'required' => false,
    ));
    $this->addElement('File', 'cat_icon', array(
        'label' => 'Upload Icon',
        'description' => 'Upload an icon. (The Recommended dimensions of the icon: 40px * 40px.]'
    ));
    $this->cat_icon->addValidator('Extension', false, 'jpg,jpeg,png,gif,webp');

    if (isset($category) && $category->cat_icon) {
      $iconObject = Engine_Api::_()->storage()->get($category->cat_icon, '');
      if ($iconObject) {
        $img_path = Engine_Api::_()->storage()->get($category->cat_icon, '')->getPhotoUrl();
        if (strpos($img_path, 'http') === FALSE) {
          $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
        } else
          $path = $img_path;
        if (isset($path) && !empty($path)) {
          $this->addElement('Image', 'cat_icon_preview', array(
              'src' => $path,
              'width' => 100,
              'height' => 100,
          ));
        }
        $this->addElement('Checkbox', 'remove_cat_icon', array(
            'label' => 'Yes, delete this category icon.'
        ));
      }
    }

    //upload colored category icon
    $this->addElement('File', 'colored_icon', array(
        'label' => 'Upload Colored Icon',
        'description' => 'Upload colored icon. (The Recommended dimensions of the icon: 40px * 40px.]'
    ));
    $this->cat_icon->addValidator('Extension', false, 'jpg,jpeg,png,gif,webp');

    if (isset($category) && $category->colored_icon) {
      $iconObject = Engine_Api::_()->storage()->get($category->colored_icon, '');
      if ($iconObject) {
        $img_path = $iconObject->getPhotoUrl();
        if (strpos($img_path, 'http') === FALSE) {
          $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
        } else
          $path = $img_path;
        if (isset($path) && !empty($path)) {
          $this->addElement('Image', 'colored_icon_preview', array(
              'src' => $path,
              'width' => 100,
              'height' => 100,
          ));
        }
        $this->addElement('Checkbox', 'remove_colored_icon', array(
            'label' => 'Yes, delete this colored category icon.'
        ));
      }
    }
    $this->colored_icon->addValidator('Extension', false, 'jpg,jpeg,png,gif,webp');

    $this->addElement('File', 'thumbnail', array(
        'label' => 'Upload Thumbnail',
        'description' => 'Upload a thumbnail. (The Recommended dimensions of the thumbnail: 500px * 300px.]'
    ));
    $this->cat_icon->addValidator('Extension', false, 'jpg,jpeg,png,gif,webp');

    if (isset($category) && $category->thumbnail) {
      $iconObject = Engine_Api::_()->storage()->get($category->thumbnail, '');
      if ($iconObject) {
        $img_path = $iconObject->getPhotoUrl();
        if (strpos($img_path, 'http') === FALSE) {
          $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
        } else
          $path = $img_path;
        if (isset($path) && !empty($path)) {
          $this->addElement('Image', 'cat_thumbnail_preview', array(
              'src' => $path,
              'width' => 100,
              'height' => 100,
          ));
        }
        $this->addElement('Checkbox', 'remove_thumbnail_icon', array(
            'label' => 'Yes, delete this category thumbnail.'
        ));
      }
    }
    $this->addElement('Button', 'submit', array(
        'label' => 'Add',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }

}
