<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Level.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Eusertip_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {
    
    parent::init();

    $this->setTitle('Member Level Settings');
    
    $this->setDescription('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');   
   
    if (!$this->isPublic()) {
    
      $this->addElement('Radio', 'create', array(
        'label' => 'Allow Creation of Tips?',
        'description' => 'Do you want to let members create Tips? If set to no, some other settings on this page may not apply.',
        'multiOptions' => array(
            1 => 'Yes, allow creation of tips.',
            0 => 'No, do not allow tips to be created.'
        ),
        'value' => 1,
      ));
    
			$this->addElement('Select', 'eusertip_admcosn', array(
	      'label' => 'Unit for Commission',
	      'description' => 'Choose the unit for admin commission which you will get on the user paid tip.',
	      'multiOptions' => array(
						1 => 'Percentage',
						2 => 'Fixed'
	      ),
				'allowEmpty' => false,
        'required' => true,
	      'value' => 1,
	    ));
			$this->addElement('Text', "eusertip_commival", array(
	        'label' => 'Commission Value',
	        'description' => "Enter the value for commission according to the unit chosen in above setting. [If you have chosen Percentage, then value should be in range 1 to 100.]",
	        'allowEmpty' => true,
	        'required' => false,
	        'value' => 1,
	    ));
	    $this->addElement('Text', "eusertip_threamt", array(
	        'label' => 'Threshold Amount for Releasing Payment',
	        'description' => "Enter the threshold amount which will be required before making request for releasing payment from admins. [Note: Threshold Amount is remaining amount which the owner will get after subtracting the admin commission from the total amount received.]",
	        'allowEmpty' => false,
	        'required' => true,
	        'value' => 100,
	    ));
    }
	}
}
