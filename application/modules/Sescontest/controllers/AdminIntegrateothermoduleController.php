<?php
class Sescontest_AdminIntegrateothermoduleController extends Core_Controller_Action_Admin {
  public function indexAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_integrateothermodule');
    $this->view->enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    $select = Engine_Api::_()->getDbtable('integrateothermodules', 'sescontest')->select();
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
  //Add New Plugin entry
  public function addmoduleAction() {
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontest_admin_main_integrateothermodule');
    $this->view->form = $form = new Sescontest_Form_Admin_Manage_Add();
    $this->view->type = $type = $this->_getParam('type');
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $integrateothermoduleTable = Engine_Api::_()->getDbtable('integrateothermodules', 'sescontest');
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
			//get primary key for content type photo
//       $contentTypeItem = Engine_Api::_()->getItemTable($values['content_type_photo']);
// 			//get primary key for content type photo
//       $primaryId = current($contentTypeItem->info("primary"));
//       if (!empty($primaryId))
//         $values['content_id_photo'] = $primaryId;
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $dbInsert = Engine_Db_Table::getDefaultAdapter();
      try {
        $row = $integrateothermoduleTable->createRow();
        $values['type'] = $type;
        $row->setFromArray($values);
        $row->save();

//         $modulename = $values['module_name'];
//         $dbInsert->query('INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("sescontest_main_browsecontest_'.$row->getIdentity().'", "sescontest", "Browse Contests", "", \'{"route":"sescontest_browsecontest_'.$row->getIdentity().'","action":"browse-contests", "resource_type":"'.$values['content_type'].'"}\', "'.$modulename.'_main", "", 1, 0, 999)');
// 
//         $this->createBrowseContestPage($modulename, $row->getIdentity());

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
  }

  function createBrowseContestPage($modulename, $id) {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    //SNS - Contest Browse Page
    $pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sescontest_index_'.$id)
            ->limit(1)
            ->query()
            ->fetchColumn();

    // insert if it doesn't exist yet
    if (!$pageId) {
      $widgetOrder = 1;
      // Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'sescontest_index_'.$id,
          'displayname' => 'SNS - Advanced Contests - '.ucfirst($modulename).' Contests Browse Page',
          'title' => ucfirst($modulename) .' Contest Browse',
          'description' => 'This page lists contests.',
          'custom' => 0,
      ));
      $pageId = $db->lastInsertId();
      // Insert top
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $pageId,
          'order' => 1,
      ));
      $topId = $db->lastInsertId();
      // Insert main
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $pageId,
          'order' => 2,
      ));
      $mainId = $db->lastInsertId();
      // Insert top-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $pageId,
          'parent_content_id' => $topId,
      ));
      $topMiddleId = $db->lastInsertId();
      // Insert main-middle
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $pageId,
          'parent_content_id' => $mainId,
          'order' => 2,
      ));
      $mainMiddleId = $db->lastInsertId();
      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => $modulename.'.browse-menu',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => $widgetOrder++,
      ));

      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.browse-search',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPcommented","entrymaxtomin","entrymintomax","mostSPfavourite","mostSPfollower","featured","sponsored","verified","hot"],"default_search_type":"recentlySPcreated","criteria":["0","1","2","3","today","tomorrow","week","nextweek","month"],"show_option":["searchContestTitle","view","browseBy","mediaType","chooseDate","Categories"],"title":"","nomobile":"0","name":"sescontest.browse-search"}',
      ));
      // Insert content
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sescontest.browse-contests',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => $widgetOrder++,
          'params' => '{"enableTabs":["list","grid","advgrid","pinboard"],"openViewType":"pinboard","show_criteria":["title","startenddate","by","mediaType","category","listdescription","griddescription","pinboarddescription","socialSharing","likeButton","favouriteButton","followButton","joinButton","entryCount","like","comment","favourite","view","follow","status","voteCount"],"show_item_count":"1","height":"250","width":"460","height_grid":"260","width_grid":"393","height_advgrid":"290","width_advgrid":"393","width_pinboard":"350","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","list_description_truncation":"300","grid_description_truncation":"75","pinboard_description_truncation":"150","limit_data_pinboard":"35","limit_data_grid":"30","limit_data_advgrid":"30","limit_data_list":"25","socialshare_enable_plusicon":"1","socialshare_icon_limit":"2","pagging":"button","title":"","nomobile":"0","name":"sescontest.browse-contests"}',
      ));
    }
  }

  //Delete entry
  public function deleteAction() {
    $this->_helper->layout->setLayout('admin-simple');
    $content_type = $this->_getParam('content_type');
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

//         $inttable = Engine_Api::_()->getItem('sescontest_integrateothermodule', $id);
//         $pageName = "sescontest_index_".$this->_getParam('integrateothermodule_id');
//         if (!empty($pageName)) {
//           $page_id = $db->select()
//                   ->from('engine4_core_pages', 'page_id')
//                   ->where('name = ?', $pageName)
//                   ->limit(1)
//                   ->query()
//                   ->fetchColumn();
//           if($page_id) {
//             Engine_Api::_()->getDbTable('content', 'core')->delete(array('page_id =?' => $page_id));
//             Engine_Api::_()->getDbTable('pages', 'core')->delete(array('page_id =?' => $page_id));
//           }
//         }
//         Engine_Api::_()->getDbtable('menuItems', 'core')->delete(array('name =?' => 'sescontest_main_browsecontest_' . $this->_getParam('integrateothermodule_id')));

        $integrateothermodule = Engine_Api::_()->getItem('sescontest_integrateothermodule', $this->_getParam('integrateothermodule_id'));
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
    $content = Engine_Api::_()->getItemTable('sescontest_integrateothermodule')->fetchRow(array('integrateothermodule_id = ?' => $this->_getParam('integrateothermodule_id')));
    try {

      //Engine_Api::_()->getDbtable('menuItems', 'core')->update(array('enabled' => !$content->enabled), array('name =?' => 'sescontest_main_browsecontest_' . $this->_getParam('integrateothermodule_id')));

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
