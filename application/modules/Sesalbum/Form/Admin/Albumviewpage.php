<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Albumviewpage.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Form_Admin_Albumviewpage extends Engine_Form {

  public function init() {
  
		$this->addElement('Radio', "view_type", array(
			'label' => "Choose the View Type for photos.",
        'multiOptions' => array(
            'masonry' => 'Masonry View',
						'grid' => 'Grid View',
        ),
        'value' => 'masonry',
    ));
		$this->addElement('Select', "insideOutside", array(
			'label' => 'Choose where do you want to show the statistics of Photos. "Outside the Photo Block" option will only work if you select "Always" option from the below setting. Also it will work with Grid View only.',
        'multiOptions' => array(
            'inside' => 'Inside the Photo Block',
						'outside' => 'Outside the Photo Block',
        ),
        'value' => 'inside',
    ));
		$this->addElement('Select', "fixHover", array(
			'label' => 'Show photo statistics Always or when users Mouse-over on photos (this setting will work only if you choose to show information inside the Photo block.)',
        'multiOptions' => array(
           'fix' => 'Always',
					 'hover' => 'On Mouse-over',
					),
						'value' => 'fix',
    ));
	  $this->addElement('MultiCheckbox', "show_criteria_album_cover", array(
      'label' => "Choose from below the details that you want to show for Album Cover in this widget.",
      'multiOptions' => array(
        'likeCount' => 'Likes Count',
        'commentCount' => 'Comments Count',
        'ratingStars' => 'Rating Stars',
        'viewCount' => 'Views Count',
        'titleName' => 'Title',
        'byName' => 'Owner\'s Name',
        'photoCountCover' => 'Photo Count',
        'timeCreation' => 'Creation Date',
        'socialSharingIcons' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
        'favouriteCounts' => 'Favourites Count',
        'downloadCounts' => 'Downloads Count',
        'likeButtonCover' =>'Like Button',
        'messageButtonCover' => 'Message Button',
        'downloadButtonCover' => 'Download Button',
        'favouriteButtonCover' =>'Favourite Button',
      ),
      'escape' => false,
    ));
		$this->addElement('MultiCheckbox', "show_criteria", array(
       
		'label' => "Choose from below the details that you want to show for Photos in this widget.",
        'multiOptions' => array(
						'like' => 'Likes Count',
						'comment' => 'Comments Count',
						'rating' => 'Rating Stars',
						'view' => 'Views Count',
						'title' => 'Title',
						'by' => 'Owner\'s Name',
						'time' => 'Creation Date',
						'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
						'favouriteCount' => 'Favourites Count',
						'downloadCount' => 'Downloads Count',
						'featured' =>'Featured Label',
						'sponsored'=>'Sponsored Label',
						'likeButton' =>'Like Button',
						'favouriteButton' =>'Favourite Button',
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
			'label' => 'count (number of photos to show).',
        'value' => 20,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));		
		$this->addElement('Radio', "pagging", array(
			'label' => "Do you want the photos to be auto-loaded when users scroll down the page?",
					'multiOptions' => array(
					'auto_load' => 'Yes, Auto Load.',
					'button' => 'No, show \'View more\' link.',
					'pagging' =>'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    ));		
		$this->addElement('Text', "title_truncation", array(
			'label' => 'Enter photo title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		
		$this->addElement('Text', "height", array(
			'label' => 'Enter the height of one photo block (in pixels).',
        'value' => '160',
				'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
        
    ));
		// for related album
		
		
	
			$this->addElement('dummy', "dummy1", array(
         'label' => "<span style='font-weight:bold;font-size:14px;'>Settings for Related Albums Tab</span>",
		
		));
	
     	$this->getElement('dummy1')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
		
		$this->addElement('Select', "insideOutsideRelated", array(
			'label' => 'Choose where do you want to show the statistics of albums.',
        'multiOptions' => array(
            'inside' => 'Inside the Album Block',
						'outside' => 'Outside the Album Block',
        ),
        'value' => 'inside',
    ));
		$this->addElement('Select', "fixHoverRelated", array(
			'label' => 'Show album statistics Always or when users Mouse-over on album blocks (this setting will work only if you choose to show information inside the Album block.)',
        'multiOptions' => array(
           'fix' => 'Always',
					 'hover' => 'On Mouse-over',
					),
						'value' => 'fix',
    ));
		$this->addElement('MultiCheckbox', "show_criteriaRelated", array(
        'label' => "Choose from below the details that you want to show for Related Albums in this widget.",
        'multiOptions' => array(
						'like' => 'Likes Count',
						'comment' => 'Comments Count',
						'rating' => 'Rating Stars',
						'view' => 'Views Count',
						'title' => 'Album Title',
						'by' => 'Owner\'s Name',
						'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
						'favouriteCount' => 'Favourites Count',
						'downloadCount' => 'Downloads Count',
						'photoCount' => 'Photos Count',
						'featured' =>'Featured Label',
						'sponsored'=>'Sponsored Label',
						'likeButton' =>'Like Button',
						'favouriteButton' =>'Favourite Button',
        ),
        'escape' => false,
    ));
    
    //Social Share Plugin work
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')) {
      
      $this->addElement('Select', "socialshare_enable_plusiconalbum", array(
        'label' => "Enable More Icon for social share buttons?",
          'multiOptions' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
        'value' => 1,
      ));
      
      $this->addElement('Text', "socialshare_icon_limitalbum", array(
          'label' => 'Count (number of social sites to show). If you enable More Icon, then other social sharing icons will display on clicking this plus icon.',
          'value' => 2,
          'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
      ));
    }
    //Social Share Plugin work
    
		$this->addElement('Text', "limit_dataRelated", array(
			'label' => 'count (number of albums to be show in Related Albums tab).',
        'value' => 20,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));		
		$this->addElement('Radio', "paggingRelated", array(
			'label' => "Do you want the albums to be auto-loaded when users scroll down the page in Related Albums tab?",
        'multiOptions' => array(
            'auto_load' => 'Yes, Auto Load.',
            'button' => 'No, show \'View more\' link.',
			'pagging' =>'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    ));		
		$this->addElement('Text', "title_truncationRelated", array(
			'label' => 'Album title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		
		$this->addElement('Text', "heightRelated", array(
			'label' => 'Enter the height of one album block (in pixels).',
        'value' => '160',
				'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
        
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
		//end code.
		
		//Album Info Tab
		
			$this->addElement('dummy', "dummy2", array(
         'label' => "<span style='font-weight:bold;font-size:14px;'>Settings for Album Info & Discussion Tabs</span>",
		
		));
	
     	$this->getElement('dummy2')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
		
		
		$this->addElement('MultiCheckbox', "search_type", array(
			 'label' => "Choose from below the option Blocks that you want to show in this widget.",
			'multiOptions' => array(
					'RecentAlbum' => 'Recent Albums Of Current Album\'s Owner',
					'Like' => 'People Who Liked the current Album',
					'TaggedUser' => 'People who are Tagged in current Album',
					'Fav' =>'People who added current Album as Favourite',
			),
    ));
		$this->addElement('Dummy', "dummy", array(
			 'label' => "<span style='font-weight:bold;'>\"Recent Albums Of Current Album\'s Owner\" Block</span>",
    ));
	
		$this->getElement('dummy')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
	
		$this->addElement('Text', "RecentAlbum_order", array(
			 'label' => "Order of this Block.",
			'value' => '1',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		// setting for Most Viewed
		$this->addElement('Text', "RecentAlbum_label", 
		array(
			'label' => 'Title of this Block. (To show owner\'s name - use variable [USER_NAME])',
			'value' => '[USER_NAME]\'s Recent Albums',
    ));
		$this->addElement('Text', "RecentAlbum_limitdata", array(
					'label' => 'Enter the number of albums to be shown. After this number option to redirect to view more albums will be shown.',
			'value' => '10',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
	
	$this->addElement('Dummy', "dummy4", array(
			 'label' => "<span style='font-weight:bold;'>\"People Who Liked the current Album\" Block</span>",
    ));
	
		$this->getElement('dummy4')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
	
		$this->addElement('Text', "Like_order", array(
		 'label' => "Order of this Block.",
			'value' => '2',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Text', "Like_label", array(
			'label' => 'Title of this Block.',
			'value' => 'People Who Liked This Album',
    ));
		$this->addElement('Text', "Like_limitdata", array(
			'label' => 'Enter the number of albums to be shown. After this number option to view more albums in popup will be shown.',
			'value' => '10',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
	
		$this->addElement('Dummy', "dummy5", array(
			 'label' => "<span style='font-weight:bold;'>\"People who are Tagged in current Album\" Block</span>",
    ));
	
		$this->getElement('dummy5')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
		
		$this->addElement('Text', "TaggedUser_order", array(
			'label' =>'Order of this Block.',
			'value' => '3',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Text', "TaggedUser_label", array(
			'label' => 'Title of this Block.',
			'value' => 'People who are Tagged in This Album',
    ));
		$this->addElement('Text', "TaggedUser_limitdata", array(
			'label' => 'Enter the number of albums to be shown. After this number option to view more albums in popup will be shown.',
			'value' => '10',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
	
		$this->addElement('Dummy', "dummy6", array(
			 'label' => "<span style='font-weight:bold;'>\"People who added This Album as Favourite\" Block</span>",
    ));
	
		$this->getElement('dummy6')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
		
		$this->addElement('Text', "Fav_order", array(
			'label' =>'Order of this Block.',
			'value' => '4',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Text', "Fav_label", array(
			'label' => 'Title of this Block.',
			'value' => 'People Who Favourite This',
    ));
		$this->addElement('Text', "Fav_limitdata", array(
			'label' => 'Enter the number of albums to be shown. After this number option to view more albums in popup will be shown.',
			'value' => '10',
			'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
  }
}
?>
