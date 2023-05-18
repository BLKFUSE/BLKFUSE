<?php
/**
 * SocialEngine
 *
 * @category   Application_Theme
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manifest.php 2022-06-20
 */

 return array (
  'package' =>
  array (
    'type' => 'theme',
    'name' => 'serenity',
    'version' => '6.2.0',
    'revision' => '$Revision: 10113 $',
    'path' => 'application/themes/serenity',
    'repository' => 'socialengine.com',
    'title' => 'Serenity',
    'thumb' => 'theme.jpg',
    'author' => 'Webligo Developments',
    'actions' =>
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'remove',
    ),
    'callback' =>
    array (
      'class' => 'Engine_Package_Installer_Theme',
    ),
    'directories' =>
    array (
      0 => 'application/themes/serenity',
    ),
    'description' => 'Serenity',
  ),
  'files' =>
  array (
    0 => 'theme.css',
    1 => 'constants.css',
  ),
); ?>
