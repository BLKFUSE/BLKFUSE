<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_manage');
    $this->view->formFilter = $formFilter = new Sescontest_Form_Admin_Filter();
    $this->view->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
    $this->view->subcat_id = isset($_GET['subcat_id']) ? $_GET['subcat_id'] : 0;
    $this->view->subsubcat_id = isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : 0;
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $contest = Engine_Api::_()->getItem('contest', $value);
          $contest->delete();
        }
      }
    }
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array(
        'order' => isset($_GET['order']) ? $_GET['order'] : '',
        'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',
            ), $values);
    $this->view->assign($values);
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestsTableName = $contestTable->info('name');
    $select = $contestTable->select()
            ->setIntegrityCheck(false)
            ->from($contestsTableName)
            ->joinLeft($tableUserName, "$contestsTableName.user_id = $tableUserName.user_id", 'username')
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'contest_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));
    if (!empty($_GET['name']))
      $select->where($contestsTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

    if (!empty($_GET['category_id']))
      $select->where($contestsTableName . '.category_id =?', $_GET['category_id']);

    if (!empty($_GET['subcat_id']))
      $select->where($contestsTableName . '.subcat_id =?', $_GET['subcat_id']);

    if (!empty($_GET['subsubcat_id']))
      $select->where($contestsTableName . '.subsubcat_id =?', $_GET['subsubcat_id']);

    if (isset($_GET['featured']) && $_GET['featured'] != '')
      $select->where($contestsTableName . '.featured = ?', $_GET['featured']);

    if (isset($_GET['sponsored']) && $_GET['sponsored'] != '')
      $select->where($contestsTableName . '.sponsored = ?', $_GET['sponsored']);

    if (isset($_GET['verified']) && $_GET['verified'] != '')
      $select->where($contestsTableName . '.verified = ?', $_GET['verified']);

    if (isset($_GET['contest_type']) && $_GET['contest_type'] != '')
      $select->where($contestsTableName . '.contest_type = ?', $_GET['contest_type']);

    if (isset($_GET['package_id']) && $_GET['package_id'] != '')
      $select->where($contestsTableName . '.package_id = ?', $_GET['package_id']);

    if (isset($_GET['hot']) && $_GET['hot'] != '')
      $select->where($contestsTableName . '.hot = ?', $_GET['hot']);

    if (isset($_GET['offtheday']) && $_GET['offtheday'] != '')
      $select->where($contestsTableName . '.offtheday = ?', $_GET['offtheday']);

    if (isset($_GET['is_approved']) && $_GET['is_approved'] != '')
      $select->where($contestsTableName . '.is_approved = ?', $_GET['is_approved']);

    if (!empty($_GET['creation_date']))
      $select->where($contestsTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams['query'][$urlParamsKey] = $urlParamsVal;
    }
    $this->view->urlParams = $urlParams;
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  public function entriesAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_manageentries');
    $this->view->formFilter = $formFilter = new Sescontest_Form_Admin_Entry_Filter();
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $contest = Engine_Api::_()->getItem('participant', $value);
          $contest->delete();
        }
      }
    }
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array(
        'order' => isset($_GET['order']) ? $_GET['order'] : '',
        'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',
            ), $values);
    $this->view->assign($values);
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestsTableName = $contestTable->info('name');
    $participantTable = Engine_Api::_()->getDbTable('participants', 'sescontest');
    $participantTableName = $participantTable->info('name');
    $select = $participantTable->select()
            ->setIntegrityCheck(false)
            ->from($participantTableName)
            ->joinLeft($tableUserName, "$participantTableName.owner_id = $tableUserName.user_id", 'username')
            ->joinLeft($contestsTableName, "$participantTableName.contest_id = $contestsTableName.contest_id", array('category_id'))
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'participant_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));
    if (!empty($_GET['name']))
      $select->where($participantTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['contest_title']))
      $select->where($contestsTableName . '.title LIKE ?', '%' . $_GET['contest_title'] . '%');

    if (!empty($_GET['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

    if (isset($_GET['media']) && $_GET['media'] != '')
      $select->where($participantTableName . '.media = ?', $_GET['media']);

    if (!empty($_GET['category_id']))
      $select->where($contestsTableName . '.category_id =?', $_GET['category_id']);

    if (!empty($_GET['subcat_id']))
      $select->where($contestsTableName . '.subcat_id =?', $_GET['subcat_id']);

    if (!empty($_GET['subsubcat_id']))
      $select->where($contestsTableName . '.subsubcat_id =?', $_GET['subsubcat_id']);

    if (isset($_GET['rank']) && $_GET['rank'] != '')
      $select->where($participantTableName . '.rank = ?', $_GET['rank']);

    if (isset($_GET['offtheday']) && $_GET['offtheday'] != '')
      $select->where($participantTableName . '.offtheday = ?', $_GET['offtheday']);

    if (!empty($_GET['creation_date']))
      $select->where($participantTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams['query'][$urlParamsKey] = $urlParamsVal;
    }
    $this->view->urlParams = $urlParams;
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(50);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  public function mediaAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_media_manage');
    $mediaTable = Engine_Api::_()->getDbTable('medias', 'sescontest');
    $selectTable = $mediaTable->select()
            ->from($mediaTable->info('name'), array('*'));
    $this->view->mediaTypes = $mediaTable->fetchAll($selectTable);
  }
  
  public function orderAction() {

    $table = Engine_Api::_()->getDbtable('slidephotos', 'sescontest');
    $results = $table->fetchAll($table->select());
    
    $orders = $this->getRequest()->getParam('order');
    $i = 0;
    foreach ($results as $result) {
      $order = $orders[$i];
      if(engine_in_array($order, $orders)) {
        $order = explode('_', $order);
        $order = $order[1];
        
        if (!$order)
          $order = 999;
        $result->order = $order;
        $result->save();
        $i++;
      }
    }
    return;
  }

  public function deleteAction() {
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sescontest_id = $id = $this->_getParam('id');
    $type = $this->_getParam('type');
    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();

    if ($type == 'contest') {
      $item = Engine_Api::_()->getItem('contest', $id);
      $form->setTitle('Delete Contest?');
      $form->setDescription('Are you sure that you want to delete this contest? It will not be recoverable after being deleted.');
      $form->submit->setLabel('Delete');
    } elseif ($type == 'participant') {
      $item = Engine_Api::_()->getItem('participant', $id);
      $form->setTitle('Delete Contest?');
      $form->setDescription('Are you sure that you want to delete this entry? It will not be recoverable after being deleted.');
      $form->submit->setLabel('Delete');
    }

    //Check post
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $item->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have successfully deleted this entry.')
      ));
    }
  }

  //Featured Action
  public function featuredAction() {
    $contest_id = $this->_getParam('id');
    if (!empty($contest_id)) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      $contest->featured = !$contest->featured;
      $contest->save();
    }
    if (isset($_SERVER['HTTP_REFERER']))
      $url = $_SERVER['HTTP_REFERER'];
    else
      $url = 'admin/sescontest/manage';
    $this->_redirect($url);
  }

  //Enabled Media Type Action
  public function enabledAction() {
    $media_id = $this->_getParam('id');
    if (!empty($media_id)) {
      $media = Engine_Api::_()->getItem('sescontest_media', $media_id);
      $media->enabled = !$media->enabled;
      $media->save();
    }
    if (isset($_SERVER['HTTP_REFERER']))
      $url = $_SERVER['HTTP_REFERER'];
    else
      $url = 'admin/sescontest/manage/media';
    $this->_redirect($url);
  }

  //Sponsored Action
  public function sponsoredAction() {
    $contest_id = $this->_getParam('id');
    if (!empty($contest_id)) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      $contest->sponsored = !$contest->sponsored;
      $contest->save();
    }
    if (isset($_SERVER['HTTP_REFERER']))
      $url = $_SERVER['HTTP_REFERER'];
    else
      $url = 'admin/sescontest/manage';
    $this->_redirect($url);
  }

  //Verify Action
  public function verifyAction() {
    $contest_id = $this->_getParam('id');
    if (!empty($contest_id)) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      $contest->verified = !$contest->verified;
      $contest->save();
    }
    if (isset($_SERVER['HTTP_REFERER']))
      $url = $_SERVER['HTTP_REFERER'];
    else
      $url = 'admin/sescontest/manage';
    $this->_redirect($url);
  }

  //Verify Action
  public function hotAction() {
    $contest_id = $this->_getParam('id');
    if (!empty($contest_id)) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      $contest->hot = !$contest->hot;
      $contest->save();
    }
    if (isset($_SERVER['HTTP_REFERER']))
      $url = $_SERVER['HTTP_REFERER'];
    else
      $url = 'admin/sescontest/manage';
    $this->_redirect($url);
  }

  public function ofthedayAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $type = $this->_getParam('type');
    $param = $this->_getParam('param');

    $this->view->form = $form = new Sescontest_Form_Admin_Oftheday();
    if ($type == 'contest') {
      $item = Engine_Api::_()->getItem('contest', $id);
      $form->setTitle("Contest of the Day");
      $form->setDescription('Here, choose the start date and end date for this contest to be displayed as "Contest of the Day".');
      if (!$param)
        $form->remove->setLabel("Remove as Contest of the Day");
      $table = 'engine4_sescontest_contests';
      $item_id = 'contest_id';
    }
    elseif ($type == 'participant') {
      $item = Engine_Api::_()->getItem('participant', $id);
      $form->setTitle("Entry of the Day");
      $form->setDescription('Here, choose the start date and end date for this entry to be displayed as "Entry of the Day".');
      if (!$param)
        $form->remove->setLabel("Remove as Entry of the Day");
      $table = 'engine4_sescontest_participants';
      $item_id = 'participant_id';
    }
    if (!empty($id))
      $form->populate($item->toArray());

    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost())) {
        return;
      }
      $values = $form->getValues();

      $start = strtotime($values['startdate']);
      $end = strtotime($values['enddate']);

      $values['startdate'] = date('Y-m-d', $start);
      $values['enddate'] = date('Y-m-d', $end);

      $db->update($table, array('startdate' => $values['startdate'], 'enddate' => $values['enddate']), array("$item_id = ?" => $id));
      if (@$values['remove']) {
        $db->update($table, array('offtheday' => 0), array("$item_id = ?" => $id));
      } else {
        $db->update($table, array('offtheday' => 1), array("$item_id = ?" => $id));
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
  }

  public function viewAction() {
    $this->view->item = Engine_Api::_()->getItem('contest', $this->_getParam('id', null));
  }

  public function entryViewAction() {
    $this->view->item = Engine_Api::_()->getItem('participant', $this->_getParam('id', null));
  }

  public function uploadBannerAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('default-simple');
    $id = $this->_getParam('id');
    $this->view->form = $form = new Sescontest_Form_Admin_Banner();
    $media = Engine_Api::_()->getItem('sescontest_media', $id);

    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost())) {
        return;
      }
      try {

        if ($media->banner && $_FILES['banner']['name'] != '') {
          $banner = Engine_Api::_()->getItem('storage_file', $media->banner);
          if ($banner)
            $banner->delete();
          $media->banner = 0;
          $media->save();
        }

        $media = Engine_Api::_()->getItem('sescontest_media', $id);
        if (empty($media->banner) && isset($_FILES['banner']['name']) && $_FILES['banner']['name'] != '') {
          $media->banner = $this->setPhoto($_FILES['banner'], $id, true);
          $media->save();
        }
        $db->commit();
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => 10,
            'parentRefresh' => 10,
            'messages' => array('')
        ));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  protected function setPhoto($photo, $media_id, $resize = false) {

    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $fileName = $file;
    } else if ($photo instanceof Storage_Model_File) {
      $file = $photo->temporary();
      $fileName = $photo->name;
    } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
      $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
      $file = $tmpRow->temporary();
      $fileName = $tmpRow->name;
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $fileName = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $fileName = $photo;
    } else {
      throw new User_Model_Exception('invalid argument passed to setPhoto');
    }

    if (!$fileName) {
      $fileName = $file;
    }

    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sescontest_media',
        'parent_id' => $media_id,
        'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        'name' => $fileName,
    );

    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    if ($resize) {
      // RESIZE IMAGE (MAIN)
      $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_poster.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(1600, 1600)
              ->write($mainPath)
              ->destroy();
      //REIZE IMAGE (NORMAL) MAKE SOME IMAGE FOR ACTIVITY FEED SO IT OPEN IN POP UP WITH OUT JUMP EFFECT.
      $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_thumb.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(500, 500)
              ->write($normalPath)
              ->destroy();
    } else {
      $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_poster.' . $extension;
      copy($file, $mainPath);
    }

    if ($resize) {
      //NORMAL MAIN IMAZE RESIZE
      $normalMainPath = $path . DIRECTORY_SEPARATOR . $base . '_icon.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(100, 100)
              ->write($normalMainPath)
              ->destroy();
    } else {
      $normalMainPath = $path . DIRECTORY_SEPARATOR . $base . '_icon.' . $extension;
      copy($file, $normalMainPath);
    }

    //STORE
    try {
      $iMain = $filesTable->createFile($mainPath, $params);
      if ($resize) {
        $iIconNormal = $filesTable->createFile($normalPath, $params);
        $iMain->bridge($iIconNormal, 'thumb.thumb');
      }
      $iNormalMain = $filesTable->createFile($normalMainPath, $params);
      $iMain->bridge($iNormalMain, 'thumb.icon');
    } catch (Exception $e) {
      //REMOVE TEMP FILES
      @unlink($mainPath);
      if ($resize) {
        @unlink($normalPath);
      }
      @unlink($normalMainPath);
      // THROW
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Sescontest_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    //REMOVE TEMP FILES
    @unlink($mainPath);
    if ($resize) {
      @unlink($normalPath);
    }
    @unlink($normalMainPath);
    // Update row
    // Delete the old file?
    if (!empty($tmpRow)) {
      $tmpRow->delete();
    }
    return $iMain->file_id;
  }

  //Approved Action
  public function approvedAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $contest_id = $this->_getParam('id');
    if (!empty($contest_id)) {
      $contest = Engine_Api::_()->getItem('contest', $contest_id);
      $contest->is_approved = !$contest->is_approved;
      $contest->save();
      if ($contest->is_approved) {
        $mailType = 'sescontest_approved_contest';
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $getActivity = $activityApi->getActionsByObject($contest);
        if (!engine_count($getActivity)) {
          $action = $activityApi->addActivity($contest->getOwner(), $contest, 'sescontest_create');
          if ($action) {
            $activityApi->attachActivity($action, $contest);
          }
        }
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($contest->getOwner(), $viewer, $contest, 'sescontest_approved_contest');
      } else {
        $mailType = 'sescontest_disapproved_contest';
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($contest->getOwner(), $viewer, $contest, 'sescontest_disapproved_contest');
      }
      //Contest approved mail send to contest owner
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($contest->getOwner(), $mailType, array('member_name' => $contest->getOwner()->getTitle(), 'contest_title' => $contest->title, 'object_link' => $contest->getHref(), 'host' => $_SERVER['HTTP_HOST']));
    }
    $this->_redirect('admin/sescontest/manage');
  }

}
