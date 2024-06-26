<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: PhotoController.php 9913 2013-02-15 00:00:42Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_PhotoController extends Core_Controller_Action_Standard
{
  public function init()
  {
    if( !Engine_Api::_()->core()->hasSubject() )
    {
      if( 0 !== ($photo_id = (int) $this->_getParam('photo_id')) &&
          null !== ($photo = Engine_Api::_()->getItem('group_photo', $photo_id)) )
      {
        Engine_Api::_()->core()->setSubject($photo);
      }

      else if( 0 !== ($group_id = (int) $this->_getParam('group_id')) &&
          null !== ($group = Engine_Api::_()->getItem('group', $group_id)) )
      {
        Engine_Api::_()->core()->setSubject($group);
      }
    }
    
    $this->_helper->requireUser->addActionRequires(array(
      'upload',
      'upload-photo', // Not sure if this is the right
      'edit',
    ));

    $this->_helper->requireSubject->setActionRequireTypes(array(
      'list' => 'group',
      'upload' => 'group',
      'view' => 'group_photo',
      'edit' => 'group_photo',
    ));
  }

  public function listAction()
  {
    $this->view->group = $group = Engine_Api::_()->core()->getSubject();
    $this->view->album = $album = $group->getSingletonAlbum();

    if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'view')->isValid() ) {
      return;
    }

    $this->view->paginator = $paginator = $album->getCollectiblesPaginator();
    $paginator->setItemCountPerPage(12);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    $this->view->canUpload = $group->authorization()->isAllowed(null, 'photo');
  }
  
  public function viewAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->photo = $photo = Engine_Api::_()->core()->getSubject();
    $this->view->album = $album = $photo->getCollection();
    $this->view->group = $group = $photo->getGroup();
    $this->view->canEdit = $photo->canEdit(Engine_Api::_()->user()->getViewer());
    $this->view->canDelete = $photo->canDelete(Engine_Api::_()->user()->getViewer());

    if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'view')->isValid() ) {
      return;
    }
    
    if( !$group || !$group->getIdentity() || ((!$group->approved) && !$group->isOwner($viewer)) ) {
      if(!empty($viewer->getIdentity()) && $viewer->isAdmin()) {
      } else
        return $this->_forward('requireauth', 'error', 'core');
    }

    if( !$viewer || !$viewer->getIdentity() || $photo->user_id != $viewer->getIdentity() ) {
      $photo->view_count = new Zend_Db_Expr('view_count + 1');
      $photo->save();
    }
  }

  public function uploadAction()
  {
    if( isset($_GET['ul']) ) {
      return $this->_forward('upload-photo', null, null, array('format' => 'json'));
    }

    if( isset($_FILES['Filedata']) ) {
      $_POST['file'] = $this->uploadPhotoAction();
    }

    $group = Engine_Api::_()->core()->getSubject();
    if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'photo')->isValid() ) {
      return;
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $album = $group->getSingletonAlbum();

    $this->view->group = $group;
    $this->view->form = $form = new Group_Form_Photo_Upload();
    $form->file->setAttrib('data', array('group_id' => $group->getIdentity()));

    if( !$this->getRequest()->isPost() )
    {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    // Process
    $table = Engine_Api::_()->getItemTable('group_photo');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $values = $form->getValues();
      $params = array(
        'group_id' => $group->getIdentity(),
        'user_id' => $viewer->getIdentity(),
      );
      
      // Add action and attachments
      $api = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $api->addActivity(Engine_Api::_()->user()->getViewer(), $group, 'group_photo_upload', null, array(
        'count' => engine_count($values['file'])
      ));

      // Do other stuff
      $count = 0;
      foreach( $values['file'] as $photo_id )
      {
        $photo = Engine_Api::_()->getItem("group_photo", $photo_id);
        if( !($photo instanceof Core_Model_Item_Abstract) || !$photo->getIdentity() ) continue;

        /*
        if( $set_cover )
        {
          $album->photo_id = $photo_id;
          $album->save();
          $set_cover = false;
        }
        */

        $photo->collection_id = $album->album_id;
        $photo->album_id = $album->album_id;
        $photo->group_id = $group->group_id;
        $photo->save();

        if( $action instanceof Activity_Model_Action && $count < 100 ) {
          $api->attachActivity($action, $photo, Activity_Model_Action::ATTACH_MULTI);
        }
        $count++;
      }
      
      //Send to all group members
      $members = Engine_Api::_()->group()->groupMembers($group->getIdentity());
      foreach($members as $member) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($member, $viewer, $group, 'group_photocreate');
      }
      
      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }


    $this->_redirectCustom($group);
  }

  public function uploadPhotoAction()
  {
    $groupId = $this->_getParam('group_id');
    if( empty($groupId) ) {
      $group = Engine_Api::_()->core()->getSubject();
    } else {
      $group = Engine_Api::_()->getItem('group', $groupId);
    }
    
    if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'photo')->isValid() ) {
      return;
    }

    if( !$this->_helper->requireUser()->checkRequire() )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
      return;
    }

    if( !$this->getRequest()->isPost() )
    {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $values = $this->getRequest()->getPost();

    if (empty($_FILES['file'])) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
      return;
    }

    $db = Engine_Api::_()->getDbtable('photos', 'group')->getAdapter();
    $db->beginTransaction();

    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      $album = $group->getSingletonAlbum();
      
      $params = array(
        'user_id' => $viewer->getIdentity(),
      );
      
      $photoTable = Engine_Api::_()->getItemTable('group_photo');
      $photo = $photoTable->createRow();
      $photo->setFromArray($params);
      $photo->save();
      
      $photo->setPhoto($_FILES['file']);
      
      $this->view->status = true;
      $this->view->name = $_FILES['file']['name'];
      $this->view->photo_id = $photo->photo_id;

      $db->commit();

      $this->sendJson([
        'id' => $photo->photo_id,
        'fileName' => $_FILES['file']['name']
      ]);
    }

    catch( Exception $e )
    {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      // throw $e;
      return;
    }
  }

  public function editAction()
  {
    $photo = Engine_Api::_()->core()->getSubject();
    $group = $photo->getParent('group');
//     if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'photo_edit')->isValid() ) {
//       return;
//     }
    $this->view->form = $form = new Group_Form_Photo_Edit();

    if( !$this->getRequest()->isPost() )
    {
      $form->populate($photo->toArray());
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('photos', 'group')->getAdapter();
    $db->beginTransaction();

    try
    {
      $photo->setFromArray($form->getValues())->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Changes saved')),
      'layout' => 'default-simple',
      'parentRefresh' => true,
      'closeSmoothbox' => true,
    ));
  }

  public function deleteAction()
  {
    $photo = Engine_Api::_()->core()->getSubject();
    $album = $photo->getParent();
    $group = $photo->getParent('group');
//     if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'photo_delete')->isValid() ) {
//       return;
//     }

    $this->view->form = $form = new Group_Form_Photo_Delete();

    if( !$this->getRequest()->isPost() )
    {
      $form->populate($photo->toArray());
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    // Process
    $photoTable = Engine_Api::_()->getDbtable('photos', 'group');
    if($group->coverphoto == $photo->photo_id) {
      $group->coverphoto = 0;
      $group->save();
      $album->coverphotoparams = null;
      $album->save();
    }
    $photoTable->deletePhoto($photo);

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Photo deleted')),
      'layout' => 'default-simple',
      'parentRedirect' => $group->getHref(),
      'closeSmoothbox' => true,
    ));
  }
  
  public function removePhotoAction() {
  
		if(empty($_GET['photo_id'])) die('error');
		$photo = Engine_Api::_()->getItem('group_photo', $_GET['photo_id']);
		$db = Engine_Api::_()->getDbTable('photos', 'group')->getAdapter();
		$db->beginTransaction();
		try {
			$photo->delete();
			$db->commit();
			echo json_encode(array('status'=>"true"));die;
		} catch (Exception $e) {
			$db->rollBack();
			throw $e;
		}
  }
}
