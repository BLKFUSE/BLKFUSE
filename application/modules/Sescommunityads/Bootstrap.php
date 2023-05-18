<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
   public function __construct($application) {
    parent::__construct($application);

    define("SESCOMMUNITYADS", "1");

  }
  protected function _initFrontController() {
    $this->initActionHelperPath();
    Zend_Controller_Action_HelperBroker::addHelper(new Sescommunityads_Controller_Action_Helper_Ads());

    include APPLICATION_PATH . '/application/modules/Sescommunityads/controllers/Checklicense.php';
  }
}
