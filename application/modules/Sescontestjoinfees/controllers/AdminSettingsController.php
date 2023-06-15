<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_AdminSettingsController extends Core_Controller_Action_Admin {

    public function extensionAction() {

      $db = Engine_Db_Table::getDefaultAdapter();

      $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontestjoinfees_admin_main');

      $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjoinfees_admin_main', array(), 'sescontestjoinfees_admin_main_settings');

      $this->view->form = $form = new Sescontestjoinfees_Form_Admin_Global();

      if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
        $values = $form->getValues();
        unset($values['commision']);
        include_once APPLICATION_PATH . "/application/modules/Sescontestjoinfees/controllers/License.php";
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.pluginactivated')) {
          foreach ($values as $key => $value) {
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
          }
          $form->addNotice('Your changes have been saved.');
          if($error)
            $this->_helper->redirector->gotoRoute(array());
        }
      }
    }

    public function levelAction(){
       $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
              ->getNavigation('sescontest_admin_main', array(), 'sescontestjoinfees_admin_main');

       $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjoinfees_admin_main', array(), 'sescontestjoinfees_admin_main_settingsmemberlevel');
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
      $this->view->form = $form = new Sescontestjoinfees_Form_Admin_Level(array(
          'public' => ( engine_in_array($level->type, array('public')) ),
          'moderator' => ( engine_in_array($level->type, array('admin', 'moderator')) ),
      ));
      $form->level_id->setValue($level_id);
      $permissionsTable = Engine_Api::_()->getDbtable('permissions', 'authorization');
      $valuesForm = $permissionsTable->getAllowed('contest', $level_id, array_keys($form->getValues()));
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)){
        $form->removeElement('level_id');
        $form->removeElement('submit');
      }
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
        $permissionsTable->setAllowed('contest', $level_id, $values, '', $nonBooleanSettings);
        // Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $form->addNotice('Your changes have been saved.');
    }
    public function managePaymentEventOwnerAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontestjoinfees_admin_main');

    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjoinfees_admin_main', array(), 'sescontestjoinfees_admin_main_paymentrequest');

    $this->view->subsubNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjoinfees_admin_main_paymentrequest', array(), 'sescontestjoinfees_admin_main_managepaymenteventownersub');

    $this->view->formFilter = $formFilter = new Sescontestjoinfees_Form_Admin_FilterPaymentEventOwner();
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    $values = array_merge(array('order' => $_GET['order'], 'order_direction' => $_GET['order_direction']), $values);

    $this->view->assign($values);

    $eventTableName = Engine_Api::_()->getItemTable('contest')->info('name');
    $ordersTable = Engine_Api::_()->getDbTable('userpayrequests', 'sescontestjoinfees');
    $ordersTableName = $ordersTable->info('name');

    $select = $ordersTable->select()
            ->setIntegrityCheck(false)
            ->from($ordersTableName)
            ->joinLeft($eventTableName, "$ordersTableName.contest_id = $eventTableName.contest_id", 'title')
            ->where($ordersTableName . '.state = ?', 'complete')
            //->where($ordersTableName . '.state = "complete" ||  state = "cancelled"')
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'userpayrequest_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['name']))
      $select->where($eventTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['creation_date']))
      $select->where($ordersTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');
	 if (!empty($_GET['gateway']))
      $select->where($ordersTableName . '.gateway_type LIKE ?', $_GET['gateway'] . '%');
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

    public function ordersAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontest_admin_main', array(), 'sescontestjoinfees_admin_main');

    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescontestjoinfees_admin_main', array(), 'sescontestjoinfees_admin_main_manageorders');

    $this->view->formFilter = $formFilter = new Sescontestjoinfees_Form_Admin_FilterOrder();
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();

    $values = array_merge(array('order' => isset($_GET['order']) ? $_GET['order'] : '', 'order_direction' => $_GET['order_direction']), $values);

    $this->view->assign($values);

    $eventTableName = Engine_Api::_()->getItemTable('contest')->info('name');
    $ordersTable = Engine_Api::_()->getDbTable('orders', 'sescontestjoinfees');
    $ordersTableName = $ordersTable->info('name');
		$userName = Engine_Api::_()->getItemTable('user')->info('name');

    $entryTable = Engine_Api::_()->getDbTable('participants', 'sescontest');
    $entryTableName = $entryTable->info('name');
    $select = $ordersTable->select()
            ->setIntegrityCheck(false)
            ->from($ordersTableName)
            ->joinLeft($eventTableName, "$ordersTableName.contest_id = $eventTableName.contest_id", 'title')
						->joinLeft($userName, "$userName.user_id = $ordersTableName.owner_id", null)
            ->joinLeft($entryTableName, "$entryTableName.participant_id = $ordersTableName.entry_id", null)
						->where($eventTableName.'.contest_id !=?','')
            ->where($ordersTableName . '.state = ?', 'complete')
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'order_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['name']))
      $select->where($eventTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');
    if (!empty($_GET['participant_title']))
      $select->where($entryTableName . '.title LIKE ?', '%' . $_GET['participant_title'] . '%');
		if (!empty($_GET['gateway']))
      $select->where($ordersTableName . '.gateway_type LIKE ?', '%' . $_GET['gateway'] . '%');

		if (!empty($_GET['owner']))
      $select->where($userName . '.displayname LIKE ?', '%' . $_GET['owner'] . '%');
    if (!empty($_GET['creation_date']))
      $select->where($ordersTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
   public function viewPaymentrequestAction() {
    $this->view->item = Engine_Api::_()->getItem('sescontestjoinfees_userpayrequest', $this->_getParam('id', null));
  }
}
