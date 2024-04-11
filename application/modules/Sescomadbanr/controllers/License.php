<?php


if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.pluginactivated')) {

  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
  
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescommunityads_admin_main_sescommunityadsbanner", "sescommunityads", "Banner Ads", "", \'{"route":"admin_default","module":"sescomadbanr","controller":"settings"}\', "sescommunityads_admin_main", "", 999),
  ("sescomadbanr_admin_main_settings", "sescomadbanr", "Global Settings", "", \'{"route":"admin_default","module":"sescomadbanr","controller":"settings"}\', "sescomadbanr_admin_main", "", 1);');
  
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescomadbanr_admin_main_bannersizes", "sescomadbanr", "Manage Banner Sizes", "", \'{"route":"admin_default","module":"sescomadbanr","controller":"manage","action":"index"}\', "sescomadbanr_admin_main", "", 2),
  ("sescomadbanr_admin_main_gustpaymanage", "sescomadbanr", "Manage User Payments", "", \'{"route":"admin_default","module":"sescomadbanr","controller":"manage-payment","action":"index"}\', "sescomadbanr_admin_main", "", 3);');

  $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
  ("sescomadbanr_paymentemail", "sescomadbanr", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[message]"),
  ("sescomadbanr_paymentreminder", "sescomadbanr", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[message]");');
  
  $db->query('DROP TABLE IF EXISTS `engine4_sescomadbanr_userpayments`;');
  $db->query('CREATE TABLE `engine4_sescomadbanr_userpayments` (
  `userpayment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `price` decimal(16,2) unsigned NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `sescommunityad_id` int(10) unsigned NOT NULL default "0",
  `status` tinyint(1) NOT NULL DEFAULT "1",
  PRIMARY KEY (`userpayment_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
  
  $db->query('DROP TABLE IF EXISTS `engine4_sescomadbanr_banners`;');
  $db->query('CREATE TABLE `engine4_sescomadbanr_banners` (
  `banner_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `banner_name` varchar(255) DEFAULT NULL,
  `height` INT(11) NOT NULL DEFAULT "0",
  `width` INT(11) NOT NULL DEFAULT "0",
  `enabled` tinyint(1) NOT NULL DEFAULT "1",
  PRIMARY KEY (`banner_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
  
  $db->query('INSERT IGNORE INTO `engine4_sescomadbanr_banners` (`banner_id`, `banner_name`, `height`, `width`, `enabled`) VALUES
  (1, "Banner - 1", 200, 200, 1);');

  include_once APPLICATION_PATH . "/application/modules/Sescomadbanr/controllers/defaultsettings.php";

  Engine_Api::_()->getApi('settings', 'core')->setSetting('sescomadbanr.pluginactivated', 1);
  $error = 1;
}
