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
class Sescontest_Widget_OtherContestController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (!Engine_Api::_()->core()->hasSubject('contest'))
      return $this->setNoRender();

    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('contest');

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    foreach ($params['show_criteria'] as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $values['limit'] = $params['limit_data'];
    $values['other-contest'] = 1;
    $values['contest_id'] = $subject->getIdentity();
    $values['user_id'] = $subject->user_id;
    $values['fetchAll'] = true;
    $this->view->contests = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($values);
    if (engine_count($this->view->contests) <= 0)
      return $this->setNoRender();
  }

}
