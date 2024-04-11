ALTER TABLE `engine4_music_playlists` ADD `approved` TINYINT(1) NOT NULL DEFAULT "1";
ALTER TABLE `engine4_music_playlists` ADD INDEX(`approved`);

ALTER TABLE `engine4_music_playlists` ADD `resubmit` TINYINT(1) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_music_playlists` ADD INDEX(`resubmit`);

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'music_playlist' as `type`,
    'approve' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('moderator', 'admin');

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    'music_playlist' as `type`,
    'approve' as `name`,
    1 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` IN('user');
