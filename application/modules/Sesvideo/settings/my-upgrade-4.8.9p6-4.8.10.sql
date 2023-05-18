UPDATE `engine4_core_menuitems` SET `params` = '{"route":"admin_default","module":"sesbasic","controller":"lightbox","action":"index"}' WHERE `engine4_core_menuitems`.`name` = 'sesvideo_admin_main_lightbox';

ALTER TABLE `engine4_sesvideo_videos` CHANGE  `code`  `code` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;