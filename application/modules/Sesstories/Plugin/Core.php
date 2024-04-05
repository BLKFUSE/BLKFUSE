<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesstories
 * @package    Sesstories
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2016-05-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesstories_Plugin_Core extends Zend_Controller_Plugin_Abstract {

  public function onUserCreateAfter($event) {

    $user = $event->getPayload();

    $userTable = Engine_Api::_()->getItemTable('user');
    $userinfoTable = Engine_Api::_()->getItemTable('sesstories_userinfo');
    
    //Enter privacy into userinfoTable
    Engine_Api::_()->sesstories()->isExist($user->user_id, 'owner_member');

    $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
    
    //View privacy
    $dbGetInsert->query('INSERT INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES ("user", "'.$user->user_id.'", "story_view", "owner_member", "0", "1", NULL);');
    
    //Comment Privacy
    $dbGetInsert->query('INSERT INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES ("user", "'.$user->user_id.'", "story_comment", "owner_member", "0", "1", NULL);');
  }
}
