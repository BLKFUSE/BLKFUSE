<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminSettingsController extends Core_Controller_Action_Admin {
  public function indexAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_settings');
    $this->view->form = $form = new Sescommunityads_Form_Admin_Global();
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('installed.sescomm')){
      Engine_Api::_()->getApi('settings', 'core')->setSetting('installed.sescomm', 1);
      $this->saveDefaultSetting();
    }
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sescommunityads/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.pluginactivated')) {
        if(Engine_Api::_()->getApi('settings', 'core')->hasSetting('sescommunityads_package_settings'))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting('sescommunityads_package_settings');
        if(Engine_Api::_()->getApi('settings', 'core')->hasSetting('sescommunityads_call_toaction'))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting('sescommunityads_call_toaction');
        if(empty($values['is_license'])) {
            if (isset($values['sescommunityads_package_settings']))
                $values['sescommunityads_package_settings'] = serialize($values['sescommunityads_package_settings']);
            else
                $values['sescommunityads_package_settings'] = serialize(array());

            if (!empty($values['sescommunityads_call_toaction']))
                $values['sescommunityads_call_toaction'] = serialize($values['sescommunityads_call_toaction']);
            else {
                unset($values['sescommunityads_call_toaction']);
                $values['sescommunityads_call_toaction'] = serialize(array());
            }
        }
        foreach ($values as $key => $value){

            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
            $this->_helper->redirector->gotoRoute(array());
      }
    }
  }

  public function manageWidgetizePageAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_managepages');

    $this->view->pagesArray = array('sescommunityads_index_package', 'sescommunityads_index_browse', 'sescommunityads_index_manageads', 'sescommunityads_index_manage', 'sescommunityads_index_create', 'sescommunityads_index_report', 'sescommunityads_index_help-and-learn', 'sescommunityads_index_view', '', '', '');
  }
    public function supportAction() {

        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_support');

    }
  function saveDefaultSetting(){
    //Default Privacy Set Work
  $permissionsTable = Engine_Api::_()->getDbTable('permissions', 'authorization');
  foreach (Engine_Api::_()->getDbTable('levels', 'authorization')->fetchAll() as $level) {
    $form = new Sescommunityads_Form_Admin_Level(array(
        'public' => ( engine_in_array($level->type, array('public')) ),
        'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $values = $form->getValues();
    $valuesForm = $permissionsTable->getAllowed('sescommunityads', $level->level_id, array_keys($form->getValues()));

    $form->populate($valuesForm);
    if ($form->defattribut)
      $form->defattribut->setValue(0);
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      if ($level->type != 'public') {
        // Set permissions
        $values['auth_comment'] = (array) $values['auth_comment'];
        $values['auth_view'] = (array) $values['auth_view'];
      }
      $nonBooleanSettings = $form->nonBooleanFields();
      $permissionsTable->setAllowed('sescommunityads', $level->level_id, $values, '', $nonBooleanSettings);
      // Commit
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
}
  }
  function feedSettingsAction(){
    $db = Engine_Db_Table::getDefaultAdapter();
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_feedactivity');
    $this->view->form = $form = new Sescommunityads_Form_Admin_FeedSettings();

     // Populate settings
    $actionTypesTable = Engine_Api::_()->getDbTable('actionTypes', 'activity');
    $actionTypes = $actionTypesTable->fetchAll();
    $modules = Engine_Api::_()->getDbtable('modules', 'core')->getModulesAssoc();

     // Make form
    $this->view->form = $form = new Engine_Form(array(
      'title' => 'Activity Feed Settings',
      'description' => 'Which of the below options you want to enable for Boost Post Feed?',
    ));

    $moduleArray = array();
    foreach( $actionTypes as $elementName => $info ) {
      if($info['module'] == "sescommunityads")
        continue;
       $moduleArray[$info['module']][$info['type']] = $this->view->translate('ADMIN_ACTIVITY_TYPE_' . strtoupper($info->type));
    }

    //get feed settings
    $feedTable = Engine_Api::_()->getDbTable('feedsettings', 'sescommunityads');
    $feedData = $feedTable->fetchAll();

    $selectedArray = array();
    foreach( $feedData as $elementName => $info ) {

       $selectedArray[$info['module']][] = $info['type'];
    }
    foreach( $moduleArray as $key => $info){
      $form->addElement('MultiCheckbox', $key, array(
        'label' => $modules[$key]['title'],
        'multiOptions' => $info,
        'value' => !empty($selectedArray[$key]) ? $selectedArray[$key] : array(),
      ));
    }

    $form->addElement('Button', 'execute', array(
      'label' => 'Save Changes',
      'type' => 'submit',
    ));
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }


    // Process
    $values = $form->getValues();
    //remove previous values from db

    $db->query("TRUNCATE table engine4_sescommunityads_feedsettings");
    foreach($values as $key=>$value){
      foreach($value as $type){
        $db->query("INSERT INTO engine4_sescommunityads_feedsettings (`module`,`type`,`creation_date`) VALUES ('".$key."','".$type."','".date('Y-m-d H:i:s')."')");
      }
    }

    $form->addNotice('Your changes have been saved.');
	  $this->_helper->redirector->gotoRoute(array());
  }
  function activityAction(){
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_activity');

    $this->view->activity = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity');

    $this->view->form = $form = new Sescommunityads_Form_Admin_Activity();
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
				foreach ($values as $key => $value) {
					Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
				}
				$form->addNotice('Your changes have been saved.');
				$this->_helper->redirector->gotoRoute(array());
    }
  }
  function modulesAction(){
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_modules');
    $this->view->enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    $select = Engine_Api::_()->getDbtable('modules', 'sescommunityads')->select();
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
   //Add New Plugin entry
  public function addmoduleAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_modules');
    $this->view->form = $form = new Sescommunityads_Form_Admin_Module_Add();
    $this->view->type = $type = $this->_getParam('type');
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $integrateothermoduleTable = Engine_Api::_()->getDbtable('modules', 'sescommunityads');
      $is_module_exists= $integrateothermoduleTable->fetchRow(array('content_type = ?' => $values['content_type'], 'module_name = ?' => $values['module_name']));
      if (!empty($is_module_exists)) {
        $error = Zend_Registry::get('Zend_Translate')->_("This Module already exist in our database.");
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }
      try{
      $contentTypeItem = Engine_Api::_()->getItemTable($values['content_type']);
			//get current content type item id
      }catch(Exception $e){
        $error = Zend_Registry::get('Zend_Translate')->_("Selected module is not defined in manifest file.");
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        $row = $integrateothermoduleTable->createRow();
        $row->setFromArray($values);
        $row->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_helper->redirector->gotoRoute(array('action' => 'modules','module'=>'sescommunityads','controller'=>'settings'),'admin_default',true);
    }
  }

  //Delete entry
  public function deleteAction() {
    $this->_helper->layout->setLayout('admin-simple');
    $content_type = $this->_getParam('content_type');
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $integrateothermodule = Engine_Api::_()->getItem('sescommunityads_modules', $this->_getParam('module_id'));
        $integrateothermodule->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array('You have successfully delete entry.')
      ));
    }
  }

  //Enable / Disable Action
  public function enabledAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $content = Engine_Api::_()->getItemTable('sescommunityads_modules')->fetchRow(array('module_id = ?' => $this->_getParam('module_id')));
    try {
      $content->enabled = !$content->enabled;
      $content->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    header('Location:'.$_SERVER['HTTP_REFERER']);
    exit();
  }
  
  public function extensionsAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_extension');
  }

  public function moduleenableAction() {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $module = $this->_getParam('modulename');
    $enabled = $this->_getParam('enabled');
    if (!empty($module)) {
      $db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_menuitems`.`name` = "sescommunityads_admin_main_sescommunityadsbanner";');
      $db->query('UPDATE `engine4_core_modules` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_modules`.`name` = "'.$module.'";');
    }
    $this->_redirect('admin/sescommunityads/settings/extensions');
  }
}
