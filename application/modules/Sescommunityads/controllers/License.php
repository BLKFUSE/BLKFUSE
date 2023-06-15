<?php
//folder name or directory name.
$module_name = 'sescommunityads';

//product title and module title.
$module_title = 'Community Advertisements Plugin';

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
    $postdata['licenseKey'] = @base64_encode($_POST['sescommunityads_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.pluginactivated')) {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
        ("sescommunityads_adsapprove", "sescommunityads", \'{var:$adsLink} advertisement has been approved.\', "0", "", "1"),
        ("sescommunityads_adsdisapprove", "sescommunityads", \'{var:$adsLink} advertisement has been disapproved.\', "0", "", "1"),
        ("sescommunityads_pmtmadeadmin", "sescommunityads", \'Payment for an advertisement {var:$adsLink} is made.\', "0", "", "1"),
        ("sescommunityads_paymentsuccessfull", "sescommunityads", \'Payment for your {var:$adsLink} advertisement is successful.\', "0", "", "1"),
        ("sescommunityads_adsactivated", "sescommunityads", \'Your {var:$adsLink} advertisement has been activated.\', "0", "", "1"),
        ("sescommunityads_paymentpending", "sescommunityads", \'Payment for your {var:$adsLink} advertisement is pending.\', "0", "", "1"),
        ("sescommunityads_paymentrefunded", "sescommunityads", \'Payment for your {var:$adsLink} advertisement is refunded.\', "0", "", "1"),
        ("sescommunityads_paymentcancel", "sescommunityads", \'Payment for your {var:$adsLink} advertisement is canceled.\', "0", "", "1"),
        ("sescommunityads_newadscreateadmin", "sescommunityads", \'A new {item:$object} advertisement is created.\', "0", "", "1"),
        ("sescommunityads_newadscreateadminapproval", "sescommunityads", \'A new {item:$object} advertisement is created & waiting approval.\', "0", "", "1"),
        ("sescommunityads_adsexpired", "sescommunityads", \'{var:$adsLink} advertisement has expired.\', "0", "", "1"),
        ("sescommunityads_adsoverdue", "sescommunityads", \'Payment for {var:$adsLink} advertisement is overdue.\', "0", "", "1"),
        ("sescommunityads_adspending", "sescommunityads", \'Payment for {item:$object} advertisement is pending.\', "0", "", "1");');

        $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
        ("sescommunityads_adsapprove", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_adsdisapprove", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_pmtmadeadmin", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_paymentsuccessfull", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_adsactivated", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_paymentpending", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_paymentrefunded", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_paymentcancel", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_newadscreateadmin", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_newadscreateadminapproval", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_adsexpired", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_adsoverdue", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]"),
        ("sescommunityads_adspending", "sescommunityads", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[title],[description],[ad_link]");');

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
        ("sescommunityads_admin_main_package", "sescommunityads", "Manage Ads Packages", "", \'{"route":"admin_default","module":"sescommunityads","controller":"package","action":"manage"}\', "sescommunityads_admin_main", "", 2),
        ("sescommunityads_admin_main_modules", "sescommunityads", "Manage Modules", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings","action":"modules"}\', "sescommunityads_admin_main", "", 3),
        ("sescommunityads_admin_main_levelsettings", "sescommunityads", "Member Level Settings", "", \'{"route":"admin_default","module":"sescommunityads","controller":"levels"}\', "sescommunityads_admin_main", "", 4),
        ("sescommunityads_admin_main_targetting", "sescommunityads", "Targeting Settings", "", \'{"route":"admin_default","module":"sescommunityads","controller":"targeting"}\', "sescommunityads_admin_main", "", 5),
        ("sescommunityads_admin_main_manageads", "sescommunityads", "Manage Advertisements", "", \'{"route":"admin_default","module":"sescommunityads","controller":"ads","action":"manage"}\', "sescommunityads_admin_main", "", 6),
        ("sescommunityads_admin_main_adsreport", "sescommunityads", "Ads Reports", "", \'{"route":"admin_default","module":"sescommunityads","controller":"report"}\', "sescommunityads_admin_main", "", 7),
        ("sescommunityads_admin_main_transactions", "sescommunityads", "Transactions", "", \'{"route":"admin_default","module":"sescommunityads","controller":"transactions"}\', "sescommunityads_admin_main", "", 8),
        ("sescommunityads_admin_main_categories", "sescommunityads", "Categories", "", \'{"route":"admin_default","module":"sescommunityads","controller":"categories"}\', "sescommunityads_admin_main", "", 10),
        ("sescommunityads_admin_main_activity", "sescommunityads", "Advanced Activity", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings","action":"activity"}\', "sescommunityads_admin_main", "", 12),
        ("sescommunityads_admin_main_feedactivity", "sescommunityads", "Feed Type Settings", "", \'{"route":"admin_default","module":"sescommunityads","controller":"settings","action":"feed-settings"}\', "sescommunityads_admin_main", "", 11),
        ("core_main_sescommunityads", "sescommunityads", "Advertisements", "", \'{"route":"sescommunityads_general","action":"browse"}\', "core_main", "", 3),
        ("core_sitemap_sescommunityads", "sescommunityads", "Advertisements", "", \'{"route":"sescommunityads_general","action":"browse"}\', "core_sitemap", "", 3),
        ("sescommunityads_main_browse", "sescommunityads", "Browse Ads", "Sescommunityads_Plugin_Menus::canViewAds", \'{"route":"sescommunityads_general", "controller": "index", "action":"browse"}\', "sescommunityads_main", "", 1),
        ("sescommunityads_main_manage", "sescommunityads", "My Campaigns", "Sescommunityads_Plugin_Menus::canCreateAds", \'{"route":"sescommunityads_general", "controller": "index", "action":"manage"}\', "sescommunityads_main", "", 2),
        ("sescommunityads_main_create", "sescommunityads", "Create Ad", "Sescommunityads_Plugin_Menus::canCreateAds", \'{"route":"sescommunityads_general", "controller": "index", "action":"create"}\', "sescommunityads_main", "", 3),
        ("sescommunityads_main_reports", "sescommunityads", "Reports", "Sescommunityads_Plugin_Menus::canViewReport", \'{"route":"sescommunityads_general", "controller": "index", "action":"report"}\', "sescommunityads_main", "", 4),
        ("sescommunityads_main_helplearn", "sescommunityads", "Help & Learn More", "", \'{"route":"sescommunityads_general", "controller": "index", "action":"help-and-learn"}\', "sescommunityads_main", "", 5);');

        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
        ("sescommunityads_main", "standard", "SNS - Advertisements Main Navigation Menu");');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_packages`;');
        $db->query('CREATE TABLE `engine4_sescommunityads_packages` (
        `package_id` int(11) unsigned NOT NULL auto_increment,
        `title` varchar(128) NOT NULL,
        `description` mediumtext NOT NULL,
        `price` decimal(16,2) NOT NULL DEFAULT "0",
        `recurrence` int(11)	unsigned NOT NULL,
        `recurrence_type` enum("day", "week", "month", "year", "forever")	 NOT NULL,
        `duration` int(11)	unsigned NOT NULL,
        `duration_type` enum("day", "week", "month", "year", "forever")	 NOT NULL,
        `default` tinyint(1)	NOT NULL DEFAULT "0",
        `enabled` tinyint(1) NOT NULL DEFAULT "1",
        `click_type` varchar(255) NOT NULL default "perclick",
        `click_limit` varchar(255) NOT NULL default "-1",
        `level_id` TEXT NOT NULL,
        `modules` TEXT NULL,
        `package_type` VARCHAR(30) NOT NULL DEFAULT "nonRecurring",
        `is_renew_link` TINYINT(1) NOT NULL DEFAULT "0",
        `renew_link_days` INT(11) NOT NULL DEFAULT "0",
        `featured` tinyint(1) NOT NULL DEFAULT "0",
        `featured_days` INT (11) NOT NULL DEFAULT "0",
        `sponsored` tinyint(1) NOT NULL DEFAULT "0",
        `sponsored_days` INT (11) NOT NULL DEFAULT "0",
        `boost_post` tinyint(1) NOT NULL DEFAULT "1",
        `promote_page` tinyint(1) NOT NULL DEFAULT "1",
        `promote_content` tinyint(1) NOT NULL DEFAULT "1",
        `website_visitor` tinyint(1) NOT NULL DEFAULT "1",
        `carousel` tinyint(1) NOT NULL DEFAULT "1",
        `video` tinyint(1) NOT NULL DEFAULT "1",
        `interests` TINYINT(1) NOT NULL DEFAULT "0",
        `banner` TINYINT(1) NOT NULL DEFAULT "0",
        `rentpackage` TINYINT(1) NOT NULL DEFAULT "0",
        `item_count` INT(11) NOT NULL DEFAULT "0",
        `auto_approve` tinyint(1) NOT NULL DEFAULT "0",
        `targetting` tinyint(1) NOT NULL DEFAULT "0",
        `networking` tinyint(1) NOT NULL DEFAULT "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`package_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_modules`;');
        $db->query('CREATE TABLE `engine4_sescommunityads_modules` (
        `module_id` int(11) unsigned NOT NULL auto_increment,
        `title` varchar(128) NOT NULL,
        `module_name` VARCHAR(255) NOT NULL,
        `content_type` VARCHAR(255) NOT NULL,
        `enabled` tinyint(1) NOT NULL DEFAULT "1",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`module_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_campaigns`;');
        $db->query('CREATE TABLE `engine4_sescommunityads_campaigns` (
        `campaign_id` int(11) unsigned NOT NULL auto_increment,
        `title` varchar(128) NOT NULL,
        `user_id` int(11) NOT NULL,
        `views_count` int(11) unsigned NOT NULL DEFAULT "0",
        `click_count` int(11) unsigned NOT NULL DEFAULT "0",
        `ads_count` int(11) unsigned NOT NULL DEFAULT "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`campaign_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_orderspackages`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_orderspackages` (
        `orderspackage_id` int(11) NOT NULL AUTO_INCREMENT,
        `package_id` int(11) NOT NULL,
        `item_count` int(11) NOT NULL,
        `owner_id` int(11) NOT NULL,
        `state` enum("pending","cancelled","failed","imcomplete","complete","refund","okay","overdue","active") NOT NULL DEFAULT "pending",
        `expiration_date` varchar(255) NULL,
        `ip_address` varchar(45) NOT NULL DEFAULT "0.0.0.0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`orderspackage_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_targetads`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_targetads` (
        `targetad_id` int(11) NOT NULL AUTO_INCREMENT,
        `sescommunityad_id` int(11) unsigned NOT NULL,
        `interest_enable` VARCHAR(255) NULL,
        PRIMARY KEY (`targetad_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_ads`;');
        $db->query('CREATE TABLE `engine4_sescommunityads_ads` (
        `sescommunityad_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT(11) UNSIGNED NOT NULL,
        `campaign_id` INT(11) UNSIGNED NOT NULL,
        `title` varchar(255) NULL,
        `description` text NULL,
        `type` varchar(45) NOT NULL,
        `state` varchar(45) NOT NULL DEFAULT "pending",
        `activity_created` TINYINT(1) NOT NULL DEFAULT "1",
        `subtype` varchar(45) NULL DEFAULT "",
        `resources_id` int(11) UNSIGNED DEFAULT NULL DEFAULT "0",
        `resources_type` varchar(100) DEFAULT NULL DEFAULT "",
        `startdate` datetime NOT NULL COMMENT "ad start date",
        `enddate` datetime NULL COMMENT "ad end date",
        `draft` TINYINT(1) NOT NULL DEFAULT "1",
        `paused` TINYINT(1) NOT NULL DEFAULT "0",
        `category_id` INT(11) NOT NULL DEFAULT "0",
        `subcat_id` INT(11) NOT NULL DEFAULT "0",
        `subsubcat_id` INT(11) NOT NULL DEFAULT "0",
        `is_deleted` TINYINT(1) NOT NULL DEFAULT "0",
        `ad_type` VARCHAR(10) NOT NULL,
        `ad_limit` INT(11) NOT NULL DEFAULT "-1",
        `website_image` INT(11) NOT NULL DEFAULT "0",
        `ad_expiration_date` VARCHAR(45) NULL,
        `location` VARCHAR(255) NULL,
        `location_type` VARCHAR(10) NULL,
        `location_distance` INT(11) NOT NULL DEFAULT "0",
        `calltoaction` varchar(45) NULL,
        `calltoaction_url` varchar(255) NULL,
        `more_image` INT(11) NOT NULL DEFAULT "0",
        `see_more_url` VARCHAR(255) NULL,
        `see_more_display_link` VARCHAR(255) NULL,
        `call_to_action_overlay` VARCHAR(255) NULL,
        `is_approved` tinyint(1) NOT NULL DEFAULT "0",
        `views_count` int(11) unsigned NOT NULL DEFAULT "0",
        `click_count` int(11) unsigned NOT NULL DEFAULT "0",
        `status` tinyint(1) NOT NULL DEFAULT "0",
        `package_id` INT(11) NOT NULL DEFAULT "0",
        `featured` TINYINT(1) NULL DEFAULT "0",
        `featured_date` VARCHAR(45) NULL,
        `sponsored` TINYINT(1) NULL DEFAULT "0",
        `sponsored_date` VARCHAR(45) NULL,
        `transaction_id` INT(11) NOT NULL DEFAULT "0",
        `existing_package_order` INT(11) NOT NULL DEFAULT "0",
        `orderspackage_id` INT(11) NOT NULL DEFAULT "0",
        `approved_date` datetime NULL,
        `expiry_notification` TINYINT(1) NOT NULL DEFAULT "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `banner_type` TINYINT(1) NOT NULL DEFAULT "1",
        `html_code` TEXT NOT NULL,
        `banner_id` INT(11) NOT NULL,
        `revselocation` VARCHAR(255) NULL,
        `revselocation_type` VARCHAR(10) NULL,
        `revselocation_distance` INT(11) NOT NULL DEFAULT "0",
        `widgetid` INT(11) NOT NULL,
        `video_src` INT(11) NOT NULL DEFAULT "0",
        PRIMARY KEY (`sescommunityad_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES ("SNS - Community Advertisements Plugin", "sescommunityads", "Sescommunityads_Plugin_Task_Jobs", "100", "1", "0", "0", "0", "0", "0", "0", "0", "0", "0");');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_attachments`;');
        $db->query('CREATE TABLE `engine4_sescommunityads_attachments` (
        `attachment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        `sescommunityad_id` INT(11) UNSIGNED NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` text NOT NULL,
        `destination_url` varchar(255) NOT NULL,
        `file_id` int(11) UNSIGNED DEFAULT NULL DEFAULT "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`attachment_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_transactions`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_transactions` (
        `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
        `package_id` int(11) NOT NULL,
        `owner_id` int(11) NOT NULL,
        `order_id` int(11) NOT NULL,
        `orderspackage_id` int(11) NOT NULL,
        `gateway_id` tinyint(1) DEFAULT NULL,
        `gateway_transaction_id` varchar(128) DEFAULT NULL,
        `gateway_parent_transaction_id` varchar(128) DEFAULT NULL,
        `item_count` int(11) NOT NULL DEFAULT "0",
        `gateway_profile_id` VARCHAR(128) DEFAULT NULL,
        `state` enum("pending","cancelled","failed","incomplete","complete","refund","okay","overdue","initial","active") NOT NULL DEFAULT "pending",
        `change_rate` float NOT NULL DEFAULT "0",
        `total_amount` float NOT NULL DEFAULT "0",
        `currency_symbol` varchar(45) DEFAULT NULL,
        `gateway_type` varchar(45) DEFAULT NULL,
        `ip_address` varchar(45) NOT NULL DEFAULT "0.0.0.0",
        `expiration_date` varchar(255) NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`transaction_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT = 1 ;');
        $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `editable`, `is_generated`) VALUES
        ("sescommunityads_page_ad", "sescommunityads", "{item:$object}", 1, 5, 1, 3, 1, 0, 1);');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_reports` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_reports` (
        `report_id` int(11) unsigned NOT NULL auto_increment,
        `value` varchar(45) NOT NULL,
        `description` TEXT NULL,
        `user_id` int(11) DEFAULT NULL,
        `item_id` int(11) DEFAULT NULL,
        `ip` VARCHAR(45) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`report_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_usefulads` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_usefulads` (
        `usefulad_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) DEFAULT NULL,
        `item_id` int(11) DEFAULT NULL,
        `ip` VARCHAR(45) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`usefulad_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_feedsettings` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_feedsettings` (
        `feedsetting_id` int(11) unsigned NOT NULL auto_increment,
        `module` VARCHAR(255) DEFAULT NULL,
        `type` VARCHAR(255) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`feedsetting_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_categories` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_categories` (
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
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_viewstats` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_viewstats` (
        `viewstat_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) DEFAULT NULL,
        `sescommunityad_id` int(11) DEFAULT NULL,
        `campaign_id` int(11) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`viewstat_id`),
        INDEX (`campaign_id`),
        INDEX (`user_id`),
        INDEX (`sescommunityad_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_campaignstats` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_campaignstats` (
        `campaignstat_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) DEFAULT NULL,
        `type` VARCHAR(10) DEFAULT NULL,
        `sescommunityad_id` int(11) DEFAULT NULL,
        `click` TINYINT(1) NOT NULL DEFAULT "0",
        `view` TINYINT(1) NOT NULL DEFAULT "0",
        `campaign_id` int(11) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`campaignstat_id`),
        INDEX (`campaignstat_id`),
        INDEX (`user_id`),
        INDEX (`sescommunityad_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescommunityads_clickstats` ;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescommunityads_clickstats` (
        `clickstat_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) DEFAULT NULL,
        `sescommunityad_id` int(11) DEFAULT NULL,
        `campaign_id` int(11) DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`clickstat_id`),
        INDEX (`campaign_id`),
        INDEX (`user_id`),
        INDEX (`sescommunityad_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
        $db->query('INSERT INTO `engine4_sescommunityads_packages` (`package_id`, `title`, `description`, `price`, `recurrence`, `recurrence_type`, `duration`, `duration_type`, `default`, `enabled`, `click_type`, `click_limit`, `level_id`, `modules`, `package_type`, `is_renew_link`, `renew_link_days`, `featured`, `featured_days`, `sponsored`, `sponsored_days`, `boost_post`, `promote_page`, `promote_content`, `website_visitor`, `carousel`, `video`, `item_count`, `auto_approve`, `targetting`, `networking`, `creation_date`, `modified_date`) VALUES
        (1, "Default Plan", "This is a default plan", "0.00", 0, "day", 0, "day", 1, 1, "perday", "0", \'["1","2","3","4","6","7"]\', "", "nonRecurring", 0, 0, 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, 5, 1, 0, 0, "NOW()", "NOW()");');
        $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
            level_id as `level_id`,
            "sescommunityads" as `type`,
            "auth_view" as `name`,
            5 as `value`,
            \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
        $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
            level_id as `level_id`,
            "sescommunityads" as `type`,
            "auth_comment" as `name`,
            5 as `value`,
            \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');

        include_once APPLICATION_PATH . "/application/modules/Sescommunityads/controllers/defaultsettings.php";
        
        include_once APPLICATION_PATH . "/application/modules/Sescomadbanr/controllers/License.php";

        Engine_Api::_()->getApi('settings', 'core')->setSetting('sescommunityads.pluginactivated', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sescommunityads.licensekey', $_POST['sescommunityads_licensekey']);
    }
    $domain_name = @base64_encode($_SERVER['HTTP_HOST']);
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescommunityads.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescommunityads.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescommunityads.licensekey', $_POST['sescommunityads_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
