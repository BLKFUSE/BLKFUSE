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

class Sescontest_Widget_MediatypeBannerController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);;
    $this->view->type = $type = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
    $this->view->banner = Engine_Api::_()->getDbTable('medias','sescontest')->getBannerid($type);
  }

}
