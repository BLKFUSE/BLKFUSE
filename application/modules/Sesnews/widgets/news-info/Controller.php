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

class Sesnews_Widget_NewsInfoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject())
      return $this->setNoRender();

    $subject = $this->view->subject = Engine_Api::_()->core()->getSubject();
		$customMetaFields = $this->view->customMetaFields = Engine_Api::_()->sesnews()->getCustomFieldMapDataNews($subject);
    if (!engine_count($customMetaFields)) {
      return $this->setNoRender();
    }
  }
}
