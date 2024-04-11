<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php 2015-03-30 00:00:00 SocialEngineSolutions $
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

 
$songPopularityParameters = array(
    'Select',
    'popularity',
    array(
        'label' => 'Popularity Criteria',
        'multiOptions' => array(
            'featured' => 'Only Featured',
            'sponsored' => 'Only Sponsored',
            'hot' => 'Only Hot',
            'upcoming' => 'Only Latest',
            'bothfesp' => 'Both Featured and Sponsored',
            'view_count' => 'Most Viewed',
            'like_count' => 'Most Liked',
            'comment_count' => 'Most Commented',
            'download_count' => 'Most Downloaded',
            "play_count" => "Most Played",
            'favourite_count' => 'Most Favorite',
            'creation_date' => 'Most Recent',
            'rating' => 'Most Rated',
            'modified_date' => 'Recently Updated',
        ),
        'value' => 'creation_date',
    )
);

$artistsPopularityParameters = array(
    'Select',
    'popularity',
    array(
        'label' => 'Popularity Criteria',
        'multiOptions' => array(
            'favourite_count' => 'Most Favorite',
            'rating' => 'Most Rated',
        //'song_count' => "Top Songs (Means here accociate with songs)",
        ),
        'value' => 'favourite_count',
    )
);

$albumPopularityParameters = array(
    'Select',
    'popularity',
    array(
        'label' => 'Popularity Criteria',
        'multiOptions' => array(
            'featured' => 'Only Featured',
            'sponsored' => 'Only Sponsored',
            'hot' => 'Only Hot',
            'upcoming' => 'Only Latest',
            'bothfesp' => 'Both Featured and Sponsored',
            'view_count' => 'Most Viewed',
            'like_count' => 'Most Liked',
            'comment_count' => 'Most Commented',
            'favourite_count' => 'Most Favorite',
            'creation_date' => 'Most Recent',
            'rating' => 'Most Rated',
            'modified_date' => 'Recently Updated',
            'song_count' => "Maximum Songs",
        ),
        'value' => 'creation_date',
    )
);

$view_type = array(
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
);

$limit = array(
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
);

$limit = array(
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
);

//Song information show
$songStats = array(
    'MultiCheckbox',
    'information',
    array(
        'label' => 'Choose the options that you want to be displayed in this widget.',
        'multiOptions' => array(
            "featured" => "Featured Label",
            "sponsored" => "Sponsored Label",
            "hot" => "Hot Label",
            "likeCount" => "Likes Count",
            "commentCount" => "Comments Count",
            "viewCount" => "Views Count",
            "downloadCount" => "Downloaded Count",
            "playCount" => "Plays Count",
            "ratingCount" => "Rating Stars",
            "title" => "Album Title",
            "postedby" => "Song Owner's Name",
            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a> [Support only in grid view]',
            "addLikeButton" => "Like Icon on Mouse Over [Support only in grid view]",
            "favourite" => "Add to Favorite Icon on Mouse Over [Support only in grid view]",
        ), 
        'escape' => false,
    ),
);


//Album information show
$AlbumStats = array(
    'MultiCheckbox',
    'information',
    array(
        'label' => 'Choose the options that you want to be displayed in this widget.',
        'multiOptions' => array(
            "featured" => "Featured Label",
            "sponsored" => "Sponsored Label",
            "hot" => "Hot Label",
            "likeCount" => "Likes Count",
            "commentCount" => "Comments Count",
            "viewCount" => "Views Count",
            "songsCount" => "Songs Count",
            "ratingCount" => "Rating Stars",
            "title" => "Music Album Title [For Grid View Only]",
            "postedby" => "Music Albums Owner's Name",
            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
            "addLikeButton" => "Like Icon on Mouse Over",
            "favourite" => "Add to Favorite Icon on Mouse Over",
        ),
        'escape' => false,
    ),
);

$height = array(
    'Text',
    'height',
    array(
        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
        'value' => 200,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ),
);

$width = array(
    'Text',
    'width',
    array(
        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
        'value' => 200,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ),
);


$show_photo = array(
    'Select',
    'showPhoto',
    array(
        'label' => 'Do you want to show only those music albums which have main photos?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => '0'
    )
);

