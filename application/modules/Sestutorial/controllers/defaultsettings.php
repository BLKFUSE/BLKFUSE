<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: defaultsettings.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

//Default Privacy Set Work
$permissionsTable = Engine_Api::_()->getDbTable('permissions', 'authorization');
foreach (Engine_Api::_()->getDbTable('levels', 'authorization')->fetchAll() as $level) {

  // Make form
	$form = new Sestutorial_Form_Admin_Settings_Level(array(
		'public' => ( engine_in_array($level->type, array('public')) ),
		'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
	));
  $values = $form->getValues();
  $valuesForm = $permissionsTable->getAllowed('sestutorial_tutorial', $level->level_id, array_keys($form->getValues()));

  $form->populate($valuesForm);
  $db = $permissionsTable->getAdapter();
  $db->beginTransaction();
  try {
    $permissionsTable->setAllowed('sestutorial_tutorial', $level->level_id, $values);
    // Commit
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    throw $e;
  }
}

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$db->query("INSERT IGNORE INTO `engine4_sestutorial_categories` (`category_id`, `slug`, `category_name`, `subcat_id`, `subsubcat_id`, `title`, `description`, `color`, `thumbnail`, `cat_icon`, `colored_icon`, `order`, `profile_type_review`, `profile_type`) VALUES
(13, 'managing-your-account', 'Managing Your Account', 0, 0, 'Managing Your Account', '', '', 40, 38, 0, 4, NULL, 0),
(14, 'using-socialeginesolutions-demo', 'Using SocialEngineSolutions Demo', 0, 0, 'Using SocialEngineSolutions Demo', '', '', 35, 63, 33, 5, NULL, 0),
(15, 'privacy-safety-standards', 'Privacy & Safety Standards', 0, 0, 'Privacy & Safety Standards', '', '', 55, 53, 0, 0, NULL, 0),
(16, 'policies-reporting', 'Policies & Reporting', 0, 0, 'Policies & Reporting', '', '', 60, 58, 0, 1, NULL, 0),
(17, 'fix-a-problem', 'Fix a problem', 0, 0, 'Fix a problem', '', '', 50, 48, 0, 2, NULL, 0),
(18, 'mobile-apps', 'Mobile Apps', 0, 0, 'Mobile Apps', '', '', 45, 43, 0, 3, NULL, 0),
(19, 'creating-an-account', 'Creating an Account', 14, 0, 'Creating an Account', '', '', 152, 150, 0, 1, NULL, 0),
(21, 'friendships', 'Friendships', 14, 0, 'Friendships', '', '', 107, 105, 0, 2, NULL, 0),
(22, 'your-home-page', 'Your Home Page', 14, 0, 'Your Home Page', '', '', 112, 110, 0, 3, NULL, 0),
(23, 'messaging', 'Messaging', 14, 0, 'Messaging', '', '', 102, 100, 0, 4, NULL, 0),
(25, 'photos', 'Photos', 14, 0, 'Photos', '', '', 97, 95, 0, 5, NULL, 0),
(27, 'videos', 'Videos', 14, 0, 'Videos', '', '', 92, 90, 0, 7, NULL, 0),
(28, 'groups', 'Groups', 14, 0, 'Groups', '', '', 87, 85, 0, 8, NULL, 0),
(29, 'events', 'Events', 14, 0, 'Events', '', '', 82, 80, 0, 9, NULL, 0),
(30, 'blogs', 'Blogs', 14, 0, 'Blogs', '', '', 77, 75, 0, 10, NULL, 0),
(31, 'articles', 'Articles', 14, 0, 'Articles', '', '', 72, 70, 0, 11, NULL, 0),
(32, 'music', 'Music', 14, 0, 'Music', '', '', 67, 65, 0, 12, NULL, 0),
(33, 'video-channels', 'Video Channels', 0, 27, 'Video Channels', '', '', 127, 125, 0, 1, NULL, 0),
(34, 'video-playlists', 'Video Playlists', 0, 27, 'Video Playlists', '', '', 122, 120, 0, 2, NULL, 0),
(35, 'video-artists', 'Video Artists', 0, 27, 'Video Artists', '', '', 117, 115, 0, 3, NULL, 0),
(36, 'importing-photos-from-other-social-media', 'Importing Photos from Other Social Media', 0, 25, 'Importing Photos from Other Social Media', '', '', 132, 130, 0, 1, NULL, 0),
(37, 'ios-app', 'iOS App', 18, 0, 'iOS App', '', '', 187, 185, 0, 1, NULL, 0),
(38, 'android-app', 'Android App', 18, 0, 'Android App', '', '', 182, 180, 0, 2, NULL, 0),
(39, 'login-password', 'Login & Password', 13, 0, 'Login & Password', '', '', 177, 175, 0, 1, NULL, 0),
(40, 'account-settings', 'Account Settings', 13, 0, 'Account Settings', '', '', 172, 170, 0, 2, NULL, 0),
(41, 'account-security', 'Account Security', 13, 0, 'Account Security', '', '', 167, 165, 0, 3, NULL, 0),
(42, 'notifications', 'Notifications', 13, 0, 'Notifications', '', '', 162, 160, 0, 4, NULL, 0),
(43, 'account-deletion', 'Account Deletion', 13, 0, 'Account Deletion', '', '', 157, 155, 0, 5, NULL, 0),
(44, 'blocking-un-blocking', 'Blocking and Un-blocking', 15, 0, 'Blocking and Un-blocking', '', '', 272, 270, 0, 1, NULL, 0),
(45, 'reporting', 'Reporting', 15, 0, 'Reporting', '', '', 267, 265, 0, 2, NULL, 0),
(47, 'your-privacy', 'Your Privacy', 15, 0, 'Your Privacy', '', '', 242, 240, 0, 3, NULL, 0),
(48, 'hacked-faked-accounts', 'Hacked & Faked Accounts', 15, 0, 'Hacked & Faked Accounts', '', '', 237, 235, 0, 4, NULL, 0),
(49, 'adding-friend', 'Adding Friend', 0, 21, 'Adding Friend', '', '', 147, 145, 0, 1, NULL, 0),
(50, 'who-can-follow-me', 'Who Can Follow Me', 0, 21, 'Who Can Follow Me', '', '', 142, 140, 0, 2, NULL, 0),
(51, 'un-friending', 'Un-Friending', 0, 21, 'Un-Friending', '', '', 137, 135, 0, 3, NULL, 0),
(52, 'who-can-see-what-i-share', 'Who Can See What I Share', 0, 47, 'Who Can See What I Share', '', '', 262, 260, 0, 1, NULL, 0),
(53, 'manage-what-you-ve-shared', 'Manage What You\'ve Shared', 0, 47, 'Manage What You\'ve Shared', '', '', 257, 255, 0, 2, NULL, 0),
(54, 'tagging', 'Tagging', 0, 47, 'Tagging', '', '', 252, 250, 0, 3, NULL, 0),
(55, 'who-can-find-me', 'Who Can Find Me', 0, 47, 'Who Can Find Me', '', '', 247, 245, 0, 4, NULL, 0),
(56, 'reporting-abuse', 'Reporting Abuse', 16, 0, 'Reporting Abuse', '', '', 227, 225, 0, 1, NULL, 0),
(57, 'how-to-report-things', 'How to Report Things', 0, 56, 'How to Report Things', '', '', 232, 230, 0, 1, NULL, 0),
(58, 'reporting-a-problem-with-us', 'Reporting a Problem with Us', 16, 0, 'Reporting a Problem with Us', '', '', 222, 220, 0, 2, NULL, 0),
(59, 'intellectual-property', 'Intellectual Property', 16, 0, 'Intellectual Property', '', '', 207, 205, 0, 3, NULL, 0),
(60, 'copyright', 'Copyright', 0, 59, 'Copyright', '', '', 217, 215, 0, 1, NULL, 0),
(61, 'trademark', 'Trademark', 0, 59, 'Trademark', '', '', 212, 210, 0, 2, NULL, 0),
(62, 'about-our-policies', 'About Our Policies', 16, 0, 'About Our Policies', '', '', 202, 200, 0, 4, NULL, 0),
(63, 'troubleshooting-tips', 'Troubleshooting tips', 17, 0, 'Troubleshooting tips', '', '', 197, 195, 0, 1, NULL, 0),
(64, 'trouble-with-emails', 'Trouble with Emails', 17, 0, 'Trouble with Emails', '', '', 192, 190, 0, 2, NULL, 0);");


$categoriesTable = Engine_Api::_()->getDbTable('categories', 'sestutorial');
$tableName = $categoriesTable->info('name');

$category_select = $categoriesTable->select()
        ->from($tableName);
$categories = $categoriesTable->fetchAll($category_select);


$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sestutorial' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category";

foreach($categories as $category) {

  $title = str_replace(" ", "-", strtolower($category->title));
  $iconPath = $path . DIRECTORY_SEPARATOR . $title . '.png';

  if (file_exists($iconPath)) {
  
    $file_ext = pathinfo($iconPath); 
    $file_ext = $file_ext['extension']; 
    $storage = Engine_Api::_()->getItemTable('storage_file');

    $storageObject = $storage->createFile($iconPath, array(
      'parent_id' => $category->getIdentity(),
      'parent_type' => $category->getType(),
      'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
    ));

    $category->thumbnail = $storageObject->file_id;
    $category->save();
    $category->cat_icon = $storageObject->file_id;
    $category->save();
  }
}

//Tutorial home page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sestutorial_index_home')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sestutorial_index_home',
      'displayname' => 'SNS - Tutorials - Tutorials Home Page',
      'title' => 'Tutorials Home Page',
      'description' => 'This page is the tutorial home page.',
      'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  // Insert top
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1
  ));
  $top_id = $db->lastInsertId();
  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $page_id,
      'order' => 2
  ));
  $main_id = $db->lastInsertId();
  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 5,
  ));
  $main_right_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.banner-search',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"backgroundimage":"public\/admin\/search-banner.jpg","logo":"0","showfullwidth":"full","autosuggest":"1","height":"400","bannertext":"How Can We Help You Today?","description":"Please try to search your queries from our Help Center. But, if you are still not getting the resolution, please contact our support team.","textplaceholder":"Type your keyword for search","template":"2","tutorialcriteria":"like_count","limit":"6","title":"","nomobile":"0","name":"sestutorial.banner-search"}',
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
  ));

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.home-category',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","showsubcategory":"0","showinformation":["description","caticon"],"criteria":"admin_order","descriptionlimit":"100","mainblockheight":"100","mainblockwidth":"100","categoryiconheight":"50","categoryiconwidth":"50","limit_data":"6","limitsubcat":"4","title":"Browse help topics","nomobile":"0","name":"sestutorial.home-category"}',
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.popular-tutorial',
      'page_id' => $page_id,
      'parent_content_id' => $main_right_id,
      'order' => $widgetOrder++,
      'params' => '{"showinformation":["viewAllLink"],"tutorialcriteria":"creation_date","tutorialtitlelimit":"25","tutorialdescriptionlimit":"50","limitdatatutorial":"7","title":"Popular Questions","nomobile":"0","name":"sestutorial.popular-tutorial"}',
  ));
}

