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
class Sesdating_Widget_LpTwoCountersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->allParams = $allParams = $this->_getAllParams();

    if(empty($allParams))
        return $this->setNoRender();

  }
}
