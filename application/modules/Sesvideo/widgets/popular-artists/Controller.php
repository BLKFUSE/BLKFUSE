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

class Sesvideo_Widget_PopularArtistsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->viewType = $this->_getParam('viewType', 'gridview');
    $this->view->height = $this->_getParam('height', 200);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '2');
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		$this->view->information = $this->_getParam('information',array('title','favouriteCount','ratingCount'));
         $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('title', 'favouriteCount', 'ratingCount'));
     if(is_array($show_criterias)){
      foreach ($show_criterias as $show_criteria)
        $this->view->{$show_criteria . 'Active'} = $show_criteria;
    }

    $params = array();
    $params['popularity'] = $this->_getParam('popularity', 'favourite_count');
    $params['limit'] = $this->_getParam('limit', 3);
    $this->view->results = Engine_Api::_()->getDbtable('artists', 'sesvideo')->getArtistsPaginator($params);
    if (engine_count($this->view->results) <= 0)
      return $this->setNoRender();
  }

}
