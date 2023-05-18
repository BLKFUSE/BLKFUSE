<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Value.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Model_Value extends Core_Model_Item_Abstract {

  // Properties
  protected $_parent_type = 'value';
  protected $_searchColumns = array('item_id', 'field_id');
  protected $_parent_is_owner = true;

}