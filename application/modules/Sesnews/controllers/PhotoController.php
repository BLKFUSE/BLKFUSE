<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: PhotoController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_PhotoController extends Core_Controller_Action_Standard {

  public function init() {
		
		if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;
		
    if (!Engine_Api::_()->core()->hasSubject()) {
      if (0 !== ($photo_id = (int) $this->_getParam('photo_id')) &&
              null !== ($photo = Engine_Api::_()->getItem('sesnews_photo', $photo_id))) {
        Engine_Api::_()->core()->setSubject($photo);
      } else if (0 !== ($news_id = (int) $this->_getParam('news_id')) &&
              null !== ($news = Engine_Api::_()->getItem('sesnews_news', $event_id))) {
        Engine_Api::_()->core()->setSubject($news);
      }
    }

		if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
			if(Engine_Api::_()->core()->hasSubject()){
				$subject = Engine_Api::_()->core()->getSubject();
				if($subject  == 'sesnews_news'){
					if(!$subject->getPackage()->getItemModule('photo')){
						return $this->_forward('notfound', 'error', 'core');
					};
				}else{
					if(!$subject->getParent()->getParent()->getPackage()->getItemModule('photo')){
						return $this->_forward('notfound', 'error', 'core');
					};
				}
			}
		}
  }
//rotate photo action from lightbox and photo view page
  public function rotateAction() {
    if (!$this->_helper->requireSubject('sesnews_photo')->isValid())
      return;
		$news_id = $this->_getParam('news_id');
		$news = Engine_Api::_()->getItem('sesnews_news', $news_id);
    if (!$this->_helper->requireAuth()->setAuthParams($news, null, 'edit')->isValid())
      return;
//     if (!$this->getRequest()->isPost()) {
//       $this->view->status = false;
//       $this->view->error = $this->view->translate('Invalid method');
//       return;
//     }
    $viewer = Engine_Api::_()->user()->getViewer();
    $photo = Engine_Api::_()->core()->getSubject('sesnews_photo');
    $angle = (int) $this->_getParam('angle', 90);
    if (!$angle || !($angle % 360)) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Invalid angle, must not be empty');
      return;
    }
    if (!engine_in_array((int) $angle, array(90, 270))) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Invalid angle, must be 90 or 270');
      return;
    }
    // Get file
    $file = Engine_Api::_()->getItem('storage_file', $photo->file_id);
    if (!($file instanceof Storage_Model_File)) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Could not retrieve file');
      return;
    }
    // Pull photo to a temporary file
    $tmpFile = $file->temporary();
    // Operate on the file
    $image = Engine_Image::factory();
    $image->open($tmpFile)
            ->rotate($angle)
            ->write()
            ->destroy()
    ;
    // Set the photo
    $db = $photo->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $photo->setPhoto($tmpFile);
      @unlink($tmpFile);
      $db->commit();
    } catch (Exception $e) {
      @unlink($tmpFile);
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->href = $photo->getPhotoUrl();
  }

  	//flip photo action function
  public function flipAction() {
   if (!$this->_helper->requireSubject('sesnews_photo')->isValid())
      return;
		$news_id = $this->_getParam('news_id');
		$news = Engine_Api::_()->getItem('sesnews_news', $news_id);
    if (!$this->_helper->requireAuth()->setAuthParams($news, null, 'edit')->isValid())
      return;
//     if (!$this->getRequest()->isPost()) {
//       $this->view->status = false;
//       $this->view->error = $this->view->translate('Invalid method');
//       return;
//     }
    $viewer = Engine_Api::_()->user()->getViewer();
    $photo = Engine_Api::_()->core()->getSubject('sesnews_photo');
    $direction = $this->_getParam('direction');
    if (!engine_in_array($direction, array('vertical', 'horizontal'))) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Invalid direction');
      return;
    }
    // Get file
    $file = Engine_Api::_()->getItem('storage_file', $photo->file_id);
    if (!($file instanceof Storage_Model_File)) {
      $this->view->status = false;
      $this->view->error = $this->view->translate('Could not retrieve file');
      return;
    }
    // Pull photo to a temporary file
    $tmpFile = $file->temporary();
    // Operate on the file
    $image = Engine_Image::factory();
    $image->open($tmpFile)
            ->flip($direction != 'vertical')
            ->write()
            ->destroy()
    ;
    // Set the photo
    $db = $photo->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $photo->setPhoto($tmpFile,false,'flip');
      @unlink($tmpFile);
      $db->commit();
    } catch (Exception $e) {
      @unlink($tmpFile);
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->href = $photo->getPhotoUrl();
  }
  	public function correspondingImageAction(){
		$album_id = $this->_getParam('album_id', false);
		$this->view->paginator = $paginator = Engine_Api::_()->getDbtable('photos', 'sesnews')->getPhotoSelect(array('album_id'=>$album_id,'limit_data'=>100));
	}
  public function uploadAction() {

    if (isset($_GET['ul']) || isset($_FILES['Filedata']))
    return $this->_forward('upload-photo', null, null, array('format' => 'json', 'news_id'=> 0));
  }
  public function viewAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->photo = $photo = Engine_Api::_()->core()->getSubject();
		$news_id = $this->_getParam('news_id');
		$news = Engine_Api::_()->getItem('sesnews_news', $news_id);
    if (!$this->_helper->requireAuth()->setAuthParams($news, null, 'view')->isValid()) {
      return;
    }

    if (!$viewer || !$viewer->getIdentity() || $photo->user_id != $viewer->getIdentity()) {
      $photo->view_count = new Zend_Db_Expr('view_count + 1');
      $photo->save();
    }
		// Render
    $this->_helper->content
            ->setEnabled();
  }
  public function uploadPhotoAction() {
 		 if (!$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'create')->isValid())
      return;
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

    $sesnewsPhotoTable = Engine_Api::_()->getDbtable('photos', 'sesnews');

    $db = $sesnewsPhotoTable->getAdapter();
    $db->beginTransaction();
		$session = new Zend_Session_Namespace();
    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      if(empty($session->album_id)) {
			$album = Engine_Api::_()->getItemTable('sesnews_album')->createRow();
			$album->setFromArray(array(
				'title' => '',
				'news_id' => 0
			));
			$album->save();
			$session->album_id = $album->getIdentity();
			$album_id = $album->getIdentity();
     }else{
      	$album_id = $session->album_id;
				$album = Engine_Api::_()->getItem('sesnews_album', $album_id);
		 }
      $params = array(
          'collection_id' => $album_id,
          'album_id' => $album_id,
          'news_id' => 0,
          'user_id' => $viewer->getIdentity(),
          'owner_id' => $viewer->getIdentity()
      );
      $photo = Engine_Api::_()->sesbasic()->setPhoto($uploadSource, $isURL,false,'sesnews','sesnews_news',$params,$album);
			$photo->album_id = $album->getIdentity();
			$photo->save();
      $this->view->status = true;
      $this->view->photo_id = $photo->photo_id;
			$this->view->url = $photo->getPhotoUrl('thumb.normal');
      $db->commit();
    } catch (Exception $e) {
			$session = new Zend_Session_Namespace();
      unset($session->album_id);
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      return;
    }
    if(isset($_GET['ul']))
    echo json_encode(array('status'=>$this->view->status,'name'=>'','photo_id'=> $this->view->photo_id,'url' => $photo->getPhotoUrl()));die;
  }

  public function editAction()
  {
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'photo.edit')->isValid() ) return;

    $photo = Engine_Api::_()->core()->getSubject();

    $this->view->form = $form = new Sesnews_Form_Photo_Edit();

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
      'messages' => array('Changes saved'),
      'layout' => 'default-simple',
      'parentRefresh' => true,
      'closeSmoothbox' => true,
    ));
  }



  public function deleteAction() {
    $photo = Engine_Api::_()->core()->getSubject();
    $news = $photo->getParent('sesnews_news');
		$album_id = $photo->album_id;
    if (!$this->_helper->requireAuth()->setAuthParams($news, null, 'edit')->isValid()) {
      return;
    }

    $this->view->form = $form = new Sesnews_Form_Photo_Delete();

    if (!$this->getRequest()->isPost()) {
      $form->populate($photo->toArray());
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Process
    $db = Engine_Api::_()->getDbtable('photos', 'sesnews')->getAdapter();
    $db->beginTransaction();

    try {
      $photo->delete();

      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
		$album = Engine_Api::_()->getItem('sesnews_album', $album_id);
    return $this->_forward('success', 'utility', 'core', array(
                'messages' => array(Zend_Registry::get('Zend_Translate')->_('Photo deleted')),
                'layout' => 'default-simple',
                'parentRedirect' => $album->getHref(),
                'closeSmoothbox' => true,
    ));
  }

}
