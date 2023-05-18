<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: CreateprofilePhoto.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_CreateprofilePhoto extends Engine_Form {

  public function init() {

    $this->setTitle('Uplaod Default Photo')->setDescription('Upload a default photo for the select profile type.');
    $this->setMethod('post');

    $results = Engine_Api::_()->getDbTable('profilephotos', 'sesmember')->getProfilePhotos();
    $alreadyUploadProfielIds = array();
    foreach ($results as $result) {
      $alreadyUploadProfielIds[$result->profiletype_id] = $result->profiletype_id;
    }

    // Element: profile_type
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if (engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
      $profileTypeField = $topStructure[0]->getChild();
      $options = $profileTypeField->getOptions();

      $options = $profileTypeField->getElementParams('user');
      unset($options['options']['order']);
      unset($options['options']['multiOptions']['0']);

      $finalArray = array_diff_key($options['options']['multiOptions'], $alreadyUploadProfielIds);
    }
    if (engine_count($finalArray) > 1) {

      $this->addElement('Select', 'profiletype_id', array(
          'label' => 'Profile Type',
          'multiOptions' => $finalArray,
          'required' => true,
          'allowEmpty' => false,
              )
      );
      $this->addElement('File', 'photo', array(
          'label' => 'Photo',
      ));
      $this->photo->addValidator('Extension', false, 'jpg,jpeg,png,gif,PNG,GIF,JPG,JPEG');

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
    } else {
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $fileLink = $view->baseUrl() . '/admin/files/';
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no more profile types for upload default photo.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'photo', array(
          'description' => $description,
      ));
      $this->photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->addElement('Cancel', 'cancel', array(
          'label' => 'Cancel',
          'link' => true,
          'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
          'onClick' => 'javascript:parent.Smoothbox.close();',
          'decorators' => array(
              'ViewHelper'
          )
      ));
    }
  }

}
