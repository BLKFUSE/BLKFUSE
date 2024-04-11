<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'egifts_profile_index')
        ->limit(1)
        ->query()
        ->fetchColumn();
// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
// Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egifts_profile_index',
      'displayname' => 'SNS - Gifts - Profile View Page',
      'title' => 'Gift View Page',
      'description' => 'This page display all details related to the current gift like description, start, end date etc.',
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
  // Insert middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $mainId,
      'order' => 6,
  ));
  $mainMiddleId = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
    'page_id' => $pageId,
    'type' => 'container',
    'name' => 'right',
    'parent_content_id' => $mainId,
    'order' => 5,
    'params' => '',
  ));
  $right_id = $db->lastInsertId('engine4_core_content');
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $right_id,
      'order' => $widgetOrder++,
      'params' => '{"criteria":"by_me","show_criteria":["title","image","price"],"height":"60","width":"60","title_truncation":"10","description_truncation":"150","limit_data":"3","title":"Recently Viewed","nomobile":"0","name":"egifts.recently-viewed-item"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '[]',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.gift-view',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["title","image","price","description","sendButton","likeButton","favoriteButton","viewCount","likeCount","favoriteCount"],"title":"","nomobile":"0","name":"egifts.gift-view"}',
  ));
}
//SNS - Gift Browse Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'egifts_index_browse')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
// Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egifts_index_browse',
      'displayname' => 'SNS - Gifts - Gift Browse Page',
      'title' => 'Browse Gifts',
      'description' => 'This page lists all gifts which are created by members of the website.',
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
      'name' => 'egifts.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '["[]"]',
  ));

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.recently-viewed-item',
      'page_id' => $pageId,
      'parent_content_id' => $mainRightId,
      'order' => $widgetOrder++,
      'params' => '{"criteria":"by_me","show_criteria":["title","image","price"],"height":"60","width":"60","title_truncation":"10","description_truncation":"150","limit_data":"3","title":"Recently Viewed","nomobile":"0","name":"egifts.recently-viewed-item"}',
  ));

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.browse-gifts',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["title","image","price","description","sendButton","likeButton","favoriteButton"],"title_truncation":"150","description_truncation":"150","height":"200","search_type":"recentlySPcreated","show_item_count":"1","limit_data":"10","pagging":"button","title":"Browse Gifts","nomobile":"0","name":"egifts.browse-egifts"}',
  ));
}

//SNS - Virtual Gifts Browse Page
$pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'egifts_index_my-gifts')
        ->limit(1)
        ->query()
        ->fetchColumn();

// insert if it doesn't exist yet
if (!$pageId) {
  $widgetOrder = 1;
// Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egifts_index_my-gifts',
      'displayname' => 'SNS - Gifts - My Gifts Page',
      'title' => 'My Gifts Page',
      'description' => 'This page displays all the gift which are Received by users.',
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
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => $widgetOrder++,
      'params' => '{"title":"","name":"egifts.browse-menu"}',
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
      'name' => 'egifts.received-gifts',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["title","image","description","displayMsg","sendBy"],"title_truncation":"150","description_truncation":"150","height":"200","show_item_count":"1","limit_data":"10","pagging":"auto_load","title":"Received Gifts","nomobile":"0","name":"egifts.received-gifts"}',
  ));
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egifts.sent-gifts',
      'page_id' => $pageId,
      'parent_content_id' => $tab_id,
      'order' => $widgetOrder++,
      'params' => '{"show_criteria":["title","image","price","description","sentTo"],"title_truncation":"150","description_truncation":"150","height":"200","show_item_count":"1","limit_data":"10","pagging":"auto_load","title":"Sent Gifts","nomobile":"0","name":"egifts.sent-gifts"}',
  ));
}
