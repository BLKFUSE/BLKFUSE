<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesstories_admin_main', array(), 'sesstories_admin_main_settings');
    
    // Check ffmpeg path for correctness
    if( function_exists('exec') ) {
      $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;

      $output = null;
      $return = null;
      if( !empty($ffmpeg_path) ) {
        exec($ffmpeg_path . ' -version', $output, $return);
      }
      // Try to auto-guess ffmpeg path if it is not set correctly
      $ffmpeg_path_original = $ffmpeg_path;
      if( empty($ffmpeg_path) || $return > 0 || stripos(join('', $output), 'ffmpeg') === false ) {
        $ffmpeg_path = null;
        // Windows
        if( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
          // @todo
        }
        // Not windows
        else {
          $output = null;
          $return = null;
          @exec('which ffmpeg', $output, $return);
          if( 0 == $return ) {
            $ffmpeg_path = array_shift($output);
            $output = null;
            $return = null;
            exec($ffmpeg_path . ' -version 2>&1', $output, $return);
            if ($output == null) {
              $ffmpeg_path = null;
            }
          }
        }
      }
      if( $ffmpeg_path != $ffmpeg_path_original ) {
        Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path = $ffmpeg_path;
      }
    }
    
    //$table_exist = $db->query('SHOW TABLES LIKE \'engine4_sesstories_stories\'')->fetch();
    //if($table_exist) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesstories.pluginactivated', 1);
    //}
    
    $this->view->form = $form = new Sesstories_Form_Admin_Settings_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      // Check ffmpeg path
      if( !empty($values['video_ffmpeg_path']) ) {
        if( function_exists('exec') ) {
          $ffmpeg_path = $values['video_ffmpeg_path'];
          $output = null;
          $return = null;
          exec($ffmpeg_path . ' -version', $output, $return);

          if( $return > 0 && $output != NULL ) {
            $form->video_ffmpeg_path->addError('FFMPEG path is not valid or does not exist');
            $values['video_ffmpeg_path'] = '';
          }
        } else {
          $form->video_ffmpeg_path->addError('The exec() function is not available. The ffmpeg path has not been saved.');
          $values['video_ffmpeg_path'] = '';
        }
      }
      //include_once APPLICATION_PATH . "/application/modules/Sesstories/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesstories.pluginactivated')) {
        foreach ($values as $key => $value) {
          if($value != '')
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
}
