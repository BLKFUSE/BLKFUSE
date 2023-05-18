<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Widget_ViewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get navigation menu

    $sescommunityad_id = isset($_POST['sescommunityad_id']) ? $_POST['sescommunityad_id'] : 0;
    if ($sescommunityad_id) {
      $subject = Engine_Api::_()->getItem('sescommunityads', $sescommunityad_id);
    } else {
      if (!Engine_Api::_()->core()->hasSubject())
        return $this->setNoRender();
      else
        $subject = Engine_Api::_()->core()->getSubject();
    }

    $this->view->ad = $subject;

  }
}
