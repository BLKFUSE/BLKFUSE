INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('sescomadbanr', 'SES - Community Advertisements Banner Extension', 'SES - Community Advertisements Banner Extension', '5.3.3', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescommunityads_admin_main_extension", "sescommunityads", "Extensions", "", '{"route":"admin_default","module":"sescommunityads","controller":"settings", "action": "extensions"}', "sescommunityads_admin_main", "", 999);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescommunityads_admin_main_sescommunityadsbanner", "sescommunityads", "Banner Ads", "", '{"route":"admin_default","module":"sescomadbanr","controller":"settings"}', "sescommunityads_admin_main", "", 999),
("sescomadbanr_admin_main_settings", "sescomadbanr", "Global Settings", "", '{"route":"admin_default","module":"sescomadbanr","controller":"settings"}', "sescomadbanr_admin_main", "", 1);
