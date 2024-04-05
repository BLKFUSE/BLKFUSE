<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

return array(
  'package' =>
  array(
      'type' => 'module',
      'name' => 'sescontestjurymember',
      //'sku' => 'sescontestjurymember',
      'version' => '6.5.1',
      'path' => 'application/modules/Sescontestjurymember',
      'title' => '<span style="color:#DDDDDD">SNS - Advanced Contests - Voting by Jury Members Plugin</span>',
      'description' => '<span style="color:#DDDDDD">SNS - Advanced Contests - Voting by Jury Members Plugin</span>',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
      'callback' => array(
        'path' => 'application/modules/Sescontestjurymember/settings/install.php',
        'class' => 'Sescontestjurymember_Installer',
      ),
      'actions' =>
      array(
          0 => 'install',
          1 => 'upgrade',
          2 => 'refresh',
          3 => 'enable',
          4 => 'disable',
      ),
      'directories' => array(
          'application/modules/Sescontestjurymember',
      ),
      'files' => array(
          'application/languages/en/sescontestjurymember.csv',
      ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
      'sescontestjurymember_member'
  ),
);
