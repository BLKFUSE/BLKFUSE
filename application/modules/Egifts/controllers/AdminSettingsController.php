<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {
  
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_admin_main', array(), 'egifts_admin_main_settings');
    
    $this->view->form = $form = new Egifts_Form_Admin_Global();
    
    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Egifts/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.pluginactivated')) {
        $this->languageWork($values);
        
        foreach ($values as $key => $value) {
          if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
          if (!$value && strlen($value) == 0)
            continue;
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
  
  public function languageWork($values) {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    //START TEXT CHNAGE WORK IN CSV FILE
    $oldSigularWord = $settings->getSetting('egifts.text.singular', 'gift');
    $oldPluralWord = $settings->getSetting('egifts.text.plural', 'gifts');
    $newSigularWord = $values['egifts_text_singular'] ? $values['egifts_text_singular'] : 'gift';
    $newPluralWord = $values['egifts_text_plural'] ? $values['egifts_text_plural'] : 'gifts';
    $newSigularWordUpper = ucfirst($newSigularWord);
    $newPluralWordUpper = ucfirst($newPluralWord); 
    if (($newSigularWord != $oldSigularWord) || ($newPluralWord != $oldPluralWord)) {
      $tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/egifts.csv', 'null', array('delimiter' => ';', 'enclosure' => '"'));
      if (!empty($tmp['null']) && is_array($tmp['null']))
          $inputData = $tmp['null'];
      else
          $inputData = array();

      $OutputData = array();
      $chnagedData = array();
      foreach ($inputData as $key => $input) {
          $chnagedData = str_replace(array($oldPluralWord, $oldSigularWord, ucfirst($oldPluralWord), ucfirst($oldSigularWord), strtoupper($oldPluralWord), strtoupper($oldSigularWord)), array($newPluralWord, $newSigularWord, ucfirst($newPluralWord), ucfirst($newSigularWord), strtoupper($newPluralWord), strtoupper($newSigularWord)), $input);
          $OutputData[$key] = $chnagedData;
      }
      $targetFile = APPLICATION_PATH . '/application/languages/en/egifts.csv';
      if (file_exists($targetFile))
          @unlink($targetFile);

      touch($targetFile);
      chmod($targetFile, 0777);
      $writer = new Engine_Translate_Writer_Csv($targetFile);
      $writer->setTranslations($OutputData);
      $writer->write();
      //END CSV FILE WORK
    }
  }
  
  public function enabledAction() {

    $id = $this->_getParam('dashboard_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('egifts_dashboards', $id);
      $item->enabled = !$item->enabled;
      $item->save();
    }
    $this->_redirect('admin/egifts/settings/manage-dashboards');
  }

  public function editDashboardsSettingsAction() {

    $dashboards = Engine_Api::_()->getItem('egifts_dashboards', $this->_getParam('dashboard_id'));
    $this->_helper->layout->setLayout('admin-simple');
    $form = $this->view->form = new Egifts_Form_Admin_EditDashboard();
    $form->setTitle('Edit This Item');
    $form->button->setLabel('Save Changes');

    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if (!($id = $this->_getParam('dashboard_id')))
      throw new Zend_Exception('No identifier specified');

    $form->populate($dashboards->toArray());

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $dashboards->title = $values["title"];
        $dashboards->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('You have successfully edit entry.')
      ));
      $this->_redirect('admin/egifts/settings/manage-dashboards');
    }
  }
  public function manageDashboardsAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_admin_main', array(), 'egifts_admin_main_managedashboards');
    $this->view->storage = Engine_Api::_()->storage();
    $this->view->paginator = Engine_Api::_()->getDbTable('dashboards', 'egifts')->getDashboardsItems();
  }

  public function createsettingsAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('egifts_admin_main', array(), 'egifts_admin_main_giftsettings');
    $this->view->form = $form = new Egift_Form_Admin_Petitionsettings();
    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
    }
  }
  public function supportAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_admin_main', array(), 'ecoupon_admin_main_support');
  }
}
