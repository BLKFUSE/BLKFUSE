<?php
//folder name or directory name.
$module_name = 'sesthought';

//product title and module title.
$module_title = 'Thoughts Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sesthought_licensekey']);
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
  
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.pluginactivated')) {
    
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sesthought_admin_main_manage", "sesthought", "Manage Thoughts", "", \'{"route":"admin_default","module":"sesthought","controller":"manage"}\', "sesthought_admin_main", "", 2),
      ("sesthought_admin_main_categories", "sesthought", "Categories", "", \'{"route":"admin_default","module":"sesthought","controller":"categories","action":"index"}\', "sesthought_admin_main", "", 3),
      ("sesthought_admin_main_level", "sesthought", "Member Level Settings", "", \'{"route":"admin_default","module":"sesthought","controller":"level"}\', "sesthought_admin_main", "", 4),
      ("sesthought_admin_main_managepages", "sesthought", "Widgetized Pages", "", \'{"route":"admin_default","module":"sesthought","controller":"settings", "action":"manage-widgetize-page"}\', "sesthought_admin_main", "", 999);');

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_sesthought", "sesthought", "Thoughts", "", \'{"route":"sesthought_general","icon":"fa-pencil"}\', "core_main", "", 4),
      ("sesthought_main_browse", "sesthought", "Browse Thoughts", "", \'{"route":"sesthought_general"}\', "sesthought_main","", 1),
      ("sesthought_main_manage", "sesthought", "My Thoughts", "", \'{"route":"sesthought_general","action":"manage"}\', "sesthought_main", "", 2),
      ("sesthought_main_browsecategory", "sesthought", "Browse Categories", "", \'{"route":"sesthought_category"}\', "sesthought_main","", 8);');

      $db->query('DROP TABLE IF EXISTS `engine4_sesthought_categories` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesthought_categories` (
        `category_id` int(11) unsigned NOT NULL auto_increment,
        `slug` varchar(255) NOT NULL,
        `category_name` varchar(128) NOT NULL,
        `subcat_id` int(11)  NULL DEFAULT "0",
        `subsubcat_id` int(11)  NULL DEFAULT "0",
        `title` varchar(255) DEFAULT NULL,
        `description` text ,
        `color` VARCHAR(255) ,
        `thumbnail` int(11) NOT NULL DEFAULT "0",
        `cat_icon` int(11) NOT NULL DEFAULT "0",
        `colored_icon` int(11) NOT NULL DEFAULT "0",
        `order` int(11) NOT NULL DEFAULT "0",
        `profile_type_review` int(11) DEFAULT NULL,
        `profile_type` int(11) DEFAULT NULL,
        PRIMARY KEY (`category_id`),
        KEY `category_id` (`category_id`,`category_name`),
        KEY `category_name` (`category_name`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');

      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("sesthought_main", "standard", "SNS - Thoughts Main Navigation Menu");');

      $db->query('DROP TABLE IF EXISTS `engine4_sesthought_thoughts`;');
      $db->query('CREATE TABLE `engine4_sesthought_thoughts` (
        `thought_id` int(11) unsigned NOT NULL auto_increment,
        `title` TEXT NOT NULL,
        `photo_id` INT(11) NOT NULL DEFAULT "0",
        `category_id` INT(11) NULL DEFAULT "0",
        `subcat_id` INT(11) NULL DEFAULT "0",
        `subsubcat_id` INT(11) NULL DEFAULT "0",
        `source` LONGTEXT NULL,
        `owner_type` varchar(64) NOT NULL,
        `owner_id` int(11) unsigned NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `view_count` int(11) unsigned NOT NULL default "0",
        `comment_count` int(11) unsigned NOT NULL default "0",
        `like_count` int(11) unsigned NOT NULL default "0",
        `action_id` INT(11) NOT NULL DEFAULT "0",
        PRIMARY KEY (`thought_id`),
        KEY `owner_type` (`owner_type`, `owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("sesthought_new", "sesthought", \'{item:$subject} wrote a new thought entry:\', 1, 5, 1, 3, 1, 1);');
      $db->query('DROP TABLE IF EXISTS `engine4_sesthought_recentlyviewitems`;');
      $db->query('CREATE TABLE IF NOT EXISTS  `engine4_sesthought_recentlyviewitems` (
        `recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `resource_id` INT NOT NULL ,
        `resource_type` VARCHAR( 65 ) NOT NULL DEFAULT "sesthought_thought",
        `owner_id` INT NOT NULL ,
        `creation_date` DATETIME NOT NULL,
        UNIQUE KEY `uniqueKey` (`resource_id`,`resource_type`, `owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      $db->query('ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;');

      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "sesthought_thought" as `type`,
        "auth_view" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
        level_id as `level_id`,
        "sesthought_thought" as `type`,
        "auth_comment" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');

      include_once APPLICATION_PATH . "/application/modules/Sesthought/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesthought.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sesthought.licensekey', $_POST['sesthought_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
		$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.licensekey');
		$licensekey = @base64_encode($licensekey);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sesthought.sesdomainauth', $domain_name);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sesthought.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sesthought.licensekey', $_POST['sesthought_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
