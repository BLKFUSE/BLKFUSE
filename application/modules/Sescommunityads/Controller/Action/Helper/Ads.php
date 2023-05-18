<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Ads.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sescommunityads_Controller_Action_Helper_Ads extends Zend_Controller_Action_Helper_Abstract {
  public function preDispatch() {
    $front = Zend_Controller_Front::getInstance();
    $key = Engine_Api::_()->sescommunityads()->getKey($front);
    if(!empty($_SESSION[$key] ))
      unset($_SESSION[$key]);
    $_SESSION[$key] = array();
    $_SESSION[$key."_stop"] = false;
  }
}