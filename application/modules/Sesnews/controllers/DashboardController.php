<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: DashboardController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_DashboardController extends Core_Controller_Action_Standard {
  public function init() {

    if (!$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid())
      return;
		if (!$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'edit')->isValid())
      return;
    if (!$this->_helper->requireUser->isValid())
      return;
    $id = $this->_getParam('news_id', null);
    $news_id = Engine_Api::_()->getDbtable('news', 'sesnews')->getNewsId($id);
    if ($news_id) {
      $news = Engine_Api::_()->getItem('sesnews_news', $news_id);
      if ($news)
        Engine_Api::_()->core()->setSubject($news);
    } else
      return $this->_forward('requireauth', 'error', 'core');
    $isNewsAdmin = Engine_Api::_()->sesnews()->isNewsAdmin($news, 'edit');
    $sesnews_edit = Zend_Registry::isRegistered('sesnews_edit') ? Zend_Registry::get('sesnews_edit') : null;
    if (empty($sesnews_edit))
      return $this->_forward('notfound', 'error', 'core');
		if (!$isNewsAdmin)
    return $this->_forward('requireauth', 'error', 'core');
  }
	public function fieldsAction(){
		if( !$this->_helper->requireUser()->isValid() ) return;
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->news = $sesnews = Engine_Api::_()->core()->getSubject();
		$package_id = $sesnews->package_id;
		$package = Engine_Api::_()->getItem('sesnewspackage_package',$package_id);
		$module = json_decode($package->params,true);
		if(empty($module['custom_fields']) || ($package->custom_fields_params == '[]'))
			 return $this->_forward('notfound', 'error', 'core');

		$this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesnews')->profileFieldId();
		$this->view->form = $form = new Sesnews_Form_Custom_Dashboardfields(array('item' => $sesnews,'topLevelValue'=>0,'topLevelId'=>0));
		 // Check post/form
    if( !$this->getRequest()->isPost() ) return;
    if( !$form->isValid($this->getRequest()->getPost()) ) return;
		$form->saveValues();

	}
  public function editAction() {
    if( !$this->_helper->requireUser()->isValid() ) return;
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->news = $sesnews = Engine_Api::_()->core()->getSubject();
    if (isset($sesnews->category_id) && $sesnews->category_id != 0)
    $this->view->category_id = $sesnews->category_id;
    else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
    $this->view->category_id = $_POST['category_id'];
    else
    $this->view->category_id = 0;
    if (isset($sesnews->subsubcat_id) && $sesnews->subsubcat_id != 0)
    $this->view->subsubcat_id = $sesnews->subsubcat_id;
    else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
    $this->view->subsubcat_id = $_POST['subsubcat_id'];
    else
    $this->view->subsubcat_id = 0;
    if (isset($sesnews->subcat_id) && $sesnews->subcat_id != 0)
    $this->view->subcat_id = $sesnews->subcat_id;
    else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
    $this->view->subcat_id = $_POST['subcat_id'];
    else
    $this->view->subcat_id = 0;
    $sesnews_edit = Zend_Registry::isRegistered('sesnews_edit') ? Zend_Registry::get('sesnews_edit') : null;
    if (empty($sesnews_edit))
      return $this->_forward('notfound', 'error', 'core');
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesnews')->profileFieldId();
    if( !Engine_Api::_()->core()->hasSubject('sesnews_news') )
    Engine_Api::_()->core()->setSubject($sesnews);

    if( !$this->_helper->requireSubject()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', $viewer, 'edit')->isValid() ) return;

    // Get navigation
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sesnews_main');

    $this->view->categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc();

    // Prepare form
    $this->view->form = $form = new Sesnews_Form_Edit(array('defaultProfileId' => $defaultProfileId));

    // Populate form
    $form->populate($sesnews->toArray());
    $form->populate(array(
        'networks' => explode(",",$sesnews->networks),
        'levels' => explode(",",$sesnews->levels),
        'location' => $sesnews->location,
    ));
    $form->getElement('newstyle')->setValue($sesnews->style);
    $latLng = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData('sesnews_news',$sesnews->news_id);
    if($latLng){
      if($form->getElement('lat'))
      $form->getElement('lat')->setValue($latLng->lat);
      if($form->getElement('lng'))
      $form->getElement('lng')->setValue($latLng->lng);
    }
    if($form->getElement('location'))
    $form->getElement('location')->setValue($sesnews->location);
		if($form->getElement('category_id'))
    $form->getElement('category_id')->setValue($sesnews->category_id);

    $tagStr = '';
    foreach( $sesnews->tags()->getTagMaps() as $tagMap ) {
      $tag = $tagMap->getTag();
      if( !isset($tag->text) ) continue;
      if( '' !== $tagStr ) $tagStr .= ', ';
      $tagStr .= $tag->text;
    }
    $form->populate(array(
      'tags' => $tagStr,
    ));
    $this->view->tagNamePrepared = $tagStr;

    $auth = Engine_Api::_()->authorization()->context;
    $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

    foreach( $roles as $role ) {
      if ($form->auth_view){
        if( $auth->isAllowed($sesnews, $role, 'view') ) {
         $form->auth_view->setValue($role);
        }
      }

      if ($form->auth_comment){
        if( $auth->isAllowed($sesnews, $role, 'comment') ) {
          $form->auth_comment->setValue($role);
        }
      }

      if ($form->auth_video){
        if( $auth->isAllowed($sesnews, $role, 'video') ) {
          $form->auth_video->setValue($role);
        }
      }

      if ($form->auth_music){
        if( $auth->isAllowed($sesnews, $role, 'music') ) {
          $form->auth_music->setValue($role);
        }
      }
    }

    // hide status change if it has been already published
    if( $sesnews->draft == "0" )
    $form->removeElement('draft');


    // Check post/form
    if( !$this->getRequest()->isPost() ) return;
    if( !$form->isValid($this->getRequest()->getPost()) ) return;

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();

    try
    {
      $values = $form->getValues();
      if($_POST['newstyle'])
      $values['style'] = $_POST['newstyle'];
      $sesnews->setFromArray($values);
      $sesnews->modified_date = date('Y-m-d H:i:s');
			if(isset($_POST['start_date']) && $_POST['start_date'] != ''){
				$starttime = isset($_POST['start_date']) ? date('Y-m-d H:i:s',strtotime($_POST['start_date'].' '.$_POST['start_time'])) : '';
      	$sesnews->publish_date =$starttime;
			}
			//else{
			//	$sesnews->publish_date = '';
			//}

        if(isset($values['levels']))
            $sesnews->levels = implode(',',$values['levels']);

        if(isset($values['networks']))
            $sesnews->networks = implode(',',$values['networks']);

      $sesnews->save();
      unset($_POST['title']);
      unset($_POST['tags']);
      unset($_POST['category_id']);
      unset($_POST['subcat_id']);
      unset($_POST['MAX_FILE_SIZE']);
      unset($_POST['body']);
      unset($_POST['search']);
      unset($_POST['execute']);
      unset($_POST['token']);
      unset($_POST['submit']);
      $values['fields'] = $_POST;
      $values['fields']['0_0_1'] = '2';

      //Location
      if (isset($_POST['lat']) && isset($_POST['lng']) && $_POST['lat'] != '' && $_POST['lng'] != '' && !empty($_POST['location'])) {
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id,venue, lat, lng ,city,state,zip,country,address,address2, resource_type) VALUES ("' . $sesnews->getIdentity() . '","' . $_POST['location'] . '", "' . $_POST['lat'] . '","' . $_POST['lng'] . '","' . $_POST['city'] . '","' . $_POST['state'] . '","' . $_POST['zip'] . '","' . $_POST['country'] . '","' . $_POST['address'] . '","' . $_POST['address2'] . '",  "sesnews_news")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['lat'] . '" , lng = "' . $_POST['lng'] . '",city = "' . $_POST['city'] . '", state = "' . $_POST['state'] . '", country = "' . $_POST['country'] . '", zip = "' . $_POST['zip'] . '", address = "' . $_POST['address'] . '", address2 = "' . $_POST['address2'] . '", venue = "' . $_POST['venue'] . '"');
        $sesnews->location = $_POST['location'];
        $sesnews->save();
      } else {
        $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
        $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id,venue, lat, lng ,city,state,zip,country,address,address2, resource_type) VALUES ("' . $sesnews->getIdentity() . '","' . $_POST['location'] . '", "' . $_POST['lat'] . '","' . $_POST['lng'] . '","' . $_POST['city'] . '","' . $_POST['state'] . '","' . $_POST['zip'] . '","' . $_POST['country'] . '","' . $_POST['address'] . '","' . $_POST['address2'] . '",  "sesnews_news")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['lat'] . '" , lng = "' . $_POST['lng'] . '",city = "' . $_POST['city'] . '", state = "' . $_POST['state'] . '", country = "' . $_POST['country'] . '", zip = "' . $_POST['zip'] . '", address = "' . $_POST['address'] . '", address2 = "' . $_POST['address2'] . '", venue = "' . $_POST['venue'] . '"');
        $sesnews->location = $_POST['location'];
        $sesnews->save();
      }

      if(isset($values['draft']) && !$values['draft']) {
        $currentDate = date('Y-m-d H:i:s');
        if($sesnews->publish_date < $currentDate) {
          $sesnews->publish_date = $currentDate;
          $sesnews->save();
        }
      }

      // Add fields
      $customfieldform = $form->getSubForm('fields');
      if (!is_null($customfieldform)) {
        $customfieldform->setItem($sesnews);
        $customfieldform->saveValues($values['fields']);
      }
      //Custom Fiels Work
      $view = $this->view;
      $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
      $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($sesnews);
      $profile_field_value = $view->FieldValueLoop($sesnews, $fieldStructure);

      // Auth
      if( empty($values['auth_view']) ) {
        $values['auth_view'] = 'everyone';
      }

      if( empty($values['auth_comment']) ) {
        $values['auth_comment'] = 'everyone';
      }

      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);
      $videoMax = array_search($values['auth_video'], $roles);
      $musicMax = array_search($values['auth_music'], $roles);
      foreach( $roles as $i => $role ) {
        $auth->setAllowed($sesnews, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($sesnews, $role, 'comment', ($i <= $commentMax));
        $auth->setAllowed($sesnews, $role, 'video', ($i <= $videoMax));
        $auth->setAllowed($sesnews, $role, 'music', ($i <= $musicMax));
      }

      // handle tags
      $tags = preg_split('/[,]+/', $values['tags']);
      $sesnews->tags()->setTagMaps($viewer, $tags);

			//upload main image
			if(isset($_FILES['photo_file']) && $_FILES['photo_file']['name'] != ''){
				$photo_id = 	$sesnews->setPhoto($form->photo_file,'direct');
			}

      // insert new activity if sesnews is just getting published
      $action = Engine_Api::_()->getDbtable('actions', 'activity')->getActionsByObject($sesnews);
      if( engine_count($action->toArray()) <= 0 && $values['draft'] == '0' && (!$sesnews->publish_date || strtotime($sesnews->publish_date) <= time())) {
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sesnews, 'sesnews_new');
          // make sure action exists before attaching the sesnews to the activity
        if( $action != null ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sesnews);
        }
        $sesnews->is_publish = 1;
      	$sesnews->save();
      }

      // Rebuild privacy
      $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
      foreach( $actionTable->getActionsByObject($sesnews) as $action ) {
        $actionTable->resetActivityBindings($action);
      }

      $db->commit();

    }
    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

     $this->_redirectCustom(array('route' => 'sesnews_dashboard', 'action' => 'edit', 'news_id' => $sesnews->custom_url));
  }

	public function upgradeAction(){
		if( !$this->_helper->requireUser()->isValid() ) return;
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->news = $sesnews = Engine_Api::_()->core()->getSubject();
    //current package
		if(!empty($sesnews->orderspackage_id)){
			$this->view->currentPackage = 	Engine_Api::_()->getItem('sesnewspackage_orderspackage',$sesnews->orderspackage_id);
			if(!$this->view->currentPackage ){
				$this->view->currentPackage = 	Engine_Api::_()->getItem('sesnewspackage_package',$sesnews->package_id);
			}
		}
		else
			$this->view->currentPackage = 	Engine_Api::_()->getItem('sesnewspackage_package',$sesnews->package_id);
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
		//get upgrade packages
		$this->view->upgradepackage =  Engine_Api::_()->getDbTable('packages','sesnewspackage')->getPackage(array('show_upgrade'=>1,'member_level'=>$viewer->level_id,'not_in_id'=>$sesnews->package_id));

	}

	 public function removeMainphotoAction() {
      //GET News ID AND ITEM
	    $news = Engine_Api::_()->core()->getSubject();
			$db = Engine_Api::_()->getDbTable('news', 'sesnews')->getAdapter();
      $db->beginTransaction();
      try {
        $news->photo_id = '';
				$news->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
			return $this->_helper->redirector->gotoRoute(array('action' => 'mainphoto', 'news_id' => $news->custom_url), "sesnews_dashboard", true);
  }

	 //get style detail
  public function styleAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->news = $news = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $news->isOwner($viewer) || $this->_helper->requireAuth()->setAuthParams(null, null, 'style')->isValid()))
      return;
		// Get current row
    $table = Engine_Api::_()->getDbtable('styles', 'core');
    $select = $table->select()
            ->where('type = ?', 'sesnews_news')
            ->where('id = ?', $news->getIdentity())
            ->limit(1);
    $row = $table->fetchRow($select);
    // Create form
    $this->view->form = $form = new Sesnews_Form_Dashboard_Style();
    // Check post
    if (!$this->getRequest()->isPost()) {
      $form->populate(array(
          'style' => ( null === $row ? '' : $row->style )
      ));
    }
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
		// Cool! Process
    $style = $form->getValue('style');
    // Save
    if (null == $row) {
      $row = $table->createRow();
      $row->type = 'sesnews_news';
      $row->id = $news->getIdentity();
    }
    $row->style = $style;
    $row->save();
  }

    //get seo detail
  public function seoAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->news = $news = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $news->isOwner($viewer)))
      return;
    // Create form
    $this->view->form = $form = new Sesnews_Form_Dashboard_Seo();

    $form->populate($news->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('news', 'sesnews')->getAdapter();
    $db->beginTransaction();
    try {
      $news->setFromArray($_POST);
      $news->save();
      $db->commit();
      $form->addNotice('Your changes have been saved.');
    } catch (Exception $e) {
      $db->rollBack();
    }
  }

  public function editPhotoAction() {

    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;

    $this->view->news = $news = Engine_Api::_()->core()->getSubject();

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    // Get form
    $this->view->form = $form = new Sesnews_Form_Edit_Photo();

    if( empty($news->photo_id) ) {
      $form->removeElement('remove');
    }

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Uploading a new photo
    if( $form->Filedata->getValue() !== null ) {
      $db = $news->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $fileElement = $form->Filedata;
        $photo_id = Engine_Api::_()->sesbasic()->setPhoto($fileElement, false,false,'sesnews','sesnews_news','',$news,true);
        $news->photo_id = $photo_id;
        $news->save();
        $db->commit();
      }
      // If an exception occurred within the image adapter, it's probably an invalid image
      catch( Engine_Image_Adapter_Exception $e )
      {
        $db->rollBack();
        $form->addError(Zend_Registry::get('Zend_Translate')->_('The uploaded file is not supported or is corrupt.'));
      }

      // Otherwise it's probably a problem with the database or the storage system (just throw it)
      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
    }
    // Resizing a photo
    elseif ($form->getValue('coordinates') !== '') {
      $storage = Engine_Api::_()->storage();

      $iProfile = $storage->get($news->photo_id);
      if (!$iProfile) {
          return;   // don't do anything
      }
      $iSquare = $storage->get($news->photo_id, 'thumb.icon');

      // Read into tmp file
      $pName = $iProfile->getStorageService()->temporary($iProfile);
      $iName = dirname($pName) . '/nis_' . basename($pName);

      list($x, $y, $w, $h) = explode(':', $form->getValue('coordinates'));

      $image = Engine_Image::factory();
      $image->open($pName)
          ->resample($x+.1, $y+.1, $w-.1, $h-.1, 48, 48)
          ->write($iName)
          ->destroy();

      $iSquare->store($iName);

      $image = Engine_Image::factory();
      $image->open($pName)
          ->resample($x+.1, $y+.1, $w-.1, $h-.1, 440, 440)
          ->write($pName)
          ->destroy();
      $iProfile->store($pName);
      // Remove temp files
      @unlink($iName);
      @unlink($pName);
    }
  }

  public function removePhotoAction() {

    //Get form
    $this->view->form = $form = new Sesnews_Form_Edit_RemovePhoto();

    if( !$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
      return;

    $news = Engine_Api::_()->core()->getSubject();
    $news->photo_id = 0;
    $news->save();

    $this->view->status = true;

    $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => true,
      'parentRefresh' => true,
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your photo has been removed.'))
    ));
  }

  public function contactInformationAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $sesnews_edit = Zend_Registry::isRegistered('sesnews_edit') ? Zend_Registry::get('sesnews_edit') : null;
    if (empty($sesnews_edit))
      return $this->_forward('notfound', 'error', 'core');
    $this->view->news = $news = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $news->isOwner($viewer)))
      return;
    // Create form
    $this->view->form = $form = new Sesnews_Form_Dashboard_Contactinformation();

    $form->populate($news->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;

    $db = Engine_Api::_()->getDbtable('news', 'sesnews')->getAdapter();
    $db->beginTransaction();
    try {
      $news->news_contact_name = isset($_POST['news_contact_name']) ? $_POST['news_contact_name'] : '';
      $news->news_contact_email = isset($_POST['news_contact_email']) ? $_POST['news_contact_email'] : '';
      $news->news_contact_phone = isset($_POST['news_contact_phone']) ? $_POST['news_contact_phone'] : '';
      $news->news_contact_website = isset($_POST['news_contact_website']) ? $_POST['news_contact_website'] : '';
      $news->news_contact_facebook = isset($_POST['news_contact_facebook']) ? $_POST['news_contact_facebook'] : '';
      $news->save();
      $db->commit();
      $form->addNotice('Your changes have been saved.');
    } catch (Exception $e) {
      $db->rollBack();
      echo false; die;
    }
  }

  public function newsRoleAction() {

    $this->view->news = $sesnews = Engine_Api::_()->core()->getSubject();
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('roles', 'sesnews')->getNewsAdmins(array('news_id' => $sesnews->news_id));
    $paginator->setItemCountPerPage(10);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  public function getMembersAction() {
    $sesdata = array();
    $roleIDArray = array();
    $ownerId = Engine_Api::_()->getItem('sesnews_news', $this->_getParam('news_id', null))->owner_id;
    $viewer = Engine_Api::_()->getItem('user', $ownerId);
    $users = $viewer->membership()->getMembershipsOfIds();
    $users = array_merge($users, array('0' => $ownerId));
    $newsRoleTable = Engine_Api::_()->getDbTable('roles', 'sesnews');
    $roleIds = $newsRoleTable->select()->from($newsRoleTable->info('name'), 'user_id')->where('news_id =?',$this->_getParam('news_id', null))->query()->fetchAll();
    foreach($roleIds as $roleID) {
      $roleIDArray[] = $roleID['user_id'];
    }
    $diffIds = array_diff($users, $roleIDArray);
    $users_table = Engine_Api::_()->getDbtable('users', 'user');
    $usersTableName = $users_table->info('name');
    $select = $users_table->select()->where('displayname  LIKE ? ', '%' . $this->_getParam('text') . '%');
		if ($diffIds)
		$select->where($usersTableName . '.user_id IN (?)', $diffIds);
// 		else
// 		$select->where($usersTableName . '.user_id IN (?)', 0);
		$select->order('displayname ASC')->limit('40');
    $users = $users_table->fetchAll($select);
    foreach ($users as $user) {
      $user_icon_photo = $this->view->itemPhoto($user, 'thumb.icon');
      $sesdata[] = array(
          'id' => $user->user_id,
          'label' => $user->getTitle(),
          'photo' => $user_icon_photo
      );
    }
    return $this->_helper->json($sesdata);
  }

  public function saveNewsAdminAction() {
    $data = explode(',',$_POST['data']);
    $sesnews_edit = Zend_Registry::isRegistered('sesnews_edit') ? Zend_Registry::get('sesnews_edit') : null;
    if (empty($sesnews_edit))
      return $this->_forward('notfound', 'error', 'core');
    $news_id = $this->_getParam('news_id', null);
    $this->view->owner_id = Engine_Api::_()->getItem('sesnews_news',$news_id)->owner_id;
    foreach($data as $newsAdminId) {
      $checkUser = Engine_Api::_()->getDbTable('roles', 'sesnews')->isNewsAdmin($news_id, $newsAdminId);
      if($checkUser)
      continue;
			$roleTable = Engine_Api::_()->getDbtable('roles', 'sesnews');
			$row = $roleTable->createRow();
			$row->news_id = $news_id;
			$row->user_id = $newsAdminId;
			$row->save();
    }
    $this->view->paginator = Engine_Api::_()->getDbTable('roles', 'sesnews')->getNewsAdmins(array('news_id' => $news_id));
  }

  public function deleteNewsAdminAction() {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$db->delete('engine4_sesnews_roles', array(
			'news_id = ?' => $_POST['news_id'],
			'role_id =?' => $_POST['role_id'],
		));
  }

  public function editLocationAction() {

    $this->view->news = $sesnews = Engine_Api::_()->core()->getSubject();
    $userLocation = $sesnews->location;
    if (!$userLocation)
      return $this->_forward('notfound', 'error', 'core');

    $this->view->locationLatLng = $locationLatLng = Engine_Api::_()->getDbtable('locations', 'sesbasic')->getLocationData($sesnews->getType(), $sesnews->getIdentity());
    if (!$locationLatLng) {
     return $this->_forward('notfound', 'error', 'core');
    }

    $this->view->form = $form = new Sesnews_Form_Locationedit();
    $form->populate(array(
        'ses_edit_location' => $userLocation,
        'ses_lat' => $locationLatLng['lat'],
        'ses_lng' => $locationLatLng['lng'],
        'ses_zip' => $locationLatLng['zip'],
        'ses_city' => $locationLatLng['city'],
        'ses_state' => $locationLatLng['state'],
        'ses_country' => $locationLatLng['country'],
    ));
    if ($this->getRequest()->getPost()) {
      Engine_Api::_()->getItemTable('sesnews_news')->update(array(
          'location' => $_POST['ses_edit_location'],
              ), array(
          'news_id = ?' => $sesnews->getIdentity(),
      ));
      if (isset($_POST['ses_lat']) && isset($_POST['ses_lng']) && $_POST['ses_lat'] != '' && $_POST['ses_lng'] != '' && !empty($_POST['ses_edit_location'])) {
        Engine_Db_Table::getDefaultAdapter()->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng ,city,state,zip,country, resource_type) VALUES ("' . $sesnews->news_id . '", "' . $_POST['ses_lat'] . '","' . $_POST['ses_lng'] . '","' . $_POST['ses_city'] . '","' . $_POST['ses_state'] . '","' . $_POST['ses_zip'] . '","' . $_POST['ses_country'] . '",  "sesnews_news")	ON DUPLICATE KEY UPDATE	lat = "' . $_POST['ses_lat'] . '" , lng = "' . $_POST['ses_lng'] . '",city = "' . $_POST['ses_city'] . '", state = "' . $_POST['ses_state'] . '", country = "' . $_POST['ses_country'] . '", zip = "' . $_POST['ses_zip'] . '"');
      }
      $this->_redirectCustom(array('route' => 'sesnews_dashboard', 'action' => 'edit-location', 'news_id' => $sesnews->custom_url));
    }
    //Render
  }

}
