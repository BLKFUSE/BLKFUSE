<?php
//folder name or directory name.
$module_name = 'sesadvancedactivity';

//product title and module title.
$module_title = 'Professional Activity & Nested Comments Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesadvancedactivity_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.pluginactivated')) {
      
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesadvancedactivity_admin_main_events", "sesadvancedactivity", "Custom Feed Notification", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"events","action":"index"}\', "sesadvancedactivity_admin_main", "", 2),
      ("sesadvancedactivity_admin_main_feednotification", "sesadvancedactivity", "Intelligent Auto Notifications", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"notification"}\', "sesadvancedactivity_admin_main", "", 3),
      ("sesadvancedactivity_admin_main_statustextcolor", "sesadvancedactivity", "String Color in Feeds", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"statustextcolor","action":"index"}\', "sesadvancedactivity_admin_main", "", 4),
      ("sesadvancedactivity_admin_filter", "sesadvancedactivity", "Feeds Filtering", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"filter"}\', "sesadvancedactivity_admin_main", "", 5),
      ("sesadvancedactivity_admin_main_filtermainsettings", "sesadvancedactivity", "Feeds Filtering Settings", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"filter"}\', "sesadvancedactivity_admin_filter", "", 1),
      ("sesadvancedactivity_admin_main_filtercontentsettings", "sesadvancedactivity", "Manage Filters", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"filter-content"}\', "sesadvancedactivity_admin_filter", "", 2),
      ("sesadvancedactivity_admin_main_welcomesettings", "sesadvancedactivity", "Welcome Tab", "", \'{"route":"admin_default","module":"sesadvancedactivity","controller":"settings", "action":"welcometab"}\', "sesadvancedactivity_admin_main", "", 6),
      ("sesadvancedactivity_admin_main_adcampaign", "sesadvancedactivity", "Ad Campaign", "", \'{"route":"admin_default","module":"core","controller":"ads", "action":"index","target":"_blank"}\', "sesadvancedactivity_admin_main", "", 7),
      ("sesadvancedactivity_admin_main_activittyfeedset", "sesadvancedactivity", "Activity Feed Settings", "", \'{"route":"admin_default","module":"activity","controller":"settings", "action":"index","target":"_blank"}\', "sesadvancedactivity_admin_main", "", 8),
      ("sesadvancedactivity_admin_main_reports", "sesadvancedactivity", "Abuse Reports", "", \'{"route":"admin_default","module":"core","controller":"report", "action":"index","target":"_blank"}\', "sesadvancedactivity_admin_main", "", 9);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesadvancedcomment_admin_main_cmtsettings", "sesadvancedcomment", "Comment Settings", "", \'{"route":"admin_default","module":"sesadvancedcomment","controller":"settings","action":"index"}\', "sesadvancedactivity_admin_main", "", 700),
      ("sesfeedbg_admin_main_febgsettings", "sesfeedbg", "Feed Backgrounds", "", \'{"route":"admin_default","module":"sesfeedbg","controller":"manage","action":"index"}\', "sesadvancedactivity_admin_main", "", 701),
      ("sesfeedgif_admin_main_fegifsettings", "sesfeedgif", "Feed GIF", "", \'{"route":"admin_default","module":"sesfeedgif","controller":"settings"}\', "sesadvancedactivity_admin_main", "", 702),
      ("sesfeelingactivity_admin_main_flngsettings", "sesfeelingactivity", "Feelings", "", \'{"route":"admin_default","module":"sesfeelingactivity","controller":"settings"}\', "sesadvancedactivity_admin_main", "", 704);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesadvancedcomment_admin_emotio", "sesadvancedcomment", "Stickers Settings", "", \'{"route":"admin_default","module":"sesadvancedcomment","controller":"emotion","action":"index"}\', "sesfeelingactivity_admin_main", "", 3),
      ("sesadvancedcomment_admin_main_emotionssettingsmain", "sesadvancedcomment", "Stickers Categories", "", \'{"route":"admin_default","module":"sesadvancedcomment","controller":"emotion","action":"index"}\', "sesadvancedcomment_admin_emotio", "", 1),
      ("sesadvancedcomment_admin_main_emotiongallery", "sesadvancedcomment", "Stickers Packs", "", \'{"route":"admin_default","module":"sesadvancedcomment","controller":"emotion","action":"gallery"}\', "sesadvancedcomment_admin_emotio", "", 2);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesadvancedcomment_admin_main_settings", "sesadvancedcomment", "Global Settings", "", \'{"route":"admin_default","module":"sesadvancedcomment","controller":"settings","action":"index"}\', "sesadvancedcomment_admin_main", "", 1),
      ("sesfeedgif_admin_main_settings", "sesfeedgif", "Global Settings", "", \'{"route":"admin_default","module":"sesfeedgif","controller":"settings"}\', "sesfeedgif_admin_main", "", 1),
      ("sesfeelingactivity_admin_main_settings", "sesfeelingactivity", "Global Settings", "", \'{"route":"admin_default","module":"sesfeelingactivity","controller":"settings"}\', "sesfeelingactivity_admin_main", "", 1);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesfeedbg_admin_main_feedbg", "sesfeedbg", "Manage Background Images", "", \'{"route":"admin_default","module":"sesfeedbg","controller":"manage","action":"index"}\', "sesfeedbg_admin_main", "", 3),
      ("sesfeedbg_admin_main_level", "sesfeedbg", "Member Level Settings", "", \'{"route":"admin_default","module":"sesfeedbg","controller":"level","action":"index"}\', "sesfeedbg_admin_main", "", 4);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesfeedgif_admin_main_feedgif", "sesfeedgif", "Manage GIF Images", "", \'{"route":"admin_default","module":"sesfeedgif","controller":"manage","action":"index"}\', "sesfeedgif_admin_main", "", 3),
      ("sesfeedgif_admin_main_level", "sesfeedgif", "Member Level Settings", "", \'{"route":"admin_default","module":"sesfeedgif","controller":"level","action":"index"}\', "sesfeedgif_admin_main", "", 4);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesfeelingactivity_admin_main_feelingactivity", "sesfeelingactivity", "Manage Categories", "", \'{"route":"admin_default","module":"sesfeelingactivity","controller":"feeling","action":"index"}\', "sesfeelingactivity_admin_main", "", 3),
      ("sesfeelingactivity_admin_main_level", "sesfeelingactivity", "Member Level Settings", "", \'{"route":"admin_default","module":"sesfeelingactivity","controller":"level"}\', "sesfeelingactivity_admin_main", "", 4);');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("post_self_link", "sesadvancedactivity", \'{item:$subject}\r\n{body:$body}\', 1, 5, 1, 4, 1, 0),
      ("post_self_music", "sesadvancedactivity", \'{item:$subject}\r\n{body:$body}\', 1, 5, 1, 4, 1, 0),
      ("post_self_photo", "sesadvancedactivity", \'{item:$subject}\r\n{body:$body}\', 1, 5, 1, 4, 1, 0),
      ("post_self_video", "sesadvancedactivity", \'{item:$subject}\r\n{body:$body}\', 1, 5, 1, 4, 1, 0),
      ("post_self_file", "sesadvancedactivity", \'{item:$subject} uploaded a file.\r\n{body:$body}\', 1, 5, 1, 4, 0, 0),
      ("post_self_buysell", "sesadvancedactivity", \'{item:$subject}\r\n{body:$body}\', 1, 5, 1, 4, 5, 0),
      ("post_video", "sesadvancedactivity", \'{item:$subject} {body:$body}\', 1, 5, 1, 4, 1, 0),
      ("post_photo", "sesadvancedactivity", \'{actors:$subject:$object}: {body:$body}\', 1, 7, 1, 4, 1, 0),("post_music", "sesadvancedactivity", \'{actors:$subject:$object}: {body:$body}\', 1, 7, 1, 4, 1, 0);');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_tagusers` (
      `taguser_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `action_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`taguser_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_files` (
      `file_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `item_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`file_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_buysells` (
      `buysell_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `action_id` int(11) unsigned NOT NULL,
      `title` varchar(255) NOT NULL,
      `price` DECIMAL(8,2) NOT NULL default "0",
      `currency` varchar(45) NOT NULL,
      `description` TEXT NULL,
      `is_sold` TINYINT(1) NOT NULL default "0",
      `buy` VARCHAR(1000) NULL,
      PRIMARY KEY  (`buysell_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_hashtags` (
      `hashtag_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `action_id` int(11) unsigned NOT NULL,
      `title` varchar(255) NOT NULL,
      PRIMARY KEY  (`hashtag_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_filterlists` (
      `filterlist_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `filtertype` VARCHAR(255) NOT NULL,
      `module` VARCHAR(255) NOT NULL,
      `title` varchar(255) NOT NULL,
      `active` TINYINT(1) NOT NULL DEFAULT "1",
      `is_delete` TINYINT(1) NOT NULL DEFAULT "1",
      `order` INT(11),
      PRIMARY KEY  (`filterlist_id`),
      UNIQUE( `filtertype`),
      `file_id` INT(11) NOT NULL DEFAULT "0"
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('INSERT IGNORE INTO `engine4_sesadvancedactivity_filterlists` (`filtertype`, `title`, `active`, `is_delete`, `order`,`module`) VALUES
      ("all", "All Updates", 1, 0, 1,"Core"),
      ("my_networks", "My Network", 1, 0, 2,"Networks"),
      ("my_friends", "Friends", 1, 0, 3,"Members"),
      ("posts", "Posts", 1, 0, 4,"Core"),
      ("saved_feeds", "Saved Feeds", 1, 0, 5,"Core"),
      ("post_self_buysell", "Sell Something", 1, 0, 6,"Core"),
      ("post_self_file", "Files", 1, 0, 7,"Core"),
      ("scheduled_post", "Scheduled Post", 1, 0, 8,"Core");');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_savefeeds` (
      `savefeed_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `action_id` int(11) unsigned NOT NULL,
      `user_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`savefeed_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_hides` (
      `hide_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `resource_id` int(11) unsigned NOT NULL,
      `resource_type` VARCHAR(20) NOT NULL,
      `user_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`hide_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES ("SES : Advanced Activity - Schedule Post", "sesadvancedactivity", "Sesadvancedactivity_Plugin_Task_Jobs", "100", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_linkedin` (
      `user_id` int(11) UNSIGNED NOT NULL,
      `linkedin_uid` bigint(20) UNSIGNED NOT NULL,
      `access_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
      `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT "",
      `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
      PRIMARY KEY (`user_id`),
      UNIQUE KEY `linkedin_uid` (`linkedin_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_targetpost` (
      `targetpost_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `action_id` int(20) UNSIGNED NOT NULL,
      `location_send` varchar(255)  NOT NULL DEFAULT "",
      `country_name` varchar(255)  NOT NULL DEFAULT "",
      `city_name` varchar(255)  NOT NULL DEFAULT "",
      `location_city` varchar(255)  NOT NULL DEFAULT "",
      `location_country` varchar(255)  NOT NULL DEFAULT "",
      `gender_send` varchar(255)  NOT NULL DEFAULT "",
      `age_min_send` varchar(255)  NOT NULL DEFAULT "",
      `age_max_send` varchar(255)  NOT NULL DEFAULT "",
      `lat` varchar(255)  NOT NULL DEFAULT "",
      `lng` varchar(255)  NOT NULL DEFAULT "",
      PRIMARY KEY  (`targetpost_id`),
      UNIQUE KEY `action_id` (`action_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_welcomemessages` (
      `welcomemessage_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(20) UNSIGNED NOT NULL,
      `creation_date` DATE DEFAULT NULL,
      PRIMARY KEY  (`welcomemessage_id`),
      UNIQUE KEY `user_id` (`user_id`,`creation_date`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_birthdaymessages` (
      `birthdaymessage_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(20) UNSIGNED NOT NULL,
      `creation_date` DATE DEFAULT NULL,
      PRIMARY KEY  (`birthdaymessage_id`),
      UNIQUE KEY `user_id` (`user_id`,`creation_date`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_friendbirthdaymessages` (
      `friendbirthdaymessage_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(20) UNSIGNED NOT NULL,
      `creation_date` DATE DEFAULT NULL,
      PRIMARY KEY  (`friendbirthdaymessage_id`),
      UNIQUE KEY `user_id` (`user_id`,`creation_date`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_events` (
      `event_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(20) UNSIGNED NOT NULL,
      `title` VARCHAR(100) NOT NULL,
      `description` TEXT NOT NULL,
      `date` DATE NOT NULL,
      `active` TINYINT(1) DEFAULT "1",
      `file_id` INT(11) UNSIGNED NOT NULL,
      `recurring` TINYINT(1) DEFAULT "1",
      `creation_date` DATE DEFAULT NULL,
      `visibility` INT(11) NOT NULL DEFAULT "2",
      `starttime` DATE NOT NULL,
      `endtime` DATE NOT NULL,
      PRIMARY KEY  (`event_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_eventmessages` (
      `eventmessage_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` int(20) UNSIGNED NOT NULL,
      `event_id` int(20) UNSIGNED NOT NULL,
      `creation_date` DATETIME NULL DEFAULT NULL,
      `userclose` TINYINT(1) NOT NULL DEFAULT "0",
      PRIMARY KEY  (`eventmessage_id`),
      UNIQUE KEY `user_id` (`user_id`,`creation_date`, `event_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_textcolors` (
      `textcolor_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `string` VARCHAR(100) NOT NULL,
      `color` VARCHAR(100) NOT NULL,
      `active` TINYINT(1) NOT NULL DEFAULT "1",
      `animation` VARCHAR(255) NULL,
      PRIMARY KEY  (`textcolor_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sesadvancedactivity_event_share", "sesadvancedactivity", \'{item:$subject} shared a {var:$type}. {body:$body}\', "1", "5", "1", "1", "0", "1");');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_details` (
      `detail_id` int(11) NOT NULL AUTO_INCREMENT,
      `action_id` int(11) NOT NULL,
      `commentable` TINYINT(1) NOT NULL DEFAULT "1",
      `schedule_time` varchar(256) NOT NULL,
      `sesapproved` TINYINT(1) NOT NULL DEFAULT "1",
      `reaction_id` INT(11) NOT NULL DEFAULT "0",
      `sesresource_id` INT( 11 ) NOT NULL DEFAULT "0",
      `sesresource_type` VARCHAR( 45 ) NULL,
      `is_community_ad` TINYINT(1) NOT NULL DEFAULT "0",
      `vote_up_count` INT(11) NOT NULL DEFAULT "0",
      `vote_down_count` INT(11) NOT NULL DEFAULT "0",
      `feedbg_id` INT(11) NOT NULL DEFAULT "0",
      `image_id` INT(11) NOT NULL DEFAULT "0",
      `view_count` INT UNSIGNED NOT NULL DEFAULT "0",
      `share_count` INT UNSIGNED NOT NULL DEFAULT "0",
      `posting_type` TINYINT(1) NOT NULL DEFAULT "0",
      PRIMARY KEY (`detail_id`),
      UNIQUE( `detail_id`, `action_id`),
      INDEX `Activity` (`action_id`) USING BTREE
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_activitylikes` (
      `activitylike_id` int(11) NOT NULL AUTO_INCREMENT,
      `activity_like_id` int(11) NOT NULL,
      `type` TINYINT(1) NOT NULL DEFAULT "1",
      PRIMARY KEY (`activitylike_id`),
      UNIQUE( `activity_like_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_corelikes` (
      `corelike_id` int(11) NOT NULL AUTO_INCREMENT,
      `core_like_id` int(11) NOT NULL,
      `type` TINYINT(1) NOT NULL DEFAULT "1",
      PRIMARY KEY (`corelike_id`),
      UNIQUE( `core_like_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_activitycomments` (
      `activitycomment_id` int(11) NOT NULL AUTO_INCREMENT,
      `activity_comment_id` int(11) NOT NULL,
      `file_id` int(11) NOT NULL DEFAULT "0",
      `parent_id` int(11) NOT NULL DEFAULT "0",
      `gif_id` int(11) NOT NULL DEFAULT "0",
      `emoji_id` int(11) NOT NULL DEFAULT "0",
      `reply_count` int(11) NOT NULL DEFAULT "0",
      `preview` int(11) NOT NULL DEFAULT "0",
      `showpreview` tinyint(1) NOT NULL DEFAULT "0",
      `vote_up_count` int(11) NOT NULL DEFAULT "0",
      `vote_down_count` int(11) NOT NULL DEFAULT "0",
      PRIMARY KEY (`activitycomment_id`),
      UNIQUE( `activity_comment_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_corecomments` (
      `corecomment_id` int(11) NOT NULL AUTO_INCREMENT,
      `core_comment_id` int(11) NOT NULL,
      `file_id` int(11) NOT NULL DEFAULT "0",
      `parent_id` int(11) NOT NULL DEFAULT "0",
      `emoji_id` int(11) NOT NULL DEFAULT "0",
      `reply_count` int(11) NOT NULL DEFAULT "0",
      `preview` int(11) NOT NULL DEFAULT "0",
      `showpreview` tinyint(1) NOT NULL DEFAULT "0",
      `gif_id` int(11) NOT NULL DEFAULT "0",
      `vote_up_count` int(11) NOT NULL DEFAULT "0",
      `vote_down_count` int(11) NOT NULL DEFAULT "0",
      PRIMARY KEY (`corecomment_id`),
      UNIQUE( `core_comment_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_pinposts` (
      `pinpost_id` int(11) NOT NULL AUTO_INCREMENT,
      `action_id` int(11) NOT NULL,
      `resource_id` int(11) NOT NULL,
      `resource_type` varchar(255) NOT NULL,
      PRIMARY KEY (`pinpost_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_tagitems` (
      `tagitem_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      `resource_id` INT(11) NOT NULL,
      `resource_type` VARCHAR(255) NOT NULL,
      `user_id` INT(11) NOT NULL,
      `action_id` INT(11) NOT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_links` (
      `link_id` int(11) unsigned NOT NULL auto_increment,
      `core_link_id` int(11) NOT NULL,
      `ses_aaf_gif` TINYINT(1) NOT NULL DEFAULT "0",
      PRIMARY KEY  (`link_id`),
      UNIQUE( `core_link_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');



      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedcomment_emotioncategories` (
      `category_id` int(11) NOT NULL AUTO_INCREMENT,
      `title` VARCHAR( 255 ) NOT NULL,
      `color` varchar(128) NOT NULL,
      `file_id` int(11) NOT NULL DEFAULT "0",
      PRIMARY KEY (`category_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedcomment_emotiongalleries` (
      `gallery_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `title` VARCHAR(255) NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      `category_id` INT(11) NOT NULL,
      PRIMARY KEY  (`gallery_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedcomment_emotionfiles` (
      `files_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `photo_id` int(11) unsigned NOT NULL,
      `gallery_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`files_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedcomment_useremotions` (
      `emotion_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `user_id` INT(11) unsigned NOT NULL,
      `gallery_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`emotion_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedcomment_commentfiles` (
      `commentfile_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `comment_id` INT(11) unsigned NOT NULL,
      `type` VARCHAR(255) NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`commentfile_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('INSERT IGNORE INTO `engine4_sesadvancedcomment_emotioncategories` (`category_id`, `title`, `color`, `file_id`) VALUES
      (1, "Happy", "#FF4912", 26983),
      (2, "In Love", "#F64E88", 26984),
      (3, "Sad", "#A9A192", 26985),
      (4, "Eating", "#FC8A0F", 26986),
      (5, "Celebrating", "#95C63F", 26987),
      (6, "Active", "#54C6E3", 26988),
      (7, "Working", "#19B596", 26989),
      (8, "Sleepy", "#9571A9", 26990),
      (9, "Angry", "#ED513E", 26991),
      (10, "Confused", "#B37736", 26992);');

      $db->query('INSERT IGNORE INTO `engine4_sesadvancedcomment_emotiongalleries` (`gallery_id`, `title`, `file_id`, `category_id`) VALUES
      (1, "Meep", 26993, 1),
      (2, "Minions", 27030, 1),
      (3, "Lazy Life Line", 27053, 8),
      (4, "Waddles", 27074, 1),
      (5, "Panda", 27109, 2),
      (6, "Tom And Jerry", 27148, 6);');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesfeedbg_backgrounds` (
      `background_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `file_id` int(11) unsigned NOT NULL,
      `order` INT(11) NOT NULL,
      `enabled` TINYINT(1) NOT NULL DEFAULT "1",
      PRIMARY KEY  (`background_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesfeedgif_images` (
      `image_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `file_id` int(11) unsigned NOT NULL,
      `order` INT(11) NOT NULL,
      `enabled` TINYINT(1) NOT NULL DEFAULT "1",
      `gifimage_code` VARCHAR(255) NULL,
      `user_count` INT(11) NOT NULL DEFAULT "0",
      PRIMARY KEY  (`image_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesfeedgif_texts` (
      `text_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `value` INT(11) NOT NULL DEFAULT "1",
      `text` VARCHAR(255) NOT NULL,
      `limit` INT(11) NOT NULL DEFAULT "0",
      PRIMARY KEY  (`text_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesfeelingactivity_feelings` (
      `feeling_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `title` VARCHAR(255) NOT NULL,
      `type` VARCHAR(255) NOT NULL,
      `file_id` int(11) unsigned NOT NULL,
      `order` INT(11) NOT NULL,
      PRIMARY KEY  (`feeling_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesfeelingactivity_feelingicons` (
      `feelingicon_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `feeling_id` int(11) unsigned NOT NULL,
      `type` VARCHAR(255) NOT NULL,
      `title` VARCHAR(255) NOT NULL,
      `feeling_icon` int(11) unsigned NOT NULL,
      `resource_type` VARCHAR(255) NOT NULL,
      `order` INT(11) NOT NULL,
      PRIMARY KEY  (`feelingicon_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesadvancedactivity_feelingposts` (
      `feelingpost_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
      `feeling_id` int(11) unsigned NOT NULL,
      `feelingicon_id` int(11) unsigned NOT NULL,
      `resource_type` varchar(255) DEFAULT NULL,
      `action_id` int(11) unsigned NOT NULL,
      PRIMARY KEY  (`feelingpost_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      $db->query('INSERT IGNORE INTO `engine4_sesfeelingactivity_feelings` (`feeling_id`, `title`, `type`, `file_id`, `order`) VALUES
      (1, "Feeling", "1", 0, 1),
      (2, "Celebrating", "1", 0, 2),
      (3, "Just", "1", 0, 3),
      (4, "Drinking", "1", 0, 4),
      (5, "Eating", "1", 0, 5),
      (6, "Attending", "1", 0, 11),
      (7, "Getting", "1", 0, 12),
      (8, "Looking For", "1", 0, 13),
      (9, "Making", "1", 0, 14),
      (10, "Meeting", "1", 0, 15),
      (11, "Remembering", "1", 0, 16),
      (12, "Thinking About", "1", 0, 17),
      (13, "Watching", "2", 0, 6),
      (14, "Reading", "2", 0, 10),
      (15, "Listening to", "2", 0, 9),
      (18, "Browsing", "2", 0, 7),
      (19, "Attending Event", "2", 0, 8);');

      $db->query('INSERT IGNORE INTO `engine4_sesfeelingactivity_feelingicons` (`feeling_id`, `type`, `title`, `feeling_icon`, `resource_type`, `order`) VALUES
      (13, "2", "Videos", 0, "video", 999),
      (14, "2", "Blogs", 0, "sesblog_blog", 0),
      (15, "2", "Songs", 0, "sesmusic_albumsong", 0),
      (18, "2", "Photos", 0, "album_photo", 0),
      (19, "2", "Events", 0, "sesevent_event", 0);');

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions` (`level_id`, `type`, `name`, `value`, `params`) VALUES (1, "sesfeelingactivity", "enablefeeling", 1, NULL),
      (1, "sesfeelingactivity", "felingscategories", 5, \'["1","2","3","4","5","6","7","8","9","10","11","12", "13", "14", "15", "16", "17", "18", "19"]\'),
      (2, "sesfeelingactivity", "enablefeeling", 1, NULL),
      (2, "sesfeelingactivity", "felingscategories", 5, \'["1","2","3","4","5","6","7","8","9","10","11","12", "13", "14", "15", "16", "17", "18", "19"]\'),
      (3, "sesfeelingactivity", "enablefeeling", 1, NULL),
      (3, "sesfeelingactivity", "felingscategories", 5, \'["1","2","3","4","5","6","7","8","9","10","11","12", "13", "14", "15", "16", "17", "18", "19"]\'),
      (4, "sesfeelingactivity", "enablefeeling", 1, NULL),
      (4, "sesfeelingactivity", "felingscategories", 5, \'["1","2","3","4","5","6","7","8","9","10","11","12", "13", "14", "15", "16", "17", "18", "19"]\');');

      include_once APPLICATION_PATH . "/application/modules/Sesadvancedactivity/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedcomment.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesfeedbg.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesfeedgif.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesfeelingactivity.pluginactivated', 1);

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.licensekey', $_POST['sesadvancedactivity_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.seslkeyauth', $licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.whatsnewicon', 'public/admin/feed.png');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.welcomeicon', 'public/admin/welcome-icon.png');
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesadvancedactivity.licensekey', $_POST['sesadvancedactivity_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}