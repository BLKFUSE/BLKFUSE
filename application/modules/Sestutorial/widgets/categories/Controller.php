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


class Sestutorial_Widget_CategoriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    $this->view->widgetParams = $this->_getAllParams();
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '3');
    $this->view->showinformation = $this->_getParam('showinformation', array('title'));
    $this->view->mainblockheight = $this->_getParam('mainblockheight', 200);
    $this->view->categoryiconheight = $this->_getParam('categoryiconheight', 75);
    $this->view->categoryiconwidth = $this->_getParam('categoryiconwidth', 75);
    $this->view->resultcategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getCategory();
    if(engine_count($this->view->resultcategories) <= 0)
      return $this->setNoRender();
  }

}
