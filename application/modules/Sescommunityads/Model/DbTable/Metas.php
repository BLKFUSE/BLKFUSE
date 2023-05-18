<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Metas.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Metas extends Engine_Db_Table {

  protected $_name = 'user_fields_meta';
  protected $_rowClass = 'Sescommunityads_Model_Meta';

  function getUserProfileFieldId(){
    $viewer = Engine_Api::_()->user()->getViewer();
    if(!$viewer->getIdentity())
      return 0;
    $viewer_id = $viewer->getIdentity();
    $metaTableName = $this->info('name');
    $valueTable = Engine_Api::_()->getDbTable('values', 'sescommunityads');
    $valueTableName = $valueTable->info('name');
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($metaTableName, array($valueTableName . '.value'))
                    ->joinLeft($valueTableName, $valueTableName . '.field_id = ' . $metaTableName . '.field_id', null)
                    ->where($valueTableName . '.item_id = ?', $viewer_id)
                    ->where($valueTableName . '.field_id = ?', 1);
    $profile = $this->fetchRow($select);
    return $profile ? $profile->value : 0;
  }
}