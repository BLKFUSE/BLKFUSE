<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: DashboardController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_DashboardController extends Core_Controller_Action_Standard {

  public function init() {
    if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'view')->isValid())
      return;
		if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'edit')->isValid())
      return;
    if (!$this->_helper->requireUser->isValid())
      return;
    $id = $this->_getParam('contest_id', null);
    $viewer = $this->view->viewer();
    $contest_id = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestId($id);
    if ($contest_id) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      if ($contest && $contest->is_approved && ($viewer->level_id == 1 || $viewer->level_id == 2 || $viewer->getIdentity() == $contest->user_id))
        Engine_Api::_()->core()->setSubject($contest);
      else
        return $this->_forward('requireauth', 'error', 'core');
    } else
      return $this->_forward('requireauth', 'error', 'core');
    if (!$this->_helper->requireAuth()->setAuthParams($contest, null, 'edit')->isValid())
      return;
  }

  public function editAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $previous_starttime = $contest->starttime;
    $previous_endtime = $contest->endtime;
    $previous_joinstarttime = $contest->joinstarttime;
    $previous_joinendtime = $contest->joinendtime;
    $previous_votingstarttime = $contest->votingstarttime;
    $previous_votingendtime = $contest->votingendtime;

    //Contest Category and profile fileds
    $this->view->defaultProfileId = $defaultProfileId = 1;
    if (isset($contest->category_id) && $contest->category_id != 0)
      $this->view->category_id = $contest->category_id;
    else if (isset($_POST['category_id']) && $_POST['category_id'] != 0)
      $this->view->category_id = $_POST['category_id'];
    else
      $this->view->category_id = 0;
    if (isset($contest->subsubcat_id) && $contest->subsubcat_id != 0)
      $this->view->subsubcat_id = $contest->subsubcat_id;
    else if (isset($_POST['subsubcat_id']) && $_POST['subsubcat_id'] != 0)
      $this->view->subsubcat_id = $_POST['subsubcat_id'];
    else
      $this->view->subsubcat_id = 0;
    if (isset($contest->subcat_id) && $contest->subcat_id != 0)
      $this->view->subcat_id = $contest->subcat_id;
    else if (isset($_POST['subcat_id']) && $_POST['subcat_id'] != 0)
      $this->view->subcat_id = $_POST['subcat_id'];
    else
      $this->view->subcat_id = 0;
    //Contest category and profile fields
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return $this->_forward('notfound', 'error', 'core');
    // Create form
    $this->view->form = $form = new Sescontest_Form_Edit(array('defaultProfileId' => $defaultProfileId));
    $this->view->category_id = $contest->category_id;
    $this->view->subcat_id = $contest->subcat_id;
    $this->view->subsubcat_id = $contest->subsubcat_id;
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
    if (!$this->getRequest()->isPost()) {
      // Populate auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      foreach ($roles as $role) {
        if (isset($form->auth_view->options[$role]) && $auth->isAllowed($contest, $role, 'view'))
          $form->auth_view->setValue($role);
        if (isset($form->auth_comment->options[$role]) && $auth->isAllowed($contest, $role, 'comment'))
          $form->auth_comment->setValue($role);
      }
      $form->populate($contest->toArray());
      if ($form->draft->getValue() == 1)
        $form->removeElement('draft');
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
    //check custom url
    if (isset($_POST['custom_url']) && !empty($_POST['custom_url'])) {
      $custom_url = Engine_Api::_()->getDbtable('contests', 'sescontest')->checkCustomUrl($_POST['custom_url'], $contest->contest_id);
      if ($custom_url) {
        $form->addError($this->view->translate("Custom Url not available.Please select other."));
        return;
      }
    }
    $values = $form->getValues();
    if (strtotime($contest->starttime) > time()) {
      $starttime = isset($_POST['start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['start_date'] . ' ' . $_POST['start_time'])) : '';
      $endtime = isset($_POST['end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['end_date'] . ' ' . $_POST['end_time'])) : '';
      $joinStartTime = isset($_POST['join_start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['join_start_date'] . ' ' . $_POST['join_start_time'])) : '';
      $joinEndTime = isset($_POST['join_end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['join_end_date'] . ' ' . $_POST['join_end_time'])) : '';
      $votingStartTime = isset($_POST['voting_start_date']) ? date('Y-m-d H:i:s', strtotime($_POST['voting_start_date'] . ' ' . $_POST['voting_start_time'])) : '';
      $votingEndTime = isset($_POST['voting_end_date']) ? date('Y-m-d H:i:s', strtotime($_POST['voting_end_date'] . ' ' . $_POST['voting_end_time'])) : '';
      $resutTime = isset($_POST['result_date']) ? date('Y-m-d H:i:s', strtotime($_POST['result_date'] . ' ' . $_POST['result_time'])) : '';
      // Process
      $values['timezone'] = $_POST['timezone'] ? $_POST['timezone'] : '';
      $values['show_timezone'] = !empty($_POST['show_timezone']) ? $_POST['show_timezone'] : '0';
      $values['show_endtime'] = !empty($_POST['show_endtime']) ? $_POST['show_endtime'] : '0';
      $values['show_starttime'] = !empty($_POST['show_starttime']) ? $_POST['show_starttime'] : '0';
      if (empty($values['timezone'])) {
        $form->addError(Zend_Registry::get('Zend_Translate')->_('Timezone is a required field.'));
        return;
      }
      $error = Engine_Api::_()->sescontest()->dateValidations($_POST);
      if (isset($error[0]) && $error[0]) {
        $form->addError(Zend_Registry::get('Zend_Translate')->_($error[1]));
        return;
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
    }
    // Process
    $db = Engine_Api::_()->getItemTable('contest')->getAdapter();
    $db->beginTransaction();
    try {
      if (!($values['draft']))
        unset($values['draft']);
      $contest->setFromArray($values);
      $contest->save();
      $tags = preg_split('/[,]+/', $values['tags']);
      $contest->tags()->setTagMaps($viewer, $tags);
      if (!$values['vote_type'])
        $values['resulttime'] = '';
      $contest->save();

      // Add photo
      if (!empty($values['photo'])) {
        $contest->setPhoto($form->photo);
      }
      // Add cover photo
      if (!empty($values['cover'])) {
        $contest->setCover($form->cover);
      }
      // Set auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      if (empty($values['auth_view']))
        $values['auth_view'] = 'everyone';
      if (empty($values['auth_comment']))
        $values['auth_comment'] = 'everyone';
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
      $db->commit();
      //Start Activity Feed Work
      if (isset($values['draft']) && $contest->draft == 1 && $contest->is_approved == 1) {
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $contest, 'sescontest_create');
        if ($action) {
          $activityApi->attachActivity($action, $contest);
        }
      }
      //End Activity Feed Work
    } catch (Engine_Image_Exception $e) {
      $db->rollBack();
      $form->addError(Zend_Registry::get('Zend_Translate')->_('The image you selected was too large.'));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $db->beginTransaction();
    try {
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    // Redirect
    $this->_redirectCustom(array('route' => 'sescontest_dashboard', 'action' => 'edit', 'contest_id' => $contest->custom_url));
  }

  public function removeMainphotoAction() {
    //GET Contest ID AND ITEM
    $contest = Engine_Api::_()->core()->getSubject();
    $db = Engine_Api::_()->getDbTable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->photo_id = '';
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'mainphoto', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
  }

  public function backgroundphotoAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)) || !Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_bgphoto'))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Backgroundphoto();
    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->setBackgroundPhoto($_FILES['background'], 'background');
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'backgroundphoto', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
  }

  public function removeBackgroundphotoAction() {
    $contest = Engine_Api::_()->core()->getSubject();
    $contest->background_photo_id = 0;
    $contest->save();
    return $this->_helper->redirector->gotoRoute(array('action' => 'backgroundphoto', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
  }

  public function mainphotoAction() {

    if (!Engine_Api::_()->authorization()->isAllowed('contest', $this->view->viewer(), 'upload_mainphoto'))
      return $this->_forward('requireauth', 'error', 'core');

    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

    // Get form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Mainphoto();

    if (empty($contest->photo_id)) {
      $form->removeElement('remove');
    }

    if (!$this->getRequest()->isPost()) {
      return;
    }

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    // Uploading a new photo
    if ($form->Filedata->getValue() !== null) {
      $db = $contest->getTable()->getAdapter();
      $db->beginTransaction();

      try {
        $fileElement = $form->Filedata;
        $photo_id = Engine_Api::_()->sesbasic()->setPhoto($fileElement, false, false, 'sescontest', 'contest', '', $contest, true);
        $contest->photo_id = $photo_id;
        $contest->save();
        $db->commit();
      }
      // If an exception occurred within the image adapter, it's probably an invalid image
      catch (Engine_Image_Adapter_Exception $e) {
        $db->rollBack();
        $form->addError(Zend_Registry::get('Zend_Translate')->_('The uploaded file is not supported or is corrupt.'));
      }

      // Otherwise it's probably a problem with the database or the storage system (just throw it)
      catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  public function removePhotoAction() {
    //Get form
    $this->view->form = $form = new Sescontest_Form_Edit_RemovePhoto();

    if (!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
      return;

    $contest = Engine_Api::_()->core()->getSubject();
    $contest->photo_id = 0;
    $contest->save();

    $this->view->status = true;

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your photo has been removed.'))
    ));
  }

  //get seo detail
  public function overviewAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)) || !Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_overview'))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Overview();
    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->setFromArray($_POST);
      $contest->save();
      $db->commit();
      //Activity Feed Work
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $activityApi->addActivity($viewer, $contest, 'contest_editcontestoverview');
      if ($action) {
        $activityApi->attachActivity($action, $contest);
      }
    } catch (Exception $e) {
      $db->rollBack();
    }
  }

  //get seo detail
  public function seoAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)) || !Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contest_seo'))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Seo();

    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->setFromArray($_POST);
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
    }
  }

  //get style detail
  public function styleAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer) || $this->_helper->requireAuth()->setAuthParams(null, null, 'style')->isValid()))
      return;
    // Get current row
    $table = Engine_Api::_()->getDbtable('styles', 'core');
    $select = $table->select()
            ->where('type = ?', 'sescontest')
            ->where('id = ?', $contest->getIdentity())
            ->limit(1);
    $row = $table->fetchRow($select);
    // Create form
    $this->view->form = $form = new Sescontest_Form_Style();
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
      $row->type = 'sescontest';
      $row->id = $contest->getIdentity();
    }
    $row->style = $style;
    $row->save();
  }

  //get contest contact information
  public function contactInformationAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)) || !Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'contactinfo'))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Contactinformation();

    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->contest_contact_name = isset($_POST['contest_contact_name']) ? $_POST['contest_contact_name'] : '';
      $contest->contest_contact_email = isset($_POST['contest_contact_email']) ? $_POST['contest_contact_email'] : '';
      $contest->contest_contact_phone = isset($_POST['contest_contact_phone']) ? $_POST['contest_contact_phone'] : '';
      $contest->contest_contact_website = isset($_POST['contest_contact_website']) ? $_POST['contest_contact_website'] : '';
      $contest->contest_contact_facebook = isset($_POST['contest_contact_facebook']) ? $_POST['contest_contact_facebook'] : '';
      $contest->contest_contact_twitter = isset($_POST['contest_contact_twitter']) ? $_POST['contest_contact_twitter'] : '';
      $contest->contest_contact_linkedin = isset($_POST['contest_contact_linkedin']) ? $_POST['contest_contact_linkedin'] : '';
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      echo false;
      die;
    }
  }

  public function awardAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Award();

    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->setFromArray($_POST);
      $count = 0;
      if (!empty($_POST['award']))
        $count++;
      if (!empty($_POST['award2']))
        $count++;
      if (!empty($_POST['award3']))
        $count++;
      if (!empty($_POST['award4']))
        $count++;
      if (!empty($_POST['award5']))
        $count++;
      $contest->award_count = $count;
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
    }
  }

  public function rulesAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;
    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Rules();

    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    $db = Engine_Api::_()->getDbtable('contests', 'sescontest')->getAdapter();
    $db->beginTransaction();
    try {
      $contest->setFromArray($_POST);
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
    }
  }

  public function contactParticipantsAction() {
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($this->view->viewer())) || !Engine_Api::_()->authorization()->isAllowed('contest', $this->view->viewer(), 'contparticipant'))
      return;

    $this->view->participants = $participants = Engine_Api::_()->getDbTable('participants', 'sescontest')->getContestMembers($contest->getIdentity(), '');

    $this->sendMessage($participants, $contest, $is_ajax_content);
  }

  public function contactWinnersAction() {

    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($this->view->viewer())))
      return;
    $this->view->winners = Engine_Api::_()->getDbTable('participants', 'sescontest')->getContestMembers($contest->getIdentity(), 'winner');

    $this->sendMessage('', $contest, $is_ajax_content);
  }

  function sendMessage($participants, $contest, $is_ajax_content) {
    // Assign the composing stuff
    $composePartials = array();
    $prohibitedPartials = array('_composeTwitter.tpl', '_composeFacebook.tpl');
    foreach (Zend_Registry::get('Engine_Manifest') as $data) {
      if (empty($data['composer'])) {
        continue;
      }
      foreach ($data['composer'] as $type => $config) {
        // is the current user has "create" privileges for the current plugin
        if (isset($config['auth'], $config['auth'][0], $config['auth'][1])) {
          $isAllowed = Engine_Api::_()
                  ->authorization()
                  ->isAllowed($config['auth'][0], null, $config['auth'][1]);

          if (!empty($config['auth']) && !$isAllowed) {
            continue;
          }
        }
        if (!engine_in_array($config['script'][0], $prohibitedPartials)) {
          $composePartials[] = $config['script'];
        }
      }
    }
    $this->view->composePartials = $composePartials;

    // Create form
    $this->view->form = $form = new Sescontest_Form_Dashboard_Compose();
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;

    // Process
    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
    $db->beginTransaction();

    try {
      // Try attachment getting stuff
      $attachment = null;
      $attachmentData = $this->getRequest()->getParam('attachment');
      if (!empty($attachmentData) && !empty($attachmentData['type'])) {
        $type = $attachmentData['type'];
        $config = null;
        foreach (Zend_Registry::get('Engine_Manifest') as $data) {
          if (!empty($data['composer'][$type])) {
            $config = $data['composer'][$type];
          }
        }
        if ($config) {
          $plugin = Engine_Api::_()->loadClass($config['plugin']);
          $method = 'onAttach' . ucfirst($type);
          $attachment = $plugin->$method($attachmentData);
          $parent = $attachment->getParent();
          if ($parent->getType() === 'user') {
            $attachment->search = 0;
            $attachment->save();
          } else {
            $parent->search = 0;
            $parent->save();
          }
        }
      }

      $viewer = Engine_Api::_()->user()->getViewer();
      $values = $form->getValues();
      $actionName = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
      if ($actionName == 'contact-participants') {
        foreach ($participants as $participant)
          $userIds[] = $participant->user_id;
      } else
        $userIds = $_POST['winner'];

      $recipientsUsers = Engine_Api::_()->getItemMulti('user', $userIds);

      // Create conversation
      $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
              $viewer, $userIds, $values['title'], $values['body'], $attachment
      );

      // Send notifications
      foreach ($recipientsUsers as $user) {
        if ($user->getIdentity() == $viewer->getIdentity()) {
          continue;
        }

        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification(
                $user, $viewer, $conversation, 'message_new'
        );
      }

      // Increment messages counter
      Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');

      // Commit
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $_SESSION['show_message'] = 1;
    if ($actionName == 'contact-participants') {
      return $this->_helper->redirector->gotoRoute(array('action' => 'contact-participants', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
    } else {
      return $this->_helper->redirector->gotoRoute(array('action' => 'contact-winners', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
    }
  }

  //Entry controller data
  public function manageOrdersAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;


    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return $this->_forward('notfound', 'error', 'core');
  }

  //get payment to admin information
  public function paymentRequestsAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;
    $viewer = Engine_Api::_()->user()->getViewer();
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      if ($package) {
        $paramsDecoded = json_decode($package->params, true);
      }
      $this->view->thresholdAmount = $thresholdAmount = $paramsDecoded['sescontest_threshold_amount'];
    } else {
      $this->view->thresholdAmount = $thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_threamt');
    }

    $this->view->userGateway = Engine_Api::_()->getDbtable('usergateways', 'sescontestjoinfees')->getUserGateway(array('contest_id' => $contest->contest_id));
    $this->view->orderDetails = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getContestStats(array('contest_id' => $contest->contest_id));
    //get ramaining amount
    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'sescontestjoinfees')->getEventRemainingAmount(array('contest_id' => $contest->contest_id));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else
      $this->view->remainingAmount = $remainingAmount->remaining_payment;
    $this->view->isAlreadyRequests = Engine_Api::_()->getDbtable('userpayrequests', 'sescontestjoinfees')->getPaymentRequests(array('contest_id' => $contest->contest_id, 'isPending' => true));
    $this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'sescontestjoinfees')->getPaymentRequests(array('contest_id' => $contest->contest_id, 'isPending' => true));
  }

  //get user account details
  public function accountDetailsAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;
    $gateway_type = isset($_GET['gateway_type']) ? $_GET['gateway_type'] : "paypal";
    $userGateway = Engine_Api::_()->getDbtable('usergateways', 'sescontestjoinfees')->getUserGateway(array('contest_id' => $contest->contest_id, 'enabled' => true,'gateway_type'=>$gateway_type,));
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $userGatewayEnable = $settings->getSetting('sescontest.userGateway', 'paypal');
    if($gateway_type == "paypal") {
        $userGatewayEnable = 'paypal';
        $this->view->form = $form = new Sescontestjoinfees_Form_PayPal();
        $gatewayTitle = 'Paypal';
        $gatewayClass= 'Sescontestjoinfees_Plugin_Gateway_PayPal';
    } else if($gateway_type == "stripe") {
        $userGatewayEnable = 'stripe';
        $this->view->form = $form = new Sesadvpmnt_Form_Admin_Settings_Stripe();
        $gatewayTitle = 'Stripe';
        $gatewayClass= 'Sescontestjoinfees_Plugin_Gateway_Event_Stripe';
    } else if($gateway_type == "paytm") {
        $userGatewayEnable = 'paytm';
        $this->view->form = $form = new Epaytm_Form_Admin_Settings_Paytm();
        $gatewayTitle = 'Paytm';
        $gatewayClass= 'Sescontestjoinfees_Plugin_Gateway_Event_Paytm';
    } else if($gateway_type == "cashfree") {
        $userGatewayEnable = 'cashfree';
        $this->view->form = $form = new Ecashfree_Form_Admin_Settings_Cashfree();
        $gatewayTitle = 'Cashfree';
        $gatewayClass= 'Sescontestjoinfees_Plugin_Gateway_Event_Cashfree';
    }
    if (!empty($userGateway)) {
      $form->populate($userGateway->toArray());
      if (is_array($userGateway['config'])) {
        $form->populate($userGateway['config']);
      }
    }
    if (!$this->getRequest()->isPost())
      return;
    // Not post/invalid
    if (!$this->getRequest()->isPost() || $is_ajax_content)
      return;
    if (!$form->isValid($this->getRequest()->getPost()) || $is_ajax_content)
      return;
    // Process
    $values = $form->getValues();
    $enabled = (bool) $values['enabled'];
    unset($values['enabled']);
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $userGatewayTable = Engine_Api::_()->getDbtable('usergateways', 'sescontestjoinfees');
    // insert data to table if not exists
    try {
      if (empty($userGateway)) {
        $gatewayObject = $userGatewayTable->createRow();
        $gatewayObject->contest_id = $contest->contest_id;
        $gatewayObject->user_id = $viewer->getIdentity();
        $gatewayObject->title = $gatewayTitle;
        $gatewayObject->plugin = $gatewayClass;
        $gatewayObject->gateway_type = $gateway_type;
        $gatewayObject->save();
      } else {
        $gatewayObject = Engine_Api::_()->getItem("sescontestjoinfees_usergateway", $userGateway['usergateway_id']);
      }
      $db->commit();
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    // Validate gateway config
    if ($enabled) {
      $gatewayObjectObj = $gatewayObject->getGateway();
      try {
        $gatewayObjectObj->setConfig($values);
        $response = $gatewayObjectObj->test();
      } catch (Exception $e) {
        $enabled = false;
        $form->populate(array('enabled' => false));
        $form->addError(sprintf('Gateway login failed. Please double check ' .
                        'your connection information. The gateway has been disabled. ' .
                        'The message was: [%2$d] %1$s', $e->getMessage(), $e->getCode()));
      }
    } else {
      $form->addError('Gateway is currently disabled.');
    }
    // Process
    $message = null;
    try {
      $values = $gatewayObject->getPlugin()->processAdminGatewayForm($values);
    } catch (Exception $e) {
      $message = $e->getMessage();
      $values = null;
    }
    if (null !== $values) {
      $gatewayObject->setFromArray(array(
          'enabled' => $enabled,
          'config' => $values,
      ));
      //echo "asdf<pre>";var_dump($gatewayObject);die;
      $gatewayObject->save();
      $form->addNotice('Changes saved.');
    } else {
      $form->addError($message);
    }
  }

  //get paymnet detail
  public function detailPaymentAction() {
    $this->view->event = $contest = Engine_Api::_()->core()->getSubject();
    $this->view->item = $paymnetReq = Engine_Api::_()->getItem('sescontestjoinfees_userpayrequest', $this->getRequest()->getParam('id'));
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    if (!$this->_helper->requireAuth()->setAuthParams($event, null, 'edit')->isValid())
      return;

    if (!$paymnetReq) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to view.");
      return;
    }
  }

  public function paymentRequestAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;
    $viewer = Engine_Api::_()->user()->getViewer();

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      if ($package) {
        $paramsDecoded = json_decode($package->params, true);
      }
      $this->view->thresholdAmount = $thresholdAmount = $paramsDecoded['sescontest_threshold_amount'];
    } else {
      $this->view->thresholdAmount = $thresholdAmount = Engine_Api::_()->authorization()->getPermission($viewer, 'contest', 'contest_threamt');
    }


    //get remaining amount
    $remainingAmount = Engine_Api::_()->getDbtable('remainingpayments', 'sescontestjoinfees')->getEventRemainingAmount(array('contest_id' => $contest->contest_id));
    if (!$remainingAmount) {
      $this->view->remainingAmount = 0;
    } else {
      $this->view->remainingAmount = $remainingAmount->remaining_payment;
    }
    $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
    $orderDetails = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getContestStats(array('contest_id' => $contest->contest_id));
    $this->view->form = $form = new Sescontestjoinfees_Form_Paymentrequest();
    $value = array();
    $value['total_amount'] = Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($orderDetails['totalAmountSale'], $defaultCurrency);
    $value['total_commission_amount'] = Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($orderDetails['commission_amount'], $defaultCurrency);
    $value['remaining_amount'] = Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($remainingAmount->remaining_payment, $defaultCurrency);
    $value['requested_amount'] = round($remainingAmount->remaining_payment, 2);
    //set value to form
    if ($this->_getParam('id', false)) {
      $item = Engine_Api::_()->getItem('sescontestjoinfees_userpayrequest', $this->_getParam('id'));
      if ($item) {
        $itemValue = $item->toArray();
        //unset($value['requested_amount']);
        $value = array_merge($itemValue, $value);
      } else {
        return $this->_forward('requireauth', 'error', 'core');
      }
    }
    if (empty($_POST))
      $form->populate($value);

    if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
    if (@round($thresholdAmount, 2) > @round($remainingAmount->remaining_payment, 2) && empty($_POST)) {
      $this->view->message = 'Remaining amount is less than Threshold amount.';
      $this->view->errorMessage = true;
      return;
    } else if (isset($_POST['requested_amount']) && @round($_POST['requested_amount'], 2) > @round($remainingAmount->remaining_payment, 2)) {
      $form->addError('Requested amount must be less than or equal to remaining amount.');
      return;
    } else if (isset($_POST['requested_amount']) && @round($thresholdAmount) > @round($_POST['requested_amount'], 2)) {
      $form->addError('Requested amount must be greater than or equal to threshold amount.');
      return;
    }

    $db = Engine_Api::_()->getDbtable('userpayrequests', 'sescontestjoinfees')->getAdapter();
    $db->beginTransaction();
    try {
      $tableOrder = Engine_Api::_()->getDbtable('userpayrequests', 'sescontestjoinfees');
      if (isset($itemValue))
        $order = $item;
      else
        $order = $tableOrder->createRow();
      $order->requested_amount = round($_POST['requested_amount'], 2);
      $order->user_message = $_POST['user_message'];
      $order->contest_id = $contest->contest_id;
      $order->owner_id = $viewer->getIdentity();
      $order->user_message = $_POST['user_message'];
      $order->creation_date = date('Y-m-d h:i:s');
      $order->currency_symbol = $defaultCurrency;
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $userGatewayEnable = $settings->getSetting('sescontest.userGateway', 'paypal');
      $order->save();
      $db->commit();

      //Notification work
      $owner_admin = Engine_Api::_()->getItem('user', 1);
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner_admin, $viewer, $contest, 'sescontestjoinfees_contest_paymentrequest', array('requestAmount' => round($_POST['requested_amount'], 2)));
      //Payment request mail send to admin
      $contest_owner = $contest->getOwner();
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner_admin, 'sescontestjoinfees_entrypayment_requestadmin', array('contest_title' => $contest->title, 'object_link' => $contest->getHref(), 'contest_owner' => $contest_owner->getTitle(), 'host' => $_SERVER['HTTP_HOST']));

      $this->view->status = true;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your payment request has been successfully sent to Admin for approval.');
      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 1000,
                  'parentRefresh' => 1000,
                  'messages' => array($this->view->message)
      ));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  //delete payment request
  public function deletePaymentAction() {

    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $paymnetReq = Engine_Api::_()->getItem('sescontestjoinfees_userpayrequest', $this->getRequest()->getParam('id'));

    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$this->_helper->requireAuth()->setAuthParams($contest, null, 'delete')->isValid())
      return;

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');

    // Make form
    $this->view->form = $form = new Sesbasic_Form_Delete();
    $form->setTitle('Delete Payment Request?');
    $form->setDescription('Are you sure that you want to delete this payment request? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if (!$paymnetReq) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Paymnet request doesn't exists or not authorized to delete");
      return;
    }

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $db = $paymnetReq->getTable()->getAdapter();
    $db->beginTransaction();

    try {
      $paymnetReq->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Payment Request has been deleted.');
    return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array($this->view->message)
    ));
  }

  public function salesStatsAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;

    $this->view->todaySale = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getSaleStats(array('stats' => 'today', 'contest_id' => $contest->contest_id));
    $this->view->weekSale = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getSaleStats(array('stats' => 'week', 'contest_id' => $contest->contest_id));
    $this->view->monthSale = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getSaleStats(array('stats' => 'month', 'contest_id' => $contest->contest_id));

    //get getEventStats
    $this->view->eventStatsSale = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getContestStats(array('contest_id' => $contest->contest_id));
  }

  //get sales report
  public function salesReportsAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;

    $this->view->form = $form = new Sescontestjoinfees_Form_Searchsalereport();
    $value = array();
    if (isset($_GET['startdate']))
      $value['startdate'] = $value['start'] = date('Y-m-d', strtotime($_GET['startdate']));
    if (isset($_GET['enddate']))
      $value['enddate'] = $value['end'] = date('Y-m-d', strtotime($_GET['enddate']));
    if (isset($_GET['type']))
      $value['type'] = $_GET['type'];
    if (!engine_count($value)) {
      $value['enddate'] = date('Y-m-d', strtotime(date('Y-m-d')));
      $value['startdate'] = date('Y-m-d', strtotime('-30 days'));
      $value['type'] = $form->type->getValue();
    }
    if (isset($_GET['excel']) && $_GET['excel'] != '')
      $value['download'] = 'excel';
    if (isset($_GET['csv']) && $_GET['csv'] != '')
      $value['download'] = 'csv';
    $form->populate($value);
    $value['event_id'] = $contest->getIdentity();
    $this->view->eventSaleData = $data = Engine_Api::_()->getDbtable('orders', 'sescontestjoinfees')->getReportData($value);

    if (isset($value['download'])) {
      $name = str_replace(' ', '_', $contest->getTitle()) . '_' . time();
      switch ($value["download"]) {
        case "excel" :
          // Submission from
          $filename = $name . ".xls";
          header("Content-Type: application/vnd.ms-excel");
          header("Content-Disposition: attachment; filename=\"$filename\"");
          $this->exportFile($data);
          exit();
        case "csv" :
          // Submission from
          $filename = $name . ".csv";
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Content-type: text/csv");
          header("Content-Disposition: attachment; filename=\"$filename\"");
          header("Expires: 0");
          $this->exportCSVFile($data);
          exit();
        default :
          //silence
          break;
      }
    }
  }

  protected function exportCSVFile($records) {
    // create a file pointer connected to the output stream
    $fh = fopen('php://output', 'w');
    $heading = false;
    $counter = 1;
    if (!empty($records))
      foreach ($records as $row) {
        $valueVal['S.No'] = $counter;
        $valueVal['Date of Purchase'] = Engine_Api::_()->sescontestjoinfees()->dateFormat($row['creation_date']);
        $valueVal['Quatity'] = $row['total_orders'];
        //$valueVal['Commission Amount'] = Engine_Api::_()->sesevent()->getCurrencyPrice($row['commission_amount'],$defaultCurrency);
        $valueVal['Total Amount'] = Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($row['totalAmountSale'], $defaultCurrency);
        $counter++;
        if (!$heading) {
          // output the column headings
          fputcsv($fh, array_keys($valueVal));
          $heading = true;
        }
        // loop over the rows, outputting them
        fputcsv($fh, array_values($valueVal));
      }
    fclose($fh);
  }

  protected function exportFile($records) {
    $heading = false;
    $counter = 1;
    $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
    if (!empty($records))
      foreach ($records as $row) {
        $valueVal['S.No'] = $counter;
        $valueVal['Date of Purchase'] = Engine_Api::_()->sescontestjoinfees()->dateFormat($row['creation_date']);
        $valueVal['Quatity'] = $row['total_orders'];
        $valueVal['Total Amount'] = Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($row['totalAmountSale'], $defaultCurrency);
        $counter++;
        if (!$heading) {
          // display field/column names as a first row
          echo implode("\t", array_keys($valueVal)) . "\n";
          $heading = true;
        }
        echo implode("\t", array_values($valueVal)) . "\n";
      }
    exit;
  }

  public function createEntryFeesAction() {
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();

    $viewer = Engine_Api::_()->user()->getViewer();

    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;

    $this->view->form = $form = new Sescontestjoinfees_Form_Create();
    $form->populate($contest->toArray());
    if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    $contest->entry_fees = $_POST['entry_fees'];
    $contest->save();
    $form->addNotice("Entry fees saved successfully.");
  }

  public function paymentTransactionAction() {

    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;

    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;

    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();

    $viewer = Engine_Api::_()->user()->getViewer();

    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)))
      return;

    $this->view->paymentRequests = Engine_Api::_()->getDbtable('userpayrequests', 'sescontestjoinfees')->getPaymentRequests(array('contest_id' => $contest->contest_id, 'state' => 'both'));
  }

  public function currencyConverterAction() {
    //default currency
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency();
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    if ($is_ajax) {
      $curr = $this->_getParam('curr', 'USD');
      $val = $this->_getParam('val', '1');
      $currencyVal = $settings->getSetting('sesmultiplecurrency.' . $curr);
      echo round($currencyVal * $val, 2);
      die;
    }
    //currecy Array
    $fullySupportedCurrenciesExists = array();
    $fullySupportedCurrencies = Engine_Api::_()->sescontestjoinfees()->getSupportedCurrency();
    foreach ($fullySupportedCurrencies as $key => $values) {
      if ($settings->getSetting('sesmultiplecurrency.' . $key))
        $fullySupportedCurrenciesExists[$key] = $values;
    }
    $this->view->form = $form = new Sescontestjoinfees_Form_Conversion();
    $form->currency->setMultioptions($fullySupportedCurrenciesExists);
    $form->currency->setValue($defaultCurrency);
  }

  public function upgradeAction() {
    if (!$this->_helper->requireUser()->isValid())
      return;
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $this->view->contest = $sescontest = Engine_Api::_()->core()->getSubject();
    //current package
    if (!empty($sescontest->orderspackage_id)) {
      $this->view->currentPackage = Engine_Api::_()->getItem('sescontestpackage_orderspackage', $sescontest->orderspackage_id);
      if (!$this->view->currentPackage) {
        $this->view->currentPackage = Engine_Api::_()->getItem('sescontestpackage_package', $sescontest->package_id);
        $price = $this->view->currentPackage->price;
      } else {
        $price = Engine_Api::_()->getItem('sescontestpackage_package', $this->view->currentPackage->package_id)->price;
      }
    } else {
      $this->view->currentPackage = array();
      $price = 0;
    }
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    //get upgrade packages
    $this->view->upgradepackage = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getPackage(array('show_upgrade' => 1, 'member_level' => $viewer->level_id, 'not_in_id' => $sescontest->package_id, 'price' => $price));
  }

  public function juryMembersAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $is_ajax_content = $this->view->is_ajax_content = $this->_getParam('is_ajax_content', null) ? $this->_getParam('is_ajax_content') : false;
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    $isAllowedJury = 0;
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      if ($package) {
        $paramsDecoded = json_decode($package->params, true);
      }
      $isAllowedJury = $paramsDecoded['jury_member_count'];
      if (!$isAllowedJury)
        $isAllowedJury = 0;
    }
    else {
      $isAllowedJury = Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'can_add_jury');
      if (!$isAllowedJury)
        $isAllowedJury = 0;
    }
    if (!($this->_helper->requireAuth()->setAuthParams(null, null, 'edit')->isValid() || $contest->isOwner($viewer)) || !$isAllowedJury) {
      if (engine_count($_POST))
        die;
      return $this->_forward('requireauth', 'error', 'core');
    }
    if ($this->getRequest()->isPost()) {
      $linkPage = Engine_Api::_()->getItem('contest', $contest->contest_id);
      $pageOwner = Engine_Api::_()->getItem('user', $linkPage->user_id);

      $juryMemberTable = Engine_Api::_()->getDbTable('members', 'sescontestjurymember');
      $db = $juryMemberTable->getAdapter();
      $db->beginTransaction();
      try {
        $juryMember = $juryMemberTable->createRow();
        $juryMember->setFromArray(array(
            'user_id' => $_POST['user_id'],
            'contest_id' => $contest->contest_id));
        $juryMember->save();
        $db->commit();
        // return $this->_helper->redirector->gotoRoute(array('action' => 'jury-members', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
      } catch (Exception $e) {
        $db->rollBack();
      }
    }
    $this->view->canAddJury = $this->checkJuryLimit($contest, $viewer);
    $paginator = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->getContestJuryMemberPaginator(array('contest_id' => $contest->contest_id));
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  public function searchMemberAction() {
    $is_ajax = $this->view->is_ajax = $this->_getParam('is_ajax', null) ? $this->_getParam('is_ajax') : false;
    $sesdata = array();
    $contest = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();
    $canAddJury = $this->checkJuryLimit($contest, $viewer);
    if (!$canAddJury)
      return;
    $userTable = Engine_Api::_()->getItemTable('user');
    $juryMemberTable = Engine_Api::_()->getDbTable('members', 'sescontestjurymember');
    $select = $juryMemberTable->select()
            ->from($juryMemberTable->info('name'), 'user_id')
            ->where('contest_id =?', $contest->contest_id);
    $existJuryMember = $juryMemberTable->fetchAll($select)->toArray();
    if (engine_count($existJuryMember))
      $selectUserTable = $userTable->select()->where('displayname LIKE "%' . $this->_getParam('text', '') . '%"')->where('user_id NOT IN(?)', $existJuryMember);
    else
      $selectUserTable = $userTable->select()->where('displayname LIKE "%' . $this->_getParam('text', '') . '%"');
    $users = $userTable->fetchAll($selectUserTable);
    foreach ($users as $user) {
      $userIcon = $this->view->itemPhoto($user, 'thumb.icon');
      $sesdata[] = array(
          'id' => $user->user_id,
          'user_id' => $user->user_id,
          'label' => $user->getTitle(),
          'photo' => $userIcon
      );
    }
    return $this->_helper->json($sesdata);
  }

  public function checkJuryLimit($contest, $viewer) {
    $canAddJury = 1;
    $totalJuryMember = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->getJuryMembers(array('contest_id' => $contest->contest_id));
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      $package = Engine_Api::_()->getItem('sescontestpackage_package', $contest->package_id);
      if ($package) {
        $paramsDecoded = json_decode($package->params, true);
      }
      $juryMemberLimit = $paramsDecoded['jury_member_count'];
      if ($totalJuryMember >= $juryMemberLimit)
        $canAddJury = 0;
    }
    else {
      $juryMemberLimit = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'contest', 'juryMemberCount');
      if ($totalJuryMember >= $juryMemberLimit)
        $canAddJury = 0;
    }
    return $canAddJury;
  }

}
