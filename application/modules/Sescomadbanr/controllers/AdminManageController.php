<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_sescommunityadsbanner');

	$this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescomadbanr_admin_main', array(), 'sescomadbanr_admin_main_bannersizes');

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('banners', 'sescomadbanr')->getBanner();
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $banner = Engine_Api::_()->getItem('sescomadbanr_banner', $value)->delete();
        }
      }
    }
    $page = $this->_getParam('page', 1);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($page);
  }


  public function createAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id', 0);

    $this->view->form = $form = new Sescomadbanr_Form_Admin_Banner();
    if ($id) {
      $form->setTitle("Edit Banner Size Name");
      $form->submit->setLabel('Save Changes');
      $banner = Engine_Api::_()->getItem('sescomadbanr_banner', $id);
      $form->populate($banner->toArray());
    }
    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost()))
        return;
      $db = Engine_Api::_()->getDbtable('banners', 'sescomadbanr')->getAdapter();
      $db->beginTransaction();
      try {
        $table = Engine_Api::_()->getDbtable('banners', 'sescomadbanr');
        $values = $form->getValues();
        if (!$id)
          $banner = $table->createRow();
        $banner->setFromArray($values);
        $banner->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('Banner created successfully.')
      ));
    }
  }

  public function enabledAction() {

    $id = $this->_getParam('id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem('sescomadbanr_banner', $id);
      $item->enabled = !$item->enabled;
      $item->save();
    }
    $this->_redirect('admin/sescomadbanr/manage');
  }

  public function deleteAction() {

    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');

    $this->view->form = $form = new Sescomadbanr_Form_Admin_Delete();
    $form->setTitle('Delete This Banner Size?');
    $form->setDescription('Are you sure that you want to delete this Banner Size? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    $this->view->item_id = $id = $this->_getParam('id');

    // Check post
    if ($this->getRequest()->isPost()) {
      Engine_Api::_()->getItem('sescomadbanr_banner', $id)->delete();
      $db = Engine_Db_Table::getDefaultAdapter();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('Banner Size Delete Successfully.')
      ));
    }
    // Output
    $this->renderScript('admin-manage/delete.tpl');
  }
}
