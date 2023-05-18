<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesuserimport_Bootstrap extends Engine_Application_Bootstrap_Abstract {

    protected function _initFrontController() {
        include APPLICATION_PATH . '/application/modules/Sesuserimport/controllers/Checklicense.php';
    }
}
