<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageController.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_AdminManageController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_menus');

    $this->view->storage = Engine_Api::_()->storage();
    $select = Engine_Api::_()->getDbTable('menuitems', 'core')->select()
            ->where('menu = ?', 'core_main')
            ->where('enabled = ?', 1)
            ->order('order ASC');
    $this->view->paginator = Engine_Api::_()->getDbTable('menuitems', 'core')->fetchAll($select);
  }

  public function footerSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_menusfooter');

    $this->view->form = $form = new Sesdating_Form_Admin_FooterSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      foreach ($values as $key => $value) {
				if ($key == 'sesdating_footer_background_image') {
          if(empty($value)) {
            $value = 'public/admin/blank.png';
          } else {
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            $value = Engine_Api::_()->sesdating()->getFileUrl($value);
          }
          Engine_Api::_()->sesdating()->readWriteXML($key, $value);
				} else {
						Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
				}
    	}
      $form->addNotice('Your changes have been saved.');
      //$this->_helper->redirector->gotoRoute(array());
  	}
	}
  public function landingPageSettingsAction() {
    $this->view->param = $this->_getParam('param', 'banner');
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_menuslanding');
    $this->view->modules = Engine_Api::_()->sesdating()->getModulesEnable();
    if (!empty($_POST)) {
      $values = $_POST;

      foreach ($values as $key => $value) {

//         if(!$value)
//           continue;

        if($key == 'sesdating_banner_content' && is_array($value))
          $value = implode(',',$value);
        if($key == 'sesdating_feature_content'){
          if(Engine_Api::_()->getApi('settings', 'core')->hasSetting($key)){
              Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
          }
          $valueArray = array();
            $counter = 0;
            foreach($value as $valueA){
              $valueArray[$counter] = $valueA;
              $counter++;
            }
            $value = $valueArray;
        }
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $this->_helper->redirector->gotoRoute(array());
    }
  }
  public function headerSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_menus');

    $this->view->form = $form = new Sesdating_Form_Admin_HeaderSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();

      if (isset($values['sesdating_header_nonloggedin_options']))
        $values['sesdating_header_nonloggedin_options'] = serialize($values['sesdating_header_nonloggedin_options']);
      else
        $values['sesdating_header_nonloggedin_options'] = serialize(array());

      if (isset($values['sesdating_header_loggedin_options']))
        $values['sesdating_header_loggedin_options'] = serialize($values['sesdating_header_loggedin_options']);
      else
        $values['sesdating_header_loggedin_options'] = serialize(array());

      $db = Engine_Db_Table::getDefaultAdapter();
      unset($values['minimenu_icons']);
      unset($values['minimenu_message_icons']);
      unset($values['minimenu_frrequest_icons']);
      foreach ($values as $key => $value) {
        if ($key == 'sesdating_header_design' || $key == 'sesdating_sidepanel_effect' || $key == 'sesdating_sidepanel_showhide') {
	        Engine_Api::_()->sesdating()->readWriteXML($key, $value);
		 			Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        } else {
          if ($key == 'sesdating_header_loggedin_options' || $key == 'sesdating_header_nonloggedin_options' || $key == 'sesdating_header_transparent') {
            if(Engine_Api::_()->getApi('settings', 'core')->hasSetting($key)){
              Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
              Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
            }
          }
					if($key == 'sesdating_menuinformation_img') {
						if(empty($value)) {
							$value = 'public/admin/blank.png';
            }
					}
					if($key == 'sesdating_menu_img') {
						if(empty($value))
							$value = 'public/admin/blank.png';
					}
					//Notification Icon
					if($key == 'minimenu_notification_normal') {
            if (isset($_FILES['minimenu_notification_normal']) && isset($_FILES['minimenu_notification_normal']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_notification_normal']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_notification_normalremove']) && !empty($values['minimenu_notification_normalremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.notification.normal', '0');
              }
            } else {
              $value = 0;
            }

          }
          if($key == 'minimenu_notification_mouseover') {
            if (isset($_FILES['minimenu_notification_mouseover']) && isset($_FILES['minimenu_notification_mouseover']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_notification_mouseover']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_notification_mouseoverremove']) && !empty($values['minimenu_notification_mouseoverremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.notification.mouseover', '0');
              }
            } else {
              $value = 0;
            }
          }

          //Message Icon upload
					if($key == 'minimenu_message_normal') {
            if (isset($_FILES['minimenu_message_normal']) && isset($_FILES['minimenu_message_normal']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_message_normal']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_message_normalremove']) && !empty($values['minimenu_message_normalremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.message.normal', '0');
              }
            } else {
              $value = 0;
            }
          }
          if($key == 'minimenu_message_mouseover') {
            if (isset($_FILES['minimenu_message_mouseover']) && isset($_FILES['minimenu_message_mouseover']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_message_mouseover']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_message_mouseoverremove']) && !empty($values['minimenu_message_mouseoverremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.message.mouseover', '0');
              }
            } else {
              $value = 0;
            }
          }

          //Friend Requests
					if($key == 'minimenu_frrequest_normal') {
            if (isset($_FILES['minimenu_frrequest_normal']) && isset($_FILES['minimenu_frrequest_normal']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_frrequest_normal']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_frrequest_normalremove']) && !empty($values['minimenu_frrequest_normalremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.frrequest.normal', '0');
              }
            } else {
              $value = 0;
            }
          }
          if($key == 'minimenu_frrequest_mouseover') {
            if (isset($_FILES['minimenu_frrequest_mouseover']) && isset($_FILES['minimenu_frrequest_mouseover']['tmp_name'])) {
              $value = $this->setPhoto($_FILES['minimenu_frrequest_mouseover']);
              if($value)
                $value = $value->file_id;
              //Remove icon
              if (isset($values['minimenu_frrequest_mouseoverremove']) && !empty($values['minimenu_frrequest_mouseoverremove'])) {
                Engine_Api::_()->getApi('settings', 'core')->setSetting('minimenu.frrequest.mouseover', '0');
              }
            } else {
              $value = 0;
            }
          }
          //if($value)
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
      }
      $form->addNotice('Your changes have been saved.');
      $this->_helper->redirector->gotoRoute(array());
    }
  }

  public function setPhoto($photo) {

    //GET PHOTO DETAILS
    $mainName = dirname($photo['tmp_name']) . '/' . $photo['name'];

    //GET VIEWER ID
    $photo_params = array(
        'parent_id' => 1,
        'parent_type' => "sesdating_minimenu_icons",
    );
    copy($photo['tmp_name'], $mainName);
    try {
      $photoFile = Engine_Api::_()->storage()->create($mainName, $photo_params);
    } catch (Exception $e) {
      if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE) {
        echo $e->getMessage();
        exit();
      }
    }

    return $photoFile;
  }

  public function uploadPhotoAction() {

    //Set default layout
    $this->_helper->layout->setLayout('default-simple');

    $admin_file = APPLICATION_PATH . '/public/adminsesdating';

    $path = realpath($admin_file);

    if (!is_dir($admin_file) && mkdir($admin_file, 0777, true))
      chmod($admin_file, 0777);

    if (empty($_FILES['userfile'])) {
      $this->view->error = 'File failed to upload. Check your server settings (such as php.ini max_upload_filesize).';
      return;
    }

    $info = $_FILES['userfile'];
    $targetFile = $path . '/' . $info['name'];

    if (!move_uploaded_file($info['tmp_name'], $targetFile)) {
      $this->view->error = "Unable to move file to upload directory.";
      return;
    }

    $this->view->status = true;
    $this->view->photo_url = 'http://' . $_SERVER['HTTP_HOST'] . '/' . Zend_Controller_Front::getInstance()->getBaseUrl() . '/public/adminsesdating/' . $info['name'];
  }

  public function uploadIconAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $id = $this->_getParam('id', null);
    $menuType = $this->_getParam('type', null);

    $db = Engine_Db_Table::getDefaultAdapter();
    $select = new Zend_Db_Select($db);
    $menu = $select->from('engine4_core_menuitems')->where('id = ?', $id)->query()->fetchObject();

    $this->view->form = new Sesdating_Form_Admin_Icon();

    if ($this->getRequest()->isPost()) {
      if (isset($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {

        $photoFile = Engine_Api::_()->sesdating()->setPhoto($_FILES['photo'], $id);
        if (!empty($photoFile->file_id)) {
          $previousFile = Engine_Api::_()->getDbTable('menusicons','sesbasic')->getRow($menu->id);
          $previous_file_id = !empty($previousFile->icon_id) ? $previousFile->icon_id : 0;
          Engine_Api::_()->getDbTable('menusicons','sesbasic')->addSave($menu->id,$photoFile->file_id);

          $file = Engine_Api::_()->getItem('storage_file', $previous_file_id);
          if (!empty($file))
            $file->delete();
        }
      }

      if ($menuType == 'main')
        $redirectUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesdating', 'controller' => 'admin-manage', 'action' => 'index'), 'default', true);
      else
        $redirectUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesdating', 'controller' => 'admin-manage', 'action' => 'footer-menu'), 'default', true);

      return $this->_forward('success', 'utility', 'core', array(
                  'parentRedirect' => $redirectUrl,
                  'messages' => 'Icon has been upoaded successfully.',
      ));
    }
  }

  public function deletePhotoAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->id = $id = $this->_getParam('id', 0);
    $this->view->file_id = $file_id = $this->_getParam('file_id', 0);

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $mainPhoto = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id);
        $mainPhoto->delete();
        $slideImage = Engine_Api::_()->getItem('sesdating_slideimage', $id);
        $slideImage->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
    // Output
    $this->renderScript('admin-manage/delete.tpl');
  }

  public function deleteMenuIconAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->id = $id = $this->_getParam('id', 0);
    $this->view->file_id = $file_id = $this->_getParam('file_id', 0);

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $mainMenuIcon = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id);
        if($mainMenuIcon)
          $mainMenuIcon->delete();
        Engine_Api::_()->getDbTable('menusicons','sesbasic')->deleteNotification($id);;
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('')
      ));
    }
    // Output
    $this->renderScript('admin-manage/delete-menu-icon.tpl');
  }


  //For write constant in xml file during upgradation
	public function constantxmlAction() {

    $bodyFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_body_fontfamily');
    if(empty($bodyFontFamily)) {
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_body_fontfamily', 'Arial, Helvetica, sans-serif');
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_body_fontsize', '13px');
    }
    $headingFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_heading_fontfamily');
    if(empty($headingFontFamily)) {
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_heading_fontfamily', 'Arial, Helvetica, sans-serif');
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_heading_fontsize', '17px');
    }
    $mainmenuFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_mainmenu_fontfamily');
    if(empty($mainmenuFontFamily)) {
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_mainmenu_fontfamily', 'Arial, Helvetica, sans-serif');
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_mainmenu_fontsize', '13px');
    }
    $tabFontFamily = Engine_Api::_()->sesdating()->getContantValueXML('sesdating_tab_fontfamily');
    if(empty($tabFontFamily)) {
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_tab_fontfamily', 'Arial, Helvetica, sans-serif');
      Engine_Api::_()->sesdating()->readWriteXML('sesdating_tab_fontsize', '15px');
    }
		$referralurl = $this->_getParam('referralurl', false);
		if($referralurl == 'install') {
			$this->_redirect('install/manage');
		} elseif($referralurl == 'query') {
			$this->_redirect('install/manage/complete');
		}
	}
}
