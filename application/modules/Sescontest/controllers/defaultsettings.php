<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: defaultsettings.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
$db = Zend_Db_Table_Abstract::getDefaultAdapter();
//SNS - Contest Home Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_home')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_home',
      'displayname' => 'SNS - Advanced Contests - Contests Home Page',
      'title' => 'My Contests',
      'description' => 'This page lists a user\'s contests.',
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

  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $mainRightId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contests-slideshow',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"leftContest":"1","info":"recently_created","criteria":"5","order":"ongoing","enableSlideshow":"1","criteria_right":"7","info_right":"most_viewed","navigation":"2","autoplay":"1","title_truncation":"45","description_truncation":"150","speed":"4000","height":"450","limit_data":"6","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","show_criteria":["title","mediaType","description","socialSharing","likeButton","favouriteButton","followButton","entryCount","status"],"title":"Hot & Verified Contests","nomobile":"0","name":"sescontest.contests-slideshow"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.featured-sponsored-carousel',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"horizontal","order":"activecoming","criteria":"1","info":"creation_date","show_criteria":["title","by","mediaType","category","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","status","voteCount"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","imageheight":"270","width":"311","limit_data":"5","title":"Featured Contests","nomobile":"0","name":"sescontest.featured-sponsored-carousel"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.category-associate-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"grid","criteria":"most_contest","popularty":"all","order":"like_count","show_category_criteria":["seeAll","countContest","categoryDescription"],"show_criteria":["title","by","mediaType","description","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"auto_load","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","grid_description_truncation":"45","title_truncation":"45","slideshow_description_truncation":"150","height":"260","width":"307","category_limit":"6","contest_limit":"3","allignment_seeall":"left","title":"Contests Based On Categories","nomobile":"0","name":"sescontest.category-associate-contests"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"list","tabOption":"default","media":"","show_criteria":["title","by","mediaType","category","listdescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","status","voteCount"],"pagging":"button","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"5","list_title_truncation":"80","list_description_truncation":"200","height":"250","width":"380","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"20","grid_description_truncation":"90","height_grid":"260","width_grid":"307","dummy17":null,"limit_data_advgrid":"8","advgrid_title_truncation":"25","height_advgrid":"320","width_advgrid":"461","dummy18":null,"limit_data_pinboard":"11","pinboard_title_truncation":"45","pinboard_description_truncation":"20","width_pinboard":"230","search_type":["active","upcoming","recentlySPcreated","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"","ended_label":"Ended","dummy2":null,"active_order":"1","active_label":"Active","dummy3":null,"upcoming_order":"2","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"3","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourited_order":"8","mostSPfavourited_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"4","featured_label":"Featured","dummy12":null,"sponsored_order":"5","sponsored_label":"Sponsored","dummy13":null,"verified_order":"","verified_label":"Verified","dummy14":null,"hot_order":"6","hot_label":"Hot","title":"Popular Contests","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-day-of-the',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Contest of the Day","contentType":"contest","information":["title","postedby","mediaType","category","endDate","socialSharing","likeButton","favouriteButton","followButton","likeCount","commentCount","viewCount","favouriteCount","followCount","featured","sponsored","hot","verified"],"imageheight":"210","title_truncation":"14","socialshare_enable_plusicon":"1","socialshare_icon_limit":"1","nomobile":"0","name":"sescontest.contest-entry-day-of-the"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-menu-quick',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"popup":"1","title":"","nomobile":"0","name":"sescontest.browse-menu-quick"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tag-cloud-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"color":"#2e1f1f","type":"cloud","text_height":"14","height":"150","itemCountPerPage":"25","title":"Popular Tags","nomobile":"0","name":"sescontest.tag-cloud-contests"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.find-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["today","tomorrow","week","nextweek","month","dateCriteria","category"],"limit_data":"5","viewMore":"yes","title":"Find Contests","nomobile":"0","name":"sescontest.find-contests"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","criteria":"on_site","show_criteria":["title","by","mediaType","category","socialSharing","joinButton"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"70","width":"80","width_pinboard":"300","limit_data":"3","title":"Recently Viewed Contests","nomobile":"0","name":"sescontest.recently-viewed-item"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.featured-sponsored-hot',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","order":"upcoming","criteria":"5","info":"recently_created","show_criteria":["title","by","mediaType","socialSharing","status"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"150","width_pinboard":"200","height":"70","width":"70","limit_data":"3","title":"Coming Soon Contests","nomobile":"0","name":"sescontest.featured-sponsored-hot"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.you-may-also-like-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"You May Also Like","viewType":"listView","information":["title","by","category","socialSharing"],"title_truncation":"45","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height":"70","width":"70","limit_data":"3","nomobile":"0","name":"sescontest.you-may-also-like-contests"}',
  ));
}
//SNS - Contest Browse Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_browse',
      'displayname' => 'SNS - Advanced Contests - Contests Browse Page',
      'title' => 'Contest Browse',
      'description' => 'This page lists contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.featured-sponsored-verified-hot-slideshow',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"order":"activecoming","criteria":"7","info":"most_joined","isfullwidth":"0","autoplay":"1","speed":"4000","navigation":"buttons","show_criteria":["title","description"],"title_truncation":"45","description_truncation":"100","height":"450","limit_data":"5","title":"Hot Contests","nomobile":"0","name":"sescontest.featured-sponsored-verified-hot-slideshow"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-category-icons',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"titleC":"CHECK OUT OPEN CONTESTS AND PARTICIPATE!","criteria":"admin_order","show_criteria":["title","countContests"],"alignContent":"center","viewType":"icon","shapeType":"circle","show_bg_color":"2","bgColor":"#E8FF9C","height":"160","width":"110","limit_data":"10","title":"","nomobile":"0","name":"sescontest.contest-category-icons"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-alphabet',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","contentType":"contests","nomobile":"0","name":"sescontest.contest-entry-alphabet"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-search',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","entrymaxtomin","entrymintomax","mostSPfavourite","mostSPfollower","featured","sponsored","verified","hot"],"default_search_type":"recentlySPcreated","criteria":["0","1","2","3","today","tomorrow","week","nextweek","month"],"show_option":["searchContestTitle","view","browseBy","mediaType","chooseDate","Categories"],"title":"","nomobile":"0","name":"sescontest.browse-search"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"pinboard","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","status","voteCount"],"show_item_count":"1","height":"250","width":"460","height_grid":"260","width_grid":"393","height_advgrid":"290","width_advgrid":"393","width_pinboard":"350","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","list_description_truncation":"300","grid_description_truncation":"75","pinboard_description_truncation":"150","limit_data_pinboard":"35","limit_data_grid":"30","limit_data_advgrid":"30","limit_data_list":"25","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","pagging":"button","title":"","nomobile":"0","name":"sescontest.browse-contests"}',
  ));
}
//SNS - Contest Entries Browse Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_entries')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_entries',
      'displayname' => 'SNS - Advanced Contests - Entries Browse Page',
      'title' => 'Contest Entries Browse',
      'description' => 'This page lists contests entries.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

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
      'parent_content_id' => $mainId,
      'order' => 6,
  ));
  $mainMiddleId = $db->lastInsertId();

  // Insert top-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 5,
  ));
  $mainRightId = $db->lastInsertId();

  // Insert bottom
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'bottom',
      'page_id' => $pageId,
      'order' => 3,
  ));
  $bottomId = $db->lastInsertId();
  // Insert bottom-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $bottomId,
      'order' => 6,
  ));
  $bottomMiddleId = $db->lastInsertId();
  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-alphabet',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","contentType":"entries","nomobile":"0","name":"sescontest.contest-entry-alphabet"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.winner-browse-search',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","rankSPlow","rankSPhigh","mostSPvoted","mostSPviewed","mostSPliked","mostSPcommented","mostSPfavourite"],"default_search_type":"recentlySPcreated","criteria":["0","1","2","3","week","month"],"show_option":["searchEntryTitle","searchContestTitle","view","browseBy","mediaType","rank"],"title":"","nomobile":"0","name":"sescontest.winner-browse-search"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.html-block',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","adminTitle":"","data":"<div style=\"margin-top:130px;\"><\/div>\r\n<style>.layout_core_html_block{background:none; !important}<\/style>","nomobile":"0","name":"core.html-block"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-day-of-the',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Entry of the Day","contentType":"entry","information":["title","postedby","mediaType","category"],"imageheight":"265","title_truncation":"15","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","nomobile":"0","name":"sescontest.contest-entry-day-of-the"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sesbasic.column-layout-width',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"layoutColumnWidthType":"px","columnWidth":"320","title":"","nomobile":"0","name":"sesbasic.column-layout-width"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-entries',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","pinboard"],"openViewType":"grid","show_criteria":["title","contestName","mediaType","listdescription","submitDate","ownerName","ownerPhoto","socialSharing","likeButton","favouriteButton","voteButton","voteCount","like","comment","favourite","view"],"show_item_count":"1","pagging":"auto_load","fixed_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height_list":"300","width_list":"480","height_grid":"360","width_grid":"590","width_pinboard":"330","list_title_truncation":"45","grid_title_truncation":"45","pinboard_title_truncation":"45","list_description_truncation":"200","grid_description_truncation":"130","pinboard_description_truncation":"120","limit_data_pinboard":"10","limit_data_grid":"20","limit_data_list":"20","title":"","nomobile":"0","name":"sescontest.browse-entries"}',
  ));
}
// profile page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_winner')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_winner',
      'displayname' => 'SNS - Advanced Contests - Browse Winners Page',
      'title' => 'Contest Winner Entries Browse',
      'description' => 'This page lists contests entries.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.winner-browse-search',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","rankSPlow","rankSPhigh","mostSPvoted","mostSPviewed","mostSPliked","mostSPcommented","mostSPfavourite"],"default_search_type":"recentlySPcreated","criteria":["0","1","2","3","week","month"],"show_option":["searchEntryTitle","searchContestTitle","view","browseBy","mediaType","rank"],"title":"","nomobile":"0","name":"sescontest.winner-browse-search"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.winners-listing',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","pinboard"],"openViewType":"pinboard","show_criteria":["title","contestName","mediaType","listdescription","submitDate","ownerName","ownerPhoto","rank","socialSharing","likeButton","favouriteButton","voteCount","like","comment","favourite","view"],"pagging":"button","fixed_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"3","limit_data_list":"20","list_title_truncation":"45","list_description_truncation":"300","height_list":"393","width_list":"220","limit_data_grid":"21","grid_title_truncation":"45","grid_description_truncation":"300","height_grid":"340","width_grid":"393","limit_data_pinboard":"5","pinboard_title_truncation":"40","pinboard_description_truncation":"120","width_pinboard":"330","title":"","nomobile":"0","name":"sescontest.winners-listing"}',
  ));
}
//SNS - Contest Category Browse Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_category_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_category_browse',
      'displayname' => 'SNS - Advanced Contests - Contest Category Browse Page',
      'title' => 'Contest Category Browse',
      'description' => 'This page lists contest categories.',
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

  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $mainRightId = $db->lastInsertId();

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.category-carousel',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title_truncation":"45","description_truncation":"45","height":"120","width":"300","speed":"300","autoplay":"1","criteria":"alphabetical","show_criteria":["title","description","countContests"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","isfullwidth":"1","limit_data":"10","title":"","nomobile":"0","name":"sescontest.category-carousel"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.banner-category',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"description":"Discover contests of all media types based on the categories and your interests.","sescontest_categorycover_photo":"public\/admin\/contest-category-banner.jpg","show_popular_contests":"1","title_pop":"Popular Contests","order":"","info":"most_joined","height":"300","isfullwidth":"0","margin_top":"20","title":"Categories","nomobile":"0","name":"sescontest.banner-category"}',
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.category-associate-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"carousel","criteria":"most_contest","popularty":"ongoing","order":"like_count","show_category_criteria":["seeAll","countContest","categoryDescription"],"show_criteria":["title","by","mediaType","description","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"auto_load","socialshare_enable_plusicon":"1","socialshare_icon_limit":"4","grid_description_truncation":"45","title_truncation":"45","slideshow_description_truncation":"250","height":"250","width":"320","category_limit":"5","contest_limit":"10","allignment_seeall":"right","title":"","nomobile":"0","name":"sescontest.category-associate-contests"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-day-of-the',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Entry of the Day","contentType":"entry","information":["title","mediaType"],"imageheight":"180","title_truncation":"45","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","nomobile":"0","name":"sescontest.contest-entry-day-of-the"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.featured-sponsored-hot',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","order":"activecoming","criteria":"5","info":"recently_created","show_criteria":["title","by","mediaType","joinButton"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"22","description_truncation":"45","width_pinboard":"300","height":"50","width":"70","limit_data":"4","title":"Latest Contests","nomobile":"0","name":"sescontest.featured-sponsored-hot"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-menu-quick',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"popup":"1","title":"","nomobile":"0","name":"sescontest.browse-menu-quick"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.featured-sponsored-hot',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","order":"activecoming","criteria":"5","info":"most_joined","show_criteria":["title","by","mediaType","joinButton","entryCount"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","width_pinboard":"300","height":"70","width":"70","limit_data":"4","title":"Most Participated Contests","nomobile":"0","name":"sescontest.featured-sponsored-hot"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","criteria":"on_site","show_criteria":["title","by","mediaType","category"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"70","width":"70","width_pinboard":"300","limit_data":"4","title":"Recently Viewed Contests","nomobile":"0","name":"sescontest.recently-viewed-item"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tag-cloud-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"color":"#00f","type":"tab","text_height":"15","height":"300","itemCountPerPage":"15","title":"Popular Tags","nomobile":"0","name":"sescontest.tag-cloud-contests"}',
  ));
}
//SNS - Contest Create Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_create')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_create',
      'displayname' => 'SNS - Advanced Contests - Contest Create Page',
      'title' => 'Contest Create',
      'description' => 'This page allows contest to be create.',
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
      'name' => 'sescontest.browse-menu',
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

