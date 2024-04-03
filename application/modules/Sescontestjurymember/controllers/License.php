<?php

if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjurymember.pluginactivated')) {

  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
  
  $db->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
  ("sescontestjurymember_admin_main", "sescontestjurymember", "Voting by Jury Members", "", \'{"route":"admin_default","module":"sescontestjurymember","controller":"settings"}\', "sescontest_admin_main", "", 996),
  ("sescontestjurymember_admin_main_settings", "sescontestjurymember", "Global Settings", "", \'{"route":"admin_default","module":"sescontestjurymember","controller":"settings"}\', "sescontestjurymember_admin_main", "", 1);');

  $db->query('DROP TABLE IF EXISTS `engine4_sescontestjurymember_members`;');
  $db->query('CREATE TABLE `engine4_sescontestjurymember_members` (
    `member_id` int(11) unsigned NOT NULL auto_increment,
    `user_id` int(11) NOT NULL,
    `contest_id` int(11) NOT NULL,
    `creation_date` datetime NOT NULL,
    `modified_date` datetime NOT NULL,
    PRIMARY KEY  (`member_id`),
    KEY `user_id` (`user_id`),
    KEY `contest_id` (`contest_id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;');
  $db->query('INSERT IGNORE INTO `engine4_sescontest_dashboards` (`type`, `title`, `enabled`, `main`) VALUES
  ("jury_member", "Manage Jury", "1", "0");');
  $db->query('ALTER TABLE `engine4_sescontest_contests` ADD `audience_type` TINYINT(1) NULL DEFAULT "1";');
  $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    "sescontest_contest" as `type`,
    "can_add_jury" as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
  $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    "sescontest_contest" as `type`,
    "jury_member_count" as `name`,
    3 as `value`,
    4 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');
  $db->query('INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    "sescontest_participant" as `type`,
    "jury_votecount_weight" as `name`,
    3 as `value`,
    2 as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN("moderator", "admin","user");');

  include_once APPLICATION_PATH . "/application/modules/Sescontestjurymember/controllers/defaultsettings.php";

  Engine_Api::_()->getApi('settings', 'core')->setSetting('sescontestjurymember.pluginactivated', 1);
  $error = 1;
}
