<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminLevelController.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_AdminLevelController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    // Make navigation
    $this->view->navigation  = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('egames_admin_main', array(), 'egames_admin_main_level');
    // Get level id
    if( null !== ($id = $this->_getParam('level_id', $this->_getParam('id'))) ) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }
    if( !$level instanceof Authorization_Model_Level ) {
      throw new Engine_Exception('missing level');
    }
    $id = $level->level_id;
    // Make form
    $this->view->form = $form = new Egames_Form_Admin_Settings_Level(array(
      'public' => ( engine_in_array($level->type, array('public')) ),
      'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($id);
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    // Check post
    if( !$this->getRequest()->isPost() ) {
      $form->populate($permissionsTable->getAllowed('egames_game', $id, array_keys($form->getValues())));
      return;
    }
    // Check validitiy
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }
    // Process
    $values = $form->getValues();
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try
    {
      // Set permissions
      $permissionsTable->setAllowed('egames_game', $id, $values);
      // Commit
      $db->commit();
    }
    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }
}
