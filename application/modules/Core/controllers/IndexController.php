<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: IndexController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_IndexController extends Core_Controller_Action_Standard {

  public function indexAction()
  {
    if( Engine_Api::_()->user()->getViewer()->getIdentity() )
    {
        return $this->_helper->redirector->gotoRoute(array('action' => 'home'), 'user_general', true);
    }

    // check public settings
    if( !Engine_Api::_()->getApi('settings', 'core')->core_general_portal &&
        !$this->_helper->requireUser()->isValid() ) {
        return;
    }

    // Render
    $this->_helper->content
        ->setNoRender()
        ->setEnabled()
    ;
  }
  
  public function inboxAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('messages_conversation')->getInboxPaginator($viewer);
    $paginator->setCurrentPageNumber($this->_getParam('page'));
    $paginator->setItemCountPerPage(10);
    $this->view->unread = Engine_Api::_()->messages()->getUnreadMessageCount($viewer);
  }
  
  public function deleteMessageAction() {

    $message_id = $this->getRequest()->getParam('id');
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $db = Engine_Api::_()->getDbtable('messages', 'messages')->getAdapter();
    $db->beginTransaction();
    try {
      $recipients = Engine_Api::_()->getItem('messages_conversation', $message_id)->getRecipientsInfo();
      foreach ($recipients as $r) {
        if ($viewer_id == $r->user_id) {
          $this->view->deleted_conversation_ids[] = $r->conversation_id;
          $r->inbox_deleted = true;
          $r->outbox_deleted = true;
          $r->save();
        }
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollback();
      throw $e;
    }
  }
  
  public function markAllReadMessagesAction() {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    Engine_Api::_()->getDbtable('recipients', 'messages')->update(array('inbox_read' => 1), array('`user_id` = ?' => $viewer_id));
  }
  
  public function donotsellinfoAction() {
  
    $donotsellinfo = $this->_getParam('donotsellinfo', 0);
    $donotsellinfo = (int)($donotsellinfo === 'true');
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer->donotsellinfo = $donotsellinfo;
    $viewer->save();
    
    if($viewer->donotsellinfo == 1) {
      echo json_encode(array('status' => 'true', 'error' => ''));die;
    } else {
      echo json_encode(array('status' => 'false', 'error' => ''));die;
    }
  }
  
  public function adminmenutypeAction() {
    $value = $this->_getParam('value', 'horizontal');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('core.menutype', $value);
    echo json_encode(array('status' => 'true', 'error' => '', 'value' => 1));die;
  }
  
  public function showadmincontentAction() {
  
    $showcontent = $this->_getParam('showcontent', 0);
    $value = $this->_getParam('value');
    $showcontent = (int)($showcontent === 'true');
    
    if($value == 1) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('core.storelisting', $showcontent);
      $contentval = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.storelisting');
    } else {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('core.newsupdates', $showcontent);
      $contentval = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.newsupdates');
    }
    if($contentval == 1) {
      echo json_encode(array('status' => 'true', 'error' => '', 'value' => 1));die;
    } else {
      echo json_encode(array('status' => 'false', 'error' => '', 'value' => 1));die;
    }

  }
  
  public function showprivatemessageAction() {
  
    $showcontent = $this->_getParam('showcontent', 0);
    $value = $this->_getParam('value');
    $showcontent = (int)($showcontent === 'true');

    Engine_Api::_()->getApi('settings', 'core')->setSetting('core.privatemessage', $showcontent);
    $contentval = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.privatemessage');
    
    if($contentval == 1) {
      echo json_encode(array('status' => 'true', 'error' => '', 'value' => 1));die;
    } else {
      echo json_encode(array('status' => 'false', 'error' => '', 'value' => 1));die;
    }

  }
  
  public function uploadPhotoAction() {

    $viewer = Engine_Api::_()->user()->getViewer();

    $this->_helper->layout->disableLayout();

    if( !$this->_helper->requireUser()->checkRequire() ) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Max file size limit exceeded (probably).');
      return;
    }

    if(!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    
    if(!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid Upload');
      return;
    }
    
    $info = $_FILES['userfile'];
    $storage = Engine_Api::_()->getItemTable('storage_file');
    
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      if(!empty($info['name'])) {
        $storageObject = $storage->createFile($info, array(
          'parent_id' => $viewer->getIdentity(),
          'parent_type' => 'core_wysiwygphotos',
          'user_id' => $viewer->getIdentity(),
        ));
        // Remove temporary file
        @unlink($info['tmp_name']);
      }
      $db->commit();
      echo json_encode(array('location' => $storageObject->map()));die;
    } catch( Exception $e ) {
      $db->rollBack();
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('An error occurred.');
      throw $e;
      return;
    }
  }

  public function subcategoryAction() {

    $category_id = $this->_getParam('category_id', null);
    $CategoryType = $this->_getParam('type', null);
    $selected = $this->_getParam('selected', null);
    if ($category_id) {
      $categoryTable = Engine_Api::_()->getDbtable('categories', 'core');
      $category_select = $categoryTable->select()
                                      ->from($categoryTable->info('name'))
                                      ->where('subcat_id = ?', $category_id);
      $subcategory = $categoryTable->fetchAll($category_select);
      $count_subcat = engine_count($subcategory->toarray());

      $data = '';
      if ($subcategory && $count_subcat) {
        if ($CategoryType == 'search') {
          $data .= '<option value="0">' . Zend_Registry::get('Zend_Translate')->_("Choose 2nd Level Category") . '</option>';
          foreach ($subcategory as $category) {
            $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
          }
        } else {
          $data .= '<option value=""></option>';
          foreach ($subcategory as $category) {
            $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
          }

        }
      }
    } else
      $data = '';
    echo $data;die;
  }

  public function subsubcategoryAction() {

    $category_id = $this->_getParam('subcategory_id', null);
    $CategoryType = $this->_getParam('type', null);
    $selected = $this->_getParam('selected', null);
    if ($category_id) {
      $categoryTable = Engine_Api::_()->getDbtable('categories', 'core');
      $category_select = $categoryTable->select()
        ->from($categoryTable->info('name'))
        ->where('subsubcat_id = ?', $category_id);
      $subcategory = $categoryTable->fetchAll($category_select);
      $count_subcat = engine_count($subcategory->toarray());

      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }

      }
    } else
      $data = '';
    echo $data;
    die;
  }
  
  function fontAction() {
    if(!engine_count($_POST)){
      echo false;die;
    }
    $font = $this->_getParam('size','');
    $_SESSION['font_theme'] = $font;
    echo true;die;
  }
  
  function modeAction() {
    if(!engine_count($_POST)){
      echo false;die;
    }
    $mode = $this->_getParam('mode','');
    $theme = $this->_getParam('theme','elpis');
    $_SESSION['mode_theme'] = $mode;
    echo true;die;
  }
}