//SNS - Contest Manage Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_manage')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_manage',
      'displayname' => 'SNS - Advanced Contests - Contest Manage Page',
      'title' => 'My Contests',
      'description' => 'This page lists a user\'s contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.manage-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"pinboard","tabOption":"vertical","media":"","show_criteria":["title","startenddate","by","mediaType","category","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"button","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"6","list_title_truncation":"45","list_description_truncation":"200","height":"200","width":"400","dummy16":null,"limit_data_grid":"6","grid_title_truncation":"45","grid_description_truncation":"180","height_grid":"350","width_grid":"393","dummy17":null,"limit_data_advgrid":"6","advgrid_title_truncation":"45","height_advgrid":"350","width_advgrid":"393","dummy18":null,"limit_data_pinboard":"6","pinboard_title_truncation":"45","pinboard_description_truncation":"180","width_pinboard":"350","search_type":["ended","active","upcoming","recentlySPcreated","mostSPliked","mostSPcommented","mostSPviewed","mostSPfavourite","mostSPfollowed","mostSPjoined","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"5","ended_label":"Ended","dummy2":null,"active_order":"3","active_label":"Active","dummy3":null,"upcoming_order":"4","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"1","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"8","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"9","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"10","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"6","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"7","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"2","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.manage-contests"}',
  ));
}
//SNS - Contest Video Media View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_media_photo')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_media_photo',
      'displayname' => 'SNS - Advanced Contests - Contest Photo Media Type Page',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry corresponding to media.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.mediatype-banner',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"banner_title":"Photo Contests","description":"The best images are the ones that retain their strength and impact over the years, regardless of the number of times they are viewed.","show_icon":"1","show_full_width":"no","margin_top":"30","cover_height":"400","title":"","nomobile":"0","name":"sescontest.mediatype-banner"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"grid","tabOption":"select","media":"2","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"auto_load","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"10","list_title_truncation":"45","list_description_truncation":"200","height":"250","width":"500","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"45","grid_description_truncation":"150","height_grid":"260","width_grid":"386","dummy17":null,"limit_data_advgrid":"12","advgrid_title_truncation":"45","height_advgrid":"300","width_advgrid":"386","dummy18":null,"limit_data_pinboard":"10","pinboard_title_truncation":"45","pinboard_description_truncation":"120","width_pinboard":"300","search_type":["ended","active","upcoming","recentlySPcreated","mostSPliked","mostSPcommented","mostSPviewed","mostSPfavourite","mostSPfollowed","mostSPjoined","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"3","ended_label":"Ended","dummy2":null,"active_order":"1","active_label":"Active","dummy3":null,"upcoming_order":"2","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Contest Video Media View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_media_video')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_media_video',
      'displayname' => 'SNS - Advanced Contests - Contest Video Media Type Page',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry corresponding to media.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.mediatype-banner',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"banner_title":"Video Contests","description":"Nobody will ever notice that. Capturing moments in Videos is not about the tiny details. It\'s about the big picture.\r\n","show_icon":"1","show_full_width":"no","margin_top":"30","cover_height":"400","title":"","nomobile":"0","name":"sescontest.mediatype-banner"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"list","tabOption":"select","media":"3","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"button","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"10","list_title_truncation":"45","list_description_truncation":"200","height":"250","width":"500","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"45","grid_description_truncation":"150","height_grid":"250","width_grid":"386","dummy17":null,"limit_data_advgrid":"12","advgrid_title_truncation":"45","height_advgrid":"260","width_advgrid":"386","dummy18":null,"limit_data_pinboard":"10","pinboard_title_truncation":"45","pinboard_description_truncation":"120","width_pinboard":"300","search_type":["ended","active","upcoming","recentlySPcreated","mostSPliked","mostSPcommented","mostSPviewed","mostSPfavourite","mostSPfollowed","mostSPjoined","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"3","ended_label":"Ended","dummy2":null,"active_order":"1","active_label":"Active","dummy3":null,"upcoming_order":"2","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Contest Text Media View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_media_text')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_media_text',
      'displayname' => 'SNS - Advanced Contests - Contest Text Media Type Page',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry corresponding to media.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.mediatype-banner',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"banner_title":"Text and Blog Contests","description":"Any kind of writing can be an art, but creative thinking is the key. Whether you plan to immerse yourself in writing poetry, believe there\'s a novel in you trying to get out, or are simply tackling an essay or a blog post, your creative thinking and skill can combine to turn it into a work of art.","show_icon":"1","show_full_width":"no","margin_top":"30","cover_height":"400","title":"","nomobile":"0","name":"sescontest.mediatype-banner"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"grid","tabOption":"filter","media":"1","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"auto_load","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"10","list_title_truncation":"45","list_description_truncation":"200","height":"260","width":"500","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"45","grid_description_truncation":"150","height_grid":"250","width_grid":"386","dummy17":null,"limit_data_advgrid":"12","advgrid_title_truncation":"45","height_advgrid":"260","width_advgrid":"386","dummy18":null,"limit_data_pinboard":"10","pinboard_title_truncation":"45","pinboard_description_truncation":"120","width_pinboard":"300","search_type":["ended","active","upcoming","mostSPliked","mostSPfavourite","mostSPfollowed","mostSPjoined","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"3","ended_label":"Ended","dummy2":null,"active_order":"1","active_label":"Active","dummy3":null,"upcoming_order":"2","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Contest Audio Media View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_media_audio')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_media_audio',
      'displayname' => 'SNS - Advanced Contests - Contest Audio Media Type Page',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry corresponding to media.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.mediatype-banner',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"banner_title":"Music Contests ","description":"Music is probably the only real magic I have encountered in my life. There\'s not some trick involved with it. It\'s pure and it\'s real. It moves, it heals, it communicates and does all these incredible things.\r\n","show_icon":"1","show_full_width":"no","margin_top":"30","cover_height":"400","title":"","nomobile":"0","name":"sescontest.mediatype-banner"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"grid","tabOption":"select","media":"4","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"button","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"10","list_title_truncation":"45","list_description_truncation":"200","height":"260","width":"500","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"45","grid_description_truncation":"150","height_grid":"250","width_grid":"386","dummy17":null,"limit_data_advgrid":"12","advgrid_title_truncation":"45","height_advgrid":"260","width_advgrid":"386","dummy18":null,"limit_data_pinboard":"10","pinboard_title_truncation":"45","pinboard_description_truncation":"120","width_pinboard":"300","search_type":["ended","active","upcoming","recentlySPcreated","mostSPliked","mostSPcommented","mostSPviewed","mostSPfavourite","mostSPfollowed","mostSPjoined","featured","sponsored","verified","hot"],"dummy1":null,"ended_order":"3","ended_label":"Ended","dummy2":null,"active_order":"1","active_label":"Active","dummy3":null,"upcoming_order":"2","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Active Contest Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_ongoing')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_ongoing',
      'displayname' => 'SNS - Contest Active Contests Page',
      'title' => 'Active Contests',
      'description' => 'This page displays active contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["advgrid"],"openViewType":"advgrid","tabOption":"advance","media":"","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status","voteCount"],"pagging":"button","show_limited_data":"yes","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"5","list_title_truncation":"45","list_description_truncation":"150","height":"230","width":"260","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"18","grid_description_truncation":"150","height_grid":"250","width_grid":"265","dummy17":null,"limit_data_advgrid":"15","advgrid_title_truncation":"30","height_advgrid":"270","width_advgrid":"386","dummy18":null,"limit_data_pinboard":"12","pinboard_title_truncation":"45","pinboard_description_truncation":"150","width_pinboard":"250","search_type":["active"],"dummy1":null,"ended_order":"1","ended_label":"Ended","dummy2":null,"active_order":"2","active_label":"Active","dummy3":null,"upcoming_order":"3","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Coming Soon Contest Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_comingsoon')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_comingsoon',
      'displayname' => 'SNS - Contest Coming Soon Contests Page',
      'title' => 'Coming Soon Contests',
      'description' => 'This page displays coming soon contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["pinboard"],"openViewType":"pinboard","tabOption":"filter","media":"","show_criteria":["title","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","status"],"pagging":"button","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"5","list_title_truncation":"45","list_description_truncation":"150","height":"230","width":"260","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"18","grid_description_truncation":"150","height_grid":"250","width_grid":"265","dummy17":null,"limit_data_advgrid":"12","advgrid_title_truncation":"18","height_advgrid":"230","width_advgrid":"260","dummy18":null,"limit_data_pinboard":"9","pinboard_title_truncation":"45","pinboard_description_truncation":"100","width_pinboard":"300","search_type":["upcoming"],"dummy1":null,"ended_order":"1","ended_label":"Ended","dummy2":null,"active_order":"2","active_label":"Active","dummy3":null,"upcoming_order":"3","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Ended Contest Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_ended')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_ended',
      'displayname' => 'SNS - Contest Ended Contests Page',
      'title' => 'Ended Contests',
      'description' => 'This page displays ended contests.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $mainId = $db->lastInsertId();

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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tabbed-widget-contest',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["grid"],"openViewType":"grid","tabOption":"advance","media":"","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","voteCount"],"pagging":"auto_load","show_limited_data":"no","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","htmlTitle":"1","dummy15":null,"limit_data_list":"10","list_title_truncation":"45","list_description_truncation":"45","height":"230","width":"260","dummy16":null,"limit_data_grid":"12","grid_title_truncation":"25","grid_description_truncation":"45","height_grid":"270","width_grid":"386","dummy17":null,"limit_data_advgrid":"10","advgrid_title_truncation":"45","height_advgrid":"230","width_advgrid":"260","dummy18":null,"limit_data_pinboard":"10","pinboard_title_truncation":"45","pinboard_description_truncation":"45","width_pinboard":"300","search_type":["ended"],"dummy1":null,"ended_order":"1","ended_label":"Ended","dummy2":null,"active_order":"2","active_label":"Active","dummy3":null,"upcoming_order":"3","upcoming_label":"Coming Soon","dummy4":null,"recentlySPcreated_order":"4","recentlySPcreated_label":"Recently Created","dummy5":null,"mostSPliked_order":"5","mostSPliked_label":"Most Liked","dummy6":null,"mostSPcommented_order":"6","mostSPcommented_label":"Most Commented","dummy7":null,"mostSPviewed_order":"7","mostSPviewed_label":"Most Viewed","dummy8":null,"mostSPfavourite_order":"8","mostSPfavourite_label":"Most Favourited","dummy9":null,"mostSPfollowed_order":"9","mostSPfollowed_label":"Most Followed","dummy10":null,"mostSPjoined_order":"10","mostSPjoined_label":"Most Joined","dummy11":null,"featured_order":"11","featured_label":"Featured","dummy12":null,"sponsored_order":"12","sponsored_label":"Sponsored","dummy13":null,"verified_order":"13","verified_label":"Verified","dummy14":null,"hot_order":"14","hot_label":"Hot","title":"","nomobile":"0","name":"sescontest.tabbed-widget-contest"}',
  ));
}
//SNS - Pinbaord View Contest Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_pinboard')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_pinboard',
      'displayname' => 'SNS - Contest Pinboard View Contests Page',
      'title' => 'Pinboard View Contests',
      'description' => 'This page displays pinboard view contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-search',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","entrymaxtomin","entrymintomax","mostSPfavourite","mostSPfollower","featured","sponsored","verified","hot"],"default_search_type":"recentlySPcreated","criteria":["0","1","2","3","today","tomorrow","week","nextweek","month"],"default_view_search_type":"0","show_option":["searchContestTitle","view","browseBy","mediaType","chooseDate","Categories"],"title":"","nomobile":"0","name":"sescontest.browse-search"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.browse-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["pinboard"],"openViewType":"pinboard","show_criteria":["title","by","mediaType","category"],"show_item_count":"0","height":"230","width":"260","height_grid":"260","width_grid":"273","height_advgrid":"260","width_advgrid":"273","width_pinboard":"300","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","list_description_truncation":"200","grid_description_truncation":"150","pinboard_description_truncation":"150","limit_data_pinboard":"20","limit_data_grid":"20","limit_data_advgrid":"20","limit_data_list":"15","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","pagging":"auto_load","title":"","nomobile":"0","name":"sescontest.browse-contests"}',
  ));
}
//SNS - Contest Browse Tag Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_tags')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_tags',
      'displayname' => 'SNS - Advanced Contests - Contests Tags Browse Page',
      'title' => 'Contest Create',
      'description' => 'This page displays all tags.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => 1,
  ));
  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.tag-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => 1,
      'params' => '{"title":"","name":"sescontest.tag-contests"}',
  ));
}
//SNS - Contest Join Create Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_join_create')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_join_create',
      'displayname' => 'SNS - Advanced Contests - Entry Submission Page',
      'title' => 'Join Contest Create',
      'description' => 'This page allows contest to be join.',
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
      'name' => 'sescontest.browse-menu',
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
//SNS - Contest Category View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_category_index')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_category_index',
      'displayname' => 'SNS - Advanced Contests - Contest Category View Page',
      'title' => 'Contest Category View',
      'description' => 'This page displays a contest entry.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.category-view',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"show_subcat":"0","subcategory_title":"Sub-Categories of this catgeory","show_subcatcriteria":["title","icon","countContests"],"heightSubcat":"160px","widthSubcat":"250px","show_popular_contests":"1","pop_title":"Popular Contests","view":"ongoing","info":"creationSPdate","dummy1":null,"contest_title":"","show_criteria":["socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","startenddate","title","mediaType","status","by"],"pagging":"button","contest_limit":"9","height":"250","width":"393","title":"","nomobile":"0","name":"sescontest.category-view"}',
  ));
}
//SNS - Contest Entry View Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_join_view')
        ->limit(1)
        ->query()
        ->fetchColumn();

