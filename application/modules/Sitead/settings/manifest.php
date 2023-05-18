<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: manifest.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
return array(
    'package' =>
    array(
        'type' => 'module',
        'name' => 'sitead',
        'sku' => 'seao-sitead',
        'version' => '5.0.1',
        'path' => 'application/modules/Sitead',
        'title' => 'Advertisements, Community Ads & Marketing Campaigns Plugin',
        'description' => 'Advertisements, Community Ads & Marketing Campaigns Plugin',
        'author' => '<a href="http://www.socialengineaddons.com" style="text-decoration:underline;" target="_blank">SocialEngineAddOns</a>',
        'date' => 'Friday, 09 Jul 2018 18:33:08 +0000',
        'copyright' => 'Copyright 2018-2019 BigStep Technologies Pvt. Ltd.',
        'actions' =>
        array(
            0 => 'install',
            1 => 'upgrade',
            2 => 'refresh',
            3 => 'enable',
            4 => 'disable',
        ),
        'callback' => array(
            'path' => 'application/modules/Sitead/settings/install.php',
            'class' => 'Sitead_Installer',
        ),
        'directories' => array(
            'application/modules/Sitead',
        ),
        'files' => array(
            'application/languages/en/sitead.csv',
        ),
    ),
    // Hooks ---------------------------------------------------------------------
    'hooks' => array(
        array(
            // 'event' => 'addActivity',
            'event' => 'onRenderLayoutDefault',
            'resource' => 'Sitead_Plugin_Core'
        ),
        array(
            'event' => 'onUserDeleteBefore',
            'resource' => 'Sitead_Plugin_Core'
        ),
        array(
            'event' => 'onSiteadAdcampaignDeleteBefore',
            'resource' => 'Sitead_Plugin_Core'
        ),
        array(
            'event' => 'onSiteadUseradDeleteBefore',
            'resource' => 'Sitead_Plugin_Core'
        ),
        array(
            'event' => 'onSitereviewListingtypeCreateAfter',
            'resource' => 'Sitead_Plugin_Core'
        ),
    ),
    // Items ---------------------------------------------------------------------
    'items' => array(
        'option',
        'meta',
        'map',
        'value',
        'target',
        'package',
        'usertarget',
        'userads',
        'adcampaign',
        'sitead_adcancel',
        'sitead_pagesetting',
        'sitead_module',
        'sitead_infopage',
        'sitead_faq',
        'sitead_like',
        'adstatistic',
        'sitead_gateway',
        'sitead_transaction',
        'sitead_storie',
        'sitead_adtype',
        'sitead_adsinfo',
        'sitead_category',
        'sitead_location',
        'activity_actions',
    ),
    // Routes --------------------------------------------------------------------
    'routes' => array(
        // Public+

        'sitead_display' => array(
            'route' => 'ads/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'display',
                'action' => 'adboard',
                'page' => 1
            )
        ),
        'sitead_help_and_learnmore' => array(
            'route' => 'ads/help-and-learnmore/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'display',
                'action' => 'help-and-learnmore',
            )
        ),
        // User
        'sitead_listpackage' => array(
            'route' => 'ads/package/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'index',
            )
        ),
        'sitead_create' => array(
            'route' => 'ads/create/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'create'
            )
        ),
        'sitead_edit' => array(
            'route' => 'ads/edit/:id',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'edit',
                'id' => '0'
            ),
            'reqs' => array(
                'id' => '\d+'
            )
        ),
        'sitead_copyad' => array(
            'route' => 'ads/create/state/:copy/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'edit',
            ),
            'reqs' => array(
                'copy' => 'copy'
            )
        ),
        'sitead_targetdetails' => array(
            'route' => 'ads/targetdetails/:id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'target-details'
            )
        ),
        'sitead_webpagereport' => array(
            'route' => 'ads/statistics/export-webpage/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'statistics',
                'action' => 'export-webpage'
            )
        ),
        'sitead_reports' => array(
            'route' => 'ads/statistics/export-report/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'statistics',
                'action' => 'export-report'
            )
        ),
        'sitead_campaigns' => array(
            'route' => 'ads/campaigns/:action/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'statistics',
                'action' => 'index'
            )
        ),
        'sitead_ads' => array(
            'route' => 'ads/campaignads/:action/:ad_subject/:adcampaign_id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'statistics',
                'action' => 'browse-ad',
                'ad_subject' => 'campaign',
                'adcampaign_id' => 0
            ),
            'reqs' => array(
                'adcampaign_id' => '\d+'
            )
        ),
        'sitead_userad' => array(
            'route' => 'ads/detail/:action/:ad_subject/:ad_id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'statistics',
                'action' => 'view-ad',
                'ad_subject' => 'ad',
                'ad_id' => 0
            ),
            'reqs' => array(
                'ad_id' => '\d+'
            )
        ),
        'sitead_payment' => array(
            'route' => 'ads/payment/',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'payment',
                'action' => 'index',
            ),
        ),
        'sitead_process_payment' => array(
            'route' => 'ads/payment/process',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'payment',
                'action' => 'process',
            ),
        ),
        'siteade_renew' => array(
            'route' => 'ads/renew/:id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'renew',
            )
        ),
        'sitead_editcamp' => array(
            'route' => 'ads/editcamp/:id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'editcamp',
            ),
        ),
        'sitead_deleteselectedcamp' => array(
            'route' => 'ads/deleteselectedcamp/',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'deleteselectedcamp',
            )
        ),
        'sitead_deletecamp' => array(
            'route' => 'ads/deletecamp/:id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'deletecamp',
            ),
        ),
        'sitead_deletead' => array(
            'route' => 'ads/deletead/:id/*',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'index',
                'action' => 'deletead',
            ),
        ),
        'sitead_adredirect' => array(
            'route' => 'ads/redirect/:adId',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'display',
                'action' => 'ad-redirect',
            )
        ),
        'sitead_help' => array(
            'route' => 'ads/help-and-learnmore/page_id/:page_id',
            'defaults' => array(
                'module' => 'sitead',
                'controller' => 'display',
                'action' => 'help-and-learnmore',
            )
        ),
    ),
);
