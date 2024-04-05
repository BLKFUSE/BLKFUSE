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
class Sesnews_Widget_FavouriteButtonController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewerId = $viewer->getIdentity();
		if (empty($viewerId))
		return $this->setNoRender();
		if (!Engine_Api::_()->core()->hasSubject('sesnews_news') || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.favourite', 1))
		return $this->setNoRender();
		$this->view->sesnews = Engine_Api::_()->core()->getSubject('sesnews_news');
  }

}
