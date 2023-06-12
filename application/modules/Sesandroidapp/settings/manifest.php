<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesandroidapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'sesandroidapp',
    //'sku' => 'sesiosappse',
    'version' => '6.4.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Sesandroidapp',
    'title' => 'SNS - Native Android Mobile App Plugin',
    'description' => 'SNS - Native Android Mobile App Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' => array(
      'path' => 'application/modules/Sesandroidapp/settings/install.php',
      'class' => 'Sesandroidapp_Installer',
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
      0 => 'application/modules/Sesandroidapp',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/sesandroidapp.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onActivityNotificationCreateAfter',
      'resource' => 'Sesandroidapp_Plugin_Core',
    ),
  ),
  'items'=>array('sesandroidapp_pushnotifications','sesandroidapp_slide','sesandroidapp_customthemes','sesandroidapp_themes','sesandroidapp_graphic')
);
