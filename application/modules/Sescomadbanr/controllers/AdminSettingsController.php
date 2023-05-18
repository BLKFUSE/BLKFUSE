<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_sescommunityadsbanner');

    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescomadbanr_admin_main', array(), 'sescomadbanr_admin_main_settings');

    $table_exist = $db->query('SHOW TABLES LIKE \'engine4_sescomadbanr_userpayments\'')->fetch();
    if($table_exist) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sescomadbanr.pluginactivated', 1);
    }
    
    $this->view->form = $form = new Sescomadbanr_Form_Admin_Settings_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sescomadbanr/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.pluginactivated')) {
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

    public function supportAction() {
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescomadbanr_admin_main', array(), 'sescomadbanr_admin_main_support');
    }
}
