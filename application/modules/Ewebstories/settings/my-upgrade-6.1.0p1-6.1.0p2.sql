INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sesstories_admin_main_bgtextstories", "sesstories", "Backgrounds For Text Stories", "", '{"route":"admin_default","module":"sesstories","controller":"managestories","action":"index"}', "sesstories_admin_main", "", 6);

CREATE TABLE IF NOT EXISTS `engine4_sesstories_backgrounds` (
  `background_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` int(11) unsigned NOT NULL,
  `order` INT(11) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT "1",
  PRIMARY KEY  (`background_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `engine4_sesstories_stories` ADD `background_id` INT(11) NOT NULL DEFAULT "0";
ALTER TABLE `engine4_sesstories_stories` ADD `story_type` VARCHAR(11) NOT NULL DEFAULT 'imagevideo';
ALTER TABLE `engine4_sesstories_stories` CHANGE `title` `title` TEXT NOT NULL;
