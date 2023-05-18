<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Locations.php 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Locations extends Engine_Db_Table {

  protected $_name = 'sescommunityads_locations';
  protected $_rowClass = 'Sescommunityads_Model_Location';

  function getLocationData($resource_type = 'sesalbum_album', $resource_id = '') {
    $lName = $this->info('name');
    $select = $this->select()
            ->from($lName)
            ->where('resource_id = ?', $resource_id)
            ->where('resource_type =?', $resource_type);
    $row = $this->fetchRow($select);
    return $row;
  }
}
