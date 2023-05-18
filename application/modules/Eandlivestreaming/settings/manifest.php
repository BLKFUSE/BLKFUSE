<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eandlivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'eandlivestreaming',
    'version' => '6.2.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Eandlivestreaming',
    'title' => 'SNS - Live Streaming in Android Mobile App',
    'description' => 'SNS - Live Streaming in Android Mobile App',
     'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
        'path' => 'application/modules/Eandlivestreaming/settings/install.php',
        'class' => 'Eandlivestreaming_Installer',
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
      'application/modules/Eandlivestreaming',
      'application/modules/Elivestreaming',
    ),
    'files' =>
    array (
      'application/languages/en/eandlivestreaming.csv',
      'application/languages/en/elivestreaming.csv',
    ),
  ),
);
