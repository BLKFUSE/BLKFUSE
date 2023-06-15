/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql 2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("core_admin_main_plugins_sesnews", "sesnews", "SNS - News / RSS Importer & Aggregator", "", '{"route":"admin_default","module":"sesnews","controller":"settings"}', "core_admin_main_plugins", "", 999),
("sesnews_admin_main_settings", "sesnews", "Global Settings", "", '{"route":"admin_default","module":"sesnews","controller":"settings"}', "sesnews_admin_main", "", 1),
("sesnews_admin_main_rsssettings", "sesnews", "RSS Settings", "", '{"route":"admin_default","module":"sesnews","controller":"settings", "action":"rss-settings"}', "sesnews_admin_main", "", 2);
