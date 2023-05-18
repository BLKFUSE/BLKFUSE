<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManagePaymentController.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_AdminManagePaymentController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_sescommunityadsbanner');

	$this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescomadbanr_admin_main', array(), 'sescomadbanr_admin_main_gustpaymanage');

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('userpayments', 'sescomadbanr')->getUserpayments();
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $userpayment = Engine_Api::_()->getItem('sescomadbanr_userpayment', $value)->delete();
        }
      }
    }
    $page = $this->_getParam('page', 1);
    $paginator->setItemCountPerPage(25);
    $paginator->setCurrentPageNumber($page);
  }

  public function sendPaymentReminderAction() {

    $id = $this->_getParam('id', 0);
    $userpayment = Engine_Api::_()->getItem('sescomadbanr_userpayment', $id);

    $paymentemail = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.paymentemail', '');
    $itemname = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.itemname', 'Ads');

    $paypalPaymentLink = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business='.$paymentemail.'&item_name='.strip_tags($itemname).'&amount='.(string) round($userpayment->price,2)."&CURRENCYCODE=USD";
    Engine_Api::_()->getApi('mail', 'core')->sendSystem($userpayment->email, 'sescomadbanr_paymentreminder', array('subject' => 'Ads Payment Email','paymentlink' => $paypalPaymentLink));

    $this->_redirect('admin/sescomadbanr/manage-payment');
  }


  public function createAction() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id', 0);

    $this->view->form = $form = new Sescomadbanr_Form_Admin_UserPaymentInfo();
    if ($id) {
      $form->setTitle("Edit Details");
      $form->submit->setLabel('Save Changes');
      $userpayment = Engine_Api::_()->getItem('sescomadbanr_userpayment', $id);
      $form->populate($userpayment->toArray());
    }
    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost()))
        return;
      $db = Engine_Api::_()->getDbtable('userpayments', 'sescomadbanr')->getAdapter();
      $db->beginTransaction();
      try {
        $table = Engine_Api::_()->getDbtable('userpayments', 'sescomadbanr');
        $values = $form->getValues();
        if (!$id)
          $userpayment = $table->createRow();
        $userpayment->setFromArray($values);
        $userpayment->save();

        $paymentemail = $settings->getSetting('sescomadbanr.paymentemail', '');
        $itemname = $settings->getSetting('sescomadbanr.itemname', 'Ads');

        if (!$id) {
            $paypalPaymentLink = 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business='.$paymentemail.'&item_name='.strip_tags($itemname).'&amount='.(string) round($values["price"],2)."&CURRENCYCODE=USD";
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($values['email'], 'sescomadbanr_paymentemail', array('subject' => 'Ads Payment Email','paymentlink' => $paypalPaymentLink));
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('User Information added successfully.')
      ));
    }
  }

  public function deleteAction() {

    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');

    $this->view->form = $form = new Sescomadbanr_Form_Admin_DeleteUserPaymentInfo();
    $form->setTitle('Delete This Entry?');
    $form->setDescription('Are you sure that you want to delete this entry? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    $this->view->item_id = $id = $this->_getParam('id');

    // Check post
    if ($this->getRequest()->isPost()) {
      Engine_Api::_()->getItem('sescomadbanr_userpayment', $id)->delete();
      $db = Engine_Db_Table::getDefaultAdapter();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('Entry Deleted Successfully.')
      ));
    }
    // Output
    $this->renderScript('admin-manage-payment/delete.tpl');
  }
}
