<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Announcement
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manifest.php 10150 2014-03-27 15:59:48Z andres $
 * @author     John
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'announcement',
    'version' => '6.5.1',
    'revision' => '$Revision: 10150 $',
    'path' => 'application/modules/Announcement',
    'repository' => 'socialengine.com',
    'title' => 'Announcements',
    'description' => 'Announcements',
    'author' => 'SocialEngine Core',
    'thumb' => 'application/modules/Core/externals/images/thumb.png',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '5.0.0',
      ),
    ),
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       //'enable',
       //'disable',
     ),
    'callback' => array(
      'class' => 'Engine_Package_Installer_Module',
    ),
    'directories' => array(
      'application/modules/Announcement',
    ),
    'files' => array(
      'application/languages/en/announcement.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onItemDeleteBefore',
      'resource' => 'Announcement_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'announcement'
  ),
  // Routes --------------------------------------------------------------------
) ?>