//Tutorials Browse Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sestutorial_index_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sestutorial_index_browse',
      'displayname' => 'SNS - Tutorials - Browse Tutorials Page',
      'title' => 'Browse Tutorials Page',
      'description' => 'This page is the browse tutorials page.',
      'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
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
      'order' => 3,
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'left',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 4,
  ));
  $main_left_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 5,
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-search',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Browse Search","name":"sestutorial.browse-search"}',
  ));

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.table-of-content',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"expanded","title":"","nomobile":"0","name":"sestutorial.table-of-content"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.tag-cloud-tutorials',
      'page_id' => $page_id,
      'parent_content_id' => $main_left_id,
      'order' => $widgetOrder++,
      'params' => '{"color":"#00f","type":"tab","text_height":"15","height":"300","itemCountPerPage":"50","title":"Popular Tags","nomobile":"0","name":"sestutorial.tag-cloud-tutorials"}',
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-tutorials',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"viewtype":"listview","showinformation":["likecount","viewcount","commentcount","ratingcount","description","readmorelink"],"paginationType":"0","tutorialtitlelimit":"60","tutorialdescriptionlimit":"200","limitdatatutorial":"10","gridblockheight":"250","title":"Browse Tutorials","nomobile":"0","name":"sestutorial.browse-tutorials"}',
  ));
}

