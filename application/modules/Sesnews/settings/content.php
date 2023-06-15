<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


  $socialshare_enable_plusicon = array(
      'Select',
      'socialshare_enable_plusicon',
      array(
          'label' => "Enable More Icon for social share buttons?",
          'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
          ),
      )
  );
  $socialshare_icon_limit = array(
    'Text',
    'socialshare_icon_limit',
    array(
      'label' => 'Count (number of social sites to show). If you enable More Icon, then other social sharing icons will display on clicking this plus icon.',
      'value' => 2,
    ),
  );

$headScript = new Zend_View_Helper_HeadScript();
$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');


$categories = array();
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.pluginactivated')) {
  $categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc(array('module'=>true));
}

$viewType = array(
  'MultiCheckbox',
  'enableTabs',
  array(
      'label' => "Choose the View Type.",
      'multiOptions' => array(
      'list' => 'List View',
      'grid' => 'Grid View',
      'pinboard' => 'Pinboard View',
      'map' => 'Map View',
    ),
  )
);

$defaultType = array(
  'Select',
  'openViewType',
  array(
    'label' => "Choose the view type which you want to display by default. (Settings will apply, if you have selected more than one option in above tab.)",
    'multiOptions' => array(
       'list' => 'List View',
      'grid' => 'Grid View',
      'pinboard' => 'Pinboard View',
      'map' => 'Map View',
    ),
    'value' => 'list',
  )
);

