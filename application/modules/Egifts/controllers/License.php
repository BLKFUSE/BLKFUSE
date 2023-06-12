<?php
//folder name or directory name.
$module_name = 'egifts';

//product title and module title.
$module_title = 'Virtual Gifts Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['egifts_licensekey']);
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

  //Here we can set some variable for checking in plugin files.
  if ($server_output == "OK" && $error != 1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.pluginactivated')) {

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();

        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
        ("egifts_admin_main_manageorder", "egifts", "Manage Order", "", \'{"route":"admin_default","module":"egifts","controller":"orders","action":"index"}\', "egifts_admin_main", "", 3),
        ("egifts_admin_main_managegift", "egifts", "Manage Gift", "", \'{"route":"admin_default","module":"egifts","controller":"managegift"}\', "egifts_admin_main", "", 1),
        ("egifts_main_browse", "egifts", "Gifts", "", \'{"route":"egifts_general","action":"browse","icon":"fas fa-gift"}\', "core_main", "", 5),
        ("egifts_admin_main_gateway", "egifts", "Manage Gateways", "",\'{"route":"admin_default","module":"payment","controller":"gateway","target":"_blank"}\', "egifts_admin_main", "", 5),
        ("egifts_main_menubrowse", "egifts", "Browse Gifts", "", \'{"route":"egifts_general","action":"browse"}\', "egifts_main", "", 1),
        ("egifts_main_mygifts", "egifts", "My Gifts", "", \'{"route":"egifts_general","action":"my-gifts"}\', "egifts_main", "", 2);');
        
        $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("egifts_main_mypurchasedorders", "egifts", "My Orders", "", \'{"route":"egifts_general","action":"my-orders"}\', "egifts_main", "", 3);');
        
        $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
        ("egifts_main", "standard", "SNS - Gifts");');
        $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
        ("egift_send_gift", "egifts", \'{item:$subject} has sent you a Gift.\', 0, ""),
        ("egift_made_payment", "egifts", \'{item:$subject} has made payment for gift {var:$giftitle}.\', 0, "");');
        $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
        ("egift_send_gift", "egifts", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[gift_title],[sender_title],[object_link]"),
        ("egift_made_payment", "egifts", "[host],[email],[recipient_title],[recipient_link],[recipient_photo],[gift_title],[sender_title],[object_link]");');
        $db->query('DROP TABLE IF EXISTS `engine4_egifts_gifts`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_egifts_gifts` (
          `gift_id` int(11) NOT NULL,
          `title` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
          `description` text DEFAULT NULL,
          `quantity` int(11) NOT NULL DEFAULT 0,
          `icon_id` int(11) DEFAULT NULL,
          `price` decimal(10,2) DEFAULT NULL,
          `created_date` datetime DEFAULT NULL,
          `created_by` int(11) DEFAULT NULL,
          `owner_id` int(11) NOT NULL DEFAULT 0,
          `view_count` int(11) NOT NULL DEFAULT 0,
          `like_count` int(11) NOT NULL DEFAULT 0,
          `favourite_count` int(11) NOT NULL DEFAULT "0",
          `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT "1->active, 2->delete"
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
        $db->query('DROP TABLE IF EXISTS `engine4_egifts_favourites`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_egifts_favourites` (
            `favourite_id` int(11) unsigned NOT NULL auto_increment,
            `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
            `resource_id` int(11) unsigned NOT NULL,
            `owner_id` int(11) unsigned NOT NULL,
            `creation_date` datetime NOT NULL,
            PRIMARY KEY  (`favourite_id`),
            KEY `resource_type` (`resource_type`, `resource_id`),
            KEY `owner_id` (`owner_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');
        $db->query('ALTER TABLE `engine4_egifts_gifts` ADD PRIMARY KEY (`gift_id`), ADD KEY `icon_id` (`icon_id`,`status`);');
        $db->query('ALTER TABLE `engine4_egifts_gifts` MODIFY `gift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
        $db->query('DROP TABLE IF EXISTS `engine4_egifts_giftpurchases`;');
        $db->query('CREATE TABLE IF NOT EXISTS `engine4_egifts_giftpurchases` (
          `giftpurchase_id` int(11) NOT NULL,
          `owner_id` int(11) DEFAULT NULL,
          `message` text DEFAULT NULL,
          `purchase_user_id` int(11) DEFAULT NULL,
          `is_private` SMALLINT(4) NOT NULL DEFAULT "0",
          `total_amount` int(11) DEFAULT NULL,
          `status` tinyint(4) NOT NULL DEFAULT 1,
          `created_date` datetime DEFAULT NULL,
          `gateway_transaction_id` varchar(50) DEFAULT NULL,
          `transcation_status` tinyint(4) NOT NULL DEFAULT 0,
          `transcation_date` datetime DEFAULT NULL,
          `state` varchar(100) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $db->query('DROP TABLE IF EXISTS `engine4_egifts_giftorders`;');
        $db->query('CREATE TABLE `engine4_egifts_giftorders` (
          `giftorder_id` int(11) NOT NULL AUTO_INCREMENT,
          `owner_id` int(11) DEFAULT NULL,
          `giftpurchase_id` text DEFAULT NULL,
          `gift_id` int(11) DEFAULT NULL,
          `gift_title` varchar(100) DEFAULT NULL,
          `gift_icon_id` int(11) DEFAULT NULL,
          `status` tinyint(4) NOT NULL DEFAULT 1,
          `gift_price` varchar(20) DEFAULT NULL,
          PRIMARY KEY (`giftorder_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
        $db->query('ALTER TABLE `engine4_egifts_giftpurchases` ADD PRIMARY KEY (`giftpurchase_id`), ADD KEY `owner_id` (`owner_id`,`purchase_user_id`,`status`,`transcation_status`);');
        $db->query('ALTER TABLE `engine4_egifts_giftpurchases` MODIFY `giftpurchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
        $db->query('DROP TABLE IF EXISTS `engine4_egifts_recentlyviewitems`;');
        $db->query('CREATE TABLE IF NOT EXISTS  `engine4_egifts_recentlyviewitems` (
          `recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          `resource_id` INT NOT NULL ,
          `resource_type` VARCHAR(64) NOT NULL,
          `owner_id` INT NOT NULL ,
          `creation_date` DATETIME NOT NULL,
          UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`, `owner_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
        $db->query('ALTER TABLE `engine4_egifts_giftpurchases` ADD `credit_point` INT(11) NOT NULL DEFAULT "0", ADD `credit_value` FLOAT NOT NULL DEFAULT "0";');
        $db->query('ALTER TABLE `engine4_egifts_giftpurchases` ADD `ordercoupon_id` INT NULL DEFAULT "0";');

        include_once APPLICATION_PATH . "/application/modules/Egifts/controllers/defaultsettings.php";
        
        Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.pluginactivated', 1);
        Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.licensekey', $_POST['egifts_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.sesdomainauth', '');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.seslkeyauth', '');
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egifts.licensekey', $_POST['egifts_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
