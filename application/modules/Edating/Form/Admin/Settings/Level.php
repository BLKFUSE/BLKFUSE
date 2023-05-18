<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Level.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {
  
    parent::init();

    // My stuff
    $this
        ->setTitle('Member Level Settings')
        ->setDescription("These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.");

    if( !$this->isPublic() ) {
      $this->addElement('Radio', 'create', array(
          'label' => 'Allow Dating?',
          'description' => 'Do you want to let members allow dating? If set to no, some other settings on this page may not apply.',
          'multiOptions' => array(
              1 => 'Yes, allow dating.',
              0 => 'No, do not allow dating.'
          ),
          'value' => 1,
      ));
    }
  }
}
