<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Form_Admin_Global extends Engine_Form {

  public function init() {

    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesnews_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesnews.licensekey'),
    ));
    $this->getElement('sesnews_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
  
    if ($settings->getSetting('sesnews.pluginactivated')) {

      if (!$settings->getSetting('sesnews.changelanding', 0)) {
        $this->addElement('Radio', 'sesnews_changelanding', array(
            'label' => 'Set Welcome Page as Landing Page',
            'description' => 'Do you want to set the Default Welcome Page of this plugin as Landing page of your website? [This is a one time setting, so if you choose ‘Yes’ and save changes, then later you can manually make changes in the Landing page from Layout Editor.]',
            'multiOptions' => array(
                '1' => 'Yes',
                '0' => 'No',
            ),
            'value' => $settings->getSetting('sesnews.changelanding', 0),
        ));
      }

      $this->addElement('Text', 'sesnews_text_singular', array(
          'label' => 'Text for "News"',
          'description' => 'Enter the text which you want to show in place of "News" at various places in this plugin like activity feeds, etc.',
          'value' => $settings->getSetting('sesnews.text.singular', 'news'),
      ));

//       $this->addElement('Text', 'sesnews_text_plural', array(
//           'label' => 'Plural Text for "News"',
//           'description' => 'Enter the text which you want to show in place of "News" at various places in this plugin like search form, navigation menu, etc.',
//           'value' => $settings->getSetting('sesnews.text.plural', 'news'),
//       ));

      $this->addElement('Text', 'sesnews_news_manifest', array(
          'label' => '"news" Text in URL',
          'description' => 'Enter the text which you want to show in place of "news" in the URLs of this plugin.',
          'value' => $settings->getSetting('sesnews.news.manifest', 'news'),
      ));

      $this->addElement('Radio', 'sesnews_check_welcome', array(
          'label' => 'Welcome Page Visibility',
          'description' => 'Choose from below the users who will see the Welcome page of this plugin?',
          'multiOptions' => array(
              0 => 'Only logged in users',
              1 => 'Only non-logged in users',
              2 => 'Both, logged-in and non-logged in users',
          ),
          'value' => $settings->getSetting('sesnews.check.welcome', 2),
      ));

      $this->addElement('Radio', 'sesnews_enable_welcome', array(
          'label' => 'News Main Menu Redirection',
          'description' => 'Choose from below where do you want to redirect users when News Menu item is clicked in the Main Navigation Menu Bar.',
          'multiOptions' => array(
              1 => 'News Welcome Page',
              0 => 'News Home Page',
              2 => 'News Browse Page',
          ),
          'value' => $settings->getSetting('sesnews.enable.welcome', 1),
      ));
      $this->addElement('Radio', 'sesnews_redirect_creation', array(
          'label' => 'Redirection After News Creation',
          'description' => 'Choose from below where do you want to redirect users after a news is successfully created.',
          'multiOptions' => array('1' => 'On News Dashboard Page', '0' => 'On News Profile Page'),
          'value' => $settings->getSetting('sesnews.redirect.creation', 0),
      ));



      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

      $this->addElement('Text', "sesnews_mainheight", array(
          'label' => 'Large Photo Height',
          'description' => 'Enter the maximum height of the large main photo (in pixels). [Note: This photo will be shown in the lightbox and on "News Photo View Page". Also, this setting will apply on new uploaded photos.]',
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesnews.mainheight', 1600),
      ));
      $this->addElement('Text', "sesnews_mainwidth", array(
          'label' => 'Large Photo Width',
          'description' => 'Enter the maximum width of the large main photo (in pixels). [Note: This photo will be shown in the lightbox and on "News Photo View Page". Also, this setting will apply on new uploaded photos.]',
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesnews.mainwidth', 1600),
      ));
      $this->addElement('Text', "sesnews_normalheight", array(
          'label' => 'Medium Photo Height',
          'description' => "Enter the maximum height of the medium photo (in pixels). [Note: This photo will be shown in the various widgets and pages. Also, this setting will apply on new uploaded photos.]",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesnews.normalheight', 500),
      ));
      $this->addElement('Text', "sesnews_normalwidth", array(
          'label' => 'Medium Photo Width',
          'description' => "Enter the maximum width of the medium photo (in pixels). [Note: This photo will be shown in the various widgets and pages. Also, this setting will apply on new uploaded photos.]",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesnews.normalwidth', 500),
      ));

//       $this->addElement('Radio', "sesnews_other_modulenews", array(
//           'label' => 'News Created in Content Visibility',
//           'description' => "Choose the visibility of the news created in a content to only that content (module) or show in Home page, Browse page and other places of this plugin as well? (To enable users to create news in a content or module, place the widget \"Content Profile News\" on the profile page of the desired content.)",
//           'multiOptions' => array(
//               '1' => 'Yes',
//               '0' => 'No',
//           ),
//           'value' => $settings->getSetting('sesnews.other.modulenews', 1),
//       ));

      //default photos
      //New File System Code
      $default_photos_main = array();
      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
      foreach( $files as $file ) {
        $default_photos_main[$file->storage_path] = $file->name;
      }
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $fileLink = $view->baseUrl() . '/admin/files/';
      //news main photo
      if (engine_count($default_photos_main) > 0) {
        $default_photos = array_merge(array('application/modules/Sesnews/externals/images/nophoto_news_thumb_profile.png' => ''), $default_photos_main);
        $this->addElement('Select', 'sesnews_news_default_photo', array(
            'label' => 'Main Default Photo for News',
            'description' => 'Choose Main default photo for the news on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change news default photo.]',
            'multiOptions' => $default_photos,
            'value' => $settings->getSetting('sesnews.news.default.photo'),
        ));
      } else {
        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo in the File & Media Manager for the main photo. Please upload the Photo to be chosen for main photo from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'sesnews_news_default_photo', array(
            'label' => 'Main Default Photo for News',
            'description' => $description,
        ));
      }
      $this->sesnews_news_default_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Radio', 'sesnews_enable_location', array(
          'label' => 'Enable Location',
          'description' => 'Do you want to enable location for news on your website?',
          'multiOptions' => array(
              '1' => 'Yes,Enable Location',
              '0' => 'No,Don\'t Enable Location',
          ),
          'onclick' => 'showSearchType(this.value)',
          'value' => $settings->getSetting('sesnews.enable.location', 1),
      ));

      $this->addElement('Radio', 'sesnews_search_type', array(
          'label' => 'Proximity Search Unit',
          'description' => 'Choose the unit for proximity search of location of news on your website.',
          'multiOptions' => array(
              1 => 'Miles',
              0 => 'Kilometers'
          ),
          'value' => $settings->getSetting('sesnews.search.type', 1),
      ));

      $this->addElement('Radio', 'sesnews_start_date', array(
          'label' => 'Enable Custom News Publish Date',
          'description' => 'Do you want to allow users to choose a custom publish date for their news. If you choose Yes, then news on your website will display in activity feeds, various pages and widgets on their publish dates.',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => $settings->getSetting('sesnews.start.date', 1),
      ));
        $this->addElement('Radio', 'sesnews_login_continuereading', array(
            'label' => 'Continue Reading Button Redirection for Non-logged in Users',
            'description' => 'Do you want to redirect non-logged in users to the login page of your website when they click on "Continue Reading" button on News view pages? If you choose No, then users can see Full News at the same page.',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => $settings->getSetting('sesnews.login.continuereading', 1),
        ));

      $this->addElement('Radio', 'sesnews_category_enable', array(
          'label' => 'Make News Categories Mandatory',
          'description' => 'Do you want to make category field mandatory when users create or edit their news?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesnews.category.enable', 1),
      ));
      $this->addElement('Radio', 'sesnews_description_mandatory', array(
          'label' => 'Make News Description Mandatory',
          'description' => 'Do you want to make description field mandatory when users create or edit their news?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesnews.description.mandatory', 1),
      ));
      $this->addElement('Radio', 'sesnews_photo_mandatory', array(
          'label' => 'Make News Main Photo Mandatory',
          'description' => 'Do you want to make main photo field mandatory when users create or edit their news?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesnews.photo.mandatory', 1),
      ));

      $this->addElement('Radio', 'sesnews_enable_favourite', array(
          'label' => 'Allow to Favourite News',
          'description' => 'Do you want to allow users to favourite news on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sesnews.enable.favourite', 1),
      ));

      $this->addElement('Radio', 'sesnews_enable_bounce', array(
          'label' => 'Allow to Bounce Marker for Sponsored News',
          'description' => 'Do you want to allow marker to bounce for sponsored news on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sesnews.enable.bounce', 1),
      ));

      $this->addElement('Radio', 'sesnews_enable_report', array(
          'label' => 'Allow to Report News',
          'description' => 'Do you want to allow users to report news on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sesnews.enable.report', 1),
      ));

      $this->addElement('Radio', 'sesnews_enable_sharing', array(
          'label' => 'Allow to Share News',
          'description' => 'Do you want to allow users to share news on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => $settings->getSetting('sesnews.enable.sharing', 1),
      ));

      $this->addElement('Select', 'sesnews_taboptions', array(
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
          'value' => $settings->getSetting('sesnews.taboptions', 6),
      ));

      $this->addElement('Select', 'sesnews_enablenewsdesignview', array(
          'label' => 'Enable News Profile Views',
          'description' => 'Do you want to enable users to choose views for their News? (If you choose No, then you can choose a default layout for the News Profile pages on your website.)',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'onchange' => "enablenewsdesignview(this.value)",
          'value' => $settings->getSetting('sesnews.enablenewsdesignview', 0),
      ));

      $chooselayout = $settings->getSetting('sesnews.chooselayout', 'a:4:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";}');
      $chooselayoutVal = unserialize($chooselayout);
;
      $this->addElement('MultiCheckbox', 'sesnews_chooselayout', array(
          'label' => 'Choose News Profile Pages',
          'description' => 'Choose layout for the news profile pages which will be available to users while creating or editing their news.',
          'multiOptions' => array(
              1 => 'Design 1',
              2 => 'Design 2',
              3 => 'Design 3',
              4 => 'Design 4',
          ),
          'value' => $chooselayoutVal,
      ));

      $this->addElement('Radio', 'sesnews_defaultlayout', array(
          'label' => 'Default News Profile Page',
          'description' => 'Choose default layout for the news profile pages.',
          'multiOptions' => array(
              1 => 'Design 1',
              2 => 'Design 2',
              3 => 'Design 3',
              4 => 'Design 4',
          ),
          'value' => $settings->getSetting('sesnews.defaultlayout', 1),
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
          'label' => 'Activate Your Plugin',
          'type' => 'submit',
          'ignore' => true
      ));
    }
  }

}
