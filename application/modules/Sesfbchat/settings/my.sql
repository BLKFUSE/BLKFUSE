/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('sesfbchat_admin_main_plugin', 'sesfbchat', 'SNS - FB Messenger Customer Live Chat Plugin', '', '{"route":"admin_default","module":"sesfbchat","controller":"settings","action":"index"}', 'core_admin_main_plugins', '', 999),
('sesfbchat_admin_main_settings', 'sesfbchat', 'Global Settings', '', '{"route":"admin_default","module":"sesfbchat","controller":"settings","action":"index"}', 'sesfbchat_admin_main', '', 1);