//Browse Tags Page
$page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sestutorial_index_tags')
            ->limit(1)
            ->query()
            ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sestutorial_index_tags',
    'displayname' => 'SNS - Tutorials - Browse Tags Page',
    'title' => 'Tutorials Browse Tags Page',
    'description' => 'This page displays the tutorial tags.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
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
    'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert main-right
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 7,
  ));
  $main_right_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.tag-tutorials',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.popular-tutorial',
    'page_id' => $page_id,
    'parent_content_id' => $main_right_id,
    'order' => $widgetOrder++,
    'params' => '{"showinformation":["likecount","viewcount","commentcount","description","viewAllLink"],"tutorialcriteria":"like_count","tutorialtitlelimit":"25","tutorialdescriptionlimit":"50","limitdatatutorial":"5","title":"Most Liked Tutorials","nomobile":"0","name":"sestutorial.popular-tutorial"}',
  ));
}

//Tutorial Category Browse Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sestutorial_category_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sestutorial_category_browse',
      'displayname' => 'SNS - Tutorials - Browse Categories Page',
      'title' => 'Browse Categories Page',
      'description' => 'This page is the browse tutorials categories page.',
      'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
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
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.banner-search',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"backgroundimage":"public\/admin\/tutorial-category-banner.jpg","logo":"public\/admin\/online-community.jpg","showfullwidth":"half","autosuggest":"1","height":"400","bannertext":"How Can We Help You Today?","description":"","textplaceholder":"Type your keyword for search","template":"1","tutorialcriteria":"like_count","limit":"5","title":"","nomobile":"0","name":"sestutorial.banner-search"}',
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.categories',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"showinformation":["title"],"mainblockheight":"210","mainblockwidth":"200","categoryiconheight":"100","categoryiconwidth":"100","title":"","nomobile":"0","name":"sestutorial.categories"}',
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.category-associate-tutorial',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"viewtype":"onlytutorialview","showinformation":["likecount","viewcount","commentcount","ratingcount","description"],"tutorialtitlelimit":"60","tutorialdescriptionlimit":"200","limit_data":"6","tutorialcriteria":"creation_date","limitdatatutorial":"3","gridblockheight":"150","title":"Category Accociated Tutorials","nomobile":"0","name":"sestutorial.category-associate-tutorial"}',
  ));
}

