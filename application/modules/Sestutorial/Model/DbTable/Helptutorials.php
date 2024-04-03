<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Helptutorials.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Model_DbTable_Helptutorials extends Engine_Db_Table {

  protected $_rowClass = 'Sestutorial_Model_Helptutorial';
  
  public function checkHelpful($tutorial_id, $user_id) {
  
    $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->where('tutorial_id = ?', $tutorial_id)
                    ->where('user_id = ?', $user_id)
                    ->limit(1);
    $row = $this->fetchAll($select);
    if (engine_count($row) > 0) return true;
      return false;
  }
  
  public function getHelpfulvalue($tutorial_id, $user_id) {
  
    return $this->select()
      ->from($this->info('name'), 'helpfultutorial')
      ->where('user_id = ?', $user_id)
      ->where('tutorial_id = ?', $tutorial_id)
      ->query()
      ->fetchColumn(0);
  }

  public function helpfulCount($tutorial_id, $helpfultutorial) {

    $select = $this->select()
                    ->from($this->info('name'))
                    ->where('tutorial_id = ?', $tutorial_id)
                    ->where('helpfultutorial = ?', $helpfultutorial);
    $row = $this->fetchAll($select);
    $total = engine_count($row);
    return $total;
  }
  
  public function setHelpful($tutorial_id, $user_id, $reason_id, $helpfultutorial) {
  
    $tutorial_item = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
    
    $table  = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial');
    $rName = $table->info('name');

    $select = $table->select()
                    ->from($rName)
                    ->where($rName.'.tutorial_id = ?', $tutorial_id)
                    ->where($rName.'.user_id = ?', $user_id);
    $row = $table->fetchRow($select);
    if (empty($row)) {
      Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->insert(array('tutorial_id' => $tutorial_id, 'user_id' => $user_id, 'reason_id' => $reason_id, 'helpfultutorial' => $helpfultutorial));
      
      if($helpfultutorial == 1) {
        $tutorial_item->helpful_count++;
        $tutorial_item->save();
      } else if($helpfultutorial == 2) {
        if($tutorial_item->helpful_count > 0) {
          $tutorial_item->helpful_count--;
          $tutorial_item->save();
        }
      }
    } else {
      Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->update(array('modified_date' => new Zend_Db_Expr('NOW()'), 'helpfultutorial' => $helpfultutorial, 'reason_id' => $reason_id),array('tutorial_id = ?' => $tutorial_id, 'user_id = ?' => $user_id));
      
      if($helpfultutorial == 1) {
        $tutorial_item->helpful_count++;
        $tutorial_item->save();
      } else if($helpfultutorial == 2) {
        if($tutorial_item->helpful_count > 0) {
          $tutorial_item->helpful_count--;
          $tutorial_item->save();
        }
      }
    }
  }
  
}
