<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EntryLevel.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_Settings_EntryLevel extends Authorization_Form_Admin_Level_Abstract {

  public function init() {

    parent::init();
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $fileLink = $view->baseUrl() . '/admin/files/';

    // My stuff
    $this->setTitle('Member Level Settings')
            ->setDescription('These settings are applied as per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level from below.');
    if (!$this->isPublic()) {
      $this->addElement('Radio', 'editentry', array(
          'label' => 'Allow Editing of Entries?',
          'description' => 'Do you want to allow members to edit entries?',
          'multiOptions' => array(
              2 => "Yes, allow members to edit everyone's entry.",
              1 => "Yes, allow  members to edit their own entries.",
              0 => "No, do not allow entries  to be edited.",
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->editentry->options[2]);
      }
      $this->addElement('Radio', 'deleteentry', array(
          'label' => 'Allow Deletion of entries?',
          'description' => 'Do you want to let members delete entries? If set to no, some other settings on this page may not apply.',
          'multiOptions' => array(
              2 => 'Yes, allow members to delete all entries.',
              1 => 'Yes, allow members to delete their own entries.',
              0 => 'No, do not allow members to delete their entries.',
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->deleteentry->options[2]);
      }

      // Element: comment
      $this->addElement('Radio', 'comment', array(
          'label' => 'Allow Commenting on entries?',
          'description' => 'Do you want to let members of this level comment on entries?',
          'multiOptions' => array(
              2 => 'Yes, allow members to comment on all contests, including private ones.',
              1 => 'Yes, allow members to comment on entries.',
              0 => 'No, do not allow members to comment on entries.',
          ),
          'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if (!$this->isModerator()) {
        unset($this->comment->options[2]);
      }

      $this->addElement('Radio', 'auth_participant', array(
          'label' => 'Allow Participation in Contest?',
          'description' => 'Do you want to allow users to participate in contest? This is useful when you want certain levels only to be able to participate in contests.',
          'multiOptions' => array(
              1 => 'Yes, allow participation in contests.',
              0 => 'No, do not allow participation in contests.',
          ),
          'value' => 1,
      ));

      //contest main photo
      if (engine_count($banner_options) > 0) {
        $this->addElement('Select', 'textEntryPhoto', array(
            'label' => 'Default Photo for Entries  - Text Type',
            'description' => 'Choose main default photo for the entries of “Text” type contests on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest entry default photo.]',
            'multiOptions' => $banner_options,
        ));
        $this->addElement('Select', 'photoEntryPhoto', array(
            'label' => 'Default Photo for Entries  - Photo Type',
            'description' => 'Choose Main default photo for the entries of “Music” type contests on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest entry default photo.]',
            'multiOptions' => $banner_options,
        ));
        $this->addElement('Select', 'musicEntryPhoto', array(
            'label' => 'Default Photo for Entries  - Music Type',
            'description' => 'Choose Main default photo for the entries of “Music” type contests on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest entry default photo.]',
            'multiOptions' => $banner_options,
        ));
        $this->addElement('Select', 'videoEntryPhoto', array(
            'label' => 'Default Photo for Entries  - Video Type',
            'description' => 'Choose Main default photo for the entries of “Video” type contests on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change contest entry default photo.]',
            'multiOptions' => $banner_options,
        ));
      } else {
        $description = "<div class='tip'><span>" . 'There are currently no photo in the File & Media Manager. Please upload the Photo to be chosen from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manage</a>" section.' . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'textEntryPhoto', array(
            'label' => 'Default Photo for Entries - Text Type',
            'description' => $description,
        ));
        $this->addElement('Dummy', 'photoEntryPhoto', array(
            'label' => 'Default Photo for Entries - Photo Type',
            'description' => $description,
        ));
        $this->addElement('Dummy', 'musicEntryPhoto', array(
            'label' => 'Default Photo for Entries - Music Type',
            'description' => $description,
        ));
        $this->addElement('Dummy', 'videoEntryPhoto', array(
            'label' => 'Default Photo for Entries - Video Type',
            'description' => $description,
        ));
      }
      $this->textEntryPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->photoEntryPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->musicEntryPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->videoEntryPhoto->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $voteSettingOptions = array("0" => "No, do not allow to vote for entries.", "1" => "Yes, allow to vote for entries.", "2" => "Yes, allow to vote for all entries including their own entries");
      $this->addElement('MultiCheckbox', 'blog_options', array(
          'label' => 'Option to Upload Blog/Text',
          'description' => 'Choose the options you want to give to users of this level for uploading blog/text during entry submission in a “Text” type contest.',
          'multiOptions' => array(
              'write' => 'Write',
              'linkblog' => 'Link My Blog (Dependent on "Advanced Blogs Plugin")',
          )
      ));
      $this->addElement('MultiCheckbox', 'photo_options', array(
          'label' => 'Option to Upload Photo',
          'description' => 'Choose the options you want to give to users of this level for uploading photo during entry submission in a “Photo” type contest.',
          'multiOptions' => array(
              'capture' => 'Capture',
              'uploadphoto' => 'Upload Photo',
              'url' => 'Using URL',
              'linkphoto' => 'Link My Photo (Dependent on "Advanced Photos & Albums Plugin")',
          )
      ));
      $this->addElement('MultiCheckbox', 'video_options', array(
          'label' => 'Option to Upload Video',
          'description' => 'Choose the options you want to give users of this level for uploading video during entry submission in a “Video” type contest.',
          'multiOptions' => array(
              'uploadvideo' => 'Upload',
              'record' => 'Record Video',
              'linkvideo' => 'Link My Video (Dependent on "Advanced Videos & Channels Plugin")',
          )
      ));
      $this->addElement('MultiCheckbox', 'music_options', array(
          'label' => 'Option to Upload Music',
          'description' => 'Choose the options you want to give users of this level for uploading music during entry submission in a “Music” type contest.',
          'multiOptions' => array(
              'uploadmusic' => 'Upload',
              'record' => 'Record Music',
              'linkmusic' => 'Link My Music (Dependent on "Advanced Music Albums, Songs & Playlists")',
          )
      ));
    } else {
      $voteSettingOptions = array("0" => "No, do not allow to vote for entries.", "1" => "Yes, allow to vote for entries.");
    }
    $this->addElement('Radio', 'allow_entry_vote', array(
        'label' => 'Allow Voting on Entries?',
        'description' => 'Do you want to allow users of this level to vote for the entries in contest? This is useful when you want certain levels only to be able to vote for entries in contests.',
        'multiOptions' => $voteSettingOptions,
        'value' => 1,
    ));
    $this->addElement('Radio', 'canEntryMultvote', array(
        'label' => 'Allow Multiple Votes on One Entry?',
        'description' => 'Do you want to allow a user to vote for one entry multiple times? (This setting will work for the votes made by both logged in members and non-logged in users on your website.)
',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => 1,
        'onclick' => 'showIntervalTime(this.value)',
    ));
    $this->addElement('Text', 'voteInterval', array(
        'label' => 'Voting Time Interval (In Minutes)',
        'description' => 'Enter the time after which user can again vote for the same entry.',
        'validators' => array(
            array('Int', true),
            new Engine_Validate_AtLeast(10),
        ),
        'value' => 10,
    ));
    if (!$this->isPublic()) {
      $this->addElement('Text', 'votecount_weight', array(
          'label' => 'Vote Count Weightage',
          'description' => 'Enter the weight of vote count when the vote is made by the member of this level. For example, if you enter 2 below, then when a member of this level votes on an entry, then the votes count increases by 2 instead of 1.',
          'validators' => array(
              array('Int', true),
              new Engine_Validate_AtLeast(1),
          ),
          'value' => 1,
      ));
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember')) {
        $this->addElement('Text', 'juryVoteWeight', array(
            'label' => 'Jury Vote Count Weightage',
            'description' => 'Enter the weight of vote count when the vote is made by the Jury Member of this level. For example, if you enter 2 below, then when a member of this level is made a Jury member in a contest and this member cast a vote, then the total votes count will increase by 2 instead of 1.',
            'validators' => array(
                array('Int', true),
                new Engine_Validate_AtLeast(1),
            ),
            'value' => 1,
        ));
      }
    }
  }

}
