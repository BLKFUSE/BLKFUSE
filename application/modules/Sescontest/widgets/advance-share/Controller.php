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

class Sescontest_Widget_AdvanceShareController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $viewr_id = $viewer->getIdentity();
    if (!Engine_Api::_()->core()->hasSubject())
      return $this->setNoRender();
    $this->view->allowAdvShareOptions = $allowAdvShareOptions = $this->_getParam('advShareOptions', array('privateMessage', 'siteShare', 'quickShare', 'addThis', 'tellAFriend'));
    if (!$viewr_id && !engine_in_array('tellAFriend', $allowAdvShareOptions) && (!engine_in_array('addThis', $allowAdvShareOptions) || !Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.addthis', 0))) {
      return $this->setNoRender();
    }
  }

}
