<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescredit_Plugin_Menus {

  public function onMenuInitialize_SescreditMainManage() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainTransactions() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainEarncredit() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainHelp() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainBadges() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainLeaderboard() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }
  
  public function onMenuInitialize_SescreditMainMyorders() {

    if (!Engine_Api::_()->user()->getViewer()->getIdentity())
      return false;
    return true;
  }

  public function onMenuInitialize_SescreditMainManagecashcredit() {
  
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
    if (!$viewer_id)
      return false;
      
		$levelId = $viewer_id ? $viewer->level_id : Engine_Api::_()->getDbTable('levels', 'authorization')->getPublicLevel()->level_id;
    if (!Engine_Api::_()->authorization()->getPermission($levelId, 'sescredit', 'sescredit_cashcredit'))
			return false;
    return true;
  }

}