$showCustomData = array(
  'MultiCheckbox',
  'show_criteria',
  array(
    'label' => "Choose the options that you want to be displayed in this widget.",
    'multiOptions' => array(
      'featuredLabel' => 'Featured Label',
      'sponsoredLabel' => 'Sponsored Label',
      'verifiedLabel' => 'Verified Label',
      'hotLabel' => 'Hot Label',
      'newLabel' => 'New Label',
      'favouriteButton' => 'Favourite Button',
      'likeButton' => 'Like Button',
      'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
      'like' => 'Likes Count',
      'favourite' => 'Favorites Count',
      'comment' => 'Comments Count',
      'ratingStar' => 'Ratings Star',
      'rating' => 'Ratings Count',
      'view' => 'Views Count',
      'title' => 'News Title',
      'category' => 'Category',
      'by' => 'News Owner Name',
      'readmore' => 'Read More Button',
      'creationDate' => 'Creation Date',
      'location'=> 'Location',
      'descriptionlist' => 'Description (In List View)',
      'descriptiongrid' => 'Description (In Grid View)',
      'descriptionpinboard' => 'Description (In Pinboard View)',
      'enableCommentPinboard'=>'Enable commenting in Pinboard View',
    ),
    'escape' => false,
  )
);
$pagging = array(
  'Radio',
  'pagging',
  array(
    'label' => "Do you want the news to be auto-loaded when users scroll down the page?",
    'multiOptions' => array(
	'auto_load' => 'Yes, Auto Load',
	'button' => 'No, show \'View more\' link.',
	'pagging' => 'No, show \'Pagination\'.'
    ),
    'value' => 'auto_load',
  )
);
$imageType = array(
    'Select',
    'imageType',
    array(
        'label' => "Choose the shape of Photo.",
        'multiOptions' => array(
            'rounded' => 'Circle',
            'square' => 'Square',
        ),
        'value' => 'square',
    )
);
$photoHeight = array(
    'Text',
    'photo_height',
    array(
        'label' => 'Enter the height of main photo block in Grid Views (in pixels).',
        'value' => '160',
    )
);
$photowidth = array(
    'Text',
    'photo_width',
    array(
        'label' => 'Enter the width of main photo block in Grid Views (in pixels).',
        'value' => '250',
    )
);
$titleTruncationList = array(
  'Text',
  'title_truncation_list',
  array(
    'label' => 'Title truncation limit for List Views.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$titleTruncationGrid = array(
  'Text',
  'title_truncation_grid',
  array(
    'label' => 'Title truncation limit for Grid Views.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$titleTruncationPinboard = array(
  'Text',
  'title_truncation_pinboard',
  array(
    'label' => 'Title truncation limit for Pinboard View.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$DescriptionTruncationList = array(
  'Text',
  'description_truncation_list',
  array(
    'label' => 'Description truncation limit for List Views.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$DescriptionTruncationGrid = array(
  'Text',
  'description_truncation_grid',
  array(
    'label' => 'Description truncation limit for Grid Views.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$DescriptionTruncationPinboard = array(
  'Text',
  'description_truncation_pinboard',
  array(
    'label' => 'Description truncation limit for Pinboard View.',
    'value' => 45,
    'validators' => array(
      array('Int', true),
      array('GreaterThan', true, array(0)),
    )
  )
);
$heightOfContainerList = array(
  'Text',
  'height_list',
  array(
    'label' => 'Enter the height of main photo block in List Views (in pixels).',
    'value' => '230',
  )
);
$widthOfContainerList = array(
  'Text',
  'width_list',
  array(
    'label' => 'Enter the width of main photo block in List Views (in pixels).',
    'value' => '260',
  )
);
$heightOfContainerGrid = array(
  'Text',
  'height_grid',
  array(
    'label' => 'Enter the height of one block in Grid Views (in pixels).',
    'value' => '270',
  )
);
$widthOfContainerGrid = array(
  'Text',
  'width_grid',
  array(
    'label' => 'Enter the width of one block in Grid Views (in pixels).',
    'value' => '389',
  )
);
$widthOfContainerPinboard = array(
  'Text',
  'width_pinboard',
  array(
    'label' => 'Enter the width of one block in Pinboard View (in pixels).',
    'value' => '300',
  )
);
$heightOfContainer = array(
    'Text',
    'height',
    array(
        'label' => 'Enter the height of one block (in pixels).',
        'value' => '160',
    )
);
$widthOfContainer = array(
    'Text',
    'width',
    array(
        'label' => 'Enter the width of one block (in pixels).',
        'value' => '250',
    )
);

return array(
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - People Like News',
    'description' => 'Placed on  a News view page.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.people-like-item',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'limit_data',
          array(
            'label' => 'Show view more after how much data?.',
            'value' => 11,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
      )
    )
  ),
	array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Sponsored / Verified News Carousel',
    'description' => "Disaplys carousel of news as configured by you based on chosen criteria for this widget. You can also choose to show News of specific categories in this widget.",
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.featured-sponsored-verified-category-carousel',
    'adminForm' => array(
      'elements' => array(
			array(
	  'Select',
	  'carousel_type',
	  array(
	    'label' => 'Choose the view type. [In Slick View, first and last news will partially show in the carousel.]',
	    'multiOptions' => array(
	      "1" => "Slick View",
	      "2" => "Simple View"
	    )
	  ),
	  'value' => '1'
	),
	array(
	  'Text',
	  'slidesToShow',
	    array(
	    'label' => 'Enter number of slides to be shown at once. (This setting will only work when "Simple View" is selected for above setting).',
	    'value' => '3',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Select',
	  'category',
	  array(
	    'label' => 'Choose the category.',
	    'multiOptions' => $categories
	  ),
	  'value' => ''
	),

	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Display Content",
	    'multiOptions' => array(
          '0' => 'All including Featured and Sponsored',
	      '1' => 'Only Featured',
	      '2' => 'Only Sponsored',
	      '6' => 'Only Verified',
	      '8' => 'Only Hot',
	      '9' => 'Only New',
	    ),
	    'value' => 5,
	  )
	),
	array(
		'Select',
		'order',
		array(
			'label' => 'Duration criteria for the news to be shown in this widget',
			'multiOptions' => array(
				'' => 'All News',
				'week' => 'This Week News',
				'month' => 'This Month News',
			),
			'value' => '',
		)
	),
	array(
	  'Select',
	  'info',
	  array(
	    'label' => 'Choose Popularity Criteria.',
	    'multiOptions' => array(
	      "recently_created" => "Recently Created",
	      "most_viewed" => "Most Viewed",
	      "most_liked" => "Most Liked",
	      "most_rated" => "Most Rated",
	      "most_commented" => "Most Commented",
	      "most_favourite" => "Most Favourite",
	    )
	  ),
	  'value' => 'recently_created',
	),
	array(
	  'Select',
	  'isfullwidth',
	  array(
	    'label' => 'Do you want to show carousel in full width?',
	    'multiOptions'=>array(
	      1=>'Yes',
	      0=>'No'
	    ),
	    'value' => 1,
	  )
	),
		array(
	  'Select',
	  'autoplay',
	  array(
	    'label' => "Do you want to enable autoplay of news?",
	    'multiOptions' => array(
	      1=>'Yes',
	      0=>'No'
	    ),
	  ),
	),
	array(
	  'Text',
	  'speed',
	    array(
	    'label' => 'Delay time for next news when you have enabled autoplay.',
	    'value' => '2000',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show in this widget.",
	    'multiOptions' => array(
	      'like' => 'Likes Count',
	      'comment' => 'Comments Count',
	      'favourite' => 'Favourites Count',
	      'view' => 'Views Count',
	      'title' => 'News Title',
	      'by' => 'News Owner\'s Name',
        'rating' =>'Rating Count',
        'ratingStar' =>'Rating Stars',
        'featuredLabel' => 'Featured Label',
        'sponsoredLabel' => 'Sponsored Label',
        'verifiedLabel' => 'Verified Label',
        'hotLabel' => "Hot Label",
        'newLabel' => "New Label",
        'favouriteButton' => 'Favourite Button',
        'likeButton' => 'Like Button',
	      'category' => 'Category',
	      'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
	      'creationDate' => 'Show Publish Date',
	    ),
	    'escape' => false,
	  )
	),
	$socialshare_enable_plusicon,
	$socialshare_icon_limit,
	array(
	  'Text',
	  'title_truncation',
	  array(
	    'label' => 'News title truncation limit.',
	    'value' => 45,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'height',
	  array(
	    'label' => 'Enter the height of one block (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'width',
	  array(
	    'label' => 'Enter the width of one block (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'limit_data',
	  array(
	    'label' => 'Count (number of news to show).',
	    'value' => 5,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
      )
    ),
	),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Tabbed widget for Popular News',
    'description' => 'Displays a tabbed widget for popular news on your website based on various popularity criterias. Edit this widget to choose tabs to be shown in this widget. This widget can be placed anywhere on your website.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.tabbed-widget-news',
    'requirements' => array(
      'subject' => 'news',
    ),
    'adminForm' => 'Sesnews_Form_Admin_Tabbed',
  ),
  /*New Feature Blocks*/

  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Explore News',
    'description' => 'Footer Feature Block for Welcome Page',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.explore-news',
    'autoEdit' => true,
    'requirements' => array(
        'no-subject',
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Footer Feature Block',
    'description' => 'Footer Feature Block for Welcome Page',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.footer-feature-block',
    'autoEdit' => true,
    'requirements' => array(
        'no-subject',
    ),
  ),

  /*New Feature Blocks Ends*/

	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Profile News',
		'description' => 'Displays a member\'s news entries on their profiles. The recommended page for this widget is "Member Profile Page"',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.profile-sesnews',
		'requirements' => array(
			'subject' => 'user',
		),
		'adminForm' => 'Sesnews_Form_Admin_Tabbed',
	),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Browse RSS',
    'description' => 'Display all rss on your website. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Browse RSS Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.browse-rss',
    'adminForm' => array(
      'elements' => array(
        array(
            'MultiCheckbox',
            'enableTabs',
            array(
                'label' => "Choose the View Type.",
                'multiOptions' => array(
                    'list' => 'List View',
                    'grid' => 'Grid View',
                ),
            ),
        ),
        array(
            'Select',
            'openViewType',
            array(
                'label' => "Choose the view type which you want to display by default. (Settings will apply, if you have selected more than one option in above tab.)",
                'multiOptions' => array(
                    'list' => 'List View',
                    'grid' => 'Grid View',
                ),
                'value' => 'list',
            )
        ),
        array(
            'MultiCheckbox',
            'show_criteria',
            array(
                'label' => "Choose the options that you want to be displayed in this widget.",
                'multiOptions' => array(
                    'title' => 'News Title',
                    'category' => 'Category',
                    'by' => 'News Owner Name',
                    'readmore' => 'Read More Button',
                    'subscribebutton' => 'Subscribe Button',
                    'creationDate' => 'Creation Date',
                    'descriptionlist' => 'Description (In List View)',
                    'descriptiongrid' => 'Description (In Grid View)',
                ),
                'escape' => false,
            ),
        ),
        array(
            'Select',
            'category',
            array(
                'label' => 'Choose the category.',
                'multiOptions' => $categories
            ),
            'value' => ''
        ),
        array(
            'Select',
            'sort',
            array(
                'label' => 'Choose Rss Display Criteria.',
                'multiOptions' => array(
                "recentlySPcreated" => "Recently Created",
                "mostSPviewed" => "Most Viewed",
                ),
            ),
                'value' => 'recentlySPcreated',
        ),
        array(
            'Select',
            'show_item_count',
            array(
                'label' => 'Do you want to show rss count in this widget?',
                'multiOptions' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
                'value' => '0',
            ),
        ),
        $titleTruncationList,
        $titleTruncationGrid,
        $DescriptionTruncationList,
        $DescriptionTruncationGrid,
        $heightOfContainerList,
        $widthOfContainerList,
        $heightOfContainerGrid,
        array(
			'Select',
			'gridblock',
			array(
					'label' => "How many content you want to show in one row in Grid View?",
					'multiOptions' => array(
						'12' => 'One',
						'6' => 'Two',
						'4' => 'Three',
						'3' => 'Four',
								),
						)
				),
        array(
            'Text',
            'limit_data_grid',
            array(
                'label' => 'Count for Grid Views (number of news to show).',
                'value' => 20,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limit_data_list',
            array(
                'label' => 'Count for List Views (number of news to show).',
                'value' => 20,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        $pagging,
      ),
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Browse News',
    'description' => 'Display all news on your website. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Browse News Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.browse-news',
    'requirements' => array(
      'subject' => 'news',
    ),
    'adminForm' => array(
      'elements' => array(
				$viewType,
				$defaultType,
				$showCustomData,
        array(
            'Select',
            'socialshare_enable_listview1plusicon',
            array(
                'label' => "Enable plus (+) icon for social share buttons in List View?",
                'multiOptions' => array(
                  '1' => 'Yes',
                  '0' => 'No',
                ),
            )
        ),
        array(
          'Text',
          'socialshare_icon_listview1limit',
          array(
            'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in List View. Other social sharing icons will display on clicking this plus icon.',
            'value' => 2,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
            ),
          ),
        ),
        array(
            'Select',
            'socialshare_enable_gridview1plusicon',
            array(
                'label' => "Enable plus (+) icon for social share buttons in Grid View?",
                'multiOptions' => array(
                  '1' => 'Yes',
                  '0' => 'No',
                ),
            )
        ),
        array(
          'Text',
          'socialshare_icon_gridview1limit',
          array(
            'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in Grid View. Other social sharing icons will display on clicking this plus icon.',
            'value' => 2,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
            ),
          ),
        ),
        array(
            'Select',
            'socialshare_enable_pinviewplusicon',
            array(
                'label' => "Enable plus (+) icon for social share buttons in Pinboard View?",
                'multiOptions' => array(
                  '1' => 'Yes',
                  '0' => 'No',
                ),
            )
        ),
        array(
          'Text',
          'socialshare_icon_pinviewlimit',
          array(
            'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in Pinboard View. Other social sharing icons will display on clicking this plus icon.',
            'value' => 2,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
            ),
          ),
        ),

        array(
            'Select',
            'socialshare_enable_mapviewplusicon',
            array(
                'label' => "Enable plus (+) icon for social share buttons in Map View?",
                'multiOptions' => array(
                  '1' => 'Yes',
                  '0' => 'No',
                ),
            )
        ),
        array(
          'Text',
          'socialshare_icon_mapviewlimit',
          array(
            'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in Map View. Other social sharing icons will display on clicking this plus icon.',
            'value' => 2,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
            ),
          ),
        ),
        array(
        'Select',
        'category',
          array(
            'label' => 'Choose the category.',
            'multiOptions' => $categories
          ),
          'value' => ''
        ),
        array(
            'Select',
            'sort',
            array(
                'label' => 'Choose News Display Criteria.',
                'multiOptions' => array(
                "recentlySPcreated" => "Recently Created",
                "mostSPviewed" => "Most Viewed",
                "mostSPliked" => "Most Liked",
                "mostSPrated" => "Most Rated",
                "mostSPcommented" => "Most Commented",
                "mostSPfavourite" => "Most Favourite",
                'featured' => 'Only Featured',
                'sponsored' => 'Only Sponsored',
                'verified' => 'Only Verified',
                'hot' => 'Only Hot',
                'latest' => 'Only New',
                ),
            ),
                'value' => 'most_liked',
        ),
        array(
            'Select',
            'show_item_count',
            array(
                'label' => 'Do you want to show news count in this widget?',
                'multiOptions' => array(
                    '1' => 'Yes',
                    '0' => 'No',
                ),
                'value' => '0',
            ),
        ),
        $titleTruncationList,
        $titleTruncationGrid,
        $titleTruncationPinboard,
        $DescriptionTruncationList,
        $DescriptionTruncationGrid,
        $DescriptionTruncationPinboard,
        $heightOfContainerList,
        $widthOfContainerList,
        $heightOfContainerGrid,
        $widthOfContainerPinboard,
				array(
			'Select',
			'gridblock',
			array(
					'label' => "How many content you want to show in one row in Grid View?",
					'multiOptions' => array(
						'12' => 'One',
						'6' => 'Two',
						'4' => 'Three',
						'3' => 'Four',
								),
						)
				),
        array(
            'Text',
            'limit_data_pinboard',
            array(
                'label' => 'Count for Pinboard View (number of news to show).',
                'value' => 10,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limit_data_grid',
            array(
                'label' => 'Count for Grid Views (number of news to show).',
                'value' => 20,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limit_data_list',
            array(
                'label' => 'Count for List Views (number of news to show).',
                'value' => 20,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        $pagging,
      ),
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Manage RSS',
    'description' => 'This widget displays rss created by the member viewing the manage rss page. Edit this widget to configure various settings.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.manage-rss',
    //'adminForm' => 'Sesnews_Form_Admin_Tabbed',
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Tabbed widget for Manage News',
    'description' => 'This widget displays news created, favourite, liked, rated, etc by the member viewing the manage page. Edit this widget to configure various settings.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.manage-news',
    'requirements' => array(
      'subject' => 'news',
    ),
    'adminForm' => 'Sesnews_Form_Admin_ManageTabbed',
  ),

  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Profile Options for News',
    'description' => 'Displays a menu of actions (edit, report, add to favourite, share, subscribe, etc) that can be performed on a news on its profile.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.gutter-menu',
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Owner Photo',
    'description' => 'Displays the owner\'s photo on the news view page.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.gutter-photo',
    'adminForm' => array(
			'elements' => array (
        array(
          'Select',
          'photoviewtype',
          array(
            'label' => "Choose the shape of Photo.",
            'multiOptions' => array(
              'square' => 'Square',
              'circle' => 'Circle'
            ),
            'value' => 'circle',
          )
        ),
        array(
          'Text',
          'user_description_limit',
          array(
            'label' => 'Truncation limit for "About User" information .',
            'value' => 150,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
			),
		),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - RSS Browse Search',
    'description' => 'Displays a search form in the rss browse page as configured by you.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.browse-rss-search',
    'autoedit' => true,
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
	    'label' => "Choose options to be shown in \'Browse By\' search fields.",
	    'multiOptions' => array(
	      'recentlySPcreated' => 'Recently Created',
	      'mostSPviewed' => 'Most Viewed',
	    ),
	  )
	),
	array(
	  'Select',
	  'default_search_type',
	  array(
	    'label' => "Default \'Browse By\' search field.",
	    'multiOptions' => array(
	      'recentlySPcreated' => 'Recently Created',
	      'mostSPviewed' => 'Most Viewed',
	    ),
	  )
	),
	array(
	  'Radio',
	  'search_title',
	  array(
	    'label' => "Show \'Search News Keyword\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
	  'Radio',
	  'browse_by',
	  array(
	    'label' => "Show \'Browse By\' search field?",
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
	    'label' => "Show \'Categories\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
      )
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Browse Search',
    'description' => 'Displays a search form in the news browse page as configured by you.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.browse-search',
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
	    'label' => "Choose options to be shown in \'Browse By\' search fields.",
	    'multiOptions' => array(
	      'recentlySPcreated' => 'Recently Created',
	      'mostSPviewed' => 'Most Viewed',
	      'mostSPliked' => 'Most Liked',
	      'mostSPcommented' => 'Most Commented',
	      'mostSPfavourite' => 'Most Favourite',
	      'mostSPrated' => 'Most Rated',
	      'featured' => 'Only Featured',
	      'sponsored' => 'Only Sponsored',
	      'verified' => 'Only Verified',
	      'hot' => 'Only Hot',
	      'latest' => 'Only New',
	    ),
	  )
	),
	array(
	  'Select',
	  'default_search_type',
	  array(
	    'label' => "Default \'Browse By\' search field.",
	    'multiOptions' => array(
	      'recentlySPcreated' => 'Recently Created',
	      'mostSPviewed' => 'Most Viewed',
	      'mostSPliked' => 'Most Liked',
	      'mostSPcommented' => 'Most Commented',
	      'mostSPrated' => 'Most Rated',
	      'featured' => 'Only Featured',
	      'sponsored' => 'Only Sponsored',
	      'verified' => 'Only Verified',
	      'hot' => 'Only Hot',
	      'latest' => 'Only New',
	    ),
	  )
	),
	array(
	  'Radio',
	  'friend_show',
	  array(
	    'label' => "Show \'View\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
	  'Radio',
	  'search_title',
	  array(
	    'label' => "Show \'Search News Keyword\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
	  'Radio',
	  'browse_by',
	  array(
	    'label' => "Show \'Browse By\' search field?",
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
	    'label' => "Show \'Categories\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
	  'Radio',
	  'location',
	  array(
	    'label' => "Show \'Location\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
	  'Radio',
	  'kilometer_miles',
	  array(
	    'label' => "Show \'Kilometer or Miles\' search field?",
	    'multiOptions' => array(
	      'yes' => 'Yes',
	      'no' => 'No'
	    ),
	    'value' => 'yes',
	  )
	),
	array(
		'Radio',
		'has_photo',
		array(
			'label' => "Show \'News With Photos\' search field?",
			'multiOptions' => array(
				'yes' => 'Yes',
				'no' => 'No',
			),
			'value' => 'yes',
		)
	),
      )
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Navigation Menu',
    'description' => 'Displays a navigation menu bar in the News / RSS Importer & Aggregator\'s pages for News Home, Browse News, Browse Categories, etc pages.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.browse-menu',
    'requirements' => array(
      'no-subject',
    ),
	'adminForm' => array(
	  'elements' => array (
		array(
		  'Radio',
		  'createButton',
		  array(
			'label' => "Enable Write New News Button? Note: You can  disable 'Write New News' menu from menu editor if you don't want it twice in navigation menu on your website.",
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
    'title' => 'SNS - News / RSS Importer & Aggregator - Create New News Link',
    'description' => 'Displays a link to create new news.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.browse-menu-quick',
    'requirements' => array(
      'no-subject',
    ),
  ),
  array(
      'title' => 'SNS News / RSS Importer & Aggregator - Categories Cloud / Hierarchy View',
      'description' => 'Displays all categories of news in cloud or hierarchy view. Edit this widget to choose various other settings.',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'name' => 'sesnews.tag-cloud-category',
      'autoEdit' => true,
      'adminForm' => 'Sesnews_Form_Admin_Tagcloudcategory',
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Similar News',
    'description' => 'Displays news similar to the current news based on the news category. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
		'autoEdit' => true,
    'name' => 'sesnews.similar-news',
    'adminForm' => array(
      'elements' => array (
        array(
          'MultiCheckbox',
          'show_criteria',
          array(
            'label' => "Choose from below the details that you want to show for news in this widget.",
            'multiOptions' => array(
              'like' => 'Likes Count',
              'comment' => 'Comments Count',
              'favourite' => 'Favourites Count',
              'view' => 'Views Count',
              'title' => 'News Title',
              'by' => 'News Owner\'s Name',
              'rating' =>'Rating Stars',
              'featuredLabel' => 'Featured Label',
              'sponsoredLabel' => 'Sponsored Label',
              'verifiedLabel' => 'Verified Label',
              'hotLabel' => "Hot Label",
              'newLabel' => "New Label",
              'favouriteButton' => 'Favourite Button',
              'likeButton' => 'Like Button',
              'category' => 'Category',
              'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likeButton' => 'Like Button',
              'favouriteButton' => 'Favourite Button',
            ),
            'escape' => false,
          )
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Select',
          'showLimitData',
          array(
            'label' => 'Do you want to allow users to view more similar news in this widget? (If you choose Yes, then users will see Next & Previous buttons to view more news.)',
            'multiOptions' => array(
              "1" => "Yes, allow.",
              "0" => "No, do not allow.",
            )
          ),
          'value' => '1',
        ),

        array(
          'Text',
          'height',
          array(
            'label' => 'Enter the height of one block (in pixels).',
            'value' => '180',
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter the width of one block (in pixels).',
            'value' => '180',
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'list_title_truncation',
          array(
            'label' => 'Title truncation limit.',
            'value' => 45,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'limit_data',
          array(
            'label' => 'Count (number of news to show).',
            'value' => 3,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
      ),
    ),
  ),
  array(
      'title' => 'SNS News / RSS Importer & Aggregator - News Profile - Tags',
      'description' => 'Displays all tags of the current news on News Profile Page. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'name' => 'sesnews.profile-tags',
      'autoEdit' => true,
      'adminForm' => array(
        'elements' => array(
          array(
            'Text',
            'itemCountPerPage',
            array(
              'label' => 'Count (number of tags to show).',
              'value' => 30,
              'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
              ),
            ),
          ),
        ),
      ),
  ),
  array(
    'title' => 'SNS News / RSS Importer & Aggregator - Tags Horizontal View',
    'description' => 'Displays all tags of news in horizontal view. Edit this widget to choose various other settings.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.tag-horizontal-news',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
				array(
					'Radio',
					'viewtype',
					array(
            'label' => "Do you want to show widget in full width ?",
            'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
            ),
            'value' => '1',
					)
				),
        array(
          'Text',
          'widgetbgcolor',
          array(
            'class' => 'SEScolor',
            'label'=>'Choose widget background color.',
            'value' => '424242',
          )
        ),
        array(
          'Text',
          'buttonbgcolor',
          array(
            'class' => 'SEScolor',
            'label'=>'Choose background color of the button.',
            'value' => '000000',
          )
        ),
        array(
          'Text',
          'textcolor',
          array(
            'class' => 'SEScolor',
            'label'=>'Choose text color on the button.',
            'value' => 'ffffff',
          )
        ),
        array(
          'Text',
          'itemCountPerPage',
          array(
            'label' => 'Count (number of tags to show).',
            'value' => 30,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            ),
          ),
        ),
      ),
    ),
  ),
  array(
      'title' => 'SNS News / RSS Importer & Aggregator - Tags Cloud / Tab View',
      'description' => 'Displays all tags of news in cloud or tab view. Edit this widget to choose various other settings.',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'name' => 'sesnews.tag-cloud-news',
      'autoEdit' => true,
      'adminForm' => 'Sesnews_Form_Admin_Tagcloudnews',
  ),
  array(
      'title' => 'SNS News / RSS Importer & Aggregator - Browse All Tags',
      'description' => 'Displays all news tags on your website. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Browse Tags Page".',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'name' => 'sesnews.tag-albums',
  ),
  array(
		'title' => 'SNS News / RSS Importer & Aggregator - Top News Posters',
		'description' => 'Displays all top posters on your website.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'name' => 'sesnews.top-newsgers',
		'adminForm' => array(
			'elements' => array(
				array(
					'MultiCheckbox',
					'show_criteria',
					array(
						'label' => "Choose the details that you want to be shown in this widget.",
						'multiOptions' => array(
							'count' => 'News Count',
							'ownername' => 'News Owner\'s Name',
						),
					)
				),
				array(
					'Text',
					'height',
					array(
						'label' => 'Enter the height of one block [for Horizontal View (in pixels)].',
						'value' => '180',
					)
				),
				array(
			'Select',
			'gridblock',
			array(
					'label' => "How many content you want to show in one row?",
					'multiOptions' => array(
						'12' => 'One',
						'6' => 'Two',
						'4' => 'Three',
						'3' => 'Four',
						'2' =>  'Six',
								),
						)
				),
				array(
					'Select',
					'showLimitData',
					array(
						'label' => 'Do you want to allow users to view more news posters in this widget? (If you choose Yes, then users will see Next & Previous buttons to view more news posters.)',
						'multiOptions' => array(
							"1" => "Yes, allow.",
							"0" => "No, do not allow.",
						)
					),
					'value' => '1',
				),
				array(
					'Text',
					'limit_data',
					array(
						'label' => 'Count (number of news posters to show).',
						'value' => 5,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
			),
		),
	),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Advanced Share Widget',
    'description' => 'This widget allow users to share the current news on your website and on other social networking websites. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.advance-share',
    'autoEdit' => true,
    'adminForm' => 'Sesnews_Form_Admin_Share',
  ),
  array(
    'title' => 'SNS News / RSS Importer & Aggregator - News of the Day',
    'description' => "This widget displays news of the day as chosen by you from the Edit Settings of this widget.",
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.of-the-day',
    'adminForm' => array(
	'elements' => array(
			array(
			'Select',
			'viewType',
			array(
				'label' => 'Choose the view type.',
				'multiOptions' => array(
					"grid1" => "Grid View",
				)
			),
			'value' => 'grid1'
		),
	 array(
		'MultiCheckbox',
		'show_criteria',
		array(
		    'label' => "Choose from below the details that you want to show for news in this widget.",
				'multiOptions' => array(
					'title' => 'News Title',
					'like' => 'Likes Count',
					'view' => 'Views Count',
					'comment' => 'Comment Count',
					'favourite' => 'Favourites Count',
					'rating' => 'Rating Count',
					'ratingStar' => 'Rating Star',
					'by' => 'Owner\'s Name',
					'favouriteButton' => 'Favourite Button',
					'likeButton' => 'Like Button',
					'featuredLabel' => 'Featured Label',
					'sponsoredLabel' => "Sponsored Label",
					'verifiedLabel' => 'Verified Label',
					'hotLabel' => "Hot Label",
					'newLabel' => "New Label",
					'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
				),
				'escape' => false,
		)
	 ),
    $socialshare_enable_plusicon,
    $socialshare_icon_limit,
	    array(
		'Text',
		'title_truncation',
		array(
		    'label' => 'News title truncation limit.',
		    'value' => 45,
		    'validators' => array(
			array('Int', true),
			array('GreaterThan', true, array(0)),
		    )
		)
	    ),
	      array(
	  'Text',
	  'description_truncation',
	  array(
	    'label' => 'News description truncation limit.',
	    'value' => 60,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	    array(
		'Text',
		'height',
		array(
		    'label' => 'Enter the height of block (in pixels).',
		    'value' => '180',
		    'validators' => array(
			array('Int', true),
			array('GreaterThan', true, array(0)),
		    )
		)
	    ),
	    array(
		'Text',
		'width',
		array(
		    'label' => 'Enter the width of block (in pixels).',
		    'value' => '180',
		    'validators' => array(
			array('Int', true),
			array('GreaterThan', true, array(0)),
		    )
		)
	    ),
	)
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Sponsored / Verified News',
    'description' => "Displays news as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.featured-sponsored',
    'adminForm' => array(
      'elements' => array(
	array(
	  'Select',
	  'viewType',
	  array(
	    'label' => 'Choose the view type.',
	    'multiOptions' => array(
	      "list" => "List View",
	      "grid" => "Grid View",
	    )
	  ),
	  'value' => 'list'
	),
	$imageType,
	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Display Content",
	    'multiOptions' => array(
	      '5' => 'All including Featured and Sponsored',
	      '1' => 'Only Featured',
	      '2' => 'Only Sponsored',
	      '8' => 'Only Hot',
	      '9' => 'Only New',
	      '3' => 'Both Featured and Sponsored',
	      '6' => 'Only Verified',
	      '4' => 'All except Featured and Sponsored',
	    ),
	    'value' => 5,
	  )
	),
	array(
		'Select',
		'order',
		array(
			'label' => 'Duration criteria for the news to be shown in this widget',
			'multiOptions' => array(
				'' => 'All News',
				'week' => 'This Week News',
				'month' => 'This Month News',
			),
			'value' => '',
		)
	),
	array(
	  'Select',
	  'info',
	  array(
	    'label' => 'Choose Popularity Criteria.',
	    'multiOptions' => array(
	      "recently_created" => "Recently Created",
	      "most_viewed" => "Most Viewed",
	      "most_liked" => "Most Liked",
	      "most_rated" => "Most Rated",
	      "most_commented" => "Most Commented",
	      "most_favourite" => "Most Favourite",
	    )
	  ),
	  'value' => 'recently_created',
	),
	array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show for news in this widget.",
	    'multiOptions' => array(
	      'like' => 'Likes Count',
	      'comment' => 'Comments Count',
	      'favourite' => 'Favourites Count',
	      'view' => 'Views Count',
	      'title' => 'News Title',
	      'by' => 'News Owner\'s Name',
	      'creationDate' => 'Show Publish Date',
	      'category' => 'Category',
	      'rating' => 'Ratings Count',
	      'ratingStar' => 'Ratings Star',
	      'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a> for Grid view only',
	      'likeButton' => 'Like Button for Grid view only',
	      'favouriteButton' => 'Favourite Button for Grid view only',
	    ),
	    'escape' => false,
	  )
	),
	$socialshare_enable_plusicon,
	$socialshare_icon_limit,
	array(
		'Radio',
		'show_star',
		array(
				'label' => "Do you want to show rating stars in this widget? (Note: Please choose star setting yes, when you are selecting \"Most Rated\" from above setting.)",
				'multiOptions' => array(
						'1' => 'Yes',
						'0' => 'No',
				),
				'value' => 0,
		)
  ),
  array(
	'Select',
	'showLimitData',
	array(
		'label' => 'Do you want to allow users to view more news posters in this widget? (If you choose Yes, then users will see Next & Previous buttons to view more news posters.)',
		'multiOptions' => array(
			"1" => "Yes, allow.",
			"0" => "No, do not allow.",
		)
	),
	'value' => '1',
),
	array(
	  'Text',
	  'title_truncation',
	  array(
	    'label' => 'News title truncation limit.',
	    'value' => 45,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
  array(
	  'Text',
	  'description_truncation',
	  array(
	    'label' => 'News description truncation limit.',
	    'value' => 60,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'height',
	  array(
	    'label' => 'Enter the height of one block (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'width',
	  array(
	    'label' => 'Enter the width of one photo block for List View (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	'Select',
			'gridblock',
			array(
					'label' => "How many content you want to show in one row in Grid View?",
					'multiOptions' => array(
						'12' => 'One',
						'6' => 'Two',
						'4' => 'Three',
						'3' => 'Four',
								),
						)
				),
	array(
	  'Text',
	  'limit_data',
	  array(
	    'label' => 'Count (number of news to show).',
	    'value' => 5,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
      )
    ),
  
      array(
        'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Albums',
        'description' => 'Displays albums on news profile page. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
				'autoEdit' => true,
        'name' => 'sesnews.profile-photos',
        'defaultParams' => array(
            'title' => 'Photos',
            'titleCount' => false,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'load_content',
                    array(
                        'label' => "Do you want the albums to be auto-loaded when users scroll down the page?",
                        'multiOptions' => array(
                            'auto_load' => 'Yes, Auto Load.',
                            'button' => 'No, show \'View more\' link.',
                            'pagging' => 'No, show \'Pagination\'.'
                        ),
                        'value' => 'auto_load',
                    )
                ),
                array(
                    'Radio',
                    'sort',
                    array(
                        'label' => 'Choose Album Display Criteria.',
                        'multiOptions' => array(
                            "recentlySPcreated" => "Recently Created",
                            "mostSPviewed" => "Most Viewed",
                            "mostSPliked" => "Most Liked",
                            "mostSPcommented" => "Most Commented",
                        ),
                        'value' => 'most_liked',
                    )
                ),
                array(
                    'Select',
                    'insideOutside',
                    array(
                        'label' => "Choose where do you want to show the statistics of albums.",
                        'multiOptions' => array(
                            'inside' => 'Inside Album Blocks',
                            'outside' => 'Outside Album Blocks',
                        ),
                        'value' => 'inside',
                    )
                ),
                array(
                    'Select',
                    'fixHover',
                    array(
                        'label' => "Show album statistics Always or when users Mouse-over on album blocks (this setting will work only if you choose to show information inside the Album block.)",
                        'multiOptions' => array(
                            'fix' => 'Always',
                            'hover' => 'On Mouse-over',
                        ),
                        'value' => 'fix',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for albums in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'view' => 'Views Count',
                            'favouriteCount' => 'Favourites Count',
                            'title' => 'Album Title',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'photoCount' => 'Photos Count',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                        ),
                        'escape' => false,
                    //'value' => array('like','comment','view','rating','title','by','socialSharing'),
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Album title truncation limit.',
                        'value' => 45,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of albums to show.)',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one album block (in pixels).',
                        'value' => 200,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one album block (in pixels).',
                        'value' => 236,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            )
        ),
        'requirements' => array(
            'subject' => 'sesnews_news',
        ),
    ),

     array(
      'title' => 'SNS News / RSS Importer & Aggregator - Album View Page - Options',
      'description' => "This widget enables you to choose various options to be shown on album view page like Likes count, Like button, etc.",
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'autoEdit' => true,
      'name' => 'sesnews.album-view-page',
      'adminForm' => 'Sesnews_Form_Admin_Albumviewpage',
    ),

    		array(
        'title' => 'SNS News / RSS Importer & Aggregator - Photo View Page - Options',
        'description' => 'This widget enables you to choose various options to be shown on photo view page like Slideshow of other photos associated with same album as the current photo, etc.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.photo-view-page',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'criteria',
                    array(
                        'label' => 'Slideshow of other photos associated with same album?',
                        'multiOptions' =>
                        array(
                        	'1' => 'Yes',
				'0' =>'No'
                        ),
				'value' => 1
                    ),
                ),
                array(
                    'Text',
                    'maxHeight',
                    array(
                        'label' => 'Enter the height of photo.',
                        'value' => 550,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            ),
        ),
    ),

  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Videos',
    'description' => 'Displays videos on news profile page. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.profile-videos',
    'autoEdit' => true,
    'adminForm' => 'Sesnews_Form_Admin_Profilevideos',
  ),
array(
	'title' => 'SNS - News / RSS Importer & Aggregator - Review Profile',
	'description' => 'Displays review and review statistics on "SNS - News / RSS Importer & Aggregator - Review Profile Page".',
	'category' => 'SNS - News / RSS Importer & Aggregator',
	'type' => 'widget',
	'name' => 'sesnews.review-profile',
	'autoedit' => 'true',
	'adminForm' => array(
	'elements' => array(
			array(
				'MultiCheckbox',
				'stats',
			array(
				'label' => 'Choose the options that you want to be displayed in this widget.',
				'multiOptions' => array(
							"likeCount" => "Likes Count",
							"commentCount" => "Comments Count",
							"viewCount" => "Views Count",
							"title" => "Review Title",
							"pros" => "Pros",
							"cons" => "Cons",
							"description" => "Description",
							"recommended" => "Recommended",
							'postedin' => "Posted In",
							"creationDate" => "Creation Date",
							'parameter'=>'Review Parameters',
							'rating' => 'Rating Stars',
							'customfields' => 'Form Questions',
							'likeButton' => 'Like Button',
							'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
							'share' => 'Share Review',
									),
									'escape' => false,
							),
					),
          $socialshare_enable_plusicon,
          $socialshare_icon_limit,
					),
			),
	),
  array(
      'title' => 'SNS - News / RSS Importer & Aggregator - Review Profile - Breadcrumb',
      'description' => 'Displays breadcrumb for Reviews. This widget should be placed on the "SNS - News / RSS Importer & Aggregator - Review Profile Page".',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'autoEdit' => true,
      'type' => 'widget',
      'name' => 'sesnews.review-breadcrumb',
      'autoEdit' => true,
  ),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Album Profile - Breadcrumb',
		'description' => 'Displays breadcrumb for Albums. This widget should be placed on the SNS - News / RSS Importer & Aggregator - Album Profile Page.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'autoEdit' => true,
		'type' => 'widget',
		'name' => 'sesnews.album-breadcrumb',
		'autoEdit' => true,
  ),
  array(
      'title' => 'SNS - News / RSS Importer & Aggregator - Review Profile - Options',
      'description' => 'Displays a menu of actions (edit, report, share, etc) that can be performed on reviews on its profile. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Review Profile Page".',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'name' => 'sesnews.review-profile-options',
      'autoEdit' => true,
      'adminForm' => array(
          'elements' => array(
              array(
                  'Select',
                  'viewType',
                  array(
                      'label' => "Choose the View Type.",
                      'multiOptions' => array(
                          'horizontal' => 'Horizontal',
                          'vertical' => 'Vertical',
                      ),
                      'value' => 'vertical',
                  ),
              ),
          ),
      ),
  ),
  array(
      'title' => "SNS - News / RSS Importer & Aggregator - Review Owner's Photo",
      'description' => 'This widget displays photo of the member who has written the current review. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Review View Page".',
      'category' => 'SNS - News / RSS Importer & Aggregator',
      'type' => 'widget',
      'autoEdit' => true,
      'name' => 'sesnews.review-owner-photo',
      'defaultParams' => array(
          'title' => '',
      ),
      'adminForm' => array(
          'elements' => array(
              array(
                  'Select',
                  'showTitle',
                  array(
                      'label' => 'Do you want to show Member’s Name in this widget?',
                      'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No'
                      ),
                      'value' => 1,
                  )
              ),
          )
      ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Category Carousel',
    'description' => 'Displays categories in attractive carousel in this widget. The placement of this widget depends on the criteria chosen for this widget.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.category-carousel',
    'adminForm' => array(
      'elements' => array(
	array(
	  'Text',
	  'title_truncation_grid',
	  array(
	    'label' => 'Title truncation limit.',
	    'value' => 45,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'description_truncation_grid',
	  array(
	    'label' => 'Description truncation limit.',
	    'value' => 45,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'height',
	  array(
	    'label' => 'Enter the height of one category block (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'width',
	  array(
	    'label' => 'Enter the width of one category block (in pixels).',
	    'value' => '180',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Select',
	  'autoplay',
	  array(
	    'label' => "Do you want to enable auto play of categories?",
	    'multiOptions' => array(
	      1=>'Yes',
	      0=>'No'
	    ),
	  ),
	),
	array(
	  'Text',
	  'speed',
	    array(
	    'label' => 'Delay time for next category when you have enabled autoplay',
	    'value' => '2000',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Choose Popularity Criteria.",
	    'multiOptions' => array(
	      'alphabetical' => 'Alphabetical order',
	      'most_news' => 'Categories with maximum news first',
	      'admin_order' => 'Admin selected order for categories',
	    ),
	  ),
	),
	array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show on each block.",
	    'multiOptions' => array(
	      'title' => 'Category title',
	      'description' => 'Category description',
	      'countNews' => 'News count in each category',
	      'socialshare' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
	    ),
	    'escape' => false,
	  )
	),
	$socialshare_enable_plusicon,
	$socialshare_icon_limit,
	array(
	  'Select',
	  'isfullwidth',
	  array(
	    'label' => 'Do you want to show category carousel in full width?',
	    'multiOptions'=>array(
	      1=>'Yes',
	      0=>'No'
	    ),
	    'value' => 1,
	  )
	),
	array(
	  'Text',
	  'limit_data',
	  array(
	    'label' => 'Count (number of categories to show in this widget).',
	    'value' => 10,
	  )
	),
      )
    ),
  ),
  array(
    'title' => 'SNS News / RSS Importer & Aggregator - Categories Icon View',
    'description' => 'Displays all categories of news in icon view with their icon. Edit this widget to configure various settings.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.news-category-icons',
    'adminForm' => array(
      'elements' => array(
	array(
	  'Text',
	  'titleC',
	  array(
	    'label' => 'Enter the title for this widget.',
	    'value' => 'Browse by Popular Categories',
	  )
	),
	 array(
			'Select',
			'gridblock',
			array(
					'label' => "How many content you want to show in one row?",
					'multiOptions' => array(
						'12' => 'One',
						'6' => 'Two',
						'4' => 'Three',
						'3' => 'Four',
						'2' =>  'Six',
					),
			)
	),
	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Choose Popularity Criteria.",
	    'multiOptions' => array(
	      'alphabetical' => 'Alphabetical order',
	      'most_news' => 'Categories with maximum news first',
	      'admin_order' => 'Admin selected order for categories',
	    ),
	  ),
	),
	array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show on each block.",
	    'multiOptions' => array(
	      'title' => 'Category title',
	      'countNews' => 'News count in each category',
	    ),
	  )
	),
	array(
	  'Text',
	  'limit_data',
	  array(
	    'label' => 'Count (number of categories to show.)',
	    'value' => 10,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
      ),
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Alphabetic Filtering of News',
    'description' => "This widget displays all the alphabets for alphabetic filtering of news which will enable users to filter news on the basis of selected alphabet.",
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.alphabet-search',
    'defaultParams' => array(
      'title' => "",
    ),
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Breadcrumb',
    'description' => 'Displays breadcrumb for News. This widget should be placed on the News / RSS Importer & Aggregator - View page of the selected content type.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.breadcrumb',
    'autoEdit' => true,
  ),
	 array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Custom Field Info',
    'description' => 'Displays news custom fields for News. This widget should be placed on the News / RSS Importer & Aggregator - View page of news.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.news-info',
		'defaultParams' => array(
      'title' => "Custom Fields",
    ),
    'autoEdit' => false,
  ),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Reviews	',
		'description' => 'Displays reviews on news profile page. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'name' => 'sesnews.news-reviews',
		'autoEdit' => true,
		'adminForm' => array(
			'elements' => array(
				array(
					'MultiCheckbox',
					'stats',
					array(
						'label' => 'Choose the options that you want to be displayed in this widget.',
						'multiOptions' => array(
							"likeCount" => "Likes Count",
							"commentCount" => "Comments Count",
							"viewCount" => "Views Count",
							"title" => "Review Title",
							"share" => "Share Button",
							"report" => "Report Button",
							"pros" => "Pros",
							"cons" => "Cons",
							"description" => "Description",
							"recommended" => "Recommended",
							'postedBy' => "Posted By",
							'parameter' => 'Review Parameters',
							"creationDate" => "Creation Date",
							'rating' => 'Rating Stars',
							'likeButton' => 'Like Button',
              'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
						),
						'escape' => false,
					),
				),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
				$pagging,
				array(
					'Text',
					'limit_data',
					array(
						'label' => 'count (number of reviews to show).',
						'value' => 5,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
			),
		),
	),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Verified Reviews',
		'description' => "Displays reviews as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.popular-featured-verified-reviews',
		'adminForm' => array(
			'elements' => array(
				array(
					'Select',
					'info',
					array(
						'label' => 'Choose Popularity Criteria.',
						'multiOptions' => array(
							"creation_date" => "Recently Created",
							"most_viewed" => "Most Viewed",
							"most_liked" => "Most Liked",
							"most_commented" => "Most Commented",
							"most_rated" => "Most Rated",
							"featured" => "Featured",
							"verified" => "Verified",
						)
					),
					'value' => 'recently_updated',
				),
				$imageType,
				array(
				'Select',
					'showLimitData',
					array(
						'label' => 'Do you want to allow users to view more reviews in this widget? (If you choose Yes, then users will see Next & Previous buttons to view more reviews.',
						'multiOptions' => array(
							"1" => "Yes",
							"0" => "No",
						)
					),
					'value' => '1',
				),
				array(
					'MultiCheckbox',
					'show_criteria',
					array(
						'label' => "Choose from below the details that you want to show for news in this widget.",
						'multiOptions' => array(
							'title' => 'Review Title',
							'like' => 'Likes Count',
							'view' => 'Views Count',
							'comment' => 'Comments Count',
							'rating' => 'Ratings',
							'verifiedLabel' => 'Verified Label',
							'featuredLabel' => 'Featured Label',
							'description' => 'Description',
							'by' => 'By',
						),
					),
				),
				array(
					'Text',
					'title_truncation',
					array(
						'label' => 'Title truncation limit.',
						'value' => 45,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
				array(
					'Text',
					'review_description_truncation',
					array(
						'label' => 'Descripotion truncation limit.',
						'value' => 45,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
				array(
					'Text',
					'limit_data',
					array(
						'label' => 'Count (number of reviews to show).',
						'value' => 5,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
			)
		),
	),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Review of the Day',
		'description' => "This widget displays review of the day as chosen by you from the \"Manage Reviews\" settings of this plugin.",
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.review-of-the-day',
		'adminForm' => array(
			'elements' => array(
				array(
					'MultiCheckbox',
					'show_criteria',
					array(
						'label' => "Choose from below the details that you want to show for member in this widget.",
						'multiOptions' => array(
							'title' => 'Display Review Title',
							'like' => 'Likes Count',
							'view' => 'Views Count',
							'rating' => 'Ratings',
							'featuredLabel' => 'Featured Label',
							'verifiedLabel' => 'Verified Label',
							'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
							'by' => 'Review Owner Name',
						),
						'escape' => false,
					),
				),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
				array(
					'Text',
					'title_truncation',
					array(
						'label' => 'Title truncation limit.',
						'value' => 45,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
				array(
					'Text',
					'height',
					array(
						'label' => 'Enter the height of photo block of review(in pixels).',
						'value' => '180',
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						)
					)
				),
			)
		),
  ),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Browse Reviews',
		'description' => 'Displays all reviews for news on your webiste. This widget is placed on "SNS - News / RSS Importer & Aggregator - Browse Reviews Page".',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.browse-reviews',
		'defaultParams' => array(
		),
		'adminForm' => array(
				'elements' => array(
					array(
						'MultiCheckbox',
						'stats',
						array(
							'label' => 'Choose options to show in this widget.',
							'multiOptions' => array(
								"likeCount" => "Likes Count",
								"commentCount" => "Comments Count",
								"viewCount" => "Views Count",
								"title" => "Review Title",
								"share" => "Share Button",
								"report" => "Report Button",
								"pros" => "Pros",
								"cons" => "Cons",
								"description" => "Description",
								"recommended" => "Recommended",
								'postedBy' => "Posted By",
								'parameter' => 'Review Parameters',
								"creationDate" => "Creation Date",
								'rating' => 'Rating Stars',
								'likeButton' => 'Like Button',
                                'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
							),
							'escape' => false,
						)
					),
                    $socialshare_enable_plusicon,
                    $socialshare_icon_limit,
					array(
						'MultiCheckbox',
						'show_criteria',
						array(
								'label' => "Choose from below the details that you want to show for news in this widget.",
								'multiOptions' => array(
										'featuredLabel' => 'Featured Label',
										'verifiedLabel' => 'Verified Label',
								),
						),
					),
					$pagging,
					array(
						'Text',
						'limit_data',
						array(
								'label' => 'Count (number of reviews to show).',
								'value' => 5,
								'validators' => array(
										array('Int', true),
										array('GreaterThan', true, array(0)),
								)
						)
					),
				),
		),
  ),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Review Browse Search',
		'description' => 'Displays a search form in the review browse page as configured by you.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'name' => 'sesnews.browse-review-search',
		'requirements' => array(
				'no-subject',
		),
		'autoEdit' => true,
		'adminForm' => array(
			'elements' => array(
				array(
					'Radio',
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
					'Radio',
					'review_title',
					array(
						'label' => "Show \'Review Title\' search field?",
						'multiOptions' => array(
								'1' => 'Yes',
								'0' => 'No'
						),
						'value' => '1',
					)
				),
				array(
					'Radio',
					'review_search',
					array(
						'label' => "Show \'Browse By\' search field?",
						'multiOptions' => array(
								'1' => 'Yes',
								'0' => 'No'
						),
						'value' => '1',
					)
				),
				array(
					'MultiCheckbox',
					'view',
					array(
						'label' => "Choose options to be shown in \'Browse By\' search fields.",
						'multiOptions' => array(
							'mostSPliked' => 'Most Liked',
							'mostSPviewed' => 'Most Viewed',
							'mostSPcommented' => 'Most Commented',
							'mostSPrated' => 'Most Rated',
							'verified' => 'Verified Only',
							'featured' => 'Featured Only',
						),
					)
				),
				array(
					'Radio',
					'review_stars',
					array(
						'label' => "Show \'Review Stars\' search field?",
						'multiOptions' => array(
								'1' => 'Yes',
								'0' => 'No'
						),
						'value' => '1',
					)
				),
				array(
					'Radio',
					'review_recommendation',
					array(
						'label' => "Show \'Recommended Review\' search field?",
						'multiOptions' => array(
								'1' => 'Yes',
								'0' => 'No',
						),
						'value' => '1',
					)
				),
			)
		),
  ),
 array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Labels',
    'description' => 'This widget displays Featured, Sponsored and Verified labels on News Profile Page. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.labels',
  ),
// 	 array(
//     'title' => 'SNS - News / RSS Importer & Aggregator - Tabs',
//     'description' => '',
//     'category' => 'SNS - News / RSS Importer & Aggregator',
//     'type' => 'widget',
//     'autoEdit' => true,
//     'name' => 'sesnews.profile-sidebar-tabs',
//   ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Sidebar Tabbed Widget',
    'description' => 'Displays a tabbed widget for news. You can place this widget anywhere on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.sidebar-tabbed-widget',
    'requirements' => array(
      'subject' => 'news',
    ),
    'adminForm' => 'Sesnews_Form_Admin_SidebarTabbed',
  ),
  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Content',
    'description' => 'Displays news content according to the design choosen by the news poster while creating or editing the news. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - News Profile Page".',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.view-news',
		'adminForm' => array(
			'elements' => array(
				array(
					'MultiCheckbox',
					'show_criteria',
					array(
						'label' => "Choose from below the details that you want to show in this widget.",
						'multiOptions' => array(
							'title' => 'Title',
							'description' => 'Show Description',
							'photo' => 'News Photo',
							'socialShare' => 'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
							'ownerOptions' => 'Owner Options',
							'postComment' => 'Comment Button',
							'originalnews' => 'Show Original News Button',
							'rating' => 'Rating Star',
							'likeButton' => 'Like Button',
                            'shareButton' => 'Large Share Button',
                            'smallShareButton' => 'Small Share Button',
							'favouriteButton' => 'Favourite Button',
							'view' => 'View Count',
							'like' => 'Like Count',
							'comment' => 'Comment Count',
							'review' => 'Review Count',
							'statics' => 'Show Statstics'
						),
						'escape' => false,
					)
				),
				$socialshare_enable_plusicon,
				$socialshare_icon_limit,
			),
		),
  ),
array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Category Banner Widget',
		'description' => 'Displays a banner for categories. You can place this widget at browse page of category on your site.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.banner-category',
		'requirements' => array(
				'subject' => 'news',
		),
		'adminForm' => 'Sesnews_Form_Admin_Categorywidget',
	),
	array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Calendar Widget',
		'description' => 'Displays calendar . You can place this widget at browse page of news on your site.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => false,
		'name' => 'sesnews.calendar'
	),
	    array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Categories Square Block View',
        'description' => 'Displays all categories of news in square blocks. Edit this widget to configure various settings.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesnews.news-category',
        'requirements' => array(
            'subject' => 'news',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels).',
                        'value' => '160px',
                    )
                ),
                 array(
										'Select',
										'gridblock',
										array(
												'label' => "How many content you want to show in one row?",
												'multiOptions' => array(
													'12' => 'One',
													'6' => 'Two',
													'4' => 'Three',
													'3' => 'Four',
													'2' =>  'Six',
												),
										)
								),
								 array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'count (number of categories to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
								array(
                    'Select',
                    'video_required',
                    array(
                        'label' => "Do you want to show only those categories under which atleast 1 news is posted?",
                        'multiOptions' => array(
                            '1' => 'Yes, show only categories with news',
                            '0' => 'No, show all categories',
                        ),
                    ),
										'value' =>'1'
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Choose Popularity Criteria.",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical Order',
                            'most_news' => 'Most News Category First',
                            'admin_order' => 'Admin Order',
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each block.",
                        'multiOptions' => array(
                            'title' => 'Category Title',
                            'icon' => 'Category Icon',
                            'countNews' => 'News count in each category',
                        ),
                    )
                ),
            ),
        ),
    ),
        array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Category Based News Block View',
        'description' => 'Displays news in attractive square block view on the basis of their categories. This widget can be placed any where on your website.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesnews.category-associate-news',
        'requirements' => array(
            'subject' => 'news',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for news in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Count',
                            'ratingStar' => 'Rating Star',
                            'view' => 'Views Count',
                            'title' => 'Title Count',
                            'favourite' => 'Favourites Count',
                            'by' => 'News Owner\'s Name',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => 'Hot Label',
                            'newLabel' => 'New Label',
                            'creationDate' => 'Show Publish Date',
                        ),
                    )
                ),
                array(
                    'Radio',
                    'popularity_news',
                    array(
                        'label' => 'Choose News Display Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "view_count" => "Most Viewed",
                            "like_count" => "Most Liked",
                            "rating" => "Most Rated",
                            "comment_count" => "Most Commented",
                            "favourite_count" => "Most Favourite",
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'hot' => 'Only Hot',
                            'latest' => 'Only New',
                        ),
                        'value' => 'like_count',
                    )
                ),
                $pagging,
                array(
                    'Select',
                    'count_news',
                    array(
                        'label' => "Show news count in each category.",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                    ),
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Choose Popularity Criteria.",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical Order',
                            'most_news' => 'Categories with maximum news first',
                            'admin_order' => 'Admin selected order for categories',
                        ),
                    ),
                ),
                array(
                    'Text',
                    'category_limit',
                    array(
                        'label' => 'count (number of categories to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'news_limit',
                    array(
                        'label' => 'count (number of news to show in each category. This settging will work, if you choose "Yes" for "Show news count in each category" setting above.").',
                        'value' => '8',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
								array(
									'Text',
									'news_description_truncation',
									array(
										'label' => 'Descripotion truncation limit.',
										'value' => 45,
										'validators' => array(
											array('Int', true),
											array('GreaterThan', true, array(0)),
										)
									)
								),
                array(
                    'Text',
                    'seemore_text',
                    array(
                        'label' => 'Enter the text for "+ See All" link. Leave blank if you don\'t want to show this link. (Use[category_name] variable to show the associated category name).',
                        'value' => '+ See all [category_name]',
                    )
                ),
                array(
                    'Select',
                    'allignment_seeall',
                    array(
                        'label' => "Choose alignment of \"+ See All\" field
",
                        'multiOptions' => array(
                            'left' => 'left',
                            'right' => 'right'
                        ),
                    ),
                ),
                $heightOfContainer,
                $widthOfContainer,
            )
        ),
    ),
        array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Rss View Page Widget',
        'description' => 'Displays a view page for rss. You can place this widget at view page of rss on your site.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.rss-view',
        'requirements' => array(
            'subject' => 'news',
        ),
        'adminForm' => array(
            'elements' => array(
								array(
									'Select',
									'viewType',
									array(
										'label' => 'Choose the view type.',
										'multiOptions' => array(
											"list" => "List View",
											"grid" => "Grid View",
										)
									),
									'value' => 'list'
								),

                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each album block.",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => "Hot Label",
                            'newLabel' => "New Label",
                            'like' => 'Likes',
                            'comment' => 'Comments',
                            'rating' => 'Rating Count',
                            'ratingStar' => 'Rating Star',
                            'favourite'=>'Favourite',
                            'view' => 'Views',
                            'title' => 'Titles',
                            'by' => 'Item Owner Name',
                            'description' => 'Show Description',
                            'readmore' => 'Show Read More',
                            'creationDate' => 'Show Publish Date',
                        ),
                    )
                ),
                $pagging,
								array(
									'Text',
									'description_truncation',
									array(
										'label' => 'Description truncation limit.',
										'value' => 45,
										'validators' => array(
											array('Int', true),
											array('GreaterThan', true, array(0)),
										)
									)
								),
                array(
                    'Text',
                    'news_limit',
                    array(
                        'label' => 'count (number of news to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels. This setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels. This setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                )
            )
        ),
    ),
        array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Category View Page Widget',
        'description' => 'Displays a view page for categories. You can place this widget at view page of category on your site.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.category-view',
        'requirements' => array(
            'subject' => 'news',
        ),
        'adminForm' => array(
            'elements' => array(
								array(
									'Select',
									'viewType',
									array(
										'label' => 'Choose the view type.',
										'multiOptions' => array(
											"list" => "List View",
											"grid" => "Grid View",
										)
									),
									'value' => 'list'
								),
                array(
                    'Select',
                    'show_subcat',
                    array(
                        'label' => "Show 2nd-level or 3rd level categories blocks.",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_subcatcriteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each categpory block.",
                        'multiOptions' => array(
                            'icon' => 'Category Icon',
                            'title' => 'Category Title',
                            'countNews' => 'News count in each category',
                        ),
                    )
                ),
                array(
                    'Text',
                    'heightSubcat',
                    array(
                        'label' => 'Enter the height of one 2nd-level or 3rd level categor\'s block (in pixels).
',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'widthSubcat',
                    array(
                        'label' => 'Enter the width of one 2nd-level or 3rd level categor\'s block (in pixels).
',
                        'value' => '250px',
                    )
                ),
								 array(
                    'Text',
                    'textNews',
                    array(
                        'label' => 'Enter teh text for \'heading\' of this widget.',
                        'value' => 'News we like',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each album block.",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => "Hot Label",
                            'newLabel' => "New Label",
                            'verifiedLabel' => "Verified Label",
                            'like' => 'Likes',
                            'comment' => 'Comments',
                            'rating' => 'Rating Count',
                            'ratingStar' => 'Rating Star',
                            'favourite'=>'Favourite',
                            'view' => 'Views',
                            'title' => 'Titles',
                            'by' => 'Item Owner Name',
                            'description' => 'Show Description',
                            'readmore' => 'Show Read More',
                            'creationDate' => 'Show Publish Date',
                        ),
                    )
                ),
                $pagging,
								array(
									'Text',
									'description_truncation',
									array(
										'label' => 'Description truncation limit.',
										'value' => 45,
										'validators' => array(
											array('Int', true),
											array('GreaterThan', true, array(0)),
										)
									)
								),
                array(
                    'Text',
                    'news_limit',
                    array(
                        'label' => 'count (number of news to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels. This setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels. This setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                )
            )
        ),
    ),
    array(
        'title' => 'SNS - News / RSS Importer & Aggregator - News tags',
        'description' => 'Displays all news tags on your website. The recommended page for this widget is "SNS - News / RSS Importer & Aggregator - Browse Tags Page".',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.tag-news',
    ),
    array(
		'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Sponsored / Verified 3 News View',
		'description' => 'Disaply news based on popularity criteria. You can place this widget anywhere.',
		'category' => 'SNS - News / RSS Importer & Aggregator',
		'type' => 'widget',
		'autoEdit' => true,
		'name' => 'sesnews.featured-sponsored-verified-random-news',
		   'adminForm' => array(
      'elements' => array(
      	array(
	  'Select',
	  'category',
	  array(
	    'label' => 'Choose the category.',
	    'multiOptions' => $categories
	  ),
	  'value' => ''
	),
	array(
	  'Select',
	  'info',
	  array(
	    'label' => 'Choose Popularity Criteria.',
	    'multiOptions' => array(
	      "recently_created" => "Recently Created",
	      "most_viewed" => "Most Viewed",
	      "most_liked" => "Most Liked",
	      "most_rated" => "Most Rated",
	      "most_commented" => "Most Commented",
	      "most_favourite" => "Most Favourite",
	    )
	  ),
	  'value' => 'recently_created',
	),
	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Display Content",
	    'multiOptions' => array(
	      '5' => 'All including Featured and Sponsored',
	      '1' => 'Only Featured',
	      '2' => 'Only Sponsored',
	      '3' => 'Both Featured and Sponsored',
	      '6' => 'Only Verified',
	      '8' => 'Only Hot',
	      '9' => 'Only New',
	      '4' => 'All except Featured and Sponsored',
	    ),
	    'value' => 5,
	  )
	),
		array(
		'Select',
		'order',
		array(
			'label' => 'Duration criteria for the news to be shown in this widget.',
			'multiOptions' => array(
				'' => 'All',
				'week' => 'This Week',
				'month' => 'This Month',
			),
			'value' => '',
		)
	),
		array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show for news in this widget.",
	    'multiOptions' => array(
	      'like' => 'Likes Count',
	      'comment' => 'Comments Count',
	      'favourite' => 'Favourites Count',
	      'view' => 'Views Count',
	      'title' => 'News Title',
	      'by' => 'News Owner\'s Name',
		'rating' =>'Rating Count',
		'ratingStar' =>'Rating Stars',
		'featuredLabel' => 'Featured Label',
		'sponsoredLabel' => 'Sponsored Label',
		'hotLabel' => "Hot Label",
		'newLabel' => "New Label",
		'verifiedLabel' => 'Verified Label',
		'favouriteButton' => 'Favourite Button',
		'likeButton' => 'Like Button',
		'category' => 'Category',
		'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
		'likeButton' => 'Like Button',
		'favouriteButton' => 'Favourite Button',
		'creationDate' => 'Show Publish Date',
	    ),
	    'escape' => false,
	  )
	),
	        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
      ),
    ),

	),
      array(
        'title' => 'SNS - News / RSS Importer & Aggregator - News Locations',
        'description' => 'This widget displays news based on their locations in Google Map.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.news-location',
				'autoEdit' => true,
    		'adminForm' => 'Sesnews_Form_Admin_Location',
    ),

    	array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Sponsored / Verified News Slideshow',
    'description' => "Displays slideshow of news as chosen by you based on chosen criteria for this widget. You can also choose to show News of specific categories in this widget. The placement of this widget depends on the criteria chosen for this widget.",
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesnews.featured-sponsored-verified-category-slideshow',
    'adminForm' => array(
      'elements' => array(
	array(
	  'Select',
	  'category',
	  array(
	    'label' => 'Choose the category.',
	    'multiOptions' => $categories
	  ),
	  'value' => ''
	),

	array(
	  'Select',
	  'criteria',
	  array(
	    'label' => "Display Content",
	    'multiOptions' => array(
          '0' => 'All News',
	      '1' => 'Only Featured',
	      '2' => 'Only Sponsored',
	      '6' => 'Only Verified',
	      '8' => 'Only Hot',
	      '9' => 'Only New',
	    ),
	    'value' => 5,
	  )
	),
	array(
		'Select',
		'order',
		array(
			'label' => 'Duration criteria for the news to be shown in this widget.',
			'multiOptions' => array(
				'' => 'All',
				'week' => 'This Week',
				'month' => 'This Month',
			),
			'value' => '',
		)
	),
	array(
	  'Select',
	  'info',
	  array(
	    'label' => 'Choose Popularity Criteria.',
	    'multiOptions' => array(
	      "recently_created" => "Recently Created",
	      "most_viewed" => "Most Viewed",
	      "most_liked" => "Most Liked",
	      "most_rated" => "Most Rated",
	      "most_commented" => "Most Commented",
	      "most_favourite" => "Most Favourite",
	    )
	  ),
	  'value' => 'recently_created',
	),
	array(
	  'Select',
	  'isfullwidth',
	  array(
	    'label' => 'Do you want to show category carousel in full width?',
	    'multiOptions'=>array(
	      1=>'Yes',
	      0=>'No'
	    ),
	    'value' => 1,
	  )
	),
		array(
	  'Select',
	  'autoplay',
	  array(
	    'label' => "Do you want to enable autoplay of news?",
	    'multiOptions' => array(
	      1=>'Yes',
	      0=>'No'
	    ),
	  ),
	),
	array(
	  'MultiCheckbox',
	  'show_criteria',
	  array(
	    'label' => "Choose from below the details that you want to show for news in this widget.",
	    'multiOptions' => array(
	      'like' => 'Likes Count',
	      'comment' => 'Comments Count',
	      'favourite' => 'Favourites Count',
	      'view' => 'Views Count',
	      'title' => 'News Title',
	      'by' => 'News Owner\'s Name',
				'rating' =>'Rating Count',
				'ratingStar' =>'Rating Stars',
				 'featuredLabel' => 'Featured Label',
				'sponsoredLabel' => 'Sponsored Label',
				'hotLabel' => "Hot Label",
				'newLabel' => "New Label",
				'verifiedLabel' => 'Verified Label',
				'favouriteButton' => 'Favourite Button',
				'likeButton' => 'Like Button',
	      'category' => 'Category',
	      'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
	      'likeButton' => 'Like Button',
	      'favouriteButton' => 'Favourite Button',
	      'creationDate' => 'Show Publish Date',
	    ),
	    'escape' => false,

	  )
	),
  $socialshare_enable_plusicon,
  $socialshare_icon_limit,
	array(
	  'Text',
	  'title_truncation',
	  array(
	    'label' => 'News title truncation limit.',
	    'value' => 45,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'height',
	  array(
	    'label' => 'Enter the height of one slide block (in pixels).',
	    'value' => '400',
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
	array(
	  'Text',
	  'limit_data',
	  array(
	    'label' => 'Count (number of news to show).',
	    'value' => 5,
	    'validators' => array(
	      array('Int', true),
	      array('GreaterThan', true, array(0)),
	    )
	  )
	),
      )
    ),

	),
	  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Content Widget',
    'description' => 'Displays a content widget for news. You can place this widget on news profile page in tab container only on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.content',
    'requirements' => array(
      'subject' => 'news',
    ),
  ),
  	  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Photo',
    'description' => 'Displays a news photo widget. You can place this widget on news profile page only on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.news-photo',
    'requirements' => array(
      'subject' => 'news',
    ),
  ),
    	  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Title Widget',
    'description' => 'Displays a news title widget. You can place this widget on news profile page only on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.news-title',
    'requirements' => array(
      'subject' => 'news',
    ),
  ),
    	  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - News Social Share Widget',
    'description' => 'Displays a news social share widget. You can place this widget on news profile page only on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.news-socialshare',
    'adminForm' => array(
			'elements' => array(
				array(
					'Radio',
					'socialshare_design',
					array(
						'label' => "Do you want this social share widget on news profile page ?",
						'multiOptions' => array(
							'1' => 'Social Share Design 1',
							'2' => 'Social Share Design 2',
							'3' => 'Social Share Design 3',
							'4' => 'Social Share Design 4',
						),
						'value' => 'design1',
					)
				),
			),
		),
    'requirements' => array(
      'subject' => 'news',
    ),
  ),
      array(
        'title' => 'SNS - News / RSS Importer & Aggregator - News Profile - Map',
        'description' => 'Displays a news location on map on it\'s profile.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'name' => 'sesnews.news-map',
        'defaultParams' => array(
            'title' => 'Map',
            'titleCount' => true,
        ),
        'requirements' => array(
            'subject' => 'user',
        ),
    ),

    	  array(
    'title' => 'SNS - News / RSS Importer & Aggregator - Css News Widget',
    'description' => 'Displays a news title widget. You can place this widget on news profile page only on your site.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.css-news',
    'requirements' => array(
      'subject' => 'news',
    ),
  ),
  		array(
        'title' => 'SNS - News / RSS Importer & Aggregator - News Contact Information',
        'description' => 'Displays news contact information in this widget. The placement of this widget depends on the news profile page.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesnews.news-contact-information',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show in this widget.",
                        'multiOptions' => array(
                            'name' => 'Contact Name',
														'email' => 'Contact Eamail',
                            'phone' => 'Contact Phone Number',
														'facebook' =>'Contact Facebook',
														'linkedin'=>'Contact Linkedin',
														'twitter'=>'Contact Twitter',
														'website'=>'Contact Website',
                        ),
                    )
                ),
            )
        ),
		),
		    array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Profile News\'s Like Button',
        'description' => 'Displays like button for news. This widget is only placed on "News Profile Page" only.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesnews.like-button',
        'defaultParams' => array(
            'title' => '',
        ),
    ),

    		    array(
        'title' => 'SNS - News / RSS Importer & Aggregator - Profile News\'s Favourite Button',
        'description' => 'Displays favourite button for news. This widget is only placed on "News Profile Page" only.',
        'category' => 'SNS - News / RSS Importer & Aggregator',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesnews.favourite-button',
        'defaultParams' => array(
            'title' => '',
        ),
    ),