return array(
    array(
        'title' => 'SNS - Professional Music - Playlist Details & Options',
        'description' => 'This widget displays playlist details and various options. The recommended page for this widget is "SNS - Professional Music - Playlist View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.profile-playlist',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'informationPlaylist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Playlist" shown in this widget.',
                        'multiOptions' => array(
                            "editButton" => "Edit Button",
                            "deleteButton" => "Delete Button",
                            "sharePl" => "Share Button",
                            "reportPl" => "Report Button",
                            "addFavouriteButtonPl" => "Add to Favorite Button",
                            "viewCountPl" => "Views Count",
                            "favouriteCountPl" => "Favourite Count",
                            "description" => "Description",
                            "postedByPl" => "Playlist Owner\'s Name Name",
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Songs" shown in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Song Owner\'s Name",
                            "downloadCount" => "Download Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorite Count",
                            "playCount" => "Play Count",
                            "addplaylist" => "Add to Playlist Button",
                            "share" => "Share Button",
                            "report" => "Report Button",
                            "addFavouriteButton" => "Add to Favorite Button",
                            "downloadButton" => "Download Button",
                            "artists" => "Artists",
                            "category" => "Category",
                            'storeLink' => "Store Link",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Artist Details & Options',
        'description' => 'This widget displays artist details and various options. The recommended page for this widget is "SNS - Professional Music - Artist View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.profile-artist',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'informationArtist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Artist" shown in this widget.',
                        'multiOptions' => array(
                            "featuredArtist" => "Featured Label",
                            "sponsoredArtist" => "Sponsored Label",
                            "favouriteCountAr" => "Favorite Count",
                            "ratingCountAr" => "Rating Count",
                            "description" => "Description",
                            "ratingStarsAr" => "Rating Stars",
                            "addFavouriteButtonAr" => "Add to Favorite Button",
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Songs" shown in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Song Owner\'s Name",
                            "downloadCount" => "Download Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorite Count",
                            "playCount" => "Play Count",
                            "addplaylist" => "Add to Playlist Button",
                            "share" => "Share Button",
                            "report" => "Report Button",
                            "downloadButton" => "Download Button",
                            "artists" => "Artists",
                            "addFavouriteButton" => "Add to Favorite Button",
                            "category" => "Category",
                            "storeLink" => "Store Link",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Browse Lyrics',
        'description' => 'Displays all songs on your website. The recommended page for this widget is "SNS - Professional Music - Browse Lyrics Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-lyrics',
        'adminForm' => array(
            'elements' => array(
                $view_type,
                array(
                    'Select',
                    'Type',
                    array(
                        'label' => 'Do you want the songs to be auto-loaded when users scroll down the page?',
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
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "playCount" => "Play Count",
                            "downloadCount" => "Download Count",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "favouriteCount" => "Favorite Count",
                            "ratingStars" => "Rating Stars",
                            "artists" => "Artists",
                            "addplaylist" => "Add to Playlist",
                            "downloadIcon" => "Download",
                            "share" => "Share",
                            "report" => "Report",
                            "title" => "Song Title",
                            "postedby" => "Song Owner's Name",
                            "favourite" => "Add to Favorite",
                            "category" => "Category / 2nd-level category/ 3rd-level category",
                            "storeLink" => "Store Link",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of songs to show)',
                        'value' => 10,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                $height,
                $width,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Favorite / Liked / Rated Songs for Manage Pages',
        'description' => 'This widget displays favorite, liked or song rated by the member on respective manage pages. Edit this widget to choose the page on which you want to place this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.manage-album-songs',
        'adminForm' => array(
            'elements' => array(
                $view_type,
                array(
                    'Select',
                    'Type',
                    array(
                        'label' => 'Do you want the songs to be auto-loaded when users scroll down the page?',
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
                          "featured" => "Featured Label",
                          "sponsored" => "Sponsored Label",
                          "hot" => "Hot Label",
                          "playCount" => "Play Count",
                          "downloadCount" => "Download Count",
                          "likeCount" => "Likes Count",
                          "commentCount" => "Comments Count",
                          "viewCount" => "Views Count",
                          "favouriteCount" => "Favorite Count",
                          "ratingStars" => "Rating Stars",
                          "artists" => "Artists",
                          "addplaylist" => "Add to Playlist",
                          "downloadIcon" => "Download",
                          "share" => "Share",
                          "report" => "Report",
                          "title" => "Song Title",
                          "postedby" => "Song Owner's Name",
                          "favourite" => "Add to Favorite Icon on Mouse Over",
                          "category" => "Category / 2nd-level category/ 3rd-level category",
                          'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                          "addLikeButton" => "Like Icon on Mouse Over",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of songs to show)',
                        'value' => 10,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                ),
                $height,
                $width,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Favorite / Liked / Rated Music Albums for Manage Pages',
        'description' => 'This widget displays favorite, liked or music albums rated by the member on respective manage pages. Edit this widget to choose the page on which you want to place this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.manage-music-albums',
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
                    'Select',
                    'Type',
                    array(
                        'label' => 'Do you want the music albums to be auto-loaded when users scroll down the page?',
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
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorite Count [for List View only]",
                            "ratingCount" => "Rating Count [for List View only]",
                            "category" => "Category / 2nd-level category/ 3rd-level category [for List View only]",
                            "description" => "Description [for List View only]",
                            "songCount" => "Songs Count",
                            "title" => "Music Album Title",
                            "postedby" => "Music Album Owner's Name",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'showSongsList' => "Show songs of each music album [for List View only]",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Icon on Mouse Over",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
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
                        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
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
                        'label' => 'Count (number of music albums to show)',
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
        'title' => 'SNS - Professional Music - Popular Playlists Carousel',
        'description' => 'Displays playlists based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.popular-playlists',
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
                            'other' => 'Member’s Other Playlists [With this option, place this widget on SNS - Professional Music - Playlist View Page.]',
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
                            'creation_date' => 'Most Recent',
                            'modified_date' => 'Recently Updated',
                            'favourite_count' => "Most Favorite",
                            'song_count' => "Maximum Songs",
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
                            "favouriteCount" => "Favorite Count",
                            "songCount" => "Songs Count",
                            "songsListShow" => "Songs of each Playlist"
                        ),
                    )
                ),
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
                $width,
                array(
                    'Text',
                    'CountToShow',
                    array(
                        'label' => 'Count  (number of content to show at one time)(Note: Set to 1 when placed in side widget)',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                $limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Recently Viewed Music Albums / Songs',
        'description' => 'This widget displays the recently viewed music albums or songs by the user who is currently viewing your website or by the logged in members friend. Edit this widget to choose whose recently viewed content will show in this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.recently-viewed-item',
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
                            'sesmusic_album' => 'Music Albums',
                            'sesmusic_albumsong' => 'Songs',
                        // 'sesmusic_artist' => 'Artists',
                        ),
                    ),
                ),
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
                    'Select',
                    'criteria',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' =>
                        array(
                            'by_me' => 'Content viewed by me',
                            'by_myfriend' => 'Content viewed by my friends',
                        //  'on_site' => 'Content View On site'
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => "Choose the options that you want to be displayed in this widget.",
                        'multiOptions' => array(
                            "hot" => "Hot Label",
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "songsCount" => "Songs Count [for Music Album]",
                            "ratingCount" => "Rating Stars",
                            "commentCount" => "Comments Count",
                            "downloadCount" => "Song Download Count [for songs]",
                            "share" => "Share Icon on Mouse-Over",
                            "postedby" => "Music Albums / Songs Owner's Name",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    )
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'Height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
                        'value' => '180',
                    )
                ),
                array(
                    'Text',
                    'Width',
                    array(
                        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
                        'value' => '180',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'count (number of content to show)',
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
      'title' => 'SNS - Professional Music - Content Profile - Profile Music',
      'description' => 'This widget enables you to allow users to create music album on different content on your website like Groups. Place this widget on the content profile page, for example SE Group to enable group owners to create music album in their Groups. You can choose the visibility of the music album created in a content to only that content or show in this plugin as well from the "Music Album Created in Content Visibility" setting in Global setting of this plugin.',
      'category' => 'SNS - Professional Music Plugin',
      'type' => 'widget',
      'name' => 'sesmusic.other-modules-profile-musicalbums',
      'autoEdit' => true,
      'defaultParams' => array(
        'titleCount' => true,
      ),
      'adminForm' => array(
        'elements' => array(
          array(
            'MultiCheckbox',
            'defaultOptionsShow',
            array(
              'label' => "Choose from below the content types that you want to show in this widget.",
              'multiOptions' => array(
                'profilemusicalbums' => 'Music Albums',
                'songofyou' => 'Songs',
                //'playlists' => 'Playlists',
                'favouriteSong' => 'Favorite Songs',
                //'favouriteArtist' => 'Favorite Artists',
              ),
            )
          ),
          array(
            'MultiCheckbox',
            'informationAlbum',
            array(
              'label' => 'Choose from below the details that you want to show for "Music Albums" shown in this widget.',
              'multiOptions' => array(
                "featured" => "Featured Label",
                "sponsored" => "Sponsored Label",
                "hot" => "Hot Label",
                "postedBy" => "Song Owner\'s Name",
                "commentCount" => "Comments Count",
                "viewCount" => "Views Count",
                "likeCount" => "Likes Count",
                "ratingStars" => "Rating Stars",
                "songCount" => "Song Count",
                "favourite" => "Favorite Icon on Mouse-Over",
                "addplaylist" => "Add Playlist Icon on Mouse-Over",
                "share" => "Share Icon on Mouse-Over",
                'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                "addLikeButton" => "Like Button",
              ),
              'escape' => false,
            ),
          ),
          $socialshare_enable_plusicon,
          $socialshare_icon_limit,
          array(
              'MultiCheckbox',
              'informationPlaylist',
              array(
                  'label' => 'Choose from below the details that you want to show for "Playlist" shown in this widget.',
                  'multiOptions' => array(
                      "sharePl" => "Share Button",
                      "addFavouriteButtonPl" => "Add to Favorite Button",
                      "viewCountPl" => "Views Count",
                      "description" => "Description",
                      "postedByPl" => "Playlist Owner\'s Name Name",
                      'showSongsList' => "Show songs of each playlist",
                  ),
              ),
          ),
          array(
              'MultiCheckbox',
              'information',
              array(
                  'label' => 'Choose from below the details that you want to show for "Songs & Favorite Songs" shown in this widget.',
                  'multiOptions' => array(
                      "featured" => "Featured Label",
                      "sponsored" => "Sponsored Label",
                      "hot" => "Hot Label",
                      "postedBy" => "Song Owner\'s Name",
                      "downloadCount" => "Download Count",
                      "commentCount" => "Comments Count",
                      "viewCount" => "Views Count",
                      "likeCount" => "Likes Count",
                      "ratingStars" => "Rating Stars",
                      "playCount" => "Play Count",
                      "favourite" => "Favorite Icon on Mouse-Over",
                      "addplaylist" => "Add Playlist Icon on Mouse-Over",
                      "share" => "Share Icon on Mouse-Over",
                      'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                      "addLikeButton" => "Like Button",
                  ),
                  'escape' => false,
              ),
          ),
          $socialshare_enable_plusicon,
          $socialshare_icon_limit,
          array(
              'MultiCheckbox',
              'informationArtist',
              array(
                  'label' => 'Choose from below the details that you want to show for "Favorite Artists" shown in this widget.',
                  'multiOptions' => array(
                      "favourite" => "Favorite Icon on Mouse-Over",
                      'favouriteCount' => 'Favorite Count',
                      'ratingCount' => 'Rating Count',
                  ),
              ),
          ),
          array(
              'Select',
              'pagging',
              array(
                  'label' => "Do you want the content to be auto-loaded when users scroll down the page?",
                  'multiOptions' => array(
                      'button' => 'No, show \'View more\'',
                      'auto_load' => 'Yes',
                  ),
                  'value' => 'auto_load',
              )
          ),
          array(
              'Text',
              'Height',
              array(
                  'label' => 'Enter the height of one block [for Grid View (in pixels)].',
                  'value' => '180',
              )
          ),
          array(
            'Text',
            'Width',
            array(
              'label' => 'Enter the width of one block [for Grid View (in pixels)].',
              'value' => '180',
            )
          ),
          array(
            'Text',
            'limit_data',
            array(
              'label' => 'count (number of content to show)',
              'value' => 3,
            )
          ),
        )
      ),
    ),
    array(
        'title' => 'SNS - Professional Music - Profile Music',
        'description' => 'Displays a member\'s music albums, songs, playlists and favorite songs on their profile. Edit this widget to choose content type to be shown. The recommended page for this widget is "Member Profile Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.profile-musicalbums',
        'autoEdit' => true,
        'defaultParams' => array(
          'titleCount' => true,
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'defaultOptionsShow',
                    array(
                        'label' => "Choose from below the content types that you want to show in this widget.",
                        'multiOptions' => array(
                            'profilemusicalbums' => 'Music Albums',
                            'songofyou' => 'Songs',
                            'playlists' => 'Playlists',
                            'favouriteSong' => 'Favorite Songs',
                            'favouriteArtist' => 'Favorite Artists',
                        ),
                    )
                ),
                array(
                    'MultiCheckbox',
                    'informationAlbum',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Music Albums" shown in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Song Owner\'s Name",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingStars" => "Rating Stars",
                            "songCount" => "Song Count",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'MultiCheckbox',
                    'informationPlaylist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Playlist" shown in this widget.',
                        'multiOptions' => array(
                            "sharePl" => "Share Button",
                            "addFavouriteButtonPl" => "Add to Favorite Button",
                            "viewCountPl" => "Views Count",
                            "description" => "Description",
                            "postedByPl" => "Playlist Owner\'s Name Name",
                            'showSongsList' => "Show songs of each playlist",
                        ),
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Songs & Favorite Songs" shown in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Song Owner\'s Name",
                            "downloadCount" => "Download Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingStars" => "Rating Stars",
                            "playCount" => "Play Count",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'MultiCheckbox',
                    'informationArtist',
                    array(
                        'label' => 'Choose from below the details that you want to show for "Favorite Artists" shown in this widget.',
                        'multiOptions' => array(
                            "favourite" => "Favorite Icon on Mouse-Over",
                            'favouriteCount' => 'Favorite Count',
                            'ratingCount' => 'Rating Count',
                        ),
                    ),
                ),
                array(
                    'Select',
                    'pagging',
                    array(
                        'label' => "Do you want the content to be auto-loaded when users scroll down the page?",
                        'multiOptions' => array(
                            'button' => 'No, show \'View more\'',
                            'auto_load' => 'Yes',
                        ),
                        'value' => 'auto_load',
                    )
                ),
                array(
                    'Text',
                    'Height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
                        'value' => '180',
                    )
                ),
                array(
                    'Text',
                    'Width',
                    array(
                        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
                        'value' => '180',
                    )
                ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'count (number of content to show)',
                        'value' => 3,
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Popular / Recommended / Related / Owner\'s Other Songs',
        'description' => 'Displays songs based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.popular-recommanded-other-related-songs',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'showType',
                    array(
                        'label' => "Show",
                        'multiOptions' => array(
                            'all' => 'Popular Songs [With this option, place this widget anywhere on your website. Choose criteria from "Popularity Criteria" setting below.]',
                            'recommanded' => 'Recommended Songs [With this option, place this widget anywhere on your website.]',
                            'other' => 'Song Owner\'s Other Albums [With this option, place this widget on SNS - Professional Music - Music Album View Page.]',
                            'related' => 'Related Songs [With this option, place this widget on SNS - Professional Music - Music Album View Page.]',
                            'artistOtherSongs' => 'Artists Other Songs [With this option, place this widget on SNS - Professional Music - Artist View Page.]',
                            'otherSongView' => 'Other Songs of associated Music Album [With this option, place this widget on SNS - Professional Music - Song View Page.]',
                        ),
                        'value' => 'all',
                    ),
                ),
                $view_type,
                $songPopularityParameters,
                $songStats,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $height,
                $width,
                $limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Popular / Recommended / Related / Owner\'s Other Music Albums',
        'description' => 'Displays music albums based on chosen criteria for this widget. The placement of this widget depends on the criteria chosen for this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.popular-recommanded-other-related-albums',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'showType',
                    array(
                        'label' => "Display",
                        'multiOptions' => array(
                            'all' => 'Popular Albums [With this option, place this widget anywhere on your website. Choose criteria from "Popularity Criteria" setting below.]',
                            'recommanded' => 'Recommended Albums [With this option, place this widget anywhere on your website.]',
                            'other' => 'Music Album Owner\'s Other Albums [With this option, place this widget on SNS - Professional Music - Music Album View Page.]',
                            'related' => 'Related Albums [With this option, place this widget on SNS - Professional Music - Music Album View Page.]',
                        ),
                        'value' => 'all',
                    ),
                ),
                $albumPopularityParameters,
                $view_type,
                $show_photo,
                $AlbumStats,
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                $height,
                $width,
                $limit,
            )
        ),
    ),
//     array(
//         'title' => 'SNS - Professional Music - My Profile Playlist',
//         'description' => 'This widget enables members of your website to choose a playlist for their profile this will be highlighted as Profile Playlist. The recommended page for this widget is right / left column of "Member Profile" page.',
//         'category' => 'SNS - Professional Music Plugin',
//         'type' => 'widget',
//         'name' => 'sesmusic.profile-myplaylist',
//         'autoEdit' => true,
//         'adminForm' => array(
//             'elements' => array(
//                 array(
//                     'MultiCheckbox',
//                     'information',
//                     array(
//                         'label' => "Choose the options that you want to be displayed in this widget.",
//                         'multiOptions' => array(
//                             "postedby" => "Playlist Owner's Name",
//                             "viewCount" => "Views Count",
//                             "favouriteCount" => "Favorite Count",
//                             "songsListShow" => "Show songs",
//                             "songCount" => "Songs Count",
//                         ),
//                     )
//                 ),
//             )
//         ),
//     ),
    array(
        'title' => 'SNS - Professional Music - Profile Options for Music Album / Song',
        'description' => 'Displays a menu of actions (edit, report, add to favorite, share, etc) that can be performed on a music album / song on its profile. The recommended page for this widget is "SNS - Professional Music - Music Album View Page" / "SNS - Professional Music - Song View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.profile-options',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewPageType',
                    array(
                        'label' => "Choose Content Type.",
                        'multiOptions' => array(
                            'album' => 'Music Album',
                            'song' => 'Song',
                        ),
                        'value' => 'album',
                    ),
                ),
                array(
                    'Select',
                    'viewType',
                    array(
                        'label' => "Choose the View Type.",
                        'multiOptions' => array(
                            'horizontal' => 'Horizontal',
                            'vertical' => 'Vertical',
                        ),
                        'value' => 'vertical',
                    ),
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Song Cover',
        'description' => 'This widget displays song cover photo on Song View page. The recommended page for this widget is "SNS - Professional Music - Song View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.song-cover',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of cover photo block (in pixels).',
                        'value' => 400,
                    )
                ),
                array(
                    'Text',
                    'mainPhotoHeight',
                    array(
                        'label' => 'Enter the height of Song\'s main photo (in pixels).',
                        'value' => 350,
                    )
                ),
                array(
                    'Text',
                    'mainPhotowidth',
                    array(
                        'label' => 'Enter the width of Song\'s main photo (in pixels).',
                        'value' => 350,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Song Owner\'s Name",
                            "creationDate" => "Released Date",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingCount" => "Rating Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorite Count",
                            "playCount" => "Play Count",
                            "playButton" => "Play Song Button",
                            "editButton" => "Edit Song Button",
                            "deleteButton" => "Delete Song Button",
                            "addplaylist" => "Add to Playlist Button",
                            "share" => "Share Button",
                            "report" => "Report Button",
                            "printButton" => "Print Button",
                            "downloadButton" => "Download Button",
                            "addFavouriteButton" => "Add to Favorite Button",
                            "addLikeButton" => "Like Button",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            'photo' => "Song's Main Photo [Photo will show in the right side above the song cover.]",
                            "category" => "Category / 2nd-level category/ 3rd-level category",
                            "storeLink" => "Store Link",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Music Album Cover',
        'description' => 'This widget displays music album cover photo on Music Album View page. The recommended page for this widget is "SNS - Professional Music - Music Album View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.album-cover',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of cover photo block (in pixels).',
                        'value' => 250,
                    )
                ),
                array(
                    'Text',
                    'mainPhotoHeight',
                    array(
                        'label' => 'Enter the height of Music Album\'s main photo (in pixels).',
                        'value' => 350,
                    )
                ),
                array(
                    'Text',
                    'mainPhotowidth',
                    array(
                        'label' => 'Enter the width of Music Album\'s main photo (in pixels).',
                        'value' => 350,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "postedBy" => "Music Album Owner's Name",
                            "creationDate" => "Creation Date",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "likeCount" => "Likes Count",
                            "ratingCount" => "Rating Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorites Count",
                            "songCount" => "Songs Count",
                            "description" => "Description",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            // "uploadButton" => "Upoload Button",
                            "editButton" => "Edit Music Album Button",
                            "deleteButton" => "Delete Music Album Button",
                            "addplaylist" => "Add to Playlist Button",
                            "share" => "Share Button",
                            "report" => "Report Button",
                            //"downloadButton" => "Download Button",
                            "addFavouriteButton" => "Add to Favorite Button",
                            "addLikeButton" => "Like Button",
                            'photo' => "Music Album\'s Main Photo [Photo will show in the right side above the music album cover.]",
                            "category" => "Category / 2nd-level category/ 3rd-level category",
                            'storeLink' => 'storeLink',
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Tabbed widget for Popular Songs',
        'description' => 'Displays a tabbed widget for popular songs on your website on various popularity criteria. Edit this widget to choose tabs to be shown in this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.tabbed-widget-songs',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels).',
                        'value' => '200px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels).',
                        'value' => '195px',
                    )
                ),
                array(
                    'Select',
                    'showTabType',
                    array(
                        'label' => 'Choose the design of the tabs.',
                        'multiOptions' => array(
                            '0' => 'Default SE Tabs',
                            '1' => 'SNS - Professional Music Plugin\'s Tabs'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'pagging',
                    array(
                        'label' => 'Do you want the music albums to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'search_type',
                    array(
                        'label' => "Choose the tabs which you want to be shown in this widget.",
                        'multiOptions' => array(
                            'recently1Created' => 'Recently Created',
                            'recently1Updated' => 'Recently Updated',
                            'most1Viewed' => 'Most Viewed',
                            'most1Liked' => 'Most Liked',
                            'most1Commented' => 'Most Commented',
                            'play1Count' => 'Most Played',
                            'most1Favourite' => 'Most Favorite',
                            'most1Rated' => 'Most Rated',
                            'hot' => 'Hot',
                            'upcoming' => 'Latest',
                            'most1Downloaded' => 'Most Downloaded',
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored'
                        ),
                    ),
                ),
                array(
                    'Select',
                    'default',
                    array(
                        'label' => "Choose the tab which you want to open by default.",
                        'multiOptions' => array(
                            'recently1Created' => 'Recently Created',
                            'recently1Updated' => 'Recently Updated',
                            'most1Viewed' => 'Most Viewed',
                            'most1Liked' => 'Most Liked',
                            'most1Commented' => 'Most Commented',
                            'play1Count' => 'Most Played',
                            'most1Favourite' => 'Most Favorite',
                            'most1Rated' => 'Most Rated',
                            'hot' => 'Hot',
                            'upcoming' => 'Latest',
                            'most1Downloaded' => 'Most Downloaded',
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored'
                        ),
                        'value' => 'recently1Updated',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "ratingStars" => "Rating Stars",
                            "playCount" => "Play Count",
                            "postedby" => "Song Owner's Name",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Icon on Mouse-Over",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'count (number of songs to show).',
                        'value' => '12',
                    )
                )
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Tabbed widget for Popular Music Albums',
        'description' => 'Displays a tabbed widget for popular music albums on your website on various popularity criteria. Edit this widget to choose tabs to be shown in this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.tabbed-widget',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block (in pixels).',
                        'value' => '200px',
                    )
                ),
                array(
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width of one block (in pixels).',
                        'value' => '195px',
                    )
                ),
                array(
                    'Select',
                    'showTabType',
                    array(
                        'label' => 'Choose the design of the tabs.',
                        'multiOptions' => array(
                            '0' => 'Default SE Tabs',
                            '1' => 'SNS - Professional Music Plugin\'s Tabs'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'pagging',
                    array(
                        'label' => 'Do you want the music albums to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 0,
                    )
                ),
                array(
                    'MultiCheckbox',
                    'search_type',
                    array(
                        'label' => "Choose the tabs which you want to be shown in this widget.",
                        'multiOptions' => array(
                            'recently1Created' => 'Recently Created',
                            'recently1Updated' => 'Recently Updated',
                            'most1Viewed' => 'Most Viewed',
                            'most1Liked' => 'Most Liked',
                            'most1Commented' => 'Most Commented',
                            'song1Count' => 'Maximum Songs',
                            'most1Favourite' => 'Most Favorite',
                            'most1Rated' => 'Most Rated',
                            'hot' => 'Hot',
                            'upcoming' => 'Latest',
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored'
                        ),
                    )
                ),
                array(
                    'Select',
                    'default',
                    array(
                        'label' => "Choose the tab which you want to open by default.",
                        'multiOptions' => array(
                            'recently1Created' => 'Recently Created',
                            'recently1Updated' => 'Recently Updated',
                            'most1Viewed' => 'Most Viewed',
                            'most1Liked' => 'Most Liked',
                            'most1Commented' => 'Most Commented',
                            'song1Count' => 'Maximum Songs',
                            'most1Favourite' => 'Most Favorite',
                            'most1Rated' => 'Most Rated',
                            'hot' => 'Hot',
                            'upcoming' => 'Latest',
                            'featured' => 'Featured',
                            'sponsored' => 'Sponsored'
                        ),
                        'value' => 'recently1Updated',
                    )
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose from below the details that you want to show in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "songCount" => "Songs Count",
                            "ratingStars" => "Rating Stars",
                            "title" => "Music Album Title",
                            "postedby" => "Music Album Owner's Name",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Icon on Mouse-Over",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'count (number of music albums to show).',
                        'value' => '12',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Browse Songs',
        'description' => 'Displays all songs on your website.  The recommended page for this widget is "SNS - Professional Music - Browse Songs Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-songs',
        'adminForm' => array(
            'elements' => array(
                $view_type,
                array(
                    'Select',
                    'paginationType',
                    array(
                        'label' => 'Do you want the songs to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 1,
                    )
                ),
                $songPopularityParameters,
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "playCount" => "Play Count",
                            "downloadCount" => "Download Count",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "favouriteCount" => "Favorite Count",
                            "ratingStars" => "Rating Stars",
                            "artists" => "Artists",
                            "addplaylist" => "Add to Playlist",
                            "downloadIcon" => "Download",
                            "share" => "Share",
                            "report" => "Report",
                            "title" => "Song Title",
                            "postedby" => "Song Owner's Name",
                            "favourite" => "Add to Favorite",
                            "category" => "Category / 2nd-level category/ 3rd-level category",
                            'storeLink' => "Store Link",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
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
                $height,
                $width,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Browse Playlists',
        'description' => 'Displays all playlists on your website.  The recommended page for this widget is "SNS - Professional Music - Browse Playlists Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-playlists',
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
                        'value' => 'listView',
                    )
                ),
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
                        //'song_count' => "Maximum Songs",
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
                            "viewCount" => "Views Count",
                            "favouriteCount" => "Favorite Count",
                            "title" => "Playlist Title",
                            "description" => "Description",
                            "postedby" => "Posted By",
                            "share" => "Share",
                            "favourite" => "Add to Favorite",
                            'showSongsList' => "Show songs of each playlist",
                        )
                    ),
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
                        'value' => 210,
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
                        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
                        'value' => 230,
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
        'title' => 'SNS - Professional Music - Browse Music Albums',
        'description' => 'Displays all music albums on your website.  The recommended page for this widget is "SNS - Professional Music - Browse Music Albums Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-albums',
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
                    'Select',
                    'paginationType',
                    array(
                        'label' => 'Do you want the music albums to be auto-loaded when users scroll down the page?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No, show \'View More\''
                        ),
                        'value' => 1,
                    )
                ),
                $albumPopularityParameters,
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "ratingStars" => "Rating Stars",
                            "favouriteCount" => "Favorite Count [For List View Only]",
                            "ratingCount" => "Rating Count [For List View Only]",
                            "category" => "Category / 2nd-level category/ 3rd-level category [For List View Only]",
                            "description" => "Description [For List View Only]",
                            "songCount" => "Songs Count",
                            "title" => "Music Album Title",
                            "postedby" => "Music Album Owner's Name",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            "share" => "Share Icon on Mouse-Over",
                            'showSongsList' => "Show songs of each playlist [For List View Only]",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Button",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of one block [for Grid View (in pixels)].',
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
                        'label' => 'Enter the width of one block [for Grid View (in pixels)].',
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
                        'label' => 'Count (number of music albums to show)',
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
        'title' => 'SNS - Professional Music - Browse Artists',
        'description' => 'Displays all artists on your website.  The recommended page for this widget is "SNS - Professional Music - Browse Artists Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-artists',
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
                    'Text',
                    'width',
                    array(
                        'label' => 'Enter the width for Grid View (in pixels).',
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
        'title' => 'SNS - Professional Music - Music Home No Music Albums Message',
        'description' => 'Displays a message when there is no Music Album on your website. The recommended page for this widget is "SNS - Professional Music - Music Album Home Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.music-home-error',
    ),
    array(
        'title' => 'SNS - Professional Music - SNS - Professional Music Player',
        'description' => 'Displays the music player in footer of your website. This widget should be placed in the Footer of your website only.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.player',
    ),
    array(
        'title' => 'SNS - Professional Music - Breadcrumb for Music Album / Song / Artist / Playlist View Page',
        'description' => 'Displays breadcrumb for Album / Song / Artist / Playlist. This widget should be placed on the SNS - Professional Music - View page of the selected content type.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.breadcrumb',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'viewPageType',
                    array(
                        'label' => "Choose content type.",
                        'multiOptions' => array(
                            'album' => 'Music Album',
                            'song' => 'Song',
                            'artist' => 'Artist',
                            'playlist' => 'Playlist',
                        ),
                        'value' => 'album',
                    ),
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Quick AJAX based Search',
        'description' => 'Displays a quick search box to enable users to quickly search Music Albums, Songs, Playlists, Artists of their choice.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.search',
    ),
    array(
        'title' => 'SNS - Professional Music - Albums Browse Search',
        'description' => 'Displays a search form in the music albums browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.browse-search',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'searchOptionsType',
                    array(
                        'label' => "Choose from below the searching options that you want to show in this widget.",
                        'multiOptions' => array(
                            'searchBox' => 'Search Music Album',
                            'category' => 'Category',
                            'view' => 'View',
                            'show' => 'List By',
                            'artists' => 'By Artists',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Songs Browse Search',
        'description' => 'Displays a search form in the songs browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.songs-browse-search',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'searchOptionsType',
                    array(
                        'label' => "Choose from below the searching options that you want to show in this widget.",
                        'multiOptions' => array(
                            'searchBox' => 'Search Song',
                            'category' => 'Category',
                            'show' => 'List By',
                            'artists' => 'By Artists',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Alphabetic Filtering of Music Albums / Songs / Playlists',
        'description' => "This widget displays all the alphabets for alphabetic filtering of music albums / songs / playlists which will enable users to filter content on the basis of selected alphabet.",
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.album-songs-alphabet',
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
                            'albums' => 'Music Albums',
                            'songs' => 'Songs',
                            'playlists' => 'Playlists',
                        ),
                        'value' => 'albums',
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Music Navigation Menu',
        'description' => 'Displays a navigation menu bar in the SNS - Professional Music plugin\'s pages for Music Home, Browse Music Albums, Browse Songs, Browse Artists, Brwose Playlists, My Music, etc pages.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-menu',
        'requirements' => array(
            'no-subject',
        ),
		'adminForm' => array(
		  'elements' => array (
			array(
			  'Radio',
			  'createButton',
			  array(
				'label' => "Enable New Music Album Button? Note: You can  disable 'New Music Album' menu from menu editor if you don't want it twice in navigation menu on your website.",
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
        'title' => 'SNS - Professional Music - Links to My Content',
        'description' => 'Displays links to the content of the user currently viewing this widget like My Playlists, My Rated Albums, etc. Place this widget on the Manage pages of this plugin in right / left column.',
        'autoEdit' => true,
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.favourites-link',
        'adminForm' => array(
            'elements' => array(
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => "Choose from below the options that you want to show in this widget.",
                        'multiOptions' => array(
                            'favAlbums' => 'Favorite Music Albums',
                            'ratedAlbums' => 'Rated Music Albums',
                            'likedAlbums' => 'Liked Music Albums',
                            'favSongs' => 'Favorite Songs',
                            'ratedSongs' => 'Rated Songs',
                            'likedSongs' => 'Liked Songs',
                            'favArtists' => 'Favorite Artists',
                            'playlists' => 'Playlists',
                        ),
                    )
                ),
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Create New Music Album Link',
        'description' => 'Displays a link to create new music album.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.browse-menu-quick',
        'requirements' => array(
            'no-subject',
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Popular Artists',
        'description' => 'Displays artists based on chosen criteria for this widget. Edit this widget to choose various settings.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.popular-artists',
        'adminForm' => array(
            'elements' => array(
                $artistsPopularityParameters,
                $view_type,
                $height,
                $width,
                $limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Music Albums / Songs Categories',
        'description' => 'Displays all categories of music albums / songs in category level hierarchy view or cloud view as chosen by you. Edit this widget to choose the view type and various other settings.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'name' => 'sesmusic.category',
        'autoEdit' => true,
        'adminForm' => 'Sesmusic_Form_Admin_Tagcloudcategory',
    ),
    array(
        'title' => 'SNS - Professional Music - Album / Song / Artist of the Day',
        'description' => 'This widget displays music album / song / artist of the day as choosen by you from the Edit setting of this widget.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.album-song-playlist-artist-day-of-the',
        'adminForm' => 'Sesmusic_Form_Admin_AlbumSongPlaylistArtistDayOfThe',
        'defaultParams' => array(
            'title' => 'Album of the Day',
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Featured, Sponsored and Hot Music Albums / Songs Carousel',
        'description' => "Disaplys Featured, Sponsored or Hot Carousel of songs / music albums.",
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.featured-sponsored-hot-carousel',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'contentType',
                    array(
                        'label' => "Which content do you want to show on this widget?",
                        'multiOptions' => array(
                            'albums' => 'Music Albums',
                            'songs' => 'Songs',
                        ),
                        'value' => 'albums',
                    )
                ),
                array(
                    'Select',
                    'popularity',
                    array(
                        'label' => 'Popularity Criteria',
                        'multiOptions' => array(
                            'view_count' => 'Most Viewed',
                            'like_count' => 'Most Liked',
                            'comment_count' => 'Most Commented',
                            'favourite_count' => 'Most Favorite',
                            'creation_date' => 'Most Recent',
                            'rating' => 'Most Rated',
                            'modified_date' => 'Recently Updated',
                            'song_count' => "Maximum Songs",
                        ),
                        'value' => 'creation_date',
                    )
                ),
                array(
                    'Select',
                    'displayContentType',
                    array(
                        'label' => "Display Content",
                        'multiOptions' => array(
                            'featured' => 'Only Featured',
                            'sponsored' => 'Only Sponsored',
                            'hot' => 'Only Hot',
                            'upcoming' => 'Only Latest',
                            'feaspo' => 'Both Featured and Sponsored',
                            'hotlat' => 'Both Hot and Latest',
                        ),
                        'value' => 'featured',
                    ),
                ),
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "songsCount" => "Songs Count",
                            "ratingCount" => "Rating Stars",
                            "downloadCount" => "Downloaded Count [Only For Songs]",
                            "playCount" => "Plays Count [Only For Songs]",
                            "title" => "Music Album / Song Title",
                            "postedby" => "Music Albums / Song Owner's Name",
                            "share" => "Share Icon on Mouse-Over",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                            'socialSharing' => 'Social Share Buttons <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a>',
                            "addLikeButton" => "Like Icon on Mouse-Over",
                        ),
                        'escape' => false,
                    ),
                ),
                $socialshare_enable_plusicon,
                $socialshare_icon_limit,
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of image [in pixels].',
                        'value' => 200,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                 array(
                    'Text',
                    'CountToShow',
                    array(
                        'label' => 'Count  (number of content to show at one time) (Note: Set to 1 when placed in side widget)',
                        'value' => 3,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
                
                $limit,
            )
        ),
    ),
    array(
        'title' => 'SNS - Professional Music - Liked Music Album / Song by Members',
        'description' => 'Displays a list of members (you can choose to show all members or friend of member viewing the content) who liked the content on which the widget is placed. The recommended page for this widget is "SNS - Professional Music - Music Album View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.albums-songs-like',
        'defaultParams' => array(
            'title' => '',
        ),
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'contentType',
                    array(
                        'label' => "Choose the content type of the associated view page on which this widget is placed.",
                        'multiOptions' => array(
                            'albums' => 'Music Albums',
                            'songs' => 'Songs',
                        ),
                        'value' => 'albums',
                    )
                ),
                array(
                    'Select',
                    'showUsers',
                    array(
                        'label' => "Who all members do you want to show in this widget?",
                        'multiOptions' => array(
                            'all' => 'All Members',
                            'friends' => 'Friends of the member viewing the content.',
                        ),
                        'value' => 'all',
                    )
                ),
                array(
                    'Select',
                    'showViewType',
                    array(
                        'label' => 'Choose the View Type.',
                        'multiOptions' => array(
                            '1' => 'List View [member\'s photo with names will show]',
                            '0' => 'Grid View [only member\'s photo will show]'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of members to show)',
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
        'title' => 'SNS - Professional Music - You May Also Like Music Albums',
        'description' => 'This widget display those music albums which the viewer may also Like.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.you-may-also-like-album-songs',
        'defaultParams' => array(
            'title' => '',
        ),

        'adminForm' => array(
            'elements' => array(
                $show_photo,
                array(
                    'MultiCheckbox',
                    'information',
                    array(
                        'label' => 'Choose the options that you want to be displayed in the List and Grid View.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "ratingCount" => "Rating Stars",
                            "songCount" => "Songs Count",
                            "title" => "Music Album Title",
                            "postedby" => "Music Album Owner’s Name"
                        )
                    ),
                ),
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
                $height,
                $width,
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of content to show)',
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
        'title' => 'SNS - Professional Music - You May Also Like Songs',
        'description' => 'This widget display those songs which the viewer may also Like.',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.you-may-also-like-songs',
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
                        'label' => 'Choose the options that you want to be displayed in this widget.',
                        'multiOptions' => array(
                            "featured" => "Featured Label",
                            "sponsored" => "Sponsored Label",
                            "hot" => "Hot Label",
                            "likeCount" => "Likes Count",
                            "commentCount" => "Comments Count",
                            "viewCount" => "Views Count",
                            "ratingCount" => "Rating Stars",
                            "downloadCount" => "Downloaded Count",
                            "playCount" => "Plays Count",
                            "title" => "Song Title",
                            "postedby" => "Song Owner's Name",
                            "share" => "Share Icon on Mouse-Over",
                            "favourite" => "Favorite Icon on Mouse-Over",
                            "addplaylist" => "Add to Playlist Icon on Mouse-Over",
                        )
                    ),
                ),
                $height,
                $width,
                array(
                    'Text',
                    'itemCount',
                    array(
                        'label' => 'Count (number of content to show)',
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
        'title' => "SNS - Professional Music - Owner's Photo",
        'description' => 'This widget display on "SNS - Professional Music - Music Album View Page", "SNS - Professional Music - Song View Page" and "SNS - Professional Music - Playlist View Page".',
        'category' => 'SNS - Professional Music Plugin',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesmusic.owner-photo',
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
        'title' => 'SNS - Professional Music - Playlist Browse Search',
        'description' => 'Displays a search form in the playlist browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.playlist-browse-search',
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
        'title' => 'SNS - Professional Music - Artist Browse Search',
        'description' => 'Displays a search form in the artist browse page. Edit this widget to choose the search option to be shown in the search form.',
        'category' => 'SNS - Professional Music Plugin',
        'autoEdit' => true,
        'type' => 'widget',
        'name' => 'sesmusic.artist-browse-search',
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
      'title' => 'SNS - Professional Music - Description',
      'description' => '.',
      'category' => 'SNS - Professional Music Plugin',
      'type' => 'widget',
      'autoEdit' => true,
      'name' => 'sesmusic.description',
    ),
    array(
      'title' => 'SNS - Professional Music - Create New Album Button',
      'description' => '.',
      'category' => 'SNS - Professional Music Plugin',
      'type' => 'widget',
      'autoEdit' => true,
      'name' => 'sesmusic.new-button',
    ),
);
