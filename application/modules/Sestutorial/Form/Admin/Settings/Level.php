<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {
  
    parent::init();

    // My stuff
    $this->setTitle('Member Level Settings')
      ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

    // Element: view
    $this->addElement('Radio', 'view', array(
      'label' => 'Allow Viewing of Tutorials?',
      'description' => 'Do you want to let members view Tutorials? If set to no, some other settings on this page may not apply.',
      'multiOptions' => array(
        2 => 'Yes, allow viewing of all Tutorials, even private ones.',
        1 => 'Yes, allow viewing of Tutorials.',
        0 => 'No, do not allow Tutorials to be viewed.',
      ),
      'value' => ( $this->isModerator() ? 2 : 1 ),
    ));
    if( !$this->isModerator() ) {
      unset($this->view->options[2]);
    }
      
    // Element: rating
    $this->addElement('Radio', 'askquestion', array(
      'label' => 'Allow to Request Tutorial?',
      'description' => 'Do you want to let members of this level to request for Tutorials?',
      'multiOptions' => array(
        1 => 'Yes, allow members to request Tutorials',
        0 => 'No, do not allow members to request Tutorials',
      ),
      'value' => 1,
    ));
    
    if( !$this->isPublic() ) {

      // Element: comment
      $this->addElement('Radio', 'comment', array(
        'label' => 'Allow Commenting on Tutorials?',
        'description' => 'Do you want to let members of this level comment on Tutorials?',
        'multiOptions' => array(
          2 => 'Yes, allow members to comment on all Tutorials.',
          1 => 'Yes, allow members to comment on Tutorials.',
          0 => 'No, do not allow members to comment on Tutorials.',
        ),
        'value' => ( $this->isModerator() ? 2 : 1 ),
      ));
      if( !$this->isModerator() ) {
        unset($this->comment->options[2]);
      }

      // Element: rating
      $this->addElement('Radio', 'rating', array(
        'label' => 'Allow Rating on Tutorials?',
        'description' => 'Do you want to let members of this level to rate Tutorials?',
        'multiOptions' => array(
          1 => 'Yes, allow members to rating on Tutorials.',
          0 => 'No, do not allow members to rating on Tutorials.',
        ),
        'value' => 1,
      ));
      
      // Element: rating
      $this->addElement('Radio', 'helpful', array(
        'label' => 'Allow to Select Tutorials as Helpful?',
        'description' => 'Do you want to let members of this level select Tutorials as helpful?',
        'multiOptions' => array(
          1 => 'Yes, allow members to helpful on Tutorials.',
          0 => 'No, do not allow members to helpful on Tutorials.',
        ),
        'value' => 1,
      ));
    }
  }
}
