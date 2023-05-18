 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
("sesadvancedcomment", "SNS - Professional Activity & Nested Comments Plugin", "SNS - Professional Activity & Nested Comments Plugin", "4.10.3p6", 1, "extra"),
("sesfeedbg", "SNS: Background Images in Status Updates Plugin", "SNS: Background Images in Status Updates Plugin", "4.10.3p1", 1, "extra"),
("sesfeedgif", "SNS: GIF Images & Giphy Integration with GIF Player Plugin", "SNS: GIF Images & Giphy Integration with GIF Player Plugin", "4.10.3p1", 1, "extra"),
("sesfeelingactivity", "SNS: Feelings & Activities Plugin", "SNS: Feelings & Activities Plugin", "4.10.3p1", 1, "extra");

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_settings_sesadvancedactivity', 'sesadvancedactivity', 'SNS - Professional Activity...', '', '{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"index"}', 'core_admin_main_plugins', '', 1),
('sesadvancedactivity_admin_main_settings', 'sesadvancedactivity', 'Global Settings', '', '{"route":"admin_default","module":"sesadvancedactivity","controller":"settings","action":"index"}', 'sesadvancedactivity_admin_main', '', 1),
('sesadvancedactivity_index_onthisday', 'sesadvancedactivity', 'Memories On This Day', 'Sesadvancedactivity_Plugin_Menus::enableonthisday', '{"route":"sesadvancedactivity_onthisday","icon":"application/modules/Sesadvancedactivity/externals/images/onthisday.png"}', 'user_home', '', 6);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('sesadvancedactivity_index_sell', 'sesadvancedactivity', 'BuySell Marketplace', '', '{"route":"sesadvancedactivity_sell"}', 'user_home', '', 99);

INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES
('sesadvancedcomment.enableattachement', 'a:3:{i:0;s:6:"photos";i:1;s:6:"videos";i:2;s:8:"emotions";}'),
('sesadvancedcomment.enableordering', 'a:4:{i:0;s:6:"newest";i:1;s:6:"oldest";i:2;s:5:"liked";i:3;s:7:"replied";}');

