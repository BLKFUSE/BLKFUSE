<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Plugin_Menus {

  public function canCreateContests() {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer || !$viewer->getIdentity()) {
      return false;
    }
    if (!Engine_Api::_()->authorization()->isAllowed('contest', $viewer, 'create')) {
      return false;
    }
    return true;
  }

  public function onMenuInitialize_SescontestMainManage($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer->getIdentity()) {
      return false;
    }

    return true;
  }

  public function onMenuInitialize_SescontestMainManagePackage($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer->getIdentity()) {
      return false;
    }
    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
      return false;
    }
    return true;
  }

  public function onMenuInitialize_SescontestMainCreate($row) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer->getIdentity()) {
      return false;
    }
    if (!Engine_Api::_()->authorization()->isAllowed('contest', null, 'create')) {
      return false;
    }
    return true;
  }

  public function onMenuInitialize_SescontestProfileDashboard() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    if ($subject->getType() !== 'contest') {
      throw new Sescontest_Model_Exception('Whoops, not a contest!');
    }
    if (!$viewer->getIdentity() || !$subject->authorization()->isAllowed($viewer, 'edit')) {
      return false;
    }
    if (!$subject->authorization()->isAllowed($viewer, 'edit')) {
      return false;
    }
    return array(
        'label' => 'Dashboard',
        'class' => 'sesbasic_icon_edit',
        'route' => 'sescontest_dashboard',
        'params' => array(
            'controller' => 'dashboard',
            'action' => 'edit',
            'contest_id' => $subject->custom_url
        )
    );
  }

  public function onMenuInitialize_SescontestProfileReport() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $sescontest = Engine_Api::_()->core()->getSubject();

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.report', 1))
    return false;

    if($sescontest->owner_id == $viewer->getIdentity())
	  return false;

    if (!$viewer->getIdentity())
    return false;

    return array(
        'label' => 'Report',
        'class' => 'smoothbox sesbasic_icon_report',
        'route' => 'default',
        'params' => array(
            'module' => 'core',
            'controller' => 'report',
            'action' => 'create',
            'subject' => $sescontest->getGuid(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SescontestProfileShare() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    if ($subject->getType() !== 'contest') {
      throw new Sescontest_Model_Exception('This contest does not exist.');
    }
    if (!$viewer->getIdentity() || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.share', 1)) {
      return false;
    }
    return array(
        'label' => 'Share This Contest',
        'class' => 'smoothbox sesbasic_icon_share',
        'route' => 'default',
        'params' => array(
            'module' => 'activity',
            'controller' => 'index',
            'action' => 'share',
            'type' => $subject->getType(),
            'id' => $subject->getIdentity(),
            'format' => 'smoothbox',
        ),
    );
  }

  public function onMenuInitialize_SescontestProfileDelete() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    if ($subject->getType() !== 'contest') {
      throw new Sescontest_Model_Exception('This contest does not exist.');
    } else if (!$subject->authorization()->isAllowed($viewer, 'delete')) {
      return false;
    }
    return array(
        'label' => 'Delete Contest',
        'class' => 'smoothbox sesbasic_icon_delete',
        'route' => 'sescontest_general',
        'params' => array(
            'action' => 'delete',
            'contest_id' => $subject->getIdentity(),
        ),
    );
  }

}
