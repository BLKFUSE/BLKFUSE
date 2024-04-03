<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminManageImportsController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_AdminManageImportsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sestutorial_admin_main', array(), 'sestutorial_admin_main_manageimport');
  }
  
  public function importAction() {

    
    $this->_helper->layout->setLayout('admin-simple');

    $this->view->form = $form = new Sestutorial_Form_Admin_Import();

    if ($this->getRequest()->isPost()) {
      
      $csvFile = explode(".", $_FILES['csvfile']['name']);

      
      if (($csvFile[1] != "csv")) {
        $itemError = Zend_Registry::get('Zend_Translate')->_("Choose only CSV file.");
        $form->addError($itemError);
        return;
      }
      
      $csv_file = $_FILES['csvfile']['tmp_name']; // specify CSV file path

      $csvfile = fopen($csv_file, 'r');
      $theData = fgets($csvfile);
      $thedata = explode('|',$theData);
      
      $title = $description = $category_id = $subcat_id = $subsubcat_id = $tags = $counter = 0;

      foreach($thedata as $data) {
        
        //Direct CSV
        if(trim(strtolower($data)) == 'title'){
          $title = $counter;	
        } else if(trim(strtolower($data)) == 'description'){
          $description = $counter;
        } else if(trim(strtolower($data)) == 'category_id'){
          $category_id = $counter;
        } else if(trim(strtolower($data)) == 'subcat_id'){
          $subcat_id = $counter;
        } else if(trim(strtolower($data)) == 'subsubcat_id'){
          $subsubcat_id = $counter;
        } else if(trim(strtolower($data)) == 'tags'){
          $tags = $counter;
        }
        $counter++;
      }
      
      $i = 0;
      $importedData = array();
      while (!feof($csvfile))
      {
        $csv_data[] = fgets($csvfile, 1024);
        $csv_array = explode("|", $csv_data[$i]);
        if(!engine_count($csv_array))
          continue;

        if(isset($csv_array[$title]))
          $importedData[$i]['title'] = $csv_array[$title];
          
        if(isset($csv_array[$description]))
          $importedData[$i]['description'] = $csv_array[$description];
          
        if(isset($csv_array[$category_id]))
          $importedData[$i]['category_id'] = $csv_array[$category_id];
          
        if(isset($csv_array[$subcat_id]))
          $importedData[$i]['subcat_id'] = $csv_array[$subcat_id];
          
        if(isset($csv_array[$subsubcat_id]))
          $importedData[$i]['subsubcat_id'] = $csv_array[$subsubcat_id];
      
        if(isset($csv_array[$tags]))
          $importedData[$i]['tags'] = $csv_array[$tags];
      
        $i++;
      }
      fclose($csvfile);
      
      $viewer = Engine_Api::_()->user()->getViewer();
      $viewer_id = $viewer->getIdentity();
      
      $tutorialTable = Engine_Api::_()->getDbtable('tutorials', 'sestutorial');
      
      $values = $form->getValues();

      if (isset($values['memberlevels'])) {
        $memberlevels = serialize($values['memberlevels']);
      } else {
        $memberlevels = serialize(array());
      }

      if (isset($values['networks'])) {
        $networks = serialize($values['networks']);
      } else {
        $networks = serialize(array());
      }
      
      if (isset($values['profile_types'])) {
        $profile_types = serialize($values['profile_types']);
      } else {
        $profile_types = serialize(array());
      }
      
      // Auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

      foreach($importedData as $tutorials) {

        if(isset($tutorials['title']) && !empty($tutorials['title'])) {
          $tutorial = $tutorialTable->createRow();
          $values['user_id'] = $viewer_id;
          $values['memberlevels'] = $memberlevels;
          $values['profile_types'] = $profile_types;
          $values['networks'] = $networks;
          $values['title'] = $tutorials['title'];
          $values['description'] = $tutorials['description'];
          $values['category_id'] = $tutorials['category_id'];
          $values['subcat_id'] = $tutorials['subcat_id'];
          $values['subsubcat_id'] = $tutorials['subsubcat_id'];
          
          $tutorial->setFromArray($values);
          $tutorial->save();
          
          
          if( empty($values['auth_view']) ) {
            $values['auth_view'] = 'everyone';
          }

          if( empty($values['auth_comment']) ) {
            $values['auth_comment'] = 'everyone';
          }

          $viewMax = array_search($values['auth_view'], $roles);
          $commentMax = array_search($values['auth_comment'], $roles);

          foreach( $roles as $i => $role ) {
            $auth->setAllowed($tutorial, $role, 'view', ($i <= $viewMax));
            $auth->setAllowed($tutorial, $role, 'comment', ($i <= $commentMax));
          }
          
          //Add tags work
          $tags = preg_split('/[,]+/', $tutorials['tags']);
          $tutorial->tags()->addTagMaps($viewer, $tags);
          
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $tutorial, 'sestutorial_new');
          // make sure action exists before attaching the tutorial to the activity
          if( $action ) {
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $tutorial);
          }

        }
      }

      $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 10,
        'parentRefresh' => 10,
        'messages' => array('You have successfully imported Tutorial.')
      ));
    }
  }

  public function downloadAction() {

    $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'Sestutorial' . DIRECTORY_SEPARATOR . "settings" .DIRECTORY_SEPARATOR.'default_template.csv';

    //KILL ZEND'S OB
    while (ob_get_level() > 0) {
      ob_end_clean();
    }
    
		@chmod($filepath, 0777);
		
		$default_template = 'title|description|category_id|subcat_id|subsubcat_id|tags';
		
    $fp = fopen(APPLICATION_PATH . '/temporary/default_template.csv', 'w+');
		fwrite($fp, $default_template);
		fclose($fp);
		
		$filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary'. DIRECTORY_SEPARATOR . 'default_template.csv';
		
		header("Content-Disposition: attachment; filename=" . urlencode(basename($filepath)), true);
		header("Content-Type: application/force-download", true);
		header("Content-Type: application/octet-stream", true);
		header("Content-Transfer-Encoding: Binary", true);
		header("Content-Type: application/download", true);
		header("Content-Description: File Transfer", true);
    header("Content-Length: " . filesize($filepath), true);
		readfile("$filepath");
    exit();
    return;
  }
}
