<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Everification_IndexController extends Core_Controller_Action_Standard {

  public function uploadDocumentAction() {

    $document_id = $this->_getParam('document_id', 0);

    $this->_helper->layout->setLayout('default-simple');
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $this->view->form = $form = new Everification_Form_Upload();

    if($document_id) {
      $document = Engine_Api::_()->getItem('everification_document', $document_id);
      $form->populate($document->toArray());
    }

    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    if ($this->getRequest()->isPost()) {
      $values = $form->getValues();

      if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {

        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($form->file, array(
          'parent_id' => $viewer_id,
          'parent_type' => 'userdocverification',
          'user_id' => $viewer_id,
        ));
        // Remove temporary file
        @unlink($file['tmp_name']);

        if($document_id) {
          $document = Engine_Api::_()->getItem('everification_document', $document_id);
          $document->file_id = $filename->file_id;
          $document->storage_path = $filename->storage_path;
          $document->submintoadmin = '0';
          $document->save();
        } else {
          $table = Engine_Api::_()->getDbTable('documents', 'everification');
          $document = $table->createRow();
          $document->user_id = $viewer_id;
          $document->file_id = $filename->file_id;
          $document->storage_path = $filename->storage_path;
          $document->submintoadmin = '0';
          $document->save();
          $document->submintoadmin = '1';
          $document->save();

          $allAdmins = Engine_Api::_()->everification()->getAdminnSuperAdmins();
          foreach ($allAdmins as $admin) {

            $user = Engine_Api::_()->getItem('user', $admin['user_id']);

            Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'everi_superadmin');

            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'notify_everi_superadmin', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
          }
        }
      } else if($document_id) {
        $document = Engine_Api::_()->getItem('everification_document', $document_id);
        //$document->file_id = $filename->file_id;
        //$document->storage_path = $filename->storage_path;
        $document->submintoadmin = '0';
        $document->save();
      }


      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('Document Uploaded Successfully.')
      ));
    }
  }
}
