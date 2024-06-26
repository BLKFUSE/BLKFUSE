/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_adcampaigns`
--

DROP TABLE IF EXISTS `engine4_core_adcampaigns`;
CREATE TABLE IF NOT EXISTS `engine4_core_adcampaigns` (
  `adcampaign_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `end_settings` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `limit_view` int(11) unsigned NOT NULL default '0',
  `limit_click` int(11) unsigned NOT NULL default '0',
  `limit_ctr` varchar(11) NOT NULL default '0',
  `network` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `target_member` tinyint(4) NOT NULL default '1',
  `views` int(11) unsigned NOT NULL default '0',
  `clicks` int(11) unsigned NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`adcampaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_adphotos`
--

DROP TABLE IF EXISTS `engine4_core_adphotos`;
CREATE TABLE IF NOT EXISTS `engine4_core_adphotos` (
  `adphoto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`adphoto_id`),
  KEY `ad_id` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_ads`
--

DROP TABLE IF EXISTS `engine4_core_ads`;
CREATE TABLE IF NOT EXISTS `engine4_core_ads` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) NOT NULL,
  `ad_campaign` int(11) unsigned NOT NULL,
  `views` int(11) unsigned NOT NULL default '0',
  `clicks` int(11) unsigned NOT NULL default '0',
  `media_type` varchar(255) NOT NULL,
  `html_code` text NOT NULL,
  `photo_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`ad_id`),
  KEY ad_campaign (`ad_campaign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_auth`
--

DROP TABLE IF EXISTS `engine4_core_auth`;
CREATE TABLE IF NOT EXISTS `engine4_core_auth` (
  `id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `expires` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`, `user_id`),
  KEY (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_bannedemails`
--

