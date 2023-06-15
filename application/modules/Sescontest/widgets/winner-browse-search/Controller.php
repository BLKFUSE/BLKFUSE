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

class Sescontest_Widget_WinnerBrowseSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->view_type = $this->_getParam('view_type', 'horizontal');
    $this->view->search_for = $search_for = $this->_getParam('search_for', 'contest');

    $searchForm = $this->view->form = new Sescontest_Form_WinnerSearch();
    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;
    if(empty($sescontest_widget)) {
      return $this->setNoRender();
    }
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm->setMethod('get')->populate($request->getParams());
  }

}
