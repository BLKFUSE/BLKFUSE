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

class Sesdating_Widget_CustomNavigationMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    $this->view->backgroundimage = $this->_getParam('backgroundimage', '');
    $this->view->height = $this->_getParam('height','150');
    $this->view->textalignment = $this->_getParam('textalignment', 'center');
    
  }

}