if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_join_view',
      'displayname' => 'SNS - Contest Entry View Page',
      'title' => 'Contest Entry View',
      'description' => 'This page displays an contest entry details.',
      'provides' => 'subject=ctparticipant',
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

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 2,
  ));
  $middleId = $db->lastInsertId();

  // Insert main-right
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $mainRightId = $db->lastInsertId();

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.entry-content',
      'page_id' => $pageId,
      'parent_content_id' => $middleId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entry-view',
      'page_id' => $pageId,
      'parent_content_id' => $middleId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["title","submitDate","contestName","votestartenddate","pPhoto","pName","mediaType","description","category","socialSharing","share","likeButton","favouriteButton","voteButton","vote","like","comment","view","favourite","optionMenu"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title":"","nomobile":"0","name":"sescontest.contest-entry-view"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.vote-graph',
      'page_id' => $pageId,
      'parent_content_id' => $middleId,
      'order' => $widgetOrder++,
      'params' => '{"heading":"as asfsdf","votelinecolor":"#269647","likelinecolor":"#9665EA","commentlinecolor":"#57A5FF","viewlinecolor":"#284FEA","favouritelinecolor":"#FF4E33","criteria":["hourly","daily","weekly","monthly"],"openViewType":"weekly","title":"","nomobile":"0","name":"sescontest.vote-graph"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedcomment.comments',
        'page_id' => $pageId,
        'parent_content_id' => $middleId,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  } else {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.comments',
        'page_id' => $pageId,
        'parent_content_id' => $middleId,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  }
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.breadcrumb',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.vote-button',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.vote-button"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.congratulation-message',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.congratulation-message"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-photo',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.owner-photo',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Entry Submitted By","showTitle":"1","nomobile":"0","name":"sescontest.owner-photo"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","criteria":"on_site","show_criteria":["title","startenddate","joinButton"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"60","width":"60","width_pinboard":"300","limit_data":"3","title":"Recently Viewed Contests","nomobile":"0","name":"sescontest.recently-viewed-item"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.you-may-also-like-contests',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Contests You May Also Like","viewType":"listView","information":["title","mediaType","category","joinButton"],"title_truncation":"45","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height":"60","width":"60","limit_data":"3","nomobile":"0","name":"sescontest.you-may-also-like-contests"}',
  ));
}
// Welcome page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_index_welcome')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_index_welcome',
      'displayname' => 'SNS - Advanced Contests - Contests Welcome Page',
      'title' => 'Contest Welcome Page',
      'description' => 'This page lists contests.',
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
      'name' => 'sescontest.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
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
}

