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



class Sestutorial_Widget_CategoryListingController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    $this->view->limitdatatutorial = $this->_getParam('limitdatatutorial', 5);
    $this->view->showinformation = $this->_getParam('showinformation', array('viewall', 'caticon'));
    $this->view->showtutorialicon = $this->_getParam('showtutorialicon', 1);
    $this->view->tutorialtitlelimit = $this->_getParam('tutorialtitlelimit', 50);
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $params['limit'] = $this->_getParam('limit_data', 4);
    $params['criteria'] = $this->_getParam('criteria', '');
    $params['hasTutorial'] = 1;
    $this->view->tutorialcriteria = $this->_getParam('tutorialcriteria', 'creation_date');
    $this->view->resultcategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getCategory($params);
    if(engine_count($this->view->resultcategories) <= 0)
      return $this->setNoRender();

  }

}
