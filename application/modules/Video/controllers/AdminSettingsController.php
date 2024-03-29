<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AdminSettingsController.php 9792 2012-09-28 21:27:57Z pamela $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Video_AdminSettingsController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('video_admin_main', array(), 'video_admin_main_settings');

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

    // Make form
    $this->view->form = $form = new Video_Form_Admin_Global();

    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      $iframelyDisallow = Engine_Api::_()->getApi('settings', 'core')->getSetting('video_iframely_disallow');
      if( !empty($iframelyDisallow) ) {
        $form->video_iframely_disallow->setValue(join(', ', $iframelyDisallow));
      }
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

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
    $videoIframelyDisallow = $values['video_iframely_disallow'];
    unset($values['video_iframely_disallow']);
    if( Engine_Api::_()->getApi('settings', 'core')->hasSetting('video_iframely_disallow') ) {
      Engine_Api::_()->getApi('settings', 'core')->removeSetting('video_iframely_disallow');
    }
    if( !empty($videoIframelyDisallow) ) {
      $videoIframelyDisallow = explode(',', $videoIframelyDisallow);
      $domains = array();
      foreach( $videoIframelyDisallow as $disallowUrl ) {
        $disallowUrl = trim($disallowUrl);
        if( empty($disallowUrl) ) {
          continue;
        }
        $domains[] = $disallowUrl;
      }
      Engine_Api::_()->getApi('settings', 'core')->setSetting('video_iframely_disallow', $domains);
      $form->video_iframely_disallow->setValue(join(', ', $domains));
    }
    // Okay, save
    foreach( $values as $key => $value ) {
      Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
    }
    $form->addNotice('Your changes have been saved.');
  }

  public function levelAction()
  {
    // Make navigation
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('video_admin_main', array(), 'video_admin_main_level');

    // Get level id
    if( null !== ($id = $this->_getParam('id')) ) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }

    if( !$level instanceof Authorization_Model_Level ) {
      throw new Engine_Exception('missing level');
    }

    $level_id = $id = $level->level_id;

    // Make form
    $this->view->form = $form = new Video_Form_Admin_Settings_Level(array(
      'public' => ( engine_in_array($level->type, array('public')) ),
      'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($id);

    // Populate values
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $form->populate($permissionsTable->getAllowed('video', $id, array_keys($form->getValues())));

    // Check post
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    // Check validitiy
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process

    $values = $form->getValues();

    // Form elements with NonBoolean values
    $nonBooleanSettings = $form->nonBooleanFields();

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();

    try
    {
      // Set permissions
      $permissionsTable->setAllowed('video', $id, $values, '', $nonBooleanSettings);

      // Commit
      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }

  public function utilityAction()
  {
    if( defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER ) {
      return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    }

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('video_admin_main', array(), 'video_admin_main_utility');

    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
    if( function_exists('shell_exec') ) {
      // Get version
      $this->view->version = $version
          = shell_exec(escapeshellcmd($ffmpeg_path) . ' -version 2>&1');
      $command = "$ffmpeg_path -formats 2>&1";
      $this->view->format = $format
          = shell_exec(escapeshellcmd($ffmpeg_path) . ' -formats 2>&1')
          . shell_exec(escapeshellcmd($ffmpeg_path) . ' -codecs 2>&1');
    }

    /*
    // Get files in admin uploads
    $it = new DirectoryIterator(APPLICATION_PATH . DIRECTORY_SEPARATOR
        . 'public' . DIRECTORY_SEPARATOR . 'admin');
    $testFiles = array();
    foreach( $it as $fileinfo ) {
      if( $fileinfo->isFile() ) {
        $testFiles[$fileinfo->getFilename()] = $fileinfo->getFilename();
      }
    }
    $this->view->testFiles = $testFiles;
     * 
     */
  }

  

  public function categoriesAction() {
    
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('video_admin_main', array(), 'video_admin_main_categories');
    Engine_Api::_()->getApi('categories', 'core')->categories(array('module' => 'video'));
  }
  
  public function changeOrderAction() {

    if ($this->_getParam('id', false) || $this->_getParam('nextid', false)) {
      $id = $this->_getParam('id', false);
      $order = $this->_getParam('categoryorder', false);
      $order = explode(',', $order);
      $nextid = $this->_getParam('nextid', false);
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      if ($id) {
        $category_id = $id;
      } else if ($nextid) {
        $category_id = $id;
      }
      $categoryTypeId = '';
      $checkTypeCategory = $dbObject->query("SELECT * FROM engine4_video_categories WHERE category_id = " . $category_id)->fetchAll();
      if (isset($checkTypeCategory[0]['subcat_id']) && $checkTypeCategory[0]['subcat_id'] != 0) {
        $categoryType = 'subcat_id';
        $categoryTypeId = $checkTypeCategory[0]['subcat_id'];
      } else if (isset($checkTypeCategory[0]['subsubcat_id']) && $checkTypeCategory[0]['subsubcat_id'] != 0) {
        $categoryType = 'subsubcat_id';
        $categoryTypeId = $checkTypeCategory[0]['subsubcat_id'];
      } else
        $categoryType = 'category_id';
      if ($checkTypeCategory)
        $currentOrder = Engine_Api::_()->getDbtable('categories', 'video')->order($categoryTypeId, $categoryType);
      // Find the starting point?
      $start = null;
      $end = null;
      $order = array_reverse(array_values(array_intersect($order, $currentOrder)));
      for ($i = 0, $l = engine_count($currentOrder); $i < $l; $i++) {
        if (engine_in_array($currentOrder[$i], $order)) {
          $start = $i;
          $end = $i + engine_count($order);
          break;
        }
      }
      if (null === $start || null === $end) {
        echo "false";
        die;
      }
      $categoryTable = Engine_Api::_()->getDbtable('categories', 'video');
      for ($i = 0; $i < engine_count($order); $i++) {
        $category_id = $order[$i - $start];
        $categoryTable->update(array('order' => $i), array('category_id = ?' => $category_id));
      }
      $checkCategoryChildrenCondition = $dbObject->query("SELECT * FROM engine4_video_categories WHERE subcat_id = '" . $id . "' || subsubcat_id = '" . $id . "' || subcat_id = '" . $nextid . "' || subsubcat_id = '" . $nextid . "'")->fetchAll();
      if (empty($checkCategoryChildrenCondition)) {
        echo 'done';
        die;
      }
      echo "children";
      die;
    }
  }

  public function editCategoryAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $form = $this->view->form = new Video_Form_Admin_Category();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    // Check post
    if( $this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost()) )
    {
      // Ok, we're good to add field
      $values = $form->getValues();

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        // edit category in the database
        // Transaction
        $row = Engine_Api::_()->video()->getCategory($values["id"]);

        $row->category_name = $values["label"];
        $row->save();
      
        if(isset($_POST['parentcategory_id']) && !empty($_POST['parentcategory_id'])) {
          $categoryItem = Engine_Api::_()->getItem('video_category', $_POST['parentcategory_id']);
          if(!empty($categoryItem->subcat_id)) {
            $row->subcat_id = 0;
            $row->subsubcat_id = $_POST['parentcategory_id'];
            $row->save();
          } else if(empty($rowItem->subcat_id)) {
            $row->subcat_id = $_POST['parentcategory_id'];
            $row->subsubcat_id = 0;
            $row->save();
          } 
        } else if($_POST['parentcategory_id'] == '') {
          $row->subcat_id = 0;
          $row->subsubcat_id = 0;
          $row->save();
        }
        $db->commit();
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh'=> 10,
          'messages' => array('')
      ));
   }

    // Must have an id
    if( !($id = $this->_getParam('id')) )
    {
      die('No identifier specified');
    }

    // Generate and assign form
    $category = Engine_Api::_()->video()->getCategory($id);
    $form->setField($category);

    // Output
    $this->renderScript('admin-settings/form.tpl');
  }

  public function testEncodeAction()
  {
    
  }

  private function verifyYotubeApiKey($key)
  {
    $option = array(
      'part' => 'id',
      'key' => $key,
      'maxResults' => 1
    );
    $url = "https://www.googleapis.com/youtube/v3/search?" . http_build_query($option, 'a', '&');
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $json_response = curl_exec($curl);
    curl_close($curl);
    $responseObj = Zend_Json::decode($json_response);
    if( empty($responseObj['error']) ) {
      return array('success' => 1);
    }
    return $responseObj['error'];
  }
}
