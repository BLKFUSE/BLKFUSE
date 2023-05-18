<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
*/
class Sescommunityads_Form_Admin_Global extends Engine_Form {
  public function init() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');
            
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sescommunityads_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sescommunityads.licensekey'),
    ));
    $this->getElement('sescommunityads_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $arrayOptions = array('price'=>'Price','payment_type'=>'Payment Type','payment_duration'=>'Billing Duration','ad_count'=>'Ads Count','auto_approve'=>'Auto Approve Ads','promote_page'=>'Promote Your Page (Depend on Page Plugin)','promote_content'=>'Promote Your Content','website_visitor'=>'Get More Website Visitor','carosel'=>'Carousel View','video'=>'Single Video','featured'=>'Featured','sponsored'=>'Sponsored','targetting'=>'Targeting','description'=>'Description','advertise'=>'You can advertise');
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
        $arrayOptions['boos_post'] = 'Boost A Posts (Depend on Advanced Activity Plugin)';
    }
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescomadbanr')) {
        $arrayOptions['banner'] = 'Banner Image';
    }
    if ($settings->getSetting('sescommunityads.pluginactivated')) {
      $this->addElement('MultiCheckbox', 'sescommunityads_package_settings', array(
        'label' => 'Display Package Information',
        'description' => 'Choose from the below options which you want to display your users when they want to select the Package at the time of Ad creation.',
        'multiOptions'=>$arrayOptions,
        'value' => unserialize($settings->getSetting('sescommunityads.package.settings', 'a:16:{i:0;s:5:"price";i:1;s:12:"payment_type";i:2;s:16:"payment_duration";i:3;s:8:"ad_count";i:4;s:12:"auto_approve";i:5;s:9:"boos_post";i:6;s:12:"promote_page";i:7;s:15:"promote_content";i:8;s:15:"website_visitor";i:9;s:7:"carosel";i:10;s:5:"video";i:11;s:8:"featured";i:12;s:9:"sponsored";i:13;s:10:"targetting";i:14;s:11:"description";i:15;s:9:"advertise";}')),
      ));
       $this->addElement('Radio', 'sescommunityads_category_enable', array(
          'label' => 'Enable Category',
          'description' => "Do you want to enable category in Ads?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No'
          ),
          'value' => $settings->getSetting('sescommunityads.category.enable', '1'),
      ));
      $this->addElement('Radio', 'sescommunityads_category_mandatory', array(
          'label' => 'Category Mandatory',
          'description' => "Do you want to make category mandatory in Ads?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No'
          ),
          'value' => $settings->getSetting('sescommunityads.category.mandatory', '1'),
      ));
       $this->addElement('Radio', 'sescommunityads_payment_mod_enable', array(
          'label' => 'Initial Subscription Status',
          'description' => "Do you want to enable Ads immediately after payment or before the payment passes the gateway’s fraud check? This may take anywhere from 20 minutes to 4 days, depending on the circumstances and the gateway.",
          'multiOptions' => array(
              'all' => 'Enable Ads immediately.',
              'some' => 'Enable if user has an existing successful transaction, wait if this is their first.',
              'none' => 'Wait until the gateway signals that the payment has completed successfully.'
          ),
          'value' => $settings->getSetting('sescommunityads.payment.mod.enable', 'all'),
      ));

      $this->addElement('Radio', 'sescommunityads_package_style', array(
          'label' => 'Package Alignment',
          'description' => "Choose the alignment for packages on Package Selection page. This setting will only effect the new packages. Existing package will show in Horizontal View only.",
          'multiOptions' => array(
              '1' => 'Horizontal',
              '0' => 'Vertical',
          ),
          'value' => $settings->getSetting('sescommunityads.package.style', 1),
      ));
      $this->addElement('MultiCheckbox', 'sescommunityads_call_toaction', array(
          'label' => 'Call to Action Button',
          'description' => "Choose from the below options you want to show in call to action button when user wants to choose it at the time of Ad Creation.",
          'multiOptions' => array(
            'request_time' => 'Request Time',
            'apply_now' => 'Apply Now',
            'book_now' => 'Book Now',
            'contact_us' => 'Contact Us',
            'download' => 'Download',
            'get_showtimes' => 'Get Showtimes',
            'learn_more' => 'Learn More',
            'listen_now' => 'Listen Now',
            'show_message' => 'Show Message',
            'see_menu' => 'See Menu',
            'shop_now' => 'Shop Now',
            'sign_up' => 'Sign Up',
            'subscribe' => 'Subscribe',
            'watch_more' => 'Watch More',
          ),
          'value' => unserialize($settings->getSetting('sescommunityads.call.toaction', ('a:14:{i:0;s:12:"request_time";i:1;s:9:"apply_now";i:2;s:8:"book_now";i:3;s:10:"contact_us";i:4;s:8:"download";i:5;s:13:"get_showtimes";i:6;s:10:"learn_more";i:7;s:10:"listen_now";i:8;s:12:"show_message";i:9;s:8:"see_menu";i:10;s:8:"shop_now";i:11;s:7:"sign_up";i:12;s:9:"subscribe";i:13;s:10:"watch_more";}'))),
      ));

      //New File System Code
      $default_photos_main = array();
      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
      foreach( $files as $file ) {
        $default_photos_main[$file->storage_path] = $file->name;
      }
	  $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $fileLink = $view->baseUrl() . '/admin/files/';
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')) {
        $this->addElement('Radio', "sescommunityads_enable_location", array(
            'label' => 'Enable Location',
            'description' => "Do you want to enable location for the Ads on your website (dependent on <a href='https://www.socialenginesolutions.com/social-engine/advanced-members-plugin/' target='_blank'>Advanced Member Plugin</a>)?",
            'multiOptions' => array(
                '1' => 'Yes',
                '0' => 'No',
            ),
            'value' => $settings->getSetting('sescommunityads_enable_location', 1),
        ));
        $this->getElement('sescommunityads_enable_location')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

        $this->addElement('Radio', 'sescommunityads_search_type', array(
            'label' => 'Proximity Search Unit',
            'description' => 'Choose the unit for proximity search of location for Ads on your website.',
            'multiOptions' => array(
                1 => 'Miles',
                0 => 'Kilometres'
            ),
            'value' => $settings->getSetting('sescommunityads_search_type', 1),
        ));
      }
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){
        if (engine_count($default_photos_main) > 0) {
          $default_photos = array_merge(array('application/modules/Sescommunityads/externals/images/boost_post_default.png'=>''),$default_photos_main);
          $this->addElement('Select', 'sescommunityads_boost_default_adult', array(
              'label' => 'Default Boost Post Photo',
              'description' => 'Choose default photo for boost post on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change boost post photo.]',
              'multiOptions' => $default_photos,
              'value' => $settings->getSetting('sescommunityads_boost_default_adult'),
          ));
        } else {
          $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for boost post on your website. Photo to be chosen for boost post on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the boost post on your website. Please upload the Photo to be chosen for boost post on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
          //Add Element: Dummy
          $this->addElement('Dummy', 'sescommunityads_boost_default_adult', array(
              'label' => 'Default Adult Photo for albums',
              'description' => $description,
          ));
        }
        $this->sescommunityads_boost_default_adult->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      }
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
      $this->addElement('Hidden','is_license',array('value'=>1));
    }
  }
}
