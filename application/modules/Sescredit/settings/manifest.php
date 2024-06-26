<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2019-01-18 00:00:00 SocialEngineSolutions $
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
$creditsRoute = 'credits';
if (empty($request) || !($module1 == 'default' && (strpos($_SERVER['REQUEST_URI'], '/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $creditsRoute = $setting->getSetting('sescredit.manifest', 'credits');
}

return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sescredit',
        'version' => '6.5.1',
        'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '6.5.1',
            ),
        ),
        'path' => 'application/modules/Sescredit',
          'title' => 'SNS - Credits & Activity / Reward Points Plugin',
        'description' => 'SNS - Credits & Activity / Reward Points Plugin',
        'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
		'thumb' => 'application/modules/Sescredit/externals/images/thumb.png',
        'callback' => array(
            'path' => 'application/modules/Sescredit/settings/install.php',
            'class' => 'Sescredit_Installer',
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
            0 => 'application/modules/Sescredit',
        ),
        'files' =>
        array(
            0 => 'application/languages/en/sescredit.csv',
        ),
    ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            'event' => 'onActivityActionCreateAfter',
            'resource' => 'Sescredit_Plugin_Core',
        ),
        array(
            'event' => 'onItemDeleteBefore',
            'resource' => 'Sescredit_Plugin_Core',
        ),
        array(
            'event' => 'onUserCreateAfter',
            'resource' => 'Sescredit_Plugin_Core',
        ),
        array(
            'event' => 'onUserLoginAfter',
            'resource' => 'Sescredit_Plugin_Core',
        ),
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sescredit_Plugin_Core',
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'sescredit_badge',
        'sescredit_credit',
        'sescredit_offer',
        'sescredit_orderdetail',
        'sescredit_transaction',
        'sescredit_gateway',
        'sescredit_value',
        'sescredit_upgradeuser',
        'sescredit_modulesetting',
        'sescredit_detail',
        'sescredit_rewardpoint',
        'sescredit_managemodule',
        'sescredit_usergateway',
        'sescredit_userpayrequest',
    ),
    'routes' => array(
        'sescredit_general' => array(
            'route' => $creditsRoute . '/:action/*',
            'defaults' => array(
                'module' => 'sescredit',
                'controller' => 'index',
                'action' => 'manage',
            ),
            'reqs' => array(
                'action' => '(manage|transaction|earn-credit|invite|signup|show-detail|show-member-level|help|badges|leaderboard|payment-requests|payment-request|delete-payment|detail-payment|payment-transaction|account-details)',
            )
        ),
    )
);
