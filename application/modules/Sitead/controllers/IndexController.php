<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: IndexController.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_IndexController extends Core_Controller_Action_Standard {

    protected $_navigation;
    protected $_viewer;
    protected $_viewer_id;
    // Zend_Session_Namespace
    protected $_session;

    public function init() {

        $this->view->viewer = $this->_viewer = Engine_Api::_()->user()->getViewer();

        if (!$this->_helper->requireAuth()->setAuthParams('sitead', $this->_viewer, 'view')->isValid()) {
            return;
        }

        $this->view->viewer_id = $this->_viewer_id = $this->_viewer->getIdentity();

        // It will show the navigation bar.
        $this->view->navigation = $this->_navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_main');

        $id = $this->_getParam('id');
        $this->_session = new Zend_Session_Namespace('Payment_Userads');
    }

     /**
     * Show package list
     */
    public function indexAction() {

        if (!$this->_helper->requireUser()->isValid())
            return;

        if (!$this->_helper->requireAuth()->setAuthParams('sitead', null, 'create')->isValid())
            return;

        $this->view->is_ajax = $this->_getParam('is_ajax', 0);
        $adFormats = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.adformat');
        if(empty($adFormats))
           $adFormats =  array('carousel', 'image', 'video');
        $this->view->adFormats = $adFormats;
        $this->view->isAdvActivity = Engine_Api::_()->sitead()->isModuleEnabled('advancedactivity');
        $this->view->isPageEnabled = Engine_Api::_()->sitead()->isModuleEnabled('sitepage');
        $this->view->adTypes = Engine_Api::_()->getItemTable('sitead_adtype')->getEnableAdType();
        if ($this->view->is_ajax) {
            $user_level = $this->_viewer->level_id;

            $start_one = "'" . $user_level . "'";
            $start = "'" . $user_level . ",%'";
            $middile = "'%," . $user_level . ",%'";
            $end = "'%," . $user_level . "'";

            $table = Engine_Api::_()->getItemtable('package');
            $packages_select = $table->select()
                    ->where("level_id = 0 or level_id LIKE $start_one or level_id LIKE $start or level_id LIKE $middile or level_id LIKE $end ")
                    ->order('order ASC')
                    ->order('creation_date DESC')
                    ->where('enabled = 1');
            $this->view->ad_type = $this->_getParam('ad_type', 'website');
            $this->view->ad_format = $this->_getParam('ad_format', 'image');
            $packages_select->where($this->_getParam('ad_format', 'image') . ' = ?', 1);
            $packages_select->where('FIND_IN_SET(?, add_categories)', $this->_getParam('ad_type', 'website'));
            $mod_type = $this->_getParam('type', 0);
            $mod_id = $this->_getParam('type_id', 0);
            if (!empty($mod_type) && !empty($mod_id)) {
                $packages_select->where("urloption  LIKE ?", '%' . $mod_type . '%');
            }
            $paginator = Zend_Paginator::factory($packages_select);
            $this->view->paginator = $paginator->setCurrentPageNumber($this->_getParam('page'));
            $this->view->totalItem = $paginator->getTotalItemCount();
            $paginator->setItemCountPerPage(20);
        }

        if (isset($this->_session->package_adtype))
            unset($this->_session->package_adtype);
        if (isset($this->_session->package_adformat))
            unset($this->_session->package_adformat);

        $this->_session->package_adtype = $this->_getParam('ad_type', 'website');
        $this->_session->package_adformat = $this->_getParam('ad_format', 'image');

        //Start Coupon plugin work.
        $couponEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sitecoupon');
        if (!empty($couponEnabled)) {
            $modules_enabled = Engine_Api::_()->getApi('settings', 'core')->getSetting('modules.enabled');
            if (!empty($modules_enabled)) {
                $this->view->modules_enabled = unserialize($modules_enabled);
            }
        }
        //End coupon plugin work.

        $this->view->type_id = $this->_getParam('type_id', null);
        $this->view->type = $this->_getParam('type', null);
        if (!$this->view->is_ajax)
            $this->_helper->content->setEnabled();
    }
    
     /**
     * Create advertisement
     */
    public function createAction() {

        // RESOLVE XSS AUDITOR ERROR FOR OPERA AND COCO BROWSER
        $this->getResponse()->setHeader('X-XSS-Protection', 0);
        // Check auth
        if (!$this->_helper->requireUser()->isValid())
            return;

        if (!$this->_helper->requireAuth()->setAuthParams('sitead', null, 'create')->isValid())
            return;
        // Hack navigation
        foreach ($this->_navigation->getPages() as $page) {
            if ($page->route != 'sitead_listpackage')
                continue;
            $page->active = true;
            break;
        }
        $settings = Engine_Api::_()->getApi('settings', 'core');
        // GET PACKAGE IN THIS YOU WANT TO CREATE AD
        $this->view->package = $package = Engine_Api::_()->getItem('package', $this->_getParam('id'));
        if (empty($package)) {
            return $this->_forward('notfound', 'error', 'core');
        }
        $this->view->limitreached = 1;
        $totalPackageAd = Engine_Api::_()->sitead()->getTotalAdOfPackage($this->_viewer_id, $this->_getParam('id'));
        if ($package->allow_ad != 0) {
            if ($totalPackageAd >= $package->allow_ad) {
                return $this->view->limitreached = 0;
            }
        }
        $this->view->mode = $mode = 0;

        if (isset($this->_session->package_adtype)) {
            $this->view->ads_Type = $ads_Type = $this->_session->package_adtype;
        } else {
            $this->view->ads_Type = $ads_Type = 'website';
        }

        if (isset($this->_session->package_adformat)) {
            $this->view->ads_format = $ads_format = $this->_session->package_adformat;
        } else {
            $this->view->ads_format = $ads_format = 'image';
        }

        $this->view->showMarkerInDate = $this->showMarkerInDate();
        $this->view->modType = $mod_type = $this->_getParam('type', 0);
        $this->view->modId = $mod_id = $this->_getParam('type_id', 0);
        if (!empty($mod_type) && !empty($mod_id)) {
            $is_packagesupport = Engine_Api::_()->sitead()->is_packagesupport($this->_getParam('id', 0), $mod_type);
            // Package not support module type.
            if (empty($is_packagesupport)) {
                return $this->_forward('notfound', 'error', 'core');
            } else {
                $this->view->module_type = $mod_type;
                $this->view->module_id = $mod_id;
            }
        }

        if (!($package->level_id == 0 || in_array($this->_viewer->level_id, explode(",", $package->level_id)))) {
            return $this->_forward('notfound', 'error', 'core');
        }

        // MAKE AD  CAMPAIGN CREATE FORM PART
        $this->view->campform = $campform = new Sitead_Form_CreateCampaign();
        $this->view->actions = Engine_Api::_()->sitead()->getActivityList();

        // check if design faq and target faq are enabled
        $infopageTable = Engine_Api::_()->getItemTable('sitead_infopage');
        $this->view->target_faq = $target_faq = $infopageTable->fetchRow(array('faq = ?' => 3, 'status = ?' => 1))->status;

        $this->view->design_faq = $design_faq = $infopageTable->fetchRow(array('faq = ?' => 2, 'status = ?' => 1))->status;

        // MAKE AD CREATE FORM PART-1
        $this->view->form = $form = new Sitead_Form_Create(array('packageId' => $this->_getParam('id'), 'typeId' => $ads_Type, 'format' => $ads_format));

        // GET ENABLE MODULES AND CUSTOM FOR THIS PACKAGE
        $levels_prepared = Engine_Api::_()->sitead()->enabled_module_content($this->_getParam('id'));
        if (!empty($levels_prepared)) {
            $this->view->is_customAs_enabled = $levels_prepared[0];
            $this->view->is_moduleAds_enabled = $levels_prepared[2];
        }

        $this->view->profileSelect_id = 0;
        // SET VALUES IN FORM PART-1
        $form->owner_id->setValue($this->_viewer_id);
        $form->package_id->setValue($package['package_id']);
        // CHECH TARGETING IN ENABLE OR NOT FOR THIS PACKAGE
        $this->view->enableTarget = $enableTarget = $package['network'];
        $this->view->enabledSponsored = $package['sponsored'];
        $this->view->enabledFeatured = $package['featured'];

        // GET TARGET FIELDS WHICH ARE SELECTED FOR TARGETING
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
        $index = 0;
        $sitead_host = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
        $sitead_is_flag = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.flag.info', 0);

        /* -----------------
         * Targeting for Genric Fields
         */
        $count_profile = 0;
        $profile = array();
        $profile_fields = array();
        $this->view->showTargetingTitle = 0;
        if (!empty($enableTarget)) {
            // fields that are includeing for targeting
            $structure = Engine_Api::_()->getApi('core', 'sitead')->getFieldsStructureSearch('user');
            $options = Engine_Api::_()->getDBTable('options', 'sitead')->getAllProfileTypes();
            if (empty($options)) {
                return;
            }
            $count_profile = @count($options);
            // Start create targeting fields
            // ELEMENTS OF PROFILE TYPE SPECIFY

            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && $enableTarget && Engine_Api::_()->sitead()->hasNetworkOnSite()) {
                $eLabel['networks']['lable'] = Zend_Registry::get('Zend_Translate')->_('Networks');
                $listFieldValue['networks'] = $this->getNetworkLists();
                $listFieldValuekey['networks']['key'] = 'networks';
                $eLabel['networks']['type'] = 'Multiselect';
            }

            $profile = array();
            $profile_fields = array();
            foreach ($options->toarray() as $opt) {
                //$profile[$opt['option_id']] = $opt['label'];
                $selectOption = Engine_Api::_()->getDBTable('metas', 'sitead')->getFields($opt['option_id']);
                // ELEMENTS OF PROFILE TYPE SPECIFY
                $profile_field_ids = array();
                foreach ($selectOption as $key => $fieldvalue) {
                    if (in_array($fieldvalue['type'], $not_addType))
                        continue;
                    $profile_field_ids[] = $key;
                }

                $profile_targeting_ids = array_intersect($req_field_id, $profile_field_ids);
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
                        if ($field->type == 'select' || $field->type == 'radio' || $field->type == 'multiselect' || $field->type == 'multi_checkbox') {

                            // $genericType = $values['type'] = 'MultiCheckbox';

                            if (empty($values['options']['multiOptions']['']))
                                unset($values['options']['multiOptions']['']);
                            if (count(@$values['options']['multiOptions']) <= 0) {
                                continue;
                            }
                            $listFieldValue[$key] = $values['options']['multiOptions'];
                        }

                        $profile[$opt['option_id']] = $opt['label'];
                        $profile_fields[$opt['option_id']][] = $key;
                        $eLabel[$key]['lable'] = $values['options']['label'];
                        $eLabel[$key]['field_id'] = $field->field_id;
                        $eLabel[$key]['type'] = $values['type'];
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
                                $this->view->showTargetingTitle = 1;

                                break;
                            // Normal
                            default:
                                $this->view->showTargetingTitle = 1;
                                break;
                        }
                    }
                }
            }

            $birthday_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.birthday', 0);

            $age_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.age', 0);
            if (!empty($age_enable) && $enableTarget) {
                $eLabel['birthdate']['lable'] = Zend_Registry::get('Zend_Translate')->_('BirthDate');
                $eLabel['birthdate']['type'] = 'select';
            }
            if (!empty($profile)) {
                ksort($profile);
                $this->view->profileSelect_id = $first_key = key($profile);
            }
            if (count($profile) == 0) {
                $this->view->noProfile = true;
            }
        }
        if (empty($sitead_is_flag)) {
            $sitead_ads_field = convert_uuencode($sitead_host);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.ads.field', $sitead_ads_field);
        }

        $get_payment_settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.payment.ad', 0);

        $this->view->profile = $profile;
        $this->view->profileField = $profile_fields;
        $this->view->profileField = $profile_fields;
        $this->view->photoName = '';
        // Post form

        if ($this->getRequest()->isPost()) {
            // get the forms values
            $formValues = $_POST;
            echo "<pre>";
            //print_r($formValues);die;
            foreach ($profile_fields as $key => $profilefield) {
                foreach ($profilefield as $fieldUnset) {
                    if (empty($formValues[$fieldUnset]))
                        unset($formValues[$fieldUnset]);
                }
            }

            $userAdInfo = array();
            foreach ($formValues as $key => $value) {
                if (strpos($key, 'ads_') === 0) {
                    $userAdInfo[$key] = $value;
                    unset($formValues[$key]);
                }
            }
            if ($formValues['cmd_ad_type'] == 'boost')
                unset($formValues['cmd_ad_format']);

            $formValues['owner_id'] = $form->getValue('owner_id');
            // set values in form

            if ($formValues['cmd_ad_type'] != 'boost') {
                if (empty($formValues['campaign_name']) && !empty($formValues['campaign_id'])) {
                    return;
                }

                if (empty($formValues['web_url'])) {
                    return;
                }

                if (empty($formValues['web_name']) && empty($formValues['content_page'])) {
                    return;
                } elseif (empty($formValues['content_title']) && !empty($formValues['content_page'])) {
                    return;
                } else if (!empty($formValues['content_title']) && !empty($formValues['content_page'])) {
                    $formValues['web_name'] = $formValues['content_title'];
                }
                foreach ($userAdInfo as $key => $value) {
                    if ($value['enable'] == 1) {
                        if (empty($value['cads_url'])) {
                            return;
                        }

                        if (empty($value['cads_title'])) {
                            return;
                        }

                        if (empty($value['cads_body'])) {
                            return;
                        } else {
                            $value['cads_body'] = @substr($value['cads_body'], 0, (Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135) + 10));
                        }
                    }
                }

                if ($formValues['cmd_ad_format'] == 'carousel' && $formValues['show_card'] == 1) {
                    if (empty($formValues['card_title']))
                        return;
                    if (empty($formValues['card_url']))
                        return;
                }
            }
            if ($formValues['cmd_ad_type'] == 'boost')
                $formValues['web_name'] = 'Boost Feed';

            if (empty($formValues['cads_end_date']['date'])) {
                $formValues['enable_end_date'] = 1;
            }

            $tempform = array_merge($formValues, $userAdInfo);
            $form->populate($tempform);
            if (isset($formValues['resource_id']))
                $this->view->resource_id = $formValues['resource_id'];
            if (isset($formValues['resource_type']))
                $this->view->resource_type = $formValues['resource_type'];
            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && Engine_Api::_()->sitead()->hasNetworkOnSite() && $enableTarget) {
                if (isset($_POST['networks'])):
                    $network_Ids = (string) ( is_array($_POST['networks']) ? join(",", $_POST['networks']) : $_POST['networks'] );
                else:
                    $network_Ids = new Zend_Db_Expr('NULL');
                endif;
            }

            $eLabel_Keys = array_keys($eLabel);

            if (!empty($this->view->enableCountry)) {
                $formValues['country'] = $formValues['toValues'];
            }

            $saveTargetValue = array();
            foreach ($formValues as $key => $value) {
                if ($key == 'Age')
                    $key = 'birthdate';
                if (in_array($key, $eLabel_Keys)) {
                    if ($key !== 'gender' && $key != 'country' && isset($eLabel[$key]['type']) && $eLabel[$key]['type'] != 'gender') {
                        $saveTargetValue[$key] = (string) ( is_array($value) ? join(",", $value) : $value );
                    } else {
                        if (isset($listFieldValue[$key][$value]))
                            $saveTargetValue[$key] = (string) ($listFieldValue[$key][$value]);
                    }
                    if (!empty($value) && !(is_scalar($value))) {
                        $range_value = null;

                        $range = array();
                        foreach ($value as $subKey => $subValue)
                            $range[$subKey] = $subValue;
                        // For Range
                        if (isset($range['min']) && isset($range['max'])) {
                            if (is_scalar($range['min']) && is_scalar($range['max'])) {
                                if ((!empty($range['min']) && !empty($range['max']))) {
                                    if ($range['max'] < $range['min']) {
                                        $min = $range['max'];
                                        $max = $range['min'];
                                    } else {
                                        $min = $range['min'];
                                        $max = $range['max'];
                                    }
                                    $saveTargetValue['age_min'] = $min;
                                    $saveTargetValue['age_max'] = $max;
                                    if ($min < $max)
                                        $range_value = "Between the ages of " . $min . " and " . $max . " inclusive.";
                                    else
                                        $range_value = 'age of ' . $min;
                                }

                                if ((empty($range['min']) && !empty($range['max']))) {
                                    $saveTargetValue['age_max'] = $range['max'];
                                    $range_value = $range['max'] . " years old and younger";
                                }

                                if ((!empty($range['min']) && empty($range['max']))) {
                                    $saveTargetValue['age_min'] = $range['min'];
                                    $range_value = "age " . $range['min'] . " and older";
                                }
                            } else {
                                $min_date = $range['min']['month'] . " " . $range['min']['day'] . " " . $range['min']['year'];
                                $max_date = $range['max']['month'] . " " . $range['max']['day'] . " " . $range['max']['year'];
                                $range_value = $min_date . " to" . $max_date;
                            }
                        } else {
                            $range_value_str = array();
                            foreach ($range as $r) {
                                if (isset($listFieldValue[$key][$r]))
                                    $range_value_str[] = $listFieldValue[$key][$r];
                            }
                            $range_value = (string) join(",", $range_value_str);
                        }

                        $eLabel[$key]['value'] = $range_value;
                    } else {
                        if (isset($eLabel[$key]['type']) && ($eLabel[$key]['type'] == 'select' || $eLabel[$key]['type'] == 'multi_select' || $eLabel[$key]['type'] == 'multi_checkbox' || $eLabel[$key]['type'] == 'gender')) {

                            if (!empty($value))
                                $value = $listFieldValue[$key][$value];
                            else
                                $value = '';
                        }elseif (isset($eLabel[$key]['type']) && $eLabel[$key]['type'] == 'checkbox') {
                            if (!empty($value))
                                $value = 'enable';
                        }
                        $eLabel[$key]['value'] = $value;
                    }
                }
            }

            $result = array();
            foreach ($eLabel as $key => $Values) {
                if (isset($Values['value']))
                    $result[$key] = $Values['value'];
            }
            $result = array_merge($result, $formValues);

            $result['cads_start_date'] = $form->cads_start_date->getValue();
            $result['cads_end_date'] = $form->cads_end_date->getValue();
            // package base value
            $result['sponsored'] = $package['sponsored'];
            $result['featured'] = $package['featured'];

            $result['public'] = $package['public'];
            $result['price_model'] = $package['price_model'];

            $approved = 0;
            if ($package->isFree())
                $approved = $package['auto_aprove'];

            $result['approved'] = $approved;
            $result['status'] = $approved;
            $result['enable'] = $approved;
            // approved and free package
            if (!empty($approved) && $package->isFree()) {
                $result['approve_date'] = date('Y-m-d H:i:s');
                if ($package['price_model'] == 'Pay/click')
                    $result['limit_click'] = $package['model_detail'];

                if ($package['price_model'] == 'Pay/view')
                    $result['limit_view'] = $package['model_detail'];

                if ($package['price_model'] == 'Pay/period') {
                    $result['model_value'] = $package['model_detail'];
                    $expiry = $result['model_value'];
                    if ($expiry == '-1')
                        $result['expiry_date'] = '2250-01-01';
                    else
                        $result['expiry_date'] = Engine_Api::_()->sitead()->getExpiryDate($expiry);
                }
            }
            if ($package->isFree())
                $result['payment_status'] = 'free';
            else
                $result['payment_status'] = 'initial';

            $userAdCardInfo = array();
            foreach ($formValues as $key => $value) {
                if (strpos($key, 'card_') === 0) {
                    $userAdCardInfo[$key] = $value;
                    unset($formValues[$key]);
                }
            }

            $adsSave = Engine_Api::_()->sitead()->saveUserAd($result);
            $saveTargetValue['userad_id'] = $adsSave->userad_id;

            //save adInfo
            if ($_POST['cmd_ad_type'] != 'boost') {
                $i = 1;
                foreach ($userAdInfo as $value) {
                    $value['userad_id'] = $adsSave->userad_id;
                    $adsInfoSave = Engine_Api::_()->sitead()->saveUserAdInfo($value, $i++);
                }

                if ($formValues['cmd_ad_format'] == 'carousel' && $formValues['show_card'] == 1) {
                    $userAdCardInfo['userad_id'] = $adsSave->userad_id;
                    $adsCardInfoSave = Engine_Api::_()->sitead()->saveAdCardInfo($userAdCardInfo);
                }
            }

            foreach ($saveTargetValue as $rKey => $rVal) {
                if (empty($rVal))
                    unset($saveTargetValue[$rKey]);
            }

            if (!empty($birthday_enable) && $enableTarget) {
                if (isset($formValues['birthday_enable']))
                    $saveTargetValue['birthday_enable'] = $formValues['birthday_enable'];
                else
                    $saveTargetValue['birthday_enable'] = 0;
            }

            // Save the targeting values for advertizing
            $targetFields = Engine_Api::_()->getDbtable('adtargets', 'sitead')->setUserAdTargets($saveTargetValue);
            // Ad is belong to free package then redirect to ad view ad details page
            if ($package->isFree()) {
                return $this->_helper->redirector->gotoRoute(array('ad_id' => $adsSave->userad_id, "state" => 'saved'), 'sitead_userad', true);
            } else {
                // Ad is belong to payment package then redirect to payment page
                $this->_session->userad_id = $adsSave->userad_id;
                return $this->_helper->redirector->gotoRoute(array(), 'sitead_payment', true);
            }

            if (isset($formValues['profile']) && !empty($formValues['profile']) && $count_profile > 1) {
                $this->view->profileEnable = $profile[$formValues['profile']];
            } else {
                $this->view->profileEnable = '';
            }
        }
        $this->view->eLabel = $eLabel;
        $this->_helper->content->setEnabled();
    }

     /**
     * Show package detail
     */
    public function packgeDetailAction() {
        $id = $this->_getParam('id');
        $onlydetails = $this->_getParam('onlydetails', 0);
        $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();

        if (empty($onlydetails)) {
            if (!empty($this->_viewer_id))
                $user_level = $this->_viewer->level_id;
            else
                $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();
            $this->view->can_create = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'create');
        }else {
            $this->view->can_create = 0;
        }
        $table = Engine_Api::_()->getDbtable('packages', 'sitead');
        $rName = $table->info('name');
        $package_select = $table->select()
                ->where('package_id = ?', $id);
        $this->view->package = $table->fetchAll($package_select);
    }

     /**
     * Active/ Pause User averisement
     */
    public function enabledAction() {
        $id = $this->_getParam('id');
        $userads = Engine_Api::_()->getItem('userads', $id);


        if (!empty($this->_viewer_id))
            $user_level = $this->_viewer->level_id;
        else
            $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();

        $can_edit = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'edit');

        if (empty($can_edit) || ($can_edit == 1 && $userads->owner_id != $this->_viewer_id)) {
            return $this->_forward('requireauth', 'error', 'core');
        }
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();
        try {

            $userads->enable = !$userads->enable;
            // CHANGE STATUS
            if ($userads->enable)
                $userads->status = 1;
            else
                $userads->status = 2;
            $userads->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_helper->redirector->gotoRoute(array('adcampaign_id' => $userads->campaign_id), 'sitead_ads', true);
    }

     /**
     * Delete Adverisement
     */
    public function deleteadAction() {

        if (!$this->_helper->requireUser()->isValid())
            return;

        $id = $this->_getParam('id');
        $userads = Engine_Api::_()->getItem('userads', $id);
        $this->view->camp_id = $userads->campaign_id;
        if (!Engine_Api::_()->core()->hasSubject('sitead')) {
            if (Engine_Api::_()->core()->hasSubject())
                Engine_Api::_()->core()->clearSubject();
            Engine_Api::_()->core()->setSubject($userads);
        }

        // Check auth
        if (!empty($this->_viewer_id))
            $user_level = $this->_viewer->level_id;
        else
            $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();

        $can_delete = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'delete');

        if (empty($can_delete) || ($can_delete == 1 && $userads->owner_id != $this->_viewer_id)) {
            return $this->_forward('requireauth', 'error', 'core');
        }

        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $userads->enable = 0;
                $userads->status = 4;
                $userads->save();
                $db->commit();
                $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('Successsfully deleted  Ad .')
                ));
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
    }

     /**
     * Delete campaign
     */
    public function deletecampAction() {
        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->adcampaign_id = $id = $this->_getParam('id');
        $camp = Engine_Api::_()->getItem('adcampaign', $id);
        $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();
        if (!empty($this->_viewer_id))
            $user_level = $this->_viewer->level_id;

        $can_delete = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'delete');

        if (empty($can_delete) || ($can_delete == 1 && $camp->owner_id != $this->_viewer_id)) {
            return $this->_forward('requireauth', 'error', 'core');
        }
        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $camp->delete();
                $db->commit();
                $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('Successsfully deleted  campaign title.')
                ));
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
    }

     /**
     * More Campiagns Delete
     */
    public function deleteselectedcampAction() {
        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->ids = $ids = $this->_getParam('ids', null);
        $confirm = $this->_getParam('confirm', false);
        $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();
        if (!empty($this->_viewer_id))
            $user_level = $this->_viewer->level_id;

        $can_delete = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'delete');

        if (empty($can_delete)) {
            return $this->_forward('requireauth', 'error', 'core');
        }
        $this->view->count = count(explode(",", $ids));

        // Save values
        if ($this->getRequest()->isPost() && $confirm == true) {
            $ids_array = explode(",", $ids);
            foreach ($ids_array as $id) {
                $camp = Engine_Api::_()->getItem('adcampaign', $id);
                if ($camp)
                    $camp->delete();
            }
            $this->_helper->redirector->gotoRoute(array(), 'sitead_campaigns', true);
        }
        
    }

     /**
     * Edit Campaign title
     */
    public function editcampAction() {
        if (!$this->_helper->requireUser()->isValid())
            return;
        $id = $this->_getParam('id');

        $this->view->adcampaign_id = $id;
        $camp = Engine_Api::_()->getItem('adcampaign', $id);

        $this->view->camp_title = $camp->name;

        if (!empty($this->_viewer_id))
            $user_level = $this->_viewer->level_id;
        else
            $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();

        $can_edit = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'edit');

        if (empty($can_edit) || ($can_edit == 1 && $camp->owner_id != $this->_viewer_id)) {
            return $this->_forward('requireauth', 'error', 'core');
        }
        // Check post
        if ($this->getRequest()->isPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            try {
                $camp->name = $_POST['name'];
                $camp->save();
                $db->commit();
                $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 100,
                    'parentRefresh' => 10,
                    'messages' => array('')
                ));
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('Successsfully edit  campaign title.')
            ));
        }
    }

    /**
     * Edit Advertisement
     */
    public function editAction() {

        // Check auth
        if (!$this->_helper->requireUser()->isValid())
            return;

        $this->view->userAds_id = $id = $this->_getParam('id');
        $this->view->userAds = $userads = Engine_Api::_()->getItem('userads', $id);
        if (empty($userads)) {
            return $this->_forward('notfound', 'error', 'core');
        }
        foreach ($this->_navigation->getPages() as $page) {
            if ($page->route != 'sitead_listpackage')
                continue;
            $page->active = true;
            break;
        }

        $this->view->ads_Type = $ads_Type = $userads->cmd_ad_type;
        $this->view->ads_format = $ads_format = $userads->cmd_ad_format;
        $this->view->showMarkerInDate = $this->showMarkerInDate();
        $this->view->ads_Type = $ads_Type = 'website';
        $this->view->is_photo_id = $userads->photo_id;

        $subModules = array();

        if (!empty($userads->resource_type)) {
            $content_array = Engine_Api::_()->sitead()->resource_content($userads->resource_type, 1, 'edit', $userads->resource_id, $userads->owner_id);
            foreach ($content_array as $module) {
                $str .= $module['title'] . '_' . $module['id'] . '::';
            }
            $str = trim($str, '::');
            $str = str_replace("'", '"', $str);

            $this->view->edit_sub_title = $str;
            $this->view->resource_id = $userads->resource_id;
        }

        $settings = Engine_Api::_()->getApi('settings', 'core');
        // GET PACKAGE FOR ADVERTIESMENT
        $this->view->package = $package = Engine_Api::_()->getItem('package', $userads->package_id);

        if (empty($package)) {
            return $this->_forward('notfound', 'error', 'core');
        }


        if (null !== ($copy = $this->_getParam('copy'))) {
            $level_id = $this->_viewer->level_id;
        } else {
            $level_id = $userads->getOwner()->level_id;
        }

        if (!($package->level_id == 0 || in_array($level_id, explode(",", $package->level_id)))) {
            return $this->_forward('notfound', 'error', 'core');
        }
        // Copy Ad
        if (null !== ($copy = $this->_getParam('copy'))) {

            if (!$this->_helper->requireAuth()->setAuthParams('sitead', null, 'create')->isValid())
                return;
            $this->view->copy = $copy;
            if ($userads->owner_id != $this->_viewer_id) {
                return $this->_forward('requireauth', 'error', 'core');
            }

            if (empty($package->enabled)) {
                return $this->_forward('notfound', 'error', 'core');
            }
        } else { // Edit Ad
            if (!$this->_helper->requireAuth()->setAuthParams('sitead', null, 'edit')->isValid())
                return;
            $this->view->copy = $copy = null;

            if (!empty($this->_viewer_id))
                $user_level = $this->_viewer->level_id;
            else
                $user_level = Engine_Api::_()->sitead()->getPublicUserLevel();

            $can_edit = Engine_Api::_()->authorization()->getPermission($user_level, 'sitead', 'edit');

            if (empty($can_edit) || ($can_edit == 1 && $userads->owner_id != $this->_viewer_id)) {
                return $this->_forward('requireauth', 'error', 'core');
            }
        }

        // check if design faq and target faq are enabled
        $infopageTable = Engine_Api::_()->getItemTable('sitead_infopage');
        $this->view->target_faq = $target_faq = $infopageTable->fetchRow(array('faq = ?' => 3, 'status = ?' => 1))->status;

        $this->view->design_faq = $design_faq = $infopageTable->fetchRow(array('faq = ?' => 2, 'status = ?' => 1))->status;

        if ($copy) {
            // FOR COPY
            $this->view->form = $form = new Sitead_Form_Create(array(
                'item' => $userads, 'packageId' => $userads->package_id, 'copy' => '1'
            ));
        } else {
            // FOR EDIT
            $this->view->form = $form = new Sitead_Form_Create(array(
                'item' => $userads, 'packageId' => $userads->package_id, 'typeId' => $userads->cmd_ad_type, 'format' => $userads->cmd_ad_format
            ));
        }

        $siteadinfo_table = Engine_Api::_()->getItemTable('sitead_adsinfo');
        $siteadinfo_table_name = $siteadinfo_table->info('name');
        $siteadinfo_select = $siteadinfo_table->select()
                ->setIntegrityCheck(false)
                ->from($siteadinfo_table_name, array($siteadinfo_table_name . '.*'))
                ->where("userad_id =?", $id);

        $fetch_site_adsinfo = $siteadinfo_select->query()->fetchAll();
        $site_adsinfo = $siteadinfo_table->fetchAll($siteadinfo_select);
        $this->view->slides_count = count($site_adsinfo);

        $num = 1;
        foreach ($fetch_site_adsinfo as $value) {
            if (empty($value['type'])) {
                $subForms = $form->getSubForm('ads_' . $num);
                $subForms->populate($value);
                $num++;
            } else {
                $form->card_title->setValue($value['cads_body']);
                $form->card_url->setValue($value['cads_url']);
                $form->show_card->setValue($value['type']);
                $this->view->slides_count = count($site_adsinfo) - 1;
                $this->view->isCarouselCard = 1;
            }
        }

        $this->view->is_edit = true;
        $this->view->useradsinfo_array = $site_adsinfo;

        foreach ($site_adsinfo as $adsinfo) {
            if ($userads->cmd_ad_format == 'video') {
                if (!empty($adsinfo->file_id)) {
                    $storage_file = Engine_Api::_()->getItem('storage_file', $adsinfo->file_id);
                    if ($storage_file) {
                        $this->view->video_location = $storage_file->map();
                    }
                }
            }
        }

        // GET ENABLE MODULES SET BY ADMIN
        $levels_prepared = Engine_Api::_()->sitead()->enabled_module_content($userads->package_id);
        if (!empty($levels_prepared)) {
            $this->view->is_customAs_enabled = $levels_prepared[0];
            $this->view->is_moduleAds_enabled = $levels_prepared[2];
        }

        $this->view->notshowapprovedMessage = $package['auto_aprove'];
        $this->view->mode = $mode = 1;
        // SET VALUES BASE ON PACKAGE
        $form->owner_id->setValue($this->_viewer_id);
        $form->package_id->setValue($package['package_id']);
        $this->view->enableTarget = $enableTarget = $package['network'];

        $this->view->enableCountry = 0;
        $this->view->profileSelect_id = 0;

        $this->view->topLevelId = $topLevelId = 0;
        $this->view->topLevelValue = $topLevelValue = null;
        // GET TARGET FIELDS WHICH ARE SELECTED FOR TARGETING
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
        $index = 0;
        $sitead_host = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
        $sitead_is_flag = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.flag.info', 0);

        /* -----------------
         * Targeting for Genric Fields
         */
        $count_profile = 0;
        $profile = array();
        $profile_fields = array();
        $this->view->showTargetingTitle = 0;
        if (!empty($enableTarget)) {
            // fields that are includeing for targeting
            $structure = Engine_Api::_()->getApi('core', 'sitead')->getFieldsStructureSearch('user');
            $options = Engine_Api::_()->getDBTable('options', 'sitead')->getAllProfileTypes();
            if (empty($options)) {
                return;
            }
            $count_profile = @count($options);
            // Start create targeting fields
            // ELEMENTS OF PROFILE TYPE SPECIFY

            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && $enableTarget && Engine_Api::_()->sitead()->hasNetworkOnSite()) {
                $eLabel['networks']['lable'] = Zend_Registry::get('Zend_Translate')->_('Networks');
                $listFieldValue['networks'] = $this->getNetworkLists();
                $listFieldValuekey['networks']['key'] = 'networks';
                $eLabel['networks']['type'] = 'Multiselect';
            }

            $profile = array();
            $profile_fields = array();
            foreach ($options->toarray() as $opt) {
                //$profile[$opt['option_id']] = $opt['label'];
                $selectOption = Engine_Api::_()->getDBTable('metas', 'sitead')->getFields($opt['option_id']);
                // ELEMENTS OF PROFILE TYPE SPECIFY
                $profile_field_ids = array();
                foreach ($selectOption as $key => $fieldvalue) {
                    if (in_array($fieldvalue['type'], $not_addType))
                        continue;
                    $profile_field_ids[] = $key;
                }

                $profile_targeting_ids = array_intersect($req_field_id, $profile_field_ids);

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
                        if ($field->type == 'select' || $field->type == 'radio' || $field->type == 'multiselect' || $field->type == 'multi_checkbox') {
                            if (empty($values['options']['multiOptions']['']))
                                unset($values['options']['multiOptions']['']);
                            if (count(@$values['options']['multiOptions']) <= 0) {
                                continue;
                            }
                            $listFieldValue[$key] = $values['options']['multiOptions'];
                        }

                        $profile[$opt['option_id']] = $opt['label'];
                        $profile_fields[$opt['option_id']][] = $key;
                        $eLabel[$key]['lable'] = $values['options']['label'];
                        $eLabel[$key]['field_id'] = $field->field_id;
                        $eLabel[$key]['type'] = $values['type'];
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
                                $this->view->showTargetingTitle = 1;
                                break;
                            // Normal
                            default:
                                $this->view->showTargetingTitle = 1;
                                break;
                        }
                    }
                }
            }

            $birthday_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.birthday', 0);

            $age_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.age', 0);
            if (!empty($age_enable) && $enableTarget) {
                $eLabel['birthdate']['lable'] = Zend_Registry::get('Zend_Translate')->_('BirthDate');
                $eLabel['birthdate']['type'] = 'select';
            }
            if (!empty($profile)) {
                ksort($profile);
                $this->view->profileSelect_id = $first_key = key($profile);
            }
            if (count($profile) == 0) {
                $this->view->noProfile = true;
            }
        }

        if (empty($sitead_is_flag)) {
            $sitead_ads_field = convert_uuencode($sitead_host);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.ads.field', $sitead_ads_field);
        }

        $get_payment_settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.payment.ad', 0);
        $this->view->profile = $profile;
        $this->view->formField = $formField;
        $this->view->eLabel = $eLabel;
        $this->view->profileField = $profile_fields;

        $this->view->photoName = '';
        // set advertiesment values before edit
        if (!$this->getRequest()->isPost()) {
            $useradsArray = $userads->toarray();
            // if ($count_profile > 1)
            //     $this->view->profileSelect_id = $useradsArray['profile'];

            $useradsArray['name'] = $useradsArray['web_name'];

            if ($useradsArray['resource_id'] && $useradsArray['resource_type']) {
                $useradsArray['content_title'] = $useradsArray['web_name'];
            }
            $this->view->titileName = $useradsArray['web_name'];
            $useradsArray['enable_end_date'] = 1;
            if (!empty($useradsArray['cads_end_date']))
                $useradsArray['enable_end_date'] = 0;
            else
                unset($useradsArray['cads_end_date']);
            // Convert and re-populate times
            $start = strtotime($useradsArray['cads_start_date']);
            if (isset($useradsArray['cads_end_date'])) {
                $end = strtotime($useradsArray['cads_end_date']);
            }
            $oldTz = date_default_timezone_get();
            date_default_timezone_set($this->_viewer->timezone);
            $useradsArray['cads_start_date'] = date('Y-m-d H:i:s', $start);
            if (isset($useradsArray['cads_end_date'])) {
                $useradsArray['cads_end_date'] = date('Y-m-d H:i:s', $end);
            }
            date_default_timezone_set($oldTz);
            unset($useradsArray['weight']);
            // get tageting for this advertisment
            $userAdTargets = Engine_Api::_()->getDbtable('adtargets', 'sitead')->getUserAdTargets($id);

            // arrange targeting value for set in form
            if (!empty($userAdTargets)) {

                $userAdTargets = $userAdTargets->toarray();

                foreach ($userAdTargets as $tKey => $tValue) {
                    if (!isset($listFieldValuekey[$tKey]['key']))
                        $listFieldValuekey[$tKey]['key'] = new Zend_Db_Expr('NULL');;
                    if (in_array($tKey, array('ethnicity', 'looking_for', 'partner_gender', 'relationship_status', 'occupation', 'religion', 'zodiac', 'weight', 'political_views')) || $tKey == $listFieldValuekey[$tKey]['key']) {
                        $userAdTargets[$tKey] = explode(',', $tValue);
                    }
                }
                // for minimum age
                if (isset($userAdTargets['age_min']) && !empty($userAdTargets['age_min'])) {
                    $birthdateForm = $form->getSubForm('birthdate');
                    if ($birthdateForm) {
                        $age['min'] = $userAdTargets['age_min'];
                        $birthdateForm->populate($age);
                    }
                }
                // for maximum age
                if (isset($userAdTargets['age_max']) && !empty($userAdTargets['age_max'])) {
                    $birthdateForm = $form->getSubForm('birthdate');
                    if ($birthdateForm) {
                        $age['max'] = $userAdTargets['age_max'];
                        $birthdateForm->populate($age);
                    }
                }
                // for gender
                if (isset($userAdTargets['gender']) && !empty($userAdTargets['gender'])) {

                    foreach ($listFieldValue['gender'] as $keygender => $valuegender) {
                        if ($valuegender == $userAdTargets['gender'])
                            break;
                    }
                    $userAdTargets['gender'] = $keygender;
                }else if (!empty($gender_key)) {
                    if (isset($userAdTargets[$gender_key]) && !empty($userAdTargets[$gender_key])) {
                        foreach ($listFieldValue[$gender_key] as $keygender => $valuegender) {
                            if ($valuegender == $userAdTargets[$gender_key])
                                break;
                        }
                        $userAdTargets[$gender_key] = $keygender;
                    }
                }

                $useradsArray = array_merge($useradsArray, $userAdTargets);
            }

            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && Engine_Api::_()->sitead()->hasNetworkOnSite() && $enableTarget) {

                if (!empty($userAdTargets['networks'])) {
                    $useradsArray['networks'] = $userAdTargets['networks']; //$networkList['title'];
                }
            }
            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.location', 0) && $enableTarget) {
                 $userAdLocation = Engine_Api::_()->getDbtable('locations', 'sitead')->getUserAdLocation($id);
                 if(!empty($userAdLocation)) {
                 $userAdLocation = $userAdLocation->toarray();
                 $useradsArray = array_merge($useradsArray, $userAdLocation);
             }
            }
            $form->populate($useradsArray);
        }

        // post the form
        if ($this->getRequest()->isPost()) {

            $formValues = $_POST;
            if (empty($formValues['cads_end_date']['date'])) {
                $formValues['enable_end_date'] = 1;
            }
            // foreach ($profile_fields as $key => $profilefield) {
            //     foreach ($profilefield as $fieldUnset) {
            //         if (empty($formValues[$fieldUnset]))
            //             unset($formValues[$fieldUnset]);
            //     }
            // }

            $userAdInfo = array();
            $userAdCardInfo = array();
            foreach ($formValues as $key => $value) {
                if (strpos($key, 'ads_') === 0) {
                    $userAdInfo[$key] = $value;
                    unset($formValues[$key]);
                }
            }

            $num = 1;
            foreach ($site_adsinfo as $sitead) {
                if (empty($sitead['type'])) {
                    $userAdInfo['ads_' . $num]['adsinfo_id'] = $sitead->adsinfo_id;
                    $num++;
                } else {
                    $userAdCardInfo['adsinfo_id'] = $sitead->adsinfo_id;
                }
            }

            $formValues['owner_id'] = $form->getValue('owner_id');

            if (empty($formValues['campaign_name']) && !empty($formValues['campaign_id'])) {
                return;
            }

            // check validation
            // Url is empty
            if (empty($formValues['web_url'])) {
                return;
            }

            if (empty($formValues['web_name']) && empty($formValues['content_page'])) {
                return;
            } elseif (empty($formValues['content_title']) && !empty($formValues['content_page'])) {
                return;
            } else if (!empty($formValues['content_title']) && !empty($formValues['content_page'])) {
                $formValues['web_name'] = $formValues['content_title'];
            }

            foreach ($userAdInfo as $key => $value) {
                if ($value['enable'] == 1) {
                    if (empty($value['cads_url'])) {
                        return;
                    }

                    if (empty($value['cads_title'])) {
                        return;
                    }

                    if (empty($value['cads_body'])) {
                        return;
                    } else {
                        $value['cads_body'] = @substr($value['cads_body'], 0, (Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135) + 10));
                    }
                }
            }

            if ($formValues['cmd_ad_format'] == 'carousel' && $formValues['show_card'] == 1) {
                if (empty($formValues['card_title']))
                    return;
                if (empty($formValues['card_url']))
                    return;
            }

            $form->populate($formValues);

            if ((boolean) Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0) && Engine_Api::_()->sitead()->hasNetworkOnSite() && $enableTarget) {

                if (isset($_POST['networks'])):
                    $network_Ids = (string) ( is_array($_POST['networks']) ? join(",", $_POST['networks']) : $_POST['networks'] );
                else:
                    $network_Ids = new Zend_Db_Expr('NULL');
                endif;
            }

            $eLabel_Keys = array_keys($eLabel);
            $eLabel_Keys[] = 'Age';

            $saveTargetValue = array();
            // set the targeting values which are geting after post
            foreach ($formValues as $key => $value) {

                if ($key == 'Age')
                    $key = 'birthdate';

                if (in_array($key, $eLabel_Keys)) {
                    if ($key !== 'gender' && $key != 'country' && isset($eLabel[$key]['type']) && $eLabel[$key]['type'] != 'gender') {
                        print_r('fff');
                        $saveTargetValue[$key] = (string) ( is_array($value) ? join(",", $value) : $value );
                    } else {
                        if (isset($listFieldValue[$key][$value]))
                            $saveTargetValue[$key] = (string) ($listFieldValue[$key][$value]);
                    }

                    if (!empty($value) && !(is_scalar($value))) {
                        $range = array();
                        foreach ($value as $subKey => $subValue)
                            $range[$subKey] = $subValue;
                        $range_value = '';
                        // For Range value
                        if (isset($range['min']) && isset($range['max'])) {
                            if (is_scalar($range['min']) && is_scalar($range['max'])) {

                                if ((!empty($range['min']) && !empty($range['max']))) {
                                    if ($range['max'] < $range['min']) {
                                        $min = $range['max'];
                                        $max = $range['min'];
                                    } else {
                                        $min = $range['min'];
                                        $max = $range['max'];
                                    }

                                    $saveTargetValue['age_min'] = $min;
                                    $saveTargetValue['age_max'] = $max;
                                    if ($min < $max)
                                        $range_value = "Between the ages of " . $min . " and " . $max . " inclusive.";
                                    else
                                        $range_value = 'age of ' . $min;
                                }

                                if ((empty($range['min']) && !empty($range['max']))) {
                                    $saveTargetValue['age_max'] = $range['max'];
                                    $range_value = $range['max'] . " years old and younger";
                                }

                                if ((!empty($range['min']) && empty($range['max']))) {
                                    $saveTargetValue['age_min'] = $range['min'];
                                    $range_value = "age " . $range['min'] . " and older";
                                }
                            } else {
                                $min_date = $range['min']['month'] . " " . $range['min']['day'] . " " . $range['min']['year'];
                                $max_date = $range['max']['month'] . " " . $range['max']['day'] . " " . $range['max']['year'];
                                $range_value = $min_date . " to" . $max_date;
                            }
                        } else {
                            // For more than one value
                            $range_value_str = array();
                            foreach ($range as $r) {
                                if (isset($listFieldValue[$key][$r]))
                                    $range_value_str[] = $listFieldValue[$key][$r];
                            }
                            $range_value = (string) join(",", $range_value_str);
                        }
                        $eLabel[$key]['value'] = $range_value;
                    } else {
                        if (isset($eLabel[$key]['type']) && ($eLabel[$key]['type'] == 'select' || $eLabel[$key]['type'] == 'multi_checkbox' || $eLabel[$key]['type'] == 'gender' )) {

                            if (!empty($value))
                                $value = $listFieldValue[$key][$value];
                            else
                                $value = '';
                        }elseif (isset($eLabel[$key]['type']) && $eLabel[$key]['type'] == 'checkbox') {
                            if (!empty($value))
                                $value = 'enable';
                        }
                        $eLabel[$key]['value'] = $value;
                    }
                }
            }

            $result = array();
            foreach ($eLabel as $key => $Values) {
                if (isset($Values['value']))
                    $result[$key] = $Values['value'];
            }

            $result = array_merge($result, $formValues);

            $result['photoPath'] = $pathName;

            $result['cads_start_date'] = $form->cads_start_date->getValue();
            $result['cads_end_date'] = $form->cads_end_date->getValue();

            $result['photoPath'] = $pathName;

            // save values base on package
            $result['sponsored'] = $package['sponsored'];
            $result['featured'] = $package['featured'];

            $result['public'] = $package['public'];
            $result['price_model'] = $package['price_model'];


            if ($copy) {
                // for copy ad from other ads
                $approved = 0;
                if ($package->isFree())
                    $approved = $package['auto_aprove'];

                $result['enable'] = $result['status'] = $result['approved'] = $approved;


                $result['photo_id'] = $userads->photo_id;
                //For Free Package
                if (!empty($approved) && $package->isFree()) {
                    $result['approve_date'] = date('Y-m-d H:i:s');

                    //For Clicks Base
                    if ($package['price_model'] == 'Pay/click')
                        $result['limit_click'] = $package['model_detail'];

                    //For Views Base
                    if ($package['price_model'] == 'Pay/view')
                        $result['limit_view'] = $package['model_detail'];

                    //For Days Base
                    if ($package['price_model'] == 'Pay/period') {
                        $result['model_value'] = $package['model_detail'];
                        $expiry = $result['model_value'];
                        if ($expiry === '-1')
                            $result['expiry_date'] = '2250-01-01';
                        else
                            $result['expiry_date'] = Engine_Api::_()->sitead()->getExpiryDate($expiry);
                    }
                }

                if ($package->isFree())
                    $result['payment_status'] = 'free';
                else
                    $result['payment_status'] = 'initial';
            }else {
                // for edit
                $approved = 0;
                if ($package->isFree())
                    $approved = $package['auto_aprove'];
                elseif (!$package->isFree() && $userads->approved)
                    $approved = $package['auto_aprove'];

                $result['approved'] = $approved;

                $result['userad_id'] = $id;
                $result['campaign_id'] = $userads->campaign_id;

                if (!empty($approved) && $userads->status == 3 && !empty($userads->cads_end_date) && date('Y-m-d H:i:s', strtotime($userads->cads_end_date)) < date('Y-m-d H:i:s')) {
                    if ($userads->enable == 1)
                        $result['status'] = 1;
                    else
                        $result['status'] = 2;
                }

                unset($result['owner_id']);
                unset($result['create_date']);
            }


            foreach ($formValues as $key => $value) {
                if (strpos($key, 'card_') === 0) {
                    $userAdCardInfo[$key] = $value;
                    unset($formValues[$key]);
                }
            }

            //save ad
            $adsSave = Engine_Api::_()->sitead()->saveUserAd($result);

            $saveTargetValue['userad_id'] = $adsSave->userad_id;

            $i = 1;
            foreach ($userAdInfo as $value) {
                $value['userad_id'] = $adsSave->userad_id;
                $adsInfoSave = Engine_Api::_()->sitead()->saveUserAdInfo($value, $i++);
            }

            if ($formValues['cmd_ad_format'] == 'carousel' && $formValues['show_card'] == 1) {
                $userAdCardInfo['userad_id'] = $adsSave->userad_id;
                $adsCardInfoSave = Engine_Api::_()->sitead()->saveAdCardInfo($userAdCardInfo);
            }

            // targeting value
            foreach ($saveTargetValue as $rKey => $rVal) {
                if (empty($rVal))
                    $saveTargetValue[$rKey] = new Zend_Db_Expr('NULL');;
            }

            if (!empty($birthday_enable) && isset($formValues['birthday_enable'])) {
                $saveTargetValue['birthday_enable'] = $formValues['birthday_enable'];
            }

            if (!isset($saveTargetValue['age_max'])) {
                $saveTargetValue['age_max'] = new Zend_Db_Expr('NULL');
                ;
            }
            if (!isset($saveTargetValue['age_min'])) {
                $saveTargetValue['age_min'] = new Zend_Db_Expr('NULL');
                ;
            }

            if (!isset($saveTargetValue['birthday_enable'])) {
                $saveTargetValue['birthday_enable'] = 0;
            }

            if (!isset($saveTargetValue['networks']))
                $saveTargetValue['networks'] = new Zend_Db_Expr('NULL');

            $targetFields = Engine_Api::_()->getDbtable('adtargets', 'sitead')->setUserAdTargets($saveTargetValue);

            if ($package->isFree() || (!$package->isFree() && ( $adsSave->payment_status != 'initial' && $adsSave->payment_status != 'overdue' ))) {
                if ($copy)
                    $state = "saved";
                else
                    $state = "edit";
                return $this->_helper->redirector->gotoRoute(array('ad_id' => $adsSave->userad_id, "state" => $state), 'sitead_userad', true);
            } else {

                $this->_session->userad_id = $adsSave->userad_id;
                return $this->_helper->redirector->gotoRoute(array(), 'sitead_payment', true);
            }

            if (isset($formValues['profile']) && !empty($formValues['profile']) && $count_profile > 1) {
                $this->view->profileEnable = $profile[$formValues['profile']];
            } else {
                $this->view->profileEnable = '';
            }
        }
        $this->view->eLabel = $eLabel;
        $this->_helper->content->setEnabled();
    }

    /**
     * Renew free package
     */
    public function renewAction() {
        $id = $this->_getParam('id');
        $this->view->userad = $userads = Engine_Api::_()->getItem('userads', $id);
        $package_id = $userads->package_id;
        $this->view->package = $package_id = $package = Engine_Api::_()->getItem('package', $package_id);
        $can_renew = 0;
        switch ($userads->price_model) {
            // FOR VIEWS
            case "Pay/view":
                if ($userads->limit_view != -1) {
                    if ($package->renew_before >= $userads->limit_view) {
                        $can_renew = 1;
                    }
                }

                break;
            // FOR CLICKS
            case "Pay/click":
                if ($userads->limit_click != -1) {
                    if ($package->renew_before >= $userads->limit_click) {
                        $can_renew = 1;
                    }
                }
            // FOR DAYS
            case "Pay/period":
                $diff_days = 0;
                if (!empty($userads->expiry_date) && date('Y-m-d', strtotime($userads->expiry_date)) > date('Y-m-d')) {
                    $diff_days = round((strtotime($userads->expiry_date) - strtotime(date('Y-m-d'))) / 86400);
                }
                if (($userads->expiry_date !== '2250-01-01') || empty($userads->expiry_date)) {
                    if ($package->renew_before >= $diff_days) {
                        $can_renew = 1;
                    }
                }
                break;
        }

        if (empty($can_renew)) {
            return $this->_forward('requireauth', 'error', 'core');
        }
        if ($this->getRequest()->isPost()) {

            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $userad->featured = $package->featured;
                $userad->sponsored = $package->sponsored;
                switch ($userads->price_model) {
                    // FOR VIEWS
                    case "Pay/view":
                        if ($userads->limit_view != -1) {

                            if ($package->model_detail == -1)
                                $userads->limit_view = $package->model_detail;
                            else
                                $userads->limit_view += $package->model_detail;
                        }

                        break;
                    // FOR CLICKS
                    case "Pay/click":
                        if ($userads->limit_click != -1) {
                            if ($package->model_detail == -1)
                                $userads->limit_click = $package->model_detail;
                            else
                                $userads->limit_click += $package->model_detail;
                            break;
                        }
                    // FOR DAYS
                    case "Pay/period":
                        $diff_days = 0;
                        if (!empty($userads->expiry_date) && date('Y-m-d', strtotime($userads->expiry_date)) > date('Y-m-d')) {
                            $diff_days = round((strtotime($userads->expiry_date) - strtotime(date('Y-m-d'))) / 86400);
                        }

                        if (($userads->expiry_date !== '2250-01-01') || empty($userads->expiry_date)) {
                            if ($diff_days < 0)
                                $diff_days = 0;
                            if ($package->model_detail == -1) {
                                $userads->expiry_date = '2250-01-01';
                            } else {

                                $userads->expiry_date = Engine_Api::_()->sitead()->getExpiryDate($package->model_detail + $diff_days);
                            }
                        }
                        break;
                }
                $userads->status = 1;
                if (empty($approved)) {
                    $userads->approved = $package->auto_aprove;
                    $userads->enable = 1;
                }
                $userads->payment_status = 'free';
                $userads->save();
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('')
            ));
        }
        $this->renderScript('index/renew.tpl');
    }
    
     /**
     * Set user ad id in Session
     */
    public function setSessionAction() {
        $this->_session->userad_id = $_POST["ad_ids_session"];
        return $this->_helper->redirector->gotoRoute(array(), 'sitead_payment', true);;
    }

     /**
     * get Network list
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

    /**
     * Get network id using network title
     */
    public function getNetworksId($netowrkTitle) {
        $table = Engine_Api::_()->getDbtable('networks', 'network');
        $netowrkTitleStr = (string) ( is_array($netowrkTitle) ? "'" . join("', '", $netowrkTitle) . "'" : $netowrkTitle );
        $select = $table->select()
                ->from($table->info('name'), array('network_id', 'title'))
                ->where('title in(?)', new Zend_Db_Expr($netowrkTitleStr));
        $result = $table->fetchAll($select);
        $network_ids = array();
        $title = array();
        foreach ($result as $value) {
            $network_ids[] = $value->network_id;
            $title[] = $value->title;
        }
        $return_ids = array();
        if (!empty($network_ids)) {
            $return_ids['ids'] = (string) ( is_array($network_ids) ? join(",", $network_ids) : $network_ids );
            $return_ids['title'] = (string) ( is_array($title) ? join(", ", $title) : $title );
        }

        return $return_ids;
    }

     /**
     * Get network title using network id
     */
    public function getNetworksTitles($netowrkIds) {

        $netowrkIds = preg_split('/[,]+/', $netowrkIds);
        $netowrkIds = array_filter(array_map("trim", $netowrkIds));
        $table = Engine_Api::_()->getDbtable('networks', 'network');
        $idsStr = (string) ( is_array($netowrkIds) ? join(", ", $netowrkIds) : $netowrkIds );
        $select = $table->select()
                ->from($table->info('name'), array('network_id', 'title'))
                ->where('network_id in(?)', new Zend_Db_Expr($idsStr));

        $result = $table->fetchAll($select);
        $network_ids = array();
        $title = array();
        foreach ($result as $value) {
            $network_ids[] = $value->network_id;
            $title[] = $value->title;
        }
        $return_ids = array();
        if (!empty($title)) {
            $return_ids['ids'] = (string) ( is_array($network_ids) ? join(",", $network_ids) : $network_ids );
            $return_ids['title'] = (string) ( is_array($title) ? join(", ", $title) : $title );
        }
        return $return_ids;
    }

     /**
     * Show date format
     */
    public function showMarkerInDate() {
        $localeObject = Zend_Registry::get('Locale');
        $dateLocaleString = $localeObject->getTranslation('long', 'Date', $localeObject);
        $dateLocaleString = preg_replace('~\'[^\']+\'~', '', $dateLocaleString);
        $dateLocaleString = strtolower($dateLocaleString);
        $dateLocaleString = preg_replace('/[^ymd]/i', '', $dateLocaleString);
        $dateLocaleString = preg_replace(array('/y+/i', '/m+/i', '/d+/i'), array('y', 'm', 'd'), $dateLocaleString);
        $dateFormat = $dateLocaleString;
        return $dateFormat == "mdy" ? 1 : 0;
    }

     /**
     * Get Boost feed ads list when create boost type ad
     */
    public function getBoostPostFeedAction() {

        $this->view->action = $this->_getParam('id');
    }

}

?>
