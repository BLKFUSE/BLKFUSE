<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageRssController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_AdminManageRssController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sesnews_admin_main', array(), 'sesnews_admin_main_managerss');

    $this->view->formFilter = $formFilter = new Sesnews_Form_Admin_FilterRss();
    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
            $allNews = Engine_Api::_()->getDbTable('news', 'sesnews')->getAllNews($value);
            foreach($allNews as $news) {
                Engine_Api::_()->sesnews()->deleteNews($news);
            }
          //Work for delete news which is linked with this rss
          $sesnews = Engine_Api::_()->getItem('sesnews_rss', $value);
          $sesnews->delete();
        }
      }
    }

    $values = array();
    if ($formFilter->isValid($this->_getAllParams()))
    $values = $formFilter->getValues();

    $values = array_merge(array(
        'order' => isset($_GET['order']) ? $_GET['order'] :'',
        'order_direction' => isset($_GET['order_direction']) ? $_GET['order_direction'] : '',
    ), $values);
    $this->view->assign($values);

    $tableUserName = Engine_Api::_()->getItemTable('user')->info('name');
    $newsTable = Engine_Api::_()->getDbTable('rss', 'sesnews');
    $rssTableName = $newsTable->info('name');
    $select = $newsTable->select()
                    ->setIntegrityCheck(false)
                    ->from($rssTableName)
                    ->joinLeft($tableUserName, "$rssTableName.owner_id = $tableUserName.user_id", 'username')
                    ->order((!empty($_GET['order']) ? $_GET['order'] : 'rss_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));

    if (!empty($_GET['name']))
    $select->where($rssTableName . '.title LIKE ?', '%' . $_GET['name'] . '%');

    if (!empty($_GET['owner_name']))
    $select->where($tableUserName . '.displayname LIKE ?', '%' . $_GET['owner_name'] . '%');

     if (!empty($_GET['category_id']))
      $select->where($rssTableName . '.category_id =?', $_GET['category_id']);

    if (!empty($_GET['subcat_id']))
      $select->where($rssTableName . '.subcat_id =?', $_GET['subcat_id']);

    if (!empty($_GET['subsubcat_id']))
      $select->where($rssTableName . '.subsubcat_id =?', $_GET['subsubcat_id']);

    if (isset($_GET['status']) && $_GET['status'] != '')
    $select->where($rssTableName . '.draft = ?', $_GET['status']);

    if (isset($_GET['is_approved']) && $_GET['is_approved'] != '')
    $select->where($rssTableName . '.is_approved = ?', $_GET['is_approved']);

    if (!empty($_GET['creation_date']))
    $select->where($rssTableName . '.creation_date LIKE ?', $_GET['creation_date'] . '%');

    if (isset($_GET['subcat_id'])) {
        $formFilter->subcat_id->setValue($_GET['subcat_id']);
        $this->view->category_id = $_GET['category_id'];
    }

    if (isset($_GET['subsubcat_id'])) {
			$formFilter->subsubcat_id->setValue($_GET['subsubcat_id']);
			$this->view->subcat_id = $_GET['subcat_id'];
    }

    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey=>$urlParamsVal){
      if($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
      continue;
      $urlParams['query'][$urlParamsKey] = $urlParamsVal;
    }
    $this->view->urlParams = $urlParams;
    $paginator = Zend_Paginator::factory($select);
    $this->view->paginator = $paginator;
    $paginator->setItemCountPerPage(100);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }

  //Approved Action
  public function approvedAction() {
    $rss_id = $this->_getParam('id');
    if (!empty($rss_id)) {
      $rss = Engine_Api::_()->getItem('sesnews_rss', $rss_id);
      $rss->is_approved = !$rss->is_approved;
      $rss->save();
    }
    $this->_redirect('admin/sesnews/manage-rss');
  }

  //Cron Enabled Action
  public function cronAction() {
    $rss_id = $this->_getParam('id');
    if (!empty($rss_id)) {
      $rss = Engine_Api::_()->getItem('sesnews_rss', $rss_id);
      $rss->cron_enabled = !$rss->cron_enabled;
      $rss->save();
    }
    $this->_redirect('admin/sesnews/manage-rss');
  }

  public function deleteAction()
  {
    // In smoothbox
    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id');
    $this->view->rss_id=$id;
    // Check post
    if( $this->getRequest()->isPost() )
    {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try
      {
        $allNews = Engine_Api::_()->getDbTable('news', 'sesnews')->getAllNews($id);
        foreach($allNews as $news) {
            Engine_Api::_()->sesnews()->deleteNews($news);
        }
        $sesnews = Engine_Api::_()->getItem('sesnews_rss', $id);
        $sesnews->delete();
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
}
