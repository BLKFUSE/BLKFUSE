<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Plugin_Menus
{
  public function canViewReport(){
     $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() ) {
      return false;
    }
    if( !Engine_Api::_()->authorization()->isAllowed('sescommunityads', $viewer, 'create') ) {
      return false;
    }
    return true;
  }
  function canViewActivity(){
      if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
          return false;
      }
      return true;
  }
  public function canCreateAds()
  {
    // Must be logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer || !$viewer->getIdentity() ) {
      return false;
    }
    // Must be able to create wishes
    if( !Engine_Api::_()->authorization()->isAllowed('sescommunityads', $viewer, 'create') ) {
      return false;
    }
    return true;
  }
  public function canViewAds()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    // Must be able to create wishes
    if( !Engine_Api::_()->authorization()->isAllowed('sescommunityads', $viewer, 'view') ) {
      return false;
    }
    return true;
  }
}