// profile page design 1
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_profile_index_1')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_profile_index_1',
      'displayname' => 'SNS - Advanced Contests - Contest View Page Design 1',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry.',
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

  // Insert top middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $topId,
  ));
  $topMiddleId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $mainId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 2,
  ));
  $mainMiddleId = $db->lastInsertId();

  // Insert left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'left',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $leftId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-view',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"layout_type":"1","tab_placement":"in","tab_type":"center","show_criteria":["title","description","by","mediaType","category","tag","startdate","enddate","joinstartdate","joinenddate","votingstartdate","votingenddate","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","statusLabel","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","optionMenu"],"photo_type":"contestPhoto","show_full_width":"yes","margin_top":"20","description_truncation":"150","cover_height":"580","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title":"","nomobile":"0","name":"sescontest.contest-view"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-status',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"current_status":"1","show_js_date":"1","show_je_date":"0","show_vs_date":"0","show_ve_date":"0","status_font_size":"12px","entry_font_size":"12px","title":"","nomobile":"0","name":"sescontest.contest-status"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-countdown',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"heading":"","type":"endtime","placement_type":"sidebar","title":"Will End After","nomobile":"0","name":"sescontest.contest-countdown"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.advance-share',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"advShareOptions":["privateMessage","siteShare","quickShare","tellAFriend","addThis"],"title":"","nomobile":"0","name":"sescontest.advance-share"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-quick-create',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.viewer-friends-participating',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"listing_type":"grid","criteria":["name"],"height":"110","width":"110","limit_data":"4","title":"Friends Who Participated","nomobile":"0","name":"sescontest.viewer-friends-participating"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recent-people-activity',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["like","favourite","follow"],"height":"66","width":"66","view_more_like":"2","view_more_favourite":"2","view_more_follow":"2","title":"","nomobile":"0","name":"sescontest.recent-people-activity"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.other-contest',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","show_criteria":["title","by","mediaType","category","socialSharing"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"40","description_truncation":"45","height":"70","width":"70","width_pinboard":"300","limit_data":"3","title":"Other Contests From Owner","nomobile":"0","name":"sescontest.other-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-similar-contest',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","search":"category","show_criteria":["title","by","mediaType","category","socialSharing"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"15","description_truncation":"40","height":"70","width":"70","width_pinboard":"70","limit_data":"3","title":"Similar Contests","nomobile":"0","name":"sescontest.contest-similar-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","criteria":"by_me","show_criteria":["title","mediaType","category","socialSharing","likeButton","favouriteButton","followButton","joinButton"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"70","width":"70","width_pinboard":"300","limit_data":"3","title":"Contests You Recently Viewed","nomobile":"0","name":"sescontest.recently-viewed-item"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-tags',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"itemCountPerPage":"20","title":"Tags","nomobile":"0","name":"sescontest.profile-tags"}',
  ));
  $db->insert('engine4_core_content', array(
      'page_id' => $pageId,
      'type' => 'widget',
      'name' => 'core.container-tabs',
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"max":"7","title":"","name":"core.container-tabs","nomobile":"0"}',
  ));
  $tab_id = $db->lastInsertId('engine4_core_content');
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-winner-entries',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","palcement":"horizontal","show_criteria":["title","listdescription","submitDate","ownerName","ownerPhoto","rank","socialSharing","likeButton","favouriteButton","voteCount","like","comment","favourite","view"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","openViewType":"low","height":"290","width":"480","width_pinboard":"450","title_truncation":"45","description_truncation":"350","title":"Winners ","nomobile":"0","name":"sescontest.contest-winner-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entries',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid"],"openViewType":"grid","show_criteria":["title","photo","description","submitDate","pPhoto","pName","socialsharing","likeButton","favouriteButton","voteButton","voteCount","likeCount","commentCount","viewCount","favouriteCount"],"pagging":"auto_load","sorting":["Newest","Oldest","mostSPvoted","mostSPliked","mostSPcommented","mostSPViewed","mostSPfavorite"],"show_item_count":"0","list_title_truncation":"45","grid_title_truncation":"45","list_description_truncation":"200","grid_description_truncation":"150","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height_list":"250","width_list":"280","height_grid":"250","width_grid":"277","limit_data":"5","title":"Entries","nomobile":"0","name":"sescontest.contest-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-info',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["info","date","description","overview","profilefield"],"title":"Details","nomobile":"0","name":"sescontest.contest-info"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-award',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["firstAward","secondAward","thirdAward","fourthAward","fifthAward"],"title":"Awards","nomobile":"0","name":"sescontest.contest-award"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-rules',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Rules","name":"sescontest.contest-rules"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedactivity.feed',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Updates","design":"4","feeddesign":"1","scrollfeed":"1","autoloadTimes":"3","statusplacehoder":"Post Something...","userphotoalign":"left","enablefeedbgwidget":"1","feedbgorder":"random","enablewidthsetting":"0","sesact_image1":null,"sesact_image1_width":"500","sesact_image1_height":"450","sesact_image2":null,"sesact_image2_width":"289","sesact_image2_height":"200","sesact_image3":null,"sesact_image3_bigwidth":"328","sesact_image3_bigheight":"300","sesact_image3_smallwidth":"250","sesact_image3_smallheight":"150","sesact_image4":null,"sesact_image4_bigwidth":"578","sesact_image4_bigheight":"300","sesact_image4_smallwidth":"192","sesact_image4_smallheight":"100","sesact_image5":null,"sesact_image5_bigwidth":"289","sesact_image5_bigheight":"260","sesact_image5_smallwidth":"289","sesact_image5_smallheight":"130","sesact_image6":null,"sesact_image6_width":"289","sesact_image6_height":"150","sesact_image7":null,"sesact_image7_bigwidth":"192","sesact_image7_bigheight":"150","sesact_image7_smallwidth":"144","sesact_image7_smallheight":"150","sesact_image8":null,"sesact_image8_width":"144","sesact_image8_height":"150","sesact_image9":null,"sesact_image9_width":"192","sesact_image9_height":"150","nomobile":"0","name":"sesadvancedactivity.feed"}',
    ));
  }
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedcomment.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  } else {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  }
}
// profile page design 2
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_profile_index_2')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_profile_index_2',
      'displayname' => 'SNS - Advanced Contests - Contest View Page Design 2',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $topId = $db->lastInsertId();

  // Insert top middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $topId,
  ));
  $topMiddleId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $mainId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 2,
  ));
  $mainMiddleId = $db->lastInsertId();

  // Insert left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $rightId = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.breadcrumb',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.breadcrumb"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-view',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"layout_type":"2","tab_type":"center","show_criteria":["title","description","by","mediaType","category","tag","startdate","enddate","joinstartdate","joinenddate","votingstartdate","votingenddate","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","statusLabel","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel","optionMenu"],"photo_type":"contestPhoto","show_full_width":"no","margin_top":"30","description_truncation":"250","cover_height":"450","socialshare_enable_plusicon":"1","socialshare_icon_limit":"0","title":"","nomobile":"0","name":"sescontest.contest-view"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-countdown',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"heading":"This Contest Will be Ending In","type":"endtime","placement_type":"extended","radious":"","title":"","nomobile":"0","name":"sescontest.contest-countdown"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.container-tabs',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"max":6}',
  ));
  $tab_id = $db->lastInsertId('engine4_core_content');
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-winner-entries',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"pinboard","palcement":"horizontal","show_criteria":["title","ownerName","ownerPhoto","rank","socialSharing","likeButton","favouriteButton","voteCount","like","comment","favourite","view"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","openViewType":"low","height":"260","width":"280","width_pinboard":"300","title_truncation":"45","description_truncation":"200","title":"Winners ","nomobile":"0","name":"sescontest.contest-winner-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entries',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid"],"openViewType":"list","show_criteria":["title","photo","description","submitDate","pPhoto","pName","socialsharing","likeButton","favouriteButton","voteButton","voteCount","likeCount","commentCount","viewCount","favouriteCount"],"pagging":"button","sorting":["Newest","Oldest","mostSPvoted","mostSPliked","mostSPcommented","mostSPViewed","mostSPfavorite"],"show_item_count":"1","list_title_truncation":"45","grid_title_truncation":"30","list_description_truncation":"90","grid_description_truncation":"76","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height_list":"175","width_list":"315","height_grid":"280","width_grid":"311","limit_data":"6","title":"Entries","nomobile":"0","name":"sescontest.contest-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-award',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["firstAward","secondAward","thirdAward","fourthAward","fifthAward"],"title":"Awards","nomobile":"0","name":"sescontest.contest-award"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-overview',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Overview","name":"sescontest.contest-overview"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-rules',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Rules","name":"sescontest.contest-rules"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedactivity.feed',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Updates","design":"3","feeddesign":"2","scrollfeed":"1","autoloadTimes":"3","statusplacehoder":"Post Something...","userphotoalign":"center","enablefeedbgwidget":"1","feedbgorder":"random","enablewidthsetting":"0","sesact_image1":null,"sesact_image1_width":"500","sesact_image1_height":"450","sesact_image2":null,"sesact_image2_width":"289","sesact_image2_height":"200","sesact_image3":null,"sesact_image3_bigwidth":"328","sesact_image3_bigheight":"300","sesact_image3_smallwidth":"250","sesact_image3_smallheight":"150","sesact_image4":null,"sesact_image4_bigwidth":"578","sesact_image4_bigheight":"300","sesact_image4_smallwidth":"192","sesact_image4_smallheight":"100","sesact_image5":null,"sesact_image5_bigwidth":"289","sesact_image5_bigheight":"260","sesact_image5_smallwidth":"289","sesact_image5_smallheight":"130","sesact_image6":null,"sesact_image6_width":"289","sesact_image6_height":"150","sesact_image7":null,"sesact_image7_bigwidth":"192","sesact_image7_bigheight":"150","sesact_image7_smallwidth":"144","sesact_image7_smallheight":"150","sesact_image8":null,"sesact_image8_width":"144","sesact_image8_height":"150","sesact_image9":null,"sesact_image9_width":"192","sesact_image9_height":"150","nomobile":"0","name":"sesadvancedactivity.feed"}',
    ));
  }
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedcomment.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  }
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.advance-share',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"advShareOptions":["privateMessage","siteShare","quickShare","tellAFriend","addThis"],"title":"","nomobile":"0","name":"sescontest.advance-share"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recent-people-activity',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["like","favourite","follow"],"height":"65","width":"66","view_more_like":"2","view_more_favourite":"2","view_more_follow":"2","title":"","nomobile":"0","name":"sescontest.recent-people-activity"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-quick-create',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-tags',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"itemCountPerPage":"30","title":"Tags","nomobile":"0","name":"sescontest.profile-tags"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.viewer-friends-participating',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"listing_type":"list","criteria":["name","contestCount","participationCount"],"height":"50","width":"50","limit_data":"3","title":"Friends Participating","nomobile":"0","name":"sescontest.viewer-friends-participating"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.other-contest',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","show_criteria":["title","by","joinButton"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"160","width":"250","width_pinboard":"300","limit_data":"3","title":"Other Contests From Owner","nomobile":"0","name":"sescontest.other-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-similar-contest',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","search":"category","show_criteria":["title","category","joinButton"],"order":"","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"45","height":"70","width":"70","width_pinboard":"300","limit_data":"3","title":"Similar Contests","nomobile":"0","name":"sescontest.contest-similar-contest"}',
  ));
}
// profile page design 3
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_profile_index_3')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_profile_index_3',
      'displayname' => 'SNS - Advanced Contests - Contest View Page Design 3',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $topId = $db->lastInsertId();

  // Insert top middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $topId,
  ));
  $topMiddleId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $mainId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 2,
  ));
  $mainMiddleId = $db->lastInsertId();

  // Insert left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'left',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $leftId = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-view',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"layout_type":"2","tab_type":"center","show_criteria":["title","startdate","enddate","joinstartdate","joinenddate","votingstartdate","votingenddate"],"photo_type":"contestPhoto","show_full_width":"yes","margin_top":"30","description_truncation":"150","cover_height":"300","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title":"","nomobile":"0","name":"sescontest.contest-view"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.join-contest',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.join-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-winner-entries',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"grid","palcement":"horizontal","show_criteria":["title","rank","socialSharing","likeButton","favouriteButton","voteCount"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","openViewType":"low","height":"230","width":"236","width_pinboard":"300","title_truncation":"18","description_truncation":"45","title":"Winners","nomobile":"0","name":"sescontest.contest-winner-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.owner-photo',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Created By","showTitle":"1","nomobile":"0","name":"sescontest.owner-photo"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-labels',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"option":["featured","sponsored","verified","hot"],"title":"","nomobile":"0","name":"sescontest.contest-labels"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-options',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.like-button',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.like-button"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.follow-button',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.follow-button"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.favourite-button',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"sescontest.favourite-button"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.advance-share',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"advShareOptions":["privateMessage","siteShare","quickShare","tellAFriend","addThis"],"title":"","nomobile":"0","name":"sescontest.advance-share"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-quick-create',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-info',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["creationDate","mediaType","categories","tag","like","comment","favourite","view","follow","entryCount"],"title":"Contest Info","nomobile":"0","name":"sescontest.profile-info"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.other-contest',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"list","show_criteria":["title","mediaType","socialSharing"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"20","description_truncation":"45","height":"60","width":"60","width_pinboard":"250","limit_data":"3","title":"Other Contests From Owner","nomobile":"0","name":"sescontest.other-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.viewer-friends-participating',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"listing_type":"list","criteria":["name","contestCount","participationCount"],"height":"50","width":"50","limit_data":"3","title":"Friends Participating","nomobile":"0","name":"sescontest.viewer-friends-participating"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recent-people-activity',
      'page_id' => $pageId,
      'parent_content_id' => $leftId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["like","favourite","follow"],"height":"66","width":"66","view_more_like":"2","view_more_favourite":"2","view_more_follow":"2","title":"","nomobile":"0","name":"sescontest.recent-people-activity"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-countdown',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"heading":"Contest Starting In","type":"starttime","placement_type":"extended","radious":"40","title":"","nomobile":"0","name":"sescontest.contest-countdown"}',
  ));
  $db->insert('engine4_core_content', array(
      'page_id' => $pageId,
      'type' => 'widget',
      'name' => 'core.container-tabs',
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"max":"8","title":"","name":"core.container-tabs","nomobile":"0"}',
  ));
  $tab_id = $db->lastInsertId('engine4_core_content');
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entries',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid"],"openViewType":"grid","show_criteria":["title","photo","description","submitDate","pPhoto","pName","socialsharing","likeButton","favouriteButton","voteButton","voteCount","likeCount","commentCount","viewCount","favouriteCount"],"pagging":"button","sorting":["Newest","Oldest","mostSPvoted","mostSPliked","mostSPcommented","mostSPViewed","mostSPfavorite"],"show_item_count":"0","list_title_truncation":"45","grid_title_truncation":"20","list_description_truncation":"150","grid_description_truncation":"50","socialshare_enable_plusicon":"1","socialshare_icon_limit":"3","height_list":"250","width_list":"350","height_grid":"210","width_grid":"307","limit_data":"9","title":"Entries","nomobile":"0","name":"sescontest.contest-entries"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedcomment.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  } else {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  }
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-award',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["firstAward","secondAward","thirdAward","fourthAward","fifthAward"],"title":"Awards","nomobile":"0","name":"sescontest.contest-award"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-info',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["info","date","description","profilefield"],"title":"Info","nomobile":"0","name":"sescontest.contest-info"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-overview',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Overview","name":"sescontest.contest-overview"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-rules',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Rules","name":"sescontest.contest-rules"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-similar-contest',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"advgrid","search":"category","show_criteria":["title","by","mediaType","category","socialSharing","likeButton","favouriteButton","followButton","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel"],"order":"","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"45","description_truncation":"150","height":"280","width":"304","width_pinboard":"270","limit_data":"9","title":"Similar Contests","nomobile":"0","name":"sescontest.contest-similar-contest"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedactivity.feed',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"What\'s New","design":"4","feeddesign":"2","scrollfeed":"1","autoloadTimes":"3","statusplacehoder":"Post Something...","userphotoalign":"center","enablefeedbgwidget":"1","feedbgorder":"random","enablewidthsetting":"0","sesact_image1":null,"sesact_image1_width":"500","sesact_image1_height":"450","sesact_image2":null,"sesact_image2_width":"289","sesact_image2_height":"200","sesact_image3":null,"sesact_image3_bigwidth":"328","sesact_image3_bigheight":"300","sesact_image3_smallwidth":"250","sesact_image3_smallheight":"150","sesact_image4":null,"sesact_image4_bigwidth":"578","sesact_image4_bigheight":"300","sesact_image4_smallwidth":"192","sesact_image4_smallheight":"100","sesact_image5":null,"sesact_image5_bigwidth":"289","sesact_image5_bigheight":"260","sesact_image5_smallwidth":"289","sesact_image5_smallheight":"130","sesact_image6":null,"sesact_image6_width":"289","sesact_image6_height":"150","sesact_image7":null,"sesact_image7_bigwidth":"192","sesact_image7_bigheight":"150","sesact_image7_smallwidth":"144","sesact_image7_smallheight":"150","sesact_image8":null,"sesact_image8_width":"144","sesact_image8_height":"150","sesact_image9":null,"sesact_image9_width":"192","sesact_image9_height":"150","nomobile":"0","name":"sesadvancedactivity.feed"}',
    ));
  }
}

