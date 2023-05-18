<?php

class Sesmusic_AdminIntegrateothermoduleController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_integrateothermodule');

    $this->view->enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $select = Engine_Api::_()->getDbtable('integrateothermodules', 'sesmusic')->select();

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  //Add New Plugin entry
  public function addmoduleAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_integrateothermodule');

    $this->view->form = $form = new Sesmusic_Form_Admin_Manage_Add();

    $this->view->type = $type = $this->_getParam('type');

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      $integrateothermoduleTable = Engine_Api::_()->getDbtable('integrateothermodules', 'sesmusic');

      $is_module_exists= $integrateothermoduleTable->fetchRow(array('content_type = ?' => $values['content_type'], 'module_name = ?' => $values['module_name']));

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
        $row = $integrateothermoduleTable->createRow();
        $values['type'] = $type;
        $row->setFromArray($values);
        $row->save();

        $modulename = $values['module_name'];
        $dbInsert->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sesmusic_main_browsemusicalbums_'.$row->getIdentity().'", "sesmusic", "Browse Music Albums", "", \'{"route":"sesmusic_browsemusicalbums_'.$row->getIdentity().'","action":"browse-musicalbums", "resource_type":"'.$values['content_type'].'"}\', "'.$modulename.'_main", "", 1, 0, 999)');

        $this->createBrowseMusicAlbumsPage($modulename, $row->getIdentity());

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
  }

  function createBrowseMusicAlbumsPage($modulename, $id) {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();

    $widgetOrder = 1;
    //Music album browse page
    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sesmusic_index_'.$id)
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$page_id) {
      $db->insert('engine4_core_pages', array(
          'name' => 'sesmusic_index_'.$id,
          'displayname' => 'SNS - Professional Music - '.ucfirst($modulename).' Browse Music Albums Page',
          'title' => ucfirst($modulename) .' Browse Music Albums',
          'description' => 'This page lists music albums.',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => 1,
      ));
      $top_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => 2,
      ));
      $main_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_id,
      ));
      $top_middle_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 2,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 1,
      ));
      $main_right_id = $db->lastInsertId();

      //Main Top
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => $modulename.'.browse-menu',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => $widgetOrder++,
      ));

      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesmusic.browse-albums',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => $widgetOrder++,
          'params' => '{"viewType":"gridview","Type":"1","popularity":"creation_date","information":["featured","sponsored","hot","likeCount","commentCount","viewCount","title","postedby","favourite","addplaylist","share"],"height":"220","width":"225","itemCount":"12","title":"","nomobile":"0","name":"sesmusic.browse-albums"}',
      ));

      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesmusic.browse-search',
          'page_id' => $page_id,
          'parent_content_id' => $main_right_id,
          'order' => $widgetOrder++,
          'params' => '{"searchOptionsType":["searchBox","category","view","show","artists"],"title":"","nomobile":"0","name":"sesmusic.browse-search"}',
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

        $inttable = Engine_Api::_()->getItem('sesmusic_integrateothermodule', $this->_getParam('integrateothermodule_id'));
        $pageName = "sesmusic_index_".$this->_getParam('integrateothermodule_id');
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
        Engine_Api::_()->getDbtable('menuItems', 'core')->delete(array('name =?' => 'sesmusic_main_browsemusicalbum_' . $this->_getParam('integrateothermodule_id')));

        $integrateothermodule = Engine_Api::_()->getItem('sesmusic_integrateothermodule', $this->_getParam('integrateothermodule_id'));
        $integrateothermodule->delete();
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
    $this->renderScript('admin-integrateothermodule/delete.tpl');
  }

  //Enable / Disable Action
  public function enabledAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    $content = Engine_Api::_()->getItemTable('sesmusic_integrateothermodule')->fetchRow(array('integrateothermodule_id = ?' => $this->_getParam('integrateothermodule_id')));
    try {

      Engine_Api::_()->getDbtable('menuItems', 'core')->update(array('enabled' => !$content->enabled), array('name =?' => 'sesmusic_main_browsemusicalbum_' . $this->_getParam('integrateothermodule_id')));

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
