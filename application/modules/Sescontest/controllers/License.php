<?php
//folder name or directory name.
$module_name = 'sescontest';

//product title and module title.
$module_title = 'Advanced Contests Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sescontest_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();

      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("sescontest_main", "standard", "SNS - Advanced Contests - Main Navigation Menu"),
      ("sescontest_quick", "standard", "SNS - Advanced Contests - Quick Navigation Menu"),
      ("sescontest_profile", "standard", "SNS - Advanced Contests - Contest Profile Options Menu");');

      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_contests`;');
      $db->query('CREATE TABLE `engine4_sescontest_contests` (
        `contest_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `resource_type` varchar(100) COLLATE utf8_unicode_ci NULL,
        `resource_id` INT(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT "0",
        `custom_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `contest_type` tinytext COLLATE utf8_unicode_ci NOT NULL,
         `conteststyle` tinyint(1) NOT NULL DEFAULT "1",
        `starttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `endtime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `joinstarttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `joinendtime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `votingstarttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `votingendtime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `resulttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `vote_type` tinyint(2) NOT NULL DEFAULT "0",
        `editor_type` tinyint(2) NOT NULL,
        `category_id` int(11) NOT NULL DEFAULT "0",
        `subcat_id` int(11) NOT NULL DEFAULT "0",
        `subsubcat_id` int(11) NOT NULL DEFAULT "0",
        `timezone` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
        `overview` text COLLATE utf8_unicode_ci,
        `term_condition` text COLLATE utf8_unicode_ci,
        `award` text COLLATE utf8_unicode_ci,
        `award1_message` text COLLATE utf8_unicode_ci,
        `award2` text COLLATE utf8_unicode_ci,
        `award2_message` text COLLATE utf8_unicode_ci,
        `award3` text COLLATE utf8_unicode_ci,
        `award3_message` text COLLATE utf8_unicode_ci,
        `award4` text COLLATE utf8_unicode_ci,
        `award4_message` text COLLATE utf8_unicode_ci,
        `award5` text COLLATE utf8_unicode_ci,
        `award5_message` text COLLATE utf8_unicode_ci,
        `rules` text COLLATE utf8_unicode_ci,
        `photo_id` int(11) DEFAULT NULL,
        `cover` int(11) DEFAULT NULL,
        `cover_position` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
        `background_photo_id` int(11) DEFAULT NULL,
        `search` tinyint(1) NOT NULL,
        `draft` tinyint(1) NOT NULL DEFAULT "0",
        `contest_contact_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `contest_contact_linkedin` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `seo_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `seo_keywords` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
        `seo_description` text COLLATE utf8_unicode_ci,
        `view_count` int(10) UNSIGNED NOT NULL,
        `like_count` int(11) UNSIGNED NOT NULL,
        `comment_count` int(11) UNSIGNED NOT NULL,
        `favourite_count` int(11) UNSIGNED NOT NULL,
        `follow_count` int(11) UNSIGNED NOT NULL,
        `join_count` int(11) UNSIGNED NOT NULL,
         `award_count` tinyint(1) NOT NULL DEFAULT "0",
        `featured` tinyint(1) NOT NULL DEFAULT "0",
        `sponsored` tinyint(1) NOT NULL DEFAULT "0",
        `hot` tinyint(1) NOT NULL DEFAULT "0",
        `verified` tinyint(1) NOT NULL DEFAULT "0",
        `is_approved` tinyint(1) NOT NULL DEFAULT "1",
        `offtheday` tinyint(1) NOT NULL,
        `startdate` date DEFAULT NULL,
        `enddate` date DEFAULT NULL,
         `process` tinyint(1) NOT NULL DEFAULT 0,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY  (`contest_id`),
        KEY `user_id` (`user_id`),
        KEY `search` (`search`),
        KEY `creation_date` (`creation_date`),
        KEY `view_count` (`view_count`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_categories` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_categories` (
        `category_id` int(11) unsigned NOT NULL auto_increment,
        `slug` varchar(255) NOT NULL,
        `category_name` varchar(128) NOT NULL,
        `subcat_id` int(11)  NULL DEFAULT 0,
        `subsubcat_id` int(11)  NULL DEFAULT 0,
        `title` varchar(255) DEFAULT NULL,
        `description` text ,
        `color` VARCHAR(255) ,
        `thumbnail` int(11) NOT NULL DEFAULT 0,
        `cat_icon` int(11) NOT NULL DEFAULT 0,
        `colored_icon` int(11) NOT NULL DEFAULT 0,
        `order` int(11) NOT NULL DEFAULT 0,
        `profile_type` int(11) DEFAULT NULL,
        PRIMARY KEY (`category_id`),
        KEY `category_id` (`category_id`,`category_name`),
        KEY `category_name` (`category_name`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_contest_fields_maps`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_contest_fields_maps` (
        `field_id` int(11) NOT NULL,
        `option_id` int(11) NOT NULL,
        `child_id` int(11) NOT NULL,
        `order` smallint(6) NOT NULL,
        PRIMARY KEY (`field_id`,`option_id`,`child_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('INSERT IGNORE INTO `engine4_contest_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES (0, 0, 1, 1);');
      $db->query('DROP TABLE IF EXISTS `engine4_contest_fields_meta`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_contest_fields_meta` (
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
      $db->query('INSERT IGNORE INTO `engine4_contest_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES(1, "profile_type", "Profile Type", "", "profile_type", 1, 0, 0, 2, 0, 999, "", NULL, NULL, NULL, NULL);');
      $db->query('DROP TABLE IF EXISTS `engine4_contest_fields_options`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_contest_fields_options` (
        `option_id` int(11) NOT NULL AUTO_INCREMENT,
        `field_id` int(11) NOT NULL,
        `label` varchar(255) NOT NULL,
        `order` smallint(6) NOT NULL DEFAULT "999",
        `type` tinyint(1) NOT NULL DEFAULT "0",
        PRIMARY KEY (`option_id`),
        KEY `field_id` (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_contest_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES (1, 1, "Rock Band", 0);');
      $db->query('DROP TABLE IF EXISTS `engine4_contest_fields_search`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_contest_fields_search` (
        `item_id` int(11) NOT NULL,
        `profile_type` smallint(11) unsigned DEFAULT NULL,
        PRIMARY KEY (`item_id`),
        KEY `profile_type` (`profile_type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_contest_fields_values`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_contest_fields_values` (
        `item_id` int(11) NOT NULL,
        `field_id` int(11) NOT NULL,
        `index` smallint(3) NOT NULL DEFAULT "0",
        `value` text NOT NULL,
        PRIMARY KEY (`item_id`,`field_id`,`index`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_dashboards` ;');
      $db->query('CREATE TABLE `engine4_sescontest_dashboards` (
        `dashboard_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `type` varchar(128) NOT NULL,
        `title` varchar(128) NOT NULL,
        `enabled` tinyint(1) NOT NULL default "1",
        `main` tinyint(1) NOT NULL default "0",
         PRIMARY KEY (`dashboard_id`),
           KEY `unique` (`type`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('INSERT IGNORE INTO `engine4_sescontest_dashboards` (`type`, `title`, `enabled`, `main`) VALUES
      ("manage_contest", "Manage Contest", "1", "1"),
      ("edit_contest", "Edit Contest", "1", "0"),
      ("edit_photo", "Edit Photo", "1", "0"),
      ("backgroundphoto", "Background Photo", "1", "0"),
      ("contact_information", "Contact Information", "1", "0"),
      ("overview", "Overview", "1", "0"),
      ("seo", "SEO", "1", "0"),
      ("award", "Awards ", "1", "0"),
      ("rule", "Rules ", "1", "0"),
      ("participant", "Contact Participants", "1", "1"),
      ("contact_participants", "Contact All Participants", "1", "0"),
      ("contact_winners", "Contact Winners", "1", "0 ");');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_medias` ;');
      $db->query('CREATE TABLE `engine4_sescontest_medias` (
        `media_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `title` varchar(128) NOT NULL,
        `enabled` tinyint(1) NOT NULL default "1",
        `banner` int(11) NOT NULL default "0",
         PRIMARY KEY (`media_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('INSERT IGNORE INTO `engine4_sescontest_medias` (`title`, `enabled`, `banner`) VALUES
      ("Text", "1", "0"),
      ("Photo", "1", "0"),
      ("Video", "1", "0"),
      ("Audio", "1", "0");');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_favourites`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_favourites` (
        `favourite_id` int(11) unsigned NOT NULL auto_increment,
        `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
        `resource_id` int(11) unsigned NOT NULL,
        `user_id` int(11) unsigned NOT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY  (`favourite_id`),
        KEY `resource_type` (`resource_type`, `resource_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_followers`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_followers` (
        `follower_id` int(11) unsigned NOT NULL auto_increment,
        `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
        `resource_id` int(11) unsigned NOT NULL,
        `user_id` int(11) unsigned NOT NULL,
        PRIMARY KEY  (`follower_id`),
        KEY `resource_type` (`resource_type`, `resource_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_participants` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_participants` (
        `participant_id` int(11) unsigned NOT NULL auto_increment,
        `contest_id` int(11) unsigned NOT NULL,
        `owner_id` int(11) unsigned NOT NULL,
        `media` tinytext COLLATE utf8_unicode_ci NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` text NOT NULL ,
        `name` varchar(255) DEFAULT NULL,
        `gender` tinyint(1) NOT NULL DEFAULT 0,
        `age` tinyint(2) DEFAULT NULL,
        `email` varchar(128) NOT NULL,
        `phoneno` int(11) DEFAULT NULL,
        `file_id` int(11) DEFAULT 0,
        `photo_id` int(11) NOT NULL DEFAULT 0,
        `main_photo_id` int(11) NOT NULL DEFAULT 0,
        `track_id` int(11) DEFAULT "0",
        `view_count` int(11) UNSIGNED NOT NULL,
        `like_count` int(11) UNSIGNED NOT NULL,
        `comment_count` int(11) UNSIGNED NOT NULL,
        `favourite_count` int(11) UNSIGNED NOT NULL,
        `vote_count` int(11) UNSIGNED NOT NULL DEFAULT 0,
        `status` tinyint(1) NOT NULL DEFAULT 0,
        `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 3,
        `code` text COLLATE utf8_unicode_ci NOT NULL,
        `duration` int(9) UNSIGNED NOT NULL,
        `rank` tinyint(1) NOT NULL DEFAULT 0,
        `creation_date` datetime NOT NULL,
        `winner_date` datetime NOT NULL,
        `vote_date` datetime NOT NULL,
        `votingstarttime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `votingendtime` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `start` tinyint(1) NOT NULL DEFAULT 0,
        `end` tinyint(1) NOT NULL DEFAULT 0,
        `offtheday` tinyint(1) NOT NULL,
        `startdate` date DEFAULT NULL,
        `enddate` date DEFAULT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY  (`participant_id`),
        KEY `participant_id` (`participant_id`,`contest_id`,`owner_id`,`creation_date`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_saves` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_saves` (
        `save_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `resource_type` varchar(32) NOT NULL,
        `resource_id` int(11) unsigned NOT NULL,
        `poster_id` int(11) unsigned NOT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`save_id`),
        KEY `resource_type` (`resource_type`,`resource_id`),
        KEY `poster_type` (`poster_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_votes` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_votes` (
        `vote_id` int(11) unsigned NOT NULL auto_increment,
        `contest_id` int(11) unsigned NOT NULL,
        `participant_id` int(11) unsigned NOT NULL,
        `owner_id` int(11) unsigned NOT NULL DEFAULT 0,
        `ip_address` VARCHAR(256) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `jury_vote_count` INT(11) NULL DEFAULT "1",
        PRIMARY KEY  (`vote_id`),
        KEY `participant_id` (`participant_id`,`contest_id`,`owner_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      $db->query('INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `enabled`, `multi`, `priority`) VALUES
      ("Advanced Contests Plugin Video Encode", "sescontest_video_encode", "sescontest", "Sescontest_Plugin_Job_Encode", 1, 2, 75),
      ("Advanced Contests Plugin Rebuild Video Privacy", "video_maintenance_rebuild_privacy", "sescontest", "Sescontest_Plugin_Job_Maintenance_RebuildPrivacy", 1, 1, 50);');
      $db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
      ("SNS - Advance Contest Winner Entries", "sescontest", "Sescontest_Plugin_Task_Jobs", 60);');
      $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
      ("notify_sescontest_processed", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("notify_sescontest_processed_failed", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]"),
      ("sescontest_admin_approval", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_send_approval_contest", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_approved_contest", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_disapproved_contest", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_create_entry", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_create_entry_followed", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("follow_sescontest", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("comment_sescontest_followed", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_vote_contest_entry", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_vote_start_entry", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_vote_end_entry", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]"),
      ("sescontest_result_announced", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title]"),
      ("sescontest_winner_contest_entry", "sescontest", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[contest_title],[entry_title][member_name][winner_rank]");');


      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sescontest_create", "sescontest", \'{item:$subject} created a new contest {item:$object}:\', 1, 5, 1, 3, 1, 1),
      ("sescontest_create_entry", "sescontest", \'{item:$subject} participated in the contest {itemParent:$object:contest}:\', 1, 5, 1, 3, 1, 1),
      ("comment_sescontest", "sescontest", \'{item:$subject} commented on {item:$owner} {item:$object:contest}: {body:$body}\', 1, 1, 1, 1, 1, 0),
      ("comment_sescontest_entry", "sescontest", \'{item:$subject} commented on {item:$owner} {item:$object:entry}: {body:$body}\', 1, 1, 1, 1, 1, 0),
      ("sescontest_like_contest", "sescontest", \'{item:$subject} likes the contest {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_like_contest_entry", "sescontest", \'{item:$subject} likes the entry {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_favourite_contest", "sescontest", \'{item:$subject} added contest {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_favourite_contest_entry", "sescontest", \'{item:$subject} added entry {item:$object} to favorite:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_follow_contest", "sescontest", \'{item:$subject} followed contest {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_vote_contest_entry", "sescontest", \'{item:$subject} voted for the entry {item:$object}:\', 1, 5, 1, 1, 1, 1),
      ("sescontest_winner_contest_entry", "sescontest", \'{item:$subject} entry of {item:$object:contest}: has been win the contest:\', 1, 5, 1, 1, 1, 1);');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("sescontest_send_approval_contest", "sescontest", \'Your contest {item:$object} has been sent to site admin for approval.\', 0, ""),
      ("sescontest_approved_contest", "sescontest", \'Your contest {item:$object} has been approved.\', 0, ""),
      ("sescontest_disapproved_contest", "sescontest", \'Your contest {item:$object} has been disapproved by site administrator.\', 0, ""),
      ("sescontest_create_entry", "sescontest", \'{item:$subject} has participated in your contest {item:$object}.\', 0, ""),
      ("sescontest_create_entry_followed", "sescontest", \'{item:$subject} has participated in the contest {item:$object}  you followed.\', 0, ""),
      ("sescontest_contest_like_followed", "sescontest", \'{item:$subject} likes the contest {item:$object} you followed.\', 0, ""),
      ("follow_sescontest", "sescontest", \'{item:$subject} followed your contest {item:$object}.\', 0, ""),
      ("comment_sescontest_followed", "sescontest", \'{item:$subject}  has commented on the contest {item:$object} you followed.\', 0, ""),
      ("sescontest_vote_contest_entry", "sescontest", \'{item:$subject} voted your entry {item:$object}.\', 0, ""),
      ("sescontest_vote_start_entry", "sescontest", \'Voting has been started on the contest {item:$object}.\', 0, ""),
      ("sescontest_vote_end_entry", "sescontest", \'Voting has been ended on the contest {item:$object}.\', 0, ""),
      ("sescontest_winner_contest_entry", "sescontest", \'You have got the {var:$rank} rank award in the contest {item:$object}.\', 0, "");');
      $db->query('DROP TABLE IF EXISTS `engine4_sescontest_recentlyviewitems`;');
      $db->query('CREATE TABLE IF NOT EXISTS  `engine4_sescontest_recentlyviewitems` (
        `recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `resource_id` INT NOT NULL ,
        `resource_type` VARCHAR(64) NOT NULL DEFAULT "contest",
        `owner_id` INT NOT NULL ,
        `creation_date` DATETIME NOT NULL,
        UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`, `owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_sescontest", "sescontest", "Contests", "", \'{"route":"sescontest_general"}\', "core_main", "", 999),
      ("sescontest_main_home", "sescontest", "Contest Home", "", \'{"route":"sescontest_general","action":"home"}\', "sescontest_main", "", 1),
      ("sescontest_main_browse", "sescontest", "Browse Contests", "", \'{"route":"sescontest_general","action":"browse"}\', "sescontest_main", "", 2),
      ("sescontest_main_entries-browse", "sescontest", "Entries", "", \'{"route":"sescontest_general","action":"entries"}\', "sescontest_main", "", 3),
      ("sescontest_main_winner-browse", "sescontest", "Winners", "", \'{"route":"sescontest_general","action":"winner"}\', "sescontest_main", "", 4),
      ("sescontest_main_categories", "sescontest", "Categories", "", \'{"route":"sescontest_category"}\', "sescontest_main", "", 5),
      ("sescontest_main_create", "sescontest", "Create New Contest", "Sescontest_Plugin_Menus", \'{"route":"sescontest_general","action":"create"}\', "sescontest_main", "", 6),
      ("sescontest_main_manage", "sescontest", "My Contests", "Sescontest_Plugin_Menus", \'{"route":"sescontest_general","action":"manage"}\', "sescontest_main", "", 7),
      ("sescontest_main_photocontest", "sescontest", "Photo Contests", "", \'{"route":"sescontest_media","action":"photo"}\', "sescontest_main", "", 8),
      ("sescontest_main_videocontest", "sescontest", "Video Contests", "", \'{"route":"sescontest_media","action":"video"}\', "sescontest_main", "", 9),
      ("sescontest_main_textcontest", "sescontest", "Text Contests", "", \'{"route":"sescontest_media","action":"text"}\', "sescontest_main", "", 10),
      ("sescontest_main_audiocontest", "sescontest", "Audio Contests", "", \'{"route":"sescontest_media","action":"audio"}\', "sescontest_main", "", 11),
      ("sescontest_main_activecontest", "sescontest", "Active Contests", "", \'{"route":"sescontest_general","action":"ongoing"}\', "sescontest_main", "", 12),
      ("sescontest_main_comingsooncontest", "sescontest", "Coming Soon Contests", "", \'{"route":"sescontest_general","action":"comingsoon"}\', "sescontest_main", "", 13),
      ("sescontest_main_endedcontest", "sescontest", "Ended Contests", "", \'{"route":"sescontest_general","action":"ended"}\', "sescontest_main", "", 14),
      ("sescontest_main_pinboard", "sescontest", "Pinboard", "", \'{"route":"sescontest_general","action":"pinboard"}\', "sescontest_main", "", 15),
      ("sescontest_admin_main_contcreate", "sescontest", "Contest Creation Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"contestcreate"}\', "sescontest_admin_main", "", 2),
      ("sescontest_admin_main_subcontestcreatesetting", "sescontest", "Creation Global Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"contestcreate"}\', "sescontest_admin_main_contcreate", "", 1),
      ("sescontest_admin_main_subcontestcreatepagesetting", "sescontest", "Create Page Fields Visibility", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"contestcreatepage"}\', "sescontest_admin_main_contcreate", "", 2),
      ("sescontest_admin_main_subcontestcreatepopupsetting", "sescontest", "Create Pop-up Fields Visibility", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"contestcreatepopup"}\', "sescontest_admin_main_contcreate", "", 3),
      ("sescontest_admin_main_entrysettings", "sescontest", "Entry Submission Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"entrycreate"}\', "sescontest_admin_main", "", 3),
      ("sescontest_admin_main_level", "sescontest", "Member Level Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings","action":"level"}\', "sescontest_admin_main", "", 4),
      ("sescontest_admin_main_subcontestmemberlevelsetting", "sescontest", "Contest Member Level Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"level"}\', "sescontest_admin_main_level", "", 1),
      ("sescontest_admin_main_subentrymemberlevelsetting", "sescontest", "Entry Member Level Settings", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"entrylevel"}\', "sescontest_admin_main_level", "", 2),
      ("sescontest_admin_main_media_manage", "sescontest", "Manage Media", "", \'{"route":"admin_default","module":"sescontest","controller":"manage","action":"media"}\', "sescontest_admin_main", "", 5),
      ("sescontest_admin_main_manage", "sescontest", "Manage Contests", "", \'{"route":"admin_default","module":"sescontest","controller":"manage"}\', "sescontest_admin_main", "", 6),
      ("sescontest_admin_main_manageentries", "sescontest", "Manage Entries", "", \'{"route":"admin_default","module":"sescontest","controller":"manage","action":"entries"}\', "sescontest_admin_main", "", 7),
      ("sescontest_admin_main_categories", "sescontest", "Categories & Profile Fields", "", \'{"route":"admin_default","module":"sescontest","controller":"categories","action":"index"}\', "sescontest_admin_main", "", 8),
      ("sescontest_admin_main_subcategories", "sescontest", "Categories & Mapping", "", \'{"route":"admin_default","module":"sescontest","controller":"categories","action":"index"}\', "sescontest_admin_categories", "", 1),
      ("sescontest_admin_main_subfields", "sescontest", "Form Questions", "", \'{"route":"admin_default","module":"sescontest","controller":"fields"}\', "sescontest_admin_categories", "", 2),
      ("sescontest_admin_main_managedashboards", "sescontest", "Dashboard", "", \'{"route":"admin_default","module":"sescontest","controller":"settings","action":"manage-dashboards"}\', "sescontest_admin_main", "", 9),
      ("sescontest_admin_main_utility", "sescontest", "Contest Utilities", "", \'{"route":"admin_default","module":"sescontest","controller":"settings","action":"utility"}\', "sescontest_admin_main", "", 10),
      ("sescontest_admin_main_statistics", "sescontest", "Statistics", "", \'{"route":"admin_default","module":"sescontest","controller":"settings","action":"statistic"}\', "sescontest_admin_main", "", 11),
      ("sescontest_admin_main_managewidgetizepage", "sescontest", "Widgetized Pages", "", \'{"route":"admin_default","module":"sescontest","controller":"settings", "action":"manage-widgetize-page"}\', "sescontest_admin_main", "", 12),
      ("sescontest_profile_dashboard", "sescontest", "Dashboard", "Sescontest_Plugin_Menus", "", "sescontest_profile", "", 1),
      ("sescontest_profile_report", "sescontest", "Report Contest", "Sescontest_Plugin_Menus", "", "sescontest_profile", "", 2),
      ("sescontest_profile_share", "sescontest", "Share Contest", "Sescontest_Plugin_Menus", "", "sescontest_profile", "", 3),
      ("sescontest_profile_delete", "sescontest", "Delete Contest", "Sescontest_Plugin_Menus", "", "sescontest_profile", "", 4);');

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescontest_admin_main_integrateothermodule", "sescontest", "Integrate Plugins", "", \'{"route":"admin_default","module":"sescontest","controller":"integrateothermodule","action":"index"}\', "sescontest_admin_main", "", 995);');

        $db->query('DROP TABLE IF EXISTS `engine4_sescontest_integrateothermodules`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontest_integrateothermodules` (
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

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "auth_view" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner","registered"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "auth_comment" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner","registered"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "create" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","üser");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "delete" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "delete" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "edit" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "edit" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "view" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "view" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user","public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "comment" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "comment" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "award_count" as `name`,
        3 as `value`,
        6 as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "auth_participant" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "upload_cover" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "upload_mainphoto" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "auth_contstyle" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "chooselayout" as `name`,
        5 as `value`,
        \'["1","2","3","4"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_approve" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_featured" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "autosponsored" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_verified" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_hot" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_count" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_seo" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_overview" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contest_bgphoto" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contactinfo" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "contest" as `type`,
        "contparticipant" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "deleteentry" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "deleteentry" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "editentry" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "editentry" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "comment" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "comment" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "blog_options" as `name`,
        5 as `value`,
        \'["write","linkblog"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "photo_options" as `name`,
        5 as `value`,
        \'["capture","uploadphoto","url","linkphoto"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "video_options" as `name`,
        5 as `value`,
        \'["uploadvideo","record","linkvideo"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "music_options" as `name`,
        5 as `value`,
        \'["uploadmusic","record","linkmusic"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "allow_entry_vote" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "allow_entry_vote" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "participant" as `type`,
        "canEntryMultvote" as `name`,
        0 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels`;');

      include_once APPLICATION_PATH . "/application/modules/Sescontest/controllers/defaultsettings.php";
      
      include_once APPLICATION_PATH . "/application/modules/Sescontestjoinfees/controllers/License.php";
      include_once APPLICATION_PATH . "/application/modules/Sescontestjurymember/controllers/License.php";
      include_once APPLICATION_PATH . "/application/modules/Sescontestpackage/controllers/License.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontest.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontest.licensekey', $_POST['sescontest_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
		$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.licensekey');
		$licensekey = @base64_encode($licensekey);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontest.sesdomainauth', $domain_name);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontest.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontest.licensekey', $_POST['sescontest_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
