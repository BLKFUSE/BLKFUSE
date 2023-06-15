<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: VideoController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_VideoController extends Core_Controller_Action_Standard {

  public function init() {
    // Must be able to use videos
    if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'view')->isValid()) {
      return;
    }

    // Get subject
    $video = null;
    $id = $this->_getParam('video_id', $this->_getParam('id', null));
    if ($id) {
      $video = Engine_Api::_()->getItem('participant', $id);
      if ($video) {
        Engine_Api::_()->core()->setSubject($video);
      }
    }

    // Require subject
    if (!$this->_helper->requireSubject()->isValid()) {
      return;
    }
  }

  public function outerurlAction() {
    // Get subject
    $this->view->video = $video = Engine_Api::_()->core()->getSubject('participant');
    // Get embed code
    $this->view->videoEmbedded = $video->code;
  }

}
