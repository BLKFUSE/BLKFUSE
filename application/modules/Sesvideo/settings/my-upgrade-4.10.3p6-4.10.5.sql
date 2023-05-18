CREATE TABLE IF NOT EXISTS `engine4_sesvideo_videos` (
  `video_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `search` tinyint(1) NOT NULL default 1,
  `owner_type` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `parent_type` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci default NULL,
  `parent_id` int(11) unsigned default NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) unsigned NOT NULL default 0,
  `favourite_count` int(11) unsigned NOT NULL default 0,
  `comment_count` int(11) unsigned NOT NULL default 0,
  `like_count` int(11) unsigned NOT NULL default 0,
  `type` VARCHAR( 32 ) NOT NULL,
  `code` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar (255) default NULL,
  `photo_id` int(11) unsigned default NULL,
  `rating` float NOT NULL,
  `category_id` int(11) unsigned NOT NULL default 0,
  `subcat_id` int(11) unsigned  NULL default 0,
  `thumbnail_id` int(11) unsigned default NULL,
  `is_locked` tinyint(1) unsigned  NULL default 0,
  `password` VARCHAR(255)  CHARACTER SET latin1 COLLATE latin1_general_ci default NULL,
  `subsubcat_id` int(11) unsigned  NULL default 0,
  `status` tinyint(1) NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `duration` int(9) unsigned NOT NULL,
  `rotation` smallint unsigned NOT NULL DEFAULT 0,
  `is_sponsored` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `is_hot` tinyint(1) unsigned NOT NULL DEFAULT 0,
  `offtheday` tinyint(1)	NOT NULL DEFAULT "0",
  `starttime` DATE DEFAULT NULL,
  `endtime` DATE DEFAULT NULL,
  `ip_address` VARCHAR(45)  NULL,
  `artists` longtext NOT NULL,
  `view_privacy` VARCHAR (255) NULL,
  `importthumbnail` TINYINT( 1 ) NOT NULL DEFAULT "0",
  `approve` TINYINT(1) NOT NULL DEFAULT "1",
  `adult` TINYINT( 1 ) NOT NULL DEFAULT  "0",
  `activity_text` TEXT NULL,
  `networks` VARCHAR(255) NULL,
  `levels` VARCHAR(255) NULL,
    PRIMARY KEY  (`video_id`),
    KEY `owner_id` (`owner_id`,`owner_type`),
    KEY `search` (`search`),
    KEY `creation_date` (`creation_date`),
    KEY `view_count` (`view_count`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
					
CREATE TABLE IF NOT EXISTS `engine4_sesvideo_categories` (
      `category_id` int(11) unsigned NOT NULL auto_increment,
      `slug` varchar(255) NOT NULL,
      `category_name` varchar(128) NOT NULL,
      `subcat_id` int(11)  NULL DEFAULT 0,
      `subsubcat_id` int(11)  NULL DEFAULT 0,
      `title` varchar(255) DEFAULT NULL,
      `description` text,
      `thumbnail` int(11) NOT NULL DEFAULT 0,
      `cat_icon` int(11) NOT NULL DEFAULT 0,
      `order` int(11) NOT NULL DEFAULT 0,
      `profile_type` int(11) DEFAULT NULL,
      `member_levels` varchar (255) DEFAULT NULL,
      PRIMARY KEY (`category_id`),
      KEY `category_id` (`category_id`,`category_name`),
      KEY `category_name` (`category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
		  
CREATE TABLE IF NOT EXISTS `engine4_sesvideo_watchlaters` (
	`watchlater_id` int(11) unsigned NOT NULL auto_increment,
	`video_id` int(11) unsigned NOT NULL,
	`owner_id` int(11) unsigned NOT NULL,
	`creation_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	PRIMARY KEY  (`watchlater_id`),
	UNIQUE KEY `uniqueKey` (`video_id`,`owner_id`),
	KEY `creation_date` (`creation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `engine4_sesvideo_chanels` (
	`chanel_id` int(11) unsigned NOT NULL auto_increment,
	`title` varchar(100) NOT NULL,
	`description` text NOT NULL,
	`search` tinyint(1) NOT NULL default "1",
	`owner_type` varchar(128)  NOT NULL,
	`owner_id` int(11) NOT NULL,
	`overview` TEXT default NULL,
	`parent_type` varchar(128)  default NULL,
	`parent_id` int(11) unsigned default NULL,
	`creation_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	`view_count` int(11) unsigned NOT NULL default "0",
	`comment_count` int(11) unsigned NOT NULL default "0",
	`like_count` int(11) unsigned NOT NULL default "0",
	`thumbnail_id` int(11) unsigned default NULL,
	`custom_url` VARCHAR(255) default NULL,
	`rating` float NOT NULL,
	`category_id` int(11) unsigned NOT NULL default "0",
	`favourite_count` int(11) unsigned NOT NULL default "0",
	`follow_count` int(11) unsigned NOT NULL default "0",
	`subcat_id` int(11) unsigned NOT NULL default "0",
	`subsubcat_id` int(11) unsigned NOT NULL default "0",
	`cover_id` int(11) unsigned NOT NULL,
	`follow` TINYINT(2) NOT NULL DEFAULT "1",
	`offtheday` tinyint(1)	NOT NULL DEFAULT "0",
	`starttime` DATE DEFAULT NULL,
	`endtime` DATE DEFAULT NULL,
	`is_verified` TINYINT(1) NOT NULL DEFAULT "0",
	`is_sponsored` tinyint(1) unsigned NOT NULL DEFAULT "0",
	`is_featured` tinyint(1) unsigned NOT NULL DEFAULT "0",
	`is_hot` tinyint(1) unsigned NOT NULL DEFAULT "0",
	`ip_address` VARCHAR(45)  NULL,
	`adult` TINYINT( 1 ) NOT NULL DEFAULT "0",
	 PRIMARY KEY  (`chanel_id`),
	 KEY `owner_id` (`owner_id`,`owner_type`),
	 KEY `search` (`search`),
	 KEY `creation_date` (`creation_date`),
	 KEY `view_count` (`view_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `engine4_sesvideo_chanelphotos` (
	`chanelphoto_id` int(11) unsigned NOT NULL auto_increment,
	`title` varchar(100) NOT NULL,
	`description` text NOT NULL,
	`chanel_id` int(11) unsigned NOT NULL default "0",
	`order` int(11) unsigned NOT NULL default "0",
	`file_id` int(11) unsigned NOT NULL default "0",
	`owner_id` int(11) NOT NULL,
	`creation_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	`location` VARCHAR(255) NULL DEFAULT NULL,
	`view_count` int(11) unsigned NOT NULL default "0",
	`comment_count` int(11) unsigned NOT NULL default "0",
	`like_count` int(11) unsigned NOT NULL default "0",
	`rating` float NOT NULL,
	`favourite_count` int(11) unsigned NOT NULL DEFAULT "0",
	`download_count` INT(11) NOT NULL DEFAULT "0",
	`ip_address` VARCHAR(45) NULL DEFAULT NULL,
	 PRIMARY KEY  (`chanelphoto_id`),
	 KEY `owner_id` (`owner_id`),
	 KEY `creation_date` (`creation_date`),
	 KEY `view_count` (`view_count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `engine4_sesvideo_chanelvideos` (
	`chanelvideo_id` int(11) unsigned NOT NULL auto_increment,
	`chanel_id` int(11) unsigned NOT NULL,
	`video_id` int(11) unsigned NOT NULL,
	`owner_id` int(11) unsigned NOT NULL,
	`creation_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	PRIMARY KEY  (`chanelvideo_id`),
	KEY `creation_date` (`creation_date`),
	UNIQUE KEY `uniqueKey` (`chanel_id`,`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

CREATE TABLE IF NOT EXISTS `engine4_sesvideo_chanelfollows` (
	`chanelfollow_id` int(11) unsigned NOT NULL auto_increment,
	`chanel_id` int(11) unsigned NOT NULL,
	`owner_id` int(11) unsigned NOT NULL,
	`creation_date` datetime NOT NULL,
	`modified_date` datetime NOT NULL,
	PRIMARY KEY  (`chanelfollow_id`),
	UNIQUE KEY `uniqueKey` (`chanel_id`,`owner_id`),
	KEY `creation_date` (`creation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;



INSERT IGNORE INTO `engine4_sesvideo_chanelfollows`( `chanel_id`, `owner_id`, `creation_date`, `modified_date`) SELECT  `chanel_id`, `owner_id`, `creation_date`, `modified_date` FROM `engine4_video_chanelfollows`;

INSERT IGNORE INTO `engine4_sesvideo_chanelphotos`(`chanelphoto_id`, `title`, `description`, `chanel_id`, `order`, `file_id`, `owner_id`, `creation_date`, `modified_date`, `location`, `view_count`, `comment_count`, `like_count`, `rating`, `favourite_count`, `download_count`, `ip_address`) SELECT  `chanelphoto_id`, `title`, `description`, `chanel_id`, `order`, `file_id`, `owner_id`, `creation_date`, `modified_date`, `location`, `view_count`, `comment_count`, `like_count`, `rating`, `favourite_count`, `download_count`, `ip_address` FROM engine4_video_chanelphotos;

INSERT IGNORE INTO `engine4_sesvideo_chanels`(`chanel_id`, `title`, `description`, `search`, `owner_type`, `owner_id`, `overview`, `parent_type`, `parent_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `like_count`, `thumbnail_id`, `custom_url`, `rating`, `category_id`, `favourite_count`, `follow_count`, `subcat_id`, `subsubcat_id`, `cover_id`, `follow`, `offtheday`, `starttime`, `endtime`, `is_verified`, `is_sponsored`, `is_featured`, `is_hot`, `ip_address`, `adult`) SELECT  `chanel_id`, `title`, `description`, `search`, `owner_type`, `owner_id`, `overview`, `parent_type`, `parent_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `like_count`, `thumbnail_id`, `custom_url`, `rating`, `category_id`, `favourite_count`, `follow_count`, `subcat_id`, `subsubcat_id`, `cover_id`, `follow`, `offtheday`, `starttime`, `endtime`, `is_verified`, `is_sponsored`, `is_featured`, `is_hot`, `ip_address`, `adult` FROM engine4_video_chanels;

INSERT IGNORE INTO `engine4_sesvideo_ratings`( `user_id`, `rating`, `creation_date`, `resource_id`, `resource_type`) SELECT  `user_id`, `rating`, `creation_date`, `resource_id`, `resource_type` FROM engine4_video_ratings;

INSERT IGNORE INTO `engine4_sesvideo_chanelvideos`(`chanelvideo_id`, `chanel_id`, `video_id`, `owner_id`, `creation_date`, `modified_date`) SELECT  `chanelvideo_id`, `chanel_id`, `video_id`, `owner_id`, `creation_date`, `modified_date` FROM engine4_video_chanelvideos;

INSERT IGNORE INTO `engine4_sesvideo_videos`(`video_id`, `title`, `description`, `search`, `owner_type`, `owner_id`, `parent_type`, `parent_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `like_count`, `type`, `code`, `photo_id`, `rating`, `category_id`, `status`, `file_id`, `duration`, `rotation`, `importthumbnail`, `artists`, `offtheday`, `ip_address`, `starttime`, `endtime`, `location`, `favourite_count`, `is_locked`, `password`, `is_featured`, `is_sponsored`, `is_hot`, `subcat_id`, `thumbnail_id`, `subsubcat_id`, `approve`, `adult`, `networks`, `levels`) SELECT  `video_id`, `title`, `description`, `search`, `owner_type`, `owner_id`, `parent_type`, `parent_id`, `creation_date`, `modified_date`, `view_count`, `comment_count`, `like_count`, `type`, `code`, `photo_id`, `rating`, `category_id`, `status`, `file_id`, `duration`, `rotation`, `importthumbnail`, `artists`, `offtheday`, `ip_address`, `starttime`, `endtime`, `location`, `favourite_count`, `is_locked`, `password`, `is_featured`, `is_sponsored`, `is_hot`, `subcat_id`, `thumbnail_id`, `subsubcat_id`, `approve`, `adult`, `networks`, `levels` FROM engine4_video_videos;

INSERT IGNORE INTO `engine4_sesvideo_watchlaters`( `video_id`, `owner_id`, `creation_date`, `modified_date`) SELECT  `video_id`, `owner_id`, `creation_date`, `modified_date` FROM engine4_video_watchlaters;