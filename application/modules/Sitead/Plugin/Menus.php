<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Plugin_Menus {

  public function canViewAdvertiesment() {
    $temp_file = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.temp.file');
    if (empty($temp_file)) {
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer();

    // Must be able to view advertising
    if (!Engine_Api::_()->authorization()->isAllowed('sitead', $viewer, 'view')) {
      return false;
    }

    return true;
  }

  public function canManageAdvertiesment() {
    $viewer = Engine_Api::_()->user()->getViewer();

    // Must be able to view advertising
    if (!(Engine_Api::_()->authorization()->isAllowed('sitead', $viewer, 'create'))) {
      return false;
    }

    return true;
  }

  public function canCreateAdvertiesment() {
    $viewer = Engine_Api::_()->user()->getViewer();
    // Must be able to view advertising
    if (!Engine_Api::_()->authorization()->isAllowed('sitead', $viewer, 'create')) {
      return false;
    }

    return true;
  }

  // SHOWING LINK ON "GROUP PROFILE PAGE".
  public function onMenuInitialize_CoreMainSitead($row) {
    $temp_file = Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.temp.file');
    if (empty($temp_file)) {
      return;
    }
    $viewer = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (!empty($viewer)) {
      return array(
          'label' => $row->label,
          'icon' => 'application/modules/Sitead/externals/images/ad-icon16.png',
          'route' => 'sitead_listpackage',
      );
    }
    return false;
  }

}