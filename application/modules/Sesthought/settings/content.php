<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2017-12-12 00:00:00 SocialEngineSolutions $
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

$categories = array();
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.pluginactivated')) {
  $categories = Engine_Api::_()->getDbtable('categories', 'sesthought')->getCategoriesAssoc(array('module'=>true));
}

return array(
  array(
    'title' => 'SNS Thoughts - Top Thought Posters',
    'description' => 'Displays all top thought posters on your website.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.top-thought-poster',
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
    'title' => 'SNS Thoughts - People Like Thought',
    'description' => 'Placed on  a Thought view page. You can place this widget on SNS - Thoughts - Thought View Page.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.people-like-item',
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
    'title' => 'SNS Thoughts - Thought Category Icons Block',
    'description' => 'Displays thought categories in block view with their icon, and statistics. You can place this widget on SNS  Thoughts - Browse Categories Page.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.category-icons',
    'adminForm' => array(
      'elements' => array(
        array(
            'Text',
            'heighticon',
            array(
                'label' => 'Enter the height of category icon (in pixels).',
                'value' => '50px',
            )
        ),
        array(
            'Text',
            'widthicon',
            array(
                'label' => 'Enter the width of category icon (in pixels).',
                'value' => '50px',
            )
        ),
				array(
						'Select',
						'gridblock',
						array(
								'label' => "How many content you want to show in one row",
								'multiOptions' => array(
									'12' => 'One',
									'6' => 'Two',
									'4' => 'Three',
									'3' => 'Four',
									'2' => 'Six',
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
                    'most_thought' => 'Categories with maximum thoughts first',
                    'admin_order' => 'Admin selected order for categories',
                ),
            ),
        ),
        array(
            'MultiCheckbox',
            'showStats',
            array(
                'label' => "Choose from below the details that you want to show on each block.",
                'multiOptions' => array(
                    'title' => 'Category title',
                    'countThoughts' => 'Thoughts count in each category',
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
    'title' => "SNS Thoughts - Profile Thoughts",
    'description' => 'This widget display thoughts. This widget can only be placed on "Member Profile Page" only.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.profile-thoughts',
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'stats',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likecount" => "Likes Count",
              "commentcount" => "Comments Count",
              "viewcount" => "Views Count",
              "postedby" => "Thought Owner Name",
              "posteddate" => "Posted Date",
              'source' => "Source",
              'category' => "Category",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Pinboard view',
            'value' => 250,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Text',
          'limit',
          array(
            'label' => 'Count (number of content to show)',
            'value' => 10,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
      )
    ),
  ),
  array(
    'title' => "SNS Thoughts - Browse Thoughts",
    'description' => 'This widget display thoughts. This widget can only be placed on "SNS - Thoughts - Thoughts Browse Page".',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.browse-thoughts',
    'adminForm' => array(
      'elements' => array(
        array(
        'Select',
        'category_id',
          array(
            'label' => 'Choose the category.',
            'multiOptions' => $categories
          ),
          'value' => ''
        ),
        array(
          'MultiCheckbox',
          'stats',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likecount" => "Likes Count",
              "commentcount" => "Comments Count",
              "viewcount" => "Views Count",
              "postedby" => "Thought Owner Name",
              "posteddate" => "Posted Date",
              'source' => "Source",
              'category' => "Category",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Pinboard view',
            'value' => 250,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Radio',
          'pagging',
          array(
          'label' => "Do you want the thoughts to be auto-loaded when users scroll down the page?",
          'multiOptions' => array(
            'auto_load' => 'Yes, Auto Load',
            'button' => 'No, show View more link.',
            'pagging' => 'No, show Pagination.'
            ),
            'value' => 'pagging',
          )
        ),
        array(
          'Text',
          'limit',
          array(
            'label' => 'Count (number of content to show)',
            'value' => 10,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
      )
    ),
  ),
  array(
    'title' => 'SNS Thoughts - Breadcrumb for Thought View Page',
    'description' => 'Displays breadcrumb for Thought. This widget should be placed on the "SNS - Thoughts - Thought View Page."',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.breadcrumb',
  ),
  array(
    'title' => 'Thought Browse Menu',
    'description' => 'Displays a Navigation Menu Bar on SNS Thoughts - Browse Thoughts, Browse Categories and Manage Thoughts Pages.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.browse-menu',
    'requirements' => array(
      'no-subject',
    ),
	'adminForm' => array(
	  'elements' => array (
		array(
		  'Radio',
		  'createButton',
		  array(
			'label' => "Enable Write New Thought Button? Note: You can  disable Write New Thought menu from menu editor if you dont want it twice in navigation menu on your website.",
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
    'title' => 'Thought Browse Search',
    'description' => 'Displays a search form in the thoughts browse page.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.browse-search',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose the View Type.",
            'multiOptions' => array(
              'horizontal' => 'Horizontal',
              'vertical' => 'Vertical'
            ),
            'value' => 'vertical',
          )
        ),
      )
    ),
  ),
  array(
    'title' => "SNS Thoughts - Thought Of the Day",
    'description' => 'This widget display thought of the day.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.thoughts-of-the-day',
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'information',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likeCount" => "Likes Count",
              "commentCount" => "Comments Count",
              "viewCount" => "Views Count",
              "postedby" => "Text Owner Name",
              "posteddate" => "Posted Date",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Grid view.',
            'value' => 300,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Text',
          'description_truncation',
          array(
            'label' => 'Thought truncation limit',
            'value' => 60,
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
    'title' => "SNS Thoughts - Other Thoughts",
    'description' => 'This widget display thoughts other than the current thought on the site. This widget cans only be placed on "SNS - Thoughts - Thought View Page".',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.other-thoughts',
    'defaultParams' => array(
        'title' => 'Other Thoughts',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose the view type which you want to display by default.",
            'multiOptions' => array(
              'list' => 'List View',
              'grid' => 'Pinboard View',
            ),
            'value' => 'list',
          )
        ),
        array(
          'Select',
          'popularity',
          array(
            'label' => 'Popularity Criteria',
            'multiOptions' => array(
              'creation_date' => 'Most Recent',
              'view_count' => 'Most Viewed',
              'like_count' => 'Most Liked',
              'comment_count' => 'Most Commented',
              'modified_date' => 'Recently Updated',
              'random' => "Random"
            ),
            'value' => 'creation_date',
          )
        ),
        array(
          'MultiCheckbox',
          'information',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likeCount" => "Likes Count",
              "commentCount" => "Comments Count",
              "viewCount" => "Views Count",
              "postedby" => "Text Owner Name",
              "posteddate" => "Posted Date",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Pinboard view.',
            'value' => 300,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Text',
          'description_truncation',
          array(
            'label' => 'Thought truncation limit',
            'value' => 60,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'limit',
          array(
            'label' => 'Count (number of thoughts to show)',
            'value' => 3,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
      )
    ),
  ),
  array(
    'title' => "SNS Thoughts - Popular Thoughts",
    'description' => 'This widget display thoughts based on popularity criteria.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'autoEdit' => true,
    'name' => 'sesthought.popularity-thoughts',
    'defaultParams' => array(
        'title' => 'Popular Thoughts',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose the view type which you want to display by default.",
            'multiOptions' => array(
              'list' => 'List View',
              'grid' => 'Pinboard View',
            ),
            'value' => 'list',
          )
        ),
        array(
          'Select',
          'popularity',
          array(
            'label' => 'Popularity Criteria',
            'multiOptions' => array(
              'creation_date' => 'Most Recent',
              'view_count' => 'Most Viewed',
              'like_count' => 'Most Liked',
              'comment_count' => 'Most Commented',
              'modified_date' => 'Recently Updated',
            ),
            'value' => 'creation_date',
          )
        ),
        array(
          'MultiCheckbox',
          'information',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likeCount" => "Likes Count",
              "commentCount" => "Comments Count",
              "viewCount" => "Views Count",
              "postedby" => "Thought Owner Name",
              "posteddate" => "Posted Date",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Pinboard view',
            'value' => 250,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Text',
          'description_truncation',
          array(
            'label' => 'Thought truncation limit',
            'value' => 60,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'limit',
          array(
            'label' => 'Count (number of thoughts to show)',
            'value' => 3,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
      )
    ),
  ),
  array(
    'title' => 'SNS Thoughts - Recently Viewed Thoughts',
    'description' => 'This widget displays the recently viewed thought by the user who is currently viewing your website or by the logged in members friend. Edit this widget to choose whose recently viewed content will show in this widget.',
    'category' => 'SNS - Thoughts Plugin',
    'type' => 'widget',
    'name' => 'sesthought.recently-viewed-thought',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose the view type which you want to display by default.",
            'multiOptions' => array(
              'list' => 'List View',
              'grid' => 'Pinboard View',
            ),
            'value' => 'list',
          )
        ),
        array(
          'Select',
          'criteria',
          array(
            'label' => 'Popularity Criteria',
            'multiOptions' =>
            array(
              'by_me' => 'Thoughts viewed by me',
              'by_myfriend' => 'Thoughts viewed by my friends',
              'byallmembers' => 'Thoughts viewed by all members',
            ),
          ),
        ),
        array(
          'MultiCheckbox',
          'information',
          array(
            'label' => 'Choose the options that you want to be displayed in this widget.',
            'multiOptions' => array(
              "title" => "Title",
              "likeCount" => "Likes Count",
              "commentCount" => "Comments Count",
              "viewCount" => "Views Count",
              "postedby" => "Thought Owner Name",
              "posteddate" => "Posted Date",
              'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
              'likebutton' => 'Like Button',
              "permalink" => "Permalink",
            ), 
            'escape' => false,
          ),
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Text',
          'width',
          array(
            'label' => 'Enter width of Pinboard view.',
            'value' => 300,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          ),
        ),
        array(
          'Text',
          'description_truncation',
          array(
            'label' => 'Thought truncation limit',
            'value' => 60,
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Text',
          'limit',
          array(
            'label' => 'count (number of thoughts to show)',
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
);
