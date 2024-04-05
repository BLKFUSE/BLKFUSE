<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AdminIndexController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_AdminIndexController extends Core_Controller_Action_Admin {

  public function indexAction() {
    if( !Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.site.url')) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('core.general.site.url', _ENGINE_SITE_URL);
    }
  }

  public function changeEnvironmentModeAction()
  {
    if ($this->getRequest()->isPost() && $this->_getParam('environment_mode', false)) {
      $global_settings_file = APPLICATION_PATH . '/application/settings/general.php';
      if (file_exists($global_settings_file)) {
          $g = include $global_settings_file;
          if (!is_array($g)) {
              $g = (array) $g;
          }
      } else {
          $g = array();
      }

      if (!is_writable($global_settings_file)) {
          // not writable; can we delete and re-create?
          if (is_writable(dirname($global_settings_file))) {
              @rename($global_settings_file, $global_settings_file.'_backup.php');
              @touch($global_settings_file);
              @chmod($global_settings_file, 0666);
              if (!file_exists($global_settings_file) || !is_writable($global_settings_file)) {
                  @rename($global_settings_file, $global_settings_file.'_delete.php');
                  @rename($global_settings_file.'_backup.php', $global_settings_file);
                  @unlink($global_settings_file.'_delete.php');
              }
          }
          if (!is_writable($global_settings_file)) {
              $this->view->success = false;
              $this->view->error   = 'Unable to write to settings file; please CHMOD 666 the file /application/settings/general.php, then try again.';
              return;
          } else {
              // it worked; continue.
          }
      }

      if ($this->_getParam('environment_mode') != @$g['environment_mode']) {
          $g['environment_mode'] = $this->_getParam('environment_mode');
          $file_contents  = "<?php defined('_ENGINE') or die('Access Denied'); return ";
          $file_contents .= var_export($g, true);
          $file_contents .= "; ?>";
          $this->view->success = @file_put_contents($global_settings_file, $file_contents);

          // clear scaffold cache
          Core_Model_DbTable_Themes::clearScaffoldCache();

          // Increment site counter
          $settings = Engine_Api::_()->getApi('settings', 'core');
          $settings->core_site_counter = $settings->core_site_counter + 1;

          return;
      } else {
          $this->view->message = 'No change necessary';
          $this->view->success = true; // no change
      }
    }
    $this->view->success = false;
  }
  
  public function flushPhotoAction() {
  
    $this->view->form = $form = new Core_Form_Admin_FlushPhotos();
    
    $this->_helper->layout->setLayout('admin-simple');
    
    if( !$this->getRequest()->isPost())
      return;

    try {
      $flushData = Engine_Api::_()->getDbTable('files', 'storage')->getFlushPhotoData();
      foreach($flushData as $item) {
        Engine_Api::_()->storage()->deleteExternalsFiles($item->file_id);
        $item->delete();
      }
    } catch(Exception $e) {
      //throw $e;
    }
    $this->view->message = Zend_Registry::get('Zend_Translate')->_("Unmapped photos remove successfully.");
    return 
    $this->_forward('success' ,'utility', 'core', array(
      'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'index'), 'admin_default', true),
      'messages' => Array($this->view->message)
    ));
  }
  
//   function thememodeAction() {
//     if(!engine_count($_POST)) {
//       echo false;die;
//     }
//     $mode = $this->_getParam('mode','');
//     
//     $_SESSION['adminmode_theme'] = $mode;
//     echo true;die;
//   }

  public function notesAction() {
    if( !Engine_Api::_()->getApi('settings', 'core')->getSetting('coreadmin.notes')) {
      $notes = Engine_Api::_()->getApi('settings', 'core')->setSetting('coreadmin.notes', $_POST['coreadmin_notes']);
    } else {
      $notes = Engine_Api::_()->getApi('settings', 'core')->setSetting('coreadmin.notes', $_POST['coreadmin_notes']);
    }
    echo json_encode(array('status' => true));die;
  }
}
