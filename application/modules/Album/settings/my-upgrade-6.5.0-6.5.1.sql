UPDATE `engine4_activity_actiontypes` SET `body` = '{item:$subject} added photo(s) to the album {item:$object}:' WHERE `engine4_activity_actiontypes`.`type` = 'album_photo_new';

ALTER TABLE `engine4_album_albums` ADD `approved` TINYINT(1) NOT NULL DEFAULT "1";
ALTER TABLE `engine4_album_albums` ADD INDEX(`approved`);

ALTER TABLE `engine4_album_albums` ADD `resubmit` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_album_albums` ADD INDEX(`resubmit`);

ALTER TABLE `engine4_album_photos` ADD `approved` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `engine4_album_photos` ADD INDEX(`approved`);

ALTER TABLE `engine4_album_photos` ADD `resubmit` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_album_photos` ADD INDEX(`resubmit`);

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'approve' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'approve' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
  

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'photoapprove' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'album' as `type`,
    'photoapprove' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');

UPDATE `engine4_core_menuitems` SET `label` = 'Manage Albums' WHERE `engine4_core_menuitems`.`name` = 'album_admin_main_manage';
UPDATE `engine4_core_menuitems` SET `label` = 'Manage Photos' WHERE `engine4_core_menuitems`.`name` = 'album_admin_main_managephotos';
