<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: License.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
//folder name or directory name.
$module_name = 'egames';

//product title and module title.
$module_title = 'Games Plugin';

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
  $postdata['licenseKey'] = @base64_encode($_POST['egames_licensekey']);
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

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('egames.pluginactivated')) {

      $db = Zend_Db_Table_Abstract::getDefaultAdapter();

      $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
      ("core_main_egames", "egames", "Games", "", \'{"route":"egames_general","action":"browse","icon":"fas fa-dice"}\', "core_main", "", 3),
      ("core_sitemap_egames", "egames", "Games", "", \'{"route":"egames_general","action":"browse"}\', "core_sitemap", "", 3),
      ("egames_admin_main_manage", "egames", "Manage Games", "", \'{"route":"admin_default","module":"egames","controller":"manage"}\', "egames_admin_main", "", 2),
      ("egames_admin_main_level", "egames", "Member Level Settings", "", \'{"route":"admin_default","module":"egames","controller":"level"}\', "egames_admin_main", "", 3),
      ("egames_admin_main_categories", "egames", "Categories", "", \'{"route":"admin_default","module":"egames","controller":"categories", "action":"index"}\', "egames_admin_main", "", 4),
      ("egames_admin_main_managepages", "egames", "Manage Widgetize Page", "", \'{"route":"admin_default","module":"egames","controller":"settings", "action":"manage-widgetize-page"}\', "egames_admin_main", "", 999);');

      $db->query('INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
      ("egames_game_create", "egames", \'{item:$subject} created a new game:\', 1, 5, 1, 3, 1, 1),
      ("comment_egames_game", "egames", \'{item:$subject} has commented on the game {item:$object}:\', 1, 5, 1, 3, 1, 1),
      ("like_egames_game", "egames", \'{item:$subject} has liked a game {item:$object}:\', 1, 5, 1, 3, 1, 1);');
      
      $db->query('INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
      ("egames_game_like", "egames", \'{item:$subject} has liked your game {item:$object}.\', 0, "");');
      
      $db->query('DROP TABLE IF EXISTS `engine4_egames_categories`;');
      $db->query('CREATE TABLE IF NOT EXISTS `engine4_egames_categories` (
        `category_id` int(11) unsigned NOT NULL auto_increment,
        `slug` varchar(255) NOT NULL,
        `category_name` varchar(128) NOT NULL,
        `subcat_id` int(11)  NULL DEFAULT 0,
        `subsubcat_id` int(11)  NULL DEFAULT 0,
        `title` varchar(255) DEFAULT NULL,
        `description` text ,
        `thumbnail` int(11) NOT NULL DEFAULT 0,
        `cat_icon` int(11) NOT NULL DEFAULT 0,
        `order` int(11) NOT NULL DEFAULT 0,
        `member_levels` TEXT NULL,
        PRIMARY KEY (`category_id`),
        KEY `category_id` (`category_id`,`category_name`),
        KEY `category_name` (`category_name`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;');
      
      $db->query('DROP TABLE IF EXISTS `engine4_egames_games`;');
      $db->query('CREATE TABLE `engine4_egames_games` (
        `game_id` int(11) unsigned NOT NULL auto_increment,
        `owner_id` int(11) NOT NULL,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NULL,
        `url` text COLLATE utf8_unicode_ci NOT NULL,
        `category_id` int(11) NOT NULL DEFAULT "0",
        `subcat_id` int(11) NOT NULL DEFAULT "0",
        `subsubcat_id` int(11) NOT NULL DEFAULT "0",
        `photo_id` int(11) DEFAULT NULL,
        `search` tinyint(1) NOT NULL,
        `view_privacy` VARCHAR(24) NOT NULL,
        `view_count` int(10) UNSIGNED NOT NULL,
        `like_count` int(11) UNSIGNED NOT NULL,
        `comment_count` int(11) UNSIGNED NOT NULL,
        `play_count` int(11) UNSIGNED NOT NULL,
        `creation_date` datetime NOT NULL,
        `modified_date` datetime NOT NULL,
        PRIMARY KEY  (`game_id`),
        KEY `owner_id` (`owner_id`),
        KEY `search` (`search`),
        KEY `creation_date` (`creation_date`),
        KEY `view_count` (`view_count`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');

      include_once APPLICATION_PATH . "/application/modules/Egames/controllers/defaultsettings.php";

      Engine_Api::_()->getApi('settings', 'core')->setSetting('egames.pluginactivated', 1);
      Engine_Api::_()->getApi('settings', 'core')->setSetting('egames.licensekey', $_POST['egames_licensekey']);
    }
    $domain_name = @base64_encode(str_replace(array('http://','https://','www.'),array('','',''),$_SERVER['HTTP_HOST']));
    $licensekey = Engine_Api::_()->getApi('settings', 'core')->getSetting('egames.licensekey');
    $licensekey = @base64_encode($licensekey);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egames.sesdomainauth', $domain_name);
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egames.seslkeyauth', $licensekey);
    $error = 1;
  } else {
    $error = $this->view->translate('Please enter correct License key for this product.');
    $error = Zend_Registry::get('Zend_Translate')->_($error);
    $form->getDecorator('errors')->setOption('escape', false);
    $form->addError($error);
    $error = 0;
    Engine_Api::_()->getApi('settings', 'core')->setSetting('egames.licensekey', $_POST['egames_licensekey']);
    return;
    $this->_helper->redirector->gotoRoute(array());
  }
}
