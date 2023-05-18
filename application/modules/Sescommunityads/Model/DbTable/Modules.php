<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Modules.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Model_DbTable_Modules extends Engine_Db_Table
{
  protected $_rowClass = 'Sescommunityads_Model_Module';

 function getEnabledModuleNames($params = array()){
   
  
    if (isset($params['column_name']))
      $columnName = $params['column_name'];
    else
      $columnName = '*';
    $select = $this->select()
            ->from($this->info('name'), $columnName);
            
    if (isset($params['module_id']))
      $select = $select->where('module_id = ?', $params['module_id']);
      
    if (isset($params['content_type']))
      $select = $select->where('content_type = ?', $params['content_type']);

    if (isset($params['module_name']))
      $select = $select->where('module_name = ?', $params['module_name']);
      
    if (isset($params['enabled']))
      $select = $select->where('enabled = ?', $params['enabled']);
    if (isset($params['fetchRow']))
      return $this->fetchRow($select);
    return $select->query()->fetchAll();
 }
}