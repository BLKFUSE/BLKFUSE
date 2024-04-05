<?php

//folder name or directory name.
$module_name = 'sesmember';

//product title and module title.
$module_title = 'Ultimate Members Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesmember_licensekey']);
  $postdata['module_name'] = @base64_encode($module_name);
  $postdata['module_title'] = @base64_encode($module_title);

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, "https://socialnetworking.solutions/licensecheck.php");


  curl_setopt($ch, CURLOPT_POST, 1);

// in real life you should use something like:
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));

// receive server response ...
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $server_output = curl_exec($ch);
  $output = explode(" sesquerysql ",$server_output);
  $error = 0;
  if (curl_error($ch)) {
    $error = 1;
  }
  curl_close($ch);

  //Here we can set some variable for checking in plugin files.
  if ($output[0] == "OK" && $error != 1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.pluginactivated')) {
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
			$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES ("sesmember_main", "standard", "SNS - Ultimate Members Main Navigation Menu");');

			$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
			("sesmember_admin_main_manage", "sesmember", "Manage Members", "", \'{"route":"admin_default","module":"sesmember","controller":"manage"}\', "sesmember_admin_main", "", 2),
      ("sesmember_admin_main_profilephoto", "sesmember", "Manage Default Profile Photo", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-profile-photo"}\', "sesmember_admin_main", "", 3),
      ("sesmember_admin_main_homepage", "sesmember", "Manage Member Home Pages", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-page"}\', "sesmember_admin_main", "", 4),
      ("sesmember_admin_main_profilepage", "sesmember", "Manage Member Profile Pages", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-profile"}\', "sesmember_admin_main", "", 5),

      ("sesbasic_admin_tooltip", "sesbasic", "Tooltip Settings", "", \'{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index"}\', "sesbasic_admin_main", "", 4),
      ("sesbasic_admin_main_generaltooltip", "sesbasic", "General Settings", "", \'{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index"}\', "sesbasic_admin_tooltipsettings", "", 1),

      ("user_settings_seslocation", "sesmember", "Edit Location", "Sesmember_Plugin_Menus::canEditLocation", \'{"route":"sesmember_general","module":"sesmember","controller":"index","action":"edit-location"}\', "user_edit", "", 998),

      ("sesbasic_admin_main_sesmember", "sesbasic", "Advanced Member", "", \'{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index","modulename":"sesmember"}\', "sesbasic_admin_tooltipsettings", "", 3),

      ("core_main_sesmember", "sesmember", "Members", "", \'{"route":"user_general","action":"browse"}\', "core_main", "", 2),
      ("sesmember_main_index", "sesmember", "Browse Members", "", \'{"route":"user_general","action":"browse"}\', "sesmember_main", "", 1),
      ("sesmember_main_membernearest", "sesmember", "Nearest Members", "Sesmember_Plugin_Menus::nearestMember", \'{"route":"sesmember_general","action":"nearest-member"}\', "sesmember_main", "", 2),
      ("sesmember_main_topmembers", "sesmember", "Top Rated Members", "", \'{"route":"sesmember_general","action":"top-members"}\', "sesmember_main", "", 3),
      ("sesmember_main_memberreviews", "sesmember", "Browse Member Reviews", "Sesmember_Plugin_Menus::reviewEnable", \'{"route":"sesmember_review","action":"browse"}\', "sesmember_main", "", 4),
      ("sesmember_main_memberlocation", "sesmember", "Locations", "Sesmember_Plugin_Menus::locationEnable", \'{"route":"sesmember_general","action":"locations"}\', "sesmember_main", "", 5),
      ("sesmember_main_pinboardviewmembers", "sesmember", "Pinboard View", "", \'{"route":"sesmember_general","action":"pinborad-view-members"}\', "sesmember_main", "", 6);
      ');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
      ("sesmember_follow", "sesmember", \'{item:$subject} follow you.\', "0", "", "1"),
      ("sesmember_vipmember", "sesmember", \'{item:$subject} mark as vip to you.\', "0", "", "1"),
      ("sesmember_sponsoredmember", "sesmember", \'{item:$subject} mark as sponsored to you.\', "0", "", "1"),
      ("sesmember_featuredmember", "sesmember", \'{item:$subject} mark as featured to you.\', "0", "", "1"),
      ("sesmember_reviewpost", "sesmember", \'{item:$subject} has written a review {item:$object}.\', "0", "", "1");');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sesmember_follow", "sesmember", \'{item:$subject} follow {item:$object}.\', 1, 5, 1, 1, 1, 1),
      ("sesmember_reviewpost", "sesmember", \'{item:$subject} rated and written a review for the member {item:$object}:\', 1, 5, 1, 1, 1, 1);');

			$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sesmember_location_edit", "sesmember", "Edit Location", "Sesmember_Plugin_Menus", "", "user_profile", "", 2);');

			$db->query('DROP TABLE  IF EXISTS `engine4_sesmember_userviews`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_userviews` (
        `view_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `resource_id` int(11) NOT NULL,
        `user_id` int(11) COLLATE utf8mb4_unicode_ci NOT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`view_id`),
        UNIQUE KEY `uniqueKey` (`user_id`,`resource_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');

			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_homepages`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_homepages` (
        `homepage_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
        `title` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `page_id` int(11) NOT NULL,
        `member_levels` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        PRIMARY KEY (`homepage_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_reviewvotes`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_reviewvotes` (
        `reviewvote_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` INT(11) unsigned NOT NULL,
        `review_id` INT(11) unsigned NOT NULL ,
        `type` tinyint(1) NOT NULL,
        PRIMARY KEY (`reviewvote_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_profilephotos`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_profilephotos` (
        `profilephoto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `profiletype_id` int(11) NOT NULL,
        `photo_id` varchar(128) NOT NULL,
        PRIMARY KEY (`profilephoto_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_follows`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_follows` (
        `follow_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `resource_id` int(11) NOT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`follow_id`),
        UNIQUE KEY `uniqueKey` (`user_id`,`resource_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_featuredphotos`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_featuredphotos` (
        `featuredphoto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `photo_id` int(11) NOT NULL,
        PRIMARY KEY (`featuredphoto_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesmember_admin_main_reviewsettings", "sesmember", "Review Settings", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"review-settings"}\', "sesmember_admin_main", "", 5),
      ("sesmember_admin_main_reviewparametersettings", "sesmember", "Setting", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"review-settings"}\', "sesmember_admin_main_review", "", 1),
      ("sesmember_admin_main_managereview", "sesmember", "Manage Reviews", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-reviews"}\', "sesmember_admin_main_review", "", 2),
      ("sesmember_admin_main_levelsettings", "sesmember", "Member Level Setting", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"level-settings"}\', "sesmember_admin_main_review", "", 3),
      ("sesmember_admin_main_reviewparameter", "sesmember", "Review parameters", "", \'{"route":"admin_default","module":"sesmember","controller":"manage","action":"review-parameter"}\', "sesmember_admin_main_review", "", 4);');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_reviews`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_reviews` (
        `review_id` int(11) NOT NULL AUTO_INCREMENT,
        `owner_id` int(11) unsigned NOT NULL,
        `user_id` int(11) unsigned NOT NULL DEFAULT "0",
        `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `pros` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `cons` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `recommended` tinyint(1) NOT NULL DEFAULT "1",
        `like_count` int(11) NOT NULL,
        `comment_count` int(11) NOT NULL,
        `view_count` int(11) NOT NULL,
        `rating` tinyint(1) DEFAULT NULL,
        `featured` tinyint(1) NOT NULL DEFAULT "0",
        `verified` tinyint(1) NOT NULL DEFAULT "0",
        `oftheday` tinyint(1) DEFAULT "0",
        `starttime` datetime DEFAULT NULL,
        `endtime` datetime DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        `useful_count` int(11) NOT NULL DEFAULT "0",
        `funny_count` int(11) NOT NULL DEFAULT "0",
        `cool_count` int(11) NOT NULL DEFAULT "0",
        PRIMARY KEY (`review_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_parameters`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_parameters` (
        `parameter_id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `rating` float NOT NULL,
        `profile_type` int(2) DEFAULT NULL,
        PRIMARY KEY (`parameter_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
			$db->query('DROP TABLE IF EXISTS `engine4_sesmember_review_parametervalues`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_review_parametervalues` (
        `parametervalue_id` int(11) NOT NULL AUTO_INCREMENT,
        `parameter_id` int(11) NOT NULL,
        `rating` float NOT NULL,
        `user_id` INT(11) NOT NULL,
        `resources_id` INT(11) NOT NULL,
        `content_id` INT(11) NOT NULL,
          PRIMARY KEY (`parametervalue_id`),
        UNIQUE KEY `uniqueKey` (`parameter_id`,`user_id`,`resources_id`,`content_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

			$db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES("sesmember_reviewprofile", "standard", "SNS - Ultimate Members - Review Profile Options Menu");');
			
			$db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesmember_review_profile_edit", "sesmember", "Edit Review", "Sesmember_Plugin_Menus", "", "sesmember_reviewprofile", "", 1),
      ("sesmember_review_profile_delete", "sesmember", "Delete Review", "Sesmember_Plugin_Menus", "", "sesmember_reviewprofile", "", 2),
      ("sesmember_review_profile_report", "sesmember", "Report", "Sesmember_Plugin_Menus", "", "sesmember_reviewprofile", "", 3),
      ("sesmember_review_profile_share", "sesmember", "Share", "Sesmember_Plugin_Menus", "", "sesmember_reviewprofile", "", 4);');

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "auth_view" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "auth_comment" as `name`,
          5 as `value`,
          \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "create" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "edit" as `name`,
        2 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "delete" as `name`,
        2 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "create" as `name`,
        1 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "edit" as `name`,
        1 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");');
			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesmember_review" as `type`,
        "delete" as `name`,
        1 as `value`,
        NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");');

			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "view" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
			$db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "comment" as `name`,
          2 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
        $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "comment" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("user");');
        $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
          level_id as `level_id`,
          "sesmember_review" as `type`,
          "view" as `name`,
          1 as `value`,
          NULL as `params`
        FROM `engine4_authorization_levels` WHERE `type` IN("public");');
      $db->update('engine4_core_menuitems', array('enabled' => 0), array('name = ?' => 'core_main_user'));
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
      ("sesmember_admin_main_browsememberspage", "sesmember", "Browse Pages for Profile Types", "", \'{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-browsepage"}\', "sesmember_admin_main", "", 1, 0, 999);');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES ("sesmember_member_likes", "sesmember", \'{item:$subject} has liked your profile.\', 0, "");');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesmember_main_alphbeticmemberssearch", "sesmember", "Alphabetic Members Search", "", \'{"route":"sesmember_general","action":"alphabetic-members-search"}\', "sesmember_main", "", 880);');

      $db->query('ALTER TABLE `engine4_sesmember_follows` ADD `resource_approved` TINYINT(1) NOT NULL DEFAULT "0" AFTER `creation_date`, ADD `user_approved` TINYINT(1) NOT NULL DEFAULT "0" AFTER `resource_approved`;');

      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
      ("sesmember_follow_request", "sesmember", \'{item:$subject} send you follow request.\', "0", "", "1"),
      ("sesmember_follow_requestaccept", "sesmember", \'{item:$subject} accept your follow request.\', "0", "", "1");');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesmember_userinfos` (
        `userinfo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(11) unsigned NOT NULL,
        `follow_count` INT(11) NOT NULL DEFAULT "0",
        `location` VARCHAR(512) NOT NULL,
        `rating` float NOT NULL DEFAULT "0",
        `user_verified` TINYINT(1) NOT NULL DEFAULT "0",
        `cool_count` INT( 11 ) NOT NULL DEFAULT "0",
        `funny_count` INT( 11 ) NOT NULL DEFAULT "0",
        `useful_count` INT( 11 ) NOT NULL DEFAULT "0",
        `featured` TINYINT( 1 ) NOT NULL DEFAULT "0",
        `sponsored` TINYINT( 1 ) NOT NULL DEFAULT "0",
        `vip` TINYINT( 1 ) NOT NULL DEFAULT "0",
        `offtheday` tinyint(1)	NOT NULL DEFAULT "0",
        `starttime` DATE DEFAULT NULL,
        `endtime` DATE DEFAULT NULL,
        `adminpicks` TINYINT(1) NOT NULL DEFAULT "0",
        `order` INT(11) NOT NULL DEFAULT "0",
        PRIMARY KEY (`userinfo_id`),
        UNIQUE KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');
      
      $db->query('INSERT IGNORE INTO `engine4_sesmember_userinfos`(`user_id`) select `user_id` from `engine4_users`;');
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sesmember_admin_main_managepages", "sesmember", "Manage Widgetize Page", "", \'{"route":"admin_default","module":"sesmember","controller":"settings", "action":"manage-widgetize-page"}\', "sesmember_admin_main", "", 999);');

      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`follow_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`location`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`rating`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`user_verified`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`cool_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`funny_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`useful_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`featured`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`sponsored`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`vip`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`offtheday`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`starttime`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`endtime`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`adminpicks`);');
      $db->query('ALTER TABLE `engine4_sesmember_userinfos` ADD INDEX(`order`);');

      $db->query('ALTER TABLE `engine4_sesmember_featuredphotos` ADD INDEX(`user_id`);');
      $db->query('ALTER TABLE `engine4_sesmember_featuredphotos` ADD INDEX(`photo_id`);');

      $db->query('ALTER TABLE `engine4_sesmember_follows` ADD INDEX(`resource_approved`);');
      $db->query('ALTER TABLE `engine4_sesmember_follows` ADD INDEX(`user_approved`);');

      $db->query('ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`type`);');
      $db->query('ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`page_id`);');
      $db->query('ALTER TABLE `engine4_sesmember_homepages` ADD INDEX(`member_levels`);');

      $db->query('ALTER TABLE `engine4_sesmember_profilephotos` ADD INDEX(`profiletype_id`);');
      $db->query('ALTER TABLE `engine4_sesmember_profilephotos` ADD INDEX(`photo_id`);');

      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`owner_id`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`user_id`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`recommended`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`like_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`comment_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`view_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`rating`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`featured`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`verified`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`oftheday`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`starttime`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`endtime`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`creation_date`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`useful_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`funny_count`);');
      $db->query('ALTER TABLE `engine4_sesmember_reviews` ADD INDEX(`cool_count`);');
      
      include_once APPLICATION_PATH . "/application/modules/Sesmember/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmember.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmember.licensekey', $_POST['sesmember_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
		$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.licensekey');
		$licensekey = @base64_encode($licensekey);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmember.sesdomainauth', $domain_name);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmember.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmember.licensekey', $_POST['sesmember_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
