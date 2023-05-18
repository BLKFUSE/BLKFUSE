<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$seslocation = array();
if (Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('seslocation')) {
  $seslocation = array(
      'Radio',
      'locationEnable',
      array(
          'label' => 'Do you want to show content in this widget based on the Location chosen by users on your website? (This is dependent on the “Location Based Member & Content Display Plugin”. Also, make sure location is enabled and entered for the content in this plugin. If setting is enabled and the content does not have a location, then such content will not be shown in this widget.)
',
          'multiOptions' => array('1' => 'Yes', '0' => 'No'),
          'value' => 0
      ),
  );
}
$categories = array();
if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.pluginactivated',0)) {
  $categories = Engine_Api::_()->getDbTable('categories', 'sescommunityads')->getCategoriesAssoc();
  $categories = array('' => '') + $categories;
}

return array(
	array(
		'title' => 'SNS - Community Ads - Browse Menu',
		'description' => 'This is the menu widget for Ads which will show at every page of this plugin.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.browse-menu',
		'autoEdit' => false,
		'adminForm' => array(
		  'elements' => array (
			array(
			  'Radio',
			  'createButton',
			  array(
				'label' => "Enable Create Ad Button? Note: You can  disable 'Create Ad' menu from menu editor if you don't want it twice in navigation menu on your website.",
				'multiOptions' => array(
				  '1' => 'Yes',
				  '0' => 'No',
				),
				'value' => '1',
			  )
			),
		  ),
		),
	),
	array(
		'title' => 'SNS - Community Ads - Ads Payment Status',
		'description' => 'This widget will display the status of your Payment as successful or pending at the Ad View Page. It get displayed after the payment has been made for the Advertisement.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.payment-status',
		'autoEdit' => false,
	),

  array(

		'title' => 'SNS - Community Ads - Browse Search',
		'description' => 'Display Search Form in the Community Advertisements Browse page through which you can search any Ad.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.browse-search',
		'autoEdit' => true,
		'adminForm' => array(
				'elements' => array(
            array(
            'Select',
            'view_type',
            array(
              'label' => "Choose the View Type.",
              'multiOptions' => array(
                'horizontal' => 'Horizontal',
                'vertical' => 'Vertical'
              ),
              'value' => 'vertical',
            )
          ),
          array(
            'MultiCheckbox',
            'search_type',
            array(
              'label' => "Choose options to be shown in 'Browse By' search fields.",
              'multiOptions' => array(
                'recentlySPcreated' => 'Recently Created',
                'mostSPviewed' => 'Most Viewed',
                'featured' => 'Only Featured',
                'sponsored' => 'Only Sponsored',
              ),
            )
          ),
          array(
            'Select',
            'default_search_type',
            array(
              'label' => "Default 'Browse By' search field.",
              'multiOptions' => array(
                'recentlySPcreated' => 'Recently Created',
                'mostSPviewed' => 'Most Viewed',
                'featured' => 'Only Featured',
                'sponsored' => 'Only Sponsored'
              ),
            )
          ),
           array(
            'Radio',
            'content_option',
            array(
              'label' => "Show 'Content Type' search field?",
              'multiOptions' => array(
                'yes' => 'Yes',
                'no' => 'No'
              ),
              'value' => 'yes',
            )
          ),
          array(
            'Radio',
            'friend_show',
            array(
              'label' => "Show 'View' search field?",
              'multiOptions' => array(
                'yes' => 'Yes',
                'no' => 'No'
              ),
              'value' => 'yes',
            )
          ),
            array(
								'Radio',
								'categories',
								array(
										'label' => 'Show \'Categories\' search field?',
										'value' => 'yes',
										'multiOptions' => array('yes'=>'Yes','no'=>'No'),
								)
						),
            array(
								'Radio',
								'location',
								array(
										'label' => 'Show \'Location\' search field (Dependend on Sesmember plugin)?',
										'value' => 'yes',
										'multiOptions' => array('yes'=>'Yes','no'=>'No'),
								)
						),
				)
		)

  ),
  array(
		'title' => 'SNS - Community Ads - Sidebar Widget Ads',
		'description' => 'This widget displays ads in the left/right sidebar columns of your website.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.sidebar-widget-ads',
		'autoEdit' => true,
		'adminForm' => array(
				'elements' => array(
            $seslocation,
            array(
								'Select',
								'category',
								array(
										'label' => 'Choose category you want to display in this widge.',
										'value' => '',
										'multiOptions' => $categories,
								)
						),

            array(
								'Select',
								'featured_sponsored',
								array(
										'label' => 'Choose criteria from below to show in this widget.',
										'value' => '3',
										'multiOptions' => array('1'=>'Featured','2'=>'Sponsored','4'=>'Featured & Sponsored','3'=>'All ads'),
								)
            ),

            array(
								'Text',
								'limit',
								array(
										'label' => 'Count (number of Advertisement to show)',
										'value' => 10,
										'validators' => array(
												array('Int', true),
												array('GreaterThan', true, array(0)),
										),
								)
						),
				)
		)
	),
	array(
		'title' => 'SNS - Community Ads - Browse Ads',
		'description' => 'Display all the Ads created on your website. The recommended page for this widget is "Communities Advertisements Plugin - Browse Page".',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.browse-ads',
		'autoEdit' => true,
		'adminForm' => array(
				'elements' => array(
            $seslocation,
            array(
								'Select',
								'category',
								array(
										'label' => 'Choose category you want to display in this widge.',
										'value' => '',
										'multiOptions' => $categories,
								)
						),

            array(
								'Select',
								'featured_sponsored',
								array(
										'label' => 'Choose criteria from below to show in this widget.',
										'value' => '3',
										'multiOptions' => array('1'=>'Featured','2'=>'Sponsored','4'=>'Featured & Sponsored','3'=>'All ads'),
								)
            ),

            array(
								'Text',
								'limit',
								array(
										'label' => 'Count (number of campaign to show)',
										'value' => 10,
										'validators' => array(
												array('Int', true),
												array('GreaterThan', true, array(0)),
										),
								)
						),
						array(
								'Radio',
								'pagging',
								array(
										'label' => "Do you want the campaign to be auto-loaded when users scroll down the page?",
										'multiOptions' => array(
												'auto_load' => 'Yes, Auto Load',
												'button' => 'No, show \'View more\' link.',
												'pagging' => 'No, show \'Pagination\'.'
										),
										'value' => 'auto_load',
								)
						)
				)
		)
	),
	array(
		'title' => 'SNS - Community Ads - Manage Ads',
		'description' => 'This widget displays ads to the ad owners on Manage Ads page.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.manage-ads',
		'autoEdit' => true,
		'adminForm' => array(
				'elements' => array(
						array(
								'Text',
								'limit',
								array(
										'label' => 'Count (number of campaign to show)',
										'value' => 10,
										'validators' => array(
												array('Int', true),
												array('GreaterThan', true, array(0)),
										),
								)
						),
						array(
								'Radio',
								'pagging',
								array(
										'label' => "Do you want the campaign to be auto-loaded when users scroll down the page?",
										'multiOptions' => array(
												'auto_load' => 'Yes, Auto Load',
												'button' => 'No, show \'View more\' link.',
												'pagging' => 'No, show \'Pagination\'.'
										),
										'value' => 'auto_load',
								)
						)
				)
		)
	),
	array(
			'title' => 'SNS - Community Ads - Manage Ads Campaign',
			'description' => 'This widget displays ad campaigns to campaign owners.',
			'category' => 'SNS - Community Advertisements Plugin',
			'type' => 'widget',
			'name' => 'sescommunityads.manage-campaign',
			'autoEdit' => true,
			'adminForm' => array(
					'elements' => array(
							array(
									'Text',
									'limit',
									array(
											'label' => 'Count (number of campaign to show)',
											'value' => 10,
											'validators' => array(
													array('Int', true),
													array('GreaterThan', true, array(0)),
											),
									)
							),
							array(
									'Radio',
									'pagging',
									array(
											'label' => "Do you want the campaign to be auto-loaded when users scroll down the page?",
											'multiOptions' => array(
													'auto_load' => 'Yes, Auto Load',
													'button' => 'No, show \'View more\' link.',
													'pagging' => 'No, show \'Pagination\'.'
											),
											'value' => 'auto_load',
									)
							)
					)
			)
	),
  array(
		'title' => 'SNS - Community Ads - Manage Campaigns Stats',
		'description' => 'This widget displays campaign statistics to ad owners.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.campaign-stats',
		'autoEdit' => false,
    'adminForm' => 'Sescommunityads_Form_Admin_Stats',

	),
  array(
		'title' => 'SNS - Community Ads - Ads View Page Stats',
		'description' => 'The recommended page for this widget is Ads View Page. The statistics for the Ads in a Campaign will get displayed in this widget.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.ads-stats',
		'autoEdit' => false,
    'adminForm' => 'Sescommunityads_Form_Admin_Stats',

	),
	array(
		'title' => 'SNS - Community Ads - Reports',
		'description' => 'This widget displays the report download option to the ad owners on your website.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.report',
		'autoEdit' => false,
	),
	array(
		'title' => 'SNS - Community Ads - Help And Learn',
		'description' => 'This widget displays Help & Learn center of ads on your website.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.help-and-learn',
		'autoEdit' => false,
	),
	array(
		'title' => 'SNS - Community Ads - View Ads',
		'description' => 'This widget displays ads on your website.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.view',
		'autoEdit' => false,
	),
	array(
		'title' => 'SNS - Community Ads - Want More Customers',
		'description' => 'This widget displays "Want More Customers" block to the users of your website. From this block they can see link to create new ad and get started with ads on your site.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.want-more-customers',
		'autoEdit' => false,
	),
);
