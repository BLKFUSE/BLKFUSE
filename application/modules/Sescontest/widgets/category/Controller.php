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

class Sescontest_Widget_CategoryController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);

    if (!empty($_GET['category_id']))
      $this->view->category_id = $_GET['category_id'];
    else
      $this->view->category_id = 0;

    $this->view->categories = Engine_Api::_()->getDbtable('categories', 'sescontest')->getCategory(array('column_name' => '*', 'limit' => $params['count_category']));
    if (engine_count($this->view->categories) <= 0)
      return $this->setNoRender();
  }

}
