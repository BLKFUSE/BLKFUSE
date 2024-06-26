
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     Sami
 */


-- --------------------------------------------------------

--
-- Table structure for table `engine4_album_albums`
--

DROP TABLE IF EXISTS `engine4_album_albums`;
CREATE TABLE `engine4_album_albums` (
  `album_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(128) NOT NULL,
  `description` mediumtext NOT NULL,
  `owner_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL default '0',
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `photo_id` int(11) unsigned NOT NULL default '0',
  `view_count` int(11) unsigned NOT NULL default '0',
  `comment_count` int(11) unsigned NOT NULL default '0',
  `like_count` int(11) unsigned NOT NULL default '0',
  `search` tinyint(1) NOT NULL default '1',
  `type` VARCHAR(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `view_privacy` VARCHAR(24) NOT NULL default 'everyone',
  `networks` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`album_id`),
  KEY `owner_type` (`owner_type`, `owner_id`),
  KEY `category_id` (`category_id`),
  KEY `search` (`search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_album_categories`
--

DROP TABLE IF EXISTS `engine4_album_categories`;
CREATE TABLE `engine4_album_categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `category_name` varchar(128) NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

--
-- Dumping data for table `engine4_album_categories`
--

INSERT IGNORE INTO `engine4_album_categories` (`category_id`, `user_id`, `category_name`) VALUES
(1, 1, 'Arts & Culture'),
(2, 1, 'Business'),
(3, 1, 'Entertainment'),
(5, 1, 'Family & Home'),
(6, 1, 'Health'),
(7, 1, 'Recreation'),
(8, 1, 'Personal'),
(9, 1, 'Shopping'),
(10, 1, 'Society'),
(11, 1, 'Sports'),
(12, 1, 'Technology'),
(13, 1, 'Other');

INSERT INTO engine4_album_categories VALUES (2147483647, 1, 'All Categories');
UPDATE `engine4_album_categories` SET `category_id` = '0', `user_id` = '1', `category_name` = 'All Categories' WHERE `category_id` = '2147483647' LIMIT 1;

ALTER TABLE engine4_album_categories AUTO_INCREMENT = 14;

-- --------------------------------------------------------

--
-- Table structure for table `engine4_album_photos`
--

DROP TABLE IF EXISTS `engine4_album_photos`;
CREATE TABLE `engine4_album_photos` (
  `photo_id` int(11) unsigned NOT NULL auto_increment,
  `album_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` mediumtext NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `order` int(11) unsigned NOT NULL default '0',
  `owner_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL default '0',
  `comment_count` int(11) unsigned NOT NULL default '0',
  `like_count` int(11) unsigned NOT NULL default '0',
  `parent_id` INT(11) NOT NULL DEFAULT '0',
  `parent_type` VARCHAR(64) NULL DEFAULT NULL,
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `owner_type` (`owner_type`, `owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_jobtypes`
--

INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `priority`) VALUES
('Rebuild Album Privacy', 'album_maintenance_rebuild_privacy', 'album', 'Album_Plugin_Job_Maintenance_RebuildPrivacy', 50);


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menus`
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
('album_main', 'standard', 'Album Main Navigation Menu'),
('album_quick', 'standard', 'Album Quick Navigation Menu')
;


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_main_album', 'album', 'Albums', '', '{"route":"album_general","action":"browse","icon":"fa fa-image"}', 'core_main', '', 3),

('core_sitemap_album', 'album', 'Albums', '', '{"route":"album_general","action":"browse"}', 'core_sitemap', '', 3),

('album_main_browse_photos', 'album', 'Browse Photos', 'Album_Plugin_Menus::canViewAlbums', '{"route":"album_general", "controller": "index", "action":"browse-photos","icon":"fa fa-images"}', 'album_main', '', 1),
('album_main_browse', 'album', 'Browse Albums', 'Album_Plugin_Menus::canViewAlbums', '{"route":"album_general","action":"browse","icon":"fa fa-image"}', 'album_main', '', 2),
('album_main_manage', 'album', 'My Albums', 'Album_Plugin_Menus::canCreateAlbums', '{"route":"album_general","action":"manage","icon":"fa fa-user"}', 'album_main', '', 3),
('album_main_upload', 'album', 'Add New Photos', 'Album_Plugin_Menus::canCreateAlbums', '{"route":"album_general","action":"upload","icon":"fa fa-plus"}', 'album_main', '', 4),

('album_quick_upload', 'album', 'Add New Photos', 'Album_Plugin_Menus::canCreateAlbums', '{"route":"album_general","action":"upload","class":"buttonlink icon_photos_new"}', 'album_quick', '', 1),

('core_admin_main_plugins_album', 'album', 'Photo Albums', '', '{"route":"admin_default","module":"album","controller":"manage","action":"index"}', 'core_admin_main_plugins', '', 999),

('album_admin_main_manage', 'album', 'Manage Albums', '', '{"route":"admin_default","module":"album","controller":"manage"}', 'album_admin_main', '', 1),
('album_admin_main_settings', 'album', 'Global Settings', '', '{"route":"admin_default","module":"album","controller":"settings"}', 'album_admin_main', '', 2),
('album_admin_main_level', 'album', 'Member Level Settings', '', '{"route":"admin_default","module":"album","controller":"level"}', 'album_admin_main', '', 3),
('album_admin_main_categories', 'album', 'Categories', '', '{"route":"admin_default","module":"album","controller":"settings", "action":"categories"}', 'album_admin_main', '', 4),

('authorization_admin_level_album', 'album', 'Photo Albums', '', '{"route":"admin_default","module":"album","controller":"level","action":"index"}', 'authorization_admin_level', '', 999),
('album_admin_main_managephotos', 'album', 'Manage Photos', '', '{"route":"admin_default","module":"album","controller":"manage-photos"}', 'album_admin_main', '', 999);


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_core_modules`
--

INSERT INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('album', 'Photo Albums', 'This plugin gives your users their own personal photo albums. These albums can be configured to store photos, videos,or any other file types you choose to allow. Users can interact by commenting on each others photos and viewing their friends'' recent updates.', '4.10.0beta1', 1, 'extra');


-- --------------------------------------------------------

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `editable`, `is_generated`) VALUES
('album_photo_new', 'album', '{item:$subject} added photo(s) to the album {item:$object}:', 1, 5, 1, 3, 1, 0, 1),
('post_self_multi_photo', 'album', '{item:$subject} added {var:$count} {item:$action:new photos}.\r\n{body:$body}', 1, 5, 1, 1, 1, 1, 0),
('comment_album', 'album', '{item:$subject} commented on {item:$owner}''s {item:$object:album}.', 1, 1, 1, 3, 3, 0, 0),
('comment_album_photo', 'album', '{item:$subject} commented on {item:$owner}''s {item:$object:photo}.', 1, 1, 1, 3, 3, 0, 0),
('like_album', 'album', '{item:$subject} liked {item:$owner}''s {item:$object:album}.', 1, 1, 1, 3, 3, 0, 0),
('like_album_photo', 'album', '{item:$subject} liked {item:$owner}''s {item:$object:photo}.', 1, 1, 1, 3, 3, 0, 0);

-- --------------------------------------------------------

--
-- Dumping data for table `engine4_authorization_permissions`
--

-- ALL
-- auth_view, auth_comment, auth_tag, attach_max
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'auth_view' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'auth_comment' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'auth_tag' as `name`,
    5 as `value`,
    '["everyone","owner_network","owner_member_member","owner_member","owner"]' as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'attach_max' as `name`,
    3 as `value`,
    0 as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN('public');

-- USERS
-- view, comment, tag, create, edit, delete
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'comment' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'tag' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'edit' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'delete' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'allow_network' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
-- ADMIN, MODERATOR
-- view, comment, tag, create, edit, delete
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'view' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'comment' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'tag' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'create' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'edit' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'delete' as `name`,
    2 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'allow_network' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');
-- PUBLIC
-- view, tag
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'view' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('public');
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'tag' as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('public');

--
-- Dumping data for table `engine4_core_settings`
--

INSERT IGNORE INTO `engine4_core_settings`(`name`, `value`) VALUES ('album.searchable', 0);

--
-- indexing for table `engine4_album_albums`
--

ALTER TABLE `engine4_album_albums` ADD INDEX(`type`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`creation_date`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`modified_date`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`view_count`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`comment_count`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`like_count`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`view_privacy`);

ALTER TABLE `engine4_album_albums` ADD INDEX(`networks`);

--
-- indexing for table `engine4_album_photos`
--
ALTER TABLE `engine4_album_photos` ADD INDEX(`creation_date`);

ALTER TABLE `engine4_album_photos` ADD INDEX(`modified_date`);

ALTER TABLE `engine4_album_photos` ADD INDEX(`view_count`);

ALTER TABLE `engine4_album_photos` ADD INDEX(`comment_count`);

ALTER TABLE `engine4_album_photos` ADD INDEX(`like_count`);

ALTER TABLE `engine4_album_photos` ADD INDEX(`order`);


ALTER TABLE `engine4_album_categories` ADD `subcat_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_album_categories` ADD `subsubcat_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_album_categories` ADD `order` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_album_albums` ADD `subcat_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `engine4_album_albums` ADD `subsubcat_id` INT(11) NOT NULL DEFAULT '0';

-- --------------------------------------------------------

--
-- Table structure for table `engine4_album_ratings`
--

DROP TABLE IF EXISTS `engine4_album_ratings`;
CREATE TABLE IF NOT EXISTS `engine4_album_ratings` (
  `album_id` int(10) unsigned NOT NULL,
  `user_id` int(9) unsigned NOT NULL,
  `rating` tinyint(1) unsigned default NULL,
  `type` VARCHAR(16) NOT NULL DEFAULT 'album',
  PRIMARY KEY  (`album_id`,`user_id`, `type`),
  KEY `INDEX` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;
ALTER TABLE `engine4_album_albums` ADD `rating` FLOAT NOT NULL;
ALTER TABLE `engine4_album_photos` ADD `rating` FLOAT NOT NULL;

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
("album_rating", "album", '{item:$subject} has rated your album {item:$object}.', 0, "");

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES ("notify_album_rating", "album", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]");

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
("album_photo_rating", "album", '{item:$subject} has rated your photo {item:$object}.', 0, "");

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES ("notify_album_photo_rating", "album", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]");


ALTER TABLE `engine4_album_albums` ADD `approved` TINYINT(1) NOT NULL DEFAULT "1";
ALTER TABLE `engine4_album_albums` ADD INDEX(`approved`);

ALTER TABLE `engine4_album_albums` ADD `resubmit` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_album_albums` ADD INDEX(`resubmit`);

ALTER TABLE `engine4_album_photos` ADD `approved` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `engine4_album_photos` ADD INDEX(`approved`);

ALTER TABLE `engine4_album_photos` ADD `resubmit` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_album_photos` ADD INDEX(`resubmit`);

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
  level_id as `level_id`,
  'album' as `type`,
  'approve' as `name`,
  1 as `value`,
  NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
  level_id as `level_id`,
  'album' as `type`,
  'approve' as `name`,
  1 as `value`,
  NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN('user');

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
  level_id as `level_id`,
  'album' as `type`,
  'photoapprove' as `name`,
  1 as `value`,
  NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

INSERT IGNORE INTO `engine4_authorization_permissions`
SELECT
  level_id as `level_id`,
  'album' as `type`,
  'photoapprove' as `name`,
  1 as `value`,
  NULL as `params`
FROM `engine4_authorization_levels` WHERE `type` IN('user');
