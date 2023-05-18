<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminManageController.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_AdminManageController extends Core_Controller_Action_Admin
{
  public function indexAction() {
    $this->view->navigation  = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('egames_admin_main', array(), 'egames_admin_main_manage');
		$this->view->formFilter = $formFilter = new Egames_Form_Admin_Manage_Filter();
		$this->view->category_id=isset($_GET['category_id']) ?  $_GET['category_id'] : 0;
		$this->view->subcat_id=isset($_GET['subcat_id']) ?  $_GET['subcat_id'] : 0;
		$this->view->subsubcat_id=isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : 0;
		// Process form
    $values = array();
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $values = $formFilter->getValues();
    }
    foreach( $_GET as $key => $value ) {
      if( '' === $value ) {
        unset($_GET[$key]);
      }else
				$values[$key]=$value;
    }
    if( $this->getRequest()->isPost() ) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $game = Engine_Api::_()->getItem('egames_game', $value);
          if($game)
            $game->delete();
        }
      }
    }
		$tableAlbum = Engine_Api::_()->getDbtable('games', 'egames');
		$tableAlbumName = $tableAlbum->info('name');
		$tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $select = $tableAlbum->select()
													->from($tableAlbumName)
												 ->setIntegrityCheck(false)
												 ->joinLeft($tableUserName, "$tableUserName.user_id = $tableAlbumName.owner_id", 'username');
		$select->order('game_id DESC'); 
		// Set up select info
		if( isset($_GET['category_id']) && $_GET['category_id'] != 0)
      $select->where($tableAlbumName.'.category_id = ?', $values['category_id'] );
    
		if( isset($_GET['subcat_id']) && $_GET['subcat_id'] != 0) 
      $select->where($tableAlbumName.'.subcat_id = ?',  $values['subcat_id']);
    
		if( isset($_GET['subsubcat_id']) && $_GET['subsubcat_id'] != 0) 
      $select->where($tableAlbumName.'.subsubcat_id = ?', $values['subsubcat_id']);
    
    if( !empty($_GET['title']) ) 
      $select->where($tableAlbumName.'.title LIKE ?', '%' . $values['title'] . '%');
    
    
    if( !empty($values['creation_date']) ) 
      $select->where('date('.$tableAlbumName.'.creation_date) = ?', $values['creation_date'] );
    
		 
		if (!empty($_GET['owner_name']))
      $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');
		   
    $page = $this->_getParam('page', 1);
		
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber( $page );
  }
  public function viewAction() {
    $this->view->type = $type = $this->_getParam('type', 1);
    $id = $this->_getParam('id', 1);
    
      $item = Engine_Api::_()->getItem('egames_game', $id);
    $this->view->item = $item;
  }
	
  public function deleteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->game_id = $id = $this->_getParam('id');
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try
      {
        $game = Engine_Api::_()->getItem('egames_game', $id);
        // delete the game in the database
        $game->delete();
        $db->commit();
      }
      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh'=> 10,
          'messages' => array('Game deleted successfully.')
      ));
    }
    // Output
    $this->renderScript('admin-manage/delete.tpl');
  }
	
}