//Tutorial Category View Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sestutorial_category_index')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sestutorial_category_index',
      'displayname' => 'SNS - Tutorials - Category View Page',
      'title' => 'Category View Page',
      'description' => 'This page is the category view page.',
      'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
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
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.browse-menu',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.category-banner',
      'page_id' => $page_id,
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
  ));
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sestutorial.category-view-page',
      'page_id' => $page_id,
      'parent_content_id' => $main_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"subcategoryview","viewtype":"onlytutorialview","tutorialcriteria":"creation_date","showinformation":["caticon","viewall"],"showinformation1":["likecount","viewcount","commentcount","ratingcount","description","readmorelink"],"tutorialtitlelimit":"60","tutorialdescriptionlimit":"200","gridblockheight":"250","limit_data":"10","limitdatatutorial":"4","title":"","nomobile":"0","name":"sestutorial.category-view-page"}',
  ));
}

// tutorial profile page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sestutorial_index_view')
  ->limit(1)
  ->query()
  ->fetchColumn();

// insert if it doesn't exist yet
if( !$page_id ) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sestutorial_index_view',
    'displayname' => 'SNS - Tutorials - Tutorial View Page',
    'title' => 'Tutorial View',
    'description' => 'This page displays a tutorial entry.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  
  // Insert top
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $page_id,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $top_id,
      'order' => 6
  ));
  $top_middle_id = $db->lastInsertId();
  
  // Insert main
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $page_id,
  ));
  $main_id = $db->lastInsertId();
  
  // Insert left
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'left',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $left_id = $db->lastInsertId();
  
  // Insert middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 2,
  ));
  $middle_id = $db->lastInsertId();
  
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.breadcrumb',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.table-of-content',
    'page_id' => $page_id,
    'parent_content_id' => $left_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"expanded","title":"","nomobile":"0","name":"sestutorial.table-of-content"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.related-tutorials',
    'page_id' => $page_id,
    'parent_content_id' => $left_id,
    'order' => $widgetOrder++,
    'params' => '{"showinformation":"","tutorialcriteria":"creation_date","tutorialtitlelimit":"25","tutorialdescriptionlimit":"50","limitdatatutorial":"4","title":"Related Tutorials","nomobile":"0","name":"sestutorial.related-tutorials"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sestutorial.tutorial-view-page',
    'page_id' => $page_id,
    'parent_content_id' => $middle_id,
    'order' => $widgetOrder++,
    'params' => '{"showinformation":["likecount","viewcount","commentcount","ratingcount","category","tags","socialshare","siteshare","report","showhelpful"],"title":"","nomobile":"0","name":"sestutorial.tutorial-view-page"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.comments',
    'page_id' => $page_id,
    'parent_content_id' => $middle_id,
    'order' => $widgetOrder++,
    'params' => '{"title":"Comments"}',
  ));
}

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sestutorial_admin_main_manageimport", "sestutorial", "Import Tutorials", "", \'{"route":"admin_default","module":"sestutorial","controller":"manage-imports", "action":"index"}\', "sestutorial_admin_main", "", 800);');

