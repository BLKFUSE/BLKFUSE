<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminManageController.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Edating_AdminManageController extends Core_Controller_Action_Admin {

	public function actionsAction() {
	
    $this->view->navigation  = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('edating_admin_main', array(), 'edating_admin_main_manageaction');
    
		$this->view->formFilter = $formFilter = new Edating_Form_Admin_Manage_Filter();
		
		// Process form
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    foreach( $_GET as $key => $value ) {
      if( '' === $value ) {
        unset($_GET[$key]);
      }else
				$values[$key]=$value;
    }
// 		if( $this->getRequest()->isPost() ) {
//       $values = $this->getRequest()->getPost();
//       foreach ($values as $key => $value) {
//         if ($key == 'delete_' . $value) {
//           $photo = Engine_Api::_()->getItem('edating_photo', $value);
//           $photo->delete();
//         }
//       }
//     }
				
		$tableActions = Engine_Api::_()->getDbtable('actions', 'edating');
		$tableActionsName = $tableActions->info('name');

		$tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $select =  $tableActions->select()
													->from($tableActionsName)
													->setIntegrityCheck(false)
													->joinLeft($tableUserName, "$tableUserName.user_id = $tableActionsName.user_id", 'username');

		if (!empty($values['owner_name']))			
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

		$select->order($tableActionsName.'.action_id DESC');
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber( $this->_getParam('page', 1) );
  }
  
	public function photosAction() {
	
    $this->view->navigation  = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('edating_admin_main', array(), 'edating_admin_main_managephotos');
    
		$this->view->formFilter = $formFilter = new Edating_Form_Admin_Manage_Filter();
		
		// Process form
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    foreach( $_GET as $key => $value ) {
      if( '' === $value ) {
        unset($_GET[$key]);
      }else
				$values[$key]=$value;
    }
		if( $this->getRequest()->isPost() ) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $photo = Engine_Api::_()->getItem('edating_photo', $value);
          $photo->delete();
        }
      }
    }
				
		$tablePhoto = Engine_Api::_()->getDbtable('photos', 'edating');
		$tablePhotoName = $tablePhoto->info('name');
		
		$tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $select =  $tablePhoto->select()
													->from($tablePhotoName)
													->setIntegrityCheck(false)
													->joinLeft($tableUserName, "$tableUserName.user_id = $tablePhotoName.user_id", 'username');

		if (!empty($values['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

		$select->order($tablePhotoName.'.photo_id DESC');
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber( $this->_getParam('page', 1) );
  }

	public function deletePhotoAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try
      {
        $photo = Engine_Api::_()->getItem('edating_photo', $id);
        $photo->delete();
        $db->commit();
      }
      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh'=> 10,
          'messages' => array('Photo deleted successfully.')
      ));
    }
    $this->renderScript('admin-manage/delete-photo.tpl');
	}
}
