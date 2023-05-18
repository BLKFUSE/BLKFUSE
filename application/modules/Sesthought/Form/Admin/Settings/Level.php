<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {
  
    parent::init();

    // My stuff
    $this
      ->setTitle('Member Level Settings')
      ->setDescription("These settings are applied as per member level. Start by selecting the member level you want to modify, then adjust the settings for that level from below.");

    // Element: view
    $this->addElement('Radio', 'view', array(
      'label' => 'Allow Viewing of Thoughts?',
      'description' => 'Do you want to let members view thoughts? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        //2 => 'Yes, allow viewing of all thoughts, even private ones.',
        1 => 'Yes, allow viewing of thoughts.',
        0 => 'No, do not allow thoughts to be viewed.',
      ),
      'value' => 1,
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }
    
    if( !$this->isPublic() ) {

      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of Thoughts?',
        'description' => 'Do you want to let members create thoughts? If set to no, some other settings on this page may not apply. This is useful if you want members to be able to view thoughts, but only want certain levels to be able to create thoughts.',
        'multiOptions' => array(
          1 => 'Yes, allow creation of thoughts.',
          0 => 'No, do not allow thoughts to be created.'
        ),
        'value' => 1,
      ));

      // Element: edit
      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of Thoughts?',
        'description' => 'Do you want to let members edit thoughts? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all thoughts.',
          1 => 'Yes, allow members to edit their own thoughts.',
          0 => 'No, do not allow members to edit their thoughts.',
        ),
        'value' => ($this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }

      // Element: delete
      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of Thoughts?',
        'description' => 'Do you want to let members delete thoughts? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all thoughts.',
          1 => 'Yes, allow members to delete their own thoughts.',
          0 => 'No, do not allow members to delete their thoughts.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }
      
      
      // Element: comment
      $this->addElement('Radio', 'comment', array(
        'label' => 'Allow Commenting on Thoughts?',
        'description' => 'Do you want to let members of this level comment on thoughts?',
        'multiOptions' => array(
          //2 => 'Yes, allow members to comment on all thoughts, including private ones.',
          1 => 'Yes, allow members to comment on thoughts.',
          0 => 'No, do not allow members to comment on thoughts.',
        ),
        'value' => ( $this->isModerator() ? 1 : 0 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->comment->options[2]);
      }
    }
  }
}