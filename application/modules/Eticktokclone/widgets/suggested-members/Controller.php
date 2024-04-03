<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_Widget_SuggestedMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $limit = $this->_getParam('limit', 10);
      
    $table = Engine_Api::_()->getDbtable('users', 'eticktokclone');
    $select = $table->select()
            ->where('search = ?', 1)
            ->where('enabled = ?', 1)
            ->where('user_id <> ?', $viewer_id)
            ->order('Rand()')
            ->limit($limit);

    $select->where("engine4_users.user_id NOT IN (SELECT CASE blocked_user_id
            WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
    if($viewer_id) {
      $select->where('user_id <> ?', $viewer_id);
    }
    
    $this->view->results = $results = $table->fetchAll($select);
    if(engine_count($results) == 0) 
      return $this->setNoRender();
  }
}
