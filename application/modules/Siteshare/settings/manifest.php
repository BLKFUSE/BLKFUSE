<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
  'package' => array(
    'type' => 'module',
    'name' => 'siteshare',
    'sku' => 'seao-siteshare',
    'version' => '5.1.0',
    'path' => 'application/modules/Siteshare',
    'title' => 'Advanced Share Plugin',
    'description' => 'Advanced Share Plugin',
    'author' => '<a href="http:\\\\www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
    'actions' => array(
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'callback' => array(
      'path' => 'application/modules/Siteshare/settings/install.php',
      'class' => 'Siteshare_Installer',
    ),
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '4.10.3',
      ),
    ),
    'directories' => array(
      0 => 'application/modules/Siteshare',
    ),
    'files' => array(
      0 => 'application/languages/en/siteshare.csv',
    ),
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public
    'siteshare_sendemail' => array(
      'route' => 'share/send-email',
      'defaults' => array(
        'module' => 'siteshare',
        'controller' => 'index',
        'action' => 'send-email',
      )
    ),
  ),
);
?>
