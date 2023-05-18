<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_HomePhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Don't render this if not logged in
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$viewer->getIdentity()) {
      return $this->setNoRender();
    }
    $show_criterias = $this->_getParam('show_criteria', array('title', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
  }

  public function getCacheKey() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if (!$viewer_id) {
      return parent::getCacheKey();
    }
    $translate = Zend_Registry::get('Zend_Translate');
    return $viewer_id . $translate->getLocale() . sprintf('%d', $viewer->photo_id);
  }

}