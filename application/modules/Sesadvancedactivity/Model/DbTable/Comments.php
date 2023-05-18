<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Comments.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_DbTable_Comments extends Core_Model_DbTable_Comments
{
  protected $_rowClass = 'Sesadvancedactivity_Model_Comment';
	protected $_name = 'activity_comments';
  public function getResourceType()
  {
    return 'activity_action';
  }
}