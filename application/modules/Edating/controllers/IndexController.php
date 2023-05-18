<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_IndexController extends Core_Controller_Action_Standard {

	public function init() {
		if (!$this->_helper->requireUser()->isValid())
      return;

	}
	
	public function browseAction() {
    if (!$this->_helper->requireAuth()->setAuthParams('edating_dating', null, 'create')->isValid())
      return;
		$this->_helper->content->setEnabled();
	}

	public function alreadyViewedAction() {
		$this->_helper->content->setEnabled();
	}
	
	public function whoLikeMeAction() {
		$this->_helper->content->setEnabled();
	}
	
	public function mutualLikesAction() {
		$this->_helper->content->setEnabled();
	}
	
	public function myLikesAction() {
		$this->_helper->content->setEnabled();
	}
	
	public function likeAction() {

		$viewer = Engine_Api::_()->user()->getViewer();
		
    $user_id = $this->_getParam('user_id');
		$owner_id = $viewer->getIdentity();
		$reaction = $this->_getParam('reaction', 'liked');
		
    $likesTable = Engine_Api::_()->getDbtable('likes', 'edating');
    $actionsTable = Engine_Api::_()->getDbtable('actions', 'edating');
		
		if($reaction == 'liked') {
      //CHECK FOR MUTUAL
      $mutual = Engine_Api::_()->getDbtable('likes', 'edating')->checkmutual($owner_id,$user_id);
      
      $db = $likesTable->getAdapter();
      $db->beginTransaction();
      try {
        if (engine_count($mutual)) {
          $likesTable->update(array('mutual' => 1, 'is_viewed' => 1), array('user_id = ?' => $user_id, 'owner_id = ?' =>$owner_id));

          //WHO ALREADY
          $likesTable->update(array('mutual' => 1, 'is_viewed' => 0), array('user_id = ?' => $owner_id, 'owner_id = ?' =>$user_id));

          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(Engine_Api::_()->user()->getUser($user_id), $viewer, Engine_Api::_()->user()->getUser($user_id), 'edating_mutual', array("url1" => $viewer->getHref(), "url2" => $this->view->url(array('action' => "wholikes"), "edating_general")));
        } else {
          $row = $likesTable->createRow();
          $row->setFromArray(array("user_id" => $owner_id,"owner_id" => $user_id,"time_stamp" => time()));
          $row->save();
          $db->commit();
          
          $row = $likesTable->createRow();
          $row->setFromArray(array("user_id" => $user_id,"is_own"=>1,"is_viewed"=>1,"owner_id" => $owner_id,"time_stamp" => time()));
          $row->save();
          $db->commit();

          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(Engine_Api::_()->user()->getUser($user_id), $viewer, Engine_Api::_()->user()->getUser($user_id), 'edating_like', array("url1" => $viewer->getHref(), "url2" => $this->view->url(array('action' => "wholikes"), "edating_general")));
        }

        $action = $actionsTable->createRow();
        $action->setFromArray(array("user_id" => $user_id,"owner_id" => $owner_id,"action" => 'like',"time_stamp" => time()));
        $action->save();
        $db->commit();
        echo json_encode(array('status'=>true, 'message' => 'liked'));die;
      } catch (Exception $error){
        $db->rollBack();
        throw $error;
      }
		} elseif($reaction == 'disliked') {
      $db = $actionsTable->getAdapter();
      $db->beginTransaction();
      try {
        $row = $actionsTable->createRow();
        $row->setFromArray(array("user_id" => $user_id, "owner_id" => $owner_id, "action" => 'visit', "time_stamp"=>time()));
        $row->save();
        $db->commit();
        echo json_encode(array('status'=>true, 'message' => 'disliked'));die;
      } catch (Exception $error){
        $db->rollBack();
        throw $error;
      }
		}
  }
 
	public function photosAction() {
		
		if(isset($_GET['ul']) || isset($_FILES['Filedata']))
      return $this->_forward('upload-photo', null, null, array('format' => 'json'));
      
		$viewer = Engine_Api::_()->user()->getViewer();	

		$this->view->form = $form = new Edating_Form_Upload();
		
		$values["user_id"] = $viewer->getIdentity();
		$values["page"] = $this->_getParam('page');

		$paginator = Engine_Api::_()->getDbTable('photos', 'edating')->getPhotosPaginator($values);
		$paginator->setItemCountPerPage(30);
		$this->view->paginator = $paginator->setCurrentPageNumber($this->_getParam('page'));
		$this->_helper->content->setEnabled();
	}
	
	public function makemainphotoAction() {
	
		$id = $this->_getParam('id', null);
		$photosTable = Engine_Api::_()->getDbtable('photos', 'edating');
		$photo = $photosTable->find($id)->current();
		$this->view->form = $form = new Edating_Form_MainPhoto();
		$values = $this->getRequest()->getPost();
		if ($values and !empty($id)){
			$photosTable->cleanMain();
			$photo->is_main = 1;
			$photo->save();
			return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array($this->view->translate('Your this photo setup as main photo for dating.'))
      ));
    } elseif ($photo){
      $form->populate($photo->toArray());
		}
	}
	
  public function uploadPhotoAction() {
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $values = $this->getRequest()->getPost();

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    if (empty($_FILES['file'])) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
      return;
    }
    
    $photosTable = Engine_Api::_()->getDbtable('photos', 'edating');
    
    $db = $photosTable->getAdapter();
    $db->beginTransaction();

    try {

      $photo = $photosTable->createRow();
      $photo->setFromArray(array(
        'user_id' => $viewer->getIdentity()
      ));
      $photo->save();
      
      $photo->setPhoto($_FILES['file']);
      $photo->save();
      $tempId = $photosTable->uploadTemPhoto($_FILES['file']);
      if(!$tempId){
            $this->view->status = false;
      } else {
            $this->view->status = true;
      }
      $this->view->name = $_FILES['file']['name'];
      $this->view->photo_id = $photo->photo_id;
      $db->commit();
      $this->sendJson([
        'id' => $tempId,
        'fileName' => $_FILES['file']['name']
      ]);
    } catch( User_Model_Exception $e ) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = $this->view->translate($e->getMessage());
      throw $e;
      return;
    } catch( Exception $e ) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
  }
  
	public function deletephotoAction() {
	
		$id = $this->_getParam('id', null);
		$photosTable = Engine_Api::_()->getDbtable('photos', 'edating');
		$photo = $photosTable->find($id)->current();

		$this->view->form = $form = new Edating_Form_DeletePhoto();
		
		if ($this->getRequest()->isPost()) {
			$db = $photosTable->getAdapter();
			$db->beginTransaction();
			try {
				$photo->delete();
				$db->commit();
        return $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array($this->view->translate('Photo deleted successfully.'))
        ));
			} catch (Exception $error) {
				$db->rollBack();
				throw $error;
			}
		}
	}
	
	public function settingsAction() {

		$viewer = Engine_Api::_()->user()->getViewer();
		$settingsTable = Engine_Api::_()->getDbTable('settings', 'edating');
    $userRow = $settingsTable->getViewerRow($viewer->getIdentity());
		$this->view->form = $form = new Edating_Form_Settings();
		$values = $this->getRequest()->getPost();
		$values['user_id'] = $viewer->getIdentity();
		
		if (!empty($values["description"]) || !empty($values["is_search"])) {
      $db = $settingsTable->getAdapter();
			if ($userRow) {
        $userRow->setFromArray($values);
        $userRow->save();
        $db->commit();
			} else {
        $userRow = $settingsTable->createRow();
        $userRow->setFromArray($values);
        $userRow->save();
        $db->commit();
			}
			$form->populate($userRow->toArray());
		} elseif($userRow) {
			$form->populate($userRow->toArray());
		}
		$this->_helper->content->setEnabled();
	}
	
	
  public function rejectAction() {
  
    $owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $user_id = $this->_getParam('user_id');
    $table = Engine_Api::_()->getDbTable('actions', 'edating');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      
      $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
      $dbGetInsert->query("DELETE FROM engine4_edating_likes WHERE user_id = " . $user_id . ' AND owner_id =' . $owner_id);
      $dbGetInsert->query("DELETE FROM engine4_edating_likes WHERE user_id = " . $owner_id . ' AND owner_id =' . $user_id);
      $dbGetInsert->query("UPDATE `engine4_edating_actions` SET `action` = 'visit' WHERE user_id = " . $owner_id . ' AND owner_id =' . $user_id);
      $db->commit();
      echo json_encode(array('status' => true, 'message' => 'reject'));die;
    } catch (Exception $error){
      $db->rollBack();
      throw $error;
    }
  }
}
