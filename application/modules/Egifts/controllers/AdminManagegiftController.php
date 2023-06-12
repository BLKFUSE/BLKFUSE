<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminManagegiftController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_AdminManagegiftController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
	  $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egifts_admin_main', array(), 'egifts_admin_main_managegift');
	  $this->view->paginator = Engine_Api::_()->getDbTable('gifts', 'egifts')->getGiftPaginator(array('page' => $this->_getParam('page', 1)));
  }
  public function changestatusgiftAction()
  {
	  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && ((isset($_POST['gift_id']) && !empty($_POST['gift_id']))))
	  {
		  $db = Engine_Db_Table::getDefaultAdapter();
		  $status=$db->select()->from('engine4_egifts_gifts')->where('gift_id = ?',$_POST['gift_id'])->query()->fetch();
		  $status_type=0;
		  if($status['status']==0)
		  {
		  	$status_type=1;
		  }
		  $db->update('engine4_egifts_gifts', array('status' => $status_type), array('gift_id = ?' =>$_POST['gift_id']));
		  echo 1;
		  exit();
	  }
	  exit();
  }
  public function deletegiftAction()
  {
	  if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' && ((isset($_POST['gift_id']) && !empty($_POST['gift_id']))))
	  {
		  $db = Engine_Db_Table::getDefaultAdapter();
		  $db->update('engine4_egifts_gifts', array('status' => 2), array('gift_id = ?' =>$_POST['gift_id']));
		  echo 1;

	  }
	  exit();
  }

  public function createAction()
  {
    $this->view->form=$form=new Egifts_Form_Admin_Creategift();
	  if(!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
	  {
		  return;
	  }
	  if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
	  {
		  $params = $form->getValues();
		  try {
			  $viewer = Engine_Api::_()->user()->getViewer();
			  $gift_add = Engine_Api::_()->getDbtable('gifts', 'egifts')->createRow();
			  $gift_add->setFromArray($params);
			  $gift_add->save();
			  $gift_add->created_date= date("Y-m-d H:i:s");
			  $gift_add->created_by = $viewer->getIdentity();
			  $gift_add->owner_id = $viewer->getIdentity();
			  $gift_add->icon_id = 0;
			  $gift_add->status = 1;
			  $gift_add->save();
			  if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
				  $file_ext = pathinfo($_FILES['file']['name']);
				  $file_ext = $file_ext['extension'];
				  $storage = Engine_Api::_()->getItemTable('storage_file');
				  $storageObject = $storage->createFile($form->file, array(
					  'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
					  'parent_type' => Engine_Api::_()->user()->getViewer()->getType(),
					  'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
				  ));
				  // Remove temporary file
				  //@unlink($file['tmp_name']);
				  if(isset($storageObject->file_id) && !empty($storageObject->file_id))
				  {
					  $gift_add->icon_id = $storageObject->file_id;
					  $gift_add->save();
				  }
			  }

			  $this->_forward('success', 'utility', 'core', array(
				  'smoothboxClose' => true,
				  'parentRefresh' => true,
				  'messages' => array(Zend_Registry::get('Zend_Translate')->_('Gift created successfully.'))
			  ));


		  } catch (Exception $e) {
			  throw  $e;
		  }
	  }
  }
  public function editAction()
  {
  	$id = $this->_getParam('gift_id', null); 
  	$gift = Engine_Api::_()->getItem('egifts_gift', $id);
  	if(empty($gift)){
  		 return $this->_forward('requireauth', 'error', 'core'); 
  	}
    $this->view->form = $form = new Egifts_Form_Admin_Editgift();
    $form->populate($gift->toArray());
    $form->file->setRequired(false);
    $form->file->setAllowEmpty(true);
  	if(!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
 	{
	  	return;
  	}
  	if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()))
  	{
		$params = $form->getValues();
		try {
			$viewer = Engine_Api::_()->user()->getViewer();
		  	$gift->setFromArray($params);
		  	$gift->save();
		  	$gift->created_date= date("Y-m-d H:i:s");
		  	$gift->created_by = $viewer->getIdentity();
		  	$gift->owner_id = $viewer->getIdentity();
		  	$gift->status = 1;
		  	$gift->save();
		  	if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
				$file_ext = pathinfo($_FILES['file']['name']);
			  	$file_ext = $file_ext['extension'];
			  	$storage = Engine_Api::_()->getItemTable('storage_file');
			  	$storageObject = $storage->createFile($form->file, array(
					'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
				  	'parent_type' => Engine_Api::_()->user()->getViewer()->getType(),
				  	'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
			  	));
			  	// Remove temporary file
			  	//@unlink($file['tmp_name']);
			  	if(isset($storageObject->file_id) && !empty($storageObject->file_id))
				{
					$gift->icon_id = $storageObject->file_id;
				  	$gift->save();
			  	}
		  	}
		  	$this->_forward('success', 'utility', 'core', array(
				'smoothboxClose' => true,
			  	'parentRefresh' => true,
			  	'messages' => array(Zend_Registry::get('Zend_Translate')->_('Gift created successfully.'))
		 	));
		} catch (Exception $e) {
			throw  $e;
		}
	  }
  	}
}
