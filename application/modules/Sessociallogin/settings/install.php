<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sessociallogin
 * @package    Sessociallogin
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php 2017-07-04 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sessociallogin_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {

    $db = $this->getDb();
    parent::onInstall();
  }

  function onEnable() {

    $db = $this->getDb();

   $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Account';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Fields';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Photo';");

    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Account';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Fields';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Photo';");
    parent::onEnable();
  }

  public function onDisable() {

    $db = $this->getDb();
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Account';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Fields';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '1' WHERE  `engine4_user_signup`.`class` ='User_Plugin_Signup_Photo';");

    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Account';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Fields';");
    $db->query("UPDATE  `engine4_user_signup` SET  `enable` =  '0' WHERE  `engine4_user_signup`.`class` ='Sessociallogin_Plugin_Signup_Photo';");

    parent::onDisable();
  }
}
