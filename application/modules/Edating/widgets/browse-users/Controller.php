<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Widget_BrowseUsersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->allParams = $this->_getAllParams();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $this->view->paginator = $paginator = Engine_Api::_()->edating()->getUsersPaginator($request->getParams());
    $paginator->setItemCountPerPage($this->_getParam('limit_data', 10));
    $paginator->setCurrentPageNumber($request->getParam("page", 1));
    $view = $this->view;
    $view->addHelperPath(APPLICATION_PATH . '/application/modules/Fields/View/Helper', 'Fields_View_Helper');
  }
}
