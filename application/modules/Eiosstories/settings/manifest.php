<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eiosstories
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'eiosstories',
    'version' => '6.5.1',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.5.1',
      ),
    ),
    'path' => 'application/modules/Eiosstories',
    'title' => 'SNS - Stories Feature in iOS Mobile App',
    'description' => 'SNS - Stories Feature in iOS Mobile App',
     'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	 'thumb' => 'application/modules/Eiosstories/externals/images/thumb.png',
    'callback' =>
    array (
        'path' => 'application/modules/Eiosstories/settings/install.php',
        'class' => 'Eiosstories_Installer',
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
      'application/modules/Eiosstories',
      'application/modules/Sesstories',
    ),
    'files' =>
    array (
      'application/languages/en/eiosstories.csv',
      'application/languages/en/sesstories.csv',
    ),
  ),
);
