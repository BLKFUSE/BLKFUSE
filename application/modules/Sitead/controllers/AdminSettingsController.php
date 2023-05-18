<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_AdminSettingsController extends Core_Controller_Action_Admin {

    public function indexAction() {

        if (!$this->getRequest()->isPost()) {
            $timeObj = new Zend_Date(time());
            $current_time = $timeObj->getTimestamp();
            $current_date = gmdate('Y-m-d', $current_time);
            $adstatisticscache_table = Engine_Api::_()->getDbTable('adstatisticscache', 'sitead');
            $adstatisticscache_table->removeStatisticsCache(array('response_date < ?' => $current_date));
        }
       
        $sitead_form_content = array('ad_char_title', 'ad_char_body', 'ad_show_menu', 'ad_saleteam_con', 'ad_saleteam_email', 'ad_board_limit', 'currency', 'advertise_benefit', 'submit', 'adboard_footer', 'adblock_create_link', 'adcancel_enable', 'show.adboard', 'show_adboard', 'sitead_view_limit', 'sitead_ad_type', 'dummy_sitead_title', 'dummy_story_title', 'dummy_general_title', 'story_char_title', 'custom_ad_url', 'ad_statistics_limit', 'sitead_package_view', 'dummy_sitead_package', 'sitead_package_information');

        // Save the Advertisment Type value in data base
        $getPostValue = $this->getRequest()->getPost();
        if (!empty($getPostValue) && array_key_exists('sitead_ad_type', $getPostValue)) {
            $adTypeArray = $getPostValue['sitead_ad_type'];
            Engine_Api::_()->getItemTable('sitead_adtype')->setSettings($adTypeArray);
            unset($getPostValue['sitead_ad_type']);
        }

        $showAdBoard = Engine_Api::_()->getApi('settings', 'core')->getSetting('show.adboard', 1);
    
        $this->createDir(APPLICATION_PATH . '/public/sitead');
        $this->createDir(APPLICATION_PATH . '/public/sitead/temporary');

        $update_table = Engine_Api::_()->getDbtable('menuItems', 'core');
        $update_name = $update_table->info('name');
        $check_table = $update_table->select()
                ->from($update_name, array('id'))
                ->where('name = ?', 'core_admin_main_plugins_sitead');
        $fetch_result = $check_table->query()->fetchAll();
        if (!empty($fetch_result)) {
            $update_table->update(array("params" => '{"route":"admin_default","module":"sitead","controller":"settings"}'), array('name =?' => 'core_admin_main_plugins_sitead'));
        }
        $product_type = 'sitead';
        $replace_container_temp = 0;

        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_main_settings');

        // generate the form
        $this->view->form = $form = new Sitead_Form_Admin_Global();
        
        $module_like = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));

        $c = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
        $sitead_values_array = array(
            'host_att' => $module_like,
            'auth_att' => 1,
            'is_att' => 1
        );
        $sitead_values_serialize = serialize($sitead_values_array);
        $sitead_values_encode = convert_uuencode($sitead_values_serialize);
        $sitead_ads_field = convert_uuencode($module_like);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.ads.field', $sitead_ads_field);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.multi.target', $sitead_values_encode);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.payment.ad', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.graph.type', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.temp.file', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.target.network', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.target.location', 1);

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $this->getRequest()->getPost();
            if (!empty($values)) {
                $is_error = 0;
                $is_error_slide = 0;
                // check for empty(zero) values  List_comment_widgets
                if ($values['ad_char_title'] == 0) {
                    $is_error = 1;
                } elseif ($values['ad_char_body'] == 0) {
                    $is_error = 1;
                } elseif ($values['ad_board_limit'] == 0) {
                    $is_error = 1;
                } elseif ($values['sitead_coreFeed_position'] == 0) {
                    $is_error = 1;
                }
                if ($is_error == 1) {
                    $error = $this->view->translate('Filled value can not be zero !');
                    $this->view->status = false;
                    $error = Zend_Registry::get('Zend_Translate')->_($error);

                    $form->getDecorator('errors')->setOption('escape', false);
                    $form->addError($error);
                    return;
                }
                if ($values['ad_slide_limit'] < 2) {
                    $is_error_slide = 1;
                }

                if ($is_error_slide == 1) {
                    $error = $this->view->translate('Minimum value of Carousel ad slides should be 2 !');
                    $this->view->status = false;
                    $error = Zend_Registry::get('Zend_Translate')->_($error);

                    $form->getDecorator('errors')->setOption('escape', false);
                    $form->addError($error);
                    return;
                }
                foreach ($values as $key => $value) {
                    if ($key == 'ad_show_menu') {
                        $menu_table = Engine_Api::_()->getDbtable('menuitems', 'core');
                        if ($value == 1) {
                            $menu_table->update(array('menu' => 'core_footer', 'plugin' => 'Sitead_Plugin_Menus::canViewAdvertiesment'), array('name =?' => 'core_main_sitead'));
                        } else if ($value == 3) {
                            $menu_table->update(array('menu' => 'core_main', 'plugin' => 'Sitead_Plugin_Menus::canViewAdvertiesment'), array('name =?' => 'core_main_sitead'));
                        } else if ($value == 2) {
                            $menu_table->update(array('menu' => 'core_mini', 'plugin' => 'Sitead_Plugin_Menus::canViewAdvertiesment'), array('name =?' => 'core_main_sitead'));
                        } else if (empty($value)) {
                            $menu_table->update(array('menu' => 'user_home', 'plugin' => 'Sitead_Plugin_Menus'), array('name =?' => 'core_main_sitead'));
                        }
                    }
                    if ($key == 'auth_module') {
                        $serializedArray = serialize($value);
                        Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead_adcreate', $serializedArray);
                    } else {
                        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
                    }
                }
                $form->addNotice('Your changes have been saved.');
            }
        }

        if ($this->getRequest()->isPost()) {

            if (!empty($_POST['sitead_package_information'])) {
                if (Engine_Api::_()->getApi('settings', 'core')->hasSetting('sitead_package_information')) {
                    Engine_Api::_()->getApi('settings', 'core')->removeSetting('sitead_package_information');
                }
                Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.package.information', $_POST['sitead_package_information']);
            }

            if (!empty($_POST['sitead_package_adformat'])) {
                if (Engine_Api::_()->getApi('settings', 'core')->hasSetting('sitead_package_adformat')) {
                    Engine_Api::_()->getApi('settings', 'core')->removeSetting('sitead_package_adformat');
                }
                Engine_Api::_()->getApi('settings', 'core')->setSetting('sitead.package.adformat', $_POST['sitead_package_adformat']);
            }
            $showChangeAdBoard = Engine_Api::_()->getApi('settings', 'core')->getSetting('show.adboard', 1);
            if ($showAdBoard != $showChangeAdBoard) {
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                $db->query("UPDATE `engine4_core_menuitems` SET `enabled` = '" . $showChangeAdBoard . "' WHERE `engine4_core_menuitems`.`name` ='sitead_main_adboard' AND `module` = 'sitead';
          ");
                if (!empty($showChangeAdBoard)) {
                    $db->query("UPDATE `engine4_core_menuitems` SET `plugin`='Sitead_Plugin_Menus::canViewAdvertiesment', `params` = '{\"route\":\"sitead_display\",\"action\":\"adboard\",\"controller\":\"display\"}' WHERE `engine4_core_menuitems`.`name` ='core_main_sitead' AND `module` = 'sitead';
            ");
                } else {
                    $db->query("UPDATE `engine4_core_menuitems` SET `plugin`='',`params` = '{\"route\":\"sitead_help_and_learnmore\",\"action\":\"help-and-learnmore\",\"controller\":\"display\"}' WHERE `engine4_core_menuitems`.`name` ='core_main_sitead' AND `module` = 'sitead';
            ");
                }
            }
        }

        if (!$this->getRequest()->isPost()) {
            Engine_Api::_()->getDbTable('adstatistics', 'sitead')->removeOldStatistics();
        }
    }

    /**
     * FAQ Action
     */
    public function faqAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_faq');
        $this->view->faq = 1;
        $this->view->faq_type = $this->_getParam('faq_type', 'general');
    }

    /**
     * Guidelines Action
     */
    public function guidelinesAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_widget_setting');
    }

    /**
     * Graph Action
     */
    public function graphAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_graph');

        $this->view->form = $form = new Sitead_Form_Admin_Settings_Graph();

        if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
            $values = $form->getValues();

            foreach ($values as $key => $value) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            }

            $form->addNotice('Your changes has been saved.');
        }
    }

    /**
     * Target Action
     */
    public function targetAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_target_settings');
        $targetTable = Engine_Api::_()->getItemTable('target');
        $tagetFields = $formElementsContent = Engine_Api::_()->sitead()->preFieldPkgTargetData();

        // Make form
        $this->view->form = $form = new Sitead_Form_Admin_Target();
        if (!$this->getRequest()->isPost())
            $form->populate($tagetFields);

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            if (!isset($values['target_birthday']))
                $values['target_birthday'] = 0;
            if (!isset($values['target_age']))
                $values['target_age'] = 0;

            if (!isset($values['site_target_network']))
                $values['site_target_network'] = 0;

            if (!isset($values['site_target_location']))
                $values['site_target_location'] = 0;

            Engine_Api::_()->getApi('settings', 'core')->setSetting('site.target.birthday', $values['target_birthday']);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('site.target.age', $values['target_age']);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('site.target.network', $values['site_target_network']);
            Engine_Api::_()->getApi('settings', 'core')->setSetting('site.target.location', $values['site_target_location']);


            $targetTable->delete(null);
            $checks = array();

            $field_ids = array(); //contain all the field_id to all the fields present
            $option_id = array(); //contains checked element option_id with normal key
            //Check and select elements are to be eleminated from data
            foreach ($values as $key => $value) {
                if (strstr($key, 'check') != null && $value) {
                    $tc = explode("check", $key);
                    $option_id[] = (int) $tc[0];
                    $checks[] = (int) $tc[1];
                }
            }

            for ($index = 0; $index < count($checks); $index++) {
                $targetTable->setVal($checks[$index], $option_id[$index]);
            }

            // ADD COLUMN IN USERADS TABLE
            $structure = Engine_Api::_()->getApi('core', 'sitead')->getFieldsStructureSearch('user');
            $key = array();

            foreach ($structure as $map) {
                $field = $map->getChild();

                if (!in_array($field->field_id, $checks)) {
                    continue;
                }
                $key[] = sprintf('field_%d', $field->field_id);
            }

            $data = array();
            $data = Engine_Api::_()->getApi('core', 'sitead')->getTargetColumns();

            $NULLBIRTHDAY = 0;
            $key = array_unique($key);
            if ((Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.age', 0)) == 0) {
                $NULLBIRTHDAY = 1;
            }
            $key = array_diff($key, array('target_age'));
            $remove = $key;
            $remove[] = 'age_min';
            $remove[] = 'age_max';
            $remove[] = 'adtarget_id';
            $remove[] = 'userad_id';
            $remove[] = 'birthday_enable';
            $remove[] = 'networks';

            $removeKey = array_diff($data, $remove);

            $adtargetTable = Engine_Api::_()->getDbtable('adtargets', 'sitead');
            $targetName = $adtargetTable->info('name');

            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            foreach ($removeKey as $field_name) {

                $alter_sql = "ALTER TABLE `" . $targetName . "` DROP `$field_name`";

                if (!($db->query($alter_sql))) {
                    echo "Error in running sql query.";
                }
            }

            if (!empty($NULLBIRTHDAY)) {
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                $age_aql = "UPDATE " . $adtargetTable->info("name") . " SET `age_min` = NULL, `age_max` = NULL ";
                $db->query($age_aql);
            }
            $birthday_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.birthday', 0);
            if (empty($birthday_enable)) {
                $adtargetTable->update(array("birthday_enable" => 0), null);
            }

            $network_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.target.network', 0);
            if (empty($network_enable)) {
                $db = Zend_Db_Table_Abstract::getDefaultAdapter();
                $network_sql = "UPDATE " . $adtargetTable->info("name") . " SET `networks` =NULL";
                $db->query($network_sql);
            }
            $addKey = array_diff($key, $data);

            foreach ($addKey as $field_name) {
                $alter_sql = "ALTER TABLE `" . $targetName . "` ADD `$field_name` VARCHAR( 255 ) NULL";
                if (!($db->query($alter_sql))) {
                    echo "Error in running sql query.";
                }
            }
            $form->addNotice('Your changes have been saved.');
        }
    }

    /**
     * Create Direcotory
     */
    private function createDir($path) {
        if (!empty($path)) {
            if (!@is_dir($path) && !@mkdir($path, 0777, true)) {
                @mkdir(dirname($path));
                @chmod(dirname($path), 0777);
                @touch($path);
                @chmod($path, 0777);
            }
        }
    }

    /**
     * CTA category Action
     */
    public function ctaCategoriesAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
                ->getNavigation('sitead_admin_main', array(), 'sitead_admin_ctacategory');
        $this->view->categories = Engine_Api::_()->getItemTable('sitead_category')->fetchAll();
    }

    /**
     * Add Category of CTA button
     */
    public function addCategoryAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        // Generate and assign form
        $form = $this->view->form = new Sitead_Form_Admin_Category();
        $form->setAction($this->view->url(array()));
        // Check post
        if (!$this->getRequest()->isPost()) {
            $this->renderScript('admin-settings/form.tpl');
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            $this->renderScript('admin-settings/form.tpl');
            return;
        }

        // Process
        $values = $form->getValues();
        $categoryTable = Engine_Api::_()->getItemTable('sitead_category');
        $db = $categoryTable->getAdapter();
        $db->beginTransaction();

        $viewer = Engine_Api::_()->user()->getViewer();

        try {
            $categoryTable->insert(array(
                'category_name' => $values['label'],
            ));

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('')
        ));
    }

    /**
     * Delete Category of CTA button
     */
    public function deleteCategoryAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $category_id = $this->_getParam('id');
        $this->view->cta_id = $this->view->category_id = $category_id;
        $categoriesTable = Engine_Api::_()->getDbtable('categories', 'sitead');
        $category = $categoriesTable->find($category_id)->current();

        if (!$category) {
            return $this->_forward('success', 'utility', 'core', array(
                        'smoothboxClose' => 10,
                        'parentRefresh' => 10,
                        'messages' => array('')
            ));
        } else {
            $category_id = $category->getIdentity();
        }

        if (!$this->getRequest()->isPost()) {
            // Output
            $this->renderScript('admin-settings/delete.tpl');
            return;
        }

        // Process
        $db = $categoriesTable->getAdapter();
        $db->beginTransaction();

        try {
            $category->delete();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        return $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('')
        ));
    }

    /**
     * Edit Category of CTA button
     */
    public function editCategoryAction() {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $category_id = $this->_getParam('id');
        $this->view->blog_id = $this->view->category_id = $id;
        $categoriesTable = Engine_Api::_()->getDbtable('categories', 'sitead');
        $category = $categoriesTable->find($category_id)->current();

        if (!$category) {
            return $this->_forward('success', 'utility', 'core', array(
                        'smoothboxClose' => 10,
                        'parentRefresh' => 10,
                        'messages' => array('')
            ));
        } else {
            $category_id = $category->getIdentity();
        }

        $form = $this->view->form = new Sitead_Form_Admin_Category();
        $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
        $form->setField($category);

        if (!$this->getRequest()->isPost()) {
            // Output
            $this->renderScript('admin-settings/form.tpl');
            return;
        }
        if (!$form->isValid($this->getRequest()->getPost())) {
            // Output
            $this->renderScript('admin-settings/form.tpl');
            return;
        }
        // Process
        $values = $form->getValues();
        $db = $categoriesTable->getAdapter();
        $db->beginTransaction();

        try {
            $category->category_name = $values['label'];
            $category->save();

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }

        return $this->_forward('success', 'utility', 'core', array(
                    'smoothboxClose' => 10,
                    'parentRefresh' => 10,
                    'messages' => array('')
        ));
    }

}
