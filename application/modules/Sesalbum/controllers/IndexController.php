<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sesalbum_IndexController extends Core_Controller_Action_Standard {

  //album constructor function
  public function init() {
    if (!$this->_helper->requireAuth()->setAuthParams('album', null, 'view')->isValid())
      return;
  }
  
  public function homeAction() {
    //Render
    $this->_helper->content->setEnabled();
  }
	public function tagsAction() {
    //Render
    $this->_helper->content->setEnabled();
  }
  

  public function browseAlbumsAction() {

    $integrateothermodule_id = $this->_getParam('integrateothersmodule_id', null);
    $page = 'sesalbum_index_' . $integrateothermodule_id;
    //Render
    $this->_helper->content->setContentName($page)->setEnabled();
  }
  
	public function welcomeAction(){
		//Render
    $this->_helper->content->setEnabled();
	}
	public function photoHomeAction(){
		//Render
    $this->_helper->content->setEnabled();
	}
	public function browsePhotoAction(){
		//Render
    $this->_helper->content->setEnabled();
	}
	public function editProfilephotoAction(){
		if( !Engine_Api::_()->core()->hasSubject() ) {
      // Can specifiy custom id
			$user_id = $this->_getParam('user_id', null);
      $subject = null;
      if( null === $user_id ) {
         echo json_encode(array('status'=>"error"));die;
      } else {
        $subject = Engine_Api::_()->getItem('user', $user_id);
        Engine_Api::_()->core()->setSubject($subject);
      }
    }

    $user = Engine_Api::_()->core()->getSubject();

    if( !$this->getRequest()->isPost() ) {
      echo json_encode(array('status'=>"error"));die;
    }
    // Uploading a new photo
    if(isset($_FILES['webcam']['tmp_name']) && $_FILES['webcam']['tmp_name'] != '') {
      $db = $user->getTable()->getAdapter();
      $db->beginTransaction();
      
      try {
        $userUp = $user->setPhoto($_FILES['webcam']);
        
        $iMain = Engine_Api::_()->getItem('storage_file', $user->photo_id);
        
        // Insert activity
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($user, $user, 'profile_photo_update',
          '{item:$subject} added a new profile photo.');

        // Hooks to enable albums to work
        if( $action ) {
          $event = Engine_Hooks_Dispatcher::_()
            ->callEvent('onUserProfilePhotoUpload', array(
                'user' => $user,
                'file' => $iMain,
              ));

          $attachment = $event->getResponse();
          if( !$attachment ) $attachment = $iMain;

          // We have to attach the user himself w/o album plugin
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $attachment);
        }
        
        $db->commit();
				 echo json_encode(array('status'=>"true",'src'=>Engine_Api::_()->storage()->get($userUp->photo_id)->getPhotoUrl('')));die;
      }
      // If an exception occurred within the image adapter, it's probably an invalid image
      catch( Engine_Image_Adapter_Exception $e )
      {
        $db->rollBack();
        echo json_encode(array('status'=>"error"));die;
      }
    }
  	 echo json_encode(array('status'=>"false"));die;
		
	}
	//upload existing photo
	public function uploadExistingphotoAction(){
		 $id = $this->_getParam('id', null);
     if(!$id){
		 	echo json_encode(array('status'=>"error"));die;
		 }
     $photo = Engine_Api::_()->getItem('album_photo', $id);
		 $user_id = $this->_getParam('user_id', null);
		 if(null == $user_id){
				echo json_encode(array('status'=>"error"));die; 
			}
		 $user  = Engine_Api::_()->getItem('user', $user_id);
    // Process
    $db = $user->getTable()->getAdapter();
    $db->beginTransaction();

    try {
      // Get the owner of the photo
      $photoOwnerId = null;
      if( isset($photo->user_id) ) {
        $photoOwnerId = $photo->user_id;
      } else if( isset($photo->owner_id) && (!isset($photo->owner_type) || $photo->owner_type == 'user') ) {
        $photoOwnerId = $photo->owner_id;
      }

      // if it is from your own profile album do not make copies of the image
      if( $photo instanceof Sesalbum_Model_Photo &&
          ($photoParent = $photo->getParent()) instanceof Sesalbum_Model_Album &&
          $photoParent->owner_id == $photoOwnerId &&
          $photoParent->type == 'profile' ) {

        // ensure thumb.icon and thumb.profile exist
        $newStorageFile = Engine_Api::_()->getItem('storage_file', $photo->file_id);
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
        if( $photo->file_id == $filesTable->lookupFile($photo->file_id, 'thumb.profile') ) {
          try {
            $tmpFile = $newStorageFile->temporary();
            $image = Engine_Image::factory();
            $image->open($tmpFile)
              ->autoRotate()
              ->resize(200, 400)
              ->write($tmpFile)
              ->destroy();
            $iProfile = $filesTable->createFile($tmpFile, array(
              'parent_type' => $user->getType(),
              'parent_id' => $user->getIdentity(),
              'user_id' => $user->getIdentity(),
              'name' => basename($tmpFile),
            ));
            $newStorageFile->bridge($iProfile, 'thumb.profile');
            @unlink($tmpFile);
          } catch( Exception $e ) {	echo json_encode(array('status'=>"error"));die;}
        }
        if( $photo->file_id == $filesTable->lookupFile($photo->file_id, 'thumb.icon') ) {
          try {
            $tmpFile = $newStorageFile->temporary();
            $image = Engine_Image::factory();
            $image->open($tmpFile);
            $size = min($image->height, $image->width);
            $x = ($image->width - $size) / 2;
            $y = ($image->height - $size) / 2;
            $image->resample($x, $y, $size, $size, 48, 48)
              ->write($tmpFile)
              ->destroy();
            $iSquare = $filesTable->createFile($tmpFile, array(
              'parent_type' => $user->getType(),
              'parent_id' => $user->getIdentity(),
              'user_id' => $user->getIdentity(),
              'name' => basename($tmpFile),
            ));
            $newStorageFile->bridge($iSquare, 'thumb.icon');
            @unlink($tmpFile);
          } catch( Exception $e ) {	echo json_encode(array('status'=>"error"));die;}
        }

        // Set it
        $user->photo_id = $photo->file_id;
        $user->save();
        
        // Insert activity
        // @todo maybe it should read "changed their profile photo" ?
        $action = Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $user, 'profile_photo_update',
                '{item:$subject} changed their profile photo.');
        if( $action ) {
          // We have to attach the user himself w/o sesalbum plugin
          Engine_Api::_()->getDbtable('actions', 'activity')
              ->attachActivity($action, $photo);
        }
				$db->commit();
				echo json_encode(array('status'=>"true",'src'=>Engine_Api::_()->storage()->get($user->photo_id)->getPhotoUrl('')));die;
      }

      // Otherwise copy to the profile album
      else {
        $userUp = $user->setPhoto($photo);

        // Insert activity
        $action = Engine_Api::_()->getDbtable('actions', 'activity')
            ->addActivity($user, $user, 'profile_photo_update',
                '{item:$subject} added a new profile photo.');
        
        // Hooks to enable albums to work
        $newStorageFile = Engine_Api::_()->getItem('storage_file', $user->photo_id);
        $event = Engine_Hooks_Dispatcher::_()
          ->callEvent('onUserProfilePhotoUpload', array(
              'user' => $user,
              'file' => $newStorageFile,
            ));

        $attachment = $event->getResponse();
        if( !$attachment ) {
          $attachment = $newStorageFile;
        }
        
        if( $action  ) {
          // We have to attach the user himself w/o album plugin
          Engine_Api::_()->getDbtable('actions', 'activity')
              ->attachActivity($action, $attachment);
        }
      }

      $db->commit();
			echo json_encode(array('status'=>"true",'src'=>Engine_Api::_()->storage()->get($userUp->photo_id)->getPhotoUrl('')));die;
    }
	 // Otherwise it's probably a problem with the database or the storage system (just throw it)
    catch( Exception $e )
    {
      $db->rollBack();
      echo json_encode(array('status'=>"error"));die;
    }
		echo json_encode(array('status'=>"error"));die;
	}
	//update cover photo 
	function uploadExistingcoverAction(){
		$id = $this->_getParam('id', null);
		$album_id = $this->_getParam('album_id', null);
     if(!$id){
		 	echo json_encode(array('status'=>"error"));die;
		 }
     $photo = Engine_Api::_()->getItem('album_photo', $id);
		 $album = Engine_Api::_()->getItem('album', $album_id);
		 $art_cover = $album->art_cover;
		 $storage_file = Engine_Api::_()->getItem('storage_file', $photo->file_id);
		 $coverAlbum = $album->setCoverPhoto($storage_file);
		 if($art_cover != 0){
			$im = Engine_Api::_()->getItem('storage_file', $art_cover);
			$im->delete();
		 }
		echo json_encode(array('file'=>Engine_Api::_()->getItem('storage_file', $coverAlbum->art_cover)->getPhotoUrl('')));die;
	}
	//get album photos as per given album id
	public function existingAlbumphotosAction(){
		$page = $this->_getParam('page', 1);
		$this->view->album_id = $album_id = isset($_POST['id']) ? $_POST['id'] : 0;
		if($album_id == 0){
			echo "";die;
		}
		$paginator = $this->view->paginator = Engine_Api::_()->getDbTable('photos', 'sesalbum')->getPhotoSelect(array('album_id'=>$album_id,'pagNator'=>true));
		$limit = 12;
		$paginator->setItemCountPerPage($limit);
		$paginator->setCurrentPageNumber($page);
		$this->view->page = $page ;
	}
	//get existing photo for profile photo change widget
	public function existingPhotosAction(){
		$page = $this->_getParam('page', 1);
		$paginator = $this->view->paginator = Engine_Api::_()->getDbTable('albums', 'sesalbum')->getUserAlbum();
		$this->view->limit = $limit = 12;
		$paginator->setItemCountPerPage($limit);
		$this->view->page = $page ;
		$paginator->setCurrentPageNumber($page);
	}
	//change cover photo action function
	public function changePositionAction(){
		$album_id = $this->_getParam('album_id', '0');
		if ($album_id == 0)
			return;
		$album = Engine_Api::_()->getItem('album', $album_id);
		if(!$album)
			return;
		$album->position_cover = $_POST['position'];
			$album->save();
		echo "true"; die;
	}
	//update cover photo function
	public function uploadCoverAction(){
		$album_id = $this->_getParam('album_id', '0');
		if ($album_id == 0)
			return;
		$album = Engine_Api::_()->getItem('album', $album_id);
		if(!$album)
			return;
		$art_cover = $album->art_cover;
		if(isset($_FILES['Filedata']))
			$data = $_FILES['Filedata'];
		else if(isset($_FILES['webcam']))
			$data = $_FILES['webcam'];
		$album->setCoverPhoto($data);
		if($art_cover != 0){
			$im = Engine_Api::_()->getItem('storage_file', $art_cover);
			$im->delete();
		}
		echo json_encode(array('file'=>Engine_Api::_()->storage()->get($album->art_cover)->getPhotoUrl('')));die;
	}
	//remove cover photo action
	public function removeCoverAction(){
		$album_id = $this->_getParam('album_id', '0');
		if ($album_id == 0)
			return;
		$album = Engine_Api::_()->getItem('album', $album_id);		
		if(!$album)
			return;
		if(isset($album->art_cover) && $album->art_cover>0){
			$im = Engine_Api::_()->getItem('storage_file', $album->art_cover);
			$album->art_cover = 0;
			$album->save();
			$im->delete();
		}
		echo "true";die;
	}
	//get user tag in given photo as per photo id
  public function tagPhotoAction() {
    $photo_id = $this->_getParam('photo_id', '0');
    if ($photo_id == 0)
      return;
    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $photo = Engine_Api::_()->getItem('photo', $photo_id);
    $parentTable = Engine_Api::_()->getItemTable('core_tag_map');
    $parentTableName = $parentTable->info('name');
    $select = $parentTable->select()
            ->from($parentTableName)
            ->where('resource_type = ?', 'album_photo')
            ->where('resource_id = ?', $photo->getIdentity())
            ->order('tagmap_id DESC');
    $this->view->photo_id = $photo->photo_id;
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    //Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
  }
	//get images as per album id (advance lightbox)
	public function correspondingImageAction(){
		$album_id = $this->_getParam('album_id', false);
		$this->view->paginator = $paginator = Engine_Api::_()->getDbtable('photos', 'sesalbum')->getPhotoSelect(array('album_id'=>$album_id,'limit_data'=>100));
	}
	//edit third party modules details action function
  public function editDetailAction() {
    $itemType = $this->_getParam('itemType', '0');
    if ($itemType == 'group') {
      $itemId = $this->_getParam('group_id', '0');
      $type = 'group_photo';
    } else {
      $itemId = $this->_getParam('event_id', '0');
      $type = 'event_photo';
    }
    $photo_id = $this->_getParam('photo_id', '0');
    $status = true;
    $error = false;
    $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0) {
      $status = false;
      $error = true;
    } else {
      $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
      $parentTable = Engine_Api::_()->getItemTable($type);
      $tableName = $parentTable->info('name');
      $dbGetInsert->query('UPDATE  ' . $tableName . ' SET  title ="' . $_POST['title'] . '" ,description="' . $_POST['description'] . '" WHERE ' . $itemType . '_id = "' . $itemId . '" AND photo_id ="' . $photo_id . '"');
    }
    echo json_encode(array('status' => $status, 'error' => $error));
    die;
  }
	//fetch user like album as per given album id .
  public function likeAlbumAction() {
    $album_id = $this->_getParam('album_id', '0');
    if ($album_id == 0)
      return;
		$this->view->title = $this->_getParam('title', 'People Who Like This');
    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
		$album = Engine_Api::_()->getItem('album', $album_id);
		$param['type'] = 'album';
		$param['id'] = $album->album_id;
   	$paginator = Engine_Api::_()->sesalbum()->likeItemCore($param);
    $this->view->album_id = $album->album_id;
    $this->view->paginator = $paginator ;
    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
  }
	//fetch user tagged in album as per given album id .
	public function taggedAlbumAction() {
    $album_id = $this->_getParam('album_id', '0');
    if ($album_id == 0)
      return;
		$this->view->title = $this->_getParam('title', 'User Tagged in This Album');
    $page = $this->_getParam('page', 1);
		$album = Engine_Api::_()->getItem('album', $album_id);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $select = Engine_Api::_()->sesalbum()->tagItemCore(array('id'=>$album_id,'album'=>true));
    $this->view->album_id = $album->album_id;
    $this->view->paginator = $paginator = $select;
    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
  }
	//fetch user favourite album as per given album id .
	public function favAlbumAction() {
    $album_id = $this->_getParam('album_id', '0');
    if ($album_id == 0)
      return;
		$this->view->title = $this->_getParam('title', 'User\'s Favourite This Album');
    $page = $this->_getParam('page', 1);
		$album = Engine_Api::_()->getItem('album', $album_id);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
   	$select = Engine_Api::_()->getDbTable('albums', 'sesalbum')->getFavourite(array('resource_id'=>$album_id));	
    $this->view->album_id = $album->album_id;
    $this->view->paginator = $paginator = $select;
    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
  }
	//fetch user like photo as per given photo id .
  public function likePhotoAction() {
    $photo_id = $this->_getParam('photo_id', '0');
    if ($photo_id == 0)
      return;
    $page = $this->_getParam('page', 1);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $photo = Engine_Api::_()->getItem('photo', $photo_id);
    $parentTable = Engine_Api::_()->getItemTable('core_like');
    $parentTableName = $parentTable->info('name');
    $select = $parentTable->select()
            ->from($parentTableName)
            ->where('resource_type = ?', 'album_photo')
            ->where('resource_id = ?', $photo->getIdentity())
            ->order('like_id DESC');
    $this->view->photo_id = $photo->photo_id;
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    // Set item count per page and current page number
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($page);
  }
	//get album categories ajax based.
  public function subcategoryAction() {
    $category_id = $this->_getParam('category_id', null);
    if ($category_id) {
			$subcategory = Engine_Api::_()->getDbtable('categories', 'sesalbum')->getModuleSubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';

      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;
    die;
  }
	// get album subsubcategory ajax based
  public function subsubcategoryAction() {

    $category_id = $this->_getParam('subcategory_id', null);
    if ($category_id) {
      $subcategory = Engine_Api::_()->getDbtable('categories', 'sesalbum')->getModuleSubsubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;die;
  }
	//make Album/Photo as off the day
	public function offthedayAction(){
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $type = $this->_getParam('type');
    $param = $this->_getParam('param');
    $this->view->form = $form = new Sesalbum_Form_Admin_Oftheday();
    if ($type == 'album') {
      $item = Engine_Api::_()->getItem('album', $id);
      $form->setTitle("Album of the Day");
      $form->setDescription('Here, choose the start date and end date for this  album to be displayed as "Album of the Day".');
      if (!$param)
        $form->remove->setLabel("Remove as  Album of the Day");
      $table = 'engine4_sesalbum_albums';
      $item_id = 'album_id';
    } elseif ($type == 'album_photo') {
      $item = Engine_Api::_()->getItem('album_photo', $id);
      $form->setTitle("Photo of the Day");
      if (!$param)
        $form->remove->setLabel("Remove as Photo of the Day");
      $form->setDescription('Here, choose the start date and end date for this photo to be displayed as "Photo of the Day".');
      $table = 'engine4_sesalbum_photos';
      $item_id = 'photo_id';
    }

    if (!empty($id))
      $form->populate($item->toArray());
    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost())) 
        return;
      $values = $form->getValues();
      $values['starttime'] = date('Y-m-d',  strtotime($values['starttime']));
      $values['endtime'] = date('Y-m-d', strtotime($values['endtime']));
      $db->update($table, array('starttime' => $values['starttime'], 'endtime' => $values['endtime']), array("$item_id = ?" => $id));
      if (isset($values['remove']) && $values['remove']) {
        $db->update($table, array('offtheday' => 0), array("$item_id = ?" => $id));
      } else {
        $db->update($table, array('offtheday' => 1), array("$item_id = ?" => $id));
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => false,
          'messages' => array('Successfully updated the item.')
      ));
    }
	}
		//make Album/Photo as sponsored.
	public function sponsoredAction(){
		$this->view->params = $params = $this->_getParam('type');
		if($params == 'photos'){
			$this->view->id = $id = $this->_getParam('photo_id');
			$item = Engine_Api::_()->getItem('photo', $id);
		}else{
			$this->view->id = $id = $this->_getParam('album_id');
			$item = Engine_Api::_()->getItem('album', $id);
		}
		if($item->is_sponsored == 1)
			$status = 0;
		else
			$status = 1;
		 // Check post
    //if( $this->getRequest()->isPost()) {
			$item->is_sponsored = $status;
			$item->save();
			echo $status;die;
		//}
		echo "error";die;
	}
	//make Album/Photo as featured.
	public function featuredAction(){
		$this->view->params = $params = $this->_getParam('type');
		if($params == 'photos'){
			$this->view->id = $id = $this->_getParam('photo_id');
			$item = Engine_Api::_()->getItem('photo', $id);
		}else{
			$this->view->id = $id = $this->_getParam('album_id');
			$item = Engine_Api::_()->getItem('album', $id);
		}
		if($item->is_featured == 1)
			$status = 0;
		else
			$status = 1;
		 // Check post
    //if( $this->getRequest()->isPost()) {
			$item->is_featured = $status;
			$item->save();
			echo $status;die;
		//}
		echo "error";die;
	}
	public function deletePhotoAction(){
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->album_id = $id = $this->_getParam('id');
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try
      {
        $photo = Engine_Api::_()->getItem('photo', $id);
        // delete the photo in the database
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
    // Output
    $this->renderScript('admin-manage/delete-photo.tpl');
	}
  public function deleteAction()
  {
		if($this->_getParam('photo_id', 0)){
			if ($photo_id > 0) {
				$photo = Engine_Api::_()->getItem('album_photo', $photo_id);
				$photo->_postDelete();
				$photo->delete();
				echo "true";die;
			}
		}
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->album_id = $id = $this->_getParam('id');
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try
      {
        $album = Engine_Api::_()->getItem('album', $id);
        // delete the album in the database
        $album->delete();
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
          'messages' => array('Album deleted successfully.')
      ));
    }
    // Output
    $this->renderScript('admin-manage/delete.tpl');
  }
	public function getalbumsAction(){
    $sesdata = array();
    $album_table = Engine_Api::_()->getDbtable('albums', 'sesalbum');
		$offtheday_table = Engine_Api::_()->getDbtable('offthedays', 'sesalbum');
		$offtheday_tableName = $offtheday_table->info('name');
		$sub_select = $offtheday_table->select()
                  ->from($offtheday_tableName, array("resource_id AS id"))
									->where('resource_type = ?','album');
    $select = $album_table->select()
                    ->where('title  LIKE ? ', '%' . $this->_getParam('text') . '%')
										->where("album_id NOT IN ?", $sub_select)
                    ->order('album_id ASC')->limit('25');
    $albums = $album_table->fetchAll($select);
    foreach ($albums as $album) {
      $album_icon_photo = $this->view->itemPhoto($album, 'thumb.icon');
      $sesdata[] = array(
          'id' => $album->album_id,
          'label' => $album->title,
          'photo' => $album_icon_photo
      );
    }
    return $this->_helper->json($sesdata);
	}
	public function getphotosAction(){
    $sesdata = array();
    $album_table = Engine_Api::_()->getDbtable('photos', 'sesalbum');
		$offtheday_table = Engine_Api::_()->getDbtable('offthedays', 'sesalbum');
		$offtheday_tableName = $offtheday_table->info('name');
		$sub_select = $offtheday_table->select()
                  ->from($offtheday_tableName, array("resource_id AS id"))
									->where('resource_type = ?','album_photo');
    $select = $album_table->select()
                    ->where('title  LIKE ? ', '%' . $this->_getParam('text') . '%')
										->where("photo_id NOT IN ?", $sub_select)
                    ->order('photo_id ASC')->limit('25');
    $albums = $album_table->fetchAll($select);
    foreach ($albums as $album) {
      $album_icon_photo = $this->view->itemPhoto($album, 'thumb.icon');
      $sesdata[] = array(
          'id' => $album->photo_id,
          'label' => $album->title,
          'photo' => $album_icon_photo
      );
    }
    return $this->_helper->json($sesdata);
	}	
	
	
	// album browse action.
  public function browseAction() {
    if (!$this->_helper->requireAuth()->setAuthParams('album', null, 'view')->isValid()) {
      return;
    }
    // Render
    $this->_helper->content
            //->setNoRender()
            ->setEnabled()
    ;
  }
  public function likeAction() {
    if (!$this->_helper->requireUser()->isValid()) {
      return;
    }
    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid()) {
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    $comment_id = $this->_getParam('comment_id');
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    if ($comment_id) {
      $commentedItem = $subject->comments()->getComment($comment_id);
    } else {
      $commentedItem = $subject;
    }
    // Process
    $db = $commentedItem->likes()->getAdapter();
    $db->beginTransaction();
    try {
      $commentedItem->likes()->addLike($viewer);
      // Add notification
      $owner = $commentedItem->getOwner();
      $this->view->owner = $owner->getGuid();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
        $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
        $notifyApi->addNotification($owner, $viewer, $commentedItem, 'liked', array(
            'label' => $commentedItem->getShortType()
        ));
      }
      // Stats
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.likes');
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    // For comments, render the resource
    if ($subject->getType() == 'core_comment') {
      $type = $subject->resource_type;
      $id = $subject->resource_id;
      Engine_Api::_()->core()->clearSubject();
    } else {
      $type = $subject->getType();
      $id = $subject->getIdentity();
    }
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Like added');
    $this->view->body = $this->view->action('list', 'comment', 'core', array(
        'type' => $type,
        'id' => $id,
        'format' => 'html',
        'page' => 1,
    ));
    $this->_helper->contextSwitch->initContext();
  }
	// album create action.
  public function createAction() {
  
    //Advanced activity album work
    $this->view->anfalbumparams = $anfalbumparams = $this->_getParam('params', null);
    $this->view->album_id = $this->_getParam('album_id', null);
    //media importer
    $this->view->mediaimporter = $mediaimporter = $this->_getParam('typeMedia', null);
    $this->view->isOpenPopup = $isOpenPopup = $this->_getParam('ispopup', null);
    if (isset($_GET['ul']) || isset($_FILES['Filedata']))
      return $this->_forward('upload-photo', null, null, array('format' => 'json'));
    if (!$this->_helper->requireAuth()->setAuthParams('album', null, 'create')->isValid())
      return;
		$album_id = $this->_getParam('album_id',false);
		if($album_id){
			$album = Engine_Api::_()->getItem('album', $album_id);
			if(!$album)
				return $this->_forward('notfound', 'error', 'core');
		}
		$sesalbum_create = Zend_Registry::isRegistered('sesalbum_create') ? Zend_Registry::get('sesalbum_create') : null;
    if(empty($sesalbum_create)) {
      return $this->_forward('notfound', 'error', 'core');
    }
    $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesalbum')->profileFieldId();
    if(isset($album->category_id) && $album->category_id != 0){
			$this->view->category_id = $album->category_id;	
		}else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
			$this->view->category_id = $_POST['category_id'];
		else
			$this->view->category_id = 0;
		if(isset($album->subsubcat_id) && $album->subsubcat_id != 0){
			$this->view->subsubcat_id = $album->subsubcat_id;	
		}else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
			$this->view->subsubcat_id = $_POST['subsubcat_id'];
		else
			$this->view->subsubcat_id = 0;	
		if(isset($album->subcat_id) && $album->subcat_id != 0){
			$this->view->subcat_id = $album->subcat_id;	
		}else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
			$this->view->subcat_id = $_POST['subcat_id'];
		else
			$this->view->subcat_id = 0;
		 // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['user_id'] = $viewer->getIdentity();
    $this->view->current_count =Engine_Api::_()->getDbtable('albums', 'sesalbum')->getUserAlbumCount($values);
    $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'album', 'max_albums');
		if(!empty($album))
			$this->view->quota = 0;
    // Get form
    $this->view->form = $form = new Sesalbum_Form_Album(array('defaultProfileId' => $defaultProfileId));
    // Render  
    if(empty($this->view->anfalbumparams) && empty($this->view->mediaimporter)) {
      $this->_helper->content->setEnabled();
    }
	 if (!$this->getRequest()->isPost()) {
		if (null !== ($album_id = $this->_getParam('album_id'))) {
			$form->populate(array(
					'album' => $album_id
			));
		}
		return;
	}
  if(!empty($_POST['sesmediaimporter'])){
    $form->sesmediaimporter_data->setValue($_POST['mediadata']);
    return;
  }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    if(!empty($_POST['sesmediaimporter_data'])){
       $mediaimporterdata = json_decode($_POST['sesmediaimporter_data'],true);
       $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
       if($this->_getParam('typeMedia','') == "zip"){
         $mediaimporterdata['zip_upload'] = $_SESSION['upload_zip'];
         $album = $this->sesMediaImporterAlbumCreate($form,$_POST,$viewer);
         if(!$album_id){
          $album->draft = 1;
          $album->save();
         }
         $mediaimporterdata['album_data']['sesalbum_album_id'] = $album->album_id;
       }else if(isset($mediaimporterdata['album_data']['album_id'])){
         foreach($mediaimporterdata['album_data']['album_id'] as $importerdata){
           $album = $this->sesMediaImporterAlbumCreate($form,$_POST,$viewer);
           $mediaimporterdata['album_data']['sesalbum_album_id'][$importerdata] = $album->album_id;
           if(!$album_id){
            $album->draft = 1;
            $album->save();
           }
         }
       }else{
          $album = $this->sesMediaImporterAlbumCreate($form,$_POST,$viewer);
          $mediaimporterdata['album_data']['sesalbum_album_id'] = $album->album_id;
          if(!$album_id){
            $album->draft = 1;
            $album->save();
          }
       }
       $mediaimporterdata = json_encode($mediaimporterdata);
       $this->_helper->layout->setLayout('default-simple');
       $dbGetInsert->query("INSERT INTO `engine4_sesmediaimporter_import`(`params`, `creation_date`, `modified_date`) VALUES ('".$mediaimporterdata."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");
       return $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Import Data successfully added in queue. You will get notification once your request processed.')),       'parentRefresh'=> 3000,
        'smoothboxClose' => 3000,
      ));
    }
    $db = Engine_Api::_()->getItemTable('album')->getAdapter();
    $db->beginTransaction();
    try {
      $album = $form->saveValues(true);
       
      // Add tags
      $values = $form->getValues();
      $tags = preg_split('/[,]+/', $values['tags']);
      $album->tags()->addTagMaps($viewer, $tags);
        if((Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
      if (isset($_POST['lat']) && isset($_POST['lng']) && $_POST['lat'] != '' && $_POST['lng'] != '') {
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng , resource_type) VALUES ("' . $album->album_id . '", "' . $_POST['lat'] . '","' . $_POST['lng'] . '","sesalbum_album")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['lat'] . '" , lng = "' . $_POST['lng'] . '"');
      }
    }else if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
      if(isset($_POST['country']) || isset($_POST['state']) || isset($_POST['city']) || isset($_POST['zip']) || isset($_POST['latValue']) || isset($_POST['lngValue']) || isset($_POST['location'])){
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng ,venue, resource_type , city , state , zip , country) VALUES ("' . $album->album_id . '", "' . $_POST['latValue'] . '","' . $_POST['lngValue'] . '","' .$_POST['location'] . '","sesalbum_album", "' . $_POST['city'] . '","' . $_POST['state'] . '","' . $_POST['zip'] . '", "' . $_POST['country'] . '") ON DUPLICATE KEY UPDATE lat = "' . $_POST['latValue'] . '" , lng = "' . $_POST['lngValue'] . '",city = "' . $_POST['city'] . '" ,state = "' . $_POST['state'] . '" ,zip = "' . $_POST['zip'] . '" ,country = "' . $_POST['country'] . '",venue = "' . $_POST['location'] . '"');

      }
    }
      // Add fields
      $customfieldform = $form->getSubForm('fields');
			if($customfieldform){
      	$customfieldform->setItem($album);
      	$customfieldform->saveValues();
			}
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    if($album->resource_id && $album->resource_type) {
      $resource = Engine_Api::_()->getItem($album->resource_type, $album->resource_id);
      header('location:'.$resource->getHref());
      die;
    } else {
      $url = Engine_Api::_()->sesalbum()->getHref($album->getIdentity(),$album->album_id); 
      header('location:'.$url);
    //   return $this->_forward('success', 'utility', 'core', array(
    //             'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your changes have been saved.')),
    //             'layout' => 'default-simple',
    //             'parentRefresh' => true,
    //             'smoothboxClose' => 2000,
    // ));
    }
  }
  public function sesMediaImporterAlbumCreate($form,$post,$viewer){
    $db = Engine_Api::_()->getItemTable('album')->getAdapter();
    $db->beginTransaction();
    try {
      $album = $form->saveValues(0);
      // Add tags
      $values = $form->getValues();
      $tags = preg_split('/[,]+/', $values['tags']);
      $album->tags()->addTagMaps($viewer, $tags);
      if (isset($post['lat']) && isset($post['lng']) && $post['lat'] != '' && $post['lng'] != '') {
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng , resource_type) VALUES ("' . $album->album_id . '", "' . $post['lat'] . '","' . $post['lng'] . '","sesalbum_album")	ON DUPLICATE KEY UPDATE	lat = "' . $post['lat'] . '" , lng = "' . $post['lng'] . '"');
      }
      // Add fields
      $customfieldform = $form->getSubForm('fields');
			if($customfieldform){
      	$customfieldform->setItem($album);
      	$customfieldform->saveValues();
			}
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }  
     return $album;
  }
	//send message to site user function.
	public function messageAction(){
    // Make form
    $this->view->form = $form = new Sesalbum_Form_Compose();
    // Get params
    $multi = $this->_getParam('multi');
    $to = $this->_getParam('to');
    $viewer = Engine_Api::_()->user()->getViewer();
    $toObject = null;
    // Build
    $isPopulated = false;
    if( !empty($to) && (empty($multi) || $multi == 'user') ) {
      $multi = null;
      // Prepopulate user
      $toUser = Engine_Api::_()->getItem('user', $to);
      $isMsgable = ( 'friends' != Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ||
          $viewer->membership()->isMember($toUser) );
      if( $toUser instanceof User_Model_User &&
          (!$viewer->isBlockedBy($toUser) && !$toUser->isBlockedBy($viewer)) &&
          isset($toUser->user_id) &&
          $isMsgable ) {
        $this->view->toObject = $toObject = $toUser;
        $form->toValues->setValue($toUser->getGuid());
        $isPopulated = true;
      } else {
        $multi = null;
        $to = null;
      }
    }
    $this->view->isPopulated = $isPopulated;
    // Assign the composing stuff
    $composePartials = array();
    // Get config
    $this->view->maxRecipients = $maxRecipients = 10;
    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }
    // Process
    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
    $db->beginTransaction();
    try {
      // Try attachment getting stuff
      $attachment = null;
			$id = Zend_Controller_Front::getInstance()->getRequest()->getParam('album_id');
			if($id){
					$attachment = Engine_Api::_()->getItem('album', $id);
					$type = 'album';
			}
			if(!$id){
				$id = Zend_Controller_Front::getInstance()->getRequest()->getParam('photo_id');
				if($id){
					$type = Zend_Controller_Front::getInstance()->getRequest()->getParam('type','album_photo');
					$attachment = Engine_Api::_()->getItem($type, $id);
					$type = 'photo';
				}
			}      
      $viewer = Engine_Api::_()->user()->getViewer();
      $values = $form->getValues();
			
      // Prepopulated
      if( $toObject instanceof User_Model_User ) {
        $recipientsUsers = array($toObject);
        $recipients = $toObject;
        // Validate friends
        if( 'friends' == Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ) {
          if( !$viewer->membership()->isMember($recipients) ) {
            return $form->addError('One of the members specified is not in your friends list.');
          }
        }
      } else if( $toObject instanceof Core_Model_Item_Abstract &&
          method_exists($toObject, 'membership') ) {
        $recipientsUsers = $toObject->membership()->getMembers();
        $recipients = $toObject;
      }
      // Normal
      else {
        $recipients = preg_split('/[,. ]+/', $values['toValues']);
        // clean the recipients for repeating ids
        // this can happen if recipient is selected and then a friend list is selected
        $recipients = array_unique($recipients);
        // Slice down to 10
        $recipients = array_slice($recipients, 0, $maxRecipients);
        // Get user objects
        $recipientsUsers = Engine_Api::_()->getItemMulti('user', $recipients);
        // Validate friends
        if( 'friends' == Engine_Api::_()->authorization()->getPermission($viewer, 'messages', 'auth') ) {
          foreach( $recipientsUsers as &$recipientUser ) {
            if( !$viewer->membership()->isMember($recipientUser) ) {
              return $form->addError('One of the members specified is not in your friends list.');
            }
          }
        }
      }

      // Create conversation
      $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
        $viewer,
        $recipients,
        $values['title'],
        $values['body'],
        $attachment
      );

      // Send notifications
      foreach( $recipientsUsers as $user ) {
        if( $user->getIdentity() == $viewer->getIdentity() ) {
          continue;
        }
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
          $user,
          $viewer,
          $conversation,
          'message_new'
        );
      }

      // Increment messages counter
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');

      // Commit
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
    
    if( $this->getRequest()->getParam('format') == 'smoothbox' ) {
      return $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your message has been sent successfully.')),
        'smoothboxClose' => true,
      ));
    }
  	
	}
	//share Album/photo function.
	public function shareAction(){
    if( !$this->_helper->requireUser()->isValid() ) return;
    
    $type = $this->_getParam('type');
    $id = $this->_getParam('photo_id');    

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->attachment = $attachment = Engine_Api::_()->getItem($type, $id);
    $this->view->form = $form = new Sesalbum_Form_Share();

    if( !$attachment ) {
      // tell smoothbox to close
      $this->view->status  = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('You cannot share this item because it has been removed.');
      $this->view->smoothboxClose = true;
      //return $this->render('deletedItem');
    }
    // hide facebook and twitter option if not logged in
    $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
    if( !$facebookTable->isConnected() ) {
      $form->removeElement('post_to_facebook');
    }
    $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
    if( !$twitterTable->isConnected() ) {
      $form->removeElement('post_to_twitter');
    }
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }
    // Process
    $db = Engine_Api::_()->getDbtable('actions', 'activity')->getAdapter();
    $db->beginTransaction();
    try {
      // Get body
      $body = $form->getValue('body');
      // Set Params for Attachment
      $params = array(
          'type' => '<a href="'.$attachment->getHref().'">'.$attachment->getMediaType().'</a>',          
      );
      // Add activity
      $api = Engine_Api::_()->getDbtable('actions', 'activity');
      //$action = $api->addActivity($viewer, $viewer, 'post_self', $body);
      $action = $api->addActivity($viewer, $attachment->getOwner(), 'share', $body, $params);      
      if( $action ) { 
        $api->attachActivity($action, $attachment);
      }
      $db->commit();
      // Notifications
      $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
      // Add notification for owner of activity (if user and not viewer)
      if( $action->subject_type == 'user' && $attachment->getOwner()->getIdentity() != $viewer->getIdentity() )
      {
        $notifyApi->addNotification($attachment->getOwner(), $viewer, $action, 'shared', array(
          'label' => $attachment->getMediaType(),
        ));
      }
      // Preprocess attachment parameters
      $publishMessage = html_entity_decode($form->getValue('body'));
      $publishUrl = null;
      $publishName = null;
      $publishDesc = null;
      $publishPicUrl = null;
      // Add attachment
      if( $attachment ) {        
        $publishUrl = $attachment->getHref();
        $publishName = $attachment->getTitle();
        $publishDesc = $attachment->getDescription();
        if( empty($publishName) ) {
          $publishName = ucwords($attachment->getShortType());
        }
        if( ($tmpPicUrl = $attachment->getPhotoUrl()) ) {
          $publishPicUrl = $tmpPicUrl;
        }
        // prevents OAuthException: (#100) FBCDN image is not allowed in stream
        if( $publishPicUrl &&
            preg_match('/fbcdn.net$/i', parse_url($publishPicUrl, PHP_URL_HOST)) ) {
          $publishPicUrl = null;
        }
      } else {
        $publishUrl = $action->getHref();
      }
      // Check to ensure proto/host
      if( $publishUrl &&
          false === stripos($publishUrl, 'http://') &&
          false === stripos($publishUrl, 'https://') ) {
        $publishUrl = 'http://' . $_SERVER['HTTP_HOST'] . $publishUrl;
      }
      if( $publishPicUrl &&
          false === stripos($publishPicUrl, 'http://') &&
          false === stripos($publishPicUrl, 'https://') ) {
        $publishPicUrl = 'http://' . $_SERVER['HTTP_HOST'] . $publishPicUrl;
      }
      // Add site title
      if( $publishName ) {
        $publishName = Engine_Api::_()->getApi('settings', 'core')->core_general_site_title
            . ": " . $publishName;
      } else {
        $publishName = Engine_Api::_()->getApi('settings', 'core')->core_general_site_title;
      }
      // Publish to facebook, if checked & enabled
      if( $this->_getParam('post_to_facebook', false) &&
          'publish' == Engine_Api::_()->getApi('settings', 'core')->core_facebook_enable ) {
        try {
          $facebookTable = Engine_Api::_()->getDbtable('facebook', 'user');
          $facebookApi = $facebook = $facebookTable->getApi();
          $fb_uid = $facebookTable->find($viewer->getIdentity())->current();
          if( $fb_uid &&
              $fb_uid->facebook_uid &&
              $facebookApi &&
              $facebookApi->getUser() &&
              $facebookApi->getUser() == $fb_uid->facebook_uid ) {
            $fb_data = array(
              'message' => $publishMessage,
            );
            if( $publishUrl ) {
              $fb_data['link'] = $publishUrl;
            }
            if( $publishName ) {
              $fb_data['name'] = $publishName;
            }
            if( $publishDesc ) {
              $fb_data['description'] = $publishDesc;
            }
            if( $publishPicUrl ) {
              $fb_data['picture'] = $publishPicUrl;
            }
            $res = $facebookApi->api('/me/feed', 'POST', $fb_data);
          }
        } catch( Exception $e ) {
          // Silence
        }
      } // end Facebook
      // Publish to twitter, if checked & enabled
      if( $this->_getParam('post_to_twitter', false) &&
          'publish' == Engine_Api::_()->getApi('settings', 'core')->core_twitter_enable ) {
        try {
          $twitterTable = Engine_Api::_()->getDbtable('twitter', 'user');
          if( $twitterTable->isConnected() ) {
            // Get attachment info
            $title = $attachment->getTitle();
            $url = $attachment->getHref();
            $picUrl = $attachment->getPhotoUrl();
            // Check stuff
            if( $url && false === stripos($url, 'http://') ) {
              $url = 'http://' . $_SERVER['HTTP_HOST'] . $url;
            }
            if( $picUrl && false === stripos($picUrl, 'http://') ) {
              $picUrl = 'http://' . $_SERVER['HTTP_HOST'] . $picUrl;
            }
            // Try to keep full message
            // @todo url shortener?
            $message = html_entity_decode($form->getValue('body'));
            if( strlen($message) + strlen($title) + strlen($url) + strlen($picUrl) + 9 <= 140 ) {
              if( $title ) {
                $message .= ' - ' . $title;
              }
              if( $url ) {
                $message .= ' - ' . $url;
              }
              if( $picUrl ) {
                $message .= ' - ' . $picUrl;
              }
            } else if( strlen($message) + strlen($title) + strlen($url) + 6 <= 140 ) {
              if( $title ) {
                $message .= ' - ' . $title;
              }
              if( $url ) {
                $message .= ' - ' . $url;
              }
            } else {
              if( strlen($title) > 24 ) {
                $title = Engine_String::substr($title, 0, 21) . '...';
              }
              // Sigh truncate I guess
              if( strlen($message) + strlen($title) + strlen($url) + 9 > 140 ) {
                $message = Engine_String::substr($message, 0, 140 - (strlen($title) + strlen($url) + 9)) - 3 . '...';
              }
              if( $title ) {
                $message .= ' - ' . $title;
              }
              if( $url ) {
                $message .= ' - ' . $url;
              }
            }
            $twitter = $twitterTable->getApi();
            $twitter->statuses->update($message);
          }
        } catch( Exception $e ) {
          // Silence
        }
      }
      // Publish to janrain
      if( //$this->_getParam('post_to_janrain', false) &&
          'publish' == Engine_Api::_()->getApi('settings', 'core')->core_janrain_enable ) {
        try {
          $session = new Zend_Session_Namespace('JanrainActivity');
          $session->unsetAll();
          
          $session->message = $publishMessage;
          $session->url = $publishUrl ? $publishUrl : 'http://' . $_SERVER['HTTP_HOST'] . _ENGINE_R_BASE;
          $session->name = $publishName;
          $session->desc = $publishDesc;
          $session->picture = $publishPicUrl;
          
        } catch( Exception $e ) {
          // Silence
        }
      }
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e; // This should be caught by error handler
    }
    // If we're here, we're done
    $this->view->status = true;
    $this->view->message =  Zend_Registry::get('Zend_Translate')->_('Success!');

    // Redirect if in normal context
      $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Photo share successfully.')),
        'smoothboxClose' => true,
        'parentRefresh'=> false,
      ));
	}
	//download album/photo function.
	public function downloadAction(){
		$photo =  $this->_getParam('type',false);
		if(!$photo){
			$album_id = $this->_getParam('album_id',false);
			if(!$album_id)
				return;
		}else if($photo == 'sesvideo_chanelphoto'){
			$chanelphoto_id = $this->_getParam('photo_id',false);
			if(!$chanelphoto_id)
				return;		
		}else{
			$photo_id = $this->_getParam('photo_id',false);
			if(!$photo_id)
				return;	
			
		}
		$viewer = Engine_Api::_()->user()->getViewer();
		$canDownload = Engine_Api::_()->authorization()->isAllowed('album',$viewer, 'download');
		if(!$canDownload)
			return $this->_forward('requireauth', 'error', 'core');
    $user_id = $viewer->getIdentity();
		# create new zip opbject
			$zip = new ZipArchive();			
			# create a temp file & open it
			$tmp_file = tempnam('.','');
			$zip->open($tmp_file, ZipArchive::CREATE);
			# loop through each file
		if(isset($album_id)){
			$album = Engine_Api::_()->getItem('album', $album_id);
			$album->download_count = new Zend_Db_Expr('download_count + 1');
      $album->save();
			$paginator = Engine_Api::_()->getDbTable('photos', 'sesalbum')->getPhotoSelect(array('album_id' =>$album_id));
			$counter = 0;
			foreach($paginator as $file)
			{ 
				$counter++;
				$name = Engine_Api::_()->getItem('storage_file', $file->file_id)->name;
				if(strpos($file->getPhotoUrl('thumb.main'),'http') === FALSE){
					$file = (!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$file->getPhotoUrl();
				}else{
					$file = $file->getPhotoUrl();
				}
				$download_file = file_get_contents($file);
				$zip->addFromString($name,$download_file);
				//$zip->addFile($url,$new_filename); 
			}
			$downloadfilename = substr($album->title,0,10);
		}else{
			if(!isset($chanelphoto_id)){
				$photo = Engine_Api::_()->getItem('album_photo', $photo_id);	
				$photo->download_count = new Zend_Db_Expr('download_count + 1');
				$photo->save();
			}else{
				$photo = Engine_Api::_()->getItem($photo, $chanelphoto_id);	
			}
				if(strpos($photo->getPhotoUrl('thumb.main'),'http') === FALSE){
					$file = (!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].'/'.$photo->getPhotoUrl();
				}else{
					$file = $photo->getPhotoUrl();
				}
				$download_file = file_get_contents($file);
				$name = explode('?',basename($file));
				$zip->addFromString($name[0],$download_file);
				$downloadfilename = Engine_Api::_()->getItem('storage_file', $photo->file_id)->name;
				$downloadfilename = explode('.',$downloadfilename);
				$downloadfilename = $downloadfilename[0];
				//$zip->addFile($url,$new_filename); 
		}
			# close zip
			$zip->close();
			# send the file to the browser as a download
			header('Content-disposition: attachment; filename='.urlencode(basename($downloadfilename)).'.zip');
			header('Content-type: application/zip');
			readfile($tmp_file);
			@unlink($tmp_file);
			die;
	}
	//rate album/photo function.
  public function rateAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();

    $rating = $this->_getParam('rating');
    $resource_id = $this->_getParam('resource_id');
    $resource_type = $this->_getParam('resource_type');


    $table = Engine_Api::_()->getDbtable('ratings', 'sesalbum');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
      Engine_Api::_()->getDbtable('ratings', 'sesalbum')->setRating($resource_id, $user_id, $rating, $resource_type);
      if ($this->_getParam('resource_type') && $this->_getParam('resource_type') == 'album'){
        $item = Engine_Api::_()->getItem('album', $resource_id);
				$addachActivityType = 'sesalbum_album_rating';
			}else{
        $item = Engine_Api::_()->getItem('photo', $resource_id);
				$addachActivityType = 'sesalbum_photo_rating';
			}

      $item->rating = Engine_Api::_()->getDbtable('ratings', 'sesalbum')->getRating($item->getIdentity(), $resource_type);
      $item->save();
			//send notification to owner and user activity feed work.
			$viewer = Engine_Api::_()->user()->getViewer();
			$subject =$item;
			 $owner = $subject->getOwner();
			 if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
       
			$activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
			 Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $addachActivityType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $addachActivityType);
        $result = $activityTable->fetchRow(array('type =?' => $addachActivityType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        if (!$result) {
          $action = $activityTable->addActivity($viewer, $subject, $addachActivityType);
          if ($action)
            $activityTable->attachActivity($action, $subject);
        }
		  }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $total = Engine_Api::_()->getDbtable('ratings', 'sesalbum')->ratingCount($item->getIdentity(), $resource_type);
    $rating_sum = Engine_Api::_()->getDbtable('ratings', 'sesalbum')->getSumRating($item->getIdentity(), $resource_type);

    $data = array();
		$totalTxt = $this->view->translate(array('%s rating', '%s ratings', $total), $total);
    $data[] = array(
        'total' => $total,
        'rating' => $rating,
				'totalTxt'=>str_replace($total,'',$totalTxt),
        'rating_sum' => $rating_sum
    );
    return $this->_helper->json($data);
  }
  public function manageAction() {
    if (!$this->_helper->requireUser()->isValid())
      return; 
		// Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() )
     return $this->_forward('requireauth', 'error', 'core');
    // Render
    $sesalbum_manage = Zend_Registry::isRegistered('sesalbum_manage') ? Zend_Registry::get('sesalbum_manage') : null;
    if(!empty($sesalbum_manage)) {
      $this->_helper->content->setEnabled();
    }
  }

  public function uploadPhotoAction() {
    if (!$this->_helper->requireAuth()->setAuthParams('album', null, 'create')->isValid())
      return;

    if (!$this->_helper->requireUser()->checkRequire()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
      return;
    }
	
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
		
		if(empty($_GET['isURL']) || $_GET['isURL'] == 'false'){
			$isURL = false;	
			$values = $this->getRequest()->getPost();
			if (empty($values['Filename']) && !isset($_FILES['Filedata'])) {
				$this->view->status = false;
				$this->view->error = Zend_Registry::get('Zend_Translate')->_('No file');
				return;
			}
			if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
				$this->view->status = false;
				$this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
				return;
			}
			$uploadSource = $_FILES['Filedata'];
		}else{
			$uploadSource = $_POST['Filedata'];
			$isURL = true;	
		}
		
    $db = Engine_Api::_()->getDbtable('photos', 'sesalbum')->getAdapter();
    $db->beginTransaction();
    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      $photoTable = Engine_Api::_()->getDbtable('photos', 'sesalbum');
      $photo = $photoTable->createRow();
      $photo->setFromArray(array(
          'owner_type' => 'user',
          'owner_id' => $viewer->getIdentity()
      ));

      $photo->save();

      $photo->order = $photo->photo_id;
      $setPhoto = $photo->setPhoto($uploadSource,$isURL);
			if(!$setPhoto){
				$db->rollBack();
				$this->view->status = false;
				$this->view->error = 'An error occurred.';
				return;
			}
      $photo->save();

      $this->view->status = true;
      $this->view->photo_id = $photo->photo_id;
			$this->view->url = $photo->getPhotoUrl('thumb.normalmain');
      $db->commit();
    }catch (Sesalbum_Model_Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
  }
	 public function editPhotoAction() {
    $this->view->photo_id = $photo_id = $this->_getParam('photo_id');
		$this->view->photo = Engine_Api::_()->getItem('photo', $photo_id);
    $this->view->locationData = $locationData = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData('sesalbum_photo', $photo_id);
  }
	//edit photo details from light function.
	 public function saveInformationAction() { 
    $photo_id = $this->_getParam('photo_id');
    $title = $this->_getParam('title', null);
    $description = $this->_getParam('description', null);
		$location = $this->_getParam('location',null);
    if((Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
		if (($this->_getParam('lat')) && ($this->_getParam('lng')) && $this->_getParam('lat','') != '' && $this->_getParam('lng','')!= '') {
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng , venue , resource_type) VALUES ("' . $photo_id . '", "' . $this->_getParam('lat') . '","' . $this->_getParam('lng') . '","' . $location . '","sesalbum_photo")	ON DUPLICATE KEY UPDATE	venue = "' . $location . '" ,lat = "' . $this->_getParam('lat') . '" , lng = "' . $this->_getParam('lng') . '"');
      }
    } else if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
      $country = $this->_getParam('country',null);
      $state = $this->_getParam('state',null);
      $city = $this->_getParam('city',null);
      $zip = $this->_getParam('zip',null);
      $latValue = $this->_getParam('latValue',null);
      $lngValue = $this->_getParam('lngValue',null);
      if(isset($country) || isset($state) || isset($city) || isset($zip) || isset($latValue) || isset($lngValue) || isset($location)){
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng ,venue, resource_type , city , state , zip , country) VALUES ("' . $photo_id . '", "' . $latValue . '","' . $lngValue . '","' .$location . '","sesalbum_photo", "' . $city . '","' . $state . '","' . $zip . '", "' . $country . '") ON DUPLICATE KEY UPDATE lat = "' . $latValue . '" , lng = "' . $lngValue . '",city = "' . $city . '" ,state = "' . $state . '" ,zip = "' . $zip . '" ,country = "' . $country . '",venue = "' . $location . '"');

      }

    }
    Engine_Api::_()->getDbTable('photos', 'sesalbum')->update(array('title' => $title, 'description' => $description,'location'=>$location), array('photo_id = ?' => $photo_id));
    echo json_encode(array('status'=>"true"));die;
  }
	 //ACTION FOR PHOTO DELETE
  public function removeAction() {
			if(empty($_GET['photo_id']))
				die('error');
      //GET PHOTO ID AND ITEM
			$photo_id = (int) $this->_getParam('photo_id');
	    $photo = Engine_Api::_()->getItem('photo', $photo_id);
      $db = Engine_Api::_()->getDbTable('photos', 'sesalbum')->getAdapter();
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
   public function resetPageSettingsAction(){
    // Make form
    $this->view->form = $form = new Sesalbum_Form_Photo_Delete();
    $form->setTitle("Reset This Page ?");
      $form->setDescription('Are you sure you want to reset this page? Once reset, it will not be undone.');
      $form->execute->setLabel("Reset Page");
      if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
      $page_id = (int) $this->_getParam('page_id');
      $page_name = $this->_getParam('page_name');
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      try{
      if ($page_name == 'sesalbum_index_browse-photo') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
      // Insert top
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => 1,
      ));
      $top_id = $db->lastInsertId();
      // Insert main
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => 2,
      ));
      $main_id = $db->lastInsertId();
      // Insert top-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_id,
          'order' => 6,
      ));
      $top_middle_id = $db->lastInsertId();
      // Insert main-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 6,
      ));
      $main_middle_id = $db->lastInsertId();
      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.browse-menu',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => 3,
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.browse-search',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => 4,
          'params' => '{"search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","mostSPrated","mostSPfavourite","featured","sponsored"],"search_for":"photo","view_type":"horizontal","title":"","nomobile":"0","name":"sesalbum.browse-search"}'
      ));
      // Insert content
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.album-home-error',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => 9,
          'params' => '{"itemType":"photo","title":"","nomobile":"0","name":"sesalbum.album-home-error"}'
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.tabbed-widget',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => 7,
          'params' => '{"photo_album":"photo","tab_option":"filter","view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"50","show_limited_data":"no","pagging":"auto_load","title_truncation":"40","height":"350","width":"400","search_type":["mostSPliked"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"","nomobile":"0","name":"sesalbum.tabbed-widget"}'
      ));
  }
  if ($page_name == 'sesalbum_index_browse') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    
  // Insert top
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert search
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-search',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 4,
      'params' => '{"search_for":"album","view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","mostSPrated","mostSPfavourite","featured","sponsored"],"default_search_type":"mostSPliked","friend_show":"yes","search_title":"yes","browse_by":"yes","categories":"yes","location":"yes","kilometer_miles":"yes","title":"","nomobile":"0","name":"sesalbum.browse-search"}'
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-albums',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"load_content":"auto_load","sort":"mostSPliked","view_type":"2","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","featured","sponsored","likeButton","favouriteButton"],"title_truncation":"30","limit_data":"21","height":"240","width":"395","title":"","nomobile":"0","name":"sesalbum.browse-albums"}'
  ));
   // Insert album message
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-home-error',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 8,
  ));
  }
  if ($page_name == 'sesalbum_category_index') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
  // Insert top
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 3,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.category-view',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
      'params' => '{"show_subcat":"1","show_subcatcriteria":["title","icon","countAlbums"],"heightSubcat":"170","widthSubcat":"355","dummy1":null,"show_criteria":["featuredLabel","sponsoredLabel","like","comment","rating","view","title","by","favourite","photo"],"pagging":"button","album_limit":"19","height":"260","width":"400","title":"","nomobile":"0","name":"sesalbum.category-view"}'
  ));
  }
  if ($page_name == 'sesalbum_category_browse') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesalbum' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category" . DIRECTORY_SEPARATOR;
  if (is_file($PathFile . "banners" . DIRECTORY_SEPARATOR . 'category_banner.jpg')) {
    if (!file_exists(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/admin')) {
      mkdir(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/admin', 0777, true);
    }
    copy($PathFile . "banners" . DIRECTORY_SEPARATOR . 'category_banner.jpg', APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/admin/category_banner.jpg');
    $category_banner = 'public/admin/category_banner.jpg';
  } else {
    $category_banner = '';
  }
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.banner-category',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 6,
      'params' => '{"description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\r\n\r\n Pellentesque lacinia hendrerit leo, nec hendrerit magna porttitor at. Vestibulum pellentesque erat orci, non mollis purus ornare a. Ut a blandit dolor. Quisque ac pharetra ex. Aliquam pretium pharetra elementum. Phasellus nec mollis metus, non pellentesque purus. Vivamus in sem facilisis, dictum ex suscipit, imperdiet tortor. Sed varius massa ex, quis porta elit interdum non. Mauris at dictum nisi. Maecenas malesuada diam sit amet turpis porttitor, ut aliquam nibh facilisis. Ut sit amet ligula lacus.\r\n\r\nIn hac habitasse platea dictumst. Cras mollis sagittis feugiat. Nunc ac velit eu turpis congue lobortis. Pellentesque quam diam, feugiat vitae ipsum sit amet, aliquet vestibulum ligula. Sed nulla risus, malesuada blandit egestas vel, semper a risus. Pellentesque et tincidunt mauris. Nunc sodales diam dictum, sollicitudin leo nec, dapibus sapien. Suspendisse a fringilla urna. Quisque luctus neque tristique, cursus nulla ac, egestas felis. Proin dapibus condimentum posuere. Aenean lacinia volutpat convallis. In gravida, elit eu imperdiet venenatis, lacus risus venenatis quam, at consectetur tortor tortor malesuada enim. Nam hendrerit ipsum vel odio molestie rutrum. Vivamus vitae risus eget est vehicula consequat. In varius nec dolor eu aliquet. ","sesalbum_categorycover_photo":"' . $category_banner . '","title":"Categories","nomobile":"0","name":"sesalbum.banner-category"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-category',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"height":"155","width":"250","allign_content":"center","criteria":"admin_order","show_criteria":["title","icon","countAlbums"],"title":"All Categories","nomobile":"0","name":"sesalbum.album-category"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.category-associate-album',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 8,
      'params' => '{"show_criteria":["like","comment","rating","view","title","description","favourite","by","featuredLabel","sponsoredLabel","albumPhoto","photoCounts","photoThumbnail","albumCount"],"popularity_album":"most_liked","pagging":"auto_load","count_album":"0","criteria":"most_album","category_limit":"5","album_limit":"10","photo_limit":"22","seemore_text":"+ See All [category_name]","allignment_seeall":"left","title_truncation":"150","description_truncation":"120","height":"80","width":"80","title":"","nomobile":"0","name":"sesalbum.category-associate-album"}'
  ));
  }
  if ($page_name == 'sesalbum_index_home') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert main-left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'left',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 4,
  ));
  $main_left_id = $db->lastInsertId();
  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 5,
  ));
  $main_right_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored-carosel',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 4,
      'params'=>'{"featured_sponsored_carosel":"2","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"duration":"300","height":"270","width":"293","info":"most_favourite","title_truncation":"20","limit_data":"3","total_limit_data":"5","title":"Featured Albums","nomobile":"0","name":"sesalbum.featured-sponsored-carosel"}'
  ));
  //Insert popular albums widget
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.of-the-day',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => 7,
      'params' => '{"ofTheDayType":"albums","insideOutside":"outside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"title_truncation":"15","height":"250","width":"200","title":"Album Of The Day","nomobile":"0","name":"sesalbum.of-the-day"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => 8,
      'params' => '{"tableName":"album","criteria":"5","info":"most_download","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"2","title_truncation":"20","height":"160","width":"180","limit_data":"3","title":"Most Downloaded Albums","nomobile":"0","name":"sesalbum.featured-sponsored"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => 9,
      'params' => '{"tableName":"album","criteria":"5","info":"most_liked","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"2","title_truncation":"20","height":"160","width":"180","limit_data":"3","title":"Most Liked Albums","nomobile":"0","name":"sesalbum.featured-sponsored"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => 10,
      'params' => '{"tableName":"album","criteria":"5","info":"recently_liked","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"2","title_truncation":"20","height":"162","width":"180","limit_data":"3","title":"Most Viewed Albums","nomobile":"0","name":"sesalbum.featured-sponsored"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-search',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 12,
      'params' => '{"search_for":"album","view_type":"horizontal","search_type":"","friend_show":"no","search_title":"yes","browse_by":"no","categories":"yes","location":"yes","kilometer_miles":"yes","title":"","nomobile":"0","name":"sesalbum.browse-search"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-category',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 13,
      'params' => '{"height":"110","width":"160","criteria":"admin_order","show_criteria":["title","icon"],"title":"Popular Categories","nomobile":"0","name":"sesalbum.album-category"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 14,
      'params' => '{"tableName":"album","criteria":"5","info":"most_rated","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"1","title_truncation":"20","height":"200","width":"220","limit_data":"3","title":"Top Rated Albums","nomobile":"0","name":"sesalbum.featured-sponsored"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-home-error',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 15,
      'params' => '{"itemType":"album","title":"","nomobile":"0","name":"sesalbum.album-home-error"}',
  ));
