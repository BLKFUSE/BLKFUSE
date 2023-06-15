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
class Sesnews_Widget_BrowseContributorsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $searchArray = array();
    // Prepare
    if (isset($_POST['params']))
      $params = $_POST['params'];
		if (isset($_POST['searchParams']) && $_POST['searchParams']) {
			if(engine_in_array($_POST['searchParams']))
				$searchArray = $_POST['searchParams'];
			elseif(is_string($_POST['searchParams']))
				parse_str($_POST['searchParams'], $searchArray);
		}
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $page = $this->_getParam('page', 1);
    $limit_data = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', '10');
    $this->view->title_truncation_list = $title_truncation_list = isset($params['title_truncation_list']) ? $params['title_truncation_list'] : $this->_getParam('title_truncation_list', '100');

    //search data
    $defaultOrderOrg = $this->_getParam('order');
    if (!empty($defaultOrderOrg)) {
      $orderKey = str_replace(array('SP', ''), array(' ', ' '), $defaultOrderOrg);
      $defaultOrder = Engine_Api::_()->sesbasic()->getColumnName($orderKey);
    }
    else
      $defaultOrder = 'like_count DESC';

    $value['info'] = isset($params['info']) ? $params['info'] : $this->_getParam('info', 'recently_created');

    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');

    $this->view->photo_height = $defaultPhotoHeight = isset($params['photo_height']) ? $params['photo_height'] : $this->_getParam('photo_height', '200px');
    $this->view->photo_width = $defaultPhotoWidth = isset($params['photo_width']) ? $params['photo_width'] : $this->_getParam('photo_width', '200px');

    $params = array('pagging' => $loadOptionData, 'limit_data' => $limit_data, 'title_truncation_list' => $title_truncation_list, 'photo_height' => $defaultPhotoHeight, 'photo_width' => $defaultPhotoWidth, 'info' => $value['info']);
    $this->view->widgetName = 'browse-contributors';
    $this->view->page = $page;
    $this->view->params = array_merge($params, $value);
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
    // Get paginator
    $newsTable = Engine_Api::_()->getDbTable('news', 'sesnews');
    $newsTableName = $newsTable->info('name');
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');

    $select = $userTable->select()
            ->setIntegrityCheck(false)
            ->from($userTableName, array('user_id', 'displayname', 'photo_id'))
            ->join($newsTableName, $newsTableName . '.owner_id = ' . $userTableName . '.user_id ', array("COUNT($newsTableName.news_id) as news_count"))
            ->group($userTableName .'.user_id');
    if (isset($value['info'])) {
      switch ($value['info']) {
        case 'most_viewed':
          $select->order($userTableName . '.view_count DESC');
          break;
        case 'most_contributors':
          $select->order('news_count DESC');
          break;
        case 'most_liked':
          $coreLikeTable = Engine_Api::_()->getDbtable('likes', 'core');
          $likeTableName = $coreLikeTable->info('name');
          $select = $select->joinLeft($likeTableName, $likeTableName . '.resource_id = ' . $userTableName . '.user_id AND '.$likeTableName . '.resource_type = "user"', array("COUNT($likeTableName.resource_id) as total_count"))
          ->order("total_count DESC");
        case "recently_created":
          $select->order($userTableName . '.creation_date DESC');
          break;
      }
    }
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
  }

}
