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
/**

 */

class Sesbday_Widget_BirthdayListingController extends Engine_Content_Widget_Abstract
{
  public function indexAction(){
	  $this->view->is_ajax = $this->_getParam('is_ajax',false);
	  $todaysDate = date('Y-m-d');
	  if(!$this->view->is_ajax){
		$this->view->users = $users = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,1);
		$this->view->upComingBirthday = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,2);
		if(empty($this->view->upComingBirthday['laterExists']))
		$this->view->laterBirthday = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,4);
	  }
		$comingBirthday = date('m', strtotime(date('m'). " +1 month"));
		$dateMonth = $this->_getParam('comingBirthday',$comingBirthday);
		$this->view->comingBirthday = Engine_Api::_()->sesbday()->getFriendBirthday($dateMonth,3);
		
		
	}
}
?>