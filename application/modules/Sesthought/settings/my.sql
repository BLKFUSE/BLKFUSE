
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("core_admin_main_settings_sesthought", "sesthought", "SNS - Thoughts", "", '{"route":"admin_default","module":"sesthought","controller":"settings","action":"index"}', "core_admin_main_plugins", "", 1),
("sesthought_admin_main_settings", "sesthought", "Global Settings", "", '{"route":"admin_default","module":"sesthought","controller":"settings","action":"index"}', "sesthought_admin_main", "", 1),
("sesthought_main_create", "sesthought", "Write New Thought", "Sesthought_Plugin_Menus::canCreateThoughts", '{"route":"sesthought_general","action":"create", "class":"smoothbox"}', "sesthought_main", "", 3);