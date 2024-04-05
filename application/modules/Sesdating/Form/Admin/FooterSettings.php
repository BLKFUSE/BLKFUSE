<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: FooterSettings.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_FooterSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Manage Footer Settings')
            ->setDescription('Here, you can configure the settings for the Footer of your website.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    if($sesdating_landingpage) {
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    }
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
    $this->addElement('Select', 'sesdating_footer_background_image', array(
        'label' => 'Footer Background Image',
        'description' => 'Choose from below the footer background image for your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.]',
        'multiOptions' => $banner_options,
        'escape' => false,
        'value' => $settings->getSetting('sesdating.footer.background.image'), //Engine_Api::_()->sesdating()->getContantValueXML('sesdating_footer_background_image'),
    ));
    $this->sesdating_footer_background_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


    $fileLink = $view->baseUrl() . '/admin/files/';
    $this->addElement('Select', 'sesdating_footerlogo', array(
        'label' => 'Logo in Footer',
        'description' => 'Choose from below the logo image for the footer of your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.]',
        'multiOptions' => $banner_options,
        'escape' => false,
        'value' => $settings->getSetting('sesdating.footerlogo', ''),
    ));
    $this->sesdating_footerlogo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    
    $fileLink = $view->baseUrl() . '/admin/menus/index/name/sesdating_quicklinks_footer';
    $this->addElement('Radio',
      'sesdating_quicklinksenable',
      array(
          'label' => 'Enable Quick Links',
          'description' => 'Do you want to enable quick links to your preferred links in the footer? If you choose Yes, the the menu items will display which have been configured from <a href="' . $fileLink . '" target="_blank">Click Here</a>.',
          'multiOptions' => array('1'=>'Yes','0'=>'No'),
          'value'=>$settings->getSetting('sesdating.quicklinksenable', '1'),
    ));
    $this->sesdating_quicklinksenable->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


    $this->addElement('Radio',
      'sesdating_helpenable',
      array(
          'label' => 'Enable Footer Menu Links',
          'description' => 'Do you want to enable the SocialEngine default <a href="admin/menus?name=core_footer" target="_blank">Footer Menu links in the footer</a>?',
          'multiOptions' => array('1'=>'Yes','0'=>'No'),
          'value'=>$settings->getSetting('sesdating.helpenable', '1'),
    ));
    $this->sesdating_helpenable->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    $this->addElement('Radio',
    'sesdating_socialenable',
    array(
      'label' => 'Enable Social Site Links',
      'description' => 'Do you want to enable the social links in the footer?',
      'multiOptions' => array('1'=>'Yes','0'=>'No'),
      'onchange'=>'socialmedialinks(this.value)',
      'value'=>$settings->getSetting('sesdating.socialenable', '1'),
    ));

    $this->addElement(
      'Text',
      'sesdating_facebookurl',
      array(
        'label' => 'Facebook Page URL',
        'description' => 'Enter the URL of your Facebook Page.',
        'class'=>'socialclass',
        'value' => $settings->getSetting('sesdating.facebookurl', 'http://www.facebook.com/'),
      )
    );
    
    $this->addElement(
      'Text',
      'sesdating_googleplusurl',
        array(
          'label' => 'Google Plus URL',
          'description' => 'Enter the URL of your Google Plus account.',
          'class'=>'socialclass',
          'value' => $settings->getSetting('sesdating.googleplusurl', 'http://plus.google.com/'),
        )
    );
    
    $this->addElement(
      'Text',
      'sesdating_twitterurl',
      array(
        'label' => 'Twiiter URL',
        'description' => 'Enter the URL of your Twitter account.',
        'class'=>'socialclass',
        'value' => $settings->getSetting('sesdating.twitterurl', 'https://www.twitter.com/'),
      )
    );
    
    $this->addElement(
      'Text',
      'sesdating_pinteresturl',
      array(
        'label' => 'Pinterest URL',
        'description' => 'Enter the URL of your Pinterest account.',
        'class'=>'socialclass',
        'value' => $settings->getSetting('sesdating.pinteresturl', 'https://www.pinterest.com/'),
      )
    );
    
    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }
}
