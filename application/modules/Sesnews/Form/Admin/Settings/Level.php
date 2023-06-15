<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract
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
      'label' => 'Allow Viewing of News?',
      'description' => 'Do you want to let members view news? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        2 => 'Yes, allow members to view all news, even private ones.',
        1 => 'Yes, allow members to view their own news.',
        0 => 'No, do not allow news to be viewed.',
      ),
      'value' => ( $this->isModerator() ? 2 : 1 ),
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }

    if( !$this->isPublic() ) {

      // Element: create
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of News?',
        'description' => 'Do you want to let members create news? If set to no, some other settings on this page may not apply. This is useful if you want members to be able to view news, but only want certain levels to be able to create news.',
        'multiOptions' => array(
          1 => 'Yes, allow creation of news.',
          0 => 'No, do not allow news to be created.'
        ),
        'value' => 1,
      ));

      // Element: edit
      $this->addElement('Radio', 'edit', array(
        'label' => 'Allow Editing of News?',
        'description' => 'Do you want to let members edit news? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to edit all news.',
          1 => 'Yes, allow members to edit their own news.',
          0 => 'No, do not allow members to edit their news.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->edit->options[2]);
      }

      // Element: delete
      $this->addElement('Radio', 'delete', array(
        'label' => 'Allow Deletion of News?',
        'description' => 'Do you want to let members delete news? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
          2 => 'Yes, allow members to delete all news.',
          1 => 'Yes, allow members to delete their own news.',
          0 => 'No, do not allow members to delete their news.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->delete->options[2]);
      }

      // Element: comment
      $this->addElement('Radio', 'comment', array(
        'label' => 'Allow Commenting on News?',
        'description' => 'Do you want to let members of this level comment on news?',
        'multiOptions' => array(
          2 => 'Yes, allow members to comment on all news, including private ones.',
          1 => 'Yes, allow members to comment on news.',
          0 => 'No, do not allow members to comment on news.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->comment->options[2]);
      }

      //element for event approve
      $this->addElement('Radio', 'news_approve', array(
        'description' => 'Do you want news created by members of this level to be auto-approved?',
        'label' => 'Auto Approve News',
        'multiOptions' => array(
            1=>'Yes, auto-approve news.',
            0=>'No, do not auto-approve news.'
        ),
        'value' => 1,
       ));


      // Element: auth_view
      $this->addElement('MultiCheckbox', 'auth_view', array(
        'label' => 'News Privacy',
        'description' => 'Your members can choose from any of the options checked below when they decide who can see their news entries. These options appear on your members\' "Add Entry" and "Edit Entry" pages. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
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
        'label' => 'News Comment Options',
        'description' => 'Your members can choose from any of the options checked below when they decide who can post comments on their entries. If you do not check any options, settings will default to the last saved configuration. If you select only one option, members of this level will not have a choice.',
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

			$this->addElement('Radio', 'cotinuereading', array(
        'label' => 'Allow Continue Reading enable',
        'description' => 'Do you want to allow News owner for showing Continue Reading button on News view Page.',
        'multiOptions' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
				'onchange' => 'continuereadingbutton(this.value)',
        'value' => '1',
      ));
			$this->addElement('Radio', 'cntrdng_dflt', array(
        'label' => 'Default Allow to continue reading button',
        'description' => 'Do you want to set default  Continue Reading button on News view Page.',
        'multiOptions' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
        'value' => '1',
      ));

      // Element: style
      $this->addElement('Radio', 'style', array(
        'label' => 'Allow Custom CSS Styles?',
        'description' => 'If you enable this feature, your members will be able to customize the colors and fonts of their news by altering their CSS styles.',
        'multiOptions' => array(
          1 => 'Yes, enable custom CSS styles.',
          0 => 'No, disable custom CSS styles.',
        ),
        'value' => 1,
      ));

      // Element: auth_html
      $this->addElement('Text', 'auth_html', array(
        'label' => 'HTML in News Entries?',
        'description' => 'If you want to allow specific HTML tags, you can enter them below (separated by commas). Example: b, img, a, embed, font',
        'value' => 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr'
      ));

      $this->addElement('Radio', 'allow_levels', array(
          'label' => 'Allow to choose "News View Privacy Based on Member Levels"',
          'description' => 'Do you want to allow the members of this level to choose View privacy of their Pages based on Member Levels on your website? If you choose Yes, then users will be able to choose the visibility of their Pages to members of selected member levels only.',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 0,
      ));

      $this->addElement('Radio', 'allow_network', array(
          'label' => 'Allow to choose "News View Privacy Based on Networks"',
          'description' => 'Do you want to allow the members of this level to choose View privacy of their News based on Networks on your website? If you choose Yes, then users will be able to choose the visibility of their Pages to members who have joined selected networks only.',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
          ),
          'value' => 0,
      ));



      // Element: max
      $this->addElement('Text', 'max', array(
        'label' => 'Maximum Allowed News Entries?',
        'description' => 'Enter the maximum number of allowed news entries. The field must contain an integer between 1 and 999, or 0 for unlimited.',
        'validators' => array(
          array('Int', true),
          new Engine_Validate_AtLeast(0),
        ),
      ));
    }
  }
}
