<?php

class Sescontestpackage_Form_Admin_Package_Create extends Engine_Form {

  protected $_customFields;

  function getCustomFields($customFields) {
    return $this->_customFields;
  }

  public function setCustomFields($customFields) {
    return $this->_customFields = $customFields;
  }

  public function init() {
    $this
            ->setTitle('Create New Package')
            ->setDescription('Here, you can create a new package for contests on your website. Make sure to enter correct information in this package as it can be deleted until someone has not created any contest under this package. Also, once this package is created, only the fields Description, Member Levels, Custom Fields, Highlight & Show in Upgrade, can be edited.');
    // Element: title
    $this->addElement('Text', 'title', array(
        'label' => 'Package Title',
        'required' => true,
        'allowEmpty' => false,
        'filters' => array(
            'StringTrim',
        ),
    ));
    // Element: title
    $this->addElement('Text', 'item_count', array(
        'label' => 'Contests Count',
        'description' => 'Enter the maximum number of Contests a member can create in this package. The field must contain an integer, use zero for unlimited. ',
        'required' => true,
        'allowEmpty' => false,
        'filters' => array(
            'StringTrim',
        ),
        'validators' => array(
            new Engine_Validate_AtLeast(0),
        ),
        'value' => '0',
    ));
    // Element: description
    $this->addElement('Textarea', 'description', array(
        'label' => 'Package Description',
        'description' => 'Enter the description for this package. This will be shown in the listing of packages on your website.',
        'validators' => array(
            array('StringLength', true, array(0, 250)),
        )
    ));
    // Element: level_id
    foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
      if ($level->type == 'public') {
        continue;
      }
      $multiOptions[$level->getIdentity()] = $level->getTitle();
    }
    $multiOptions = array_merge(array('0' => 'All Levels'), $multiOptions);
    $this->addElement('Multiselect', 'member_level', array(
        'label' => 'Member Level',
        'description' => 'Only the selected member levels will be allowed to view and purchase this package for creating contests on your site.',
        'multiOptions' => $multiOptions,
        'value' => '0'
    ));
    // Element: price
    $this->addElement('Text', 'price', array(
        'label' => 'Price',
        'description' => 'Enter the amount to be charged from users for creating contests under this package. This amount will be charged only once for one-time plans and at each billing cycle for recurring plans. Use ‘0’ to make this package Free.',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
            array('Float', true),
            new Engine_Validate_AtLeast(0),
        ),
        'value' => '0.00',
    ));
    // Element: recurrence
    $this->addElement('Duration', 'recurrence', array(
        'label' => 'Billing Cycle',
        'description' => 'How often should members in this package be billed?',
        'required' => true,
        'allowEmpty' => false,
        'value' => array(1, 'month'),
    ));
    // Element: duration
    $this->addElement('Duration', 'duration', array(
        'label' => 'Billing Duration',
        'description' => 'When should this package expire? For one-time package, the package will expire after the period of time set here. For recurring plans, the user will be billed at the above billing cycle for the period of time specified here.',
        'required' => true,
        'allowEmpty' => false,
        'value' => array('0', 'forever'),
    ));
    // renew
    $this->addElement('Select', 'is_renew_link', array(
        'description' => 'Renew Link',
        'label' => 'Want to show reniew link',
        'value' => 0,
        'multiOptions' => array('1' => 'Yes, show reniew link', '0' => 'No, don\'t show renew link'),
        'onchange' => 'showRenewData(this.value);',
    ));
    $this->addElement('Text', 'renew_link_days', array(
        'label' => 'Days before show renew link',
        'description' => 'Show renewal link before how many days before expiry.',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
            array('Int', true),
            new Engine_Validate_AtLeast(0),
        ),
        'value' => '0',
    ));

    $this->addElement('Text', 'award_count', array(
        'label' => 'Number of Awards in Contests',
        'description' => 'Enter the number of awards that users of this level can declare in their contests. Maximum of 5 awards can only be declared in one contest.',
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
            array('LessThan', true, array(6)),
        ),
        'value' => 5,
    ));

    $this->addElement('Radio', 'upload_cover', array(
        'label' => 'Allow to Upload Contest Cover Photo',
        'description' => 'Do you want to allow the users to upload cover photo for their contests created under this package? If set to No, then the default cover photo will get displayed instead.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));

    $this->addElement('Radio', 'upload_mainphoto', array(
        'label' => 'Allow to Upload Contest Main Photo',
        'description' => 'Do you want to allow the users to upload main photo for their contests created under this package? If set to No, then the default main photo will get displayed instead.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));

    $this->addElement('Radio', 'contest_choose_style', array(
        'label' => 'Enable Contest Profile Designs Selection',
        'description' => 'Do you want to enable users to choose designs for their Contest Profiles for contests created under this package? (If you choose No, then you can choose a default layout for the Contest Profile pages on your website.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));

    $this->addElement('MultiCheckbox', 'contest_chooselayout', array(
        'label' => 'Choose Contest Profile Design',
        'description' => 'Choose design for the contest profile pages which will be available to users while creating or editing their contests.',
        'multiOptions' => array(
            1 => 'Template 1',
            2 => 'Template 2',
            3 => 'Template 3',
            4 => 'Template 4',
        ),
        'value' => array('1', '2', '3', '4'),
    ));

    // Element: auth_view
    $this->addElement('Radio', 'contest_style_type', array(
        'label' => 'Default Contest Profile Design',
        'description' => 'Choose default design for the contest profile page of the contests created under this package.',
        'multiOptions' => array(
            '1' => 'Template 1',
            '2' => 'Template 2',
            '3' => 'Template 3',
            '4' => 'Template 4'
        )
    ));
    //New File System Code
    $default_photos = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
      $default_photos[$file->storage_path] = $file->name;
    }
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';
    //contest main photo
    if (engine_count($default_photos) > 0) {
      $this->addElement('Select', 'sescontest_contest_text_photo', array(
          'label' => 'Default Main Photo for Contests - Text Type',
          'description' => 'Choose main default photo for the contests of “Text” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_photo_photo', array(
          'label' => 'Default Main Photo for Contests - Photo Type',
          'description' => 'Choose main default photo for the contests of “Photo” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_music_photo', array(
          'label' => 'Default Main Photo for Contests - Music Type',
          'description' => 'Choose main default photo for the contests of “Music” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_video_photo', array(
          'label' => 'Default Main Photo for Contests - Video Type',
          'description' => 'Choose main default photo for the contests of “Video” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_text_coverphoto', array(
          'label' => 'Default Cover Photo for Contests - Text Type',
          'description' => 'Choose default cover photo for the contests of “Text” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_photo_coverphoto', array(
          'label' => 'Default Cover Photo for Contests - Photo Type',
          'description' => 'Choose default cover photo for the contests of “Photo” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_music_coverphoto', array(
          'label' => 'Default Cover Photo for Contests - Music Type',
          'description' => 'Choose default cover photo for the contests of “Music” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
      $this->addElement('Select', 'sescontest_contest_video_coverphoto', array(
          'label' => 'Default Cover Photo for Contests - Video Type',
          'description' => 'Choose default cover photo for the contests of “Video” type on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest default photo.]',
          'multiOptions' => $default_photos,
      ));
    } else {
      $description = "<div class='tip'><span>" . 'There are currently no photo in the File & Media Manager. Please upload the Photo to be chosen from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manage</a>" section.' . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'sescontest_contest_text_photo', array(
          'label' => 'Default Main Photo for Contests - Text Type',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_photo_photo', array(
          'label' => 'Default Main Photo for Contests - Photo Type',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_music_photo', array(
          'label' => 'Default Main Photo for Contests - Music Type',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_video_photo', array(
          'label' => 'Default Main Photo for Contests - Video Type',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_text_coverphoto', array(
          'label' => 'Choose default cover photo for the contests of “Text” type on your website.',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_photo_coverphoto', array(
          'label' => 'Choose default cover photo for the contests of “Photo” type on your website',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_music_coverphoto', array(
          'label' => 'Choose default cover photo for the contests of “Music” type on your website',
          'description' => $description,
      ));
      $this->addElement('Dummy', 'sescontest_contest_video_coverphoto', array(
          'label' => 'Choose default cover photo for the contests of “Video” type on your website.',
          'description' => $description,
      ));
    }
    $this->sescontest_contest_text_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_photo_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_music_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_video_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_text_coverphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_photo_coverphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_music_coverphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->sescontest_contest_video_coverphoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    $this->addElement('Radio', 'contest_approve', array(
        'description' => 'Do you want contests created under this package to be auto-approved?',
        'label' => 'Auto Approve Contests',
        'multiOptions' => array(
            1 => 'Yes, auto-approve contests.',
            0 => 'No, do not auto-approve contests.'
        ),
        'value' => 1,
    ));

    $information = array('featured' => 'Featured', 'sponsored' => 'Sponsored', 'verified' => 'Verified', 'hot' => 'Hot', 'custom_fields' => 'Custom Fields');
    $showinfo = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.package.info', array_keys($information));
    if (engine_in_array('featured', $showinfo)) {
      //element for contest featured
      $this->addElement('Radio', 'contest_featured', array(
          'description' => 'Do you want contests created under this package to be automatically marked as Featured?',
          'label' => 'Automatically Mark Contests as Featured',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Featured',
              0 => 'No, do not automatically mark contests as Featured',
          ),
          'value' => 0,
      ));
    }
    if (engine_in_array('sponsored', $showinfo)) {
      //element for contest sponsored
      $this->addElement('Radio', 'contest_sponsored', array(
          'description' => '“Do you want contests created under this package to be automatically marked as Sponsored?”',
          'label' => 'Automatically Mark Contests as Sponsored',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Sponsored',
              0 => 'No, do not automatically mark contests as Sponsored',
          ),
          'value' => 0,
      ));
    }
    if (engine_in_array('verified', $showinfo)) {
      //element for contest verified
      $this->addElement('Radio', 'contest_verified', array(
          'description' => 'Do you want contests created under this package to be automatically marked as Verified?',
          'label' => 'Automatically Mark Contests as Verified',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Verified',
              0 => 'No, do not automatically mark contests as Verified',
          ),
          'value' => 0,
      ));
    }
    if (engine_in_array('hot', $showinfo)) {
      //element for contest verified
      $this->addElement('Radio', 'contest_hot', array(
          'description' => ' Do you want contests created under this package to be automatically marked as Hot?',
          'label' => 'Automatically Mark Contests as Hot',
          'multiOptions' => array(
              1 => 'Yes, automatically mark contests as Hot',
              0 => 'No, do not automatically mark contests as Hot',
          ),
          'value' => 0,
      ));
    }
    $this->addElement('Radio', 'contest_seo', array(
        'description' => 'Do you want to enable the “SEO” fields for the contests created created under this package?',
        'label' => 'Enable SEO Fields',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));
    $this->addElement('Radio', 'contest_overview', array(
        'description' => 'Do you want to enable the “Overview” for the contests created under this package?',
        'label' => 'Enable Overview',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));
    $this->addElement('Radio', 'contest_bgphoto', array(
        'description' => 'Do you want to enable the “Background Photo” of the contests created under this package?',
        'label' => 'Enable Background Photo',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));
    $this->addElement('Radio', 'contest_contactinfo', array(
        'description' => 'Do you want to enable the “Contact Info” for the contests created under this package?',
        'label' => 'Enable Contact Info',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));
    $this->addElement('Radio', 'contest_enable_contactparticipant', array(
        'description' => 'Do you want to enable the ‘Contact All Participants“ for the contests created under this package?',
        'label' => 'Enable Contact All Participants',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
    ));
    if (engine_in_array('custom_fields', $showinfo)) {
      $this->addElement('Radio', 'custom_fields', array(
          'label' => 'Allow Custom Fields',
          'description' => 'Do you want to allow users to fill custom fields in the contests created under this Package?',
          'value' => 0,
          'onclick' => 'customField(this.value)',
          'multiOptions' => array(
              1 => 'Yes, allow to fill all available custom fields.',
              0 => 'No, do not allow to fill custom fields.',
              2 => 'Yes, allow to fill selected custom fields only.',
          ),
          'value' => 1
      ));
      $this->addElement('Dummy', 'customfields', array(
          'ignore' => true,
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => '_customFields.tpl',
                      'class' => 'form element',
                      'customFields' => $this->_customFields,
                  )))
      ));
    }

    //entry fees setting
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjoinfees') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.allow.entryfees', 0)) {
      //commission 
      $this->addElement('Select', 'sescontest_admin_commission', array(
          'label' => 'Unit for Commission',
          'description' => 'Choose the unit for admin commission which you will get on the contest fees.',
          'multiOptions' => array(
              1 => 'Percentage',
              2 => 'Fixed'
          ),
          'allowEmpty' => false,
          'required' => true,
          'value' => 1,
      ));
      $this->addElement('Text', "sescontest_commission_value", array(
          'label' => 'Commission Value',
          'description' => "Enter the value for commission according to the unit chosen in above setting. [If you have chosen Percentage, then value should be in range 1 to 100.]",
          'allowEmpty' => true,
          'required' => false,
          'value' => 0,
      ));
      $this->addElement('Text', "sescontest_threshold_amount", array(
          'label' => 'Threshold Amount for Releasing Payment',
          'description' => "Enter the threshold amount which will be required before making request for releasing payment from admins. [Note: Threshold Amount is remaining amount which the owner of the contest will get after subtracting the admin commission from the total amount received.]",
          'allowEmpty' => false,
          'required' => true,
          'value' => 100,
      ));
    }
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember')) {
      $this->addElement('Radio', 'can_add_jury', array(
          'label' => 'Allow to Add Jury Members',
          'description' => 'Do you want to allow contest owners of this member level to add Jury Members to their contests? If you choose Yes, then contest owners will be able to choose from “Jury Members”, “Site members” or “Both” to vote for entries in their contests or not.',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 1,
      ));
      $this->addElement('Text', 'jury_member_count', array(
          'label' => 'Jury Member Count',
          'description' => 'Enter the number of Jury members to be added in the contests created by user of this member level.',
          'validators' => array(
              array('Int', true),
              new Engine_Validate_AtLeast(1),
          ),
          'value' => 2,
      ));
    }

    // Element: highlight
    $this->addElement('Select', 'highlight', array(
        'label' => 'Highlight Package',
        'description' => 'Do you want to highlight this package among all other packages on your website. If you choose yes, then this package will be shown with some highlight effect to users.',
        'multiOptions' => array(
            '1' => 'Yes, want to highlight this package.',
            '0' => 'No, don\'t want to highlight this package.',
        ),
        'value' => 0,
    ));
    // Element: highlight
    $this->addElement('Select', 'show_upgrade', array(
        'label' => 'Show In Upgrade?',
        'description' => 'Do you want to show this package in Upgrade Package section in the dashboard of the contests? This package will show only for the contests which are created under the package of lower price than this package.',
        'multiOptions' => array(
            '1' => 'Yes, want to show this package in upgrade section.',
            '0' => 'No, don\'t want to show this package in upgrade section.',
        ),
        'value' => 0,
    ));
    // Element: enabled
    $this->addElement('Select', 'enabled', array(
        'label' => 'Enabled?',
        'description' => 'Do you want to enable this package? (The existing members that were in that plan would stay in that plan until they pick another plan.)',
        'multiOptions' => array(
            '1' => 'Yes, users may select this plan.',
            '0' => 'No, users may not select this plan.',
        ),
        'value' => 1,
    ));
    // Element: execute
    $this->addElement('Button', 'execute', array(
        'label' => 'Create Package',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
    ));
    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'prependText' => ' or ',
        'ignore' => true,
        'link' => true,
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'index')),
        'decorators' => array('ViewHelper'),
    ));
    // DisplayGroup: buttons
    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        )
    ));
  }

}
