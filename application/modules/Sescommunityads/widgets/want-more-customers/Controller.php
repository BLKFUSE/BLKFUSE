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
/**
 */
class Sescommunityads_Widget_WantMoreCustomersController extends Engine_Content_Widget_Abstract{
  public function indexAction(){
	  //checked loggedin viewer
    $viewer = $this->view->viewer = $this->view->viewer();
    if(!$viewer->getIdentity())
      return $this->setNoRender();
	}
}