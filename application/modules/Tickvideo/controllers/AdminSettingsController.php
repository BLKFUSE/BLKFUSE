<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Tickvideo_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_settings');

    $this->view->form = $form = new Tickvideo_Form_Admin_Global();

    if( $this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Tickvideo/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.pluginactivated')) {
        foreach ($values as $key => $value){
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }

  function supportAction(){
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_support');
  }
}
