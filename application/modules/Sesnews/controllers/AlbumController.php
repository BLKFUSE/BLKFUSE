<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AlbumController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_AlbumController extends Core_Controller_Action_Standard {

	public function init() {
		if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;
    $id = $this->_getParam('album_id', $this->_getParam('id', null));
    if ($id) {
      $album = Engine_Api::_()->getItem('sesnews_album', $id);
      if ($album) {
				if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
					if(!$album->getParent()->getPackage()->getItemModule('photo')){
						return $this->_forward('notfound', 'error', 'core');
					}
				}
      }
    }
  }

  public function createAction() {

    if (isset($_GET['ul']) || isset($_FILES['Filedata']))
    return $this->_forward('upload-photo', null, null, array('format' => 'json'));
     $news_id = $this->_getParam('news_id',false);
    $album_id = $this->_getParam('album_id',false);
    if($album_id){
    	$album = Engine_Api::_()->getItem('sesnews_album', $album_id);
			$this->view->news_id = $news_id = $album->news_id;
		}else{
				$this->view->news_id = $news_id = $news_id;
		}
		$news = $this->view->news = Engine_Api::_()->getItem('sesnews_news', $news_id);

		$isNewsAdmin = Engine_Api::_()->sesnews()->checkNewsAdmin($news);
    if(!$isNewsAdmin)
    return $this->_forward('notfound', 'error', 'core');

		if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1) && empty($_POST)){
			$package = $news->getPackage();
			$this->view->photoLeft = $photoLeft = $news->getPackage()->allowUploadPhoto($news->orderspackage_id,true);
			if(!$this->view->photoLeft)
				return $this->_forward('notfound', 'error', 'core');
		}

    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['user_id'] = $viewer->getIdentity();
    $this->view->current_count =Engine_Api::_()->getDbtable('albums', 'sesnews')->getUserAlbumCount($values);
    $this->view->quota = $quota = 0;
    // Get form
    $this->view->form = $form = new Sesnews_Form_Album();
    // Render
		if (!$this->getRequest()->isPost()) {
			if (null !== ($album_id = $this->_getParam('album_id'))) {
				$form->populate(array(
				'album' => $album_id
				));
			}
			return;
		}

    if (!$form->isValid($this->getRequest()->getPost()))
    return;

    $db = Engine_Api::_()->getItemTable('sesnews_album')->getAdapter();
    $db->beginTransaction();
    try {
      $album = $form->saveValues();
      // Add tags
      $values = $form->getValues();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    header('location:'.$album->getHref());
  }

  public function uploadPhotoAction() {
  	if(isset($_GET['news_id']) && $_GET['news_id'] != ''){
			$news_id = $_GET['news_id'];
		}else
			$news_id = $this->_getParam('news_id');
    $news = Engine_Api::_()->getItem('sesnews_news', $news_id);

//     if (!$this->_helper->requireAuth()->setAuthParams($event, null, 'photo')->isValid()) {
//       return;
//     }

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
    }
    else{
      $uploadSource = $_POST['Filedata'];
      $isURL = true;
    }

    $db = Engine_Api::_()->getDbtable('photos', 'sesnews')->getAdapter();
    $db->beginTransaction();
    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      $photoTable = Engine_Api::_()->getDbtable('photos', 'sesnews');
      $photo = $photoTable->createRow();
      $photo->setFromArray(array(
	'news_id' => $news->news_id,
	'user_id' => $viewer->getIdentity()
      ));

      $photo->save();
      //$photo->order = $photo->photo_id;
      $setPhoto = $photo->setAlbumPhoto($uploadSource,$isURL);
      if(!$setPhoto){
	$db->rollBack();
	$this->view->status = false;
	$this->view->error = 'An error occurred.';
	return;
      }
      $photo->save();

      $this->view->status = true;
      $this->view->photo_id = $photo->photo_id;
      $this->view->url = $photo->getAlbumPhotoUrl('thumb.normalmain');
      $db->commit();
    }catch (Sesnews_Model_Exception $e) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
    if(isset($_GET['ul']))
    echo json_encode(array('status'=>$this->view->status,'name'=>'','photo_id'=> $this->view->photo_id,'url' => $photo->getPhotoUrl()));die;
  }


  //album view function.
  public function viewAction() {
		$album_id = $this->_getParam('album_id');
		$news_id = $this->_getParam('news_id');
		$album = null;
    if ($album_id) {
      $album = Engine_Api::_()->getItem('sesnews_album', $album_id);
      if ($album) {
     	  Engine_Api::_()->core()->setSubject($album);
      }else
				return $this->_forward('requireauth', 'error', 'core');
		}
    // Render
    $this->_helper->content
            ->setEnabled();
  }

  //function for autosuggest album
  public function getAlbumAction() {
    $sesdata = array();
    $value['text'] = $this->_getParam('text');
    $albums = Engine_Api::_()->getDbTable('albums', 'sesnews')->getAlbumsAction($value);
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

  //album edit action
  public function editAction() {

    if (!$this->_helper->requireUser()->isValid())
    return;

		$album_id = $this->_getParam('album_id',false);
    if($album_id)
    $this->view->album = $album = Engine_Api::_()->getItem('sesnews_album', $album_id);
	  else
		return;

		$this->view->news = $news = Engine_Api::_()->getItem('sesnews_news', $album->news_id);
		if ($news)
	  Engine_Api::_()->core()->setSubject($news);
		else
		return;

    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid())
    return;

    // Make form
    $this->view->form = $form = new Sesnews_Form_Album_Edit();
		$form->populate($album->toArray());
		 if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    //is post
    if (!$form->isValid($this->getRequest()->getPost())) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid data');
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    // Process
    $db = $album->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      $album->setFromArray($values);
      $album->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $db->beginTransaction();
    $url = $album->getHref();
    header('location:' . $url);
  }

  // album delete action
  public function deleteAction() {

    $this->_helper->layout->setLayout('default-simple');
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$this->_helper->requireUser()->isValid())
    return;

		$album_id = $this->_getParam('album_id',false);
    if($album_id)
    $this->view->album = $album = Engine_Api::_()->getItem('sesnews_album', $album_id);
	  else
	  return;

		$news = Engine_Api::_()->getItem('sesnews_news', $album->news_id);
		if ($news)
		Engine_Api::_()->core()->setSubject($news);
		else
		return;

    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid())
    return;

    // In smoothbox
    $this->view->form = $form = new Sesnews_Form_Album_Delete();
    if (!$album) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Album doesn't exists or not authorized to delete");
      return;
    }
    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    $db = $album->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $album->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
		$this->view->status = true;
		$this->view->message = Zend_Registry::get('Zend_Translate')->_('The selected albums have been successfully deleted.');
		$tab_id = Engine_Api::_()->sesbasic()->getWidgetTabId(array('name' => 'sesnews.profile-photos'));
		return $this->_forward('success' ,'utility', 'core', array('parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'view','news_id'=>$news->custom_url, 'tab' => $tab_id), 'sesnews_entry_view', true), 'messages' => Array($this->view->message)
		));
  }

  //function for edit photo action
  public function editphotosAction() {
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $pageNumber = isset($_POST['page']) ? $_POST['page'] : 1;
    if (!$is_ajax) {
      if (!$this->_helper->requireUser()->isValid())
      return;
    }
    // Prepare data
    $album_id = $this->_getParam('album_id', null);
    $this->view->album = $album = Engine_Api::_()->getItem('sesnews_album', $album_id);
    $this->view->content_item = Engine_Api::_()->getItem('sesnews_news', $album->news_id);

    $photoTable = Engine_Api::_()->getItemTable('sesnews_photo');
    $this->view->paginator = $paginator = $photoTable->getPhotoPaginator(array(
        'album' => $album,
        'order' => 'order ASC'
    ));
    $this->view->album_id = $album->album_id;
    $paginator->setCurrentPageNumber($pageNumber);
    $itemCount = (engine_count($_POST) > 0 && !$is_ajax) ? engine_count($_POST) : 10;
    $paginator->setItemCountPerPage($itemCount);
    $this->view->page = $pageNumber;
    // Get albums
    $myAlbums = Engine_Api::_()->getDbtable('albums', 'sesnews')->editPhotos();
    $albumOptions = array('' => '');
    foreach ($myAlbums as $myAlbum) {
      $albumOptions[$myAlbum['album_id']] = $myAlbum['title'];
    }
    if (engine_count($albumOptions) == 1) {
      $albumOptions = array();
    }
    // Make form
    $this->view->form = $form = new Sesnews_Form_Album_Photos();
    foreach ($paginator as $photo) {
      $subform = new Sesnews_Form_Album_EditPhoto(array('elementsBelongTo' => $photo->getGuid()));
      $subform->populate($photo->toArray());
      $form->addSubForm($subform, $photo->getGuid());
      $form->cover->addMultiOption($photo->getIdentity(), $photo->getIdentity());
      if (empty($albumOptions)) {
        $subform->removeElement('move');
      } else {
        $subform->move->setMultiOptions($albumOptions);
      }
    }
    if ($is_ajax) {
      return;
    }
    if (!$this->getRequest()->isPost()) {
      return;
    }
    $table = $album->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $values = $_POST;
      if (!empty($values['cover'])) {
        $album->photo_id = $values['cover'];
        $album->save();
      }
      // Process
      foreach ($paginator as $photo) {
        if (isset($_POST[$photo->getGuid()])) {
          $values = $_POST[$photo->getGuid()];
        } else {
          continue;
        }
        unset($values['photo_id']);
        if (isset($values['delete']) && $values['delete'] == '1') {
          $photo->delete();
        } else if (!empty($values['move'])) {
          $nextPhoto = $photo->getNextPhoto();
          $old_album_id = $photo->album_id;
          $photo->album_id = $values['move'];
          $photo->save();
          // Change album cover if necessary
          if (($nextPhoto instanceof Sesnews_Model_Photo) &&
                  (int) $album->photo_id == (int) $photo->getIdentity()) {
            $album->photo_id = $nextPhoto->getIdentity();
            $album->save();
          }
          // Remove activity attachments for this photo
          Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($photo);
        } else {
          $photo->setFromArray($values);
          $photo->save();
        }
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    //send to specific album view page.
    header('location:'.$album->getHref());
  }


  public function removeAction() {

    if(empty($_GET['photo_id']))
    die('error');
    //GET PHOTO ID AND ITEM
    $photo_id = (int) $this->_getParam('photo_id');
    $photo = Engine_Api::_()->getItem('sesnews_photo', $photo_id);
    $db = Engine_Api::_()->getDbTable('photos', 'sesnews')->getAdapter();
    $db->beginTransaction();
    try {
      $photo->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editPhotoAction() {

    $this->view->photo_id = $photo_id = $this->_getParam('photo_id');
    $this->view->photo = Engine_Api::_()->getItem('sesnews_photo', $photo_id);
  }

  public function saveInformationAction() {

    $photo_id = $this->_getParam('photo_id');
    $title = $this->_getParam('title', null);
    $description = $this->_getParam('description', null);
    Engine_Api::_()->getDbTable('photos', 'sesnews')->update(array('title' => $title, 'description' => $description), array('photo_id = ?' => $photo_id));
    echo json_encode(array('status'=>"true"));die;
  }
		//update cover photo function
	public function uploadCoverAction(){
		$album_id = $this->_getParam('album_id', '0');
		if ($album_id == 0)
			return;
		$album = Engine_Api::_()->getItem('sesnews_album', $album_id);
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
		$album = Engine_Api::_()->getItem('sesnews_album', $album_id);
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
}
