<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$thoughtsRoute = "thoughts";
$thoughtRoute = 'thought';
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
  $thoughtsRoute = $setting->getSetting('sesthought.thoughts.manifest', 'thoughts');
  $thoughtRoute = $setting->getSetting('sesthought.thought.manifest', 'thought');
}
return array (
  'package' => array(
    'type' => 'module',
    'name' => 'sesthought',
    //'sku' => 'sesthought',
    'version' => '6.5.1',
	'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.5.1',
            ),
        ),
    'path' => 'application/modules/Sesthought',
    'title' => 'SNS - Thoughts Plugin',
    'description' => 'SNS - Thoughts Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	'thumb' => 'application/modules/Sesthought/externals/images/thumb.png',
    'actions' => array(
        'install',
        'upgrade',
        'refresh',
        'enable',
        'disable',
    ),
    'callback' => array(
        'path' => 'application/modules/Sesthought/settings/install.php',
        'class' => 'Sesthought_Installer',
    ),
    'directories' => array(
        'application/modules/Sesthought',
    ),
    'files' => array(
        'application/languages/en/sesthought.csv',
    ),
  ),
  'items' => array(
    'sesthought_thought', 'sesthought_category',
  ),
  // Compose -------------------------------------------------------------------
  'composer' => array(
//     'thought' => array(
//       'script' => array('_composethought.tpl', 'sesthought'),
//       'plugin' => 'Sesthought_Plugin_ThoughtComposer',
//     ),
  ),
  //Hooks
  'hooks' => array(
    array(
      'event' => 'onRenderLayoutDefault',
      'resource' => 'Sesthought_Plugin_Core'
    ),
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'sesthought_category' => array(
      'route' => $thoughtsRoute.'/categories/:action/*',
      'defaults' => array(
        'module' => 'sesthought',
        'controller' => 'category',
        'action' => 'browse',
      ),
    ),
    'sesthought_specific' => array(
      'route' => $thoughtsRoute.'/:action/:thought_id/*',
      'defaults' => array(
        'module' => 'sesthought',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'thought_id' => '\d+',
        'action' => '(delete|edit)',
      ),
    ),
    'sesthought_entry_view' => array(
      'route' => $thoughtsRoute.'/:user_id/:thought_id/:slug',
      'defaults' => array(
        'module' => 'sesthought',
        'controller' => 'index',
        'action' => 'view',
        'slug' => '',
      ),
      'reqs' => array(
        'user_id' => '\d+',
        'thought_id' => '\d+'
      ),
    ),
    'sesthought_general' => array(
      'route' => $thoughtsRoute.'/:action/*',
      'defaults' => array(
        'module' => 'sesthought',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'action' => '(index|create|manage)',
      ),
    ),
  ),
);
