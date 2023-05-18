<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedbg
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array (
  'package' =>
  array(
      'type' => 'module',
      'name' => 'sesfeedbg',
      'version' => '4.10.3p1',
      'path' => 'application/modules/Sesfeedbg',
      'title' => '<span style="color:#DDDDDD">SNS - Background Images in Status Updates Plugin</span>',
      'description' => '<span style="color:#DDDDDD">SNS - Background Images in Status Updates Plugin</span>',
      'author' => '<a href="http://www.socialenginesolutions.com" style="text-decoration:underline;" target="_blank">SocialEngineSolutions</a>',
      'callback' => array(
          'path' => 'application/modules/Sesfeedbg/settings/install.php',
          'class' => 'Sesfeedbg_Installer',
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
          0 => 'application/modules/Sesfeedbg',
      ),
      'files' =>
      array(
          0 => 'application/languages/en/sesfeedbg.csv',
      ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'sesfeedbg_background',
  ),
);
