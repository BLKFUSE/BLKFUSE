/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sesuserimport', 'sesuserimport', 'SNS - Bulk Importing, Creating New / Dummy Users', '', '{"route":"admin_default","module":"sesuserimport","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sesuserimport_admin_main_settings', 'sesuserimport', 'Global Settings', '', '{"route":"admin_default","module":"sesuserimport","controller":"settings"}', 'sesuserimport_admin_main', '', 1);
