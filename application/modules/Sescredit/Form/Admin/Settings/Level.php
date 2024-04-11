<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescredit_Form_Admin_Settings_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {

    parent::init();

    $this->setTitle('Member Level Settings')
            ->setDescription("These settings are applied as per member level. Start by selecting the member level you want to modify, then adjust the settings for that level from below.");

    if (!$this->isPublic()) {
      $this->addElement('Text', 'credit_referral', array(
          'label' => 'Signup Invitation Referral Credit Value',
          'description' => 'Enter the signup invitation referral credit value which will be earned by the members of this level when new members will signup using their Referral Link on your website.',
          'validators' => array(
              array('Int', true),
              new Engine_Validate_AtLeast(0),
          ),
      ));

			$this->addElement('Select', 'sescredit_cashcredit', array(
				'label' => 'Earn Cash Credits',
				'description' => 'Do you want to allow member to earn cash credit?',
				'multiOptions' => array(
					1 => 'Yes',
					0 => 'No'
				),
				'onchange' => 'hideShow(this.value);',
			));
			
			$this->addElement('Select', 'sescredit_admcosn', array(
	      'label' => 'Unit for Commission',
	      'description' => 'Choose the unit for admin commission.',
	      'multiOptions' => array(
						1 => 'Percentage',
						2 => 'Fixed'
	      ),
				'allowEmpty' => false,
        'required' => true,
	      'value' => 1,
	    ));
			$this->addElement('Text', "sescredit_commival", array(
	        'label' => 'Commission Value',
	        'description' => "Enter the value for commission according to the unit chosen in above setting. [If you have chosen Percentage, then value should be in range 1 to 100.]",
	        'allowEmpty' => true,
	        'required' => false,
	        'value' => 1,
	    ));
	    $this->addElement('Text', "sescredit_threamt", array(
	        'label' => 'Threshold Amount for Releasing Credit Points',
	        'description' => "Enter the threshold amount which will be required before making request for releasing credit points from admins.",
	        'allowEmpty' => false,
	        'required' => true,
	        'value' => 100,
	    ));
    }
  }

}
