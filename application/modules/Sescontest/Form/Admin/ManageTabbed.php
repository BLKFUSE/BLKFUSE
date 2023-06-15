<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Tabbed.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_ManageTabbed extends Engine_Form {

  public function init() {

//     $this->addElement('MultiCheckbox', "enableTabs", array(
//         'label' => "View Type.",
//         'multiOptions' => array(
//             'list' => 'List View',
//             'grid' => 'Grid View',
//             'advgrid' => 'Advanced Grid View',
//             'pinboard' => 'Pinboard View',
//         ),
//     ));
// 
//     $this->addElement('Select', "openViewType", array(
//         'label' => "Choose Default View Type (apply if more than 1 view type is selected.) (Note: In \"Tabbed widget for Manage Contests\" only List View will work.)",
//         'multiOptions' => array(
//             'list' => 'List View',
//             'grid' => 'Grid View',
//             'advgrid' => 'Advanced Grid View',
//             'pinboard' => 'Pinboard View',
//         ),
//     ));

    $this->addElement('Select', "tabOption", array(
        'label' => 'Choose Tab Type.',
        'multiOptions' => array(
            'default' => 'Default',
            'advance' => 'Advanced',
            'filter' => 'Filter',
            'vertical' => 'Vertical',
            'select' => 'Dropdown',
        ),
        'value' => 'advance',
    ));

    $this->addElement('Select', "media", array(
        'label' => 'Choose Media Type.',
        'multiOptions' => array(
            '' => 'All Media Type',
            '1' => 'Text',
            '2' => 'Photo',
            '3' => 'Video',
            '4' => 'Audio',
        ),
        'value' => 'photo',
    ));

    $this->addElement('MultiCheckbox', "show_criteria", array(
        'label' => "Choose from below the details that you want to show in this widget.",
        'multiOptions' => array(
            'title' => 'Title',
            "startenddate" => "Start & End Date of Contest (Not supported with Advanced Grid View)",
            'by' => 'Created By',
            'mediaType' => 'Media Type',
            'category' => 'Category',
            'listdescription' => 'Description (List View)',
            'griddescription' => 'Description (Grid View)',
            'pinboarddescription' => 'Description (Pinboard View)',
            'socialSharing' => 'Social Share Buttons <a href="">[FAQ]</a>',
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
            'status' => 'Contest Status ( Ended or Days Left)',
            'voteCount' => 'Votes Count (With Ended Contests Only)',
        ),
        'escape' => false,
    ));

    $this->addElement('Radio', "pagging", array(
        'label' => "Do you want the contests to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'auto_load' => 'Yes, Auto Load',
            'button' => 'No, show \'View more\' link.',
            'pagging' => 'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    ));

    $this->addElement('Select', "show_limited_data", array(
        'label' => 'Show only the number of contests entered in above setting. [If you choose No, then you can choose how do you want to show more contests in this widget.]',
        'multiOptions' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'value' => 'no',
    ));

    $this->addElement('Select', 'socialshare_enable_plusicon', array(
        'label' => "Enable More Icon for social share buttons in List View?",
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
    ));
    $this->addElement('Text', "socialshare_icon_limit", array(
        'label' => 'Count (number of social sites to show in list view). If you enable More Icon, then other social sharing icons will display on clicking this plus icon.',
        'value' => 2,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Select', "htmlTitle", array(
        'label' => 'Do you want to show HTML title on view type?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => 'advance',
    ));

    $this->addElement('Dummy', "dummy15", array(
        'label' => "<span style='font-weight:bold;'>List View</span>",
    ));
    $this->getElement('dummy15')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "limit_data_list", array(
        'label' => 'Count (number of contests to show).',
        'value' => 10,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "list_title_truncation", array(
        'label' => 'Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "list_description_truncation", array(
        'label' => 'Description truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "height", array(
        'label' => 'Enter the height of main photo block (in pixels).',
        'value' => '230',
    ));
    $this->addElement('Text', "width", array(
        'label' => 'Enter the width of main photo block (in pixels).',
        'value' => '260',
    ));

    $this->addElement('Dummy', "dummy16", array(
        'label' => "<span style='font-weight:bold;'>Grid View</span>",
    ));
    $this->getElement('dummy16')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "limit_data_grid", array(
        'label' => 'Count (number of contests to show).',
        'value' => 10,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "grid_title_truncation", array(
        'label' => 'Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "grid_description_truncation", array(
        'label' => 'Description truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "height_grid", array(
        'label' => 'Enter the height of one block (in pixels).',
        'value' => '270',
    ));

    $this->addElement('Dummy', "dummy17", array(
        'label' => "<span style='font-weight:bold;'>Advanced Grid View</span>",
    ));
    $this->getElement('dummy17')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "limit_data_advgrid", array(
        'label' => 'Count (number of contests to show).',
        'value' => 10,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "advgrid_title_truncation", array(
        'label' => 'Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "height_advgrid", array(
        'label' => 'Enter the height of main photo block (in pixels).',
        'value' => '230',
    ));
    $this->addElement('Select', "gridblock",array(
				'label' => "How many grid box you want to show in one row in Grid/ Advanced Grid View",
				'multiOptions' => array(
				  '12' => 'One',
					'6' =>  'Two',
					'4' =>  'Three',
					'3' =>  'Four',
				),
		));

    $this->addElement('Dummy', "dummy18", array(
        'label' => "<span style='font-weight:bold;'>Pinboard View</span>",
    ));
    $this->getElement('dummy18')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "limit_data_pinboard", array(
        'label' => 'Count (number of contests to show).',
        'value' => 10,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "pinboard_title_truncation", array(
        'label' => 'Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "pinboard_description_truncation", array(
        'label' => 'Description truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', "width_pinboard", array(
        'label' => 'Enter the width of one block (in pixels).',
        'value' => '300',
    ));

    $this->addElement('MultiCheckbox', "search_type", array(
        'label' => "Choose from below tabs that you want to show in this widget.",
        'multiOptions' => array(
            'ended' => 'Ended',
            'active' => 'Active',
            'upcoming' => 'Coming Soon',
            'recentlySPcreated' => 'Recently Created',
            'mostSPliked' => 'Most Liked',
            'mostSPcommented' => 'Most Commented',
            'mostSPviewed' => 'Most Viewed',
            'mostSPfavourite' => 'Most Favorited',
            'mostSPfollowed' => 'Most Followed',
            'mostSPjoined' => 'Most Joined',
            'featured' => 'Featured',
            'sponsored' => 'Sponsored',
            'verified' => 'Verified',
            'hot' => 'Hot',
        ),
    ));

    // setting for Recently Created
    $this->addElement('Dummy', "dummy1", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Ended' Tab</span>",
    ));
    $this->getElement('dummy1')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "ended_order", array(
        'label' => "Order of this Tab.",
        'value' => '1',
    ));
    $this->addElement('Text', "ended_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Ended',
    ));

    // setting for Recently Created
    $this->addElement('Dummy', "dummy2", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Active' Tab</span>",
    ));
    $this->getElement('dummy2')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "active_order", array(
        'label' => "Order of this Tab.",
        'value' => '2',
    ));
    $this->addElement('Text', "active_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Active',
    ));

    // setting for Recently Created
    $this->addElement('Dummy', "dummy3", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Coming Soon' Tab</span>",
    ));
    $this->getElement('dummy3')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "upcoming_order", array(
        'label' => "Order of this Tab.",
        'value' => '3',
    ));
    $this->addElement('Text', "upcoming_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Coming Soon',
    ));

    // setting for Recently Created
    $this->addElement('Dummy', "dummy4", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Recently Created' Tab</span>",
    ));
    $this->getElement('dummy4')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "recentlySPcreated_order", array(
        'label' => "Order of this Tab.",
        'value' => '4',
    ));
    $this->addElement('Text', "recentlySPcreated_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Recently Created',
    ));

    // setting for Most Liked
    $this->addElement('Dummy', "dummy5", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Liked' Tab</span>",
    ));
    $this->getElement('dummy5')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPliked_order", array(
        'label' => "Order of this Tab.",
        'value' => '5',
    ));
    $this->addElement('Text', "mostSPliked_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Liked',
    ));

    // setting for Most Commented
    $this->addElement('Dummy', "dummy6", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Commented' Tab</span>",
    ));
    $this->getElement('dummy6')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPcommented_order", array(
        'label' => "Order of this Tab.",
        'value' => '6',
    ));
    $this->addElement('Text', "mostSPcommented_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Commented',
    ));


    // setting for Most Viewed
    $this->addElement('Dummy', "dummy7", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Viewed' Tab</span>",
    ));
    $this->getElement('dummy7')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPviewed_order", array(
        'label' => "Order of this Tab.",
        'value' => '7',
    ));
    $this->addElement('Text', "mostSPviewed_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Viewed',
    ));

    // setting for Most Favourite
    $this->addElement('Dummy', "dummy8", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Favourited' Tab</span>",
    ));
    $this->getElement('dummy8')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPfavourite_order", array(
        'label' => "Order of this Tab.",
        'value' => '8',
    ));
    $this->addElement('Text', "mostSPfavourite_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Favourited',
    ));


    $this->addElement('Dummy', "dummy9", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Followed' Tab</span>",
    ));
    $this->getElement('dummy9')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPfollowed_order", array(
        'label' => "Order of this Tab.",
        'value' => '9',
    ));
    $this->addElement('Text', "mostSPfollowed_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Followed',
    ));

    $this->addElement('Dummy', "dummy10", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Joined' Tab</span>",
    ));
    $this->getElement('dummy10')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "mostSPjoined_order", array(
        'label' => "Order of this Tab.",
        'value' => '10',
    ));
    $this->addElement('Text', "mostSPjoined_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Most Joined',
    ));

    // setting for Featured
    $this->addElement('Dummy', "dummy11", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Featured' Tab</span>",
    ));
    $this->getElement('dummy11')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "featured_order", array(
        'label' => "Order of this Tab.",
        'value' => '11',
    ));
    $this->addElement('Text', "featured_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Featured',
    ));

    // setting for Sponsored
    $this->addElement('Dummy', "dummy12", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Sponsored' Tab</span>",
    ));
    $this->getElement('dummy12')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "sponsored_order", array(
        'label' => "Order of this Tab.",
        'value' => '12',
    ));
    $this->addElement('Text', "sponsored_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Sponsored',
    ));

    // setting for This Week
    $this->addElement('Dummy', "dummy13", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Verified' Tab</span>",
    ));
    $this->getElement('dummy13')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "verified_order", array(
        'label' => "Order of this Tab.",
        'value' => '13',
    ));
    $this->addElement('Text', "verified_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Verified',
    ));

    // setting for This Month
    $this->addElement('Dummy', "dummy14", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Hot' Tab</span>",
    ));
    $this->getElement('dummy14')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "hot_order", array(
        'label' => 'Order of this Tab.',
        'value' => '14',
    ));
    $this->addElement('Text', "hot_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Hot',
    ));
  }

}
