<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: landingPageSettings.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Eticktokclone_Form_Admin_LandingPageSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Landing Page Settings')
            ->setDescription('From here you can configure below mentioned settings for the landing page of this theme.');
            
    $eticktokclone_adminmenu = Zend_Registry::isRegistered('eticktokclone_adminmenu') ? Zend_Registry::get('eticktokclone_adminmenu') : null;
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    
    if($eticktokclone_adminmenu) {
      $this->addElement('Text', "eticktokclone_textblock1", array(
        'label' => 'Text For Heading 1',
        'description' => 'Enter Text for Heading 1',
        'value' => $settings->getSetting('eticktokclone.textblock1', 'It\'s awesome to capture and share world\'s moments'),
      ));

      $this->addElement('Text', "eticktokclone_textblock2", array(
        'label' => 'Text For Heading 2',
        'description' => 'Enter Text for Heading 2',
        'value' => $settings->getSetting('eticktokclone.textblock2', 'Instagram helps you connect with people with your amazing photos, videos and everything in between.'),
      ));
      
      $this->addElement('Select', 'eticktokclone_landingpagelogo', array(
          'label' => 'Landing Page Image',
          'description' => 'Choose image for the landing page of this theme. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>. Leave the field blank if you do not want to show image at the landing page.]',
          'multiOptions' => $banner_options,
          'escape' => false,
          'value' => $settings->getSetting('eticktokclone.landingpagelogo', ''),
      ));
      $this->eticktokclone_landingpagelogo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Text', "eticktokclone_rightheading", array(
        'label' => 'Right Side Heading',
        'description' => 'Enter text to be displayed in the right side heading.',
        'value' => $settings->getSetting('eticktokclone.rightheading', 'Login to see photos and videos from your friends.'),
      ));
      
      $this->addElement('Text', "eticktokclone_ioslink", array(
        'label' => 'iOS App Link',
        'value' => $settings->getSetting('eticktokclone.ioslink', ''),
      ));

      $this->addElement('Text', "eticktokclone_androidlink", array(
        'label' => 'Android App Link',
        'value' => $settings->getSetting('eticktokclone.androidlink', ''),
      ));
    }

    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }
}
