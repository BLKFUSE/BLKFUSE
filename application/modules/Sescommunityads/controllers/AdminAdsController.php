<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminAdsController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminAdsController extends Core_Controller_Action_Admin {

  public function manageAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_manageads');

    $this->view->category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
    $this->view->subcat_id = isset($_GET['subcat_id']) ? $_GET['subcat_id'] : 0;
    $this->view->subsubcat_id = isset($_GET['subsubcat_id']) ? $_GET['subsubcat_id'] : 0;
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $ad = Engine_Api::_()->getItem('sescommunityads', $value);
          $ad->delete();
        }
      }
    }
    $this->view->formFilter = $formFilter = new Sescommunityads_Form_Admin_Filter();
    $values = array();
    $values = $this->_getAllParams();
    unset($values['module']);
    unset($values['controller']);
    unset($values['action']);
    unset($values['rewrite']);
    unset($values['search']);
    $formFilter->populate($values);
    $values['is_admin'] = 1;
    $values = array_merge(array(
        'order' => isset($_GET['order']) ? $_GET['order'] : '',
        'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',
            ), $values);
    $this->view->assign($values);

    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getAds($values);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams['query'][$urlParamsKey] = $urlParamsVal;
    }
    $this->view->urlParams = $urlParams;
  }
  public function deleteAction() {
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sescommunityad_id = $id = $this->_getParam('id');
    $type = $this->_getParam('type');
    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();

    $item = Engine_Api::_()->getItem('sescommunityads', $id);
    $form->setTitle('Delete Ad?');
    $form->setDescription('Are you sure that you want to delete this ad? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    //Check post
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        //$item->is_deleted = 1;
        $item->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have successfully deleted this ad.')
      ));
    }
  }
  function sponsoredAction(){
    $id = $this->_getParam('id');
    $ad = Engine_Api::_()->getItem('sescommunityads',$id);
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('admin-simple');
    $type = $this->_getParam('type');
    $param = $this->_getParam('param');

    $this->view->form = $form = new Sescommunityads_Form_Admin_Featuredsponsored();

    $form->setTitle("Sponsored Advertisement");
    $form->setDescription('Here, choose the start date and end date for this ad to be displayed as "Sponsored".');
    if (!$param)
      $form->remove->setLabel("Remove as Sponsored");
    $table = "engine4_sescommunityads_ads";
    $item_id = 'sescommunityad_id';

    if (!empty($id))
      $form->populate($ad->toArray());

    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost())) {
        return;
      }
      $values = $form->getValues();
      $end = strtotime($values['enddate']);
      $values['enddate'] = date('Y-m-d', $end);
      $db->update($table, array('sponsored_date' => $values['enddate']), array("$item_id = ?" => $id));
      if (@$values['remove']) {
        $db->update($table, array('sponsored' => 0), array("$item_id = ?" => $id));
      } else {
        $db->update($table, array('sponsored' => 1), array("$item_id = ?" => $id));
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
  }
  function featuredAction(){
    $id = $this->_getParam('id');
    $ad = Engine_Api::_()->getItem('sescommunityads',$id);
    $db = Engine_Db_Table::getDefaultAdapter();
    $this->_helper->layout->setLayout('admin-simple');
    $type = $this->_getParam('type');
    $param = $this->_getParam('param');

    $this->view->form = $form = new Sescommunityads_Form_Admin_Featuredsponsored();

    $form->setTitle("Featured Advertisement");
    $form->setDescription('Here, choose the start date and end date for this ad to be displayed as "Featured".');
    if (!$param)
      $form->remove->setLabel("Remove as Featured");
    $table = "engine4_sescommunityads_ads";
    $item_id = 'sescommunityad_id';

    if (!empty($id))
      $form->populate($ad->toArray());

    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost())) {
        return;
      }
      $values = $form->getValues();
      $end = strtotime($values['enddate']);
      $values['enddate'] = date('Y-m-d', $end);
      $db->update($table, array('featured_date' => $values['enddate']), array("$item_id = ?" => $id));
      if (@$values['remove']) {
        $db->update($table, array('featured' => 0), array("$item_id = ?" => $id));
      } else {
        $db->update($table, array('featured' => 1), array("$item_id = ?" => $id));
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
  }

  function approvedAction(){
    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('id');
    $ad = Engine_Api::_()->getItem('sescommunityads',$id);
    $ad->is_approved = !$ad->is_approved;
    if(!$ad->is_approved)
      $ad->status = 4;
    else
      $ad->status = 1;
    $ad->approved_date = date('Y-m-d H:i:s');
    $ad->save();

    $user = Engine_Api::_()->getItem('user', $ad->user_id);

    //Ads Approve Notification
    if($ad->status == 1) {

        $link = '/ads/view/ad_id/'.$ad->sescommunityad_id;

        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $user, $ad, 'sescommunityads_adsapprove', array("adsLink" => $link));

        //Send email to user
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($user->email, 'sescommunityads_adsapprove', array('host' => $_SERVER['HTTP_HOST'], 'queue' => false, 'title' => $ad->title, 'description' => $ad->description, 'ad_link' => $link));

    } else if($ad->status == 4) {
        $link = '/ads/view/ad_id/'.$ad->sescommunityad_id;
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $user, $ad, 'sescommunityads_adsdisapprove', array("adsLink" => $link));

        //Send email to user
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($user->email, 'sescommunityads_adsdisapprove', array('host' => $_SERVER['HTTP_HOST'], 'queue' => false, 'title' => $ad->title, 'description' => $ad->description, 'ad_link' => $link));

    }

    header("Location:".$_SERVER['HTTP_REFERER']);
    exit();
  }
}
