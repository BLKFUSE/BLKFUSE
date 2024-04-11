<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manifest.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$newsRoute = "news";
$module1 = null;
$controller = null;
$action = null;
$request = Zend_Controller_Front::getInstance()->getRequest();
if (!empty($request)) {
  $module1 = $request->getModuleName();
  $action = $request->getActionName();
  $controller = $request->getControllerName();
}
if (empty($request) || !((strpos($_SERVER['REQUEST_URI'],'/install/') !== false))) {
  $setting = Engine_Api::_()->getApi('settings', 'core');
  $newsRoute = $setting->getSetting('sesnews.news.manifest', 'news');
  $newsRoute = $setting->getSetting('sesnews.news.manifest', 'news');
}

return array(
  // Package -------------------------------------------------------------------
  'package' => array(
    'type' => 'module',
    'name' => 'sesnews',
    //'sku' => 'sesnews',
    'version' => '6.5.1',
    'dependencies' => array(
        array(
            'type' => 'module',
            'name' => 'core',
            'minVersion' => '6.5.1',
        ),
    ),
    'path' => 'application/modules/Sesnews',
    'title' => 'SNS - News / RSS Importer & Aggregator Plugin',
    'description' => 'SNS - News / RSS Importer & Aggregator Plugin',
    'author' => '<a href="https://socialnetworking.solutions" style="text-decoration:underline;" target="_blank">SocialNetworking.Solutions</a>',
	'thumb' => 'application/modules/Sesnews/externals/images/thumb.png',
    'actions' => array(
       'install',
       'upgrade',
       'refresh',
       'enable',
       'disable',
     ),
    'callback' => array(
      'path' => 'application/modules/Sesnews/settings/install.php',
      'class' => 'Sesnews_Installer',
    ),
    'directories' => array(
      'application/modules/Sesnews',
    ),
    'files' => array(
      'application/languages/en/sesnews.csv',
    ),
  ),
  // Compose
  'composer' => array(
//     'sesnews' => array(
//       'script' => array('_composeNews.tpl', 'sesnews'),
//       'auth' => array('sesnews_news', 'create'),
//     ),
  ),
  // Hooks ---------------------------------------------------------------------
  'hooks' => array(
    array(
      'event' => 'onStatistics',
      'resource' => 'Sesnews_Plugin_Core'
    ),
		array(
			'event' => 'onRenderLayoutDefault',
			'resource' => 'Sesnews_Plugin_Core',
		),
		array(
				'event' => 'onRenderLayoutDefaultSimple',
				'resource' => 'Sesnews_Plugin_Core'
		),
		array(
				'event' => 'onRenderLayoutMobileDefault',
				'resource' => 'Sesnews_Plugin_Core'
		),
		array(
				'event' => 'onRenderLayoutMobileDefaultSimple',
				'resource' => 'Sesnews_Plugin_Core'
		),
    array(
      'event' => 'onUserDeleteBefore',
      'resource' => 'Sesnews_Plugin_Core',
    ),
  ),
  // Items ---------------------------------------------------------------------
  'items' => array(
    'sesnews',
    'sesnews_parameter',
    'sesnews_news',
    'sesnews_category',
    'sesnews_dashboards',
    'sesnews_album',
    'sesnews_photo',
    'sesnews_review',
    'sesnews_categorymapping',
    'sesnews_integrateothermodule',
    'sesnews_rss','sesnews_url',
  ),
  // Routes --------------------------------------------------------------------
  'routes' => array(
    // Public
    'sesnews_specific' => array(
      'route' => $newsRoute.'/:action/:news_id/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'index',
        'action' => 'index',
      ),
      'reqs' => array(
        'news_id' => '\d+',
        'action' => '(delete|edit)',
      ),
    ),
    'sesnews_generalrss' => array(
      'route' => $newsRoute.'/rss/:action/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'rss',
        'action' => 'browse',
      ),
      'reqs' => array(
        'action' => '(browse|create|manage|edit|delete)',
      ),
    ),
    'sesnews_viewrss' => array(
      'route' => $newsRoute.'rss/:rss_id/:slug/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'rss',
        'action' => 'view',
        'slug' =>'',
      ),
    ),

    'sesnews_general' => array(
      'route' => $newsRoute.'/:action/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'index',
        'action' => 'welcome',
      ),
      'reqs' => array(
        'action' => '(welcome|index|browse|create|manage|style|tag|upload-photo|link-news|news-request|tags|home|locations|rss-feed|contributors)',
      ),
    ),

    'sesnews_extended' => array(
        'route' => 'Sesnews/:controller/:action/*',
        'defaults' => array(
            'module' => 'sesnews',
            'controller' => 'index',
            'action' => 'index',
        ),
        'reqs' => array(
            'controller' => '\D+',
            'action' => '\D+',
        )
    ),
    'sesnews_view' => array(
      'route' => $newsRoute.'/:user_id/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'index',
        'action' => 'list',
      ),
      'reqs' => array(
        'user_id' => '\d+',
      ),
    ),
		'sesnews_category_view' => array(
		    'route' => $newsRoute.'/category/:category_id/*',
		    'defaults' => array(
		        'module' => 'sesnews',
		        'controller' => 'category',
		        'action' => 'index',
		    )
		),
    'sesnews_entry_view' => array(
      'route' => $newsRoute.'/:controller/:action/:news_id/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'index',
        'action' => 'view',
      //  'slug' => '',
      ),
      'reqs' => array(
      ),
    ),
    'sesnewsreview_extended' => array(
        'route' => $newsRoute.'/reviews/:action/:news_id/*',
        'defaults' => array(
            'module' => 'sesnews',
            'controller' => 'review',
            'action' => 'index',
        ),
        'reqs' => array(
            'news_id' => '\d+',
            'action' => '(create)',
        )
    ),
            'sesnews_specific_album' => array(
					'route' =>  'news-album/:action/:album_id',
					'defaults' => array(
							'module' => 'sesnews',
							'controller' => 'album',
							'action' => 'view',
						),
							'reqs' => array(
							'album_id' => '\d+'
						)
        ),
    'sesnewsreview_view' => array(
        'route' => $newsRoute.'/reviews/:action/:review_id/:slug',
        'defaults' => array(
            'module' => 'sesnews',
            'controller' => 'review',
            'action' => 'view',
            'slug' => ''
        ),
        'reqs' => array(
            'action' => '(edit|view|delete)',
            'review_id' => '\d+'
        )
    ),
            'sesnews_dashboard' => array(
            'route' => $newsRoute.'/dashboard/:action/:news_id/*',
            'defaults' => array(
                'module' => 'sesnews',
                'controller' => 'dashboard',
                'action' => 'edit',
            ),
            'reqs' => array(
                'action' => '(edit|edit-photo|remove-photo|contact-information|style|seo|news-role|save-news-admin|fields|upgrade|edit-location)',
            )
        ),
                'sesnews_review' => array(
            'route' => $newsRoute.'/browse-review/:action/*',
            'defaults' => array(
                'module' => 'sesnews',
                'controller' => 'review',
                'action' => 'browse'
            ),
        ),
                'sesnews_category' => array(
            'route' => $newsRoute . '/categories/:action/*',
            'defaults' => array(
                'module' => 'sesnews',
                'controller' => 'category',
                'action' => 'browse',
            ),
            'reqs' => array(
                'action' => '(index|browse)',
            )
        ),
        'sesnews_import' => array(
      'route' => $newsRoute.'/import/:action/*',
      'defaults' => array(
        'module' => 'sesnews',
        'controller' => 'import',
        'action' => 'index',
      ),
      'reqs' => array(
                'action' => '(index)',
            )

    ),
  ),
);
