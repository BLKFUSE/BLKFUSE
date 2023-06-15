<?php
//folder name or directory name.
$module_name = 'sesnews';

//product title and module title.
$module_title = 'News / RSS Importer & Aggregator Plugin';

if (!$this->getRequest()->isPost()) {
  return;
}

if (!$form->isValid($this->getRequest()->getPost())) {
  return;
}

if ($this->getRequest()->isPost()) {

  $postdata = array();
  //domain name
  $postdata['domain_name'] = $_SERVER['HTTP_HOST'];
  //license key
  $postdata['licenseKey'] = @base64_encode($_POST['sesnews_licensekey']);
  $postdata['module_title'] = @base64_encode($module_title);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://socialnetworking.solutions/licensecheck.php");
  curl_setopt($ch, CURLOPT_POST, 1);

  // in real life you should use something like:
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));

  // receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $server_output = curl_exec($ch);

  $error = 0;
  if (curl_error($ch)) {
    $error = 1;
  }
  curl_close($ch);

  //here we can set some variable for checking in plugin files.
  if ($server_output == "OK" && $error != 1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $db->query('INSERT IGNORE INTO `engine4_sesbasic_integrateothermodules` ( `module_name`, `type`, `content_type`, `content_type_photo`, `content_id`, `content_id_photo`, `enabled`) VALUES ("sesnews", "lightbox", "sesnews_album", "sesnews_photo", "album_id", "photo_id", 1);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_favourites`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_favourites` (
      `favourite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `resource_type` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
      `resource_id` int(11) NOT NULL,
      PRIMARY KEY (`favourite_id`),
      KEY `user_id` (`user_id`,`resource_type`,`resource_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_roles`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_roles` (
      `role_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `news_id` int(11) unsigned NOT NULL,
      PRIMARY KEY (`role_id`),
      KEY `user_id` (`news_id`,`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news` (
      `news_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `parent_id` int(11) DEFAULT "0",
      `photo_id` int(11) DEFAULT "0",
      `title` varchar(224) COLLATE utf8_unicode_ci NOT NULL,
      `body` longtext COLLATE utf8_unicode_ci NOT NULL,
      `location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `owner_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
      `owner_id` int(11) unsigned NOT NULL,
      `category_id` int(11) unsigned NOT NULL DEFAULT "0",
      `subcat_id` int(11) DEFAULT "0",
      `subsubcat_id` int(11) DEFAULT "0",
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `publish_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `starttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `endtime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `news_link` VARCHAR(255) NOT NULL,
      `view_count` int(11) unsigned NOT NULL DEFAULT "0",
      `comment_count` int(11) unsigned NOT NULL DEFAULT "0",
      `like_count` int(11) unsigned NOT NULL DEFAULT "0",
      `news_contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `news_contact_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `news_contact_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `news_contact_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `news_contact_facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `parent_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `seo_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `seo_description` text COLLATE utf8_unicode_ci,
      `featured` tinyint(1) NOT NULL DEFAULT "0",
      `sponsored` tinyint(1) NOT NULL DEFAULT "0",
      `hot` TINYINT(1) NOT NULL DEFAULT "0",
      `latest` TINYINT(1) NOT NULL DEFAULT "0",
      `verified` tinyint(1) NOT NULL DEFAULT "0",
      `is_approved` tinyint(1) NOT NULL DEFAULT "1",
      `ip_address` varchar(55) NOT NULL DEFAULT "0.0.0.0",
      `favourite_count` tinyint(11) NOT NULL DEFAULT "0",
      `offtheday` tinyint(1) NOT NULL,
      `style` tinyint(1) NOT NULL DEFAULT "1",
      `rating` float NOT NULL,
      `search` tinyint(1) NOT NULL DEFAULT "1",
      `draft` tinyint(1) unsigned NOT NULL DEFAULT "0",
      `is_publish` tinyint(1) NOT NULL DEFAULT "0",
      `rss_id` INT(11) NOT NULL DEFAULT "0",
      `senews_id` INT(11) NOT NULL DEFAULT "0",
      `resource_type` VARCHAR(128) NULL,
      `resource_id` INT(11) NOT NULL DEFAULT "0",
      `networks` VARCHAR(255) NULL,
      `levels` VARCHAR(255) NULL,
      `cotinuereading` TINYINT(1) NOT NULL DEFAULT "0",
      PRIMARY KEY (`news_id`),
      KEY `owner_type` (`owner_type`,`owner_id`),
      KEY `search` (`search`,`creation_date`),
      KEY `owner_id` (`owner_id`,`draft`),
      KEY `draft` (`draft`,`search`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_albums`;');
      $db->query('CREATE TABLE `engine4_sesnews_albums` (
      `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `news_id` int(11) unsigned NOT NULL,
      `owner_id` int(11) UNSIGNED NOT NULL,
      `title` varchar(128) NOT NULL,
      `description` mediumtext NOT NULL,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `search` tinyint(1) NOT NULL default "1",
      `photo_id` int(11) unsigned NOT NULL default "0",
      `view_count` int(11) unsigned NOT NULL default "0",
      `comment_count` int(11) unsigned NOT NULL default "0",
      `collectible_count` int(11) unsigned NOT NULL default "0",
      `like_count` int(11) NOT NULL DEFAULT "0",
      `position_cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `art_cover` int(11) NOT NULL DEFAULT "0",
      `favourite_count` int(11) UNSIGNED NOT NULL DEFAULT "0",
      PRIMARY KEY (`album_id`),
      KEY `news_id` (`news_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_photos`;');
      $db->query('CREATE TABLE `engine4_sesnews_photos` (
      `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `album_id` int(11) unsigned NOT NULL,
      `news_id` int(11) unsigned NOT NULL,
      `user_id` int(11) unsigned NOT NULL,
      `title` varchar(128) NOT NULL,
      `description` varchar(255) NOT NULL,
      `collection_id` int(11) unsigned NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `view_count` int(11) UNSIGNED NOT NULL DEFAULT "0",
      `comment_count` int(11) UNSIGNED NOT NULL DEFAULT "0",
      `like_count` int(11) UNSIGNED NOT NULL DEFAULT "0",
      `order` int(11) NOT NULL DEFAULT "0",
      `position_cover` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `art_cover` int(11) NOT NULL DEFAULT "0",
      `favourite_count` int(11) UNSIGNED NOT NULL DEFAULT "0",
      PRIMARY KEY (`photo_id`),
      KEY `album_id` (`album_id`),
      KEY `news_id` (`news_id`),
      KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_categories`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_categories` (
      `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `category_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
      `description` text COLLATE utf8_unicode_ci,
      `order` int(11) NOT NULL DEFAULT "0",
      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `subcat_id` int(11) DEFAULT "0",
      `subsubcat_id` int(11) DEFAULT "0",
      `thumbnail` int(11) NOT NULL DEFAULT "0",
      `cat_icon` int(11) NOT NULL DEFAULT "0",
      `colored_icon` int(11) NOT NULL,
      `color` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
      `profile_type_review` int(11) DEFAULT NULL,
      `profile_type` int(11) DEFAULT NULL,
      `senews_categoryid` INT(11) NULL,
      `member_levels` VARCHAR(255) NULL DEFAULT NULL,
      PRIMARY KEY (`category_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query("INSERT IGNORE INTO `engine4_sesnews_categories` (`category_id`, `user_id`, `category_name`, `description`, `order`, `title`, `slug`, `subcat_id`, `subsubcat_id`, `thumbnail`, `cat_icon`, `colored_icon`, `color`, `profile_type_review`, `profile_type`) VALUES (1, 1, 'Arts & Culture', '', 11, 'Arts & Culture', 'arts-culture', 0, 0, 0, 0, 0, NULL, 0, 0),(2, 1, 'Business', '', 10, 'Business', 'business', 0, 0, 0, 0, 0, NULL, NULL, 0),(3, 1, 'Entertainment', '', 9, 'Entertainment', 'entertainment', 0, 0, 0, 0, 0, NULL, NULL, 0),(5, 1, 'Family & Home', '', 8, 'Family & Home', 'family-home', 0, 0, 0, 0, 0, NULL, NULL, 0),(6, 1, 'Health', '', 7, 'Health', 'health', 0, 0, 0, 0, 0, NULL, NULL, 0),(7, 1, 'Recreation', '', 6, 'Recreation', 'recreation', 0, 0, 0, 0, 0, NULL, NULL, 0),(8, 1, 'Personal', '', 5, 'Personal', 'personal', 0, 0, 0, 0, 0, NULL, NULL, 0),(9, 1, 'Shopping', '', 4, 'Shopping', 'shopping', 0, 0, 0, 0, 0, NULL, NULL, 0),(10, 1, 'Society', '', 3, 'Society', 'society', 0, 0, 0, 0, 0, NULL, NULL, 0),(11, 1, 'Sports', '', 2, 'Sports', 'sports', 0, 0, 0, 0, 0, NULL, NULL, 0),(12, 1, 'Technology', '', 1, 'Technology', 'technology', 0, 0, 0, 0, 0, NULL, NULL, 0),(13, 1, 'Other', '', 0, 'Other', 'other', 0, 0, 0, 0, 0, NULL, NULL, 0)");
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_dashboards` ;');
      $db->query('CREATE TABLE `engine4_sesnews_dashboards` (
      `dashboard_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `type` varchar(128) NOT NULL,
      `title` varchar(128) NOT NULL,
      `enabled` tinyint(1) NOT NULL default "1",
      `main` tinyint(1) NOT NULL default "0",
      PRIMARY KEY (`dashboard_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_dashboards` (`type`, `title`, `enabled`, `main`) VALUES
      ("manage_news", "Manage News", "1", "1"),
      ("edit_news", "Edit News", "1", "0"),
      ("edit_photo", "Edit Photo", "1", "0"),
      ("news_role", "News Roles", "1", "0"),
      ("manage_news_video", "Manage Videos", "1", "0"),
      ("manage_news_albums", "Manage Albums", "1", "0"),
      ("contact_information", "Contact Information", "1", "0"),
      ("edit_style", "Edit Style", "1", "0"),
      ("edit_location", "Edit Location", "1", "0"),
      ("seo", "Seo Details", "1", "0");');

      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news_fields_maps`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news_fields_maps` (
      `field_id` int(11) NOT NULL,
      `option_id` int(11) NOT NULL,
      `child_id` int(11) NOT NULL,
      `order` smallint(6) NOT NULL,
      PRIMARY KEY (`field_id`,`option_id`,`child_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_news_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES (0, 0, 1, 1);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news_fields_meta`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news_fields_meta` (
      `field_id` int(11) NOT NULL AUTO_INCREMENT,
      `type` varchar(24) NOT NULL,
      `label` varchar(64) NOT NULL,
      `description` varchar(255) NOT NULL DEFAULT "",
      `alias` varchar(32) NOT NULL DEFAULT "",
      `required` tinyint(1) NOT NULL DEFAULT "0",
      `display` tinyint(1) unsigned NOT NULL,
      `publish` tinyint(1) unsigned NOT NULL DEFAULT "0",
      `search` tinyint(1) unsigned NOT NULL DEFAULT "0",
      `show` tinyint(1) unsigned DEFAULT "0",
      `order` smallint(3) unsigned NOT NULL DEFAULT "999",
      `config` text NOT NULL,
      `validators` text COLLATE utf8_unicode_ci,
      `filters` text COLLATE utf8_unicode_ci,
      `style` text COLLATE utf8_unicode_ci,
      `error` text COLLATE utf8_unicode_ci,
      PRIMARY KEY (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_news_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES (1, "profile_type", "Profile Type", "", "profile_type", 1, 0, 0, 2, 0, 999, "", NULL, NULL, NULL, NULL);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news_fields_options`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news_fields_options` (
      `option_id` int(11) NOT NULL AUTO_INCREMENT,
      `field_id` int(11) NOT NULL,
      `label` varchar(255) NOT NULL,
      `order` smallint(6) NOT NULL DEFAULT "999",
      `type` tinyint(1) NOT NULL DEFAULT "0",
      PRIMARY KEY (`option_id`),
      KEY `field_id` (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_news_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES (1, 1, "Rock News", 0);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news_fields_search`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news_fields_search` (
      `item_id` int(11) NOT NULL,
      `profile_type` smallint(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`item_id`),
      KEY `profile_type` (`profile_type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_news_fields_values`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_news_fields_values` (
      `item_id` int(11) NOT NULL,
      `field_id` int(11) NOT NULL,
      `index` smallint(3) NOT NULL DEFAULT "0",
      `value` text NOT NULL,
      PRIMARY KEY (`item_id`,`field_id`,`index`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci; ');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_parameters`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_parameters` (
      `parameter_id` int(11) NOT NULL AUTO_INCREMENT,
      `category_id` int(11) NOT NULL,
      `title` VARCHAR(255) NOT NULL,
      `rating` float NOT NULL,
      PRIMARY KEY (`parameter_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `priority`) VALUES
      ("SNS - News / RSS Importer & Aggregator - Rebuild Privacy", "sesnews_maintenance_rebuild_privacy", "sesnews", "Sesnews_Plugin_Job_Maintenance_RebuildPrivacy", 50);');
      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("sesnews_main", "standard", "SNS - News / RSS Importer & Aggregator - Main Navigation Menu"),
      ("sesnews_quick", "standard", "SNS - News / RSS Importer & Aggregator - Quick Navigation Menu"),
      ("sesnews_gutter", "standard", "SNS - News / RSS Importer & Aggregator - Gutter Navigation Menu"),
      ("sesnewsreview_profile", "standard", "SNS - News / RSS Importer & Aggregator - Review Profile Options Menu");');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesnewsreview_profile_edit", "sesnews", "Edit Review", "Sesnews_Plugin_Menus", "", "sesnewsreview_profile", "", 1),
      ("sesnewsreview_profile_delete", "sesnews", "Delete Review", "Sesnews_Plugin_Menus", "", "sesnewsreview_profile", "", 2),
      ("sesnewsreview_profile_report", "sesnews", "Report", "Sesnews_Plugin_Menus", "", "sesnewsreview_profile", "", 3),
      ("sesnewsreview_profile_share", "sesnews", "Share", "Sesnews_Plugin_Menus", "", "sesnewsreview_profile", "", 4),
      ("core_main_sesnews", "sesnews", "News", "", \'{"route":"sesnews_general"}\', "core_main", "", 4),
      ("core_sitemap_sesnews", "sesnews", "News", "", \'{"route":"sesnews_general"}\', "core_sitemap", "", 4),
      ("mobi_browse_sesnews", "sesnews", "News", "", \'{"route":"sesnews_general"}\', "mobi_browse", "", 3),
      ("sesnews_main_browsehome", "sesnews", "News Home", "", \'{"route":"sesnews_general","action":"home"}\', "sesnews_main", "", 1),
      ("sesnews_main_browsecategory", "sesnews", "Browse Categories", "", \'{"route":"sesnews_category"}\', "sesnews_main", "", 3),


      ("sesnews_main_browse", "sesnews", "Browse News", "Sesnews_Plugin_Menus::canViewSesnews", \'{"route":"sesnews_general","action":"browse"}\', "sesnews_main", "", 2),
      ("sesnews_main_browserss", "sesnews", "Browse RSS", "Sesnews_Plugin_Menus::canViewRssnews", \'{"route":"sesnews_generalrss","action":"browse"}\', "sesnews_main", "", 3),
      ("sesnews_main_location", "sesnews", "Locations", "Sesnews_Plugin_Menus::locationEnable", \'{"route":"sesnews_general","action":"locations"}\', "sesnews_main", "", 4),
      ("sesnews_main_reviews", "sesnews", "News Reviews", "Sesnews_Plugin_Menus::reviewEnable", \'{"route":"sesnews_review","action":"browse"}\', "sesnews_main", "", 6),
      ("sesnews_main_manage", "sesnews", "My News", "Sesnews_Plugin_Menus::canCreateSesnews", \'{"route":"sesnews_general","action":"manage"}\', "sesnews_main", "", 7),
      ("sesnews_main_managerss", "sesnews", "My RSS", "Sesnews_Plugin_Menus::canCreateRss", \'{"route":"sesnews_generalrss","action":"manage"}\', "sesnews_main", "", 8),
      ("sesnews_main_create", "sesnews", "Write New News", "Sesnews_Plugin_Menus::canCreateSesnews", \'{"route":"sesnews_general","action":"create"}\', "sesnews_main", "", 8),
      ("sesnews_main_createrss", "sesnews", "Add RSS", "Sesnews_Plugin_Menus::canCreateRss", \'{"route":"sesnews_generalrss","action":"create"}\', "sesnews_main", "", 9),
      ("sesnews_quick_create", "sesnews", "Write New News", "Sesnews_Plugin_Menus::canCreateSesnews", \'{"route":"sesnews_general","action":"create","class":"buttonlink icon_sesnews_new"}\', "sesnews_quick", "", 1),


      ("sesnews_quick_style", "sesnews", "Edit News Style", "Sesnews_Plugin_Menus", \'{"route":"sesnews_general","action":"style","class":"smoothbox buttonlink icon_sesnews_style"}\', "sesnews_quick", "", 2),
      ("sesnews_gutter_create", "sesnews", "Write New News", "Sesnews_Plugin_Menus", \'{"route":"sesnews_general","action":"create","class":"buttonlink icon_sesnews_new"}\', "sesnews_gutter", "", 2),
      ("sesnews_gutter_dashboard", "sesnews", "Dashboard", "Sesnews_Plugin_Menus", \'{"route":"sesnews_dashboard","action":"edit","class":"buttonlink icon_sesnews_edit"}\', "sesnews_gutter", "", 4),
      ("sesnews_gutter_delete", "sesnews", "Delete This News", "Sesnews_Plugin_Menus", \'{"route":"sesnews_specific","action":"delete","class":"buttonlink smoothbox icon_sesnews_delete"}\', "sesnews_gutter", "", 5),
      ("sesnews_gutter_share", "sesnews", "Share", "Sesnews_Plugin_Menus", \'{"route":"default","module":"activity","controller":"index","action":"share","class":"buttonlink smoothbox icon_comments"}\', "sesnews_gutter", "", 6),
      ("sesnews_gutter_report", "sesnews", "Report", "Sesnews_Plugin_Menus", \'{"route":"default","module":"core","controller":"report","action":"create","class":"buttonlink smoothbox icon_report"}\', "sesnews_gutter", "", 7),

      ("sesnews_admin_main_manage", "sesnews", "Manage News", "", \'{"route":"admin_default","module":"sesnews","controller":"manage"}\', "sesnews_admin_main", "", 2),
      ("sesnews_admin_main_managerss", "sesnews", "Manage Rss", "", \'{"route":"admin_default","module":"sesnews","controller":"manage-rss"}\', "sesnews_admin_main", "", 3),
      ("sesnews_admin_main_level", "sesnews", "Member Level Settings", "", \'{"route":"admin_default","module":"sesnews","controller":"level"}\', "sesnews_admin_main", "", 4),

      ("sesnews_admin_main_levelnews", "sesnews", "News", "", \'{"route":"admin_default","module":"sesnews","controller":"level"}\', "sesnews_admin_main_level", "", 1),
      ("sesnews_admin_main_levelrss", "sesnews", "Rss", "", \'{"route":"admin_default","module":"sesnews","controller":"level", "action":"rss-level"}\', "sesnews_admin_main_level", "", 2),

      ("sesnews_admin_main_categories", "sesnews", "Categories", "", \'{"route":"admin_default","module":"sesnews","controller":"categories","action":"index"}\', "sesnews_admin_main", "", 5),
      ("sesnews_admin_main_subcategories", "sesnews", "Categories", "", \'{"route":"admin_default","module":"sesnews","controller":"categories","action":"index"}\', "sesnews_admin_categories", "", 1),
      ("sesnews_admin_main_subfields", "sesnews", "Form Questions", "", \'{"route":"admin_default","module":"sesnews","controller":"fields"}\', "sesnews_admin_categories", "", 2),

      ("sesnews_admin_main_reviewsetting", "sesnews", "Review Settings", "", \'{"route":"admin_default","module":"sesnews","controller":"review", "action":"review-settings"}\', "sesnews_admin_main", "", 6),
      ("sesnews_admin_main_review_settings", "sesnews", "Review & Rating Settings", "",\'{"route":"admin_default","module":"sesnews","controller":"review", "action":"review-settings"}\', "sesnews_admin_main_reviewsetting", "", 1),
      ("sesnews_admin_main_managereview", "sesnews", "Manage Reviews", "", \'{"route":"admin_default","module":"sesnews","controller":"review", "action":"manage-reviews"}\', "sesnews_admin_main_reviewsetting", "", 2),
      ("sesnews_admin_main_levelsettings", "sesnews", "Member Level Setting", "", \'{"route":"admin_default","module":"sesnews","controller":"review", "action":"level-settings"}\', "sesnews_admin_main_reviewsetting", "", 3),
      ("sesnews_admin_main_reviewcat", "sesnews", "Rating Parameters", "", \'{"route":"admin_default","module":"sesnews","controller":"review-categories","action":"index"}\', "sesnews_admin_main_reviewsetting", "", 4),
      ("sesnews_admin_main_review_subcategories", "sesnews", "Categories & Mapping", "", \'{"route":"admin_default","module":"sesnews","controller":"review-categories","action":"index"}\', "sesnews_admin_main_reviewcat", "", 1),
      ("sesnews_admin_main_review_subfields", "sesnews", "Form Questions", "", \'{"route":"admin_default","module":"sesnews","controller":"review-fields"}\', "sesnews_admin_main_reviewcat", "", 2),

      ("sesnews_admin_main_statistic", "sesnews", "Statistics", "", \'{"route":"admin_default","module":"sesnews","controller":"settings","action":"statistic"}\', "sesnews_admin_main", "", 7),
      ("sesnews_admin_main_managepages", "sesnews", "Widgetized Pages", "", \'{"route":"admin_default","module":"sesnews","controller":"settings", "action":"manage-widgetize-page"}\', "sesnews_admin_main", "", 999);');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES 
      ("sesnews_new", "sesnews", \'{item:$subject} added a news {item:$object}:\', 1, 5, 1, 3, 1, 1), ("comment_sesnews", "sesnews", \'{item:$subject} commented on {item:$owner}\'\'s {item:$object:news entry}: {body:$body}\', 1, 1, 1, 1, 1, 0),
      ("sesnews_news_like", "sesnews", \'{item:$subject} likes the news {item:$object}:\', 1, 7, 1, 1, 1, 1),
      ("sesnews_album_like", "sesnews", \'{item:$subject} likes the news album {item:$object}:\', 1, 7, 1, 1, 1, 1),
      ("sesnews_photo_like", "sesnews", \'{item:$subject} likes the news photo {item:$object}:\', 1, 7, 1, 1, 1, 1),
      ("sesnews_news_favourite", "sesnews", \'{item:$subject} added news {item:$object} to favorite:\', 1, 7, 1, 1, 1, 1);');

      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_categorymappings`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_categorymappings` (
      `categorymapping_id` int(11) NOT NULL AUTO_INCREMENT,
      `module_name` varchar(64) NOT NULL,
      `category_id` int(11) NOT NULL,
      `profiletype_id` int(11) NOT NULL,
      `profile_type` varchar(255) NOT NULL,
      PRIMARY KEY (`categorymapping_id`),
      KEY `category_id` (`category_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_reviews`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_reviews` (
      `review_id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `pros` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `cons` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `description` text COLLATE utf8_unicode_ci NOT NULL,
      `recommended` tinyint(1) NOT NULL DEFAULT "1",
      `owner_id` int(11) unsigned NOT NULL,
      `news_id` int(11) unsigned NOT NULL DEFAULT "0",
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `like_count` int(11) NOT NULL,
      `comment_count` int(11) NOT NULL,
      `view_count` int(11) NOT NULL,
      `rating` tinyint(1) DEFAULT NULL,
      `featured` tinyint(1) NOT NULL DEFAULT "0",
      `sponsored` tinyint(1) NOT NULL DEFAULT "0",
      `verified` tinyint(1) NOT NULL DEFAULT "0",
      `oftheday` tinyint(1) DEFAULT "0",
      `starttime` datetime DEFAULT NULL,
      `endtime` datetime DEFAULT NULL,
      PRIMARY KEY (`review_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_fields_maps`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_fields_maps` (
      `field_id` int(11) NOT NULL,
      `option_id` int(11) NOT NULL,
      `child_id` int(11) NOT NULL,
      `order` smallint(6) NOT NULL,
      PRIMARY KEY (`field_id`,`option_id`,`child_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_review_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES (0, 0, 1, 1);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_fields_meta`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_fields_meta` (
      `field_id` int(11) NOT NULL AUTO_INCREMENT,
      `type` varchar(24) NOT NULL,
      `label` varchar(64) NOT NULL,
      `description` varchar(255) NOT NULL DEFAULT "",
      `alias` varchar(32) NOT NULL DEFAULT "",
      `required` tinyint(1) NOT NULL DEFAULT "0",
      `display` tinyint(1) unsigned NOT NULL,
      `publish` tinyint(1) unsigned NOT NULL DEFAULT "0",
      `search` tinyint(1) unsigned NOT NULL DEFAULT "0",
      `show` tinyint(1) unsigned DEFAULT "0",
      `order` smallint(3) unsigned NOT NULL DEFAULT "999",
      `config` text NOT NULL,
      `validators` text COLLATE utf8_unicode_ci,
      `filters` text COLLATE utf8_unicode_ci,
      `style` text COLLATE utf8_unicode_ci,
      `error` text COLLATE utf8_unicode_ci,
      PRIMARY KEY (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_review_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES (1, "profile_type", "Profile Type", "", "profile_type", 1, 0, 0, 2, 0, 999, "", NULL, NULL, NULL, NULL);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_fields_options`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_fields_options` (
      `option_id` int(11) NOT NULL AUTO_INCREMENT,
      `field_id` int(11) NOT NULL,
      `label` varchar(255) NOT NULL,
      `order` smallint(6) NOT NULL DEFAULT "999",
      `type` tinyint(1) NOT NULL DEFAULT "0",
      PRIMARY KEY (`option_id`),
      KEY `field_id` (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_sesnews_review_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES (1, 1, "Default", 0);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_fields_search`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_fields_search` (
      `item_id` int(11) NOT NULL,
      `profile_type` smallint(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`item_id`),
      KEY `profile_type` (`profile_type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_fields_values`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_fields_values` (
      `item_id` int(11) NOT NULL,
      `field_id` int(11) NOT NULL,
      `index` smallint(3) NOT NULL DEFAULT "0",
      `value` text NOT NULL,
      PRIMARY KEY (`item_id`,`field_id`,`index`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_review_parametervalues`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_review_parametervalues` (
      `parametervalue_id` int(11) NOT NULL AUTO_INCREMENT,
      `parameter_id` int(11) NOT NULL,
      `rating` float NOT NULL,
      `user_id` INT(11) NOT NULL,
      `resources_id` INT(11) NOT NULL,
      `content_id` INT(11) NOT NULL,
      PRIMARY KEY (`parametervalue_id`),
      UNIQUE KEY `uniqueKey` (`parameter_id`,`user_id`,`resources_id`,`content_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_rss` (
      `rss_id` int(11) unsigned NOT NULL auto_increment,
      `rss_link` varchar(128) NOT NULL,
      `title` varchar(128) NOT NULL,
      `body` longtext NOT NULL,
      `owner_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
      `owner_id` int(11) unsigned NOT NULL,
      `category_id` int(11) unsigned NOT NULL DEFAULT "0",
      `subcat_id` int(11) DEFAULT "0",
      `subsubcat_id` int(11) DEFAULT "0",
      `publish_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
      `photo_id` int(11) unsigned NOT NULL default "0",
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `view_count` int(11) unsigned NOT NULL default "0",
      `comment_count` int(11) unsigned NOT NULL default "0",
      `like_count` int(11) unsigned NOT NULL default "0",
      `news_count` int(11) unsigned NOT NULL default "0",
      `search` tinyint(1) NOT NULL default "1",
      `draft` tinyint(1) unsigned NOT NULL default "0",
      `view_privacy` VARCHAR(24) NOT NULL,
      `comment_privacy` VARCHAR(24) NOT NULL,
      `is_approved` TINYINT(1) NOT NULL DEFAULT "1",
      `cron_enabled` TINYINT(1) NOT NULL DEFAULT "0",
      `subscriber_count` INT(11) NOT NULL,
      PRIMARY KEY (`rss_id`),
      KEY `owner_type` (`owner_type`, `owner_id`),
      KEY `search` (`search`, `creation_date`),
      KEY `owner_id` (`owner_id`, `draft`),
      KEY `draft` (`draft`, `search`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_rsssubscriptions` (
      `rsssubscription_id` int(10) unsigned NOT NULL auto_increment,
      `rss_id` int(10) unsigned NOT NULL,
      `subscriber_user_id` int(10) unsigned NOT NULL,
      PRIMARY KEY  (`rsssubscription_id`),
      UNIQUE KEY `rss_id` (`rss_id`,`subscriber_user_id`),
      KEY `subscriber_user_id` (`subscriber_user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

      $db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
      ("SNS - News Plugin - Fetched News from RSS Feed", "sesnews", "Sesnews_Plugin_Task_Fetchenews", 172800);');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("sesnews_subscribedrss", "sesnews", \'{item:$subject} has subscribe your rss {item:$object}.\', 0, "");');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("sesnews_subscribed_new", "sesnews", \'{item:$subject} has posted a new news {item:$object}.\', 0, "");');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesnews_integrateothermodules` (
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
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesnews_admin_manageblackurl", "sesnews", "Blacklist URL", "", \'{"route":"admin_default","module":"sesnews","controller":"manage-blacklist"}\', "sesnews_admin_main", "", 88);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesnews_urls`;');
      $db->query('CREATE TABLE `engine4_sesnews_urls` (
        `url_id` int(11) unsigned NOT NULL auto_increment,
        `name` varchar(255) NOT NULL,
        `enabled` TINYINT(1) NOT NULL DEFAULT "1",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`url_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      include_once APPLICATION_PATH . "/application/modules/Sesnews/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesnews.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesnews.licensekey', $_POST['sesnews_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesnews.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesnews.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesnews.licensekey', $_POST['sesnews_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
