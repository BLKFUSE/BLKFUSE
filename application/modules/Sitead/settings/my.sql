/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: my.sql  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
--
-- Dumping data for table `engine4_core_modules`
--

INSERT  IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('sitead', 'Advertisements, Community Ads & Marketing Campaigns Plugin', 'Create a plugin for Social Engine for providing ads', '5.0.1', 1, 'extra');

--
-- Dumping data for table `engine4_core_menuitems`
--
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
('core_main_sitead', 'sitead', 'Advertising', 'Sitead_Plugin_Menus::canViewAdvertiesment', '{"route":"sitead_display","icon":"fa-bullhorn"}', 'core_main', '', 1, 0, 2),
('sitead_main_adboard', 'sitead', 'Ad Board', 'Sitead_Plugin_Menus::canViewAdvertiesment', '{"route":"sitead_display","action":"adboard","controller":"display"}', 'sitead_main', '', 1, 0, 1),
('sitead_main_campaigns', 'sitead', 'My Campaigns', 'Sitead_Plugin_Menus::canManageAdvertiesment', '{"route":"sitead_campaigns","action":"index","controller":"statistics"}', 'sitead_main', '', 1, 0, 2),
('sitead_main_create', 'sitead', 'Create an Ad', 'Sitead_Plugin_Menus::canCreateAdvertiesment', '{"route":"sitead_listpackage","action":"index","controller":"index"}', 'sitead_main', '', 1, 0, 3),
('sitead_main_report', 'sitead', 'Reports', 'Sitead_Plugin_Menus::canManageAdvertiesment', '{"route":"sitead_reports","action":"export-report","controller":"statistics"}', 'sitead_main', '', 1, 0, 4),
('sitead_main_help', 'sitead', 'Help & Learn More', '', '{"route":"sitead_help_and_learnmore","action":"help-and-learnmore","controller":"display"}', 'sitead_main', '', 1, 0, 5);

