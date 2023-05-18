<?php

/**
 * SocialEngine
 *
 * @category   Application_Theme
 * @package    Responsive Vertical Theme
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php 2016-11-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
return array (
  'package' => 
  array (
    'type' => 'theme',
    'name' => 'Responsive Dating Theme',
    'version' => '6.2.0p1',
    'path' => 'application/themes/sesdating',
    'repository' => 'socialnetworking.solutions',
    'title' => '<span style="color:#DDDDDD">Responsive Vertical Theme</span>',
    'thumb' => 'dating_theme.jpg',
    'author' => '<a href="https://socialnetworking.solutions" target="_blank" title="Visit our website!">SocialNetworking.Solutions</a>',
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
      0 => 'application/themes/sesdating',
    ),
    'description' => '',
  ),
  'files' => 
  array (
    0 => 'theme.css',
    1 => 'constants.css',
		2 => 'media-queries.css',
    3 => 'sesdating-custom.css'
  )
); 
