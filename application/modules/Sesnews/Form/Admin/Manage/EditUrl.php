<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EditUrl.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesnews_Form_Admin_Manage_EditUrl extends Sesnews_Form_Admin_Manage_AddUrl {

  public function init() {
    parent::init();
    $this->submit->setLabel('Save Changes');
  }
}
