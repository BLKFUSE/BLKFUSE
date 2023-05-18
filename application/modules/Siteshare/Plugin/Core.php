<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Plugin_Core extends Zend_Controller_Plugin_Abstract
{
  public function routeShutdown(Zend_Controller_Request_Abstract $request)
  {
    if (Engine_Api::_()->siteshare()->isSiteMobileModeEnabled()){
        return;
    }
    $requesRoutetName = join('_', array(
      $request->getModuleName(),
      $request->getControllerName(),
      $request->getActionName(),
    ));

    $shareRouteName = array('activity_index_share', 'advancedactivity_index_share', 'seaocore_activity_share');
    if( !in_array($requesRoutetName, $shareRouteName) ) {
      if( $request->getModuleName() == 'siteshare' ) {
      
      }
      return;
    }
        
    $request->setModuleName('siteshare');
    $request->setControllerName('index');
    $request->setActionName('share');
  }

}
