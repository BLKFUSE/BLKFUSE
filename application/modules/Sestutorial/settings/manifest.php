<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2017-10-16 00:00:00 SocialEngineSolutions $
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
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'],'/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $tutorialsRoute = $setting->getSetting('sestutorial.tutorials.manifest', 'tutorials');
  $tutorialRoute = $setting->getSetting('sestutorial.tutorial.manifest', 'tutorial');
}
return array(
  'package' => array(
    'type' => 'module',
    'name' => 'sestutorial',
    //'sku' => 'sestutorial',
    'version' => '6.5.1',
	'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.5.1',
            ),
        ),
    'path' => 'application/modules/Sestutorial',
    'title' => 'SNS - Multi - Use Tutorials Plugin',
    'description' => 'SNS - Multi - Use Tutorials Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	'thumb' => 'application/modules/Sestutorial/externals/images/thumb.png',
    'callback' => array(
        'path' => 'application/modules/Sestutorial/settings/install.php',
        'class' => 'Sestutorial_Installer',
    ),
    'actions' =>
    array(
        0 => 'install',
        1 => 'upgrade',
        2 => 'refresh',
        3 => 'enable',
        4 => 'disable',
    ),
    'directories' =>
    array(
      'application/modules/Sestutorial',
      'externals/ses-scripts/jscolor',
    ),
    'files' =>
    array(
      'application/languages/en/sestutorial.csv',
      'externals/ses-scripts/jquery.min.js',
      'externals/ses-scripts/odering.js',
      'externals/ses-scripts/jquery.tagcanvas.min.js',
      'externals/ses-scripts/PeriodicalExecuter.js',
      'externals/ses-scripts/Carousel.js',
      'externals/ses-scripts/Carousel.Extra.js',
      'public/admin/search-banner.jpg',
      'public/admin/tutorial-category-banner.jpg',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
        'event' => 'onUserDeleteAfter',
        'resource' => 'Sestutorial_Plugin_Core'
    ),
    array(
        'event' => 'onRenderLayoutDefault',
        'resource' => 'Sestutorial_Plugin_Core'
    ),
    array(
        'event' => 'onRenderLayoutDefaultSimple',
        'resource' => 'Sestutorial_Plugin_Core'
    ),
    array(
        'event' => 'onRenderLayoutMobileDefault',
        'resource' => 'Sestutorial_Plugin_Core'
    ),
    array(
        'event' => 'onRenderLayoutMobileDefaultSimple',
        'resource' => 'Sestutorial_Plugin_Core'
    )
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'sestutorial_category','sestutorial_tutorial', 'sestutorial_tutorials', 'sestutorial_askquestion'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'sestutorial_category' => array(
      'route' => $tutorialsRoute . '/categories/:action/*',
      'defaults' => array(
          'module' => 'sestutorial',
          'controller' => 'category',
          'action' => 'browse',
      ),
      'reqs' => array(
          'action' => '(index|browse)',
      )
    ),
    'sestutorial_general' => array(
      'route' => $tutorialsRoute . '/:action/*',
      'defaults' => array(
        'module' => 'sestutorial',
        'controller' => 'index',
        'action' => 'home',
      ),
      'reqs' => array(
          'action' => '(home|browse|view|tags|askquestion)',
      ),
    ),
    'sestutorial_category_view' => array(
      'route' => $tutorialsRoute.'/category/:category_id/*',
      'defaults' => array(
          'module' => 'sestutorial',
          'controller' => 'category',
          'action' => 'index',
      )
    ),
    'sestutorial_profile' => array(
      'route' => $tutorialRoute.'/:tutorial_id/:slug/*',
      'defaults' => array(
          'module' => 'sestutorial',
          'controller' => 'index',
          'action' => 'view',
      ),
    ),
  ),
);