// profile page design 4
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'sescontest_profile_index_4')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'sescontest_profile_index_4',
      'displayname' => 'SNS - Advanced Contests - Contest View Page Design 4',
      'title' => 'Contest View',
      'description' => 'This page displays a contest entry.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $mainId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $mainMiddleId = $db->lastInsertId();

  // Insert left
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 1,
  ));
  $rightId = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'bottom',
      'page_id' => $pageId,
      'order' => 3,
  ));
  $bottomId = $db->lastInsertId();

  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $bottomId,
      'order' => 3,
  ));
  $bottomMiddleId = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-countdown',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"heading":"Entry Submission Will Start In","type":"joinstarttime","placement_type":"extended","radious":"40","title":"","nomobile":"0","name":"sescontest.contest-countdown"}',
  ));
  $db->insert('engine4_core_content', array(
      'page_id' => $pageId,
      'type' => 'widget',
      'name' => 'core.container-tabs',
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"max":6}',
  ));
  $tab_id = $db->lastInsertId('engine4_core_content');
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-view',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"layout_type":"1","tab_placement":"out","tab_type":"center","show_criteria":["title","by","mediaType","socialSharing","likeButton","favouriteButton","followButton","joinButton","statusLabel","optionMenu"],"photo_type":"contestPhoto","show_full_width":"no","margin_top":"30","description_truncation":"150","cover_height":"400","socialshare_enable_plusicon":"1","socialshare_icon_limit":"1","title":"Highlights","nomobile":"0","name":"sescontest.contest-view"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-info',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["info","date","description","overview","profilefield"],"title":"Details","nomobile":"0","name":"sescontest.contest-info"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-rules',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"title":"Rules","name":"sescontest.contest-rules"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-award',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["firstAward","secondAward","thirdAward","fourthAward","fifthAward"],"title":"Awards","nomobile":"0","name":"sescontest.contest-award"}',
  ));
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedactivity.feed',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"What\'s New"}',
    ));
  }
  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesadvancedcomment.comments',
        'page_id' => $pageId,
        'parent_content_id' => $tab_id,
        'order' => $widgetOrder++,
        'params' => '{"title":"Comments"}',
    ));
  }
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.viewer-friends-participating',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"listing_type":"grid","criteria":["name"],"height":"120","width":"154","limit_data":"6","title":"Friends Participating in this Contest","nomobile":"0","name":"sescontest.viewer-friends-participating"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.owner-photo',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"title":"Contest Owner","showTitle":"1","nomobile":"0","name":"sescontest.owner-photo"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-labels',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"option":["featured","sponsored","verified","hot"],"title":"","nomobile":"0","name":"sescontest.contest-labels"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.advance-share',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"advShareOptions":["privateMessage","siteShare","quickShare","tellAFriend","addThis"],"title":"","nomobile":"0","name":"sescontest.advance-share"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-quick-create',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.recent-people-activity',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":["like"],"height":"66","width":"66","view_more_like":"2","view_more_favourite":"2","view_more_follow":"2","title":"","nomobile":"0","name":"sescontest.recent-people-activity"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-options',
      'page_id' => $pageId,
      'parent_content_id' => $rightId,
      'order' => $widgetOrder++,
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-winner-entries',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"grid","palcement":"horizontal","show_criteria":["title","listdescription","submitDate","ownerName","ownerPhoto","rank","socialSharing","likeButton","favouriteButton","voteCount","like","comment","favourite","view"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","openViewType":"low","height":"275","width":"236","width_pinboard":"300","title_truncation":"30","description_truncation":"200","title":"Winner Entries","nomobile":"0","name":"sescontest.contest-winner-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-entries',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"enableTabs":["list","grid"],"openViewType":"grid","show_criteria":["title","photo","description","submitDate","pPhoto","pName","socialsharing","likeButton","favouriteButton","voteButton","voteCount","likeCount","commentCount","viewCount","favouriteCount"],"pagging":"button","sorting":["Newest","Oldest","mostSPvoted","mostSPliked","mostSPcommented","mostSPViewed","mostSPfavorite"],"show_item_count":"0","list_title_truncation":"20","grid_title_truncation":"45","list_description_truncation":"200","grid_description_truncation":"70","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","height_list":"300","width_list":"480","height_grid":"260","width_grid":"297","limit_data":"16","title":"Participant Entries","nomobile":"0","name":"sescontest.contest-entries"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.contest-similar-contest',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"advgrid","search":"category","show_criteria":["title","description","by","mediaType","category","startenddate","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel"],"order":"ongoingSPupcomming","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"17","description_truncation":"45","height":"260","width":"393","width_pinboard":"300","limit_data":"3","title":"Similar Contests","nomobile":"0","name":"sescontest.contest-similar-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.other-contest',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"viewType":"advgrid","show_criteria":["title","description","by","mediaType","category","startenddate","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","featuredLabel","sponsoredLabel","verifiedLabel","hotLabel"],"socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","title_truncation":"17","description_truncation":"45","height":"250","width":"393","width_pinboard":"300","limit_data":"3","title":"Other Contests From Owner","nomobile":"0","name":"sescontest.other-contest"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'sescontest.profile-tags',
      'page_id' => $pageId,
      'parent_content_id' => $bottomMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"itemCountPerPage":"30","title":"Tags","nomobile":"0","name":"sescontest.profile-tags"}',
  ));
}

