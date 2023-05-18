<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

return array (
  'package' =>
  array(
      'type' => 'module',
      'name' => 'sescomadbanr',
      //'sku' => 'sescomadbanr',
      'version' => '5.4.0',
      'path' => 'application/modules/Sescomadbanr',
      'title' => '<span style="color:#DDDDDD">SNS - Community Advertisements Banner Extension</span>',
      'description' => '<span style="color:#DDDDDD">SNS - Community Advertisements Banner Extension</span>',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
          'path' => 'application/modules/Sescomadbanr/settings/install.php',
          'class' => 'Sescomadbanr_Installer',
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
          0 => 'application/modules/Sescomadbanr',
      ),
      'files' =>
      array(
          0 => 'application/languages/en/sescomadbanr.csv',
      ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'sescomadbanr_banner',
    'sescomadbanr_userpayment',
  ),
);
