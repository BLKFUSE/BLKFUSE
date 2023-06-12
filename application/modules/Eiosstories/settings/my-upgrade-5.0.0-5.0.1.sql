DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'eiosstories_admin_main_iosse';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'eiosstories_admin_main_iossettings';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'sesstories_admin_main_iosmanage';
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eiosstories_admin_main_iosse", "eiosstories", "iOS Stories Activation", "", '{"route":"admin_default","module":"eiosstories","controller":"settings"}', "sesstories_admin_main", "", 160),
("sesstories_admin_main_settings", "sesstories", "Global Settings", "", '{"route":"admin_default","module":"sesstories","controller":"settings"}', "sesstories_admin_main", "", 80),
("sesstories_admin_main_manage", "sesstories", "Manage Stories", "", '{"route":"admin_default","module":"sesstories","controller":"manage", "action":"index"}', "sesstories_admin_main", "", 90);
