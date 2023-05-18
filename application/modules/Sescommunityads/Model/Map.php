<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Map.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_Map extends Core_Model_Item_Abstract {

  // Properties
  protected $_parent_type = 'map';
  protected $_searchColumns = array('option_id', 'child_id',);
  protected $_parent_is_owner = true;

}