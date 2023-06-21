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

class Egifts_Widget_GiftViewController extends Engine_Content_Widget_Abstract{
  	public function indexAction()
  	{
		if (!Engine_Api::_()->core()->hasSubject()){
		 	$this->setNoRender();
		}
		$this->view->item = $item = Engine_Api::_()->core()->getSubject();
		$widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
	    $params = Engine_Api::_()->egifts()->getWidgetParams($widgetId);
	    $show_criterias = $params['show_criteria'];
      $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
      if (empty($egifts_user))
        return $this->setNoRender();
	    foreach ($show_criterias as $show_criteria)
	      $this->view->{$show_criteria . 'Active'} = $show_criteria;
	  	$this->view->params = $params;
	}
}
