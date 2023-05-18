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
class Sescommunityads_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract
{
  public function indexAction() {
    $filterOptions = (array)$this->_getParam('search_type', array('recentlySPcreated' => 'Recently Created','mostSPviewed' => 'Most Viewed','featured' => 'Featured','sponsored' => 'Sponsored'));
    $this->view->view_type = $this-> _getParam('view_type', 'horizontal');
    $default_search_type = $this-> _getParam('default_search_type', 'recentlySPcreated');
    if($this->_getParam('location','yes') == 'yes' && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads_enable_location',1))
      $location = 'yes';	
    else
      $location = 'no';
    $searchForm = $this->view->form = new Sescommunityads_Form_Search(array('categoriesSearch' => $this->_getParam('categories', 'yes'),'contentSearch' => $this->_getParam('content_option', 'yes'),'FriendsSearch'=>$this->_getParam('friend_show', 'yes'),'defaultSearchtype'=>$default_search_type,'locationSearch' => $location,'browseBy'=>$this->_getParam('browse_by', 'yes')));
    if($this->_getParam('browse_by', 'yes') == 'yes'){
      $arrayOptions = $filterOptions;
      $filterOptions = array();
      foreach ($arrayOptions as $key=>$filterOption) {
        if(is_numeric($key))
        $columnValue = $filterOption;
        else
        $columnValue = $key;
        $value = str_replace(array('SP',''), array(' ',' '), $columnValue);
        $filterOptions[$columnValue] = ucwords($value);
      }
      $filterOptions = array(''=>'')+$filterOptions;
      $searchForm->sort->setMultiOptions($filterOptions);
      $searchForm->sort->setValue($default_search_type);
    }
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm->setMethod('get')->populate($request->getParams());
  }
}
