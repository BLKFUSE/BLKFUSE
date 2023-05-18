<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$ses_field = $db->query('SHOW COLUMNS FROM engine4_user_fields_meta LIKE \'ses_field\'')->fetch();
if (empty($ses_field)) {
  $db->query('ALTER TABLE `engine4_user_fields_meta` ADD `ses_field` TINYINT(1) NOT NULL DEFAULT "0";');
}

//Browse Members Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_index_browse')
  ->limit(1)
  ->query()
  ->fetchColumn();
if( !$page_id ) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_index_browse',
    'displayname' => 'SNS - Ultimate Members - Browse Members Page',
    'title' => 'Browse Members',
    'description' => 'This page show all members of your site.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  $pageName = 'sesmember_index_browse';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
}

//Nearest Member Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_index_nearest-member')
  ->limit(1)
  ->query()
  ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_index_nearest-member',
    'displayname' => 'SNS - Ultimate Members - Nearest Member Page',
    'title' => 'Nearest Member',
    'description' => 'This page show nearest member based on current viewer.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
   $pageName = 'sesmember_index_nearest-member';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
}

//Top Members Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_index_top-members')
  ->limit(1)
  ->query()
  ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_index_top-members',
    'displayname' => 'SNS - Ultimate Members - Top Members Page',
    'title' => 'Top Members',
    'description' => 'This page show top members based on ratings and reviews.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  $pageName = 'sesmember_index_top-members';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
  
}

//Browse Members Review Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_review_browse')
  ->limit(1)
  ->query()
  ->fetchColumn();
// insert if it doesn't exist yet
if( !$page_id ) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_review_browse',
    'displayname' => 'SNS - Ultimate Member - Browse Members Review Page',
    'title' => 'Member Browse Reviews',
    'description' => 'This page show member reviews.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
   $pageName = 'sesmember_review_browse';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
 
}

//Members Location Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_index_locations')
  ->limit(1)
  ->query()
  ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_index_locations',
    'displayname' => 'SNS - Ultimate Members - Members Location Page',
    'title' => 'Member Locations',
    'description' => 'This page show member locations.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();

  $pageName = 'sesmember_index_locations';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
}

//Pinboard View Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_index_pinborad-view-members')
  ->limit(1)
  ->query()
  ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_index_pinborad-view-members',
    'displayname' => 'SNS - Ultimate Members - Pinboard View Page',
    'title' => 'Show Member in Pinboard View',
    'description' => 'This page show all members in pinboard view.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  $pageName = 'sesmember_index_pinborad-view-members';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
  
}

//Review View Page
$page_id = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'sesmember_review_view')
  ->limit(1)
  ->query()
  ->fetchColumn();
if (!$page_id) {
  $widgetOrder = 1;
  $db->insert('engine4_core_pages', array(
    'name' => 'sesmember_review_view',
    'displayname' => 'SNS - Ultimate Members - Review View Page',
    'title' => 'Member Review View',
    'description' => 'This page displays a review entry.',
    'custom' => 0,
  ));
  $page_id = $db->lastInsertId();
  $pageName = 'sesmember_review_view';
  include APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
}

$db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES ("sesmember_follow_create", "sesmember", \'{item:$subject} create a {var:$itemtype} {item:$object}.\', 0, "");');

//Member Profile Page
$page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'user_profile_index')
            ->limit(1)
            ->query()
            ->fetchColumn();
