<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Level.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_Form_Admin_Level extends Authorization_Form_Admin_Level_Abstract {

  public function init() {
    
    parent::init();

    // My stuff
    $this
            ->setTitle('Member Level Settings');
            
    //check package enable
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)){     
    $this->setDescription('Since, you have enabled Packages settings for Contest creation on your website, some settings are moved from Member Level to the packages. So, please configure them from Package Settings section.');
    return;
    }else{
        $this->setDescription('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');   
    }
   
    if (!$this->isPublic()) {
			//commission 
			$this->addElement('Select', 'contest_admcosn', array(
	      'label' => 'Unit for Commission',
	      'description' => 'Choose the unit for admin commission which you will get on the contest fees.',
	      'multiOptions' => array(
						1 => 'Percentage',
						2 => 'Fixed'
	      ),
				'allowEmpty' => false,
	       'required' => true,
	      'value' => 1,
	    ));
			$this->addElement('Text', "contest_commival", array(
	        'label' => 'Commission Value',
	        'description' => "Enter the value for commission according to the unit chosen in above setting. [If you have chosen Percentage, then value should be in range 1 to 100.]",
	        'allowEmpty' => true,
	        'required' => false,
	        'value' => 1,
	    ));
	    $this->addElement('Text', "contest_threamt", array(
	        'label' => 'Threshold Amount for Releasing Payment',
	        'description' => "Enter the threshold amount which will be required before making request for releasing payment from admins. [Note: Threshold Amount is remaining amount which the owner of the contest will get after subtracting the admin commission from the total amount received.]",
	        'allowEmpty' => false,
	        'required' => true,
	        'value' => 100,
	    ));
      
    }
	}
}