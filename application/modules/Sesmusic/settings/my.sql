/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sesmusic', 'sesmusic', 'SNS - Professional Music', '', '{"route":"admin_default","module":"sesmusic","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sesmusic_admin_main_settings', 'sesmusic', 'Settings', '', '{"route":"admin_default","module":"sesmusic","controller":"settings"}', 'sesmusic_admin_main', '', 1),
('sesmusic_admin_main_subglobalsettings', 'sesmusic', 'Global Settings', '', '{"route":"admin_default","module":"sesmusic","controller":"settings"}', 'sesmusic_admin_main_settings', '', 1);

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
("sesmusic_likealbum", "sesmusic", '{item:$subject} likes music album {item:$object}:', 1, 5, 1, 1, 1, 1),
("sesmusic_likealbumsong", "sesmusic", '{item:$subject} likes song {item:$object}:', 1, 5, 1, 1, 1, 1);