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

class Sescontest_Widget_CategoryCarouselController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $value = array();
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $this->view->criteria = $value['criteria'] = $params['criteria'];
    $sescontest_categories = Zend_Registry::isRegistered('sescontest_categories') ? Zend_Registry::get('sescontest_categories') : null;
    if(empty($sescontest_categories)) {
      return $this->setNoRender();
    }
    if ($params['limit_data'])
      $value['limit'] = $params['limit_data'];
    $this->view->paginator = Engine_Api::_()->getDbTable('categories', 'sescontest')->getCategory($value);
    if (is_countable($this->view->paginator) && engine_count($this->view->paginator) == 0)
      return $this->setNoRender();
  }

}
