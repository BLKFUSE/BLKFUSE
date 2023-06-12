DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`menu` = 'sesfeedgif_admin_main';
DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'sesfeedgif_admin_main_fegifsettings';

DELETE FROM `engine4_core_menuitems` WHERE `engine4_core_menuitems`.`name` = 'sesadvancedcomment_admin_main_managereactions';

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sesadvancedactivity_admin_main_managereactions", "sesadvancedactivity", "Manage Reactions", "", '{"route":"admin_default","module":"sesadvancedcomment","controller":"manage-reactions","action":"index"}', "sesadvancedactivity_admin_main", "", 5);

ALTER TABLE `engine4_sesadvancedactivity_filterlists` ADD `icon` VARCHAR(128) NULL DEFAULT NULL;

UPDATE `engine4_core_menuitems` SET `label`='SNS - Professional Activity...' WHERE `name` = 'core_admin_main_settings_sesadvancedactivity';

UPDATE `engine4_core_settings` SET `value`=18 WHERE `name` = 'sesadvancedactivity.fonttextsize';
UPDATE `engine4_core_settings` SET `value`=5 WHERE `name` = 'sesadvancedactivity.visiblesearchfilter';