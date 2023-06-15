<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {

    parent::init();

    // My stuff
    $this->setTitle('Member Level Settings')
            ->setDescription('These settings are applied as per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level from below.');

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $class = 'contest_package';
    } else {
      $class = '';
    }

    // Element: view
    $this->addElement('Radio', 'view', array(
        'label' => 'Allow Viewing of Contests?',
        'description' => 'Do you want to let users to view contests? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
            2 => 'Yes, allow members to view all contests, even private ones.',
            1 => 'Yes, allow members to view their own contests.',
            0 => 'No, do not allow contests to be viewed.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
    ));
    if (!$this->isModerator()) {
      unset($this->view->options[2]);
    }

    if (!$this->isPublic()) {

      // Element: create
      $this->addElement('Radio', 'create', array(
          'label' => 'Allow Creation of Contests?',
          'description' => 'Do you want to allow users to create contests? If set to no, some other settings on this page may not apply. This is useful when you want certain levels only to be able to create contests.',
          'multiOptions' => array(
              1 => 'Yes, allow creation of contests.',
              0 => 'No, do not allow contests to be created.',
          ),
          'value' => 1,
      ));

      // Element: edit
      $this->addElement('Radio', 'edit', array(
          'label' => 'Allow Editing of Contests?',
          'description' => 'Do you want to let members edit and delete contests?',
          'multiOptions' => array(
              2 => "Yes, allow members to edit everyone's contests.",
              1 => "Yes, allow  members to edit their own contests.",
              0 => "No, do not allow contests to be edited.",
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->edit->options[2]);
      }

      // Element: delete
      $this->addElement('Radio', 'delete', array(
          'label' => 'Allow Deletion of Contests?',
          'description' => 'Do you want to let members delete contests? If set to no, some other settings on this page may not apply.',
          'multiOptions' => array(
              2 => 'Yes, allow members to delete all contests.',
              1 => 'Yes, allow members to delete their own contests.',
              0 => 'No, do not allow members to delete their contests.',
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->delete->options[2]);
      }

      // Element: comment
      $this->addElement('Radio', 'comment', array(
          'label' => 'Allow Commenting on Contests?',
          'description' => 'Do you want to let members of this level comment on contests?',
          'multiOptions' => array(
              2 => 'Yes, allow members to comment on all contests, including private ones.',
              1 => 'Yes, allow members to comment on contests.',
              0 => 'No, do not allow members to comment on contests.',
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->comment->options[2]);
      }
      // Element: auth_view
      $this->addElement('MultiCheckbox', 'auth_view', array(
          'label' => 'Contest View Privacy',
          'description' => 'Your users can choose any of the options from below, whom they want can see their contests. If you do not check any option, settings will set default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
          'multiOptions' => array(
              'everyone' => 'Everyone',
              'registered' => 'Registered Members',
              'owner_network' => 'Friends and Networks',
              'owner_member_member' => 'Friends of Friends',
              'owner_member' => 'Friends Only',
              'owner' => 'Just Me'
          )
      ));

      // Element: auth_comment
      $this->addElement('MultiCheckbox', 'auth_comment', array(
          'label' => 'Contest Comment Options',
          'description' => 'Your users can choose any of the options from below whom they want can post to their contest discussions. If you do not check any options, settings will set default to the last saved configuration. If you select only one option, members of this level will not have a choice.
',
          'multiOptions' => array(
              'everyone' => 'Everyone',
              'registered' => 'Registered Members',
              'owner_network' => 'Friends and Networks',
              'owner_member_member' => 'Friends of Friends',
              'owner_member' => 'Friends Only',
              'owner' => 'Just Me'
          )
      ));

      if ($class == "contest_package") {
        $this->addElement('Select', 'isPackageCont', array(
            'description' => 'Since, you have enabled Packages settings for Contest creation on your website, some settings are moved from Member Level to the packages. So, please configure them from Package Settings section.'
        ));
      }

      $this->addElement('Text', 'award_count', array(
          'label' => 'Number of Awards in Contests',
          'description' => 'Enter the number of awards that users of this level can declare in their contests. Maximum of 5 awards can only be declared in one contest.',
          'class' => $class,
          'validators' => array(
              array('Int', true),
              new Engine_Validate_AtLeast(1),
          ),
          'value' => 5,
      ));

      $this->addElement('Radio', 'upload_cover', array(
          'label' => 'Allow to Upload Contest Cover Photo',
          'description' => 'Do you want to allow the users of this member level to upload cover photo for their contests? If set to No, then the default cover photo will get displayed instead.',
          'class' => $class,
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 1,
      ));

      $this->addElement('Radio', 'upload_mainphoto', array(
          'label' => 'Allow to Upload Contest Main Photo',
          'description' => 'Do you want to allow the users of this member level to upload main photo for their contests? If set to No, then the default main photo will get displayed instead.',
          'class' => $class,
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 1,
      ));

      $this->addElement('Radio', 'auth_contstyle', array(
          'label' => 'Enable Contest Profile Designs Selection',
          'description' => 'Do you want to enable users of this level to choose designs for their Contest Profiles? (If you choose No, then you can choose a default layout for the Contest Profile pages on your website.)',
          'class' => $class,
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 1,
      ));

      $this->addElement('MultiCheckbox', 'chooselayout', array(
          'label' => 'Choose Contest Profile Design',
          'description' => 'Choose design for the contest profile pages which will be available to users while creating or editing their contests.',
          'class' => $class,
          'multiOptions' => array(
              1 => 'Template 1',
              2 => 'Template 2',
              3 => 'Template 3',
              4 => 'Template 4',
          ),
          'value' => array(1 => 'Template 1', 2 => 'Template 2', 3 => 'Template 3', 4 => 'Template 4'),
      ));

      // Element: auth_view
      $this->addElement('Radio', 'style', array(
          'label' => 'Default Contest Profile Design',
          'description' => 'Choose default design for the contest profile page of the contests created by users of this member level.',
          'class' => $class,
          'multiOptions' => array(
              '1' => 'Template 1',
              '2' => 'Template 2',
              '3' => 'Template 3',
              '4' => 'Template 4'
          ),
          'value' => 1,
      ));
      //New File System Code
      $banner_options = array('' => '');
      $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
      foreach( $files as $file ) {
        $banner_options[$file->storage_path] = $file->name;
      }
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $fileLink = $view->baseUrl() . '/admin/files/';
      //contest main photo
      if (engine_count($banner_options) > 0) {
        $this->addElement('Select', 'textContPhoto', array(
            'label' => 'Default Main Photo for Contests - Text Type',
            'description' => 'Choose main default photo for the contests of “Text” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'photoContPhoto', array(
            'label' => 'Default Main Photo for Contests - Photo Type',
            'description' => 'Choose main default photo for the contests of “Photo” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'musicContPhoto', array(
            'label' => 'Default Main Photo for Contests - Music Type',
            'description' => 'Choose main default photo for the contests of “Music” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'videoContPhoto', array(
            'label' => 'Default Main Photo for Contests - Video Type',
            'description' => 'Choose main default photo for the contests of “Video” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'textContCPhoto', array(
            'label' => 'Default Cover Photo for Contests - Text Type',
            'description' => 'Choose default cover photo for the contests of “Text” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'photoContCPhoto', array(
            'label' => 'Default Cover Photo for Contests - Photo Type',
            'description' => 'Choose default cover photo for the contests of “Photo” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'musicContCPhoto', array(
            'label' => 'Default Cover Photo for Contests - Music Type',
            'description' => 'Choose default cover photo for the contests of “Music” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
        $this->addElement('Select', 'videoContCPhoto', array(
            'label' => 'Default Cover Photo for Contests - Video Type',
            'description' => 'Choose default cover photo for the contests of “Video” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
            'multiOptions' => $banner_options,
            'class' => $class,
        ));
      } else {
        $description = "<div class='tip'><span>" . 'There are currently no photo in the File & Media Manager. Please upload the Photo to be chosen from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manage</a>" section.' . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'textContPhoto', array(
            'label' => 'Default Main Photo for Contests - Text Type',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'photoContPhoto', array(
            'label' => 'Default Main Photo for Contests - Photo Type',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'musicContPhoto', array(
            'label' => 'Default Main Photo for Contests - Music Type',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'videoContPhoto', array(
            'label' => 'Default Main Photo for Contests - Video Type',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'textContCPhoto', array(
            'label' => 'Choose default cover photo for the contests of “Text” type on your website.',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'photoContCPhoto', array(
            'label' => 'Choose default cover photo for the contests of “Photo” type on your website',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'musicContCPhoto', array(
            'label' => 'Choose default cover photo for the contests of “Music” type on your website',
            'description' => $description,
            'class' => $class,
        ));
        $this->addElement('Dummy', 'videoContCPhoto', array(
            'label' => 'Choose default cover photo for the contests of “Video” type on your website.',
            'description' => $description,
            'class' => $class,
        ));
      }
      $this->textContPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->photoContPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->musicContPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->videoContPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->textContCPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->photoContCPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->musicContCPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->videoContCPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->addElement('Radio', 'contest_approve', array(
          'description' => 'Do you want contests created by users of this level to be auto-approved?',
          'label' => 'Auto Approve Contests',
          'multiOptions' => array(
              1 => 'Yes, auto-approve contests.',
              0 => 'No, do not auto-approve contests.'
          ),
          'class' => $class,
          'value' => 1,
      ));
      //element for contest featured
      $this->addElement('Radio', 'contest_featured', array(
          'description' => 'Do you want contests created by users of this level to be automatically marked as Featured?',
          'label' => 'Automatically Mark Contests as Featured',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Featured',
              0 => 'No, do not automatically mark contests as Featured',
          ),
          'class' => $class,
          'value' => 0,
      ));
      //element for contest sponsored
      $this->addElement('Radio', 'autosponsored', array(
          'description' => '“Do you want contests created by users of this level to be automatically marked as Sponsored?”',
          'label' => 'Automatically Mark Contests as Sponsored',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Sponsored',
              0 => 'No, do not automatically mark contests as Sponsored',
          ),
          'class' => $class,
          'value' => 0,
      ));
      //element for contest verified
      $this->addElement('Radio', 'contest_verified', array(
          'description' => 'Do you want contests created by users of this level to be automatically marked as Verified?',
          'label' => 'Automatically Mark Contests as Verified',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Verified',
              0 => 'No, do not automatically mark contests as Verified',
          ),
          'class' => $class,
          'value' => 0,
      ));
      //element for contest verified
      $this->addElement('Radio', 'contest_hot', array(
          'description' => ' Do you want contests created by users of this level to be automatically marked as Hot?',
          'label' => 'Automatically Mark Contests as Hot',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Hot',
              0 => 'No, do not automatically mark contests as Hot',
          ),
          'class' => $class,
          'value' => 0,
      ));
      $this->addElement('Text', 'contest_count', array(
          'label' => 'Maximum allowed contests',
          'description' => 'Enter the maximum number of contests a user of this member level can create. The field must contain an integer, use zero for unlimited.',
          'validators' => array(
              array('Int', true),
              new Engine_Validate_AtLeast(0),
          ),
          'class' => $class,
          'value' => 0
      ));
      $this->addElement('Radio', 'contest_seo', array(
          'description' => 'Do you want to enable the “SEO” fields for the contests created by the users of this member level?',
          'label' => 'Enable SEO Fields',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'class' => $class,
          'value' => 1,
      ));
      $this->addElement('Radio', 'contest_overview', array(
          'description' => 'Do you want to enable the “Overview” for the contests created by the users of this member level?',
          'label' => 'Enable Overview',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'class' => $class,
          'value' => 1,
      ));
      $this->addElement('Radio', 'contest_bgphoto', array(
          'description' => 'Do you want to enable the “Background Photo” of the contests created by the users of this member level?',
          'label' => 'Enable Background Photo',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'class' => $class,
          'value' => 1,
      ));
      $this->addElement('Radio', 'contactinfo', array(
          'description' => 'Do you want to enable the “Contact Info” for the contests created by the users of this member level?',
          'label' => 'Enable Contact Info',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'class' => $class,
          'value' => 1,
      ));
      $this->addElement('Radio', 'contparticipant', array(
          'description' => 'Do you want to enable the ‘Contact All Participants“ for the contests created by the users of this member level?',
          'label' => 'Enable Contact All Participants',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'class' => $class,
          'value' => 1,
      ));
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember')) {
        $this->addElement('Radio', 'can_add_jury', array(
            'label' => 'Allow to Add Jury Members',
            'description' => 'Do you want to allow contest owners of this member level to add Jury Members to their contests? If you choose Yes, then contest owners will be able to choose from “Jury Members”, “Site members” or “Both” to vote for entries in their contests or not.',
            'class' => $class,
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => 1,
        ));
        $this->addElement('Text', 'juryMemberCount', array(
            'label' => 'Jury Member Count',
            'description' => 'Enter the number of Jury members to be added in the contests created by user of this member level.',
            'class' => $class,
            'validators' => array(
                array('Int', true),
                new Engine_Validate_AtLeast(1),
            ),
            'value' => 2,
        ));
      }
    }
  }

}
