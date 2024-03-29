<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminLevelController.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_AdminLevelController extends Core_Controller_Action_Admin
{

  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('elivestreaming_admin_main', array(), 'elivestreaming_admin_main_level');
    // Get level id
    if (null !== ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }
    if (!$level instanceof Authorization_Model_Level)
      throw new Engine_Exception('missing level');

    $id = $level->level_id;
    // Make form
    $this->view->form = $form = new Elivestreaming_Form_Admin_Level_Level(array(
      'public' => (engine_in_array($level->type, array('public'))),
      'moderator' => (engine_in_array($level->type, array('admin', 'moderator'))),
    ));
    $form->level_id->setValue($id);

    // Populate values
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $form->populate($permissionsTable->getAllowed('elivehost', $id, array_keys($form->getValues())));
    // Check post
    if (!$this->getRequest()->isPost())
      return;
    // Check validitiy
    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $values = $form->getValues();
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      // Set permissions
      // if ($values['duration'] > 10)
      //   $values['duration'] = 10;
      // if ($values['duration'] < 1)
      //   $values['duration'] = 1;
      $permissionsTable->setAllowed('elivehost', $id, $values);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }
}
