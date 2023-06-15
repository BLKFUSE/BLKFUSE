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
class Sescontest_Widget_PopularCategoriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $widgetParams = $this->_getAllParams();
    $this->view->widgetParams = $widgetParams;
    $this->view->resultcategories = Engine_Api::_()->getDbTable('categories', 'sescontest')->getCategory(array('limit' => $widgetParams['limit_data'], 'criteria' => $widgetParams['criteria']));
    if (is_countable($this->view->resultcategories) && engine_count($this->view->resultcategories) <= 0)
      return $this->setNoRender();
  }

}
