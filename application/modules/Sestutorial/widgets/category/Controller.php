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



class Sestutorial_Widget_CategoryController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->image = $image = $this->_getParam('image', 1);
    $this->view->storage = Engine_Api::_()->storage();

    $this->view->categories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getCategory();
    
    if(engine_count($this->view->categories) <= 0)
      return $this->setNoRender();
  }

}
