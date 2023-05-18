<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbday_Widget_CalendarController extends Engine_Content_Widget_Abstract
{
  public function indexAction(){
	  $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		if(isset($_POST['month']) && isset($_POST['year'])){
			if($_POST['type'] == 'prev'){
				$dateCheck = date('Y-m-d',strtotime('last day of previous month',strtotime(date($_POST['year'].'-'.$_POST['month'].'-10'))));
			}else{
				$dateCheck = date('Y-m-d',strtotime('first day of next month',strtotime(date($_POST['year'].'-'.$_POST['month'].'-10'))));
			}			
			$params['month']=	(int)  date('m',strtotime($dateCheck));
			$params['year']	= (int)   date('Y',strtotime($dateCheck));
		}else{
			$params['month']=	(int) (isset($_POST['month']) ? $_POST['month'] : date('m'));
			$params['year']	= (int)  (isset($_POST['year']) ? $_POST['year'] : date('Y'));
		}		
		
		$todaysDate = $params['month'];
		$this->view->month = strlen($params['month']) == 1 ? '0'.$params['month'] : $params['month'];
		$this->view->year = $params['year'] ;
		$this->view->viewMoreAfter = $this->_getParam('viewmore','3');
		$this->view->loadData = $this->_getParam('loadData','nextprev');
		
		  $this->view->users = $users = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,3);
		 
		$userObj = array();
		if(engine_count($users["data"])){ 
			foreach($users["data"] as $valueUser){
				$userObj[date('m-d',strtotime($valueUser['value']))][] = $valueUser;	
			}
		}
		$this->view->users = $userObj;
		$this->view->widgetName = 'calendar';
	}
}
