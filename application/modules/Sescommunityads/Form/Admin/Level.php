<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Form_Admin_Level extends Authorization_Form_Admin_Level_Abstract
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
      'label' => 'Allow Viewing of Advertisements?',
      'description' => 'Do you want to let users view advertisements? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        1 => 'Yes, allow viewing of advertisements.',
        0 => 'No, do not allow advertisements to be viewed.'
      ),
      'value' => 1,
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }
    if( !$this->isPublic() ) {
      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of Advertisements?',
        'description' => 'Do you want to let users create advertisements? If set to no, some other settings on this page may not apply. This is useful if you want users to be able to view advertisements, but only want certain levels to be able to create advertisements.',
        'value' => 1,
        'multiOptions' => array(
          1 => 'Yes, allow creation of advertisements.',
          0 => 'No, do not allow advertisements to be created.'
        ),
        'value' => 1,
      ));
      // Element: edit
      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of Advertisements?',
        'description' => 'Do you want to let members of this level edit advertisements(User will not be able to edit ads once the ad starts.)?',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all advertisements.',
          1 => 'Yes, allow members to edit their own advertisements.',
          0 => 'No, do not allow advertisements to be edited.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }
      // Element: delete
      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of Advertisements?',
        'description' => 'Do you want to let members of this level delete advertisements?',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all advertisements.',
          1 => 'Yes, allow members to delete their own advertisements.',
          0 => 'No, do not allow members to delete their advertisements.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }
      $this->addElement('Hidden', 'auth_view', array(
        'value' => 1,
        'order' => 99999,
      ));
      $this->addElement('Hidden', 'auth_comment', array(
        'value' => 1,
        'order' => 9999,
      ));
    }
  }
}
