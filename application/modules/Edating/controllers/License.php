<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: License.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

//folder name or directory name.
$module_name = 'edating';

//product title and module title.
$module_title = 'Dating Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['edating_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('edating.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("edating_admin_main_managephotos", "edating", "Manage Photos", "", \'{"route":"admin_default","module":"edating","controller":"manage", "action": "photos"}\', "edating_admin_main", "", 2),
      ("edating_admin_main_manageaction", "edating", "Manage Actions", "", \'{"route":"admin_default","module":"edating","controller":"manage", "action":"actions"}\', "edating_admin_main", "", 3),
      ("edating_admin_main_level", "edating", "Member Level Settings", "", \'{"route":"admin_default","module":"edating","controller":"level"}\', "edating_admin_main", "", 4),
      ("edating_admin_main_managewidgetizepage", "edating", "Widgetized Pages", "", \'{"route":"admin_default","module":"edating","controller":"settings", "action":"manage-widgetize-page"}\', "edating_admin_main", "", 999);');
      
      $db->query('INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
      ("edating_main", "standard", "SNS - Dating Main Navigation Menu");');
      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_edating", "edating", "Dating", "", \'{"route":"edating_general"}\', "core_main", "", 4),
      ("edating_main_browse", "edating", "Browse", "", \'{"route":"edating_general"}\', "edating_main", "", 1),
      ("edating_main_photos", "edating", "Your Dating Photos", "", \'{"route":"edating_general", "action": "photos"}\', "edating_main", "", 2),
      ("edating_main_settings", "edating", "Settings", "", \'{"route":"edating_general", "action":"settings"}\', "edating_main", "", 3),
      ("edating_main_mylikes", "edating", "My Likes", "", \'{"route":"edating_general", "action":"my-likes"}\', "edating_main", "", 4),
      ("edating_main_wholikeme", "edating", "Who Like Me", "", \'{"route":"edating_general", "action":"who-like-me"}\', "edating_main", "", 5),
      ("edating_main_mutuallikes", "edating", "Mutual Likes", "", \'{"route":"edating_general", "action":"mutual-likes"}\', "edating_main", "", 6),
      ("edating_main_alreadyviewed", "edating", "Already Viewed", "", \'{"route":"edating_general", "action":"already-viewed"}\', "edating_main", "", 7);');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes`(`type`,`module`,`body`,`is_request`,`handler`) VALUES ( "edating_like","edating",\'{item:$subject} like you dating profile.\',"0","");');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes`(`type`,`module`,`body`,`is_request`,`handler`) VALUES ( "edating_mutual","edating",\'{item:$subject} send you mutual like!\',"0","");');
      
      $db->query('DROP TABLE IF EXISTS `engine4_edating_actions`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_edating_actions` (
        `action_id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `owner_id` int(11) NOT NULL,
        `action` varchar(100) NOT NULL,
        `time_stamp` int(11) NOT NULL,
        PRIMARY KEY (`action_id`)
      ) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;');
      
      $db->query('DROP TABLE IF EXISTS `engine4_edating_settings`;');
      $db->query('CREATE TABLE `engine4_edating_settings` (
        `setting_id` int(11) NOT NULL auto_increment,
        `user_id` int(11) unsigned NOT NULL,
        `is_search` int(11) NOT NULL,
        `description` text CHARACTER SET utf8 NOT NULL,
        PRIMARY KEY (`setting_id`),
        KEY `user_id` (`user_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
      
      $db->query('DROP TABLE IF EXISTS `engine4_edating_photos`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_edating_photos` (
        `photo_id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `title` text CHARACTER SET utf8 NOT NULL,
        `description` text CHARACTER SET utf8 NOT NULL,
        `creation_date` varchar(255) CHARACTER SET utf8 NOT NULL,
        `view_count` int(11) NOT NULL,
        `file_id` int(11) NOT NULL,
        `is_main` int(11) NOT NULL,
        PRIMARY KEY (`photo_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');
      
      $db->query('DROP TABLE IF EXISTS `engine4_edating_likes`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_edating_likes` (
        `like_id` int(11) NOT NULL AUTO_INCREMENT,
        `owner_id` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `time_stamp` int(11) NOT NULL,
        `mutual` int(11) NOT NULL,
        `is_viewed` int(11) NOT NULL,
        `is_own` int(11) NOT NULL,
        PRIMARY KEY (`like_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;');

      include_once APPLICATION_PATH . "/application/modules/Edating/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('edating.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('edating.licensekey', $_POST['edating_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('edating.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('edating.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('edating.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('edating.licensekey', $_POST['edating_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