$db->query('ALTER TABLE `engine4_sestutorial_askquestions` ADD `tutorial_id` INT(11) NOT NULL DEFAULT "0";');
$db->query('ALTER TABLE `engine4_sestutorial_askquestions` ADD `answered` VARCHAR(255) NOT NULL;');

$db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("sestutorial_tutorial_like", "sestutorial", \'{item:$subject} likes the tutorial {item:$object}:\', 1, 7, 1, 1, 1, 1);');

$db->query("UPDATE `engine4_core_menuitems` SET `name` = 'core_main_sestutorial' WHERE `engine4_core_menuitems`.`name` = 'sestutorial_main_sestutorial';");

$db->query('ALTER TABLE `engine4_sestutorial_tutorials` ADD `order` INT(11) NOT NULL;');


$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
("sestutorial_main", "standard", "SNS - Multi - Use Tutorials - Main Navigation Menu");');

$db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "0" WHERE `engine4_core_menuitems`.`name` = "sestutorial_mini_sestutorial";');
$db->query('UPDATE `engine4_core_menuitems` SET `enabled` = "0" WHERE `engine4_core_menuitems`.`name` = "sestutorial_footer_sestutorial";');

$db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
("sestutorial_new", "sestutorial", \'{item:$subject} posted a new Tutorial {item:$object}:\', 1, 5, 1, 3, 1, 1);');

$db->query('ALTER TABLE `engine4_sestutorial_tutorial_fields_meta` ADD `icon` TEXT NULL DEFAULT NULL;');
