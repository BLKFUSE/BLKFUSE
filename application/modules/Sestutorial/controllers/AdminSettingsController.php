<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_settings');
    
    $this->view->form = $form = new Sestutorial_Form_Admin_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      include_once APPLICATION_PATH . "/application/modules/Sestutorial/controllers/License.php";
      $db = Engine_Db_Table::getDefaultAdapter();
      $values = $form->getValues();
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.pluginactivated')) {
      
        //START TEXT CHNAGE WORK IN CSV FILE
        $oldSigularWord = Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.text.singular', 'tutorial');
        $oldPluralWord = Engine_Api::_()->getApi('settings', 'core')->getSetting('sestutorial.text.plural', 'tutorials');
        $newSigularWord = $values['sestutorial_text_singular'] ? $values['sestutorial_text_singular'] : 'tutorial';
        $newPluralWord = $values['sestutorial_text_plural'] ? $values['sestutorial_text_plural'] : 'tutorials';
        $newSigularWordUpper = ucfirst($newSigularWord);
        $newPluralWordUpper = ucfirst($newPluralWord);
        if($newSigularWord != $oldSigularWord && $newPluralWord != $oldPluralWord) {
        
          $tmp = Engine_Translate_Parser_Csv::parse(APPLICATION_PATH . '/application/languages/en/sestutorial.csv', 'null', array('delimiter' => ';','enclosure' => '"'));
          if( !empty($tmp['null']) && is_array($tmp['null']) )
            $inputData = $tmp['null'];
          else
            $inputData = array();
            
          $OutputData = array();
          $chnagedData = array();
          foreach($inputData as $key => $input) {
            $chnagedData = str_replace(array($oldPluralWord, $oldSigularWord,ucfirst($oldPluralWord),ucfirst($oldSigularWord),strtoupper($oldPluralWord),strtoupper($oldSigularWord)), array($newPluralWord, $newSigularWord, ucfirst($newPluralWord), ucfirst($newSigularWord), strtoupper($newPluralWord), strtoupper($newSigularWord)), $input);       
            $OutputData[$key] = $chnagedData;
          }

          $targetFile = APPLICATION_PATH . '/application/languages/en/sestutorial.csv';
          if (file_exists($targetFile))
            @unlink($targetFile);
              
          touch($targetFile);
          chmod($targetFile, 0777);
              
          $writer = new Engine_Translate_Writer_Csv($targetFile);
          $writer->setTranslations($OutputData);
          $writer->write();
          //END CSV FILE WORK
        }
        
        foreach ($values as $key => $value) {
          if ($key == 'sestutorial_link') {
            $menu_table = Engine_Api::_()->getDbtable('menuitems', 'core');
            if ($value == 1) {
              $menu_table->update(array('enabled' => 1), array('name =?' => 'sestutorial_footer_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'sestutorial_mini_sestutorial'));
              if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmenu')) {
								Engine_Api::_()->getDbtable('menuitems', 'sesmenu')->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              }
            }else if ($value == 3){
              $menu_table->update(array('enabled' => 1), array('name =?' => 'core_main_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'sestutorial_mini_sestutorial'));
              $menu_table->update(array('enabled' =>0), array('name =?' => 'sestutorial_footer_sestutorial'));
              if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmenu')) {
								Engine_Api::_()->getDbtable('menuitems', 'sesmenu')->update(array('enabled' => 1), array('name =?' => 'core_main_sestutorial'));
              }
            }else if ($value == 2){
              $menu_table->update(array('enabled' => 1), array('name =?' => 'sestutorial_mini_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'sestutorial_footer_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmenu')) {
								Engine_Api::_()->getDbtable('menuitems', 'sesmenu')->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              }
            }else if ($value == 0){
              $menu_table->update(array('enabled' => 0), array('name =?' => 'sestutorial_mini_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              $menu_table->update(array('enabled' => 0), array('name =?' => 'sestutorial_footer_sestutorial'));
              if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmenu')) {
								Engine_Api::_()->getDbtable('menuitems', 'sesmenu')->update(array('enabled' => 0), array('name =?' => 'core_main_sestutorial'));
              }
            }
          }
          Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved.');
        if($error)
        $this->_helper->redirector->gotoRoute(array());
      }
    }
  }

  public function manageWidgetizePageAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_managewidgetizepage');
    $pagesArray = array('sestutorial_index_home', 'sestutorial_category_browse', 'sestutorial_index_browse', 'sestutorial_index_tags', 'sestutorial_category_index', 'sestutorial_index_view');
    $this->view->pagesArray = $pagesArray;
  }	
}
