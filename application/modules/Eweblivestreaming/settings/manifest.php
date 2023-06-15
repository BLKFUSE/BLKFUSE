<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eweblivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2020-07-05  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'eweblivestreaming',
    'version' => '6.4.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Eweblivestreaming',
    'title' => 'SNS - Live Streaming in Website',
    'description' => 'SNS - Live Streaming in Website',
     'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
        'path' => 'application/modules/Eweblivestreaming/settings/install.php',
        'class' => 'Eweblivestreaming_Installer',
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
      'application/modules/Eweblivestreaming',
      'application/modules/Elivestreaming',
    ),
    'files' =>
    array (
      'application/languages/en/eweblivestreaming.csv',
      'application/languages/en/elivestreaming.csv',
    ),
  ),
);
