<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminReportController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminReportController extends Core_Controller_Action_Admin {

  public function indexAction() {
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $report = Engine_Api::_()->getItem('sescommunityads_report', $value);
          $report->delete();
        }
      }
    }


    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_adsreport');


   // Make form
    $this->view->formFilter = $formFilter = new Core_Form_Admin_Filter();

    // Process form
    if( $formFilter->isValid($this->_getAllParams()) ) {
      $filterValues = $formFilter->getValues();
    } else {
      $filterValues = array();
    }
    if( empty($filterValues['order']) ) {
      $filterValues['order'] = 'report_id';
    }
    if( empty($filterValues['direction']) ) {
      $filterValues['direction'] = 'DESC';
    }
    $this->view->filterValues = $filterValues;

   $table = Engine_Api::_()->getDbTable('reports','sescommunityads');
   $tableName = $table->info('name');

   $userTableName = Engine_Api::_()->getDbTable('users','user')->info('name');
   $adTableName = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->info('name');
   $select = $table->select()->from($tableName,'*')
             ->setIntegrityCheck(false)
             ->order($filterValues['order'] . ' ' . $filterValues['direction'])
             ->joinLeft($adTableName,$adTableName.'.sescommunityad_id = '.$tableName.'.item_id',null)
             ->joinLeft($userTableName,$userTableName.'.user_id = '.$tableName.'.user_id',null)
             ->where($adTableName.'.is_deleted =?',0);
   $this->view->paginator = $paginator =  Zend_Paginator::factory($select);
   $paginator->setCurrentPageNumber($this->_getParam('page', 1));

  }
  public function deleteAction()
  {
    $this->view->id = $id = $this->_getParam('id', null);
    $report = Engine_Api::_()->getItem('sescommunityads_report', $id);
    // Save values
    if( $this->getRequest()->isPost() )
    {
      $report->delete();
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 100,
          'parentRefresh' => 100,
          'messages' => array('You have successfully deleted this report.')
      ));
      //$form->addMessage('Changes Saved!');
    }
  }
  public function actionAction()
  {
    // Check report ID and report
    $report_id = $this->_getParam('id', $this->_getParam('report_id'));
    if( !$report_id ) {
      $this->view->closeSmoothbox = true;
      return;
    }

    $report = Engine_Api::_()->getItem('sescommunityads_report', $report_id);
    if( !$report ) {
      $this->view->closeSmoothbox = true;
      return;
    }

    // Get subject
    try {
      $this->view->subject = $subject = $report->getSubject();
    } catch( Exception $e ) {
      $this->view->subject = $subject = null;
    }

    // Get subject owner
    if( $subject instanceof Core_Model_Item_Abstract ) {
      try {
        $this->view->subjectOwner = $subjectOwner = $subject->getOwner('user');
      } catch( Exception $e ) {
        // Silence
        $this->view->subjectOwner = $subjectOwner = null;
      }
    } else {
      $this->view->subjectOwner = $subjectOwner = null;
    }

    // Make form
    $this->view->form = $form = new Core_Form_Admin_Report_Action();

    $form->removeElement('ban');
    $form->removeElement('action_poster');

    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $values = $form->getValues();

    // Process action
    if( !empty($values['action']) ) {
      if( $values['action'] == 1 && $subject instanceof Core_Model_Item_Abstract ) {
        $subject->is_deleted = 1;
      }
    }

    // Process dismiss
    if( !empty($values['dismiss']) ) {
      $report->delete();
    }

    // Done
    $this->view->closeSmoothbox = true;
  }
}
