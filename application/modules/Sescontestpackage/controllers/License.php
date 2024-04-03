<?php

if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.pluginactivated')) {

  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
  
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescontest_admin_packagesetting", "sescontestpackage", "Package Settings", "", \'{"route":"admin_default","module":"sescontestpackage","controller":"package","action":"settings"}\', "sescontest_admin_main", "", 2),
  ("sescontest_admin_subpackagesetting", "sescontestpackage", "Package Settings", "", \'{"route":"admin_default","module":"sescontestpackage","controller":"package","action":"settings"}\', "sescontest_admin_packagesetting", "", 1),
  ("sescontest_main_manage_package", "sescontest", "My Packages", "Sescontest_Plugin_Menus", \'{"route":"sescontest_general","action":"packages"}\', "sescontest_main", "", 7);');

  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescontest_admin_package", "sescontestpackage", "Manage Packages", "", \'{"route":"admin_default","module":"sescontestpackage","controller":"package"}\', "sescontest_admin_packagesetting", "", 2),
  ("sescontestpackage_admin_main_transaction", "sescontestpackage", "Manage Transactions", "", \'{"route":"admin_default","module":"sescontestpackage","controller":"package", "action":"manage-transaction"}\', "sescontest_admin_packagesetting", "", 3);');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestpackage_packages`;');
  $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontestpackage_packages` (
    `package_id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255),
    `description` text,
    `item_count` INT(11) DEFAULT "0",
    `custom_fields` TEXT DEFAULT NULL,
    `member_level` varchar(255) DEFAULT NULL,
    `price` float DEFAULT "0",
    `recurrence` varchar(25) DEFAULT "0",
    `renew_link_days` INT(11) DEFAULT "0",
    `is_renew_link` tinyint(1) DEFAULT "0",
    `recurrence_type` varchar(25) DEFAULT NULL,
    `duration` varchar(25) DEFAULT "0",
    `duration_type` varchar(10) DEFAULT NULL,
    `enabled` tinyint(1) NOT NULL DEFAULT "1",
    `params` text DEFAULT NULL,
    `custom_fields_params` TEXT DEFAULT NULL,
    `default` tinyint(1) NOT NULL DEFAULT "0",
    `order` INT(11) NOT NULL DEFAULT "0",
    `highlight` TINYINT(1) NOT NULL DEFAULT "0",
    `show_upgrade` INT(11) NOT NULL DEFAULT "0",
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY (`package_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

  $db->query('INSERT IGNORE INTO `engine4_sescontestpackage_packages` (`title`, `description`, `member_level`, `price`, `recurrence`, `recurrence_type`, `duration`, `duration_type`, `enabled`, `params`, `default`, `creation_date`, `modified_date`) VALUES ("Free Contest Package", NULL, "0,1,2,3,4", "0", "0", "forever", "0", "forever", "1", \'{"is_featured":"1","is_sponsored":"1","is_verified":"1","award_count":"5","allow_participant":null,"upload_cover":"1","upload_mainphoto":"1","contest_choose_style":"1","contest_chooselayout":["1","2","3","4"],"contest_approve":"1","contest_featured":"0","contest_sponsored":"0","contest_verified":"0","contest_hot":0,"contest_seo":"1","contest_overview":"1","contest_bgphoto":"1","contest_contactinfo":"1","contest_enable_contactparticipant":"1","custom_fields":1}\', "1", "NOW()", "NOW()");');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestpackage_transactions`;');
  $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontestpackage_transactions` (
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
    `state` enum("pending","cancelled","failed","imcomplete","complete","refund","okay","overdue","initial","active") NOT NULL DEFAULT "pending",
    `change_rate` float NOT NULL DEFAULT "0",
    `total_amount` float NOT NULL DEFAULT "0",
    `currency_symbol` varchar(45) DEFAULT NULL,
    `gateway_type` varchar(45) DEFAULT NULL,
    `ip_address` varchar(45) NOT NULL DEFAULT "0.0.0.0",
    `expiration_date` datetime NOT NULL,
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY (`transaction_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT = 1 ;');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestpackage_orderspackages`;');
  $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontestpackage_orderspackages` (
    `orderspackage_id` int(11) NOT NULL AUTO_INCREMENT,
    `package_id` int(11) NOT NULL,
    `item_count` int(11) NOT NULL,
    `owner_id` int(11) NOT NULL,
    `state` enum("pending","cancelled","failed","imcomplete","complete","refund","okay","overdue","active") NOT NULL DEFAULT "pending",
    `expiration_date` datetime NOT NULL,
    `ip_address` varchar(45) NOT NULL DEFAULT "0.0.0.0",
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY (`orderspackage_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

  $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES ("sescontest_payment_notify", "sescontest", \'Make payment of your contest {item:$object} to get your contest approved.\', 0, "");');

  $db->query('INSERT IGNORE INTO `engine4_sescontest_dashboards` (`type`, `title`, `enabled`, `main`) VALUES
  ("upgrade", "Upgrade Package", "1", "0");');

  $db->query('ALTER TABLE `engine4_sescontest_contests` ADD `package_id` INT(11) NOT NULL DEFAULT "0";');
  $db->query('ALTER TABLE  `engine4_sescontest_contests` ADD  `transaction_id` INT(11) NOT NULL DEFAULT "0";');
  $db->query('ALTER TABLE  `engine4_sescontest_contests` ADD  `existing_package_order` INT(11) NOT NULL DEFAULT "0";');
  $db->query('ALTER TABLE  `engine4_sescontest_contests` ADD  `orderspackage_id` INT(11) NOT NULL DEFAULT "0";');

  include_once APPLICATION_PATH . "/application/modules/Sescontestpackage/controllers/defaultsettings.php";

  Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontestpackage.pluginactivated', 1);
  $error = 1;
}