$db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-widget',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 16,
      'params' => '{"photo_album":"album","tab_option":"default","view_type":"grid","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"4","show_limited_data":"no","pagging":"pagging","title_truncation":"20","height":"200","width":"334","search_type":["recentlySPcreated"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"Recent Albums","nomobile":"0","name":"sesalbum.tabbed-widget"}',
  ));
  // Insert slide show featured photo
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-widget',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 17,
      'params' => '{"photo_album":"album","tab_option":"filter","view_type":"grid","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","featured","sponsored","likeButton","favouriteButton"],"limit_data":"6","show_limited_data":"no","pagging":"button","title_truncation":"20","height":"210","width":"334","search_type":["mostSPfavourite","mostSPliked","mostSPrated","mostSPdownloaded","featured","sponsored"],"dummy1":null,"recentlySPcreated_order":"9","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"8","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"6","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"5","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"4","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"7","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"3","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"1","featured_label":"Featured","dummy9":null,"sponsored_order":"2","sponsored_label":"Sponsored","title":"Popular Albums","nomobile":"0","name":"sesalbum.tabbed-widget"}'
  ));
  // Insert categories
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored-carosel',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 19,
      'params' => '{"featured_sponsored_carosel":"4","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"duration":"250","height":"275","width":"200","info":"recently_created","title_truncation":"18","limit_data":"1","total_limit_data":"5","title":"Sponsored Albums","nomobile":"0","name":"sesalbum.featured-sponsored-carosel"}'
  ));
  // Insert search
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tag-cloud-albums',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 20,
      'params'=>'{"color":"#000000","text_height":"15","height":"150","itemCountPerPage":"25","title":"Popular Tags","nomobile":"0","name":"sesalbum.tag-cloud-albums"}'
  ));
  // Insert search
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 21,
      'params' => '{"tableName":"album","criteria":"5","info":"most_favourite","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"2","title_truncation":"20","height":"155","width":"180","limit_data":"3","title":"Most Favourite Album","nomobile":"0","name":"sesalbum.featured-sponsored"}'
  ));
  // Insert browse menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 22,
      'params' => '{"tableName":"album","criteria":"5","info":"most_commented","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"2","title_truncation":"20","height":"151","width":"180","limit_data":"3","title":"Most Commented Albums","nomobile":"0","name":"sesalbum.featured-sponsored"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.recently-viewed-item',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 23,
      'params' => '{"category":"album","criteria":"on_site","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"title_truncation":"20","height":"150","width":"180","limit_data":"2","title":"Recently Viewed Albums","nomobile":"0","name":"sesalbum.recently-viewed-item"}',
  ));
  }
  if ($page_name == 'sesalbum_index_welcome') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.welcome',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
      'params' => '{"criteria_slide":"allincludedfeaturedsponsored","slide_to_show":"most_liked","height_slideshow":"550","limit_data_slide":"8","is_fullwidth":"1","slide_title":"Share your Stories with Photos!","slide_descrition":"Let your photos do the talking for you. After all, they\'re worth a million words.","enable_search":"yes","search_criteria":"photos","show_album_under":"yes","show_statistics":"yes","criteria_slide_album":"sponsored","album_criteria":"most_liked","limit_data_album":"3","title_truncation":"45","title":"","nomobile":"0","name":"sesalbum.welcome"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-widget',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"photo_album":"photo","tab_option":"filter","view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"12","show_limited_data":"yes","pagging":"pagging","title_truncation":"45","height":"500","width":"140","search_type":["featured"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"Popular Albums","nomobile":"0","name":"sesalbum.tabbed-widget"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 5,
      'params' => '{"bodysimple":"<div class=\"sesalbum_welcome_html_block\">\r\n<h2>Upload your photos and share them with the World.<\/h2>\r\n<p>Share your photos with your family & friends in an effective way. Let the world know you!<\/p>\r\n<p><a href=\"javascript:;\" onclick=\"browsePhotoURL();returnfalse;\">Browse All Photos<\/a><\/p>\r\n<\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-category',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 8,
      'params' => '{"height":"160px","width":"260px","allign_content":"center","criteria":"alphabetical","show_criteria":["title","icon","countAlbums"],"title":"Browse Albums by Category","nomobile":"0","name":"sesalbum.album-category"}',
  ));
  }
  if ($page_name == 'sesalbum_index_tags') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 7,
  ));
  $main_right_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tag-albums',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
  ));
  }
  if ($page_name == 'sesalbum_index_create') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
     $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.content',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
  ));
  }
  if ($page_name == 'sesalbum_index_photo-home') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 5,
  ));
  $main_right_id = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.slideshows',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 4,
      'params'=>'{"featured_sponsored_carosel":"1","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"height_container":"350","num_rows":"2","info":"recently_created","title_truncation":"45","limit_data":"37","title":"Featured Photos Slideshow","nomobile":"0","name":"sesalbum.slideshows"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-home-error',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 9,
      'params' => '{"itemType":"photo","title":"","nomobile":"0","name":"sesalbum.album-home-error"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored-carosel',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"featured_sponsored_carosel":"3","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"duration":"250","height":"250","width":"306","info":"most_liked","title_truncation":"18","limit_data":"3","total_limit_data":"5","title":"Sponsored Photos","nomobile":"0","name":"sesalbum.featured-sponsored-carosel"}'
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-widget',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 8,
      'params' => '{"photo_album":"photo","tab_option":"advance","view_type":"grid","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"12","show_limited_data":"no","pagging":"pagging","title_truncation":"45","height":"190","width":"228","search_type":["mostSPviewed","mostSPfavourite","mostSPliked","mostSPcommented","mostSPrated","mostSPdownloaded"],"dummy1":null,"recentlySPcreated_order":"7","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"6","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"3","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"4","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"2","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"5","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"1","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"Popular Photos","nomobile":"0","name":"sesalbum.tabbed-widget"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 9,
      'params' => '{"tableName":"photo","criteria":"5","info":"recently_created","insideOutside":"inside","fixHover":"hover","show_criteria":["by","likeButton","favouriteButton"],"view_type":"1","title_truncation":"20","height":"111","width":"111","limit_data":"32","title":"Most Recent Photos","nomobile":"0","name":"sesalbum.featured-sponsored"}'
  ));
  //Insert offthe day albums
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' =>'sesalbum.of-the-day',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 11,
      'params' => '{"ofTheDayType":"photos","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"title_truncation":"20","height":"262","width":"235","title":"Photo Of The Day","nomobile":"0","name":"sesalbum.of-the-day"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-search',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 12,
      'params' => '{"search_for":"photo","view_type":"vertical","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","mostSPrated","mostSPfavourite","featured","sponsored"],"friend_show":"no","search_title":"yes","browse_by":"yes","categories":"yes","location":"yes","kilometer_miles":"no","title":"Search Photos","nomobile":"0","name":"sesalbum.browse-search"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.featured-sponsored',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 13,
      'params' => '{"tableName":"photo","criteria":"5","info":"recently_created","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"view_type":"1","title_truncation":"18","height":"219","width":"200","limit_data":"2","title":"Top Rated Photos","nomobile":"0","name":"sesalbum.featured-sponsored"}'
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.recently-viewed-item',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => 14,
      'params' => '{"category":"photo","criteria":"on_site","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"title_truncation":"18","height":"180","width":"180","limit_data":"2","title":"Recently Viewed Photos","nomobile":"0","name":"sesalbum.recently-viewed-item"}'
  ));
  }
  if ($page_name == 'sesalbum_album_view') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6,
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.breadcrumb-album-view',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 3,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-view-page',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
      'params' => '{"view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","featured","sponsored","likeButton","favouriteButton"],"limit_data":"20","pagging":"auto_load","title_truncation":"20","height":"350","width":"250","dummy1":null,"insideOutsideRelated":"outside","fixHoverRelated":"fix","show_criteriaRelated":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","featured","sponsored","likeButton","favouriteButton"],"limit_dataRelated":"15","paggingRelated":"auto_load","title_truncationRelated":"45","heightRelated":"240","widthRelated":"294","dummy2":null,"search_type":["RecentAlbum","Like","TaggedUser","Fav"],"dummy":null,"RecentAlbum_order":"1","RecentAlbum_label":"[USER_NAME]\'s Recent Albums","RecentAlbum_limitdata":"17","dummy4":null,"Like_order":"2","Like_label":"People Who Like This","Like_limitdata":"17","dummy5":null,"TaggedUser_order":"3","TaggedUser_label":"People Who Are Tagged In This Album","TaggedUser_limitdata":"17","dummy6":null,"Fav_order":"4","Fav_label":"People Who Added This As Favourite","Fav_limitdata":"17","title":"","nomobile":"0","name":"sesalbum.album-view-page"}'
  ));
  }
  if ($page_name == 'sesalbum_photo_view') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2
  ));
  $main_id = $db->lastInsertId();
  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6,
  ));
  $middle_id = $db->lastInsertId();
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.breadcrumb-photo-view',
      'page_id' => $page_id,
      'parent_content_id' => $middle_id,
      'order' => 3,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.photo-view-page',
      'page_id' => $page_id,
      'parent_content_id' => $middle_id,
      'order' => 4,
      'params' => '{"criteria":["like","favourite","tagged","slideshowPhoto"],"maxHeight":"550","view_more_like":"17","view_more_favourite":"10","view_more_tagged":"10","title":"","nomobile":"0","name":"sesalbum.photo-view-page"}'
  ));
  }
  if ($page_name == 'sesalbum_index_manage') {
      //Photo Browse Page
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
     $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6,
  ));
  $main_middle_id = $db->lastInsertId();

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-manage-widget',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"view_type":"masonry","insideOutside":"outside","fixHover":"fix","limit_data":"8","pagging":"auto_load","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"title_truncation":"20","height":"220","height_masonry":"250","width":"238","search_type":["ownalbum","likeAlbum","likePhoto","ratedAlbums","ratedPhotos","favouriteAlbums","favouritePhotos","featuredAlbums","featuredPhotos","sponsoredPhotos","sponsoredAlbums"],"dummy":null,"ownalbum_order":"1","ownalbum_label":"My Albums","dummy1":null,"likeAlbum_order":"2","likeAlbum_label":"Liked Albums","dummy2":null,"likePhoto_order":"3","likePhoto_label":"Liked Photos","dummy3":null,"ratedAlbums_order":"3","ratedAlbums_label":"Rated Albums","dummy4":null,"ratedPhotos_order":"5","ratedPhotos_label":"Rated Photos","dummy5":null,"favouriteAlbums_order":"4","favouriteAlbums_label":"Favourite Albums","dummy6":null,"favouritePhotos_order":"5","favouritePhotos_label":"Favourite Photos","dummy7":null,"featuredAlbums_order":"5","featuredAlbums_label":"Featured Albums","dummy8":null,"featuredPhotos_order":"6","featuredPhotos_label":"Featured Photos","dummy9":null,"sponsoredAlbums_order":"7","sponsoredAlbums_label":"Sponsored Albums","dummy10":null,"sponsoredPhotos_order":"8","sponsoredPhotos_label":"Sponsored Photos","title":"","nomobile":"0","name":"sesalbum.tabbed-manage-widget"}'
  ));
  }
   } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    if( $this->getRequest()->getParam('format') == 'smoothbox' ) {
      return $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('This Page has been reset successfully.')),
        'smoothboxClose' => true,
      ));
    }
  }

  public function setLandingPageAction(){

      $this->view->form = $form = new Sesalbum_Form_Photo_Delete();
    $form->setTitle("Set This Page As Landing Page?");
      $form->setDescription('Are you sure want to set the Welcome Page of this plugin as the Landing page of your website? For old landing page you will have to manually make changes in the Landing page from Layout Editor. Backup page of your current landing page will get created with the name "SNS - Advanced Photos - Backup - Landing Page".');
      $form->execute->setLabel("confirm");
      if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
      $page_id = (int) $this->_getParam('page_id');
      $page_name = $this->_getParam('page_name');
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      try{
        if ($page_name == 'sesalbum_index_welcome') {

    $orlanpage_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'core_index_index')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if ($orlanpage_id) {
      $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = 940000");
      $db->query("DELETE FROM `engine4_core_pages` WHERE `engine4_core_pages`.`page_id` = 940000");
      $db->query('UPDATE `engine4_core_content` SET `page_id` = "940000" WHERE `engine4_core_content`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `page_id` = "940000" WHERE `engine4_core_pages`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index_1" WHERE `engine4_core_pages`.`name` = "core_index_index";');
    }
    //New Landing Page
    $pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sesalbum_index_sesbackuplandingppage')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$pageId) {
      $widgetOrder = 1;
      //Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'sesalbum_index_sesbackuplandingppage',
          'displayname' => 'Landing Page',
          'title' => 'Landing Page',
          'description' => 'This is your site\'s landing page.',
          'custom' => 0,
      ));
      $newpagelastId = $pageId = $db->lastInsertId();
 // Insert top
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $top_middle_id,
      'order' => 3,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.welcome',
      'page_id' => $pageId,
      'parent_content_id' => $main_middle_id,
      'order' => 4,
      'params' => '{"criteria_slide":"allincludedfeaturedsponsored","slide_to_show":"most_liked","height_slideshow":"550","limit_data_slide":"8","is_fullwidth":"1","slide_title":"Share your Stories with Photos!","slide_descrition":"Let your photos do the talking for you. After all, they\'re worth a million words.","enable_search":"yes","search_criteria":"photos","show_album_under":"yes","show_statistics":"yes","criteria_slide_album":"sponsored","album_criteria":"most_liked","limit_data_album":"3","title_truncation":"45","title":"","nomobile":"0","name":"sesalbum.welcome"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.tabbed-widget',
      'page_id' => $pageId,
      'parent_content_id' => $main_middle_id,
      'order' => 7,
      'params' => '{"photo_album":"photo","tab_option":"filter","view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"12","show_limited_data":"yes","pagging":"pagging","title_truncation":"45","height":"500","width":"140","search_type":["featured"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"Popular Albums","nomobile":"0","name":"sesalbum.tabbed-widget"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'page_id' => $pageId,
      'parent_content_id' => $main_middle_id,
      'order' => 5,
      'params' => '{"bodysimple":"<div class=\"sesalbum_welcome_html_block\">\r\n<h2>Upload your photos and share them with the World.<\/h2>\r\n<p>Share your photos with your family & friends in an effective way. Let the world know you!<\/p>\r\n<p><a href=\"javascript:;\" onclick=\"browsePhotoURL();returnfalse;\">Browse All Photos<\/a><\/p>\r\n<\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesalbum.album-category',
      'page_id' => $pageId,
      'parent_content_id' => $main_middle_id,
      'order' => 8,
      'params' => '{"height":"160px","width":"260px","allign_content":"center","criteria":"alphabetical","show_criteria":["title","icon","countAlbums"],"title":"Browse Albums by Category","nomobile":"0","name":"sesalbum.album-category"}',
  ));
      $newbakpage_id = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'sesalbum_index_sesbackuplandingppage')
              ->limit(1)
              ->query()
              ->fetchColumn();
      if ($newbakpage_id) {
        $db->query('UPDATE `engine4_core_content` SET `page_id` = "3" WHERE `engine4_core_content`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `page_id` = "3" WHERE `engine4_core_pages`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index" WHERE `engine4_core_pages`.`name` = "sesalbum_index_sesbackuplandingppage";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "sesalbum_index_sesbackuplandingppage" WHERE `engine4_core_pages`.`name` = "core_index_index_1";');
        $db->query('UPDATE `engine4_core_pages` SET `displayname` = "SNS - Advanced Photos - Backup - Landing Page" WHERE `engine4_core_pages`.`name` = "sesalbum_index_sesbackuplandingppage";');
      }
    }
          }
        } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    if( $this->getRequest()->getParam('format') == 'smoothbox' ) {
      return $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('This page has been set as landing page successfully.')),
        'smoothboxClose' => true,
      ));
    }
  }

}
