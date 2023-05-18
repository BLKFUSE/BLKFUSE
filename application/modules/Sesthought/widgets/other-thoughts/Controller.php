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

class Sesthought_Widget_OtherThoughtsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    if (Engine_Api::_()->core()->hasSubject('sesthought_thought'))
      $item = Engine_Api::_()->core()->getSubject('sesthought_thought');
    if (!$item)
      return $this->setNoRender();
    $this->view->allParams = $allParams = $this->_getAllParams();
    $this->view->thoughts = Engine_Api::_()->getDbTable('thoughts', 'sesthought')->getThoughtsSelect(array('orderby' => $allParams['popularity'], 'limit' => $allParams['limit'], 'widget' => 1, 'thought_id' => $item->thought_id, 'owner_id' => $item->owner_id));
    if(engine_count($this->view->thoughts) == 0)
      return $this->setNoRender();
  }
}
