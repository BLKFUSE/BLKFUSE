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
class Sesmusic_Widget_SongCoverController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();  
    $ratingTable = Engine_Api::_()->getDbTable('ratings', 'sesmusic');
    $this->view->categoriesTable = Engine_Api::_()->getDbTable('categories', 'sesmusic');
    
    $this->view->songCover = $settings->getSetting('sesmusic.show.songcover', 1);

    //Get song
    $this->view->albumsong = $albumsong = Engine_Api::_()->core()->getSubject('sesmusic_albumsong');
    $this->view->album = $album = $albumsong->getParent();
    if (!$albumsong)
      return $this->setNoRender();

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    $this->view->height = $this->_getParam('height', 400);
    
    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->mainPhotoHeight = $this->_getParam('mainPhotoHeight', 350);
    $this->view->mainPhotowidth = $this->_getParam('mainPhotowidth', 350);
    $this->view->songCoverPhoto = $settings->getSetting('sesmusic.songcover.photo');

    
    //Songs settings.
    $this->view->songlink = unserialize($settings->getSetting('sesmusic.songlink'));

    //Favourite work
    $this->view->isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $albumsong->getIdentity()));
    
    //Like Status
    $this->view->isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($albumsong, $viewer);

    $this->view->information = $this->_getParam('information', array('postedBy', 'creationDate', 'commentCount', 'viewCount', 'likeCount', 'ratingCount', 'favouriteCount', 'playCount', 'ratingStars', 'playButton', 'editButton', 'deleteButton', 'addplaylist', 'share', 'report', 'downloadButton', 'addFavouriteButton', "printButton", 'photo', 'category', "addLikeButton", "socialSharing"));

    $this->view->canAddPlaylistAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_song');
    
    $this->view->addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'addstore_link');
    
    $this->view->canEditSong = $album->authorization()->isAllowed( $viewer, 'edit_song');
    $this->view->canDeleteSong = $album->authorization()->isAllowed($viewer, 'delete_song');
    
    $this->view->addfavouriteAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_song');
    
    $this->view->canLike = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'comment');
    
    $this->view->downloadAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'download_song');

    //$this->view->results = Engine_Api::_()->sesmusic()->albumsSongsLikeResults(array('type' => 'sesmusic_albumsong', 'id' => $albumsong->getIdentity(), 'limit' => '10', 'showUsers' => 'all'));
    
    //Start Rating work
    $this->view->mine = $mine = true;
    if (!$viewer->isSelf($albumsong->getOwner()))
      $this->view->mine = $mine = false;

    $this->view->allowShowRating = $allowShowRating = $settings->getSetting('sesmusic.ratealbumsong.show', 1);
    $this->view->allowRating = $allowRating = $settings->getSetting('sesmusic.albumsong.rating', 1);
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    }
    else
      $showRating = true;

    $this->view->showRating = $showRating;

    if ($showRating) {
      $this->view->canRate = $canRate = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'rating_albumsong');
      $this->view->allowRateAgain = $allowRateAgain = $settings->getSetting('sesmusic.ratealbumsong.again', 1);
      $this->view->allowRateOwn = $allowRateOwn = $settings->getSetting('sesmusic.ratealbumsong.own', 1);
      if ($canRate == 0 || $allowRating == 0)
        $allowRating = false;
      else
        $allowRating = true;

      if ($allowRateOwn == 0 && $mine)
        $allowMine = false;
      else
        $allowMine = true;

      $this->view->allowMine = $allowMine;
      $this->view->allowRating = $allowRating;
      $this->view->rating_type = $rating_type = 'sesmusic_albumsong';
      $this->view->rating_count = $ratingTable->ratingCount($albumsong->getIdentity(), $rating_type);
      $this->view->rated = $rated = $ratingTable->checkRated($albumsong->getIdentity(), $viewer->getIdentity(), $rating_type);

      if (!$allowRateAgain && $rated)
        $rated = false;
      else
        $rated = true;
      $this->view->ratedAgain = $rated;
    }
    //End rating work
		$getmodule = Engine_Api::_()->getDbTable('modules', 'core')->getModule('core');
		if (!empty($getmodule->version) && version_compare($getmodule->version, '4.8.8') >= 0){
			$this->view->doctype('XHTML1_RDFA');
			$this->view->docActive = true;
		}
  }

}