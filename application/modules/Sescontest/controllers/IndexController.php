<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_IndexController extends Core_Controller_Action_Standard {

  public function init() {
    if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'view')->isValid())
      return;
  }

  public function setDateDataAction() {
    $timeZone = $_POST['timezone'];
    $values = trim($_POST['values'], '&');
    parse_str($values, $parameters);
    $oldTz = date_default_timezone_get();
    date_default_timezone_set($timeZone);
    $start_date = date('m/d/Y', strtotime($parameters["sescontest_start_date"]));
    $start_time = date('g:ia', strtotime($parameters['sescontest_start_time']));
    $end_date = date('m/d/Y', strtotime($parameters['sescontest_end_date']));
    $end_time = date('g:ia', strtotime($parameters['sescontest_end_time']));

    $join_start_date = date('m/d/Y', strtotime($parameters['join_start_date']));
    $join_start_time = date('g:ia', strtotime($parameters['sescontest_join_start_time']));
    $join_end_date = date('m/d/Y', strtotime($parameters['sescontest_join_end_date']));
    $join_end_time = date('g:ia', strtotime($parameters['sescontest_join_end_time']));

    $voting_start_date = date('m/d/Y', strtotime($parameters['sescontest_join_start_date']));
    $voting_start_time = date('g:ia', strtotime($parameters['sescontest_voting_start_time']));
    $voting_end_date = date('m/d/Y', strtotime($parameters['sescontest_voting_end_date']));
    $voting_end_time = date('g:ia', strtotime($parameters['sescontest_voting_end_time']));
    date_default_timezone_set($oldTz);
    $dateData = array(array('key' => 'sescontest_start_date', 'value' => $start_date), array('key' => 'sescontest_end_date', 'value' => $end_date), array('key' => 'sescontest_start_time', 'value' => $start_time), array('key' => 'sescontest_end_time', 'value' => $end_time), array('key' => 'join_start_date', 'value' => $join_start_date), array('key' => 'sescontest_join_end_date', 'value' => $join_end_date), array('key' => 'sescontest_join_start_time', 'value' => $join_start_time), array('key' => 'sescontest_join_end_time', 'value' => $join_end_time), array('key' => 'sescontest_join_start_date', 'value' => $voting_start_date), array('key' => 'sescontest_voting_end_date', 'value' => $voting_end_date), array('key' => 'sescontest_voting_start_time', 'value' => $voting_start_time), array('key' => 'sescontest_voting_end_time', 'value' => $voting_end_time));
    echo json_encode($dateData);
    die;
  }

  public function votesAction() {
    $this->view->is_ajax = !empty($_POST['is_ajax']) ? $_POST['is_ajax'] : false;
    $this->view->page = $page = !empty($_POST['page']) ? $_POST['page'] : 1;

    $this->view->contest_id = $contest_id = $this->_getParam('contest_id', 0);
    $contest = Engine_Api::_()->getItem('contest', $contest_id);
    $limit = 10;

    $params = array('contest_id' => $contest_id);
    if (!$this->view->viewer()->getIdentity()):
      $levelId = 5;
    else: $levelId = $this->view->viewer()->level_id;
    endif;
    $voteType = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'allow_entry_vote');

    if ($voteType == 1 && $this->view->viewer()->getIdentity() != 0) {
      $params['own_entry'] = $contest->user_id;
    }

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('participants', 'sescontest')
            ->getParticipantPaginator($params);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
  }

  public function sesbackuplandingppageAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function browseContestsAction() {

    $integrateothermodule_id = $this->_getParam('integrateothermodule_id', null);
    $page = 'sescontest_index_' . $integrateothermodule_id;
    //Render
    $this->_helper->content->setContentName($page)->setEnabled();
  }

  public function browseAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function packagesAction() {
    if (!$this->_helper->requireUser->isValid())
      return;
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->existingleftpackages = $existingleftpackages = Engine_Api::_()->getDbTable('orderspackages', 'sescontestpackage')->getLeftPackages(array('owner_id' => $viewer->getIdentity()));
    $this->_helper->content->setEnabled();
  }

  public function transactionsAction() {
    if (!$this->_helper->requireUser->isValid())
      return;
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    $tableTransaction = Engine_Api::_()->getItemTable('sescontestpackage_transaction');
    $tableTransactionName = $tableTransaction->info('name');
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

    $select = $tableTransaction->select()
            ->setIntegrityCheck(false)
            ->from($tableTransactionName)
            ->joinLeft($tableUserName, "$tableTransactionName.owner_id = $tableUserName.user_id", 'username')
            ->where($tableUserName . '.user_id IS NOT NULL')
            ->joinLeft($contestTableName, "$tableTransactionName.transaction_id = $contestTableName.transaction_id", 'contest_id')
            ->where($contestTableName . '.contest_id IS NOT NULL')
            ->where($tableTransactionName . '.owner_id =?', $viewer->getIdentity());
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(10);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $this->_helper->content->setEnabled();
  }

  public function ongoingAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function comingsoonAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function endedAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function pinboardAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function welcomeAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function homeAction() {
    //Render
    $sescontest_browse = Zend_Registry::isRegistered('sescontest_browse') ? Zend_Registry::get('sescontest_browse') : null;
    if (!empty($sescontest_browse)) {
      $this->_helper->content->setEnabled();
    }
  }

  public function tagsAction() {
    //Render
    $this->_helper->content->setEnabled();
  }

  public function winnerAction() {

    $this->_helper->content->setEnabled();
  }

  public function entriesAction() {

    $this->_helper->content->setEnabled();
  }

  public function manageAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    // Render
    $sescontest_manage = Zend_Registry::isRegistered('sescontest_manage') ? Zend_Registry::get('sescontest_manage') : null;
    if (!empty($sescontest_manage)) {
      $this->_helper->content->setEnabled();
    }

    $this->view->canCreate = $this->_helper->requireAuth()->setAuthParams('sescontest', null, 'create')->checkRequire();
  }

  public function createAction() {

    if (!$this->_helper->requireUser->isValid())
      return;
    $viewer = Engine_Api::_()->user()->getViewer();
    $totalContest = Engine_Api::_()->getDbTable('contests', 'sescontest')->countContests($viewer->getIdentity());
    $allowContestCount = Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_count');
    if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'create')->isValid())
      return;
    //Start Package Work
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $this->_getParam('package_id', 0));
      $existingpackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $this->_getParam('existing_package_id', 0));
      if ($existingpackage) {
        $package = Engine_Api::_()->getItem('sescontestpackage_package', $existingpackage->package_id);
      }
      if (!$package && !$existingpackage) {
        //check package exists for this member level
        $packageMemberLevel = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getPackage(array('member_level' => $viewer->level_id));
        if (engine_count($packageMemberLevel)) {
          //redirect to package page
          return $this->_helper->redirector->gotoRoute(array('action' => 'contest'), 'sescontestpackage_general', true);
        }
      }
      if ($existingpackage) {
        $canCreate = Engine_Api::_()->getDbTable('orderspackages', 'sescontestpackage')->checkUserPackage($this->_getParam('existing_package_id', 0), $this->view->viewer()->getIdentity());
        if (!$canCreate)
          return $this->_helper->redirector->gotoRoute(array('action' => 'contest'), 'sescontestpackage_general', true);
      }
    }
    //End Package Work
    $sescontest_create = Zend_Registry::isRegistered('sescontest_create') ? Zend_Registry::get('sescontest_create') : null;
    if (empty($sescontest_create)) {
      return $this->_forward('notfound', 'error', 'core');
    }
    $this->view->resource_id = $resource_id = $this->_getParam('resource_id', 0);
    $this->view->resource_type = $resource_type = $this->_getParam('resource_type', 0);
    $this->view->widget_id = $widget_id = $this->_getParam('widget_id', 0);
    if ($resource_id && $resource_type) {
      $this->view->item = $item = Engine_Api::_()->getItem($resource_type, $resource_id);
      if (!$item)
        return $this->_forward('notfound', 'error', 'core');
    }
    //Render
    $sessmoothbox = $this->view->typesmoothbox = false;
    if ($this->_getParam('typesmoothbox', false)) {
      // Render
      $sessmoothbox = true;
      $this->view->typesmoothbox = true;
      $this->_helper->layout->setLayout('default-simple');
      $layoutOri = $this->view->layout()->orientation;
      if ($layoutOri == 'right-to-left') {
        $this->view->direction = 'rtl';
      } else {
        $this->view->direction = 'ltr';
      }
      $language = explode('_', $this->view->locale()->getLocale()->__toString());
      $this->view->language = $language[0];
    } else {
      $this->_helper->content->setEnabled();
    }
    $this->view->createLimit = 1;
    if ($totalContest >= $allowContestCount && $allowContestCount != 0) {
      $this->view->createLimit = 0;
    } else {
      if (!isset($_GET['category_id']) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.category.selection', 1)) {
        $this->view->categories = Engine_Api::_()->getDbTable('categories', 'sescontest')->getCategory(array('fetchAll' => true));
      }

      $this->view->defaultProfileId = 1;
      $this->view->form = $form = new Sescontest_Form_Create(array(
          'defaultProfileId' => 1,
          'smoothboxType' => $sessmoothbox,
      ));

      if (isset($_GET['category_id']) && !empty($_GET['category_id']))
        $form->category_id->setValue($_GET['category_id']);

      //START QUICK CONTEST CREATION WORK
      $refereneId = $this->_getParam('ref', 0);
      if ($this->_getParam('ref', 0)) {
        $contest = Engine_Api::_()->getItem('contest', $refereneId);
        $form->title->setValue($contest->title);
        $tagStr = '';
        foreach ($contest->tags()->getTagMaps() as $tagMap) {
          $tag = $tagMap->getTag();
          if (!isset($tag->text))
            continue;
          if ('' !== $tagStr)
            $tagStr .= ', ';
          $tagStr .= $tag->text;
        }
        $form->populate(array(
            'tags' => $tagStr,
        ));
        $form->contest_type->setValue($contest->contest_type);
        $form->category_id->setValue($contest->category_id);
        $form->subcat_id->setValue($contest->subcat_id);
        $form->subsubcat_id->setValue($contest->subsubcat_id);
        $form->description->setValue($contest->description);
        $form->award->setValue($contest->award);
        $form->rules->setValue($contest->rules);
        $form->vote_type->setValue($contest->vote_type);
      }
    }
    //END QUICK CONTEST CREATION WORK
    // Not post/invalid
    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    //check custom url
    if (isset($_POST['custom_url']) && !empty($_POST['custom_url'])) {
      $custom_url = Engine_Api::_()->getDbtable('contests', 'sescontest')->checkCustomUrl($_POST['custom_url']);
      if ($custom_url) {
        $form->addError($this->view->translate("Custom Url not available.Please select other."));
        return;
      }
    }

    // Process
    $starttime = isset($_POST['start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['start_date'] . ' ' . $_POST['start_time'])) : '';
    $endtime = isset($_POST['end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['end_date'] . ' ' . $_POST['end_time'])) : '';
    $joinStartTime = isset($_POST['join_start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['join_start_date'] . ' ' . $_POST['join_start_time'])) : '';
    $joinEndTime = isset($_POST['join_end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['join_end_date'] . ' ' . $_POST['join_end_time'])) : '';
    $votingStartTime = isset($_POST['voting_start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['voting_start_date'] . ' ' . $_POST['voting_start_time'])) : '';
    $votingEndTime = isset($_POST['voting_end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['voting_end_date'] . ' ' . $_POST['voting_end_time'])) : '';
    $resutTime = isset($_POST['result_date']) ? date('Y-m-d H:i:s', strtotime($_POST['result_date'] . ' ' . $_POST['result_time'])) : '';
    $values = $form->getValues();
    $values['user_id'] = $viewer->getIdentity();
    $values['timezone'] = isset($_POST['timezone']) ? $_POST['timezone'] : '';
    if (empty($values['timezone'])) {
      $values['timezone'] = $viewer->timezone;
    }

    $error = Engine_Api::_()->sescontest()->dateValidations($_POST);
    if (isset($error[0]) && $error[0]) {
      $form->addError(Zend_Registry::get('Zend_Translate')->_($error[1]));
      return;
    }
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if ($settings->getSetting('sescontest.contestmainphoto', 1)) {
      if (isset($values['photo']) && empty($values['photo'])) {
        $form->addError(Zend_Registry::get('Zend_Translate')->_('Main Photo is a required field.'));
        return;
      }
    }
    // Convert times
    $oldTz = date_default_timezone_get();
    date_default_timezone_set($values['timezone']);
    $start = strtotime($starttime);
    $end = strtotime($endtime);
    $joinStart = strtotime($joinStartTime);
    $joinEnd = strtotime($joinEndTime);
    $votingStart = strtotime($votingStartTime);
    $votingEnd = strtotime($votingEndTime);
    $ResultDate = strtotime($resutTime);
    date_default_timezone_set($oldTz);
    $values['starttime'] = date('Y-m-d H:i:s', $start);
    $values['endtime'] = date('Y-m-d H:i:s', $end);
    $values['joinstarttime'] = date('Y-m-d H:i:s', $joinStart);
    $values['joinendtime'] = date('Y-m-d H:i:s', $joinEnd);
    $values['votingstarttime'] = date('Y-m-d H:i:s', $votingStart);
    $values['votingendtime'] = date('Y-m-d H:i:s', $votingEnd);
    $values['resulttime'] = date('Y-m-d H:i:s', $ResultDate);
    if (!$values['vote_type'])
      $values['resulttime'] = '';

    $contestTable = Engine_Api::_()->getDbtable('contests', 'sescontest');
    $db = $contestTable->getAdapter();
    $db->beginTransaction();
    try {
      // Create contest
      $contest = $contestTable->createRow();

      $sescontest_draft = $settings->getSetting('sescontest.draft', 1);
      if (empty($sescontest_draft)) {
        $values['draft'] = 1;
      }
      if (empty($values['category_id']))
        $values['category_id'] = 0;
      if (empty($values['subsubcat_id']))
        $values['subsubcat_id'] = 0;
      if (empty($values['subcat_id']))
        $values['subcat_id'] = 0;
      if (isset($package)) {
        $values['package_id'] = $package->getIdentity();
        if ($package->isFree()) {
          if (isset($params['contest_approve']) && $params['contest_approve'])
            $values['is_approved'] = 1;
        } else
          $values['is_approved'] = 0;
        if ($existingpackage) {
          $values['existing_package_order'] = $existingpackage->getIdentity();
          $values['orderspackage_id'] = $existingpackage->getIdentity();
          $existingpackage->item_count = $existingpackage->item_count - 1;
          $existingpackage->save();
          $params = json_decode($package->params, true);
          if (isset($params['contest_approve']) && $params['contest_approve'])
            $values['is_approved'] = 1;
          if (isset($params['contest_featured']) && $params['contest_featured'])
            $values['featured'] = 1;
          if (isset($params['contest_sponsored']) && $params['contest_sponsored'])
            $values['sponsored'] = 1;
          if (isset($params['contest_verified']) && $params['contest_verified'])
            $values['verified'] = 1;
          if (isset($params['contest_hot']) && $params['contest_hot'])
            $values['hot'] = 1;
        }
      } else {
        if (!isset($package) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage')) {
          $values['package_id'] = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getDefaultPackage();
        }
      }
      $contest->setFromArray($values);
      $contest->search = $values['search'];
      $contest->save();

      //Start Default Package Order Work
      if (isset($package) && $package->isFree()) {
        if (!$existingpackage) {
          $transactionsOrdersTable = Engine_Api::_()->getDbtable('orderspackages', 'sescontestpackage');
          $transactionsOrdersTable->insert(array(
              'owner_id' => $viewer->user_id,
              'item_count' => ($package->item_count - 1 ),
              'package_id' => $package->getIdentity(),
              'state' => 'active',
              'expiration_date' => '3000-00-00 00:00:00',
              'ip_address' => $_SERVER['REMOTE_ADDR'],
              'creation_date' => new Zend_Db_Expr('NOW()'),
              'modified_date' => new Zend_Db_Expr('NOW()'),
          ));
          $contest->orderspackage_id = $transactionsOrdersTable->getAdapter()->lastInsertId();
          $contest->existing_package_order = 0;
        } else {
          $existingpackage->item_count = $existingpackage->item_count--;
          $existingpackage->save();
        }
      }
      //End Default package Order Work

      if ($resource_id && $resource_type) {
        $contest->resource_id = $resource_id;
        $contest->resource_type = $resource_type;
        $contest->save();
      }
      $tags = preg_split('/[,]+/', $values['tags']);
      $contest->seo_keywords = implode(',', $tags);
      //$contest->seo_title = $contest->title;
      $contest->save();
      $contest->tags()->addTagMaps($viewer, $tags);

      $count = 0;
      if (!empty($values['award']))
        $count++;
      if (!empty($values['award2']))
        $count++;
      if (!empty($values['award3']))
        $count++;
      if (!empty($values['award4']))
        $count++;
      if (!empty($values['award5']))
        $count++;

      $contest->award_count = $count;
      $contest->save();

      if (!isset($package)) {
        if (!Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_approve'))
          $contest->is_approved = 0;
        if (Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_featured'))
          $contest->featured = 1;
        if (Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'autosponsored'))
          $contest->sponsored = 1;
        if (Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_verified'))
          $contest->verified = 1;
        if (Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_hot'))
          $contest->hot = 1;
      }

      // Add photo
      if (!empty($values['photo'])) {
        $contest->setPhoto($form->photo);
      }
      // Set auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

      if (empty($values['auth_view'])) {
        $values['auth_view'] = 'everyone';
      }
      if (empty($values['auth_comment'])) {
        $values['auth_comment'] = 'everyone';
      }
      $viewMax = array_search($values['auth_view'], $roles);
      $commentMax = array_search($values['auth_comment'], $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($contest, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($contest, $role, 'comment', ($i <= $commentMax));
      }
      //Add fields
      $customfieldform = $form->getSubForm('fields');
      if ($customfieldform) {
        $customfieldform->setItem($contest);
        $customfieldform->saveValues();
      }
      $contest->save();
      // Commit
      $db->commit();
      if (!empty($_POST['custom_url']) && $_POST['custom_url'] != '')
        $contest->custom_url = $_POST['custom_url'];
      else
        $contest->custom_url = $contest->contest_id;
      $contest->save();
      $autoOpenSharePopup = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.autoopenpopup', 1);
      if ($autoOpenSharePopup && $contest->draft && $contest->is_approved) {
        $_SESSION['newContest'] = true;
      }
      //Start Activity Feed Work
      if ($contest->draft == 1 && $contest->is_approved == 1) {
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $contest, 'sescontest_create');
        if ($action) {
          $activityApi->attachActivity($action, $contest);
        }
      }
      //End Activity Feed Work
      //Start Send Approval Request to Admin
      if (!$contest->is_approved) {
        if (isset($package) && $package->price > 0) {
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($contest->getOwner(), $viewer, $contest, 'sescontest_payment_notify_contest');
        } else {
          Engine_Api::_()->sescontest()->sendMailNotification(array('contest' => $contest));
        }
      }
      if (!empty($item)) {
        $tab = "";
        if ($widget_id)
          $tab = "/tab/" . $widget_id;
        header('location:' . $item->getHref() . $tab);
        die;
      }

      //End Work Here.
      $redirection = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.redirect', 1);
      if (!$contest->is_approved) {
        return $this->_helper->redirector->gotoRoute(array('action' => 'manage'), 'sescontest_general', true);
      } else
      if ($redirection == 1) {
        header('location:' . $contest->getHref());
        die;
      } else {
        return $this->_helper->redirector->gotoRoute(array('contest_id' => $contest->custom_url), 'sescontest_dashboard', true);
      }
    } catch (Engine_Image_Exception $e) {
      $db->rollBack();
      $form->addError(Zend_Registry::get('Zend_Translate')->_('The image you selected was too large.'));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function deleteAction() {

    if (!$this->_helper->requireUser()->isValid())
      return;

    $sescontest = Engine_Api::_()->getItem('contest', $this->getRequest()->getParam('contest_id'));
    if (!$this->_helper->requireAuth()->setAuthParams($sescontest, null, 'delete')->isValid())
      return;

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');

    $this->view->form = $form = new Sescontest_Form_Delete();

    if (!$sescontest) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Contest entry doesn't exist or not authorized to delete");
      return;
    }

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    $db = $sescontest->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $sescontest->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your contest has been deleted successfully!');
    return $this->_forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sescontest_general', true),
                'messages' => Array($this->view->message)
    ));
  }

}
