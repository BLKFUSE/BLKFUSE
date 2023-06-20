/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sesalbum', 'sesalbum', 'SNS - Advanced Photos & Albums', '', '{"route":"admin_default","module":"sesalbum","controller":"settings","action":"index"}', 'core_admin_main_plugins', '', 999),
('sesalbum_admin_main_settings', 'sesalbum', 'Global Settings', '', '{"route":"admin_default","module":"sesalbum","controller":"settings"}', 'sesalbum_admin_main', '', 1);
