<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminLevelsController.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbday_AdminLevelsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $db = Engine_Db_Table::getDefaultAdapter();
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesbday_admin_main', array(), 'sesbday_admin_main_levels');
      $this->view->form = $form = new Sesbday_Form_Admin_Settings_Level();
      $settings = Engine_Api::_()->getApi('settings', 'core');
      if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
          $values = $form->getValues();
          $db = Engine_Db_Table::getDefaultAdapter();
          $db->beginTransaction();
          try {
              $values = $form->getValues();
              foreach ($values as $key=>$value){
                  $settings->setSetting($key,$value);
              }
              $db->commit();
          } catch( Exception $e ) {
              $db->rollback();
              throw $e;
          }
          $form->addNotice('Your changes have been saved.');
          $this->_helper->redirector->gotoRoute(array());
      }
  }
}
