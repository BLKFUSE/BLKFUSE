<?php
//folder name or directory name.
$module_name = 'sesvideo';

//product title and module title.
$module_title = 'Advanced Videos & Channels Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesvideo_licensekey']);
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
  
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.pluginactivated')) {
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesvideo_ratings` (
      `rating_id`  int(11) unsigned NOT NULL auto_increment,
      `resource_id` int(11) NOT NULL,
      `resource_type` varchar(128) NOT NULL,
      `user_id` int(9) unsigned NOT NULL,
      `rating` tinyint(1) unsigned DEFAULT NULL,
      `creation_date` DATETIME NOT NULL ,
      PRIMARY KEY  (`rating_id`),
      UNIQUE KEY `uniqueKey` (`user_id`,`resource_type`,`resource_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesvideo_videos` (
        `video_id` int(11) unsigned NOT NULL auto_increment,
        `title` varchar(100) NOT NULL,
        `description` text NOT NULL,
        `search` tinyint(1) NOT NULL default 1,
        `owner_type` varchar(128) NOT NULL,
        `owner_id` int(11) NOT NULL,
        `parent_type` varchar(128) default NULL,
        `parent_id` int(11) unsigned default NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `view_count` int(11) unsigned NOT NULL default 0,
        `favourite_count` int(11) unsigned NOT NULL default 0,
        `comment_count` int(11) unsigned NOT NULL default 0,
        `like_count` int(11) unsigned NOT NULL default 0,
        `type` VARCHAR( 32 ) NOT NULL,
        `code` TEXT NOT NULL,
        `location` varchar (255) default NULL,
        `photo_id` int(11) unsigned default NULL,
        `rating` float NOT NULL,
        `category_id` int(11) unsigned NOT NULL default 0,
        `subcat_id` int(11) unsigned  NULL default 0,
        `thumbnail_id` int(11) unsigned default NULL,
        `is_locked` tinyint(1) unsigned  NULL default 0,
        `password` VARCHAR(255)  default NULL,
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
          PRIMARY KEY  (`video_id`),
          KEY `owner_id` (`owner_id`,`owner_type`),
          KEY `search` (`search`),
          KEY `creation_date` (`creation_date`),
          KEY `view_count` (`view_count`)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;');

    $query = 'DROP TABLE IF EXISTS `engine4_sesvideo_artists`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_artists` (
      `artist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255)  NOT NULL,
      `overview` text  NOT NULL,
      `artist_photo` int(11) NOT NULL,
      `owner_id` INT (11) NOT NULL,
      `order` int(3) NOT NULL,
      `rating` float NOT NULL,
      `favourite_count` int(11) NOT NULL,
      `offtheday` TINYINT( 1 ) NOT NULL,
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY (`artist_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

      DROP TABLE IF EXISTS `engine4_sesvideo_chanels`;
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
        PRIMARY KEY  (`chanel_id`),
        KEY `owner_id` (`owner_id`,`owner_type`),
        KEY `search` (`search`),
        KEY `creation_date` (`creation_date`),
        KEY `view_count` (`view_count`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

      DROP TABLE IF EXISTS `engine4_sesvideo_chanelphotos`;
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
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

      DROP TABLE IF EXISTS `engine4_sesvideo_chanelvideos`;
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
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

      DROP TABLE IF EXISTS `engine4_sesvideo_watchlaters`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_watchlaters` (
        `watchlater_id` int(11) unsigned NOT NULL auto_increment,
        `video_id` int(11) unsigned NOT NULL,
        `owner_id` int(11) unsigned NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY  (`watchlater_id`),
        UNIQUE KEY `uniqueKey` (`video_id`,`owner_id`),
        KEY `creation_date` (`creation_date`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;

      DROP TABLE IF EXISTS `engine4_sesvideo_chanelfollows`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_chanelfollows` (
        `chanelfollow_id` int(11) unsigned NOT NULL auto_increment,
        `chanel_id` int(11) unsigned NOT NULL,
        `owner_id` int(11) unsigned NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY  (`chanelfollow_id`),
        UNIQUE KEY `uniqueKey` (`chanel_id`,`owner_id`),
        KEY `creation_date` (`creation_date`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;


      DROP TABLE IF EXISTS `engine4_sesvideo_playlists`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_playlists` (
      `playlist_id` int(11) unsigned NOT NULL auto_increment,
      `owner_id` int(11) unsigned NOT NULL,
      `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
      `photo_id` INT(11) NULL DEFAULT 0,
      `cover_id` INT(11) NULL DEFAULT 0,
      `video_count`	INT(3) NULL DEFAULT 0,
      `is_private` TINYINT(1) NULL default 0,
      `creation_date` DATETIME NOT NULL,
      `modified_date` DATETIME NOT NULL,
      `video_id` INT(11) unsigned NOT NULL default 0,
      `favourite_count` INT(11) NOT NULL,
      `view_count` int(11) unsigned NOT NULL default 0,
      `like_count` int(11) unsigned NOT NULL default 0,
      `offtheday` tinyint(1)	NOT NULL DEFAULT "0",
      `starttime` DATE DEFAULT NULL,
      `endtime` DATE DEFAULT NULL,
      `is_sponsored` int(11) unsigned NOT NULL DEFAULT 0,
      `is_featured` int(11) unsigned NOT NULL DEFAULT 0,
      `ip_address` VARCHAR(45)  NULL,
      PRIMARY KEY (`playlist_id`),
      KEY `owner_id` (`owner_id`),
      KEY `is_private` (`is_private`),
      KEY `creation_date` (`creation_date`),
      KEY `view_count` (`view_count`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_sesvideo_slides`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_slides` (
      `slide_id` int(11) unsigned NOT NULL auto_increment,
      `gallery_id` int(11) DEFAULT NULL,
      `title` varchar(255) DEFAULT NULL,
      `title_button_color` varchar(255) DEFAULT NULL,
      `description` text,
      `description_button_color` varchar(255) DEFAULT NULL,
      `thumb_icon` INT(11) DEFAULT "0",
      `file_type` varchar(255) DEFAULT NULL,
      `file_id` INT(11) DEFAULT "0",
      `login_button` tinyint(1) DEFAULT "1",
      `extra_button` tinyint(1) DEFAULT "0",
      `signup_button` tinyint(1) DEFAULT "1",
      `login_button_color` varchar(255) DEFAULT NULL,
      `login_button_mouseover_color` varchar(255) DEFAULT NULL,
      `login_button_text` varchar(255) DEFAULT NULL,
      `login_button_text_color` varchar(255) DEFAULT NULL,
      `signup_button_color` varchar(255) DEFAULT NULL,
      `signup_button_mouseover_color` varchar(255) DEFAULT NULL,
      `signup_button_text` varchar(255) DEFAULT NULL,
      `signup_button_text_color` varchar(255) DEFAULT NULL,
      `show_register_form` tinyint(1) DEFAULT "0",
      `position_register_form` enum("left","right") DEFAULT "right",
      `extra_button_color` varchar(255) DEFAULT NULL,
      `extra_button_mouseover_color` varchar(255) DEFAULT NULL,
      `extra_button_text` varchar(255) DEFAULT NULL,
      `extra_button_text_color` varchar(255) DEFAULT NULL,
      `extra_button_link` varchar(255) DEFAULT NULL,
      `order` tinyint(10) NOT NULL DEFAULT "0",
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      PRIMARY KEY (`slide_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_sesvideo_galleries`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_galleries` (
      `gallery_id` int(11) unsigned NOT NULL auto_increment,
      `gallery_name` VARCHAR(255)  NULL ,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      PRIMARY KEY (`gallery_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_sesvideo_playlistvideos`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_playlistvideos` (
      `playlistvideo_id` int(11) unsigned NOT NULL auto_increment,
      `playlist_id` INT(11) NOT NULL ,
      `file_id` int(11) unsigned NOT NULL,
      `order` int(11) unsigned NOT NULL,
      PRIMARY KEY (`playlistvideo_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_sesvideo_favourites`;
      CREATE TABLE IF NOT EXISTS `engine4_sesvideo_favourites` (
      `favourite_id` int(11) unsigned NOT NULL auto_increment,
      `user_id` int(11) unsigned NOT NULL,
      `resource_type` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
      `resource_id` int(11) NOT NULL,
      PRIMARY KEY (`favourite_id`),
      KEY `user_id` (`user_id`,`resource_type`,`resource_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_sesvideo_recentlyviewitems`;
      CREATE TABLE IF NOT EXISTS  `engine4_sesvideo_recentlyviewitems` (
      `recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `resource_id` INT NOT NULL ,
      `resource_type` VARCHAR(64) NOT NULL DEFAULT "album",
      `owner_id` INT NOT NULL ,
      `creation_date` DATETIME NOT NULL,
      UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`, `owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

      DROP TABLE IF EXISTS `engine4_video_fields_maps`;
      CREATE TABLE IF NOT EXISTS `engine4_video_fields_maps` (
      `field_id` int(11) NOT NULL,
      `option_id` int(11) NOT NULL,
      `child_id` int(11) NOT NULL,
      `order` smallint(6) NOT NULL,
      PRIMARY KEY (`field_id`,`option_id`,`child_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;


      INSERT IGNORE INTO `engine4_video_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES (0, 0, 1, 1);

      DROP TABLE IF EXISTS `engine4_video_fields_meta`;
      CREATE TABLE IF NOT EXISTS `engine4_video_fields_meta` (
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
      `validators` text COLLATE utf8mb4_unicode_ci,
      `filters` text COLLATE utf8mb4_unicode_ci,
      `style` text COLLATE utf8mb4_unicode_ci,
      `error` text COLLATE utf8mb4_unicode_ci,
      PRIMARY KEY (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;

      INSERT IGNORE INTO `engine4_video_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES
      (1, "profile_type", "Profile Type", "", "profile_type", 1, 0, 0, 2, 0, 999, "", NULL, NULL, NULL, NULL);

      DROP TABLE IF EXISTS `engine4_video_fields_options`;
      CREATE TABLE IF NOT EXISTS `engine4_video_fields_options` (
      `option_id` int(11) NOT NULL AUTO_INCREMENT,
      `field_id` int(11) NOT NULL,
      `label` varchar(255) NOT NULL,
      `order` smallint(6) NOT NULL DEFAULT "999",
      `type` tinyint(1) NOT NULL DEFAULT "0",
      PRIMARY KEY (`option_id`),
      KEY `field_id` (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

      INSERT IGNORE INTO `engine4_video_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES
      (1, 1, "Rock Videos", 0);

      DROP TABLE IF EXISTS `engine4_video_fields_search`;
      CREATE TABLE IF NOT EXISTS `engine4_video_fields_search` (
      `item_id` int(11) NOT NULL,
      `profile_type` smallint(11) unsigned DEFAULT NULL,
      PRIMARY KEY (`item_id`),
      KEY `profile_type` (`profile_type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

      DROP TABLE IF EXISTS `engine4_video_fields_values`;
      CREATE TABLE IF NOT EXISTS `engine4_video_fields_values` (
      `item_id` int(11) NOT NULL,
      `field_id` int(11) NOT NULL,
      `index` smallint(3) NOT NULL DEFAULT "0",
      `value` text NOT NULL,
      PRIMARY KEY (`item_id`,`field_id`,`index`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;


      INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `enabled`, `multi`, `priority`) VALUES
      ("Advanced Videos & Channels Plugin Video Encode", "video_encode", "sesvideo", "Sesvideo_Plugin_Job_Encode", 1, 2, 75),
      ("Advanced Videos & Channels Plugin Rebuild Video Privacy", "video_maintenance_rebuild_privacy", "sesvideo", "Sesvideo_Plugin_Job_Maintenance_RebuildPrivacy", 1, 1, 50);

      INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("sesvideo_main", "standard", "SNS - Advanced Videos & Channels Plugin Main Navigation Menu");

      INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_sesvideo", "sesvideo", "Videos", "", \'{"route":"sesvideo_general", "icon":"fas fa-video","action":"welcome"}\', "core_main", "", 7),
      ("core_sitemap_sesvideo", "sesvideo", "Videos", "", \'{"route":"sesvideo_general"}\', "core_sitemap", "", 7),
      ("sesvideo_main_browsehome", "sesvideo", "Videos Home", "", \'{"route":"sesvideo_general","action":"home"}\', "sesvideo_main", "", 1),
      ("sesvideo_main_browsevideo", "sesvideo", "Browse Videos", "", \'{"route":"sesvideo_general","action":"browse"}\', "sesvideo_main", "", 2),
      ("sesvideo_main_browsechanel", "sesvideo", "Browse Channels", "Sesvideo_Plugin_Menus::canChanelEnable", \'{"route":"sesvideo_chanel"}\', "sesvideo_main", "", 3),
      ("sesvideo_main_browseplaylist", "sesvideo", "Browse Playlist", "", \'{"route":"sesvideo_playlist"}\', "sesvideo_main", "", 4),
      ("sesvideo_main_artistsbrowse", "sesvideo", "Artists", "", \'{"route":"sesvideo_artists","action":"browse"}\', "sesvideo_main", "", 5),
      ("sesvideo_main_browsecategory", "sesvideo", "Browse Categories", "", \'{"route":"sesvideo_category"}\', "sesvideo_main", "", 6),
      ("sesvideo_main_manage", "sesvideo", "My Videos", "Sesvideo_Plugin_Menus", \'{"route":"sesvideo_general","action":"manage"}\', "sesvideo_main", "", 7),
      ("sesvideo_main_videolocation", "sesvideo", "Locations", "Sesvideo_Plugin_Menus::enableLocation", \'{"route":"sesvideo_general","action":"locations"}\', "sesvideo_main", "", 8),
      ("sesvideo_main_browsepinboard", "sesvideo", "Browse Pinboard", "", \'{"route":"sesvideo_general","action":"browse-pinboard"}\', "sesvideo_main", "", 9),
      ("sesvideo_main_create", "sesvideo", "Post New Video", "Sesvideo_Plugin_Menus", \'{"route":"sesvideo_general","action":"create"}\', "sesvideo_main", "", 999),
      ("sesvideo_quick_create", "sesvideo", "Post New Video", "Sesvideo_Plugin_Menus::canCreateVideos", \'{"route":"sesvideo_general","action":"create","class":"buttonlink sesvideo_icon_video_add"}\', "sesvideo_quick", "", 1),
      ("sesvideo_quick_chanelcreate", "sesvideo", "Create New Channel", "Sesvideo_Plugin_Menus::canCreateChanel", \'{"route":"sesvideo_chanel","action":"create","class":"buttonlink sesvideo_icon_video_add"}\', "sesvideo_chanel_quick", "", 1),
      ("sesvideo_admin_main_utility", "sesvideo", "Video Utilities", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"utility"}\',"sesvideo_admin_main", "", 2),
      ("sesvideo_admin_main_manage", "sesvideo", "Manage Videos", "", \'{"route":"admin_default","module":"sesvideo","controller":"manage"}\', "sesvideo_admin_main", "", 3),
      ("sesvideo_admin_main_managechanels", "sesvideo", "Manage Channels", "", \'{"route":"admin_default","module":"sesvideo","controller":"manage","action":"chanel"}\',"sesvideo_admin_main", "", 4),
      ("sesvideo_admin_main_manageplaylists", "sesvideo", "Manage Playlists", "", \'{"route":"admin_default","module":"sesvideo","controller":"manage","action":"playlist"}\', "sesvideo_admin_main", "", 5),
      ("sesvideo_admin_main_artist", "sesvideo", "Manage Artists", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings", "action":"artists"}\', "sesvideo_admin_main", "", 6),
      ("sesvideo_admin_main_categories", "sesvideo", "Categories", "", \'{"route":"admin_default","module":"sesvideo","controller":"categories","action":"index"}\', "sesvideo_admin_main", "", 7),
      ("sesvideo_admin_main_subcategories", "sesvideo", "Categories", "", \'{"route":"admin_default","module":"sesvideo","controller":"categories","action":"index"}\', "sesvideo_admin_categories", "", 1),
      ("sesvideo_admin_main_subfields", "sesvideo", "Custom Fields", "", \'{"route":"admin_default","module":"sesvideo","controller":"fields"}\', "sesvideo_admin_categories", "", 2),
      ("sesvideo_admin_main_level", "sesvideo", "Member Level Settings", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"level"}\', "sesvideo_admin_main", "", 8),
      ("sesvideo_admin_main_level_video", "sesvideo", "Video Member Level Settings", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"level"}\', "sesvideo_admin_level", "", 1),
      ("sesvideo_admin_main_level_chanel", "sesvideo", "Channel Member Level Settings", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"level-chanel"}\', "sesvideo_admin_level", "", 2),
      ("sesvideo_admin_main_level_chanelphoto", "sesvideo", "Channel Photo Member Level Settings", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"level-chanelphoto"}\', "sesvideo_admin_level", "", 3),
      ("sesvideo_admin_main_lightbox", "sesvideo", "Manage Lightbox", "", \'{"route":"admin_default","module":"sesvideo","controller":"lightbox","action":"index"}\', "sesvideo_admin_main", "", 9),
      ("sesvideo_admin_main_manageSlides", "sesvideo", "Manage Slides", "", \'{"route":"admin_default","module":"sesvideo","controller":"manage-slide","action":"index"}\', "sesvideo_admin_main", "", 10),
      ("sesvideo_admin_main_statistic", "sesvideo", "Statistics", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings","action":"statistic"}\', "sesvideo_admin_main", "", 11),
      ("sesvideo_admin_main_managepages", "sesvideo", "Manage Widgetize Page", "", \'{"route":"admin_default","module":"sesvideo","controller":"settings", "action":"manage-widgetize-page"}\', "sesvideo_admin_main", "", 12),
      ("mobi_browse_video", "sesvideo", "Videos", "", \'{"route":"sesvideo_general"}\', "mobi_browse", "", 9);


      INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`,  `body`,  `enabled`,  `displayable`,  `attachable`,  `commentable`,  `shareable`, `is_generated`) VALUES
      ("sesvideo_video_create", "sesvideo", \'{item:$subject} posted a new video {item:$object}:\', "1", "5", "1", "3", "1", 0),
      ("sesvideo_chanel_create", "sesvideo", \'{item:$subject} create a new channel {item:$object}:\', "1", "5", "1", "3", "1", 0),
      ("sesvideo_playlist_create", "sesvideo", \'{item:$subject} added video {item:$object} to playlist {item:$playlist}:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_video_favourite", "sesvideo", \'{item:$subject} added video {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_chanel_favourite", "sesvideo", \'{item:$subject} added channel {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_playlist_favourite", "sesvideo", \'{item:$subject} added playlist {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_video_rating", "sesvideo", \'{item:$subject} rated video {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_chanel_rating", "sesvideo", \'{item:$subject} rated channel {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sesvideo_chanel_follow", "sesvideo", \'{item:$subject} follow channel {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("comment_video", "sesvideo", \'{item:$subject} commented on {item:$owner}\'\'s {item:$object:video}: {body:$body}\', 1, 1, 1, 1, 1, 0),
      ("sesvideo_chanel_new", "sesvideo", \'{item:$subject} added {var:$count} video(s) to the channel {item:$object}:\', 1, 5, 1, 3, 1, 1),
      ("sesvideo_photo_add", "sesvideo", \'{item:$subject} added {var:$count} photos(s) to the channel {item:$object}:\', 1, 5, 1, 3, 1, 1);

      INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("video_processed", "sesvideo", "Your {item:$object:video} is ready to be viewed.", 0, ""),
      ("sesvideo_video_favourite", "sesvideo", \'{item:$subject} has added your video {item:$object} to favorite.\', 0, ""),
      ("sesvideo_chanel_favourite", "sesvideo", \'{item:$subject} has added your channel {item:$object} to favorite.\', 0, ""),
      ("sesvideo_playlist_favourite", "sesvideo", \'{item:$subject} has added your playlist {item:$object} to favorite.\', 0, ""),
      ("sesvideo_video_rating", "sesvideo", \'{item:$subject} has rated your video {item:$object}.\', 0, ""),
      ("sesvideo_chanel_rating", "sesvideo", \'{item:$subject} has rated your channel {item:$object}.\', 0, ""),
      ("sesvideo_chanel_follow", "sesvideo", \'{item:$subject} has follow your channel {item:$object}.\', 0, ""),
      ("video_processed_failed", "sesvideo", \'Your {item:$object:video} has failed to process.\', 0, "");


      INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
      ("video.ffmpeg.path", ""),
      ("video.jobs", 2),
      ("video.embeds", 1),
      ("video.videos.manifest","videos"),
      ("video.chanel.manifest", "chanels"),
      ("video.video.rating","1"),
      ("video.ratevideo.own","1"),
      ("video.ratevideo.again",1),
      ("video.enable.chanel", 0),
      ("video.upload.option.0","youtube"),
      ("video.upload.option.1","vimeo"),
      ("video.upload.option.2","dailymotion"),
      ("video.upload.option.3","youtubePlaylist");


      INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
      ("notify_video_processed", "sesvideo", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("notify_video_processed_failed", "sesvideo", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("sesvideo_fav_rate_follow", "sesvideo", "[host],[email],[subject],[body],[recipient_title],[recipient_link],[recipient_photo],[object_link]");


      INSERT IGNORE INTO `engine4_authorization_permissions`
          SELECT
          level_id as `level_id`,
          "video" as `type`,
          "auth_view" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "auth_comment" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "max" as `name`,
          3 as `value`,
          "0" as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "addplaylist_max" as `name`,
          3 as `value`,
          "0" as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "view" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "edit" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "delete" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "comment" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "locked" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "upload" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "rating" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "imageviewer" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "addplayl_video" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "edit" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "delete" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "comment" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "locked" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "upload" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "rating" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "imageviewer" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "addplayl_video" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "video" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "auth_view" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "auth_comment" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "maxchannel" as `name`,
          3 as `value`,
          "0" as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "view" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "edit" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "delete" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "comment" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "rating_chanel" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "edit" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "delete" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "comment" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "rating_chanel" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesvideo_chanel" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "auth_view" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "auth_comment" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner","registered","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "maxchanel" as `name`,
          3 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "view" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "edit" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "delete" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "comment" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "create" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "edit" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "delete" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "comment" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "chanelphoto" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("public");

      INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesvideo_video" as `type`,
        "rating_artist" as `name`,
        1 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");

      UPDATE `engine4_core_modules` SET `enabled` = "0" WHERE `engine4_core_modules`.`name` = "video"';
      $results = explode(';', $query);
      foreach ($results as $result) {
        if (!empty($result)) {
            try {
                $db->query($result);
            }catch(Exception $e){
                echo $result;die;
            }
        }
      }

      include_once APPLICATION_PATH . "/application/modules/Sesvideo/controllers/defaultsettings.php";
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesvideo.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesvideo.licensekey', $_POST['sesvideo_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesvideo.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesvideo.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesvideo.licensekey', $_POST['sesvideo_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
