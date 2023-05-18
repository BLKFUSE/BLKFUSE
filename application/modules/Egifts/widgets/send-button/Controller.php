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

class Egifts_Widget_SendButtonController extends Engine_Content_Widget_Abstract{
  public function indexAction()
  {
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject('user') ) {
      return $this->setNoRender();
    }
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    if (empty($egifts_user))
      return $this->setNoRender();
    if($viewer->getIdentity() == Engine_Api::_()->core()->getSubject('user')->getIdentity()){
      return $this->setNoRender();
    } 
	}
}
