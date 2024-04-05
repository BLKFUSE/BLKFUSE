<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php 2015-10-11 00:00:00 SocialEngineSolutions $
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

$viewType = array(
    'MultiCheckbox',
    'enableTabs',
    array(
        'label' => "Choose the View Type.",
        'multiOptions' => array(
            'list' => 'List View',
            'grid' => 'Grid View',
            'pinboard' => 'Pinboard View'
        ),
    )
);
$defaultType = array(
    'Select',
    'openViewType',
    array(
        'label' => " Default open View Type (apply if selected More Than One View Type options in above tab)?",
        'multiOptions' => array(
            'list' => 'List View',
            'grid' => 'Grid View',
            'pinboard' => 'Pinboard View',
        ),
        'value' => 'list',
    )
);
$menuOption = array(
    'Select',
    'showTabType',
    array(
        'label' => 'Show Tab Type?',
        'multiOptions' => array(
            '0' => 'Default',
            '1' => 'Custom'
        ),
        'value' => 1,
    )
);
$viewTypeStyle = array(
    'Select',
    'viewTypeStyle',
    array(
        'label' => 'Show Data in this widget on mouse over/fixed (work in grid view only)?',
        'multiOptions' => array(
            'mouseover' => 'Yes,on mouse over',
            'fixed' => 'No,not on mouse over'
        ),
        'value' => 'fixed',
    )
);
$showCustomData = array(
    'MultiCheckbox',
    'show_criteria',
    array(
        'label' => "Data show in widget ?",
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
						'location' => 'Video Location',
            'rating' => 'Rating Stars  [enable "Item Owner Name" setting to see on Pinboard]',
            'view' => 'View Counts',
            'title' => 'Title',
            'category' => 'Category',
            'by' => 'Item Owner Name',
            'duration' => 'Duration',
            'descriptionlist' => 'Description (List View)',
						'descriptiongrid' => 'Description (Grid View)',
						'descriptionpinboard' => 'Description (Pinboard View)',
						'enableCommentPinboard'=>'Enable comment on pinboard',
        ),
        'escape' => false,
    ),
    
);
$showCustomDataChanel = array(
    'MultiCheckbox',
    'show_criteria',
    array(
        'label' => "Data show in widget ?",
        'multiOptions' => array(
            'featuredLabel' => 'Featured Label',
            'sponsoredLabel' => 'Sponsored Label',
            'video' => 'Videos Count',
            'view' => 'Views Count',
            'view' => 'Followers Count',
            'title' => 'Title',
            'category' => 'Category',
            'by' => 'Item Owner Name',
            'description' => 'Description ',
            'showVideo' => 'Show Videos'
        ),
    )
);
$limitData = array(
    'Text',
    'limit_data',
    array(
        'label' => 'count (number of videos to show).',
        'value' => 20,
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
        'label' => "Do you want the videos to be auto-loaded when users scroll down the page?",
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
    'title_truncation_list',
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
    'title_truncation_grid',
    array(
        'label' => 'Title truncation limit for Grid View.',
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
        'label' => 'Description truncation limit for List View.',
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
        'label' => 'Description truncation limit for Grid View.',
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
$heightOfContainerList = array(
    'Text',
    'height_list',
    array(
        'label' => 'Enter the height of one block List(in pixels).',
        'value' => '230',
    )
);
$widthOfContainerList = array(
    'Text',
    'width_list',
    array(
        'label' => 'Enter the width of one block List(in pixels).',
        'value' => '260',
    )
);
$heightOfContainerGrid = array(
    'Text',
    'height_grid',
    array(
        'label' => 'Enter the height of one block Grid(in pixels).',
        'value' => '270',
    )
);
$widthOfContainerPinboard = array(
    'Text',
    'width_pinboard',
    array(
        'label' => 'Enter the width of one block Pinboard(in pixels).',
        'value' => '300',
    )
);
$arrayGallery = array();
  if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.pluginactivated')) {

  $results = Engine_Api::_()->getDbtable('galleries', 'sesvideo')->getGallery(array('fetchAll' => true));
  if(is_countable($results) && engine_count($results) > 0 ) {
    foreach ($results as $gallery)
      $arrayGallery[$gallery['gallery_id']] = $gallery['gallery_name'];
  }
}
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.enable.location', 1)) {
                $milesData = array(
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
                );
                }
        

return array(
  array(
    'title' => 'SNS - Advanced Videos & Channels - Content Profile Videos',
    'description' => 'This widget enables you to allow users to create videos on different content on your website like Groups. Place this widget on the content profile page, for example SE Group to enable group owners to create videos in their Groups. You can choose the visibility of the videos created in a content to only that content or show in this plugin as well from the "Videos Created in Content Visibility" setting in Global setting of this plugin.',
    'category' => 'SNS - Advanced Videos & Channels Plugin',
    'type' => 'widget',
    'name' => 'sesvideo.other-modules-profile-videos',
    'autoEdit' => true,
    'defaultParams' => array(
      'title' => 'Videos',
      'titleCount' => true,
    ),
    'adminForm' => 'Sesvideo_Form_Admin_OtherModulesProfilevideos',
  ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Playlist Browse Search',
        'description' => 'Displays a search form in the playlist browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesvideo.playlist-browse-search',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'searchOptionsType',
                    array(
                        'label' => "Choose from below the searching options that you want to show in this widget.",
                        'multiOptions' => array(
                            'searchBox' => 'Search Playlist',
                            'view' => 'View',
                            'show' => 'List By',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => "SNS - Advanced Videos & Channels - Owner's Photo",
        'description' => 'This widget display on "SNS - Advanced Video - Video View Page", "SNS - Advanced Video - Channel View Page" and "SNS - Advanced Video - Playlist View Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.owner-photo',
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
        'title' => 'SNS - Advanced Videos & Channels - Videos / Channels Categories',
        'description' => 'Displays all categories of videos / channels in category level hierarchy view or cloud view as chosen by you. Edit this widget to choose the view type and various other settings.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.category',
        'autoEdit' => true,
        'adminForm' => 'Sesvideo_Form_Admin_Tagcloudcategory',
    ),
		array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Add Videos Button Widget',
        'description' => 'Displays a button to add videos on channel view page',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.channel-add-photo',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Alphabetic Filtering of Videos / Channel / Playlists /  Artists',
        'description' => "This widget displays all the alphabets for alphabetic filtering of videos / channels / playlists which will enable users to filter content on the basis of selected alphabet.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.alphabet-search',
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
                            'videos' => 'Videos',
                            'chanels' => 'Channels',
                            'playlists' => 'Playlists',
                            'artists' => 'Artists',
                        ),
                        'value' => 'video',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Artist Browse Search',
        'description' => 'Displays a search form in the artist browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesvideo.artist-browse-search',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'searchOptionsType',
                    array(
                        'label' => "Choose from below the searching options that you want to show in this widget.",
                        'multiOptions' => array(
                            'searchBox' => 'Search Artist',
                            'show' => 'List By',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Popular Playlists Carousel',
        'description' => 'Displays playlists based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.popular-playlists',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'showOptionsType',
                    array(
                        'label' => "Show",
                        'multiOptions' => array(
                            'all' => 'Popular Playlist [With this option, place this widget anywhere on your website. Choose criteria from "Popularity Criteria" setting below.]',
                            'recommanded' => 'Recommended Playlist [With this option, place this widget anywhere on your website.]',
                            'other' => 'Member’s Other Playlists [With this option, place this widget on Advanced Video - Playlist View Page.]',
                        ),
                        'value' => 'all',
                    ),
                ),
                array(
                    'Select',
                    'showType',
                    array(
                        'label' => "Do you want to show carousel?",
                        'multiOptions' => array(
                            'carouselview' => 'Yes',
                            'gridview' => 'No',
                        ),
                        'onchange' => 'showtypevalue(this.value)',
                        'value' => 'horizontal',
                    ),
                ),
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'featured' => 'Only Featured',
                            'view_count' => 'Most Viewed',
														'like_count' => 'Most Liked',
                            'creation_date' => 'Most Recent',
                            'modified_date' => 'Recently Updated',
                            'favourite_count' => "Most Favorite",
                            'video_count' => "Maximum Video",
                        ),
                        'value' => 'creation_date',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => "Choose the options that you want to be displayed in this widget.",
                        'multiOptions' => array(
                            "postedby" => "Playlist Owner's Name",
                            "viewCount" => "Views Count",
														 "likeCount" => "Likes Count",
                            "favouriteCount" => "Favorite Count",
                            "videoCount" => "Videos Count",
                            "songsListShow" => "Videos of each Playlist"
                        ),
                    )
                ),
                // array(
                //     'Select',
                //     'viewType',
                //     array(
                //         'label' => "View Type",
                //         'multiOptions' => array(
                //             'horizontal' => 'Horizontal',
                //             'vertical' => 'Vertical',
                //         ),
                //         'value' => 'horizontal',
                //     ),
                // ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block.',
                        'value' => 200,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                $widthOfContainer,
                array(
                    'Text',
                    'limitCarousel',
                    array(
                        'label' => 'Count (number of content to show at one time)[Count should be less than total count.]',
                        'value' => 1,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                array(
                    'Text',
                    'totalLimit',
                    array(
                        'label' => 'Total Count To Show (Total number of content to show)',
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
        'title' => 'SNS - Advanced Videos & Channels - Featured / Sponsored Videos / Channels or Playlists Carousel',
        'description' => "Disaplys Featured or Sponsored Carousel of Videos / Channels / Playlists.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.featured-sponsored-carosel',
        'adminForm' => array(
            'elements' => array(
								array(
                    'Select',
                    'category',
                    array(
                        'label' => 'Choose from below the content types that you want to show in this widget.',
                        'multiOptions' =>
                        array(
                            'videos' => 'Videos',
                            'chanels' => 'Channels',
														'playlists' => 'Playlists',
                        ),
                    ),
                ),
                array(
                    'Select',
                    'featured_sponsored_carosel',
                    array(
                        'label' => "Choose the content you want to show in this widget.",
                        'multiOptions' => array(
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored',
                            'hot' => 'Hot For Videos and Channels',
                            'verified' => 'Verified For Channels only',
														'all' => 'All',
                        ),
                        'value' => 'all',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for photos / albums in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'by' => 'Owner\'s Name',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'favouriteCount' => 'Favourites Count',
                            'videoCount' => 'Video Counts For Channels and Playlists',
                            'featured' => 'Featured Label',
														'sponsored' => 'Sponsored Label',
														'hot' => 'Hot For Videos and Channels',
														'duration' =>'Duration for Videos',
														'watchlater' =>'Watchlater for Videos',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of image of one block of item (in pixels).',
                        'value' => '200',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
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
                            "most_rated" => "Most Rated for Channels and Videos",
                            "most_commented" => "Most Commented",
                            "most_favourite" => "Most Favourite",
                            "most_video" => "Most Videos for Channels and Playlists",
                        )
                    ),
                    'value' => 'recently_updated',
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
                    'limit_value',
                    array(
                        'label' => 'Count (number of videos to show at one time.)[Count should be less than Total count.]',
                        'value' => 3,
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
                        'label' => 'Count (Total number of data to show.)',
                        'value' => 15,
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
        'title' => 'SNS - Advanced Videos & Channels - Featured / Sponsored Videos / Channels or Playlists Fixed View',
        'description' => "Disaplys Featured or Sponsored Carousel of Videos / Channels / Playlists.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.featured-sponsored-fixed-view',
        'adminForm' => array(
            'elements' => array(
								array(
                    'Select',
                    'category',
                    array(
                        'label' => 'Choose from below the content types that you want to show in this widget.',
                        'multiOptions' =>
                        array(
                            'videos' => 'Videos',
                            'chanels' => 'Channels',
														'playlists' => 'Playlists',
                        ),
                    ),
                ),
                array(
                    'Select',
                    'featured_sponsored_carosel',
                    array(
                        'label' => "Choose the content you want to show in this widget.",
                        'multiOptions' => array(
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored',
                            'hot' => 'Hot For Videos and Channels',
                            'verified' => 'Verified For Channels only',
														'all' => 'All',
                        ),
                        'value' => 'all',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for photos / albums in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'by' => 'Owner\'s Name',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'favouriteCount' => 'Favourites Count',
                            'videoCount' => 'Video Counts For Channels and Playlists',
                            'featured' => 'Featured Label',
														'sponsored' => 'Sponsored Label',
														'hot' => 'Hot For Videos and Channels',
														'duration' =>'Duration for Videos',
														'watchlater' =>'Watchlater for Videos',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
								array(
                    'Text',
                    'heightMain',
                    array(
                        'label' => 'Height of First big video (in pixels).',
                        'value' => '300',
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
                        'label' => 'Enter the height of small videos (in pixels).',
                        'value' => '200',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
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
                            "most_rated" => "Most Rated for Channels and Videos",
                            "most_commented" => "Most Commented",
                            "most_favourite" => "Most Favourite",
                            "most_video" => "Most Videos for Channels and Playlists",
                        )
                    ),
                    'value' => 'recently_updated',
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
                    'limit_data',
                    array(
                        'label' => 'Count (number of data to show.)',
                        'value' => 15,
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
        'title' => 'SNS - Advanced Videos & Channels - Featured / Sponsored Videos / Channels or Playlists Lp Widget',
        'description' => "Disaplys Featured or Sponsored Carousel of Videos / Channels / Playlists.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.featured-sponsored-lp-widget',
        'adminForm' => array(
            'elements' => array(
								array(
                    'Select',
                    'category',
                    array(
                        'label' => 'Choose from below the content types that you want to show in this widget.',
                        'multiOptions' =>
                        array(
                            'videos' => 'Videos',
                            'chanels' => 'Channels',
														'playlists' => 'Playlists',
                        ),
                    ),
                ),
                array(
                    'Select',
                    'featured_sponsored_carosel',
                    array(
                        'label' => "Choose the content you want to show in this widget.",
                        'multiOptions' => array(
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored',
                            'hot' => 'Hot For Videos and Channels',
                            'verified' => 'Verified For Channels only',
														'all' => 'All',
                        ),
                        'value' => 'all',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for photos / albums in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'by' => 'Owner\'s Name',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'favouriteCount' => 'Favourites Count',
                            'videoCount' => 'Video Counts For Channels and Playlists',
                            'featured' => 'Featured Label',
														'sponsored' => 'Sponsored Label',
														'hot' => 'Hot For Videos and Channels',
														'duration' =>'Duration for Videos',
														'watchlater' =>'Watchlater for Videos',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of videos (in pixels).',
                        'value' => '270',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
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
                            "most_rated" => "Most Rated for Channels and Videos",
                            "most_commented" => "Most Commented",
                            "most_favourite" => "Most Favourite",
                            "most_video" => "Most Videos for Channels and Playlists",
                        )
                    ),
                    'value' => 'recently_updated',
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
                    'limit_data',
                    array(
                        'label' => 'Count (number of data to show.)',
                        'value' => 15,
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
        'title' => 'SNS - Advanced Videos & Channels - Popular Artists',
        'description' => 'Displays artists based on chosen criteria for this widget. Edit this widget to choose various settings.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.popular-artists',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'favourite_count' => 'Most Favorite',
                            'rating' => 'Most Rated',
                        ),
                        'value' => 'favourite_count',
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
													'2' => 'Six',
                        ),
                    )
                ),
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => 'Choose the View Type.',
                        'multiOptions' => array(
                            'listview' => 'List View',
                            'gridview' => 'Grid View'
                        ),
                        'value' => 'listview',
                    )
                ),
								array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show in this widget.",
                        'multiOptions' => array(
                            'title' => 'Artists Title',
                            'favouriteCount' => 'Artists Favourite Counts',
                            'ratingCount' => 'Artists Rating Counts',
                        ),
                    ),
                ),
                $heightOfContainer,
                $widthOfContainer,
                array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'Count (number of content to show)',
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
        'title' => 'SNS - Advanced Videos & Channels - Popular / Featured / Sponsored Videos / Channels',
        'description' => "Displays videos or channels as chosen by you based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.featured-sponsored',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'tableName',
                    array(
                        'label' => 'Choose the content type.',
                        'multiOptions' => array(
                            "video" => "Video",
                            "chanel" => "Channel"
                        )
                    ),
                    'value' => 'video'
                ),
								array(
                    'Select',
                    'type',
                    array(
                        'label' => 'Choose the view type.',
                        'multiOptions' => array(
                            "list" => "List",
                            "grid" => "Grid"
                        )
                    ),
                    'value' => 'list'
								),
								$viewTypeStyle
								,
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            '5' => 'All including Featured and Sponsored',
                            '1' => 'Only Featured',
                            '2' => 'Only Sponsored',
                            '6' => 'Only Hot',
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
                            "recently_created" => "Recently Created",
                            "most_viewed" => "Most Viewed",
                            "most_liked" => "Most Liked",
                            "most_rated" => "Most Rated",
                            "most_commented" => "Most Commented",
                            "most_favourite" => "Most Favourite",
                            "random" => "Random",
                        )
                    ),
                    'value' => 'recently_created',
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for video / channel in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'category' => 'Category',
                            'duration' => 'Duration on video',
                            'watchLater' => 'WatchLater on video' ,
														'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
														 'likeButton' => 'Like Button for Grid view only',
                            'favouriteButton' => 'Favourite Button for Grid view only',
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
                        'label' => 'Video / Channel title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => 'Enter the width of one thumbnail in list view (in pixels).',
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
                        'label' => 'Count (number of videos / channels to show).',
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
        'title' => 'SNS - Advanced Videos & Channels - Recently Viewed Videos / Channels',
        'description' => 'This widget displays the recently viewed videos or channels by the user who is currently viewing your website or by the logged in members friend or by all the members of your website. Edit this widget to choose whose recently viewed content will show in this widget.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.recently-viewed-item',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'category',
                    array(
                        'label' => 'Choose from below the content types that you want to show in this widget.',
                        'multiOptions' =>
                        array(
                            'video' => 'Videos',
                            'chanel' => 'Channels',
                        ),
                    ),
                ),
								array(
                    'Select',
                    'type',
                    array(
                        'label' => 'Choose the view type.',
                        'multiOptions' => array(
                            "list" => "List",
                            "grid" => "Grid"
                        )
                    ),
                    'value' => 'list'
								),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => 'Display Criteria',
                        'multiOptions' =>
                        array(
                            'by_me' => 'Viewed By logged-in member',
                            'by_myfriend' => 'Viewed By logged-in member\'s friend',
                            'on_site' => 'Viewed by all members of website'
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for video / channel in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'category' => 'Category',
                            'duration' => 'Duration on video',
                            'watchLater' => 'WatchLater on video' ,
                        ),
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Video / Channel title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => 'Enter the width of one thumbnail in list view (in pixels).',
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
                        'label' => 'Count (number of video / channel to show.)',
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
        'title' => 'SNS - Advanced Videos & Channels - Videos / Channels / Playlists of the Day',
        'description' => "This widget displays videos / channels / playlists of the day as chosen by you from the Edit Settings of this widget.",
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.of-the-day',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'ofTheDayType',
                    array(
                        'label' => 'Choose content type to be shown in this widget.',
                        'multiOptions' => array(
                            'video' => 'Videos',
                            'chanel' => 'Channels',
                            'artist' => 'Artist',
                            'playlist' => 'Playlist',
                        ),
                        'value' => 'video',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for videos / channels / playlists in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
														 'favourite' => 'Favourite Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => 'Hot Label',
                            'likeButton' => 'Like Button',
                            'favouriteButton' => 'Favourite Button',
														'watchLater' =>'Watch Later on Videos',
                            'videoListShow' => "Videos List Show Playlist",
														'duration' =>'Duration on Videos',
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
                        'label' => 'Video / Channel title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => "How many content you want to show in one row?",
                        'multiOptions' => array(
												  '12' => 'One',
                          '6' => 'Two',
                          '4' => 'Three',
                          '3' => 'Four',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Quick AJAX based Search',
        'description' => 'Displays a quick search box to enable users to quickly search Videos, Channel, Playlists, Artists of their choice.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesvideo.search',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Html 5 Videos Slideshow',
        'description' => 'This widget displays video slideshow as chosen by you from the "Manage Slides" section of Advanced Video.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.slideshow',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'gallery_id',
                    array(
                        'label' => 'Select Gallery which you created from Manage Slide option.?',
                        'multiOptions' => $arrayGallery,
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'full_width',
                    array(
                        'label' => 'Do you want to show slideshow in full width?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'logo',
                    array(
                        'label' => 'Do you want to show logo on video?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'main_navigation',
                    array(
                        'label' => 'Do you want to show main navigation on video?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
								array(
                    'Select',
                    'mini_navigation',
                    array(
                        'label' => 'Do you want to show mini navigation on video?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
								array(
                    'Select',
                    'autoplay',
                    array(
                        'label' => 'Do you want to auto play slideshow?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
								array(
                    'Select',
                    'thumbnail',
                    array(
                        'label' => 'Do you want to show thumbnail?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
								array(
                    'Select',
                    'searchEnable',
                    array(
                        'label' => 'Do you want to enable search?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Height of video container (px)',
                        'value' => 583,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                /*array(
                    'Text',
                    'maxLimit',
                    array(
                        'label' => 'After how much data want to show view more(setting work if select yes in above field)',
                        'value' => 10,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),*/
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video / Channel tags',
        'description' => 'Displays all video / channel tags on your website. The recommended page for this widget is "SNS - Advanced Videos & Channels - Browse Tags Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.tag-video-chanel',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Artist Details & Options',
        'description' => 'This widget displays artist details and various options. The recommended page for this widget is "Advanced Video - Artist View Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.profile-artist',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'informationArtist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Artist" shown in this widget.',
                        'multiOptions' => array(
                            "favouriteCountAr" => "Favorite Count",
                            "ratingCountAr" => "Rating Count",
                            "socialShare" => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "description" => "Description",
                            "ratingStarsAr" => "Rating Stars",
                            "addFavouriteButtonAr" => "Add to Favorite Button",
                        ),
                        'escape' => false,
                    ),
                ),
								$viewType,
								$viewTypeStyle,
							  $defaultType,
								array(
                                    'MultiCheckbox',
                                    'show_criteria',
                                    array(
                                        'label' => "Data show in widget ?",
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
                                                        'location' => 'Video Location',
                                            'rating' => 'Rating Count  [enable "Item Owner Name" setting to see on Pinboard]',
                                            'view' => 'View Counts',
                                            'title' => 'Title',
                                            'category' => 'Category',
                                            'by' => 'Item Owner Name',
                                            'duration' => 'Duration',
                                            'descriptionlist' => 'Description (List View)',
                                                        'descriptiongrid' => 'Description (Grid View)',
                                                        'descriptionpinboard' => 'Description (Pinboard View)',
                                                        'enableCommentPinboard'=>'Enable comment on pinboard',
                                        ),
                                        'escape' => false,
                                    ),
                                    
                                ),
								$socialshare_enable_plusicon,
								$socialshare_icon_limit,
								$titleTruncationGrid,
								$titleTruncationList,
								$titleTruncationPinboard,
								$DescriptionTruncationList,
								$DescriptionTruncationGrid,
								$DescriptionTruncationPinboard,
								$limitData,
								$pagging,
                $heightOfContainerGrid,
								$heightOfContainerList,
								$widthOfContainerList,
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
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Browse Artists',
        'description' => 'Displays all artists on your website.  The recommended page for this widget is "Advanced Video - Browse Artists Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.browse-artists',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'paginationType',
                    array(
                        'label' => 'Do you want artists to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in this widget".',
                        'multiOptions' => array(
                            'showfavourite' => 'Show Favorite Count',
                            'showrating' => 'Show Rating Count',
                        ),
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height for Grid View (in pixels).',
                        'value' => 200,
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
                        'label' => "How many content you want to show in one row?",
                        'multiOptions' => array(
												  '12' => 'One',
                          '6' => 'Two',
                          '4' => 'Three',
                        ),
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of content to show)',
                        'value' => 2,
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
        'title' => 'SNS - Advanced Videos & Channels - Breadcrumb for Video / Channel / Artist / Playlist View Page',
        'description' => 'Displays breadcrumb for Video / Channel / Artist / Playlist. This widget should be placed on the Advanced Video - View page of the selected content type.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesvideo.breadcrumb',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewPageType',
                    array(
                        'label' => "Choose content type.",
                        'multiOptions' => array(
                            'video' => 'Video',
                            'chanel' => 'Channel',
                            'artist' => 'Artist',
                            'playlist' => 'Playlist',
                        ),
                        'value' => 'video',
                    ),
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Playlist Details',
        'description' => 'This widget displays playlist details and various options. The recommended page for this widget is "Advanced Video - Playlist View Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.playlist-view-page',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'informationPlaylist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Playlist" shown in this widget.',
                        'multiOptions' => array(
                            "editButton" => "Edit Button Playlist",
                            "deleteButton" => "Delete Button Playlist",
                            "viewCountPlaylist" => "Views Count Playlist",
                            "descriptionPlaylist" => "Description Playlist",
                            "postedby" => "Created By Playlist",
                            "sharePlaylist" => "Share Button Playlist",
                            "favouriteButtonPlaylist" => "Add to Favorite Playlist",
														'favouriteCountPlaylist'=>'Favourite Count Playlist',
														'likeButtonPlaylist'=>'Like Button Playlist',
														'featuredLabelPlaylist'=>'Featured label Playlist',
														'sponsoredLabelPlaylist'=>'Sponsored label Playlist',
														'socialSharingPlaylist'=>'Social Share Playlist',
														'likeCountPlaylist' =>'Like Counts',
														'reportPlaylist'=>'Report Playlist',
                        ),
                        'escape' => false,
                    ),
                ),
								$viewTypeStyle,
                $viewType,
								$defaultType,
								$showCustomData,
								$socialshare_enable_plusicon,
								$socialshare_icon_limit,
								$titleTruncationGrid,
								$titleTruncationList,
								$titleTruncationPinboard,
								$DescriptionTruncationList,
								$DescriptionTruncationGrid,
								$DescriptionTruncationPinboard,
								$limitData,
								$pagging,								
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
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video Browse Page Widget',
        'description' => 'Displays a browse page for videos. You can place this widget at browse page of video on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.browse-video',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => array(
            'elements' => array(
                $viewTypeStyle,
                $viewType,
                $defaultType,
                $showCustomData,
                array(
                    'Select',
                    'socialshare_enable_listviewplusicon',
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
                  'socialshare_icon_listviewlimit',
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
                    'socialshare_enable_gridviewplusicon',
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
                  'socialshare_icon_gridviewlimit',
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
								'Radio',
								'sort',
								array(
										'label' => 'Choose Video Display Criteria.',
										'multiOptions' => array(
												"recentlySPcreated" => "Recently Created",
												"mostSPviewed" => "Most Viewed",
												"mostSPliked" => "Most Liked",
												"mostSPated" => "Most Rated",
												"mostSPcommented" => "Most Commented",
												"mostSPfavourite" => "Most Favourite",
												'featured' => 'Only Featured',
												'sponsored' => 'Only Sponsored',
												'hot' => 'hot',
										),
										'value' => 'most_liked',
								)
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
											'label' => 'Pinboard count (number of videos to show).',
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
											'label' => 'Grid count (number of videos to show).',
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
											'label' => 'List count (number of videos to show).',
											'value' => 20,
											'validators' => array(
													array('Int', true),
													array('GreaterThan', true, array(0)),
											)
									)
							),
              $pagging,
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Photo View Page Widget',
        'description' => 'This widget enables you to choose various options to be shown on channel photo view page like Slideshow of other photos associated with same channel as the current photo, etc.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-photo-view-page',
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
                            'like' => 'People who Liked the current photo',
                            'slideshowPhoto' => 'Slideshow of other photos associated with same channel',
                        ),
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
                array(
                    'Text',
                    'view_more_like',
                    array(
                        'label' => 'Enter the number of photos to be shown in "People Who Liked This Photo" block. After the number of photos entered below, option to view more photos in popup will be shown.',
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
        'title' => 'SNS - Advanced Videos & Channels - Browse Playlists',
        'description' => 'Displays all playlists on your website.  The recommended page for this widget is "Advanced Video - Browse Playlists Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.browse-playlists',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'featured' => 'Only Featured',
                            'view_count' => 'Most Viewed',
                            'creation_date' => 'Most Recent',
                            'modified_date' => 'Recently Updated',
                        ),
                        'value' => 'creation_date',
                    )
                ),
                array(
                    'Select',
                    'paginationType',
                    array(
                        'label' => 'Do you want the playlists to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in this widget.',
                        'multiOptions' => array(
                            'videoCount' => 'Video Count',
                            "viewCount" => "Views Count",
                            "title" => "Playlist Title",
                            "description" => "Description",
                            "postedby" => "Created By",
                            "share" => "Share Button",
														'favouriteButton'=>'Favourite Button',
                            'watchLater' => "Watch Later [for playlist videos]",
														'favouriteCount'=>'Favourite counts',
														'featuredLabel'=>'Featured label',
														'sponsoredLabel'=>'Sponsored label',
														'likeButton'=>'Like Button',
														'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
														'likeCount'=>'Like Counts',
                            'showVideosList' => "Show videos in the playlist",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
								  array(
                    'Text',
                    'description_truncation',
                    array(
                        'label' => 'Enter the description truncation of playlist (numeric value).',
                        'value' => '60',
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of content to show)',
                        'value' => 10,
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
        'title' => 'SNS - Advanced Videos & Channels - Tabbed Widget',
        'description' => 'Displays a tabbed widget for videos. You can place this widget anywhere on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.tabbed-widget-video',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => 'Sesvideo_Form_Admin_Tabbed',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Manage Video Tabbed Widget',
        'description' => 'Displays a manage page for videos. You can place this widget at manage page of video on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.tabbed-widget-videomanage',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => 'Sesvideo_Form_Admin_Manage',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Category Associate Videos Widget',
        'description' => 'Displays a category associate Videos. You can place this widget anywhere on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.category-associate-video',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in widget ?",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'favourite' => 'Favourites Count',
                            'by' => 'Owner\'s Name',
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => 'Hot Label',
														'watchnow' =>'Watch Now Button'
                        ),
                    )
                ),
                array(
                    'Radio',
                    'popularity_video',
                    array(
                        'label' => 'Choose Video Display Criteria.',
                        'multiOptions' => array(
                            "creationSPdate" => "Recently Created",
                            "viewSPcount" => "Most Viewed",
                            "likeSPcount" => "Most Liked",
                            "rating" => "Most Rated",
                            "commentSPcount" => "Most Commented",
                            "favouriteSPcount" => "Most Favourite",
                            'isSPfeatured' => 'Only Featured',
                            'isSPsponsored' => 'Only Sponsored',
                            'isSPhot' => 'Only Hot',
                        ),
                        'value' => 'like_count',
                    )
                ),
                $pagging,
                array(
                    'Select',
                    'count_video',
                    array(
                        'label' => "Show count video",
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
                        'label' => "Criteria to show category in this widget",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical Order',
                            'most_video' => 'Most Videos Category First',
                            'admin_order' => 'Admin Order',
                        ),
                    ),
                ),
                array(
                    'Text',
                    'category_limit',
                    array(
                        'label' => 'count (number of category to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'video_limit',
                    array(
                        'label' => 'count (number of video to show in each category).',
                        'value' => '8',
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
                        'label' => 'See All text, leave blank if don\'t won\'t to show see all text( put [category_name] if you want to show category name).',
                        'value' => '+ See all [category_name]',
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
                $heightOfContainer,
                $widthOfContainer,
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video Home No Video Message',
        'description' => 'Displays a message when there is no Video on your website. The recommended page for this widget is "Advanced Video - Video Home Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.video-home-error',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Category View Page Widget',
        'description' => 'Displays a view page for categories. You can place this widget at view page of category on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.category-view',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'show_subcat',
                    array(
                        'label' => "Show sub categories",
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
                        'label' => "Data show in subcategory  ?",
                        'multiOptions' => array(
                            'icon' => 'Icon',
                            'title' => 'Category Name',
                            'countVideo' => 'Count Videos',
                        ),
                    )
                ),
								array(
                    'Select',
                    'mouse_over_title',
                    array(
                        'label' => "Show Subcategory Title on mouse over (if selected category name from above widget)?",
                        'multiOptions' => array(
                            '1' => 'Yes,show category title on mouseover.',
                            '0' => 'No,always show category title',
                        ),
                    ),
										'value' =>'1'
                ),
                array(
                    'Text',
                    'heightSubcat',
                    array(
                        'label' => 'Enter the height of one block of subcategory (in pixels).',
                        'value' => '160px',
                    )
                ),
								 array(
                    'Select',
                    'gridblock',
                    array(
                        'label' => "How many subcategory blocks you want to show in one row?",
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
                    'textVideo',
                    array(
                        'label' => 'Text Heading For Videos.',
                        'value' => 'Videos we love',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in videos ?",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
														'hotLabel'=>'Hot Label',
                            'like' => 'Likes',
                            'comment' => 'Comments',
                            'rating' => 'Ratings',
														'favourite'=>'Favourite',
                            'view' => 'Views',
                            'title' => 'Title',
                            'by' => 'Item Owner Name',
														'watchnow' =>'Watch Now Button'
                        ),
                    )
                ),
                $pagging,
                array(
                    'Text',
                    'video_limit',
                    array(
                        'label' => 'count (number of videos to show).',
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
                        'label' => 'Enter the height of one block (in pixels,this setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels,this setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                )
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Category Banner Widget',
        'description' => 'Displays a banner for categories. You can place this widget at browse page of category on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.banner-category',
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => 'Sesvideo_Form_Admin_Categorywidget',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Videos Category Widget',
        'description' => 'Displays a video categories. You can place this widget anywhere on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.video-category',
        'requirements' => array(
            'subject' => 'video',
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
                        ),
                    )
                ),
								 array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'count (number of category to show).',
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
                        'label' => " Show categories without videos",
                        'multiOptions' => array(
                            '1' => 'Yes,show only categories with videos',
                            '0' => 'No,show all categories',
                        ),
                    ),
										'value' =>'1'
                ),
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => "Criteria to show category in this widget",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical Order',
                            'most_video' => 'Most Videos Category First',
                            'admin_order' => 'Admin Order',
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in this widget ?",
                        'multiOptions' => array(
                            'title' => 'Title',
                            'icon' => 'Category Icon',
                            'countVideos' => 'Count of Videos',
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Browse Page Widget',
        'description' => 'Displays a browse page for video channels. You can place this widget at browse page of video channel on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-chanel',
        'autoEdit' => true,
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in widget ?",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => 'Hot Label',
                            'description' => 'Description',
                            'follow' => 'Follow Counts',
                            'followButton' => 'Follow Button',
                            'favouriteButton' => 'Favourite Button',
                            'likeButton' => 'Like Button',
                            'verified' => 'Verified Icon',
														'rating'=>'Rating Stars',
														'socialeShare'=> 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'like' => 'Like Count',
                            'comment' => 'Comment Count',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'favourite' => 'Favourite Count',
                            'by' => 'Item Owner Name',
                            'chanelPhoto' => 'Channel Photo',
                            'chanelVideo' => 'Channel Video',
                            'chanelThumbnail' => 'Channel Thumbnails',
                            'videoCount' => 'Video Counts',
                            'duration' => 'Duration On Video',
                            'watchLater' => 'Watchlater On Video',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $pagging,
								 array(
                    'Select',
                    'view_channel_type',
                    array(
                        'label' => "Select Channel View Type",
                        'multiOptions' => array(
                            '1' => 'Category Slideshow View',
                            '0' => 'Grid View'
                        ),
                    ),
                ),
                array(
                    'Select',
                    'count_chanel',
                    array(
                        'label' => "Show count Channel",
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
                        'label' => "Criteria to show category in this widget",
                        'multiOptions' => array(
                            'alphabetical' => 'Alphabetical Order',
                            'most_chanel' => 'Most Channel Category First',
                            'admin_order' => 'Admin Order',
                        ),
                    ),
                ),
                array(
                    'Text',
                    'category_limit',
                    array(
                        'label' => 'count (number of category to show).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'chanel_limit',
                    array(
                        'label' => 'count (number of channel to show in each category).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'video_limit',
                    array(
                        'label' => 'count (number of video to show in each channel).',
                        'value' => '6',
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
                        'label' => 'See All text, leave blank if don\'t won\'t to show see all text( put [category_name] if you want to show category name).',
                        'value' => '+ See all [category_name]',
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
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Enter the title truncation of channel (numeric value).',
                        'value' => '150',
                    )
                ),
                array(
                    'Text',
                    'description_truncation',
                    array(
                        'label' => 'Enter the description truncation of channel (numeric value).',
                        'value' => '200',
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of video thumbnail block (in pixels).',
                        'value' => '80',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of video thumbnail (in pixels).',
                        'value' => '120',
                    )
                ),)
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Category Browse Page Widget',
        'description' => 'Displays a channel category browse page for video channels. You can place this widget at browse page of video channel catgeory page on your site.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-category-chanel',
        'autoEdit' => true,
        'requirements' => array(
            'subject' => 'video',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in widget ?",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
                            'hotLabel' => 'Hot Label',
                            'description' => 'Description',
                            'follow' => 'Follow Counts',
                            'followButton' => 'Follow Button',
                            'favouriteButton' => 'Favourite Button',
                            'likeButton' => 'Like Button',
                            'verified' => 'Verified Icon',
														'rating'=>'Rating Stars',
														'socialeShare' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'like' => 'Like Count',
                            'comment' => 'Comment Count',
                            'photo' => 'Photo Count',
                            'view' => 'Views Count',
                            'title' => 'Title',
                            'favourite' => 'Favourite Count',
                            'by' => 'Item Owner Name',
                            'chanelPhoto' => 'Channel Photo',
                            'chanelVideo' => 'Channel Video',
                            'chanelThumbnail' => 'Channel Thumbnails',
                            'videoCount' => 'Video Counts',
                            'duration' => 'Duration On Video',
                            'watchLater' => 'Watchlater On Video',
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $pagging,
                array(
                    'Text',
                    'chanel_limit',
                    array(
                        'label' => 'count (number of channel to show ).',
                        'value' => '10',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'video_limit',
                    array(
                        'label' => 'count (number of video to show in each channel).',
                        'value' => '6',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Enter the title truncation of channel (numeric value).',
                        'value' => '150',
                    )
                ),
                array(
                    'Text',
                    'description_truncation',
                    array(
                        'label' => 'Enter the description truncation of channel (numeric value).',
                        'value' => '200',
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of video thumbnail block (in pixels).',
                        'value' => '80',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of video thumbnail (in pixels).',
                        'value' => '120',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video/Channel People Also Liked',
        'description' => 'Displays a list of other videos / channels that the people who liked this video / channel also liked.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.show-also-liked',
        'defaultParams' => array(
            'title' => 'People Also Liked',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'tableName',
                    array(
                        'label' => 'Choose the content type.',
                        'multiOptions' => array(
                            "video" => "Video",
                            "chanel" => "Channel"
                        )
                    ),
                    'value' => 'video'
                ),
								array(
                    'Select',
                    'type',
                    array(
                        'label' => 'Choose the view type.',
                        'multiOptions' => array(
                            "list" => "List",
                            "grid" => "Grid"
                        )
                    ),
                    'value' => 'list'
								),
								$viewTypeStyle,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for video / channel in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'category' => 'Category',
                            'duration' => 'Duration on video',
                            'watchLater' => 'WatchLater on video' ,
                        ),
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Video / Chanel title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => 'Enter the width of thumbnail in List View (in pixels).',
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
                        'label' => 'Count (number of videos / channels to show).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            )
        ),
        'requirements' => array(
            'subject' => 'video',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Other Videos /Channels From Member',
        'description' => 'Displays the other videos/channels from the same User. Place this widget on "Video View Page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.show-same-poster',
        'defaultParams' => array(
            'title' => 'From the same member',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'tableName',
                    array(
                        'label' => 'Choose the content type.',
                        'multiOptions' => array(
                            "video" => "Video",
                            "chanel" => "Channel"
                        )
                    ),
                    'value' => 'video'
                ),
                array(
                    'Select',
                    'type',
                    array(
                        'label' => 'Choose the view type.',
                        'multiOptions' => array(
                            "list" => "List",
                            "grid" => "Grid"
                        )
                    ),
                    'value' => 'list'
								),
								$viewTypeStyle,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for video / channel in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'category' => 'Category',
                            'duration' => 'Duration on video',
                            'watchLater' => 'WatchLater on video' ,
                        ),
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Video /channel  title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => 'Enter the width of thumbnail in List View (in pixels).',
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
                        'label' => 'Count (number of videos / channels to show).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            )
        ),
        'requirements' => array(
            'subject' => 'video',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Similar Videos/Channels',
        'description' => 'Displays a list of other videos that are similar to the current video, based on tags.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.show-same-tags',
        'defaultParams' => array(
            'title' => 'Similar Videos',
        ),
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'tableName',
                    array(
                        'label' => 'Choose the content type.',
                        'multiOptions' => array(
                            "video" => "Video",
                            "chanel" => "Channel"
                        )
                    ),
                    'value' => 'video'
                ),
               array(
                    'Select',
                    'type',
                    array(
                        'label' => 'Choose the view type.',
                        'multiOptions' => array(
                            "list" => "List",
                            "grid" => "Grid"
                        )
                    ),
                    'value' => 'list'
								),
								$viewTypeStyle,
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Choose from below the details that you want to show for video / channel in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'favourite' => 'Favourite Count',
                            'view' => 'Views Count',
                            'title' => 'Video / Channel Title',
                            'by' => 'Owner\'s Name',
                            'category' => 'Category',
                            'duration' => 'Duration on video',
                            'watchLater' => 'WatchLater on video' ,
                        ),
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                    array(
                        'label' => 'Video  title truncation limit.',
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
                        'label' => 'Enter the height of one video / channel block (in pixels).',
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
                        'label' => 'Enter the width of one thumbnail in List View (in pixels).',
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
                        'label' => 'Count (number of videos / channels to show).',
                        'value' => 5,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
            )
        ),
        'requirements' => array(
            'subject' => 'video',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video Browse Menu',
        'description' => 'Displays a menu in the video browse page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-menu',
        'requirements' => array(
            'no-subject',
        ),
		'adminForm' => array(
		  'elements' => array (
			array(
			  'Radio',
			  'createButton',
			  array(
				'label' => "Enable Post New Video Button? Note: You can  disable 'Post New Video' menu from menu editor if you don't want it twice in navigation menu on your website.",
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
        'title' => 'SNS - Advanced Videos & Channels - Video / Channel Tags Cloud',
        'description' => 'Displays all tags of video / channel in cloud view. Edit this widget to choose various other settings.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.tag-cloud',
        'autoEdit' => true,
        'adminForm' => 'Sesvideo_Form_Admin_Tagcloud',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Post New Video Button',
        'description' => 'Displays a small menu in the video browse page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-menu-quick',
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Browse Quick Menu',
        'description' => 'Displays a small menu in the video channel create page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-chanel-quick',
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Follow User',
        'description' => 'Displays a list of channel follow users on video channel view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.channel-follow-user',
        'requirements' => array(
            'sesvideo_chanel',
        ),
        'adminForm' => 'Sesvideo_Form_FollowersSettings',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Overview ',
        'description' => 'Displays a Channel overview on video channel view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-overview',
        'requirements' => array(
            'sesvideo_chanel',
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Info',
        'description' => 'Displays a Channel about on video channel view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-info',
        'requirements' => array(
            'sesvideo_chanel',
        ),
    ),
    //channel view page
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Profile / Owner Photo',
        'description' => 'Displays a channel\'s photo on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-photo',
				'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
						 array(
                    'Select',
                    'photo',
                    array(
                        'label' => "Do you want to show Channel’s Profile or Owner photo in this widget.",
                        'multiOptions' => array(
                            'pPhoto' => 'Profile Photo',
                            'oPhoto' => 'Owner Photo',
                        ),
                        'value' => 'pPhoto',
                    )
                ),
						 ),
				 ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Profile Options',
        'description' => 'Displays a menu of actions (edit, follow , delete) that can be performed on a channel on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-options',
    ),
		array(
        'title' => 'SNS - Advanced Videos & Channels - Channel / Video Labels ',
        'description' => 'Displays a featured, sponsored , verified and hot on a channel / video on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-video-label',
				'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
						 array(
												'MultiCheckbox',
												'option',
												array(
														'label' => "Choose options to be shown in this widget.",
														'multiOptions' => array(
																'hot' => 'Hot',
																'featured' => 'Featured',
																'sponsored' => 'Sponsored',
																'verified' => 'Verified',
																'offtheday' => 'Of The Day',
														),
												)
										),
						   ),
				 ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Likes',
        'description' => 'Displays a channel\'s likes on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-likes',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Profile Status',
        'description' => 'Displays a channel\'s title on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-status',
				'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
						 array(
												'MultiCheckbox',
												'option',
												array(
														'label' => "Choose options to be shown on Cover Photo in this widget.",
														'multiOptions' => array(
																'report' => 'Report Button',
																'follow' => 'Follow Button',
																'like' => 'Like Button',
																'share' => 'Share Button',
																'delete' => 'Delete Button',
																'edit' => 'Edit Button',
																'favourite' => 'Follow Button',
																'verified' =>'Verified Button',
														),
												)
										),
						   ),
				 ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Videos',
        'description' => 'Displays a channel\'s videos on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.chanel-videos',
        'adminForm' => array(
            'elements' => array(
                $showCustomData,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $viewType,
								$viewTypeStyle,
                $defaultType,
                array(
									'Text',
									'limit_data_pinboard',
									array(
											'label' => 'Pinboard count (number of videos to show).',
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
											'label' => 'Grid count (number of videos to show).',
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
											'label' => 'List count (number of videos to show).',
											'value' => 20,
											'validators' => array(
													array('Int', true),
													array('GreaterThan', true, array(0)),
											)
									)
							),
                $pagging,
                $titleTruncationList,
								$titleTruncationGrid,
								$titleTruncationPinboard,
                $DescriptionTruncationList,
								$DescriptionTruncationGrid,
								$DescriptionTruncationPinboard,
								$heightOfContainerGrid,
								$heightOfContainerList,
								$widthOfContainerList,
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
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Profile Videos',
        'description' => 'Displays a member\'s videos, channels and playlist  on their profile. The recommended page for this widget is "Member Profile Page".',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.profile-videos',
        'autoEdit' => true,
        'defaultParams' => array(
          'title' => 'Videos',
          'titleCount' => true,
        ),
        'adminForm' => 'Sesvideo_Form_Admin_Profilevideos',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Discussion',
        'description' => 'Displays a channel\'s discussion on it\'s profile.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-discussion',
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Photos',
        'description' => 'Displays a channel\'s photos on it\'s profile.This widget will appear when you have installed "SNS - Advanced Photos & Albums Plugin"',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-photos',
				'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
							array(
                    'Select',
                    'view_type',
                    array(
										'label' => "Choose the View Type for Photos (Pinboard View for photos only).",
											'multiOptions' => array(
													'masonry' => 'Masonry View',
													'grid' => 'Grid View',
											),
											'value' => 'masonry',
									)
                ),
                array(
                    'Select',
                    'insideOutside',
                    array(
                        'label' => "Choose where do you want to show the statistics of photos .",
                        'multiOptions' => array(
                            'inside' => 'Inside the Photo Block',
                            'outside' => 'Outside the Photo Block',
                        ),
                        'value' => 'inside',
                    )
                ),
                array(
                    'Select',
                    'fixHover',
                    array(
                        'label' => "Show photo  statistics Always or when users Mouse-over on photo / album blocks (this setting will work only if you choose to show information inside the Photo  block.)",
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
                        'label' => "Choose from below the details that you want to show in this widget.",
                        'multiOptions' => array(
                            'like' => 'Likes Count',
                            'comment' => 'Comments Count',
                            'rating' => 'Rating Stars',
                            'view' => 'Views Count',
                            'title' => 'Photo / Album Title',
                            'by' => 'Owner\'s Name',
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'downloadCount' => 'Downloads Count',
                            'likeButton' => 'Like Button',
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
                        'label' => 'Photo  title truncation limit.',
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
                        'label' => 'Enter the height of one photo  block (in pixels).',
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
                        'label' => 'Enter the width of one photo  block (in pixels).',
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
                        'label' => 'Count (number of photo  to show.)',
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
        'title' => 'SNS - Advanced Videos & Channels - People favourite Channel / Video',
        'description' => 'Placed on  a video\'s / channel\'s view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.people-favourite-item',
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
        'title' => 'SNS - Advanced Videos & Channels - People like Channel / Video',
        'description' => 'Placed on  a video\'s / channel\'s view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.people-like-item',
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
        'title' => 'SNS - Advanced Videos & Channels - Video Location Page',
        'description' => 'Displays a video\'s location.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.video-location',
				'autoEdit' => true,
    		'adminForm' => 'Sesvideo_Form_Admin_Location',
    ),
		array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Follow Button',
        'description' => 'Displays a channel follow button placed on channel view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.channel-follow', 
    ),
		array(
        'title' => 'SNS - Advanced Videos & Channels - Channel Cover Photo',
        'description' => 'Displays a channel cover photo placed on channel view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.chanel-cover',
				'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
						 array(
                    'Select',
                    'photo',
                    array(
                        'label' => "Do you want to show Channel’s Profile or Owner photo in this widget.",
                        'multiOptions' => array(
                            'pPhoto' => 'Profile Photo',
                            'oPhoto' => 'Owner Photo',
                        ),
                        'value' => 'pPhoto',
                    )
                ),
							array(
                    'Select',
                    'tab',
                    array(
                        'label' => "Place Tab ?",
                        'multiOptions' => array(
                            'inside' => 'Inside Cover Container',
                            'outside' => 'Outside Cover Container',
                        ),
                        'value' => 'pPhoto',
                    )
                ),								
//                 array(
//                     'Select',
//                     'is_fullwidth',
//                     array(
//                         'label' => "Want to show this widget in full width?",
//                         'multiOptions' => array(
//                             '1' => 'Yes, want to show this widget in full width',
//                             '0' => 'No, don\'t want to show this widget in full width',
//                         ),
//                         'value' => '1',
//                     )
//                 ),
// 								array(
//                     'Text',
//                     'height_cover',
//                     array(
//                         'label' => 'Height of cover this widget(px)?',
//                         'value' => 300,
//                         'validators' => array(
//                             array('Int', true),
//                             array('GreaterThan', true, array(0)),
//                         )
//                     )
//                 ),
                array(
												'MultiCheckbox',
												'option',
												array(
														'label' => "Choose options to be shown on Cover Photo in this widget.",
														'multiOptions' => array(
																'report' => 'Report Button',
																'follow' => 'Follow Button',
																'like' => 'Like Button',
																'share' => 'Share Button',
																'delete' => 'Delete Button',
																'edit' => 'Edit Button',
																'favourite' => 'Follow Button',
																'rating' => 'Rating Stars',
																'stats'=>'Stat Counts',
																'verified' =>'Verified Button',
                                                                'addVideo' =>'Add video Button',
														),
												)
										),
						   ),
				 ),
    ),
		array(
        'title' => 'SNS - Advanced Videos & Channels - Channel / Playlist / Artist  Advance Share Widget',
        'description' => 'Placed on view page',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.advance-share',
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
																
														),
												)
										),
						   ),
				 ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video View Page',
        'description' => 'Displays a video\'s view page.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.video-view-page',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'advSearchOptions',
                    array(
                        'label' => "Choose options to be shown in this widget.",
                        'multiOptions' => array(
                            'likeCount' => 'Likes Count',
                            'viewCount' => 'Views Count',
                            'commentCount' => 'Comments Count',
                            'favouriteButton' => 'Favourite Button',
                            'addToPlaylist' => 'Add To Playlist',
                            'watchLater' => 'Watch Later Button',
                            'favouriteCount' => 'Favourites Count',
                            'rateCount' => 'Rating Stars',
                            'openVideoLightbox' => 'Video Lightbox Button',
                            'editVideo' => 'Edit Button',
                            'deleteVideo' => 'Delete Button',
                            'embedVideo' => 'Embed Button',
                            'shareSimple' => 'Simple Share Button',
                            'shareAdvance' => 'Advance Share Button',
                            'reportVideo' => 'Report Button',
														'peopleLike' => 'User like this video',
														'favourite' => 'Show Favourite',
														'comment' =>'Show Comments',
														'artist' =>'Show Artists',
                        ),
                    )
                ),
								array(
                    'Select',
                    'autoplay',
                    array(
                        'label' => "Autoplay Video ?",
                        'multiOptions' => array(
                            '1' => 'Yes,autoplay video',
                            '0' => 'No,don\'t autoplay video',
                        ),
                        'value' => '0',
                    )
                ),
								 array(
                    'Text',
                    'likelimit_data',
                    array(
                        'label' => 'Show view more for user like after how much data?',
                        'value' => 11,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
								array(
                    'Text',
                    'favouritelimit_data',
                    array(
                        'label' => 'Show view more for show favourite after how much data?',
                        'value' => 11,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'MultiCheckbox',
                    'advShareOptions',
                    array(
                        'label' => "Choose options to be shown in Advance Share in this widget.",
                        'multiOptions' => array(
                            'privateMessage' => 'Private Message',
                            'siteShare' => 'Site Share',
                            'quickShare' => 'Quick Share',
                            
                            'embed' => 'Embed Code',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Advanced Videos & Channels - Video/Channels Browse Search',
        'description' => 'Displays a search form in the video / channel  browse page as placed by you. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'name' => 'sesvideo.browse-search',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'search_for',
                    array(
                        'label' => "Choose the content for which results will be shown.",
                        'multiOptions' => array(
                            'video' => 'Videos',
                            'chanel' => 'Channels',
                        ),
                        'value' => 'video',
                    )
                ),
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
                            'mostSPrated' => 'Most Rated',
                            'mostSPfavourite' => 'Most Favourite',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'verified' => 'Only Verified',
                            'hot' => 'Only Hot'
                        ),
                    )
                ),
                array(
                    'Select',
                    'default_search_type',
                    array(
                        'label' => "Default \'Browse By\' search fields.",
                        'multiOptions' => array(
                            'recentlySPcreated' => 'Recently Created',
                            'mostSPviewed' => 'Most Viewed',
                            'mostSPliked' => 'Most Liked',
                            'mostSPcommented' => 'Most Commented',
                            'mostSPrated' => 'Most Rated',
                            'mostSPfavourite' => 'Most Favourite',
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'hot' => 'Only Hot'
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
                        'label' => "Show \'Search Videos or Channels or Playlists or Artists /Keyword\' search field?",
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
                $milesData,
                
                array(
                    'Radio',
                    'price',
                    array(
                        'label' => "Show \'Price\' search field? (This setting is dependent on 'Advanced Videos & Channel - Sell Extension' and require this plugin to be installed and enabled on your site.)",
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
        'title' => 'SNS - Advanced Videos & Channels - Popularity Videos Widget',
        'description' => 'Displays a videos according to popularity.',
        'category' => 'SNS - Advanced Videos & Channels Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesvideo.popularity-videos',
        'adminForm' => array(
        'elements' => array(
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'is_featured' => 'Only Featured',
                            'is_sponsored' => 'Only Sponsored',
                            'is_hot' => 'Only Hot',
                            'view_count' => 'Most Viewed',
														'like_count' => 'Most Liked',
                            'creation_date' => 'Most Recent',
                            'modified_date' => 'Recently Updated',
                            'favourite_count' => "Most Favorite",
                        ),
                        'value' => 'creation_date',
                    )
                ),
							  array(
                    'Text',
                    'textVideo',
                    array(
                        'label' => 'Text Heading For Videos.',
                        'value' => 'Videos we love',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'show_criteria',
                    array(
                        'label' => "Data show in videos ?",
                        'multiOptions' => array(
                            'featuredLabel' => 'Featured Label',
                            'sponsoredLabel' => 'Sponsored Label',
														'hotLabel'=>'Hot Label',
                            'like' => 'Likes',
                            'comment' => 'Comments',
                            'rating' => 'Ratings',
														'favourite'=>'Favourite',
                            'view' => 'Views',
                            'title' => 'Title',
                            'by' => 'Item Owner Name',
														'watchnow' =>'Watch Now Button'
                        ),
                    )
                ),
                array(
							    'Radio',
							    'pagging',
							    array(
						        'label' => "Do you want the videos to be auto-loaded when users scroll down the page?",
						        'multiOptions' => array(
						            'button' => 'View more',
						            'auto_load' => 'Auto Load',
						            'pagging' => 'Pagination',
						            'fixedbutton' => 'Fixed Data'
						        ),
						        'value' => 'fixedbutton',
							    )
								),
                array(
                    'Text',
                    'video_limit',
                    array(
                        'label' => 'count (number of videos to show).',
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
                        'label' => 'Enter the height of one block (in pixels,this setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels,this setting will effect after 3 designer blocks).',
                        'value' => '160px',
                    )
                ),
            ),
            ),
        ),
    
 );
?>
