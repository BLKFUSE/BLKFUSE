<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Tabbed.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Tabbed extends Engine_Form {

  public function init() {

    $this->addElement('MultiCheckbox', "enableTabs", array(
        'label' => "Choose the View Type.",
        'multiOptions' => array(
            'list' => 'List View',
            'advlist' => 'Advanced List View',
            'grid' => 'Grid View',
            'advgrid' => 'Advanced Grid View',
            'pinboard' => 'Pinboard View',
            'map' => 'Map View',
        ),
        'value' => '',
    ));
    $this->addElement('Select', "openViewType", array(
        'label' => " Default open View Type (apply if select more than one option in above tab)?",
        'multiOptions' => array(
            'list' => 'List View',
            'advlist' => 'Advanced List View',
            'grid' => 'Grid View',
            'advgrid' => 'Advanced Grid View',
            'pinboard' => 'Pinboard View',
            'map' => 'Map View',
        ),
        'value' => '',
    ));
    $this->addElement('Radio', "tabOption", array(
        'label' => 'Show Tab Type?',
        'multiOptions' => array(
            'default' => 'Default',
            'advance' => 'Advanced',
            'filter' => 'Filter',
            'vertical' => 'Vertical',
        ),
        'value' => 'advance',
    ));
    $this->addElement('Select', "show_item_count", array(
        'label' => 'Show Members count in this widget',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => '0',
    ));
    $this->addElement('MultiCheckbox', "show_criteria", array(
        'label' => "Choose from below the details that you want to show in this widget.",
        'multiOptions' => array(
            'featuredLabel' => 'Featured Label',
            'sponsoredLabel' => 'Sponsored Label',
            'verifiedLabel' => 'Verified Label',
            'vipLabel' => 'Vip Label',
            'likeButton' => 'Like Button',
            'likemainButton' => 'Main Like Button',
            'friendButton' => 'Friend Button',
            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
            'like' => 'Likes Count (Not Supported in Grid View)',
            'location' => 'Location',
            'rating' => 'Ratings star',
            'ratingCount' => 'Ratings Count (Not Supported in Grid View)',
            'view' => 'Views Count (Not Supported in Grid View)',
            'title' => 'Titles',
            'friendCount' => 'Show Total Number of Friends (Not Supported in Grid View)',
            'mutualFriendCount' => 'Show Mutual Friends (Not Supported in Grid View)',
            'profileType' => 'Profile Type (Not Supported in Grid View)',
            'age' => 'Show Member’s Age [Age will show even if any member has hide their "Birth Date"].',
            'message' => 'Message',
            //'pinboardSlideshow' => 'Show Slideshow of Featured Photos of member in Pinboard view only',
        ),
        'escape' => false,
    ));
    
    //Social Share Plugin work
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')) {
      
      $this->addElement('Select', "socialshare_enable_plusicon", array(
        'label' => "Enable More Icon for social share buttons?",
          'multiOptions' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
        'value' => 1,
      ));
      
      $this->addElement('Text', "socialshare_icon_limit", array(
          'label' => 'Count (number of social sites to show). If you enable More Icon, then other social sharing icons will display on clicking this plus icon.',
          'value' => 2,
          'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
      ));
    }
    //Social Share Plugin work
    
    $this->addElement('Text', "limit_data", array(
        'label' => 'count (number of members to show).',
        'value' => 20,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Select', "show_limited_data", array(
        'label' => 'Show only the number of members entered in above setting. [If you choose No, then you can choose how do you want to show more members in this widget in below setting.]',
        'multiOptions' => array(
            'yes' => 'Yes',
            'no' => 'No',
        ),
        'value' => 'no',
    ));
    $this->addElement('Radio', "pagging", array(
        'label' => "Do you want the members to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'button' => 'View more',
            'auto_load' => 'Auto Load',
            'pagging' => 'Pagination'
        ),
        'value' => 'auto_load',
    ));
    $this->addElement('Text', "grid_title_truncation", array(
        'label' => 'Title truncation limit for Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "advgrid_title_truncation", array(
        'label' => 'Title truncation limit for Advanced Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "list_title_truncation", array(
        'label' => 'Title truncation limit for List View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "pinboard_title_truncation", array(
        'label' => 'Title truncation limit for Pinboard View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "height", array(
        'label' => 'Enter the height of List block (in pixels).',
        'value' => '160',
    ));
    $this->addElement('Text', "width", array(
        'label' => 'Enter the width of List block (in pixels).',
        'value' => '140',
    ));
    $this->addElement('Text', "main_height", array(
        'label' => 'Enter the height of List main container (in pixels).',
        'value' => '180',
    ));
    $this->addElement('Text', "main_width", array(
        'label' => 'Enter the width of List main container (in pixels).',
        'value' => '450',
    ));
    $this->addElement('Text', "photo_height", array(
        'label' => 'Enter the height of grid photo block (in pixels).',
        'value' => '160',
    ));
    $this->addElement('Text', "info_height", array(
        'label' => 'Enter the height of grid info block (in pixels).',
        'value' => '160',
    ));
    $this->addElement('Text', "advgrid_height", array(
        'label' => 'Enter the height of advanced grid block (in pixels).',
        'value' => '222',
    ));
		 $this->addElement('Select', "gridblock",array(
				'label' => "How many grid box you want to show in one row in Grid / Advanced Grid View",
				'multiOptions' => array(
				  '12' => 'One',
					'6' =>  'Two',
					'4' =>  'Three',
					'3' =>  'Four',
				),
		));
    $this->addElement('Text', "pinboard_width", array(
        'label' => 'Enter the width of pinboard block (in pixels).',
        'value' => '250',
    ));
    $this->addElement('MultiCheckbox', "search_type", array(
        'label' => "Choose from below the tab that you want to show in this widget.",
        'multiOptions' => array(
            'week' => 'This Week',
            'month' => 'This Month',
            'recentlySPcreated' => 'Recently Created',
            'mostSPviewed' => 'Most Viewed',
            'mostSPliked' => 'Most Liked',
            'mostSPrated' => 'Most Rated',
            'featured' => 'Featured',
            'sponsored' => 'Sponsored',
            'verified' => 'Verified',
            'vip' => 'Vip',
        ),
    ));
    $counter = 1;
    // setting for Week
    $this->addElement('Text', "week_order", array(
        'label' => "Enter The order & text for tabs to be shown in this widget. ",
        'value' => $counter++,
    ));
    $this->addElement('Text', "week_label", array(
        'value' => 'This Week',
    ));
    // setting for Month
    $this->addElement('Text', "month_order", array(
        'label' => "Enter The order & text for tabs to be shown in this widget. ",
        'value' => $counter++,
    ));
    $this->addElement('Text', "month_label", array(
        'value' => 'This Month',
    ));
    // setting for Recently Updated
    $this->addElement('Text', "recentlySPcreated_order", array(
        'label' => "Enter The order & text for tabs to be shown in this widget. ",
        'value' => $counter++,
    ));
    $this->addElement('Text', "recentlySPcreated_label", array(
        'value' => 'Recently Created',
    ));
    // setting for Most Viewed
    $this->addElement('Text', "mostSPviewed_order", array(
        'label' => 'Most Viewed',
        'value' => $counter++,
    ));
    $this->addElement('Text', "mostSPviewed_label", array(
        'value' => 'Most Viewed',
    ));
    // setting for Most Liked
    $this->addElement('Text', "mostSPliked_order", array(
        'label' => 'Most Liked',
        'value' => $counter++,
    ));
    $this->addElement('Text', "mostSPliked_label", array(
        'value' => 'Most Liked',
    ));
    // setting for Most Rated
    $this->addElement('Text', "mostSPrated_order", array(
        'label' => 'Most Rated',
        'value' => $counter++,
    ));
    $this->addElement('Text', "mostSPrated_label", array(
        'value' => 'Most Rated',
    ));
    // setting for Featured
    $this->addElement('Text', "featured_order", array(
        'label' => 'Featured',
        'value' => $counter++,
    ));
    $this->addElement('Text', "featured_label", array(
        'value' => 'Featured',
    ));
    // setting for Sponsored
    $this->addElement('Text', "sponsored_order", array(
        'label' => 'Sponsored',
        'value' => $counter++,
    ));
    $this->addElement('Text', "sponsored_label", array(
        'value' => 'Sponsored',
    ));
    // setting for Verified
    $this->addElement('Text', "verified_order", array(
        'label' => 'Verified',
        'value' => $counter++,
    ));
    $this->addElement('Text', "verified_label", array(
        'value' => 'Verified',
    ));
    // setting for Verified
    $this->addElement('Text', "vip_order", array(
        'label' => 'Vip',
        'value' => $counter++,
    ));
    $this->addElement('Text', "vip_label", array(
        'value' => 'Vip',
    ));
  }
}
