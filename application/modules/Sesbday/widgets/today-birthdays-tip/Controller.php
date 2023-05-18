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

class Sesbday_Widget_TodayBirthdaysTipController extends Engine_Content_Widget_Abstract
{
  public function indexAction(){
	  $todaysDate = date('Y-m-d');
	  $this->view->users = Engine_Api::_()->sesbday()->getFriendBirthday($todaysDate,1);
	   $viewer = Engine_Api::_()->user()->getViewer();
        if(!$viewer->getIdentity()){
            return $this->setNoRender();
        }
	 if(!engine_count($this->view->users['data']))
	  {
		return $this->setNoRender();
	  }
	}
}
