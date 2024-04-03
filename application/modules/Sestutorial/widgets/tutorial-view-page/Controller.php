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

class Sestutorial_Widget_TutorialViewPageController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    if( !Engine_Api::_()->core()->hasSubject() ) {
      return $this->setNoRender();
    }
    $subject = Engine_Api::_()->core()->getSubject();
    $this->view->tutorial = $subject;
    $this->view->tutorialTags = $subject->tags()->getTagMaps();
    $this->view->categoriesTable = Engine_Api::_()->getDbTable('categories', 'sestutorial');

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->showinformation = $this->_getParam('showinformation', array('likecount', 'viewcount', 'commentcount', 'ratingcount', 'category', 'tags', 'socialshare', 'siteshare', 'showhelpful'));
    
    $this->view->canRate = Engine_Api::_()->authorization()->isAllowed('sestutorial_tutorial', $viewer, 'rating');
    $this->view->canhelpful = Engine_Api::_()->authorization()->isAllowed('sestutorial_tutorial', $viewer, 'helpful');
    
    $this->view->rating_count = Engine_Api::_()->sestutorial()->ratingCount($subject->getIdentity());
    $this->view->rated = Engine_Api::_()->sestutorial()->checkRated($subject->getIdentity(), $viewer->getIdentity());
  }

}
