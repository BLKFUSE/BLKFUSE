INSERT IGNORE INTO `engine4_sesbasic_integrateothermodules` (`module_name`, `type`, `content_type`, `content_type_photo`, `content_id`, `content_id_photo`, `enabled`) VALUES ('sesevent', 'lightbox', 'sesevent_album', 'sesevent_photo', 'album_id', 'photo_id', '1');

DELETE FROM engine4_core_menuitems WHERE `engine4_core_menuitems`.`name` = "sesbasic_admin_photolightboxphotolightbox";
DELETE FROM engine4_core_menuitems WHERE `engine4_core_menuitems`.`name` = "sesbasic_admin_memberlevelphotolightbox";


ALTER TABLE `engine4_core_menuitems` CHANGE `name` `name` VARCHAR(128) NOT NULL;
ALTER TABLE `engine4_core_menuitems` CHANGE `menu` `menu` VARCHAR(128) NULL DEFAULT NULL, CHANGE `submenu` `submenu` VARCHAR(128) NULL DEFAULT NULL;


INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('sesbasic_admin_photolightboxphotolightbox', 'sesbasic', 'Photo Lightbox Settings', '', '{"route":"admin_default","module":"sesbasic","controller":"photolightbox","action":"photo"}', 'sesbasic_admin_managephotolightbox', '', 1),
('sesbasic_admin_memberlevelphotolightbox', 'sesbasic', 'Member Level Setting', '', '{"route":"admin_default","module":"sesbasic","controller":"photolightbox","action":"index"}', 'sesbasic_admin_managephotolightbox', '', 2);
