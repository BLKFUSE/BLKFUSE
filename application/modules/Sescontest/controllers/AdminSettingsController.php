<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_settings');
    $settingsTable = Engine_Api::_()->getDbTable('settings', 'core');
    $settingsTableName = $settingsTable->info('name');
    $this->view->form = $form = new Sescontest_Form_Admin_Global();
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();

      include_once APPLICATION_PATH . "/application/modules/Sescontest/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.pluginactivated')) {

        if (@$values['sescontest_changelanding']) {
          $this->landingpageSet();
        }

        $db = Engine_Db_Table::getDefaultAdapter();
        //START TEXT CHNAGE WORK IN CSV FILE
        $oldSigularWord = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.text.singular', 'contest');
        $oldPluralWord = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.text.plural', 'contests');
        if (isset($values['sescontest_text_singular']))
          $newSigularWord = $values['sescontest_text_singular'] ? $values['sescontest_text_singular'] : 'contest';
        else
          $newSigularWord = 'contest';
        if (isset($values['sescontest_text_plural']))
          $newPluralWord = $values['sescontest_text_plural'] ? $values['sescontest_text_plural'] : 'contests';
        else
          $newPluralWord = 'contests';
        $newSigularWordUpper = ucfirst($newSigularWord);
        $newPluralWordUpper = ucfirst($newPluralWord);
        if ($newSigularWord != $oldSigularWord && $newPluralWord != $oldPluralWord) {

          $tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/sescontest.csv', 'null', array('delimiter' => ';', 'enclosure' => '"'));
          if (!empty($tmp['null']) && is_array($tmp['null']))
            $inputData = $tmp['null'];
          else
            $inputData = array();

          $OutputData = array();
          $chnagedData = array();
          foreach ($inputData as $key => $input) {
            $chnagedData = str_replace(array($oldPluralWord, $oldSigularWord, ucfirst($oldPluralWord), ucfirst($oldSigularWord), strtoupper($oldPluralWord), strtoupper($oldSigularWord)), array($newPluralWord, $newSigularWord, ucfirst($newPluralWord), ucfirst($newSigularWord), strtoupper($newPluralWord), strtoupper($newSigularWord)), $input);
            $OutputData[$key] = $chnagedData;
          }

          $targetFile = APPLICATION_PATH . '/application/languages/en/sescontest.csv';
          if (file_exists($targetFile))
            @unlink($targetFile);

          touch($targetFile);
          chmod($targetFile, 0777);

          $writer = new Engine_Translate_Writer_Csv($targetFile);
          $writer->setTranslations($OutputData);
          $writer->write();
          //END CSV FILE WORK
        }
        foreach ($values as $key => $value) {
          if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
          if (!$value && strlen($value) == 0)
            continue;
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if ($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }

  public function contestcreateAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_contcreate');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main_contcreate', array(), 'sescontest_admin_main_subcontestcreatesetting');

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->form = $form = new Sescontest_Form_Admin_ContestCreatePageSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function contestcreatepageAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_contcreate');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main_contcreate', array(), 'sescontest_admin_main_subcontestcreatepagesetting');

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->form = $form = new Sescontest_Form_Admin_ContestCreatePage();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function contestcreatepopupAction() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_contcreate');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main_contcreate', array(), 'sescontest_admin_main_subcontestcreatepopupsetting');

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->form = $form = new Sescontest_Form_Admin_ContestCreatePopup();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function entrycreateAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_entrysettings');

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->form = $form = new Sescontest_Form_Admin_EntryCreateSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      foreach ($values as $key => $value) {
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function levelAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_level');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main_level', array(), 'sescontest_admin_main_subcontestmemberlevelsetting');
    // Get level id
    if (null !== ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }
    if (!$level instanceof Authorization_Model_Level) {
      throw new Engine_Exception('missing level');
    }
    $level_id = $id = $level->level_id;
    // Make form
    $this->view->form = $form = new Sescontest_Form_Admin_Settings_Level(array(
        'public' => ( engine_in_array($level->type, array('public')) ),
        'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($level_id);
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $valuesForm = $permissionsTable->getAllowed('contest', $level_id, array_keys($form->getValues()));
    if ($valuesForm['award_count'] == 6)
      $valuesForm['award_count'] = 5;
    $form->populate($valuesForm);
    if (!$this->getRequest()->isPost()) {
      return;
    }
    // Check validitiy
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    // Process
    $values = $form->getValues();
    if ($values['award_count'] == 5)
      $values['award_count'] = 6;
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      if ($level->type != 'public') {
        // Set permissions
        $values['auth_comment'] = (array) $values['auth_comment'];
        $values['auth_view'] = (array) $values['auth_view'];
      }
      $nonBooleanSettings = $form->nonBooleanFields();
      $permissionsTable->setAllowed('contest', $level_id, $values, '', $nonBooleanSettings);
      // Commit
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }

  public function entrylevelAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_level');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main_level', array(), 'sescontest_admin_main_subentrymemberlevelsetting');
    // Get level id
    if (null !== ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }
    if (!$level instanceof Authorization_Model_Level) {
      throw new Engine_Exception('missing level');
    }
    $level_id = $id = $level->level_id;
    // Make form
    $this->view->form = $form = new Sescontest_Form_Admin_Settings_EntryLevel(array(
        'public' => ( engine_in_array($level->type, array('public')) ),
        'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    $form->level_id->setValue($level_id);
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $valuesForm = $permissionsTable->getAllowed('participant', $level_id, array_keys($form->getValues()));
    $form->populate($valuesForm);
    if (!$this->getRequest()->isPost()) {
      return;
    }
    // Check validitiy
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    // Process
    $values = $form->getValues();
    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      if ($level->type != 'public') {
        // Set permissions
        $values['auth_comment'] = (array) (isset($values['auth_comment']) ? $values['auth_comment'] : '');
      }
      $nonBooleanSettings = $form->nonBooleanFields();
      $permissionsTable->setAllowed('participant', $level_id, $values, '', $nonBooleanSettings);
      // Commit
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }

  public function manageDashboardsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_managedashboards');

    $this->view->storage = Engine_Api::_()->storage();
    $this->view->paginator = Engine_Api::_()->getDbtable('dashboards', 'sescontest')->getDashboardsItems();
  }

  //Enable Action
  public function enabledAction() {

    $id = $this->_getParam('dashboard_id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('sescontest_dashboards', $id);
      $item->enabled = !$item->enabled;
      $item->save();
    }
    $this->_redirect('admin/sescontest/settings/manage-dashboards');
  }

  public function editDashboardsSettingsAction() {

    $dashboards = Engine_Api::_()->getItem('sescontest_dashboards', $this->_getParam('dashboard_id'));
    $this->_helper->layout->setLayout('admin-simple');
    $form = $this->view->form = new Sescontest_Form_Admin_EditDashboard();
    $form->setTitle('Edit This Item');
    $form->button->setLabel('Save Changes');

    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));

    if (!($id = $this->_getParam('dashboard_id')))
      throw new Zend_Exception('No identifier specified');

    $form->populate($dashboards->toArray());

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $dashboards->title = $values["title"];
        $dashboards->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array('You have successfully edit entry.')
      ));
      $this->_redirect('admin/sescontest/settings/manage-dashboards');
    }
  }

  public function utilityAction() {
    if (defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER) {
      return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    }

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_utility');

    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sescontest_ffmpeg_path;
    if (function_exists('shell_exec')) {
      // Get version
      $this->view->version = $version = shell_exec(escapeshellcmd($ffmpeg_path) . ' -version 2>&1');
      $command = "$ffmpeg_path -formats 2>&1";
      $this->view->format = $format = shell_exec(escapeshellcmd($ffmpeg_path) . ' -formats 2>&1')
              . shell_exec(escapeshellcmd($ffmpeg_path) . ' -codecs 2>&1');
    }
  }

  public function statisticAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_statistics');

    $contestTable = Engine_Api::_()->getDbtable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');

    //Total contests
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalcontest');
    $this->view->totalcontest = $select->query()->fetchColumn();

    //Total approved contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalapprovedcontest')->where('is_approved =?', 1);
    $this->view->totalapprovedcontest = $select->query()->fetchColumn();

    //Total verified contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalverified')->where('verified =?', 1);
    $this->view->totalcontestverified = $select->query()->fetchColumn();

    //Total featured contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalfeatured')->where('featured =?', 1);
    $this->view->totalcontestfeatured = $select->query()->fetchColumn();

    //Total sponsored contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalsponsored')->where('sponsored =?', 1);
    $this->view->totalcontestsponsored = $select->query()->fetchColumn();

    //Total hot contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalhot')->where('hot =?', 1);
    $this->view->totalcontesthot = $select->query()->fetchColumn();

    //Total favourite contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalfavourite')->where('favourite_count <>?', 0);
    $this->view->totalcontestfavourite = $select->query()->fetchColumn();

    //Total comments contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalcomment')->where('comment_count <>?', 0);
    $this->view->totalcontestcomments = $select->query()->fetchColumn();

    //Total view contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalview')->where('view_count <>?', 0);
    $this->view->totalcontestviews = $select->query()->fetchColumn();

    //Total like contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totallike')->where('like_count <>?', 0);
    $this->view->totalcontestlikes = $select->query()->fetchColumn();

    //Total follow contest
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalfollow')->where('follow_count <>?', 0);
    $this->view->totalcontestfollowers = $select->query()->fetchColumn();

    //Total contest entries
    $select = $contestTable->select()->from($contestTableName, 'count(*) AS totalentries')->where('join_count <>?', 0);
    $this->view->totalcontestentries = $select->query()->fetchColumn();
  }

  public function manageWidgetizePageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_managewidgetizepage');

    $pagesArray = array('sescontest_media_photo', 'sescontest_join_view', 'sescontest_index_welcome', 'sescontest_index_home', 'sescontest_index_browse', 'sescontest_index_tags', 'sescontest_index_manage', 'sescontest_category_browse', 'sescontest_profile_index_1', 'sescontest_profile_index_2', 'sescontest_profile_index_3', 'sescontest_profile_index_4', 'sescontest_category_index', 'sescontest_index_create', 'sescontest_media_text', 'sescontest_media_video', 'sescontest_media_audio', 'sescontest_join_create', 'sescontest_index_winner', 'sescontest_index_entries', 'sescontest_index_ongoing', 'sescontest_index_comingsoon', 'sescontest_index_ended', 'sescontest_index_pinboard');
    $this->view->pagesArray = $pagesArray;
  }

  // for default installation
  function setCategoryPhoto($file, $cat_id, $resize = false, $media = false) {
    $fileName = $file;
    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    if ($media) {
      $resourceType = 'sescontest_category';
      $width = $height = 1600;
    } else {
      $resourceType = 'sescontest_media';
      $width = $height = 800;
    }
    $params = array(
        'parent_type' => $resourceType,
        'parent_id' => $cat_id,
        'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        'name' => $name,
    );

    // Save
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    if ($resize) {
      // Resize image (main)
      $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_poster.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize($height, $width)
              ->write($mainPath)
              ->destroy();

      // Resize image (normal) make same image for activity feed so it open in pop up with out jump effect.
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
      // normal main  image resize
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
    // Store
    try {
      $iMain = $filesTable->createFile($mainPath, $params);
      if ($resize) {
        $iIconNormal = $filesTable->createFile($normalPath, $params);
        $iMain->bridge($iIconNormal, 'thumb.thumb');
      }
      $iNormalMain = $filesTable->createFile($normalMainPath, $params);
      $iMain->bridge($iNormalMain, 'thumb.icon');
    } catch (Exception $e) {
      die;
      // Remove temp files
      @unlink($mainPath);
      if ($resize) {
        @unlink($normalPath);
      }
      @unlink($normalMainPath);
      // Throw
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Sesevent_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    // Remove temp files
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

  public function landingpageSet() {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    //Set Welcomw page as Landing Page and backup of Old Landing Page
    $orlanpage_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'core_index_index')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if ($orlanpage_id) {
      $db->query('UPDATE `engine4_core_content` SET `page_id` = "990000" WHERE `engine4_core_content`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `page_id` = "990000" WHERE `engine4_core_pages`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index_1" WHERE `engine4_core_pages`.`name` = "core_index_index";');
    }
    //New Landing Page
    $pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sescontest_index_sesbackuplandingppage')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$pageId) {
      $widgetOrder = 1;
      //Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'sescontest_index_sesbackuplandingppage',
          'displayname' => 'Landing Page',
          'title' => 'Landing Page',
          'description' => 'This is your site\'s landing page.',
          'custom' => 0,
      ));
      $newpagelastId = $pageId = $db->lastInsertId();

      // Insert top
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $pageId,
          'order' => 1,
      ));
      $topId = $db->lastInsertId();

      // Insert main
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $pageId,
          'order' => 2,
      ));
      $mainId = $db->lastInsertId();

      // Insert top-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $pageId,
          'parent_content_id' => $topId,
      ));
      $topMiddleId = $db->lastInsertId();

      // Insert main-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $pageId,
          'parent_content_id' => $mainId,
          'order' => 2,
      ));
      $mainMiddleId = $db->lastInsertId();
      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesbasic.body-class',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"bodyclass":"sescontest_welcome_page","title":"","nomobile":"0","name":"sesbasic.body-class"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.featured-sponsored-verified-hot-slideshow',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"order":"ongoingSPupcomming","criteria":"3","info":"creation_date","isfullwidth":"1","autoplay":"1","speed":"4000","navigation":"nextprev","show_criteria":["title","description"],"title_truncation":"45","description_truncation":"250","height":"550","limit_data":"6","title":"","nomobile":"0","name":"sescontest.featured-sponsored-verified-hot-slideshow"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.media-type-icons',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"banner_title":"EXPLORE, CREATE & JOIN CONTESTS IN YOUR INTEREST AREA","description":"We have contests in all possible Media Types with various categories & subcategories. Create or Join unlimited contests and win exciting prizes!!","show_criteria":["photo","video","music","text"],"photo_text":"PHOTOS","photo_image":"0","video_text":"VIDEOS","video_image":"0","audio_text":"MUSIC","audio_image":"0","blog_text":"TEXT","text_image":"0","title":"","nomobile":"0","name":"sescontest.media-type-icons"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.winners-listing',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"enableTabs":["grid"],"openViewType":"pinboard","show_criteria":["title","mediaType","pinboarddescription","rank","socialSharing"],"pagging":"pagging","fixed_data":"yes","socialshare_enable_plusicon":"1","socialshare_icon_limit":"5","limit_data_list":"5","list_title_truncation":"45","list_description_truncation":"45","height_list":"230","width_list":"260","limit_data_grid":"6","grid_title_truncation":"45","grid_description_truncation":"45","height_grid":"290","width_grid":"396","limit_data_pinboard":"8","pinboard_title_truncation":"45","pinboard_description_truncation":"45","width_pinboard":"393","title":"RECENT WINNER ENTRIES & ACHIEVERS","nomobile":"0","name":"sescontest.winners-listing"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.how-it-works',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.featured-sponsored-verified-hot-random-contest',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"order":"ongoingSPupcomming","criteria":"2","order_content":"most_joined","show_criteria":["title","mediaType","category","socialSharing"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"4","title_truncation":"45","description_truncation":"45","height":"350","title":"SPONSORED CONTESTS","nomobile":"0","name":"sescontest.featured-sponsored-verified-hot-random-contest"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.parallax-contest-statistics',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"banner_title":"Join . Participate . Win . Enjoy Awesome Awards!!!","description":"Default Description: Show your talent in various contests and never lose a chance of winning.","bg_image":"0","show_criteria":["totalContest","totalEntries","totalVotes","totalRanks","totalWinners"],"show_custom_count":"real","dummy1":null,"custom_contest":"100","custom_entry":"100","custom_vote":"100","custom_rank":"100","custom_winner":"100","effect_type":"2","height":"500","title":"","nomobile":"0","name":"sescontest.parallax-contest-statistics"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.browse-entries',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"enableTabs":["grid"],"openViewType":"grid","show_criteria":["title","mediaType"],"show_item_count":"0","pagging":"button","fixed_data":"yes","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height_list":"230","width_list":"260","height_grid":"270","width_grid":"389","width_pinboard":"300","list_title_truncation":"45","grid_title_truncation":"45","pinboard_title_truncation":"45","list_description_truncation":"45","grid_description_truncation":"45","pinboard_description_truncation":"45","limit_data_pinboard":"10","limit_data_grid":"6","limit_data_list":"20","title":"Latest Entries","nomobile":"0","name":"sescontest.browse-entries"}',
      ));
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.top-members',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"placement_type":"extended","viewType":"2","criteria":["topParticipant","topCreator","topVoter"],"tabOption":"advance","show_criteria":["userName","contestCount","entryCount","voteCount","socialsharing","follow","cover"],"socialshare_enable_listview1plusicon":"1","socialshare_icon_listview1limit":"5","height":"150","width":"150","imagewidth":"150","limit_data":"18","pagging":"button","dummy1":null,"topParticipant_order":"1","topParticipant_label":"Top Participant","dummy2":null,"topCreator_order":"2","topCreator_label":"Top Contest Creators","dummy3":null,"topVoters_order":"3","topVoters_label":"Top Voters","title":"","nomobile":"0","name":"sescontest.top-members"}',
      ));
      $newbakpage_id = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'sescontest_index_sesbackuplandingppage')
              ->limit(1)
              ->query()
              ->fetchColumn();
      if ($newbakpage_id) {
        $db->query('UPDATE `engine4_core_content` SET `page_id` = "3" WHERE `engine4_core_content`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `page_id` = "3" WHERE `engine4_core_pages`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index" WHERE `engine4_core_pages`.`name` = "sescontest_index_sesbackuplandingppage";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "sescontest_index_sesbackuplandingppage" WHERE `engine4_core_pages`.`name` = "core_index_index_1";');
        $db->query('UPDATE `engine4_core_pages` SET `displayname` = "SNS - Advanced Contest - Landing Page Backup" WHERE `engine4_core_pages`.`name` = "sescontest_index_sesbackuplandingppage";');
      }
    }
  }
  
  public function extensionsAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_extension');
  }

  public function moduleenableAction() {
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $module = $this->_getParam('modulename');
    $enabled = $this->_getParam('enabled');
    if (!empty($module)) {
      if($module == 'sescontestpackage') {
        $db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_menuitems`.`name` = "sescontest_admin_packagesetting";');
      } else if($module == 'sescontestjurymember') {
        $db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_menuitems`.`name` = "sescontestjurymember_admin_main";');
      } else if($module == 'sescontestjoinfees') {
        $db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_menuitems`.`name` = "sescontestjoinfees_admin_main";');
      }
      
      $db->query('UPDATE `engine4_core_modules` SET `enabled` = "'.$enabled.'" WHERE `engine4_core_modules`.`name` = "'.$module.'";');
    }
    $this->_redirect('admin/sescontest/settings/extensions');
  }
}
