 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
("eticktokclone", "SNS - TikTok Clone", "SNS - TikTok Clone", "6.4.0", 1, "extra");

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_tickvideo', 'tickvideo', 'SNS - Short TikTak Video', '', '{"route":"admin_default","module":"tickvideo","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('tickvideo_admin_main_settings', 'tickvideo', 'Global Settings', '', '{"route":"admin_default","module":"tickvideo","controller":"settings"}', 'tickvideo_admin_main', '', 1);
