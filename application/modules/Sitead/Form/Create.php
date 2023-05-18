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
class Sitead_Form_Create extends Engine_Form {

    public $_error = array();
    protected $_item;
    protected $_copy;
    protected $_packageId;
    protected $_typeId;
    protected $_format;

    public function getCopy() {
        return $this->_copy;
    }

    public function setCopy($item) {
        $this->_copy = $item;
        return $this;
    }

    public function getItem() {
        return $this->_item;
    }

    public function setItem(Core_Model_Item_Abstract $item) {
        $this->_item = $item;
        return $this;
    }

    public function getPackageId() {
        return $this->_packageId;
    }

    public function setPackageId($id) {
        $this->_packageId = $id;
        return $this;
    }

    public function getTypeId() {
        return $this->_typeId;
    }

    public function setTypeId($id) {
        $this->_typeId = $id;
        return $this;
    }

    public function getFormat() {
        return $this->_format;
    }

    public function setFormat($id) {
        $this->_format = $id;
        return $this;
    }

    public function init() {
        parent::init();
        $this->setAttrib('id', 'wholeform');

        $changeLink = '';
        if ($this->_typeId == 'content')
            $levels_prepared = Engine_Api::_()->sitead()->enabled_module_content($this->_packageId);
        if ($this->_typeId == 'page')
            $levels_prepared = Engine_Api::_()->sitead()->enabled_page_content($this->_packageId);
        if (!empty($levels_prepared)) {
            if ($this->_typeId == 'content')
                $modulesArray = array_filter($levels_prepared[1]);
            if ($this->_typeId == 'page')
                $modulesArray = array_filter($levels_prepared);
        }

        $package = Engine_Api::_()->getItem('package', $this->_packageId);
        $enableTarget = $package['network'];

        $owner_id = null;
        if (!empty($this->_item))
            $owner_id = $this->_item->owner_id;
        $ownerCampaigns = Engine_Api::_()->sitead()->getUserCampaigns($owner_id);
        $campaignsList = array('0' => 'Create a New Campaign');

        foreach ($ownerCampaigns as $campaign) {
            $campaignsList[$campaign->adcampaign_id] = $campaign->name;
        }

        //ELEMENT CAMPAGIN_ID
        $this->addElement('Select', 'campaign_id', array(
            'Label' => Zend_Registry::get('Zend_Translate')->_('Select Campaign'),
            'multiOptions' => $campaignsList,
                // 'onchange' => "updateTextFields()",
        ));

        //ELEMENT CAMPAGIN NAME
        $this->addElement('Hidden', 'campaign_name', array(
            'Label' => Zend_Registry::get('Zend_Translate')->_('Campaign Name'),
            'maxlength' => 100,
            'description' => Zend_Registry::get('Zend_Translate')->_('This is only for your indicative purpose and not visible to viewers.')
        ));

        $this->addDisplayGroup(array(
            'campaign_id',
            'campaign_name',
                ), 'sitead_campaign'
        );
        $ad_group = $this->getDisplayGroup('sitead_campaign');
        $ad_group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag', array('id' => 'sitead_campaign_wrp', 'class' => 'sitead-campaign-wrp', 'style' => 'display:none'))
        ));

        $this->addElement('Hidden', 'owner_id', array(
            'order' => 950
        ));
        $this->addElement('Hidden', 'package_id', array(
            'order' => 951
        ));
        $this->addElement('Hidden', 'cmd_ad_type', array(
            'order' => 952,
            'value' => $this->_typeId,
        ));

        $this->addElement('Hidden', 'cmd_ad_format', array(
            'order' => 953,
            'value' => $this->_format,
        ));

        $this->addElement('Hidden', 'resource_type', array(
            'order' => 954
        ));

        $this->addElement('Hidden', 'resource_id', array(
            'order' => 955
        ));

        $this->addElement('Hidden', 'photo_id_filepath', array(
            'value' => 0,
            'order' => 956
        ));

        $this->addElement('Hidden', 'imageenable', array(
            'value' => 0,
            'order' => 957
        ));

        $this->addElement('Hidden', 'content_title', array(
            'value' => '',
            'order' => 958
        ));

        $this->addElement('Hidden', 'content_page', array(
            'value' => 0,
            'order' => 959
        ));

        switch ($this->_typeId) {
            case 'content':
                $content = Zend_Registry::get('Zend_Translate')->_('Select Content');
                break;
            case 'page':
                $content = Zend_Registry::get('Zend_Translate')->_('Select Page');
                break;
            case 'website':
                $content = Zend_Registry::get('Zend_Translate')->_('Select Website Ad Content');
                break;
            default:
                $content = Zend_Registry::get('Zend_Translate')->_('Select Website Ad Content');
                break;
        }

        $this->addElement('Dummy', 'ad_heading_top', array(
            'content' => '<h3>' . $content . '</h3>',
        ));

        // Init name
        $site_name = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.title');
        $changeLink = Zend_Registry::get('Zend_Translate')->_('Example: http://www.yourwebsite.com/');
        $this->addElement('Text', 'web_url', array(
            'label' => 'Website URL',
            'description' => $changeLink,
            'required' => true,
            'allowEmpty' => false,
            'value' => 'http://',
            'validators' => array(
                array('NotEmpty', true),
        )));
        $this->web_url->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
        $this->web_url->getValidator('NotEmpty')->setMessage('Please enter a valid email address.', 'isEmpty');


        $this->addElement('File', 'web_icon', array(
            'label' => Zend_Registry::get('Zend_Translate')->_('Ad Icon'),
            'required' => true,
            'description' => Zend_Registry::get('Zend_Translate')->_("Browse and choose an image for ad icon. Max file size allowed : "). (int) ini_get('upload_max_filesize') . Zend_Registry::get('Zend_Translate')->_(" MB. File types allowed: jpg, jpeg, png."),
            'validators' => array(
                array('Extension', false, 'jpg,png,jpeg')
            ),
            'onchange' => 'iconupload(event)',
        ));
        $this->web_icon->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));

        $this->addElement('Text', 'web_name', array(
            'label' => 'Ad Name ',
            'value' => '',
            'required' => true,
            'allowEmpty' => false,
            'validators' => array(
                array('NotEmpty', true),
        )));

        if ($this->_typeId == 'content' || $this->_typeId == 'page') {
            $changeLink = Zend_Registry::get('Zend_Translate')->_('Select the ' . $this->_typeId . ' you want to advertise.');
            $changeLink = sprintf($changeLink);
            $this->addElement('Select', 'create_feature', array(
                'label' => 'Content Type',
                'description' => $changeLink,
                'required' => true,
                'multiOptions' => $modulesArray,
                'onchange' => "subcontent(this.value);"
            ));
            $this->create_feature->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));

            //ELEMENT TITLE
            $this->addElement('Select', 'title', array(
                'RegisterInArrayValidator' => false,
                'required' => true,
                'label' => 'Select Content',
                'description' => '',
                'decorators' => array(array('ViewScript', array(
                            'viewScript' => '_formModtitle.tpl',
                            'class' => 'form element'))),
            ));

            $this->addDisplayGroup(array(
                'create_feature',
                'title',
                'web_icon',
                'web_name',
                'web_url',
                    ), 'sitead_adinfo'
            );
        }

        if ($this->_typeId == 'website') {
            $this->addDisplayGroup(array(
                'web_url',
                'web_name',
                'web_icon',
                    ), 'sitead_adinfo'
            );
        }

        if ($this->_typeId == 'boost') {
            $this->addDisplayGroup(array(
                'web_url',
                'web_name',
                'web_icon',
                    ), 'sitead_adinfo'
            );
        }
        $ad_group = $this->getDisplayGroup('sitead_adinfo');
        $ad_group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag', array('id' => 'sitead_adinfo', 'class' => 'sitead_crate'))
        ));


        $is_edit_content = 0;
        $is_edit_value = 0;
        if (!empty($this->_item)) {
            if ($this->_typeId == 'content' || $this->_typeId == 'page')
                $is_edit_content = 1;
            $is_edit_value = 1;
        }

        $this->addElement('Hidden', 'is_edit', array(
            'value' => $is_edit_value,
            'order' => 960
        ));

        $this->addElement('Hidden', 'is_edit_content', array(
            'value' => $is_edit_content,
            'order' => 961
        ));

        $this->addElement('Dummy', 'ad_heading_design', array(
            'content' => '<h3>' . 'Design your Ad' . '</h3>',
        ));

        if ($this->_format == 'carousel') {
            $num = 9;
            $this->addElement('Text', 'carousel_add_slides', array(
                'label' => 'No of Slides 2',
                'decorators' => array(array('ViewScript', array(
                            'viewScript' => '_carouselAddSlides.tpl',
                            'label' => 'No of Slides 2',
                        ))),
            ));
        } else
            $num = 1;

        for ($i = 1; $i <= $num; $i++) {
            $name = 'ads_' . $i;
            $subform = new Sitead_Form_SlideAds(array('elementsBelongTo' => $name, 'number' => $i, 'format' => $this->_format));
            $this->addSubForm($subform, $name);
        }

        if ($this->_format == 'carousel') {
            $this->addElement('Checkbox', 'show_card', array(
                'label' => 'Add a card at the end.',
                'value' => 0,
                'onclick' => 'showCard(event)',
            ));

            $this->addElement('File', 'Filedata_10', array(
                'label' => Zend_Registry::get('Zend_Translate')->_('Card Image'),
                'required' => true,
                'accept' => 'image/*',
                'description' => Zend_Registry::get('Zend_Translate')->_("Browse and choose an image for your ad. Max file size allowed : "). (int) ini_get('upload_max_filesize') . Zend_Registry::get('Zend_Translate')->_(" MB. File types allowed: jpg, jpeg, png."),
                'validators' => array(
                    array('Extension', false, 'jpg,png,jpeg')
                ),
                'onchange' => 'imageupload(event)',
            ));

            $this->addElement('Text', 'card_title', array(
                'label' => 'See More Display Link',
                'required' => true,
                'allowEmpty' => false,
            ));

            $this->addElement('Text', 'card_url', array(
                'label' => Zend_Registry::get('Zend_Translate')->_('See More URL'),
                'required' => true,
                'value' => 'http://',
                'allowEmpty' => false,
            ));

            $this->addDisplayGroup(array(
                'Filedata_10',
                'card_title',
                'card_url',
                    ), 'sitead_endcard'
            );

            $ad_group = $this->getDisplayGroup('sitead_endcard');
            $ad_group->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag', array('id' => 'sitead_endcard', 'class' => 'sitead_crate', 'style' => 'display:none'))
            ));
        }

        $this->addElement('Dummy', 'ad_heading_targeting', array(
            'content' => '<h3>' . 'Profile Targeting' . '</h3>',
        ));

        $targetFields = Engine_Api::_()->getItemTable('target')->getFields();
        $targetFieldIds = array();
        $targetMapIds = array();
        // GET TARGETING FIELDS ID
        foreach ($targetFields as $targetField) {
            $targetFieldIds[] = $targetField->field_id;
        }
        $req_field_id = $targetFieldIds;
        // OBJECT OF USER_FIELDS_MAP
        $mapTable = Engine_Api::_()->getItemTable('map');
        $select = $mapTable->select();

        $targetFieldStr = (string) ( "'" . join("', '", $targetFieldIds) . "'");
        $select->where('child_id in (?)', new Zend_Db_Expr($targetFieldStr));
        $fieldStructure = $mapTable->fetchAll($select)->toArray();

        foreach ($fieldStructure as $key => $value) {
            $fieldStructure[$value['field_id'] . '_' . $value['option_id'] . '_' . $value['child_id']] = $value;
            unset($fieldStructure[$key]);
        }

        //Refined field structure
        $newFieldStructure = $fieldStructure;
        $type = array();

        // General form without profile type
        $newFieldKeys = array_keys($newFieldStructure);

        // fields that are not includeing for targeting
        $not_addType = array('heading', 'birthdate');

        // fields that required to change discription
        $addDiscription = array('first_name', 'last_name', 'website', 'twitter', 'facebook', 'aim', 'about_me', 'city', 'zip_code', 'location', 'interests');

        $eLabel = array();
        $listFieldValue = array();
        $fieldElements = array();

        $structure = Engine_Api::_()->getApi('core', 'sitead')->getFieldsStructureSearch('user');

        //Start create targeting fields
        $index = 963;

        /* -----------------
         * Targeting for Genric Fields
         */
        $count_profile = 0;
        $profile = array();
        $profile_fields = array();
        if (!empty($enableTarget)) {
            // fields that are includeing for targeting
            $addType = array();
            // fields that arenot includeing for targeting
            // $not_addType = array();

            $structure = Engine_Api::_()->getApi('core', 'sitead')->getFieldsStructureSearch('user');
            $options = Engine_Api::_()->getDBTable('options', 'sitead')->getAllProfileTypes();
            if (empty($options)) {
                return;
            }
            $count_profile = @count($options);
            // Start create targeting fields
            $profile_base_targeting_flage = 1;
            // ELEMENTS OF PROFILE TYPE SPECIFY

            $profile_base_targeting_flage = 0;
            // Add field for profile
            $this->addElement('radio', 'profile', array(
                'label' => Zend_Registry::get('Zend_Translate')->_('Select Profile Type'),
                'onclick' => 'profileFields(this.value)',
                'class' => 'profile-select',
            ));
            $this->addDisplayGroup(array('profile'), 'field profile');
            $eleField = $this->getDisplayGroup('field profile');
            $eleField->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag', array('tag' => 'div', 'id' => 'field_profile'))
            ));

            $profile = array();
            $profile_fields = array();
            foreach ($options->toarray() as $opt) {
                $selectOption = Engine_Api::_()->getDBTable('metas', 'sitead')->getFields($opt['option_id']);

                // ELEMENTS OF PROFILE TYPE SPECIFY
                $profile_field_ids = array();
                foreach ($selectOption as $key => $fieldvalue) {
                    if (in_array($fieldvalue['type'], $not_addType))
                        continue;
                    $profile_field_ids[] = $key;
                }

                $profile_targeting_ids = array_intersect($req_field_id, $profile_field_ids);
                $fieldSetKey = array();
                if (!empty($profile_targeting_ids)) {
                    foreach ($structure as $map) {
                        $field = $map->getChild();
                        $index++;

                        if (!in_array($field->field_id, $profile_targeting_ids)) {
                            continue;
                        }
                        // Get key
                        $key = null;
                        $key = sprintf('field_%d', $field->field_id);

                        // Get params
                        $values = $field->getElementParams('user', array('required' => false));

                        if (!@is_array($values['options']['attribs'])) {
                            $values['options']['attribs'] = array();
                        }
                        // Remove some stuff
                        unset($values['options']['required']);
                        unset($values['options']['allowEmpty']);
                        unset($values['options']['validators']);
                        // Change order
                        $values['options']['order'] = $index;
                        // Get generic type
                        $info = Engine_Api::_()->fields()->getFieldInfo($field->type);
                        $genericType = null;
                        if (!empty($info['base'])) {
                            $genericType = $info['base'];
                        } else {
                            $genericType = $field->type;
                        }
                        $values['type'] = $genericType; // For now
                        //change into multicheckbox
                        if ($field->type == 'radio' || $field->type == 'multiselect' || $field->type == 'multi_checkbox') {
                            if($field->type == 'multiselect' || $field->type == 'multi_checkbox') {
                                $genericType = $values['type'] = 'MultiCheckbox';
                            }
                            if (empty($values['options']['multiOptions']['']))
                                unset($values['options']['multiOptions']['']);
                            if (count(@$values['options']['multiOptions']) <= 0) {
                                continue;
                            }
                            $listFieldValue[$key] = $values['options']['multiOptions'];
                        }

                        if ($field->type == 'ethnicity' || $field->type == 'looking_for' || $field->type == 'partner_gender' || $field->type == 'relationship_status' || $field->type == 'occupation' || $field->type == 'religion' || $field->type == 'zodiac' || $field->type == 'weight' || $field->type == 'political_views') {
                          $genericType = $values['type'] = 'MultiCheckbox';

                          if (empty($values['options']['multiOptions']['']))
                            unset($values['options']['multiOptions']['']);

                        $listFieldValue[$key] = $values['options']['multiOptions'];
                    }

                        if (in_array($field->type, $addDiscription)) {
                            $values['options']['description'] = "Separate multiple entries with commas.";
                        }

                        $profile[$opt['option_id']] = $opt['label'];
                        $profile_fields[$opt['option_id']][] = $key;
                        $eLabel[$key]['lable'] = $values['options']['label'];
                        $eLabel[$key]['field_id'] = $field->field_id;
                        $eLabel[$key]['type'] = $values['type'];
                        unset($values['options']['order']);


                        // Hacks
                        switch ($genericType) {

                            // Select types
                            case 'select':
                            case 'radio':
                            case 'multiselect':
                            case 'multi_checkbox':
                                // Ignore if there is only one option
                                if (count(@$values['options']['multiOptions']) <= 0) {
                                    continue;
                                }
                                if (count(@$values['options']['multiOptions']) <= 1 && isset($values['options']['multiOptions'][''])) {
                                    continue;
                                }
                                $listFieldValue[$key] = $values['options']['multiOptions'];
                                // $values['type'] = 'MultiCheckbox';
                                $this->addElement($values['type'], $key, $values['options']);

                                break;
                            // Normal
                            default:
                                $this->addElement($values['type'], $key, $values['options']);
                                break;
                        }
                        if (in_array($field->type, $addDiscription)) {
                            $this->$key->getDecorator("Description")->setOption("placement", "append");
                        }
                        $element = $this->$key;
                        $fieldElements[$key] = $element;

                        $fieldSetKey[] = $key;
                    }
                }
                if (!empty($fieldSetKey)) {
                    $this->addDisplayGroup($fieldSetKey, 'group' . $opt['option_id']);
                    $button_group = $this->getDisplayGroup('group' . $opt['option_id']);
                    $button_group->setDecorators(array(
                        'FormElements',
                        'Fieldset',
                        array('HtmlTag', array('tag' => 'div', 'id' => 'group_' . $opt['option_id']))
                    ));
                }
            }

            $multiOptionAge = array('' => 'Any');
            for ($i = 13; $i <= 100; $i++) {
                $multiOptionAge[$i] = $i;
            }
            $age_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.age', 0);

            if (!empty($age_enable) && $enableTarget) {

                $this->addElement('Dummy', 'ad_heading_age_targeting', array(
                    'content' => '<h3>' . 'Age Targeting' . '</h3>',
                ));

                $subform = new Zend_Form_SubForm(array(
                    'description' => 'Age',
                    'decorators' => array(
                        'FormElements',
                        array('Description', array('placement' => 'PREPEND', 'tag' => 'div', 'class' => 'form-label')),
                        array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper'))
                    )
                ));
                $subform->addElement('Select', 'min', array(
                    'label' => 'Age',
                    'multiOptions' => $multiOptionAge,
                    'decorators' => array('ViewHelper', array('HtmlTag', array('tag' => 'div', 'class' => 'form-element form-element-age'))),
                ));
                $subform->addElement('Select', 'max', array(
                    'label' => 'Age',
                    'multiOptions' => $multiOptionAge,
                    'decorators' => array('ViewHelper', array('HtmlTag', array('tag' => 'div', 'class' => 'form-element form-element-age'))),
                ));
                $subform->addDecorator('HtmlTag', array('tag' => 'fieldset', 'id' => 'group_age', 'class' => 'form-wrapper'));
                $this->addSubForm($subform, 'birthdate');
            }
            $birthday_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.birthday', 0);
            // Element Birthday Enable
            if (!empty($birthday_enable) && $enableTarget) {

                $this->addElement('Dummy', 'ad_heading_bday_targeting', array(
                    'content' => '<h3>' . 'Birthday Targeting' . '</h3>',
                ));

                $this->addElement('Checkbox', 'birthday_enable', array(
                    'label' => 'Target people having their birthday on current date.',
                    'description' => 'Birthday',
                ));
                $this->addDisplayGroup(array('birthday_enable'), 'birthday-field');
                $birthdayField = $this->getDisplayGroup('birthday-field');
                $birthdayField->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag', array('tag' => 'div', 'id' => 'field_birthdate'))
                ));
            }

            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && $enableTarget && Engine_Api::_()->sitead()->hasNetworkOnSite()) {

                $this->addElement('Dummy', 'ad_heading_network_targeting', array(
                    'content' => '<h3>' . 'Network Targeting' . '</h3>',
                ));
                //Add network fields
                $this->addElement('Multiselect', 'networks', array(
                    'Label' => Zend_Registry::get('Zend_Translate')->_("Select Networks"),
                    'description' => Zend_Registry::get('Zend_Translate')->_('Networks based advanced targeting enables you to target your ad to users of specific networks. Select the networks, separated by commas, to which you want your ad to be targeted, using the auto-suggest box below. To reach all the networks, simply leave the box empty.'),
                    'attribs' => array('style' => 'height:100px; '),
                    'multiOptions' => $this->getNetworkLists()
                ));
                $eLabel['networks']['lable'] = Zend_Registry::get('Zend_Translate')->_('Networks');
                $listFieldValue['networks'] = $this->getNetworkLists();
                $listFieldValuekey['networks']['key'] = 'networks';
                $eLabel['networks']['type'] = 'Multiselect';
                $this->addDisplayGroup(array('networks'), 'field-network');
                $networkField = $this->getDisplayGroup('field-network');
                $networkField->setDecorators(array(
                    'FormElements',
                    'Fieldset',
                    array('HtmlTag', array('tag' => 'div', 'id' => 'field_network'))
                ));
            }
            if (!empty($profile)) {
                ksort($profile);
                $this->getElement('profile')
                        ->setMultiOptions($profile)
                        ->setValue($first_key);
            }
            if (count($profile) <= 1) {
                $this->removeElement('profile');
            }
        }

        if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.location', 0) && $enableTarget) {

            $this->addElement('Dummy', 'ad_heading_location', array(
                'content' => '<h3>' . 'Location Targeting' . '</h3>',
            ));

            $this->addElement('Select', 'location_type', array(
                'label' => 'Location Type',
                'multiOptions' => array(
                    1 => 'Miles',
                    0 => 'Kilometre',
                ),
                'value' => 0,
            ));

            $this->addElement('text', 'location_distance', array(
                'label' => 'Location Distance',
            ));

            $this->addElement('Text', 'location', array(
                'label' => 'Location',
                'description' => 'Eg: Fairview Park, Berkeley, CA',
                'filters' => array(
                    'StripTags',
                    new Engine_Filter_Censor(),
            )));
            $this->location->getDecorator('Description')->setOption('placement', 'append');
            $this->addElement('Hidden', 'locationParams', array('order' => 991,));

            include_once APPLICATION_PATH . '/application/modules/Seaocore/Form/specificLocationElement.php';

            $this->addDisplayGroup(array(
                'location',
                'location_distance',
                'location_type',
                    ), 'sitead_location'
            );

            $ad_group = $this->getDisplayGroup('sitead_location');
            $ad_group->setDecorators(array(
                'FormElements',
                'Fieldset',
                array('HtmlTag', array('id' => 'sitead_location', 'class' => 'sitead_crate'))
            ));
        }

        $this->addElement('Dummy', 'ad_heading_schedule', array(
            'content' => '<h3>' . 'Scheduling' . '</h3>',
        ));

        $date = (string) date('Y-m-d');
        // Start Date
        $cads_start_date = new Engine_Form_Element_CalendarDateTime('cads_start_date');
        $cads_start_date->setLabel("Start Date");
        $cads_start_date->setAllowEmpty(false);
        $cads_start_date->setValue($date . ' 00:00:00');
        $cads_start_date->setOrder('994');
        $this->addElement($cads_start_date);

        //Enable End Date
        $this->addElement('Checkbox', 'enable_end_date', array(
            'label' => "Run my ad continuously from starting date till it expires.",
            'value' => 1,
            'order' => 995,
        ));

        // End Date
        $cads_end_date = new Engine_Form_Element_CalendarDateTime('cads_end_date');
        $cads_end_date->setLabel("End Date");
        $cads_end_date->setValue('0000-00-00 00:00:00');
        $cads_end_date->setOrder('996');
        $this->addElement($cads_end_date);

        $this->addElement('Button', 'continue_review', array(
            'label' => 'Save',
            'order' => 999,
            'ignore' => 'true',
        ));

        $this->addDisplayGroup(array(
            'cads_start_date',
            'enable_end_date',
            'cads_end_date',
                ), 'sitead_scheduling'
        );

        $ad_group = $this->getDisplayGroup('sitead_scheduling');
        $ad_group->setDecorators(array(
            'FormElements',
            'Fieldset',
            array('HtmlTag', array('id' => 'sitead_scheduling', 'class' => 'sitead_crate', 'order' => 997))
        ));
    }

    /**
     * Get network lists
     */
    public function getNetworkLists() {
        $table = Engine_Api::_()->getDbtable('networks', 'network');
        $select = $table->select()
                ->order('title ASC')
                ->where('hide = ?', 0);
        $lists = $table->fetchAll($select);
        $data = array();
        foreach ($lists as $network) {
            $data[$network->network_id] = $network->title;
        }
        return $data;
    }

}