//         array(
//         'title' => 'SNS - Advanced Members - Browse Contributors',
//         'description' => 'Displays all members of your site based on criteria. This widgets is placed on "SNS - Advanced Members - Browse Contributor Page" only.',
//         'category' => 'SNS - News / RSS Importer & Aggregator',
//         'type' => 'widget',
//         'autoEdit' => true,
//         'name' => 'sesnews.browse-contributors',
//         'adminForm' => array(
//             'elements' => array(
//                 array(
//                     'Text',
//                     'limit_data',
//                     array(
//                         'label' => 'count (number of members to show).',
//                         'value' => 20,
//                         'validators' => array(
//                             array('Int', true),
//                             array('GreaterThan', true, array(0)),
//                         )
//                     )
//                 ),
//                 $pagging,
//                 	array(
// 	  'Select',
// 	  'info',
// 	  array(
// 	    'label' => 'Choose Popularity Criteria.',
// 	    'multiOptions' => array(
// 	      "recently_created" => "Recently Created",
// 	      "most_viewed" => "Most Viewed",
// 	      "most_liked" => "Most Liked",
// 	      "most_contributors" => "More Articles Written",
// 	    )
// 	  ),
// 	  'value' => 'recently_created',
// 	),
//                 $titleTruncationList,
//                 $photoHeight,
//                 $photowidth,
//             )
//         ),
//     ),

  array(
    'title' => 'SNS News / RSS Importer & Aggregator - Double News Slideshow',
    'description' => 'This widget displays 2 types of news. The one section of this widget will be slideshow and the other will show 3 news based on the criterion chosen in this widget.',
    'category' => 'SNS - News / RSS Importer & Aggregator',
    'type' => 'widget',
    'name' => 'sesnews.news-slideshow',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'criteria',
          array(
            'label' => "Content for 3 news display in left side.",
            'multiOptions' => array(
              '5' => 'All including Featured and Sponsored',
              '1' => 'Only Featured',
              '2' => 'Only Sponsored',
              '3' => 'Both Featured and Sponsored',
              '6' => 'Only Verified',
              '8' => 'Only Hot',
              '9' => 'Only New',
              '4' => 'All except Featured and Sponsored',
            ),
            'value' => 5,
          )
        ),
        array(
            'Text',
            'height',
            array(
                'label' => 'Enter the height of this widget.',
                'value' => '160',
            )
        ),
        array(
          'Select',
          'leftNews',
          array(
            'label' => "Do you want to enable the 3 News in left side?",
            'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
            ),
            'value' => 1,
          )
        ),
        array(
          'Select',
          'enableSlideshow',
          array(
            'label' => "Do you want to enable the slideshow in right side?",
            'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
            ),
            'value' => 1,
          )
        ),
        array(
          'Select',
          'order',
          array(
            'label' => 'Duration criteria for the 3 news to be shown in this widget in left side.',
            'multiOptions' => array(
              '' => 'All News',
              'week' => 'This Week News',
              'month' => 'This Month News',
            ),
            'value' => '',
          )
        ),
        array(
          'Select',
          'info',
          array(
            'label' => 'Choose popularity criteria for the 3 news to be displayed in left side.',
            'multiOptions' => array(
              "recently_created" => "Recently Created",
              "most_viewed" => "Most Viewed",
              "most_liked" => "Most Liked",
              "most_rated" => "Most Rated",
              "most_commented" => "Most Commented",
              "most_favourite" => "Most Favourite",
            )
          ),
          'value' => 'recently_created',
        ),


        array(
          'Select',
          'criteria_right',
          array(
            'label' => "Content for slideshow in right side",
            'multiOptions' => array(
              '5' => 'All including Featured and Sponsored',
              '1' => 'Only Featured',
              '2' => 'Only Sponsored',
              '3' => 'Both Featured and Sponsored',
              '6' => 'Only Verified',
              '8' => 'Only Hot',
              '9' => 'Only New',
              '4' => 'All except Featured and Sponsored',
            ),
            'value' => 5,
          )
        ),
        array(
          'Select',
          'order_right',
          array(
            'label' => 'Duration criteria for the news to be shown in the slideshow of this widget in right side',
            'multiOptions' => array(
              '' => 'All News',
              'week' => 'This Week News',
              'month' => 'This Month News',
            ),
            'value' => '',
          )
        ),
        array(
          'Select',
          'info_right',
          array(
            'label' => 'Choose popularity criteria for the news to be displayed in the slideshow in right side.',
            'multiOptions' => array(
              "recently_created" => "Recently Created",
              "most_viewed" => "Most Viewed",
              "most_liked" => "Most Liked",
              "most_rated" => "Most Rated",
              "most_commented" => "Most Commented",
              "most_favourite" => "Most Favourite",
            )
          ),
          'value' => 'recently_created',
        ),
        array(
          'Select',
          'autoplay',
          array(
            'label' => "Do you want to enable the autoplay of news slideshow?",
            'multiOptions' => array(
              1=>'Yes',
              0=>'No'
            ),
          ),
        ),
        array(
          'Text',
          'speed',
            array(
            'label' => 'Enter the delay time for the next news to be displayed in slideshow. (work if autoplay is enabled.)',
            'value' => '2000',
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'MultiCheckbox',
          'show_criteria',
          array(
            'label' => "Choose from below the details that you want to show for news in this widget.",
            'multiOptions' => array(
              'like' => 'Likes Count',
              'comment' => 'Comments Count',
              'favourite' => 'Favourites Count',
              'view' => 'Views Count',
              'title' => 'News Title',
              'by' => 'News Owner\'s Name',
              'rating' =>'Rating Count',
              'favouriteButton' => 'Favourite Button',
              'likeButton' => 'Like Button',
              'category' => 'Category',
              'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likeButton' => 'Like Button',
              'favouriteButton' => 'Favourite Button',
              'creationDate' => 'Show Publish Date',
              'description' => "Description (This will only show in the slideshow.)"
            ),
            'escape' => false,
          )
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'limit_data',
          array(
            'label' => 'Count for the news to be displayed in slideshow.',
            'value' => 5,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
      ),
    ),
  ),
