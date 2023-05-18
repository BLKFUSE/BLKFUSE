<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesdating_Widget_LpTwoMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->newest_members = Engine_Api::_()->sesdating()->getMembers('Newest');
    $this->view->popular_members = Engine_Api::_()->sesdating()->getMembers('Popular');
    $this->view->active_members = Engine_Api::_()->sesdating()->getMembers('Active');
  }
}
