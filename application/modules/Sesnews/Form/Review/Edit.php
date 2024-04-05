<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Review_Edit extends Sesnews_Form_Review_Create {

  protected $_defaultProfileId;

  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }

  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }

  public function init() {
		parent::init();
	}

}