//   array(
//     'title' => 'SNS - News / RSS Importer & Aggregator - Popular / Featured / Sponsored / Verified News Crawl',
//     'description' => '',
//     'category' => 'SNS - News / RSS Importer & Aggregator',
//     'type' => 'widget',
//     'name' => 'sesnews.news-crawl',
//     'requirements' => array(
//       'subject' => 'news',
//     ),
//     'autoEdit' => true,
//     'adminForm' => array(
//       'elements' => array(
//         array(
//           'Select',
//           'criteria',
//           array(
//             'label' => "Content for news display in this widget.",
//             'multiOptions' => array(
//               '5' => 'All including Featured and Sponsored',
//               '1' => 'Only Featured',
//               '2' => 'Only Sponsored',
//               '3' => 'Both Featured and Sponsored',
//               '6' => 'Only Verified',
//               '8' => 'Only Hot',
//               '9' => 'Only New',
//               '4' => 'All except Featured and Sponsored',
//             ),
//             'value' => 5,
//           )
//         ),
//         array(
//           'Select',
//           'order',
//           array(
//             'label' => 'Duration criteria for the news to be shown in this widget.',
//             'multiOptions' => array(
//               '' => 'All News',
//               'week' => 'This Week News',
//               'month' => 'This Month News',
//             ),
//             'value' => '',
//           )
//         ),
//         array(
//           'Select',
//           'info',
//           array(
//             'label' => 'Choose popularity criteria for the news to be displayed in this widget.',
//             'multiOptions' => array(
//               "recently_created" => "Recently Created",
//               "most_viewed" => "Most Viewed",
//               "most_liked" => "Most Liked",
//               "most_rated" => "Most Rated",
//               "most_commented" => "Most Commented",
//               "most_favourite" => "Most Favourite",
//             )
//           ),
//           'value' => 'recently_created',
//         ),
//         array(
//           'Select',
//           'showCreationDate',
//           array(
//             'label' => "Do you want show Creation Date?",
//             'multiOptions' => array(
//               1=>'Yes',
//               0=>'No'
//             ),
//           ),
//         ),
//         array(
//           'Select',
//           'autoplay',
//           array(
//             'label' => "Do you want to enable the autoplay of news slideshow?",
//             'multiOptions' => array(
//               1=>'Yes',
//               0=>'No'
//             ),
//           ),
//         ),
//         array(
//           'Text',
//           'speed',
//             array(
//             'label' => 'Enter the delay time for the next news to be displayed in slideshow. (work if autoplay is enabled.)',
//             'value' => '2000',
//             'validators' => array(
//               array('Int', true),
//               array('GreaterThan', true, array(0)),
//             )
//           )
//         ),
//         array(
//           'Text',
//           'limit_data',
//           array(
//             'label' => 'Count for the news to be displayed in slideshow.',
//             'value' => 5,
//             'validators' => array(
//               array('Int', true),
//               array('GreaterThan', true, array(0)),
//             )
//           )
//         ),
//       ),
//     ),
//   ),
);
