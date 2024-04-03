<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Widget_BannerSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->widgetParams = $widgetParams = $this->_getAllParams();
    if($widgetParams['limit'] > 0)
      $this->view->tutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect(array('limit' => $widgetParams['limit'], 'order' => $widgetParams['tutorialcriteria'], 'fetchAll' => 1));
  }
}