<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Activity.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Admin_Activity extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Advanced Activity Feed Settings')
            ->setDescription('You can enable various Activity Feed Settings for the Ads which get displayed at the various pages in Activity feed.');

    $this->addElement('Radio', 'sescommunityads_advertisement_enable', array(
        'label' => 'Enable Advertisement',
        'description' => "Do you want to enable advertisement in Advanced Activity feed?",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No'
        ),
        'value' => $settings->getSetting('sescommunityads_advertisement_enable', '1'),
    ));

    $this->addElement('Radio', 'sescommunityads_ads_count', array(
        'label' => 'Advertisement Count',
        'description' => "How many ads you want to show in activity feed?",
        'multiOptions' => array(
            '1' => '1',
            '2' => '2',
            '3' => '3'
        ),
        'value' => $settings->getSetting('sescommunityads_ads_count', '1'),
    ));

    $this->addElement('Radio', 'sescommunityads_advertisement_display', array(
        'label' => 'Display Advertisement',
        'description' => "Choose from below which advertisement you want to display?",
        'multiOptions' => array(
            '1' => 'Featured',
            '2' => 'Sponsored',
            '3' => 'Featured & Sponsored',
            '3' =>'All'
        ),
        'value' => $settings->getSetting('sescommunityads_advertisement_display', '3'),
    ));

    $this->addElement('Radio', 'sescommunityads_advertisement_displayfeed', array(
        'label' => 'Subject Feeds',
        'description' => "Do you want to show advertisement in subject feed also?",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescommunityads_advertisement_displayfeed', '1'),
    ));


      $this->addElement('Text', 'sescommunityads_advertisement_displayads', array(
        'label' => 'Placement',
        'description' => "After how many feeds you want to display community ads in advanced activity?",
        'value' => $settings->getSetting('sescommunityads_advertisement_displayads', '5'),
    ));
			// Add submit button
			$this->addElement('Button', 'submit', array(
					'label' => 'Save Changes',
					'type' => 'submit',
					'ignore' => true
			));
  }

}