//categories default instalation work
//categoryname,banner image,simple icon,colored icon
$catgoryData = array(0 => array('Others', 'others.jpg', 'others.png', 'others.png', 'E6D8A1'), 1 => array('Fashion & Beauty', 'fashion-&-beauty.jpg', 'fashion-&-beauty.png', 'fashion-&-beauty.png', 'FF7C0A'), 2 => array('Entertainment', 'entertainment.jpg', 'entertainment.png', 'entertainment.png', 'C7304E'), 3 => array('Sports & Adventures', 'sports-&-adventures.jpg', 'sports-&-adventures.png', 'sports-&-adventures.png', '83E6D8'), 4 => array('Nature & Animals', 'nature-&-animals.jpg', 'nature-&-animals.png', 'nature-&-animals.png', '179C36'), 5 => array('Health & Food', 'health-&-food.jpg', 'health-&-food.png', 'health-&-food.png', '179C36'), 6 => array('Kids', 'kids.jpg', 'kids.png', 'kids.png', 'FF99B1'), 7 => array('Society & Personal', 'society-&-personal.jpg', 'society-&-personal.png', 'society-&-personal.png', 'FF0F7F'), 8 => array('Business & Technology', 'business-&-technology.jpg', 'business-&-technology.png', 'business-&-technology.png', 'FFEE59'), 9 => array('Education & Arts', 'education-&-arts.jpg', 'education-&-arts.png', 'education-&-arts.png', 'FFB163'));
$Entertainment = array(0 => array('Comedy', '', '', ''), 1 => array('Singing', '', '', ''), 2 => array('Dancing', '', '', ''));
$SportsAdventures = array(0 => array('Travel', '', '', ''), 1 => array('Treckking & Hiking', '', '', ''), 2 => array('Sports', '', '', ''));
$Sports = array(0 => array('Others', '', '', ''), 1 => array('Basketball', '', '', ''), 2 => array('Football', '', '', ''));
$NatureAnimals = array(0 => array('Animals', '', '', ''), 1 => array('Nature', '', '', ''));
$HealthFood = array(0 => array('Food', '', '', ''), 1 => array('Health', '', '', ''));
$EducationArts = array(0 => array('Education', '', '', ''), 1 => array('Arts', '', '', ''));
$Arts = array(0 => array('Modern Art', '', '', ''), 1 => array('Conceptual Paintings', '', '', ''), 2 => array('Abstract Paintings', '', '', ''));

