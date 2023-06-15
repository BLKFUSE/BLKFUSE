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

class Sescontest_Widget_ContestContactInformationController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject('contest');
    if (!$subject) {
      return $this->setNoRender();
    }
    $this->view->info = $this->_getParam('show_criteria', array('name', 'email', 'phone', 'facebook', 'linkedin', 'twitter', 'website'));
    if (!$subject->contest_contact_name && !$subject->contest_contact_email && !$subject->contest_contact_phone && !$subject->contest_contact_website && !$subject->contest_contact_facebook && !$subject->contest_contact_twitter && !$subject->contest_contact_linkedin)
      return $this->setNoRender();
    $this->view->subject = $subject;
  }

}
