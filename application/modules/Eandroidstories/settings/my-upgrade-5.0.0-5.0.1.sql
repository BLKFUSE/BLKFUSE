DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'eandroidstories_admin_main_andset';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'eandroidstories_admin_main_settings';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'sesstories_admin_main_andmanage';
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eandroidstories_admin_main_andset", "eandroidstories", "Android Stories Activation", "", '{"route":"admin_default","module":"eandroidstories","controller":"settings"}', "sesstories_admin_main", "", 170);
("sesstories_admin_main_settings", "sesstories", "Global Settings", "", '{"route":"admin_default","module":"sesstories","controller":"settings"}', "sesstories_admin_main", "", 80),
("sesstories_admin_main_manage", "sesstories", "Manage Stories", "", '{"route":"admin_default","module":"sesstories","controller":"manage", "action":"index"}', "sesstories_admin_main", "", 90);
