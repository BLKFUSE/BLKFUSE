<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
$module1 = null;
$controller = null;
$action = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module1 = $request->getModuleName();
  $action = $request->getActionName();
  $controller = $request->getControllerName();
}
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'], '/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $contestsRoute = $setting->getSetting('sescontest.contests.manifest', 'contests');
  $contestRoute = $setting->getSetting('sescontest.contest.manifest', 'contest');
}
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sescontest',
        //'sku' => 'sescontest',
        'version' => '6.4.0p1',
        'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.2.0',
            ),
        ),
        'path' => 'application/modules/Sescontest',
        'title' => 'SNS - Advanced Contests Plugin',
        'description' => 'SNS - Advanced Contests Plugin',
        'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
        'callback' => array(
            'path' => 'application/modules/Sescontest/settings/install.php',
            'class' => 'Sescontest_Installer',
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
            'application/modules/Sescontest',
            'application/modules/Sescontestjoinfees',
            'application/modules/Sescontestjurymember',
            'application/modules/Sescontestpackage',
            
        ),
        'files' => array(
            'application/languages/en/sescontest.csv',
            'application/languages/en/sescontestjoinfees.csv',
            'application/languages/en/sescontestjurymember.csv',
            'application/languages/en/sescontestpackage.csv',
            'sesrecord.php',
        ),
    ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sescontest_Plugin_Core',
        ),
        array(
            'event' => 'onRenderLayoutDefaultSimple',
            'resource' => 'Sescontest_Plugin_Core'
        ),
        array(
            'event' => 'onCoreCommentCreateAfter',
            'resource' => 'Sescontest_Plugin_Core'
        ),
        array(
            'event' => 'onCoreLikeCreateAfter',
            'resource' => 'Sescontest_Plugin_Core'
        ),
        array(
            'event' => 'onCoreLikeDeleteBefore',
            'resource' => 'Sescontest_Plugin_Core'
        ),
        array(
            'event' => 'getAdminNotifications',
            'resource' => 'Sescontest_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'sescontest_category',
        'contest',
        'sescontest_dashboards',
        'participant',
        'sescontest_follower',
        'sescontest_favourite',
        'sescontest_media',
        'sescontest_integrateothermodule'
    ),
    'routes' => array(
        'sescontest_profile' => array(
            'route' => $contestRoute . '/:id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'profile',
                'action' => 'index',
            ),
        ),
        'sescontest_dashboard' => array(
            'route' => $contestsRoute . '/dashboard/:action/:contest_id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'dashboard',
                'action' => 'edit',
            ),
        ),
        'sescontest_general' => array(
            'route' => $contestsRoute . '/:action/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'index',
                'action' => 'welcome',
            ),
            'reqs' => array(
                'action' => '(sesbackuplandingppage|pinboard|ongoing|comingsoon|ended|welcome|browse|create|delete|manage|home|edit|delete|winner|entries|tags|upgrade|packages|transactions)',
            )
        ),
        'sescontest_join_contest' => array(
            'route' => $contestRoute . '/:action/:contest_id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'join',
                'action' => 'create',
            ),
            'reqs' => array(
                'action' => '(create|record|edit|delete)',
            )
        ),
        'sescontest_entry_profile' => array(
            'route' => $contestRoute . '/:contest_id/entry/:id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'join',
                'action' => 'view',
            ),
        ),
        'sescontest_entry_vote' => array(
            'route' => $contestRoute . '/:contest_id/vote/:id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'vote',
                'action' => 'vote',
            ),
        ),
        'sescontest_category_view' => array(
            'route' => $contestsRoute . '/category/:category_id/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'category',
                'action' => 'index',
            )
        ),
        'sescontest_category' => array(
            'route' => $contestsRoute . '/categories/:action/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'category',
                'action' => 'browse',
            ),
            'reqs' => array(
                'action' => '(index|browse)',
            )
        ),
        'sescontest_media' => array(
            'route' => $contestRoute . '/media/:action/*',
            'defaults' => array(
                'module' => 'sescontest',
                'controller' => 'media'
            ),
            'reqs' => array(
                'action' => '(photo|text|video|audio)',
            )
        ),
    )
);
