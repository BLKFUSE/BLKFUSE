<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescredit_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  public function __construct($application) {
    parent::__construct($application);
    $this->initViewHelperPath();
  }
}
