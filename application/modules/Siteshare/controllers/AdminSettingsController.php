<?php


/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminSettingsController.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_settings');
    $this->view->form = $form = new Siteshare_Form_Admin_Global();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    
    $coreSetting = Engine_Api::_()->getApi('settings', 'core');

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      foreach ($values as $key => $value) { 
        if(!empty($settings->getSetting($key)))
          $settings->removeSetting($key);
          $settings->setSetting($key, $value);    
        }
      $form->addNotice('Your changes have been saved.');
    }
    $this->view->enabled = $coreSetting->getSetting('siteshare.share.bookmarks.enabled', 1);
  }

  public function faqAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_faq');
    $this->view->action = 'faq';
    $this->view->faq_type = $this->_getParam('faq_type', 'general');
  }

}
