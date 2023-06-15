<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Widget_ProfileInfoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get subject and check auth
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('contest');
    if (!$subject)
      return $this->setNoRender();

    $this->view->contestTags = $subject->tags()->getTagMaps();
    $this->view->criteria = $this->_getParam('criteria', array('date', 'entryDate', 'voteDate', 'mediaType', 'categories', 'like', 'comment', 'favourite', 'view', 'follow', 'owner', 'entryCount', 'tag', 'creationDate'));
  }

}
