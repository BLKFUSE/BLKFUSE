<?php
//folder name or directory name.
$module_name = 'eandroidstories';

//product title and module title.
$module_title = 'Stories Feature in Android Mobile Apps';

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
  $postdata['licenseKey'] = @base64_encode($_POST['eandroidstories_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('eandroidstories.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES 
      ("sesstories_admin_main_settings", "sesstories", "Global Settings", "", \'{"route":"admin_default","module":"sesstories","controller":"settings"}\', "sesstories_admin_main", "", 80),
      ("sesstories_admin_main_manage", "sesstories", "Manage Stories", "", \'{"route":"admin_default","module":"sesstories","controller":"manage", "action":"index"}\', "sesstories_admin_main", "", 90);');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesstories_stories` (
        `story_id` int(11) unsigned NOT NULL auto_increment,  
        `owner_id` int(11) unsigned NOT NULL,
        `type` TINYINT(1) NOT NULL default "0",
        `file_id` int(11) unsigned NOT NULL default "0",
        `title` VARCHAR(255) NOT NULL,
        `view_count` int(11) unsigned NOT NULL default "0",
        `comment_count` int(11) unsigned NOT NULL default "0",
        `like_count` int(11) unsigned NOT NULL default "0",
        `creation_date` datetime NOT NULL,
        `status` TINYINT(1) NOT NULL DEFAULT "1",
        `code` VARCHAR(16) NOT NULL,
        `duration` INT(9) NOT NULL,
        `rotation` SMALLINT NOT NULL DEFAULT "0",
        `photo_id` INT(11) NOT NULL,
        `resource_type` VARCHAR(64) NOT NULL DEFAULT "user",
        `highlight` TINYINT(1) NOT NULL DEFAULT "0",
        `view_privacy` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`story_id`),
        KEY `owner_id` (`owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesstories_mutes` (
        `mute_id` int(11) unsigned NOT NULL auto_increment,  
        `user_id` int(11) NOT NULL,
        `resource_id` int(11) NOT NULL,
        `mute` tinyint(1) NOT NULL DEFAULT "0",
        PRIMARY KEY (`mute_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesstories_userinfos` (
        `userinfo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `owner_id` int(11) unsigned NOT NULL,
        `view_privacy` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`userinfo_id`),
        UNIQUE KEY `owner_id` (`owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
      
      $db->query('CREATE TABLE IF NOT EXISTS  `engine4_sesstories_recentlyviewitems` (
        `recentlyviewed_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `resource_id` INT NOT NULL ,
        `owner_id` INT NOT NULL ,
        `creation_date` DATETIME NOT NULL,
        UNIQUE KEY `uniqueKey` (`resource_id`, `owner_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

      $db->query('CREATE TABLE IF NOT EXISTS `engine4_sesstories_usersettings` (
        `usersetting_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `user_id` int(11) unsigned NOT NULL,
        `can_comment` tinyint(1) NOT NULL DEFAULT "1",
        `auto_archive` tinyint(1) NOT NULL DEFAULT "1",
        `blocked_friends` varchar(255) NOT NULL,
        UNIQUE KEY `uniqueKey` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
      
      $db->query('INSERT IGNORE INTO `engine4_core_jobtypes` (`title`, `type`, `module`, `plugin`, `enabled`, `multi`, `priority`) VALUES
      ("Story Encode", "sesstories_encode", "sesstories", "Sesstories_Plugin_Job_Encode", 1, 2, 75);');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesstories_story" as `type`,
        "view" as `name`,
        5 as `value`,
        \'["registered","owner_network","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "sesstories_story" as `type`,
        "comment" as `name`,
        5 as `value`,
        \'["registered","owner_network","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "user" as `type`,
        "story_view" as `name`,
        5 as `value`,
        \'["registered","owner_network","owner_member","owner"]\' as `params`
        FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
      SELECT
      level_id as `level_id`,
      "user" as `type`,
      "story_comment" as `name`,
      5 as `value`,
      \'["registered","owner_network","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      
      $table_exist_action = $db->query('SHOW TABLES LIKE \'engine4_sesstories_stories\'')->fetch();
      if (!empty($table_exist_action)) {
        $plateform = $db->query('SHOW COLUMNS FROM engine4_sesstories_stories LIKE \'plateform\'')->fetch();
        if (empty($plateform)) {
          $db->query('ALTER TABLE `engine4_sesstories_stories` ADD `plateform` TINYINT(1) NOT NULL DEFAULT "1";');
        }
      }

      include_once APPLICATION_PATH . "/application/modules/Eandroidstories/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('eandroidstories.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('eandroidstories.licensekey', $_POST['eandroidstories_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eandroidstories.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eandroidstories.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eandroidstories.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eandroidstories.licensekey', $_POST['eandroidstories_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
