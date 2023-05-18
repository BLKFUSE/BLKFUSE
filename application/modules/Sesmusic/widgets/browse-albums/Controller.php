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
class Sesmusic_Widget_BrowseAlbumsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();

    $this->view->viewer_id = $viewer->getIdentity();
    $this->view->viewmore = $this->_getParam('viewmore', 0);

    $this->view->paginationType = $paginationType = $this->_getParam('paginationType', 1);
    $this->view->width = $width = isset($params['width']) ? $params['width'] : $this->_getParam('width', 200);
    $this->view->viewType = $viewType = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'gridview');    
    $this->view->height = $height = isset($params['height']) ? $params['height'] : $this->_getParam('height', 200);
    
    
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->information = $information = isset($params['information']) ? $params['information'] : $this->_getParam('information', array('featured', 'sponsored', 'hot', 'likeCount', 'commentCount', 'viewCount', 'title', 'postedby', 'favourite', 'addplaylist', 'share', 'ratingStars'));

    if ($this->view->viewmore)
      $this->getElement()->removeDecorator('Container');

    $sesmusic_browse = Zend_Registry::isRegistered('sesmusic_browse') ? Zend_Registry::get('sesmusic_browse') : null;
    if(empty($sesmusic_browse)) {
      return $this->setNoRender();
    }
    
    //Can create?
    $this->view->canCreate = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'create');
    $this->view->canAddPlaylist = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_album');
    $this->view->canAddFavourite = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_album');
    $this->view->albumlink = unserialize($settings->getSetting('sesmusic.albumlink'));

    $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : (isset($params['category_id']) ? $params['category_id'] : '');
    $subcat_id = isset($_GET['subcat_id']) ? $_GET['subcat_id'] : (isset($params['subcat_id']) ? $params['subcat_id'] : '');
    $subsubcat_id = isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : (isset($params['subsubcat_id']) ? $params['subsubcat_id'] : '');
    $alphabet = isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : '');
    $showPhoto = isset($_GET['showPhoto']) ? $_GET['showPhoto'] : (isset($params['showPhoto']) ? $params['showPhoto'] : '');
    
    $popularity = isset($_GET['popularity']) ? $_GET['popularity'] : (isset($params['popularity']) ? $params['popularity'] : 'creation_date');
    
    
    $title = isset($_GET['title_name']) ? $_GET['title_name'] : (isset($params['title_name']) ? $params['title_name'] : '');
    $show = isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : '');
    $itemCount = isset($params['itemCount']) ? $params['itemCount'] : $this->_getParam('itemCount', 10);
    $artists = isset($_GET['artists']) ? $_GET['artists'] : (isset($params['artists']) ? $params['artists'] : '');
    
    $identity = isset($_GET['identity']) ? $_GET['identity'] : (isset($params['identity']) ? $params['identity'] : $this->view->identity);

    $users = array();
    if (isset($_GET['show']) && $_GET['show'] == 2 && $viewer->getIdentity()) {
      $users = $viewer->membership()->getMembershipsOfIds();
    }

    $values = array('paginationType' => $paginationType, 'width' => $width, 'height' => $height, 'information' => $information, 'category_id' => $category_id, 'subcat_id' => $subcat_id, 'subsubcat_id' => $subsubcat_id, 'alphabet' => $alphabet, 'title' => $title, 'showPhoto' => $showPhoto, 'popularity' => $popularity, 'show' => $show, 'users' => $users, 'itemCount' => $itemCount, 'artists' => $artists, 'viewType' => $viewType, 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit, 'identity' => $identity);

    $this->view->allowShowRating = $allowShowRating = $settings->getSetting('sesmusic.ratealbum.show', 1);
    $this->view->allowRating = $allowRating = $settings->getSetting('sesmusic.album.rating', 1);
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    }
    else
      $showRating = true;
    $this->view->showRating = $showRating;
    
    // Content browse page work
    $type = '';
    $page_id = Engine_Api::_()->sesmusic()->getWidgetPageId($identity);
    if($page_id) {
      $pageName = Engine_Db_Table::getDefaultAdapter()->select()
              ->from('engine4_core_pages', 'name')
              ->where('page_id = ?', $page_id)
              ->limit(1)
              ->query()
              ->fetchColumn();
      if($pageName) {
        $explode = explode('sesmusic_index_', $pageName);
        if(is_numeric($explode[1])) {
          $type = Engine_Db_Table::getDefaultAdapter()->select()
                ->from('engine4_sesmusic_integrateothermodules', 'content_type')
                ->where('integrateothermodule_id = ?', $explode[1])
                ->limit(1)
                ->query()
                ->fetchColumn();
          if($type) {
            $values['resource_type'] = $type;
          }
        }
      }
    }
    $this->view->type = $type;
    // Content browse page work
    
    $this->view->all_params = $values;

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('albums', 'sesmusic')->getPlaylistPaginator($values);
    $paginator->setItemCountPerPage($itemCount);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $this->view->count = $paginator->getTotalItemCount();
  }

}