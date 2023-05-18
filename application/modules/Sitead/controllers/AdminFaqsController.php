<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Sitead
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminFaqsController.php 2010-08-010 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_AdminFaqsController extends Core_Controller_Action_Admin {

    public function faqcreateAction() {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sitead_admin_main', array(), 'sitead_admin_user_manage');
        $this->view->page_id = $page_id = $this->_getParam('page_id');
        $faq_type = Engine_Api::_()->getItem('sitead_infopage', $page_id)->faq;
        $faq_id = $this->_getParam('faq_id');
        $this->view->form = $form = new Sitead_Form_Admin_Faqcreate();

        $user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $this->getRequest()->getPost();
            $siteadFaqTable = Engine_Api::_()->getItemTable('sitead_faq');
            if (empty($faq_id)) {
                $siteadFaqInsert = $siteadFaqTable->createRow();
                $siteadFaqInsert->question = $values['faq_question'];
                $siteadFaqInsert->answer = $values['faq_answer'];
                $siteadFaqInsert->type = $faq_type;
                $siteadFaqInsert->poster_id = $user_id;
                $siteadFaqInsert->status = 1;
                $siteadFaqInsert->save();
            } else {
                $siteadFaqTable->update(array('question' => $values['faq_question'], 'answer' => $values['faq_answer']), array('faq_id =?' => $faq_id));
            }
            $this->_helper->redirector->gotoRoute(array('module' => 'sitead', 'controller' => 'helps', 'action' => 'help-page-create', 'page_id' => $page_id), 'admin_default', true);
        }
    }

    public function deleteAction() {
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('faq_id');
        $this->view->faq_id = $id;
        if ($this->getRequest()->getPost()) {
            $db = Engine_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

            try {
                $featured = Engine_Api::_()->getItem('sitead_faq', $id);
                $featured->delete();
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => true,
                'parentRefresh' => true,
                'messages' => array('FAQ has been deleted')
            ));
        }
    }

    public function faqDefaultMsgAction() {
        $faq_id = $this->_getParam('faq_id');
        if (!empty($faq_id)) {
            $this->view->item = $item = Engine_Api::_()->getItem('sitead_faq', $faq_id);
        }
    }

}