foreach ($catgoryData as $key => $value) {
  //Upload categories icon
  $db->query("INSERT IGNORE INTO `engine4_sescontest_categories` (`category_name`,`subcat_id`,`subsubcat_id`,`slug`,`description`) VALUES ( '" . $value[0] . "',0,0,'','')");
  $catId = $db->lastInsertId();
  $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sescontest' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category" . DIRECTORY_SEPARATOR;

  //colored icon upload
  if (is_file($PathFile . "icons" . DIRECTORY_SEPARATOR . "color" . DIRECTORY_SEPARATOR . $value[3]))
    $color_icon = $this->setCategoryPhoto($PathFile . "icons" . DIRECTORY_SEPARATOR . "color" . DIRECTORY_SEPARATOR . $value[3], $catId, false);
  else
    $color_icon = 0;

  //simple icon
  if (is_file($PathFile . "icons" . DIRECTORY_SEPARATOR . "dark" . DIRECTORY_SEPARATOR . $value[2]))
    $cat_icon = $this->setCategoryPhoto($PathFile . "icons" . DIRECTORY_SEPARATOR . "dark" . DIRECTORY_SEPARATOR . $value[2], $catId, false);
  else
    $cat_icon = 0;

  //banner image
  if (is_file($PathFile . "banners" . DIRECTORY_SEPARATOR . $value[1]))
    $thumbnail_icon = $this->setCategoryPhoto($PathFile . "banners" . DIRECTORY_SEPARATOR . $value[1], $catId, true);
  else
    $thumbnail_icon = 0;

  $db->query("UPDATE `engine4_sescontest_categories` SET `cat_icon` = '" . $cat_icon . "',`thumbnail` = '" . $thumbnail_icon . "' ,`colored_icon` = '" . $color_icon . "' , `color` = '" . $value[4] . "' WHERE category_id = " . $catId);

  $valueName = str_replace(array(' ', '&', '/'), array('', '', ''), $value[0]);
  if (isset(${$valueName})) {
    foreach (${$valueName} as $value) {
      $db->query("INSERT IGNORE INTO `engine4_sescontest_categories` (`category_name`,`subcat_id`,`subsubcat_id`,`slug`,`description`) VALUES ( '" . $value[0] . "','" . $catId . "',0,'','')");
      $subId = $db->lastInsertId();
      $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sescontest' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category" . DIRECTORY_SEPARATOR;
      $cat_icon = 0;
      //upload banner image
      if (is_file($PathFile . "banners" . DIRECTORY_SEPARATOR . 'subcategory' . DIRECTORY_SEPARATOR . $value[1]))
        $thumbnail_icon = $this->setCategoryPhoto($PathFile . "banners" . DIRECTORY_SEPARATOR . 'subcategory' . DIRECTORY_SEPARATOR . $value[1], $subId, true);
      else
        $thumbnail_icon = 0;

      $db->query("UPDATE `engine4_sescontest_categories` SET `cat_icon` = '" . $cat_icon . "',`thumbnail` = '" . $thumbnail_icon . "' WHERE category_id = " . $subId);
      $valueSubName = str_replace(array(' ', '&', '/'), array('', '', ''), $value[0]);
      if (isset(${$valueSubName})) {
        foreach (${$valueSubName} as $value) {
          $db->query("INSERT IGNORE INTO `engine4_sescontest_categories` (`category_name`,`subcat_id`,`subsubcat_id`,`slug`,`description`) VALUES ( '" . $value[0] . "','0','" . $subId . "','','')");
          $subsubId = $db->lastInsertId();
          $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sescontest' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "category" . DIRECTORY_SEPARATOR;
          $cat_icon = 0;
          if (is_file($PathFile . "banners" . DIRECTORY_SEPARATOR . 'subcategory' . DIRECTORY_SEPARATOR . $value[1]))
            $thumbnail_icon = $this->setCategoryPhoto($PathFile . "banners" . DIRECTORY_SEPARATOR . 'subcategory' . DIRECTORY_SEPARATOR . $value[1], $subsubId, true);
          else
            $thumbnail_icon = 0;
          $db->query("UPDATE `engine4_sescontest_categories` SET `cat_icon` = '" . $cat_icon . "',`thumbnail` = '" . $thumbnail_icon . "' WHERE category_id = " . $subsubId);
        }
      }
    }
  }
  $runInstallCategory = true;
}
$db->query('UPDATE `engine4_sescontest_categories` set `slug` = LOWER(REPLACE(REPLACE(REPLACE(category_name,"&",""),"  "," ")," ","-")) where slug = "";');
$db->query('UPDATE `engine4_sescontest_categories` SET `order` = `category_id` WHERE `order` = 0;');
$db->query('UPDATE `engine4_sescontest_categories` set `title` = `category_name` where title = "" OR title IS NULL;');

