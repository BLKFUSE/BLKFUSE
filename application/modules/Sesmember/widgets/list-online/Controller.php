<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_ListOnlineController extends Engine_Content_Widget_Abstract {

  protected $_onlineUserCount;

  public function indexAction() {

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;
    $this->view->widgetIdentity = $this->_getParam('content_id', $identity);
    $this->view->is_ajax = $is_ajax = $this->_getParam('is_ajax', 0);
    if ($this->_getParam('showLimitData', 1))
      $this->view->widgetName = 'list-online';
      
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', '2');

    $this->view->height = isset($params['height']) ? $params['height'] : $this->_getParam('height', '350');
    $this->view->width = isset($params['width']) ? $params['width'] : $this->_getParam('width', '220');
    $this->view->photo_height = isset($params['photo_height']) ? $params['photo_height'] : $this->_getParam('photo_height', '200');
    $this->view->photo_width = isset($params['photo_width']) ? $params['photo_width'] : $this->_getParam('photo_width', '200');
    $this->view->title_truncation_list = isset($params['list_title_truncation']) ? $params['list_title_truncation'] : $this->_getParam('list_title_truncation', '45');
    $this->view->title_truncation_grid = isset($params['grid_title_truncation']) ? $params['grid_title_truncation'] : $this->_getParam('grid_title_truncation', '45');
    $this->view->view_type = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'list');
    $this->view->image_type = isset($params['imageType']) ? $params['imageType'] : $this->_getParam('imageType', 'square');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel', 'likeButton', 'friendButton', 'likemainButton', 'message', 'followButton', 'rating', 'friendCount', 'profileType', 'mutualFriendCount', 'email', 'location', 'age'));

    foreach ((array) $show_criterias as $show_criteria)
     $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $limit = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', 5);

    // Get online users
    $table = Engine_Api::_()->getItemTable('user');
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');

    $tableName = $table->info('name');
    $onlineTableName = $onlineTable->info('name');

    $select = $table->select()
            ->from($tableName)
            ->joinRight($onlineTableName, $onlineTableName . '.user_id = ' . $tableName . '.user_id', null)
            ->where($onlineTableName . '.user_id > ?', 0)
            ->where($onlineTableName . '.active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
            ->where($tableName . '.search = ?', 1)
            ->where($tableName . '.enabled = ?', 1)
            ->order($onlineTableName . '.active DESC')
            ->group($onlineTableName . '.user_id');

    $paginator = Zend_Paginator::factory($select);
    $page = $this->_getParam('page', 1);
    // Set item count per page and current page number
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);

    // Skip if empty
    $this->view->onlineCount = $count = $paginator->getTotalItemCount();
    if ($count <= 0) {
      return $this->setNoRender();
    }
    $this->getElement()->removeDecorator('Title');
    $this->view->results = $paginator;

    // Make title
    $this->_onlineUserCount = $count;

    $element = $this->getElement();
    $title = $this->view->translate(array($element->getTitle(), $element->getTitle(), $count), $this->view->locale()->toNumber($count));
    $element->setTitle($title);
    $element->setParam('disableTranslate', true);


    $this->view->params = array('height' => $this->view->height, 'width' => $this->view->width, 'photo_height' => $this->view->photo_height, 'photo_width' => $this->view->photo_width, 'list_title_truncation' => $this->view->title_truncation_list, 'grid_title_truncation' => $this->view->title_truncation_grid, 'viewType' => $this->view->view_type, 'imageType' => $this->view->image_type, 'show_criterias' => $show_criterias, 'limit_data' => $limit, 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit);

    // Guests online
    $this->view->guestCount = null;
    if ($this->_getParam('showGuests', false)) {
      $this->view->guestCount = $onlineTable->select()
              ->from($onlineTable, new Zend_Db_Expr('COUNT(*) as count'))
              ->where('user_id = ?', 0)
              ->where('active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
              ->query()
              ->fetchColumn();
      ;
    }
  }

  public function getCacheKey() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $translate = Zend_Registry::get('Zend_Translate');
    return $viewer->getIdentity() . $translate->getLocale();
  }

  public function getCacheSpecificLifetime() {
    return 120;
  }

  public function getCacheExtraContent() {
    return $this->_onlineUserCount;
  }

  public function setCacheExtraData($data) {
    $element = $this->getElement();
    $element->setTitle(sprintf($element->getTitle(), (int) $data));
  }

}
