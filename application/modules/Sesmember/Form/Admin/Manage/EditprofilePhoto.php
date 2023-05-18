<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: EditprofilePhoto.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_EditprofilePhoto extends Engine_Form {

  public function init() {

    $this->setTitle('Uplaod Default Photo')
            ->setDescription('Upload a default photo for the select profile type.');
    $this->setMethod('post');

    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if (engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
      $profileTypeField = $topStructure[0]->getChild();
      $options = $profileTypeField->getOptions();

      $options = $profileTypeField->getElementParams('user');
      unset($options['options']['order']);
      unset($options['options']['multiOptions']['0']);
      $this->addElement('Select', 'profiletype_id', array_merge($options['options'], array(
          'required' => true,
          'allowEmpty' => false,
          'disable' => true,
          'tabindex' => $tabIndex++,
      )));
    }

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
  }

}
