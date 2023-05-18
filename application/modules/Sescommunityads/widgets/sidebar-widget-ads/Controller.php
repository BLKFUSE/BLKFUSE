<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Widget_SidebarWidgetAdsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    
    $view = Engine_Api::_()->authorization()->isAllowed('sescommunityads', null, 'view');
    if(!$view)
      return $this->setNoRender();
    $this->view->limit = $limit = $this->_getParam('limit',10);
    $this->view->category = $value['category_id'] = $category = $this->_getParam('category',0);
    $this->view->locationEnable = $value['locationEnable'] =  $this->_getParam('locationEnable','0');
    $this->view->featured_sponsored = $value['communityAdsDisplay'] = $this->_getParam('featured_sponsored',3);
    $value['browsePage'] = 1;
    $value['isWidget'] = 1;
    $paginator = $this->view->paginator =  Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getAds($value);
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    if($paginator->getTotalItemCount() == 0)
      return $this->setNoRender();
  }
}
