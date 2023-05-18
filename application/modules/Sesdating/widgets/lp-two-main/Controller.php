<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesdating_Widget_LpTwoMainController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->newest_members = Engine_Api::_()->sesdating()->getMembers('Newest');
		 $settings = Engine_Api::_()->getApi('settings', 'core');
    $defaultoptn = array('search','miniMenu','mainMenu','logo', 'socialshare');
		$loggedinHeaderCondition = $settings->getSetting('sesdating.header.loggedin.options', 'a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}');
		$nonloggedinHeaderCondition = $settings->getSetting('sesdating.header.nonloggedin.options','a:4:{i:0;s:6:"search";i:1;s:8:"miniMenu";i:2;s:8:"mainMenu";i:3;s:4:"logo";}');

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $sesdating_header = Zend_Registry::isRegistered('sesdating_header') ? Zend_Registry::get('sesdating_header') : null;
    if(empty($sesdating_header)) {
      return $this->setNoRender();
    }
    if($viewer_id != 0) {
      $this->view->show_menu = (is_array($loggedinHeaderCondition) && engine_in_array('mainMenu',$loggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_mini = (is_array($loggedinHeaderCondition) && engine_in_array('miniMenu',$loggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_logo = (is_array($loggedinHeaderCondition) && engine_in_array('logo',$loggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_search = (is_array($loggedinHeaderCondition) && engine_in_array('search',$loggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_socialshare = (is_array($loggedinHeaderCondition) && engine_in_array('socialshare',$loggedinHeaderCondition)) ? 1 : 0;
    } else {
      $this->view->show_menu = (is_array($nonloggedinHeaderCondition) && engine_in_array('mainMenu',$nonloggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_mini = (is_array($nonloggedinHeaderCondition) && engine_in_array('miniMenu',$nonloggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_logo = (is_array($nonloggedinHeaderCondition) && engine_in_array('logo',$nonloggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_search = (is_array($nonloggedinHeaderCondition) && engine_in_array('search',$nonloggedinHeaderCondition)) ? 1 : 0;
      $this->view->show_socialshare = (is_array($nonloggedinHeaderCondition) && engine_in_array('socialshare',$nonloggedinHeaderCondition)) ? 1 : 0;
		}
  }
}
