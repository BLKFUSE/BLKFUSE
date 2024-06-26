<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesiosapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesiosapp_Form_Admin_Global extends Engine_Form {

  public function init() {
  
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');
            
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/tickets" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesiosapp_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesiosapp.licensekey'),
    ));
    $this->getElement('sesiosapp_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
		if ($settings->getSetting('sesiosapp.pluginactivated')) {



      $this->addElement('Text', 'sesiosapp_server_key', array(
          'label' => 'iOS API Key',
          'description' => 'iOS API Key will be used to send Push Notifications from your server. So, an API key will be required to enable this service. Here, enter the key. If you are not sure what to enter  , then please contact our support team.',
          'value'=>$settings->getSetting('sesiosapp_server_key', ''),
      ));
      $this->getElement('sesiosapp_server_key')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));


      $this->addElement('Radio', 'sesiosapp_disable_welcome', array(
          'label' => 'Disable welcome screen',
          'description' => 'Do you want to disable welcome screen',
          'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
          'value'=>$settings->getSetting('sesiosapp_disable_welcome', '0'),
      ));
      $this->getElement('sesiosapp_disable_welcome')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Radio', 'sesiosapp_guest_enable', array(
        'label' => 'Enable "Skip Login"',
        'description' => 'Do you want to allow "Guests" or "Non-Logged In" users to browse your app without login to your site? (If No, then only "Logged In" members will be able to use your app. If Yes, users will see "Skip Login" link to browse and use your app without having to login into your app.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sesiosapp.guest.enable', 1),
      ));

      $this->addElement('Radio', 'sesiosapp_show_titleheader', array(
        'label' => 'Display Site Title or Search in Header',
        'description' => 'Do you want to display the Site Title or the Global Search in header of your app?',
        'multiOptions' => array(
            1 => 'Show Site Title',
            2 => 'Show Global Search',
        ),
        'value' => $settings->getSetting('sesiosapp_show_titleheader', 2),
      ));

      $this->addElement('Text', 'sesiosapp_sitetitle', array(
        'label' => 'Site Title in Header',
        'description' => 'Enter the title of the site which you want to show in the header of your app.',
        'value' => $settings->getSetting('sesiosapp_sitetitle', ''),
      ));

      // $this->addElement('Radio', 'sesiosapp_display_loggedinuserphoto', array(
      //   'label' => 'Display Logged-in Member’s Photo',
      //   'description' => 'Do you want to display current logged-in member’s photo in the Top Right corner of your app header after global search or site title? (If you choose Yes, then a small photo will show in circle and clicking on this photo will send users to their member profile page.)',
      //   'multiOptions' => array(
      //       1 => 'Yes',
      //       0 => 'No',
      //   ),
      //   'value' => $settings->getSetting('sesiosapp_display_loggedinuserphoto', 1),
      // ));

      $this->addElement('Radio', 'sesiosapp_headerfixed', array(
        'label' => 'Fix Header on Home Page',
        'description' => 'Do you want to fix the header on the home page of your app? Currently, activity feed is shown by default on the home page, so if you choose Yes, then the header will shown when users scroll down the page. But, if you choose No, then the header will disappear on scrolling down and will reappear on scrolling up.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sesiosapp_headerfixed', 0),
      ));

     /* $this->addElement('Radio', 'sesiosapp_isNavigationTransparent', array(
        'label' => 'Enable transparency in App Navigation Bar',
        'description' => 'Do you want to enable transparency in app navigation bar.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sesiosapp_isNavigationTransparent', 1),
      ));*/

      $this->addElement('Radio', 'sesiosapp_memberImageShapeIsRound', array(
        'label' => 'Member Avatar Shape',
        'description' => 'Choose from below the shape of the member avatar in Activity Feeds & Browse Members page.',
        'multiOptions' => array(
            1 => 'Circle',
            0 => 'Square',
        ),
        'value' => $settings->getSetting('sesiosapp_memberImageShapeIsRound', 0),
      ));

       $this->addElement('Radio', 'sesiosapp_enable_tabbedmenu', array(
        'label' => 'Display Dashboard Viewer on Home Page',
        'description' => 'Do you want to display the Dashboard View icon on the home page of your app? If you choose Yes, then the icon will appear in the left side of the header of your app in Home page.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sesiosapp_enable_tabbedmenu', 1),
      ));

      $this->addElement('Text', 'sesiosapp_limitForIphone', array(
        'label' => 'Content Load Count in iPhones',
        'description' => 'Enter the count for the content to be loaded by default in iPhones. If you entre 10, then 10 feeds will load in activity feeds at once, 10 Photos will load on Browse Photos page, and so on for all modules and pages.',
        'value' => $settings->getSetting('sesiosapp_limitForIphone', '10'),
      ));

      $this->addElement('Text', 'sesiosapp_limitForIpad', array(
        'label' => 'Content Load Count in iPads',
        'description' => 'Enter the count for the content to be loaded by default in iPads. If you entre 10, then 10 feeds will load in activity feeds at once, 10 Photos will load on Browse Photos page, and so on for all modules and pages.',
        'value' => $settings->getSetting('sesiosapp_limitForIpad', '15'),
      ));

      $this->addElement('Radio', 'sesiosapp_showtabbartitle', array(
        'label' => 'Display Tab Titles under Tab Bar',
        'description' => 'Do you want to display the titles of the tabs: “Activity”, “Requests”, “Notifications” & “Messages” in the Tab bar which comes at the bottom of the app?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sesiosapp_showtabbartitle', 1),
      ));

       $this->addElement('Text', 'sesiosapp_shareontext', array(
        'label' => 'Text for “Share On” in Activity Feeds Share.',
        'description' => 'Enter the text to be shown when users share activity feeds on your website for “Share On”. We recommend you to enter the site title or any short name of your site, so that text does not go too long.',
        'value' => $settings->getSetting('sesiosapp_shareontext', 'SocialEngine'),
      ));

      $this->addElement('Text', 'sesiosapp_feedtruncationlimit', array(
        'label' => 'Activity Feed Character Limit',
        'description' => 'Enter the character limit after which users will see "more" option in the feeds. After clicking on "more" they will redirect to the Activity Feed View Page.',
        'value' => $settings->getSetting('sesiosapp_feedtruncationlimit', '200'),
      ));

      $options[1] = "1. ballPulse";
      $options[2] = "2. ballGridPulse";
      $options[3] = "3. ballClipRotate";
      $options[4] = "4. squareSpin";
      $options[5] = "5. ballClipRotatePulse";
      $options[6] = "6. ballClipRotateMultiple";
      $options[7] = "7. ballPulseRise";
      $options[8] = "8. ballRotate";
      $options[9] = "9. cubeTransition";
      $options[10] = "10. ballZigZag";
      $options[11] = "11. ballZigZagDeflect";
      $options[12] = "12. ballTrianglePath";
      $options[13] = "13. ballScale";
      $options[14] = "14. lineScale";
      $options[15] = "15. lineScaleParty	";
      $options[16] = "16. ballScaleMultiple";
      $options[17] = "17. ballPulseSync";
      $options[18] = "18. ballBeat";
      $options[19] = "19. lineScalePulseOut";
      $options[20] = "20. lineScalePulseOutRapid";
      $options[21] = "21. ballScaleRipple";
      $options[22] = "22. ballScaleRippleMultiple";
      $options[23] = "23. ballSpinFadeLoader	";
      $options[24] = "24. lineSpinFadeLoader";
      $options[25] = "25. triangleSkewSpin";
      $options[26] = "26. pacman";
      $options[27] = "27. ballGridBeat";
      $options[28] = "28. semiCircleSpin";
      $options[29] = "29. ballRotateChase";
      $options[30] = "30. orbit";
      $options[31] = "31. audioEqualizer";
      $options[32] = "32. circleStrokeSpin";
      $this->addElement('Select', 'sesiosapp_loadingimage', array(
        'label' => 'Loading Image in App',
        'description' => 'Select from below the loading image in app (<a href="javascript:;" class="loading_img">view</a>).',
        'multiOptions' =>$options,
        'value' => $settings->getSetting('sesiosapp_loadingimage', '32'),
      ));
      $this->getElement('sesiosapp_loadingimage')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
      $this->addElement('Text', 'sesiosapp_appurl', array(
        'label' => 'App URL for Rating',
        'description' => 'Enter the URL of your app at itunes store where users will be able to give their rating. The Rate Us option will be shown in the Dashboard of your app.',
        'value' => $settings->getSetting('sesiosapp_appurl', ''),
      ));

      $this->addElement('Text', 'sesiosapp_googleapikey', array(
        'label' => 'Google Place API Key',
        'description' => 'Enter the Google Place API key for entering location, check-in and displaying map in your app.',
        'value' => $settings->getSetting('sesiosapp_googleapikey', ''),
      ));

      // Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Save Changes',
          'type' => 'submit',
          'ignore' => true
      ));

    } else {
      $enabledSesbasic = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic');
      $fields = array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      );
      if(!$enabledSesbasic){
        $fields['disable'] = true;
        $fields['title'] = 'To Activate this plugin, please first install all dependent plugins as show in the tips above.';
      }
      //Add submit button
      $this->addElement('Button', 'submit',$fields);
    }
  }
}
