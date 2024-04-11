<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_settings');

    $this->view->form = $form = new Sesdating_Form_Admin_Global();

    if ($this->getRequest()->isPost() && $form->isValid($_POST)) {
      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sesdating/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.pluginactivated')) {
        //Landing Page Work
				if (!empty($values['sesdating_layout_enable'])) {
                    $this->landingpagesetup();
				}
				//Landing Page Work

				//Here we have set the value of theme active.
				$themeactive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.themeactive');
				if (empty($themeactive)) {

					$db->query("INSERT IGNORE INTO `engine4_core_themes` (`name`, `title`, `description`, `active`) VALUES ('sesdating', 'Responsive Dating Theme', '', 0)");
          $themeName = 'sesdating';
					$themeTable = Engine_Api::_()->getDbtable('themes', 'core');
					$themeSelect = $themeTable->select()
									->orWhere('theme_id = ?', $themeName)
									->orWhere('name = ?', $themeName)
									->limit(1);
					$theme = $themeTable->fetchRow($themeSelect);
					if ($theme) {
						$db = $themeTable->getAdapter();
						$db->beginTransaction();
						try {
							$themeTable->update(array('active' => 0), array('1 = ?' => 1));
							$theme->active = true;
							$theme->save();
							// clear scaffold cache
							Core_Model_DbTable_Themes::clearScaffoldCache();
							$db->commit();
						} catch (Exception $e) {
							$db->rollBack();
							throw $e;
						}
					}
					Engine_Api::_()->getApi('settings', 'core')->setSetting('sesdating.themeactive', 1);
				}

				//Start Make extra file for dating theme custom css
        $themeDirName = APPLICATION_PATH . '/application/themes/sesdating';
        @chmod($themeDirName, 0777);
        if (!is_readable($themeDirName)) {
          $itemError = Zend_Registry::get('Zend_Translate')->_("You have not read permission on below file path. So, please give chmod 777 recursive permission to continue this process. Path Name: %s", $themeDirName);
          $form->addError($itemError);
          return;
        }
        $fileName = $themeDirName . '/sesdating-custom.css';
        $fileexists = @file_exists($fileName);
        if (empty($fileexists)) {
          @chmod($themeDirName, 0777);
          if (!is_writable($themeDirName)) {
            $itemError = Zend_Registry::get('Zend_Translate')->_("You have not writable permission on below file path. So, please give chmod 777 recursive permission to continue this process. <br /> Path Name: $themeDirName");
            $form->addError($itemError);
            return;
          }
          $fh = @fopen($fileName, 'w');
          @fwrite($fh, '/* ADD YOUR CUSTOM CSS HERE */');
          @chmod($fileName, 0777);
          @fclose($fh);
          @chmod($fileName, 0777);
          @chmod($fileName, 0777);
        }
        //Start Make extra file for dating theme custom css

				foreach ($values as $key => $value) {
				 if ($key == 'sesdating_responsive_layout' || $key == 'sesdating_body_background_image' || $key == 'sesdating_left_columns_width' || $key == 'sesdating_right_columns_width' || $key == 'sesdating_feed_style' || $key == 'sesdating_user_photo_round') {
					 	if($key ==  'sesdating_body_background_image') {
							if($value == '') {
								$value = 'public/admin/blank.png';
              } else {
                if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
                Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
                if (!$value && strlen($value) == 0)
                    continue;
                Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
                $value = Engine_Api::_()->sesdating()->getFileUrl($value);
              }
						}
            Engine_Api::_()->sesdating()->readWriteXML($key, $value);
          }
          if($key ==  'sesdating_loginsignupbgimage') {
            if($value == '')
              $value = 'public/admin/blank.png';
          }
          if($key != 'sesdating_body_background_image') {
            if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
                Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
            if (!$value && strlen($value) == 0)
                continue;
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
          }
				}

				$form->addNotice('Your changes have been saved.');
				if($error)
				$this->_helper->redirector->gotoRoute(array());
      }
    }
  }

  public function stylingAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_styling');

    $this->view->customtheme_id = $this->_getParam('customtheme_id', null);

    $this->view->form = $form = new Sesdating_Form_Admin_Styling();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
      $values = $form->getValues();
      unset($values['header_settings']);
      unset($values['footer_settings']);
      unset($values['body_settings']);
      $db = Engine_Db_Table::getDefaultAdapter();

      $settingsTable = Engine_Api::_()->getDbTable('settings', 'core');
      $settingsTableName = $settingsTable->info('name');

      foreach ($values as $key => $value) {

        if (isset($_POST['save'])) {
          Engine_Api::_()->sesdating()->readWriteXML($key, $value, '');
        }

        if ((isset($_POST['submit']) || isset($_POST['save'])) && $values['custom_theme_color'] > '13') {
          if ($values['custom_theme_color'] > '13') {
            $description = serialize($values);
            $db->query("UPDATE `engine4_sesdating_customthemes` SET `description` = '".$description."' WHERE `engine4_sesdating_customthemes`.`customtheme_id` = '".$values['custom_theme_color']."'");
          }
        }

        if ($values['theme_color'] == '5') {
          $stringReplace = str_replace('_', '.', $key);
          if($key == 'sesdating_button_background_color') {
            $stringReplace = 'sesdating.button.backgroundcolor';
          }
          if($key == 'sesdating_font_color') {
            $stringReplace = 'sesdating.fontcolor';
          }

          $columnVal = $settingsTable->select()
                                    ->from($settingsTableName, array('value'))
                                    ->where('name = ?', $stringReplace)
                                    ->query()
                                    ->fetchColumn();
          if($columnVal) {
            $db->query('UPDATE `engine4_core_settings` SET `value` = "'.$value.'" WHERE `engine4_core_settings`.`name` = "'.$stringReplace.'";');
          } else {
            $db->query('INSERT IGNORE INTO `engine4_core_settings` (`name`, `value`) VALUES ("'.$stringReplace.'", "'.$value.'");');
          }
        }
      }


      //Clear scaffold cache
      Core_Model_DbTable_Themes::clearScaffoldCache();
      
      $form->addNotice('Your changes have been saved.');

      if($values['theme_color'] != 5 || $values['custom_theme_color'] < 13) {
        $this->_helper->redirector->gotoRoute(array('module' => 'sesdating', 'controller' => 'settings', 'action' => 'styling'),'admin_default',true);
      } else if($values['theme_color'] == 5 && $values['custom_theme_color'] > 13) {
        $this->_helper->redirector->gotoRoute(array('module' => 'sesdating', 'controller' => 'settings', 'action' => 'styling', 'customtheme_id' => $values['custom_theme_color']),'admin_default',true);
      }
    }
    $this->view->activatedTheme = Engine_Api::_()->sesdating()->getContantValueXML('custom_theme_color');
  }

  //Get Custom theme color values
  public function getcustomthemecolorsAction() {

    $customtheme_id = $this->_getParam('customtheme_id', 22);
    if(empty($customtheme_id))
      return;
    $customthemeItem = Engine_Api::_()->getItem('sesdating_customthemes', $customtheme_id);
    $customthecolorvalue = unserialize($customthemeItem->description);
    $customthecolorArray = array();
    foreach($customthecolorvalue as $key =>  $customthecolorvalues) {
      $customthecolorArray[] = $key.'||'.$customthecolorvalues;
    }
    echo json_encode($customthecolorArray);die;
  }

  public function addCustomThemeAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $customtheme_id = $this->_getParam('customtheme_id', 0);
    $this->view->form = $form = new Sesdating_Form_Admin_CustomTheme();
    if ($customtheme_id) {
      $form->setTitle("Edit Custom Theme Name");
      $form->submit->setLabel('Save Changes');
      $customtheme = Engine_Api::_()->getItem('sesdating_customthemes', $customtheme_id);
      $form->populate($customtheme->toArray());
    }
    if ($this->getRequest()->isPost()) {
      if (!$form->isValid($this->getRequest()->getPost()))
        return;
      $db = Engine_Api::_()->getDbtable('customthemes', 'sesdating')->getAdapter();
      $db->beginTransaction();
      try {
        $table = Engine_Api::_()->getDbtable('customthemes', 'sesdating');
        $values = $form->getValues();

        if(!$customtheme_id)
          $customtheme = $table->createRow();
        $customtheme->setFromArray($values);
        $customtheme->save();

        if(!empty($values['customthemeid'])) {
          $customthemeItem = Engine_Api::_()->getItem('sesdating_customthemes', $values['customthemeid']);
          $description = unserialize($customthemeItem->description);
          $finalDescription = serialize(array_merge($description, array('custom_theme_color' => $customtheme->customtheme_id)));
          $customtheme->description = $finalDescription;
          $customtheme->save();
        }
        $db->commit();
        return $this->_forward('success', 'utility', 'core', array(
          'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesdating', 'controller' => 'settings', 'action' => 'styling', 'customtheme_id' => $customtheme->customtheme_id),'admin_default',true),
          'messages' => array('New Custom theme created successfully.')
        ));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

    }

  }

  public function deleteCustomThemeAction() {

    $this->_helper->layout->setLayout('admin-simple');
    $this->view->customtheme_id = $customtheme_id = $this->_getParam('customtheme_id', 0);

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();

      try {
        $slideImage = Engine_Api::_()->getItem('sesdating_customthemes', $customtheme_id);
        $slideImage->delete();
        $db->commit();
        $activatedTheme = Engine_Api::_()->sesdating()->getContantValueXML('custom_theme_color');
        $this->_forward('success', 'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesdating', 'controller' => 'settings', 'action' => 'styling', 'customtheme_id' => $activatedTheme),'admin_default',true),
            'messages' => array('You have successfully delete custom theme.')
        ));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

    } else {
      // Output
      $this->renderScript('admin-settings/delete-custom-theme.tpl');
    }
  }

  public function widgetCheck($params = array()) {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    return $db->select()
                    ->from('engine4_core_content', 'content_id')
                    ->where('type = ?', 'widget')
                    ->where('page_id = ?', $params['page_id'])
                    ->where('name = ?', $params['widget_name'])
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }


  public function landingpagesetup() {

	  $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	   //Landing Page Set and Already Landing Page Make Backup
		$orlanpage_id = $db->select()
				      ->from('engine4_core_pages', 'page_id')
				      ->where('name = ?', 'core_index_index')
				      ->limit(1)
				      ->query()
				      ->fetchColumn();
		if($orlanpage_id) {
		  $db->query('UPDATE `engine4_core_content` SET `page_id` = "123456" WHERE `engine4_core_content`.`page_id` = "'.$orlanpage_id.'";');
		  $db->query('UPDATE `engine4_core_pages` SET `page_id` = "123456" WHERE `engine4_core_pages`.`page_id` = "'.$orlanpage_id.'";');
		  $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index_12" WHERE `engine4_core_pages`.`name` = "core_index_index";');
		}

		//New Landing Page
		$page_id = $db->select()
					      ->from('engine4_core_pages', 'page_id')
					      ->where('name = ?', 'sesdating_index_sesbackuplandingppage')
					      ->limit(1)
					      ->query()
					      ->fetchColumn();
		if( !$page_id ) {
		   $widgetOrder = 1;
		  //Insert page
		  $db->insert('engine4_core_pages', array(
		    'name' => 'sesdating_index_sesbackuplandingppage',
		    'displayname' => 'Landing Page',
		    'title' => 'Landing Page',
		    'description' => 'This is your site\'s landing page.',
		    'custom' => 0,
		  ));
		  $newpagelastId = $page_id = $db->lastInsertId();

		  // Insert main
		  $db->insert('engine4_core_content', array(
		    'type' => 'container',
		    'name' => 'main',
		    'page_id' => $page_id,
		  ));
		  $main_id = $db->lastInsertId();

		  // Insert middle
		  $db->insert('engine4_core_content', array(
		    'type' => 'container',
		    'name' => 'middle',
		    'page_id' => $page_id,
		    'parent_content_id' => $main_id,
		    'order' => 2,
		  ));
		  $middle_id = $db->lastInsertId();

		  // Insert content
		  $db->insert('engine4_core_content', array(
		    'type' => 'widget',
		    'name' => 'sesdating.landing-page',
		    'page_id' => $page_id,
		    'parent_content_id' => $middle_id,
		    'order' => $widgetOrder++,
		  ));

            $newbakpage_id = $db->select()
					      ->from('engine4_core_pages', 'page_id')
					      ->where('name = ?', 'sesdating_index_sesbackuplandingppage')
					      ->limit(1)
					      ->query()
					      ->fetchColumn();
            if($newbakpage_id) {

                $db->query('UPDATE `engine4_core_content` SET `page_id` = "3" WHERE `engine4_core_content`.`page_id` = "'.$newbakpage_id.'";');
                $db->query('UPDATE `engine4_core_pages` SET `page_id` = "3" WHERE `engine4_core_pages`.`page_id` = "'.$newbakpage_id.'";');
                $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index" WHERE `engine4_core_pages`.`name` = "sesdating_index_sesbackuplandingppage";');
                $db->query('UPDATE `engine4_core_pages` SET `name` = "sesdating_index_sesbackuplandingppage" WHERE `engine4_core_pages`.`name` = "core_index_index_12";');
                $db->query('UPDATE `engine4_core_pages` SET `displayname` = "SNS - Responsive Dating Theme - Landing Page Backup" WHERE `engine4_core_pages`.`name` = "sesdating_index_sesbackuplandingppage";');
            }
		}
  }

  //Upload Home Banner images
	public function uploadHomeBanner(){

		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$slideData = array('slide_1', 'slide_2', 'slide_3');
		foreach($slideData as $data) {
      $data1 = explode('_', $data);
      $PathFile = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sesdating' . DIRECTORY_SEPARATOR . "externals" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "homebanner" . DIRECTORY_SEPARATOR;
      if (is_file($PathFile . $data . '.jpg')) {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($PathFile . $data.'.jpg', array(
            'parent_id' => $data1[1],
            'parent_type' => 'sesdating_slide',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $file_id = $filename->file_id;
        $db->query("UPDATE `engine4_sesdating_slides` SET `file_id` = '" . $file_id . "' WHERE slide_id = " . $data1[1]);
      }
		}
	}


  public function typographyAction() {

    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_admin_main', array(), 'sesdating_admin_main_typography');

    $this->view->form = $form = new Sesdating_Form_Admin_Typography();

    if ($this->getRequest()->isPost() && $form->isValid($_POST)) {

      $values = $form->getValues();
      unset($values['sesdating_body']);
      unset($values['sesdating_heading']);
      unset($values['sesdating_mainmenu']);
      unset($values['sesdating_tab']);

      $db = Engine_Db_Table::getDefaultAdapter();
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.pluginactivated')) {

        foreach ($values as $key => $value) {

          if($values['sesdating_googlefonts']) {
            unset($values['sesdating_body_fontfamily']);
            unset($values['sesdating_heading_fontfamily']);
            unset($values['sesdating_mainmenu_fontfamily']);
            unset($values['sesdating_tab_fontfamily']);

            unset($values['sesdating_body_fontsize']);
            unset($values['sesdating_heading_fontsize']);
            unset($values['sesdating_mainmenu_fontsize']);
            unset($values['sesdating_tab_fontsize']);

            if($values['sesdating_googlebody_fontfamily'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_body_fontfamily', $values['sesdating_googlebody_fontfamily']);

            if($values['sesdating_googlebody_fontsize'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_body_fontsize', $values['sesdating_googlebody_fontsize']);

            if($values['sesdating_googleheading_fontfamily'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_heading_fontfamily', $values['sesdating_googleheading_fontfamily']);

            if($values['sesdating_googleheading_fontsize'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_heading_fontsize', $values['sesdating_googleheading_fontsize']);

            if($values['sesdating_googlemainmenu_fontfamily'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_mainmenu_fontfamily', $values['sesdating_googlemainmenu_fontfamily']);

            if($values['sesdating_googlemainmenu_fontsize'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_mainmenu_fontsize', $values['sesdating_googlemainmenu_fontsize']);

            if($values['sesdating_googletab_fontfamily'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_tab_fontfamily', $values['sesdating_googletab_fontfamily']);

            if($values['sesdating_googletab_fontsize'])
              Engine_Api::_()->sesdating()->readWriteXML('sesdating_tab_fontsize', $values['sesdating_googletab_fontsize']);

            //Engine_Api::_()->sesdating()->readWriteXML($key, $value);
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
          } else {
            unset($values['sesdating_googlebody_fontfamily']);
            unset($values['sesdating_googleheading_fontfamily']);
            unset($values['sesdating_googleheading_fontfamily']);
            unset($values['sesdating_googletab_fontfamily']);

            unset($values['sesdating_googlebody_fontsize']);
            unset($values['sesdating_googleheading_fontsize']);
            unset($values['sesdating_googlemainmenu_fontsize']);
            unset($values['sesdating_googletab_fontsize']);

            Engine_Api::_()->sesdating()->readWriteXML($key, $value);
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
          }
        }
        $form->addNotice('Your changes have been saved.');
        $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
}
