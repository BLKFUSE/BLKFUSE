<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminManageController.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_manage');

    $this->view->formFilter = $formFilter = new Sesmember_Form_Admin_Manage_Filter();
    $page = $this->_getParam('page', 1);

    $table = Engine_Api::_()->getDbtable('users', 'user');
    $tableName = $table->info('name');

    $userinfoTableName = Engine_Api::_()->getDbtable('userinfos', 'sesmember')->info('name');

    $select = $table->select()
                    ->from($table->info('name'))
                    ->setIntegrityCheck(false)
                    ->joinLeft($userinfoTableName, "$userinfoTableName.user_id = $tableName.user_id",array('userinfo_id', 'follow_count', 'location', 'rating', 'user_verified', 'cool_count', 'funny_count', 'useful_count', 'featured', 'sponsored', 'vip', 'offtheday', 'starttime', 'endtime', 'adminpicks', 'order'));

    // Process form
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array(
        'order' => 'user_id',
        'order_direction' => 'DESC',
            ), $values);
    $this->view->assign($values);

    //Set up select info
    $select->order((!empty($values['order']) ? $values['order'] : 'user_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if (!empty($values['displayname']))
      $select->where('displayname LIKE ?', '%' . $values['displayname'] . '%');

    if (!empty($values['username']))
      $select->where('username LIKE ?', '%' . $values['username'] . '%');

    if (!empty($values['email']))
      $select->where('email LIKE ?', '%' . $values['email'] . '%');

    if (!empty($values['level_id']))
      $select->where('level_id = ?', $values['level_id']);

    if (isset($values['enabled']) && $values['enabled'] != -1)
      $select->where('enabled = ?', $values['enabled']);

    if (!empty($values['user_id']))
      $select->where('user_id = ?', (int) $values['user_id']);

    // Filter out junk
    $valuesCopy = array_filter($values);
    // Reset enabled bit
    if (isset($values['enabled']) && $values['enabled'] == 0) {
      $valuesCopy['enabled'] = 0;
    }

    if (isset($_GET['featured']) && $_GET['featured'] != '')
      $select->where($userinfoTableName.'.featured = ?', $values['featured']);

    if (isset($_GET['vip']) && $_GET['vip'] != '')
      $select->where($userinfoTableName.'.vip = ?', $values['vip']);

    if (isset($_GET['sponsored']) && $_GET['sponsored'] != '')
      $select->where($userinfoTableName.'.sponsored = ?', $values['sponsored']);

    if (isset($_GET['offtheday']) && $_GET['offtheday'] != '')
      $select->where($userinfoTableName.'.offtheday =?', $values['offtheday']);

    if (isset($_GET['rating']) && $_GET['rating'] != '') {
      if ($_GET['rating'] == 1):
        $select->where($userinfoTableName.'.rating != ?', 0);
      elseif ($_GET['rating'] == 0 && $_GET['rating'] != ''):
        $select->where($userinfoTableName.'.rating =?', 0);
      endif;
    }

    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
    $this->view->hideEmails = _ENGINE_ADMIN_NEUTER;
  }

  public function managePageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_homepage');
    $this->view->pages = Engine_Api::_()->getDbTable('homepages', 'sesmember')->getHomepages('home');
  }


  public function manageProfileAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_profilepage');
    $this->view->pages = Engine_Api::_()->getDbTable('homepages', 'sesmember')->getHomepages('profile');
  }

  public function manageProfilePhotoAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_profilephoto');
    $this->view->pages = Engine_Api::_()->getDbTable('profilephotos', 'sesmember')->getProfilePhotos();
  }

  //VIP Action
  public function vipAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('user_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('user', $id);
      $iteminfo = Engine_Api::_()->getItem('sesmember_userinfo', $this->_getParam('userinfo_id'));
      $iteminfo->vip = !$iteminfo->vip;
      $iteminfo->save();
    }
    if ($iteminfo->vip == 0) {
      Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmember_vipmember", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
    } else {
      $owner = $item->getOwner();
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesmember_vipmember');
    }
    $this->_redirect('admin/sesmember/manage');
  }

  //Featured Action
  public function featuredAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('user_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('user', $id);
      $iteminfo = Engine_Api::_()->getItem('sesmember_userinfo', $this->_getParam('userinfo_id'));
      $iteminfo->featured = !$iteminfo->featured;
      $iteminfo->save();
    }
    if ($iteminfo->featured == 0) {
      Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmember_featuredmember", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
    } else {
      $owner = $item->getOwner();
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesmember_featuredmember');
    }
    $this->_redirect('admin/sesmember/manage');
  }

  public function userVerifiedAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('user_id');
    if (!empty($id)) {
      $iteminfo = Engine_Api::_()->getItem('sesmember_userinfo', $this->_getParam('userinfo_id'));
      $item = Engine_Api::_()->getItem('user', $id);
      $iteminfo->user_verified = !$iteminfo->user_verified;
      $iteminfo->save();
    }
    $this->_redirect('admin/sesmember/manage');
  }

  //Sponsored Action
  public function sponsoredAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('user_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('user', $id);
      $iteminfo = Engine_Api::_()->getItem('sesmember_userinfo', $this->_getParam('userinfo_id'));
      $iteminfo->sponsored = !$iteminfo->sponsored;
      $iteminfo->save();
    }
    if ($iteminfo->sponsored == 0) {
      Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmember_sponsoredmember", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
    } else {
      $owner = $item->getOwner();
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesmember_sponsoredmember');
    }
    $this->_redirect('admin/sesmember/manage');
  }

  public function ofthedayAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $this->view->form = $form = new Sesmember_Form_Admin_Oftheday();
    $item = Engine_Api::_()->getItem('user', $id);
    $iteminfo = Engine_Api::_()->getItem('sesmember_userinfo', $this->_getParam('userinfo_id'));
    if (!empty($id))
      $form->populate($iteminfo->toArray());

    if ($this->getRequest()->isPost()) {

      if (!$form->isValid($this->getRequest()->getPost()))
        return;

      $values = $form->getValues();
      $values['starttime'] = date('Y-m-d', strtotime($values['starttime']));
      $values['endtime'] = date('Y-m-d', strtotime($values['endtime']));

      $db->update('engine4_sesmember_userinfos', array('starttime' => $values['starttime'], 'endtime' => $values['endtime']), array("user_id = ?" => $id));
      if (isset($values['remove']) && $values['remove']) {
        $db->update('engine4_sesmember_userinfos', array('offtheday' => 0), array("user_id = ?" => $id));
      } else {
        $db->update('engine4_sesmember_userinfos', array('offtheday' => 1), array("user_id = ?" => $id));
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
  }

  public function statsAction() {

    $this->view->user = $user = Engine_Api::_()->getItem('user', $this->_getParam('id', null));

    $fieldsByAlias = Engine_Api::_()->fields()->getFieldsObjectsByAlias($user);

    if (!empty($fieldsByAlias['profile_type'])) {
      $optionId = $fieldsByAlias['profile_type']->getValue($user);
      if ($optionId) {
        $optionObj = Engine_Api::_()->fields()
                ->getFieldsOptions($user)
                ->getRowMatching('option_id', $optionId->value);
        if ($optionObj) {
          $this->view->memberType = $optionObj->label;
        }
      }
    }

    // Networks
    $select = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfSelect($user)->where('hide = ?', 0);
    $this->view->networks = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll($select);

    // Friend count
    $this->view->friendCount = $user->membership()->getMemberCount($user);
  }

  public function createAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_homepage');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Create();

    // If not post or form not valid, return
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $pageHomeTable = Engine_Api::_()->getDbTable('homepages', 'sesmember');
    $values = $form->getValues();
    $menuTitle = $_POST['title'];
    $db = Engine_Db_Table::getDefaultAdapter();
    $db = $pageHomeTable->getAdapter();
    $db->beginTransaction();

    try {
      $homePage = $pageHomeTable->createRow();
      $values['member_levels'] = json_encode($values['member_levels']);
      $homePage->type = 'home';
      $homePage->setFromArray($values);
      $homePage->save();
      $homePageId = $homePage->homepage_id;
      $page_id = Engine_Api::_()->sesmember()->checkPage('sesmember_index_' . $homePageId, $menuTitle, 'home');
      $homePage->page_id = $page_id;
      $homePage->save();
      // Commit
      $db->commit();
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage-page'));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editAction() {

    $this->view->homepage_id = $homePageId = $this->_getParam('id');
    $homePage = Engine_Api::_()->getItem('sesmember_homepage', $homePageId);

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_homepage');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Edit();
    $homePage['member_levels'] = json_decode($homePage['member_levels']);
    // Populate form
    $form->populate($homePage->toArray());

    // Check post/form
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      $values['member_levels'] = json_encode($values['member_levels']);
      $homePage->setFromArray($values);
      $homePage->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage-page'));
  }

  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->homepage_id = $id = $this->_getParam('id');

    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle('Delete This Page?');
    $form->setDescription('Are you sure that you want to delete this page entry? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $fixedPage = Engine_Api::_()->getItem('sesmember_homepage', $id);
        $pageName = "sesmember_index_$id";
        if (!empty($pageName)) {

          $page_id = $db->select()
                  ->from('engine4_core_pages', 'page_id')
                  ->where('name = ?', $pageName)
                  ->limit(1)
                  ->query()
                  ->fetchColumn();
          Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id =?' => $page_id));
          Engine_Api::_()->getDbTable('pages', 'core')->delete(array('page_id =?' => $page_id));
        }

        $fixedPage->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have been deleted row successfully.')
      ));
    }
  }

  public function createProfileAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_profilepage');

    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Createprofilepage();

    // If not post or form not valid, return
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $pageHomeTable = Engine_Api::_()->getDbTable('homepages', 'sesmember');
    $values = $form->getValues();
    $menuTitle = $_POST['title'];
    $db = Engine_Db_Table::getDefaultAdapter();
    $db = $pageHomeTable->getAdapter();
    $db->beginTransaction();

    try {
      $homePage = $pageHomeTable->createRow();
      $values['member_levels'] = json_encode($values['member_levels']);
      $homePage->type = 'profile';
      $homePage->setFromArray($values);
      $homePage->save();
      $homePageId = $homePage->homepage_id;
      $page_id = Engine_Api::_()->sesmember()->checkPage('sesmember_index_' . $homePageId, $menuTitle, 'profile');
      $homePage->page_id = $page_id;
      $homePage->save();
      // Commit
      $db->commit();
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage-profile'));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editProfileAction() {

    $this->view->homepage_id = $homePageId = $this->_getParam('id');
    $homePage = Engine_Api::_()->getItem('sesmember_homepage', $homePageId);

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_profilepage');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Editprofilepage();
    $homePage['member_levels'] = json_decode($homePage['member_levels']);
    // Populate form
    $form->populate($homePage->toArray());

    // Check post/form
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      $values['member_levels'] = json_encode($values['member_levels']);
      $homePage->setFromArray($values);
      $homePage->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage-profile'));
  }

  public function deleteProfileAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->homepage_id = $id = $this->_getParam('id');

    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle('Delete This Page?');
    $form->setDescription('Are you sure that you want to delete this page entry? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $fixedPage = Engine_Api::_()->getItem('sesmember_homepage', $id);
        $pageName = "sesmember_index_$id";
        if (!empty($pageName)) {

          $page_id = $db->select()
                  ->from('engine4_core_pages', 'page_id')
                  ->where('name = ?', $pageName)
                  ->limit(1)
                  ->query()
                  ->fetchColumn();
          Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id =?' => $page_id));
          Engine_Api::_()->getDbTable('pages', 'core')->delete(array('page_id =?' => $page_id));
        }

        $fixedPage->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have been deleted row successfully.')
      ));
    }
  }


  public function manageBrowsepageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_browsememberspage');
    $this->view->pages = Engine_Api::_()->getDbTable('homepages', 'sesmember')->getHomepages('browse');
  }

  public function createBrowseAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_browsememberspage');

    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Createbrowsepage();

    // If not post or form not valid, return
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $pageHomeTable = Engine_Api::_()->getDbTable('homepages', 'sesmember');
    $values = $form->getValues();
    $menuTitle = $_POST['title'];
    $db = Engine_Db_Table::getDefaultAdapter();
    $db = $pageHomeTable->getAdapter();
    $db->beginTransaction();

    try {
      $homePage = $pageHomeTable->createRow();
      $values['member_levels'] = '["'.$values['member_levels'].'"]';
      $homePage->type = 'browse';
      $homePage->setFromArray($values);
      $homePage->save();
      $homePageId = $homePage->homepage_id;
      $page_id = Engine_Api::_()->sesmember()->checkPage('sesmember_index_' . $homePageId, $menuTitle, 'browse');
      $homePage->page_id = $page_id;
      $homePage->save();

      $homePageId = $homePage->homepage_id;
      $routeName = 'sesmember_index_' . $homePageId;
      $menuName = 'sesmember_main_' . $homePageId;
      $db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ('" . $menuName . "', 'sesmember', '" . $menuTitle . "', 'Sesmember_Plugin_Menus::canViewProfileTypepage', '{\"route\":\"$routeName\", \"homepage_id\":\"$homePageId\"}', 'sesmember_main', '', 999)");
      // Commit
      $db->commit();
      return $this->_helper->redirector->gotoRoute(array('action' => 'manage-browsepage', 'id' => 0));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }


  public function editBrowseAction() {

    $this->view->homepage_id = $homePageId = $this->_getParam('id');
    $homePage = Engine_Api::_()->getItem('sesmember_homepage', $homePageId);

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_browsememberspage');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Editbrowsepage();
    $homePage['member_levels'] = json_decode($homePage['member_levels']);
    // Populate form
    $form->populate($homePage->toArray());

    // Check post/form
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      $values['member_levels'] = '["'.$homePage['member_levels'][0].'"]';
      $homePage->setFromArray($values);
      $homePage->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage-browsepage', 'id' => 0));
  }

  public function deleteBrowseAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->homepage_id = $id = $this->_getParam('id');

    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle('Delete This Page?');
    $form->setDescription('Are you sure that you want to delete this page entry? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $fixedPage = Engine_Api::_()->getItem('sesmember_homepage', $id);
        $pageName = "sesmember_index_$id";
        if (!empty($pageName)) {

          $page_id = $db->select()
                  ->from('engine4_core_pages', 'page_id')
                  ->where('name = ?', $pageName)
                  ->limit(1)
                  ->query()
                  ->fetchColumn();
          Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id =?' => $page_id));
          Engine_Api::_()->getDbTable('pages', 'core')->delete(array('page_id =?' => $page_id));

          Engine_Api::_()->getDbtable('menuItems', 'core')->delete(array('name =?' => "sesmember_main_$id"));
        }

        $fixedPage->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have been deleted row successfully.')
      ));
    }
  }

  public function reviewSettingsAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_reviewsettings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main_review', array(), 'sesmember_admin_main_reviewparametersettings');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_ReviewSettings();
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function manageReviewsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_reviewsettings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main_review', array(), 'sesmember_admin_main_managereview');

    $this->view->formFilter = $formFilter = new Sesmember_Form_Admin_Manage_Review_Filter();

    //Process form
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    foreach ($_GET as $key => $value) {
      if ('' === $value)
        unset($_GET[$key]);
      else
        $values[$key] = $value;
    }

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          Engine_Api::_()->getItem('sesmember_review', $value)->delete();
        }
      }
    }

    $table = Engine_Api::_()->getDbtable('reviews', 'sesmember');
    $tableName = $table->info('name');
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $select = $table->select()
                    ->from($tableName)
                    ->setIntegrityCheck(false)
                    ->joinLeft($tableUserName, "$tableUserName.user_id = $tableName.owner_id", 'username')->order('review_id DESC');

    // Set up select info
    if (!empty($_GET['title']))
      $select->where($tableName . '.title LIKE ?', '%' . $values['title'] . '%');

    if (isset($_GET['featured']) && $_GET['featured'] != '')
      $select->where($tableName . '.featured = ?', $values['featured']);

    if (isset($_GET['new']) && $_GET['new'] != '')
      $select->where('new = ?', $values['new']);

    if (isset($_GET['verified']) && $_GET['verified'] != '')
      $select->where($tableName . '.verified = ?', $values['verified']);

    if (!empty($values['creation_date']))
      $select->where('date(' . $tableName . '.creation_date) = ?', $values['creation_date']);

    if (!empty($_GET['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

    if (isset($_GET['oftheday']) && $_GET['oftheday'] != '')
      $select->where($tableName . '.oftheday =?', $values['oftheday']);

    $page = $this->_getParam('page', 1);

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($page);
  }

  public function levelSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_reviewsettings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main_review', array(), 'sesmember_admin_main_levelsettings');

    //Get level id
    if (null !== ($id = $this->_getParam('level_id', $this->_getParam('id'))))
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    else
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();

    if (!$level instanceof Authorization_Model_Level)
      throw new Engine_Exception('missing level');

    $id = $level->level_id;

    //Make form
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_Level(array(
        'public' => ( engine_in_array($level->type, array('public')) ),
        'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($id);

    $content_type = 'sesmember_review';
    $module_name = $this->_getParam('module_name', null);

    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $form->populate($permissionsTable->getAllowed($content_type, $id, array_keys($form->getValues())));

    //Check post
    if (!$this->getRequest()->isPost())
      return;

    //Check validitiy
    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    //Process
    $values = $form->getValues();

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      //Set permissions
      $permissionsTable->setAllowed($content_type, $id, $values);
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }

  public function reviewParameterAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_reviewsettings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main_review', array(), 'sesmember_admin_main_reviewparameter');

    //START PROFILE TYPE WORK
    $optionsData = Engine_Api::_()->getApi('core', 'fields')->getFieldsOptions('user');
    $mapData = Engine_Api::_()->getApi('core', 'fields')->getFieldsMaps('user');
    // Get top level fields
    $topLevelMaps = $mapData->getRowsMatching(array('field_id' => 0, 'option_id' => 0));
    $topLevelFields = array();
    foreach ($topLevelMaps as $map) {
      $field = $map->getChild();
      $topLevelFields[$field->field_id] = $field;
    }
    $topLevelField = array_shift($topLevelFields);
    $topLevelOptions = array();
    foreach ($optionsData->getRowsMatching('field_id', $topLevelField->field_id) as $option) {
      $topLevelOptions[$option->option_id] = $option->label;
    }
    $this->view->topLevelOptions = $topLevelOptions;

    //END PROFILE TYPE WORK
  }

  public function addParameterAction() {
    $profile_id = $this->_getParam('id', null);
    if (!$profile_id)
      return $this->_forward('notfound', 'error', 'core');
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->form = $form = new Sesmember_Form_Admin_Parameter_Add();
    $reviewParameters = Engine_Api::_()->getDbtable('parameters', 'sesmember')->getParameterResult(array('profile_type' => $profile_id));
    if (!engine_count($reviewParameters))
      $form->setTitle('Add Review Parameters');
    else {
      $form->setTitle('Edit Review Parameters');
      $form->submit->setLabel('Edit');
    }
    $form->setDescription("");
    if (!$this->getRequest()->isPost()) {
      return;
    }
    $table = Engine_Api::_()->getDbtable('parameters', 'sesmember');
    $tablename = $table->info('name');
    try {
      $values = $form->getValues();
      unset($values['addmore']);
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      $deleteIds = explode(',', $_POST['deletedIds']);
      foreach ($deleteIds as $val) {
        if (!$val)
          continue;
        $query = 'DELETE FROM ' . $tablename . ' WHERE parameter_id = ' . $val;
        $dbObject->query($query);
      }
      foreach ($_POST as $key => $value) {
        if (engine_count(explode('_', $key)) != 3 || !$value)
          continue;
        $id = str_replace('sesmember_review_', '', $key);
        $query = 'UPDATE ' . $tablename . ' SET title = "' . $value . '" WHERE parameter_id = ' . $id;
        $dbObject->query($query);
      }
      foreach ($_POST['parameters'] as $key => $val) {
        if ($_POST['parameters'][$key] != '') {
          $query = 'INSERT IGNORE INTO ' . $tablename . ' (`parameter_id`, `profile_type`, `title`, `rating`) VALUES ("","' . $profile_id . '","' . $val . '","0")';
          $dbObject->query($query);
        }
      }
    } catch (Exception $e) {
      throw $e;
    }
    $this->view->message = Zend_Registry::get('Zend_Translate')->_("Review Parameters have been saved.");
    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array($this->view->message)
    ));
  }

  public function createProfilePhotoAction() {

    $this->view->form = $form = new Sesmember_Form_Admin_Manage_CreateprofilePhoto();

    // If not post or form not valid, return
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    // Process
    $profilephotos = Engine_Api::_()->getDbTable('profilephotos', 'sesmember');
    $values = $form->getValues();
    $db = Engine_Db_Table::getDefaultAdapter();
    $db = $profilephotos->getAdapter();
    $db->beginTransaction();

    try {
      $row = $profilephotos->createRow();
      $row->setFromArray($values);
      $row->save();

      if (!empty($values['photo'])) {
        $row->setPhoto($form->photo);
      }

      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('You have successfully upload default photo for selected profile type.')
    ));
  }

  public function editProfilePhotoAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->form = $form = new Sesmember_Form_Admin_Manage_EditprofilePhoto();
    $profiletype_id = $this->_getParam('id');
    $profilephoto = Engine_Api::_()->getItem('sesmember_profilephoto', $profiletype_id);
    $form->populate($profilephoto->toArray());

    if (!$this->getRequest()->isPost())
      return;

    $values = $form->getValues();
    if (empty($values['profiletype_id']))
      unset($values['profiletype_id']);

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $profilephoto->save();
      if (isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
        $previousCatIcon = $profilephoto->photo_id;
        $Photo = $profilephoto->setPhoto($form->photo);
        if (!empty($Photo->file_id)) {
          if ($previousCatIcon) {
            $storagephotos = Engine_Api::_()->getItem('storage_file', $previousCatIcon);
            $storagephotos->delete();
          }
          $profilephoto->photo_id = $Photo->file_id;
          $profilephoto->save();
        }
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('You have successfully edit default photo of selected profile type.')
    ));
  }

  public function deleteProfilePhotoAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->homepage_id = $id = $this->_getParam('id');

    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle('Delete Default Profile Photo?');
    $form->setDescription('Are you sure that you want to delete this default profile photo? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $profilephotos = Engine_Api::_()->getItem('sesmember_profilephoto', $id);
        $profilephotos->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have been deleted row successfully.')
      ));
    }
  }

  public function viewAction() {
    $this->view->item = Engine_Api::_()->getItem('sesmember_review', $this->_getParam('id', null));
  }
 public function deleteReviewAction() {
   $this->_helper->layout->setLayout('admin-simple');
    $this->view->review_id = $review_id = Engine_Api::_()->getItem('sesmember_review', $this->_getParam('id', null));
     $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle('Do you want to delete review?');
    $form->setDescription('Are you sure that you want to delete this review? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $reviewData = Engine_Api::_()->getItem('sesmember_review', $review_id);
        $reviewData->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have been deleted row successfully.')
      ));
    }

  }
  public function featuredReviewAction() {

    $id = $this->_getParam('review_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('sesmember_review', $id);
      $item->featured = !$item->featured;
      $item->save();
    }
    $this->_redirect('admin/sesmember/manage/manage-reviews');
  }

  public function verifiedReviewAction() {

    $id = $this->_getParam('review_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('sesmember_review', $id);
      $item->verified = !$item->verified;
      $item->save();
    }
    $this->_redirect('admin/sesmember/manage/manage-reviews');
  }

  public function reviewOfthedayAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->_helper->layout->setLayout('admin-simple');

    $id = $this->_getParam('id');

    $this->view->form = $form = new Sesmember_Form_Admin_Oftheday();
    $form->setTitle('Review of the Day');
    $form->setDescription('Here, choose the start date and end date for this review to be displayed as "Review of the Day".');

    $param = Zend_Controller_Front::getInstance()->getRequest()->getParam('param');
    if(!$param)
     $form->remove->setLabel('Remove as Review of the Day');
    $item = Engine_Api::_()->getItem('sesmember_review', $id);

    if (!empty($id))
      $form->populate($item->toArray());

    if ($this->getRequest()->isPost()) {

      if (!$form->isValid($this->getRequest()->getPost()))
        return;

      $values = $form->getValues();
      $values['starttime'] = date('Y-m-d', strtotime($values['starttime']));
      $values['endtime'] = date('Y-m-d', strtotime($values['endtime']));

      $db->update('engine4_sesmember_reviews', array('starttime' => $values['starttime'], 'endtime' => $values['endtime']), array("review_id = ?" => $id));
      if (isset($values['remove']) && $values['remove']) {
        $db->update('engine4_sesmember_reviews', array('oftheday' => 0), array("review_id = ?" => $id));
      } else {
        $db->update('engine4_sesmember_reviews', array('oftheday' => 1), array("review_id = ?" => $id));
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
  }

}
