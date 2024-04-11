<?php
//folder name or directory name.
$module_name = 'sessociallogin';

//product title and module title.
$module_title = 'Social Media Login - 1 Click Social Connect Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['sessociallogin_licensekey']);
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
  if (1) {

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sessociallogin.pluginactivated')) {
    
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("sessociallogin_admin_main_instagram", "sessociallogin", "Instagram", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"instagram"}\', "sessociallogin_admin_main", "", 7),
      ("sessociallogin_admin_main_linkedin", "sessociallogin", "LinkedIn", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"linkedin"}\', "sessociallogin_admin_main", "", 3),
      -- ("sessociallogin_admin_main_yahoo", "sessociallogin", "Yahoo", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"yahoo"}\', "sessociallogin_admin_main", "", 9),
      ("sessociallogin_admin_main_google", "sessociallogin", "Google", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"google"}\', "sessociallogin_admin_main", "", 4),
      ("sessociallogin_admin_main_flickr", "sessociallogin", "Flickr", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"flickr"}\', "sessociallogin_admin_main", "", 10),
      ("sessociallogin_admin_main_vk", "sessociallogin", "Vkontakte", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"vk"}\', "sessociallogin_admin_main", "", 6),
      ("sessociallogin_admin_main_facebook", "sessociallogin", "Facebook", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"facebook"}\', "sessociallogin_admin_main", "", 2),
      ("sessociallogin_admin_main_twitter", "sessociallogin", "Twitter", "", \'{"route":"admin_default","module":"user","controller":"settings","action":"twitter", "target":"_blank"}\', "sessociallogin_admin_main", "", 11),
      ("sessociallogin_admin_main_statistic", "sessociallogin", "Statistics", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"statistic"}\', "sessociallogin_admin_main", "", 12);');
      
      //("sessociallogin_admin_main_hotmail", "sessociallogin", "Hotmail", "", \'{"route":"admin_default","module":"sessociallogin","controller":"settings","action":"hotmail"}\', "sessociallogin_admin_main", "", 5),
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_linkedin` (
        `user_id` int(11) UNSIGNED NOT NULL auto_increment,
        `linkedin_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
        PRIMARY KEY (`user_id`),
        UNIQUE KEY `linkedin_uid` (`linkedin_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_vk` (
        `user_id` int(11) UNSIGNED NOT NULL auto_increment,
        `vk_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
        PRIMARY KEY (`user_id`),
        UNIQUE KEY `vk_uid` (`vk_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_flickr` (
        `flickr_id` int(11) UNSIGNED NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `flickr_uid` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
        PRIMARY KEY (`flickr_id`),
        UNIQUE KEY `flickr_uid` (`flickr_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_instagram` (
        `instagram_id` int(11) NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `instagram_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
        PRIMARY KEY (`instagram_id`),
        UNIQUE KEY `instagram_uid` (`instagram_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci; ');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_google` (
        `google_id` int(11) NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `google_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        `expires` bigint(20) UNSIGNED NOT NULL DEFAULT "0",
        PRIMARY KEY (`google_id`),
        UNIQUE KEY `google_uid` (`google_uid`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_yahoo` (
        `yahoo_id` int(11) NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `yahoo_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        PRIMARY KEY (`yahoo_id`),
        UNIQUE KEY `yahoo_id` (`yahoo_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci; ');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_pinterest` (
        `pinterest_id` int(11) NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `pinterest_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        PRIMARY KEY (`pinterest_id`),
        UNIQUE KEY `pinterest_id` (`pinterest_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci; ');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_user_hotmail` (
        `hotmail_id` int(11) NOT NULL auto_increment,
        `user_id` INT(11) NOT NULL,
        `hotmail_uid` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
        `access_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT "",
        PRIMARY KEY (`hotmail_id`),
        UNIQUE KEY `hotmail_id` (`hotmail_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES 
      ("sessociallogin.facebooksignup", "0"),
      ("sessociallogin.twittersignup", "0"),
      ("sessociallogin.googlesignup", "0"),
      ("sessociallogin.linkedinsignup", "0"),
      ("sessociallogin.hotmailsignup", "0"),
      ("sessociallogin.instagramsignup", "0"),
      ("sessociallogin.pinterestsignup", "0"),
      ("sessociallogin.yahoosignup", "0"),
      ("sessociallogin.flickrsignup", "0"),
      ("sessociallogin.vksignup", "0");');

      include_once APPLICATION_PATH . "/application/modules/Sessociallogin/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('sessociallogin.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('sessociallogin.licensekey', $_POST['sessociallogin_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
		$licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('sessociallogin.licensekey');
		$licensekey = @base64_encode($licensekey);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sessociallogin.sesdomainauth', $domain_name);
		Engine_Api::_()->getApi('settings', 'core')->setSetting('sessociallogin.seslkeyauth', $licensekey);
		$error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('sessociallogin.licensekey', $_POST['sessociallogin_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
