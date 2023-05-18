<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: HeaderSettings.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_HeaderSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Manage Header Settings')
            ->setDescription('Here, you can configure the settings for the Header, Main and Mini navigation menus of your website. Below, you can choose to place the Main Navigation menu vertically or horizontally.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    if($sesdating_landingpage) {
      //New File System Code
      $banner_options = array('' => '');
      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
      foreach( $files as $file ) {
        $banner_options[$file->storage_path] = $file->name;
      }
    }
    $fileLink = $view->baseUrl() . '/admin/files/';
    $this->addElement('Select', 'sesdating_logo', array(
        'label' => 'Logo in Header',
        'description' => 'Choose from below the logo image for the header of your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.]',
        'multiOptions' => $banner_options,
        'escape' => false,
        'value' => $settings->getSetting('sesdating.logo', ''),
    ));
    $this->sesdating_logo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

    
    $this->addElement('MultiCheckbox', 'sesdating_header_loggedin_options', array(
        'label' => 'Header Options for Logged In Members',
        'description' => 'Choose from below the options to be available in header to the logged in members on your website.',
        'multiOptions' => array(
            'search' => 'Search',
            'miniMenu' => 'Mini Menu',
            'mainMenu' =>'Main Menu',
            'logo' =>'Logo',
        ),
        'value' => unserialize($settings->getSetting('sesdating.header.loggedin.options','a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}')),
    ));
    
    $this->addElement('MultiCheckbox', 'sesdating_header_nonloggedin_options', array(
        'label' => 'Header Options for Non-Logged In Members',
        'description' => 'Choose from below the options to be available in header to the non-logged in members on your website.',
        'multiOptions' => array(
            'search' => 'Search Bar',
            'miniMenu' => 'Mini Menu Items',
            'mainMenu' =>'Main Menu Items',
            'logo' =>'Website Logo',
        ),
        'value' => unserialize($settings->getSetting('sesdating.header.nonloggedin.options','a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}')),
    ));
      
      $this->addElement('Select', 'sesdating_header_design', array(
        'label' => 'Main Menu Placement',
        'description' => 'Choose the placement for the Main Navigation Menu of your website. For Vertical placement, you can configure various options for the menu display.',
        'multiOptions' => array(
            '1' => 'Horizontal',
            '2' => 'Vertical',
        ),
        'onchange'=>'showHeaderDesigns(this.value)',
        'value' =>  Engine_Api::_()->sesdating()->getContantValueXML('sesdating_header_design'),
    ));
    
    $this->addElement('Select', 'sesdating_header_transparent', array(
      'label' => 'Transparent Header',
      'description' => 'Do you want to make the header of your website transparent?',
      'multiOptions' => array(
          1 => 'Yes',
          0 => 'No',
      ),
      'value' => $settings->getSetting('sesdating.header.transparent', 0),
    ));

    $this->addElement('Text', 'sesdating_limit', array(
        'label' => 'Menu Count',
        'description' => 'Choose number of menu items to be displayed before â€œMoreâ€? dropdown menu occurs?',
        'value' => $settings->getSetting('sesdating.limit', 4),
    ));

    $this->addElement('Text', 'sesdating_moretext', array(
        'label' => '"More" Button Text',
        'description' => 'Enter "More" Button Text',
        'value' => $settings->getSetting('sesdating.moretext', 'More'),
    ));
    $this->addElement('Select', 'sesdating_submenu', array(
        'label' => 'Show Plugin Navigation Menu',
        'description' => 'Do you want to show plugin navigation menu for the Main Menus?',
        'multiOptions'=>array('1'=>'Yes','0'=>'No'),
        'value' => $settings->getSetting('sesdating.submenu', '1'),
    ));
    
    $this->addElement('Select', 'sesdating_sidepanel_effect', array(
        'label' => 'Main Menu Opening Effect',
        'description' => 'Below, choose the effect of opening the Main menu when the icon is clicked in the site header. [For Slide option, you can choose to show the main menu always or hide it.]',
        'multiOptions' => array(
            '1' => 'Overlay',
            '2' => 'Slide',
        ),
        'onclick' => 'showHidePanel(this.value);',
        'value' =>  Engine_Api::_()->sesdating()->getContantValueXML('sesdating_sidepanel_effect'),
    ));
    
    $this->addElement('Select', 'sesdating_sidepanel_showhide', array(
        'label' => 'Option to Hide Main Menu',
        'description' => 'Do you want to allow users to choose to hide the main menu? If you choose Yes, then users will be able to hide the main menu and to see the main menu, they will have to click on the icon in the site Header.',
        'multiOptions' => array(
            '1' => 'No, always show',
            '0' => 'Yes, allow users to hide',
        ),
        'value' =>  $settings->getSetting('sesdating.sidepanel.showhide', '0'),
    ));
		
    $this->addElement('Select', 'sesdating_menuinformation_img', array(
        'label' => 'Background Image for User in Main Menu',
        'description' => 'Choose from below the background image for the user section in Main Menu. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.] If you have the <a href="http://www.socialenginesolutions.com/social-engine/advanced-members-plugin/" target="_blank">Advanced Members plugin</a>, then the user?s Cover Photo will show up in this section, instead of this background cover photo.',
        'multiOptions' => $banner_options,
        'escape' => false,
        'value' => $settings->getSetting('sesdating.menuinformation.img', ''),
    ));
    $this->sesdating_menuinformation_img->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      	
    $this->addElement('Select', 'sesdating_menu_img', array(
        'label' => 'Background Image for Menu Items in Main Menu',
        'description' => 'Choose from below the background image for the menu section in Main Menu. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.]',
        'multiOptions' => $banner_options,
        'escape' => false,
        'value' => $settings->getSetting('sesdating.menu.img', ''),
    ));
    $this->sesdating_menu_img->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
	
    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
