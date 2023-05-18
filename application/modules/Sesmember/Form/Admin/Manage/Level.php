<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Level.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {

    parent::init();

    $this->setTitle('Member Level Settings')->setDescription('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');

    // Element: view
    $this->addElement('Radio', 'view', array(
        'label' => 'Allow Viewing of Reviews?',
        'description' => 'Do you want to let members view a reviews for this content?',
        'multiOptions' => array(
            1 => 'Yes, allow viewing  of reviews.',
            0 => 'No, do not allow reviewes to be viewed.',
        ),
        'value' => 1,
    ));
    if (!$this->isPublic()) {
      $this->addElement('Radio', 'create', array(
          'label' => 'Allow Write a Reviews',
          'description' => 'Do you want to let members write a reviews for this content?',
          'multiOptions' => array(
              1 => 'Yes, allow members to write a reviews.',
              0 => 'No, do not allow members to write a reviews.'
          ),
          'value' => 1,
      ));
      // Element: edit
      $this->addElement('Radio', 'edit', array(
          'label' => 'Allow Editing of Reviews?',
          'description' => 'Do you want to let members edit  reviews?',
          'multiOptions' => array(
              1 => "Yes, allow  members to edit their own reviews.",
              0 => "No, do not allow reviews to be edited.",
          ),
          'value' => 1,
      ));
      //Element: delete
      $this->addElement('Radio', 'delete', array(
          'label' => 'Allow Deletion of Reviews?',
          'description' => 'Do you want to let members delete reviews? If set to no, some other settings on this page may not apply.',
          'multiOptions' => array(
              1 => 'Yes, allow members to delete their own reviews.',
              0 => 'No, do not allow members to delete their reviews.',
          ),
          'value' => 1,
      ));
      //Element: comment
      $this->addElement('Radio', 'comment', array(
          'label' => 'Allow Commenting on Reviews?',
          'description' => 'Do you want to let members comment on Reviews?',
          'multiOptions' => array(
              2 => 'Yes, allow members to comment on reviews.',
              0 => 'No, do not allow commenting on reviews.',
          ),
          'value' => 1,
      ));
    }
  }

}