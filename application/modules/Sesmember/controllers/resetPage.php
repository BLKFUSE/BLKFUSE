<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: resetPage.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();
$widgetOrder = 1;

if($pageName == 'sesmember_index_browse') {
	 $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $page_id,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $page_id,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 2,
  ));
  $main_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-search',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPrated","featured","sponsored","verified"],"view":["0","1","3","week","month"],"default_search_type":"creation_date ASC","show_advanced_search":"1","network":"yes","alphabet":"yes","friend_show":"yes","search_title":"yes","browse_by":"yes","location":"yes","kilometer_miles":"yes","country":"yes","state":"yes","city":"yes","zip":"yes","member_type":"yes","has_photo":"yes","is_online":"yes","is_vip":"yes","title":"","nomobile":"0","name":"sesmember.browse-search"}'
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-members',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"enableTabs":["list","advlist","grid","advgrid","pinboard","map"],"openViewType":"advlist","show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","vipLabel","message","friendButton","followButton","viewDetailsLink","likemainButton","socialSharing","like","location","rating","view","title","friendCount","mutualFriendCount","profileType","age","pinboardSlideshow"],"limit_data":"12","pagging":"button","order":"mostSPviewed","show_item_count":"1","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","main_height":"350","main_width":"180","height":"220","width":"300","photo_height":"350","info_height":"315","advgrid_height":"180","pinboard_width":"350","title":"","nomobile":"0","name":"sesmember.browse-members"}',

  ));
}
if($pageName == 'sesmember_index_nearest-member') {
	//Insert top
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $page_id,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  //Insert main
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $page_id,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  //Insert top-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  //Insert main-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 2,
  ));
  $main_middle_id = $db->lastInsertId();

  //Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-search',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPrated","featured","sponsored","verified"],"view":["0","1","3","week","month"],"default_search_type":"creation_date ASC","show_advanced_search":"yes","network":"no","alphabet":"no","friend_show":"no","search_title":"yes","browse_by":"yes","location":"yes","kilometer_miles":"yes","country":"yes","state":"yes","city":"yes","zip":"yes","member_type":"yes","has_photo":"no","is_online":"no","is_vip":"no","title":"","nomobile":"0","name":"sesmember.browse-search"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-members',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"enableTabs":["map"],"openViewType":"list","show_criteria":"","limit_data":"20","profileFieldCount":"5","pagging":"auto_load","order":null,"show_item_count":"0","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","main_height":"224","main_width":"250","height":"160","width":"250","photo_height":"160","photo_width":"282","info_height":"200","advgrid_height":"260","advgrid_width":"282","pinboard_width":"250","title":"","nomobile":"0","name":"sesmember.browse-members"}',
  ));
}
if($pageName == 'sesmember_index_top-members') {
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

  // Insert main-right
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $right_id = $db->lastInsertId();

  //Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-search-toprated',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'params' => '{"view_type":"vertical","view":["0","1","3","week","month"],"show_advanced_search":"yes","network":"no","alphabet":"no","friend_show":"yes","search_title":"yes","location":"yes","kilometer_miles":"yes","country":"no","state":"no","city":"no","zip":"no","member_type":"yes","has_photo":"yes","is_online":"yes","is_vip":"no","title":"","nomobile":"0","name":"sesmember.browse-search-toprated"}',
    'order' => $widgetOrder++,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.top-rated-members',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","vipLabel","likeButton","friendButton","followButton","message","socialSharing","title","location","like","rating","view","friendCount","mutualFriendCount","profileType","age"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","rating_graph":"1","list_title_truncation":"45","height":"185","width":"200","pagging":"button","limit_data":"12","title":"Top Rated Members","nomobile":"0","name":"sesmember.top-rated-members"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.popular-featured-verified-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"info":"featured","imageType":"square","showLimitData":"1","show_criteria":["title","description","by"],"list_title_truncation":"45","review_description_truncation":"80","limit_data":"3","title":"Featured Reviews","nomobile":"0","name":"sesmember.popular-featured-verified-reviews"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.popular-featured-verified-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"info":"most_rated","imageType":"rounded","showLimitData":"1","show_criteria":["title","like","view","comment","rating","description","by"],"list_title_truncation":"45","review_description_truncation":"45","limit_data":"3","title":"Most Rated Members","nomobile":"0","name":"sesmember.popular-featured-verified-reviews"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.featured-sponsored',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"list","imageType":"square","order":"","criteria":"6","info":"creation_date","showLimitData":"0","show_star":"0","show_criteria":["verifiedLabel","title","like","rating","view"],"grid_title_truncation":"45","list_title_truncation":"45","height":"180","width":"180","photo_height":"160","photo_width":"250","limit_data":"5","title":"Verified Members","nomobile":"0","name":"sesmember.featured-sponsored"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.top-reviewers',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"list","imageType":"square","show_criteria":["title","rating"],"grid_title_truncation":"45","list_title_truncation":"45","showLimitData":"0","height":"180","width":"180","photo_height":"160","photo_width":"250","limit_data":"5","title":"Top Reviewers","nomobile":"0","name":"sesmember.top-reviewers"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesbasic.column-layout-width',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"layoutColumnWidthType":"px","columnWidth":"300","title":"","nomobile":"0","name":"sesbasic.column-layout-width"}',
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.popular-featured-verified-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $right_id,
    'order' => $widgetOrder++,
    'params' => '{"info":"most_commented","imageType":"rounded","showLimitData":"1","show_criteria":["title","like","view","comment","rating","verifiedLabel","featuredLabel","description","by"],"list_title_truncation":"45","review_description_truncation":"45","limit_data":"2","title":"Most Commented Reviews","nomobile":"0","name":"sesmember.popular-featured-verified-reviews"}',
  ));
}
if($pageName == 'sesmember_review_browse') {
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

  // Insert main-left
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'left',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_left_id = $db->lastInsertId();

  // Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.review-of-the-day',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"gridOutside","show_criteria":["title","like","view","rating","featuredLabel","verifiedLabel","socialSharing","likeButton"],"grid_title_truncation":"45","list_title_truncation":"45","height":"180","width":"180","photo_height":"160","photo_width":"250","title":"Review of the Day","nomobile":"0","name":"sesmember.review-of-the-day"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-review-search',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"view_type":"vertical","review_title":"1","view":["likeSPcount","viewSPcount","commentSPcount","mostSPrated","leastSPrated","usefulSPcount","funnySPcount","coolSPcount","verified","featured"],"review_stars":"1","network":"1","title":"Review Browse Search","nomobile":"0","name":"sesmember.browse-review-search"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesbasic.column-layout-width',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"layoutColumnWidthType":"px","columnWidth":"300","title":"","nomobile":"0","name":"sesbasic.column-layout-width"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.top-reviewers',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"list","imageType":"rounded","show_criteria":["title","like","rating","view"],"grid_title_truncation":"45","list_title_truncation":"45","showLimitData":"0","height":"180","width":"180","photo_height":"160","photo_width":"250","limit_data":"5","title":"Top Reviewers","nomobile":"0","name":"sesmember.top-reviewers"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.featured-sponsored',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"viewType":"thumbView","imageType":"square","order":"","criteria":"5","info":"most_rated","showLimitData":"1","show_star":"0","show_criteria":["title"],"grid_title_truncation":"45","list_title_truncation":"45","height":"84","width":"84","photo_height":"160","photo_width":"250","limit_data":"18","title":"Most Rated Members","nomobile":"0","name":"sesmember.featured-sponsored"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.popular-featured-verified-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"info":"featured","imageType":"rounded","showLimitData":"1","show_criteria":["title","like","view","comment","rating","verifiedLabel","featuredLabel","description","by"],"list_title_truncation":"45","review_description_truncation":"45","limit_data":"3","title":"Featured Reviews","nomobile":"0","name":"sesmember.popular-featured-verified-reviews"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.popular-featured-verified-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
    'params' => '{"info":"verified","imageType":"rounded","showLimitData":"1","show_criteria":["title","like","view","comment","rating","verifiedLabel","featuredLabel","description","by"],"list_title_truncation":"45","review_description_truncation":"45","limit_data":"2","title":"Verfied Reviews","nomobile":"0","name":"sesmember.popular-featured-verified-reviews"}',
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-reviews',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"stats":["likeCount","commentCount","viewCount","title","share","report","pros","cons","description","recommended","postedBy","parameter","creationDate","rating"],"show_criteria":"","pagging":"button","limit_data":"9","title":"","nomobile":"0","name":"sesmember.browse-reviews"}',
  ));
}
if($pageName == 'sesmember_index_locations') {
	//Insert top
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $page_id,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  //Insert main
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $page_id,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  //Insert top-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  //Insert main-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 2,
  ));
  $main_middle_id = $db->lastInsertId();

  //Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-search',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPrated","featured","sponsored","verified"],"view":["0","1","week","month"],"default_search_type":"creation_date ASC","show_advanced_search":"yes","alphabet":"no","friend_show":"yes","search_title":"yes","browse_by":"yes","location":"yes","kilometer_miles":"yes","country":"yes","state":"yes","city":"yes","zip":"yes","title":"","nomobile":"0","name":"sesmember.browse-search"}',
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.member-location',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"location":"","lat":"","lng":"","show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","location","likeButton","friendButton","followButton","message","likemainButton","rating","socialSharing","like","view","profileType","age"],"location-data":null,"title":"","nomobile":"0","name":"sesmember.member-location"}',
  ));
}
if($pageName == 'sesmember_index_pinborad-view-members') {
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

  //Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-menu',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-search',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPrated","featured","sponsored","verified"],"view":["0","1","3","week","month"],"default_search_type":"creation_date ASC","show_advanced_search":"yes","network":"yes","alphabet":"yes","friend_show":"yes","search_title":"yes","browse_by":"yes","location":"yes","kilometer_miles":"yes","country":"yes","state":"yes","city":"yes","zip":"yes","member_type":"yes","has_photo":"yes","is_online":"yes","is_vip":"yes","title":"","nomobile":"0","name":"sesmember.browse-search"}',
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.browse-members',
    'page_id' => $page_id,
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"enableTabs":["pinboard"],"openViewType":"pinboard","show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","vipLabel","message","friendButton","followButton","likemainButton","viewDetailsLink","socialSharing","like","location","rating","view","title","friendCount","mutualFriendCount","profileType","age","pinboardSlideshow"],"limit_data":"18","pagging":"auto_load","order":"mostSPviewed","show_item_count":"1","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","main_height":"160","main_width":"250","height":"160","width":"250","photo_height":"160","photo_width":"250","info_height":"160","advgrid_height":"322","advgrid_width":"322","pinboard_width":"250","title":"","nomobile":"0","name":"sesmember.browse-members"}',

  ));
}
if($pageName == 'sesmember_review_view') {
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

  // Insert main-left
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'left',
    'page_id' => $page_id,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_left_id = $db->lastInsertId();

  // Insert menu
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'sesmember.breadcrumb',
    'page_id' => $page_id,
    'parent_content_id' => $top_middle_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'page_id' => $page_id,
    'type' => 'widget',
    'name' => 'sesmember.review-owner-photo',
    'parent_content_id' => $main_left_id,
    'params' => '{"title":"","showTitle":"1","nomobile":"0","name":"sesmember.review-owner-photo"}',
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'page_id' => $page_id,
    'type' => 'widget',
    'name' => 'sesmember.review-profile-options',
    'parent_content_id' => $main_left_id,
    'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
    'page_id' => $page_id,
    'type' => 'widget',
    'name' => 'sesmember.profile-review',
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"stats":["likeCount","commentCount","viewCount","title","pros","cons","description","recommended","postedin","creationDate","parameter","rating"],"title":"","nomobile":"0","name":"sesmember.profile-review"}',
  ));

  $db->insert('engine4_core_content', array(
    'page_id' => $page_id,
    'type' => 'widget',
    'name' => 'core.comments',
    'parent_content_id' => $main_middle_id,
    'order' => $widgetOrder++,
    'params' => '{"title":"Comments"}',
  ));
}
