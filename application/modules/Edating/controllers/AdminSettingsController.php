<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('edating_admin_main', array(), 'edating_admin_main_settings');

    $this->view->form = $form = new Edating_Form_Admin_Settings_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Edating/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('edating.pluginactivated')) {
        foreach ($values as $key => $value) {
          if($value != '')
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
  
  public function manageWidgetizePageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('edating_admin_main', array(), 'edating_admin_main_managewidgetizepage');

    $pagesArray = array(
        'edating_index_already-viewed',
        'edating_index_mutual-likes',
        'edating_index_who-like-me',
        'edating_index_my-likes',
        'edating_index_browse',
        'edating_index_settings',
        'edating_index_photos',
    );

    $this->view->pagesArray = $pagesArray;
  }
}
