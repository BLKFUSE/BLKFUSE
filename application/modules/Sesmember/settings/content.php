<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
$interest = array();
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesinterest.pluginactivated',0)) {
    $interest = array(
        'Radio',
        'showinterest',
        array(
            'label' => "Show Interest search field?",
            'multiOptions' => array(
                'yes' => 'Yes',
                'no' => 'No',
                'hide' => 'Show this option when click on "Advanced Settings  Button"',
            ),
            'value' => 'yes',
        )
    );
}

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
    'validators' => array(
        array('Int', true),
        array('GreaterThan', true, array(0)),
    ),
  ),
);

if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesblog.pluginactivated',0)) {
    $browseMemberSearch = array(
        'MultiCheckbox',
        'search_type',
        array(
            'label' => "Choose options to be shown in 'Browse By' search fields.",
            'multiOptions' => array(
                'recentlySPcreated' => 'Recently Created',
                'mostSPviewed' => 'Most Viewed',
                'mostSPliked' => 'Most Liked',
                'mylike' => 'Members I Liked',
                'myfollow' => 'Members I Followed',
                'mostSPreviewed' => 'Most Reviewed [Dependant on Advanced Blog Plugin]',
                'mostcontributors' => 'Most Contributors [Dependant on Advanced Blog Plugin]',
                'mostSPcommented' => 'Most Commented [Dependant on Advanced Blog Plugin]',
                'atoz' => 'A to Z',
                'ztoa' => 'Z to A',
                'mostSPrated' => 'Most Rated',
                'featured' => 'Only Featured',
                'sponsored' => 'Only Sponsored',
                'verified' => 'Only Verified',
            ),
        ),
    );
} else {
    $browseMemberSearch = array(
        'MultiCheckbox',
        'search_type',
        array(
            'label' => "Choose options to be shown in 'Browse By' search fields.",
            'multiOptions' => array(
                'recentlySPcreated' => 'Recently Created',
                'mostSPviewed' => 'Most Viewed',
                'mostSPliked' => 'Most Liked',
                'mylike' => 'Members I Liked',
                'myfollow' => 'Members I Followed',
                'atoz' => 'A to Z',
                'ztoa' => 'Z to A',
                'mostSPrated' => 'Most Rated',
                'featured' => 'Only Featured',
                'sponsored' => 'Only Sponsored',
                'verified' => 'Only Verified',
            ),
        ),
    );
}

$member_levels = array();
$public_level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel();
foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $row) {
  if ($public_level->level_id != $row->level_id) {
    $member_count = $row->getMembershipCount();
    $title = $row->title;
    $member_levels[$row->level_id] = $title;
  }
}

