<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  protected function _initFrontController()
  {
    $front = Zend_Controller_Front::getInstance();
    $front->registerPlugin(new Sitead_Plugin_FrontController);
  }
}