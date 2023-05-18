<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

return array (
  'package' =>
  array(
      'type' => 'module',
      'name' => 'everification',
      //'sku' => 'everification',
      'version' => '5.8.1',
        'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '5.0.0',
            ),
        ),
      'path' => 'application/modules/Everification',
      'title' => 'SNS - Verified Badge Plugin',
      'description' => 'SNS - Verified Badge Plugin',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
          'path' => 'application/modules/Everification/settings/install.php',
          'class' => 'Everification_Installer',
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
          0 => 'application/modules/Everification',
      ),
      'files' =>
      array(
          0 => 'application/languages/en/everification.csv',
      ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'everification_document',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'everification_extended' => array(
      'route' => 'verifiedbadges/:controller/:action/*',
      'defaults' => array(
        'module' => 'everification',
        'controller' => 'settings',
        'action' => 'manage'
      ),
    ),
  ),
);
