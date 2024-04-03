<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    
    $this->setTitle('Global Settings')
        ->setDescription('These settings affect all members in your community.');

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('elivestreaming.pluginactivated')) {
        $this->addElement('Text', "elivestreaming_agoraappid", array(
            'label' => 'Agora App ID',
            'description' => "",
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting('elivestreaming.agoraappid'),
        ));
        $this->getElement('elivestreaming_agoraappid')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
//      $this->addElement('Radio', 'elivestreaming_showliveimage', array(
//        'label' => 'Show image in stories?',
//        'description' => 'Select below image when user goes live? If no, then user profile is shown.',
//        'multiOptions' => array(
//          1 => 'Yes',
//          0 => 'No',
//        ),
//        'value' => $settings->getSetting('elivestreaming.showliveimage', 1),
//      ));

      $this->addElement('Text', 'elivestreaming_linux_base_url', array(
        'label' => 'Stream base URL',
        'description' => 'Stream base URL.',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('elivestreaming.linux.base.url',""),
      ));

//      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
//      //New File System Code
//      $banner_options = array();
//      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
//      foreach( $files as $file ) {
//        $banner_options[$file->storage_path] = $file->name;
//      }
//
//      $fileLink = $view->baseUrl() . '/admin/files/';
//      if (engine_count($banner_options) > 1) {
//        $this->addElement('Select', 'elivestreaming_storieslivedefaultimage', array(
//          'label' => 'Stories Live Default Image',
//          'description' => 'Choose a default photo for the stories when user goes live on mobile app. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>. Leave the field blank if you do not want to change default photo.]',
//          'multiOptions' => $banner_options,
//          'value' => $settings->getSetting('elivestreaming.storieslivedefaultimage'),
//        ));
//        $this->elivestreaming_storieslivedefaultimage->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
//      } else {
//        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("There are currently no photos added in the File & Media Manager. Please upload a photo from here: <a href='" . $fileLink . "' target='_blank'>File & Media Manager</a> and refresh the page to display new files.") . "</span></div>";
//
//        //Add Element: Dummy
//        $this->addElement('Dummy', 'elivestreaming_storieslivedefaultimage', array(
//          'label' => 'Playlist Default Photo',
//          'description' => $description,
//        ));
//        $this->elivestreaming_storieslivedefaultimage->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
//      }

      // Add submit button
      $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
      ));
    } else {
      //Add submit button
      $this->addElement('Button', 'submit', array(
        'label' => 'Activate This Plugin',
        'type' => 'submit',
        'ignore' => true
      ));
    }
  }
}
