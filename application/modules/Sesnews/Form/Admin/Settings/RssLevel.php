<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: RssLevel.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Admin_Settings_RssLevel extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
    parent::init();

    // My stuff
    $this
      ->setTitle('Member Level Settings')
      ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

    // Element: view
    $this->addElement('Radio', 'view', array(
      'label' => 'Allow Viewing of RSS?',
      'description' => 'Do you want to let members view rss? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        2 => 'Yes, allow members to view all rss, even private ones.',
        1 => 'Yes, allow members to view their own rss.',
        0 => 'No, do not allow rss to be viewed.',
      ),
      'value' => ( $this->isModerator() ? 2 : 1 ),
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }

    if( !$this->isPublic() ) {

      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of RSS?',
        'description' => 'Do you want to let members create rss? If set to no, some other settings on this page may not apply. This is useful if you want members to be able to view rss, but only want certain levels to be able to create rss.',
        'multiOptions' => array(
          1 => 'Yes, allow creation of rss.',
          0 => 'No, do not allow rss to be created.'
        ),
        'value' => 1,
      ));

      // Element: edit
      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of RSS?',
        'description' => 'Do you want to let members edit rss? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all rss.',
          1 => 'Yes, allow members to edit their own rss.',
          0 => 'No, do not allow members to edit their rss.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }

      // Element: delete
      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of RSS?',
        'description' => 'Do you want to let members delete rss? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all rss.',
          1 => 'Yes, allow members to delete their own rss.',
          0 => 'No, do not allow members to delete their rss.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }

      // Element: comment
//       $this->addElement('Radio', 'comment', array(
//         'label' => 'Allow Commenting on RSS?',
//         'description' => 'Do you want to let members of this level comment on rss?',
//         'multiOptions' => array(
//           2 => 'Yes, allow members to comment on all rss, including private ones.',
//           1 => 'Yes, allow members to comment on rss.',
//           0 => 'No, do not allow members to comment on rss.',
//         ),
//         'value' => ( $this->isModerator() ? 2 : 1 ),
//       ));
//       if( !$this->isModerator() ) {
//         unset($this->comment->options[2]);
//       }

      //element for event approve
      $this->addElement('Radio', 'rss_approve', array(
        'description' => 'Do you want rss created by members of this level to be auto-approved?',
        'label' => 'Auto Approve RSS',
        'multiOptions' => array(
            1=>'Yes, auto-approve rss.',
            0=>'No, do not auto-approve rss.'
        ),
        'value' => 1,
       ));


      // Element: auth_view
      $this->addElement('MultiCheckbox', 'auth_view', array(
        'label' => 'RSS Privacy',
        'description' => 'Your members can choose from any of the options checked below when they decide who can see their rss entries. These options appear on your members\' "Add Entry" and "Edit Entry" pages. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
        'multiOptions' => array(
          'everyone'            => 'Everyone',
          'registered'          => 'All Registered Members',
          'owner_network'       => 'Friends and Networks',
          'owner_member_member' => 'Friends of Friends',
          'owner_member'        => 'Friends Only',
          'owner'               => 'Just Me'
        ),
        'value' => array('everyone', 'owner_network', 'owner_member_member', 'owner_member', 'owner','registered'),
      ));

      // Element: auth_comment
      $this->addElement('MultiCheckbox', 'auth_comment', array(
        'label' => 'RSS Comment Options',
        'description' => 'Your members can choose from any of the options checked below when they decide who can post comments on their news. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
        'multiOptions' => array(
          'everyone'            => 'Everyone',
          'registered'          => 'All Registered Members',
          'owner_network'       => 'Friends and Networks',
          'owner_member_member' => 'Friends of Friends',
          'owner_member'        => 'Friends Only',
          'owner'               => 'Just Me'
        ),
        'value' => array('everyone', 'owner_network', 'owner_member_member', 'owner_member', 'owner','registered'),
      ));

      // Element: max
      $this->addElement('Text', 'max', array(
        'label' => 'Maximum Allowed RSS Entries?',
        'description' => 'Enter the maximum number of allowed rss entries. The field must contain an integer between 1 and 999, or 0 for unlimited.',
        'validators' => array(
          array('Int', true),
          new Engine_Validate_AtLeast(0),
        ),
      ));
    }
  }
}
