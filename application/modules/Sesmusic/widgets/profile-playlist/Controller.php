<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Widget_ProfilePlaylistController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $playlist_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('playlist_id');

    $this->view->playlist = $playlist = Engine_Api::_()->getItem('sesmusic_playlist', $playlist_id);
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();
    
    //Get viewer/subject
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    
    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->informationPlaylist = $this->_getParam('informationPlaylist', array('editButton', 'deleteButton', "favouriteCountAr", "viewCountAr", "description", "ratingStarsAr"));

    $this->view->information = $this->_getParam('information', array('postedBy', 'creationDate', 'commentCount', 'viewCount', 'likeCount', 'ratingCount', 'favouriteCount', 'playCount', 'ratingStars', 'playButton', 'editButton', 'deleteButton', 'addplaylist', 'share', 'report', 'downloadButton', 'addFavouriteButton', "printButton", 'photo', 'category', "favouriteCountAr", "viewCountAr", "description", "ratingStarsAr"));

    //Songs settings.
    $this->view->songlink = unserialize($settings->getSetting('sesmusic.songlink'));

    $this->view->canAddPlaylistAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_song');

    $this->view->downloadAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'download_song');

    $this->view->canAddFavouriteAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_song');

    $allowShowRating = $settings->getSetting('sesmusic.ratealbumsong.show', 1);
    $allowRating = $settings->getSetting('sesmusic.albumsong.rating', 1);
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    }
    else
      $showRating = true;
    $this->view->showAlbumSongRating = $showRating;
  }

}