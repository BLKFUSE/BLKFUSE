<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Create.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_Create extends Engine_Form {

  public function init() {

    $this->setTitle('Create New Member Home Page')
            ->setDescription('Here, you can create widgetized member home page for your website. Below, you can choose a member level.');

    $this->addElement('Text', 'title', array(
        'label' => 'Page Title',
        'description' => 'Enter a title for this page. [Note: This title will be used for your indicative purpose in “Manage Member Home Pages” section, but, if you want to show this title on the page too, then you can choose Yes for showing the title in the “Widgetized Page” widget on associated widgetized page.',
        'allowEmpty' => false,
        'required' => true,
        'filters' => array(
            new Engine_Filter_Censor(),
            'StripTags',
            new Engine_Filter_StringLength(array('max' => '63'))
        ),
        'autofocus' => 'autofocus',
    ));

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $moduleName = $request->getModuleName();
    $controllerName = $request->getControllerName();
    $actionName = $request->getActionName();

    $levelOptions = array();
    $levelValues = array();
    if ($moduleName == 'sesmember' && $controllerName == 'admin-manage' && $actionName == 'create') {
      foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
        $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($level->level_id, '0', 'home');
        if ($checkLevelId || ($level->level_id == '5'))
          continue;
        $levelOptions[$level->level_id] = $level->getTitle();
        $levelValues[] = $level->level_id;
      }
    }
    else {
      foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
        $homepage_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
        $checkLevelId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($level->level_id, $homepage_id, 'home');
        if ($checkLevelId || ($level->level_id == '5'))
          continue;
        $levelOptions[$level->level_id] = $level->getTitle();
        $levelValues[] = $level->level_id;
      }
    }
    // Select Member Levels
    $this->addElement('multiselect', 'member_levels', array(
        'label' => 'Member Levels',
        'multiOptions' => $levelOptions,
        'description' => 'Choose the Member Levels to which this Page will be displayed.',
        'value' => $levelValues,
    ));

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
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage-page')),
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('save', 'submit', 'cancel'), 'buttons');
  }

}