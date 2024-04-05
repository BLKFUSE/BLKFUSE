<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktoktheme
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Widget_TikttokProfileLinkController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
		$viewer = Engine_Api::_()->user()->getViewer();
		if(empty($viewer->getIdentity()))
			return $this->setNoRender();
			
    if( !Engine_Api::_()->core()->hasSubject() ) {
      return $this->setNoRender();
    }
    
		if(Engine_Api::_()->core()->hasSubject('user'))
			$this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user'); 
		
	}
}
