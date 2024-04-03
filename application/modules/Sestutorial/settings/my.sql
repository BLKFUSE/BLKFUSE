
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sestutorial', 'sestutorial', 'SNS - Multi-Use Tutorials', '', '{"route":"admin_default","module":"sestutorial","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sestutorial_admin_main_settings', 'sestutorial', 'Global Settings', '', '{"route":"admin_default","module":"sestutorial","controller":"settings"}', 'sestutorial_admin_main', '', 1),
('sestutorial_main_askquestion', 'sestutorial', 'Request Tutorial', 'Sestutorial_Plugin_Menus::askquestion', '{"class":"smoothbox", "route":"sestutorial_general","action":"askquestion"}', 'sestutorial_main', '', 6);