//Upload Media Banners
$mediaTypes = array('photo' => 'Photo', 'video' => 'Video', 'text' => 'Text', 'audio' => 'Audio');
foreach ($mediaTypes as $key => $value) {
  if ($key == 'photo') {
    $column = '2';
    $image = 'photo.jpeg';
  } elseif ($key == 'video') {
    $column = '3';
    $image = 'video.jpeg';
  } elseif ($key == 'text') {
    $column = '1';
    $image = 'text.jpg';
  } elseif ($key == 'audio') {
    $column = '4';
    $image = 'music.jpeg';
  }
  $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sescontest' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "media-type" . DIRECTORY_SEPARATOR;
  if (is_file($PathFile . $image))
    $banner = $this->setCategoryPhoto($PathFile . $image, $column, false, 'media');
  else
    $banner = 0;
  $db->query("UPDATE `engine4_sescontest_medias` SET `banner` = '" . $banner . "' WHERE media_id = " . $column);
}

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescontest_admin_main_integrateothermodule", "sescontest", "Integrate Plugins", "", \'{"route":"admin_default","module":"sescontest","controller":"integrateothermodule","action":"index"}\', "sescontest_admin_main", "", 995);');

$db->query('DROP TABLE IF EXISTS `engine4_sescontest_integrateothermodules`;');
$db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_integrateothermodules` (
  `integrateothermodule_id` int(11) unsigned NOT NULL auto_increment,
  `module_name` varchar(64) NOT NULL,
  `content_type` varchar(64) NOT NULL,
  `content_url` varchar(255) NOT NULL,
  `content_id` varchar(64) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`integrateothermodule_id`),
  UNIQUE KEY `content_type` (`content_type`,`content_id`),
  KEY `module_name` (`module_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

$memberLevelColumn = $db->query('SHOW COLUMNS FROM engine4_sescontest_categories LIKE \'member_levels\'')->fetch();
if (empty($memberLevelColumn)) {
  $db->query('ALTER TABLE `engine4_sescontest_categories` ADD `member_levels` VARCHAR(255) NULL DEFAULT NULL;');
}
$db->query("UPDATE `engine4_sescontest_categories` SET `member_levels` = '1,2,3,4' WHERE `engine4_sescontest_categories`.`subcat_id` = 0 and  `engine4_sescontest_categories`.`subsubcat_id` = 0;");

$db->query('ALTER TABLE `engine4_contest_fields_meta` ADD `icon` TEXT NULL DEFAULT NULL;');

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescontest_admin_main_extension", "sescontest", "Extensions", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action": "extensions"}\', "sescontest_admin_main", "", 999);');
