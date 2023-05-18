<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eusertip_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main', array(), 'eusertip_admin_main_settings');

    $this->view->form = $form = new Eusertip_Form_Admin_Settings_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Eusertip/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('eusertip.pluginactivated')) {
        foreach ($values as $key => $value) {
          if($value != '')
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
          $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
  
  public function levelAction() {
  
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main', array(), 'eusertip_admin_main_settingsmemberlevel');

    // Get level id
    if (null !== ($id = $this->_getParam('id'))) {
      $level = Engine_Api::_()->getItem('authorization_level', $id);
    } else {
      $level = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel();
    }
    
    if (!$level instanceof Authorization_Model_Level) {
      throw new Engine_Exception('missing level');
    }
    
    $level_id = $id = $level->level_id;
    
    // Make form
    $this->view->form = $form = new Eusertip_Form_Admin_Settings_Level(array(
        'public' => ( engine_in_array($level->type, array('public')) ),
        'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
    ));
    
    $form->level_id->setValue($level_id);
    $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
    $valuesForm = $permissionsTable->getAllowed('eusertip', $level_id, array_keys($form->getValues()));

    $form->populate($valuesForm);
    if (!$this->getRequest()->isPost()) {
      return;
    }
    // Check validitiy
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    
    // Process
    $values = $form->getValues();

    $db = $permissionsTable->getAdapter();
    $db->beginTransaction();
    try {
      $nonBooleanSettings = $form->nonBooleanFields();
      $permissionsTable->setAllowed('eusertip', $level_id, $values, '', $nonBooleanSettings);

      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $form->addNotice('Your changes have been saved.');
  }
  
  public function managePaymentOwnerAction() {
  
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main', array(), 'eusertip_admin_main_paymentrequest');

    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main_paymentr', array(), 'eusertip_admin_main_managepaymenteventownersub');

    $this->view->formFilter = $formFilter = new Eusertip_Form_Admin_Settings_FilterPaymentOwner();
    
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    $values = array_merge(array('order' => @$_GET['order'], 'order_direction' => @$_GET['order_direction']), $values);

    $this->view->assign($values);

    $userTableName = Engine_Api::_()->getItemTable('user')->info('name');
    
    $userpayrequestsTable = Engine_Api::_()->getDbTable('userpayrequests', 'eusertip');
    $userpayrequestsTableName = $userpayrequestsTable->info('name');

    $select = $userpayrequestsTable->select()
            ->setIntegrityCheck(false)
            ->from($userpayrequestsTableName)
            ->joinLeft($userTableName, "$userpayrequestsTableName.owner_id = $userTableName.user_id", 'displayname')
            ->where($userpayrequestsTableName . '.state = ?', 'complete')
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'userpayrequest_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['name']))
      $select->where($userTableName . '.displayname LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['creation_date']))
      $select->where($userpayrequestsTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');
      
    if(!empty($_GET['gateway']))
      $select->where($userpayrequestsTableName . '.gateway_type LIKE ?', $_GET['gateway'] . '%');
      
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  public function ordersAction() {
  
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main', array(), 'eusertip_admin_main_manageorders');
    
    $this->view->formFilter = $formFilter = new Eusertip_Form_Admin_Settings_FilterOrder();
    
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
      
    $values = array_merge(array('order' => isset($_GET['order']) ? $_GET['order'] : '', 'order_direction' => @$_GET['order_direction']), $values);
    $this->view->assign($values);

    $userTableName = Engine_Api::_()->getItemTable('eusertip_tip')->info('name');
    
    $ordersTable = Engine_Api::_()->getDbTable('orders', 'eusertip');
    $ordersTableName = $ordersTable->info('name');
    
		$userName = Engine_Api::_()->getItemTable('user')->info('name');

    $select = $ordersTable->select()
                        ->setIntegrityCheck(false)
                        ->from($ordersTableName)
                        ->joinLeft($userTableName, "$ordersTableName.tip_id = $userTableName.tip_id", 'title')
                        ->joinLeft($userName, "$userName.user_id = $ordersTableName.owner_id", null)
                        ->where($userTableName.'.tip_id !=?','')
                        ->where($ordersTableName . '.state = ?', 'complete')
                        ->order((!empty($_GET['order']) ? $_GET['order'] : 'order_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['name']))
      $select->where($userTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

		if (!empty($_GET['gateway']))
      $select->where($ordersTableName . '.gateway_type LIKE ?', '%' . $_GET['gateway'] . '%');

		if (!empty($_GET['owner']))
      $select->where($userName . '.displayname LIKE ?', '%' . $_GET['owner'] . '%');
      
    if (!empty($_GET['creation_date']))
      $select->where($ordersTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');
      
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
  
  public function viewPaymentrequestAction() {
    $this->view->item = Engine_Api::_()->getItem('eusertip_userpayrequest', $this->_getParam('id', null));
  }
  
  public function manageWidgetizePageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('eusertip_admin_main', array(), 'eusertip_admin_main_managewidgetizepage');

    $pagesArray = array(
        'eusertip_index_account-details',
        'eusertip_index_manage-tips',
        'eusertip_index_createtip',
        'eusertip_index_edittip',
        'eusertip_index_manage-orders',
        'eusertip_index_sales-stats',
        'eusertip_index_sales-reports',
        'eusertip_index_payment-transaction',
        'eusertip_index_payment-requests',
        'eusertip_index_my-orders',
    );
    $this->view->pagesArray = $pagesArray;
  }
}
