<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_settings');
    $this->view->form = $form = new Sesalbum_Form_Admin_Global();
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sesalbum/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.pluginactivated')) {
        if (@$values['sesalbum_set_landingpage']) {
        $this->landingpageSet();
      }else{
        //$this->revertlandingpageSet();
      }
        // if (isset($values['sesalbum_set_landingpage']) && $values['sesalbum_set_landingpage'] == 1) {
        //   $db = Engine_Db_Table::getDefaultAdapter();
        //   $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = 3");
        //   $page_id = 3;
        //   // Insert top
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'container',
        //       'name' => 'top',
        //       'page_id' => $page_id,
        //       'order' => 1,
        //   ));
        //   $top_id = $db->lastInsertId();
        //   // Insert main
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'container',
        //       'name' => 'main',
        //       'page_id' => $page_id,
        //       'order' => 2,
        //   ));
        //   $main_id = $db->lastInsertId();
        //   // Insert main-middle
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'container',
        //       'name' => 'middle',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_id,
        //       'order' => 6
        //   ));
        //   $main_middle_id = $db->lastInsertId();
        //   // Insert content
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'sesalbum.welcome',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 4,
        //       'params' => '{"slide_to_show":"sponsored","height_slideshow":"550","limit_data_slide":"8","slide_title":"Share your Stories with Photos!","slide_descrition":"Let your photos do the talking for you. After all, they\'re worth a million words.","enable_search":"yes","search_criteria":"photos","show_album_under":"yes","album_criteria":"sponsored","limit_data_album":"3","title_truncation":"45","title":"","nomobile":"0","name":"sesalbum.welcome"}',
        //   ));
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'sesalbum.album-home-error',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 6,
        //       'params' => '{"itemType":"photo","title":"","nomobile":"0","name":"sesalbum.album-home-error"}'
        //   ));
        //   // Insert content
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'sesalbum.tabbed-widget',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 7,
        //       'params' => '{"photo_album":"photo","tab_option":"filter","view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"12","show_limited_data":"yes","pagging":"pagging","title_truncation":"45","height":"500","width":"140","search_type":["recentlySPcreated"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"","nomobile":"0","name":"sesalbum.tabbed-widget"}',
        //   ));
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'core.html-block',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 5,
        //       'params' => '{"title":"","data":"<div class=\"sesalbum_welcome_html_block\">\r\n<h2>Upload your photos and share them with the World.<\/h2>\r\n<p>Share your photos with your family & friends in an effective way. Let the world know you!<\/p>\r\n<p><a href=\"javascript:;\" onclick=\"browsePhotoURL();returnfalse;\">Browse All Photos<\/a><\/p>\r\n<\/div>","nomobile":"0","name":"core.html-block"}',
        //   ));
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'sesalbum.browse-categories',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 8,
        //       'params' => '{"type":"photo","show_category_has_count":"no","show_count":"no","allign":"1","limit_data":"12","title":"Browse More Photos by Categories","nomobile":"0","name":"sesalbum.browse-categories"}',
        //   ));
        // }
        // if (isset($values['sesalbum_set_landingpage']) && $values['sesalbum_set_landingpage'] == 0) {
        //   $db = Engine_Db_Table::getDefaultAdapter();
        //   $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = 3");
        //   $page_id = 3;
        //   $top_id = $db->lastInsertId();
        //   // Insert main
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'container',
        //       'name' => 'main',
        //       'page_id' => $page_id,
        //       'order' => 1,
        //   ));
        //   $main_id = $db->lastInsertId();
        //   // Insert main-middle
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'container',
        //       'name' => 'middle',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_id,
        //       'order' => 2
        //   ));
        //   $main_middle_id = $db->lastInsertId();
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'core.landing-page-banner',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 1,
        //       'params' => '{"bannerId":"2","height":"600","title":"","nomobile":"0","name":"core.landing-page-banner"}',
        //   ));
        //   $db->insert('engine4_core_content', array(
        //       'type' => 'widget',
        //       'name' => 'core.landing-page-features',
        //       'page_id' => $page_id,
        //       'parent_content_id' => $main_middle_id,
        //       'order' => 2,
        //       'params' => '{"dummy1":null,"fe1img":"0","fe1heading":"Easy Login / Signup","fe1description":"You can easily sign up on our community or simply login, if you already have an account to get started !","dummy2":null,"fe2img":"0","fe2heading":"Post Content","fe2description":"Quickly start by posting your status updates, photos, videos, groups, blogs, classifieds, etc inside.","dummy3":null,"fe3img":"0","fe3heading":"Responsive","fe3description":"Our community is 100% responsive, so you can use it anywhere, & anytime from any device.","dummy4":null,"fe4img":"0","fe4heading":"Flexible","fe4description":"Our community is available 24x7, so you can use it as per your flexibility and requirement.","title":"Why Choose Us?","nomobile":"0","name":"core.landing-page-features"}',
        //   ));
        //}
        foreach ($values as $key => $value) {
          if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
                Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
            if (!$value && strlen($value) == 0)
                continue;
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
  function helpAction(){
      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
          ->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_settings');
  }
  // for default installation
  function setCategoryPhoto($file, $cat_id, $resize = false) {
    $fileName = $file;
    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sesalbum_category',
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
              ->autoRotate()
              ->resize(800, 800)
              ->write($mainPath)
              ->destroy();

      // Resize image (normal) make same image for activity feed so it open in pop up with out jump effect.
      $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_thumb.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->autoRotate()
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
              ->autoRotate()
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
        throw new Sesalbum_Model_Exception($e->getMessage(), $e->getCode());
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

  public function flushPhotoAction() {
    $dbObject = Engine_Db_Table::getDefaultAdapter();
    try {
        $dbObject->query('DELETE  FROM `engine4_sesalbum_photos` WHERE (album_id =0) AND (DATE(NOW()) != DATE(creation_date))');
    }catch(Exception $e){
        //silence
    }
    header('location:' . $_SERVER['HTTP_REFERER']);
    exit();
  }

  public function statisticAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_statistic');

    $albumTable = Engine_Api::_()->getDbtable('albums', 'sesalbum');
    $albumTableName = $albumTable->info('name');

    //Total Albums
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalalbum');
    $this->view->totalalbum = $select->query()->fetchColumn();

    //Total featured album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalfeatured')->where('is_featured =?', 1);
    $this->view->totalfeatured = $select->query()->fetchColumn();

    //Total sponsored album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalsponsored')->where('is_sponsored =?', 1);
    $this->view->totalsponsored = $select->query()->fetchColumn();

    //Total favourite album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalfavourite')->where('favourite_count <>?', 0);
    $this->view->totalfavourite = $select->query()->fetchColumn();

    //Total rated album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalrated')->where('rating <>?', 0);
    $this->view->totalrated = $select->query()->fetchColumn();

    //Album Photos
    $albumPhotosTable = Engine_Api::_()->getDbtable('photos', 'sesalbum');
    $albumphotosTableName = $albumPhotosTable->info('name');

    //Total photos
    $select = $albumPhotosTable->select()->from($albumphotosTableName, 'count(*) AS totalalbumphotos')->where('album_id != ?', '');
    $this->view->totalalbumphotos = $select->query()->fetchColumn();

    //Total featured photos
    $select = $albumPhotosTable->select()->from($albumphotosTableName, 'count(*) AS totalfeaturedphotos')->where('is_featured =?', 1);
    $this->view->totalfeaturedphotos = $select->query()->fetchColumn();

    //Total sponsored photos
    $select = $albumPhotosTable->select()->from($albumphotosTableName, 'count(*) AS totalsponsoredphotos')->where('is_sponsored =?', 1);
    $this->view->totalsponsoredphotos = $select->query()->fetchColumn();

    //Total favourite photos
    $select = $albumPhotosTable->select()->from($albumphotosTableName, 'count(*) AS totalfavouritephotos')->where('favourite_count <>?', 0);
    $this->view->totalfavouritephotos = $select->query()->fetchColumn();

    //Total rated photos
    $select = $albumPhotosTable->select()->from($albumphotosTableName, 'count(*) AS totalratedphotos')->where('rating <>?', 0);
    $this->view->totalratedphotos = $select->query()->fetchColumn();
  }
  public function manageWidgetizePageAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_managepages');

   $this->view->pagesArray = array('sesalbum_index_browse','sesalbum_category_index', 'sesalbum_category_browse', 'sesalbum_index_home', 'sesalbum_index_welcome', 'sesalbum_index_tags', 'sesalbum_index_create', 'sesalbum_index_photo-home', 'sesalbum_index_browse-photo', 'sesalbum_album_view', 'sesalbum_photo_view', 'sesalbum_index_manage');
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
      $db->query('UPDATE `engine4_core_content` SET `page_id` = "980000" WHERE `engine4_core_content`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `page_id` = "980000" WHERE `engine4_core_pages`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index_1" WHERE `engine4_core_pages`.`name` = "core_index_index";');
    }
    //New Landing Page
    $pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sesalbum_index_sesbackuplandingppage')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$pageId) {
      $widgetOrder = 1;
      //Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'sesalbum_index_sesbackuplandingppage',
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
          $top_id = $db->lastInsertId();
          // Insert main
          $db->insert('engine4_core_content', array(
              'type' => 'container',
              'name' => 'main',
              'page_id' => $pageId,
              'order' => 2,
          ));
          $main_id = $db->lastInsertId();
          // Insert main-middle
          $db->insert('engine4_core_content', array(
              'type' => 'container',
              'name' => 'middle',
              'page_id' => $pageId,
              'parent_content_id' => $main_id,
              'order' => 6
          ));
          $main_middle_id = $db->lastInsertId();
          // Insert content
          $db->insert('engine4_core_content', array(
              'type' => 'widget',
              'name' => 'sesalbum.welcome',
              'page_id' => $pageId,
              'parent_content_id' => $main_middle_id,
              'order' => 4,
              'params' => '{"slide_to_show":"sponsored","height_slideshow":"550","limit_data_slide":"8","slide_title":"Share your Stories with Photos!","slide_descrition":"Let your photos do the talking for you. After all, they\'re worth a million words.","enable_search":"yes","search_criteria":"photos","show_album_under":"yes","album_criteria":"sponsored","limit_data_album":"3","title_truncation":"45","title":"","nomobile":"0","name":"sesalbum.welcome"}',
          ));
          $db->insert('engine4_core_content', array(
              'type' => 'widget',
              'name' => 'sesalbum.album-home-error',
              'page_id' => $pageId,
              'parent_content_id' => $main_middle_id,
              'order' => 6,
              'params' => '{"itemType":"photo","title":"","nomobile":"0","name":"sesalbum.album-home-error"}'
          ));
          // Insert content
          $db->insert('engine4_core_content', array(
              'type' => 'widget',
              'name' => 'sesalbum.tabbed-widget',
              'page_id' => $pageId,
              'parent_content_id' => $main_middle_id,
              'order' => 7,
              'params' => '{"photo_album":"photo","tab_option":"filter","view_type":"masonry","insideOutside":"inside","fixHover":"hover","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","likeButton","favouriteButton"],"limit_data":"12","show_limited_data":"yes","pagging":"pagging","title_truncation":"45","height":"500","width":"140","search_type":["recentlySPcreated"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPfavourite_order":"2","mostSPfavourite_label":"Most Favourite","dummy4":null,"mostSPdownloaded_order":"2","mostSPdownloaded_label":"Most Downloaded","dummy5":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy8":null,"featured_order":"6","featured_label":"Featured","dummy9":null,"sponsored_order":"7","sponsored_label":"Sponsored","title":"","nomobile":"0","name":"sesalbum.tabbed-widget"}',
          ));
          $db->insert('engine4_core_content', array(
              'type' => 'widget',
              'name' => 'core.html-block',
              'page_id' => $pageId,
              'parent_content_id' => $main_middle_id,
              'order' => 5,
              'params' => '{"title":"","data":"<div class=\"sesalbum_welcome_html_block\">\r\n<h2>Upload your photos and share them with the World.<\/h2>\r\n<p>Share your photos with your family & friends in an effective way. Let the world know you!<\/p>\r\n<p><a href=\"javascript:;\" onclick=\"browsePhotoURL();returnfalse;\">Browse All Photos<\/a><\/p>\r\n<\/div>","nomobile":"0","name":"core.html-block"}',
          ));
          $db->insert('engine4_core_content', array(
              'type' => 'widget',
              'name' => 'sesalbum.browse-categories',
              'page_id' => $pageId,
              'parent_content_id' => $main_middle_id,
              'order' => 8,
              'params' => '{"type":"photo","show_category_has_count":"no","show_count":"no","allign":"1","limit_data":"12","title":"Browse More Photos by Categories","nomobile":"0","name":"sesalbum.browse-categories"}',
          ));
      $newbakpage_id = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'sesalbum_index_sesbackuplandingppage')
              ->limit(1)
              ->query()
              ->fetchColumn();
      if ($newbakpage_id) {
        $db->query('UPDATE `engine4_core_content` SET `page_id` = "3" WHERE `engine4_core_content`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `page_id` = "3" WHERE `engine4_core_pages`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index" WHERE `engine4_core_pages`.`name` = "sesalbum_index_sesbackuplandingppage";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "sesalbum_index_sesbackuplandingppage" WHERE `engine4_core_pages`.`name` = "core_index_index_1";');
        $db->query('UPDATE `engine4_core_pages` SET `displayname` = "SNS - Advanced Photos & Albums - Landing Page Backup" WHERE `engine4_core_pages`.`name` = "sesalbum_index_sesbackuplandingppage";');
      }
    }
  }
  
}
