<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array (
  'package' =>
  array(
    'type' => 'module',
    'name' => 'eticktokclone',
    'version' => '6.4.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.5.1',
      ),
    ),
    'path' => 'application/modules/Eticktokclone',
    'title' => '<span style="color:#DDDDDD">SNS - TikTok Clone</span>',
    'description' => '<span style="color:#DDDDDD">SNS - TikTok Clone</span>',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	'thumb' => 'application/modules/Eticktokclone/externals/images/thumb.png',
    'callback' => array(
        'path' => 'application/modules/Eticktokclone/settings/install.php',
        'class' => 'Eticktokclone_Installer',
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
			'application/modules/Eticktokclone',
    ),
    'files' =>
    array(
			'application/languages/en/eticktokclone.csv',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' =>array(
    'eticktokclone_user'
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'eticktokclone_default' => array(
      'route' => 'explore/*',
      'defaults' => array(
        'module' => 'eticktokclone',
        'controller' => 'index',
        'action' => 'explore'
      )
    ),
    'eticktokclone_profile' => array(
      'route' => 'tiktok/:id/*',
      'defaults' => array(
          'module' => 'eticktokclone',
          'controller' => 'profile',
          'action' => 'index'
      )
		),
		'eticktokclone_tagged' => array(
			'route' => 'tiktok/tag/:tag/*',
			'defaults' => array(
					'module' => 'eticktokclone',
					'controller' => 'index',
					'action' => 'tagged'
			)
		),
  )
);
