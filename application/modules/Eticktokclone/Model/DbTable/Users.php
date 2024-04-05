<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Users.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Model_DbTable_Users extends User_Model_DbTable_Users
{
    protected $_rowClass = "Eticktokclone_Model_User";

    public function followers($params = array()) {
  
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');
    
    $tablenameFollow = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->info('name');
    $select = $table->select()
                  ->from($memberTableName, array('user_id', 'photo_id', 'displayname', 'email'))
                  ->setIntegrityCheck(false)
                  ->joinLeft($tablenameFollow, $tablenameFollow . '.resource_id = ' . $memberTableName . '.user_id AND ' . $tablenameFollow . '.user_id =  ' . $params['user_id'], null)
                  ->where('follow_id IS NOT NULL')
                  ->where('resource_id !=?', $params['user_id'])
                  ->where($memberTableName . '.user_id IS NOT NULL');
    $select->where($memberTableName.".user_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
                  
    return Zend_Paginator::factory($select);
  }

  public function following($params = array()) {
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');
    $tablenameFollow = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->info('name');
    $select = $table->select()
                ->from($memberTableName, array('user_id', 'photo_id', 'displayname', 'email'))
                ->setIntegrityCheck(false)
                ->joinLeft($tablenameFollow, $tablenameFollow . '.user_id = ' . $memberTableName . '.user_id AND ' . $tablenameFollow . '.resource_id =  ' . $params['user_id'], null)
                ->where('follow_id IS NOT NULL')
                ->where($tablenameFollow . '.user_id !=?', $params['user_id'])
                ->where($memberTableName . '.user_id IS NOT NULL');
                 $select->where($memberTableName.".user_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
          
    return Zend_Paginator::factory($select);
  }

}