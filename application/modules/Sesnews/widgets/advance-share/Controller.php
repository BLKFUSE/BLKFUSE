<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Widget_AdvanceShareController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject() ||  !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.sharing', 1))
    return $this->setNoRender();
    $this->view->allowAdvShareOptions = $this->_getParam('advShareOptions',array('privateMessage','siteShare','quickShare'));
  }
}
