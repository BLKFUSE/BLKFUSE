<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SettingsController.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Everification_SettingsController extends Core_Controller_Action_User
{
  protected $_user;

  public function init()
  {
    // Can specifiy custom id
    $id = $this->_getParam('id', null);
    $subject = null;
    if( null === $id )
    {
      $subject = Engine_Api::_()->user()->getViewer();
      Engine_Api::_()->core()->setSubject($subject);
    }
    else
    {
      $subject = Engine_Api::_()->getItem('user', $id);
      Engine_Api::_()->core()->setSubject($subject);
    }

    // Set up require's
    $this->_helper->requireUser();
    $this->_helper->requireSubject();
    $this->_helper->requireAuth()->setAuthParams($subject, null, 'edit');

    $contextSwitch = $this->_helper->contextSwitch;
    $contextSwitch->initContext();
  }

  public function manageAction() {
    // Render
    $this->_helper->content->setEnabled();
    $user = Engine_Api::_()->core()->getSubject();
    $this->view->user_id = $user_id = $user->getIdentity();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $this->view->documents = Engine_Api::_()->getDbTable('documents', 'everification')->getAllUserDocuments(array('user_id' => $user_id, 'fetchAll' => '1'));

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('documents', 'everification')->getAllUserDocuments(array('user_id' => $user_id));
    $page = $this->_getParam('page', 1);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($page);
  }

  public function deleteAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $document = Engine_Api::_()->getItem('everification_document', $this->getRequest()->getParam('document_id'));

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');

    $this->view->form = $form = new Everification_Form_Delete();

    if( !$document ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Document entry doesn't exist or you are not authorized to delete it.");
      return;
    }

    if( !$this->getRequest()->isPost() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

    $db = $document->getTable()->getAdapter();
    $db->beginTransaction();

    try {

      $storage = Engine_Api::_()->getItem('storage_file', $document->file_id);
      $storage->delete();
      $document->delete();

      $db->commit();

        $this->view->message = Zend_Registry::get('Zend_Translate')->_("Document deleted successfully.");
        $this->_forward('success', 'utility', 'core', array(
            'smoothboxClose' => true,
            'parentRefresh' => true,
            'messages' => array($this->view->message)
        ));
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }

  }
}
