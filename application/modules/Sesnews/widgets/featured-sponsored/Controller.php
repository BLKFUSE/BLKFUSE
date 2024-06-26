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
class Sesnews_Widget_FeaturedSponsoredController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

		if (isset($_POST['params']))
		$params = json_decode($_POST['params'], true);
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
		$identity = $view->identity;
		$this->view->widgetIdentity = $this->_getParam('content_id', $identity);
		$this->view->is_ajax = $is_ajax = $this->_getParam('is_ajax', 0);
		if ($this->_getParam('showLimitData', 1))
		$this->view->widgetName = 'featured-sponsored';
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '12');
		$this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon =isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
		$this->view->socialshare_icon_limit = $socialshare_icon_limit =isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', 2);

    $this->view->height = isset($params['height']) ? $params['height'] : $this->_getParam('height', '350');
    $this->view->width = isset($params['width']) ? $params['width'] : $this->_getParam('width', '220');
    $this->view->title_truncation = isset($params['title_truncation']) ? $params['title_truncation'] : $this->_getParam('title_truncation', '45');
     $this->view->description_truncation = isset($params['description_truncation']) ? $params['description_truncation'] : $this->_getParam('description_truncation', '60');
    $this->view->view_type = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'list');
    $this->view->image_type = isset($params['imageType']) ? $params['imageType'] : $this->_getParam('imageType', 'square');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'comment', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'verifiedLabel', 'rating', 'ratingStar', 'by', 'creationDate', 'favourite','category'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $limit = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', 5);
    $this->view->show_star = isset($params['show_star']) ? $params['show_star'] : $this->_getParam('show_star', 0);
    $value['criteria'] = isset($params['criteria']) ? $params['criteria'] : $this->_getParam('criteria', 5);
    $value['info'] = isset($params['info']) ? $params['info'] : $this->_getParam('info', 'recently_created');
    $value['order'] = isset($params['order']) ? $params['order'] : $this->_getParam('order', '');
    $this->view->params = array('gridblock' => $gridblock, 'height' => $this->view->height, 'width' => $this->view->width,'description_truncation' => $this->view->description_truncation, 'title_truncation' => $this->view->title_truncation, 'viewType' => $this->view->view_type, 'imageType' => $this->view->image_type, 'show_criterias' => $show_criterias, 'limit_data' => $limit, 'show_star' => $this->view->show_star, 'criteria' => $value['criteria'], 'info' => $value['info'], 'order' => $value['order'], 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit);
    $page = $this->_getParam('page', 1);
    $value['draft'] = "0";

    $this->view->results = $paginator = Engine_Api::_()->getDbTable('news', 'sesnews')->getSesnewsPaginator($value);
		$paginator->setItemCountPerPage($limit);
		$this->view->page = $page;
		$paginator->setCurrentPageNumber($page);
		if ($paginator->getTotalItemCount() <= 0)
		return $this->setNoRender();
		if ($is_ajax)
		$this->getElement()->removeDecorator('Container');
  }
}
