<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Classified
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: AdminManageController.php 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Classified
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Classified_AdminManageController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('classified_admin_main', array(), 'classified_admin_main_manage');

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $classified = Engine_Api::_()->getItem('classified', $value);
          $classified->delete();
        }
      }
    }

    $page=$this->_getParam('page',1);
    $this->view->paginator = Engine_Api::_()->getItemTable('classified')->getClassifiedsPaginator(array(
      'orderby' => 'classified_id',
      'showclassified' => 1,
    ));
    $this->view->paginator->setItemCountPerPage(25);
    $this->view->paginator->setCurrentPageNumber($page);
  }

  public function deleteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $this->view->classified_id=$id;
    // Check post
    if( $this->getRequest()->isPost())
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();


      try
      {
        $classified = Engine_Api::_()->getItem('classified', $id);
        // delete the classified listing the database
        $classified->delete();
        $db->commit();
      }

      catch( Exception $e )
      {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh'=> 10,
          'messages' => array('')
      ));
    }

    // Output
    $this->renderScript('admin-manage/delete.tpl');
  }
  
  //Approved Action
  public function approvedAction() {
  
    $id = $this->_getParam('id');
    if (!empty($id)) {
    
      $item = Engine_Api::_()->getItem('classified', $id);
      $item->approved = !$item->approved;
      $item->save();

      // Re-index
      Engine_Api::_()->getApi('search', 'core')->index($item);
      
      if ($item->approved) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($item->getOwner(), $item->getOwner(), $item, 'classified_approvedbyadmin', array('classified_title' => $item->getTitle(), 'classifiedowner_title' => $item->getOwner()->getTitle(), 'object_link' => $item->getHref(), 'host' => $_SERVER['HTTP_HOST']));
      } else {
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($item->getOwner(), $item->getOwner(), $item, 'classified_disapprovedbyadmin', array('classified_title' => $item->getTitle(), 'classifiedowner_title' => $item->getOwner()->getTitle(), 'object_link' => $item->getHref(), 'host' => $_SERVER['HTTP_HOST']));
      }
    }
    $this->_redirect('admin/classified/manage');
  }
}
