<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjurymember_IndexController extends Core_Controller_Action_Standard {

  public function deleteMemberAction() {
    $memberId = $this->_getParam('id', 0);
    $contest = Engine_Api::_()->getItem('contest', $this->_getParam('contest_id', 0));
    $this->view->form = $form = new Sescontestjurymember_Form_Deletemember();
    if (!$this->getRequest()->isPost() || !$form->isValid($this->getRequest()->getPost()))
      return;
    $juryMember = Engine_Api::_()->getItem('sescontestjurymember_member', $memberId);
    $juryMember->delete();
    return $this->_helper->redirector->gotoRoute(array('action' => 'jury-members', 'contest_id' => $contest->custom_url), "sescontest_dashboard", true);
  }

}
