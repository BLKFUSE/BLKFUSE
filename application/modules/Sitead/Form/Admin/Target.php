<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Target.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Form_Admin_Target extends Engine_Form {

    public function init() {

        $not_addType = array('birthdate', 'heading');
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this->setTitle('Ads Targeting Settings')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setDescription("This powerful advertising system enables ads to be targeted to users based on specific profile fields as well as networks. Depending on whether ads targeting has been enabled for a particular ads package, advertisers will be able to target their ads to desired set of users. Below, you can choose the specific profile fields on which you want targeting to be enabled, and also whether networks based targeting should enabled.");

        //Pickup the dynamic values in the fields_meta table according to the profile type
        $options = Engine_Api::_()->getDBTable('options', 'sitead')->getAllProfileTypes();
        if (empty($options)) {
            return;
        }

        $generalbirthdateFlag = 0;
        $insertLable = array();
        $insertType = array();
        foreach ($options->toarray() as $opt) {

            $selectOption = Engine_Api::_()->getDBTable('metas', 'sitead')->getFields($opt['option_id']);

            $this->addElement('Dummy', $opt['option_id'] . 'mptypelabel', array(
                'label' => $opt['label'],
            ));

            // ELEMENTS OF PROFILE TYPE SPECIFY
            foreach ($selectOption as $key => $value) {
                if ((count($selectOption) == 1) && ($value['type'] == 'birthdate')) {
                    $this->addElement('Dummy', 'note', array(
                        'description' => '<div class="tip"><span>' . Zend_Registry::get('Zend_Translate')->_('Note: You have not created field of ' . $opt['label'] . ' profile type yet.') . '</span></div>',
                        'decorators' => array(
                            'ViewHelper', array(
                                'description', array('placement' => 'APPEND', 'escape' => false)
                            ))
                    ));
                }
                if (in_array($value['type'], $not_addType)) {
                    if ($value['type'] == 'birthdate') {
                        $generalbirthdateFlag = 1;
                        $insertLable['lable'] = "Age";
                        $insertType['type'] = "birthdate";
                    }
                    continue;
                }

                $sTypeIndex = -2;
                $sLableIndex = -123;

                $this->addElement('Checkbox', $opt['option_id'] . 'check' . $key, array(
                    'label' => $value['lable'] . " (" . $value['type'] . ")",
                    'decorators' => array('ViewHelper', array('Label', array('placement' => 'APPEND'),
                            array('HtmlTag', array('tag' => 'div', 'style' => 'float:left;'))))
                ));

                $this->addDisplayGroup(array($opt['option_id'] . 'check' . $key), $opt['option_id'] . 'group' . $key);
                $button_group = $this->getDisplayGroup($opt['option_id'] . 'group' . $key);
                $button_group->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag', array('tag' => 'div', 'style' => 'width:50%;float:left;margin-bottom:15px;', "title" => $value['type']))
                ));
            }
        }

        $insertLable = array_unique($insertLable);
        $insertType = array_unique($insertType);

        if ($generalbirthdateFlag) {
            $this->addElement('Dummy', 'generalmptypelabel', array(
                'label' => 'Age Targeting',
            ));

            $this->addElement('Checkbox', 'target_age', array(
                'label' => $insertLable['lable'] . " (" . $insertType['type'] . ")",
                'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.age', 0),
                'decorators' => array('ViewHelper', array('Label', array('placement' => 'APPEND'),
                        array('HtmlTag', array('tag' => 'div', 'style' => 'float:left;'))))
            ));
            $this->addDisplayGroup(array('target_age'), 'age-targeting-group');
            $button_group = $this->getDisplayGroup('age-targeting-group');
            $button_group->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag', array('tag' => 'div', 'style' => 'width:50%;float:left;margin-bottom:15px;', "title" => 'age'))
            ));
        }

        // ELEMENT TARGET BIRTHDAY
        $this->addElement('Dummy', 'generalmptypelabelbday', array(
            'label' => 'Birthday Targeting',
        ));

        $this->addElement('Checkbox', 'target_birthday', array(
            'label' => 'Target people having their birthday on current date',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.birthday', 0),
            'decorators' => array('ViewHelper', array('Label', array('placement' => 'APPEND'),
                    array('HtmlTag', array('tag' => 'div', 'style' => 'float:left;'))))
        ));

        $this->addDisplayGroup(array('target_birthday'), 'birthday-targeting-group');
        $button_group = $this->getDisplayGroup('birthday-targeting-group');
        $button_group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag', array('tag' => 'div', 'style' => 'width:50%;float:left;margin-bottom:15px;', "title" => 'age'))
        ));

        if (Engine_Api::_()->sitead()->hasNetworkOnSite()) {
            $this->addElement('Dummy', 'profile_base_targeting_others', array(
                'label' => 'Networks Based',
                'description' => '<div class="tip"><span>' . Zend_Registry::get('Zend_Translate')->_("Note: Using Networks Based - Advanced Targeting Options, advertisers will additionally be able to target their ads to users based on Networks, by selecting one or more networks to target, or by choosing the ad to be shown to all networks.") . '</span></div>',
            ));
            $this->profile_base_targeting_others->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
            $this->profile_base_targeting_others->getDecorator('Label')->setOptions(array('style' => "font-weight:bold;"));
            $this->addElement('Checkbox', 'site_target_network', array(
                'label' => 'Enable Networks based targeting.',
                'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0),
                'decorators' => array('ViewHelper', array('Label', array('placement' => 'APPEND'),
                        array('HtmlTag', array('tag' => 'div'))))
            ));
             $this->addDisplayGroup(array('site_target_network'), 'network-targeting-group');
             $button_group = $this->getDisplayGroup('network-targeting-group');
             $button_group->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag', array('tag' => 'div', 'style' => 'width:50%;float:left;margin-bottom:15px;', "title" => 'network'))
                ));
        }

        $this->addElement('Dummy', 'location_base_targeting_others', array(
            'label' => "Location Based",
            'description' => '<div class="tip"><span>' . Zend_Registry::get('Zend_Translate')->_("Note: Using Location Based - Advanced Targeting Options, advertisers will additionally be able to target their ads to users based on their, or by choosing the specific range of location.") . '</span></div>',
        ));
        $this->location_base_targeting_others->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
        $this->location_base_targeting_others->getDecorator('Label')->setOptions(array('style' => "font-weight:bold; margin-top:15px"));

        $this->addElement('Checkbox', 'site_target_location', array(
            'label' => 'Enable Location based targeting.',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.location', 0),
            'decorators' => array('ViewHelper', array('Label', array('placement' => 'APPEND'),
                    array('HtmlTag', array('tag' => 'div'))))
        ));
        $this->addDisplayGroup(array('site_target_location'), 'location-targeting-group');
             $button_group = $this->getDisplayGroup('location-targeting-group');
             $button_group->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag', array('tag' => 'div', 'style' => 'width:50%;float:left;margin-bottom:15px;', "title" => 'location'))
                ));
        // ELEMENT SUBMIT
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true,
        ));
    }

}
