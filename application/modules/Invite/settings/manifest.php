<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Invite
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manifest.php 10194 2014-05-01 17:41:40Z mfeineman $
 * @author     Steve
 */
return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'invite',
    'version' => '6.5.1',
    'revision' => '$Revision: 10194 $',
    'path' => 'application/modules/Invite',
    'repository' => 'socialengine.com',
    'title' => 'Invite',
    'description' => 'Invite',
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
      'path' => 'application/modules/Invite/settings/install.php',
      'class' => 'Invite_Installer',
    ),
    'directories' => array(
      'application/modules/Invite',
    ),
    'files' => array(
      'application/languages/en/invite.csv',
    ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onUserCreateAfter',
      'resource' => 'Invite_Plugin_Signup',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'invite',
  ),
);
