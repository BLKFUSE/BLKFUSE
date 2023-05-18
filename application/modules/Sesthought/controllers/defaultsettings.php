<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: defaultsettings.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$db->query('INSERT IGNORE INTO `engine4_sesthought_categories` (`category_id`, `slug`, `category_name`, `subcat_id`, `subsubcat_id`, `title`, `description`, `color`, `thumbnail`, `cat_icon`, `colored_icon`, `order`, `profile_type_review`, `profile_type`) VALUES
(1, "", "Festive", 0, 0, "", NULL, NULL, 0, 36580, 0, 1, NULL, NULL),
(2, "new-year", "New Year", 1, 0, "", NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(3, "christmas", "Christmas", 1, 0, "", NULL, NULL, 0, 0, 0, 2, NULL, NULL),
(4, "diwali", "Diwali", 1, 0, "", NULL, NULL, 0, 0, 0, 3, NULL, NULL),
(5, "thanksgiving", "Thanksgiving", 1, 0, "", NULL, NULL, 0, 0, 0, 4, NULL, NULL),
(6, "", "Wishes", 0, 0, "", NULL, NULL, 0, 36578, 0, 2, NULL, NULL),
(7, "new-job", "New Job", 6, 0, "", NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(8, "father-s-day", "Father\"s Day", 6, 0, "", NULL, NULL, 0, 0, 0, 2, NULL, NULL),
(9, "mother-s-day", "Mother\"s Day", 6, 0, "", NULL, NULL, 0, 0, 0, 3, NULL, NULL),
(10, "having-a-child", "Having a Child", 6, 0, "", NULL, NULL, 0, 0, 0, 4, NULL, NULL),
(11, "engagement", "Engagement", 6, 0, "", NULL, NULL, 0, 0, 0, 5, NULL, NULL),
(12, "marriage", "Marriage", 6, 0, "", NULL, NULL, 0, 0, 0, 6, NULL, NULL),
(13, "anniversary", "Anniversary", 6, 0, "", NULL, NULL, 0, 0, 0, 7, NULL, NULL),
(14, "birthday", "Birthday", 6, 0, "", NULL, NULL, 0, 0, 0, 8, NULL, NULL),
(15, "", "Feeling", 0, 0, "", NULL, NULL, 0, 36576, 0, 3, NULL, NULL),
(16, "romantic", "Romantic", 15, 0, "", NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(17, "jealous", "Jealous", 15, 0, "", NULL, NULL, 0, 0, 0, 2, NULL, NULL),
(18, "kind", "Kind", 15, 0, "", NULL, NULL, 0, 0, 0, 3, NULL, NULL),
(19, "grateful", "Grateful", 15, 0, "", NULL, NULL, 0, 0, 0, 4, NULL, NULL),
(20, "angry", "Angry", 15, 0, "", NULL, NULL, 0, 0, 0, 5, NULL, NULL),
(21, "sad", "Sad", 15, 0, "", NULL, NULL, 0, 0, 0, 6, NULL, NULL),
(22, "lonely", "Lonely", 15, 0, "", NULL, NULL, 0, 0, 0, 7, NULL, NULL),
(23, "happy", "Happy", 15, 0, "", NULL, NULL, 0, 0, 0, 8, NULL, NULL),
(24, "", "Sports", 0, 0, "", NULL, NULL, 0, 36574, 0, 4, NULL, NULL),
(25, "", "Teamwork", 0, 0, "", NULL, NULL, 0, 36572, 0, 5, NULL, NULL),
(26, "", "Poetry", 0, 0, "", NULL, NULL, 0, 36570, 0, 6, NULL, NULL),
(27, "", "Money", 0, 0, "", NULL, NULL, 0, 36568, 0, 7, NULL, NULL),
(28, "", "Success", 0, 0, "", NULL, NULL, 0, 36566, 0, 8, NULL, NULL),
(29, "", "Education", 0, 0, "", NULL, NULL, 0, 36564, 0, 9, NULL, NULL),
(30, "", "Equailty", 0, 0, "", NULL, NULL, 0, 36562, 0, 10, NULL, NULL),
(31, "", "Death", 0, 0, "", NULL, NULL, 0, 36560, 0, 11, NULL, NULL),
(32, "", "Life", 0, 0, "", NULL, NULL, 0, 36558, 0, 12, NULL, NULL),
(33, "", "Relations", 0, 0, "", NULL, NULL, 0, 36556, 0, 13, NULL, NULL),
(34, "children", "Children", 33, 0, "", NULL, NULL, 0, 0, 0, 1, NULL, NULL),
(35, "son", "Son", 33, 0, "", NULL, NULL, 0, 0, 0, 2, NULL, NULL),
(36, "daughter", "Daughter", 33, 0, "", NULL, NULL, 0, 0, 0, 3, NULL, NULL),
(37, "sister", "Sister", 33, 0, "", NULL, NULL, 0, 0, 0, 4, NULL, NULL),
(38, "brother", "Brother", 33, 0, "", NULL, NULL, 0, 0, 0, 5, NULL, NULL),
(39, "father", "Father", 33, 0, "", NULL, NULL, 0, 0, 0, 6, NULL, NULL),
(41, "friendship", "Friendship", 33, 0, "", NULL, NULL, 0, 0, 0, 7, NULL, NULL),
(42, "love", "Love", 33, 0, "", NULL, NULL, 0, 0, 0, 8, NULL, NULL),
(43, "mother", "Mother", 33, 0, "", NULL, NULL, 0, 0, 0, 9, NULL, NULL),
(44, "", "Parenting", 0, 0, "", NULL, NULL, 0, 36554, 0, 14, NULL, NULL),
(45, "", "Health", 0, 0, "", NULL, NULL, 0, 36552, 0, 15, NULL, NULL),
(46, "", "Religion", 0, 0, "", NULL, NULL, 0, 36550, 0, 16, NULL, NULL),
(47, "", "Funny", 0, 0, "", NULL, NULL, 0, 36548, 0, 17, NULL, NULL),
(48, "", "God", 0, 0, "", NULL, NULL, 0, 36546, 0, 18, NULL, NULL),
(49, "", "Inspirational", 0, 0, "", NULL, NULL, 0, 36544, 0, 19, NULL, NULL),
(50, "", "Motivational", 0, 0, "", NULL, NULL, 0, 36542, 0, 20, NULL, NULL);');



// profile page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesthought_index_index')
  ->limit(1)
  ->query()
  ->fetchColumn();

// insert if it doesn't exist yet
if( !$pageId ) {
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sesthought_index_index',
    'displayname' => 'SNS - Thoughts - Thoughts Browse Page',
    'title' => 'Thought Browse',
    'description' => 'This page lists thought entries.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

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
    'name' => 'sesthought.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $topMiddleId,
    'order' => 1,
  ));
  
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.browse-search',
    'page_id' => $pageId,
    'parent_content_id' => $topMiddleId,
    'order' => 2,
    'params' => '{"viewType":"horizontal","title":"","nomobile":"0","name":"sesthought.browse-search"}',
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.browse-thoughts',
    'page_id' => $pageId,
    'parent_content_id' => $mainMiddleId,
    'order' => 3,
    'params' => '{"stats":["likecount","commentcount","viewcount","postedby","posteddate","source","category","socialSharing","likebutton","permalink"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","width":"250","pagging":"button","limit":"10","title":"","nomobile":"0","name":"sesthought.browse-thoughts"}',
  ));

}

