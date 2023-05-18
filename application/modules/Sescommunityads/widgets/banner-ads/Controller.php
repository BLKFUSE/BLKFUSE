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
class Sescommunityads_Widget_BannerAdsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {

    $view = Engine_Api::_()->authorization()->isAllowed('sescommunityads', null, 'view');
    if(!$view)
      return $this->setNoRender();

    $this->view->banner_id = $value['banner_id'] = $this->_getParam('bannerid',0);
    $this->view->banner = Engine_Api::_()->getItem('sescomadbanr_banner', $this->view->banner_id);
    if(empty($value['banner_id']))
        return $this->setNoRender();

    //For Rented Work
    $this->view->rented = $rented = $this->_getParam('rented',0);
    $this->view->package_id = $package_id = $this->_getParam('package_id',null);
    $this->view->defaultbanner = $defaultbanner = $this->_getParam('defaultbanner','');
    if(!empty($rented) && empty($package_id)) {
        return $this->setNoRender();
    }

    $this->view->limit = $limit = $this->_getParam('limit',10);
    $this->view->category = $value['category_id'] = $category = $this->_getParam('category',0);
    $this->view->locationEnable = $value['locationEnable'] =  $this->_getParam('locationEnable','0');
    $this->view->featured_sponsored = $value['communityAdsDisplay'] = $this->_getParam('featured_sponsored',3);
    $value['browsePage'] = 1;
    $value['subtype'] = 1;

    if(!empty($rented)) {
        $value['widgetid'] = $this->view->identity;
    }
    $paginator = $this->view->paginator =  Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getAds($value);

    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    if(empty($rented)) {
        if($paginator->getTotalItemCount() == 0)
        return $this->setNoRender();
    }

    if(!empty($rented)) {
        $value['widgetid'] = $this->view->identity;
        $this->view->widgetIdAds = $getWidgetAds = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads')->getWidgetAds(array('widgetid' => $this->view->identity));
        if(empty($paginator->getTotalItemCount()) && !empty($getWidgetAds))
            return $this->setNoRender();
    }

  }
}
