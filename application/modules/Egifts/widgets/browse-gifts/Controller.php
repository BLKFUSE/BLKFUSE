<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Widget_BrowseGiftsController extends Engine_Content_Widget_Abstract{
  	public function indexAction()
  	{
  		$getParams = '';
        $searchArray = array();
        $defaultSearchParams = array();
				if (isset($_POST['searchParams']) && $_POST['searchParams']) {
					if(engine_in_array($_POST['searchParams']))
						$searchArray = $_POST['searchParams'];
					elseif(is_string($_POST['searchParams']))
						parse_str($_POST['searchParams'], $searchArray);
				}
        else {
          $getParams = !empty($_POST['getParams']) ? $_POST['getParams'] : $_SERVER['QUERY_STRING'];
          parse_str($getParams, $get_array);
        }
        $value = array();
        $this->view->getParams = $getParams;
        $this->view->view_more = isset($_POST['view_more']) ? true : false;
        $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
        $this->view->is_search = $is_search = !empty($_POST['is_search']) ? true : false;
  		$this->view->widgetName = 'browse-gifts';
        $page = $this->_getParam('page', 1);
        $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
        $params = Engine_Api::_()->egifts()->getWidgetParams($widgetId);
        $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
        if (empty($egifts_user))
          return $this->setNoRender();
        $text =  isset($searchArray['search']) ? $searchArray['search'] : (!empty($params['search']) ? $params['search'] : (isset($_GET['search']) && ($_GET['search'] != '') ? $_GET['search'] : ''));
        @$value['alphabet'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : $this->_getParam('alphabet')));
        $this->view->text = @$value['text']  = @stripslashes($text);
        if(!empty($params['show_criteria'])){
        	foreach (@$params['show_criteria'] as $show_criteria)
            	$this->view->{$show_criteria . 'Active'} = $show_criteria;
        }
        $params['limit_data'] = $params['limit_data'] ?? 10;
        $this->view->page = $page;
        if (isset($params['search']))
            $params['text'] = addslashes($params['search']); 
        $params = array_merge($params, $value); 
        $this->view->params = $params;

		$this->view->paginator = $paginator = Engine_Api::_()->getDbtable('gifts', 'egifts')->getGiftPaginator($params);
		$paginator->setItemCountPerPage($params['limit_data']); 
        $paginator->setCurrentPageNumber($page);
        if ($is_ajax) {
            $this->getElement()->removeDecorator('Container');
        }
	}
}
