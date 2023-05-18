<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$videosRoute = "videos";
$chanelsRoute = "chanels";
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
    $videosRoute = $setting->getSetting('video.videos.manifest', 'videos');
    $videoRoute = $setting->getSetting('video.video.manifest', 'video');
    $chanelsRoute = $setting->getSetting('video.chanels.manifest','channels');
    $chanelRoute = $setting->getSetting('video.chanel.manifest','channel');
}

return array (
  'package' => 
  array (
    'type' => 'module',
    'name' => 'tickvideo',
    'version' => '6.2.0',
	'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Tickvideo',
    'title' => 'SNS - Short TikTak Videos Plugin for Mobile Apps',
    'description' => 'SNS - Short TikTak Videos Plugin for Mobile Apps',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
          'path' => 'application/modules/Tickvideo/settings/install.php',
          'class' => 'Tickvideo_Installer',
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
      0 => 'application/modules/Tickvideo',
    ),
    'files' => 
    array (
      0 => 'application/languages/en/tickvideo.csv',
    ),
  ),
    'items' =>array(
        'tickvideo_category','tickvideo_music'
    ),
    // Routes --------------------------------------------------------------------
    'routes' => array(
        'tickvideo_general' => array(
//            'route' =>   $videosRoute.'/:action/*',
//            'defaults' => array(
//                'module' => 'sesvideo',
//                'controller' => 'index',
//                'action' => 'welcome',
//            ),
        ),
    )
); ?>