if($page_id) {
  $main_id = $db->select()
            ->from('engine4_core_content', 'content_id')
            ->where('page_id = ?', $page_id)
            ->where('name = ?', 'main')
            ->limit(1)
            ->query()
            ->fetchColumn();

  $left_id = $db->select()
          ->from('engine4_core_content', 'content_id')
          ->where('page_id = ?', $page_id)
          ->where('name = ?', 'left')
          ->limit(1)
          ->query()
          ->fetchColumn();

  $right_id = $db->select()
          ->from('engine4_core_content', 'content_id')
          ->where('page_id = ?', $page_id)
          ->where('name = ?', 'right')
          ->limit(1)
          ->query()
          ->fetchColumn();

  if($left_id) {
    $widgets = $db->select()
    ->from('engine4_core_content')
    ->where('parent_content_id = ?', $left_id)
    ->query()
    ->fetchAll();

    if(!$right_id) {
      $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $page_id,
      'parent_content_id' => $main_id,
      'order' => 1,
      ));
      $right_id = $db->lastInsertId();
    }
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.follow-button',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'order' => 0,
    ));
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.like-button',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"title":""}',
      'order' => 0,
    ));
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.review-add',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'order' => 0,
    ));

    $infoContentId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('name = ?', 'user.profile-info')
    ->limit(1)
    ->query()
    ->fetchColumn();

    if($infoContentId) {
      $db->delete('engine4_core_content', array('page_id =?' => $page_id, 'content_id =?' => $infoContentId));
    }

    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.profile-info',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"show_criteria":["location","like","rating","view","friendCount","mutualFriendCount","profileType","joinInfo","updateInfo","network"],"title":"Information","nomobile":"0","name":"sesmember.profile-info"}',
      'order' => 0,
    ));

    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.member-featured-photos',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"title":"Featured Photos","name":"sesmember.member-featured-photos"}',
      'order' => 0,
    ));

    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.profile-user-ratings',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"title":"User\'s Ratings","name":"sesmember.profile-user-ratings"}',
      'order' => 0,
    ));
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.profile-user-review-votes',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"title":"Review Votes","name":"sesmember.profile-user-review-votes"}',
      'order' => 0,
    ));
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesbasic.column-layout-width',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"layoutColumnWidthType":"px","columnWidth":"300","title":"","nomobile":"0","name":"sesbasic.column-layout-width"}',
      'order' => 0,
    ));
    $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesmember.member-liked',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'params' => '{"viewType":"thumbView","imageType":"square","showLimitData":"1","show_criteria":["title"],"grid_title_truncation":"45","list_title_truncation":"45","height":"66","width":"66","photo_height":"160","photo_width":"250","limit_data":"9","title":"Member Liked Me","nomobile":"0","name":"sesmember.member-liked"}',
      'order' => 0,
    ));
    foreach($widgets as $widget) {
      $db->query("UPDATE `engine4_core_content` SET `parent_content_id` = '" . $right_id . "' WHERE parent_content_id = " . $left_id);
    }
    $db->delete('engine4_core_content', array('content_id =?' => $left_id));
  }

  $select = new Zend_Db_Select($db);
  $select->from('engine4_core_content')
        ->where('type = ?', 'widget')
        ->where('name = ?', 'core.container-tabs')
        ->where('page_id = ?', $page_id)
        ->limit(1);
  $tab_id = $select->query()->fetchObject();
  if( $tab_id && @$tab_id->content_id ) {
    $tab_id = $tab_id->content_id;
  } else {
    $tab_id = null;
  }

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.user-map',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"title":"Map","titleCount":true,"name":"sesmember.user-map"}',
    'order' => 999,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.member-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"stats":["likeCount","commentCount","viewCount","title","share","report","pros","cons","description","recommended","postedBy","parameter","creationDate","rating"],"title":"Reviews","nomobile":"0","name":"sesmember.member-reviews"}',
    'order' => 999,
  ));

  $infoContentId = $db->select()
              ->from('engine4_core_content', 'content_id')
              ->where('page_id = ?', $page_id)
              ->where('name = ?', 'user.profile-friends')
              ->limit(1)
              ->query()
              ->fetchColumn();
  if($infoContentId) {
    $db->delete('engine4_core_content', array('page_id =?' => $page_id, 'content_id =?' => $infoContentId));
  }

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.profile-friends',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","likeButton","friendButton","followButton","message","likemainButton","socialSharing","title","location","like","rating","view","friendCount","mutualFriendCount","profileType","age"],"title":"Friends","nomobile":"0","name":"sesmember.profile-friends"}',
    'order' => 999,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.recently-viewed-by-me',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"viewType":"gridOutside","imageType":"square","showLimitData":"1","order":"","criteria":"5","info":"creation_date","show_criteria":["friendButton","followButton","message","socialSharing","title","location","like","rating","view","friendCount","mutualFriendCount","age"],"grid_title_truncation":"45","list_title_truncation":"45","height":"300","width":"277","photo_height":"200","photo_width":"250","limit_data":"9","title":"Recently Viewed By Me","nomobile":"0","name":"sesmember.recently-viewed-by-me"}',
    'order' => 999,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.recently-viewed-me',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"viewType":"gridInside","imageType":"square","showLimitData":"1","order":"","criteria":"5","info":"creation_date","show_criteria":["friendButton","followButton","message","socialSharing","title","location","like","rating","view","friendCount","mutualFriendCount","profileType","age"],"grid_title_truncation":"45","list_title_truncation":"45","height":"320","width":"205","photo_height":"200","photo_width":"200","limit_data":"9","title":"Recently Viewed Me","nomobile":"0","name":"sesmember.recently-viewed-me"}',
    'order' => 999,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.followers',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"viewType":"list","imageType":"square","showLimitData":"1","show_criteria":["title","friendCount","mutualFriendCount"],"grid_title_truncation":"45","list_title_truncation":"45","height":"180","width":"180","photo_height":"160","photo_width":"250","limit_data":"10","title":"Followers","nomobile":"0","name":"sesmember.followers"}',
    'order' => 999,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.following',
    'page_id' => $page_id,
    'parent_content_id' => $tab_id,
    'params' => '{"viewType":"gridInside","imageType":"square","showLimitData":"1","show_criteria":["followButton","title"],"grid_title_truncation":"45","list_title_truncation":"45","height":"185","width":"162","photo_height":"100","photo_width":"100","limit_data":"10","title":"Following","nomobile":"0","name":"sesmember.following"}',
    'order' => 999,
  ));
}

