<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Option.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Model_Option extends Core_Model_Item_Abstract {

  // Properties
  protected $_parent_type = 'option';
  protected $_searchColumns = array('option_id');
  protected $_parent_is_owner = true;

}