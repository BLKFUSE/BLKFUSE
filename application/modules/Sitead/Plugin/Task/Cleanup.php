<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Cleanup.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Plugin_Task_Cleanup extends Core_Plugin_Task_Abstract {

  public function execute() {

    if (date('Y-m-d', strtotime(Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.update.approved'))) < date('Y-m-d')) {
      Engine_Api::_()->getDbtable('userads', 'sitead')->updateApproved();
    }
  }

}