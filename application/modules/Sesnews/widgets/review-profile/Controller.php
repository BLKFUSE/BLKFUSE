<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Widget_ReviewProfileController extends Engine_Content_Widget_Abstract {
  public function indexAction() {

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

		if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1))
		return $this->setNoRender();
    $this->view->stats = isset($params['stats']) ? $params['stats'] : $this->_getParam('stats', array('featured', 'sponsored', 'new', 'likeCount', 'commentCount', 'viewCount', 'title', 'postedBy', 'pros', 'cons', 'description', 'creationDate', 'recommended','parameter','rating','customfields','likeButton', 'socialSharing', 'share'));
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject('sesnews_review'))
      return $this->setNoRender();
    //Get subject and check auth
    $this->view->review = $review = Engine_Api::_()->core()->getSubject();
		$this->view->item = $event = Engine_Api::_()->getItem('sesnews_news', $review->news_id);
  }
}
