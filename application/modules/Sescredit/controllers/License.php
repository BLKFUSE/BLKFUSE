<?php
//folder name or directory name.
$module_name = 'sescredit';

//product title and module title.
$module_title = 'Credits & Activity / Reward Points Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sescredit_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.pluginactivated')) {

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
        ("sescredit_main", "standard", "SNS - Credit - Main Navigation Menu");');

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
        ("sescredit_admin_main_managecredits", "sescredit", "Manage Credit Points", "", \'{"route":"admin_default","module":"sescredit","controller":"credits"}\', "sescredit_admin_main", "", 2),
        ("sescredit_admin_main_modulesettings", "sescredit", "Module Name Settings", "", \'{"route":"admin_default","module":"sescredit","controller":"credits","action":"settings"}\', "sescredit_admin_main", "", 3),
        ("sescredit_admin_main_level", "sescredit", "Member Level Settings", "", \'{"route":"admin_default","module":"sescredit","controller":"level"}\', "sescredit_admin_main", "", 4),
        ("sescredit_admin_main_badges", "sescredit", "Manage Badges", "", \'{"route":"admin_default","module":"sescredit","controller":"badges","action":"index"}\', "sescredit_admin_main", "", 5),
        ("sescredit_admin_main_badgegeneralsetting", "sescredit", "General Settings", "", \'{"route":"admin_default","module":"sescredit","controller":"badges"}\', "sescredit_admin_main_badges", "", 1),
        ("sescredit_admin_main_managebadges", "sescredit", "Manage Badge", "", \'{"route":"admin_default","module":"sescredit","controller":"badges","action":"manage"}\', "sescredit_admin_main_badges", "", 2),
        ("sescredit_admin_main_offer", "sescredit", "Credit Sale Offers", "", \'{"route":"admin_default","module":"sescredit","controller":"offers"}\', "sescredit_admin_main", "", 6),
        ("sescredit_admin_main_membercredit", "sescredit", "Earned Credits", "", \'{"route":"admin_default","module":"sescredit","controller":"credits","action":"member-points"}\', "sescredit_admin_main", "", 7),
        ("sescredit_admin_main_transaction", "sescredit", "Transactions", "", \'{"route":"admin_default","module":"sescredit","controller":"credits","action":"transactions"}\', "sescredit_admin_main", "", 8),
        ("sescredit_admin_main_upgrequest", "sescredit", "Manage Membership Upgrades", "", \'{"route":"admin_default","module":"sescredit","controller":"upgradelevel"}\', "sescredit_admin_main", "", 9),
        ("sescredit_admin_main_pointsetting", "sescredit", "Manage Points for Upgrades", "", \'{"route":"admin_default","module":"sescredit","controller":"upgradelevel"}\', "sescredit_admin_main_upgrequest", "", 1),
        ("sescredit_admin_main_managerequest", "sescredit", "Upgrade Requests", "", \'{"route":"admin_default","module":"sescredit","controller":"upgradelevel","action":"manage-request"}\', "sescredit_admin_main_upgrequest", "", 2),
        ("sescredit_admin_main_sendpoint", "sescredit", "Send Credit Points", "", \'{"route":"admin_default","module":"sescredit","controller":"credits", "action":"send-points"}\', "sescredit_admin_main", "", 10),
        ("sescredit_admin_main_managewidgetizepage", "sescredit", "Widgetized Pages", "", \'{"route":"admin_default","module":"sescredit","controller":"settings", "action":"manage-widgetize-page"}\', "sescredit_admin_main", "", 11),
        ("sescredit_admin_main_statstics", "sescredit", "Statstics", "", \'{"route":"admin_default","module":"sescredit","controller":"settings", "action":"statstics"}\', "sescredit_admin_main", "", 12),
        ("core_main_sescredit", "sescredit", "Credits", "", \'{"route":"sescredit_general"}\', "core_main", "", 999),
        ("sescredit_main_manage", "sescredit", "My Credits", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"manage"}\', "sescredit_main", "", 1),
        ("sescredit_main_transactions", "sescredit", "My Transactions", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"transaction"}\', "sescredit_main", "", 2),
        ("sescredit_main_earncredit", "sescredit", "Earn Credits", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"earn-credit"}\', "sescredit_main", "", 3),
        ("sescredit_main_help", "sescredit", "Help & Learn More", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"help"}\', "sescredit_main", "", 4),
        ("sescredit_main_badges", "sescredit", "Badges", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"badges"}\', "sescredit_main", "", 5),
        ("sescredit_main_leaderboard", "sescredit", "Leaderboard", "Sescredit_Plugin_Menus", \'{"route":"sescredit_general","action":"leaderboard"}\', "sescredit_main", "", 6);');

        $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
        ("sescredit_send_point", "sescredit", "[host],[email],[sender_title],[point],[object_link]"),
        ("sescredit_approve_upgrade_request", "sescredit", "[host],[email],[new_member_level],[owner_title]"),
        ("sescredit_reject_upgrade_request", "sescredit", "[host],[email],[new_member_level],[owner_title]"),
        ("sescredit_send_upgrade_request", "sescredit", "[host],[email],[new_member_level],[owner_title]"),
        ("sescredit_purchase_point", "sescredit", "[host],[email],[point],[owner_title]"),
        ("sescredit_send_by_site", "sescredit", "[host],[email],[point],[owner_title],[object_link],[message]"),
        ("sescredit_received_referral_point", "sescredit", "[host],[email],[point]"),
        ("sescredit_purchased_point_success", "sescredit", "[host],[email],[point]");');
        $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
        ("notify_sescredit_send_point", "sescredit", \'{item:$subject} has send you {var:$point} point {var:$creditPageLink}.\', 0, ""),
        ("notify_sescredit_approve_upgrade_request", "sescredit", \'Your member level upgrade request {item:$object} has been approved.\', 0, ""),
        ("notify_sescredit_reject_upgrade_request", "sescredit", \'Your member level upgrade request {item:$object} has been rejected.\', 0, ""),
        ("notif_sescredit_send_by_site", "sescredit", \'Site Admin has been sent you {item:$object} point.\', 0, "");');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_badges`;');
        $db->query('CREATE TABLE `engine4_sescredit_badges` (
        `badge_id` int(11) unsigned NOT NULL auto_increment,
        `title` varchar(128) NOT NULL,
        `description` varchar(128) NOT NULL,
        `photo_id` int(11) unsigned NOT NULL default "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `credit_value` int(11) unsigned NOT NULL default "0",
        `enabled` tinyint(1) NOT NULL default "1",
        PRIMARY KEY (`badge_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_userbadges`;');
        $db->query('CREATE TABLE `engine4_sescredit_userbadges` (
        `userbadge_id` int(11) unsigned NOT NULL auto_increment,
        `badge_id` int(11) unsigned NOT NULL,
        `user_id` int(11) unsigned NOT NULL,
        `active` tinyint(1) NOT NULL default "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`userbadge_id`),
        UNIQUE KEY (`badge_id`,`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_values`;');
        $db->query('CREATE TABLE `engine4_sescredit_values` (
        `value_id` int(11) unsigned NOT NULL auto_increment,
        `type` varchar(256) NOT NULL,
        `module` varchar(132) NOT NULL,
        `firstactivity` int(5) NOT NULL default "0",
        `nextactivity` int(5) NOT NULL default "0",
        `maxperday` int(5) NOT NULL default "0",
        `status` tinyint(1) NOT NULL default "0",
        `member_level` int(2) NOT NULL default "0",
        `deduction` int(5) NOT NULL default "0",
        `en` text  default NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`value_id`),
        KEY `type` (`type`, `member_level`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_credits`;');
        $db->query('CREATE TABLE `engine4_sescredit_credits` (
        `credit_id` int(11) unsigned NOT NULL auto_increment,
        `owner_id` int(11) NOT NULL,
        `type` varchar(256) NOT NULL,
        `object_id` int(11) NOT NULL default "0",
        `action_id` int(11) NOT NULL default "0",
        `credit` int(11) NOT NULL default "0",
        `attempt` varchar(32) NOT NULL,
        `point_type` varchar(32) NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`credit_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_details`;');
        $db->query('CREATE TABLE `engine4_sescredit_details` (
        `detail_id` int(11) unsigned NOT NULL auto_increment,
        `owner_id` int(11) NOT NULL,
        `total_credit` varchar(256) NOT NULL,
        `first_activity_date` datetime NOT NULL,
        `progress` tinyint(1) NOT NULL default "0",
        PRIMARY KEY (`detail_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_modulesettings`;');
        $db->query('CREATE TABLE `engine4_sescredit_modulesettings` (
        `modulesetting_id` int(11) unsigned NOT NULL auto_increment,
        `order_id` int(11) NOT NULL,
        `parent_id` varchar(256) default NULL,
        `module` varchar(132) NOT NULL,
        `title` varchar(256) default NULL,
        `status` tinyint(1) NOT NULL default "0",
        PRIMARY KEY (`modulesetting_id`),
        UNIQUE KEY (`module`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_affiliates`;');
        $db->query('CREATE TABLE `engine4_sescredit_affiliates` (
        `affiliate_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) NOT NULL,
        `affiliate` varchar(256) NOT NULL,
        PRIMARY KEY (`affiliate_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_offers`;');
        $db->query('CREATE TABLE `engine4_sescredit_offers` (
        `offer_id` int(11) unsigned NOT NULL auto_increment,
        `point_value` varchar(132) NOT NULL,
        `point` varchar(132) NOT NULL,
        `limit_offer` int(11) NOT NULL default "0",
        `user_avail` int(11) NOT NULL default "0",
        `offer_time` tinyint(1) NOT NULL default "0",
        `starttime` datetime default NULL,
        `endtime` datetime default NULL,
        `enable` tinyint(1) NOT NULL default "0",
        PRIMARY KEY (`offer_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_transactions`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescredit_transactions` (
        `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
        `owner_id` int(11) NOT NULL,
        `order_id` int(11) NOT NULL,
        `gateway_id` tinyint(1) DEFAULT NULL,
        `gateway_transaction_id` varchar(128) DEFAULT NULL,
        `gateway_parent_transaction_id` varchar(128) DEFAULT NULL,
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
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT = 1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_orderdetails`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescredit_orderdetails` (
        `orderdetail_id` int(11) NOT NULL AUTO_INCREMENT,
        `purchase_type` tinyint(1) NOT NULL default "0",
        `point` int(11) NOT NULL default "0",
        `offer_id` int(11) DEFAULT NULL,
        `owner_id` int(11) DEFAULT NULL,
        `transaction_id` int(11) NOT NULL default "0",
        PRIMARY KEY (`orderdetail_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_upgradeusers`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescredit_upgradeusers` (
        `upgradeuser_id` int(11) NOT NULL AUTO_INCREMENT,
        `owner_id` int(11) DEFAULT NULL,
        `level_id` int(11) DEFAULT NULL,
        `status` tinyint(1) NOT NULL default "0",
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`upgradeuser_id`),
        KEY (`owner_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');

        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_levelpoints`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescredit_levelpoints` (
        `levelpoint_id` int(11) NOT NULL AUTO_INCREMENT,
        `level_id` int(11) DEFAULT NULL,
        `point` int(11) NOT NULL default "0",
        PRIMARY KEY (`levelpoint_id`),
        UNIQUE KEY (`level_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        $db->query('DROP TABLE IF EXISTS `engine4_sescredit_rewardpoints`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_sescredit_rewardpoints` (
        `rewardpoint_id` int(11) NOT NULL AUTO_INCREMENT,
        `member_type` tinyint(1) NOT NULL default "0",
        `level_id` int(11) DEFAULT NULL,
        `user_id` int(11) DEFAULT NULL,
        `point` int(11) NOT NULL default "0",
        `reason` varchar(128) NOT NULL,
        `creation_date` datetime NOT NULL,
        PRIMARY KEY (`rewardpoint_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
        $db->query('INSERT IGNORE INTO `engine4_core_tasks` (`title`, `module`, `plugin`, `timeout`) VALUES
        ("Credits & Activity / Reward Points Validity Check", "sescredit", "Sescredit_Plugin_Task_Jobs", 60);');

        include_once APPLICATION_PATH . "/application/modules/Sescredit/controllers/defaultsettings.php";

        Engine_Api::_()->getApi('settings', 'core')->setSetting('sescredit.pluginactivated', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('sescredit.licensekey', $_POST['sescredit_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescredit.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescredit.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sescredit.licensekey', $_POST['sescredit_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
