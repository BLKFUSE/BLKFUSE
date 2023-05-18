<?php
/**

**/
class Eweblivestreaming_Widget_LiveMembersController extends Engine_Content_Widget_Abstract
{
  public function indexAction()
  {
    $this->view->live_icon = $this->_getParam("live_icon",1);
    $table = Engine_Api::_()->getDbTable("elivehosts",'elivestreaming');
    $tableName = $table->info("name");

    $userTable = Engine_Api::_()->getItemTable("user");
    $userTableName = $userTable->info("name");
    
    $select = $userTable->select()->from($userTableName,'*')->setIntegrityCheck(false);
    $select->joinInner($tableName,$tableName.'.user_id = '.$userTableName.'.user_id',array('max_elivehost_id'=>new Zend_Db_Expr("MAX(elivehost_id)"),'max_story_id'=>new Zend_Db_Expr("MAX(story_id)"),'max_action_id'=>new Zend_Db_Expr("MAX(action_id)")));
    $select->where($tableName.'.status =?','started');
  //$select->where('DATE_ADD('.$tableName.'.datetime, INTERVAL 4 HOUR) >= NOW()');
    
    $select->limit($this->_getParam("limit",15));
    $select->group($tableName.'.user_id');
    $this->view->users = $users = $userTable->fetchAll($select);
//    echo "<prE>";var_dump($users);die;

    if(engine_count($users) == 0){
      return $this->setNoRender();
    }

  }
}
