<?php

class Sescontestpackage_IndexController extends Core_Controller_Action_Standard {

  public function indexAction() {
    $this->view->someVar = 'someVal';
  }

  public function cancelAction() {
    $packageId = $this->_getParam('package_id', 0);

    $this->view->form = $form = new Sescontestpackage_Form_Cancel();

    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }
    
    Engine_Api::_()->getDbTable('packages','sescontestpackage')->cancelSubscription(array('package_id' => $packageId));

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your Package Subscription has been Deleted Successfully.'))
    ));
  }

  public function contestAction() {
    if (!$this->_helper->requireUser->isValid())
      return;
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->package = $packageMemberLevel = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getPackage(array('member_level' => $viewer->level_id, 'enabled' => 0));
    if (!engine_count($packageMemberLevel) || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0))
      return $this->_helper->redirector->gotoRoute(array('action' => 'create'), 'sescontest_general', true);

    $this->view->existingleftpackages = $existingleftpackages = Engine_Api::_()->getDbTable('orderspackages', 'sescontestpackage')->getLeftPackages(array('owner_id' => $viewer->getIdentity()));

    $this->_helper->content->setEnabled();
  }

  public function confirmUpgradeAction() {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $contest_id = $this->_getParam('contest_id', false);
    $package_id = $this->_getParam('package_id', false);
    if (!$contest_id || !$package_id)
      return $this->_forward('notfound', 'error', 'core');
    $sescontest = Engine_Api::_()->getItem('contest', $contest_id);
    $package = Engine_Api::_()->getItem('sescontestpackage_package', $package_id);
    if (!$sescontest || !$package || $sescontest->package_id == $package_id)
      return $this->_forward('notfound', 'error', 'core');

    $this->view->form = $form = new Sescontestpackage_Form_Confirm();

    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $validPackage = Engine_Api::_()->getDbTable('packages', 'sescontestpackage')->getPackage(array('member_level' => $viewer->level_id, 'show_upgrade' => 1, 'package_id' => $package_id));
    if (empty($package->enabled) || !$validPackage)
      return $this->_forward('notfound', 'error', 'core');
    $tableContest = Engine_Api::_()->getDbTable('contests', 'sescontest');
    if ($this->getRequest()->getPost()) {
      $orderpackage_id = $sescontest->orderspackage_id;
      if (empty($sescontest->transaction_id)) {
        //get transaction id
        $select = $tableContest->select()->where('transaction_id !=?', '')->where('orderspackage_id =?', $sescontest->orderspackage_id);
        $transactionContest = $tableContest->fetchRow($select);
        if ($transactionContest)
          $transaction = Engine_Api::_()->getItem('sescontestpackage_transaction', $transactionContest->transaction_id);
        else
          $transaction = '';
      }else {
        $transaction = Engine_Api::_()->getItem('sescontestpackage_transaction', $sescontest->transaction_id);
        $transactionContest = $sescontest;
      }
      if ($transactionContest) {
        $transactionContest->cancel();
        $tableContest->update(array('transaction_id' => '', 'package_id' => $package_id), array('orderspackage_id' => $orderpackage_id));
        if ($transaction) {
          $tableContest->update(array('transaction_id' => '', 'package_id' => $package_id), array('orderspackage_id' => $orderpackage_id));
          $transaction->delete();
        }
      }
      if ($package->isFree()) {
        if (!empty($orderpackage_id))
          $tableContest->update(array('transaction_id' => '', 'package_id' => $package_id, 'is_approved' => 1), array('orderspackage_id' => $orderpackage_id));
        else {
          $sescontest->is_approved = 1;
          $sescontest->package_id = $package_id;
          $sescontest->save();
        }
      } else {
        if (!empty($orderpackage_id))
          $tableContest->update(array('transaction_id' => '', 'package_id' => $package_id, 'is_approved' => 0), array('orderspackage_id' => $orderpackage_id));
        else {
          $sescontest->is_approved = 0;
          $sescontest->package_id = $package_id;
          $sescontest->save();
        }
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => true,
          'parentRefresh' => true,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('Contest Package changed successfully.'))
      ));
    }
  }

}
