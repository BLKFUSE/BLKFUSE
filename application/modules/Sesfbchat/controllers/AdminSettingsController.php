<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesfbchat_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesfbchat_admin_main', array(), 'sesfbchat_admin_main_settings');

    $this->view->form = $form = new Sesfbchat_Form_Admin_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
        $values = $form->getValues();
        if($values['sesfbchat_enable_timing']) {
          $starttime = strtotime(date('H:i:s',strtotime($values["sesfbchat_starttime"])));
          $endtime = strtotime(date('H:i:s',strtotime($values["sesfbchat_endtime"])));
          if($starttime > $endtime){
            $form->addError("Start Time must be less than End Time");
            return;
          }
        }
        include_once APPLICATION_PATH . "/application/modules/Sesfbchat/controllers/License.php";
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesfbchat.pluginactivated')) {
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
}
