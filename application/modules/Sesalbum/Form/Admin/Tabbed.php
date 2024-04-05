<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Tabbed.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Form_Admin_Tabbed extends Engine_Form
{
  public function init()
  {
		$this->addElement('Radio', "photo_album", array(
			'label' => "Choose from below the content types that you want to show in this widget.",
        'multiOptions' => array(
            'album' => 'Album',
						'photo' => 'Photo',
        ),
        'value' => 'photo',
    ));
		$this->addElement('Radio', "tab_option", array(
			'label' => "Choose the design of the tabs.",
      'multiOptions' => array(
			'default' => 'Default SE Tabs',
        'advance' => 'Advanced Tabs',
        'filter' =>'Advanced Tabs Filter Buttons'
      ),
      'value' => 'filter',
    ));
		$this->addElement('Radio', "view_type", array(
			'label' => "Choose the View Type for Photos (Pinboard View for photos only).",
        'multiOptions' => array(
            'masonry' => 'Masonry View',
						'grid' => 'Grid View',
						'pinboard'=>'Pinboard View',
        ),
        'value' => 'masonry',
    ));
		$this->addElement('Text', "description_truncation", array(
			'label' => 'Description limit if you choose pinboard view in above setting.',
        'value' => 80,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Radio', "hide_row", array(
			'label' => "Hide incomplete row.",
        'multiOptions' => array(
            '1' => 'Yes',
						'0' => 'No',
        ),
        'value' => '1',
    ));
		$this->addElement('Select', "insideOutside", array(
			'label' => 'Choose where do you want to show the statistics of photos / albums. "Outside the Photo/Album Block" option will only work if you select "Always" option from the below setting. Also it will work with Grid View only.',
        'multiOptions' => array(
            'inside' => 'Inside the Photo / Album Block',
						'outside' => 'Outside the Photo / Album Block',
        ),
        'value' => 'inside',
    ));
		$this->addElement('Select', "fixHover", array(
			'label' => 'Show photo / album statistics Always or when users Mouse-over on photo / album blocks (this setting will work only if you choose to show information inside the Photo / Album block.)',
        'multiOptions' => array(
           'fix' => 'Always',
					 'hover' => 'On Mouse-over',
					),
						'value' => 'always',
    ));
		$this->addElement('MultiCheckbox', "show_criteria", array(
        'label' => "Choose from below the details that you want to show in this widget.",
        'multiOptions' => array(
						'like' => 'Likes Count',
						'comment' => 'Comments Count',
						'rating' => 'Rating Stars',
						'view' => 'Views Count',
						'title' => 'Photo / Album Title',
						'by' => 'Owner\'s Name',
						'description'=>'Description (pinboard view photo only)',
						'socialSharing' =>'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
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
			'label' => 'count (number of photos / albums to show).',
        'value' => 20,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Select', "show_limited_data", array(
			'label' => 'Show only the number of photos / albums entered in above setting. [If you choose No, then you can choose how do you want to show more photos / albums in this widget in below setting.]',
			'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => '0',
    ));
		$this->addElement('Radio', "pagging", array(
			'label' => "Do you want the photos / albums to be auto-loaded when users scroll down the page? [This setting will work if you choose 'No' in the above setting.]",
					'multiOptions' => array(
					'auto_load' => 'Yes, Auto Load.',
					'button' => 'No, show \'View more\' link.',
					'pagging' =>'No, show \'Pagination\'.'
			),

        'value' => 'auto_load',
    ));
		$this->addElement('Text', "title_truncation", array(
			'label' => 'Photo / Album title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));
		$this->addElement('Text', "height", array(
			'label' => 'Enter the height of one block (in pixels).',
        'value' => '160',
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
		$this->addElement('MultiCheckbox', "search_type", array(
			 'label' => "Choose Popularity Criteria.",
			'multiOptions' => array(
					'recentlySPcreated' => 'Recently Created',
					'mostSPviewed' => 'Most Viewed',
					'mostSPfavourite' => 'Most Favourite',
					'mostSPliked' => 'Most Liked',
					'mostSPcommented' => 'Most Commented',
					'mostSPrated' => 'Most Rated',
					'mostSPdownloaded' => 'Most Downloaded',
					'featured' => 'Only Featured',
					'sponsored' => 'Only Sponsored',
					'thisweek' => 'This Week'
			),
    ));

	//Recently Created

		$this->addElement('Dummy', "dummy1", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Recently Created' Tab</span>",
    ));

		$this->getElement('dummy1')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "recentlySPcreated_order", array(
			 'label' => "Order of this Tab.",
			'value' => '1',
    ));
		$this->addElement('Text', "recentlySPcreated_label", array(
     		'label' => 'Title of this Tab.',
			'value' => 'Recently Created',
    ));
		// setting for Most Viewed

		$this->addElement('Dummy', "dummy2", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Viewed' Tab</span>",
    ));

		$this->getElement('dummy2')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "mostSPviewed_order", array(
			'label' => "Order of this Tab.",
			'value' => '2',
    ));
		$this->addElement('Text', "mostSPviewed_label", array(
     		'label' => 'Title of this Tab.',
			'value' => 'Most Viewed',
    ));
		// setting for Most Favourite

				$this->addElement('Dummy', "dummy3", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Favorite' Tab</span>",
    ));

		$this->getElement('dummy3')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "mostSPfavourite_order", array(
			'label' => "Order of this Tab.",
			'value' => '2',
    ));
		$this->addElement('Text', "mostSPfavourite_label", array(
     		'label' => 'Title of this Tab.',
			'value' => 'Most Favourite',
    ));
		// setting for Most Downloaded

		$this->addElement('Dummy', "dummy4", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Downloaded' Tab</span>",
    ));

		$this->getElement('dummy4')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "mostSPdownloaded_order", array(
			'label' => "Order of this Tab.",
			'value' => '2',
    ));
		$this->addElement('Text', "mostSPdownloaded_label", array(
			'label' => "Title of this Tab.",
			'value' => 'Most Downloaded',
    ));
		// setting for Most Liked

		$this->addElement('Dummy', "dummy5", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Liked' Tab</span>",
    ));

		$this->getElement('dummy5')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "mostSPliked_order", array(
			'label' =>'Order of this Tab.',
			'value' => '3',
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
			'label' =>'Order of this Tab.',
			'value' => '4',
    ));
		$this->addElement('Text', "mostSPcommented_label", array(
			'label' => 'Title of this Tab.',
			'value' => 'Most Commented',
    ));
		// setting for Most Rated
		$this->addElement('Dummy', "dummy7", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Most Rated' Tab</span>",
    ));

		$this->getElement('dummy7')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "mostSPrated_order", array(
			'label' =>'Order of this Tab',
			'value' => '5',
    ));
		$this->addElement('Text', "mostSPrated_label", array(
			'label' => 'Title of this Tab',
			'value' => 'Most Rated',
    ));
		// setting for Featured

		$this->addElement('Dummy', "dummy8", array(
			 'label' => "<span style='font-weight:bold;'>Order and Title of 'Featured' Tab</span>",
    ));

		$this->getElement('dummy8')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "featured_order", array(
			'label' =>'Order of this Tab.',
			'value' => '6',
    ));
		$this->addElement('Text', "featured_label", array(
			'label' => 'Title of this Tab.',
			'value' => 'Featured',
    ));
		// setting for Sponsored

    $this->addElement('Dummy', "dummy9", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Sponsored' Tab</span>",
    ));

		$this->getElement('dummy9')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

		$this->addElement('Text', "sponsored_order", array(
			'label' =>'Order of this Tab.',
			'value' => '7',
    ));
		$this->addElement('Text', "sponsored_label", array(
			'label' => 'Title of this Tab.',
			'value' => 'Sponsored',
    ));

    $this->addElement('Dummy', "dummy10", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'This Week' Tab</span>",
    ));

    $this->getElement('dummy10')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "thisweek_order", array(
        'label' =>'Order of this Tab.',
        'value' => '8',
    ));
    $this->addElement('Text', "thisweek_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'This Week',
    ));
  }
}
