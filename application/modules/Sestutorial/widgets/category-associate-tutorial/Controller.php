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



class Sestutorial_Widget_CategoryAssociateTutorialController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
		
    $params['limit'] = $this->_getParam('limit_data', 4);
    $params['hasTutorial'] = 1;
    $this->view->tutorialcriteria = $this->_getParam('tutorialcriteria', 'creation_date');
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '6');
    $this->view->showviewalllink = $this->_getParam('showviewalllink', 1);
    $this->view->resultcategories = Engine_Api::_()->getDbTable('categories', 'sestutorial')->getCategory($params);
		$this->view->limitdatatutorial = $this->_getParam('limitdatatutorial', 5);
		
		$this->view->showviewalllink = $this->_getParam('showviewalllink', 1);
		
    if(engine_count($this->view->resultcategories) <= 0)
      return $this->setNoRender();
		
		$this->view->gridblockheight = $this->_getParam('gridblockheight', 250);
		$this->view->viewtype = $this->_getParam('viewtype', 'listview');
		$params['limit'] = $this->_getParam('limitdatatutorial', 10);
		$params['tutorialcriteria'] = $this->_getParam('tutorialcriteria', 'creation_date');
		$this->view->tutorialtitlelimit = $this->_getParam('tutorialtitlelimit', 60);
		$this->view->tutorialdescriptionlimit = $this->_getParam('tutorialdescriptionlimit', 200);
		$this->view->showinformation = $this->_getParam('showinformation', array('likecount', 'viewcount', 'commentcount', 'ratingcount', 'description', 'readmorelink'));
		//$this->view->paginator = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialPaginator($params);

  }

}

