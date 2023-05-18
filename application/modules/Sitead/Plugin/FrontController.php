<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Plugin_FrontController extends Zend_Controller_Plugin_Abstract
{

  public function routeShutdown(Zend_Controller_Request_Abstract $request)
  {

    if( Zend_Registry::isRegistered('Zend_View') ) {
      Zend_Registry::get('Zend_View')
        ->addFilterPath(APPLICATION_PATH_MOD . "/Sitead/View/Filter", 'Sitead_View_Filter_')
        ->addFilter('SiteadInjectdCoreFeed');
    }
    $uri = $request->getPathInfo();
    if( !$request->isXmlHttpRequest() && !$request->isFlashRequest() && strpos($uri, '/application/') == false && substr($uri, 1, 5) !== "admin" && substr($uri, 1, 12) !== "application/" ) {
      Engine_Api::_()->sitead()->resetAlreadyRenderAdIds();
    }
  }

}