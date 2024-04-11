UPDATE `engine4_core_menuitems` SET `params` = '{"route":"admin_default","module":"sesbasic","controller":"lightbox","action":"index"}' WHERE `engine4_core_menuitems`.`name` = 'sesvideo_admin_main_lightbox';

ALTER TABLE `engine4_sesvideo_videos` CHANGE  `code`  `code` TEXT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL ;