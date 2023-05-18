<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Createbrowsepage.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_Createbrowsepage extends Engine_Form {

  public function init() {

    $this->setTitle('Create New Browse Page for Profile Type')->setDescription('Here, you can create new widgetized page for browsing members based on selected Profile Types.');

    $this->addElement('Text', 'title', array(
        'label' => 'Page Title',
        'description' => 'Enter a title for this page. [Note: This title will be used for your indicative purpose in “Manage Browse Page for Profile Types” section.]',
        'allowEmpty' => false,
        'required' => true,
        'filters' => array(
            new Engine_Filter_Censor(),
            'StripTags',
            new Engine_Filter_StringLength(array('max' => '63'))
        ),
        'autofocus' => 'autofocus',
    ));
      //$multiOptions = array('' => ' ');
      $profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias('user', 'profile_type');
      if (engine_count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']))
        return;
      $profileTypeField = $profileTypeFields['profile_type'];

      $options = $profileTypeField->getOptions();
      
      foreach ($options as $option) {
        $multiOptions[$option->option_id] = $option->label;
      }

      $request = Zend_Controller_Front::getInstance()->getRequest();
      $moduleName = $request->getModuleName();
      $controllerName = $request->getControllerName();
      $actionName = $request->getActionName();

      $levelOptions = array();
      $levelValues = array();
      if ($moduleName == 'sesmember' && $controllerName == 'admin-manage' && $actionName == 'create') {
        foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
          $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($level->level_id, '0', 'browse');
          if ($checkLevelId || ($level->level_id == '5'))
            continue;
          $levelOptions[$level->level_id] = $level->getTitle();
          $levelValues[] = $level->level_id;
        }
      }
      else { 
        foreach ($multiOptions as $key =>  $level) {
          $homepage_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);

          $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($key, $homepage_id, 'browse');
          if ($checkLevelId)
            continue;
          $levelOptions[$key] = $level;
          $levelValues[] = $key;
        }
      }
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
    
    if(empty($id)) {

      // Select Member Levels
      $this->addElement('Select', 'member_levels', array(
          'label' => 'Choose Profile Type',
          'multiOptions' => $levelOptions,
          'description' => 'Select the profile type belonging to which Members will be displayed on the associated widgetized page.',
          //'value' => $levelValues,
      ));
    } else {
        
        
      // Select Member Levels
      $this->addElement('Select', 'member_levels', array(
          'label' => 'Choose Profile Type',
          'multiOptions' => $levelOptions,
          'description' => 'Select the profile type belonging to which Members will be displayed on the associated widgetized page.',
          //'value' => $levelValues,
          'disable' => true,
      ));  
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage-profile')),
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('save', 'submit', 'cancel'), 'buttons');
  }

}
