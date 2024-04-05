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
class Sesnews_Widget_CategoryCarouselController extends Engine_Content_Widget_Abstract {
  public function indexAction() {

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

    $this->view->height = $this->_getParam('height', '310');
    $this->view->width = $this->_getParam('width', '400');
    $this->view->speed = $this->_getParam('speed', '300');
    $this->view->autoplay = $this->_getParam('autoplay', '1');
    $this->view->isfullwidth = $this->_getParam('isfullwidth', '1');
    $this->view->description_truncation_grid = $this->_getParam('description_truncation_grid', '100');
    $this->view->title_truncation_grid = $this->_getParam('title_truncation_grid', '45');
    $show_criterias = isset($values['show_criterias']) ? $values['show_criterias'] : $this->_getParam('show_criteria', array( 'title', 'countNews', 'icon','socialshare'));

    foreach ($show_criterias as $show_criteria)
    $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $this->view->criteria =$params['criteria'] = $this->_getParam("criteria",array('alphabetical','most_news','admin_order'));
    $limit = $this->_getParam('limit_data', 15);
    if($limit)
    $params['limit'] = $limit;
		if (engine_in_array('countNews', $show_criterias))
		$params['countNews'] = true;
    $this->view->paginator = Engine_Api::_()->getDbTable('categories', 'sesnews')->getCategory($params);
    if (is_countable($this->view->paginator) && engine_count($this->view->paginator) == 0)
    return $this->setNoRender();
  }
}
