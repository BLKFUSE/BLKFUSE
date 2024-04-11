<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
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
$gamesRoute = 'games';
$gameRoute = 'game';
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'], '/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $gamesRoute = $setting->getSetting('egames.pages.manifest', 'games');
  $gameRoute = $setting->getSetting('egames.page.manifest', 'game');
}

return array(
  'package' =>
  [
    'type' => 'module',
    'name' => 'egames',
    'version' => '6.5.1',
	'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.5.1',
      ),
    ),
    'path' => 'application/modules/Egames',
    'title' => 'SNS - Games Plugin',
    'description' => 'SNS - Games Plugin',
     'author' => '<a href="http://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	 'thumb' => 'application/modules/Egames/externals/images/thumb.png',
     'actions' => [
          'install',
          'upgrade',
          'refresh',
          'enable',
          'disable',
     ],
     'callback' => [
          'path' => 'application/modules/Egames/settings/install.php',
          'class' => 'Egames_Installer',
     ],
    'directories' =>
    [
      0 => 'application/modules/Egames',
    ],
    'files' =>
    [
      0 => 'application/languages/en/egames.csv',
    ],
  ],
    'hooks' => array(
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Egames_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => [
        'egames_game',
        "egames_category"
    ],
    'routes' => [
        // Public
        'egames_profile' => array(
          'route' => $gameRoute . '/:game_id/:slug/*',
          'defaults' => array(
              'module' => 'egames',
              'controller' => 'index',
              'action' => 'view',
          ),
        ),
        
        'egames_general' => [
            'route' => $gamesRoute.'/:action/*',
            'defaults' => [
                'module' => 'egames',
                'controller' => 'index',
                'action' => 'browse',
            ],
        ],
        'egames_specific' => array(
          'route' => $gamesRoute.'/:action/:game_id/*',
          'defaults' => array(
            'module' => 'egames',
            'controller' => 'index',
            'action' => 'edit',
          ),
          'reqs' => array(
            'game_id' => '\d+',
            'action' => '(delete|edit)',
          ),
        ),
    ]
      ); ?>
