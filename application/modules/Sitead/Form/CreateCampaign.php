<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Create.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

class Sitead_Form_CreateCampaign extends Engine_Form {

    protected $_item;

    public function getItem() {
        return $this->_item;
    }

    public function setItem(Core_Model_Item_Abstract $item) {
        $this->_item = $item;
        return $this;
    }

    public function init() {

        $this->setTitle('Create Ad Campaign');
        $owner_id = null;
        if (!empty($this->_item))
            $owner_id = $this->_item->owner_id;
        $ownerCampaigns = Engine_Api::_()->sitead()->getUserCampaigns($owner_id);
        $campaignsList = array('0' => 'Create a New Campaign');

        foreach ($ownerCampaigns as $campaign) {
            $campaignsList[$campaign->adcampaign_id] = $campaign->name;
        }
        //ELEMENT CAMPAGIN_ID
        $this->addElement('Select', 'temp_campaign_id', array(
            'label' => Zend_Registry::get('Zend_Translate')->_('Select Campaign'),
            'multiOptions' => $campaignsList,
            'onchange' => "updateTextFields()",
        ));

        //ELEMENT CAMPAGIN NAME
        $this->addElement('Text', 'temp_campaign_name', array(
            'Label' => Zend_Registry::get('Zend_Translate')->_('Campaign Name'),
            'maxlength' => 100,
        ));
        // Element: cancel
        $this->addElement('Cancel', 'camp_back', array(
            'label' => Zend_Registry::get('Zend_Translate')->_('Back'),
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        $this->addElement('Button', 'continue_title', array(
            'label' => Zend_Registry::get('Zend_Translate')->_('Next'),
            'order' => '998',
            'decorators' => array(
                'ViewHelper',
            ),
        ));

        // DisplayGroup: buttons
        $this->addDisplayGroup(array(
            'camp_back',
            'continue_title',
                ), 'buttons', array(
            'decorators' => array(
                'FormElements',
                'DivDivDivWrapper'
            ),
        ));
    }

}
