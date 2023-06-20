CREATE TABLE IF NOT EXISTS `engine4_sesalbum_albums` (

    `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,

    `title` varchar(128) NOT NULL,

    `description` mediumtext NOT NULL,

    `owner_type` varchar(64) NOT NULL,

    `owner_id` int(11) unsigned NOT NULL,

    `category_id` int(11) unsigned NOT NULL DEFAULT "0",

    `subcat_id` int(11) unsigned NOT NULL DEFAULT "0",

    `subsubcat_id` int(11) unsigned NOT NULL DEFAULT "0",

    `creation_date` datetime NOT NULL,

    `modified_date` datetime NOT NULL,

    `photo_id` int(11) unsigned NOT NULL DEFAULT "0",

    `view_count` int(11) unsigned NOT NULL DEFAULT "0",

    `like_count` int(11) unsigned NOT NULL DEFAULT "0",

    `comment_count` int(11) unsigned NOT NULL DEFAULT "0",

    `favourite_count` int(11) unsigned NOT NULL DEFAULT "0",

    `search` tinyint(1) NOT NULL DEFAULT "1",

    `type` enum("wall","profile","message","blog") DEFAULT NULL,

    `is_featured` tinyint(1) NOT NULL DEFAULT "0",

    `is_sponsored` tinyint(1) NOT NULL DEFAULT "0",

    `offtheday` tinyint(1)	NOT NULL DEFAULT "0",

    `starttime` DATE DEFAULT NULL,

    `endtime` DATE DEFAULT NULL,

    `position_cover` VARCHAR(255) NULL,

    `art_cover` int(11) unsigned NOT NULL DEFAULT "0",

    `location` TEXT  NULL,

    `ip_address` VARCHAR(45)  NULL,

    `rating` FLOAT NOT NULL DEFAULT '0',

    `draft` TINYINT(1) NOT NULL DEFAULT "0",

    `resource_type` VARCHAR(128) NULL,

    `resource_id` INT(11) NOT NULL DEFAULT "0",

    `view_privacy` VARCHAR(24) NOT NULL,
	
	`is_locked` TINYINT(1) NOT NULL DEFAULT '0',
	
	`adult` TINYINT( 1 ) NOT NULL DEFAULT  '0',
	
	`password` VARCHAR(255) NULL,

    `download_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',

  PRIMARY KEY (`album_id`),

  KEY `owner_type` (`owner_type`,`owner_id`),

  KEY `category_id` (`category_id`),

  KEY `search` (`search`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

		

CREATE TABLE IF NOT EXISTS `engine4_sesalbum_categories` (

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

      `member_levels` VARCHAR(255) NULL DEFAULT NULL,

      PRIMARY KEY (`category_id`),

      KEY `category_id` (`category_id`,`category_name`),

      KEY `category_name` (`category_name`)

) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1
;
		  

CREATE TABLE IF NOT EXISTS `engine4_sesalbum_photos` (

    `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,

    `album_id` int(11) unsigned NOT NULL,

    `title` varchar(128) NOT NULL,

    `description` mediumtext NOT NULL,

    `creation_date` datetime NOT NULL,

    `modified_date` datetime NOT NULL,

    `order` int(11) unsigned NOT NULL DEFAULT "0",

    `owner_type` varchar(64) NOT NULL,

    `owner_id` int(11) unsigned NOT NULL,

    `file_id` int(11) unsigned NOT NULL,

    `view_count` int(11) unsigned NOT NULL DEFAULT "0",

    `like_count` int(10) unsigned NOT NULL DEFAULT "0",

    `comment_count` int(11) unsigned NOT NULL DEFAULT "0",

    `is_featured` tinyint(1) NOT NULL DEFAULT "0",

    `is_sponsored` tinyint(1) NOT NULL DEFAULT "0",

    `offtheday` tinyint(1)	NOT NULL DEFAULT "0",

    `starttime` DATE DEFAULT NULL,

    `endtime` DATE DEFAULT NULL,

    `position_cover` VARCHAR(255) NULL,

    `rating` INT( 11 ) NOT NULL DEFAULT '0',

    `location` TEXT  NULL,

    `ip_address` VARCHAR(45)  NULL,

    `download_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',

    `favourite_count` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',

    PRIMARY KEY (`photo_id`),

    KEY `album_id` (`album_id`),

    KEY `owner_type` (`owner_type`,`owner_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

					

INSERT IGNORE INTO engine4_sesalbum_albums (album_id,title,description,owner_type,owner_id,category_id,creation_date,modified_date,photo_id,view_count,comment_count,like_count,search,type)  SELECT album_id,title,description,owner_type,owner_id,category_id,creation_date,modified_date,photo_id,view_count,comment_count,like_count,search,type FROM engine4_album_albums
;
					

INSERT IGNORE INTO engine4_sesalbum_photos (photo_id,album_id,title,description,creation_date,modified_date,`order`,owner_type,owner_id,file_id,view_count,comment_count,like_count)  SELECT photo_id,album_id,title,description,creation_date,modified_date,`order`,owner_type,owner_id,file_id,view_count,comment_count,like_count FROM engine4_album_photos
;