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
class Sesmusic_Widget_PopularRecommandedOtherRelatedSongsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $coreApi = Engine_Api::_()->core();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();

    $showType = $this->_getParam('showType', 'all');

    if ($showType == 'other') {

      $albumSong = $coreApi->getSubject('sesmusic_albumsong');
      if (!$albumSong)
        return $this->setNoRender();
    } elseif ($showType == 'related') {

      $albumsong = $coreApi->getSubject('sesmusic_albumsong');
      if (!$albumsong)
        return $this->setNoRender();

      $album = Engine_Api::_()->getItem('sesmusic_album', $albumsong->album_id);
      if (!$album)
        return $this->setNoRender();

      if (!$album->category_id)
        return $this->setNoRender();
    } elseif ($showType == 'artistOtherSongs') {
      $artist_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('artist_id');

      $artist = Engine_Api::_()->getItem('sesmusic_artists', $artist_id);
      if (!$artist)
        return $this->setNoRender();
    } elseif ($showType == 'otherSongView') {
      $albumsong = $coreApi->getSubject('sesmusic_albumsong');
      if (!$albumsong)
        return $this->setNoRender();

      $album = Engine_Api::_()->getItem('sesmusic_album', $albumsong->album_id);
      if (!$album)
        return $this->setNoRender();
    }

    $this->view->viewType = $this->_getParam('viewType', 'gridview');
    $this->view->height = $this->_getParam('height', 200);
    
    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->width = $this->_getParam('width', 100);
    $this->view->showLyrics = $this->_getParam('showLyrics', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    //Songs settings.
    $this->view->songlink = unserialize($settings->getSetting('sesmusic.songlink'));

    $this->view->information = $this->_getParam('information', array('featuredLabel', 'sponsoredLabel', 'newLabel', 'likeCount', 'commentCount', "downloadCount", 'viewCount', 'title', 'postedby'));


    $this->view->canAddPlaylistAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_song');

    $this->view->addfavouriteAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_song');

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

    $params = array();
    if ($showType == 'recommanded') {
      $params['widgteName'] = 'Recommanded Album Songs';
    } elseif ($showType == 'other') {
      $params['widgteName'] = 'Other Album Songs';
      $params['albumsong_id'] = $albumSong->albumsong_id;
    } elseif ($showType == 'related') {
      $params['widgteName'] = 'Related Album Songs';
      $params['album_id'] = $album->album_id;
      $params['category_id'] = $album->category_id;
    } elseif ($showType == 'artistOtherSongs') {
      $params['widgteName'] = 'Artist Other Songs';
      $params['artist_id'] = $artist_id;
    } elseif ($showType == 'otherSongView') {
      $params['widgteName'] = 'Other Songs of Music Album';
      $params['album_id'] = $album->album_id;
    }

    $params['popularity'] = $this->_getParam('popularity', 'creation_date');
    $params['limit'] = $this->_getParam('limit', 3);

    $params['column'] = array('albumsong_id', 'album_id', 'title', 'photo_id', 'lyrics', 'view_count', 'like_count', 'comment_count', "download_count", 'featured', 'hot', 'sponsored', 'rating', 'artists', 'file_id', 'track_id', 'song_url', 'upcoming', 'play_count', 'store_link','favourite_count');

    $this->view->results = Engine_Api::_()->getDbtable('albumsongs', 'sesmusic')->widgetResults($params);
    if (engine_count($this->view->results) <= 0)
      return $this->setNoRender();
  }

}
