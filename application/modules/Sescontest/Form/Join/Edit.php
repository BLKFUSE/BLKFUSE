<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Join_Edit extends Engine_Form {

  public function init() {
    $this->setDescription('Below, you can edit basic information about your entry.')
            ->setMethod('POST');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $contest_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('contest_id', 0);
    $contest = Engine_Api::_()->getItem('contest', $contest_id);
    $this->addElement('Dummy', 'contest_user_info', array(
        'label' => 'Basic Info',
    ));
    $this->addElement('Text', 'title', array(
        'label' => 'Entry Title',
        'autocomplete' => 'off',
        'allowEmpty' => false,
        'required' => true,
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', false, array(1, 255)),
        ),
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
    ));
    if ($contest->contest_type != 1) {
      if ($settings->getSetting('sescontest.show.entrydescription', 1) && $contest->contest_type != 1) {
        if ($settings->getSetting('sescontest.entrydescription.required', 1)) {
          $required = true;
          $allowEmpty = false;
        } else {
          $required = false;
          $allowEmpty = true;
        }
        $this->addElement('Textarea', 'description', array(
            'label' => 'Description',
            'allowEmpty' => $allowEmpty,
            'required' => $required,
        ));
      }
    }
    if ($settings->getSetting('sescontest.show.entrytag', 1)) {
      $this->addElement('Text', 'tags', array(
          'label' => 'Tags (Keywords)',
          'autocomplete' => 'off',
          'description' => 'Separate tags with commas.',
          'filters' => array(
              new Engine_Filter_Censor(),
          ),
      ));
      $this->tags->getDecorator("Description")->setOption("placement", "append");
    }
    $this->addElement('Dummy', 'contest_basic_info', array(
        'label' => 'User Info',
    ));
    $userInfoOptions = $settings->getSetting('sescontest.user.info', array('name', 'gender', 'age', 'email', 'phone_no'));
    if (engine_in_array('name', $userInfoOptions)) {
      $userName = $userData['first_name'] . ' ' . $userData['last_name'];
      $this->addElement('Text', 'name', array(
          'label' => 'Name',
          'value' => $userName,
      ));
    }
    if (engine_in_array('gender', $userInfoOptions)) {
      $this->addElement('Select', 'gender', array(
          'label' => 'Gender',
          'description' => '',
          'multiOptions' => array(
              1 => '',
              '2' => 'Male',
              '3' => 'Female',
          ),
          'value' => $userData['gender'],
      ));
    }
    if (engine_in_array('age', $userInfoOptions)) {
      $this->addElement('Text', 'age', array(
          'label' => 'Age',
          'autocomplete' => 'off',
          'filters' => array(
              'StripTags',
              new Engine_Filter_Censor(),
          ),
      ));
    }
    if (engine_in_array('email', $userInfoOptions)) {
      // Element: email
      $this->addElement('Text', 'email', array(
          'label' => 'Email',
          'description' => '',
          'required' => true,
          'allowEmpty' => false,
          'value' => $viewer->email,
      ));
    }
    if (engine_in_array('phone_no', $userInfoOptions)) {
      $this->addElement('Text', 'phoneno', array(
          'label' => 'Phone No.',
      ));
    }
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'decorators' => array('ViewHelper')
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'href' => '',
        'prependText' => ' or ',
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
  }

}
