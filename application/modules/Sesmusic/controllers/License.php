<?php
//folder name or directory name.
$module_name = 'sesmusic';

//product title and module title.
$module_title = 'Professional Music Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesmusic_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.pluginactivated')) {
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
      ("sesmusic.albumlink", \'a:2:{i:0;s:6:"report";i:1;s:5:"share";}\'),
      ("sesmusic.artistlink", \'a:1:{i:0;s:9:"favourite";}\'),
      ("sesmusic.songlink", \'a:2:{i:0;s:6:"report";i:1;s:5:"share";}\');');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES

      ("sesmusic_admin_main_subalbumssettings", "sesmusic", "Music Album Settings", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings", "action": "album-settings"}\', "sesmusic_admin_main_settings", "", 2),

      ("sesmusic_admin_main_subsongssettings", "sesmusic", "Song Settings", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings", "action": "song-settings"}\', "sesmusic_admin_main_settings", "", 3),

      ("sesmusic_admin_main_subartistssettings", "sesmusic", "Artist Settings", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings", "action": "artist-settings"}\', "sesmusic_admin_main_settings", "", 4),

      ("sesmusic_admin_main_manage", "sesmusic", "Manage Music Albums", "", \'{"route":"admin_default","module":"sesmusic","controller":"manage"}\', "sesmusic_admin_main", "", 2),

      ("sesmusic_admin_main_managesongs", "sesmusic", "Manage Songs", "", \'{"route":"admin_default","module":"sesmusic","controller":"managesongs"}\', "sesmusic_admin_main", "", 3),

      ("sesmusic_admin_main_playlists", "sesmusic", "Manage Playlists", "", \'{"route":"admin_default","module":"sesmusic","controller":"manageplaylists", "action":"index"}\', "sesmusic_admin_main", "", 4),
      ("sesmusic_admin_main_artist", "sesmusic", "Manage Artists", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings", "action":"artists"}\', "sesmusic_admin_main", "", 5),
      ("sesmusic_admin_main_level", "sesmusic", "Member Level Settings", "", \'{"route":"admin_default","module":"sesmusic","controller":"level"}\', "sesmusic_admin_main", "", 6),
      ("sesmusic_admin_main_categories", "sesmusic", "Manage Categories", "", \'{"route":"admin_default","module":"sesmusic","controller":"categories", "action":"index"}\', "sesmusic_admin_main", "", 7),
      ("sesmusic_admin_main_subcategories", "sesmusic", "Music Album Categories", "", \'{"route":"admin_default","module":"sesmusic","controller":"categories", "action":"index"}\', "sesmusic_admin_main_categories", "", 1),
      ("sesmusic_admin_main_subsongcategories", "sesmusic", "Song Categories", "", \'{"route":"admin_default","module":"sesmusic","controller":"song-categories", "action":"index"}\', "sesmusic_admin_main_categories", "", 2),
      ("sesmusic_admin_main_statistic", "sesmusic", "Statistics", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings","action":"statistic"}\', "sesmusic_admin_main", "", 8);
      ');

      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
        ("sesmusic_main", "standard", "SNS - Professional Music - Main Navigation Menu"),
        ("sesmusic_quick", "standard", "SNS - Professional Music - Quick Navigation Menu"),
        ("sesmusic_profile", "standard", "SNS - Professional Music - Album Profile Options Menu"),
        ("sesmusic_song_profile", "standard", "SNS - Professional Music - Song Profile Options Menu");
      ');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesmusic_profile_create", "sesmusic", "Upload Album", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 1),
      ("sesmusic_profile_edit", "sesmusic", "Edit Album", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 2),
      ("sesmusic_profile_delete", "sesmusic", "Delete Album", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 3),
      ("sesmusic_profile_addplaylist", "sesmusic", "Add to Playlist", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 4),
      ("sesmusic_profile_report", "sesmusic", "Report", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 5),
      ("sesmusic_profile_share", "sesmusic", "Share", "Sesmusic_Plugin_Menus", "", "sesmusic_profile", "", 6),

      ("sesmusic_song_profile_edit", "sesmusic", "Edit Song", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 1),
      ("sesmusic_song_profile_delete", "sesmusic", "Delete Song", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 2),
      ("sesmusic_song_profile_addplaylist", "sesmusic", "Add to Playlist", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 3),
      ("sesmusic_song_profile_print", "sesmusic", "Print", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 4),
      ("sesmusic_song_profile_report", "sesmusic", "Report", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 5),
      ("sesmusic_song_profile_share", "sesmusic", "Share", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 6),
      ("sesmusic_song_profile_download", "sesmusic", "Share", "Sesmusic_Plugin_Menus", "", "sesmusic_song_profile", "", 7),

      ("core_main_sesmusic", "sesmusic", "Music", "", \'{"route":"sesmusic_general","icon":"fas fa-music","action":"home"}\', "core_main", "", 100),

      ("sesmusic_main_home", "sesmusic", "Music Home", "", \'{"route":"sesmusic_general","action":"home"}\', "sesmusic_main", "", 1),

      ("sesmusic_main_browse", "sesmusic", "Music Albums", "Sesmusic_Plugin_Menus", \'{"route":"sesmusic_general","action":"browse"}\', "sesmusic_main", "", 2),

      ("sesmusic_main_songsbrowse", "sesmusic", "Browse Songs", "", \'{"route":"sesmusic_songs","action":"browse"}\', "sesmusic_main", "", 3),

      ("sesmusic_main_playlistbrowse", "sesmusic", "Browse Playlists", "", \'{"route":"sesmusic_playlists","action":"browse"}\', "sesmusic_main", "", 4),

      ("sesmusic_main_artistsbrowse", "sesmusic", "Browse Artists", "", \'{"route":"sesmusic_artists","action":"browse"}\', "sesmusic_main", "", 5),

      ("sesmusic_main_manage", "sesmusic", "My Music Albums", "Sesmusic_Plugin_Menus", \'{"route":"sesmusic_general","action":"manage"}\', "sesmusic_main", "", 6),

      ("sesmusic_main_create", "sesmusic", "New Music Album", "Sesmusic_Plugin_Menus", \'{"route":"sesmusic_general","action":"create"}\', "sesmusic_main", "", 7),

      ("sesmusic_quick_create", "sesmusic", "New Music Album", "Sesmusic_Plugin_Menus", \'{"route":"sesmusic_general","action":"create","class":"buttonlink icon_sesmusic_new"}\', "sesmusic_quick", "", 1),

      ("sesmusic_main_lyricsbrowse", "sesmusic", "Lyrics", "", \'{"route":"sesmusic_songs","action":"lyrics"}\', "sesmusic_main", "", 8);
      ');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_albums`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albums` (
      `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `owner_id` int(11) unsigned NOT NULL,
      `owner_type` varchar(24)  NOT NULL,
      `category_id` int(11) DEFAULT "0",
      `subcat_id` int(11) DEFAULT "0",
      `subsubcat_id` int(11) DEFAULT "0",
      `title` varchar(63)  NOT NULL DEFAULT "",
      `description` text  NOT NULL,
      `ip_address` VARCHAR( 128 ) NOT NULL,
      `photo_id` int(11) unsigned NOT NULL DEFAULT "0",
      `album_cover` int(11) NOT NULL,
      `search` tinyint(1) NOT NULL DEFAULT "1",
      `profile` tinyint(1) NOT NULL DEFAULT "0",
      `special` enum("wall","message")  DEFAULT NULL,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `view_count` int(11) unsigned NOT NULL DEFAULT "0",
      `like_count` int(11) NOT NULL,
      `comment_count` int(11) unsigned NOT NULL DEFAULT "0",
      `song_count` int(11) NOT NULL,
      `rating` float NOT NULL,
      `favourite_count` int(11) NOT NULL,
      `featured` tinyint(1) NOT NULL,
      `sponsored` tinyint(1) NOT NULL,
      `hot` int(11) NOT NULL,
      `upcoming` tinyint(1) NOT NULL,
      `offtheday` TINYINT( 1 ) NOT NULL,
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY (`album_id`),
      KEY `creation_date` (`creation_date`),
      KEY `owner_id` (`owner_type`,`owner_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_albumsongs`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_albumsongs` (
      `albumsong_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `album_id` int(11) unsigned NOT NULL,
      `title` varchar(60)  NOT NULL,
      `description` text  NOT NULL,
      `ip_address` VARCHAR( 128 ) NOT NULL,
      `photo_id` int(11) NOT NULL,
      `category_id` INT( 11 ) NOT NULL,
      `subcat_id` INT( 11 ) NOT NULL,
      `subsubcat_id` INT( 11 ) NOT NULL,
      `song_cover` int(11) NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      `lyrics` text  NOT NULL,
      `artists` longtext  NOT NULL,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `play_count` int(11) unsigned NOT NULL DEFAULT "0",
      `order` smallint(6) NOT NULL DEFAULT "0",
      `song_id` int(11) NOT NULL,
      `view_count` int(11) NOT NULL,
      `like_count` int(11) NOT NULL,
      `comment_count` int(11) NOT NULL,
      `download_count` int(11) NOT NULL,
      `favourite_count` int(11) NOT NULL,
      `rating` float NOT NULL,
      `featured` tinyint(1) NOT NULL,
      `sponsored` tinyint(1) NOT NULL,
      `hot` tinyint(1) NOT NULL,
      `upcoming` TINYINT( 1 ) NOT NULL,
      `track_id` int(11) NOT NULL,
      `song_url` text NOT NULL,
      `download` TINYINT( 1 ) NOT NULL DEFAULT "1",
      `offtheday` TINYINT( 1 ) NOT NULL,
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY (`albumsong_id`),
      KEY `album_id` (`album_id`,`file_id`),
      KEY `play_count` (`play_count`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_artists`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_artists` (
      `artist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `name` varchar(255)  NOT NULL,
      `overview` text  NOT NULL,
      `artist_photo` int(11) NOT NULL,
      `order` int(3) NOT NULL,
      `rating` float NOT NULL,
      `favourite_count` int(11) NOT NULL,
      `offtheday` TINYINT( 1 ) NOT NULL,
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY (`artist_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_categories`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_categories` (
      `category_id` int(11) NOT NULL AUTO_INCREMENT,
      `param` VARCHAR( 64 ) NOT NULL DEFAULT "album",
      `category_name` varchar(128) NOT NULL,
      `subcat_id` int(11) NOT NULL DEFAULT "0",
      `subsubcat_id` int(11) NOT NULL DEFAULT "0",
      `cat_icon` int(11) NOT NULL DEFAULT "0",
      PRIMARY KEY (`category_id`),
      KEY `category_id` (`category_id`,`category_name`),
      KEY `category_name` (`category_name`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

			$db->query('INSERT IGNORE INTO `engine4_sesmusic_categories` (`param`, `category_name`, `subcat_id`, `subsubcat_id`, `cat_icon`) VALUES
			("song", "Pop", 0, 0, 0),
			("song", "Rap & Hip-Hop", 0, 0, 0),
			("song", "Rock", 0, 0, 0),
			("song", "Country", 0, 0, 0),
			("song", "Latin", 0, 0, 0),
			("song", "R&B", 0, 0, 0),
			("song", "Electronic", 0, 0, 0),
			("song", "Metal", 0, 0, 0),
			("song", "Blues", 0, 0, 0),
			("song", "Alternative/Indie Rock", 0, 0, 0),
			("song", "Religious", 0, 0, 0),
			("song", "Jazz", 0, 0, 0),
			("song", "Classical", 0, 0, 0),
			("song", "Folk", 0, 0, 0);');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_playlists`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_playlists` (
      `playlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `owner_type` varchar(24)  NOT NULL,
      `owner_id` int(11) unsigned NOT NULL,
      `title` varchar(63)  NOT NULL DEFAULT "",
      `description` text  NOT NULL,
      `photo_id` INT( 11 ) NOT NULL,
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      `view_count` int(11) unsigned NOT NULL DEFAULT "0",
      `featured` TINYINT( 1 ) NOT NULL,
      `favourite_count` INT( 11 ) NOT NULL DEFAULT "0",
      `song_count` INT( 11 ) NOT NULL,
      `offtheday` TINYINT( 1 ) NOT NULL,
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY (`playlist_id`),
      KEY `creation_date` (`creation_date`),
      KEY `owner_id` (`owner_type`,`owner_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_playlistsongs`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_playlistsongs` (
      `playlistsong_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `playlist_id` int(11) unsigned NOT NULL,
      `albumsong_id` int(11) NOT NULL,
      `title` varchar(60)  NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      `order` smallint(6) NOT NULL DEFAULT "0",
      PRIMARY KEY (`playlistsong_id`),
      KEY `playlist_id` (`playlist_id`,`file_id`,`albumsong_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_ratings`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_ratings` (
      `rating_id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(9) unsigned NOT NULL,
      `resource_id` int(11) NOT NULL,
      `resource_type` varchar(128) NOT NULL,
      `rating` tinyint(1) unsigned DEFAULT NULL,
      PRIMARY KEY (`rating_id`),
      UNIQUE KEY `resource_id` (`resource_id`,`resource_type`,`user_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_favourites`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_favourites` (
      `favourite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `resource_type` varchar(128) NOT NULL,
      `resource_id` int(11) NOT NULL,
      PRIMARY KEY (`favourite_id`),
      KEY (`user_id`,`resource_type`,`resource_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

			$db->query('DROP TABLE IF EXISTS `engine4_sesmusic_recentlyviewitems`;');
			$db->query('CREATE TABLE IF NOT EXISTS  `engine4_sesmusic_recentlyviewitems` (
			`recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
			`resource_id` INT NOT NULL ,
			`resource_type` VARCHAR( 65 ) NOT NULL DEFAULT "album",
			`owner_id` INT NOT NULL ,
			`creation_date` DATETIME NOT NULL,
			UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`, `owner_id`)
			) ENGINE = InnoDB ;');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sesmusic_album_new", "sesmusic", \'{item:$subject} created a new music album {item:$object}:\', "1", "5", "1", "3", "1", 1);');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES

      ("sesmusic_favouritealbum", "sesmusic", \'{item:$subject} added music album {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sesmusic_favouriteartist", "sesmusic", \'{item:$subject} added artist {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sesmusic_favouriteplaylist", "sesmusic", \'{item:$subject} added playlist {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),

      ("sesmusic_addplaylist", "sesmusic", \'{item:$subject} added song {item:$object} to playlist {item:$playlist}:\', 1, 5, 1, 1, 1, 1),

      ("sesmusic_addalbumplaylist", "sesmusic", \'{item:$subject} added album {item:$object} songs to playlist {item:$playlist}:\', 1, 5, 1, 1, 1, 1),

      ("sesmusic_albumrating", "sesmusic", \'{item:$subject} rated music album {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sesmusic_songrating", "sesmusic", \'{item:$subject} rated song {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sesmusic_artistrating", "sesmusic", \'{item:$subject} rated artist {item:$object}:\', 1, 5, 1, 1, 1, 1),

      ("sesmusic_playedsong", "sesmusic", \'{item:$subject} played song {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sesmusic_favouritealbumsong", "sesmusic", \'{item:$subject} added song {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1);');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("sesmusic_favourite_musicalbum", "sesmusic", \'{item:$subject} has added your music album {item:$object} to favorite.\', 0, ""),
      ("sesmusic_favourite_song", "sesmusic", \'{item:$subject} has added your song {item:$object} to favorite.\', 0, ""),
      ("sesmusic_favourite_playlist", "sesmusic", \'{item:$subject} has added your playlist {item:$object} to favorite.\', 0, "");');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("sesmusic_rated_musicalbum", "sesmusic", \'{item:$subject} has rated your music album {item:$object}.\', 0, ""),
      ("sesmusic_rated_song", "sesmusic", \'{item:$subject} has rated your song {item:$object}.\', 0, "");');

//       $users_table_exist = $db->query('SHOW TABLES LIKE \'engine4_users\'')->fetch();
//       if (!empty($users_table_exist)) {
//         $infomusic_playlist = $db->query("SHOW COLUMNS FROM engine4_users LIKE 'infomusic_playlist'")->fetch();
//         if (empty($infomusic_playlist)) {
//           $db->query('ALTER TABLE `engine4_users` ADD `infomusic_playlist` INT( 11 ) NOT NULL;');
//         }
//       }

//       $music_playlist_table_exist = $db->query('SHOW TABLES LIKE \'engine4_music_playlists\'')->fetch();
//       if (!empty($music_playlist_table_exist)) {
//         $musicimport = $db->query("SHOW COLUMNS FROM engine4_music_playlists LIKE 'musicimport'")->fetch();
//         if (empty($musicimport)) {
//           $db->query("ALTER TABLE `engine4_music_playlists` ADD `musicimport` TINYINT(1) NOT NULL DEFAULT '0'");
//         }
//       }

      $music_album_table_exist = $db->query('SHOW TABLES LIKE \'engine4_sesmusic_albums\'')->fetch();
      if (!empty($music_album_table_exist)) {
        $resource_type = $db->query("SHOW COLUMNS FROM engine4_sesmusic_albums LIKE 'resource_type'")->fetch();
        if (empty($resource_type)) {
          $db->query("ALTER TABLE `engine4_sesmusic_albums` ADD `resource_type` varchar(128) NOT NULL");
        }

        $resource_id = $db->query("SHOW COLUMNS FROM engine4_sesmusic_albums LIKE 'resource_id'")->fetch();
        if (empty($resource_id)) {
          $db->query("ALTER TABLE `engine4_sesmusic_albums` ADD `resource_id` int(11) NOT NULL");
        }

        $store_link = $db->query("SHOW COLUMNS FROM engine4_sesmusic_albums LIKE 'store_link'")->fetch();
        if (empty($store_link)) {
          $db->query("ALTER TABLE `engine4_sesmusic_albums` ADD `store_link` VARCHAR( 255 ) NULL;");
        }
      }

      $music_albumsongs_table_exist = $db->query('SHOW TABLES LIKE \'engine4_sesmusic_albumsongs\'')->fetch();
      if (!empty($music_albumsongs_table_exist)) {
        $store_link = $db->query("SHOW COLUMNS FROM engine4_sesmusic_albumsongs LIKE 'store_link'")->fetch();
        if (empty($store_link)) {
          $db->query("ALTER TABLE `engine4_sesmusic_albumsongs` ADD `store_link` VARCHAR( 255 ) NULL;");
        }
      }
      $db->query('UPDATE  `engine4_core_menuitems` SET  `params` =  \'{"route":"sesmusic_general_home","icon":"fas fa-music","action":"home"}\' WHERE  `engine4_core_menuitems`.`name` = "core_main_sesmusic";');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sesmusic_admin_main_utility", "sesmusic", "Music Utilities", "", \'{"route":"admin_default","module":"sesmusic","controller":"settings","action":"utility"}\', "sesmusic_admin_main", "", 2);');

      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouritealbum';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_artist_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouriteartist';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouriteplaylist';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_albumrating';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_songrating';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_artist_rating' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_artistrating';");
      $db->query("UPDATE `engine4_activity_actiontypes` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_actiontypes`.`type` = 'sesmusic_favouritealbumsong';");


      $db->query("UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_album_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_musicalbum';");
      $db->query("UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_albumsong_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_song';");
      $db->query("UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_playlist_favourite' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_favourite_playlist';");
      $db->query("UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_album_rating' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_rated_musicalbum';");
      $db->query("UPDATE `engine4_activity_notificationtypes` SET `type` = 'sesmusic_albumsong_rating' WHERE `engine4_activity_notificationtypes`.`type` = 'sesmusic_rated_song';");


      $db->query('ALTER TABLE `engine4_sesmusic_albums` CHANGE `resource_type` `resource_type` VARCHAR(128) NULL;');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sesmusic_admin_main_integrateothermodule", "sesmusic", "Integrate Plugins", "", \'{"route":"admin_default","module":"sesmusic","controller":"integrateothermodule","action":"index"}\', "sesmusic_admin_main", "", 995);');

      $db->query('DROP TABLE IF EXISTS `engine4_sesmusic_integrateothermodules`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmusic_integrateothermodules` (
        `integrateothermodule_id` int(11) unsigned NOT NULL auto_increment,
        `module_name` varchar(64) NOT NULL,
        `content_type` varchar(64) NOT NULL,
        `content_url` varchar(255) NOT NULL,
        `content_id` varchar(64) NOT NULL,
        `enabled` tinyint(1) NOT NULL,
        PRIMARY KEY (`integrateothermodule_id`),
        UNIQUE KEY `content_type` (`content_type`,`content_id`),
        KEY `module_name` (`module_name`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('ALTER TABLE `engine4_sesmusic_albums` CHANGE `resource_type` `resource_type` VARCHAR(128) NULL;');

      $db->query('UPDATE `engine4_sesmusic_albums` SET `resource_type` = NULL;');

      $db->query('UPDATE `engine4_core_menuitems` SET `order` = "9" WHERE `engine4_core_menuitems`.`name` = "sesmusic_main_lyricsbrowse";');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesmusic_main_uploadsong", "sesmusic", "Upload Song", "Sesmusic_Plugin_Menus", \'{"route":"sesmusic_general","action":"create","upload":"song"}\', "sesmusic_main", "", 8);');

      $db->query('ALTER TABLE `engine4_sesmusic_albums` ADD `upload_param` VARCHAR(32) NOT NULL DEFAULT "album";');

      $db->query('ALTER TABLE `engine4_sesmusic_albumsongs` ADD `upload_param` VARCHAR(32) NOT NULL DEFAULT "album";');

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
      level_id as `level_id`,
      "sesmusic_album" as `type`,
      "uploadsong" as `name`,
      0 as `value`,
      NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
      level_id as `level_id`,
      "sesmusic_album" as `type`,
      "uploadsong" as `name`,
      0 as `value`,
      NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sesmusic_song_new", "sesmusic", \'{item:$subject} upload a new song {item:$object}:\', "1", "5", "1", "3", "1", 1);');
      
      include_once APPLICATION_PATH . "/application/modules/Sesmusic/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.licensekey', $_POST['sesmusic_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.licensekey', $_POST['sesmusic_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