// profile page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesthought_index_manage')
  ->limit(1)
  ->query()
  ->fetchColumn();

// insert if it doesn't exist yet
if( !$pageId ) {
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sesthought_index_manage',
    'displayname' => 'SNS - Thoughts - Thoughts Manage Page',
    'title' => 'My Thought',
    'description' => 'This page lists a user\'s thought entries.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

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
    'name' => 'sesthought.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $topMiddleId,
    'order' => 1,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.content',
    'page_id' => $pageId,
    'parent_content_id' => $mainMiddleId,
    'order' => 1,
  ));
}

//Thought View Page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesthought_index_view')
  ->limit(1)
  ->query()
  ->fetchColumn();

// insert if it doesn't exist yet
if( !$pageId ) {
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sesthought_index_view',
    'displayname' => 'SNS - Thoughts - Thought View Page',
    'title' => 'Thought View',
    'description' => 'This page displays a thought entry.',
    'provides' => 'subject=sesthought_thought',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
  ));
  $mainId = $db->lastInsertId();

  // Insert left
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'left',
    'page_id' => $pageId,
    'parent_content_id' => $mainId,
    'order' => 1,
  ));
  $leftId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $mainId,
    'order' => 2,
  ));
  $middleId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.other-thoughts',
    'page_id' => $pageId,
    'parent_content_id' => $leftId,
    'order' => 1,
    'params' => '{"title":"Most Liked Thoughts","viewType":"list","popularity":"like_count","information":["likeCount","commentCount","viewCount","socialSharing","likebutton","permalink"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","width":"300","description_truncation":"75","limit":"3","nomobile":"0","name":"sesthought.other-thoughts"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.other-thoughts',
    'page_id' => $pageId,
    'parent_content_id' => $leftId,
    'order' => 2,
    'params' => '{"title":"Popular Thoughts","viewType":"grid","popularity":"view_count","information":["likeCount","commentCount","viewCount","postedby","posteddate","socialSharing","likebutton","permalink"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","width":"300","description_truncation":"60","limit":"3","nomobile":"0","name":"sesthought.other-thoughts"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.breadcrumb',
    'page_id' => $pageId,
    'parent_content_id' => $middleId,
    'order' => 3,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.content',
    'page_id' => $pageId,
    'parent_content_id' => $middleId,
    'order' => 4,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.comments',
    'page_id' => $pageId,
    'parent_content_id' => $middleId,
    'order' => 5,
  ));
}


