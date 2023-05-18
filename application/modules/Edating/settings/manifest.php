<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array (
  'package' =>
  array(
      'type' => 'module',
      'name' => 'edating',
      'version' => '6.2.0',
      'dependencies' => array(
        array(
          'type' => 'module',
          'name' => 'core',
          'minVersion' => '6.2.0',
        ),
      ),
      'path' => 'application/modules/Edating',
      'title' => 'SNS - Dating Plugin',
      'description' => 'SNS - Dating Plugin',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
          'path' => 'application/modules/Edating/settings/install.php',
          'class' => 'Edating_Installer',
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
          0 => 'application/modules/Edating',
      ),
      'files' =>
      array(
          0 => 'application/languages/en/edating.csv',
      ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'edating_dating','edating_photo',
  ),
	
	'routes' => array(
    'edating_general' => array(
      'route' => 'datings/:action/*',
      'defaults' => array(
          'module' => 'edating',
          'controller' => 'index',
          'action' => 'browse'
      ),
      'reqs' => array(
        'action' => '(browse|settings|photos|upload-photo|makemainphoto|editphoto|deletephoto|like|my-likes|who-like-me|mutual-likes|already-viewed|reject)',
      )
    ),
  ),
);
