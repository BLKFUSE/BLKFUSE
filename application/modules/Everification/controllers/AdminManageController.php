<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Everification_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('everion_admin_main', array(), 'everification_admin_main_manage');

    $this->view->formFilter = $formFilter = new Everification_Form_Admin_Manage_Filter();

    $page = $this->_getParam('page', 1);

    $documentsTable = Engine_Api::_()->getDbTable('documents', 'everification');
    $documentsTableName = $documentsTable->info('name');

    $table = Engine_Api::_()->getDbtable('users', 'user');
    $tableName = $table->info('name');

    $select = $table->select()
              ->setIntegrityCheck(false)
              ->from($tableName, array('user_id', 'displayname', 'email'))
              ->join($documentsTableName, "$documentsTableName.user_id = $tableName.user_id", array('file_id', 'storage_path', 'verified', 'document_id', 'note'))
              ->where($documentsTableName. '.submintoadmin =?', '1');

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
        'order' => 'document_id',
        'order_direction' => 'DESC',
            ), $values);
    $this->view->assign($values);

    //Set up select info
    $select->order((!empty($values['order']) ? $values['order'] : 'document_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if (!empty($values['displayname']))
      $select->where($tableName.'.displayname LIKE ?', '%' . $values['displayname'] . '%');

    if (!empty($values['username']))
      $select->where($tableName.'.username LIKE ?', '%' . $values['username'] . '%');

    if (!empty($values['email']))
      $select->where($tableName.'.email LIKE ?', '%' . $values['email'] . '%');

    if (!empty($values['user_id']))
      $select->where($tableName.'.user_id = ?', (int) $values['user_id']);

    // Filter out junk
    $valuesCopy = array_filter($values);

    if (isset($_GET['verified']) && $_GET['verified'] != '' && $_GET['verified'] == '1')
      $select->where($documentsTableName .'.verified = ?', '1');
    else if (isset($_GET['verified']) && $_GET['verified'] != '' && $_GET['verified'] == '0')
        $select->where($documentsTableName .'.verified IN (?)', array('0', '2'));

    if (isset($_GET['rejected']) && $_GET['rejected'] != '' && $_GET['rejected'] == '1')
      $select->where($documentsTableName .'.verified = ?', '2');
    else if (isset($_GET['rejected']) && $_GET['rejected'] != '' && $_GET['rejected'] == '0')
        $select->where($documentsTableName .'.verified IN (?)', array('0', '1'));

    if (isset($_GET['pending']) && $_GET['pending'] != '' && $_GET['pending'] == '1') {
      $select->where($documentsTableName .'.submintoadmin = ?', $values['pending'])->where($documentsTableName .'.verified = ?', '0');
    } else if (isset($_GET['pending']) && $_GET['pending'] != '' && $_GET['pending'] == '0') {
        $select->where($documentsTableName .'.submintoadmin = ?', '1')->where($documentsTableName .'.verified IN (?)', array('1', '2'));
    }
    $select->order($documentsTableName.'.document_id DESC');

    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
    $this->view->hideEmails = _ENGINE_ADMIN_NEUTER;
  }

  public function multiModifyAction()
  {
    if( $this->getRequest()->isPost() ) {
      $values = $this->getRequest()->getPost();
      $viewer = Engine_Api::_()->user()->getViewer();
      foreach ($values as $key=>$value) {
        if( $key == 'modify_' . $value ) {
          $document = Engine_Api::_()->getItem('everification_document', (int) $value);
          $user = Engine_Api::_()->getItem('user', $document->user_id);
          if( $values['submit_button'] == 'delete' ) {
              $document->delete();
          } else if( $values['submit_button'] == 'approve' ) {

            $document->verified = 1;
            $document->save();

            Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'everi_verified');

            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'notify_everi_verified', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
          } else if( $values['submit_button'] == 'reject' ) {

            $document->verified = '2';
            $document->save();

            Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'everi_reject');

            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'notify_everi_reject', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
          }
        }
      }
    }

    return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }

  public function verifiedAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('document_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('everification_document', $id);
      $item->verified = !$item->verified;
      $item->save();
    }
    $this->_redirect('admin/everification/manage');
  }

  public function rejectAction() {

    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $document_id = $this->_getParam('id');

    $document = Engine_Api::_()->getItem('everification_document', $document_id);
    $documentsTable = Engine_Api::_()->getDbtable('documents', 'everification');
    $user = Engine_Api::_()->getItem('user', $document->user_id);
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-manage/reject.tpl');
      return;
    }

    // Process
    $db = $documentsTable->getAdapter();
    $db->beginTransaction();
    try {
        $document->verified = '2';
        if(!empty($_POST['note'])) {
            $document->note = $_POST['note'];
        }
        $document->save();

        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'everi_reject');

        Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'notify_everi_reject', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh'=> 10,
      'messages' => array('You have successfully rejected document verification.')
    ));
  }


  public function verifieddocumentAction() {

    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $document_id = $this->_getParam('id');

    $this->view->enable = $enable = $this->_getParam('enable', 0);
    $this->view->user_id = $user_id = $this->_getParam('user_id', 0);

    $document = Engine_Api::_()->getItem('everification_document', $document_id);
    $documentsTable = Engine_Api::_()->getDbtable('documents', 'everification');
    $user = Engine_Api::_()->getItem('user', $document->user_id);
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-manage/verifieddocument.tpl');
      return;
    }

    // Process
    $db = $documentsTable->getAdapter();
    $db->beginTransaction();

    try {

      $document->verified = 1;
      $document->save();

      if($enable) {
        $user = Engine_Api::_()->getItem('user', $user_id);
        $user->enabled = 1;
        $user->approved = 1;
        $user->save();
      }

      Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'everi_verified');

      Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'notify_everi_verified', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));

      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh'=> 10,
      'messages' => array('')
    ));
  }

  public function noteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $document_id = $this->_getParam('id');
    $this->view->document = Engine_Api::_()->getItem('everification_document', $document_id);

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-manage/note.tpl');
      return;
    }
  }

  public function deletedocumentAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $document_id = $this->_getParam('id');
    $documentTable = Engine_Api::_()->getDbtable('documents', 'everification');
    $document = Engine_Api::_()->getItem('everification_document', $document_id);

    if( !$document ) {
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh'=> 10,
        'messages' => array('')
      ));
    }

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-manage/deletedocument.tpl');
      return;
    }

    // Process
    $db = $documentTable->getAdapter();
    $db->beginTransaction();

    try {
      $storage = Engine_Api::_()->getItem('storage_file', $document->file_id);
      $storage->delete();
      $document->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh'=> 10,
      'messages' => array('You have successfully deleted document.')
    ));
  }

  public function deletedocumentsAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $document_id = $this->_getParam('id');
    $documentTable = Engine_Api::_()->getDbtable('documents', 'everification');
    $document = Engine_Api::_()->getItem('everification_document', $document_id);

    if( !$document ) {
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh'=> 10,
        'messages' => array('')
      ));
    }

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-manage/deletedocuments.tpl');
      return;
    }

    // Process
    $db = $documentTable->getAdapter();
    $db->beginTransaction();

    try {
      $storage = Engine_Api::_()->getItem('storage_file', $document->file_id);
      $storage->delete();
      $document->file_id = '0';
      $document->storage_path = '';
      $document->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh'=> 10,
      'messages' => array('You have successfully deleted document.')
    ));
  }
}
