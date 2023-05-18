<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminSettingsController.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_settings');

    $this->view->form = $form = new Sesmember_Form_Admin_Global();
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sesmember/controllers/License.php";
      //$this->getUserPhotoURL();
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.pluginactivated')) {
        if($values['sesmember_autofollow'] == 1) {
            $db->query('UPDATE `engine4_sesmember_follows` SET `user_approved` = "1";');
            $db->query('UPDATE `engine4_sesmember_follows` SET `resource_approved` = "1";');
        }
        foreach ($values as $key => $value) {
          if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
              Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
          if (!$value && strlen($value) == 0)
              continue;
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
        $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
  
  public function manageWidgetizePageAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmember_admin_main', array(), 'sesmember_admin_main_managepages');
    
    $this->view->pagesArray = array('sesmember_index_browse', 'sesmember_index_top-members', 'sesmember_review_browse', 'sesmember_index_locations', 'sesmember_index_pinborad-view-members', 'sesmember_review_view', 'sesmember_index_alphabetic-members-search', 'sesmember_index_nearest-member');
  }

  public function resetPageSettingsAction() {

    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
    $form->setTitle("Reset This Page?");
    $form->setDescription('Are you sure you want to reset this page? Once reset, it will not be undone.');
    $form->submit->setLabel("Reset Page");
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    $page_id = (int) $this->_getParam('page_id');
    $pageName = $this->_getParam('page_name');
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    try {
      $db->query("DELETE FROM `engine4_core_content` WHERE `engine4_core_content`.`page_id` = $page_id");
      include_once APPLICATION_PATH . "/application/modules/Sesmember/controllers/resetPage.php";
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    if( $this->getRequest()->getParam('format') == 'smoothbox' ) {
      return $this->_forward('success', 'utility', 'core', array(
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('This Page has been reset successfully.')),
        'smoothboxClose' => true,
      ));
    }
  }

  function getUserPhotoURL() {

    $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . "application/modules/User/Model/User.php";
    chmod($file, 0777);
    $Vdata = file_get_contents($file);
    $searchterm = "array('search', 'displayname', 'username');";

    $findString = "public function getPhotoUrl(";

    if (strpos($Vdata, "$findString") !== false) {
    } else {
      $new_code = '

      public function getPhotoUrl($type = NULL) {
        if(!$this->getIdentity()) return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
        $photoId = $this->photo_id;
        if(Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sesmember")) {
          if(empty($photoId)) {
            $profiletype_id = Engine_Api::_()->sesmember()->getProfileTypeId(array("user_id" => $this->user_id, "field_id" => 1));
            $photo_id = Engine_Api::_()->getDbtable("profilephotos", "sesmember")->getPhotoId($profiletype_id);
            if ($photo_id) {
              $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photo_id, $type);
              if($file) {
                return $file->map();
              } elseif($photo_id) {
                $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photo_id,"thumb.profile");
                if($file)
                  return $file->map();
              } else {
                return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
              }
            } else {
              return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
            }
          } else {
            $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId, $type);
            if($file) {
              return $file->map();
            } elseif($photoId) {
              $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId,"thumb.profile");
              if($file)
              return $file->map();
            } else {
              return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
            }
          }
        }
        else {
          if ($photoId) {
            $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId, $type);
            if($file)
            return $file->map();
            else
            return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
          } else {
            return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
          }
        }
      }';
      $newstring = str_replace($searchterm, $searchterm.$new_code, $Vdata);
      chmod($file, 0777);
      chmod($file, 0777);
      $user_model_codewrite = fopen($file, 'w+');
      fwrite($user_model_codewrite, $newstring);
      fclose($user_model_codewrite);
    }
  }
}
