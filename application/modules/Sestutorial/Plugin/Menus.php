<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Menus.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Plugin_Menus {

  public function askquestion() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $authorizationApi = Engine_Api::_()->authorization();
    if (!$authorizationApi->isAllowed('sestutorial_tutorial', $viewer, 'view'))
      return false;
    if (!$authorizationApi->isAllowed('sestutorial_tutorial', $viewer, 'askquestion'))
      return false;
    return true;
  }
}