<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Bootstrap.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  protected function _initFrontController()
  {
    Zend_Controller_Front::getInstance()->registerPlugin(new Siteshare_Plugin_Core);
    $this->initViewHelperPath();
  }

}
