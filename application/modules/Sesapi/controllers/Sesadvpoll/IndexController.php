<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesadvpoll_IndexController extends Sesapi_Controller_Action_Standard
{

  public function init()
  {
    // Get subject
    $poll = null;
    if (null !== ($pollIdentity = $this->_getParam('poll_id'))) {
      $poll = Engine_Api::_()->getItem('sesadvpoll_poll', $pollIdentity);
      if (null !== $poll) {
        Engine_Api::_()->core()->setSubject($poll);
      }
    }
    // Get viewer
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    // only show polls if authorized
    $resource = ($poll ? $poll : 'sesadvpoll_poll');
    $viewer = ($viewer && $viewer->getIdentity() ? $viewer : null);
    if (!$this->_helper->requireAuth()->setAuthParams($resource, $viewer, 'view')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
    }
  }

  public function menuAction()
  {

    $menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesadvpoll_main', array());
    $menu_counter = 0;
    foreach ($menus as $menu) {
      $class = end(explode(' ', $menu->class));
      if ($class == "sesadvpoll_main_pollhome")
        continue;
      $result_menu[$menu_counter]['label'] = $this->view->translate($menu->label);
      $result_menu[$menu_counter]['action'] = $class;
      $result_menu[$menu_counter]['isActive'] = $menu->active;
      $menu_counter++;
    }
    $result['menus'] = $result_menu;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result)));
  }

  public function browseAction()
  {
    // Prepare
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->canCreate = Engine_Api::_()->authorization()->isAllowed('sesadvpoll_poll', null, 'create');

    // Get form
    $this->view->form = $form = new Sesadvpoll_Form_Search();

    // Process form
    $values = array();
    if ($form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
    }

    //     if (empty($this->_getParam('user_id')))
//       $values['browse'] = 1;

    $this->view->formValues = array_filter($values);

    if (@$values['show'] == 2 && $viewer->getIdentity()) {
      // Get an array of friend ids
      $values['users'] = $viewer->membership()->getMembershipsOfIds();
    }
    unset($values['show']);

    // check to see if request is for specific user's listings
    if (($user_id = $this->_getParam('user_id'))) {
      $values['user_id'] = $user_id;
      $values['actionName'] = 'manage';
    } else {
			$values['browse'] = 1;
    }	

    if (empty($values['order']))
      $values['order'] = 'recentlycreated';
    $values['search'] = 1;
    $values = $_POST;

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('polls', 'sesadvpoll')->getPollsPaginator($values);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $paginator->setItemCountPerPage(10);

    $result = $this->pollsResult($paginator);

    foreach ($result['polls'] as $key => $value) {

      $poll = Engine_Api::_()->getItem('sesadvpoll_poll', $value['poll_id']);

      $user = Engine_Api::_()->getItem('user', $value['user_id']);
      if ($user) {
        $ownerimage = Engine_Api::_()->sesapi()->getPhotoUrls($user, "", "");
        if ($ownerimage) {
          $result['polls'][$key]['owner_image'] = $ownerimage;
        } else {
          $userMainTempProfile = array(
            "main" => $value['owner_photo'],
            "icon" => $value['owner_photo'],
            "normal" => $value['owner_photo'],
            "profile" => $value['owner_photo'],
          );
          $result['polls'][$key]['owner_image'] = $userMainTempProfile;
        }
      }


      if (!empty($this->_getParam('user_id'))) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $menuoptions = array();
        $counter = 0;
        if (!$poll->getVote($viewer)) {
          $canEdit = Engine_Api::_()->authorization()->getPermission($viewer, 'sesadvpoll_poll', 'edit');
          if ($canEdit) {
            $menuoptions[$counter]['name'] = "edit";
            $menuoptions[$counter]['label'] = $this->view->translate("Edit Privacy");
            $counter++;
          }

          $canDelete = Engine_Api::_()->authorization()->getPermission($viewer, 'sesadvpoll_poll', 'delete');
          if ($canDelete) {
            $menuoptions[$counter]['name'] = "delete";
            $menuoptions[$counter]['label'] = $this->view->translate("Delete Poll");
            $counter++;
          }
        }

        $menuoptions[$counter]['name'] = "close";
        $menuoptions[$counter]['label'] = $this->view->translate("Open Poll");
        $menuoptions[$counter]['cl'] = $value['closed'];
        if ($value['closed'] == "0") {
          $menuoptions[$counter]['label'] = $this->view->translate("Close Poll");
        }
        if ($value['closed'] == "1") {
          $menuoptions[$counter]['label'] = $this->view->translate("Open Poll");
        }

        $result['polls'][$key]['menus'] = $menuoptions;
      }
    }

    $canCreate = false;
    if (!empty($this->_getParam('user_id'))) {
      $canCreate = Engine_Api::_()->authorization()->getPermission($viewer, 'sesadvpoll_poll', 'create');
    }
    $result['can_create'] = $canCreate;

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if ($result <= 0)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist polls.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
  }

  function pollsResult($paginator)
  {
    $result = array();
    $counterLoop = 0;
    $viewer = Engine_Api::_()->user()->getViewer();

    foreach ($paginator as $item) {

      $resource = $item->toArray();
      $resource['owner_title'] = Engine_Api::_()->getItem('user', $resource['owner_id'])->getTitle();
      $resource['resource_type'] = $item->getType();
      $resource['resource_id'] = $item->getIdentity();

      // Check content like or not and get like count
      if ($viewer->getIdentity() != 0) {
        $likeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($item->getIdentity(), $item->getType());
        $resource['is_content_like'] = $likeStatus > 0 ? true : false;

        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.allow.favourite', 1)) {
          $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sesadvpoll')->isFavourite(array('resource_id' => $item->getIdentity(), 'resource_type' => $item->getType()));
          $resource['is_content_favourite'] = $favouriteStatus > 0 ? true : false;
        }
      }

      $owner = $item->getOwner();
      if ($owner && $owner->photo_id) {
        $photo = $this->getBaseUrl(false, $owner->getPhotoUrl('thumb.profile'));
        $resource['owner_photo'] = $photo;
      } else {
        $resource['owner_photo'] = $this->getBaseUrl(true, '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
      }
      $resource['owner_title'] = $this->view->translate("Posted by ") . $item->getOwner()->getTitle();
				
			$shareType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.allow.share', 1);
			$resource['shareType'] = $shareType;
      //Share
      if ($shareType) {
        $resource["can_share"] = 1;
        $photo = $this->getBaseUrl(false, $item->getPhotoUrl());
        if ($photo)
          $resource["share"]["imageUrl"] = $photo;
        $resource["share"]["url"] = $this->getBaseUrl(false, $item->getHref());
        $resource["share"]["title"] = $item->getTitle();
        $resource["share"]["description"] = strip_tags($item->getDescription());
        $resource["share"]['urlParams'] = array(
          "type" => $item->getType(),
          "id" => $item->getIdentity()
        );
        if (is_null($resource["share"]["title"]))
          unset($resource["share"]["title"]);
      }

      $result['polls'][$counterLoop] = $resource;
      $result['polls'][$counterLoop]['images'] = $images;
      $counterLoop++;
    }
    return $result;
  }

  public function createAction()
  {

    if (!$this->_helper->requireUser()->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
    }

    if (!$this->_helper->requireAuth()->setAuthParams('sesadvpoll_poll', null, 'create')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
    }

    $this->view->options = array();
    $this->view->maxOptions = $max_options = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.maxoptions', 15);

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->form = $form = new Sesadvpoll_Form_Create();

    // Check if post and populate
    if ($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
      // $formFields['maxOptions'] = $max_options;
      $this->generateFormFields($formFields, array('resources_type' => 'sesadvpoll_poll', 'max_options' => $max_options));
    }

    if (!$this->getRequest()->isPost()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'post data error', 'result' => array()));
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      $validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
      if (is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    $optionImages = @$_FILES['optionsImage'];
    $optionGifs = @$_FILES['optionsGif'];
    $options = (array) $this->_getParam('optionsArray');
    $options = array_filter(array_map('trim', $options));
    $options = array_slice($options, 0, $max_options);
    $this->view->options = $options;

    if (empty($options) || !is_array($options) || engine_count($options) < 2) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'You must provide at least two possible answers.', 'result' => array()));
    }

    foreach ($options as $index => $option) {
      if (strlen($option) > 300) {
        $options[$index] = Engine_String::substr($option, 0, 300);
      }
    }

    // Process
    $pollTable = Engine_Api::_()->getItemTable('sesadvpoll_poll');
    $pollOptionsTable = Engine_Api::_()->getDbtable('options', 'sesadvpoll');
    $db = $pollTable->getAdapter();
    $db->beginTransaction();

    try {
      $values = $form->getValues();

      $values['user_id'] = $viewer->getIdentity();
      if (empty($values['auth_view'])) {
        $values['auth_view'] = 'everyone';
      }

      if (empty($values['auth_comment'])) {
        $values['auth_comment'] = 'everyone';
      }

      $values['view_privacy'] = $values['auth_view'];

      $poll = $pollTable->createRow();
      $poll->setFromArray($values);
      $poll->save();

      $censor = new Engine_Filter_Censor();
      $html = new Engine_Filter_Html(array('AllowedTags' => array('a')));
      $counter = 0;
      $storage = Engine_Api::_()->getItemTable('storage_file');

      foreach ($options as $option) {
        $option = $censor->filter($html->filter($option));
        $file_id = 0;
        $image_type = 0;
        if (!empty($_FILES['optionsImage']['name'][$counter])) {
          $file['tmp_name'] = $_FILES['optionsImage']['tmp_name'][$counter];
          $file['name'] = $_FILES['optionsImage']['name'][$counter];
          $file['size'] = $_FILES['optionsImage']['size'][$counter];
          $file['error'] = $_FILES['optionsImage']['error'][$counter];
          $file['type'] = $_FILES['optionsImage']['type'][$counter];
          $image_type = 1;
        } elseif (!empty($_POST['optionsGif'][$counter])) {
          $file_id = $_POST['optionsGif'][$counter];
          $image_type = 2;
        }

        if (@$file && $image_type == 1) {
          $thumbname = $storage->createFile(
            $file,
            array(
              'parent_id' => $poll->getIdentity(),
              'parent_type' => 'sesadvpoll_poll',
              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            )
          );
          $file_id = $thumbname->file_id;
        }
        $pollOptionsTable->insert(
          array(
            'poll_id' => $poll->getIdentity(),
            'poll_option' => $option,
            'file_id' => $file_id,
            'image_type' => $image_type
          )
        );
        $image_type = 0;
        $counter++;
      }

      // Privacy
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($poll, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($poll, $role, 'comment', ($i <= $commentMax));
      }
      $auth->setAllowed($poll, 'registered', 'vote', true);
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }

    // Process activity
    $db = Engine_Api::_()->getDbTable('polls', 'sesadvpoll')->getAdapter();
    $db->beginTransaction();
    try {
      $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity(Engine_Api::_()->user()->getViewer(), $poll, 'sesadvpoll_createpoll');
      if ($action != null) {
        Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $poll);
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }

    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('poll_id' => $poll->getIdentity(), 'message' => $this->view->translate('Poll created successfully.'))));
  }

  public function editAction()
  {
    if (!$this->_helper->requireUser()->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    if (!$this->_helper->requireAuth()->setAuthParams('sesadvpoll_poll', null, 'edit')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }
    if (!Engine_Api::_()->core()->hasSubject())
      $subject = Engine_Api::_()->getItem('sesadvpoll_poll', $this->_getParam('id', null));
    else
      $subject = Engine_Api::_()->core()->getSubject();
    if (!$subject)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    $viewer = Engine_Api::_()->user()->getViewer();
    $pollData = array();
    $poll = $subject;
    $pollData['poll'] = $poll->toArray();
    $poll_options = $poll->optionsFetchAll();
    $pollOptionCount = 0;
    foreach ($poll_options as $polloptn) {
      $pollData['poll_options'][$pollOptionCount] = $polloptn;
      if ($polloptn['file_id'] > 0 && $polloptn['image_type'] > 0) {
        $imageUrl = Engine_Api::_()->storage()->get($polloptn['file_id'], '')->map();
        $pollData['poll_options'][$pollOptionCount]['option_image'] = $this->getBaseUrl(true, $imageUrl);
      }
      $pollOptionCount++;
    }
    $pollData['max_options'] = $max_options = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.maxoptions', 15);
    $form = new Sesadvpoll_Form_Edit();
    $form->getElement('title')->setValue($poll->title);
    $form->getElement('description')->setValue($poll->description);
    $auth = Engine_Api::_()->authorization()->context;
    $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
    // Populate form with current settings
    $form->search->setValue($poll->search);
    foreach ($roles as $role) {
      if (1 === $auth->isAllowed($poll, $role, 'view')) {
        $form->auth_view->setValue($role);
      }

      if (1 === $auth->isAllowed($poll, $role, 'comment')) {
        $form->auth_comment->setValue($role);
      }
    }
    if ($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields, $pollData);
    }
    if (!$form->isValid($_POST)) {
      $validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
      if (is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    if (!$this->getRequest()->isPost()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
    }
    //is post
    if (!$form->isValid($this->getRequest()->getPost())) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('validation_error'), 'result' => array()));
    }
    $options = (array) $this->_getParam('optionsArray');
    $optionsCount = engine_count($options);
    $ids = (array) $this->_getParam('optionIds');
    $options = array_filter(array_map('trim', $options));
    $options = array_slice($options, 0, $max_options);
    if (empty($options) || !is_array($options) || engine_count($options) < 2) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('You must provide at least two possible answers.'), 'result' => array()));
    }
    $message['status'] = false;
    $message['message'] = $this->view->translate('Something went wrong.');
    $message['poll_id'] = 0;
    foreach ($options as $index => $option) {
      if (strlen($option) > 300) {
        $options[$index] = Engine_String::substr($option, 0, 300);
      }
    }
    $pollTable = Engine_Api::_()->getItemTable('sesadvpoll_poll');
    $pollOptionsTable = Engine_Api::_()->getDbtable('options', 'sesadvpoll');
    $getoptionIds = $pollOptionsTable->select()
      ->from($pollOptionsTable, '*')
      ->where('poll_id = ?', $poll->getIdentity())
      ->query()
      ->fetchAll()
    ;
    $getoptionIdsCounter = 0;

    foreach ($getoptionIds as $index => $value) {
      $getoptionIdsArray[$getoptionIdsCounter] = $value['poll_option_id'];
      $getoptionTextArray[$getoptionIdsCounter] = $value['poll_option'];
      $getoptionIdsCounter++;
    }
    $IdsDiffrence = array_diff($getoptionIdsArray, $ids);

    if (!empty($IdsDiffrence)) {
      foreach ($IdsDiffrence as $index => $value) {
        $diffItem = $optionItem = Engine_Api::_()->getItem('sesadvpoll_option', $value);
        if (!empty($diffItem)) {
          $option_file_id = $diffItem->file_id;
          if ($option_file_id && $diffItem->image_type != 2) {
            $fileobj = Engine_Api::_()->getItem('storage_file', $option_file_id);
            $fileobj->remove();
          }
          $diffItem->delete();
        }
      }
    }
    
    if ($this->getParam('is_image_delete', 0) == 1) {
      foreach ($ids as $k => $value) {
        $Item = Engine_Api::_()->getItem('sesadvpoll_option', $value);
        if ($Item) {
          $fileobj = Engine_Api::_()->getItem('storage_file', $Item->file_id);
          if ($fileobj) {
            if ($Item->image_type == 1)
              $fileobj->remove();
            $pollOptionsTable->update(
              array('poll_option' => $options[$k], 'file_id' => 0, 'image_type' => 0),
              array('`poll_option_id` = ?' => $value)
            );
            $fileobj = null;
          }
        }
      }
    }
    $dbOptn = $pollTable->getAdapter();
    $dbOptn->beginTransaction();
    $storage = Engine_Api::_()->getItemTable('storage_file');
    $censor = new Engine_Filter_Censor();
    $html = new Engine_Filter_Html(array('AllowedTags' => array('a')));
    $counter = 0;
    try {
      foreach ($options as $optionKey => $optionValue) {
        $optionItem = Engine_Api::_()->getItem('sesadvpoll_option', $ids[$optionKey]);
        $pollOptn = $censor->filter($html->filter($optionValue));
        if (!empty($optionItem)) {
          $optionItemArray = $optionItem->toArray();
          $fileobj = Engine_Api::_()->getItem('storage_file', $optionItemArray['file_id']);
          $image_type = 0;
          if (!empty($_FILES['optionsImage']['name'][$optionKey])) {
            if ($optionItemArray['file_id'] && $optionItemArray['image_type'] != 2) {
              if ($fileobj) {
                $fileobj->remove();
              }
            }
            $file['tmp_name'] = $_FILES['optionsImage']['tmp_name'][$optionKey];
            $file['name'] = $_FILES['optionsImage']['name'][$optionKey];
            $file['size'] = $_FILES['optionsImage']['size'][$optionKey];
            $file['error'] = $_FILES['optionsImage']['error'][$optionKey];
            $file['type'] = $_FILES['optionsImage']['type'][$optionKey];
            $image_type = 1;
          } elseif (!empty($_POST['optionsGif'][$optionKey])) {
            $file = $_POST['optionsGif'][$optionKey];
            $image_type = 2;
          }
          if ($file && $image_type == 1) {
            $thumbname = $storage->createFile($file, array(
              'parent_id' => $poll->getIdentity(),
              'parent_type' => 'sesadvpoll_poll',
              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            )
            );
            $file_id = $thumbname->file_id;
            $pollOptionsTable->update(
              array('poll_option' => $pollOptn, 'file_id' => $file_id, 'image_type' => $image_type),
              array('`poll_option_id` = ?' => $ids[$optionKey])
            );
            $file = null;
          } else if ($file && $image_type == 2) {
            $file_id = engine_count($file) > 0 ? $file : 0;
            $pollOptionsTable->update(
              array('poll_option' => $pollOptn, 'file_id' => $file_id, 'image_type' => $image_type),
              array('`poll_option_id` = ?' => $ids[$optionKey])
            );
            $file = null;
          } else {
            $pollOptionsTable->update(
              array('poll_option' => $optionValue),
              array('`poll_option_id` = ?' => $ids[$optionKey])
            );
            $file = null;
          }
        } else {
          $file_id = 0;
          $image_type = 0;
          if (!empty($_FILES['optionsImage']['name'][$optionKey])) {
            $file['tmp_name'] = $_FILES['optionsImage']['tmp_name'][$optionKey];
            $file['name'] = $_FILES['optionsImage']['name'][$optionKey];
            $file['size'] = $_FILES['optionsImage']['size'][$optionKey];
            $file['error'] = $_FILES['optionsImage']['error'][$optionKey];
            $file['type'] = $_FILES['optionsImage']['type'][$optionKey];
            $image_type = 1;
          } elseif (!empty($_POST['optionsGif'][$optionKey])) {
            $file = $_POST['optionsGif'][$optionKey];
            $image_type = 2;
          }
          if ($file && $image_type == 1) {
            $thumbname = $storage->createFile($file, array(
              'parent_id' => $poll->getIdentity(),
              'parent_type' => 'sesadvpoll_poll',
              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            )
            );
            $file_id = $thumbname->file_id;
          }
          if ($image_type == 2) {
            $file_id = engine_count($file) > 0 ? $file : 0;
          }
          $pollOptionsTable->insert(
            array(
              'poll_id' => $poll->getIdentity(),
              'poll_option' => $pollOptn,
              'file_id' => $file_id,
              'image_type' => $image_type
            )
          );
          $file = null;
        }
      }
      $message['status'] = true;
      $message['message'] = $this->view->translate('Poll successfully edited.');
      $message['poll_id'] = $poll->getIdentity();
    } catch (Exception $e) {
      $dbOptn->rollback();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      // CREATE AUTH STUFF HERE
      if (empty($values['auth_view'])) {
        $values['auth_view'] = 'everyone';
      }
      if (empty($values['auth_comment'])) {
        $values['auth_comment'] = 'everyone';
      }
      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($poll, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($poll, $role, 'comment', ($i <= $commentMax));
      }
      $poll->title = $values['title'];
      $poll->description = $values['description'];
      $poll->search = (bool) $values['search'];
      $poll->view_privacy = $values['auth_view'];
      $poll->save();
      $db->commit();
      $message['status'] = true;
      $message['message'] = $this->view->translate('Poll successfully edited.');
      $message['poll_id'] = $poll->getIdentity();
    } catch (Exception $e) {
      $dbOptn->rollBack();
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      // Rebuild privacy
      $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
      foreach ($actionTable->getActionsByObject($poll) as $action) {
        $actionTable->resetActivityBindings($action);
      }
      $db->commit();
      $message['status'] = true;
      $message['message'] = $this->view->translate('Poll successfully edited.');
      $message['poll_id'] = $poll->getIdentity();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $message));
  }

  public function deleteAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $poll = Engine_Api::_()->getItem('sesadvpoll_poll', $this->getRequest()->getParam('poll_id'));

    if (!$this->_helper->requireAuth()->setAuthParams($poll, null, 'delete')->isValid())
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));

    if (!$poll) {
      $this->view->status = false;
      $error = Zend_Registry::get('Zend_Translate')->_("Poll doesn't exist or not authorized to delete");
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    $db = $poll->getTable()->getAdapter();
    $db->beginTransaction();

    try {
      $poll->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'databse_error', 'result' => array()));
    }

    $this->view->status = true;
    $message = Zend_Registry::get('Zend_Translate')->_('Your poll has been deleted.');
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $message));
  }

  public function closeAction()
  {

    $data = array();
    if (!$this->_helper->requireUser()->isValid())
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject())
      $poll = Engine_Api::_()->getItem('sesadvpoll_poll', $this->getRequest()->getParam('poll_id'));
    else
      $poll = Engine_Api::_()->core()->getSubject();

    if (!$poll) {
      $error = Zend_Registry::get('Zend_Translate')->_("Poll doesn't exist or not authorized to delete");
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    // Check auth
    if (!$this->_helper->requireAuth()->setAuthParams($poll, $viewer, 'edit')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    if (!$this->getRequest()->isPost()) {
      $data['status'] = false;
      $data['message'] = $this->view->translate('Invalid request method');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $data['message'], 'result' => $data));
    }

    // @todo convert this to post only
    $table = $poll->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $poll->closed = $poll->closed == 1 ? 0 : 1;
      $poll->save();
      $db->commit();
      $data['status'] = true;
      $data['message'] = $poll->closed == 1 ? $this->view->translate('Successfully Closed') : $this->view->translate('Successfully Unclosed');
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $data));
  }

  public function viewAction()
  {
    // Check auth
    if (!Engine_Api::_()->core()->hasSubject())
      $poll = Engine_Api::_()->getItem('sesadvpoll_poll', $this->_getParam('id', null));
    else
      $poll = Engine_Api::_()->core()->getSubject();
    if (!$poll)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('This poll does not seem to exist anymore.'), 'result' => array()));

    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }
    $result = array();
    $poll = Engine_Api::_()->core()->getSubject('sesadvpoll_poll');
    $owner = $poll->getOwner();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $pollOptions = $poll->getOptions();
    $hasVoted = $poll->viewerVoted();
    $showPieChart = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.showpiechart', false);
    $canVote = $poll->authorization()->isAllowed(null, 'vote');
    $canChangeVote = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.canchangevote', false);
    $canDelete = $poll->authorization()->isAllowed($viewer, 'delete');
    $canEdit = $poll->authorization()->isAllowed($viewer, 'edit');
    $poll_is_voted = $poll->vote_count > 0 ? true : false;
    $shareType = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.allow.share', 1);
    $likeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($poll->poll_id, 'sesadvpoll_poll');
    $can_fav = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.allow.favourite', 1);
    $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sesadvpoll')->isFavourite(array('resource_id' => $poll->poll_id, 'resource_type' => 'sesadvpoll_poll'));

    if (!$owner->isSelf($viewer)) {
      $poll->view_count++;
      $poll->save();
    }
    $counterOpt = 0;
    $optionData = array();
    if ($viewer_id > 0 && !$owner->isSelf($viewer)) {
      $optionData[$counterOpt]['name'] = 'report';
      $optionData[$counterOpt]['label'] = $this->view->translate('Report');
      $counterOpt++;
    }
    if ($canEdit && !$poll_is_voted) {
      $optionData[$counterOpt]['name'] = 'edit';
      $optionData[$counterOpt]['label'] = $this->view->translate('Edit');
      $counterOpt++;
    }
    if ($canDelete && !$poll_is_voted) {
      $optionData[$counterOpt]['name'] = 'delete';
      $optionData[$counterOpt]['label'] = $this->view->translate('Delete');
      $counterOpt++;
    }
    if ($shareType) {
      $optionData[$counterOpt]['name'] = 'share';
      $optionData[$counterOpt]['label'] = $this->view->translate('Share');
      $counterOpt++;
    }
    if ($owner->isSelf($viewer) && !$poll->closed) {
      $optionData[$counterOpt]['name'] = 'close';
      $optionData[$counterOpt]['label'] = $this->view->translate('Close');
      $counterOpt++;
    } elseif ($owner->isSelf($viewer) && $poll->closed) {
      $optionData[$counterOpt]['name'] = 'open';
      $optionData[$counterOpt]['label'] = $this->view->translate('Open');
      $counterOpt++;
    }


    $result = $poll->toArray();
    $user_id = $owner->getIdentity();
    $user = Engine_Api::_()->getItem('user', $user_id);
    $result['owner_title'] = $poll->getOwner()->getTitle();
    $result['can_edit'] = $canEdit > 0 ? true : false;
    $result['can_delete'] = $canDelete > 0 ? true : false;
    $result['has_voted'] = $hasVoted > 0 ? true : false;
    $result['has_voted_id'] = ($hasVoted == false) ? 0 : $hasVoted;
    $result['shareType'] = $shareType;
    //$result['token'] = $this->view->sesadvpollVoteHash($poll)->generateHash();
    $result['can_change_vote'] = $canChangeVote;
    if ($hasVoted) {
      if ($canChangeVote) {
        $result['can_vote'] = true;
      } else {
        $result['can_vote'] = false;
      }
    } else {
      $result['can_vote'] = $canVote > 0 ? true : false;
    }
    $result['can_vote'] = $poll->authorization()->isAllowed(null, 'vote') ? 'true' : 'false';
    if ($user) {
      $ownerimage = Engine_Api::_()->sesapi()->getPhotoUrls($user, "", "");
      $result['owner_image'] = $ownerimage;
    }
    
		if($shareType){
      $result['can_share'] = true;
    }else{
      $result['can_share'] = false;
    }
    $result["share"]["name"] = "share";
    $result["share"]["label"] = $this->view->translate("Share");
    $photo = $this->getBaseUrl(false,$poll->getPhotoUrl());
    if($photo)
			$result["share"]["imageUrl"] = $photo;
		$result["share"]["url"] = $this->getBaseUrl(false,$poll->getHref());
    $result["share"]["title"] = $poll->getTitle();
    $result["share"]["description"] = strip_tags($poll->getDescription());
    $result["share"]['urlParams'] = array(
        "type" => $poll->getType(),
        "id" => $poll->getIdentity()
    );
    if(is_null($result["share"]["title"]))
      unset($result["share"]["title"]);

    if ($viewer_id)
      $result['is_content_like'] = $likeStatus > 0 ? true : false;
    if ($can_fav)
      $result['is_content_favourite'] = $favouriteStatus > 0 ? true : false;
    $counter = 0;

    foreach ($pollOptions as $option) {
      $voteUserCounter = 0;
      $result['options'][$counter] = $option->toArray();
      if ($option->file_id > 0 && $option->image_type > 0) {
        $pct = $poll->vote_count ? floor(100 * ($option->votes / $poll->vote_count)) : 0;
        if (!$pct)
          $pct = 1;
        $result['options'][$counter]['vote_percent'] = $result['options'][$counter]['vote_percent'] = $this->view->translate(array('%1$s vote', '%1$s votes', $option->votes), $this->view->locale()->toNumber($option->votes)) . '(' . $this->view->
          translate('%1$s%%', $this->view->locale()->toNumber($option->votes ? $pct : 0)) . ')';
        $result['options'][$counter]['option_image'] = ($storage = Engine_Api::_()->storage()->get($option->file_id, '')) ? $this->getBaseUrl(true, $storage->map()) : "";
        $tables = Engine_Api::_()->getDbtable('votes', 'sesadvpoll')->getVotesPaginator($option->poll_option_id)->setItemCountPerPage(5)->setCurrentPageNumber(1);
        $pagecount = $tables->getPages()->pageCount;
        foreach ($tables as $table) {
          $user = Engine_Api::_()->getItem('user', $table->user_id);
          $userImage = Engine_Api::_()->sesapi()->getPhotoUrls($user, "", "");
          $result['options'][$counter]['voted_user'][$voteUserCounter]['resourece_id'] = $user->getIdentity();
          $result['options'][$counter]['voted_user'][$voteUserCounter]['resource_type'] = $user->getType();
          if ($userImage) {
            $result['options'][$counter]['voted_user'][$voteUserCounter]['user_image'] = $userImage;
          }
          $voteUserCounter++;
        }
        $result['options'][$counter]['more_user_link'] = $pagecount > 1 ? true : false;
      } else {
        $pct = $poll->vote_count ? floor(100 * ($option->votes / $poll->vote_count)) : 0;
        if (!$pct)
          $pct = 1;
        $result['options'][$counter]['vote_percent'] = $this->view->translate(array('%1$s vote', '%1$s votes', $option->votes), $this->view->locale()->toNumber($option->votes)) . '(' . $this->view->
          translate('%1$s%%', $this->view->locale()->toNumber($option->votes ? $pct : 0)) . ')';
        $tables = Engine_Api::_()->getDbtable('votes', 'sesadvpoll')->getVotesPaginator($option->poll_option_id)->setItemCountPerPage(4)->setCurrentPageNumber(1);
        $pagecount = $tables->getPages()->pageCount;
        foreach ($tables as $table) {
          $user = Engine_Api::_()->getItem('user', $table->user_id);
          $userImage = Engine_Api::_()->sesapi()->getPhotoUrls($user, "", "");
          $result['options'][$counter]['voted_user'][$voteUserCounter]['resourece_id'] = $user->getIdentity();
          $result['options'][$counter]['voted_user'][$voteUserCounter]['resource_type'] = $user->getType();
          if ($userImage) {
            $result['options'][$counter]['voted_user'][$voteUserCounter]['user_image'] = $userImage;
          }
          $voteUserCounter++;
        }
        $result['options'][$counter]['more_user_link'] = $pagecount > 1 ? true : false;
      }
      $counter++;
    }
    $data = array();
    if (engine_count($optionData) > 0)
      $data['options'] = $optionData;
    $data['poll'] = $result;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $data)));
  }

	public function searchAction() {
		
		$option_action = $this->_getParam('option_action', 'browse');
		$viewer = Engine_Api::_()->user()->getViewer();
		$p = Zend_Controller_Front::getInstance()->getRequest()->getParams();
		// Get form
		$form = new Sesadvpoll_Form_Search(array('searchTitle'=>$this->_getParam('search_title')));
		if( !$viewer->getIdentity() ) {
				$form->removeElement('show');
		}
		if($option_action == 'browse') {
			$form->removeElement('closed');
		} else if($option_action == 'manage') {
			$form->removeElement('show');
		}
		// Process form
		if ($this->_getParam('getForm')) {
				$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
				$this->generateFormFields($formFields, array('resources_type' => 'sesadvpoll_poll'));
		} else {
				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		}
	}

  public function voteAction()
  {
    // Check auth
    if (!$this->_helper->requireUser()->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
    }
    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'You do not have permission to view this private page.', 'result' => array()));
    }
    if (!$this->_helper->requireAuth()->setAuthParams(null, null, 'vote')->isValid()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'You do not have permission to vote on poll.', 'result' => array()));
    }

    // Check method
    if (!$this->getRequest()->isPost()) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'post data error', 'result' => array()));
    }

    $option_id = $this->_getParam('option_id');
    $canChangeVote = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpoll.canchangevote', false);

    $poll = Engine_Api::_()->core()->getSubject('sesadvpoll_poll');
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$poll) {
      $success = false;
      $error = Zend_Registry::get('Zend_Translate')->_('This poll does not seem to exist anymore.');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    //$hashElement = $this->view->sesadvpollVoteHash($poll)->getElement();
    if ($poll->closed) {
      $success = false;
      $error = Zend_Registry::get('Zend_Translate')->_('This poll is closed.');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    if ($poll->hasVoted($viewer) && !$canChangeVote) {
      $success = false;
      $error = Zend_Registry::get('Zend_Translate')->_('You have already voted on this poll, and are not permitted to change your vote.');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    $data = array();

    $owner = $poll->getOwner();
    $db = Engine_Api::_()->getDbTable('polls', 'sesadvpoll')->getAdapter();
    $db->beginTransaction();
    try {
      $poll->vote($viewer, $option_id, $owner, $poll);
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      $this->view->success = false;
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => array()));
    }

    //$this->view->token = $this->view->sesadvpollVoteHash($poll)->generateHash();
    $data['success'] = true;

    $pollOptions = array();
    foreach ($poll->getOptions()->toArray() as $option) {
      $option['votesTranslated'] = $this->view->translate(array('%s vote', '%s votes', $option['votes']), $this->view->locale()->toNumber($option['votes']));
      $pollOptions[] = $option;
    }
    $data['options'] = $pollOptions;
    $data['votes_total'] = $poll->vote_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $data));
  }

  public function favouriteAction()
  {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $item_id = $this->_getParam('id', null);
    if (intval($item_id) == 0) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }

    $viewer = Engine_Api::_()->user()->getViewer();

    $favTable = Engine_Api::_()->getDbTable('favourites', 'sesadvpoll');
    $Fav = $favTable->getItemfav('sesadvpoll_poll', $item_id);

    $favItem = Engine_Api::_()->getDbTable('polls', 'sesadvpoll');
    $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);

    if (!empty($Fav)) {
      $db = $Fav->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $Fav->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        //throw $e;
      }

      $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count - 1')), array('poll_id = ?' => $item_id));
      $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);
      Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => 'sesadvpoll_favourite_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));

      Engine_Api::_()->getDbTable('actions', 'activity')->delete(array('type =?' => 'favourite_sesadvpoll_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));

      Engine_Api::_()->getDbTable('actions', 'activity')->detachFromActivity($item);

      $temp['data']['favourite_count'] = $item->favourite_count;

      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));

    } else {

      $db = $favTable->getAdapter();
      $db->beginTransaction();
      try {
        $fav = $favTable->createRow();
        $fav->user_id = $viewer->getIdentity();
        $fav->resource_type = 'sesadvpoll_poll';
        $fav->resource_id = $item_id;
        $fav->save();
        $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1')), array('poll_id = ?' => $item_id));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        //throw $e;
      }
      $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);
      $owner = $item->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
        $activityTable = Engine_Api::_()->getDbTable('actions', 'activity');
        Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => 'sesadvpoll_favourite_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesadvpoll_favourite_poll');
        $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $item, 'favourite_sesadvpoll_poll');
        if ($action != null) {
          Engine_Api::_()->getDbTable('actions', 'activity')->attachActivity($action, $item);
        }
      }

      $temp['data']['favourite_count'] = $item->favourite_count;
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    }
  }

  public function likeAction()
  {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }

    $viewer = Engine_Api::_()->user()->getViewer();

    $tableLike = Engine_Api::_()->getDbTable('likes', 'core');
    $tableMainLike = $tableLike->info('name');

    $itemTable = Engine_Api::_()->getDbTable('polls', 'sesadvpoll');

    $select = $tableLike->select()->from($tableMainLike)->where('resource_type =?', 'sesadvpoll_poll')->where('poster_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('poster_type =?', 'user')->where('resource_id =?', $item_id);
    $Like = $tableLike->fetchRow($select);

    $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);

    if (!empty($Like)) {
      $db = $Like->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $Like->delete();
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Page Successfully Unliked.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }
      $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);
      Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => 'sesadvpoll_like_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      Engine_Api::_()->getDbTable('actions', 'activity')->delete(array('type =?' => 'like_sesadvpoll_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      Engine_Api::_()->getDbTable('actions', 'activity')->detachFromActivity($item);

      $temp['data']['like_count'] = $item->like_count;
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    } else {
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = $viewer->getIdentity();
        $like->resource_type = 'sesadvpoll_poll';
        $like->resource_id = $item_id;
        $like->poster_type = 'user';
        $like->save();
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array('poll_id = ?' => $item_id));
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Page Successfully Liked.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }
      $item = Engine_Api::_()->getItem('sesadvpoll_poll', $item_id);
      $owner = $item->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => 'sesadvpoll_like_poll', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesadvpoll_like_poll');
        $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $item, 'like_sesadvpoll_poll');
        if ($action != null) {
          Engine_Api::_()->getDbTable('actions', 'activity')->attachActivity($action, $item);
        }
      }
      $temp['data']['like_count'] = $item->like_count;
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    }
  }
}
