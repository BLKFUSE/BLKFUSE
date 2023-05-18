<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {
  
    $db = Engine_Db_Table::getDefaultAdapter();
    
    $settings = Engine_Api::_()->getApi('settings', 'core');
	
    // Make form
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('elivestreaming_admin_main', array(), 'elivestreaming_admin_main_settings');
    
    $this->view->form = $form = new Elivestreaming_Form_Admin_Settings_Global();
    
    $this->view->isCheck = $settings->getSetting('elivestreaming.showliveimage');

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      //include_once APPLICATION_PATH . "/application/modules/Elivestreaming/controllers/License.php";
      if ($settings->getSetting('elivestreaming.pluginactivated')) {
        foreach ($values as $key => $value) {
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if (@$error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
}
