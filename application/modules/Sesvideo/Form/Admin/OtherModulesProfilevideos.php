<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Profilevideos.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Admin_OtherModulesProfilevideos extends Engine_Form {

  public function init() {

    $this->addElement('MultiCheckbox', "enableTabs", array(
        'label' => "Choose the View Type for videos.",
        'multiOptions' => array(
            'list' => 'List View',
            'grid' => 'Grid View',
            'pinboard' => 'Pinboard View'
        ),
        'value' => 'list',
    ));
		
    $this->addElement('Select', "viewTypeStyle", array(
        'label' => "Show Data in this widget on mouse over/fixed (work in grid view only)?",
        'multiOptions' => array(
            'mouseover' => 'Yes,on mouse over',
            'fixed' => 'No,not on mouse over'
        ),
        'value' => 'fixed',
    ));
    $this->addElement('Select', "openViewType", array(
        'label' => 'Default open View Type (apply if select Both View option in above tab)?',
        'multiOptions' => array(
            'list' => 'List View',
            'grid' => 'Grid View',
            'pinboard' => 'Pinboard View',
        ),
        'value' => 'list',
    ));
    $this->addElement('MultiCheckbox', "show_criteria", array(
        'label' => "Choose from below the details that you want to show in this widget.",
        'multiOptions' => array(
            'featuredLabel' => 'Featured Label',
            'sponsoredLabel' => 'Sponsored Label',
            'hotLabel' => 'Hot Label',
            'watchLater' => 'Watch Later Button',
            'favouriteButton' => 'Favourite Button',
            'playlistAdd' => 'Playlist Add Button',
            'likeButton' => 'Like Button',
            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
            'like' => 'Like Counts',
            'favourite' => 'Favourite Counts',
            'comment' => 'Comment Counts',
            'rating' => 'Rating Starts',
            'view' => 'View Counts',
            'title' => 'Titles',
            'category' => 'Category',
            'by' => 'Item Owner Name',
            'duration' => 'Duration',
            'descriptionlist' => 'Description (List View)',
						'descriptiongrid' => 'Description (Grid View)',
						'descriptionpinboard' => 'Description (Pinboard View)',
						'enableCommentPinboard'=>'Enable comment on pinboard',
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
    
    $this->addElement('Radio', "pagging", array(
        'label' => "Do you want the videos to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'button' => 'View more',
            'auto_load' => 'Yes, Auto Load.',
            'pagging' => 'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    ));
   $this->addElement('Text', "title_truncation_grid", array(
        'label' => 'Title truncation limit for Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "title_truncation_list", array(
        'label' => 'Title truncation limit for List View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Text', "title_truncation_pinboard", array(
        'label' => 'Title truncation limit for Pinboard View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Text', "description_truncation_list", array(
        'label' => 'Description truncation limit for List View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		 $this->addElement('Text', "description_truncation_grid", array(
        'label' => 'Description truncation limit for Grid View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		 $this->addElement('Text', "description_truncation_pinboard", array(
        'label' => 'Description truncation limit for Pinboard View.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
   $this->addElement('Text', "height_grid", array(
        'label' => 'Enter the height of one block Grid(in pixels).',
        'value' => '270',
    ));
		$this->addElement('Select', "gridblock",array(
				'label' => "How many grid box you want to show in one row in Grid View",
				'multiOptions' => array(
				  '12' => 'One',
					'6' =>  'Two',
					'4' =>  'Three',
					'3' =>  'Four',
				),
		));
		$this->addElement('Text', "height_list", array(
        'label' => 'Enter the height of one block List(in pixels).',
        'value' => '230',
    ));
    $this->addElement('Text', "width_list", array(
        'label' => 'Enter the width of one block List(in pixels).',
        'value' => '260',
    ));
    $this->addElement('Text', "width_pinboard", array(
        'label' => 'Enter the width of one block Pinboard(in pixels).',
        'value' => '300',
    ));

    $this->addElement('MultiCheckbox', "search_type", array(
        'label' => "Choose from below the Tabs that you want to show in this widget.",
        'multiOptions' => array(
            'mySPvideo' => 'My Videos',
           // 'mySPchanel' => 'My Chanels',
           // 'mySPplaylist' => 'My Playlist',
						'mySPaddVideo' => 'Add Video Button',
						//'mySPaddChannel' => 'Add Channel Button',
        ),
    ));
    $this->addElement('Text', "limit_data", array(
        'label' => 'count (number of videos to show).',
        'value' => 20,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
    $this->addElement('Dummy', "dummy", array(
        'label' => "Enter the order of the Tabs to be shown in this widget. ",
    ));
    $this->addElement('Text', "mySPvideo_order", array(
        'label' => "Profile Owner\'s Videos",
        'value' => '1',
    ));

    /* $this->addElement('Text', "mySPvideo_label", array(
      'value' => 'Videos',
      )); */

//     $this->addElement('Text', "mySPchanel_order", array(
//         'label' => 'Profile Owner\'s Chanel',
//         'value' => '2',
//     ));
    /* 	$this->addElement('Text', "mySPchanel_label", array(
      'value' => 'Chanels',
      )); */

//     $this->addElement('Text', "mySPplaylist_order", array(
//         'label' => 'Profile Owner\'s Playlist',
//         'value' => '3',
//     ));
		
		 $this->addElement('Text', "mySPaddVideo_order", array(
        'label' => 'Add Video Button',
        'value' => '4',
    ));
		
// 		 $this->addElement('Text', "mySPaddChannel_order", array(
//         'label' => 'Add Channel Button',
//         'value' => '5',
//     ));
    /* 	$this->addElement('Text', "mySPplaylist_label", array(
      'value' => 'Playlists',
      )); */
  }
}