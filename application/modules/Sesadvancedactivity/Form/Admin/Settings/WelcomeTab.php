<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: WelcomeTab.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Form_Admin_Settings_WelcomeTab extends Engine_Form {

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Welcome (or Introduction) Tab Settings')
            ->setDescription('Here, you can configure the welcome tab settings and various sections to be displayed under this tab. You can choose these section in “Welcome Tab Sections” widget to set their positions.
            Since, this page supports other widgets as well, you can place widgets from other plugins as well (including 3rd party plugin widgets).');

    $this->addElement('Radio', 'sesadvancedactivity_showwelcometab', array(
      'label' => 'Show Welcome Tab',
      'description' => 'Do you want to show this tab to users of your website?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'onclick' => 'showwelcometab(this.value)',
      'value' => $settings->getSetting('sesadvancedactivity.showwelcometab', 1),
    ));

    //New File System Code
    $default_photos_main = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
      $default_photos_main[$file->storage_path] = $file->name;
    }
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
      $this->addElement('Text', 'sesadvancedactivity_welcometabtext', array(
              'label' => "Welcome Tab Title",
              'description' => 'Enter the title of the Welcome tab. If you do not want to show title, then simply leave this field empty.',
              'value' => $settings->getSetting('sesadvancedactivity.welcometabtext', 'Welcome'),
      ));

      if (engine_count($default_photos_main) > 0) {
      $default_photos = $default_photos_main;
      $this->addElement('Select', 'sesadvancedactivity_welcomeicon', array(
          'label' => 'Welcome Tab Icon',
          'description' => 'Choose icon of the Welcome tab. [Note: You can add a new icon from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to show icon.]',
          'multiOptions' => $default_photos,
          'value' => $settings->getSetting('sesadvancedactivity.welcomeicon'),
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no icon for Welcome Tab. Icon to be chosen for Welcome Tab should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no Icon in the File & Media Manager for Welcome Tab. Please upload the Icon to be chosen for Welcome Tab from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sesadvancedactivity_welcomeicon', array(
          'label' => 'Welcome Tab Icon',
          'description' => $description,
      ));
    }
    $this->sesadvancedactivity_welcomeicon->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    $this->addElement('Radio', 'sesadvancedactivity_tabvisibility', array(
      'label' => 'Tab Visibility',
      'description' => 'Do you want to set a duration for the visibility of this Welcome Tab?',
      'multiOptions' => array(
        2 => 'Yes, enter number of days after which users will not see this tab since their sign up.',
        1 => 'Yes, enter number of friends, after which this tab will not show.',
        0 => 'No, always show this tab.',
      ),
      'onclick' => 'tabvisibility(this.value)',
      'value' => $settings->getSetting('sesadvancedactivity.tabvisibility', 0),
    ));

    $this->addElement('Text', 'sesadvancedactivity_numberofdays', array(
      'label' => 'Number of Days',
      'description' => 'Enter number of days.',
      'validators' => array(
          array('Int', true),
          array('GreaterThan', true, array(0)),
      ),
      'value' => $settings->getSetting('sesadvancedactivity.numberofdays', 3),
    ));

    $this->addElement('Text', 'sesadvancedactivity_numberoffriends', array(
      'label' => 'Number of Friends',
      'description' => 'Enter number of friends.',
      'validators' => array(
          array('Int', true),
          array('GreaterThan', true, array(0)),
      ),
      'value' => $settings->getSetting('sesadvancedactivity.numberoffriends', 3),
    ));

    $this->addElement('Radio', 'sesadvancedactivity_makelandingtab', array(
      'label' => 'Make Landing Tab',
      'description' => 'Do you want to make this tab as the landing tab of Professional Activity & Comments on your website?',
      'multiOptions' => array(
        2 => 'Yes, make for all users. (Everyone will see this tab default opened.)',
        1 => 'Yes, make for new signup user. (Only new signup member will see this tab default opened for 1 time.)',
        0 => 'No, do not make this landing tab and default open “What’s New” tab.',
      ),
      'value' => $settings->getSetting('sesadvancedactivity.makelandingtab', 2),
    ));


    $description = 'Do you want to enable members to upload their profile photos via AJAX from this tab, if they have still not uploaded? [This will encourage users to upload their photos quickly and easily without reloading the page.';

//     $this->addElement('Radio', 'sesadvancedactivity_profilephotoupload', array(
//       'label' => 'Profile Photo Upload',
//       'description' => $description,
//       'multiOptions' => array(
//         1 => 'Yes',
//         0 => 'No',
//       ),
//       'onclick' => 'profilephotoupload(this.value)',
//       'value' => $settings->getSetting('sesadvancedactivity.profilephotoupload', 0),
//     ));
//     $this->getElement('sesadvancedactivity_profilephotoupload')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
//
//     $this->addElement('Radio', 'sesadvancedactivity_canphotoshow', array(
//       'label' => 'Always Display Profile Photo Upload',
//       'description' => 'Do you always want to display the "Profile Photo Upload" section on the Welcome Tab?',
//       'multiOptions' => array(
//         1 => 'Yes, always show.',
//         2 => 'No, show only when user has not uploaded his profile photo.',
//       ),
//       'value' => $settings->getSetting('sesadvancedactivity.canphotoshow', 1),
//     ));

    $this->addElement('Radio', 'sesadvancedactivity_friendrequest', array(
      'label' => 'Friend Requests',
      'description' => 'Do you want to show Friend Requests in this tab?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No',
      ),
      'onclick' => 'friendrequest(this.value)',
      'value' => $settings->getSetting('sesadvancedactivity.friendrequest', 1),
    ));

    $this->addElement('Text', 'sesadvancedactivity_countfriend', array(
      'label' => 'Count of Friends',
      'description' => 'Enter the number of friend requests to be shown.',
      'validators' => array(
          array('Int', true),
          array('GreaterThan', true, array(0)),
      ),
      'value' => $settings->getSetting('sesadvancedactivity.countfriend', 3),
    ));


    $this->addElement('Radio', 'sesadvancedactivity_findfriends', array(
      'label' => 'Find Friends',
      'description' => 'Do you want to enable members to find their friends from this tab?',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No',
      ),
      'onclick' => "findfriendssearch(this.value)",
      'value' => $settings->getSetting('sesadvancedactivity.findfriends', 1),
    ));

    $this->addElement('Text', 'sesadvancedactivity_searchnumfriend', array(
      'label' => 'Friends Count',
      'description' => 'Enter the number of friends count until which members will see “Find Friends” section.',
      'validators' => array(
          array('Int', true),
          array('GreaterThan', true, array(0)),
      ),
      'value' => $settings->getSetting('sesadvancedactivity.searchnumfriend', 3),
    ));

    $this->addElement('Text', 'sesadvancedactivity_tabsettings', array(
      'label' => 'Tab Heading',
      'description' => 'Enter the heading of this tab. This heading will display on the top of the tab. Use [site_title] and [user_name] variables to show your website title and member name respectively in the heading.',
      'value' => $settings->getSetting('sesadvancedactivity.tabsettings', "Welcome to [site_title], [user_name]"),
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));

  }
}
