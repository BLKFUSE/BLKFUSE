<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Maps.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Maps extends Engine_Db_Table {

  protected $_name = 'user_fields_maps';
  protected $_rowClass = 'Sescommunityads_Model_Map';

  public function getProfileFields($params = array()){
    $metaTable = Engine_Api::_()->getDbTable('metas','sescommunityads');
    $metaTableName = $metaTable->info('name');
    $select = $metaTable->select()->from($metaTableName,'*')->setIntegrityCheck(false);
    $table = $this->info('name');
    $select->joinLeft($table,$metaTableName.'.field_id ='.$table.'.child_id',array('option_id'));
    
    if(!empty($params['profile_id']))
      $select->where($this->info('name').'.option_id =?',$params['profile_id']);  
    return $metaTable->fetchAll($select);
  }
}