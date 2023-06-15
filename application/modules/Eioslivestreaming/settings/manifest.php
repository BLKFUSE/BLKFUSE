<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eioslivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2020-06-01  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'eioslivestreaming',
    'version' => '6.4.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Eioslivestreaming',
    'title' => 'SNS - Live Streaming in iOS Mobile App',
    'description' => 'SNS - Live Streaming in iOS Mobile App',
     'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
        'path' => 'application/modules/Eioslivestreaming/settings/install.php',
        'class' => 'Eioslivestreaming_Installer',
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
      'application/modules/Eioslivestreaming',
      'application/modules/Elivestreaming',
    ),
    'files' =>
    array (
      'application/languages/en/eioslivestreaming.csv',
      'application/languages/en/elivestreaming.csv',
    ),
  ),
);
