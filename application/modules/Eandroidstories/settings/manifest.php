<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eandroidstories
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'eandroidstories',
    'version' => '6.4.0p1',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.2.0',
      ),
    ),
    'path' => 'application/modules/Eandroidstories',
    'title' => 'SNS - Stories Feature in Android Mobile Apps',
    'description' => 'SNS - Stories Feature in Android Mobile Apps',
     'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
        'path' => 'application/modules/Eandroidstories/settings/install.php',
        'class' => 'Eandroidstories_Installer',
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
      'application/modules/Eandroidstories',
      'application/modules/Sesstories',
    ),
    'files' =>
    array (
      'application/languages/en/eandroidstories.csv',
      'application/languages/en/sesstories.csv',
    ),
  ),
);
