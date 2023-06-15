<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
$module1 = null;
$controller = null;
$action = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module1 = $request->getModuleName();
  $action = $request->getActionName();
  $controller = $request->getControllerName();
}
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'], '/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $contestsRoute = $setting->getSetting('sescontest.contests.manifest', 'contests');
  $contestRoute = $setting->getSetting('sescontest.contest.manifest', 'contest');
}

return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'sescontestjoinfees',
    //'sku' => 'sescontestjoinfees',
    'version' => '5.4.0',
    'dependencies' => array(
        array(
            'type' => 'module',
            'name' => 'core',
            'minVersion' => '5.0.0',
        ),
    ),
    'path' => 'application/modules/Sescontestjoinfees',
    'title' => '<span style="color:#DDDDDD">SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin</span>',
    'description' => '<span style="color:#DDDDDD">Advanced Contests - Contests Joining Fees & Payments System Plugin</span>',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
      'path' => 'application/modules/Sescontestjoinfees/settings/install.php',
	    'class' => 'Sescontestjoinfees_Installer',
    ),
    'actions' =>
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' =>
    array (
      0 => 'application/modules/Sescontestjoinfees',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/sescontestjoinfees.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onSescontestParticipantCreateAfter',
            'resource' => 'Sescontestjoinfees_Plugin_Core',
        ),
   ),
  // Items ---------------------------------------------------------------------
    'items' => array(
      'sescontestjoinfees_order',
      'sescontestjoinfees_gateway',
      'sescontestjoinfees_usergateway',
      'sescontestjoinfees_userpayrequest',
      'sescontestjoinfees_remainingpayment',
    ),
     // Routes --------------------------------------------------------------------
    'routes' => array(
      'sescontestjoinfees_user_order' => array(
          'route' => $contestsRoute.'/orders/:action/*',
          'defaults' => array(
              'module' => 'sescontestjoinfees',
              'controller' => 'index',
              'action' => 'order',
          ),
      ),
      'sescontestjoinfees_order' => array(
          'route' => $contestsRoute.'/order/:action/:contest_id/*',
          'defaults' => array(
              'module' => 'sescontestjoinfees',
              'controller' => 'order',
              'action' => 'index',
          ),

        ),
    ),
); ?>
