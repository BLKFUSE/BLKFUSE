<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $filterOptions = (array)$this->_getParam('search_type', array('recentlySPcreated' => 'Recently Created','mostSPplayed' => 'Most Played','mostSPliked' => 'Most Liked', 'mostSPcommented' => 'Most Commented'));
    $this->view->view_type = $this-> _getParam('view_type', 'horizontal');
		$default_search_type = $this-> _getParam('default_search_type', 'mostSPliked');
		
	 $searchForm = $this->view->searchForm = new Egames_Form_Search(array('searchTitle' => $this->_getParam('search_title', 'yes'),'browseBy' => $this->_getParam('browse_by', 'yes'),'categoriesSearch' => $this->_getParam('categories', 'yes'),'searchFor'=>$search_for,'FriendsSearch'=>$this->_getParam('friend_show', 'yes'),'defaultSearchtype'=>$default_search_type));
	 
   if($this->_getParam('search_type','1') !== null && $this->_getParam('browse_by', 'yes') == 'yes'){
		$arrayOptions = $filterOptions;
		$filterOptions = array();
		foreach ($arrayOptions as $key=>$filterOption) {
      $value = str_replace(array('SP',''), array(' ',' '), $filterOption);
      $filterOptions[str_replace("SP",'_',$key)] = ucwords($value);
    }
		$filterOptions = array(''=>'')+$filterOptions;
		 $searchForm->sort->setMultiOptions($filterOptions);
		 $searchForm->sort->setValue($default_search_type);
	 }
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm
            ->setMethod('get')
            ->populate($request->getParams());
    
    // Album browse page work
    $page_id = Engine_Api::_()->egames()->getWidgetPageId($this->view->identity);
    if($page_id) {
      $pageName = Engine_Db_Table::getDefaultAdapter()->select()
              ->from('engine4_core_pages', 'name')
              ->where('page_id = ?', $page_id)
              ->limit(1)
              ->query()
              ->fetchColumn();
      if($pageName) {
        $this->view->pageName = $pageName;
        $explode = explode('egames_index_', $pageName);
        if(!empty($explode[1])) {
          $this->view->page_id = $explode[1];
        }
      }
    }
    // Album browse page work
  }
}
