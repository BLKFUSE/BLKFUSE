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

class Sescommunityads_Widget_BrowseAdsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $this->view->is_ajax = $is_ajax = $this->_getParam('is_ajax',false);
    $this->view->limit = $limit = $this->_getParam('limit',10);
    $this->view->page = $page = $this->_getParam('page',1);
    $this->view->category = $value['category_id'] = $category = $this->_getParam('category',0);
    $this->view->locationEnable = $value['locationEnable'] =  $this->_getParam('locationEnable','0');
    $this->view->featured_sponsored = $value['communityAdsDisplay'] = $this->_getParam('featured_sponsored',3);
    $value['browsePage'] = 1;
    $this->view->loadType = $value['loadType'] = $this->_getParam('pagging','pagging');
    $this->view->widgetId = !empty($this->view->identity) ? $this->view->identity : $this->_getParam('identityWidget',0);

    $searchParams = $this->_getParam('searchParams',false);
    if($searchParams){
      parse_str($searchParams,$searchArray);
      $value = array_merge($value,$searchArray);
    }
    $paginator = $this->view->paginator =  Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getAds($value);
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    $this->view->widgetName = "browse-ads";
    if($is_ajax){
      $this->getElement()->removeDecorator('Container');
    }
  }
}
