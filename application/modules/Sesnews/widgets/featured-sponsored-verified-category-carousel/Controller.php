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
class Sesnews_Widget_FeaturedSponsoredVerifiedCategoryCarouselController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  	$value['category_id'] = $this->_getParam('category','');

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

		$this->view->height =  $this->_getParam('height', '350');
    $this->view->width =  $this->_getParam('width', '220');
    $this->view->title_truncation = $this->_getParam('title_truncation', '45');
   	$this->view->slidesToShow = $this->_getParam('slidesToShow',3);
	 	$this->view->isfullwidth = $this->_getParam('isfullwidth',1);
	 	$this->view->autoplay = $this->_getParam('autoplay',1);
		$this->view->speed = $this->_getParam('speed',2000);

    $this->view->carousel_type = $this->_getParam('carousel_type', '1');

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'comment', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'verifiedLabel', 'rating', 'ratingStar', 'by', 'favourite','category','favouriteButton','likeButton', 'creationDate'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $limit =  $this->_getParam('limit_data', 5);
    $value['criteria'] = $this->_getParam('criteria', 5);
    $value['info'] = $this->_getParam('info', 'recently_created');
   	$value['order'] = $this->_getParam('order', '');
    $value['fetchAll'] = true;
		$value['limit'] = $limit;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('news', 'sesnews')->getSesnewsSelect($value);
		if (is_countable($paginator) && engine_count($paginator) <= 0)
			return $this->setNoRender();
  }
}
