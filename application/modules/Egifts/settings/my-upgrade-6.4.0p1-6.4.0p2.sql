ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL, CHANGE `name` `name` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `engine4_core_menuitems` CHANGE `menu` `menu` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_general_ci NULL DEFAULT NULL;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES 
("egifts_admin_main_level", "egifts", "Member Level Settings", "", '{"route":"admin_default","module":"egifts","controller":"level"}', "egifts_admin_main", "", 4),
("egifts_main_managecashgifts", "egifts", "Manage Cash Gifts", "Egifts_Plugin_Menus", '{"route":"egifts_general","action":"payment-requests"}', "egifts_main", "", 99),
("egifts_admin_main_paymentrequest", "egifts", "Manage Payments", "", '{"route":"admin_default","module":"egifts","controller":"payment"}', "egifts_admin_main", "", 99),
("egifts_admin_main_paymentrequestsub", "egifts", "Payment Requests", "", '{"route":"admin_default","module":"egifts","controller":"payment"}', "egifts_admin_main_paymentrequest", "", 1),
("egifts_admin_main_managepaymenteventownersub", "egifts", "Manage Payments Made", "", '{"route":"admin_default","module":"egifts","controller":"payment","action":"manage-payment-owner"}', "egifts_admin_main_paymentrequest", "", 2);

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES ("egifts_adminpayaprov", "egifts", '{item:$subject} apporved your payment request for cash gifts.', 0, "", 1),
("egifts_adminpaycancl", "egifts", '{item:$subject} cancel your payment request for cash gifts.', 0, "", 1),
("egifts_payrequest", "egifts", '{item:$subject} request payment {var:$requestAmount} for cash gifts.', 0, "", 1);

DROP TABLE IF EXISTS `engine4_egifts_remainingpayments`;
CREATE TABLE IF NOT EXISTS `engine4_egifts_remainingpayments` (
	`remainingpayment_id` INT(11) unsigned NOT NULL auto_increment,
	`user_id` INT(11) unsigned NOT NULL,
	`remaining_payment` FLOAT DEFAULT 0,
	PRIMARY KEY (`remainingpayment_id`),
	KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `engine4_egifts_userpayrequests`;
CREATE TABLE IF NOT EXISTS `engine4_egifts_userpayrequests` (
	`userpayrequest_id` INT(11) unsigned NOT NULL auto_increment,
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
	`total_commission_amount` FLOAT NULL DEFAULT "0",
	PRIMARY KEY (`userpayrequest_id`),
	KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `engine4_egifts_usergateways`;
CREATE TABLE IF NOT EXISTS `engine4_egifts_usergateways` (
  `usergateway_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT "0",
  `plugin` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `config` mediumblob,
  `test_mode` tinyint(1) unsigned NOT NULL DEFAULT "0",
  `gateway_type` varchar(64) NOT NULL,
  PRIMARY KEY (`usergateway_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
