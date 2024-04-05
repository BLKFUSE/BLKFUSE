<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageBlacklistController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_AdminManageBlacklistController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_manageblackurl');

    $this->view->formFilter = $formFilter = new Sesnews_Form_Admin_Manage_Url();
    $page = $this->_getParam('page', 1);

    $table = Engine_Api::_()->getDbtable('urls', 'sesnews');

    $select = $table->select();

    // Process form
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    foreach ($values as $key => $value) {
      if (null === $value) {
        unset($values[$key]);
      }
    }

    $values = array_merge(array('order' => 'url_id', 'order_direction' => 'DESC'), $values);
    $this->view->assign($values);

    $select->order((!empty($values['order']) ? $values['order'] : 'url_id' ) . ' ' . (!empty($values['order_direction']) ? $values['order_direction'] : 'DESC' ));

    if (!empty($values['name']))
      $select->where('name LIKE ?', '%' . $values['name'] . '%');

    if (!empty($values['url_id']))
      $select->where('url_id = ?', (int) $values['url_id']);

    // Filter out junk
    $valuesCopy = array_filter($values);

    // Reset enabled bit
    if (isset($values['enabled']) && $values['enabled'] == 0) {
      $valuesCopy['enabled'] = 0;
    }

    // Make paginator
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(50);
    $this->view->formValues = $valuesCopy;
  }

  public function addAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->form = $form = new Sesnews_Form_Admin_Manage_AddUrl();
    $form->setTitle('Add New URL');
    $form->setDescription('Enter Url of rss that you want to blacklist.');
    $form->name->setLabel('Enter Url');
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $row = Engine_Api::_()->getDbtable('urls', 'sesnews')->createRow();
        $row->setFromArray($values);
        $row->save();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('You have successfully added.')
      ));
    }
  }


  public function editAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $this->view->form = $form = new Sesnews_Form_Admin_Manage_EditUrl();
    $form->setTitle('Edit this Url');
    $form->name->setLabel('Enter Url');
    $itemType = 'sesnews_url';
    $item = Engine_Api::_()->getItem($itemType, $id);
    $form->populate($item->toArray());

    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $values = $form->getValues();
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $item->name = $values['name'];
      $item->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    return $this->_forward('success', 'utility', 'core', array(
      'smoothboxClose' => 10,
      'parentRefresh' => 10,
      'messages' => array('You have successfully edited.')
    ));
  }


  public function deleteAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $itemTable = Engine_Api::_()->getDbtable('urls', 'sesnews');
    $item = $itemTable->find($id)->current();
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $item->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      return $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('You have successfully deleted.')
      ));
    }

    $this->renderScript('admin-manage-blacklist/delete.tpl');
  }

  public function enabledAction() {
    $itemType = 'sesnews_url';
    $redirectUrl = 'admin/sesnews/manage-blacklist';
    $id = $this->_getParam('id');
    if (!empty($id)) {
      $item = Engine_Api::_()->getItem($itemType, $id);
      $item->enabled = !$item->enabled;
      $item->save();
    }
    $this->_redirect($redirectUrl);
  }
}
