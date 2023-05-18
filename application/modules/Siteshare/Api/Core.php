<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Api_Core extends Core_Api_Abstract {

  public function isSiteMobileModeEnabled() {
    return $this->checkSitemobileMode('tablet-mode') || $this->checkSitemobileMode('mobile-mode');
  }

  public function checkSitemobileMode($mode = 'fullsite-mode') {
    if (Engine_Api::_()->hasModuleBootstrap('sitemobile')) {
      return (bool) (Engine_API::_()->sitemobile()->getViewMode() === $mode);
    } else {
      return (bool) ('fullsite-mode' === $mode);
    }
  }
}
