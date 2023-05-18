/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sesdating', 'sesdating', 'SNS - Responsive Dating Theme', '', '{"route":"admin_default","module":"sesdating","controller":"settings"}', 'core_admin_main', '', 999),
('sesdating_admin_main_settings', 'sesdating', 'Global Settings', '', '{"route":"admin_default","module":"sesdating","controller":"settings"}', 'sesdating_admin_main', '', 1);
