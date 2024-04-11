<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesmusic_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesmusic.licensekey'),
    ));
    $this->getElement('sesmusic_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('sesmusic.pluginactivated')) {
      $guidlines = 'How to get SoundCloud Client ID and Client Secret?

        1. Login to your SoundCloud Account. Register a new account, if you do not have one.
        2. Now, go to the URL: https://soundcloud.com/you/apps
        3. Click on Register a new application button.
        4. Follow the easy steps by entering the name of your app and your website URL.
        5. Now, copy the Client ID and Client Secret and paste here.';

				// welcome page
// 				if (!$settings->getSetting('sesmusic.changelanding', 0)) {
//         $this->addElement('Radio', 'sesmusic_changelanding', array(
//             'label' => 'Set Welcome Page as Landing Page',
//             'description' => 'Do you want to set the Default Welcome Page of this plugin as Landing page of your website?  [This is a one time setting, so if you choose ‘Yes’ and save changes, then later you can manually make changes in the Landing page from Layout Editor. Back up page of your current landing page will get created with the name “LP backup from SNS Advanced Music”.]  ',
//             'onclick' => 'confirmChangeLandingPage(this.value)',
//             'multiOptions' => array(
//                 '1' => 'Yes',
//                 '0' => 'No',
//             ),
//             'value' => $settings->getSetting('sesmusic.changelanding', 0),
//         ));
//       }
//       $this->addElement('Radio', 'sesmusic_check_welcome', array(
//           'label' => 'Welcome Page Visibility',
//           'description' => 'Who all users do you want to see this "Welcome Page"?',
//           'multiOptions' => array(
//               0 => 'Only logged in users',
//               1 => 'Only non-logged in users',
//               2 => 'Both, logged-in and non-logged in users',
//           ),
//           'value' => $settings->getSetting('sesmusic.check.welcome', 2),
//       ));
      $this->addElement('Radio', 'sesmusic_enable_welcome', array(
          'label' => 'Music Main Menu Redirection',
          'description' => 'Choose from below where do you want to redirect users when Music Menu item is clicked in the Main Navigation Menu Bar.',
          'multiOptions' => array(
              //1 => 'Music Welcome Page',
              0 => 'Music Home Page',
              2 => 'Music Browse Page'
          ),
          'value' => $settings->getSetting('sesmusic.enable.welcome', 0),
      ));
			// welcome page

      $this->addElement('Radio', 'sesmusic_uploadoption', array(
          'label' => 'Choose Song Source',
          'description' => 'Choose the source of songs from which you want songs to be uploaded on your website.',
          'multiOptions' => array(
              'myComputer' => "My Computer",
             // 'soundCloud' => 'SoundCloud [enter the "SoundCloud Client Id" and "SoundCloud Client Secret" below.]',
             // 'both' => 'Both "My Computer" and "SoundCloud"',
          ),
          'escape' => false,
          'onchange' => 'checkUpload(this.value)',
          'value' => $settings->getSetting('sesmusic.uploadoption', 'myComputer'),
      ));

      $this->addElement('Text', "sesmusic_scclientid", array(
          'label' => 'SoundCloud Client Id',
          'description' => 'Enter the SoundCloud Client Id. [Note: If you remove this “Id”, then the current songs from SoundCloud will not show Play option.] ' . '<a href="javascript:void(0);" title="' . $guidlines . '"><img onclick="showPopUp();" src="application/modules/Sesbasic/externals/images/icons/question.png" alt="Question" /></a>',
          'value' => $settings->getSetting('sesmusic.scclientid'),
      ));
      $this->sesmusic_scclientid->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Text', "sesmusic_scclientscreatid", array(
          'label' => 'SoundCloud Client Secret',
          'description' => 'Enter the SoundCloud Client Secret Key. [Note: If you remove this “Key”, then the current songs from SoundCloud will not show Play option.] ' . '<a href="javascript:void(0);" title="' . $guidlines . '"><img onclick="showPopUp();" src="application/modules/Sesbasic/externals/images/icons/question.png" alt="Question" /></a>',
          'value' => $settings->getSetting('sesmusic.scclientscreatid'),
      ));
      $this->sesmusic_scclientscreatid->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Radio', 'sesmusic_showplayer', array(
          'label' => 'Choose Music Player',
          'description' => 'Choose the music player which you want to show on your website to play songs?',
          'multiOptions' => array(
              '1' => 'Mini Player',
              '0' => 'Full Width Player',
          ),
          'value' => $settings->getSetting('sesmusic.showplayer', 0),
      ));

      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      //New File System Code
      $banner_options = array('' => '');
      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
      foreach( $files as $file ) {
        $banner_options[$file->storage_path] = $file->name;
      }
      $fileLink = $view->baseUrl() . '/admin/files/';
      if (engine_count($banner_options) > 1) {
        $this->addElement('Select', 'sesmusic_playistdefaultphoto', array(
            'label' => 'Playlist Default Photo',
            'description' => 'Choose a default photo for the playlists on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>. Leave the field blank if you do not want to change playlist default photo.]',
            'multiOptions' => $banner_options,
            'value' => $settings->getSetting('sesmusic.playistdefaultphoto'),
        ));
        $this->sesmusic_playistdefaultphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      } else {
        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("There are currently no photos added in the File & Media Manager. Please upload a photo from here: <a href='" . $fileLink . "' target='_blank'>File & Media Manager</a> and refresh the page to display new files.") . "</span></div>";

        //Add Element: Dummy
        $this->addElement('Dummy', 'playlist_defaultphoto', array(
            'label' => 'Playlist Default Photo',
            'description' => $description,
        ));
        $this->playlist_defaultphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      }

      $this->addElement('Select', 'sesmusic_taboptions', array(
        'label' => 'Menu Items Count in Main Navigation',
        'description' => 'How many menu items do you want to show in the Main Navigation Menu of this plugin?',
        'multiOptions' => array(
          0 => 0,
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
          6 => 6,
          7 => 7,
          8 => 8,
          9 => 9,
        ),
        'value' => $settings->getSetting('sesmusic.taboptions', 6),
      ));

      $this->addElement('Radio', 'sesmusic_enable_addmusichortcut', array(
          'label' => 'Show Create New Music Album Icon',
          'description' => 'Do you want to show "Create New Music Album" Icon on all the pages of this plugin? This icon will redirect users to Create New Music Album page.',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesmusic.enable.addmusichortcut', 1),
      ));

      $this->addElement('Radio', 'sesmusic_download_publicuser', array(
          'label' => 'Download Songs for Non-logged-In User',
          'description' => 'Do you want show "Download" Icon for Non-logged-In Members?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesmusic.download.publicuser', 0),
      ));

      $this->addElement('Radio', "sesmusic_other_modulemusics", array(
        'label' => 'Music Albums Created in Content Visibility',
        'description' => "Choose the visibility of the musics created in a content to only that content (module) or show in Home page, Browse page and other places of this plugin as well? (To enable users to create musics in a content or module, place the widget \"Content Profile Music Albums\" on the profile page of the desired content.)",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sesmusic.other.modulemusics', 1),
      ));
			$this->addElement('Text', 'sesmusic_ffmpeg_path', array(
          'label' => 'Path to FFMPEG',
          'description' => 'Please enter the full path to your FFMPEG installation. (Environment variables are not present)',
          'value' => $settings->getSetting('video.ffmpeg.path', ''),
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
