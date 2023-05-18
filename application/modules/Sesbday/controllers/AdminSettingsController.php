<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbday_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {

      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesbday_admin_main', array(), 'sesbday_admin_main_settings');
      $this->view->form = $form = new Sesbday_Form_Admin_Settings_Global();
      $settings = Engine_Api::_()->getApi('settings', 'core');


      //move this in license file
      if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.activate', 0)) {
          $birthdayContent = '<table style="background: #C2C2C2; padding: 20px; width: 100%; height: 100vh;" cellspacing="0" cellpadding="0"><tbody><tr><td><table style="width: 625px; margin: 0 auto;"><tbody><tr><td style="font-family: Arial, Helvetica, sans-serif;"><div style="background-image: url(\'/application/modules/Sesbday/externals/images/Balloon_Birthday.jpg\'); color: #ff008a; min-height: 466px; position: relative; text-align: center; background-repeat: no-repeat;"><h1 style="font-family: \'Comic Sans MS\', cursive; padding-top: 15%; text-align: center;"><br>Many Many Happy <br> Returns <br> Of The Day<br>[recipient_title]</h1><img style="width: 135px;" src="/application/modules/Sesbday/externals/images/Pink_Birthday.png" alt="" align="center"></div></td></tr></tbody></table></td></tr></tbody></table>';
          //Engine_Api::_()->getApi('settings', 'core')->setSetting('sesbday.birthday.subject', 'Wish you a very Happy Birthday!');
          Engine_Api::_()->getApi('settings', 'core')->setSetting('sesbday.birthday.content', $birthdayContent);
          Engine_Api::_()->getApi('settings', 'core')->setSetting('sesbday.birthday.activate', 1);
      }
      




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
