<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: MediaController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_MediaController extends Core_Controller_Action_Standard {

  public function textAction() {
    // Render
    $this->_helper->content->setNoRender()->setEnabled();
  }
  public function photoAction() {
    // Render
    $this->_helper->content->setNoRender()->setEnabled();
  }
  public function videoAction() {
    // Render
    $this->_helper->content->setNoRender()->setEnabled();
  }public function audioAction() {
    // Render
    $this->_helper->content->setNoRender()->setEnabled();
  }
  
}
