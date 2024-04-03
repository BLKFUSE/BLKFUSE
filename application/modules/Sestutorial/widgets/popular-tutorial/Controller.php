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

class Sestutorial_Widget_PopularTutorialController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->showinformation = $this->_getParam('showinformation', array('likecount', 'viewcount', 'commentcount', 'description', 'ratingcount'));
    
    $params['order'] = $this->_getParam('tutorialcriteria', 'creation_date');
    $this->view->tutorialtitlelimit = $this->_getParam('tutorialtitlelimit', 25);
    $this->view->tutorialdescriptionlimit = $this->_getParam('tutorialdescriptionlimit', 50);
    $params['limit'] = $this->_getParam('limitdatatutorial', 3);
    $params['fetchAll'] = 1;
    
    $this->view->tutorials = Engine_Api::_()->getDbTable('tutorials', 'sestutorial')->getTutorialSelect($params);
    
    if(engine_count($this->view->tutorials) <= 0)
      return $this->setNoRender();
  }

}
