<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Members.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjurymember_Model_DbTable_Members extends Engine_Db_Table {

  protected $_rowClass = "Sescontestjurymember_Model_Member";

  public function getContestJuryMemberPaginator($params = array()) {
    return Zend_Paginator::factory($this->getContestJuryMemberSelect($params));
  }

  public function getContestJuryMemberSelect($params = array()) {
    $userTable = Engine_Api::_()->getItemTable('user');
    $tableUserName = $userTable->info('name');
    $contestsTableName = $this->info('name');
    $select = $userTable->select()
            ->setIntegrityCheck(false)
            ->from($tableUserName, array('user_id', 'displayname', 'photo_id'))
            ->joinLeft($contestsTableName, "$tableUserName.user_id = $contestsTableName.user_id", 'member_id')
            ->where($contestsTableName . '.contest_id =?', $params['contest_id']);
    if (isset($params['fetchAll'])) {
      $select->limit($params['limit']);
      return $this->fetchAll($select);
    } else
      return $select;
  }

  public function getJuryMembers($params = array()) {
    return $this->select()
                    ->from($this->info('name'), 'COUNT(member_id)')
                    ->where('contest_id =?', $params['contest_id'])
                    ->query()
                    ->fetchColumn();
  }

  public function isJuryMember($params = array()) {
    return $this->select()
                    ->from($this->info('name'), 'member_id')
                    ->where('user_id =?', $params['user_id'])
                    ->where('contest_id =?', $params['contest_id'])
                    ->query()
                    ->fetchColumn();
  }

}
