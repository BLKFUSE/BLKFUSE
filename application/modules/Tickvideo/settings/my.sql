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
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_tickvideo', 'tickvideo', 'SNS - Short TikTak Video', '', '{"route":"admin_default","module":"tickvideo","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('tickvideo_admin_main_settings', 'tickvideo', 'Global Settings', '', '{"route":"admin_default","module":"tickvideo","controller":"settings"}', 'tickvideo_admin_main', '', 1);
INSERT INTO `engine4_core_jobtypes` ( `title`, `type`, `module`, `plugin`, `form`, `enabled`, `priority`, `multi`) VALUES ( 'SNS - Tick Video - Video Encode', 'tickvideo_encode', 'tickvideo', 'Tickvideo_Plugin_Job_Encode', NULL, 1, 75, 2);