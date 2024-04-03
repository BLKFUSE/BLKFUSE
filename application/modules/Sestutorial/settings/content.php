<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2017-10-16 00:00:00 SocialEngineSolutions $
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


//New File System Code
$banner_options = array('' => '');
$files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
foreach( $files as $file ) {
  $banner_options[$file->storage_path] = $file->name;
}

return array(
  array(
    'title' => 'SNS - Tutorials - Table of Content',
    'description' => 'Displays all Tutorials and categories in table of content format. You can place this widget at any place of your website. This widget will highlight the Tutorial being viewed, is this is placed on Tutorial View Page.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.table-of-content',
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose the view of the content to be shown in this widget.",
            'multiOptions' => array(
              'expanded' => 'Expanded View',
              'collapsed' => 'Collapsed View',
            ),
            'value' => 'collapsed',
          ),
        ),
        array(
          'Select',
          'showicons',
          array(
            'label' => "Do you want to enable users to expand / collapse the content of this table? [Will work in Expanded View]",
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
            ),
            'value' => 1,
          ),
        ),
      ),
    ),
  ),
  array(
    'title' => 'SNS - Tutorials - Browse All Tutorial Tags',
    'description' => 'Displays all Tutorials tags on your website. The recommended page for this widget is "SNS - Tutorials - Browse Tags Page".',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.tag-tutorials',
  ),
  array(
      'title' => 'SNS - Tutorials - Tags Cloud / Tab View',
      'description' => 'Displays all tags of Tutorials in cloud or tab view. Edit this widget to choose various other settings.',
      'category' => 'SNS - Multi - Use Tutorials Plugin',
      'type' => 'widget',
      'name' => 'sestutorial.tag-cloud-tutorials',
      'autoEdit' => true,
      'adminForm' => 'Sestutorial_Form_Admin_Tagcloudtutorial',
  ),
  array(
    'title' => 'SNS - Tutorials - Tutorials Browse Search',
    'description' => 'Displays a search form in the Tutorials browse page. Edit this widget to choose the search option to be shown in the search form.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'autoEdit' => true,
    'type' => 'widget',
    'name' => 'sestutorial.browse-search',
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "View Type",
            'multiOptions' => array(
                'horizontal' => 'Horizontal',
                'vertical' => 'Vertical',
            ),
            'value' => 'horizontal',
          ),
        ),
      ),
    ),
  ),
  
  array(
    'title' => 'SNS - Tutorials - Tutorial View - Breadcrumb',
    'description' => 'Displays breadcrumb for Tutorials. This widget should be placed on the Tutorials View Page.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'autoEdit' => true,
    'type' => 'widget',
    'name' => 'sestutorial.breadcrumb',
    'autoEdit' => true,
  ),
  array(
    'title' => 'SNS - Tutorials - Category View - Banner',
    'description' => 'Displays category banner. This widget will be place only on "Tutorials - Category View Page"',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.category-banner',
  ),
  array(
    'title' => 'SNS - Tutorials - Categories Hierarchy Sidebar View',
    'description' => 'Displays all the categories of this Tutorial plugin in category level hierarchy view as chosen by you. Edit this widget to choose to show / hide icons in this widget. Clicking on any category in this widget will redirect users to the Browse Tutorials page.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.category',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'image',
          array(
            'label' => "Do you want to show category icon in this widget?",
            'multiOptions' => array(
              0 => 'Yes',
              1 => 'No',
            ),
          ),
        ),
      ),
    ),
  ),
  array(
    'title' => 'SNS - Tutorials - Category View - Categories of all Levels',
    'description' => 'Displays 2nd-level or 3rd level categories and Tutorials associated with the current category on the respective category\'s view page. This widget should be placed on the "Tutorials - Category View Page".',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.category-view-page',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Choose content to be shown in this widget.",
            'multiOptions' => array(
              'subcategoryview' => 'Sub/3rd-categories and Tutorials',
              'listtutorials' => 'Only Tutorials',
            ),
          ),
        ),
        array(
          'Select',
          'viewtype',
          array(
            'label' => "Choose View Type for Tutorial.",
            'multiOptions' => array(
              'listview' => 'Expanded List View',
              'onlytutorialview' => 'Only Tutorial Link View',
              'gridview' => 'Grid View',
            ),
          ),
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria for Tutorials.",
            'multiOptions' => array(
              //'featured' => 'Only Featured',
              //'sponsored' => 'Only Sponsored',
              'creation_date' => 'Recenty Created',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
            ),
          ),
        ),
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Select if you want to show below details in this widget.",
            'multiOptions' => array(
              'caticon' => 'Icon for Sub/3rd-categories. (Appear if you have chooses Sub/3rd-categories to be shown).',
              'viewall' => 'View All Link to view all Tutorials. (Appear only in Collapsed View) '
            ),
          )
        ),
        array(
          'MultiCheckbox',
          'showinformation1',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget. (Will work only for Expanded List View and Grid View).",
            'multiOptions' => array(
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'description' => 'Description',
			  'readmorelink' => "Read More",
            ),
          )
        ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limit for Tutorials.',
                'value' => 50,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'tutorialdescriptionlimit',
            array(
                'label' => 'Description truncation limit for Tutorials.',
                'value' => 200,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'gridblockheight',
            array(
                'label' => 'Enter the height of one block in Grid View (in pixels).',
                'value' => 250,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'limit_data',
            array(
                'label' => 'Count (number of Sub/3rd-categories to show).',
                'value' => 10,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limitdatatutorial',
            array(
                'label' => 'Count (number of Tutorials to be shown in each Sub/3rd-categories).',
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
    'title' => 'SNS - Tutorials - Browse Category - Category Associated Tutorials',
    'description' => 'Displays Tutorials based on main category. This widget only for browse category page only.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.category-associate-tutorial',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewtype',
          array(
            'label' => "Choose View Type for Tutorials.",
            'multiOptions' => array(
              'listview' => 'Expanded List View',
              'onlytutorialview' => 'Only Tutorial Link View',
              'gridview' => 'Grid View',
            ),
          ),
        ),
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget. (Will work only for Expanded List View and Grid View).",
            'multiOptions' => array(
              'photo' => 'Photo (will work in Grid view.)',
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'description' => 'Description',
							'readmorelink' => "Read More",
            ),
          )
        ),
        array(
          'Select',
          'gridblock',
          array(
              'label' => "How many content you want to show in one row in Grid View?",
              'multiOptions' => array(
                '12' => 'One',
                '6' => 'Two',
                '4' => 'Three',
              ),
          )
      ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limit for Tutorials.',
                'value' => 60,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'tutorialdescriptionlimit',
            array(
                'label' => 'Description truncation limit for Tutorials.',
                'value' => 200,
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
						'label' => 'Count (number of categories to show).',
						'value' => 3,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						),
					),
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria for Tutorials.",
            'multiOptions' => array(
              'creation_date' => 'Recenty Created',
              //'featured' => 'Only Featured',
             // 'sponsored' => 'Only Sponsored',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
            ),
          ),
        ),
        array(
          'Select',
          'showviewalllink',
          array(
            'label' => 'Do you want to show "See All Tutorials" link for each category?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
            ),
            'value' => 1,
          ),
        ),
        array(
					'Text',
					'limitdatatutorial',
					array(
						'label' => 'Count (number of Tutorials to show).',
						'value' => 3,
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
    'title' => 'SNS - Tutorials - Browse Tutorials',
    'description' => 'Displays Tutorials on browse page only.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.browse-tutorials',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewtype',
          array(
            'label' => "Choose View Type",
            'multiOptions' => array(
              'listview' => 'Expanded List View',
              'onlytutorialview' => 'Only Tutorial Link View',
              'gridview' => 'Grid View',
            ),
          ),
        ),
        array(
          'Select',
          'showicons',
          array(
            'label' => "Do you want to enable users to expand / collapse Tutorials in Expanded List View?",
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
            ),
            'value' => 1,
          ),
        ),
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget. (Will work only for Expanded List View and Grid View).",
            'multiOptions' => array(
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'description' => 'Description',
							'readmorelink' => "Read More",
							'photo' => 'Photo (will work in Grid view.)',
            ),
          )
        ),
        array(
          'Select',
          'paginationType',
          array(
              'label' => 'Do you want the Tutorials to be auto-loaded when users scroll down the page?',
              'multiOptions' => array(
                  '1' => 'Yes',
                  '0' => 'No, show \'View More\''
              ),
              'value' => 1,
          )
        ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limit for Tutorials.',
                'value' => 60,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'tutorialdescriptionlimit',
            array(
                'label' => 'Description truncation limit for Tutorials.',
                'value' => 200,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
					'Text',
					'limitdatatutorial',
					array(
						'label' => 'Count (number of Tutorials to show).',
						'value' => 3,
						'validators' => array(
							array('Int', true),
							array('GreaterThan', true, array(0)),
						),
					),
        ),
        array(
          'Select',
          'gridblock',
          array(
              'label' => "How many content you want to show in one row in Grid View?",
              'multiOptions' => array(
                '12' => 'One',
                '6' => 'Two',
                '4' => 'Three',
              ),
          )
       ),
      ),
    ),
  ),
  array(
    'title' => 'SNS - Tutorials - Categories Icon View',
    'description' => 'Displays Tutorials categories in icon view. This widget can be placed anywhere on the website.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.categories',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for categories in this widget.",
            'multiOptions' => array(
              'title' => 'Title',
              'socialshare' => 'Social Share Icons [Grid View] <a class="smoothbox" href="/admin/sesbasic/settings/faqwidget">[FAQ]</a>'
            ),
            'escape' => false,
          )
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
            'Text',
            'mainblockheight',
            array(
                'label' => 'Enter the main block height (in pixels).',
                'value' => 200,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'mainblockwidth',
            array(
                'label' => 'Enter the main block width (in pixels).',
                'value' => 250,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'categoryiconheight',
            array(
                'label' => 'Enter the category icon height (in pixels).',
                'value' => 75,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
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
              ),
          )
      ),
      ),
    ),
  ),
  array(
    'title' => 'SNS - Tutorials - Tutorials Navigation Menu',
    'description' => 'Displays a navigation menu bar in the Tutorials Pages for Browse Tutorials, Tutorials Home, Categories, etc pages.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.browse-menu',
  ),
  array(
    'title' => 'SNS - Tutorials - Tutorial View - Related Tutorials widget',
    'description' => 'Displays a list of other Tutorials that are related to the current Tutorial based on category. This widget will be placed on the "Tutorials - Tutorial View Page".',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.related-tutorials',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget.",
            'multiOptions' => array(
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'description' => 'Description',
            ),
          )
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria of Tutorials.",
            'multiOptions' => array(
              'creation_date' => 'Recenty Created',
              //'featured' => 'Only Featured',
              //'sponsored' => 'Only Sponsored',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
            ),
          ),
        ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limit for Tutorials.',
                'value' => 25,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'tutorialdescriptionlimit',
            array(
                'label' => 'Description truncation limit for Tutorials.',
                'value' => 50,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limitdatatutorial',
            array(
                'label' => 'Count (number of Tutorials to show).',
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
    'title' => 'SNS - Tutorials - Sidebar Popular Tutorials widget',
    'description' => 'Displays popular Tutorials in the sidebar of your website. This widget can be placed any where on the website.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.popular-tutorial',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget.",
            'multiOptions' => array(
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'description' => 'Description',
              "viewAllLink" => "View All Link",
            ),
          )
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria of Tutorials.",
            'multiOptions' => array(
              'creation_date' => 'Recenty Created',
             //// 'featured' => 'Only Featured',
             // 'sponsored' => 'Only Sponsored',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
              'helpful_count' => "Most Helpful",
            ),
          ),
        ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limit for Tutorials.',
                'value' => 25,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'tutorialdescriptionlimit',
            array(
                'label' => 'Description truncation limit for Tutorials.',
                'value' => 50,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limitdatatutorial',
            array(
                'label' => 'Count (number of Tutorials to show).',
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
//   array(
//     'title' => 'Side Bar Category widget',
//     'description' => 'Displays Tutorial category in hierarchy.',
//     'category' => 'SNS - Multi - Use Tutorials Plugin',
//     'type' => 'widget',
//     'name' => 'sestutorial.category-menu-list',
//     'autoEdit' => true,
//     'adminForm' => array(
//       'elements' => array(
//       ),
//     ),
//   ),
  array(
    'title' => 'SNS - Tutorials - Tutorial View - Tutorial View Page',
    'description' => 'Displays detailed informtion for the Tutorial. This widget should be placed on the "Tutorials - Tutorial View Page".',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.tutorial-view-page',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for Tutorials in this widget.",
            'multiOptions' => array(
              'likecount' => 'Likes Count',
              'viewcount' => 'Views Count',
              'commentcount' => 'Comments Count',
              'ratingcount' => 'Ratings Count',
              'category' => 'Category, Sub-Category and 3rd Level Category',
              'tags' => 'Keyword Tags',
              'socialshare' => 'Social Share Icons [Grid View] <a class="smoothbox" href="admin/sesbasic/settings/tutorialwidget">[Tutorial]</a>',
              'siteshare' => 'Site Share',
              'report' => 'Report',
              'showhelpful' => 'Was This Helpful',
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
    'title' => 'SNS - Tutorials - Banner with Tutorials Search',
    'description' => 'Displays a banner with the auto-suggest search box for Tutorials. As user types, Tutorials will be displayed in an auto-suggest box. This widget can be placed any where on the website.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.banner-search',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'backgroundimage',
          array(
            'label' => 'Choose the background image to be shown in this widget.',
            'multiOptions' => $banner_options,
            'value' => '',
          )
        ),	
        array(
          'Select',
          'logo',
          array(
            'label' => 'Choose the logo to be shown in this widget.',
            'multiOptions' => $banner_options,
            'value' => '',
          )
        ),
        array(
          'Select',
          'showfullwidth',
          array(
            'label' => 'Do you want to show this banner in full width?',
            'multiOptions' => array(
              'full' => 'Yes, show in full width.',
              'half' => 'No, do not show in full width.',
            ),
            'value' => 'full',
          )
        ),
        array(
          'Select',
          'autosuggest',
          array(
            'label' => 'Do you want to provide auto-suggest search box in this widget?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No',
            ),
            'value' => 1,
          )
        ),
        array(
            'Text',
            'height',
            array(
                'label' => 'Enter the height of this banner (in pixels).',
                'value' => 400,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
          'Text',
          'bannertext',
          array(
            'label' => 'Enter the title to be shown in this banner.',
            'value' => 'How Can We Help You Today?',
          )
        ),
        array(
          'Text',
          'description',
          array(
            'label' => 'Enter the description to be shown in this banner.',
          )
        ),
        array(
          'Text',
          'textplaceholder',
          array(
            'label' => 'Enter placeholder text for the auto-suggest search box.',
            'value' => 'Type your keyword for search',
          )
        ),
        array(
          'Select',
          'template',
          array(
            'label' => "Choose the design template for the banner.",
            'multiOptions' => array(
              1 => 'Design - 1',
              2 => 'Design - 2',
              3 => 'Design - 3',
            ),
          ),
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria of Tutorials.",
            'multiOptions' => array(
              //'featured' => 'Only Featured',
              //'sponsored' => 'Only Sponsored',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'creation_date' => 'Recenty Created',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
            ),
          ),
        ),
        array(
            'Text',
            'limit',
            array(
                'label' => 'Count (number of Tutorials to show).',
                'value' => 5,
            )
        ),
      ),
    ),
  ),
  array(
      'title' => 'SNS - Tutorials - Categories Carousel',
      'description' => 'Displays categories in attractive carousel in this widget. This widget can be placed any where on the website.',
      'type' => 'widget',
      'category' => 'SNS - Multi - Use Tutorials Plugin',
      'autoEdit' => true,
      'name' => 'sestutorial.tutorial-carousel',
      'adminForm' => array(
          'elements' => array(
              array(
                'MultiCheckbox',
                'showdetails',
                array(
                  'label' => "Choose the details that you want to be shown in this widget.",
                  'multiOptions' => array(
                    'categorytitle' => 'Title',
                    'description' => 'Description',
                    'socialshare' => 'Social Share Icons [Grid View] <a class="smoothbox" href="admin/sesbasic/settings/tutorialwidget">[Tutorial]</a>'
                  ),
                  'escape' => false,
                )
              ),
              $socialshare_enable_plusicon,
              $socialshare_icon_limit,
              array(
                  'Select',
                  'viewType',
                  array(
                      'label' => "View Type",
                      'multiOptions' => array(
                          'horizontal' => 'Horizontal',
                          'vertical' => 'Vertical',
                      ),
                      'value' => 'horizontal',
                  ),
              ),
              array(
                  'Text',
                  'height',
                  array(
                      'label' => 'Enter the height of one block (in pixels).',
                      'value' => 200,
                      'validators' => array(
                          array('Int', true),
                          array('GreaterThan', true, array(0)),
                      )
                  ),
              ),
              array(
                  'Text',
                  'heightphoto',
                  array(
                      'label' => 'Enter the height of photo (in pixels).',
                      'value' => 200,
                      'validators' => array(
                          array('Int', true),
                          array('GreaterThan', true, array(0)),
                      )
                  ),
              ),
              array(
                'Text',
                'width',
                array(
                  'label' => 'Enter the width of one block for Grid View (in pixels).',
                  'value' => 200,
                  'validators' => array(
                    array('Int', true),
                     array('GreaterThan', true, array(0)),
                  )
                ),
              ),
              array(
                'Text',
                'itemCount',
                array(
                  'label' => 'Count',
                  'description' => '(number of categories to show)',
                  'value' => 5,
                  'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                  ),
                )
              ),
          )
      ),
  ),
  array(
    'title' => 'SNS - Tutorials - Popular Categories',
    'description' => 'Displays all Tutorials categories in grid and list view. This widget can be placed anywhere on the site to display Tutorial categories.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.home-category',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
            'label' => "Categories View Type",
            'multiOptions' => array(
              'list' => 'List View',
              'grid' => 'Grid View'
            ),
          )
        ),
        array(
          'Select',
          'showsubcategory',
          array(
            'label' => "Do you want to show sub-categories in this widget? (Works only with the List View)",
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
          )
        ),
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown for categories in this widget. (Will work only for Grid View).",
            'multiOptions' => array(
              'description' => 'Category Description [Only Grid View]',
              'caticon' => 'Category Icon [Both Grid and List View]',
              'subcaticon' => 'Sub-category Icon [List View]',
              'socialshare' => 'Social Share Icons [Grid View] <a class="smoothbox" href="admin/sesbasic/settings/tutorialwidget">[Tutorial]</a>'
            ),
            'escape' => false,
          )
        ),
        $socialshare_enable_plusicon,
        $socialshare_icon_limit,
        array(
          'Select',
          'criteria',
          array(
            'label' => "Choose Popularity Criteria.",
            'multiOptions' => array(
              'alphabetical' => 'Alphabetical order',
              'most_tutorial' => 'Categories with maximum Tutorials first',
              'admin_order' => 'Admin selected order for categories',
            ),
          ),
        ),
        array(
            'Text',
            'descriptionlimit',
            array(
                'label' => 'Description truncation limit.',
                'value' => 100,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'mainblockheight',
            array(
                'label' => 'Enter the main block height for grid view (in pixels).',
                'value' => 300,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'mainblockwidth',
            array(
                'label' => 'Enter the main block width for grid view (in pixels).',
                'value' => 250,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'categoryiconheight',
            array(
                'label' => 'Enter the category icon height (in pixels).',
                'value' => 122,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'categoryiconwidth',
            array(
                'label' => 'Enter the category icon width (in pixels).',
                'value' => 122,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'subcaticonheight',
            array(
                'label' => 'Enter the sub-category icon height (in pixels).',
                'value' => 122,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'subcaticonwidth',
            array(
                'label' => 'Enter the sub-category icon width (in pixels).',
                'value' => 122,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            ),
        ),
        array(
            'Text',
            'limit_data',
            array(
                'label' => 'Count (number of categories to show).',
                'value' => 4,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limitsubcat',
            array(
                'label' => 'Count (number of sub-categories to show).',
                'value' => 4,
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
    'title' => 'SNS - Tutorials - Categories with Tutorials',
    'description' => 'Displays Tutorials based on Category.',
    'category' => 'SNS - Multi - Use Tutorials Plugin',
    'type' => 'widget',
    'name' => 'sestutorial.category-listing',
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'MultiCheckbox',
          'showinformation',
          array(
            'label' => "Choose the details to be shown in this widget.",
            'multiOptions' => array(
              'caticon' => 'Category Icon In Heading',
              'viewall' => 'View All Link Tutorials link'
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
              'most_tutorial' => 'Categories with maximum Tutorials first',
              'admin_order' => 'Admin selected order for categories',
            ),
          ),
        ),
        array(
          'Select',
          'tutorialcriteria',
          array(
            'label' => "Choose Popularity Criteria for Tutorials.",
            'multiOptions' => array(
            //  'featured' => 'Only Featured',
           //   'sponsored' => 'Only Sponsored',
              'like_count' => 'Most Liked',
              'rating' => 'Most Rated',
              'creation_date' => 'Recenty Created',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
            ),
          ),
        ),
        array(
          'Select',
          'showtutorialicon',
          array(
            'label' => "Do you want to show default icon for Tutorials?",
            'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
            ),
          ),
        ),
        array(
            'Text',
            'tutorialtitlelimit',
            array(
                'label' => 'Title truncation limitfor Tutorials.',
                'value' => 50,
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
                'label' => 'Count (number of categories to show).',
                'value' => 4,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                )
            )
        ),
        array(
            'Text',
            'limitdatatutorial',
            array(
                'label' => 'Count (number of Tutorials to be shown in each category).',
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


);
