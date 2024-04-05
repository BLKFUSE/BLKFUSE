<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: License.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
//folder name or directory name.
$module_name = 'tickvideo';

//product title and module title.
$module_title = 'Short Tiktak Videos Plugin for Mobile Apps - Tiktok Clone';

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
  $postdata['licenseKey'] = @base64_encode($_POST['tickvideo_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("tickvideo_admin_main_categories", "tickvideo", "Music", "", \'{"route":"admin_default","module":"tickvideo","controller":"music", "action":"categories"}\', "tickvideo_admin_main", "", 2);');
      $db->query('DROP TABLE IF EXISTS `engine4_tickvideo_categories`;');
      $db->query('CREATE TABLE `engine4_tickvideo_categories` (
      `category_id` int(11) UNSIGNED  NOT NULL auto_increment,
      `category_name` varchar(128) NOT NULL,
      `item_count` int(11) NOT NULL DEFAULT  "0",
      `order` int(11) NOT NULL DEFAULT  "0",
      `status` tinyint(1) NOT NULL DEFAULT  "1",
      PRIMARY KEY (`category_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;');
      $db->query('DROP TABLE IF EXISTS `engine4_tickvideo_musics`;');
      $db->query('CREATE TABLE `engine4_tickvideo_musics` (
      `music_id` int(11) unsigned NOT NULL auto_increment,
      `owner_id` int(11) unsigned NOT NULL default "0",
      `favourite_count` int(11) unsigned NOT NULL default "0",
      `title` varchar(128) NOT NULL,
      `description` mediumtext NOT NULL,
      `category_id` int(11) unsigned NOT NULL default "0",
      `photo_id` int(11) unsigned NOT NULL default "0",
      `file_id` int(11) unsigned NOT NULL default "0",
      `duration` int (5) NOT NULL DEFAULT "0",
      `creation_date` datetime NOT NULL,
      `modified_date` datetime NOT NULL,
      PRIMARY KEY (`music_id`),
      KEY `category_id` (`category_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci ;');
      $db->query('DROP TABLE IF EXISTS `engine4_tickvideo_favourites`;');
      $db->query('CREATE TABLE `engine4_tickvideo_favourites` (
      `favourite_id` int(11) UNSIGNED NOT NULL auto_increment,
      `user_id` int(11) UNSIGNED NOT NULL,
      `resource_type` varchar (55) NOT NULL,
      `resource_id` int(11) NOT NULL,
      PRIMARY KEY (`favourite_id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

			$table_exist_video = $db->query('SHOW TABLES LIKE \'engine4_sesvideo_videos\'')->fetch();
			if (!empty($table_exist_video)) {
				$song_id = $db->query('SHOW COLUMNS FROM engine4_sesvideo_videos LIKE \'song_id\'')->fetch();
				if (empty($song_id)) {
					$db->query('ALTER TABLE `engine4_sesvideo_videos` ADD `song_id` INT(11) UNSIGNED NOT NULL DEFAULT "0";');
				}
				$is_tickvideo = $db->query('SHOW COLUMNS FROM engine4_sesvideo_videos LIKE \'is_tickvideo\'')->fetch();
				if (empty($is_tickvideo)) {
					$db->query('ALTER TABLE `engine4_sesvideo_videos` ADD `is_tickvideo` TINYINT NOT NULL DEFAULT "0";');
				}
			}
			
			$table_exist_chanels = $db->query('SHOW TABLES LIKE \'engine4_sesvideo_chanels\'')->fetch();
			if (!empty($table_exist_chanels)) {
				$is_default = $db->query('SHOW COLUMNS FROM engine4_sesvideo_chanels LIKE \'is_default\'')->fetch();
				if (empty($is_default)) {
					$db->query('ALTER TABLE `engine4_sesvideo_chanels` ADD `is_default` tinyint(1) NOT NULL DEFAULT "0";');
				}
			}
			
      $db->query('INSERT IGNORE INTO `engine4_core_jobtypes` ( `title`, `type`, `module`, `plugin`, `form`, `enabled`, `priority`, `multi`) VALUES ( "SNS - Tick Video - Video Encode", "tickvideo_encode", "tickvideo", "Tickvideo_Plugin_Job_Encode", NULL, 1, 75, 2);');
      
      include_once APPLICATION_PATH . "/application/modules/Eticktokclone/controllers/License.php";

      include_once APPLICATION_PATH . "/application/modules/Tickvideo/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('tickvideo.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('tickvideo.licensekey', $_POST['tickvideo_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('tickvideo.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('tickvideo.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('tickvideo.licensekey', $_POST['tickvideo_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
