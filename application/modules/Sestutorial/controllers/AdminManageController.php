<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_managetutorial');

    $this->view->formFilter = $formFilter = new Sestutorial_Form_Admin_Filter();

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $value);
          $tutorial->delete();
        }
      }
    }

    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array('order' => isset($_GET['order']) ? $_GET['order'] :'', 'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',), $values);

    $this->view->assign($values);
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $tutorialTable = Engine_Api::_()->getDbTable('tutorials', 'sestutorial');
    $tutorialsTableName = $tutorialTable->info('name');
    $select = $tutorialTable->select()
            ->setIntegrityCheck(false)
            ->from($tutorialsTableName)
            ->joinLeft($tableUserName, "$tutorialsTableName.user_id = $tableUserName.user_id", 'username')
            ->order('order ASC');

    if (!empty($_GET['name']))
      $select->where($tutorialsTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['category_id']))
      $select->where($tutorialsTableName . '.category_id =?', $_GET['category_id']);

    if (!empty($_GET['subcat_id']))
      $select->where($tutorialsTableName . '.subcat_id =?', $_GET['subcat_id']);

    if (!empty($_GET['subsubcat_id']))
      $select->where($tutorialsTableName . '.subsubcat_id =?', $_GET['subsubcat_id']);

    if (isset($_GET['status']) && $_GET['status'] != '')
      $select->where($tutorialsTableName . '.status = ?', $_GET['status']);

    if (isset($_GET['rating']) && $_GET['rating'] != '') {
      if ($_GET['rating'] == 1):
        $select->where($tutorialsTableName . '.rating <> ?', 0);
      elseif ($_GET['rating'] == 0 && $_GET['rating'] != ''):
        $select->where($tutorialsTableName . '.rating = ?', $_GET['rating']);
      endif;
    }

    if (!empty($_GET['creation_date']))
      $select->where($tutorialsTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

    if (isset($_GET['subcat_id']))
      $formFilter->subcat_id->setValue($_GET['subcat_id']);

    if (isset($_GET['subsubcat_id']))
      $formFilter->subsubcat_id->setValue($_GET['subsubcat_id']);

		$urlParams = array();
		foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey=>$urlParamsVal){
			if($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
				continue;
			$urlParams['query'][$urlParamsKey] = $urlParamsVal;
		}

		$this->view->urlParams = $urlParams;
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(50);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
  
  public function orderManageTutorialAction() {
    $table = Engine_Api::_()->getDbTable('tutorials', 'sestutorial');
    $results = $table->fetchAll($table->select());
    $orders = $this->getRequest()->getParam('order');
    foreach ($results as $result) {
      $key = array_search ('managesearch_'.$result->getIdentity(), $orders);
      $result->order = $key+1;
      $result->save();
    }
    return;
  }

  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sestutorial_id = $id = $this->_getParam('id');

    $this->view->form = $form = new Sestutorial_Form_Admin_Delete();
    $form->setTitle('Delete Tutorial?');
    $form->setDescription('Are you sure that you want to delete this Tutorial? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    //Check post
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $id);
        $tutorial->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have successfully delete Tutorial.')
      ));
    }
  }

  //Status Action
  public function statusAction() {
    $tutorial_id = $this->_getParam('id');
    if (!empty($tutorial_id)) {
      $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
      $tutorial->status = !$tutorial->status;
      $tutorial->save();
    }
		if(isset($_SERVER['HTTP_REFERER']))
			$url = $_SERVER['HTTP_REFERER'];
		else
			$url = 'admin/sestutorial/manage';
    $this->_redirect($url);
  }

  public function viewAction() {
    $this->view->item = Engine_Api::_()->getItem('sestutorial_tutorial', $this->_getParam('id', null));
  }

  public function createAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_managetutorial');

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $this->view->askquestion_id = $askquestion_id = $this->_getParam('askquestion_id', null);
    if($askquestion_id) {

      $askquestion = Engine_Api::_()->getItem('sestutorial_askquestion', $askquestion_id);
      if (isset($askquestion->category_id) && $askquestion->category_id != 0)
        $this->view->category_id = $askquestion->category_id;
      else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
        $this->view->category_id = $_POST['category_id'];
      else
        $this->view->category_id = 0;
      if (isset($askquestion->subsubcat_id) && $askquestion->subsubcat_id != 0)
        $this->view->subsubcat_id = $askquestion->subsubcat_id;
      else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
        $this->view->subsubcat_id = $_POST['subsubcat_id'];
      else
        $this->view->subsubcat_id = 0;
      if (isset($askquestion->subcat_id) && $askquestion->subcat_id != 0)
        $this->view->subcat_id = $askquestion->subcat_id;
      else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
        $this->view->subcat_id = $_POST['subcat_id'];
      else
        $this->view->subcat_id = 0;
    }

    $this->view->form = $form = new Sestutorial_Form_Admin_Create();
    if($askquestion_id) {
      $form->category_id->setValue($askquestion->category_id);
      $this->view->category_id = $askquestion->category_id;
      $this->view->subcat_id = $askquestion->subcat_id;
      $this->view->subsubcat_id = $askquestion->subsubcat_id;
    }

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $tutorialTable = Engine_Api::_()->getDbtable('tutorials', 'sestutorial');

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        $values = $form->getValues();
        if (isset($values['memberlevels'])) {
          $memberlevels = serialize($values['memberlevels']);
        } else {
          $memberlevels = serialize(array());
        }

        if (isset($values['networks'])) {
          $networks = serialize($values['networks']);
        } else {
          $networks = serialize(array());
        }

        if (isset($values['profile_types'])) {
          $profile_types = serialize($values['profile_types']);
        } else {
          $profile_types = serialize(array());
        }


        $tutorial = $tutorialTable->createRow();
        $values['user_id'] = $viewer_id;
        $values['memberlevels'] = $memberlevels;
        $values['profile_types'] = $profile_types;
        $values['networks'] = $networks;

        $tutorial->setFromArray($values);
        $tutorial->save();

        //Upload categories icon
        if (isset($_FILES['photo_id']) && $values['photo_id']) {
          $Icon = $this->setPhoto($form->photo_id, array('tutorial_id' => $tutorial->tutorial_id));
          if (!empty($Icon))
            $tutorial->photo_id = $Icon;
          $tutorial->save();
        }

        // Auth
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

        if( empty($values['auth_view']) ) {
          $values['auth_view'] = 'everyone';
        }

        if( empty($values['auth_comment']) ) {
          $values['auth_comment'] = 'everyone';
        }

        $viewMax = array_search($values['auth_view'], $roles);
        $commentMax = array_search($values['auth_comment'], $roles);

        foreach( $roles as $i => $role ) {
          $auth->setAllowed($tutorial, $role, 'view', ($i <= $viewMax));
          $auth->setAllowed($tutorial, $role, 'comment', ($i <= $commentMax));
        }

        //Add tags work
        $tags = preg_split('/[,]+/', $values['tags']);
        $tutorial->tags()->addTagMaps($viewer, $tags);

        if($askquestion_id) {
          $askquestion = Engine_Api::_()->getItem('sestutorial_askquestion',$askquestion_id);
          $askquestion->tutorial_id = $tutorial->getIdentity();
          $askquestion->save();
        }

        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $tutorial, 'sestutorial_new');
        // make sure action exists before attaching the tutorial to the activity
        if( $action ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $tutorial);
        }

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
  }

  public function editAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_managetutorial');

    $viewer = Engine_Api::_()->user()->getViewer();
    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorials', $this->_getParam('tutorial_id'));

    //Event Category and profile fileds
    $this->view->defaultProfileId = $defaultProfileId = 1; //Engine_Api::_()->getDbTable('metas', 'sestutorial')->profileFieldId();
    if (isset($tutorial->category_id) && $tutorial->category_id != 0)
      $this->view->category_id = $tutorial->category_id;
    else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
      $this->view->category_id = $_POST['category_id'];
    else
      $this->view->category_id = 0;
    if (isset($tutorial->subsubcat_id) && $tutorial->subsubcat_id != 0)
      $this->view->subsubcat_id = $tutorial->subsubcat_id;
    else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
      $this->view->subsubcat_id = $_POST['subsubcat_id'];
    else
      $this->view->subsubcat_id = 0;
    if (isset($tutorial->subcat_id) && $tutorial->subcat_id != 0)
      $this->view->subcat_id = $tutorial->subcat_id;
    else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
      $this->view->subcat_id = $_POST['subcat_id'];
    else
      $this->view->subcat_id = 0;

    $form = $this->view->form = new Sestutorial_Form_Admin_Edittutorial();
    $this->view->category_id = $tutorial->category_id;
    $this->view->subcat_id = $tutorial->subcat_id;
    $this->view->subsubcat_id = $tutorial->subsubcat_id;

    //Tags Work
    $tagStr = '';
    foreach( $tutorial->tags()->getTagMaps() as $tagMap ) {
      $tag = $tagMap->getTag();
      if( !isset($tag->text) ) continue;
      if( '' !== $tagStr ) $tagStr .= ', ';
      $tagStr .= $tag->text;
    }
    $form->populate(array('tags' => $tagStr));
    $this->view->tagNamePrepared = $tagStr;

    $form->setDescription("Below you can edit this Tutorial.");
    $form->button->setLabel('Save Changes');
    $form->populate($tutorial->toArray());
    $form->memberlevels->setValue(unserialize($tutorial->memberlevels));
    if(!empty($tutorial->networks) && isset($tutorial->networks) && engine_count(unserialize($tutorial->networks)) >  0) {
      $form->networks->setValue(unserialize($tutorial->networks));
    }
    $form->profile_types->setValue(unserialize($tutorial->profile_types));

    //Check post
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      if (empty($values['photo_id']))
        unset($values['photo_id']);
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        if (isset($values['memberlevels'])) {
          $memberlevels = serialize($values['memberlevels']);
        } else {
          $memberlevels = serialize(array());
        }

        if (isset($values['networks'])) {
          $networks = serialize($values['networks']);
        } else {
          $networks = serialize(array());
        }

        if (isset($values['profile_types'])) {
          $profile_types = serialize($values['profile_types']);
        } else {
          $profile_types = serialize(array());
        }

        $values['memberlevels'] = $memberlevels;
        $values['networks'] = $networks;
        $values['profile_types'] = $profile_types;
        $tutorial->setFromArray($values);
        $tutorial->save();

        //Upload categories icon
        if (isset($_FILES['photo_id']) && !empty($_FILES['photo_id']['name'])) {
          $previousCatIcon = $tutorial->photo_id;
          $Icon = $this->setPhoto($form->photo_id, array('tutorial_id' => $tutorial->tutorial_id));
          if (!empty($Icon)) {
            if ($previousCatIcon) {
              $catIcon = Engine_Api::_()->getItem('storage_file', $previousCatIcon);
              $catIcon->delete();
            }
            $tutorial->photo_id = $Icon;
            $tutorial->save();
          }
        }

        if (isset($values['remove_profilecover']) && !empty($values['remove_profilecover'])) {
          $storage = Engine_Api::_()->getItem('storage_file', $tutorial->photo_id);
          $tutorial->photo_id = 0;
          $tutorial->save();
          if ($storage)
            $storage->delete();
        }

        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        foreach( $roles as $role ) {
          if ($form->auth_view){
            if( $auth->isAllowed($tutorial, $role, 'view') ) {
            $form->auth_view->setValue($role);
            }
          }

          if ($form->auth_comment){
            if( $auth->isAllowed($tutorial, $role, 'comment') ) {
              $form->auth_comment->setValue($role);
            }
          }
        }

        // handle tags
        $tags = preg_split('/[,]+/', $values['tags']);
        $tutorial->tags()->setTagMaps($viewer, $tags);

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }

    //Output
    $this->renderScript('admin-manage/edit.tpl');
  }

  public function setPhoto($photo, $param = null) {

    if ($photo instanceof Zend_Form_Element_File)
      $file = $photo->getFileName();
    else if (is_array($photo) && !empty($photo['tmp_name']))
      $file = $photo['tmp_name'];
    else if (is_string($photo) && file_exists($photo))
      $file = $photo;
    else
      throw new Sestutorial_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sestutorial_tutorial',
        'parent_id' => $param['tutorial_id']
    );

    //Save
    $storage = Engine_Api::_()->storage();
    if ($param == 'mainPhoto') {
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(500, 500)
              ->write($path . '/m_' . $name)
              ->destroy();
    } else {
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(1600, 1600)
              ->write($path . '/m_' . $name)
              ->destroy();
    }

    //Resize image (icon)
    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
            ->write($path . '/is_' . $name)
            ->destroy();

    //Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iMain, 'thumb.profile');
    $iMain->bridge($iSquare, 'thumb.icon');

    //Remove temp files
    @unlink($path . '/m_' . $name);
    @unlink($path . '/is_' . $name);

    $photo_id = $iMain->getIdentity();
    return $photo_id;
  }

  public function uploadImageAction() {


    $ses_public_path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'sesadminWysiwygPhotos';

    if (!is_dir($ses_public_path) && mkdir($ses_public_path, 0777, true))
      @chmod($ses_public_path, 0777);

    // Prepare
    if (empty($_FILES['userfile'])) {
      $this->view->error = 'File failed to upload. Check your server settings (such as php.ini max_upload_filesize).';
      return;
    }

    $info = $_FILES['userfile'];
    $targetFile = realpath($ses_public_path) . '/' . $info['name'];

    if( !move_uploaded_file($info['tmp_name'], $targetFile) ) {
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Unable to move file to upload directory.");
      return;
    }

    $this->view->status = 1;

    $this->view->photo_url = ( _ENGINE_SSL ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . Zend_Registry::get('Zend_View')->baseUrl().'/public/sesadminWysiwygPhotos/' . $info['name'];
  }
}
