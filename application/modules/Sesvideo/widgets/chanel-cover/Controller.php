<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Widget_ChanelCoverController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
		$chanel_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('chanel_id', 0);

    if ($chanel_id) {
      $chanel_id = Engine_Api::_()->getDbtable('chanels', 'sesvideo')->getChanelId($chanel_id);
    } else {
      return $this->setNoRender();
    }
    $chanel = Engine_Api::_()->getItem('sesvideo_chanel', $chanel_id);
    
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject()) {
      return $this->setNoRender();
    }
		$setting = Engine_Api::_()->getApi('settings', 'core');
		if (!$setting->getSetting('video_enable_chanel', 1)) {
      return $this->setNoRender();
    }
		$this->view->tab =	$this->_getParam('tab','inside');
		$this->view->photo =	$this->_getParam('photo','pPhoto');
		$this->view->option =	$this->_getParam('option',array('report','follow','like','share','delete','edit','favourite','stats','rating','verified','addVideo'));
		
    // Get subject and check auth
    $this->view->subject = $subject = $chanel; //Engine_Api::_()->core()->getSubject('sesvideo_chanel');

		$this->view->video_count = $subject->countVideos();
		$this->view->photo_count = $subject->count();
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->can_edit = 0;
		$this->view->can_delete = 0;
		if($viewer->getIdentity() != 0){
			$this->view->can_edit = $canEdit = $subject->authorization()->isAllowed($viewer, 'edit');
			$this->view->can_delete = $canDelete = $subject->authorization()->isAllowed($viewer, 'delete');
		}
		$this->view->height = $this->_getParam('height_cover','400');
		$this->view->is_fullwidth = $this->_getParam('is_fullwidth','0');
		$this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->viewer_id = $viewer->getIdentity();
		 // rating code
    $this->view->allowShowRating = $allowShowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratechanel.show', 1);
    $this->view->allowRating = $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.chanel.rating', 1);
    $this->view->getAllowRating = $allowRating;
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    } else
      $showRating = true;
    $this->view->showRating = $showRating;
		
    if ($showRating) {
      $this->view->canRate = $canRate = Engine_Api::_()->authorization()->isAllowed('sesvideo_chanel', $viewer, 'rating_chanel');
      $this->view->allowRateAgain = $allowRateAgain = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratechanel.again', 1);
      $this->view->allowRateOwn = $allowRateOwn = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratechanel.own', 1);
      if ($canRate == 0 || $allowRating == 0)
        $allowRating = false;
      else
        $allowRating = true;
      if ($allowRateOwn == 0 && $mine)
        $allowMine = false;
      else
        $allowMine = true;
      $this->view->allowMine = $allowMine;
      $this->view->allowRating = $allowRating;
      $this->view->rating_type = $rating_type = 'sesvideo_chanel';
      $this->view->rating_count = $rating_count = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->ratingCount($subject->getIdentity(), $rating_type);
      $this->view->rated = $rated = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->checkRated($subject->getIdentity(), $viewer->getIdentity(), $rating_type);
      $rating_sum = Engine_Api::_()->getDbTable('ratings', 'sesvideo')->getSumRating($subject->getIdentity(), $rating_type);
      if ($rating_count != 0) {
        $this->view->total_rating_average = $rating_sum / $rating_count;
      } else {
        $this->view->total_rating_average = 0;
      }
      if (!$allowRateAgain && $rated) {
        $rated = false;
      } else {
        $rated = true;
      }
      $this->view->ratedAgain = $rated;
    }

  }
}
