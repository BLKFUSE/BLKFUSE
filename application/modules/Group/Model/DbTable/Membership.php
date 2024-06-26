<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Membership.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Model_DbTable_Membership extends Core_Model_DbTable_Membership
{
  protected $_type = 'group';


  // Configuration

  /**
   * Does membership require approval of the resource?
   *
   * @param Core_Model_Item_Abstract $resource
   * @return bool
   */
  public function isResourceApprovalRequired(Core_Model_Item_Abstract $resource)
  {
    return $resource->approval;
  }
  
  public function hasMember($group_id) {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    return $this->select()
                ->from($this->info('name'), 'notification')
                ->where('resource_id =?', $group_id)
                ->where('user_id =?', $viewer_id)
                ->query()
                ->fetchColumn();
  }
}
