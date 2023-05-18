<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminTargettingController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_AdminTargetingController extends Core_Controller_Action_Admin {
  public function indexAction() {
    $db = Engine_Db_Table::getDefaultAdapter();	
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sescommunityads_admin_main', array(), 'sescommunityads_admin_main_targetting');
    $this->view->form = $form = new Sescommunityads_Form_Admin_Target();
    $targetAdsColumns = Engine_Api::_()->sescommunityads()->getTargetAds(array('fieldsArray'=>1));
    if(!$this->getRequest()->isPost()){
      $form->populate($targetAdsColumns);  
      return;
    }
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();      
      // remove column key from table
      $table = Engine_Api::_()->getDbTable('targetads','sescommunityads');
      $tableName = $table->info('name');
      $values = array_filter($values);
      $dropColumns = array_diff(array_keys($targetAdsColumns), array_keys($values));
      foreach($dropColumns as $column){
         $db->query("ALTER TABLE `" . $tableName . "` DROP `$column`");  
      }
      //add column in table
      foreach($values as $key => $value){
        if(strpos($key,'profile_field_') !== false || strpos($key,'profile_field_birthday_') !== false || strpos($key,'netowrk') !== false)
          continue;
        try{
          $db->query("ALTER TABLE `" . $tableName . "` ADD `$key` VARCHAR( 255 ) NULL");
        }catch(Exception $e){
            //silence
        }
      }      
    }
    $form->addNotice('Data Saved Successfully.');
  }
}