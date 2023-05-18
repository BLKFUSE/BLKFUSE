<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: AdminManageController.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_AdminManageController extends Core_Controller_Action_Admin
{
  public function indexAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_manage');
    $shareType = Engine_Api::_()->getDbTable('sharetypes', 'siteshare');
    $modules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    $select = $shareType->select()
      ->where('module_name  IN(?) ', $modules)
      ->order("order ASC");
    $this->view->sharetypes = $shareType->fetchAll($select);

  }

  public function addSharetypeAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_manage');
    $this->view->form = $form = new Siteshare_Form_Admin_Manage_Sharetype();
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    $shareType = Engine_Api::_()->getDbTable('sharetypes', 'siteshare');
    $values = $form->getValues();
    $db = Engine_Db_Table::getDefaultAdapter();
    if( in_array($values['module_name'], array('sitereview', 'sitereviewlistingtype')) ) {
      $params = array(
        'membership' => 0,
        'admin' => 0,
        'owner' => 'owner_id'
      );
    } else {
      $itemType = $values['type'];
      $tablePrefix = $shareType->getTablePrefix();
      $membershipTablename = $tablePrefix . $values['module_name'] . "_membership";

      $listTableName = $tablePrefix . $values['module_name'] . '_lists';
      $listItemTableName = $tablePrefix . $values['module_name'] . '_listitems';
      $tableExist = $db->query('SHOW TABLES LIKE "' . $membershipTablename . '"')->fetch();
      $listTableExist = $db->query('SHOW TABLES LIKE "' . $listTableName . '"')->fetch();
      $listItemTableExist = $db->query('SHOW TABLES LIKE "' . $listItemTableName . '"')->fetch();
      $tableCols = Engine_Api::_()->getItemTable($itemType)->info('cols');
      $ownerColumnName = in_array('owner_id', $tableCols) ? 'owner_id' : 'user_id';
      $params = array(
        'membership' => $tableExist ? 1 : 0,
        'owner' => $ownerColumnName,
        'admin' => ($listTableExist && $listItemTableExist) ? 1 : 0,
      );
    }
    $values['params'] = $params;
    if( $values['share_allow'] === 'member' && $params['membership'] == 0 ) {
      $values['share_allow'] = 'admin';
    }
    if( $values['share_allow'] == 'admin' && $params['admin'] == 0 ) {
      $values['share_allow'] = 'owner';
    }
    if( $values['notification_allow'] === 'member' && $params['membership'] == 0 ) {
      $values['notification_allow'] = 'admin';
    }
    if( $values['notification_allow'] == 'admin' && $params['admin'] == 0 ) {
      $values['notification_allow'] = 'owner';
    }

    $row = $shareType->createRow();
    $row->setFromArray($values);
    $row->save();
    $this->_helper->redirector->gotoRoute(array('module' => 'siteshare', 'controller' => 'manage'), 'admin_default', true);
  }

  public function editSharetypeAction()
  {
    $sharetype_id = $this->_getParam('sharetype_id');
    $module_name = $this->_getParam('module_name');
    $shareType = Engine_Api::_()->getDbtable('sharetypes', 'siteshare')->find($sharetype_id)->current();

    if( empty($sharetype_id) || !$shareType ) {
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
    $params = $shareType->params;
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_manage');
    $this->view->form = $form = new Siteshare_Form_Admin_Manage_SharetypeEdit();
    if( in_array($module_name, array('user', 'message', 'activity', 'core')) ) {
      foreach( array('module_name', 'type', 'share_allow', 'notification_allow') as $element ) {
        $form->removeElement($element);
      }
    } else {
      $shareAllowOptions = $form->getElement('share_allow')->getMultiOptions();
      $notificationAllowOptions = $form->getElement('notification_allow')->getMultiOptions();
      if( in_array($module_name, array('sitepage', 'sitegroup', 'sitestore', 'sitebusiness')) ) {
        $params['membership'] = (int) Engine_Api::_()->getDbtable('modules', 'core')
            ->isModuleEnabled($module_name . 'member');
      }
      if( $params['membership'] == 0 ) {
        $values['share_allow'] = 'admin';
        unset($shareAllowOptions['member']);
        unset($notificationAllowOptions['member']);
      }
      if( $params['admin'] == 0 ) {
        unset($shareAllowOptions['admin']);
        unset($notificationAllowOptions['admin']);
      }
      $shareAllowOptions = $form->getElement('share_allow')->setMultiOptions($shareAllowOptions);
      $notificationAllowOptions = $form->getElement('notification_allow')->setMultiOptions($notificationAllowOptions);
    }

    $form->populate($shareType->toArray());
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    $values = $form->getValues();
    if (isset($values['share_allow'])) {
      if( $values['share_allow'] === 'member' && $params['membership'] == 0 ) {
        $values['share_allow'] = 'admin';
      }
      if( $values['share_allow'] == 'admin' && $params['admin'] == 0 ) {
        $values['share_allow'] = 'owner';
      }
    }
    if (isset($values['notification_allow'])) {
      if( $values['notification_allow'] === 'member' && $params['membership'] == 0 ) {
        $values['notification_allow'] = 'admin';
      }
      if( $values['notification_allow'] == 'admin' && $params['admin'] == 0 ) {
        $values['notification_allow'] = 'owner';
      }
    }
    $shareType->setFromArray($values);
    $shareType->save();
    $this->_helper->redirector->gotoRoute(array('module' => 'siteshare', 'controller' => 'manage'), 'admin_default', true);
  }

  public function enableSharetypeAction()
  {
    $sharetype_id = $this->_getParam('sharetype_id');
    if( empty($sharetype_id) ) {
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
    $shareType = Engine_Api::_()->getDbtable('sharetypes', 'siteshare')->find($sharetype_id)->current();
    if( empty($shareType) ) {
      $this->_helper->redirector->gotoRoute(array('action' => 'index'));
    }
    $shareType->enabled = !$shareType->enabled;
    $shareType->save();
    $this->_helper->redirector->gotoRoute(array('action' => 'index'));
  }

  public function deleteSharetypeAction()
  {
    $this->_helper->layout->setLayout('admin-simple');
    $this->view->sharetype_id = $sharetype_id = $this->_getParam('sharetype_id');


    if( $this->getRequest()->isPost() ) {
      if( empty($sharetype_id) ) {
        return;
      }

      $shareType = Engine_Api::_()->getDbtable('sharetypes', 'siteshare')->find($sharetype_id)->current();
      $shareType->delete();
      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_(''))
      ));
    }
  }

  //ACTION FOR UPDATE ORDER 
  public function updateOrderAction()
  {
    //CHECK POST
    if( $this->getRequest()->isPost() ) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      $values = $_POST;
      try {
        foreach( $values['order'] as $key => $value ) {
          $content = Engine_Api::_()->getDbtable('sharetypes', 'siteshare')->find((int) $value)->current();
          if( !empty($content) ) {
            $content->order = $key + 1;
            $content->save();
          }
        }
        $db->commit();
        $this->_helper->redirector->gotoRoute(array('action' => 'index'));
      } catch( Exception $e ) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  public function socialServicesStatesAction()
  {
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('siteshare_admin_main', array(), 'siteshare_admin_main_social_services_status');
    //FILTER FORM
    $this->view->formFilter = $formFilter = new Siteshare_Form_Admin_Manage_SocialServicesStatistics();
    //PROCESS FORM
    $filterValues = $formFilter->getValues();
    if( $this->_getParam('search', 0) ) {
      $formFilter->isValid($this->_getAllParams());
      $filterValues = $formFilter->getValues();
    }
    $formatString = '%1$04d-%2$02d-%3$02d %4$02d:%5$02d:%6$02d';
    if( !empty($filterValues['start']) ) {
      $m = explode('-', $filterValues['start']);
      $filterValues['start'] = sprintf($formatString, $m[0], $m[1], $m[2], 0, 0, 0);
    }

    if( !empty($filterValues['end']) ) {
      $m = explode('-', $filterValues['end']);
      $filterValues['end'] = sprintf($formatString, $m[0], $m[1], $m[2], 23, 59, 59);
    }
    $socialShareHistories = Engine_Api::_()->getDbTable('socialShareHistories', 'siteshare');
    $results = $socialShareHistories->getShareCounts($filterValues);
    $menu = Engine_Api::_()->getApi('menus', 'core')
        ->getMenu('siteshare_social_link');
    $menusNames = array();
    foreach($menu as $menuItem) {
      $menusNames[str_replace('siteshare_social_link_', '', $menuItem->name)] = $menuItem->label;
    }
    $this->view->serviceNames = $menusNames;
    $this->view->serviceStatistics = $results;
  }

}
