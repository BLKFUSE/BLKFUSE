<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manifest.php 2022-06-21
 */

return array (
  'package' =>
  array(
    'type' => 'module',
    'name' => 'serenity',
    'sku' => 'serenity',
    'version' => '6.2.0',
    'path' => 'application/modules/Serenity',
    'title' => 'Serenity Theme',
    'description' => 'Serenity Theme',
    'author' => 'WebligoDevelopments',
    'callback' => array(
        'path' => 'application/modules/Serenity/settings/install.php',
        'class' => 'Serenity_Installer',
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
      'application/modules/Serenity',
      'application/themes/serenity',
    ),
    'files' =>
    array(
      'application/languages/en/serenity.csv',
    ),
  ),
  'items' => array(
    'serenity_customthemes',
  ),
	// Hooks ---------------------------------------------------------------------
	'hooks' => array(
		array(
			'event' => 'onRenderLayoutDefault',
			'resource' => 'Serenity_Plugin_Core'
		),
	),
);
