<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjurymember_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontestjurymember_admin_main');

    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjurymember_admin_main', array(), 'sescontestjurymember_admin_main_settings');

    $this->view->form = $form = new Sescontestjurymember_Form_Admin_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sescontestjurymember/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjurymember.pluginactivated')) {
        unset($values['sescontestjurymember_memberlevel_setting']);
        unset($values['sescontestjurymember_memberlevel_votesetting']);
        foreach ($values as $key => $value) {
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if ($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
}