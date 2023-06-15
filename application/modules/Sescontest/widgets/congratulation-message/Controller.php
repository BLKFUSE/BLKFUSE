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

class Sescontest_Widget_CongratulationMessageController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (!Engine_Api::_()->core()->hasSubject('participant'))
      return $this->setNoRender();
    $this->view->subject = $entry = Engine_Api::_()->core()->getSubject('participant');
    $this->view->contest = $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);
    $this->view->rank = $rank = $entry->rank;
    $awardColumn = 'award' . $rank . '_message';
    if (!($rank) || empty($contest->$awardColumn))
      return $this->setNoRender();
    $this->view->message = $contest->$awardColumn;
  }

}
