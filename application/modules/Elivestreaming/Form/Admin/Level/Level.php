<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Level.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_Form_Admin_Level_Level extends Authorization_Form_Admin_Level_Abstract
{

  public function init()
  {

    parent::init();

    // My stuff
    $this->setTitle('Member Level Settings')
      ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");
    unset($this->level_id->options[5]);

    // $this->addElement('Radio', 'view', array(
    //   'label' => 'Allow Viewing of Live Streaming?',
    //   'description' => 'Do you want to let members view Live Streaming? If set to no, some other settings on this Live Streaming may not apply.',
    //   'multiOptions' => array(
    //     2 => 'Yes, allow members to view all  Live Streaming',
    //     1 => 'Yes, allow members to view their own  Live Streaming.',
    //     0 => 'No, do not allow  Live Streaming to be viewed.',
    //   ),
    //   'value' => ($this->isModerator() ? 2 : 1),
    // ));
    // if (!$this->isModerator()) {
    //   unset($this->view->options[2]);
    // }

    if (!$this->isPublic()) {

      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Enable Live Streaming?',
        'description' => 'Do you want to allow members of this level to perform Live Streaming.',
        'multiOptions' => array(
          1 => 'Yes, allow to perform live streaming.',
          0 => 'No, do not allow to perform.'
        ),
        'value' => 1,
      ));

      $this->addElement('Radio', 'save', array(
        'label' => 'Allow Save Live Video?',
        'description' => 'Do you want the members to save their Live Video?',
        'multiOptions' => array(
          1 => 'Yes, save in Phone Gallery',
          0 => 'No, do not allow to save.'
        ),
        'value' => 1,
      ));

      $options = array();$isActive = false;
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){
        $options['sesadvancedactivity'] = 'SES - Professional Activity & Nested Comments Plugin';
        $isActive = true;
      }
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesstories'))
        $options['sesstories'] = 'Stories Feature Plugin';
      if ($isActive) {
        $this->addElement('MultiCheckbox', 'share', array(
          'label' => 'Sharable Places for Live Video',
          'description' => 'Your members can choose from any of the options checked below for sharing their Live Videos',
          'multiOptions' => $options,
          'value' => array('sesadvancedactivity', 'sesstories')
        ));
      } else {
        $this->addError('Professional Activity & Nested Comments Plugin not activated yet.');
      }

      $description = Zend_Registry::get('Zend_Translate')->_('Enter the Time Duration (in minutes) upto which the members can stay Live.');
      $this->addElement('Text', 'duration', array(
        'label' => 'Maximum Allowed Live Video Duration',
        'description' => $description,
        'validators' => array(
          array('Int', true),
        ),
        'value' => 2,
      ));

      $this->addElement('Text', 'max', array(
        'label' => 'Maximum Allowed Live Streaming',
        'description' => 'Enter the maximum number of allowed Live Streaming performed by the member of this level. The field must contain an integer between 1 and 999, or 0 for unlimited.',
        'validators' => array(
          array('Int', true),
        ),
        'value' => 0,
      ));
    }
  }
}