//Member Home Page
$page_id = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'user_index_home')
        ->limit(1)
        ->query()
        ->fetchColumn();
if($page_id) {
  $right_id = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'container')
    ->where('name = ?', 'right')
    ->limit(1)
    ->query()
    ->fetchColumn();

  $topContainerId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'container')
    ->where('name = ?', 'main')
    ->limit(1)
    ->query()
    ->fetchColumn();

  $middleContainerId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'container')
    ->where('name = ?', 'middle')
    ->where('parent_content_id = ?', $topContainerId)
    ->limit(1)
    ->query()
    ->fetchColumn();

  $infoContentId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('type = ?', 'widget')
    ->where('name = ?', 'user.home-photo')
    ->limit(1)
    ->query()
    ->fetchColumn();

  if($infoContentId) {
    $db->update('engine4_core_content', array('name' => 'sesmember.home-photo', 'params' => '{"show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","vipLabel","title"],"title":"","nomobile":"0","name":"sesmember.home-photo"}'), array('content_id =?' => $infoContentId));
  }

  $infoContentId = $db->select()
    ->from('engine4_core_content', 'content_id')
    ->where('page_id = ?', $page_id)
    ->where('name = ?', 'user.list-online')
    ->limit(1)
    ->query()
    ->fetchColumn();

  if($infoContentId) {
		$db->update('engine4_core_content', array('name' => 'sesmember.list-online', 'params' => '{"viewType":"thumbView","imageType":"square","show_criteria":"","grid_title_truncation":"45","list_title_truncation":"45","showLimitData":"0","height":"66","width":"66","photo_height":"160","photo_width":"250","limit_data":"6","title":"Online Users","nomobile":"0","name":"sesmember.list-online"}'), array('content_id =?' => $infoContentId));
  }

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.featured-sponsored',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'params' => '{"viewType":"thumbView","imageType":"square","order":"","criteria":"5","info":"most_viewed","showLimitData":"1","show_star":"0","show_criteria":"","grid_title_truncation":"45","list_title_truncation":"45","height":"66","width":"66","photo_height":"160","photo_width":"250","limit_data":"12","title":"Popular Members","nomobile":"0","name":"sesmember.featured-sponsored"}',
    'order' => 0,
  ));
}
