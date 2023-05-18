<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Widget_RecentlyViewedThoughtController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->allParams = $allParams = $this->_getAllParams();
    $this->view->thoughts = Engine_Api::_()->getDbtable('recentlyviewitems', 'sesthought')->getitem(array('limit' => $allParams['limit'], 'criteria' => $allParams['criteria']));

    if (engine_count($this->view->thoughts) == 0)
      return $this->setNoRender();
  }
}