//Profile Type
$profileTypes = array();
$profileType = array('' => '');
$profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias('user', 'profile_type');
if (engine_count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']))
  return;
$profileTypeField = $profileTypeFields['profile_type'];
$options = $profileTypeField->getOptions();
foreach ($options as $option) {
  $profileTypes[$option->option_id] = $option->label;
  $profileType[$option->option_id] = $option->label;
}



$sidebarwidgetsViewType = array(
    'Select',
    'viewType',
    array(
        'label' => "View Type",
        'multiOptions' => array(
            'list' => 'List View',
            'grid' => 'Grid View',
            'thumbView' => 'Thumb View',
        ),
    )
);

$pagging = array(
    'Radio',
    'pagging',
    array(
        'label' => "Do you want the members to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'button' => 'View more',
            'auto_load' => 'Auto Load',
            'pagging' => 'Pagination'
        ),
        'value' => 'auto_load',
    )
);
$titleTruncationList = array(
    'Text',
    'list_title_truncation',
    array(
        'label' => 'Title truncation limit for List View.',
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
$commonCriteria = array(
    'featuredLabel' => 'Featured Label',
    'sponsoredLabel' => 'Sponsored Label',
    'verifiedLabel' => 'Verified Label',
    'vipLabel' => 'Vip Label',
    'friendButton' => 'Friend Button',
    'followButton' => 'Follow Button',
    'message' => 'Message Button',
    'likemainButton' => 'Like Button',
    'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
    'title' => 'Display Name',
    'location' => 'Location',
    'like' => 'Likes Count',
    'follow' => 'Followers Count',
    'rating' => 'Ratings Count',
    'view' => 'Views Count',
    'friendCount' => 'Friends Count',
    'mutualFriendCount' => 'Mutual Friends Count',
    'profileType' => 'Profile Type',
    'age' => 'Show Member’s Age [Age will show even if any member has hide their "Birth Date"].',
);

$showCustomData = array(
    'MultiCheckbox',
    'show_criteria',
    array(
        'label' => "Data show in widget ?",
        'multiOptions' => array(
            'featuredLabel' => 'Featured Label',
            'sponsoredLabel' => 'Sponsored Label',
            'verifiedLabel' => 'Verified Label',
            'vipLabel' => 'Vip Label',
            'message' => 'Message Button',
            'friendButton' => 'Friend Button',
            'followButton' => 'Follow Button',
            'likeButton' => 'Like Button',
            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
            'like' => 'Likes Count (Not Supported in Grid View)',
            'follow' => 'Followers Count (Not Supported in Grid View)',
            'location' => 'Location',
            'rating' => 'Ratings Count (Not Supported in Grid View)',
            'view' => 'Views Count (Not Supported in Grid View)',
            'title' => 'Display Name',
            'friendCount' => 'Friends Count (Not Supported in Grid View)',
            'mutualFriendCount' => 'Mutual Friends Count (Not Supported in Grid View)',
            'profileType' => 'Profile Type (Not Supported in Grid View)',
            'age' => 'Show Members Age [Age will show even if any member has hide their "Birth Date"].',
            //'pinboardSlideshow' => 'Show Slideshow of Featured Photos of member in Pinboard View only',
        ),
        'escape' => false,
    )
);
$photoHeight = array(
    'Text',
    'photo_height',
    array(
        'label' => 'Enter the height of grid photo block (in pixels).',
        'value' => '160',
    )
);
$photowidth = array(
    'Text',
    'photo_width',
    array(
        'label' => 'Enter the width of grid photo block (in pixels).',
        'value' => '250',
    )
);
$criteria = array(
    'Select',
    'criteria',
    array(
        'label' => "Display Content",
        'multiOptions' => array(
            '5' => 'All including Featured and Sponsored',
            '1' => 'Only Featured',
            '2' => 'Only Sponsored',
            '6' => 'Only Verified',
            '3' => 'Both Featured and Sponsored',
            '4' => 'All except Featured and Sponsored',
        ),
        'value' => 5,
    )
);

$mageType = array(
    'Select',
    'imageType',
    array(
        'label' => "Image Type",
        'multiOptions' => array(
            'rounded' => 'Rounded View',
            'square' => 'Square View',
        ),
        'value' => 'square',
    )
);

return array(
    array(
        'title' => 'SNS - Ultimate Members - Member Profile Map',
        'description' => 'Displays a member\'s location on map on it\'s profile.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.user-map',
        'defaultParams' => array(
            'title' => 'Map',
            'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'text',
                    'height',
                    array(
                        'label' => 'Enter the height of map (In Pixels).',
                        'value' => 400,
                    )
                ),
            ),
        ),
        'requirements' => array(
            'subject' => 'user',
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Profile User\'s Follow Button',
        'description' => 'Displays follow button for member. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.follow-button',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Profile User\'s Rating Review Votes',
        'description' => 'Displays Profile User\'s rating review votes. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => false,
        'name' => 'sesmember.profile-user-review-votes',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Profile User\'s Ratings',
        'description' => 'Displays Profile User\'s Ratings. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => false,
        'name' => 'sesmember.profile-user-ratings',
        'defaultParams' => array(
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Profile User\'s Like Button',
        'description' => 'Displays like button for member. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.like-button',
        'defaultParams' => array(
            'title' => '',
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Member Browse Menu',
        'description' => 'Displays a menu in the member browse page.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.browse-menu',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'sesmember_taboptions',
                    array(
                        'label' => 'Enter the number of count of tab  want to show in member navigaton menu.',
                        'value' => 6,
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
        'title' => 'SNS - Ultimate Members - Browse Members',
        'description' => 'Displays all members of your site based on criteria. This widgets is placed on "SNS - Ultimate Members - Browse Member Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.browse-members',
        'adminForm' => array(
            'elements' => array(
                //member level exclude
                array(
                  'MultiCheckbox',
                  'memberlevels',
                  array(
                    'label' => "Choose the member levels which you do not want to be searched in this widget?",
                    'multiOptions' => $member_levels,
                  )
                ),
                //member level exclude
                array(
                    'MultiCheckbox',
                    'enableTabs',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'advlist' => 'Advanced List View',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                            'map' => 'Map View',
                        ),
                    )
                ),
                array(
                    'Select',
                    'openViewType',
                    array(
                        'label' => "Default open View Type (apply if select Both View option in above tab)?",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'advlist' => 'Advanced List View',
                            'grid' => 'Grid View',
                            'advgrid' => 'Advanced Grid View',
                            'pinboard' => 'Pinboard View',
                            'map' => 'Map View',
                        ),
                        'value' => 'list',
                    )
                ),
                $showCustomData,
                array(
                    'Select',
                    'Overlay_color_grid',
                    array(
                        'label' => "Overlay Color for Grid Info Block",
                        'multiOptions' => array(
                          'white' => 'White',
                          'black' => 'Black',
                        ),
                    )
                ),
                array(
                    'Select',
                    'enable_cover_photo_adv_grid',
                    array(
                        'label' => "Enable Cover Photo for Adv. Grid",
                        'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No',
                        ),
                    )
                ),
                array(
                    'Select',
                    'socialshare_enable_plusiconlistview',
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
                  'socialshare_icon_limitlistview',
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
                    'socialshare_enable_plusiconadvlistview',
                    array(
                        'label' => "Enable plus (+) icon for social share buttons in Advanced List View?",
                        'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No',
                        ),
                    )
                ),
                array(
                  'Text',
                  'socialshare_icon_limitadvlistview',
                  array(
                    'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in Advanced List View. Other social sharing icons will display on clicking this plus icon.',
                    'value' => 2,
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                  ),
                ),
                array(
                    'Select',
                    'socialshare_enable_plusicongridview',
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
                  'socialshare_icon_limitgridview',
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
                    'socialshare_enable_plusiconadvgridview',
                    array(
                        'label' => "Enable plus (+) icon for social share buttons in Advanced Grid View?",
                        'multiOptions' => array(
                          '1' => 'Yes',
                          '0' => 'No',
                        ),
                    )
                ),
                array(
                  'Text',
                  'socialshare_icon_limitadvgridview',
                  array(
                    'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in Advanced Grid View. Other social sharing icons will display on clicking this plus icon.',
                    'value' => 2,
                    'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                    ),
                  ),
                ),
                array(
                    'Select',
                    'socialshare_enable_plusiconpinview',
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
                  'socialshare_icon_limitpinview',
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
                    'socialshare_enable_plusiconmapview',
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
                  'socialshare_icon_limitmapview',
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
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'count (number of members to show).',
                        'value' => 20,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $pagging,
                array(
                    'Radio',
                    'order',
                    array(
                        'label' => 'Choose Member Display Criteria.',
                        'multiOptions' => array(
                            "recentlySPcreated" => "Recently Created",
                            "mostSPviewed" => "Most Viewed",
                            "mostSPliked" => "Most Liked",
                            "mostSPrated" => "Most Rated",
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified',
                        ),
                        'value' => 'most_liked',
                    )
                ),
                array(
                    'Select',
                    'show_item_count',
                    array(
                        'label' => 'Show Members count in this widget',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '0',
                    ),
                ),
//                 $titleTruncationList,
//                 $titleTruncationGrid,
//                 array(
//                     'Text',
//                     'advgrid_title_truncation',
//                     array(
//                         'label' => 'Title truncation limit for Advanced Grid View.',
//                         'value' => 45,
//                         'validators' => array(
//                             array('Int', true),
//                             array('GreaterThan', true, array(0)),
//                         )
//                     )
//                 ),
//                 array(
//                     'Text',
//                     'pinboard_title_truncation',
//                     array(
//                         'label' => 'Title truncation limit for Pinboard View.',
//                         'value' => 45,
//                         'validators' => array(
//                             array('Int', true),
//                             array('GreaterThan', true, array(0)),
//                         )
//                     )
//                 ),
                array(
                    'Text',
                    'main_width',
                    array(
                        'label' => 'Enter the width of List Photo Block (in pixels).',
                        'value' => '180',
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of Advanced List Photo block (in pixels).',
                        'value' => '220',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of Advanced List Photo block (in pixels).',
                        'value' => '300',
                    )
                ),
                $photoHeight,
                array(
                    'Text',
                    'advgrid_height',
                    array(
                        'label' => 'Enter the height of Advanced Grid Photo Block/Advanced Grid Cover Photo Block (in pixels).',
                        'value' => '180',
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
                    'pinboard_width',
                    array(
                        'label' => 'Enter the width of pinboard block (in pixels).',
                        'value' => '250',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Review Browse Search',
        'description' => 'Displays a search form in the review browse page. This widgets is placed on "SNS - Ultimate Members - Browse Member Reviews Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.browse-review-search',
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
                            'vertical' => 'Vertical [Please choose vertical option when place this widget as a side widget.]'
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
                    'view_search',
                    array(
                        'label' => "Show \'View\' search field?",
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
                        'label' => "Choose options to be shown in \'View\' search fields.",
                        'multiOptions' => array(
                            'likeSPcount' => 'Most Liked',
                            'viewSPcount' => 'Most Viewed',
                            'commentSPcount' => 'Most Commented',
                            'mostSPrated' => 'Most Rated',
                            Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first111', 'useful') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first', 'Useful'),
                            Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second111', 'funny') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second', 'Funny'),
                            Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third111', 'cool') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third', 'Cool'),
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
                    'network',
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
        'title' => 'SNS - Ultimate Members - Member\'s Browse Search',
        'description' => 'Displays a search form in the member browse page. This widgets is placed on "SNS - Ultimate Members - Browse Member Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.browse-search',
        'requirements' => array(
            'no-subject',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                //profile type exclude
                array(
                  'MultiCheckbox',
                  'profiletypes',
                  array(
                    'label' => "Select the Profile Types which you do not want to be searched in this widget.",
                    'multiOptions' => $profileTypes,
                  )
                ),
                array(
                  'Select',
                  'defaultprofiletypes',
                  array(
                    'label' => "Choose the default Profile Type which will be shown when the search page is viewed. Do not choose the profile type which is selected in above setting.",
                    'multiOptions' => $profileType,
                  )
                ),
                //profile type exclude
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
                $browseMemberSearch,
                array(
                    'Select',
                    'default_search_type',
                    array(
                        'label' => "Default 'Browse By' search fields.",
                        'multiOptions' => array(
                            'creation_date DESC' => 'Recently Created',
                            'view_count DESC' => 'Most Viewed',
                            'like_count DESC' => 'Most Liked',
                            'rate_count DESC' => 'Most Rated',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified'
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'view',
                    array(
                        'label' => "Choose options to be shown in 'View' search fields.",
                        'multiOptions' => array(
                            '0' => 'All\'s Users',
                            '1' => 'Only My Friend\'s',
                            '3' => 'Only My Network\'s',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                    )
                ),
                array(
                    'Radio',
                    'show_advanced_search',
                    array(
                        'label' => "Show 'Advanced Settings Button' search field?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => '1',
                    )
                ),
                array(
                    'Radio',
                    'network',
                    array(
                        'label' => "Show 'Network' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'alphabet',
                    array(
                        'label' => "Show 'Alphabet' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
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
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'search_title',
                    array(
                        'label' => "Show 'Search Members /Keyword' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'browse_by',
                    array(
                        'label' => "Show 'Browse By' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'location',
                    array(
                        'label' => "Show 'Location' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'kilometer_miles',
                    array(
                        'label' => "Show 'Kilometer or Miles' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'country',
                    array(
                        'label' => "Show 'Country' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'state',
                    array(
                        'label' => "Show 'State' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'city',
                    array(
                        'label' => "Show 'City' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'zip',
                    array(
                        'label' => "Show 'Zip' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'member_type',
                    array(
                        'label' => "Show 'Member Type' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'has_photo',
                    array(
                        'label' => "Show 'Member With Photos' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'is_online',
                    array(
                        'label' => "Show 'Member Online' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'is_vip',
                    array(
                        'label' => "Show 'Vip Members' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                $interest,
            ),
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Member\'s Top Rated Search',
        'description' => 'Displays a search form in the member top rated page. This widgets is placed on "SNS - Ultimate Members - Top Member Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.browse-search-toprated',
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
                            'vertical' => 'Vertical [Please choose vertical option when place this widget as a side widget.]'
                        ),
                        'value' => 'vertical',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'view',
                    array(
                        'label' => "Choose options to be shown in \'View\' search fields.",
                        'multiOptions' => array(
                            '0' => 'All\'s Users',
                            '1' => 'Only My Friend\'s',
                            '3' => 'Only My Network\'s',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                    )
                ),
                array(
                    'Radio',
                    'show_advanced_search',
                    array(
                        'label' => "Show \'Advanced Settings Button\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No'
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'network',
                    array(
                        'label' => "Show \'Network\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'alphabet',
                    array(
                        'label' => "Show \'Alphabet\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'friend_show',
                    array(
                        'label' => "Show \'View\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'search_title',
                    array(
                        'label' => "Show \'Search Member /Keyword\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
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
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
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
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'country',
                    array(
                        'label' => "Show \'Country\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'state',
                    array(
                        'label' => "Show \'State\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'city',
                    array(
                        'label' => "Show \'City\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'zip',
                    array(
                        'label' => "Show \'Zip\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'member_type',
                    array(
                        'label' => "Show \'Member Type\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'has_photo',
                    array(
                        'label' => "Show \'Member With Photos\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'is_online',
                    array(
                        'label' => "Show \'Member Online\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
                array(
                    'Radio',
                    'is_vip',
                    array(
                        'label' => "Show \'Vip Members\' search field?",
                        'multiOptions' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                            'hide' => 'Show this option when click on "Advanced Settings  Button"',
                        ),
                        'value' => 'yes',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Tabbed Widget',
        'description' => 'Displays a tabbed widget for members. You can place this widget anywhere on your site.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.tabbed-members',
        'requirements' => array(
            'no-subject',
        ),
        'adminForm' => 'Sesmember_Form_Admin_Tabbed',
    ),
    array(
        'title' => 'SNS - Ultimate Members - Member Location Page',
        'description' => 'This widget displays members location. This widgets is placed on "SNS - Ultimate Members -  Members Location Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.member-location',
        'autoEdit' => true,
        'adminForm' => 'Sesmember_Form_Admin_Location',
    ),
    array(
        'title' => 'SNS - Ultimate Members - Members of the Day',
        'description' => "This widget displays members of the day as chosen by you from the Edit Settings of this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.of-the-day',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "View Type",
                        'multiOptions' => array(
                            'gridInside' => 'Grid View',
                            'gridOutside' => 'Advanced Grid View',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
            )
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Member Review of the Day',
        'description' => "This widget displays review of the day as chosen by you from the Edit Settings of this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.review-of-the-day',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "View Type",
                        'multiOptions' => array(
                            'list' => 'List View',
                            'gridInside' => 'Grid View',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'title' => 'Display Name',
                            'like' => 'Likes Count',
                            'view' => 'Views Count',
                            'rating' => 'Ratings',
                            'featuredLabel' => 'Featured Label',
                            'verifiedLabel' => 'Verified Label',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'likeButton' => 'Like Button',
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $titleTruncationGrid,
                array(
                        'Text',
                        'list_title_truncation',
                        array(
                            'label' => 'Title truncation limit for advanced grid View.',
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
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
            )
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Popular / Featured / Sponsored / Verified Members',
        'description' => "Displays members as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.featured-sponsored',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'Radio',
                    'show_star',
                    array(
                        'label' => "Do you want to show rating star in widget ? (Note: Please choose star setting yes, when you are selction \"Most Rated\" from above setting.)",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Popular / Featured / Sponsored / Verified Members Carousel',
        'description' => "Displays members as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.featured-sponsored-carousel',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                //$titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
                        'value' => '440',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Online Users',
        'description' => "Displays a list of online members. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.list-online',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Featured Photos',
        'description' => 'Displays a list of featured photos of member. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.member-featured-photos',
        'defaultParams' => array(
            'title' => '',
        ),
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Following',
        'description' => 'Displays a list of following me members. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.following',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Followers',
        'description' => 'Displays a list of followers me members. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.followers',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Member Liked',
        'description' => 'Displays a list of member liked by me. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.member-liked',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(

                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Recently Viewed Me',
        'description' => 'Displays a list of recently viewed me members. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.recently-viewed-me',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Recently Viewed By Me',
        'description' => 'Displays a list of recently viewed by me members. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.recently-viewed-by-me',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Popular / Featured / Sponsored / Verified Members Slideshow',
        'description' => "Displays members as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.slideshows',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'likeButton' => 'Like Button',
                            'friendButton' => 'Friend Button',
                            'followButton' => 'Follow Button',
                            'message' => 'Message Button',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'like' => 'Likes Count',
                            'location' => 'Location',
                            'rating' => 'Ratings Count',
                            'view' => 'Views Count',
                            'title' => 'Display Name',
                            'friendCount' => 'Friends Count',
                            'mutualFriendCount' => 'Mutual Friends Count',
                            'profileType' => 'Profile Type',
                            'age' => 'Show Member’s Age [Age will show even if any member has hide their "Birth Date"].',
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                //$titleTruncationList,
                array(
                    'Text',
                    'slideheight',
                    array(
                        'label' => 'Enter the height of slideshow.',
                        'value' => '270',
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
                        'label' => 'Enter the height of image (in pixels).',
                        'value' => '220',
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
                        'label' => 'Enter the width of image (in pixels).',
                        'value' => '220',
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
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - People You May Know',
        'description' => 'Displays a list of users which are not friend of this member.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.people-you-may-know',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'Select',
                    'order',
                    array(
                        'label' => 'Members Criteria to show in this widget',
                        'multiOptions' => array(
                            '' => 'All Members',
                            'week' => 'This Week',
                            'month' => 'This Month',
                        ),
                        'value' => '',
                    )
                ),
                $criteria,
                array(
                    'Select',
                    'info',
                    array(
                        'label' => 'Choose Popularity Criteria.',
                        'multiOptions' => array(
                            "creation_date" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                        )
                    ),
                    'value' => 'recently_updated',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Profile User Review',
        'description' => 'This widget display review of user. This widget placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.member-reviews',
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
                        )
                    ),
                )
            ),
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - Review Breadcrumb for View Page',
        'description' => 'Displays breadcrumb for Review. This widget should be placed on the SNS - Advanced Member - Review View page.',
        'category' => 'SNS - Ultimate Members',
        'autoEdit' => false,
        'type' => 'widget',
        'name' => 'sesmember.breadcrumb',
    ),
    array(
        'title' => 'SNS - Ultimate Members - Review Add Button',
        'description' => 'Displays review add button for User profile page. This widget should be placed on the Member Profile Page only and work if you placed  "SNS - Advanced Member - Profile User Review" widget.',
        'category' => 'SNS - Ultimate Members',
        'autoEdit' => false,
        'type' => 'widget',
        'name' => 'sesmember.review-add',
    ),
    array(
        'title' => 'SNS - Ultimate Members - Review Profile Options',
        'description' => 'Displays a menu of actions (edit, report, share, etc) that can be performed on a content on its profile. The recommended page for this widget is "SNS - Advanced Member - Review View Page".',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.review-profile-options',
        'autoEdit' => false,
    ),
    array(
        'title' => 'SNS - Ultimate Members - Profile Review',
        'description' => 'Displays a member\'s review entries on their profile. This widge is placed on "SNS - Advanced Member - Review View Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.profile-review',
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
                            'parameter' => 'Review Parameters',
                            'rating' => 'Rating Stars',
                        )
                    ),
                )
            ),
        ),
    ),
    array(
        'title' => "SNS - Ultimate Members - Review Owner's Photo",
        'description' => 'This widget display review owner photo. This widge is placed on "SNS - Advanced Member - Review View Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.review-owner-photo',
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
        'title' => 'SNS - Ultimate Members - Browse Reviews',
        'description' => 'Displays reviews for member. This widget is only placed on "SNS - Ultimate Members - Browse Reviews Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.browse-reviews',
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
                            'rating' => 'Rating Stars'
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'likemainButton' => 'Like Button',
                            'friendButton' => 'Friend Button',
                            'followButton' => 'Follow Button',
                            'socialSharing' => 'Social Share Button',
                            'message' => 'Message Button',
                            'featuredLabel' => 'Featured Label',
                            'verifiedLabel' => 'Verified Label',
                        ),
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
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
        'title' => 'SNS - Ultimate Members - Popular / Featured / Verified Member Reviews',
        'description' => "Displays members reviews as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.popular-featured-verified-reviews',
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
                $mageType,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
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
                $titleTruncationList,
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
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Profile Friends',
        'description' => 'Displays a member\'s friends on their profile. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.profile-friends',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $pagging,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of firends to show).',
                        'value' => 10,
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
        'title' => 'SNS - Ultimate Members - Top Reviewers',
        'description' => 'Displays a list of users which have given maximum number of reviews to site member.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.top-reviewers',
        'adminForm' => array(
            'elements' => array(
                $sidebarwidgetsViewType,
                $mageType,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => $commonCriteria,
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
//                 $titleTruncationGrid,
//                 $titleTruncationList,
                array(
                    'Select',
                    'showLimitData',
                    array(
                        'label' => 'Do you want to show next/previous buttons in this widget.',
                        'multiOptions' => array(
                            "1" => "Yes, want to show next/previous buttons",
                            "0" => "No, don't want to show next/previous buttons",
                        )
                    ),
                    'value' => '1',
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $photoHeight,
                $photowidth,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Top Rated Members',
        'description' => 'Displays top rated member\'s.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmember.top-rated-members',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'vipLabel' => 'Vip Label',
                            'likeButton' => 'Like Button',
                            'friendButton' => 'Friend Button',
                            'followButton' => 'Follow Button',
                            'message' => 'Message Button',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'title' => 'Display Name',
                            'location' => 'Location',
                            'like' => 'Likes Count',
                            'follow' => 'Followers Count',
                            'rating' => 'Ratings Count',
                            'view' => 'Views Count',
                            'friendCount' => 'Friends Count',
                            'mutualFriendCount' => 'Mutual Friends Count',
                            'profileType' => 'Profile Type',
                            'age' => 'Show Member’s Age [Age will show even if any member has hide their "Birth Date"].',
                            'recommendation' => 'Recommendation',
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Radio',
                    'rating_graph',
                    array(
                        'label' => "Do you want the members to show rating graph on the page?",
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No',
                        ),
                        'value' => '1',
                    )
                ),
                $titleTruncationList,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one member block (in pixels).',
                        'value' => '220',
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
                        'label' => 'Enter the width of one member block (in pixels).',
                        'value' => '220',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                $pagging,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count (number of member to show).',
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
        'title' => 'SNS - Ultimate Members - Profile Info',
        'description' => 'Displays a member\'s info (signup date, friend count, etc) on their profile. This widget is only placed on "Member Profile Page" only.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.profile-info',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'location' => 'Location',
                            'like' => 'Likes Count',
                            'rating' => 'Ratings Count',
                            'view' => 'Views Count',
                            'friendCount' => 'Friends Count',
                            'mutualFriendCount' => 'Mutual Friends Count',
                            'profileType' => 'Profile Type',
                            'joinInfo' => 'Member Joining Information',
                            'updateInfo' => 'Member Last Update Information',
                            'network' => 'Show Member Network',
                        ),
                    ),
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Ultimate Members - User Photo',
        'description' => 'Displays the logged-in member\'s photo.',
        'category' => 'SNS - Ultimate Members',
        'type' => 'widget',
        'name' => 'sesmember.home-photo',
        'requirements' => array(
            'viewer',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for member in this widget.",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'verifiedLabel' => 'Verified Label',
                            'vipLabel' => 'Vip Label',
                            'title' => 'Display Name',
                        ),
                    ),
                ),
            )
        ),
    ),
	  array(
    'title' => 'Members Listing',
    'description' => '',
    'category' => 'Core',
    'type' => 'widget',
    'name' => 'sesmember.members-listing',
    'autoEdit' => false,
  ),
);
