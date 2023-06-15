<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
     
    $this->view->view_type = $this->_getParam('view_type', 'horizontal');
    $this->view->search_for = $search_for = $this->_getParam('search_for', 'contest');
    $searchForm = $this->view->form = new Sescontest_Form_Search();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm->setMethod('get')->populate($request->getParams());
    $sescontest_browsesearch = Zend_Registry::isRegistered('sescontest_browsesearch') ? Zend_Registry::get('sescontest_browsesearch') : null;
    if(empty($sescontest_browsesearch)) {
      return $this->setNoRender();
    }
    // Contest based contest browse page work
    $page_id = Engine_Api::_()->sescontest()->getWidgetPageId($this->view->identity);
    if($page_id) {
      $pageName = Engine_Db_Table::getDefaultAdapter()->select()
              ->from('engine4_core_pages', 'name')
              ->where('page_id = ?', $page_id)
              ->limit(1)
              ->query()
              ->fetchColumn();
      if($pageName) {
        $explode = explode('sescontest_index_', $pageName);
        if(is_numeric($explode[1])) {
          $this->view->page_id = $explode[1];
        }
      }
    }
    // Contest based contest browse page work
  }

}
