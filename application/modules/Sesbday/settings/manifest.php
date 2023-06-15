<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: mainfest.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'sesbday',
    'version' => '6.4.0',
	'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.2.0',
            ),
        ),
    //'sku' => 'sesbday',
    'path' => 'application/modules/Sesbday',
    'title' => 'SNS - Birthday Plugin',
    'description' => '',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
          'path' => 'application/modules/Sesbday/settings/install.php',
          'class' => 'Sesbday_Installer',
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
      0 => 'application/modules/Sesbday',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/sesbday.csv',
    ),
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
     'sesbday_general' => array(
      'route' => 'user/birthday',
      'defaults' => array(
        'module' => 'sesbday',
        'controller' => 'index',
        'action' => 'browse'
      ),
    ),
   ),
); ?>
