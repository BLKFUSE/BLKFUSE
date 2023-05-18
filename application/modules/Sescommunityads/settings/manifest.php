<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
$adsRoute = "ads";
$adRoute = 'ad';
$module1 = null;
$controller = null;
$action = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module1 = $request->getModuleName();
  $action = $request->getActionName();
  $controller = $request->getControllerName();
}
if (empty($request) || !((strpos($_SERVER['REQUEST_URI'],'/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $adsRoute = $setting->getSetting('sescommunityads.ads.manifest', 'ads');
  $adRoute = $setting->getSetting('sescommunityads.ads.manifest', 'ad');
}
return array (
  'package' =>
    array (
      'type' => 'module',
      'name' => 'sescommunityads',
      //'sku' => 'sescommunityads',
      'version' => '6.2.0',
      'dependencies' => array(
          array(
              'type' => 'module',
              'name' => 'core',
              'minVersion' => '6.2.0',
          ),
      ),
      'path' => 'application/modules/Sescommunityads',
      'title' => 'SNS - Community Advertisements Plugin',
      'description' => 'SNS - Community Advertisements Plugin',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'actions' => array(
          'install',
          'upgrade',
          'refresh',
          'enable',
          'disable',
      ),
      'callback' => array(
          'path' => 'application/modules/Sescommunityads/settings/install.php',
          'class' => 'Sescommunityads_Installer',
      ),
      'directories' =>
      array (
        'application/modules/Sescommunityads',
        'application/modules/Sescomadbanr',
      ),
      'files' =>
      array (
        'application/languages/en/sescommunityads.csv',
        'application/languages/en/sescomadbanr.csv',
      ),
    ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sescommunityads_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sescommunityads_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefaultSimple',
            'resource' => 'Sescommunityads_Plugin_Core'
        ),
        array(
            'event' => 'onRenderLayoutMobileDefault',
            'resource' => 'Sescommunityads_Plugin_Core'
        ),
        array(
            'event' => 'onRenderLayoutMobileDefaultSimple',
            'resource' => 'Sescommunityads_Plugin_Core'
        ),
    ),
    'items' => array('sescommunityads_packages','sescommunityads_modules','sescommunityads_orderspackage','sescommunityad_targetad','sescommunityads_gateway','sescommunityads_attachment','sescommunityads_campaign','sescommunityads_ads','sescommunityads_transaction','sescommunityads_package','sescommunityads_category','sescommunityads_report', 'sescommunityads', 'sescommunityads_location',),
    //Routes --------------------------------------------------------------------
    'routes' => array(
        'sescommunityads_whyseeing'=> array(
            'route' => $adsRoute.'/whyseeing/*',
            'defaults' => array(
            'module' => 'sescommunityads',
            'controller' => 'index',
            'action' => 'why-seeing',
            ),
        ),
        'sescommunityads_general' => array(
            'route' => $adsRoute.'/:action/*',
            'defaults' => array(
            'module' => 'sescommunityads',
            'controller' => 'index',
            'action' => 'browse',
            ),
        ),
        'sescommunityads_view' => array(
            'route' => $adRoute.'/:sescommunityad_id/*',
            'defaults' => array(
            'module' => 'sescommunityads',
            'controller' => 'index',
            'action' => 'browse',
            ),
        ),
        'sescomminityads_payment' => array(
            'route' => $adsRoute.'/payment/:action/:sescommunityad_id/*',
            'defaults' => array(
                'module' => 'sescommunityads',
                'controller' => 'payment',
                'action' => 'index',
            ),
            'reqs' => array(
                'action' => '(index|process|return|finish|charge)',
            )
        ),
        'sescommunityads_redirect'=>array(
            'route' => $adsRoute . '/rd/*',
            'defaults' => array(
                'module' => 'sescommunityads',
                'controller' => 'index',
                'action' => 'redirect',
            )
        ),
        'sescomminityads_category_view' => array(
            'route' => $adsRoute . '/category/:category_id/*',
            'defaults' => array(
                'module' => 'sescommunityads',
                'controller' => 'category',
                'action' => 'index',
            )
        ),
        'sescomminityads_category' => array(
            'route' => $adsRoute . '/categories/:action/*',
            'defaults' => array(
                'module' => 'sescommunityads',
                'controller' => 'category',
                'action' => 'browse',
            ),
            'reqs' => array(
                'action' => '(index|browse)',
            )
        ),
    ),
);
