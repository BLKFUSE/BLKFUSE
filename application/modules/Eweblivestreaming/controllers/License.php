<?php
//folder name or directory name.
$module_name = 'eweblivestreaming';

//product title and module title.
$module_title = 'Live Streaming in Website';

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
  $postdata['licenseKey'] = @base64_encode($_POST['eweblivestreaming_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('eweblivestreaming.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES 
      ("elivestreaming_admin_main_settings", "elivestreaming", "Global Settings", "", \'{"route":"admin_default","module":"elivestreaming","controller":"settings"}\', "elivestreaming_admin_main", "", 80),
      ("elivestreaming_admin_main_level", "elivestreaming", "Member Level Settings", "", \'{"route":"admin_default","module":"elivestreaming","controller":"level"}\', "elivestreaming_admin_main", "", 90);');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "auth_view" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "auth_comment" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "auth_photo" as `name`,
        5 as `value`,
        \'["everyone","owner_network","owner_member_member","owner_member","owner"]\' as `params`
      FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "create" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "delete" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "edit" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "view" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "comment" as `name`,
        2 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "create" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "delete" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "edit" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "view" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      
      $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
        SELECT
        level_id as `level_id`,
        "elivehost" as `type`,
        "comment" as `name`,
        1 as `value`,
        NULL as `params`
      FROM `engine4_authorization_levels` WHERE `type` IN("user");');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("elivestreaming_golive", "elivestreaming", \'{item:$subject} started a live video. Watch it before it ends!\', 0, ""),
      ("elivestreaming_was_live", "elivestreaming", \'{item:$subject} was live.\', 0, "");');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("elivestreaming_golive", "elivestreaming", \'{item:$subject} started a live video.\', 1, 5, 1, 3, 1, 1),
      ("elivestreaming_was_live", "elivestreaming", \'{item:$subject} was live.\', 1, 5, 1, 3, 1, 1);');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_elivestreaming_hosts` (
        `elivehost_id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `video_id` INT NULL DEFAULT NULL,
        `action_id` INT NOT NULL,
        `story_id` INT NULL DEFAULT NULL,
        `status` VARCHAR(20) NULL DEFAULT NULL,
        `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`elivehost_id`),
        KEY `elivehost_id_1` (`elivehost_id`,`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_elivestreaming_notificationreceivers` ( 
        `notificationreceiver_id` INT NOT NULL AUTO_INCREMENT , 
        `elivehost_id` INT NOT NULL , 
        `notification_id` INT NOT NULL , 
        PRIMARY KEY (`notificationreceiver_id`)
      ) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');
      

      include_once APPLICATION_PATH . "/application/modules/Eweblivestreaming/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('elivestreaming.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.pluginactivated', 1);
      
      Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.licensekey', $_POST['eweblivestreaming_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('eweblivestreaming.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.seslkeyauth', $licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('eweblivestreaming.licensekey', $_POST['eweblivestreaming_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}