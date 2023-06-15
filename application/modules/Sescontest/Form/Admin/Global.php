<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sescontest_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sescontest.licensekey'),
    ));
    $this->getElement('sescontest_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    if ($settings->getSetting('sescontest.pluginactivated')) {
      if (!$settings->getSetting('sescontest.changelanding', 0)) {
        $this->addElement('Radio', 'sescontest_changelanding', array(
            'label' => 'Set Welcome Page as Landing Page',
            'description' => 'Do you want to set the Default Welcome Page of this plugin as Landing page of your website?  [This is a one time setting, so if you choose ‘Yes’ and save changes, then later you can manually make changes in the Landing page from Layout Editor. Back up page of your current landing page will get created with the name “LP backup from SES Contests”.]  ',
            'onclick' => 'confirmChangeLandingPage(this.value)',
            'multiOptions' => array(
                '1' => 'Yes',
                '0' => 'No',
            ),
            'value' => $settings->getSetting('sescontest.changelanding', 0),
        ));
      }
      $this->addElement('Radio', 'sescontest_check_welcome', array(
          'label' => 'Welcome Page Visibility',
          'description' => 'Who all users do you want to see this "Welcome Page"?',
          'multiOptions' => array(
              0 => 'Only logged in users',
              1 => 'Only non-logged in users',
              2 => 'Both, logged-in and non-logged in users',
          ),
          'value' => $settings->getSetting('sescontest.check.welcome', 2),
      ));
      $this->addElement('Radio', 'sescontest_enable_welcome', array(
          'label' => 'Contest Main Menu Redirection',
          'description' => 'Choose from below where do you want to redirect users when Contest Menu item is clicked in the Main Navigation Menu Bar.',
          'multiOptions' => array(
              1 => 'Contest Welcome Page',
              0 => 'Contest Home Page',
              2 => 'Contest Browse Page'
          ),
          'value' => $settings->getSetting('sescontest.enable.welcome', 1),
      ));
      $this->addElement('Text', 'sescontest_contests_manifest', array(
          'label' => 'Plural "contests" Text in URL',
          'description' => 'Enter the text which you want to show in place of "contests" in the URLs of this plugin.',
          'value' => $settings->getSetting('sescontest.contests.manifest', 'contests'),
      ));
      $this->addElement('Text', 'sescontest_contest_manifest', array(
          'label' => 'Singular "contest" Text in URL',
          'description' => 'Enter the text which you want to show in place of "contest" in the URLs of this plugin.',
          'value' => $settings->getSetting('sescontest.contest.manifest', 'contest'),
      ));
      $this->addElement('Text', 'sescontest_text_singular', array(
          'label' => 'Singular Text for "Contest"',
          'description' => 'Enter the text which you want to show in place of "Contest" at various places in this plugin like activity feeds, etc.',
          'value' => $settings->getSetting('sescontest.text.singular', 'contest'),
      ));
      $this->addElement('Text', 'sescontest_text_plural', array(
          'label' => 'Plural Text for "Contest"',
          'description' => 'Enter the text which you want to show in place of "Contests" at various places in this plugin like search form, navigation menu, etc.',
          'value' => $settings->getSetting('sescontest.text.plural', 'contests'),
      ));

      $this->addElement('Text', "sescontest_mainheight", array(
          'label' => 'Large Photo Height',
          'description' => 'Enter the maximum height of the large main photo (in pixels) for both contests and entries. [Note: This photo will be shown in the lightbox and on "Contest View Page". Also, this setting will apply on new uploaded photos.]',
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sescontest.mainheight', 1600),
      ));
      $this->addElement('Text', "sescontest_mainwidth", array(
          'label' => 'Large Photo Width',
          'description' => 'Enter the maximum width of the large main photo (in pixels) for both contests and entries. [Note: This photo will be shown in the lightbox and on "Contest View Page". Also, this setting will apply on new uploaded photos.]',
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sescontest.mainwidth', 1600),
      ));
      $this->addElement('Text', "sescontest_normalheight", array(
          'label' => 'Medium Photo Height',
          'description' => "Enter the maximum height of the medium photo (in pixels) for both contests and entries. [Note: This photo will be shown in the various widgets and pages. Also, this setting will apply on new uploaded photos.]",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sescontest.normalheight', 500),
      ));
      $this->addElement('Text', "sescontest_normalwidth", array(
          'label' => 'Medium Photo Width',
          'description' => "Enter the maximum width of the medium photo (in pixels) for both contests and entries. [Note: This photo will be shown in the various widgets and pages. Also, this setting will apply on new uploaded photos.]",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sescontest.normalwidth', 500),
      ));
      $this->addElement('Radio', "sescontest_other_modulecontests", array(
          'label' => 'Contests Created in Content Visibility',
          'description' => "Choose the visibility of the contests created in a content to only that content (module) or show in Home page, Browse page and other places of this plugin as well? (To enable users to create contests in a content or module, place the widget \"Content Profile Contests\" on the profile page of the desired content.)",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.other.modulecontests', 1),
      ));

      $this->addElement('Radio', "sescontest_vote_integrate", array(
          'label' => 'Integrate Vote & Like Buttons of Entry',
          'description' => "Do you want to integrate the Vote and Like buttons of an entry such that when a user will Vote for an entry, then user will automatically Like that entry and when user will Like entry, then that entry will also be voted?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.vote.integrate', 0),
      ));


      $this->addElement('Radio', "sescontest_allow_share", array(
          'label' => 'Allow to Share Contests',
          'description' => "Do you want to allow users to share contests of your website?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.allow.share', 1),
      ));
      $this->addElement('Radio', "sescontest_entry_allow_share", array(
          'label' => 'Allow to Share Entries',
          'description' => "Do you want to allow users to share contest entries of your website?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.entry.allow.share', 1),
      ));
      $this->addElement('Radio', "sescontest_allow_favourite", array(
          'label' => ' Allow to Favorite Contests',
          'description' => "Do you want to allow the members to add the contests to Favorites?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.allow.favourite', 1),
      ));
      $this->addElement('Radio', "sescontest_allow_follow", array(
          'label' => ' Allow to Follow Contests',
          'description' => "Do you want to allow the members to Follow the contests?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.allow.follow', 1),
      ));
      $this->addElement('Radio', "sescontest_entry_allow_favourite", array(
          'label' => ' Allow to Favourite Entries',
          'description' => "Do you want to allow the members to add the entries to Favorites?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.entry.allow.favourite', 1),
      ));
      $this->addElement('Radio', "sescontest_allow_report", array(
          'label' => ' Allow to Report',
          'description' => "Do you want to allow users to report against Contests and entries on your website?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sescontest.allow.report', 1),
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
      //no contest default photo
      if (engine_count($default_photos_main) > 0) {
        $default_photos = array_merge(array('application/modules/Sescontest/externals/images/contest-icon.png' => ''), $default_photos_main);
        $this->addElement('Select', 'sescontest_contest_no_photo', array(
            'label' => 'Default Photo for No Contest Tip',
            'description' => 'Choose a default photo for No contests tip on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change this default photo.]',
            'multiOptions' => $default_photos,
            'value' => $settings->getSetting('sescontest.contest.no.photo'),
        ));
      } else {
        $description = "<div class='tip'><span>" . 'There are currently no photos in the File & Media Manager. So, photo should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.' . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'sescontest_contest_no_photo', array(
            'label' => 'Contest Default No Contest Photo',
            'description' => $description,
        ));
      }
      $this->sescontest_contest_no_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Select', 'sescontest_taboptions', array(
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
          'value' => $settings->getSetting('sescontest.taboptions', 6),
      ));

      $this->addElement('Text', 'sescontest_ffmpeg_path', array(
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

      //Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      ));
    }
  }
}
