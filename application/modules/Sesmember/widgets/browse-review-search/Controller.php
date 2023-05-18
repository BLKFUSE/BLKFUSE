<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_BrowseReviewSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Create form
    $isWidget = $this->_getParam('isWidget', 0);
    $this->view->view_type = $view_type = isset($_POST['view_type']) ? $_POST['view_type'] : $this->_getParam('view_type', 'vertical');
    $viewOptions = $this->_getParam('view');

    $viewOptionsData = array('' => '');
    if(engine_in_array('likeSPcount',$viewOptions))
      $viewOptionsData += ['likeSPcount' => 'Most Liked'];
    if(engine_in_array('viewSPcount',$viewOptions))
      $viewOptionsData += ['viewSPcount' => 'Most Viewed'];
    if(engine_in_array('commentSPcount',$viewOptions))
      $viewOptionsData += ['commentSPcount' => 'Most Commented'];
    if(engine_in_array('mostSPrated',$viewOptions))
      $viewOptionsData += ['mostSPrated' => 'Most Rated'];
    if(engine_in_array('leastSPrated',$viewOptions))
      $viewOptionsData += ['leastSPrated' => 'Least Rated'];
    if(engine_in_array(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first111', 'useful') . 'SPcount',$viewOptions))
      $viewOptionsData += [Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first111', 'useful') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first', 'Useful')];
    if(engine_in_array(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second111', 'funny') . 'SPcount',$viewOptions))
      $viewOptionsData += [Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second111', 'funny') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second', 'Funny')];
    if(engine_in_array(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third111', 'cool') . 'SPcount',$viewOptions))
      $viewOptionsData += [Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third111', 'cool') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third', 'Cool')];
    if(engine_in_array('verified',$viewOptions))
      $viewOptionsData += ['verified' => 'Verified Only'];
    if(engine_in_array('featured',$viewOptions))
      $viewOptionsData += ['featured' => 'Featured Only'];


    if (engine_count($viewOptions))
      $view = true;
    else
      $view = false;
    $this->view->subject_id = $this->_getParam('user_id', 0);
    $this->view->widgetIdentity = $this->_getParam('widgetIdentity', 0);

    $this->view->form = $formFilter = new Sesmember_Form_Review_Browse(array('reviewTitle' => $this->_getParam('review_title', 1),'viewSearch' => $this->_getParam('view_search', 1), 'reviewSearch' => $this->_getParam('review_search', 1), 'reviewStars' => $this->_getParam('review_stars', 1), 'reviewRecommended' =>  $this->_getParam('network', 1)));
    if ($formFilter) {
      if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.votes', '1')) {
        unset($viewOptionsData[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.first11', 'useful') . 'SPcount']);
        unset($viewOptionsData[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.second11', 'funny') . 'SPcount']);
        unset($viewOptionsData[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.third11', 'cool') . 'SPcount']);
      }
    
      if($this->_getParam('view_search', 1)){
      $formFilter->order->setMultiOptions($viewOptionsData);
    }
    }
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->setNoRender();
    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams[$urlParamsKey] = $urlParamsVal;
    }

    $formFilter->populate($urlParams);
    if ($isWidget) {
      $this->getElement()->removeDecorator('Container');
      $formFilter->setAttrib('class', '');
    }
  }

}
