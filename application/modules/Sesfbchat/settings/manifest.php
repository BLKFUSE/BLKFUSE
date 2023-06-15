<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */ 

return array (
  'package' =>
  array (
    'type' => 'module',
    'name' => 'sesfbchat',
	//'sku'=>'sesfbchat',
    'version' => '6.4.0',
    'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.2.0',
            ),
        ),
    'path' => 'application/modules/Sesfbchat',
    'title' => 'SNS - FB Messager Customer Live Chat Plugin',
    'description' => 'FB Messager Customer Live Chat Plugin.',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' =>
    array (
      'class' => 'Sesfbchat_Installer',
      'path' => 'application/modules/Sesfbchat/settings/install.php',
    ),
    'actions' =>
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'enable',
      4 => 'disable',
    ),
    'directories' =>
    array (
      0 => 'application/modules/Sesfbchat',
    ),
    'files' =>
    array (
      0 => 'application/languages/en/sesfbchat.csv',
    ),
  ),
); ?>
