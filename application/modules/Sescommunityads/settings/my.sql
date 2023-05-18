/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('sescomadbanr', 'SNS - Community Advertisements Banner Extension', 'SNS - Community Advertisements Banner Extension', '5.3.3', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sescommunityads', 'sescommunityads', 'SNS - Community Advertisements', '', '{"route":"admin_default","module":"sescommunityads","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sescommunityads_admin_main_settings', 'sescommunityads', 'Global Settings', '', '{"route":"admin_default","module":"sescommunityads","controller":"settings"}', 'sescommunityads_admin_main', '', 1);
