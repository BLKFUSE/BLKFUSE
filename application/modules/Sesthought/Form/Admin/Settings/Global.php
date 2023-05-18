<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="http://www.socialenginesolutions.com" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesthought_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesthought.licensekey'),
    ));
    $this->getElement('sesthought_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
		if ($settings->getSetting('sesthought.pluginactivated')) {

      $this->addElement('Text', 'sesthought_thought_singular', array(
        'label' => 'Singular Text for "Thought"',
        'description' => 'Enter the text which you want to show in place of "Thought" at various places in this plugin like activity feeds, etc.',
        'value' => $settings->getSetting('sesthought.thought.singular', 'thought'),
      ));

      $this->addElement('Text', 'sesthought_thought_plural', array(
        'label' => 'Plural Text for "Thoughts"',
        'description' => 'Enter the text which you want to show in place of "Thoughts" at various places in this plugin like search form, navigation menu, etc.',
        'value' => $settings->getSetting('sesthought.thought.plural', 'thoughts'),
      ));

      $this->addElement('Text', 'sesthought_thought_manifest', array(
        'label' => 'Singular "thought" text in URL',
        'description' => 'Enter the text which you want to show in place of "thought" in the URLs of this plugin.',
        'value' => $settings->getSetting('sesthought.thought.manifest', 'thought'),
      ));

      $this->addElement('Text', 'sesthought_thoughts_manifest', array(
        'label' => 'Plural "thoughts" text in URL',
        'description' => 'Enter the text which you want to show in place of "thoughts" in the URLs of this plugin.',
        'value' => $settings->getSetting('sesthought.thoughts.manifest', 'thoughts'),
      ));

      $this->addElement('Text', 'sesthought_descriptionlimit', array(
        'label' => 'Thought Content Character Limit',
        'description' => 'Enter the thought content character limit for thoughts created on your website. Use 0 for unlimited. (This limit will apply while creating and editing new thoughts.)',
        'value' => $settings->getSetting('sesthought.descriptionlimit', 0),
      ));

      $this->addElement('Radio', 'sesthought_show', array(
        'label' => 'Show Thoughts',
        'description' => 'How do you want to show thought entries when someone clicks the title of the entry from Browse pages, widgets, feeds or other places your website?',
        'multiOptions' => array(
          1 => 'In Popups',
          0 => 'On View Page'
        ),
        'value' => $settings->getSetting('sesthought.show', 0),
      ));

      $this->addElement('Radio', 'sesthought_allowshare', array(
        'label' => 'Allow Sharing',
        'description' => 'Do you want to allow members to share thoughts posted on your site?',
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No'
        ),
        'value' => $settings->getSetting('sesthought.allowshare', 1),
      ));

      $this->addElement('Radio', 'sesthought_allowreport', array(
        'label' => 'Allow Reporting',
        'description' => 'Do you want to allow members to Report against thoughts posted on your site?',
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No'
        ),
        'value' => $settings->getSetting('sesthought.allowreport', 1),
      ));

      $this->addElement('Radio', 'sesthought_enablecategory', array(
        'label' => 'Enable Category',
        'description' => 'Do you want to enable categories for the thoughts posted on your site?',
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No'
        ),
        'onclick' => 'showCat(this.value)',
        'value' => $settings->getSetting('sesthought.enablecategory', 1),
      ));

      $this->addElement('Radio', 'sesthought_categoryrequried', array(
        'label' => 'Make Thought Categories Mandatory',
        'description' => 'Do you want to make category field mandatory when users create or edit their thoughts?',
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No'
        ),
        'value' => $settings->getSetting('sesthought.categoryrequried', 0),
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

      //no thought default photo
      if (engine_count($default_photos_main) > 0) {
        $default_photos = array_merge(array('application/modules/Sesthought/externals/images/thought-icon.png'=>''),$default_photos_main);
        $this->addElement('Select', 'sesthought_thought_no_photo', array(
            'label' => 'Default Photo for No Thought Tip',
            'description' => 'Choose a default photo for No thoughts tip on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change this default photo.]',
            'multiOptions' => $default_photos,
            'value' => $settings->getSetting('sesthought.thought.no.photo'),
        ));
      } else {
        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for no photo. Photo to be chosen for no photo should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the main photo. Please upload the Photo to be chosen for no photo from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manage</a>" section.') . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'sesthought_thought_no_photo', array(
            'label' => 'Thought Default No Thought Photo',
            'description' => $description,
        ));
      }
      $this->sesthought_thought_no_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));


      $this->addElement('Textarea', 'sesthought_iframely_disallow', array(
        'label' => 'Disallow these Sources',
        'description' => 'Enter the domains of the sites (separated by commas) that you do not want to allow for Video'
          . ' Source. Example: example1.com, example2.com. Note: We\'ve integrated Iframely API with this module. By '
          . 'default URLs that return a \'player\' are allowed, such as music based websites like Soundcloud. You can '
          . 'use this setting to control which sites should not be allowed in this section.',
        'filters' => array(
          'StringTrim',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.iframely.disallow', ''),
      ));
      $this->sesthought_iframely_disallow->getDecorator('Description')->setOption('escape', false);

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
