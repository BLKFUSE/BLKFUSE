<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$module1 = null;
$controller = null;
$action = null;
$giftsRoute = 'gifts';
$giftRoute = 'gift';
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module1 = $request->getModuleName();
  $action = $request->getActionName();
  $controller = $request->getControllerName();
}
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'], '/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $giftsRoute = $setting->getSetting('egifts.plural.manifest', 'gifts');
  $giftRoute = $setting->getSetting('egifts.singular.manifest', 'gift');
}
return array (
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'egifts',
    'version' => '6.4.0p2',
		'dependencies' => array(
        array(
          'type' => 'module',
          'name' => 'core',
          'minVersion' => '6.2.0',
        ),
      ),
    'path' => 'application/modules/Egifts',
    'title' => 'SNS - Virtual Gifts Plugin',
    'description' => 'SNS - Virtual Gifts Plugin',
    'author' => '<a href="http://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       'enable',
       'disable',
     ),
    'callback' => array(
      'path' => 'application/modules/Egifts/settings/install.php',
      'class' => 'Egifts_Installer',
    ),
    'directories' => array(
      'application/modules/Egifts',
    ),
    'files' => array(
      'application/languages/en/egifts.csv',
    ),
  ),
	// Items ---------------------------------------------------------------------
	'items' => array(
		'egifts',
		'egifts_gift',
		'egifts_giftorder',
		'egifts_giftpurchase',
		'egifts_usergateway',
		'egifts_userpayrequest',
	),

	// Routes --------------------------------------------------------------------
	'routes' => array(
		// Public
		'egifts_general' => array(
			'route' => $giftsRoute .'/:action/*',
			'defaults' => array(
				'module' => 'egifts',
				'controller' => 'index',
				'action' => 'browse',
			),
			'reqs' => array(
				'userid' => '\d+',
				'action' => '(index|manage|browse|my-gifts|send-gift|get-user|purchasegift|my-orders|payment-requests|payment-request|delete-payment|detail-payment|payment-transaction|account-details)',
			),
		),
    'egifts_payment' => array(
      'route' => $giftsRoute .'/payment/:action/*',
      'defaults' => array(
        'module' => 'egifts',
        'controller' => 'payment',
        'action' => 'index',
      ),
      'reqs' => array(
        'userid' => '\d+',
        'action' => '(index)',
      ),
    ),
    'egifts_profile' => array(
        'route' => $giftRoute .'/:gift_id/*',
        'defaults' => array(
            'module' => 'egifts',
            'controller' => 'profile',
            'action' => 'index',
        ),
        'reqs' => array(
            'action' => '(index)',
        )
    ),
	),
);
