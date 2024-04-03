<?php

if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.pluginactivated')) {

  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
  
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescontestjoinfees_admin_main", "sescontestjoinfees", "Contest Joining Fees", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"settings", "action":"extension"}\', "sescontest_admin_main", "", 995),
  ("sescontestjoinfees_admin_main_settings", "sescontestjoinfees", "Global Settings", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"settings","action":"extension"}\', "sescontestjoinfees_admin_main", "", 1);');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestjoinfees_orders`;');
  $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontestjoinfees_orders` (
    `order_id` int(11) unsigned NOT NULL auto_increment,
    `contest_id` int(11) unsigned NOT NULL,
    `entry_id` INT(11) NOT NULL DEFAULT "0",
    `owner_id` int(11) unsigned NOT NULL,
    `gateway_id` varchar(128) DEFAULT NULL,
    `order_no` varchar(255) DEFAULT NULL,
    `gateway_transaction_id` varchar(128) DEFAULT NULL,
    `commission_amount` float DEFAULT 0,
    `private` TINYINT(1) DEFAULT "0",
    `state` ENUM("pending","cancelled","failed","incomplete","complete","refund") DEFAULT "incomplete",
    `change_rate` float	DEFAULT "0.00",
    `total_amount` float DEFAULT "0.00",
    `currency_symbol` VARCHAR(45) NOT NULL,
    `gateway_type` VARCHAR(45) NOT NULL DEFAULT "Paypal",
    `is_delete` TINYINT(1) NOT NULL DEFAULT "0",
    `ip_address` varchar(55) NOT NULL DEFAULT "0.0.0.0",
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY (`order_id`),
    KEY `contest_id` (`contest_id`),
    KEY `owner_id` (`owner_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestjoinfees_userpayrequests`;');
  $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescontestjoinfees_userpayrequests` (
    `userpayrequest_id` INT(11) unsigned NOT NULL auto_increment,
    `contest_id` INT(11) unsigned NOT NULL,
    `owner_id` INT(11) unsigned NOT NULL,
    `requested_amount` FLOAT DEFAULT "0",
    `release_amount` FLOAT DEFAULT "0",
    `user_message` TEXT,
    `admin_message` TEXT,
    `creation_date` datetime NOT NULL,
    `release_date` datetime NOT NULL,
    `is_delete` TINYINT(1) NOT NULL DEFAULT "0",
    `gateway_id` TINYINT (1) DEFAULT "2",
    `gateway_transaction_id` varchar(128) DEFAULT NULL,
    `state` ENUM("pending","cancelled","failed","incomplete","complete","refund") DEFAULT "pending",
    `currency_symbol` VARCHAR(45) NOT NULL,
    `gateway_type` VARCHAR(45) NOT NULL DEFAULT "Paypal",
    PRIMARY KEY (`userpayrequest_id`),
    KEY `contest_id` (`contest_id`),
    KEY `owner_id` (`owner_id`)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestjoinfees_remainingpayments`;');
  $db->query('CREATE TABLE `engine4_sescontestjoinfees_remainingpayments` (
    `remainingpayment_id` int(11) UNSIGNED NOT NULL auto_increment,
    `contest_id` int(11) UNSIGNED NOT NULL,
    `remaining_payment` float DEFAULT "0",
    PRIMARY KEY (`remainingpayment_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescontestjoinfees_admin_main_settingsmemberlevel", "sescontestjoinfees", "Member Level Settings", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"settings","action":"level"}\', "sescontestjoinfees_admin_main", "", 2),
  ("sescontestjoinfees_admin_main_manageorders", "sescontestjoinfees", "Manage Orders", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"settings","action":"orders"}\', "sescontestjoinfees_admin_main", "", 3),
  ("sescontestjoinfees_admin_main_paymentrequest", "sescontestjoinfees", "Manage Payments", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"payment"}\', "sescontestjoinfees_admin_main", "", 4),
  ("sescontestjoinfees_admin_main_paymentrequestsub", "sescontestjoinfees", "Payment Requests", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"payment"}\', "sescontestjoinfees_admin_main_paymentrequest", "", 1),
  ("sescontestjoinfees_admin_main_managepaymenteventownersub", "sescontestjoinfees", "Manage Payments Made", "", \'{"route":"admin_default","module":"sescontestjoinfees","controller":"settings","action":"manage-payment-event-owner"}\', "sescontestjoinfees_admin_main_paymentrequest", "", 2),
  ("sescontestjoinfees_admin_main_gateway", "sescontestjoinfees", "Manage Gateways", "", \'{"route":"admin_default","module":"payment","controller":"gateway","target":"_blank"}\', "sescontestjoinfees_admin_main", "", 6);');

  $db->query('ALTER TABLE `engine4_sescontest_contests` ADD `entry_fees` DECIMAL(7,2) NOT NULL DEFAULT "0.0";');
  $db->query('ALTER TABLE `engine4_sescontest_contests` ADD `currency` VARCHAR(45) NULL;');

  $db->query('INSERT IGNORE INTO `engine4_sescontest_dashboards` (`type`, `title`, `enabled`, `main`) VALUES
  ("entry_fees_paid_sespaidext", "Manage Fees & Orders", "1", "0"),
  ("create_feed_sespaidext", "Entry Fees", "1", "0"),
  ("account_details_sespaidext", "Account Details", "1", "0"),
  ("sales_statistics_sespaidext", "Sales Statistics", "1", "0"),
  ("manage_orders_sespaidext", "Manage Orders", "1", "0"),
  ("sales_orders_sespaidext", "Sales Reports", "1", "0"),
  ("payment_requests_sespaidext", "Payment Requests", "1", "0"),
  ("payment_transactions_sespaidext", "Payment Transactions", "1", "0");');

  $db->query('DROP TABLE IF EXISTS engine4_sescontestjoinfees_usergateways;');
  $db->query('CREATE TABLE `engine4_sescontestjoinfees_usergateways` (
    `usergateway_id` int(11) UNSIGNED NOT NULL auto_increment,
    `contest_id` int(11) UNSIGNED NOT NULL,
    `user_id` int(11) UNSIGNED NOT NULL,
    `title` varchar(244) COLLATE utf8_unicode_ci NOT NULL,
    `description` text COLLATE utf8_unicode_ci,
    `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT "0",
    `plugin` varchar(128) NOT NULL,
    `config` mediumblob,
    `test_mode` tinyint(1) UNSIGNED NOT NULL DEFAULT "0",
    PRIMARY KEY (`usergateway_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');

  $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
  ("sescontestjoinfees_adminpayaprov", "sescontestjoinfees", \'{item:$subject} apporved your payment request for contest {item:$object}.\', 0, "", 1),
  ("sescontestjoinfees_adminpaycancl", "sescontestjoinfees", \'{item:$subject} cancel your payment request for contest {item:$object}.\', 0, "", 1),
  ("sescontestjoinfees_payrequest", "sescontestjoinfees", \'{item:$subject} request payment {var:$requestAmount} for contest {item:$object}.\', 0, "", 1);');

  $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
  ("sescontestjoinfees_orderinvoice_buyer", "sescontestjoinfees", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[contest_title],[object_link],[invoice_body]"),
  ("sescontestjoinfees_orderpurchased_contestowner", "sescontestjoinfees", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[contest_title],[object_link],[buyer_name]"),
  ("sescontestjoinfees_payment_order_pending", "sescontestjoinfees", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[contest_title],[contest_description],[object_link]"),
  ("sescontestjoinfees_payment_adminrequestapproved", "sescontestjoinfees", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[contest_title],[object_link]"),
  ("sescontestjoinfees_entrypayment_requestadmin", "sescontestjoinfees", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[contest_title],[object_link],[buyer_name]");');

  include_once APPLICATION_PATH . "/application/modules/Sescontestjoinfees/controllers/defaultsettings.php";

  Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontestjoinfees.pluginactivated', 1);
  $error = 1;
}
