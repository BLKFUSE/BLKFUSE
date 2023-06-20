<?php

class Sesalbum_AdminIntegrateothersmodulesController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_integrateothersmodules');

    $this->view->enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $select = Engine_Api::_()->getDbtable('integrateothersmodules', 'sesalbum')->select();

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  //Add New Plugin entry
  public function addmoduleAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesalbum_admin_main', array(), 'sesalbum_admin_main_integrateothersmodules');

    $this->view->form = $form = new Sesalbum_Form_Admin_Manage_AddModules();

    $this->view->type = $type = $this->_getParam('type');

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      $integrateothersmodulesTable = Engine_Api::_()->getDbtable('integrateothersmodules', 'sesalbum');

      $is_module_exists= $integrateothersmodulesTable->fetchRow(array('content_type = ?' => $values['content_type'], 'module_name = ?' => $values['module_name']));

      if (!empty($is_module_exists)) {
        $error = Zend_Registry::get('Zend_Translate')->_("This Module already exist in our database.");
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      $contentTypeItem = Engine_Api::_()->getItemTable($values['content_type']);

			//get current content type item id
      $primaryId = current($contentTypeItem->info("primary"));

			//get primary key for content type
      if (!empty($primaryId))
        $values['content_id'] = $primaryId;

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $dbInsert = Engine_Db_Table::getDefaultAdapter();
      try {
        $row = $integrateothersmodulesTable->createRow();
        $values['type'] = $type;
        $row->setFromArray($values);
        $row->save();

        $modulename = $values['module_name'];
        $dbInsert->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sesalbum_main_browsealbum_'.$row->getIdentity().'", "sesalbum", "Browse Albums", "", \'{"route":"sesalbum_browsealbum_'.$row->getIdentity().'","action":"browse-albums", "resource_type":"'.$values['content_type'].'"}\', "'.$modulename.'_main", "", 1, 0, 999)');

        $this->createBrowseAlbumPage($modulename, $row->getIdentity());

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
  }

  function createBrowseAlbumPage($modulename, $id) {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    //Album Browse Page
    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sesalbum_index_'.$id)
            ->limit(1)
            ->query()
            ->fetchColumn();
    // insert if it doesn't exist yet
    if (!$page_id) {
      // Insert page
      $db->insert('engine4_core_pages', array(
        'name' => 'sesalbum_index_'.$id,
        'displayname' => 'SNS - Advanced Albums - '.ucfirst($modulename).' Albums Browse Page',
        'title' => ucfirst($modulename) .' Album Browse',
        'description' => 'This page lists albums.',
        'custom' => 0,
      ));
      $page_id = $db->lastInsertId();
      // Insert top
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => 1,
      ));
      $top_id = $db->lastInsertId();
      // Insert main
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => 2,
      ));
      $main_id = $db->lastInsertId();
      // Insert top-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_id,
          'order' => 6
      ));
      $top_middle_id = $db->lastInsertId();
      // Insert main-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 6
      ));
      $main_middle_id = $db->lastInsertId();
      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => $modulename.'.browse-menu',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => 3,
      ));
      // Insert search
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.browse-search',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => 4,
          'params' => '{"search_for":"album","view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","mostSPrated","mostSPfavourite","featured","sponsored"],"default_search_type":"mostSPliked","friend_show":"yes","search_title":"yes","browse_by":"yes","categories":"yes","location":"yes","kilometer_miles":"yes","title":"","nomobile":"0","name":"sesalbum.browse-search"}'
      ));
      // Insert content
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesalbum.browse-albums',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => 7,
          'params' => '{"load_content":"auto_load","sort":"mostSPliked","view_type":"2","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","featured","sponsored","likeButton","favouriteButton"],"title_truncation":"30","limit_data":"21","height":"240","width":"395","title":"","nomobile":"0","name":"sesalbum.browse-albums"}'
      ));
    }
  }

  //Delete entry
  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        $inttable = Engine_Api::_()->getItem('sesalbum_integrateothersmodule', $this->_getParam('integrateothersmodule_id'));
        $pageName = "sesalbum_index_".$this->_getParam('integrateothersmodule_id');
        if (!empty($pageName)) {
          $page_id = $db->select()
                  ->from('engine4_core_pages', 'page_id')
                  ->where('name = ?', $pageName)
                  ->limit(1)
                  ->query()
                  ->fetchColumn();
          if($page_id) {
            Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id =?' => $page_id));
            Engine_Api::_()->getDbTable('pages', 'core')->delete(array('page_id =?' => $page_id));
          }
        }
        Engine_Api::_()->getDbtable('menuItems', 'core')->delete(array('name =?' => 'sesalbum_main_browsealbum_' . $this->_getParam('integrateothersmodule_id')));

        $integrateothersmodules = Engine_Api::_()->getItem('sesalbum_integrateothersmodule', $this->_getParam('integrateothersmodule_id'));
        $integrateothersmodules->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('You have successfully delete entry.')
      ));
    }
    $this->renderScript('admin-integrateothersmodules/delete.tpl');
  }

  //Enable / Disable Action
  public function enabledAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $content = Engine_Api::_()->getItemTable('sesalbum_integrateothersmodule')->fetchRow(array('integrateothersmodule_id = ?' => $this->_getParam('integrateothersmodule_id')));
    try {

      Engine_Api::_()->getDbtable('menuItems', 'core')->update(array('enabled' => !$content->enabled), array('name =?' => 'sesalbum_main_browsealbum_' . $this->_getParam('integrateothersmodule_id')));

      $content->enabled = !$content->enabled;
      $content->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }
}
