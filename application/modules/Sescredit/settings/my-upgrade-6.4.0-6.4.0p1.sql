/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR(32) NOT NULL, CHANGE `name` `name` VARCHAR(32) NOT NULL;
ALTER TABLE `engine4_core_menuitems` CHANGE `menu` `menu` VARCHAR(64) NULL DEFAULT NULL;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescredit_main_managecashcredit", "sescredit", "Manage Cash Credits", "Sescredit_Plugin_Menus", '{"route":"sescredit_general","action":"payment-requests"}', "sescredit_main", "", 99),
("sescredit_admin_main_paymentrequest", "sescredit", "Manage Payments", "", '{"route":"admin_default","module":"sescredit","controller":"payment"}', "sescredit_admin_main", "", 99),
("sescredit_admin_main_paymentrequestsub", "sescredit", "Payment Requests", "", '{"route":"admin_default","module":"sescredit","controller":"payment"}', "sescredit_admin_main_paymentrequest", "", 1),
("sescredit_admin_main_managepaymenteventownersub", "sescredit", "Manage Payments Made", "", '{"route":"admin_default","module":"sescredit","controller":"payment","action":"manage-payment-owner"}', "sescredit_admin_main_paymentrequest", "", 2);

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
("sescredit_adminpayaprov", "sescredit", '{item:$subject} apporved your payment request for cash credits.', 0, "", 1),
("sescredit_adminpaycancl", "sescredit", '{item:$subject} cancel your payment request for cash credits.', 0, "", 1),
("sescredit_payrequest", "sescredit", '{item:$subject} request payment {var:$requestAmount} for cash credit.', 0, "", 1);

DROP TABLE IF EXISTS `engine4_sescredit_userpayrequests`;
CREATE TABLE IF NOT EXISTS `engine4_sescredit_userpayrequests` (
	`userpayrequest_id` INT(11) unsigned NOT NULL auto_increment,
	`owner_id` INT(11) unsigned NOT NULL,
	`credit_point` INT(11) unsigned NOT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `engine4_sescredit_usergateways`;
CREATE TABLE IF NOT EXISTS `engine4_sescredit_usergateways` (
  `usergateway_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(128) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT "0",
  `plugin` varchar(128) NOT NULL,
  `config` mediumblob,
  `test_mode` tinyint(1) unsigned NOT NULL DEFAULT "0",
  `gateway_type` varchar(64) NOT NULL,
  PRIMARY KEY (`usergateway_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
