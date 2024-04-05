<?php
//folder name or directory name.
$module_name = 'sestutorial';

//product title and module title.
$module_title = 'Multi-Use Tutorials Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sestutorial_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.pluginactivated')) {
    
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sestutorial_admin_main_managetutorial", "sestutorial", "Add & Manage Tutorials", "", \'{"route":"admin_default","module":"sestutorial","controller":"manage","action":"index"}\', "sestutorial_admin_main", "", 2),
      ("sestutorial_admin_main_categories", "sestutorial", "Categories", "", \'{"route":"admin_default","module":"sestutorial","controller":"categories","action":"index"}\', "sestutorial_admin_main", "", 3),
      ("sestutorial_admin_main_subcategories", "sestutorial", "Categories", "", \'{"route":"admin_default","module":"sestutorial","controller":"categories","action":"index"}\', "sestutorial_admin_categories", "", 1),
      ("sestutorial_admin_main_level", "sestutorial", "Member Level Settings", "", \'{"route":"admin_default","module":"sestutorial","controller":"level"}\', "sestutorial_admin_main", "", 4),
      ("sestutorial_admin_main_manageaskquestion", "sestutorial", "Manage Requested Tutorials", "", \'{"route":"admin_default","module":"sestutorial","controller":"manageaskquestion","action":"index"}\', "sestutorial_admin_main", "", 4),
      ("sestutorial_admin_main_managewidgetizepage", "sestutorial", "Manage Widgetized Pages", "", \'{"route":"admin_default","module":"sestutorial","controller":"settings", "action":"manage-widgetize-page"}\', "sestutorial_admin_main", "", 990),

      ("sestutorial_mini_sestutorial", "sestutorial", "Tutorials", "", \'{"route":"sestutorial_general","action":""}\', "core_mini", "", 999),
      ("sestutorial_main_sestutorial", "sestutorial", "Tutorials", "", \'{"route":"sestutorial_general","icon":"fas fa-layer-group","action":""}\', "core_main", "", 999),
      ("sestutorial_footer_sestutorial", "sestutorial", "Tutorials", "", \'{"route":"sestutorial_general","action":""}\', "core_footer", "", 999),
      ("sestutorial_main_home", "sestutorial", "Tutorial Home", "", \'{"route":"sestutorial_general","action":"home"}\', "sestutorial_main", "", 1),
      ("sestutorial_main_browse", "sestutorial", "Browse Tutorials", "", \'{"route":"sestutorial_general","action":"browse"}\', "sestutorial_main", "", 2),
      ("sestutorial_main_browsecategory", "sestutorial", "Categories", "", \'{"route":"sestutorial_category"}\', "sestutorial_main","", 5);');

      $db->query('INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
      ("SESTutorial_ASKQUESTION_EMAIL", "sestutorial", "[host],[email],[site_title],[description]"),
      ("SESTutorial_ASKANSWER_EMAIL", "sestutorial", "[host],[email],[site_title],[description],[questionreply]");');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorials`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorials` (
        `tutorial_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) NOT NULL,
        `title` varchar(255) NOT NULL,
        `description` longtext NOT NULL,
        `custom_url` varchar(255) NOT NULL,
        `category_id` int(11) NOT NULL DEFAULT "0",
        `subcat_id` int(11) NOT NULL DEFAULT "0",
        `subsubcat_id` int(11) NOT NULL DEFAULT "0",
        `photo_id` INT(11) DEFAULT NULL,
        `search` TINYINT(11) NOT NULL DEFAULT "1",
        `rating` float NOT NULL,
        `view_count` int(10) unsigned NOT NULL,
        `like_count` int(11) unsigned NOT NULL,
        `helpful_count` int(11) unsigned NOT NULL,
        `comment_count` int(11) unsigned NOT NULL,
        `memberlevels` longtext NOT NULL,
        `profile_types` varchar(255) DEFAULT NULL,
        `networks` longtext NOT NULL,
        `status` TINYINT(1) NOT NULL DEFAULT "1",
        `featured` TINYINT(1) NOT NULL DEFAULT "0",
        `sponsored` TINYINT(1) NOT NULL DEFAULT "0",
        `offtheday` tinyint(1) NOT NULL,
        `startdate` date DEFAULT NULL,
        `enddate` date DEFAULT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`tutorial_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_askquestions`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_askquestions` (
        `askquestion_id` int(11) unsigned NOT NULL auto_increment,
        `user_id` int(11) DEFAULT NULL,
        `category_id` int(11) NOT NULL DEFAULT "0",
        `subcat_id` int(11) NOT NULL DEFAULT "0",
        `subsubcat_id` int(11) NOT NULL DEFAULT "0",
        `name` varchar(255) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `description` text NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        `reply` TINYINT(1) NULL DEFAULT "0",
        PRIMARY KEY (`askquestion_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_categories` ;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_categories` (
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
        `profile_type_review` int(11) DEFAULT NULL,
        `profile_type` int(11) DEFAULT NULL,
        PRIMARY KEY (`category_id`),
        KEY `category_id` (`category_id`,`category_name`),
        KEY `category_name` (`category_name`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorial_fields_maps`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorial_fields_maps` (
        `field_id` int(11) NOT NULL,
        `option_id` int(11) NOT NULL,
        `child_id` int(11) NOT NULL,
        `order` smallint(6) NOT NULL,
        PRIMARY KEY (`field_id`,`option_id`,`child_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

      $db->query('INSERT IGNORE INTO `engine4_sestutorial_tutorial_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES (0, 0, 1, 1);');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorial_fields_meta`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorial_fields_meta` (
        `field_id` int(11) NOT NULL AUTO_INCREMENT,
        `type` varchar(24) NOT NULL,
        `label` varchar(64) NOT NULL,
        `description` varchar(255) NOT NULL DEFAULT "",
        `alias` varchar(32) NOT NULL DEFAULT "",
        `required` tinyint(1) NOT NULL DEFAULT "0",
        `display` tinyint(1) unsigned NOT NULL,
        `publish` tinyint(1) unsigned NOT NULL DEFAULT "0",
        `search` tinyint(1) unsigned NOT NULL DEFAULT "0",
        `show` tinyint(1) unsigned DEFAULT "0",
        `order` smallint(3) unsigned NOT NULL DEFAULT "999",
        `config` text NOT NULL,
        `validators` text COLLATE utf8mb4_unicode_ci,
        `filters` text COLLATE utf8mb4_unicode_ci,
        `style` text COLLATE utf8mb4_unicode_ci,
        `error` text COLLATE utf8mb4_unicode_ci,
        PRIMARY KEY (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1;');

      $db->query('INSERT IGNORE INTO `engine4_sestutorial_tutorial_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `show`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES (1, "profile_type", "Profile Type", "", "profile_type", 1, 0, 0, 2, 0, 999, "", NULL, NULL, NULL, NULL);');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorial_fields_options`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorial_fields_options` (
        `option_id` int(11) NOT NULL AUTO_INCREMENT,
        `field_id` int(11) NOT NULL,
        `label` varchar(255) NOT NULL,
        `order` smallint(6) NOT NULL DEFAULT "999",
        `type` tinyint(1) NOT NULL DEFAULT "0",
        PRIMARY KEY (`option_id`),
        KEY `field_id` (`field_id`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci AUTO_INCREMENT=1 ;');
      $db->query('INSERT IGNORE INTO `engine4_sestutorial_tutorial_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES (1, 1, "Rock Band", 0);');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorial_fields_search`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorial_fields_search` (
        `item_id` int(11) NOT NULL,
        `profile_type` smallint(11) unsigned DEFAULT NULL,
        PRIMARY KEY (`item_id`),
        KEY `profile_type` (`profile_type`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_tutorial_fields_values`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_tutorial_fields_values` (
        `item_id` int(11) NOT NULL,
        `field_id` int(11) NOT NULL,
        `index` smallint(3) NOT NULL DEFAULT "0",
        `value` text NOT NULL,
        PRIMARY KEY (`item_id`,`field_id`,`index`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_ratings`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_ratings` (
        `tutorial_id` int(10) unsigned NOT NULL,
        `user_id` int(9) unsigned NOT NULL,
        `rating` tinyint(1) unsigned default NULL,
        PRIMARY KEY  (`tutorial_id`,`user_id`),
        KEY `INDEX` (`tutorial_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;');

      $db->query('DROP TABLE IF EXISTS `engine4_sestutorial_helptutorials`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sestutorial_helptutorials` (
        `helptutorial_id` int(11) unsigned NOT NULL auto_increment,
        `tutorial_id` int(11) unsigned NOT NULL,
        `user_id` int(11) unsigned NOT NULL,
        `helpfultutorial` tinyint(1) unsigned DEFAULT NULL,
        `reason_id` int(1) DEFAULT "0",
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY (`helptutorial_id`),
        UNIQUE KEY `tutorial_id` (`tutorial_id`,`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      $cols = $db->describeTable('engine4_authorization_permissions');
      if($cols['type']['LENGTH'] < 32){
         $db->query('ALTER TABLE `engine4_authorization_permissions` CHANGE `type` `type` VARCHAR(32) NOT NULL;');
      }

      include_once APPLICATION_PATH . "/application/modules/Sestutorial/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sestutorial.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sestutorial.licensekey', $_POST['sestutorial_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
		$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.licensekey');
		$licensekey = @base64_encode($licensekey);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sestutorial.sesdomainauth', $domain_name);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sestutorial.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sestutorial.licensekey', $_POST['sestutorial_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
