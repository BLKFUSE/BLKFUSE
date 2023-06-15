<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sescontestjurymember_Widget_JuryMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);

    $contest = Engine_Api::_()->core()->getSubject();
    $this->view->results = $results = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->getContestJuryMemberSelect(array('contest_id' => $contest->contest_id, 'fetchAll' => true, 'limit' => $params['limit_data']));
    if (is_countable($results) && engine_count($results) <= 0)
      return $this->setNoRender();
  }

}
