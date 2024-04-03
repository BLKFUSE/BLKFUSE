<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageaskquestionController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_AdminManageaskquestionController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_manageaskquestion');
    
		$this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $page = $this->_getParam('page', 1);
    
    $this->view->formFilter = $formFilter = new Sestutorial_Form_Admin_FilterAskQuestion();
    
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $askquestion = Engine_Api::_()->getItem('sestutorial_askquestion', $value);
          $askquestion->delete();
        }
      }
    }
    
    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
      $values = $formFilter->getValues();
    $values = array_merge(array('order' => isset($_GET['order']) ? $_GET['order'] :'', 'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',), $values);
    
    $this->view->assign($values);
    
    $askquesitonsTable = Engine_Api::_()->getDbtable('askquestions', 'sestutorial');
    $askquesitonsTableName = $askquesitonsTable->info('name');
    $select = $askquesitonsTable->select()
              ->setIntegrityCheck(false)
              ->from($askquesitonsTableName)
              ->order($askquesitonsTableName . '.askquestion_id DESC');
              
    if (!empty($_GET['email']))
      $select->where($askquesitonsTableName . '.email LIKE ?', '%' . $_GET['email'] . '%');
      
    if (!empty($_GET['name']))
      $select->where($askquesitonsTableName . '.name LIKE ?', '%' . $_GET['name'] . '%');
      
    if (!empty($_GET['creation_date']))
      $select->where($askquesitonsTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');
      
    if (!empty($_GET['description']))
      $select->where($askquesitonsTableName . '.description LIKE ?', '%' . $_GET['description'] . '%');

    if (!empty($_GET['category_id']))
      $select->where($askquesitonsTableName . '.category_id =?', $_GET['category_id']);

    if (!empty($_GET['subcat_id']))
      $select->where($askquesitonsTableName . '.subcat_id =?', $_GET['subcat_id']);

    if (!empty($_GET['subsubcat_id']))
      $select->where($askquesitonsTableName . '.subsubcat_id =?', $_GET['subsubcat_id']);
      
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->paginator->setItemCountPerPage(100);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
	}

	public function answerquestionAction() {

		$this->_helper->layout->setLayout('admin-simple');

		$askquestion_id = $this->_getParam('askquestion_id');
		$askquestion = Engine_Api::_()->getItem('sestutorial_askquestion', $askquestion_id);

    $this->view->form = $form = new Sestutorial_Form_Admin_Answerquestion();

    if(!$this->getRequest()->isPost()) return;
    
    if(!$form->isValid($this->getRequest()->getPost())) return;
    
		$values = $form->getValues();
		$settings = Engine_Api::_()->getApi('settings', 'core');
		
		if(!empty($askquestion->user_id)) {

			$user = Engine_Api::_()->getItem('user', $askquestion->user_id);
			$viewer = Engine_Api::_()->user()->getViewer();
			if($viewer->getIdentity() != $user->getIdentity()) {
        //Sent message when reply by admin
        $messageSubject = Zend_Registry::get('Zend_Translate')->_("Reply Your Asked Question: ").$askquestion->description;
        $message = Engine_Api::_()->getItemTable('messages_conversation')->send($viewer, $user, $messageSubject,$values['body'], NULL);
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $viewer, $message, 'message_new');
        Engine_Api::_()->getDbtable('statistics', 'core')->increment('messages.creations');
        //Save value of reply done by admin
        $askquestion->reply = 1;
        $askquestion->answered = $values['body'];
        $askquestion->save();
			}
		} elseif(!empty($askquestion->email)) {
			$email = $settings->core_mail_from;
			Engine_Api::_()->getApi('mail', 'core')->sendSystem($askquestion->email, 'SESTutorial_ASKANSWER_EMAIL', array(
        'site_title' => $settings->getSetting('core.general.site.title', 'My Community'),
        'description' => $askquestion->description,
        'questionreply' => $values['body'],
        'queue' => true,
        'email' => $email,
			));
			$askquestion->reply = 1;
			$askquestion->answered = $values['body'];
			$askquestion->save();
		}

		$this->_forward('success', 'utility', 'core', array(
			'smoothboxClose' => 5,
			'parentRefresh'=> 5,
			'messages' => array('You have successfully replied question.')
		));
	}

  public function deleteAction() {
  
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sestutorial_id = $id = $this->_getParam('id');
    
    $this->view->form = $form = new Sestutorial_Form_Admin_Delete();
    $form->setTitle('Delete Question?');
    $form->setDescription('Are you sure that you want to delete this question? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    //Check post
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $tutorial = Engine_Api::_()->getItem('sestutorial_askquestion', $id);
        $tutorial->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have successfully delete question.')
      ));
    }
  }
}
