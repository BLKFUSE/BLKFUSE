<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array (
  'package' =>
  array(
    'type' => 'module',
    'name' => 'epaidcontent',
    'version' => '6.3.0',
    'dependencies' => array(
      array(
        'type' => 'module',
        'name' => 'core',
        'minVersion' => '6.0.0',
      ),
    ),
    'path' => 'application/modules/Epaidcontent',
    'title' => 'SNS - Paid User Content Plugin',
    'description' => 'SNS - Paid User Content Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
    'callback' => array(
        'path' => 'application/modules/Epaidcontent/settings/install.php',
        'class' => 'Epaidcontent_Installer',
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
        0 => 'application/modules/Epaidcontent',
    ),
    'files' =>
    array(
        0 => 'application/languages/en/epaidcontent.csv',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'epaidcontent_usergateway',
    'epaidcontent_package',
    'epaidcontent_gateway',
    'epaidcontent_order',
    'epaidcontent_userpayrequest',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    'epaidcontent_order' => array(
      'route' => 'paidcontent/order/:action/:package_id/*',
      'defaults' => array(
          'module' => 'epaidcontent',
          'controller' => 'order',
          'action' => 'index',
      ),
    ),
    'epaidcontent_user_order' => array(
      'route' => 'paidcontent/orders/:action/*',
      'defaults' => array(
        'module' => 'epaidcontent',
        'controller' => 'index',
        'action' => 'order',
      ),
    ),
    'epaidcontent_general' => array(
      'route' => 'paidcontent/:action/*',
      'defaults' => array(
        'module' => 'epaidcontent',
        'controller' => 'index',
        'action' => 'manage-packages',
      ),
      'reqs' => array(
        'action' => '(account-details|sales-stats|manage-orders|payment-requests|delete-payment|payment-request|payment-transaction|sales-reports|manage-packages|createpackage|editpackage|showpackage|makepayment|process|return|success|finish|detail-payment|my-orders)',
      ),
    ),
  ),
);
