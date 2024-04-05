<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_settings');

    $this->view->form  = $form = new Sesnews_Form_Admin_Global();

    if( $this->getRequest()->isPost() && $form->isValid($this->_getAllParams()) ) {

      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sesnews/controllers/License.php";

      $db = Engine_Db_Table::getDefaultAdapter();

      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.pluginactivated')) {

        // News Design Layout
        if (isset($values['sesnews_chooselayout']))
          $values['sesnews_chooselayout'] = serialize($values['sesnews_chooselayout']);
        else
          $values['sesnews_chooselayout'] = serialize(array());

        //Start Landing page set
        if (isset($_POST['sesnews_changelanding']) && $_POST['sesnews_changelanding'] == 1) {
          $this->landingPageSetup();
				}
        //End Landing Page set

        //START TEXT CHNAGE WORK IN CSV FILE
        $oldSigularWord = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.text.singular', 'news');

        $oldPluralWord = $oldSigularWord; //Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.text.plural', 'news');
        $newSigularWord = @$values['sesnews_text_singular'] ? @$values['sesnews_text_singular'] : 'news';
        $newPluralWord = $newSigularWord; //@$values['sesnews_text_plural'] ? @$values['sesnews_text_plural'] : 'news';
        $newSigularWordUpper = ucfirst($newSigularWord);
        $newPluralWordUpper = ucfirst($newPluralWord);

        if($newSigularWord != $oldSigularWord && $newPluralWord != $oldPluralWord) {

          $tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/sesnews.csv', 'null', array('delimiter' => ';','enclosure' => '"'));
          if( !empty($tmp['null']) && is_array($tmp['null']) )
            $inputData = $tmp['null'];
          else
            $inputData = array();

          $OutputData = array();
          $chnagedData = array();
          foreach($inputData as $key => $input) {
            $chnagedData = str_replace(array($oldPluralWord, $oldSigularWord,ucfirst($oldPluralWord),ucfirst($oldSigularWord),strtoupper($oldPluralWord),strtoupper($oldSigularWord)), array($newPluralWord, $newSigularWord, ucfirst($newPluralWord), ucfirst($newSigularWord), strtoupper($newPluralWord), strtoupper($newSigularWord)), $input);
            $OutputData[$key] = $chnagedData;
          }

          $targetFile = APPLICATION_PATH . '/application/languages/en/sesnews.csv';
          if (file_exists($targetFile))
            @unlink($targetFile);

          touch($targetFile);
          chmod($targetFile, 0777);

          $writer = new Engine_Translate_Writer_Csv($targetFile);
          $writer->setTranslations($OutputData);
          $writer->write();
          //END CSV FILE WORK
        }
        foreach ($values as $key => $value){
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

    public function rssSettingsAction() {

        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_rsssettings');
        $this->view->form  = $form = new Sesnews_Form_Admin_RssSettings();
        if( $this->getRequest()->isPost() && $form->isValid($this->_getAllParams()) ) {
            $values = $form->getValues();
            foreach ($values as $key => $value){
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

// 	public function uploadDefaultCategory(){
// 		$catgoryData = array('0' => array('Arts & Culture', 'arts-culture.jpg', 'arts-ulture.png',''),array('Business', 'business.jpg', 'business.png',''),array('Entertainment', 'entertainment.jpg', 'entertainment.png',''),array('Family & Home', 'family.jpeg', 'family.png',''),array('Health', 'health.jpg', 'health.png',''),array('Recreation', 'recreation.jpg', 'recreation.png',' '),array('Personal', 'personal.jpg', 'personal.png',''),array('Shopping', 'shopping.jpg', 'shopping.png',''),array('Society', 'society.jpeg', 'society.png',''),array('Sports', 'sports.jpg', 'sports.png',''),array('Technology', 'technology.jpeg', 'technology.png',''),array('Other', 'others.png', 'others.png',''));
// 		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
// 		foreach($catgoryData as $data){
// 			 $catId = $db->query("SELECT category_id FROM `engine4_sesnews_categories` WHERE category_name = '" . $data[0] . "' AND (thumbnail = 0 OR cat_icon = 0)")->fetchAll();
// 			 if(!empty($catId[0]['category_id'])){
// 				$catId = $catId[0]['category_id'];
// 			 }else
// 				continue;
// 			 $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesnews' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category" . DIRECTORY_SEPARATOR;
// 			 //upload thumbnails
// 			 if (is_file($PathFile . "banner" . DIRECTORY_SEPARATOR . $data[1]))
// 					$thumbnail_icon = $this->setCategoryPhoto($PathFile . "banner" . DIRECTORY_SEPARATOR . $data[1], $catId, true);
// 			 else
// 					$thumbnail_icon = 0;
//
// 			 //upload icons
// 			 if (is_file($PathFile . "icons" . DIRECTORY_SEPARATOR . $data[2]))
// 					$cat_icon = $this->setCategoryPhoto($PathFile . "icons" . DIRECTORY_SEPARATOR . $data[2], $catId, true);
// 			 else
// 					$cat_icon = 0;
//
// 			 $db->query("UPDATE `engine4_sesnews_categories` SET `cat_icon` = '" . $cat_icon . "',`thumbnail` = '" . $thumbnail_icon . "' WHERE category_id = " . $catId);
// 			 $db->query("UPDATE `engine4_sesnews_categories` set `title` = category_name WHERE category_id = ".$catId ." AND ( title IS NULL OR title = '')");
//        $db->query("UPDATE `engine4_sesnews_categories` set `slug` = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(category_name,'&',''),'  ',' '),' ','-'),'/','-'))  WHERE category_id = ".$catId ." AND ( title IS NULL OR slug = '')");
//        $db->query("UPDATE `engine4_sesnews_categories` SET `order` = `category_id`  WHERE category_id = ".$catId ." AND ( `order` IS NULL OR `order` = 0)");
//
// 		}
//
// 	}
	// for default installation
  function setCategoryPhoto($file, $cat_id, $resize = false) {
    $fileName = $file;
    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sesnews_category',
        'parent_id' => $cat_id,
        'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        'name' => $fileName,
    );
    // Save
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    if ($resize) {
      // Resize image (main)
      $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_poster.' . $extension;
      $image = Engine_Image::factory();
      $image->open($file)
              ->resize(800, 800)
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
      // Remove temp files
      @unlink($mainPath);
      if ($resize) {
        @unlink($normalPath);
      }
      @unlink($normalMainPath);
      // Throw
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Sesnews_Model_Exception($e->getMessage(), $e->getCode());
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

  public function categoriesAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_categories');

    $this->view->categories = Engine_Api::_()->getItemTable('sesnews_category')->fetchAll();
  }


  public function addCategoryAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');

    // Generate and assign form
    $form = $this->view->form = new Sesnews_Form_Admin_Category();
    $form->setAction($this->view->url(array()));



    // Check post
    if( !$this->getRequest()->isPost() ) {
      $this->renderScript('admin-settings/form.tpl');
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $this->renderScript('admin-settings/form.tpl');
      return;
    }


    // Process
    $values = $form->getValues();

    $categoryTable = Engine_Api::_()->getItemTable('sesnews_category');
    $db = $categoryTable->getAdapter();
    $db->beginTransaction();

    $viewer = Engine_Api::_()->user()->getViewer();

    try {
      $categoryTable->insert(array(
        'user_id' => $viewer->getIdentity(),
        'category_name' => $values['label'],
      ));

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

  public function deleteCategoryAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $category_id = $this->_getParam('id');
    $this->view->news_id = $this->view->category_id = $category_id;
    $categoriesTable = Engine_Api::_()->getDbtable('categories', 'sesnews');
    $category = $categoriesTable->find($category_id)->current();

    if( !$category ) {
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh'=> 10,
        'messages' => array('')
      ));
    } else {
      $category_id = $category->getIdentity();
    }

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-settings/delete.tpl');
      return;
    }

    // Process
    $db = $categoriesTable->getAdapter();
    $db->beginTransaction();

    try {

      $category->delete();

      $sesnewsTable = Engine_Api::_()->getDbtable('news', 'sesnews');
      $sesnewsTable->update(array(
        'category_id' => 0,
      ), array(
        'category_id = ?' => $category_id,
      ));

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

  public function editCategoryAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $category_id = $this->_getParam('id');
    $this->view->news_id = $this->view->category_id = $id;
    $categoriesTable = Engine_Api::_()->getDbtable('categories', 'sesnews');
    $category = $categoriesTable->find($category_id)->current();

    if( !$category ) {
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh'=> 10,
        'messages' => array('')
      ));
    } else {
      $category_id = $category->getIdentity();
    }

    $form = $this->view->form = new Sesnews_Form_Admin_Category();
    $form->setAction($this->getFrontController()->getRouter()->assemble(array()));
    $form->setField($category);

    if( !$this->getRequest()->isPost() ) {
      // Output
      $this->renderScript('admin-settings/form.tpl');
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      // Output
      $this->renderScript('admin-settings/form.tpl');
      return;
    }

    // Process
    $values = $form->getValues();

    $db = $categoriesTable->getAdapter();
    $db->beginTransaction();

    try {
      $category->category_name = $values['label'];
      $category->save();

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

  //site statis for sesnews plugin
  public function statisticAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_statistic');

    $newsTable = Engine_Api::_()->getDbtable('news', 'sesnews');
    $newsTableName = $newsTable->info('name');

    $newsPhotoTable = Engine_Api::_()->getDbtable('photos', 'sesnews');
    $newsPhotoTableName = $newsPhotoTable->info('name');

    $newsAlbumTable = Engine_Api::_()->getDbtable('albums', 'sesnews');
    $newsAlbumTableName = $newsAlbumTable->info('name');

    //Total News
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalnews');
    $this->view->totalnews = $select->query()->fetchColumn();

    //Total approved news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalapprovednews')->where('is_approved =?', 1);
    $this->view->totalapprovednews = $select->query()->fetchColumn();

    //Total verified news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalverified')->where('verified =?', 1);
    $this->view->totalnewsverified = $select->query()->fetchColumn();

    //Total featured news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalfeatured')->where('featured =?', 1);
    $this->view->totalnewsfeatured = $select->query()->fetchColumn();

    //Total sponsored news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalsponsored')->where('sponsored =?', 1);
    $this->view->totalnewsponsored = $select->query()->fetchColumn();

    //Total albums of news
    $select = $newsAlbumTable->select()->from($newsAlbumTableName, 'count(*) AS totalalbums');
    $this->view->totalalbums = $select->query()->fetchColumn();

    //Total photos of news
    $select = $newsPhotoTable->select()->from($newsPhotoTableName, 'count(*) AS totalphotos');
    $this->view->totalphotos = $select->query()->fetchColumn();

    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideo')) {
			$videoTable = Engine_Api::_()->getDbtable('videos', 'sesvideo');
			$videoTableName = $videoTable->info('name');
			//Total videos of news
			$select = $videoTable->select()->from($videoTableName, 'count(*) AS totalvideos')->where('parent_type =?', 'sesnews_news');
			$this->view->totalvideos = $select->query()->fetchColumn();
    }

    //Total favourite news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalfavourite')->where('favourite_count <>?', 0);
    $this->view->totalnewsfavourite = $select->query()->fetchColumn();

    //Total comments news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalcomment')->where('comment_count <>?', 0);
    $this->view->totalnewscomments = $select->query()->fetchColumn();

     //Total view news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalview')->where('view_count <>?', 0);
    $this->view->totalnewsviews = $select->query()->fetchColumn();

     //Total like news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totallike')->where('like_count <>?', 0);
    $this->view->totalnewslikes = $select->query()->fetchColumn();

    //Total rated news
    $select = $newsTable->select()->from($newsTableName, 'count(*) AS totalrated')->where('rating <>?', 0);
    $this->view->totalnewsrated = $select->query()->fetchColumn();
  }

  public function manageWidgetizePageAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_managepages');

    $this->view->pagesArray = array('sesnews_index_welcome', 'sesnews_index_home', 'sesnews_index_browse', 'sesnews_category_browse', 'sesnews_index_locations', 'sesnews_review_browse', 'sesnews_index_manage', 'sesnews_index_create', 'sesnews_index_view_1', 'sesnews_index_view_2','sesnews_index_view_3','sesnews_index_view_4', 'sesnews_index_tags', 'sesnews_review_view', 'sesnews_album_view', 'sesnews_photo_view', 'sesnews_index_list', 'sesnews_category_index', 'sesnews_rss_browse', 'sesnews_rss_view', 'sesnews_rss_create', 'sesnews_rss_manage');
  }

  public function landingPageSetup() {

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = 3");
    $page_id = 3;
    $widgetOrder = 1;

    // Insert top
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
    ));
    $top_id = $db->lastInsertId();

    // Insert main
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2,
    ));
    $main_id = $db->lastInsertId();

    // Insert top-middle
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
    ));
    $top_middle_id = $db->lastInsertId();

    // Insert main-middle
    $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 2,
    ));
    $main_middle_id = $db->lastInsertId();

    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"bodysimple":"<div class=\"sesnews_welcome_text_block_wrapper sesbasic_bxs sesbasic_clearfix\">\r\n\t<div class=\"sesnews_welcome_text_block\">\r\n  \t<h2 class=\"sesnews_welcome_text_block_maintxt\">SHARE YOUR  IDEAS & STORIES  WITH THE WORLD<\/h2>\r\n    <div class=\"sesnews_welcome_text_block_buttons sesbasic_clearfix\">\r\n    \t<a href=\"news\/home\" class=\"sesbasic_link_btn sesbasic_animation\">Explore Popular News<\/a>\r\n      <a href=\"news\/create\" class=\"sesbasic_link_btn sesbasic_animation\">Create Your Unique News<\/a>\r\n      <a href=\"news\/categories\" class=\"sesbasic_link_btn\">Explore By Category<\/a>\r\n    <\/div>\r\n<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 6px;width: 1200px; margin-left: 50px;\">\r\n<\/div>\r\n<div style=\"font-size: 24px;margin-bottom: 30px;  margin-top: 25px;text-align: center;\">Read our Sponsored News!<\/div>\r\n  <\/div>\r\n<\/div>","en_US_bodysimple":"<div class=\"sesnews_welcome_text_block_wrapper sesbasic_bxs sesbasic_clearfix\">\r\n\t<div class=\"sesnews_welcome_text_block\">\r\n  \t<h2 class=\"sesnews_welcome_text_block_maintxt\">SHARE YOUR  \u2022  IDEAS & STORIES  \u2022 WITH THE WORLD<\/h2>\r\n    <div class=\"sesnews_welcome_text_block_buttons sesbasic_clearfix\">\r\n    \t<a href=\"news\/home\" class=\"sesbasic_link_btn sesbasic_animation\">Explore Popular News<\/a>\r\n      <a href=\"news\/create\" class=\"sesbasic_link_btn sesbasic_animation\">Create Your Unique News<\/a>\r\n      <a href=\"news\/categories\" class=\"sesbasic_link_btn sesbasic_animation\">Explore By Category<\/a>\r\n    <\/div>\r\n    <p class=\"sesnews_welcome_text_block_subtxt\">Read our Sponsored News!<\/p>\r\n  <\/div>\r\n<\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesnews.featured-sponsored-verified-category-carousel',
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"carousel_type":"1","slidesToShow":"4","category":"0","criteria":"2","order":"","info":"most_liked","isfullwidth":"1","autoplay":"1","speed":"2000","show_criteria":["title","favouriteButton","likeButton","category","socialSharing"],"title_truncation":"35","height":"350","width":"400","limit_data":"10","title":"","nomobile":"0","name":"sesnews.featured-sponsored-verified-category-carousel"}',
    ));

    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"bodysimple":"<div style=\"font-size: 34px;margin-bottom: 50px;margin-top:50px;text-align: center;\">Featured Posts -  Heads up newsgers!<\/div>","en_US_bodysimple":"<div style=\"font-size: 34px;margin-bottom: 50px;margin-top:50px;text-align: center;\">Featured Posts -  Heads up newsgers!<\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesnews.featured-sponsored-verified-random-news',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"category":"0","criteria":"1","order":"","show_criteria":["like","comment","favourite","view","title","by","rating","ratingStar","verifiedLabel","favouriteButton","likeButton","category","socialSharing","creationDate"],"title":"","nomobile":"0","name":"sesnews.featured-sponsored-verified-random-news"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/browse\">Read all Posts\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">Verified News on our Community\r\n<\/span><\/div>","en_US_bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/browse\">Read all Posts\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">Verified News on our Community\r\n<\/span><\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesnews.tabbed-widget-news',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["grid"],"openViewType":"grid","show_criteria":["favouriteButton","location","likeButton","socialSharing","like","favourite","comment","rating","view","title","category","by","readmore","creationDate","descriptiongrid"],"pagging":"button","title_truncation_grid":"45","title_truncation_list":"45","title_truncation_pinboard":"45","limit_data_pinboard":"10","limit_data_list":"10","limit_data_grid":"10","description_truncation_list":"35","description_truncation_grid":"45","description_truncation_pinboard":"45","height_grid":"280","width_grid":"393","height_list":"230","width_list":"260","width_pinboard":"300","search_type":["verified"],"dummy1":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy2":null,"mostSPviewed_order":"2","mostSPviewed_label":"Most Viewed","dummy3":null,"mostSPliked_order":"3","mostSPliked_label":"Most Liked","dummy4":null,"mostSPcommented_order":"4","mostSPcommented_label":"Most Commented","dummy5":null,"mostSPrated_order":"5","mostSPrated_label":"Most Rated","dummy6":null,"mostSPfavourite_order":"6","mostSPfavourite_label":"Most Favourite","dummy7":null,"featured_order":"7","featured_label":"Featured","dummy8":null,"sponsored_order":"8","sponsored_label":"Sponsored","dummy9":null,"verified_order":"9","verified_label":"Verified","dummy10":null,"week_order":"10","week_label":"This Week","dummy11":null,"month_order":"11","month_label":"This Month","title":"","nomobile":"0","name":"sesnews.tabbed-widget-news"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/locations\">Explore All News\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">What do you want to read out?\r\n<\/span><\/div>","en_US_bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/locations\">Explore All News\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">What do you want to read out?\r\n<\/span><\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesnews.news-category',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"height":"180","width":"196","limit":"12","video_required":"1","criteria":"admin_order","show_criteria":["title","countNews"],"title":"","nomobile":"0","name":"sesnews.news-category"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesbasic.simple-html-block',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/categories\">Browse All Categories\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">Meet our Top Newsgers!\r\n<\/span><\/div>","en_US_bodysimple":"<div style=\"text-align: center;margin-top:30px; box-shadow:inset 0 1px 0 rgba(255,255,255,.1),0 1px 0 rgba(8,32,84,.1);padding-bottom: 30px;\">\r\n\t<a class=\"sesnews_welcome_btn sesbasic_animation\" href=\"news\/categories\">Browse All Categories\r\n<\/a><\/div>\r\n<div style=\"font-size: 34px;margin-bottom: 30px;  margin-top: 30px;text-align: center;\">Meet our Top Newsgers!\r\n<\/span><\/div>","show_content":"1","title":"","nomobile":"0","name":"sesbasic.simple-html-block"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesnews.top-newsgers',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"horizontal","show_criteria":["count","ownername"],"height":"180","width":"234","showLimitData":"0","limit_data":"5","title":"","nomobile":"0","name":"sesnews.top-newsgers"}',
    ));
    $db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesspectromedia.banner',
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"is_full":"1","is_pattern":"1","banner_image":"public\/admin\/banner_final.jpg","banner_title":"Start by creating your Unique News","title_button_color":"FFFFFF","description":"Publish your personal or professional news at your desired date and time!","description_button_color":"FFFFFF","button1":"1","button1_text":"Get Started","button1_text_color":"0295FF","button1_color":"FFFFFF","button1_mouseover_color":"EEEEEE","button1_link":"news\/create","button2":"0","button2_text":"Button - 2","button2_text_color":"FFFFFF","button2_color":"0295FF","button2_mouseover_color":"067FDE","button2_link":"","button3":"0","button3_text":"Button - 3","button3_text_color":"FFFFFF","button3_color":"F25B3B","button3_mouseover_color":"EA350F","button3_link":"","height":"400","title":"","nomobile":"0","name":"sesspectromedia.banner"}',
    ));
  }
}
