<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Form_Admin_Global extends Engine_Form {

  public function init() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "egames_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('egames.licensekey'),
    ));
    $this->getElement('egames_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('egames.pluginactivated')) {
     
      $this->addElement('Text', 'egames_pages_manifest', array(
        'label' => 'Plural Text for "games" in URL',
        'description' => 'Enter the text which you want to show in place of "games" in the URLs of this plugin.',
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('egames.pages.manifest', 'games'),
    ));
    $this->addElement('Text', 'egames_page_manifest', array(
        'label' => 'Singular Text for "game" in URL',
        'description' => 'Enter the text which you want to show in place of "game" in the URLs of this plugin.',
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('egames.page.manifest', 'game'),
    ));


				//default photos
    //New File System Code
    $default_photos_main = array();
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
        $default_photos_main[$file->storage_path] = $file->name;
    }
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
		//event main photo
    // if (engine_count($default_photos_main) > 0) {
		// 	$default_photos = array_merge(array('application/modules/Egames/externals/images/egames.png'=>''),$default_photos_main);
    //   $this->addElement('Select', 'egames_game_default_adult', array(
    //       'label' => 'Default Photo for Games',
    //       'description' => 'Choose default photo for games on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change adult default photo.]',
    //       'multiOptions' => $default_photos,
    //       'value' => $settings->getSetting('egames.game.default.adult'),
    //   ));
    // } else {
    //   $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for games on your website. Photo to be chosen for games on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the games on your website. Please upload the Photo to be chosen for games on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
    //   //Add Element: Dummy
    //   $this->addElement('Dummy', 'egames_game_default_adult', array(
    //       'label' => 'Default Adult Photo for albums',
    //       'description' => $description,
    //   ));
    // }
    // $this->egames_game_default_adult->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    
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
