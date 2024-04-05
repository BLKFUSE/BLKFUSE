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
class Sesnews_Widget_OfTheDayController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

    // default params of the widget
    $this->view->height = $this->_getParam('height', '180');
    $this->view->width = $this->_getParam('width', '180');
    $this->view->limit_data = $this->_getParam('limit_data', '5');
    $this->view->title_truncation = $this->_getParam('title_truncation', '45');
     $this->view->description_truncation = $this->_getParam('description_truncation', '120');
    $show_criterias = $this->_getParam('show_criteria',array('like','comment','view', 'rating','ratingStar', 'by','title','socialSharing','favourite','featuredLabel','sponsoredLabel','verifiedLabel', 'favouriteButton','likeButton'));
    $this->view->type = $this->_getParam('viewType', 'grid1');
    $this->view->fixHover = isset($params['fixHover']) ? $params['fixHover'] :$this->_getParam('fixHover', 'fix');
    $this->view->insideOutside = isset($params['insideOutside']) ? $params['insideOutside'] : $this->_getParam('insideOutside', 'inside');
    foreach($show_criterias as $show_criteria)
    $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $newsId = Engine_Api::_()->getDbTable('news', 'sesnews')->getOfTheDayResults();
    $this->view->news_id = $newsId ;
    // Do not render if nothing to show
    if (empty($newsId)){
    return $this->setNoRender();
    }
  }

}
