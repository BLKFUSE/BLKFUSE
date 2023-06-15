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

class Sescontest_Widget_BannerCategoryController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $value = array();
    $value['order'] = $params['order'];
    $value['info'] = $params['info'];
    $this->view->paginator = array();
    if ($params['show_popular_contests']) {
      $this->view->paginator = Engine_Api::_()->getDbTable('contests', 'sescontest')
              ->getContestSelect(array_merge($value, array('search' => 1, 'fetchAll' => true, 'limit' => 3)));
    }
  }

}
