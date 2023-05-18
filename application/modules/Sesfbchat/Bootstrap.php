<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesfbchat_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesfbchat/controllers/Checklicense.php';
  }

}