//Thought Category Browse Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sesthought_category_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sesthought_category_browse',
      'displayname' => 'SNS - Thoughts - Thought Category Browse Page',
      'title' => 'Thought Category Browse',
      'description' => 'This page is the browse thoughts categories page.',
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
  
  // Insert left
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $rightId = $db->lastInsertId();
  
  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 6
  ));
  $main_middle_id = $db->lastInsertId();
	
	$db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesthought.browse-menu',
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '',
  ));
  
	$db->insert('engine4_core_content', array(
      'page_id' => $page_id,
      'type' => 'widget',
      'name' => 'sesthought.recently-viewed-thought',
      'parent_content_id' => $top_middle_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"grid","criteria":"by_myfriend","information":["likeCount","commentCount","viewCount","socialSharing","likebutton","permalink"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","width":"250","description_truncation":"60","limit":"4","title":"Friends Also Viewed","nomobile":"0","name":"sesthought.recently-viewed-thought"}',
  ));
  
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.category-icons',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"heighticon":"50px","widthicon":"50px","criteria":"most_thought","showStats":["title","countThoughts"],"limit_data":"30","title":"","nomobile":"0","name":"sesthought.category-icons"}',
  ));
  
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesthought.popularity-thoughts',
    'page_id' => $page_id,
    'parent_content_id' => $rightId,
    'order' => $widgetOrder++,
    'params' => '{"title":"Most Liked Thoughts","viewType":"list","popularity":"like_count","information":["likeCount","commentCount","viewCount","socialSharing","likebutton","permalink"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","width":"250","description_truncation":"60","limit":"3","nomobile":"0","name":"sesthought.popularity-thoughts"}',
  ));
}

$offtheday = $db->query('SHOW COLUMNS FROM engine4_sesthought_thoughts LIKE \'offtheday\'')->fetch();
if (empty($offtheday)) {
  $db->query("ALTER TABLE `engine4_sesthought_thoughts` ADD `offtheday` tinyint(1)	NOT NULL DEFAULT '0';");
}
$starttime = $db->query('SHOW COLUMNS FROM engine4_sesthought_thoughts LIKE \'starttime\'')->fetch();
if (empty($starttime)) {
  $db->query("ALTER TABLE `engine4_sesthought_thoughts` ADD `starttime` DATE DEFAULT NULL;");
}

$endtime = $db->query('SHOW COLUMNS FROM engine4_sesthought_thoughts LIKE \'endtime\'')->fetch();
if (empty($endtime)) {
  $db->query("ALTER TABLE `engine4_sesthought_thoughts` ADD `endtime` DATE DEFAULT NULL;");
}
$db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES ("sesthought_thought_like", "sesthought", \'{item:$subject} likes the thought {item:$object}:\', 1, 5, 1, 1, 1, 1);');
$db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES ("sesthought_thought_like", "sesthought", \'{item:$subject} has liked your thought {item:$object}.\', 0, "");');

$db->query('ALTER TABLE `engine4_sesthought_thoughts` ADD `thoughttitle` VARCHAR(255) NULL;');
$db->query('ALTER TABLE `engine4_sesthought_thoughts` ADD `mediatype` TINYINT(1) NOT NULL DEFAULT "1";');
$db->query('ALTER TABLE `engine4_sesthought_thoughts` ADD `code` TEXT NOT NULL;');

$db->query('UPDATE `engine4_core_menuitems` SET `params` = \'{"route":"sesthought_general","action":"create", "class":"sessmoothbox"}\' WHERE `engine4_core_menuitems`.`name` = "sesthought_main_create";');

//Default Privacy Set Work
$permissionsTable = Engine_Api::_()->getDbTable('permissions', 'authorization');
foreach (Engine_Api::_()->getDbTable('levels', 'authorization')->fetchAll() as $level) {
  $form = new Sesthought_Form_Admin_Settings_Level(array(
      'public' => ( engine_in_array($level->type, array('public')) ),
      'moderator' => ( engine_in_array($level->type, array('admin', 'moderator'))),
  ));
  $values = $form->getValues();
  $valuesForm = $permissionsTable->getAllowed('sesthought_thought', $level->level_id, array_keys($form->getValues()));
  $form->populate($valuesForm);
  if ($form->defattribut)
    $form->defattribut->setValue(0);
  $db = $permissionsTable->getAdapter();
  $db->beginTransaction();
  try {
    $nonBooleanSettings = $form->nonBooleanFields();
    $permissionsTable->setAllowed('sesthought_thought', $level->level_id, $values, '', $nonBooleanSettings);
    // Commit
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    throw $e;
  }
}
