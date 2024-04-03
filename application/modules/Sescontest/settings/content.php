<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2017-12-01 00:00:00 SocialEngineSolutions $
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
$titleTruncation = array(
    'Text',
    'title_truncation',
    array(
        'label' => 'Enter Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$descriptionTruncation = array(
    'Text',
    'description_truncation',
    array(
        'label' => 'Enter Description Truncation Limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$titleTruncationList = array(
    'Text',
    'list_title_truncation',
    array(
        'label' => 'Enter title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$titleTruncationGrid = array(
    'Text',
    'grid_title_truncation',
    array(
        'label' => 'Title truncation limit for Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$titleTruncationAdvGrid = array(
    'Text',
    'advgrid_title_truncation',
    array(
        'label' => 'Title truncation limit for Advanced Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$titleTruncationPinboard = array(
    'Text',
    'pinboard_title_truncation',
    array(
        'label' => 'Title truncation limit for Pinboard View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$descriptionTruncationList = array(
    'Text',
    'list_description_truncation',
    array(
        'label' => 'Description truncation limit for List View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$descriptionTruncationGrid = array(
    'Text',
    'grid_description_truncation',
    array(
        'label' => 'Description truncation limit for Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$descriptionTruncationAdvGrid = array(
    'Text',
    'advgrid_description_truncation',
    array(
        'label' => 'Description truncation limit for Advanced Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$descriptionTruncationPinboard = array(
    'Text',
    'pinboard_description_truncation',
    array(
        'label' => 'Description truncation limit for Pinboard View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    )
);
$pagging = array(
    'Radio',
    'pagging',
    array(
        'label' => "Do you want the contests to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'auto_load' => 'Yes, Auto Load',
            'button' => 'No, show \'View more\' link.',
            'pagging' => 'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    )
);
return array(
    array(
        'title' => 'SNS - Advanced Contests - Contests Navigation Menu',
        'description' => 'Displays a navigation menu bar in the Advanced Contests Pages for Browse Contest, Contest Home, Categories, Browse Winners, etc pages.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.browse-menu',
        'requirements' => array(
            'no-subject',
        ),
		'adminForm' => array(
		  'elements' => array (
			array(
			  'Radio',
			  'createButton',
			  array(
				'label' => "Enable Create New Contest Button? Note: You can  disable 'Create New Contest' menu from menu editor if you don't want it twice in navigation menu on your website.",
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
        'title' => 'SNS - Advanced Contests - Content Profile Contests',
        'description' => 'This widget enables you to allow user users to create contests on different content on your website like Groups. Place this widget on the content profile page, for example SE Group to enable group owners to create contests in their Groups. You can choose the visibility of the contests created in a content to only that content or show in this plugin as well from the "Contests Created in Content Visibility" setting in Global setting of this plugin.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.other-modules-browse-contests',
        'autoEdit' => true,
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
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose Default View Type (This settings will apply, if you have selected more than one option in above setting)",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                        'value' => 'list',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Title',
                            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            'listdescription' => 'Description (List View)',
                            'griddescription' => 'Description (Grid View)',
                            'pinboarddescription' => 'Description (Pinboard View)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourite Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status (Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Do you want to show contest count in this widget?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of main photo block in List Views (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of main photo block in List Views (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one block in Grid Views (in pixels).',
                        'value' => '270',
                    )
                ),
                array(
                    'Text',
                    'width_grid',
                    array(
                        'label' => 'Enter the width of one block in Grid Views (in pixels).',
                        'value' => '389',
                    )
                ),
                array(
                    'Text',
                    'height_advgrid',
                    array(
                        'label' => 'Enter the height of one block in Advanced Grid Views (in pixels).',
                        'value' => '160',
                    )
                ),
                array(
                    'Text',
                    'width_advgrid',
                    array(
                        'label' => 'Enter the width of one block in Advanced Grid Views (in pixels).',
                        'value' => '250',
                    )
                ),
                array(
                    'Text',
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                $titleTruncationList,
                $titleTruncationGrid,
                $titleTruncationAdvGrid,
                $titleTruncationPinboard,
                $descriptionTruncationList,
                $descriptionTruncationGrid,
                $descriptionTruncationPinboard,
                array(
                    'Text',
                    'limit_data_pinboard',
                    array(
                        'label' => 'Count for Pinboard View (number of contests to show).',
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
                        'label' => 'Count for Grid Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'limit_data_advgrid',
                    array(
                        'label' => 'Count for Advanced Grid View (number of contests to show).',
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
                        'label' => 'Count for List Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $pagging
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Browse Contests',
        'description' => 'Displays all the browsed contests on the page in different views.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.browse-contests',
        'autoEdit' => true,
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
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose Default View Type (This settings will apply, if you have selected more than one option in above setting)",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                        'value' => 'list',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Title',
                            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            'listdescription' => 'Description (List View)',
                            'griddescription' => 'Description (Grid View)',
                            'pinboarddescription' => 'Description (Pinboard View)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourite Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status (Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Do you want to show contest count in this widget?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of main photo block in List Views (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of main photo block in List Views (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one block in Grid Views (in pixels).',
                        'value' => '270',
                    )
                ),
                array(
                    'Text',
                    'height_advgrid',
                    array(
                        'label' => 'Enter the height of one block in Advanced Grid Views (in pixels).',
                        'value' => '160',
                    )
                ),
								array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                $titleTruncationList,
                $titleTruncationGrid,
                $titleTruncationAdvGrid,
                $titleTruncationPinboard,
                $descriptionTruncationList,
                $descriptionTruncationGrid,
                $descriptionTruncationPinboard,
                array(
                    'Text',
                    'limit_data_pinboard',
                    array(
                        'label' => 'Count for Pinboard View (number of contests to show).',
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
                        'label' => 'Count for Grid Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'limit_data_advgrid',
                    array(
                        'label' => 'Count for Advanced Grid View (number of contests to show).',
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
                        'label' => 'Count for List Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $pagging
            ),
        ),
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Profile Contests',
        'description' => 'Displays all the profile contests on the page in different views.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.profile-contests',
        'autoEdit' => true,
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
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose Default View Type (This settings will apply, if you have selected more than one option in above setting)",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                        'value' => 'list',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Title',
                            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            'listdescription' => 'Description (List View)',
                            'griddescription' => 'Description (Grid View)',
                            'pinboarddescription' => 'Description (Pinboard View)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourite Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status (Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Do you want to show contest count in this widget?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of main photo block in List Views (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of main photo block in List Views (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one block in Grid Views (in pixels).',
                        'value' => '270',
                    )
                ),
                array(
                    'Text',
                    'height_advgrid',
                    array(
                        'label' => 'Enter the height of one block in Advanced Grid Views (in pixels).',
                        'value' => '160',
                    )
                ),
               array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                $titleTruncationList,
                $titleTruncationGrid,
                $titleTruncationAdvGrid,
                $titleTruncationPinboard,
                $descriptionTruncationList,
                $descriptionTruncationGrid,
                $descriptionTruncationPinboard,
                array(
                    'Text',
                    'limit_data_pinboard',
                    array(
                        'label' => 'Count for Pinboard View (number of contests to show).',
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
                        'label' => 'Count for Grid Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'limit_data_advgrid',
                    array(
                        'label' => 'Count for Advanced Grid View (number of contests to show).',
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
                        'label' => 'Count for List Views (number of contests to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $pagging
            ),
        ),
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Advance Share Widget',
        'description' => 'This widget allow users to share the current contest on your website and on other social networking websites.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.advance-share',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'advShareOptions',
                    array(
                        'label' => "Choose options to be shown in Advance Share in this widget.",
                        'multiOptions' => array(
                            'privateMessage' => 'Private Message',
                            'siteShare' => 'Site Share',
                            'quickShare' => 'Quick Share',
                            'tellAFriend' => 'Tell A Friend',
                            'addThis' => 'Add This Share Links',
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Join Button',
        'description' => 'Displays Join button for the contest using which users will be able to participate in the contest and enter their Entries. This widget should be placed on "Contest - Contest View Page". ',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.join-contest',
        'autoEdit' => true,
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Countdown For Contest, Entry or Voting\'s Start or End Dates',
        'description' => 'Displays countdown of the contest start and end date / entry submission start and end date / voting end date in attractive dials. This widget should be placed on the "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-countdown',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'heading',
                    array(
                        'label' => 'Heading for the Count Down Widget.',
                        'value' => '',
                    )
                ),
                array(
                    'Select',
                    'type',
                    array(
                        'label' => "Choose end date for which you want to show the countdown in this widget.",
                        'multiOptions' => array(
                            'endtime' => 'End Date for Contest',
                            'joiningendtime' => 'End Date for Entry Submission',
                            'votingendtime' => 'End Date for Voting',
                            'starttime' => 'Start Date of Contest',
                            'joinstarttime' => 'Start Date of Entry Submission',
                            'votingstarttime' => 'Start Date of Voting',
                        ),
                    )
                ),
                array(
                    'Select',
                    'placement_type',
                    array(
                        'label' => "Choose the placement of this widget.",
                        'multiOptions' => array(
                            'sidebar' => 'Left / Right Column',
                            'extended' => 'Middle / Extended Column',
                        ),
                    )
                ),
                array(
                    'Text',
                    'radious',
                    array(
                        'label' => ' Enter the radius of the blocks in left or right sidebar (in pixels).',
                        'value' => '',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Friends Participating',
        'description' => 'Displays currently viewing member\'s friends on your website who have participated in the current contest. This widget should be placed on "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.viewer-friends-participating',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'listing_type',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for participants in this widget.",
                        'multiOptions' => array(
                            'name' => 'Member Name',
                            'contestCount' => 'Created Contests Count',
                            'participationCount' => 'Participating Contests Count',
                        ),
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of member profile photo(in pixels)',
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
                        'label' => 'Enter the width of member profile photo (in pixels).',
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
                        'label' => 'Count (number of members to show).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            ),
        )
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Post a Similar Contest',
        'description' => 'Displays a link to post new contest which would be similar to the current contest. This widget should be placed on "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-quick-create',
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Profile Options',
        'description' => 'Displays a menu of options (Edit, Report, Join, etc.) that can be performed on a Contest on its View Page. You can enable disable any option from the Admin Panel >> Menu Editor.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.profile-options',
        'requirements' => array(
            'subject' => 'contest',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Cover Photo, Details & Options',
        'description' => 'Displays Cover photo of a Contest. You can edit this widget to configure various settings and options to be shown in this widget. This widget should be placed on the "Contests - Contest View Page"',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-view',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'layout_type',
                    array(
                        'label' => "Choose layout of view page.",
                        'multiOptions' => array(
                            '1' => ' Design 1 [Details are shown inside the block]',
                            '2' => ' Design 2 [Details are shown outside the block]',
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'tab_placement',
                    array(
                        'label' => "Choose the placement of the Tabs of tab container in Design 1.",
                        'multiOptions' => array(
                            'in' => 'Inside',
                            'out' => 'Outside'
                        ),
                        'value' => 'in',
                    )
                ),
                array(
                    'Radio',
                    'tab_type',
                    array(
                        'label' => "Choose the placement for the tabs when shown Inside in Design 1 of this widget.",
                        'multiOptions' => array(
                            'full' => 'In Full View',
                            'center' => 'In Center View',
                        ),
                        'value' => 'center',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose options to be shown in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'description' => 'Description [Design 2]',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category [Design 2]',
                            'tag' => 'Show Tags [Design 2]',
                            "startdate" => "Start Date of Contest [Design 2]",
                            "enddate" => "End Date of Contest [Design 2]",
                            "joinstartdate" => "Start Date of Entries [Design 2]",
                            "joinenddate" => "End Date of Entries [Design 2]",
                            "votingstartdate" => "Start Date of Voting [Design 2]",
                            "votingenddate" => "End Date of Voting [Design 2]",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count [Design 2]',
                            'like' => 'Likes Count [Design 2]',
                            'comment' => 'Comments Count [Design 2]',
                            'favourite' => 'Favourites Count [Design 2]',
                            'view' => 'Views Count [Design 2]',
                            'follow' => 'Follow Count [Design 2]',
                            'statusLabel' => 'Status Label [Active, Coming Soon, Ended] [Design 2]',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'optionMenu' => 'Profile Options Menu',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Radio',
                    'photo_type',
                    array(
                        'label' => "Choose photo shown in Main photo in this widget.",
                        'multiOptions' => array(
                            'contestPhoto' => 'Contest Main Photo',
                            'ownerPhoto' => 'Owner’s Photo',
                            'nophoto' => 'Do not show Photo',
                        ),
                        'value' => 'ownerPhoto',
                    )
                ),
                array(
                    'Radio',
                    'show_full_width',
                    array(
                        'label' => "Show this widget in full width in Design 1?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No'
                        ),
                        'value' => 'no',
                    )
                ),
                array(
                    'Text',
                    'margin_top',
                    array(
                        'label' => 'Enter the value of the Margin Top for this widget (in pixels). (This setting will only work if the widget is chosen to be placed in full width.)',
                        'value' => 30,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    ),
                ),
                array(
                    'Text',
                    'description_truncation',
                    array(
                        'label' => 'Enter Description truncation limit. [Supported with Design 2]',
                        'value' => 150,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'cover_height',
                    array(
                        'label' => 'Enter the height of this cover photo container (in pixels).',
                        'value' => 400,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
            ),
        ),
    ),
    array(
        'title' => 'SNS - Contest / Entry Profile - Main Photo',
        'description' => 'Displays Profile photo of a contest or entry depending on its placement. This widget should be placed on the "Contests - Contest View Page" or "Contests - Entry View Page" only.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-photo',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter height of the photo (in pixels).',
                        'value' => '90',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter width of the photo (in pixels).',
                        'value' => '90',
                    )
                ),
                array(
                    'Radio',
                    'show_title',
                    array(
                        'label' => "Do you want to show contest / entry title?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_photo',
                    array(
                        'label' => "Do you want to show Entry main photo for Photo Media Type also?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 0,
                    )
                ),
            ),
        ),
        'requirements' => array(
            'subject' => 'contest',
        ),
    ),
    array(
        'title' => 'SNS - Entry Profile - Graphical Representation for Entry Statistics',
        'description' => 'Displays number of votes, likes, comments, views and favourite on the current entry in graphical representation form. This widget should be placed on "Contests - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.vote-graph',
        'adminForm' => 'Sescontest_Form_Admin_VoteGraph',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Contest Status',
        'description' => 'Displays the current Status of the contest - i.e. Active, Coming Soon or Completed. This widget should be placed on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-status',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'current_status',
                    array(
                        'label' => "Do you want to show the current status of the Contest in this widget?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_js_date',
                    array(
                        'label' => " Do you want to show if the submission of entries in current contest has been started? (This will be shown from start date to end date of Entry Submission if the contest is Active.)",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_je_date',
                    array(
                        'label' => "Do you want to show if the submission of entries in current contest has been ended? (This will be shown after end date of Entry Submission if the contest is Active.If the contest is ended then this setting will not be shown.)",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_vs_date',
                    array(
                        'label' => "Do you want to show if the voting on entries in current contest has been started? (This will be shown from start date to end date of Voting if the contest is Active.)",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_ve_date',
                    array(
                        'label' => " Do you want to show if the voting on entries in current contest has been ended? (This will be shown from start date to end date of Voting if the contest is Active.If the contest is ended then this setting will not be shown.)",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Text',
                    'status_font_size',
                    array(
                        'label' => 'Enter the font size for the Contest Status (in pixels).',
                        'value' => '10px',
                    )
                ),
                array(
                    'Text',
                    'entry_font_size',
                    array(
                        'label' => 'Enter the font size for the submission of entries and voting on entries status (in pixels).',
                        'value' => '10px',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Information',
        'description' => 'Displays information about the contest when placed on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-info',
        'autoEdit' => true,
        'requirements' => array(
            'contest',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => 'Choose from below the details you want to show in this widget.',
                        'multiOptions' => array(
                            'info' => 'Basic Info',
                            'date' => 'When (Start and End Dates)',
                            'description' => 'Description',
                            'overview' => 'Overview',
                            'profilefield' => 'Profile Fields'
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Info Sidebar',
        'description' => 'Displays a Contest\'s info (likes creation details, stats, type, categories, etc) on their profiles. This widget should be placed in the right or left sidebar column on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.profile-info',
        'requirements' => array(
            'subject' => 'contest',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => 'Choose from below the content show in this widget.',
                        'multiOptions' => array(
                            'owner' => 'Created By',
                            'creationDate' => 'Created On',
                            'date' => 'Contest Start & End date',
                            'entryDate' => 'Entry Submission Start & End Date',
                            'voteDate' => 'Voting Start & End Date',
                            'mediaType' => 'Media Type',
                            'categories' => 'Category',
                            'tag' => 'tags',
                            'like' => 'Like Counts',
                            'comment' => 'Comment Counts',
                            'favourite' => 'Favourites Counts',
                            'view' => 'View Counts',
                            'follow' => 'Follow Counts',
                            'entryCount' => 'Entries Count',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Media Type',
        'description' => 'Displays the media type of the contest i.e. photo, video, music or text. This Widget should be placed on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-media-type',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'show_media_type',
                    array(
                        'label' => "Choose the information for the media type to be shown in this widget.",
                        'multiOptions' => array(
                            '1' => 'Icons Only',
                            '2' => 'Text Only',
                            '3' => 'Icons and Text Both',
                        ),
                        'value' => '3',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Similar Contests',
        'description' => 'Displays a list of other contests that are similar to the current contest, based on tags or category as per the criteria chosen by you. This widget should be placed on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-similar-contest',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View [Supported when placed in sidebar only.]',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Radio',
                    'search',
                    array(
                        'label' => "Choose the criteria based on which contests will be shown in this widget.",
                        'multiOptions' => array(
                            'tag' => 'Same Tags',
                            'category' => 'Same Categories'
                        ),
                        'value' => 'tag',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'description' => 'Description (Not supported with Advanced Grid View)',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Categories',
                            "startenddate" => "Start & End Date of Contest",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourite Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                        ),
                        'value' => '',
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels), this setting will work for List View, Grid View and Advanced Grid View.',
                        'value' => '160',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels), this setting will work for List View.',
                        'value' => '250',
                    )
                ),
								array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count for (number of contests to show).',
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
        'title' => 'SNS - Advanced Contests - Contest Profile - Contest Entries',
        'description' => 'Displays Entries of a Contest on "Contests - Contest View Page". You can edit this widget to choose various display criterias.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-entries',
        'autoEdit' => true,
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
                    )
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
                        'label' => "Choose from below the details that you want to show in this widget.",
                        'multiOptions' => array(
                            'title' => 'Entry Title',
                            'listdescription' => 'Description (List View)',
                            'submitDate' => 'Submitted On',
                            'ownerName' => 'Participant Name',
                            'ownerPhoto' => 'Participant Photo',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'voteButton' => 'Vote Button',
                            'voteCount' => 'Votes Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                        ),
                        'escape' => false,
                    )
                ),
                $pagging,
                array(
                    'MultiCheckbox',
                    'sorting',
                    array(
                        'label' => "Choose sorting criteria for entries.",
                        'multiOptions' => array(
                            'Newest' => 'Newest',
                            'Oldest' => 'Oldest',
                            'mostSPvoted' => 'Most Voted',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'mostSPViewed' => 'Most Viewed',
                            'mostSPfavorite' => 'Most Favorite',
                        ),
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Do you want to show entries count in this widget?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
                $titleTruncationList,
                $titleTruncationGrid,
                $descriptionTruncationList,
                $descriptionTruncationGrid,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height_list',
                    array(
                        'label' => 'Enter the height of main photo in List View.',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width_list',
                    array(
                        'label' => 'Enter the width of main photo in List View.',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one entry  block in Grid View. (in pixels)',
                        'value' => '270',
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
                          '3' => 'Four',
                        ),
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of entries to show).',
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
        'title' => 'SNS - Advanced Contests - Contest Profile - People Who Acted on This Contest',
        'description' => 'This widget displays People who Liked / Followed or added current contest as Favourite. This widget should be placed on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.recent-people-activity',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => 'Choose from below the options that you want to show in this widget.',
                        'multiOptions' =>
                        array(
                            'like' => 'People who Liked this contest',
                            'favourite' => 'People who added this contest as Favourite',
                            'follow' => 'People who Followed this contest',
                        ),
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of member profile photo',
                        'value' => 70,
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
                        'label' => 'Enter the width of member profile photo',
                        'value' => 70,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'view_more_like',
                    array(
                        'label' => 'Enter the number of members to be shown in "People Who Liked This Contest" block. After the number of members entered below, option to view more members in popup will be shown.',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'view_more_favourite',
                    array(
                        'label' => 'Enter the number of members to be shown in "People Who Favorited This Contest" block. After the number of members entered below, option to view more members in popup will be shown.',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'view_more_follow',
                    array(
                        'label' => 'Enter the number of members to be shown in "People who added this contest as Favourite" block. After the number of members entered below, option to view more members in popup will be shown. ',
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
        'title' => 'SNS - Advanced Contests - Entry Profile - Uploaded Content',
        'description' => 'Displays uploaded content for the current entry. The recommended page for this widget is "Contests - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.entry-content',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'next',
                    array(
                        'label' => "Do you want to enable next entry button ?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'previous',
                    array(
                        'label' => "Do you want to enable previous entry button ?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Entry Profile - Details & Options',
        'description' => 'This widget displays entry details and various other options. The recommended page for this widget is "Contests - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-entry-view',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Entry" shown in this widget',
                        'multiOptions' => array(
                            'title' => 'Title',
                            'submitDate' => 'Submitted On',
                            'contestName' => 'Contest Name',
                            'votestartenddate' => 'Voting Start End Date',
                            'pPhoto' => 'Participant Photo',
                            'pName' => 'Participant Name',
                            'mediaType' => 'Media Type',
                            'description' => 'Description[Not supported with Text Media Type]',
                            'category' => 'Category',
                            'uploadesContent' => 'Uploaded Content',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'share' => 'Share',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'voteButton' => 'Vote Button',
                            'vote' => 'Votes Count [Will only be displayed if owner choose setting: show votes during voting on entries during contest creation]
',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'view' => 'Views Count',
                            'favourite' => 'Favourite Count',
                            'optionMenu' => 'Profile Options Menu',
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
        'title' => 'SNS - Advanced Contests - Contest Profile - Contest Winner Entries',
        'description' => 'Displays the Winner entries of current Contests. This Widget should be placed on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-winner-entries',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'palcement',
                    array(
                        'label' => "Choose Placement Type (Only List View and Grid View are supported)",
                        'multiOptions' => array(
                            'vertical' => 'Vertical',
                            'horizontal' => 'Horizontal',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for entries in this widget.",
                        'multiOptions' => array(
                            'title' => 'Entry Title',
                            'listdescription' => 'Description (Supported with List View and Pinboard View)',
                            'submitDate' => 'Submitted On',
                            'ownerName' => 'Participant Name',
                            'ownerPhoto' => 'Participant Photo',
                            'rank' => 'Rank',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'voteCount' => 'Votes Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose Default Visibility Order.",
                        'multiOptions' => array(
                            'low' => 'Rank High to Low',
                            'high' => 'Rank Low to High',
                        ),
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block for List View and Grid View (in pixels)',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block for List View and Grid View (in pixels)',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                $titleTruncation,
                $descriptionTruncation,
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Awards',
        'description' => 'Displays the award which is to be given to the winners of the current contest. This widget should be placed on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-award',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => "Choose from below which all awards you want to show in this widget.",
                        'multiOptions' => array(
                            'firstAward' => 'First Award',
                            'secondAward' => 'Second Award',
                            'thirdAward' => 'Third Award',
                            'fourthAward' => 'Fourth Award',
                            'fifthAward' => 'Fifth Award',
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Rules',
        'description' => 'Displays the Rules of current contest as entered by the owner of the contest. This widget should be placed on the "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-rules',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Labels',
        'description' => 'Displays Featured, Sponsored , Verified and Hot labels on current contest depending on the labels it has enabled from the admin panel of this plugin. This widget should be placed on "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-labels',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'option',
                    array(
                        'label' => "Choose options to be shown in this widget.",
                        'multiOptions' => array(
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored',
                            'verified' => 'Verified',
                            'hot' => 'Hot',
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Overview',
        'description' => 'Displays overview of the Contest on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contest-overview',
        'requirements' => array(
            'contest',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contest - Contest Profile - Contact Info',
        'description' => 'Displays the contact information about the contest as entered by the owner of current contest. This widget should be placed on "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.contest-contact-information',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details you want to show in this widget.",
                        'multiOptions' => array(
                            'name' => 'Contact Name',
                            'email' => 'Contact Email',
                            'phone' => 'Contact Phone Number',
                            'facebook' => 'Contact Facebook',
                            'linkedin' => 'Contact Linkedin',
                            'twitter' => 'Contact Twitter',
                            'website' => 'Contact Website',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Tags',
        'description' => 'Displays all the tags of the current contest on the "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.profile-tags',
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
        'title' => 'SNS - Advanced Contests - Contest Profile - Other Contests From Owner',
        'description' => 'Displays the other contests created by the owner of current Contest on which this widget is placed. This widget should be placed on the "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.other-contest',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View [Supported when placed in sidebar only.]',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'description' => 'Description (Not supported with Advanced Grid View)',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Categories',
                            "startenddate" => "Start & End Date of Contest",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels), this setting will work for List View, Grid View and Advanced Grid View.',
                        'value' => '160',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels), this setting will work for List View',
                        'value' => '250',
                    )
                ),
								array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count for (number of contests to show).',
                        'value' => 3,
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
        'title' => 'SNS - Advanced Contests - Contest / Entry Profile - Breadcrumb for Contest View Page',
        'description' => 'Displays Breadcrumb for the contests and entries. This widget should be placed on "Contest - Contest View Page" or "Contest - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sescontest.breadcrumb',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Like Button',
        'description' => 'Displays Like button on the contest using which users will be able to add current contest to their Like lists. This widget should be placed on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.like-button',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Favorite Button',
        'description' => 'Displays Favorite button on the contest using which users will be able to add current contest to their Favorite lists. This widget should be placed on "Contests - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.favourite-button',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest Profile - Follow Button',
        'description' => 'Displays Follow button on the contest using which users will be able to Follow current contest. This widget should be placed on "Contest - Contest View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.follow-button',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contest - Entry Profile - Vote Button',
        'description' => 'Displays a Vote button on the "Contest - Entry View Page". Using this button, users will be able to give their votes to the current entry.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.vote-button',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Entry Profile - Congratulations Message',
        'description' => 'Displays congratulations message on the winners entries which is entered by the respective contest owners in contest dashboard. This widget should be placed on the "Contests - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.congratulation-message',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => "SNS - Advanced Contest - Contest / Entry Profile -  Owner's Photo",
        'description' => 'Displays the photo of the owner of the Contest or the participant of the entry depending on the page on which this widget is placed. This widget should be placed on the "Contest  - Contest View Page" or "Contest - Entry View Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.owner-photo',
        'defaultParams' => array(
            'title' => '',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'showTitle',
                    array(
                        'label' => 'Member’s Name',
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
        'title' => 'SNS - Advanced Contest - Contests Browse Search',
        'description' => 'Displays search form in the contest browse page. This widget should be placed on "Contests - Contest Browse Page.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => 'true',
        'name' => 'sescontest.browse-search',
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
                            'recentlySPcreated' => 'Newest',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'entrymaxtomin' => 'Entries Max to Min',
                            'entrymintomax' => 'Entries Min to Max',
                            'mostSPfavourite' => 'Most Favorited',
                            'mostSPfollower' => 'Most Followed',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified',
                            'hot' => 'Only Hot',
                        ),
                    )
                ),
                array(
                    'Select',
                    'default_search_type',
                    array(
                        'label' => "Default value for 'Browse By' search field.",
                        'multiOptions' => array(
                            'recentlySPcreated' => 'Newest',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'entrymaxtomin' => 'Entries Max to Min',
                            'entrymintomax' => 'Entries Min to Max',
                            'mostSPfavourite' => 'Most Favorited',
                            'mostSPfollower' => 'Most Followed',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified',
                            'hot' => 'Only Hot',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => "Choose options to be shown in 'View' search fields.",
                        'multiOptions' => array(
                            '0' => 'All Contests',
                            'ongoingSPupcomming' => 'Active & Coming Soon Contests',
                            'ongoing' => 'Active Contests',
                            'upcoming' => 'Coming Soon Contests',
                            'ended' => 'Ended Contests',
                            '1' => 'Only My Friend\'s Contests',
                            '2' => 'Only My Network Contests',
                            '3' => 'Only My Contests',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                    )
                ),
                array(
                    'Select',
                    'default_view_search_type',
                    array(
                        'label' => "Default value for 'View' search field.",
                        'multiOptions' => array(
                            '0' => 'All Contests',
                            'ongoingSPupcomming' => 'Active & Coming Soon Contests',
                            'ongoing' => 'Active Contests',
                            'upcoming' => 'Coming Soon Contests',
                            'ended' => 'Ended Contests',
                            '1' => 'Only My Friend\'s Contests',
                            '2' => 'Only My Network Contests',
                            '3' => 'Only My Contests',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_option',
                    array(
                        'label' => "Choose from below the search fields to be shown in this widget.",
                        'multiOptions' => array(
                            'searchContestTitle' => 'Search Contests / Keywords',
                            'view' => 'View',
                            'browseBy' => 'Browse By',
                            'alphabet' => 'Alphabet',
                            'mediaType' => 'Media Type',
                            'chooseDate' => 'Choose Date Range',
                            'Categories' => 'Category ',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contest - Entries / Winners Browse Search',
        'description' => 'Displays a search form in the winners browse page.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.winner-browse-search',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'view_type',
                    array(
                        'label' => "Choose the Placement Type.",
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
                            'recentlySPcreated' => 'Newest',
                            'rankSPlow' => 'Rank High to Low',
                            'rankSPhigh' => 'Rank Low to High',
                            'mostSPvoted' => 'Most Voted',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'mostSPfavourite' => 'Most Favourite',
                        ),
                    )
                ),
                array(
                    'Select',
                    'default_search_type',
                    array(
                        'label' => "Default 'Browse By' search field.",
                        'multiOptions' => array(
                            'recentlySPcreated' => 'Newest',
                            'rankSPlow' => 'Rank High to Low',
                            'rankSPhigh' => 'Rank Low to High',
                            'mostSPvoted' => 'Most Voted',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'mostSPfavourite' => 'Most Favourite',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'criteria',
                    array(
                        'label' => "Choose options to be shown in 'View' search fields.",
                        'multiOptions' => array(
                            '0' => 'All Entries',
                            '1' => 'Only My Friend\'s Entries',
                            '2' => 'Only My Network Entries',
                            '3' => 'Only My Entries',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_option',
                    array(
                        'label' => "Choose from below the search fields to be shown in this widget.",
                        'multiOptions' => array(
                            'searchEntryTitle' => 'Search Entries',
                            'searchContestTitle' => 'Search In Contest',
                            'view' => 'View',
                            'browseBy' => 'Browse By',
                            'mediaType' => 'Media Type',
                            'rank' => 'Rank ',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contest - Tabbed widget for Manage Contests',
        'description' => 'This widget displays contests created, favourite, liked, etc by the member viewing the manage page. Edit this widget to configure various settings.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.manage-contests',
        'requirements' => array(
            'subject' => 'contest',
        ),
        'adminForm' => 'Sescontest_Form_Admin_ManageTabbed',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Tabbed Widget for Popular Contests',
        'description' => 'Displays tabbed widget for contests on your website. Edit this widget to choose and configure the tabs to be shown in this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.tabbed-widget-contest',
        'requirements' => array(
            'subject' => 'contest',
        ),
        'adminForm' => 'Sescontest_Form_Admin_Tabbed',
    ),
    array(
        'title' => 'SNS - Advanced Contest - You May Also Like Contests',
        'description' => 'Displays the contests which the current user viewing the widget may like.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.you-may-also-like-contests',
        'defaultParams' => array(
            'title' => '',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => 'View Type',
                        'multiOptions' => array(
                            'listView' => 'List View',
                            'gridview' => 'Grid View'
                        ),
                        'value' => 'gridview',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show for contests in this widget.',
                        'multiOptions' => array(
                            "title" => "Contest Title",
                            "by" => "Created By",
                            "mediaType" => "Media Type",
                            "category" => "Category",
                            "startenddate" => "Start & End Date of Contest",
                            "description" => "Description",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                            'follow' => 'Follow Counts',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status ( Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    ),
                ),
                $titleTruncation,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
                        'value' => '230',
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
                          '3' => 'Four',
                        ),
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of contests to show)',
                        'value' => 3,
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
        'title' => 'SNS - Advanced Contests - Create New Contest Link',
        'description' => 'Displays a link to create a new contest.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.browse-menu-quick',
        'autoEdit' => true,
        'requirements' => array(
            'no-subject',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'popup',
                    array(
                        'label' => "Do you want to show popup.",
                        'multiOptions' => array(
                            '1' => 'Yes,want to show popup',
                            '0' => 'No,don\'t want to show poup',
                        ),
                        'value' => '1',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Categories Cloud / Hierarchy View',
        'description' => 'Displays all categories with their 2nd and 3rd level categories in cloud view and hierachy view. In hierarchy view categories can be shown either in horizontal view or vertical view and in both the views subcategories can be shown in expanded or collapsed form according to the chosen setting. Edit this widget to make changes accordingly. ',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.category',
        'autoEdit' => true,
        'adminForm' => 'Sescontest_Form_Admin_Tagcloudcategory',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Category Carousel',
        'description' => 'Displays categories in a carousel. The placement criteria of this widget depends upon the chosen criteria for this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.category-carousel',
        'adminForm' => array(
            'elements' => array(
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one contest block (in pixels).',
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
                        'label' => 'Enter the width of one contest block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'speed',
                    array(
                        'label' => 'Enter the delay time for next category when you have enabled autoplay.',
                        'value' => '300',
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
                        'label' => "Enables auto play of slides",
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                    ),
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Choose Popularity Criteria.",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical order',
                            'most_contest' => 'Categories with maximum contests first',
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
                            'countContests' => 'Contest count in each category',
                        ),
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Select',
                    'isfullwidth',
                    array(
                        'label' => 'Do you want to show category carousel in full width?',
                        'multiOptions' => array(
                            1 => 'Yes,want to show this widget in full width.',
                            0 => 'No,don\'t want to show this widget in full width.'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of category to show in this widget,put 0 for unlimited).',
                        'value' => 10,
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Popular Contests Carousel',
        'description' => "Displays contests in attractive carousel based on the chosen criteria in this widget. The placement of this widget is based on the criteria chosen.",
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.featured-sponsored-carousel',
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
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            '5' => 'All Contests',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '7' => 'Only Hot',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_viewed" => "Most Viewed",
                            "most_commented" => "Most Commented",
                            "favourite_count" => "Most Favorited",
                            "follow_count" => "Most Followed",
                            'most_joined' => 'Most Joined',
                        )
                    ),
                    'value' => 'creation_date',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status ( Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                array(
                    'Text',
                    'imageheight',
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
                        'label' => 'Count (number of contests to show).',
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
        'title' => 'SNS - Advanced Contests - Contests - Popular Contests',
        'description' => "Displays contests based on the chosen criteria in this widget. The placement of this widget depend upon the criteria chosen.",
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.featured-sponsored-hot',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View [Supported when placed in sidebar only.]',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'upcoming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            '5' => 'All Contests',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '7' => 'Only Hot',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "recently_created" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_commented" => "Most Commented",
                            "most_viewed" => "Most Viewed",
                            "favourite_count" => "Most Favorited",
                            "follow_count" => "Most Followed",
                            'most_joined' => 'Most Joined',
                        )
                    ),
                    'value' => 'recently_created',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
                            'description' => 'Description (Not supported with List & Advanced Grid View)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourites Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts(Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status ( Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block in List, Grid and Advanced Grid View (in pixels).',
                        'value' => '160',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block in List View (in pixels).',
                        'value' => '250',
                    )
                ),
								array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                        'label' => 'Count for (number of contests to show).',
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
        'title' => 'SNS - Advanced Contests - Alphabetic Filtering of Contests / Entries',
        'description' => "This widget displays all the alphabets for alphabetic filtering of Contests or Entries. Edit this widget to choose content based on which alphabet filtering will be done using this widget.",
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.contest-entry-alphabet',
        'defaultParams' => array(
            'title' => "",
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'contentType',
                    array(
                        'label' => "Choose content type.",
                        'multiOptions' => array(
                            'contests' => 'Contest',
                            'entries' => 'Entries',
                        ),
                        'value' => 'contests',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contest - Contest / Entry of the Day',
        'description' => 'This widget displays Contest of the day or Entry of the day according to the chosen criteria. If there are more than 1 Contest/Entry marked as of the day, then those will show randomly on each page refresh in this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.contest-entry-day-of-the',
        'adminForm' => 'Sescontest_Form_Admin_ContestEntryDayOfThe',
        'defaultParams' => array(
            'title' => 'Contest of the Day',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contest - Browse Entries',
        'description' => 'Displays entries from all the contests and specific contest from which users land on this page on your website. Edit this widget to choose information you want to show in this widget. This widget should be placed on "SNS - Advanced Contests - Entries Browse Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.browse-entries',
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
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose default view type (This settings will apply, if you have selected more than one option in above setting.)",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                        'value' => 'list',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for entries in this widget.",
                        'multiOptions' => array(
                            'title' => 'Entry Title',
                            'contestName' => 'Contest Name',
                            'mediaType' => 'Media Type',
                            'listdescription' => 'Description (List View)',
                            'pinboarddescription' => 'Description (Pinboard View)',
                            'submitDate' => 'Submitted On',
                            'ownerName' => 'Participant Name',
                            'ownerPhoto' => 'Participant Photo',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'voteButton' => 'Vote Button',
                            'voteCount' => 'Votes Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                        ),
                        'escape' => false,
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Do you want to show entries count in this widget?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
                $pagging,
                array(
                    'Radio',
                    'fixed_data',
                    array(
                        'label' => "Show only the number of entries entered in Count setting for each view. [If you choose No, then more entries in this widget will display as per your selection in above setting.]",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'value' => 'no',
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height_list',
                    array(
                        'label' => 'Enter the height of main photo block in List Views (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width_list',
                    array(
                        'label' => 'Enter the width of main photo block in List Views (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one block in Grid Views (in pixels).',
                        'value' => '270',
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
                          '3' => 'Four',
                        ),
                    )
                ),
                array(
                    'Text',
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                $titleTruncationList,
                $titleTruncationGrid,
                $titleTruncationPinboard,
                $descriptionTruncationList,
                $descriptionTruncationGrid,
                $descriptionTruncationPinboard,
                array(
                    'Text',
                    'limit_data_pinboard',
                    array(
                        'label' => 'Count for Pinboard View (number of entries to show).',
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
                        'label' => 'Count for Grid Views (number of entries to show).',
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
                        'label' => 'Count for List Views (number of entries to show).',
                        'value' => 20,
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
        'title' => 'SNS - Advanced Contest - Browse Winners',
        'description' => 'Displays winner entries from all the contests on your website. Edit this widget to choose information you want to show in this widget. This widget should be placed on "Contests - Browse Winners Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.winners-listing',
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
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Choose the view type which you want to display by default. (Settings will apply, if you have selected more than one option in above tab.)",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'grid' => 'Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                        'value' => 'list',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for entries in this widget.",
                        'multiOptions' => array(
                            'title' => 'Entry Title',
                            'contestName' => 'Contest Name',
                            'mediaType' => 'Media Type',
                            'listdescription' => 'Description (List View)',
                            'griddescription' => 'Description (Grid View)',
                            'pinboarddescription' => 'Description (Pinboard View)',
                            'submitDate' => 'Submitted On',
                            'ownerName' => 'Participant Name',
                            'ownerPhoto' => 'Participant Photo',
                            'rank' => 'Rank',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'voteCount' => 'Votes Count',
                            'like' => 'Like Count',
                            'comment' => 'Comment Count',
                            'favourite' => 'Favourite Count',
                            'view' => 'View Count',
                        ),
                        'escape' => false,
                    )
                ),
                $pagging,
                array(
                    'Radio',
                    'fixed_data',
                    array(
                        'label' => "Show only the number of entries entered in Count setting for each view. [If you choose No, then more entries in this widget will display as per your selection in above setting.]",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'value' => 'no',
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'limit_data_list',
                    array(
                        'label' => 'Count for List View (number of entries to show).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $titleTruncationList,
                $descriptionTruncationList,
                array(
                    'Text',
                    'height_list',
                    array(
                        'label' => 'Enter the height of main photo block in List Views (in pixels).',
                        'value' => '230',
                    )
                ),
                array(
                    'Text',
                    'width_list',
                    array(
                        'label' => 'Enter the width of main photo block in List Views (in pixels).',
                        'value' => '260',
                    )
                ),
                array(
                    'Text',
                    'limit_data_grid',
                    array(
                        'label' => 'Count for Grid View (number of entries to show).',
                        'value' => 10,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $titleTruncationGrid,
                $descriptionTruncationGrid,
                array(
                    'Text',
                    'height_grid',
                    array(
                        'label' => 'Enter the height of one block in Grid Views (in pixels).',
                        'value' => '270',
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
                          '3' => 'Four',
                        ),
                    )
                ),
                array(
                    'Text',
                    'limit_data_pinboard',
                    array(
                        'label' => 'Count for Pinboard View (number of entries to show).',
                        'value' => 10,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $titleTruncationPinboard,
                $descriptionTruncationPinboard,
                array(
                    'Text',
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Tags Cloud / Tab View',
        'description' => 'Displays all tags of contest in cloud or tab view. Edit this widget to choose various other settings.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.tag-cloud-contests',
        'autoEdit' => true,
        'adminForm' => 'Sescontest_Form_Admin_TagCloudContest',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Top Members',
        'description' => 'Displays people who most participated in contests, people who most created the contests, and people who most voted for the contest entries.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.top-members',
        'autoEdit' => true,
        'adminForm' => 'Sescontest_Form_Admin_MemberTabbed',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contests - Popular Contests Based on Categories',
        'description' => 'Displays contests based on their categories in attarctive grid view, slideshow or carousel as chosen by you in this widget. Edit this widget to choose number of categories and contests. You can configure various other settings also.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => 'true',
        'name' => 'sescontest.category-associate-contests',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'view_type',
                    array(
                        'label' => "Choose View Type.",
                        'multiOptions' => array(
                            'grid' => 'Grid View',
                            'slideshow' => 'Slideshow',
                            'carousel' => 'Carousel',
                        ),
                        'value' => 'carousel',
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Choose Popularity Criteria for Categories",
                        'multiOptions' => array(
                            'most_contest' => 'Categories with maximum contests first',
                            'alphabetical' => 'Alphabetical Order',
                            'admin_order' => 'Admin selected order for categories',
                        ),
                    ),
                ),
                array(
                    'Radio',
                    'popularty',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget',
                        'multiOptions' => array(
                            'all' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored',
                            'verified' => 'Verified',
                            'hot' => 'Hot',
                        ),
                        'value' => 'ongoing',
                    )
                ),
                array(
                    'Radio',
                    'order',
                    array(
                        'label' => 'Choose Popularity Criteria for Contests',
                        'multiOptions' => array(
                            "creation_date" => "Most Recent",
                            "like_count" => "Most Liked",
                            "comment_count" => "Most Commented",
                            "view_count" => "Most Viewed",
                            "favourite_count" => "Most Favourite",
                            "follow_count" => "Most Followed",
                        ),
                        'value' => 'like_count',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_category_criteria',
                    array(
                        'label' => "Choose the details to be shown with each category in this widget.",
                        'multiOptions' => array(
                            'seeAll' => 'See All link',
                            'countContest' => 'Count of Contest',
                            'categoryDescription' => 'Category Description (Supported in grid View)',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget. (All settings are Supported in Slideshow and Carousel only.)",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'by' => 'Created By',
                            'mediaType' => 'MediaType',
                            'description' => 'Contest Description (Supported in Slideshow)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                            'follow' => 'Follow Counts',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                $pagging,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'grid_description_truncation',
                    array(
                        'label' => 'Enter the Category Description truncation limit(Supported in grid View).',
                        'value' => '45',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $titleTruncation,
                array(
                    'Text',
                    'slideshow_description_truncation',
                    array(
                        'label' => 'Enter contest description truncation limit (Supported in slideshow View).',
                        'value' => '45',
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
                        'label' => 'Enter the height of one block (in pixels) (Supported in slideshow and carousel).',
                        'value' => '230',
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
                          '3' => 'Four',
                        ),
                    )
                ),
                array(
                    'Text',
                    'category_limit',
                    array(
                        'label' => 'Count (number of categories to show)',
                        'value' => '8',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'contest_limit',
                    array(
                        'label' => 'Count (number of contests to show in each category in this widget).',
                        'value' => '8',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Select',
                    'allignment_seeall',
                    array(
                        'label' => "Allignment of see all field",
                        'multiOptions' => array(
                            'left' => 'left',
                            'right' => 'right'
                        ),
                    ),
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Popular Contests Slideshow - Double Contests',
        'description' => 'This widget displays 2 types of contests. The one section of this widget will be slideshow and the other will show 3 contests based on the criterion chosen in this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.contests-slideshow',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'leftContest',
                    array(
                        'label' => "Do you want to enable the 3 Contests in left side?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose popularity criteria for the 3 contests to be displayed in left side.',
                        'multiOptions' => array(
                            "recently_created" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_commented" => "Most Commented",
                            "most_viewed" => "Most Viewed",
                            "most_favourite" => "Most Favorited",
                            "most_followed" => "Most Followed",
                            "most_joined" => "Most Joined",
                        )
                    ),
                    'value' => 'recently_created',
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content in left side.",
                        'multiOptions' => array(
                            '5' => 'All Contests',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '7' => 'Only Hot',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
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
                    'criteria_right',
                    array(
                        'label' => "Display Content for Slideshow in right side.",
                        'multiOptions' => array(
                            '5' => 'All',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '7' => 'Only Hot',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'info_right',
                    array(
                        'label' => 'Choose popularity criteria for the contests to be displayed in the slideshow in right side.',
                        'multiOptions' => array(
                            "recently_created" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_commented" => "Most Commented",
                            "most_viewed" => "Most Viewed",
                            "most_favourite" => "Most Favourited",
                            "most_followed" => "Most Followed",
                            "most_joined" => "Most Joined",
                        )
                    ),
                    'value' => 'recently_created',
                ),
                array(
                    'Select',
                    'navigation',
                    array(
                        'label' => "How do you want to allow your users to navigate to next slide?",
                        'multiOptions' => array(
                            1 => 'Using Bullets',
                            2 => 'Using Navigation Buttons'
                        ),
                    ),
                ),
                array(
                    'Select',
                    'autoplay',
                    array(
                        'label' => "Do you want to enable the autoplay of contests slideshow?",
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                    ),
                ),
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'speed',
                    array(
                        'label' => 'Enter the delay time for the next contest to be displayed in slideshow. (work if autoplay is enabled.)',
                        'value' => '2000',
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
                        'label' => 'Enter the height of this widget.',
                        'value' => '160',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of contests to be displayed in slideshow).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'by' => 'Created By',
                            'category' => 'Category',
                            'mediaType' => 'Media Type',
                            'creationDate' => 'Show Publish Date',
                            'description' => "Description (This will only show in the slideshow.)",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'favourite' => 'Favourites Count',
                            'view' => 'Views Count',
                            'follow' => 'Follow Counts',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status ( Ended or Days Left)',
                            'voteCount' => 'Votes Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Popular Contests Slideshow - Contests with Content',
        'description' => "Displays slideshow of contests with content on 1 side based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.featured-sponsored-verified-hot-slideshow',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            '5' => 'All Contests',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '7' => 'Only Hot',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_viewed" => "Most Viewed",
                            "most_commented" => "Most Commented",
                            "favourite_count" => "Most Favorited",
                            "follow_count" => "Most Followed",
                            'most_joined' => 'Most Joined',
                        )
                    ),
                    'value' => 'creation_date',
                ),
                array(
                    'Select',
                    'isfullwidth',
                    array(
                        'label' => 'Do you want to show slideshow in full width?',
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'autoplay',
                    array(
                        'label' => "Do you want to enable autoplay of contests?",
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                    ),
                ),
                array(
                    'Text',
                    'speed',
                    array(
                        'label' => 'Delay time for next contest when you have enabled autoplay.',
                        'value' => '2000',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Select',
                    'navigation',
                    array(
                        'label' => "Do you want to show buttons or circles to navigate to next slide.",
                        'multiOptions' => array(
                            'nextprev' => 'Show buttons',
                            'buttons' => 'Show circle'
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'description' => 'Description',
                        ),
                        'escape' => false,
                    )
                ),
                $titleTruncation,
                $descriptionTruncation,
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
                        'label' => 'Count (number of contests to show).',
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
        'title' => 'SNS - Advanced Contests - Category View Page for All Category Levels',
        'description' => 'Displays banner, 2nd-level or 3rd level categories and contests associated with the current category on its view page.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.category-view',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
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
                array('Text', "subcategory_title", array(
                        'label' => "Sub-Categories Title for contests",
                        'value' => 'Sub-Categories of this catgeory',
                    )),
                array(
                    'MultiCheckbox',
                    'show_subcatcriteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each categpory block.",
                        'multiOptions' => array(
                            'title' => 'Category title',
                            'icon' => 'Category icon',
                            'countContests' => 'Contest count in each category',
                        ),
                    )
                ),
                array(
                    'Text',
                    'heightSubcat',
                    array(
                        'label' => 'Enter the height of one 2nd-level or 3rd level categor\'s block (in pixels).',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'widthSubcat',
                    array(
                        'label' => 'Enter the width of one 2nd-level or 3rd level category\'s block (in pixels).',
                        'value' => '250px',
                    )
                ),
                array('Select', "show_popular_contests", array(
                        'label' => "Do you want to show popular contests in  banner widget",
                        'multiOptions' => array('1' => 'Yes,want to show popular contest', 0 => 'No,don\'t want to show popular contests'),
                        'value' => 1,
                    )),
                array('Text', "pop_title", array(
                        'label' => "Title for contests",
                        'value' => 'Popular Contests',
                    )),
                array(
                    'Select',
                    'view',
                    array(
                        'label' => "Choose options of contest to be show in banner widget .",
                        'multiOptions' => array(
                            'upcoming' => 'Coming Soon',
                            'ongoing' => 'Active',
                            'past' => 'Ended',
                        ),
                        'value' => 'ongoing',
                    )
                ),
                array(
                    'Select',
                    'info',
                    array(
                        'label' => "choose criteria by which contest shown in banner widget.",
                        'multiOptions' => array(
                            'upcoming' => 'Coming Soon',
                            'ongoing' => 'Active',
                            'past' => 'Ended',
                            'creationSPdate' => 'Recently Created',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'favouriteSPcount' => 'Most Favourite',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified',
                            'hot' => 'Only Hot'
                        ),
                        'value' => 'creationSPdate',
                    )
                ),
                array(
                    'dummy',
                    'dummy1',
                    array(
                        'label' => "Contest Settings"
                    )
                ),
                array('Text', "contest_title", array(
                        'label' => "Contests Title for contests",
                        'value' => 'Contests of this catgeory',
                    )),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show on each contest block.",
                        'multiOptions' => array(
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Like Count',
                            'comment' => 'Comment Count',
                            'favourite' => 'Favourite Count',
                            'view' => 'View Count',
                            'follow' => 'Follow Count',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'startenddate' => 'Start End Date of Contest',
                            'view' => 'Views Count',
                            'title' => 'Contest Title',
                            'mediaType' => 'Media Type',
                            'status' => 'Contest Status (Ended or Days Left)',
                            'by' => 'Contest Owner\'s Name',
                        ),
                        'escape' => false,
                    )
                ),
                $pagging,
                array(
                    'Text',
                    'contest_limit',
                    array(
                        'label' => 'count (number of contests to show).',
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
                        'label' => 'Enter the height of one contest block (in pixels). [Note: This setting will not affect the contest blocks displayed in Advanced View.]',
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
                            '2' => 'Six',
                        )
                    )
                ),
                
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contests - Find Contests Based on Time Period',
        'description' => 'Displays search criterias for searching contests on the basis of All contests or created Today, Tommorrow, This Week, Next Week, This Month or on a Chosen Date in attractive view. You can also enable categories to show in this widget. Edit this widget to select search criterias to be available in this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.find-contests',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'enableTabs',
                    array(
                        'label' => "Choose the criteria for contests.",
                        'multiOptions' => array(
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                            'dateCriteria' => 'Choose Date [Calendar will get open at user end for choosing dates]',
                            'category' => 'Categories',
                        ),
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of categories to show in Less view)',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Radio',
                    'viewMore',
                    array(
                        'label' => "Do you want to give More / Less option for category display in this widget?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'value' => 'yes',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Recently Viewed Contests',
        'description' => 'Displays the contests which are viewed by current member / by his friends (if logged in) / by all members of your website. Edit this widget to configure various settings.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.recently-viewed-item',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "View Type.",
                        'multiOptions' => array(
                            'list' => 'List View [Supported when placed in sidebar only.]',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => 'Display Criteria',
                        'multiOptions' =>
                        array(
                            'by_me' => 'Viewed by current member',
                            'by_myfriend' => 'Viewed by current logged-in member\'s friend',
                            'on_site' => 'Viewed by all members of website'
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'by' => 'Created By',
                            'mediaType' => 'Media Type',
                            'category' => 'Category',
                            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
                            'description' => 'Description (Not supported with List View & Advanced Grid View)',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Likes Count (Not supported with Advanced Grid View)',
                            'comment' => 'Comments Count (Not supported with Advanced Grid View)',
                            'favourite' => 'Favourites Count (Not supported with Advanced Grid View)',
                            'view' => 'Views Count (Not supported with Advanced Grid View)',
                            'follow' => 'Follow Counts (Not supported with Advanced Grid View)',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                            'status' => 'Contest Status (Ended or Days Left)',
                            'voteCount' => 'Vote Count (With Ended Contests Only)',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                array(
                    'Text',
                    'description_truncation',
                    array(
                        'label' => 'Enter Description truncation limit. (Not supported in List View & Advanced Grid View)
',
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
                        'label' => 'Enter the height of one block in List, Grid & Advanced Grid View (in pixels).',
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
                        'label' => 'Enter the width of one block in List View (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
								array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many content you want to show in one row in Grid / Advanced Grid View?",
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
                    'width_pinboard',
                    array(
                        'label' => 'Enter the width of one block in Pinboard View (in pixels).',
                        'value' => '300',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of contests to show.)',
                        'value' => 20,
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
        'title' => 'SNS - Advanced Contests - Popular 3 Contests View',
        'description' => 'Displays contests in attractive view based on the chosen criteria in this widget. The placement of this widget is based on the criteria chosen. In this widget only 3 contests will show attractively.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.featured-sponsored-verified-hot-random-contest',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Choose criteria for contests to be show in this widget.',
                        'multiOptions' => array(
                            '' => 'All Contests',
                            'ended' => 'Ended',
                            'ongoing' => 'Active',
                            'coming' => 'Coming Soon',
                            'ongoingSPupcomming' => 'Active & Coming Soon',
                            'today' => 'Today',
                            'tomorrow' => 'Tomorrow',
                            'week' => 'This Week',
                            'nextweek' => 'Next Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            '5' => 'All Contests',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Verified',
                            '3' => 'Both Featured and Sponsored',
                            '4' => 'All except Featured and Sponsored',
                        ),
                        'value' => 5,
                    )
                ),
                array(
                    'Select',
                    'order_content',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Most Recent",
                            "most_liked" => "Most Liked",
                            "most_viewed" => "Most Viewed",
                            "most_commented" => "Most Commented",
                            "favourite_count" => "Most Favorited",
                            "follow_count" => "Most Followed",
                            'most_joined' => 'Most Joined',
                        )
                    ),
                    'value' => 'creation_date',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for contests in this widget.",
                        'multiOptions' => array(
                            'title' => 'Contest Title',
                            'description' => 'Description',
                            'by' => 'Created By',
                            'creationDate' => 'Created On',
                            'mediaType' => 'Media Type',
                            'category' => 'Categories',
                            "startenddate" => "Start End Date of Contest",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                            'followButton' => 'Follow Button',
                            'joinButton' => 'Join Now Button',
                            'entryCount' => 'Entries Count',
                            'like' => 'Like Count',
                            'comment' => 'Comment Count',
                            'favourite' => 'Favourite Count',
                            'view' => 'View Count',
                            'follow' => 'Follow Count',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'hotLabel' => 'Hot Label',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncation,
                $descriptionTruncation,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels).',
                        'value' => '160',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Contests - Media Type Banner',
        'description' => '',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.mediatype-banner',
        'autoEdit' => false,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'banner_title',
                    array(
                        'label' => 'Enter the Banner Title.',
                        'value' => '',
                    )
                ),
                array(
                    'Textarea',
                    'description',
                    array(
                        'label' => 'Enter the Description.',
                        'value' => '',
                    )
                ),
                array(
                    'Radio',
                    'show_icon',
                    array(
                        'label' => "Show font icon?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'show_full_width',
                    array(
                        'label' => "Show this widget in full width?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No'
                        ),
                        'value' => 'no',
                    )
                ),
                array(
                    'Text',
                    'margin_top',
                    array(
                        'label' => 'Enter the value of the Margin Top for this widget (in pixels). (This setting will only work if the widget is chosen to be placed in full width.)',
                        'value' => 30,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    ),
                ),
                array(
                    'Text',
                    'cover_height',
                    array(
                        'label' => 'Enter the height of this cover photo container (in pixels).',
                        'value' => 400,
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
        'title' => 'SNS - Advanced Contests - Categories - Contest Categories in Icons or Images',
        'description' => 'Displays all categories of contests in circular or square view with their icons or images. Edit this widget to configure various settings.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.contest-category-icons',
        'adminForm' => 'Sescontest_Form_Admin_CategoryIcon',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Category Banner Widget',
        'description' => 'Displays a banner for categories. You can place this widget at browse page of category on your site.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.banner-category',
        'adminForm' => 'Sescontest_Form_Admin_Categorywidget',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Contests tags',
        'description' => 'Displays all contest tags on your website. The recommended page for this widget is "SNS - Advanced Contest - Browse Tags Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.tag-contests',
    ),
    array(
        'title' => 'SNS - Advanced Contest - Contest Media Types with Icons',
        'description' => 'Displays all four media types with attractive icons, titles and description. We recommend you to place this widget on "SNS - Advanced Contests - Contest Welcome Page".',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.media-type-icons',
        'adminForm' => 'Sescontest_Form_Admin_Mediawidget',
    ),
    array(
        'title' => 'SNS - Advanced Contest - How It Works',
        'description' => 'This widget displays the content for how the contest plugin works from creating a contest to announcement of the winners via submitting entries, sharing contests and entries, voting and all the steps involved in using this plugin. It is a quick summary which is displayed in this widget.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.how-it-works',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Parallax Contest Statistics Widget',
        'description' => '',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sescontest.parallax-contest-statistics',
        'adminForm' => 'Sescontest_Form_Admin_Parallaxwidget',
    ),
    array(
        'title' => 'SNS - Advanced Contests - Popular Categories',
        'description' => 'Displays all Contests Categories in grid and list view. This widget can be placed anywhere on the site to display contests categories.',
        'category' => 'SNS - Advanced Contests',
        'type' => 'widget',
        'name' => 'sescontest.popular-categories',
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
                        'label' => "Choose the details to be shown for categories in this widget.",
                        'multiOptions' => array(
                            'description' => 'Category Description [Only Grid View]',
                            'caticon' => 'Category Icon [Both Grid and List View]',
                            'subcaticon' => 'Sub-category Icon [List View]',
                            'socialshare' => 'Social Share Icons [Grid View] <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>'
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
                            'most_faq' => 'Categories with maximum contests first',
                            'admin_order' => 'Admin selected order for categories',
                        ),
                    ),
                ),
                $descriptionTruncation,
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
        /* array(
          'title' => 'SNS - Advanced Contests - Share Content',
          'description' => '',
          'category' => 'SNS - Advanced Contests',
          'type' => 'widget',
          'autoEdit' => true,
          'name' => 'sescontest.share-content',
          'adminForm' => '',
          ), */
        )
?>
