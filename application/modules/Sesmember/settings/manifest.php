<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manifest.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
return array(
  'package' =>
  array(
      'type' => 'module',
      'name' => 'sesmember',
      //'sku' => 'sesmember',
      'version' => '6.5.1',
      'dependencies' => array(
          array(
              'type' => 'module',
              'name' => 'core',
              'minVersion' => '6.5.1',
          ),
      ),
      'path' => 'application/modules/Sesmember',
      'title' => 'SNS - Ultimate Members Plugin',
      'description' => 'SNS - Ultimate Members Plugin',
      'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	  'thumb' => 'application/modules/Sesmember/externals/images/thumb.png',
      'callback' => array(
          'path' => 'application/modules/Sesmember/settings/install.php',
          'class' => 'Sesmember_Installer',
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
          0 => 'application/modules/Sesmember',
      ),
      'files' =>
      array(
          0 => 'application/languages/en/sesmember.csv',
      ),
  ),
  'items' => array(
    'sesmember_homepage',
    'sesmember_review',
    'sesmember_userdetail',
    'sesmember_parameter',
    'sesmember_profilephoto',
    'sesmember_follow',
    'sesmember_userinfo'
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
      array(
          'event' => 'onUserCreateAfter',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onRenderLayoutDefault',
          'resource' => 'Sesmember_Plugin_Core'
      ),
      array(
          'event' => 'onRenderLayoutDefaultSimple',
          'resource' => 'Sesmember_Plugin_Core'
      ),
      array(
          'event' => 'onRenderLayoutMobileDefault',
          'resource' => 'Sesmember_Plugin_Core'
      ),
      array(
          'event' => 'onRenderLayoutMobileDefaultSimple',
          'resource' => 'Sesmember_Plugin_Core'
      ),
      array(
          'event' => 'onCoreLikeCreateAfter',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onCoreLikeDeleteAfter',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onCoreLikeDeleteBefore',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onFieldMetaCreate',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onFieldMetaEdit',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
          'event' => 'onItemCreateAfter',
          'resource' => 'Sesmember_Plugin_Core',
      ),
      array(
        'event' => 'onUserDeleteBefore',
        'resource' => 'Sesmember_Plugin_Core',
      ),
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
      'sesmember_general' => array(
          'route' => 'member/:action/*',
          'defaults' => array(
              'module' => 'sesmember',
              'controller' => 'index',
              'action' => 'browse',
          ),
          'reqs' => array(
              'action' => '(browse|locations|featured-block|review-stats|get-friends|get-mutual-friends|nearest-member|top-members|pinborad-view-members|add-location|edit-location|alphabetic-members-search)',
          )
      ),
      'sesmember_review_view' => array(
          'route' => 'reviews/:action/:review_id/:slug',
          'defaults' => array(
              'module' => 'sesmember',
              'controller' => 'review',
              'action' => 'view',
              'slug' => ''
          ),
          'reqs' => array(
              'action' => '(edit|view|delete|edit-review)',
              'review_id' => '\d+'
          )
      ),
      'sesmember_review' => array(
          'route' => 'browse-review/:action/*',
          'defaults' => array(
              'module' => 'sesmember',
              'controller' => 'review',
              'action' => 'browse'
          ),
      ),
  ),
);
