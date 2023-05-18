/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: my-upgrade-5.0.1-5.1.0.sql 2022-06-20
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("serenity_admin_main_managefonts", "serenity", "Manage Fonts", "", '{"route":"admin_default","module":"serenity","controller":"settings", "action":"manage-fonts"}', "serenity_admin_main", "", 3);
