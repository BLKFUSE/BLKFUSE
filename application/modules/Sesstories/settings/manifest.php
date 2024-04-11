<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: install.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'sesstories',
    'version' => '6.5.1',
		'dependencies' => array(
			array(
				'type' => 'module',
				'name' => 'core',
				'minVersion' => '5.0.0',
			),
		),
    'path' => 'application/modules/Sesstories',
    'title' => 'SNS - Stories Feature in Website',
    'description' => 'SNS - Stories Feature in Android Mobile Apps',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	'thumb' => 'application/modules/Sesstories/externals/images/thumb.png',
    'callback' => array(
        'path' => 'application/modules/Sesstories/settings/install.php',
        'class' => 'Sesstories_Installer',
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
      0 => 'application/modules/Sesstories',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/sesstories.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserCreateAfter',
      'resource' => 'Sesstories_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'sesstories_story', 'sesstories_mute','sesstories_userinfo','sesstories_background'
  ),
);
