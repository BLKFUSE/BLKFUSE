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

class Eticktokclone_Widget_BlockMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    if(Engine_Api::_()->core()->hasSubject('user'))
			$this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user'); 

    $viewer = Engine_Api::_()->user()->getViewer();
    if(empty($viewer->getIdentity()))
			return $this->setNoRender();
			
    $this->view->isAjax = $isAjax  = $this->_getParam('is_ajax',0);
    
    if($subject->user_id != $viewer->getIdentity())
			return $this->setNoRender();
   
    $blocksTable = Engine_Api::_()->getDbtable('blocks', 'eticktokclone');
    $blocksTableName = $blocksTable->info('name');
    
    $select = $blocksTable->select()
												->from($blocksTableName)
												->where('user_id =?', $subject->user_id);
    

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(10);
    $paginator->setCurrentPageNumber($this->_getParam("page",1));
    $this->view->page = $this->_getParam("page",1);
    
		if($paginator->getTotalItemCount() == 0)
			return $this->setNoRender();

	}
}
