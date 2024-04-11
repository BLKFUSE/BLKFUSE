ALTER TABLE `engine4_album_photos` ADD `parent_id` INT(11) NOT NULL DEFAULT '0' , ADD `parent_type` VARCHAR(64) NULL DEFAULT NULL;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ('album_admin_main_managephotos', 'album', 'View Photos', '', '{"route":"admin_default","module":"album","controller":"manage-photos"}', 'album_admin_main', '', 999);

DELETE FROM engine4_core_search WHERE `engine4_core_search`.`type` = 'album_category';
