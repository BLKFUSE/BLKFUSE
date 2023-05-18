<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: License.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

//folder name or directory name.
$module_name = 'epaidcontent';

//product title and module title.
$module_title = 'Paid User Content Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['epaidcontent_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('ALTER TABLE `engine4_activity_actions` ADD `package_id` INT(11) NOT NULL DEFAULT "0";');
      $db->query('ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;');
      $db->query('ALTER TABLE `engine4_authorization_permissions` CHANGE `name` `name` VARCHAR(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;');
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("epaidcontent_admin_main_settingsmemberlevel", "epaidcontent", "Member Level Settings", "", \'{"route":"admin_default","module":"epaidcontent","controller":"settings","action":"level"}\', "epaidcontent_admin_main", "", 2),
      ("epaidcontent_admin_main_manageorders", "epaidcontent", "Manage Orders", "", \'{"route":"admin_default","module":"epaidcontent","controller":"settings","action":"orders"}\', "epaidcontent_admin_main", "", 3),
      ("epaidcontent_admin_main_paymentrequest", "epaidcontent", "Manage Payments", "", \'{"route":"admin_default","module":"epaidcontent","controller":"payment"}\', "epaidcontent_admin_main", "", 4),
      ("epaidcontent_admin_main_paymentrequestsub", "epaidcontent", "Payment Requests", "", \'{"route":"admin_default","module":"epaidcontent","controller":"payment"}\', "epaidcontent_admin_main_paymentrequest", "", 1),
      ("epaidcontent_admin_main_managepaymenteventownersub", "epaidcontent", "Manage Payments Made", "", \'{"route":"admin_default","module":"epaidcontent","controller":"settings","action":"manage-payment-owner"}\', "epaidcontent_admin_main_paymentrequest", "", 2),
      ("epaidcontent_admin_main_gateway", "epaidcontent", "Manage Gateways", "", \'{"route":"admin_default","module":"payment","controller":"gateway","target":"_blank"}\', "epaidcontent_admin_main", "", 6),
      ("epaidcontent_admin_main_managewidgetizepage", "epaidcontent", "Widgetized Pages", "", \'{"route":"admin_default","module":"epaidcontent","controller":"settings", "action":"manage-widgetize-page"}\', "epaidcontent_admin_main", "", 90);');
      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("epaidcontent_main", "standard", "SNS - Paid Content Main Navigation Menu");');
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_epaidcontent", "epaidcontent", "Paid Content", "", \'{"route":"epaidcontent_general","action":"manage-packages","icon":"fas fa-money-bill"}\', "core_main", "", 4),
      ("epaidcontent_main_managepakg", "epaidcontent", "Manage Packages", "", \'{"route":"epaidcontent_general", "action":"manage-packages"}\', "epaidcontent_main", "", 1),
      ("epaidcontent_main_accdetails", "epaidcontent", "Account Details", "", \'{"route":"epaidcontent_general", "action":"account-details"}\', "epaidcontent_main", "", 2),
      ("epaidcontent_main_manageorder", "epaidcontent", "Manage Orders", "", \'{"route":"epaidcontent_general", "action":"manage-orders"}\', "epaidcontent_main", "", 3),
      ("epaidcontent_main_myorders", "epaidcontent", "My Purchased Orders", "", \'{"route":"epaidcontent_general","action":"my-orders"}\', "epaidcontent_main", "", 4),
      ("epaidcontent_main_salestats", "epaidcontent", "Sales Statistics", "", \'{"route":"epaidcontent_general", "action":"sales-stats"}\', "epaidcontent_main", "", 5),
      ("epaidcontent_main_salereport", "epaidcontent", "Sales Reports", "", \'{"route":"epaidcontent_general", "action":"sales-reports"}\', "epaidcontent_main", "", 6),
      ("epaidcontent_main_payreq", "epaidcontent", "Payment Requests", "", \'{"route":"epaidcontent_general", "action":"payment-requests"}\', "epaidcontent_main", "", 7),
      ("epaidcontent_main_paytra", "epaidcontent", "Payment Transactions", "", \'{"route":"epaidcontent_general", "action":"payment-transaction"}\', "epaidcontent_main", "", 8);');
      
      $db->query('DROP TABLE IF EXISTS `engine4_epaidcontent_packages`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_epaidcontent_packages` (
        `package_id` int(10) unsigned NOT NULL auto_increment,
        `title` varchar(128) NOT NULL,
        `description` text NOT NULL,
        `user_id` INT(11) NOT NULL,
        `price` decimal(16,2) unsigned NOT NULL,
        `recurrence` int(11) unsigned NOT NULL,
        `recurrence_type` enum("day","week","month","year","forever") NOT NULL,
        `duration` int(11) unsigned NOT NULL,
        `duration_type` enum("day","week","month","year","forever") NOT NULL,
        `enabled` tinyint(1) unsigned NOT NULL default "1",
        `modules` VARCHAR(255) NULL DEFAULT NULL,
        PRIMARY KEY  (`package_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS `engine4_epaidcontent_orders`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_epaidcontent_orders` (
        `order_id` int(11) unsigned NOT NULL auto_increment,
        `package_id` int(11) unsigned NOT NULL,
        `package_owner_id` INT(11) NOT NULL,
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
        `credit_point` INT(11) NOT NULL DEFAULT "0",
        `credit_value` FLOAT NOT NULL DEFAULT "0",
        `ordercoupon_id` INT NULL DEFAULT "0",
        PRIMARY KEY (`order_id`),
        KEY `package_id` (`package_id`),
        KEY `owner_id` (`owner_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_epaidcontent_userpayrequests`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_epaidcontent_userpayrequests` (
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
        PRIMARY KEY (`userpayrequest_id`),
        KEY `owner_id` (`owner_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('DROP TABLE IF EXISTS `engine4_epaidcontent_remainingpayments`;');
      $db->query('CREATE TABLE `engine4_epaidcontent_remainingpayments` (
        `remainingpayment_id` int(11) UNSIGNED NOT NULL auto_increment,
        `user_id` int(11) UNSIGNED NOT NULL,
        `remaining_payment` float DEFAULT "0",
        PRIMARY KEY (`remainingpayment_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      $db->query('DROP TABLE IF EXISTS engine4_epaidcontent_usergateways;');
      $db->query('CREATE TABLE `engine4_epaidcontent_usergateways` (
        `usergateway_id` int(11) UNSIGNED NOT NULL auto_increment,
        `user_id` int(11) UNSIGNED NOT NULL,
        `title` varchar(244) NOT NULL,
        `description` text,
        `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT "0",
        `plugin` varchar(128) NOT NULL,
        `config` mediumblob,
        `test_mode` tinyint(1) UNSIGNED NOT NULL DEFAULT "0",
        `gateway_type` VARCHAR(64) NULL DEFAULT "paypal",
        PRIMARY KEY (`usergateway_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
      ("epaidcontent_adminpayaprov", "epaidcontent", \'{item:$subject} apporved your payment request for paid content packages.\', 0, "", 1),
      ("epaidcontent_adminpaycancl", "epaidcontent", \'{item:$subject} cancel your payment request for paid content packages.\', 0, "", 1),
      ("epaidcontent_payrequest", "epaidcontent", \'{item:$subject} request payment {var:$requestAmount} for paid content packages.\', 0, "", 1);');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("epaidcontent_order", "epaidcontent", \'{item:$subject} has paid you for your paid content {var:$package_name}.\', 0, "");');
      
      $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
      ("epaidcontent_orderinvoice_buyer", "epaidcontent", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[paidcontent_title],[object_link],[invoice_body]"),
      ("epaidcontent_orderpurchased_paidcontentowner", "epaidcontent", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[package_title],[object_link],[buyer_name]"),
      ("epaidcontent_payment_order_pending", "epaidcontent", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[package_title],[package_description],[object_link]"),
      ("epaidcontent_payment_adminrequestapproved", "epaidcontent", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[paidcontent_title],[object_link]"),
      ("epaidcontent_entrypayment_requestadmin", "epaidcontent", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[paidcontent_title],[object_link],[buyer_name]");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
			SELECT
				level_id as `level_id`,
				"epaidcontent_package" as `type`,
				"auth_view" as `name`,
				5 as `value`,
				\'["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]\' as `params`
			FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
			SELECT
				level_id as `level_id`,
				"epaidcontent_package" as `type`,
				"view" as `name`,
				2 as `value`,
				NULL as `params`
			FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
			SELECT
				level_id as `level_id`,
				"epaidcontent_package" as `type`,
				"view" as `name`,
				1 as `value`,
				NULL as `params`
			FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
			SELECT
				level_id as `level_id`,
				"epaidcontent_package" as `type`,
				"view" as `name`,
				1 as `value`,
				NULL as `params`
			FROM `engine4_authorization_levels` WHERE `type` IN("public");');

      include_once APPLICATION_PATH . "/application/modules/Epaidcontent/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('epaidcontent.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('epaidcontent.licensekey', $_POST['epaidcontent_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('epaidcontent.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('epaidcontent.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('epaidcontent.licensekey', $_POST['epaidcontent_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
