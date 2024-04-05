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
class Sesnews_Widget_LikeButtonController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewerId = $viewer->getIdentity();
		if (empty($viewerId))
		return $this->setNoRender();
		if (!Engine_Api::_()->core()->hasSubject('sesnews_news'))
		return $this->setNoRender();
		$this->view->subject = $newsItem = Engine_Api::_()->core()->getSubject('sesnews_news');
		$this->view->news_id = $newsItem->getIdentity();
		$this->view->is_like = Engine_Api::_()->getDbTable('likes', 'core')->isLike($newsItem, $viewer);
  }

}
