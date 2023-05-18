<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminTransactionsController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminTransactionsController extends Core_Controller_Action_Admin {
  public function indexAction() {
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_transactions');

    $this->view->formFilter = $formFilter = new Sescommunityads_Form_Admin_Paymentfilter();

    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array(
        'order' => isset($_GET['order']) ? $_GET['order'] : '',
        'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',
            ), $values);
    $this->view->assign($values);

    $tableTransaction = Engine_Api::_()->getItemTable('sescommunityads_transaction');
    $tableTransactionName = $tableTransaction->info('name');
    $adsTable = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads');
    $adsTableName = $adsTable->info('name');
    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');

    $select = $tableTransaction->select()
            ->setIntegrityCheck(false)
            ->from($tableTransactionName)
            ->joinLeft($tableUserName, "$tableTransactionName.owner_id = $tableUserName.user_id", 'username')
            ->where($tableUserName . '.user_id IS NOT NULL')
            ->joinLeft($adsTableName, "$tableTransactionName.transaction_id = $adsTableName.transaction_id", 'sescommunityad_id')
            ->where($adsTableName . '.sescommunityad_id IS NOT NULL')
            ->order((!empty($_GET['order']) ? $_GET['order'] : 'transaction_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['title'])) {
      $select
              ->where('(' . $tableTransactionName . '.gateway_transaction_id LIKE ? || ' .
                      $tableTransactionName . '.gateway_profile_id LIKE ? || ' .
                      'title LIKE ? || ' .
                      'displayname LIKE ? || username LIKE ? || ' .
                      $tableUserName . '.email LIKE ?)', '%' . $_GET['title'] . '%');
    }

    if (!empty($_GET['gateway_id']))
      $select->where($tableTransactionName . '.gateway_id LIKE ?', '%' . $_GET['gateway_id'] . '%');

    if (!empty($_GET['gateway_type']))
      $select->where($tableTransactionName . '.gateway_type LIKE ?', '%' . $_GET['gateway_type'] . '%');


    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams['query'][$urlParamsKey] = $urlParamsVal;
    }
    $this->view->urlParams = $urlParams;
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(10);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

  }

  public function detailAction() {
    if (!($transaction_id = $this->_getParam('transaction_id')) ||
            !($transaction = Engine_Api::_()->getItem('sescommunityads_transaction', $transaction_id))) {
      return;
    }

    $this->view->transaction = $transaction;
    $this->view->gateway = Engine_Api::_()->getItem('payment_gateway', $transaction->gateway_id);
    $this->view->order = Engine_Api::_()->getItem('payment_order', $transaction->order_id);
    $this->view->item = Engine_Api::_()->getItem('sescommunityads', $this->_getParam('sescommunityad_id'));
    $this->view->user = Engine_Api::_()->getItem('user', $transaction->owner_id);
  }

}
