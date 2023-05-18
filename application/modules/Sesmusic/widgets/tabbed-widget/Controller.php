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
class Sesmusic_Widget_TabbedWidgetController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    // Default option for tabbed widget
    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $page = $this->_getParam('page', 1);
    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->defaultOptions = $defaultOptions = isset($params['defaultOptions']) ? $params['defaultOptions'] : $this->_getParam('search_type', 'recently1Updated');


    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 0);
    $this->view->defaultOpenTab = $defaultOpenTab = ($this->_getParam('openTab') != NULL ? $this->_getParam('openTab') : (isset($params['openTab']) ? $params['openTab'] : $this->_getParam('default', 'recently_updated')));
    $this->view->height = $defaultHeight = isset($params['height']) ? $params['height'] : $this->_getParam('height', '200px');


    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', 2);

    $this->view->width = $defaultWidth = isset($params['width']) ? $params['width'] : $this->_getParam('width', '195px');
    $this->view->showTabType = $showTabType = isset($params['showTabType']) ? $params['showTabType'] : $this->_getParam('showTabType', 1);
    $this->view->limit_data = $limit_data = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', '9');
    $this->view->limit = ($page - 1) * $limit_data;
    $this->view->albumPhotoOption = $albumPhotoOption = isset($params['albumPhotoOption']) ? $params['albumPhotoOption'] : $this->_getParam('photo_album', 'photo');
    $this->view->information = $information = isset($params['information']) ? $params['information'] : $this->_getParam('information', array('featured', 'sponsored', 'hot', 'likeCount', 'commentCount', 'viewCount', 'title', 'postedby', 'favourite', 'addplaylist', 'share', "ratingStars"));
    $params = $this->view->params = array('height' => $defaultHeight, 'width' => $defaultWidth, 'limit_data' => $limit_data, 'albumPhotoOption' => $albumPhotoOption, 'openTab' => $defaultOpenTab, 'pagging' => $loadOptionData, 'showTabType' => $showTabType, 'information' => $information, 'defaultOptions' => $defaultOptions);
    $this->view->loadMoreLink = $this->_getParam('openTab') != NULL ? true : false;


    $this->view->canAddPlaylist = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_album');
    $this->view->canAddFavourite = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_album');
    $this->view->albumlink = unserialize($settings->getSetting('sesmusic.albumlink'));

    $allowShowRating = $settings->getSetting('sesmusic.ratealbum.show', 1);
    $allowRating = $settings->getSetting('sesmusic.album.rating', 1);
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    } else
      $showRating = true;
    $this->view->showRating = $showRating;

    $type = '';
    switch ($defaultOpenTab) {
      case 'recently1Created':
        $popularCol = 'creation_date';
        $type = 'recently';
        break;
      case 'recently1Updated':
        $popularCol = 'modified_date';
        $type = 'modified';
        break;
      case 'most1Viewed':
        $popularCol = 'view_count';
        $type = 'view';
        break;
      case 'most1Liked':
        $popularCol = 'like_count';
        $type = 'like';
        break;
      case 'most1Commented':
        $popularCol = 'comment_count';
        $type = 'comment';
        break;
      case 'song1Count':
        $popularCol = 'song_count';
        $type = 'song';
        break;
      case 'most1Favourite':
        $popularCol = 'favourite_count';
        $type = 'favourite';
        break;
      case 'most1Rated':
        $popularCol = 'rating';
        $type = 'rating';
        break;
      case 'upcoming':
        $popularCol = 'upcoming';
        $type = 'upcoming';
        $fixedData = 'upcoming';
        break;
      case 'hot':
        $popularCol = 'hot';
        $type = 'hot';
        $fixedData = 'hot';
        break;
      case 'featured':
        $popularCol = 'featured';
        $type = 'featured';
        $fixedData = 'featured';
        break;
      case 'sponsored':
        $popularCol = 'sponsored';
        $type = 'sponsored';
        $fixedData = 'sponsored';
        break;
    }

    $this->view->type = $type;
    $sesmusic_widget = Zend_Registry::isRegistered('sesmusic_widget') ? Zend_Registry::get('sesmusic_widget') : null;
    if(empty($sesmusic_widget)) {
      return $this->setNoRender();
    }
    $table = Engine_Api::_()->getItemTable('sesmusic_albums');
    $tableName = $table->info('name');
    $select = $table->select()
            ->from($tableName)
            ->where($tableName . '.search = ?', 1)
            ->where($tableName.'.upload_param =?', 'album');

    if (isset($popularCol))
      $select->order($popularCol . ' DESC');
    if (isset($fixedData)) {
      $select = $select->where($tableName . '.' . $fixedData . ' =?', 1);
    }

    //don't show other module musics
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.other.modulemusics', 1) && empty($params['resource_type'])) {
      $select->where($tableName . '.resource_type IS NULL')
              ->where($tableName . '.resource_id =?', 0);
    } else if (!empty($params['resource_type']) && !empty($params['resource_id'])) {
      $select->where($tableName . '.resource_type =?', $params['resource_type'])
              ->where($tableName . '.resource_id =?', $params['resource_id']);
    } else if(!empty($params['resource_type'])) {
      $select->where($tableName . '.resource_type =?', $params['resource_type']);
    }
    //don't show other module musics

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', $limit_data));
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);

    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
    else {
      if ($paginator->getTotalItemCount() <= 0)
        return $this->setNoRender();
    }
  }

}
