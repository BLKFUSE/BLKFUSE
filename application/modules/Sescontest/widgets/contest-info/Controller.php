<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Widget_ContestInfoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject('contest');
    if (!$subject) {
      return $this->setNoRender();
    }
    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;
    if(empty($sescontest_widget)) {
      return $this->setNoRender();
    }
    $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $this->view->subject = $subject;
    $this->view->contestTags = $subject->tags()->getTagMaps();
  }

}
