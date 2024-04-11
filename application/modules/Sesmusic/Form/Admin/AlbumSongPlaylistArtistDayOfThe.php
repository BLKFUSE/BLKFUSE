<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AlbumSongPlaylistArtistDayOfThe.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Form_Admin_AlbumSongPlaylistArtistDayOfThe extends Engine_Form {

  public function init() {

    $this->setTitle('Album / Song / Artist of the Day')
            ->setDescription('Displays the Album / Song / Artist of The day as selected by the admin from the edit setting of this widget.');

    $this->addElement('Select', 'contentType', array(
        'label' => 'Choose content type to be shown in this widget.',
        'multiOptions' => array(
            'album' => 'Music Album',
            'albumsong' => 'Album Song',
            'artist' => 'Artist',
            'playlist' => 'Playlist',
        ),
        'value' => 'album',
    ));

    $this->addElement('MultiCheckbox', 'information', array(
        'label' => 'Choose the options that you want to be displayed in this widget.',
        'multiOptions' => array(
            "featured" => "Featured Label",
            "sponsored" => "Sponsored Label",
            "hot" => "Hot Label",
            "likeCount" => "Likes Count [This will only show for 'Music Album' and 'Songs' content types]",
            "commentCount" => "Comments Count [This will only show for 'Music Album' and 'Songs' content types]",
            "viewCount" => "Views Count",
            "ratingCount" => "Rating Stars",
            "title" => "Content Title",
            "postedby" => "Content Owner's Name",
            "songsCount" => "Songs Count [This will only show for 'Music Album' content type]",
            "favouriteCount" => "Favorite Count",
            "downloadCount" => "Downloaded Count [This will only show for 'Album Song' content type]",
            "songsListShow" => "Show songs of each playlist",
            'socialSharing' => 'Social Share Button [This will only show for "Music Album" and "Songs" content types] <a class="smoothbox" href="'._ENGINE_SITE_URL.'/admin/sesbasic/settings/faqwidget">[FAQ]</a> ',
            "addLikeButton" => "Like Button [This will only show for 'Music Album' and 'Songs' content types]",
            "addFavouriteButton" => "Add to Favorite Button [This will only show for 'Music Album' and 'Songs' content types]",
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
    
    
  }

}
