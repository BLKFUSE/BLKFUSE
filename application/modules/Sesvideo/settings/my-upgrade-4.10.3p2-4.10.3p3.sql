ALTER TABLE `engine4_sesvideo_videos` ADD `networks` VARCHAR(255) NULL, ADD `levels` VARCHAR(255) NULL;

INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    "sesvideo_video" as `type`,
    "allow_levels" as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");
  
INSERT IGNORE INTO `engine4_authorization_permissions`
  SELECT
    level_id as `level_id`,
    "sesvideo_video" as `type`,
    "allow_network" as `name`,
    0 as `value`,
    NULL as `params`
  FROM `engine4_authorization_levels` WHERE `type` NOT IN("public");
  
ALTER TABLE `engine4_sesvideo_videos` CHANGE `type` `type` VARCHAR( 32 ) NOT NULL;
ALTER TABLE `engine4_sesvideo_videos` CHANGE `code` `code` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;
UPDATE engine4_authorization_permissions SET name = 'addplayl_video' WHERE name = 'addplayl_video';
UPDATE engine4_authorization_permissions SET name = 'video_approvety' WHERE name = 'video_approve_type';
UPDATE engine4_authorization_permissions SET name = 'video_uploadoptn' WHERE name = 'video_upload_option';