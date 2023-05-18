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
class Sesmusic_Widget_BrowseSongsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();

    $this->view->viewmore = $this->_getParam('viewmore', 0);
    $this->view->paginationType = $paginationType = $this->_getParam('paginationType', 1);
    $this->view->viewType = $viewType = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'gridview');
    $this->view->width = $width = isset($params['width']) ? $params['width'] : $this->_getParam('width', 200);
    $this->view->height = $height = isset($params['height']) ? $params['height'] : $this->_getParam('height', 200);
    $this->view->information = $information = isset($params['information']) ? $params['information'] : $this->_getParam('information', array('playCount', 'downloadCount', 'likeCount', 'commentCount', 'viewCount', 'favouriteCount', 'ratingStars', 'artists', 'addplaylist', 'downloadIcon', 'share', 'report', 'favourite'));


    if ($this->view->viewmore)
      $this->getElement()->removeDecorator('Container');

    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : (isset($params['category_id']) ? $params['category_id'] : '');
    $subcat_id = isset($_GET['subcat_id']) ? $_GET['subcat_id'] : (isset($params['subcat_id']) ? $params['subcat_id'] : '');
    $subsubcat_id = isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : (isset($params['subsubcat_id']) ? $params['subsubcat_id'] : '');
    $alphabet = isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : '');
    $popularity = isset($_GET['popularity']) ? $_GET['popularity'] : (isset($params['popularity']) ? $params['popularity'] : $this->_getParam('popularity', 'creation_date'));
    $title = isset($_GET['title_song']) ? $_GET['title_song'] : (isset($params['title_song']) ? $params['title_song'] : '');
    $itemCount = isset($params['itemCount']) ? $params['itemCount'] : $this->_getParam('itemCount', 10);
    $artists = isset($_GET['artists']) ? $_GET['artists'] : (isset($params['artists']) ? $params['artists'] : '');
    
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', 2);


    $this->view->params = $params = array('paginationType' => $paginationType, 'information' => $information, 'alphabet' => $alphabet, 'title' => $title, 'popularity' => $popularity, 'itemCount' => $itemCount, 'artists' => $artists, 'column' => '*', 'category_id' => $category_id, 'subcat_id' => $subcat_id, 'subsubcat_id' => $subsubcat_id, 'viewType' => $viewType, 'width' => $width, 'height' => $height, 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit);

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    //Songs settings.
    $this->view->songlink = unserialize($settings->getSetting('sesmusic.songlink'));

    $this->view->canAddPlaylistAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_song');

    $this->view->canAddFavouriteAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_song');

    $this->view->downloadAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'download_song');

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
    $params['paginator'] = true;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('albumsongs', 'sesmusic')->widgetResults($params);
    $paginator->setItemCountPerPage($itemCount);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $this->view->count = $paginator->getTotalItemCount();
  }

}