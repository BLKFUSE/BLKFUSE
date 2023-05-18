<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: defaultsettings.php  2018-08-14 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
$db = Zend_Db_Table_Abstract::getDefaultAdapter();

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescommunityads_admin_main_support", "sescommunityads", "Help", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings", "action":"support"}\', "sescommunityads_admin_main", "", 999),
("sescommunityads_admin_main_managepages", "sescommunityads", "Widgetized Pages", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings", "action":"manage-widgetize-page"}\', "sescommunityads_admin_main", "", 990);');

$db->query("INSERT IGNORE INTO engine4_sescommunityads_feedsettings (`module`, `type`, `creation_date`) SELECT `module`, `type`, 'NOW()' FROM engine4_activity_actiontypes;");

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_package')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_package',
        'displayname' => 'SNS - Community Advertisements Plugin - Choose Package Page',
        'title' => 'Community Advertisements Plugin Create Page',
        'description' => 'This page is the choose package page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.content',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_browse')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_browse',
        'displayname' => 'SNS - Community Advertisements Plugin - Browse Page',
        'title' => 'Community Advertisements Plugin Browse Page',
        'description' => 'This page is the browse ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.browse-search',
        'page_id' => $page_id,
        'parent_content_id' => $main_right_id,
        'order' => 4,
        'params' => '{"view_type":"vertical","search_type":["recentlySPcreated","mostSPviewed","featured","sponsored"],"default_search_type":"recentlySPcreated","content_option":"yes","friend_show":"yes","categories":"yes","location":"yes","title":"","nomobile":"0","name":"sescommunityads.browse-search"}',
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.browse-ads',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 5,
        'params' => '{"category":"","featured_sponsored":"3","limit":"9","pagging":"auto_load","title":"","nomobile":"0","name":"sescommunityads.browse-ads"}',
    ));

}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_manageads')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_manageads',
        'displayname' => 'SNS - Community Advertisements Plugin - Manage Ads Page',
        'title' => 'Community Advertisements Plugin Manage Ads Page',
        'description' => 'This page is the manage ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.manage-ads',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.campaign-stats',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 5,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_manage')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_manage',
        'displayname' => 'SNS - Community Advertisements Plugin - Manage Campaign Page',
        'title' => 'Community Advertisements Plugin Manage Campaign Page',
        'description' => 'This page is the manage ads campaign page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.manage-campaign',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_create')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_create',
        'displayname' => 'SNS - Community Advertisements Plugin - Create Page',
        'title' => 'Community Advertisements Plugin Create Page',
        'description' => 'This page is the create ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'core.content',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_report')
    ->limit(1)
    ->query()
    ->fetchColumn();
if (!$page_id) {
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_report',
        'displayname' => 'SNS - Community Advertisements Plugin - Report Page',
        'title' => 'Community Advertisements Plugin Report Page',
        'description' => 'This page is the report ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.report',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_help-and-learn')
    ->limit(1)
    ->query()
    ->fetchColumn();
if(!$page_id){
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_help-and-learn',
        'displayname' => 'SNS - Community Advertisements Plugin - Help And Learn Page',
        'title' => 'Community Advertisements Plugin Help And Learn Page',
        'description' => 'This page is the help and learn ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.help-and-learn',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
}

$page_id = $db->select()
    ->from('engine4_core_pages', 'page_id')
    ->where('name = ?', 'sescommunityads_index_view')
    ->limit(1)
    ->query()
    ->fetchColumn();
if(!$page_id){
    // Insert page
    $db->insert('engine4_core_pages', array(
        'name' => 'sescommunityads_index_view',
        'displayname' => 'SNS - Community Advertisements Plugin - View Page',
        'title' => 'Community Advertisements Plugin View Page',
        'description' => 'This page is the view ads page.',
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
        'name' => 'sescommunityads.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => 3,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.view',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 4,
    ));
    // Insert content
    $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sescommunityads.ads-stats',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => 5,
    ));
}

$db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
("sescommunityads.ads.count", "1"),
("sescommunityads.advertisement.display", "3"),
("sescommunityads.advertisement.displayads", "2"),
("sescommunityads.advertisement.displayfeed", "1"),
("sescommunityads.advertisement.enable", "1"),
("sescommunityads.boost.default.adult", "application/modules/Sescommunityads/externals/images/boost_post_default.png"),
("sescommunityads.category.enable", "1"),
("sescommunityads.category.mandatory", "1"),
("sescommunityads.enable.location", "1"),
("sescommunityads.package.style", "1"),
("sescommunityads.payment.mod.enable", "all"),
("sescommunityads.search.type", "0");');

$db->query('DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = "sescommunityads.package.settings";');
$db->query('DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = "sescommunityads.call.toaction";');

$db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_locations`;');
$db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_locations` (
`location_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`resource_id` INT( 11 ) NOT NULL ,
`lat` DECIMAL( 10, 8 ) NULL ,
`lng` DECIMAL( 11, 8 ) NULL ,
`resource_type` VARCHAR( 65 ) NOT NULL DEFAULT "sescommunityads",
`venue` VARCHAR(255) NULL,
`address` TEXT NULL,
`address2` TEXT NULL,
`city` VARCHAR(255) NULL,
`state` VARCHAR(255) NULL,
`zip` VARCHAR(255) NULL,
`country` VARCHAR(255) NULL,
`modified_date` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

$db->query("ALTER TABLE `engine4_sescommunityads_transactions` ADD `credit_point` INT(11) NOT NULL DEFAULT '0', ADD `credit_value` FLOAT NOT NULL DEFAULT '0';");
$db->query("ALTER TABLE `engine4_sescommunityads_transactions` ADD `ordercoupon_id` INT NULL DEFAULT '0';");

$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescommunityads_admin_main_extension", "sescommunityads", "Extensions", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings", "action": "extensions"}\', "sescommunityads_admin_main", "", 999);');
