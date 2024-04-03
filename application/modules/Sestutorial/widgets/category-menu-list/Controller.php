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

class Sestutorial_Widget_CategoryMenuListController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->resultcategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getCategory($params);
    if(engine_count($this->view->resultcategories) <= 0)
      return $this->setNoRender();
  }

}
