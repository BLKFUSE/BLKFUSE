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

class Sescontest_Widget_ContestQuickCreateController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if (!Engine_Api::_()->core()->hasSubject('contest'))
      return $this->setNoRender();
    $this->view->contest_id = Engine_Api::_()->core()->getSubject()->contest_id;
  }

}