DELETE FROM `engine4_core_settings` WHERE `engine4_core_settings`.`name` = "sitead.navi.auth" LIMIT 1;    
  DROP TABLE IF EXISTS `engine4_sitead_target`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_target` (
		`target_id` int(11) NOT NULL AUTO_INCREMENT,
		`field_id` int(11) NOT NULL,
		`mp_id` int(11) NOT NULL,
		PRIMARY KEY (`target_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci COMMENT="Used for targetting the advertisements" AUTO_INCREMENT=1 ;

	DROP TABLE IF EXISTS `engine4_sitead_adtype`;
	CREATE TABLE IF NOT EXISTS  `engine4_sitead_adtype` (
	  `adtype_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	  `type` VARCHAR( 255 ) NOT NULL ,
	  `title` VARCHAR( 255 ) NOT NULL ,
	  `desc` VARCHAR( 255 ) NOT NULL ,
	  `status` INT NOT NULL
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

	INSERT IGNORE INTO `engine4_sitead_adtype` (`type`, `title`, `desc`, `status`) VALUES
    ('boost', 'Boost A Post', 'Make your post more reachable to users.', 1),
    ('content', 'Promote Your Content', 'Engage more people with the content you post.', 1),
    ('page', 'Promote Your Page', 'Grow visibility and awareness to your page.', 1),
    ('website', 'Get More Website Visitor', 'Get more visitors to your website.', 1);

	DROP TABLE IF EXISTS `engine4_sitead_userads`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_userads` (
		`userad_id` int(11) NOT NULL AUTO_INCREMENT,
		`cmd_ad_type` ENUM("boost","page", "content", "website") CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "website",
		`cmd_ad_format` ENUM("carousel","image", "video") CHARACTER SET utf8 COLLATE utf8_unicode_ci ,
		`ad_type` ENUM("default") CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "default",
		`package_id` int(11) NOT NULL,
		`campaign_id` int(11) NOT NULL,
		`web_url` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		`web_name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
		`owner_id` int(11) NOT NULL,
		`photo_id` int(11) NOT NULL,
		`create_date` datetime NOT NULL,
		`cads_start_date` datetime NOT NULL,
		`cads_end_date` datetime DEFAULT NULL,
		`sponsored` tinyint(1) NOT NULL,
		`featured` tinyint(1) NOT NULL,
		`like` tinyint(1) NOT NULL,
		`resource_type` VARCHAR( 65 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		`resource_id` int(11) NOT NULL,
		`public` tinyint(1) NOT NULL DEFAULT "1",
		`location` text NOT NULL,
		`approved` tinyint(1) NOT NULL,
		`enable` tinyint(1) NOT NULL,
		`status` tinyint(1) NOT NULL,
		`payment_status` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "free",
		`declined` tinyint(1) NOT NULL DEFAULT "0",
		`approve_date` datetime DEFAULT NULL,
		`price_model` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		`limit_click` int(11) NOT NULL DEFAULT "0",
		`limit_view` int(11) NOT NULL DEFAULT "0",
		`count_click` int(11) NOT NULL DEFAULT "0",
		`count_view` int(11) NOT NULL DEFAULT "0",
		`count_like` int(11) NOT NULL DEFAULT "0",
		`expiry_date` date DEFAULT NULL,
		`weight` int(11) DEFAULT "0",
		`min_ctr` float NOT NULL DEFAULT "0",
		`gateway_id` int(10) NOT NULL DEFAULT "0",
		`gateway_profile_id` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
		`renewbyadmin_date` datetime default NULL,
		`profile` int(11) NOT NULL DEFAULT "0",
		PRIMARY KEY (`userad_id`),
    KEY `ad_type` (`ad_type`),
    KEY `package_id` (`package_id`),
    KEY `campaign_id` (`campaign_id`),
    KEY `owner_id` (`owner_id`),
    KEY `sponsored` (`sponsored`),
    KEY `featured` (`featured`),
    KEY `approved` (`approved`,`enable`,`status`,`declined`),
    KEY `public` (`public`,`approved`,`enable`,`status`,`declined`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `engine4_sitead_adsinfo`;
CREATE TABLE IF NOT EXISTS `engine4_sitead_adsinfo` (
  `adsinfo_id` int(11) NOT NULL AUTO_INCREMENT,
  `userad_id` int(11) NOT NULL,
  `cads_title` varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cads_body` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cads_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cta_button` varchar(65) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `file_id` int(11) NOT NULL,
  `file_type` varchar(65) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `overlay` varchar(65) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`adsinfo_id`),
  KEY `userad_id` (`userad_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `engine4_sitead_locations`;
CREATE TABLE IF NOT EXISTS `engine4_sitead_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `userad_id` int(11) NOT NULL,
  `location` text,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `formatted_address` text,
  `country` varchar(255) DEFAULT NULL,
  `state` varchar(255)  DEFAULT NULL,
  `zipcode` varchar(255)  DEFAULT NULL,
  `city` varchar(255)  DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `location_distance` int(11) NOT NULL,
   PRIMARY KEY (`location_id`),
   KEY `userad_id` (`userad_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `engine4_sitead_categories`;
CREATE TABLE IF NOT EXISTS `engine4_sitead_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(128) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;
INSERT IGNORE INTO `engine4_sitead_categories` (`category_name`) VALUES
   ('Shop Now'),
   ('Learn More'),
   ('Buy Now'),
   ('Install');	

DROP TABLE IF EXISTS `engine4_sitead_adcampaigns`;
CREATE TABLE IF NOT EXISTS `engine4_sitead_adcampaigns` (
  `adcampaign_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT "0",
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`adcampaign_id`)
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;

	DROP TABLE IF EXISTS `engine4_sitead_adstatistics`;

	CREATE TABLE IF NOT EXISTS `engine4_sitead_adstatistics` (
		`adstatistic_id` int(11) NOT NULL AUTO_INCREMENT,
		`userad_id` int(11) NOT NULL,
		`adcampaign_id` int(11) NOT NULL,
		`viewer_id` int(11) NOT NULL,
		`hostname` varchar(60) DEFAULT NULL,
		`user_agent` varchar(500) DEFAULT NULL,
		`url` varchar(1000) DEFAULT NULL,
		`response_date` datetime NOT NULL,
		`value_click` int(11) DEFAULT NULL,
		`value_view` int(11) DEFAULT NULL,
		`value_like` varchar(35) DEFAULT NULL,
		 PRIMARY KEY (`adstatistic_id`),
		 KEY `viewer_id` (`viewer_id`),
		 KEY `userad_id` (`userad_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;

	DROP TABLE IF EXISTS `engine4_sitead_adcancels`;

	CREATE TABLE IF NOT EXISTS `engine4_sitead_adcancels` (
		`adcancel_id` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) NOT NULL,
		`report_type` varchar(35) NOT NULL,
		`report_description` text,
		`ad_id` int(11) NOT NULL,
	    `is_cancel` int(11) NOT NULL,
		`creation_date` datetime NOT NULL,
		PRIMARY KEY (`adcancel_id`),
		KEY `user_id` (`user_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;


	DROP TABLE IF EXISTS `engine4_sitead_adtargets`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_adtargets` (
		`adtarget_id` int(11) NOT NULL AUTO_INCREMENT,
		`userad_id` int(11) NOT NULL,
		`birthday_enable` tinyint(1) NOT NULL DEFAULT "0",
		`age_min` int(11) DEFAULT NULL,
		`age_max` int(11) DEFAULT NULL,
		`networks` varchar(500) DEFAULT NULL,
		PRIMARY KEY (`adtarget_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

	DROP TABLE IF EXISTS `engine4_sitead_transactions`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_transactions` (
		`transaction_id` int(10) unsigned NOT NULL auto_increment,
		`user_id` int(10) unsigned NOT NULL default "0",
		`gateway_id` int(10) unsigned NOT NULL,
		`timestamp` datetime NOT NULL,
		`order_id` int(10) unsigned NOT NULL default "0",
		`type` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
		`state` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
		`gateway_transaction_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
		`gateway_parent_transaction_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
		`gateway_order_id` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
		`amount` decimal(16,2) NOT NULL,
		`currency` char(3) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL default "",
		PRIMARY KEY  (`transaction_id`),
		KEY `user_id` (`user_id`),
		KEY `gateway_id` (`gateway_id`),
		KEY `type` (`type`),
		KEY `state` (`state`),
		KEY `gateway_transaction_id` (`gateway_transaction_id`),
		KEY `gateway_parent_transaction_id` (`gateway_parent_transaction_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;

	DROP TABLE IF EXISTS `engine4_sitead_modules`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_modules` (
		`module_id` int(11) NOT NULL AUTO_INCREMENT,
		`module_name` varchar(150) NOT NULL,
		`module_title` varchar(250) NOT NULL,
		`table_name` varchar(150) NOT NULL,
		`title_field` varchar(150) NOT NULL,
		`body_field` varchar(150) NOT NULL,
		`owner_field` varchar(150) NOT NULL,
		`displayable` INT(11) NOT NULL DEFAULT 7,
		`is_delete` int(11) NOT NULL DEFAULT 0,
		PRIMARY KEY (`module_id`),
    KEY `module_name` (`module_name`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;

	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"create" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"delete" as `name`,
			2 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"edit" as `name`,
			2 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"showdetail" as `name`,
			2 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"view" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"create" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("user");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"delete" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("user");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"edit" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("user");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"showdetail" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("user");
	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"view" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("user");

	INSERT IGNORE INTO `engine4_authorization_permissions`
		SELECT
			level_id as `level_id`,
			"sitead" as `type`,
			"view" as `name`,
			1 as `value`,
			NULL as `params`
		FROM `engine4_authorization_levels` WHERE `type` IN("public");

	INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `custom`, `order`) VALUES
	( "core_admin_main_plugins_sitead", "sitead", "SEAO - Advertisements, Community Ads & Marketing Campaigns Plugin", "", '{"route":"admin_default","module":"sitead", "controller":"settings"}', "core_admin_main_plugins", "", 0, 1),
	( "sitead_admin_main_settings", "sitead", "Global Settings", "", '{"route":"admin_default","module":"sitead","controller":"settings"}', "sitead_admin_main", "", 0, 1),
	( "sitead_admin_main_packagelist", "sitead", "Manage Ad Packages", "", '{"route":"admin_default","module":"sitead","controller":"packagelist"}', "sitead_admin_main", "", 0, 2),
	( "sitead_admin_admodule_manage", "sitead", "Manage Modules", "", '{"route":"admin_default","module":"sitead","controller":"widgets","action":"admodule-manage"}', "sitead_admin_main", "", 0, 3),
	( "sitead_admin_level_settings", "sitead", "Member Level Settings", "", '{"route":"admin_default","module":"sitead","controller":"level"}', "sitead_admin_main", "", 0, 4),
	( "sitead_admin_target_settings", "sitead", "Targeting Settings", "", '{"route":"admin_default","module":"sitead","controller":"settings","action":"target"}', "sitead_admin_main", "", 0, 5),
	( "sitead_admin_graph", "sitead", "Graphs Settings", "", '{"route":"admin_default","module":"sitead","controller":"settings","action":"graph"}', "sitead_admin_main", "", 0, 6),
	( "sitead_admin_view_advertisment", "sitead", "Manage Advertisements", "", '{"route":"admin_default","module":"sitead","controller":"viewad","action":"index"}', "sitead_admin_main", "", 0, 7),
	( "sitead_admin_ctacategory", "sitead", "CTA Button", "", '{"route":"admin_default","module":"sitead","controller":"settings","action":"cta-categories"}', "sitead_admin_main", "", 0, 8),
	( "sitead_admin_main_statistics", "sitead", "Ad Reports", "", '{"route":"admin_default","module":"sitead","controller":"statistics","action":"export-report"}', "sitead_admin_main", "", 0, 9),
	( "sitead_admin_payment_history", "sitead", "Transactions", "", '{"route":"admin_default","module":"sitead","controller":"payment","action":"index"}', "sitead_admin_main", "", 0, 10),
	( "sitead_admin_user_manage", "sitead", "Manage Help & Learn More", "", '{"route":"admin_default","module":"sitead","controller":"helps","action":"help-and-learnmore"}', "sitead_admin_main", "", 0, 11),
	( "sitead_admin_adreports", "sitead", "Abuse Reports", "", '{"route":"admin_default","module":"sitead","controller":"widgets","action":"adreports"}', "sitead_admin_main", "", 0, 12),
	( "sitead_admin_faq", "sitead", "FAQ", "", '{"route":"admin_default","module":"sitead","controller":"settings","action":"faq"}', "sitead_admin_main", "", 0, 13);

	INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES ("Ad Statistics Maintenance", "sitead", "Sitead_Plugin_Task_StatsMaintenance", "86400", "1", "0", "0", "0", "0", "0", "0", "0", "0", "0");

	INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES ("sitead_main", "standard", "Advertising Main Navigation Menu", "999");

	INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
	("sitead_userad_active", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_cancelled", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_expired", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_overdue", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_pending", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_refunded", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_approved", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_disapproved", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_notify_admindisapproved", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[userad_owner],[object_link]"),
	("site_team_contact", "sitead", "[host],[email],[sitead_name],[sitead_email],[sitead_messages]");

	INSERT IGNORE INTO `engine4_sitead_modules` (`module_name`, `module_title`, `table_name`, `title_field`, `body_field`, `owner_field`, `is_delete`) VALUES
	("classified", "Classified", "classified", "title", "body", "owner_id", 1),
	("blog", "Blog", "blog", "title", "body", "owner_id", 1),
	("album", "Album", "album", "title", "description", "owner_id", 1),
	("event", "Event", "event", "title", "description", "user_id", 1),
	("forum", "Forum Topic", "forum_topic", "title", "description", "user_id", 1),
	("group", "Group", "group", "title", "description", "user_id", 1),
	("music", "Music", "music_playlist", "title", "description", "owner_id", 1),
	("poll", "Poll", "poll", "title", "description", "user_id", 1),
	("video", "Video", "video", "title", "description", "owner_id", 1),
	("list", "Listing", "list_listing", "title", "body", "owner_id", 1),
	("sitepage", "Page", "sitepage_page", "title", "body", "owner_id", 1),
    ("sitebusiness", "Business", "sitebusiness_business", "title", "body", "owner_id", 1),
    ("sitegroup", "Groups", "sitegroup_group", "title", "body", "owner_id", 1),
	("document", "Document", "document", "document_title", "document_description", "owner_id", 1),
	("recipe", "Recipe", "recipe", "title", "body", "owner_id", 1);


	DROP TABLE IF EXISTS `engine4_sitead_faqs`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_faqs` (
		`faq_id` int(11) NOT NULL AUTO_INCREMENT,
		`type` varchar(50) NOT NULL,
		`question` text NOT NULL,
		`answer` text NOT NULL,
		`poster_id` int(11) NOT NULL,
		`status` int(11) NOT NULL,
	`faq_default` int(11) NOT NULL DEFAULT "0",
		PRIMARY KEY (`faq_id`),
    KEY `type` (`type`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;


	INSERT IGNORE INTO `engine4_sitead_faqs` (`type`, `question`, `answer`, `poster_id`, `status`, `faq_default`) VALUES
	("1", "_sitead_help_generalfaq_3", "_sitead_help_generalfaq_4", 1, 1,  3),
	("2", "_sitead_help_designfaq_3", "_sitead_help_designfaq_4", 1, 1,  3),
	("2", "_sitead_help_designfaq_5", "_sitead_help_designfaq_6", 1, 1,  5),
	("2", "_sitead_help_designfaq_7", "_sitead_help_designfaq_8", 1, 1,  7),
	("2", "_sitead_help_designfaq_9", "_sitead_help_designfaq_10", 1, 1,  9),
	("3", "_sitead_help_targetingfaq_1", "_sitead_help_targetingfaq_2", 1, 1,  1),
	("3", "_sitead_help_targetingfaq_3", "_sitead_help_targetingfaq_4", 1, 1,  3),
	("3", "_sitead_help_targetingfaq_5", "_sitead_help_targetingfaq_6", 1, 1,  5),
	("2", "_sitead_help_designfaq_1", "_sitead_help_designfaq_2", 1, 1,  1),
	("1", "_sitead_help_generalfaq_1", "_sitead_help_generalfaq_2", 1, 1,  1),
	("1", "_sitead_help_generalfaq_5", "_sitead_help_generalfaq_6", 1, 1,  5),
	("1", "_sitead_help_generalfaq_7", "_sitead_help_generalfaq_8", 1, 1,  7),
	("1", "_sitead_help_generalfaq_9", "_sitead_help_generalfaq_10", 1, 1,  9),
	("1", "_sitead_help_generalfaq_11", "_sitead_help_generalfaq_12", 1, 1,  11),
	("1", "_sitead_help_generalfaq_13", "_sitead_help_generalfaq_14", 1, 1,  13);

	DROP TABLE IF EXISTS `engine4_sitead_infopages`;

	CREATE TABLE IF NOT EXISTS `engine4_sitead_infopages` (
		`infopage_id` int(11) NOT NULL auto_increment,
		`title` varchar(255) NOT NULL,
		`description` text NOT NULL,
		`status` int(11) NOT NULL,
		`delete` int(11) NOT NULL,
		`faq` int(11) NOT NULL,
		`contect_team` int(11) NOT NULL,
		`package` int(11) NOT NULL,
	`page_default` int(11) NOT NULL DEFAULT "0",
		PRIMARY KEY  (`infopage_id`)
	) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;

	INSERT IGNORE INTO `engine4_sitead_infopages` (`title`, `description`, `status`, `delete`, `faq`, `contect_team`, `package`, `page_default`) VALUES
	("Overview", "", 1, 0, 0, 0, 0, 1),
	("Get Started", "", 1, 0, 0, 0, 0, 2),
	("Improve Your Ads", "", 1, 0, 0, 0, 0, 3),
	("Contact Sales Team", "Contact Sales Team", 1, 0, 0, 1, 0, 0),
	("General FAQ", "", 1, 0, 1, 0, 0, 0),
	("Design Your Ad FAQ", "", 1, 0, 2, 0, 0, 0),
	("Targeting FAQ", "", 1, 0, 3, 0, 0, 0),
	("package", "<p>package</p>", 1, 0, 0, 0, 1, 0);

	INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES

	("sitead_userad_approval_pending", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	("sitead_userad_declined", "sitead", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[object_link]"),
	('sitead_notify_admindisapproved', 'communityad', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[userad_title],[userad_description],[userad_owner],[object_link]');

	INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
	("Advertisement Maintenance", "sitead", "Sitead_Plugin_Task_Cleanup", 43200);

	DROP TABLE IF EXISTS `engine4_sitead_package`;
	CREATE TABLE IF NOT EXISTS `engine4_sitead_package` (
	`package_id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`desc` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
	`level_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT "0",
	`add_categories` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci ,
	`price`  DECIMAL( 16, 2 ) NULL DEFAULT "0",
	`carousel` tinyint(1) NOT NULL,
	`image` tinyint(1) NOT NULL,
	`video` tinyint(1) NOT NULL,
	`sponsored` tinyint(1) NOT NULL,
	`featured` tinyint(1) NOT NULL,
	`urloption` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
	`enabled` tinyint(1) NOT NULL,
	`network` tinyint(1) NOT NULL,
	`public` tinyint(1) NOT NULL,
	`price_model` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`model_detail` int(11) DEFAULT NULL,
	`allow_ad` int(11) DEFAULT NULL,
	`creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`renew` tinyint(1) NOT NULL,
	`renew_before` int(11) NOT NULL DEFAULT "0",
	`auto_aprove` tinyint(1) NOT NULL,
	`order` int(10) NOT NULL DEFAULT "0",
	`type` VARCHAR( 255 ) NOT NULL DEFAULT "default",
	PRIMARY KEY (`package_id`),
  KEY `type` (`type`)
	)  ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1;

	DROP TABLE IF EXISTS `engine4_sitead_adstatisticscache`;
CREATE TABLE IF NOT EXISTS `engine4_sitead_adstatisticscache` (
  `adstatisticcache_id` int(11) NOT NULL AUTO_INCREMENT,
  `userad_id` int(11) NOT NULL,
  `adcampaign_id` int(11) NOT NULL,
  `viewer_id` int(11) NOT NULL,
  `hostname` varchar(60) DEFAULT NULL,
  `user_agent` varchar(500) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  `response_date` datetime NOT NULL,
  `value_click` int(11) DEFAULT NULL,
  `value_view` int(11) DEFAULT NULL,
  `value_like` varchar(35) DEFAULT NULL,
  `adstatistic_id` int(11) NOT NULL,
  PRIMARY KEY (`adstatisticcache_id`),
  KEY `viewer_id` (`viewer_id`),
  KEY `userad_id` (`userad_id`)
) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1 ;
ALTER TABLE `engine4_sitead_package` CHANGE `price` `price` DECIMAL( 16, 2 ) NULL DEFAULT '0';

INSERT IGNORE INTO `engine4_sitead_modules` (`module_name`, `module_title`, `table_name`, `title_field`, `body_field`, `owner_field`, `is_delete`) VALUES
('sitestore', 'Store', 'sitestore_store', 'title', 'body', 'owner_id', 1),
('sitestoreproduct', 'Product', 'sitestoreproduct_product', 'title', 'body', 'owner_id', 1),
('sitecrowdfunding', 'Project', 'sitecrowdfunding_project', 'title', 'description', 'owner_id', 1),
('sitevideo', 'Video', 'video', 'title', 'description', 'owner_id', 1),
('siteforum', 'Forum Topic', 'forum_topic', 'title', 'description', 'user_id', 1),
('siteevent', 'Event', 'siteevent_event', 'title', 'body', 'owner_id', 1);


