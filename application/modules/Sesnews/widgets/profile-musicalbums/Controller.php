<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Widget_ProfileMusicalbumsController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $coreApi = Engine_Api::_()->core();
    $authorizationApi = Engine_Api::_()->authorization();

    if (empty($_POST['is_ajax'])) {
      /* check sesmusic plugin enable or not ,if no then return */
      if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmusic'))
        return $this->setNoRender();

      if (!$coreApi->hasSubject('sesnews_news'))
        return $this->setNoRender();

      $subject_news = $coreApi->getSubject('sesnews_news');
      $this->view->news_id = $subject_news->news_id;

      $subject = $coreApi->getSubject();

			$this->view->allow_create = true;
			if(isset($value['is_ajax']) && !$value['is_ajax'] && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
				$package = $subject->getPackage();
				$viewAllowed = $package->getItemModule('music');
				if(!$viewAllowed)
					return $this->setNoRender();
				//allow upload photo
				$this->view->allow_create = $allow_create = $package->allowUploadMusic($subject);
			}
			$this->view->canCreate  = Engine_Api::_()->sesnews()->isNewsAdmin($subject, 'music_create');
    }

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);

    $this->view->defaultOpenTab = $defaultOpenTab = ($this->_getParam('openTab') != NULL ? $this->_getParam('openTab') : (isset($params['openTab']) ? $params['openTab'] : 'profilemusicalbums'));
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $page = $this->_getParam('page', 1);
    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->defaultOptions = array('profilemusicalbums');
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');
    $this->view->height = $defaultHeight = isset($params['height']) ? $params['height'] : $this->_getParam('Height', '180px');
    $this->view->width = $defaultWidth = isset($params['width']) ? $params['width'] : $this->_getParam('Width', '180px');
    $this->view->limit_data = $limit_data = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', '20');
    $this->view->news_id = $news_id = isset($params['news_id']) ? $params['news_id'] : $subject->news_id;
    $this->view->limit = ($page - 1) * $limit_data;
    $this->view->albumPhotoOption = 'album';
    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'comment', 'rating', 'by', 'title', 'social_sharing', 'view'));

    $this->view->informationAlbum = $informationAlbum = isset($params['informationAlbum']) ? $params['informationAlbum'] : $this->_getParam('informationAlbum', array('featured', 'sponsored', 'new', 'likeCount', 'commentCount', "downloadCount", 'viewCount', 'title', 'postedby'));

    foreach ($show_criterias as $show_criteria)
      $this->view->$show_criteria = $show_criteria;

    $params = $this->view->params = array('height' => $defaultHeight, 'width' => $defaultWidth, 'limit_data' => $limit_data, 'albumPhotoOption' => 'album', 'openTab' => $defaultOpenTab, 'pagging' => $loadOptionData, 'show_criterias' => $show_criterias, "informationAlbum" => $informationAlbum, 'news_id' => $news_id);

    $this->view->loadMoreLink = $this->_getParam('openTab') != NULL ? true : false;
    $this->view->type = 'profilemusicalbums';

    $this->view->canAddPlaylistAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'addplaylist_albumsong');
    $this->view->addfavouriteAlbumSong = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'addfavourite_albumsong');
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

    $table = Engine_Api::_()->getItemTable('sesmusic_album');
    $tableName = $table->info('name');
    $select = $table->select()
            ->from($tableName)
            ->where($tableName . '.search = ?', true)
            ->where($tableName . '.resource_id = ?', $news_id)
            ->where($tableName . '.resource_type = ?', 'sesnews_news')
            ->order('creation_date DESC');
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($limit_data);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');

    // Add count to title if configured
    if ($paginator->getTotalItemCount() > 0) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}
