<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Avatarstyler
 * @copyright  Copyright Hire-Experts LLC
 * @license    http://www.hire-experts.com
 * @version    Id: install.php 18.10.13 14:26 Ulan T $
 * @author     Ulan T
 */

/**
 * @category   Application_Extensions
 * @package    Avatarstyler
 * @copyright  Copyright Hire-Experts LLC
 * @license    http://www.hire-experts.com
 */

class Avatarstyler_Installer extends Engine_Package_Installer_Module
{
  public function onPreInstall()
  {
    parent::onPreInstall();

    $db = $this->getDb();
    $translate = Zend_Registry::get('Zend_Translate');
    
    $db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `params`, `menu`) VALUES ('core_admin_main_plugins_avatarstyler', 'avatarstyler', 'HE - Avatar Styler', '{\"route\":\"admin_default\",\"module\":\"avatarstyler\",\"controller\":\"index\"}', 'core_admin_main_plugins');");
    
    $db->query("INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `menu`) VALUES ('user_profile_avatar', 'avatarstyler', 'Style your avatar', 'Avatarstyler_Plugin_Menus', 'user_profile');");
    
    $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES ('avatarstyler.usage', 'allow');");
    
    $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES  ('avatarstyler.current.photo.id', '0');");
    
    $db->query("INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES  ('avatarstyler.photo.ids', 'null');");
    
    $db->query("
CREATE TABLE IF NOT EXISTS  `engine4_avatarstyler_images` (
`images_id` INT(10) NOT NULL AUTO_INCREMENT,
`photo_id` INT(10) NOT NULL DEFAULT '0',
`photo_url` VARCHAR(255) NOT NULL DEFAULT '0',
PRIMARY KEY (`images_id`)
);");
    
    
    
  }
}
