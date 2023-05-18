<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Level.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
{
  public function init()
  {
    parent::init();
    // My stuff
    $this
      ->setTitle('Member Level Settings')
      ->setDescription('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');
    // Element: view
    $this->addElement('Radio', 'view', array(
      'label' => 'Allow Viewing of Games?',
      'description' => 'Do you want to let users view games? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        2 => 'Yes, allow members to view all games, even private ones.',
        1 => 'Yes, allow viewing of games.',
        0 => 'No, do not allow games to be viewed.'
      ),
      'value' => ( $this->isModerator() ? 2 : 1 ),
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }
    if( !$this->isPublic() ) {			
      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of Games?',
        'description' => 'Do you want to let users create games? If set to no, some other settings on this page may not apply. This is useful if you want users to be able to view games, but only want certain levels to be able to create games.',
        'value' => 1,
        'multiOptions' => array(
          1 => 'Yes, allow creation of games.',
          0 => 'No, do not allow games to be created.'
        ),
        'value' => 1,
      ));      
      // Element: edit
      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of Games?',
        'description' => 'Do you want to let members of this level edit games?',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all games.',
          1 => 'Yes, allow members to edit their own games.',
          0 => 'No, do not allow games to be edited.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }
      // Element: delete
      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of Games?',
        'description' => 'Do you want to let members of this level delete games?',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all games.',
          1 => 'Yes, allow members to delete their own games.',
          0 => 'No, do not allow members to delete their games.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }
      // Element: comment
      $this->addElement('Radio', 'comment', array(
        'label' => 'Allow Commenting on Games?',
        'description' => 'Do you want to let members of this level comment on games?',
        'multiOptions' => array(
          2 => 'Yes, allow members to comment on all games, including private ones.',
          1 => 'Yes, allow members to comment on games.',
          0 => 'No, do not allow members to comment on games.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->comment->options[2]);
      }
      // Element: auth_view
      $this->addElement('MultiCheckbox', 'auth_view', array(
        'label' => 'Games Privacy',
        'description' => 'Your users can choose from any of the options checked below when they decide who can see their games. These options appear on your users "Create Game" and "Edit Game" pages. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
        'multiOptions' => array(
          'everyone'            => 'Everyone',
          'registered'          => 'All Registered Members',
          'owner_network'       => 'Friends and Networks',
          'owner_member_member' => 'Friends of Friends',
          'owner_member'        => 'Friends Only',
          'owner'               => 'Just Me'
        ),
        'value' => array('registered','everyone', 'owner_network','owner_member_member', 'owner_member', 'owner'),
      ));
      // Element: auth_comment
      $this->addElement('MultiCheckbox', 'auth_comment', array(
        'label' => 'Game Comment Privacy',
        'description' => 'Your users can choose from any of the options checked below when they decide who can post comments on their games. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
        'multiOptions' => array(
          'everyone'            => 'Everyone',
          'registered'          => 'All Registered Members',
          'owner_network'       => 'Friends and Networks',
          'owner_member_member' => 'Friends of Friends',
          'owner_member'        => 'Friends Only',
          'owner'               => 'Just Me'
        ),
        'value' => array('registered','everyone', 'owner_network','owner_member_member', 'owner_member', 'owner'),
      ));
      
			 
		
			  // Element: max_games
      $this->addElement('Text', 'max_games', array(
        'label' => 'Maximum Allowed Games',
        'description' => 'Enter the maximum number of games a member can create. The field must contain an integer, use zero for unlimited.',
        'validators' => array(
          array('Int', true),
          new Engine_Validate_AtLeast(0),
        ),
				 'value' => 0,
      ));
      
		 
    }		
		 
  }
}