DROP TABLE IF EXISTS `engine4_core_bannedemails`;
CREATE TABLE IF NOT EXISTS `engine4_core_bannedemails` (
  `bannedemail_id` int(10) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY  (`bannedemail_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_bannedips`
--

DROP TABLE IF EXISTS `engine4_core_bannedips`;
CREATE TABLE IF NOT EXISTS `engine4_core_bannedips` (
  `bannedip_id` int(10) unsigned NOT NULL auto_increment,
  `start` varbinary(16) NOT NULL,
  `stop` varbinary(16) NOT NULL,
  PRIMARY KEY (`bannedip_id`),
  UNIQUE KEY `start` (`start`, `stop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_bannedusernames`
--

DROP TABLE IF EXISTS `engine4_core_bannedusernames`;
CREATE TABLE IF NOT EXISTS `engine4_core_bannedusernames` (
  `bannedusername_id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY  (`bannedusername_id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_bannedwords`
--

DROP TABLE IF EXISTS `engine4_core_bannedwords`;
CREATE TABLE IF NOT EXISTS `engine4_core_bannedwords` (
  `bannedword_id` int(10) unsigned NOT NULL auto_increment,
  `word` text NOT NULL,
  PRIMARY KEY  (`bannedword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_comments`
--

DROP TABLE IF EXISTS `engine4_core_comments`;
CREATE TABLE IF NOT EXISTS `engine4_core_comments` (
  `comment_id` int(11) unsigned NOT NULL auto_increment,
  `resource_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `creation_date` datetime NOT NULL,
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `like_count` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`comment_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `poster_type` (`poster_type`, `poster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_content`
--

DROP TABLE IF EXISTS `engine4_core_content`;
CREATE TABLE IF NOT EXISTS `engine4_core_content` (
  `content_id` int(11) unsigned NOT NULL auto_increment,
  `page_id` int(11) unsigned NOT NULL,
  /* Rendering */
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL default 'widget',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  /* Placement */
  `parent_content_id` int(11) unsigned NULL,
  `order` int(11) NOT NULL default '1',
  /* Misc */
  `params` text NULL,
  `attribs` text NULL,
  PRIMARY KEY  (`content_id`),
  KEY (`page_id`, `order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_content`
--

INSERT INTO `engine4_core_content` (`content_id`, `page_id`, `type`, `name`, `parent_content_id`, `order`, `params`) VALUES
/* Header */
(100, 1, 'container', 'main', NULL, 1, ''),
(110, 1, 'widget', 'core.menu-mini', 100, 1, ''),
(111, 1, 'widget', 'core.search-mini', 100, 2, ''),
(112, 1, 'widget', 'core.menu-logo', 100, 3, ''),
(113, 1, 'widget', 'core.menu-main', 100, 4, ''),

/* Footer */
(200, 2, 'container', 'main', NULL, 1, ''),

(210, 2, 'widget', 'core.menu-footer', 200, 2, ''),
(211, 2, 'widget', 'core.menu-social-sites', 200, 3, ''),

/* Home */
(300, 3, 'container', 'main', NULL, 1, ''),
(312, 3, 'container', 'middle', 300, 2, ''),
(320, 3, 'widget', 'core.landing-page-banner', 312, 1, '{"height":"550","title":"","nomobile":"0","name":"core.landing-page-banner"}'),
(321, 3, 'widget', 'core.landing-page-features', 312, 2, '{"dummy1":null,"fe1img":"0","fe1heading":"Easy Login \/ Signup","fe1description":"You can easily sign up on our community or simply login, if you already have an account to get started !","dummy2":null,"fe2img":"0","fe2heading":"Post Content","fe2description":"Quickly start by posting your status updates, photos, videos, groups, blogs, classifieds, etc inside.","dummy3":null,"fe3img":"0","fe3heading":"Responsive","fe3description":"Our community is 100% responsive, so you can use it anywhere, & anytime from any device.","dummy4":null,"fe4img":"0","fe4heading":"Flexible","fe4description":"Our community is available 24x7, so you can use it as per your flexibility and requirement.","title":"Why Choose Us?","nomobile":"0","name":"core.landing-page-features"}'),
(322, 3, 'widget', 'core.parallax', 312, 3, '{"bgphoto":"","heading":"Engage with people of your interests","height":"300","title":"","nomobile":"0","name":"core.parallax"}'),
(323, 3, 'widget', 'elpis.landing-page-blogs', 312, 4, '{"title":"Explore Popular Blogs","popularType":"view","itemCountPerPage":"2","nomobile":"0","name":"elpis.landing-page-blogs"}'),
(324, 3, 'widget', 'elpis.landing-page-members', 312, 5, '{"title":"Popular Members","name":"elpis.landing-page-members","itemCountPerPage":"12"}'),

/* User Home */
(400, 4, 'container', 'main', NULL, 1, ''),

(410, 4, 'container', 'left', 400, 1, ''),
(411, 4, 'container', 'right', 400, 2, ''),
(412, 4, 'container', 'middle', 400, 3, ''),

(420, 4, 'widget', 'user.home-photo', 410, 1, ''),
(421, 4, 'widget', 'user.home-links', 410, 2, ''),
(422, 4, 'widget', 'user.list-online', 410, 3, '{"title":"%s Members Online"}'),
(423, 4, 'widget', 'core.statistics', 410, 4, '{"title":"Network Stats"}'),

(430, 4, 'widget', 'activity.list-requests', 411, 1, '{"title":"Requests"}'),
(431, 4, 'widget', 'user.list-signups', 411, 2, '{"title":"Newest Members"}'),
(432, 4, 'widget', 'user.list-popular', 411, 3, '{"title":"Popular Members"}'),

(440, 4, 'widget', 'announcement.list-announcements', 412, 1, ''),
(441, 4, 'widget', 'activity.feed', 412, 2, '{"title":"What''s New"}'),

/* User Profile */
(500, 5, 'container', 'main', NULL, 1, ''),

(510, 5, 'container', 'left', 500, 1, ''),
(511, 5, 'container', 'middle', 500, 3, ''),

-- (520, 5, 'widget', 'user.profile-photo', 510, 1, ''),
(521, 5, 'widget', 'user.profile-options', 510, 2, ''),
(522, 5, 'widget', 'user.profile-friends-common', 510, 3, '{"title":"Mutual Friends"}'),
(523, 5, 'widget', 'user.profile-info', 510, 4, '{"title":"Member Info"}'),

(530, 5, 'widget', 'user.profile-status', 511, 1, ''),
(531, 5, 'widget', 'core.container-tabs', 511, 2, '{"max":"6"}'),

(540, 5, 'widget', 'activity.feed', 531, 1, '{"title":"Updates"}'),
(541, 5, 'widget', 'user.profile-fields', 531, 2, '{"title":"Info"}'),
(542, 5, 'widget', 'user.profile-friends', 531, 3, '{"title":"Friends","titleCount":true}'),
(546, 5, 'widget', 'core.profile-links', 531, 7, '{"title":"Links","titleCount":true}');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_geotags`
--

DROP TABLE IF EXISTS `engine4_core_geotags`;
CREATE TABLE IF NOT EXISTS `engine4_core_geotags` (
  `geotag_id` int(11) unsigned NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  PRIMARY KEY  (`geotag_id`),
  KEY `latitude` (`latitude`,`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_jobs`
--

DROP TABLE IF EXISTS `engine4_core_jobs`;
CREATE TABLE IF NOT EXISTS `engine4_core_jobs` (
  `job_id` bigint(20) unsigned NOT NULL auto_increment,
  `jobtype_id` int(10) unsigned NOT NULL,
  `state` enum('pending','active','sleeping','failed','cancelled','completed','timeout') NOT NULL default 'pending',
  `is_complete` tinyint(1) unsigned NOT NULL default '0',
  `progress` decimal(5,4) unsigned NOT NULL default '0.0000',
  `creation_date` datetime NOT NULL,
  `modified_date` datetime default NULL,
  `started_date` datetime default NULL,
  `completion_date` datetime default NULL,
  `priority` mediumint(9) NOT NULL default '100',
  `data` text NULL,
  `messages` text NULL,
  PRIMARY KEY  (`job_id`),
  KEY `jobtype_id` (`jobtype_id`),
  KEY `state` (`state`),
  KEY `is_complete` (`is_complete`, `priority`, `job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_jobtypes`
--

DROP TABLE IF EXISTS `engine4_core_jobtypes`;
CREATE TABLE IF NOT EXISTS `engine4_core_jobtypes` (
  `jobtype_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plugin` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `form` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `enabled` tinyint(1) unsigned NOT NULL default '1',
  `priority` mediumint(9) NOT NULL default '100',
  `multi` tinyint(3) unsigned NULL default '1',
  PRIMARY KEY  (`jobtype_id`),
  UNIQUE KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `engine4_core_jobtypes`
--

INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `form`) VALUES
('Download File', 'file_download', 'core', 'Core_Plugin_Job_FileDownload', 'Core_Form_Admin_Job_FileDownload'),
('Upload File', 'file_upload', 'core', 'Core_Plugin_Job_FileUpload', 'Core_Form_Admin_Job_FileUpload');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_languages`
--

DROP TABLE IF EXISTS `engine4_core_languages`;
CREATE TABLE `engine4_core_languages` (
  `language_id` int(11) unsigned NOT NULL auto_increment,
  `code` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) NOT NULL,
  `fallback` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `order` smallint(6) NOT NULL default '1',
  `enabled` TINYINT(1) NOT NULL DEFAULT "1",
  PRIMARY KEY  (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_languages`
--

INSERT IGNORE INTO `engine4_core_languages` (`language_id`, `code`, `name`, `fallback`, `order`) VALUES
(1, 'en', 'English', 'en', 1);


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_likes`
--

DROP TABLE IF EXISTS `engine4_core_likes`;
CREATE TABLE IF NOT EXISTS `engine4_core_likes` (
  `like_id` int(11) unsigned NOT NULL auto_increment,
  `resource_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  `creation_date` DATETIME NOT NULL,
  PRIMARY KEY  (`like_id`),
  KEY `resource_type` (`resource_type`, `resource_id`),
  KEY `poster_type` (`poster_type`, `poster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_links`
--

DROP TABLE IF EXISTS `engine4_core_links`;
CREATE TABLE IF NOT EXISTS `engine4_core_links` (
  `link_id` int(11) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo_id` int(11) unsigned NOT NULL default '0',
  `parent_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `owner_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `view_count` mediumint(6) unsigned NOT NULL default '0',
  `creation_date` datetime NOT NULL,
  `search` tinyint(1) NOT NULL default '1',
  `params` text NULL default NULL,
  PRIMARY KEY  (`link_id`),
  KEY `owner` (`owner_type`, `owner_id`),
  KEY `parent` (`parent_type`, `parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_listitems`
--

DROP TABLE IF EXISTS `engine4_core_listitems`;
CREATE TABLE IF NOT EXISTS `engine4_core_listitems` (
  `listitem_id` int(11) unsigned NOT NULL auto_increment,
  `list_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`listitem_id`),
  KEY `list_id` (`list_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_lists`
--

DROP TABLE IF EXISTS `engine4_core_lists`;
CREATE TABLE IF NOT EXISTS `engine4_core_lists` (
  `list_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(64) NOT NULL default '',
  `owner_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `child_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `child_count` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`list_id`),
  KEY `owner_type` (`owner_type`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_log`
--

DROP TABLE IF EXISTS `engine4_core_log`;
CREATE TABLE IF NOT EXISTS `engine4_core_log` (
  `message_id` bigint(20) unsigned NOT NULL auto_increment,
  `domain` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned default NULL,
  `plugin` varchar(128) NULL,
  `timestamp` datetime NOT NULL,
  `message` longtext NOT NULL,
  `priority` smallint(2) NOT NULL default '6',
  `priorityName` varchar(16) NOT NULL default 'INFO',
  PRIMARY KEY  (`message_id`),
  KEY `domain` (`domain`, `timestamp`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mail`
--

DROP TABLE IF EXISTS `engine4_core_mail`;
CREATE TABLE IF NOT EXISTS `engine4_core_mail` (
  `mail_id` int(11) unsigned NOT NULL auto_increment,
  `type` enum('system', 'zend') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext NOT NULL,
  `priority` smallint(3) default '100',
  `recipient_count` int(11) unsigned default '0',
  `recipient_total` int(10) NOT NULL default '0',
  `creation_time` DATETIME NOT NULL,
  PRIMARY KEY  (`mail_id`),
  KEY (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mailrecipients`
--

DROP TABLE IF EXISTS `engine4_core_mailrecipients`;
CREATE TABLE IF NOT EXISTS `engine4_core_mailrecipients` (
  `recipient_id` int(11) unsigned NOT NULL auto_increment,
  `mail_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NULL,
  `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY  (`recipient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mailtemplates`
--

DROP TABLE IF EXISTS `engine4_core_mailtemplates`;
CREATE TABLE IF NOT EXISTS `engine4_core_mailtemplates` (
  `mailtemplate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(64) NOT NULL default '',
  `vars` varchar(255) NOT NULL,
  `default` TINYINT(1) NOT NULL DEFAULT "1",
  PRIMARY KEY (`mailtemplate_id`),
  UNIQUE KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_mailtemplates`
--

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
('header', 'core', ''),
('footer', 'core', ''),
('header_member', 'core', ''),
('footer_member', 'core', ''),
('core_contact', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_name],[sender_email],[sender_link],[sender_photo],[message]'),
('core_verification', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]'),
('core_verification_password', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[password]'),
('core_welcome', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]'),
('core_welcome_password', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[password]'),
('notify_admin_user_signup', 'core', '[host],[email],[date],[recipient_title],[object_title],[object_link]'),
('core_lostpassword', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menus`
--

DROP TABLE IF EXISTS `engine4_core_menus`;
CREATE TABLE IF NOT EXISTS `engine4_core_menus` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('standard','hidden','custom') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL default 'standard',
  `title` varchar(64) NOT NULL,
  `order` smallint(3) NOT NULL default '999',
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`name`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_menus`
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`, `order`) VALUES
('core_main', 'standard', 'Main Navigation Menu', 1),
('core_mini', 'standard', 'Mini Navigation Menu', 2),
('core_footer', 'standard', 'Footer Menu', 3),
('core_sitemap', 'standard', 'Sitemap', 4),
('core_social_sites', 'standard', 'Social Site Links Menu', 5)
;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menuitems`
--

DROP TABLE IF EXISTS `engine4_core_menuitems`;
CREATE TABLE IF NOT EXISTS `engine4_core_menuitems` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(64) NOT NULL,
  `plugin` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `params` text NOT NULL,
  `menu` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `submenu` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  `custom` tinyint(1) NOT NULL default '0',
  `order` smallint(6) NOT NULL default '999',
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`name`),
  KEY `LOOKUP` (`name`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_main_home', 'core', 'Home', 'User_Plugin_Menus', '{"icon":"fa fa-home"}', 'core_main', '', 1),

('core_sitemap_home', 'core', 'Home', '', '{"route":"default"}', 'core_sitemap', '', 1),

('core_footer_privacy', 'core', 'Privacy', '', '{"route":"default","module":"core","controller":"help","action":"privacy"}', 'core_footer', '', 1),
('core_footer_terms', 'core', 'Terms of Service', '', '{"route":"default","module":"core","controller":"help","action":"terms"}', 'core_footer', '', 2),
('core_footer_contact', 'core', 'Contact', '', '{"route":"default","module":"core","controller":"help","action":"contact"}', 'core_footer', '', 3),

('core_mini_update', 'activity', 'Updates', 'Activity_Plugin_Menus', '{"icon":"far fa-bell"}', 'core_mini', '', 1),
-- ('core_mini_admin', 'core', 'Admin', 'User_Plugin_Menus', '{"icon":"fas fa-tools"}', 'core_mini', '', 2),
('core_mini_profile', 'user', 'My Profile', 'User_Plugin_Menus', '', 'core_mini', '', 3),
-- ('core_mini_settings', 'user', 'Settings', 'User_Plugin_Menus', '{"icon":"fas fa-cog"}', 'core_mini', '', 5),
('core_mini_auth', 'user', 'Auth', 'User_Plugin_Menus', '{"icon":"fa-sign-in-alt"}', 'core_mini', '', 6),
('core_mini_signup', 'user', 'Signup', 'User_Plugin_Menus', '{"icon":"fa-user-plus"}', 'core_mini', '', 7),

('core_admin_main_home', 'core', 'Home', '', '{"route":"admin_default"}', 'core_admin_main', '', 1),
('core_admin_main_manage', 'core', 'Manage', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_manage', 2),
('core_admin_main_settings', 'core', 'Settings', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_settings', 3),
('core_admin_main_plugins', 'core', 'Plugins', '', '{"route":"admin_default","module":"core","controller":"manage-packages","action":"enabled"}', 'core_admin_main', 'core_admin_main_plugins', 4),
('core_admin_main_layout', 'core', 'Appearance', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_layout', 5),
('core_admin_main_monetization', 'core', 'Monetization', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_monetization', 6),
('core_admin_main_ads', 'core', 'Ads', '', '{"route":"admin_default","controller":"ads"}', 'core_admin_main_monetization', '', 6),
('core_admin_main_stats', 'core', 'Stats', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_stats', 8),

('core_admin_main_manage_levels', 'core', 'Member Levels', '', '{"route":"admin_default","module":"authorization","controller":"level"}', 'core_admin_main_manage', '', 2),
('core_admin_main_manage_networks', 'network', 'Networks', '', '{"route":"admin_default","module":"network","controller":"manage"}', 'core_admin_main_manage', '', 3),
('core_admin_main_manage_announcements', 'announcement', 'Announcements', '', '{"route":"admin_default","module":"announcement","controller":"manage"}', 'core_admin_main_manage', '', 4),
('core_admin_message_mail',  'core',  'Email All Members',  '',  '{"route":"admin_default","module":"core","controller":"message","action":"mail"}',  'core_admin_main_manage',  '',  5),
('core_admin_main_manage_reports', 'core', 'Abuse Reports', '', '{"route":"admin_default","module":"core","controller":"report"}', 'core_admin_main_manage', '', 6),
('core_admin_main_manage_packages', 'core', 'Packages & Plugins', '', '{"route":"admin_default","module":"core","controller":"packages"}', 'core_admin_main_manage', '', 7),

('core_admin_main_settings_general', 'core', 'General Settings', '', '{"route":"core_admin_settings","action":"general"}', 'core_admin_main_settings', '', 1),
('core_admin_main_settings_locale', 'core', 'Locale Settings', '', '{"route":"core_admin_settings","action":"locale"}', 'core_admin_main_settings', '', 1),
('core_admin_main_settings_fields', 'fields', 'Profile Questions', '', '{"route":"admin_default","module":"user","controller":"fields"}', 'core_admin_main_settings', '', 2),
('core_admin_main_settings_spam', 'core', 'Spam & Banning Tools', '', '{"route":"core_admin_settings","action":"spam"}', 'core_admin_main_settings', '', 5),
('core_admin_main_settings_mailtemplates', 'core', 'Mail Templates', '', '{"route":"admin_default","controller":"mail","action":"templates"}', 'core_admin_main_settings', '', 6),
('core_admin_main_settings_mailsettings', 'core', 'Mail Settings', '', '{"route":"admin_default","controller":"mail","action":"settings"}', 'core_admin_main_settings', '', 7),
('core_admin_main_settings_performance', 'core', 'Performance & Caching', '', '{"route":"core_admin_settings","action":"performance"}', 'core_admin_main_settings', '', 8),
('core_admin_main_settings_password', 'core', 'Admin Password', '', '{"route":"core_admin_settings","action":"password"}', 'core_admin_main_settings', '', 9),
('core_admin_main_settings_tasks', 'core', 'Task Scheduler', '', '{"route":"admin_default","controller":"tasks"}', 'core_admin_main_settings', '', 10),
('core_admin_main_settings_iframely', 'core', 'Iframely Integration', '', '{"route":"admin_default","controller":"iframely"}', 'core_admin_main_settings', '', 11),

('core_admin_main_layout_content', 'core', 'Layout Editor', '', '{"route":"admin_default","controller":"content"}', 'core_admin_main_layout', '', 1),
('core_admin_main_layout_themes', 'core', 'Theme Editor', '', '{"route":"admin_default","controller":"themes"}', 'core_admin_main_layout', '', 2),
('core_admin_main_layout_files', 'core', 'File & Media Manager', '', '{"route":"admin_default","controller":"files"}', 'core_admin_main_layout', '', 3),
('core_admin_main_layout_language', 'core', 'Language Manager', '', '{"route":"admin_default","controller":"language"}', 'core_admin_main_layout', '', 4),
('core_admin_main_layout_menus', 'core', 'Menu Editor', '', '{"route":"admin_default","controller":"menus"}', 'core_admin_main_layout', '', 5),
('core_admin_main_layout_banners', 'core', 'Banner Manager', '', '{"route":"admin_default","controller":"banners"}', 'core_admin_main_layout', '', 6),

('core_admin_main_ads_manage', 'core', 'Manage Ad Campaigns', '', '{"route":"admin_default","controller":"ads"}', 'core_admin_main_ads', '', 1),
('core_admin_main_ads_create', 'core', 'Create New Campaign', '', '{"route":"admin_default","controller":"ads","action":"create"}', 'core_admin_main_ads', '', 2),

('core_admin_main_stats_statistics', 'core', 'Site-wide Statistics', '', '{"route":"admin_default","controller":"stats"}', 'core_admin_main_stats', '', 1),
('core_admin_main_stats_url', 'core', 'Referring URLs', '', '{"route":"admin_default","controller":"stats","action":"referrers"}', 'core_admin_main_stats', '', 2),
('core_admin_main_stats_resources', 'core', 'Server Information', '', '{"route":"admin_default","controller":"system"}', 'core_admin_main_stats', '', 3),
('core_admin_main_stats_logs', 'core', 'Log Browser', '', '{"route":"admin_default","controller":"log","action":"index"}', 'core_admin_main_stats', '', 3),

('core_admin_banning_general', 'core', 'Spam & Banning Tools', '', '{"route":"core_admin_settings","action":"spam"}', 'core_admin_banning', '', 1),

('adcampaign_admin_main_edit', 'core', 'Edit Settings', '', '{"route":"admin_default","module":"core","controller":"ads","action":"edit"}', 'adcampaign_admin_main', '', 1),
('adcampaign_admin_main_manageads', 'core', 'Manage Advertisements', '', '{"route":"admin_default","module":"core","controller":"ads","action":"manageads"}', 'adcampaign_admin_main', '', 2),
('core_admin_main_settings_redirection', 'core', 'Redirection Settings', '', '{"route":"core_admin_settings","action":"redirection"}', 'core_admin_main_settings', '', 15),
('core_admin_main_settings_editor', 'core', 'TinyMCE Editor Settings', '', '{"route":"core_admin_settings","action":"editor"}', 'core_admin_main_settings', '', 16);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `enabled`, `custom`, `order`) VALUES
('core_social_site_facebook', 'core', 'Facebook', '', '{"uri": "javascript:void(0)","target":"_blank", "icon":"fa-facebook"}', 'core_social_sites', 0, 1, 1),
('core_social_site_twitter', 'core', 'Twitter', '', '{"uri": "javascript:void(0)","target":"_blank", "icon":"fa-twitter"}', 'core_social_sites', 0, 1, 2),
('core_social_site_linkedin', 'core', 'Linkedin', '', '{"uri": "javascript:void(0)","target":"_blank", "icon":"fab fa-linkedin-in"}', 'core_social_sites', 0, 1, 3),
('core_social_site_youtube', 'core', 'Youtube', '', '{"uri": "javascript:void(0)","target":"_blank", "icon":"fa-youtube"}', 'core_social_sites', 0, 1, 4),
('core_social_site_pinterest', 'core', 'Pinterest', '', '{"uri": "javascript:void(0)","target":"_blank", "icon":"fa-brands fa-pinterest-p"}', 'core_social_sites', 0, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_migrations`
--

DROP TABLE IF EXISTS `engine4_core_migrations`;
CREATE TABLE IF NOT EXISTS `engine4_core_migrations` (
  `package` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY (`package`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_modules`
--

DROP TABLE IF EXISTS `engine4_core_modules`;
CREATE TABLE IF NOT EXISTS `engine4_core_modules` (
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) NOT NULL,
  `description` text NULL,
  `version` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  `type` enum('core','standard','extra') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL default 'extra',
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `engine4_core_modules`
--

INSERT INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('core', 'Core', 'The Alpha and the Omega.', '4.10.0beta1', 1, 'core');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_nodes`
--

DROP TABLE IF EXISTS `engine4_core_nodes`;
CREATE TABLE IF NOT EXISTS `engine4_core_nodes` (
  `node_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `signature` char(40) NOT NULL,
  `host` varchar(255) NOT NULL,
  `ip` varbinary(16) NOT NULL,
  `first_seen` datetime NOT NULL,
  `last_seen` datetime NOT NULL,
  PRIMARY KEY (`node_id`),
  UNIQUE KEY `signature` (`signature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_pages`
--

DROP TABLE IF EXISTS `engine4_core_pages`;
CREATE TABLE IF NOT EXISTS `engine4_core_pages` (
  `page_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `displayname` varchar(128) NOT NULL default '',
  `url` varchar(128) NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `custom` tinyint(1) NOT NULL default '1',
  `fragment` tinyint(1) NOT NULL default '0',
  `layout` varchar(32) NOT NULL default '',
  `levels` text default NULL,
  `provides` text default NULL,
  `view_count` int(11) unsigned NOT NULL default '0',
  `search` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`page_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_pages`
--

INSERT INTO `engine4_core_pages` (`page_id`, `name`, `displayname`, `title`, `description`, `keywords`, `custom`, `fragment`, `provides`) VALUES
(1, 'header', 'Site Header', '', '', '', 0, 1, 'header-footer'),
(2, 'footer', 'Site Footer', '', '', '', 0, 1, 'header-footer'),
(3, 'core_index_index', 'Landing Page', 'Landing Page', 'This is your site''s landing page.', '', 0, 0, 'no-viewer;no-subject'),
(4, 'user_index_home', 'Member Home Page', 'Member Home Page', 'This is the home page for members.', '', 0, 0, 'viewer;no-subject'),
(5, 'user_profile_index', 'Member Profile', 'Member Profile', 'This is a member''s profile.', '', 0, 0, 'subject=user');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_processes`
--

DROP TABLE IF EXISTS `engine4_core_processes`;
CREATE TABLE IF NOT EXISTS `engine4_core_processes` (
  `pid` int(10) unsigned NOT NULL,
  `parent_pid` int(10) unsigned NOT NULL default '0',
  `system_pid` int(10) unsigned NOT NULL default '0',
  `started` int(10) unsigned NOT NULL,
  `timeout` mediumint(10) unsigned NOT NULL default '0',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY  (`pid`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_referrers`
--

DROP TABLE IF EXISTS `engine4_core_referrers`;
CREATE TABLE IF NOT EXISTS `engine4_core_referrers` (
  `host` varchar(64) NOT NULL,
  `path` varchar(64) NOT NULL,
  `query` varchar(128) NOT NULL,
  `value` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`host`,`path`,`query`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_reports`
--

DROP TABLE IF EXISTS `engine4_core_reports`;
CREATE TABLE IF NOT EXISTS `engine4_core_reports` (
  `report_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `category` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `read` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`report_id`),
  KEY `category` (`category`),
  KEY `user_id` (`user_id`),
  KEY `read` (`read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_routes`
--

DROP TABLE IF EXISTS `engine4_core_routes`;
CREATE TABLE `engine4_core_routes` (
  `name` varchar(32) NOT NULL,
  `config` text NOT NULL,
  `order` smallint(6) NOT NULL default '1',
  PRIMARY KEY  (`name`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_search`
--

DROP TABLE IF EXISTS `engine4_core_search`;
CREATE TABLE IF NOT EXISTS `engine4_core_search` (
  `search_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text  NULL DEFAULT NULL,
  `keywords` varchar(255) NOT NULL,
  `hidden` varchar(255) NULL DEFAULT NULL,
  `approved` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`search_id`),
  UNIQUE KEY `type` (`type`,`id`),
  FULLTEXT KEY `LOOKUP` (`title`, `description`, `keywords`, `hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_serviceproviders`
--

DROP TABLE IF EXISTS `engine4_core_serviceproviders`;
CREATE TABLE IF NOT EXISTS `engine4_core_serviceproviders` (
  `serviceprovider_id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`serviceprovider_id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_serviceproviders`
--

INSERT IGNORE INTO `engine4_core_serviceproviders` (`title`, `type`, `name`, `class`, `enabled`) VALUES
('MySQL', 'database', 'mysql', 'Engine_ServiceLocator_Plugin_Database_Mysql', 1),
('PDO MySQL', 'database', 'mysql_pdo', 'Engine_ServiceLocator_Plugin_Database_MysqlPdo', 1),
('MySQLi', 'database', 'mysqli', 'Engine_ServiceLocator_Plugin_Database_Mysqli', 1),
('File', 'cache', 'file', 'Engine_ServiceLocator_Plugin_Cache_File', 1),
('Memcache', 'cache', 'memcached', 'Engine_ServiceLocator_Plugin_Cache_Memcached', 1),
('Simple', 'captcha', 'image',  'Engine_ServiceLocator_Plugin_Captcha_Image', 1),
('ReCaptcha', 'captcha', 'recaptcha',  'Engine_ServiceLocator_Plugin_Captcha_Recaptcha', 1),
('SMTP', 'mail', 'smtp', 'Engine_ServiceLocator_Plugin_Mail_Smtp', 1),
('Sendmail', 'mail', 'sendmail', 'Engine_ServiceLocator_Plugin_Mail_Sendmail', 1),
('GD', 'image', 'gd', 'Engine_ServiceLocator_Plugin_Image_Gd', 1),
('Imagick', 'image', 'imagick', 'Engine_ServiceLocator_Plugin_Image_Imagick', 1),
('Akismet', 'akismet', 'standard', 'Engine_ServiceLocator_Plugin_Akismet', 1);


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_services`
--

DROP TABLE IF EXISTS `engine4_core_services`;
CREATE TABLE IF NOT EXISTS `engine4_core_services` (
  `service_id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL default 'default',
  `config` text NOT NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`service_id`),
  UNIQUE KEY `type` (`type`, `profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_services`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_servicetypes`
--

DROP TABLE IF EXISTS `engine4_core_servicetypes`;
CREATE TABLE IF NOT EXISTS `engine4_core_servicetypes` (
  `servicetype_id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `interface` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci default NULL,
  `enabled` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`servicetype_id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_servicetypes`
--

INSERT IGNORE INTO `engine4_core_servicetypes` (`title`, `type`, `interface`, `enabled`) VALUES
('Database', 'database', 'Zend_Db_Adapter_Abstract', 1),
('Cache', 'cache', 'Zend_Cache_Backend', 1),
('Captcha', 'captcha', 'Zend_Captcha_Adapter', 1),
('Mail Transport', 'mail', 'Zend_Mail_Transport_Abstract', 1),
('Image', 'image', 'Engine_Image_Adapter_Abstract', 1),
('Akismet', 'akismet', 'Zend_Service_Akismet', 1);


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_session`
--

DROP TABLE IF EXISTS `engine4_core_session`;
CREATE TABLE `engine4_core_session` (
  `id` char(32) NOT NULL default '',
  `modified` int(11) default NULL,
  `lifetime` int(11) default NULL,
  `data` text,
  `user_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_settings`
--

DROP TABLE IF EXISTS `engine4_core_settings`;
CREATE TABLE `engine4_core_settings` (
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_settings`
--

INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
('core.admin.reauthenticate', '0'),
('core.admin.mode', 'none'),
('core.admin.password', ''),
('core.admin.timeout', '600'),
('core.doctype', 'HTML5'),
('core.facebook.enable', 'none'),
('core.facebook.key', ''),
('core.facebook.secret', ''),
('core.general.browse', '1'),
('core.general.commenthtml', ''),
('core.general.notificationupdate', 120000),
('core.general.portal', '1'),
('core.general.profile', '1'),
('core.general.quota', '0'),
('core.general.search', '1'),
('core.license.email', 'email@domain.com'),
('core.license.key', '6666-6666-6666-6666'),
('core.license.statistics', '1'),
('core.locale.locale', 'auto'),
('core.locale.timezone', 'US/Pacific'),
('core.log.adapter', 'file'),
('core.mail.enabled', '1'),
('core.mail.from', 'email@domain.com'),
('core.mail.name', 'Site Admin'),
('core.mail.queueing', '1'),
('core.mail.count', '25'),
('core.secret', 'staticSalt'),
('core.site.title', 'Social Network'),
('core.site.creation', NOW()),
('core.spam.censor', ''),
('core.spam.comment', 0),
('core.spam.contact', 0),
('core.spam.invite', 0),
('core.spam.ipbans', ''),
('core.spam.login', 0),
('core.spam.signup', 0),
('core.spam.email.antispam.login', 1),
('core.spam.email.antispam.signup', 1),
('core.tasks.count', '1'),
('core.tasks.interval', '60'),
('core.tasks.jobs', '3'),
('core.tasks.key', ''),
('core.tasks.last', ''),
('core.tasks.mode', 'curl'),
('core.tasks.pid', ''),
('core.tasks.processes', '2'),
('core.tasks.time', '120'),
('core.tasks.timeout', '900'),
('core.thumbnails.main.width', '720'),
('core.thumbnails.main.height', '720'),
('core.thumbnails.main.mode', 'resize'),
('core.thumbnails.profile.width', '200'),
('core.thumbnails.profile.height', '400'),
('core.thumbnails.profile.mode', 'resize'),
('core.thumbnails.normal.width', '140'),
('core.thumbnails.normal.height', '160'),
('core.thumbnails.normal.mode', 'resize'),
('core.thumbnails.icon.width', '48'),
('core.thumbnails.icon.height', '48'),
('core.thumbnails.icon.mode', 'crop'),
('core.translate.adapter', 'csv'),
('core.twitter.enable', 'none'),
('core.twitter.key', ''),
('core.twitter.secret', ''),
('user.support.links', 1),
('elpis.changelanding', '1'),
('core.storelisting', '1'),
('core.newsupdates', '1'),
('core.general.enableloginlogs', '1'),
('core.general.logincrondays', '5');


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_statistics`
--

DROP TABLE IF EXISTS `engine4_core_statistics`;
CREATE TABLE IF NOT EXISTS `engine4_core_statistics` (
  `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`type`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_status`
--

DROP TABLE IF EXISTS `engine4_core_status`;
CREATE TABLE IF NOT EXISTS `engine4_core_status` (
  `status_id` int(11) unsigned NOT NULL auto_increment,
  `resource_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_styles`
--

DROP TABLE IF EXISTS `engine4_core_styles`;
CREATE TABLE IF NOT EXISTS `engine4_core_styles` (
  `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `style` text NOT NULL,
  PRIMARY KEY  (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tagmaps`
--

DROP TABLE IF EXISTS `engine4_core_tagmaps`;
CREATE TABLE IF NOT EXISTS `engine4_core_tagmaps` (
  `tagmap_id` int(11) unsigned NOT NULL auto_increment,
  `resource_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `tagger_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tagger_id` int(11) unsigned NOT NULL,
  `tag_type` varchar(24) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  `creation_date` datetime default NULL,
  `extra` text NULL,
  PRIMARY KEY  (`tagmap_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `tagger_type` (`tagger_type`,`tagger_id`),
  KEY `tag_type` (`tag_type`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tags`
--

DROP TABLE IF EXISTS `engine4_core_tags`;
CREATE TABLE IF NOT EXISTS `engine4_core_tags` (
  `tag_id` int(11) unsigned NOT NULL auto_increment,
  `text` varchar(255) NOT NULL,
  `tag_count` int(11) NOT NULL DEFAULT '0',
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY  (`tag_id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tasks`
--

DROP TABLE IF EXISTS `engine4_core_tasks`;
CREATE TABLE IF NOT EXISTS `engine4_core_tasks` (
  `task_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `module` varchar(128) NOT NULL default '',
  `plugin` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeout` int(11) unsigned NOT NULL default '60',
  `processes` smallint(3) unsigned NOT NULL default '1',
  `semaphore` smallint(3) NOT NULL default '0',
  `started_last` int(11) NOT NULL default '0',
  `started_count` int(11) unsigned NOT NULL default '0',
  `completed_last` int(11) NOT NULL default '0',
  `completed_count` int(11) unsigned NOT NULL default '0',
  `failure_last` int(11) NOT NULL default '0',
  `failure_count` int(11) unsigned NOT NULL default '0',
  `success_last` int(11) NOT NULL default '0',
  `success_count` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`task_id`),
  UNIQUE KEY `plugin` (`plugin`),
  KEY `module` (`module`),
  KEY `started_last` (`started_last`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_tasks`
--

INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
('Job Queue', 'core', 'Core_Plugin_Task_Jobs', 5),
('Background Mailer', 'core', 'Core_Plugin_Task_Mail', 15),
('Cache Prefetch', 'core', 'Core_Plugin_Task_Prefetch', 300),
('Log Rotation', 'core', 'Core_Plugin_Task_LogRotation', 7200),
('Clear Login Logs', 'core', 'Core_Plugin_Task_ClarLoginLog', 432000);


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_themes`
--

DROP TABLE IF EXISTS `engine4_core_themes`;
CREATE TABLE IF NOT EXISTS `engine4_core_themes` (
  `theme_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`theme_id`),
  UNIQUE KEY `name` (`name`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_core_themes`
--

INSERT IGNORE INTO `engine4_core_themes` (`theme_id`, `name`, `title`, `description`, `active`) VALUES
(1, 'default', 'Default', '', 0),
(2, 'midnight', 'Midnight', '', 0),
(3, 'clean', 'Clean', '', 0),
(4, 'modern', 'Modern', '', 0),
(5, 'serenity', 'Serenity', '', 0),
(6, 'elpis', 'Elpis', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_banners`
--

DROP TABLE IF EXISTS `engine4_core_banners`;
CREATE TABLE IF NOT EXISTS `engine4_core_banners` (
  `banner_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) NOT NULL,
  `body` varchar(255) NOT NULL,
  `photo_id` int(11) unsigned NOT NULL default '0',
  `params` text NOT NULL,
  `custom` tinyint(1) NOT NULL default '0',
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_tasks`
--

INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
('Session Maintenance', 'core', 'Core_Plugin_Task_Cleanup', 86400);


--
-- indexing for table `engine4_core_auth`
--
ALTER TABLE `engine4_core_auth` ADD INDEX(`type`);

--
-- indexing for table `engine4_core_banners`
--
ALTER TABLE `engine4_core_banners` ADD INDEX(`module`);

--
-- indexing for table `engine4_core_content`
--
ALTER TABLE `engine4_core_content` ADD INDEX(`name`);

--
-- indexing for table `engine4_core_jobtypes`
--
ALTER TABLE `engine4_core_jobtypes` ADD INDEX(`module`);

ALTER TABLE `engine4_core_jobtypes` ADD INDEX(`enabled`);

--
-- indexing for table `engine4_core_links`
--
ALTER TABLE `engine4_core_links` ADD INDEX(`creation_date`);

ALTER TABLE `engine4_core_links` ADD INDEX(`search`);

--
-- indexing for table `engine4_core_mailrecipients`
--
ALTER TABLE `engine4_core_mailrecipients` ADD INDEX(`mail_id`);

ALTER TABLE `engine4_core_mailrecipients` ADD INDEX(`user_id`);

--
-- indexing for table `engine4_core_mailtemplates`
--
ALTER TABLE `engine4_core_mailtemplates` ADD INDEX(`module`);

--
-- indexing for table `engine4_core_menuitems`
--
ALTER TABLE `engine4_core_menuitems` ADD INDEX(`module`);
ALTER TABLE `engine4_core_menuitems` ADD INDEX(`enabled`);

--
-- indexing for table `engine4_core_modules`
--
ALTER TABLE `engine4_core_modules` ADD INDEX(`enabled`);

--
-- indexing for table `engine4_core_status`
--
ALTER TABLE `engine4_core_status` ADD INDEX(`resource_type`);

ALTER TABLE `engine4_core_status` ADD INDEX(`resource_id`);

DROP TABLE IF EXISTS `engine4_core_files`; 
CREATE TABLE `engine4_core_files` ( 
  `file_id` int(10) unsigned NOT NULL auto_increment, 
  `name` varchar(255) default NULL, 
  `storage_path` varchar(255) NOT NULL, 
  `extension` varchar(8) NOT NULL, 
  `storage_file_id` int(10) unsigned NULL, 
  `creation_date` datetime NOT NULL, 
  `modified_date` datetime NOT NULL, 
  PRIMARY KEY  (`file_id`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

ALTER TABLE `engine4_core_mailtemplates` ADD `member_level` TEXT NULL DEFAULT NULL;
ALTER TABLE `engine4_core_languages` ADD `icon` VARCHAR(255) NULL DEFAULT NULL;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_manage_tags', 'core', 'Manage Tags', '', '{"route":"admin_default","module":"core","controller":"managetags"}', 'core_admin_main_manage', '', 8),
('core_admin_main_manage_comments', 'core', 'Manage Comments', '', '{"route":"admin_default","module":"core","controller":"manage-comments"}', 'core_admin_main_manage', '', 10), ('core_admin_main_contentcomments', 'core', 'Comments on Content', '', '{"route":"admin_default","module":"core","controller":"manage-comments"}', 'core_admin_main_manage_comments', '', 1),
('core_admin_main_activitycomments', 'core', 'Comments on Activity Feeds', '', '{"route":"admin_default","module":"core","controller":"manage-comments", "action":"activity"}', 'core_admin_main_manage_comments', '', 2),

('core_admin_settings_activity', 'activity', 'Activity Feed Settings', '', '{"route":"admin_default","module":"activity","controller":"settings","action":"index"}', 'core_admin_main_settings_activity', '', 1),
('core_admin_settings_activitytypes', 'activity', 'Activity Feeds Item Type Settings', '', '{"route":"admin_default","module":"activity","controller":"settings","action":"types"}', 'core_admin_main_settings_activity', '', 2),
('core_admin_main_manage_activity', 'core', 'Manage Activity Feeds', '', '{"route":"admin_default","module":"core","controller":"manage-activity"}', 'core_admin_main_settings_activity', '', 3),('core_admin_main_manage_activitycom', 'core', 'Manage Comments on Activity Feeds', '', '{"route":"admin_default","module":"core","controller":"manage-comments", "action":"activity"}', 'core_admin_main_settings_activity', '', 4);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ('core_admin_main_manage_tags', 'core', 'Manage Tags', '', '{"route":"admin_default","module":"core","controller":"managetags"}', 'core_admin_main_manage', '', 8);

ALTER TABLE `engine4_core_mailtemplates` ADD `is_admin` TINYINT(1) NOT NULL DEFAULT '0';

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_reasons`
--

DROP TABLE IF EXISTS `engine4_core_tickets`;
CREATE TABLE IF NOT EXISTS `engine4_core_tickets` (
  `ticket_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `description` text NULL DEFAULT NULL,
  `resource_type` varchar(32) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL default 'Open',
  `creation_date` datetime NOT NULL,
  `lastreply_date` datetime NOT NULL,
  `category_id` INT(11) NOT NULL DEFAULT '0',
  `subcat_id` INT(11) NOT NULL DEFAULT '0',
  `subsubcat_id` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`ticket_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  KEY `subcat_id` (`subcat_id`),
  KEY `subsubcat_id` (`subsubcat_id`),
  KEY `status` (`status`),
  KEY `resource_type` (`resource_type`),
  KEY `resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

DROP TABLE IF EXISTS `engine4_core_ticketreplies`;
CREATE TABLE IF NOT EXISTS `engine4_core_ticketreplies` (
  `ticketreply_id` int(11) NOT NULL auto_increment,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NULL DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY  (`ticketreply_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ('core_admin_main_manage_tickets', 'core', 'Support Inbox', '', '{"route":"admin_default","module":"core","controller":"support"}', 'core_admin_main_manage', '', 12), ('core_admin_manage_tickets', 'core', 'Support Inbox', '', '{"route":"admin_default","module":"core","controller":"support"}', 'core_admin_main_manage_tickets', '', 1), ('core_admin_manage_categories', 'core', 'Categories', '', '{"route":"admin_default","module":"core","controller":"support", "action": "categories"}', 'core_admin_main_manage_tickets', '', 2);


DROP TABLE IF EXISTS `engine4_core_categories`;
CREATE TABLE `engine4_core_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `category_name` varchar(128) NOT NULL,
  `subcat_id` INT(11) NOT NULL DEFAULT '0',
  `subsubcat_id` INT(11) NOT NULL DEFAULT '0',
  `order` INT(11) NOT NULL DEFAULT '0',
  `type` varchar(128) NOT NULL DEFAULT 'tickets',
  PRIMARY KEY (`category_id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`, `category_name`),
  KEY `category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
('core_minimenuquick', 'standard', 'Mini Menu Quick Links Menu');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_minimenu_edit', 'core', 'Edit Profile', 'User_Plugin_Menus', '', 'core_minimenuquick', '', 1),
('core_minimenu_settings', 'core', 'Account Settings', '', '{"route":"user_extended","module":"user","controller":"settings","action":"general", "icon":"fas fa-cog"}', 'core_minimenuquick', '', 2);

ALTER TABLE `engine4_core_categories` ADD INDEX(`subcat_id`);
ALTER TABLE `engine4_core_categories` ADD INDEX(`subsubcat_id`);
ALTER TABLE `engine4_core_categories` ADD INDEX(`order`);
ALTER TABLE `engine4_core_categories` ADD INDEX(`type`);
ALTER TABLE `engine4_core_comments` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_core_comments` ADD INDEX(`like_count`);
ALTER TABLE `engine4_core_files` ADD INDEX(`name`);
ALTER TABLE `engine4_core_files` ADD INDEX(`storage_file_id`);
ALTER TABLE `engine4_core_files` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_core_languages` ADD INDEX(`code`);
ALTER TABLE `engine4_core_languages` ADD INDEX(`enabled`);
ALTER TABLE `engine4_core_languages` ADD INDEX(`order`);
ALTER TABLE `engine4_core_likes` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_core_search` ADD INDEX(`approved`);
ALTER TABLE `engine4_core_ticketreplies` ADD INDEX(`ticket_id`);
ALTER TABLE `engine4_core_ticketreplies` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_core_tickets` ADD INDEX(`creation_date`);
ALTER TABLE `engine4_core_tickets` ADD INDEX(`lastreply_date`);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES 
('core_minimenu_supportinbox', 'core', 'Support Inbox', '', '{"route":"user_support","module":"user","controller":"support","action":"index", "icon":"fas fa-headset"}', 'core_minimenuquick', '', 3);

ALTER TABLE `engine4_core_search` ADD `username` VARCHAR(255) NULL DEFAULT NULL;
