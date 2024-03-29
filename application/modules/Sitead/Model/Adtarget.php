<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Adtarget.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_Adtarget extends Core_Model_Item_Abstract {

  protected $_searchTriggers = false;
  protected $_modifiedTriggers = false;

  // Properties
  public function getTable() {
    if (is_null($this->_table)) {
      $this->_table = Engine_Api::_()->getDbtable('adtargets', 'sitead');
    }

    return $this->_table;
  }

}