<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array (
  'package' =>
  array(
    'type' => 'module',
    'name' => 'eusertip',
    'version' => '6.3.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.0.0',
      ),
    ),
    'path' => 'application/modules/Eusertip',
    'title' => 'SNS - User Paid Tip Plugin',
    'description' => 'SNS - User Paid Tip Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' => array(
        'path' => 'application/modules/Eusertip/settings/install.php',
        'class' => 'Eusertip_Installer',
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
        0 => 'application/modules/Eusertip',
    ),
    'files' =>
    array(
        0 => 'application/languages/en/eusertip.csv',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'eusertip_usergateway',
    'eusertip_tip',
    'eusertip_gateway',
    'eusertip_order',
    'eusertip_userpayrequest',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'eusertip_order' => array(
      'route' => 'paidcontent/order/:action/:tip_id/*',
      'defaults' => array(
          'module' => 'eusertip',
          'controller' => 'order',
          'action' => 'index',
      ),
    ),
    'eusertip_user_order' => array(
      'route' => 'paidcontent/orders/:action/*',
      'defaults' => array(
        'module' => 'eusertip',
        'controller' => 'index',
        'action' => 'order',
      ),
    ),
    'eusertip_general' => array(
      'route' => 'paidcontent/:action/*',
      'defaults' => array(
        'module' => 'eusertip',
        'controller' => 'index',
        'action' => 'manage-tips',
      ),
      'reqs' => array(
        'action' => '(account-details|sales-stats|manage-orders|payment-requests|delete-payment|payment-request|payment-transaction|sales-reports|manage-tips|createtip|edittip|showtip|makepayment|process|return|success|finish|detail-payment|my-orders)',
      ),
    ),
  ),
);
