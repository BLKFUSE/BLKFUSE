<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eresume
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2021-04-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eresume_IndexController extends Sesapi_Controller_Action_Standard {

    public function indexAction() {
      
      $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      if(empty($viewer_id)) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      }
      
      // Don't render this if not authorized
      $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();

      // Get subject and check auth
      $this->view->subject = $subject = $viewer; //Engine_Api::_()->core()->getSubject('user');

      $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
      $this->view->subject_id = $subject_id = $subject->getIdentity();

      if($subject_id != $viewer_id)
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
        
      $table = Engine_Api::_()->getItemTable('eresume_resume');
      $tableName = $table->info('name');
      
      $select = $table->select()
                      ->from($tableName)
                      ->where('owner_id =?', $viewer_id);
      $results = $paginator = Zend_Paginator::factory($select);
      
      $max_resume = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'max');
      $canCreate = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'create');
      $canEdit = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'edit');
      $canDelete = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'delete');
      
      
      $result = array();
      $counterLoop = 0;
      foreach($results as $item) {

        $resource = $item->toArray();
        
        
        if(!empty($this->view->viewer_id)) {
        
          $menuoptions= array();
          $counter = 0;
          
//           $menuoptions[$counter]['name'] = "preview";
//           $menuoptions[$counter]['resume_id'] = $item->resume_id;
//           $menuoptions[$counter]['label'] = $this->view->translate("Preview");
//           $counter++;
          
//           if($item->resume_id) {
//             $menuoptions[$counter]['name'] = "download";
//             $menuoptions[$counter]['resume_id'] = $item->resume_id;
//             $menuoptions[$counter]['label'] = $this->view->translate("Download Resume");
//             $menuoptions[$counter]['url'] = $this->getBaseUrl(true, 'eresume/index/download-resume');
//             $counter++;
//           }

//           if($canCreate) {
//             $menuoptions[$counter]['name'] = "create";
//             $menuoptions[$counter]['label'] = $this->view->translate("Create Resume");
//             $counter++;
//           }
          
          if($canEdit) {
            $menuoptions[$counter]['name'] = "edit";
            $menuoptions[$counter]['resume_id'] = $item->resume_id;
            $menuoptions[$counter]['label'] = $this->view->translate("Edit");
            $counter++;
            
            $menuoptions[$counter]['name'] = "editinformation";
            $menuoptions[$counter]['resume_id'] = $item->resume_id;
            $menuoptions[$counter]['label'] = $this->view->translate("Edit Resume Information");
            $counter++;
          }
          
          if($canDelete) {
            $menuoptions[$counter]['name'] = "delete";
            $menuoptions[$counter]['resume_id'] = $item->resume_id;
            $menuoptions[$counter]['label'] = $this->view->translate("Delete");
            $counter++;
          }
        }
        $result['resumes'][$counterLoop] = $resource;
        $result['resumes'][$counterLoop]['menus'] = $menuoptions;
        $counterLoop++;
      }
      //$result['downloadurl'] = $this->getBaseUrl(true, 'eresume/index/download-resume');;
      
      $result['canCreate'] = $canCreate;
      
      $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
      $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
      $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
      $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
      if(engine_count($result) <= 0) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work experiences.'), 'result' => array()));
      } else {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
      }
      
    }
    
    
    public function generateresumeAction() {
        $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
        $template_id = $this->_getParam('template_id', 1);
        
        if(empty($resume_id))
          return $this->_forward('requireauth', 'error', 'core');
        $resume = Engine_Api::_()->getItem('eresume_resume', $resume_id);
        Engine_Api::_()->eresume()->createPdfFile($resume_id, $template_id);
    }

    public function downloadResumeAction() {
      $resume_id = $this->_getParam('resume_id', null);
      $resume = Engine_Api::_()->getItem('eresume_resume', $resume_id);
      if(empty($resume_id)) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      }
      $pdfname =	Engine_Api::_()->eresume()->createPdfFile($resume_id, $resume->template_id);
    }
  
    public function generateAction() {
        
      if( !$this->_helper->requireUser()->isValid())
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
      $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
      if(empty($resume_id))
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
        
      $resume = Engine_Api::_()->getItem('eresume_resume', $resume_id);


      // set up data needed to check quota
      $viewer = Engine_Api::_()->user()->getViewer();
      $values['user_id'] = $viewer->getIdentity();
      
      $result = array();
      $counterLoop = 0;
      for($i=1;$i<=20;$i++) {
        $result['template_id'][$counterLoop]['id'] = $i;
        $result['template_id'][$counterLoop]['url'] = $this->getBaseUrl(true, 'application/modules/Eresume/externals/images/template'.$i.'.png');
        $counterLoop++;
      }
      //$result['template_id'][$counterLoop]['previewurl'] = $this->getBaseUrl(true, 'resumes/preview/');;

      
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result));
    }

  public function downloadAction() {
    
    $project = Engine_Api::_()->getItem('eresume_project', $this->_getParam('project_id'));
    
    $file = Engine_Api::_()->getItem('core_file', $project->photo_id);

    $storageTable = Engine_Api::_()->getDbTable('files', 'storage');
    $select = $storageTable->select()->from($storageTable->info('name'), array('file_id', 'storage_path', 'name'))->where('file_id = ?', $project->photo_id);
    $storageData = $storageTable->fetchRow($select);
    
    $storage = Engine_Api::_()->getItem('storage_file', $storageData->file_id);
    $basePath = $storage->map();
    if($storage->service_id == 1)
      $basePath = APPLICATION_PATH . '/' . $storageData->storage_path;
      
    $storageData = (object) $storageData->toArray();
    if (empty($storageData->name) || $storageData->name == '' || empty($storageData->storage_path) || $storageData->storage_path == '')
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    if($storage->service_id != 1) {
      
      $details = Engine_Api::_()->getDbTable('services', 'storage')->getServiceDetails();
      $config = Zend_Json::decode($details->config);

      $s3 = new Zend_Service_Amazon_S3($config['accessKey'], $config['secretKey'], $config['region']);
      $object = $s3->getObject($config['bucket'].'/'. $storageData->storage_path);
      $info = $s3->getInfo($config['bucket'].'/'. $storageData->storage_path);

      header("Content-Disposition: attachment; filename=" . urlencode(basename($storageData->name)), true);
      header("Content-Transfer-Encoding: Binary", true);
      header('Content-Type: ' . $info['type']);
      header("Content-Type: application/force-download", true);
      header("Content-Type: application/octet-stream", true);
      header("Content-Type: application/download", true);
      header("Content-Description: File Transfer", true);
      header("Content-Length: " . $info['size'], true);
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      //send file to browser for download. 
      ob_clean();
      flush();
      echo $object;
      exit();
    } else {
      @chmod($basePath, 0777);
      header("Content-Disposition: attachment; filename=" . urlencode(basename($storageData->name)), true);
      header("Content-Transfer-Encoding: Binary", true);
      header("Content-Type: application/force-download", true);
      header("Content-Type: application/octet-stream", true);
      header("Content-Type: application/download", true);
      header("Content-Description: File Transfer", true);
      header("Content-Length: " . filesize($basePath), true);
      readfile("$basePath");
      exit();
      // for safety resason double check
      return;
    }
  }
  
  public function previewAction() {
    
    $this->view->template_id = $template_id = $this->_getParam('template_id', null);
    if(empty($template_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    $this->view->resume = Engine_Api::_()->getItem('eresume_resume', $resume_id);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if(empty($viewer_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $getDetailId = Engine_Api::_()->getDbTable('details', 'eresume')->getDetailId($resume_id, $viewer->getIdentity());
    $this->view->details = Engine_Api::_()->getItem('eresume_detail', $getDetailId);
    
    $this->view->skills = Engine_Api::_()->getDbtable('skills', 'eresume')->getSkills(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->interests = Engine_Api::_()->getDbtable('interests', 'eresume')->getInterests(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->strengths = Engine_Api::_()->getDbtable('strengths', 'eresume')->getStrengths(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->hobbies = Engine_Api::_()->getDbtable('hobbies', 'eresume')->getHobbies(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->achievements = Engine_Api::_()->getDbtable('achievements', 'eresume')->getAchievements(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->curriculars = Engine_Api::_()->getDbTable('curriculars', 'eresume')->getCurriculars(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $this->view->experienceEntries = Engine_Api::_()->getDbTable('experiences', 'eresume')->getAllExperiences($resume_id, $viewer_id);
    
    $this->view->projectEntries = $projectEntries = Engine_Api::_()->getDbTable('projects', 'eresume')->getAllProjects($resume_id, $viewer_id);
    
    $this->view->certificateEntries = $certificateEntries = Engine_Api::_()->getDbTable('certificates', 'eresume')->getAllCertificates($resume_id, $viewer_id);
    
    $this->view->educationEntries = $educationEntries = Engine_Api::_()->getDbTable('educations', 'eresume')->getAllEducations($resume_id, $viewer_id);
    
    $this->view->referenceEntries = $referenceEntries = Engine_Api::_()->getDbTable('references', 'eresume')->getAllReferences($resume_id, $viewer_id);
  }
  
  public function editAchievementAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->achievement_id = $achievement_id = $this->_getParam('achievement_id');
    $this->view->achievement = $achievement = Engine_Api::_()->getItem('eresume_achievement', $achievement_id);


    $this->view->form = $form = new Eresume_Form_Resume_EditAchievement();
    // Populate form
    $form->populate($achievement->toArray());
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_achievement'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $achievement->setFromArray($_POST);
      $achievement->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your achievement is successfully edited.'))));
  }
  
  public function editCurricularAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->curricular_id = $curricular_id = $this->_getParam('curricular_id');
    $this->view->curricular = $curricular = Engine_Api::_()->getItem('eresume_curricular', $curricular_id);


    $this->view->form = $form = new Eresume_Form_Resume_EditCurricular();
    // Populate form
    $form->populate($curricular->toArray());
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_curricular'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $curricular->setFromArray($_POST);
      $curricular->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your curricular is successfully edited.'))));
  }
  
  public function editStrengthAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->strength_id = $strength_id = $this->_getParam('strength_id');
    $this->view->strength = $strength = Engine_Api::_()->getItem('eresume_strength', $strength_id);

    $this->view->form = $form = new Eresume_Form_Resume_EditStrength();
    // Populate form
    $form->populate($strength->toArray());
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_strength'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $strength->setFromArray($_POST);
      $strength->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your strength is successfully added.'))));
  }
  
  
  public function editHobbieAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->hobbie_id = $hobbie_id = $this->_getParam('hobbie_id');
    $this->view->hobbie = $hobbie = Engine_Api::_()->getItem('eresume_hobbie', $hobbie_id);

    $this->view->form = $form = new Eresume_Form_Resume_EditHobbie();
    // Populate form
    $form->populate($hobbie->toArray());
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_hobbie'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $hobbie->setFromArray($_POST);
      $hobbie->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your hobbie is successfully edited.'))));
  }
  
  
  public function editInterestAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->interest_id = $interest_id = $this->_getParam('interest_id');
    $this->view->interest = $interest = Engine_Api::_()->getItem('eresume_interest', $interest_id);


    $this->view->form = $form = new Eresume_Form_Resume_EditInterest();
    $form->populate($interest->toArray());
    

    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_interest'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $interest->setFromArray($_POST);
      $interest->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your interest is successfully edited.'))));
  }
  
  public function certificateAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('certificate', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $certificate_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'certificate_count');
    
    $certificateEntries = $paginator = Engine_Api::_()->getDbTable('certificates', 'eresume')->getAllCertificates($resume_id, $viewer->getIdentity(), 1);
    
    $result = array();
    $counterLoop = 0;
    foreach($certificateEntries as $item) {

      $resource = $item->toArray();
      
      if($item->photo_id) {
        $img_path = Engine_Api::_()->storage()->get($item->photo_id, '');
        $img_path = $img_path->getPhotoUrl();
        $resource['image_url'] = $this->getBaseUrl(false, $img_path);
      } else {
        $resource['image_url'] = $this->getBaseUrl(false, 'application/modules/Eresume/externals/images/certificate-thumb.png');
      }

      
      if(!empty($this->view->viewer_id)) {

        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Certificate");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['certificates'][$counterLoop] = $resource;
      $result['certificates'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work certificates.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
  }
  
  public function addCertificateAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $certificate_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'certificate_count');
    $certificateEntries = $certificateEntries = Engine_Api::_()->getDbTable('certificates', 'eresume')->getAllCertificates($resume_id, $viewer->getIdentity());
    
    if($certificate_count <= engine_count($certificateEntries)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed certificates.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->form = $form = new Eresume_Form_Resume_AddCertificate();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_certificate'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $table = Engine_Api::_()->getDbTable('certificates', 'eresume');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $values = array_merge($_POST, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity()
      ));
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      
      
      if (isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['photo_id'], array(
            'parent_id' => $row->certificate_id,
            'parent_type' => 'eresume_certificate',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $row->photo_id = $filename->file_id;
        $row->save();
      }
      if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['image'], array(
            'parent_id' => $row->certificate_id,
            'parent_type' => 'eresume_certificate',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $row->photo_id = $filename->file_id;
        $row->save();
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your certificate is successfully added.'))));
  }

  public function editCertificateAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $certificate_id = $certificate_id = $this->_getParam('certificate_id');
    $certificate = $certificate = Engine_Api::_()->getItem('eresume_certificate', $certificate_id);

    $this->view->form = $form = new Eresume_Form_Resume_EditCertificate();
    // Populate form
    $form->populate($certificate->toArray());

    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_certificate'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }


    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

      $certificate->setFromArray($_POST);
      $certificate->modified_date = date('Y-m-d H:i:s');
      $certificate->save();
      
      if (!empty($_FILES['photo_id']) && ($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['photo_id'], array(
            'parent_id' => $certificate->certificate_id,
            'parent_type' => 'eresume_certificate',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $certificate->photo_id = $filename->file_id;
        $certificate->save();
      }
      if (!empty($_FILES['image']) && ($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['image'], array(
            'parent_id' => $certificate->certificate_id,
            'parent_type' => 'eresume_certificate',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $certificate->photo_id = $filename->file_id;
        $certificate->save();
      }
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your certificate is successfully edited.'))));
  }

  public function deleteCertificateAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->certificate_id = $certificate_id = $this->_getParam('certificate_id');
    $certificate = Engine_Api::_()->getItem('eresume_certificate', $certificate_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteCertificate();

    $db = $certificate->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $certificate->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your certificate is successfully deleted.'))));
  }
  
  public function objectivesAction() {
    
    if( !$this->_helper->requireUser()->isValid())
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['owner_id'] = $viewer->getIdentity();
    
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('objectives', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
    $getDetailId = Engine_Api::_()->getDbTable('details', 'eresume')->getDetailId($resume_id, $viewer->getIdentity());

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_Objective();

    
    if(!empty($getDetailId)) {
      $this->view->details = $detail = Engine_Api::_()->getItem('eresume_detail', $getDetailId);
      $form->populate($detail->toArray());
    }

    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_resume'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }
    
    if(!$getDetailId) {
      // Process
      $table = Engine_Api::_()->getDbTable('details', 'eresume');
      $db = $table->getAdapter();
      $db->beginTransaction();
      try {
          $viewer = Engine_Api::_()->user()->getViewer();
          $formValues = $form->getValues();

          $values = array_merge($formValues, array(
              'owner_id' => $viewer->getIdentity(),
              'resume_id' => $resume_id,
          ));
          
        if($values['date']) {
          $date = explode('/',$values['date']);
          $date = $date[2].'-'.$date['1'].'-'.$date[0];
          $values['date'] = date('Y-m-d', strtotime($date));
        }
        else 
          $values['date'] = null;  

          $resume = $table->createRow();
          $resume->setFromArray($values);
          $resume->save();
          
          $db->commit();
      } catch( Exception $e ) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
      }
    } else {
      // Process
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $values = $form->getValues();
        if($values['date']) {
          $date = explode('/',$values['date']);
          $date = $date[2].'-'.$date['1'].'-'.$date[0];
          $values['date'] = date('Y-m-d', strtotime($date));
        }
        else 
          $values['date'] = null;  
          
        $values['resume_id'] = $resume_id;
        $values['owner_id'] = $viewer->getIdentity();
        $detail->setFromArray($values);
        $detail->save();
        $db->commit();
      }
      catch( Exception $e ) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
      }
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your changes is successfully saved.'))));
  }


  public function achievementsAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('curricular', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $this->view->curric_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'curric_count');
    
    $achievements = $paginator = Engine_Api::_()->getDbtable('achievements', 'eresume')->getAchievements(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'), 1);
    
    $curriculars = $paginator = Engine_Api::_()->getDbTable('curriculars', 'eresume')->getCurriculars(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'), 1);
    
    $result = array();
    $counterLoop = 0;
    foreach($achievements as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Achievement");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['achievements'][$counterLoop] = $resource;
      $result['achievements'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }
    
    $counterLoop = 0;
    foreach($curriculars as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Curricular Activities");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['curriculars'][$counterLoop] = $resource;
      $result['curriculars'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work achievements.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
  }

  
  public function addCurricularAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $curric_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'curric_count');
    $curriculars = Engine_Api::_()->getDbTable('curriculars', 'eresume')->getCurriculars(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    if($curric_count <= engine_count($curriculars)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed curricular activities.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();

    $this->view->form = $form = new Eresume_Form_Resume_AddCurricular();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_curricular'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $curricularsTable = Engine_Api::_()->getDbtable('curriculars', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $userCurricularRow = $curricularsTable->createRow();
      $userCurricularRow->setFromArray(array('curricularname'=>$_POST['curricularname'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id']));
      $userCurricularRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    } 
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your curricular is successfully added.'))));
  }

  public function deleteCurricularAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->curricular_id = $curricular_id = $this->_getParam('curricular_id');
    $curricular = Engine_Api::_()->getItem('eresume_curricular', $curricular_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteCurricular();

    $db = $curricular->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $curricular->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your curricular is successfully added.'))));
  }
  
  public function getCurricularAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  public function addAchievementAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $curric_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'curric_count');
    $achievements = Engine_Api::_()->getDbtable('achievements', 'eresume')->getAchievements(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));

    if($curric_count <= engine_count($achievements)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed achievements.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();

    $this->view->form = $form = new Eresume_Form_Resume_AddAchievement();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_achievement'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $achievementsTable = Engine_Api::_()->getDbtable('achievements', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
    
      $userAchievementRow = $achievementsTable->createRow();
      $userAchievementRow->setFromArray(array('achievementname'=>$_POST['achievementname'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id']));
      $userAchievementRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your achievement is successfully added.'))));
  }

  public function deleteAchievementAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->achievement_id = $achievement_id = $this->_getParam('achievement_id');
    $achievement = Engine_Api::_()->getItem('eresume_achievement', $achievement_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteAchievement();

    $db = $achievement->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $achievement->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your achievement is successfully deleted.'))));
  }
  
  public function getAchievementAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  
  public function referenceAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
  
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('references', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $refer_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'refer_count');

    $referenceEntries = $paginator = Engine_Api::_()->getDbTable('references', 'eresume')->getAllReferences($resume_id, $viewer->getIdentity(), 1);
    
    
    $result = array();
    $counterLoop = 0;
    foreach($referenceEntries as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {

        $menuoptions= array();
        $counter = 0;
/*
        $menuoptions[$counter]['name'] = "add";
        $menuoptions[$counter]['label'] = $this->view->translate("Add Reference");
        $counter++;*/
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['references'][$counterLoop] = $resource;
      $result['references'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work references.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
  }
  
  // public function validate_mobile($mobile)
  // {
  //     return preg_match('/^[6-9]\d{9}$/', $mobile);
  // }
  
  function checkemail($str) {
         return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
   }
  
  public function addReferenceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    
    $refer_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'refer_count');
    $referenceEntries = $referenceEntries = Engine_Api::_()->getDbTable('references', 'eresume')->getAllReferences($resume_id, $viewer->getIdentity());
    
    if($refer_count <= engine_count($referenceEntries)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed references.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->form = $form = new Eresume_Form_Resume_AddReference();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_reference'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    // if(!$this->validate_mobile($_POST['mobile_number'])) {
    //   $message = $this->view->translate('You have entered an invalid phone number.');
    //   Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    // }
    
    if(!$this->checkemail($_POST['email_id'])) {
      $message = $this->view->translate('You have entered an invalid email address.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }
    
    // Process
    $table = Engine_Api::_()->getDbTable('references', 'eresume');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
      $values = array_merge($_POST, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity(),
        'resume_id' => $_POST['resume_id'],
      ));
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message' => $e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your reference is successfully added.'))));
  }
  
  public function editReferenceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);

    $this->view->reference_id = $reference_id = $this->_getParam('reference_id');
    $this->view->reference = $reference = Engine_Api::_()->getItem('eresume_reference', $reference_id);

    $this->view->form = $form = new Eresume_Form_Resume_EditReference();
        
    // Populate form
    $form->populate($reference->toArray());
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_reference'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    // if(!$this->validate_mobile($_POST['mobile_number'])) {
    //   $message = $this->view->translate('You have entered an invalid phone number.');
    //   Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    // }
    
    if(!$this->checkemail($_POST['email_id'])) {
      $message = $this->view->translate('You have entered an invalid email address.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }


    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

      $reference->setFromArray($_POST);
      $reference->modified_date = date('Y-m-d H:i:s');
      $reference->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message' => $e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your reference is successfully added.'))));
  }

  public function deleteReferenceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->reference_id = $reference_id = $this->_getParam('reference_id');
    $reference = Engine_Api::_()->getItem('eresume_reference', $reference_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteReference();

    $db = $reference->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $reference->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message' => $e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your reference is successfully deleted.'))));
  }

  public function skillsAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('skills', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $this->view->skill_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'skill_count');
    
    $skills = Engine_Api::_()->getDbtable('skills', 'eresume')->getSkills(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $interests = Engine_Api::_()->getDbtable('interests', 'eresume')->getInterests(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $strengths = Engine_Api::_()->getDbtable('strengths', 'eresume')->getStrengths(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    $hobbies = Engine_Api::_()->getDbtable('hobbies', 'eresume')->getHobbies(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    

    $result = array();
    $counterLoop = 0;
    foreach($skills as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Skills");
//         $counter++;
//         
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['skills'][$counterLoop] = $resource;
      $result['skills'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }
    
    $counterLoop = 0;
    foreach($interests as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Interests");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['interests'][$counterLoop] = $resource;
      $result['interests'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }
    
    $counterLoop = 0;
    foreach($strengths as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Strengths");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['strengths'][$counterLoop] = $resource;
      $result['strengths'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }
    
    $counterLoop = 0;
    foreach($hobbies as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Hobbies");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['hobbies'][$counterLoop] = $resource;
      $result['hobbies'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

//     $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
//     $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
//     $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
//     $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result));
    }
  }

  
  public function addHobbieAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $skill_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'skill_count');
    $hobbies = Engine_Api::_()->getDbtable('hobbies', 'eresume')->getHobbies(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    if($skill_count <= engine_count($hobbies)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed hobbies.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();
    
    $this->view->form = $form = new Eresume_Form_Resume_AddHobbie();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_hobbie'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $hobbiesTable = Engine_Api::_()->getDbtable('hobbies', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $userHobbieRow = $hobbiesTable->createRow();
      $userHobbieRow->setFromArray(array('hobbiename'=>$_POST['hobbiename'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id']));
      $userHobbieRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your hobbie is successfully added.'))));
  }

  public function deleteHobbieAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->hobbie_id = $hobbie_id = $this->_getParam('hobbie_id');
    $hobbie = Engine_Api::_()->getItem('eresume_hobbie', $hobbie_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteHobbie();

    $db = $hobbie->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $hobbie->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your hobbie is successfully deleted.'))));
  }
  
  public function getHobbieAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  
  public function addStrengthAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $skill_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'skill_count');
    $strengths = Engine_Api::_()->getDbtable('strengths', 'eresume')->getStrengths(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    if($skill_count <= engine_count($strengths)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed strengths.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();

    $this->view->form = $form = new Eresume_Form_Resume_AddStrength();


    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_strength'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $strengthsTable = Engine_Api::_()->getDbtable('strengths', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $userStrengthRow = $strengthsTable->createRow();
      $userStrengthRow->setFromArray(array('strengthname'=>$_POST['strengthname'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id']));
      $userStrengthRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your strength is successfully added.'))));
  }

  public function deleteStrengthAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->strength_id = $strength_id = $this->_getParam('strength_id');
    $strength = Engine_Api::_()->getItem('eresume_strength', $strength_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteStrength();

    $db = $strength->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $strength->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your certificate is successfully added.'))));
  }
  
  public function getStrengthAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  
  public function addInterestAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $skill_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'skill_count');
    $interests = Engine_Api::_()->getDbtable('interests', 'eresume')->getInterests(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));

    if($skill_count <= engine_count($interests)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed interests.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();

    $this->view->form = $form = new Eresume_Form_Resume_AddInterest();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_interest'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $interestsTable = Engine_Api::_()->getDbtable('interests', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $userInterestRow = $interestsTable->createRow();
      $userInterestRow->setFromArray(array('interestname'=>$_POST['interestname'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id']));
      $userInterestRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your interest is successfully added.'))));
  }

  public function deleteInterestAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->interest_id = $interest_id = $this->_getParam('interest_id');
    $interest = Engine_Api::_()->getItem('eresume_interest', $interest_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteInterest();

    $db = $interest->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $interest->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your interest is successfully deleted.'))));
  }
  
  public function getInterestAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  public function editSkillAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->skill_id = $skill_id = $this->_getParam('skill_id');
    $this->view->skill = $skill = Engine_Api::_()->getItem('eresume_skill', $skill_id);


    $this->view->form = $form = new Eresume_Form_Resume_EditSkill();
    // Populate form
    $form->populate($skill->toArray());
    $form->rate_value->setValue($skill->rating);
    
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_skill'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
    
      $skill->setFromArray($_POST);
      $skill->rating = $_POST['rate_value'];
      $skill->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your skill is successfully edited.'))));
  }
  
  public function addSkillAction() {

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $skill_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'skill_count');
    
    $skills = Engine_Api::_()->getDbtable('skills', 'eresume')->getSkills(array('resume_id' => $resume_id, 'user_id' => $viewer_id, 'column_name' => '*'));
    
    if($skill_count <= engine_count($skills)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed skills.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->user_id = $user_id = $viewer->getIdentity();

    $this->view->form = $form = new Eresume_Form_Resume_AddSkill();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_skill'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    $skillsTable = Engine_Api::_()->getDbtable('skills', 'eresume');

    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $userSkillRow = $skillsTable->createRow();
      $userSkillRow->setFromArray(array('skillname'=>$_POST['skillname'],'user_id'=>$user_id, 'resume_id' => $_POST['resume_id'], 'rating' => $_POST['rate_value']));
      $userSkillRow->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your skill is successfully added.'))));
  }

  public function deleteSkillAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->skill_id = $skill_id = $this->_getParam('skill_id');
    $skill = Engine_Api::_()->getItem('eresume_skill', $skill_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteSkill();

    $db = $skill->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $skill->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your skill is successfully deleted.'))));
  }
  
  public function getSkillAction() {

    $sesdata = array();
    $sesdata[] = array(
      'id' => 0,
      'label' => 'Add New'
    );
    return $this->_helper->json($sesdata);
  }
  
  public function projectAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('project', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $project_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'project_count');
    
    $projectEntries = $paginator = Engine_Api::_()->getDbTable('projects', 'eresume')->getAllProjects($resume_id, $viewer->getIdentity(), 1);
    
    $result = array();
    $counterLoop = 0;
    foreach($projectEntries as $item) {

      $resource = $item->toArray();
      
      
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Project");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
        
        if($item->photo_id) {
          $menuoptions[$counter]['name'] = "download";
          $menuoptions[$counter]['label'] = $this->view->translate("Download");
          
          $storageTable = Engine_Api::_()->getDbTable('files', 'storage');
          $select = $storageTable->select()->from($storageTable->info('name'), array('file_id', 'storage_path', 'name'))->where('file_id = ?', $item->photo_id);
          $storageData = $storageTable->fetchRow($select);
          
          $storage = Engine_Api::_()->getItem('storage_file', $storageData->file_id);
          $basePath = $storage->map();
    
          $img_path = Engine_Api::_()->storage()->get($item->photo_id, '');
          if($img_path) {
            $img_path = $img_path->getPhotoUrl();
            
            $menuoptions[$counter]['image_url'] = $this->getBaseUrl(true, $basePath);
            $counter++;
          }
        }
      }
      $result['projects'][$counterLoop] = $resource;
      $result['projects'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work projects.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
  }
  
  public function addProjectAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $project_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'project_count');
    $projectEntries = $projectEntries = Engine_Api::_()->getDbTable('projects', 'eresume')->getAllProjects($resume_id, $viewer->getIdentity());

    if($project_count <= engine_count($projectEntries)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed projects.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->form = $form = new Eresume_Form_Resume_AddProject();

    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_project'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Year for End Date should be greater the Start Date.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    
    if (!preg_match('#^http(s)?://#', $_POST['project_url'])) {
      $message = $this->view->translate('Please add the http or https with the project URL.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    
    
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }

    // Process
    $table = Engine_Api::_()->getDbTable('projects', 'eresume');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $values = array_merge($_POST, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity()
      ));
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      
      if (isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['photo_id'], array(
            'parent_id' => $row->project_id,
            'parent_type' => 'eresume_project',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $row->photo_id = $filename->file_id;
        $row->save();
      }
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your project is successfully added.'))));
  }

  public function editProjectAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->project_id = $project_id = $this->_getParam('project_id');
    $this->view->project = $project = Engine_Api::_()->getItem('eresume_project', $project_id);

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_EditProject();
    // Populate form
    $form->populate($project->toArray());
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_project'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Year for End Date should be greater the Start Date.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    if (!preg_match('#^http(s)?://#', $_POST['project_url'])) {
      $message = $this->view->translate('Please add the http or https with the project URL.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }


    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {

      $project->setFromArray($_POST);
      $project->modified_date = date('Y-m-d H:i:s');
      $project->save();
      if (isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $filename = $storage->createFile($_FILES['photo_id'], array(
            'parent_id' => $project->project_id,
            'parent_type' => 'eresume_project',
            'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
        ));
        $project->photo_id = $filename->file_id;
        $project->save();
      }
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your project is successfully edited.'))));
  }

  public function deleteProjectAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->project_id = $project_id = $this->_getParam('project_id');
    $project = Engine_Api::_()->getItem('eresume_project', $project_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteProject();


    $db = $project->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $project->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your project is successfully deleted.'))));
  }
  
  public function workexperienceAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    
    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('experience', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $exper_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'exper_count');
    $experienceEntries = $paginator = Engine_Api::_()->getDbTable('experiences', 'eresume')->getAllExperiences($resume_id, $this->view->viewer_id, 1);
    
    $result = array();
    $counterLoop = 0;
    foreach($experienceEntries as $item) {

      $resource = $item->toArray();
      
      
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Experience");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['experiences'][$counterLoop] = $resource;
      $result['experiences'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work experiences.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
    
  }
  
  public function educationAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $allowprofile = json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile'));
    if(!engine_in_array('education', $allowprofile))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    
    $edution_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'edution_count');
    
    $educationEntries = $paginator = Engine_Api::_()->getDbTable('educations', 'eresume')->getAllEducations($resume_id, $viewer->getIdentity(), 1);
    
    
    $result = array();
    $counterLoop = 0;
    foreach($educationEntries as $item) {

      $resource = $item->toArray();
      if(!empty($this->view->viewer_id)) {
        $menuoptions= array();
        $counter = 0;

//         $menuoptions[$counter]['name'] = "add";
//         $menuoptions[$counter]['label'] = $this->view->translate("Add Education");
//         $counter++;
        
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
        
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
        $counter++;
      }
      $result['educations'][$counterLoop] = $resource;
      $result['educations'][$counterLoop]['menus'] = $menuoptions;
      $counterLoop++;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if(engine_count($result) <= 0) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('Does not exist work educations.'), 'result' => array()));
    } else {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
  }
  
  public function addEducationAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    
    $edution_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'edution_count');
    $educationEntries = $educationEntries = Engine_Api::_()->getDbTable('educations', 'eresume')->getAllEducations($resume_id, $viewer->getIdentity());
    

    if($edution_count <= engine_count($educationEntries)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed education.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    $this->view->form = $form = new Eresume_Form_Resume_AddEducation();

    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_education'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Please select year greater than From year.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }
    
    // Process
    $table = Engine_Api::_()->getDbTable('educations', 'eresume');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $values = array_merge($_POST, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity(),
        'resume_id' => $_POST['resume_id'],
      ));
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your education is successfully added.'))));
  }

  public function editEducationAction() {
    
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $education_id = $this->_getParam('education_id');
    $education = Engine_Api::_()->getItem('eresume_education', $education_id);

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_EditEducation();
    // Populate form
    $form->populate($education->toArray());

    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_education'));
    }
    // Check post/form
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Please select year greater than From year.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $education->setFromArray($_POST);
      $education->modified_date = date('Y-m-d H:i:s');
      $education->save();
      $db->commit();
    }
    catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your education is successfully edited.'))));
  }

  public function deleteEducationAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();
    $education_id = $this->_getParam('education_id');
    $education = Engine_Api::_()->getItem('eresume_education', $education_id);

    $this->view->form = $form = new Eresume_Form_Resume_DeleteEducation();

    $db = $education->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $education->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('message' => $this->view->translate('Your education is successfully deleted.'))));
  }

  
  
  public function addExperienceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);
    
    $exper_count = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'exper_count');
    $experienceEntries = Engine_Api::_()->getDbTable('experiences', 'eresume')->getAllExperiences($resume_id, $this->view->viewer_id);
    
    if($exper_count <= engine_count($experienceEntries)) {
      // return error message
      $message = $this->view->translate('You have already uploaded the maximum number of allowed work experience.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_AddExperience();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_experience'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Please select year greater than From year.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    // Process
    $table = Engine_Api::_()->getDbTable('experiences', 'eresume');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
      $values = array_merge($_POST, array(
        'owner_type' => $viewer->getType(),
        'owner_id' => $viewer->getIdentity(),
        'resume_id' => $_POST['resume_id'],
      ));
      $row = $table->createRow();
      $row->setFromArray($values);
      $row->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your experience is successfully added.'))));
  }
  
  public function editExperienceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();
    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', 0);

    $this->view->experience_id = $experience_id = $this->_getParam('experience_id');
    $this->view->experience = $experience = Engine_Api::_()->getItem('eresume_experience', $experience_id);

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_EditExperience();
    // Populate form
    $form->populate($experience->toArray());

    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_experience'));
    }
    // Check post/form
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    if(empty($_POST['currentlywork']) && $_POST['fromyear'] && $_POST['toyear'] < $_POST['fromyear']) {
      $message = $this->view->translate('Please select year greater than From year.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    // Process
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->beginTransaction();
    try {
      $experience->setFromArray($_POST);
      $experience->modified_date = date('Y-m-d H:i:s');
      $experience->save();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your education is successfully edited.'))));
  }

  public function deleteExperienceAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->experience_id = $experience_id = $this->_getParam('experience_id');
    $experience = Engine_Api::_()->getItem('eresume_experience', $experience_id);
    
    $this->view->form = $form = new Eresume_Form_Resume_DeleteExperience();

    $db = $experience->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $experience->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    $message = Zend_Registry::get('Zend_Translate')->_('Your education is successfully deleted.');
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $message));

  }

  public function contactInformationAction() {
    
    if( !$this->_helper->requireUser()->isValid())
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $this->view->resume_id = $resume_id = $this->_getParam('resume_id', null);
    if(empty($resume_id))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
      
    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['owner_id'] = $viewer->getIdentity();
    
    $getDetailId = Engine_Api::_()->getDbTable('details', 'eresume')->getDetailId($resume_id, $viewer->getIdentity());

    // Prepare form
    $this->view->form = $form = new Eresume_Form_Resume_ContactInformation();
    
    if(!empty($getDetailId)) {
      $this->view->details = $detail = Engine_Api::_()->getItem('eresume_detail', $getDetailId);
      $form->populate($detail->toArray());
    }
    
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_resume'));
    }

    // Check post/form
    if( !$this->getRequest()->isPost() ) {
      return;
    }
    
    // if(!$this->validate_mobile($_POST['mobile_number'])) {
    //   $message = $this->view->translate('You have entered an invalid phone number.');
    //   Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    // }
    
    if(!$this->checkemail($_POST['email'])) {
      $message = $this->view->translate('You have entered an invalid email address.');
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
    }
    
    //if($this->_getParam('validateFieldsForm')) {
      $values = $this->getRequest()->getPost();
      
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      foreach($formFields as $key => $value){
      
        if($value['type'] == "Date"){
          $date = $values[$value['name']];
          if(!empty($date) && !is_null($date)){
            $values[$value['name']] = array();
            $values[$value['name']]['month'] = date('m',strtotime($date));
            $values[$value['name']]['year'] = date('Y',strtotime($date));
            $values[$value['name']]['day'] = date('d',strtotime($date));
          }
        }
      }
      
      if( !$form->isValid($values) ) {
        $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
        if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
      }
    //}
    
    if(!$getDetailId) {
      // Process
      $table = Engine_Api::_()->getDbTable('details', 'eresume');
      $db = $table->getAdapter();
      $db->beginTransaction();

      try {
          // Create blog
          $viewer = Engine_Api::_()->user()->getViewer();
          $formValues = $form->getValues();

          $values = array_merge($formValues, array(
              'owner_id' => $viewer->getIdentity(),
              'resume_id' => $resume_id,
          ));
          $values['birthday_date'] = $_POST['birthday_date'];
          if($values['birthday_date']) {
            $birthday_date = explode('/',$values['birthday_date']);
            $birthday_date = $birthday_date[2].'-'.$birthday_date['1'].'-'.$birthday_date[0];
            $values['birthday_date'] = date('Y-m-d', strtotime($birthday_date));
          }
          $resume = $table->createRow();
          $resume->setFromArray($values);
          $resume->save();
          
          if (isset($_FILES['main_preview']['name']) && $_FILES['main_preview']['name'] != '') {
            $storage = Engine_Api::_()->getItemTable('storage_file');
            $filename = $storage->createFile($form->main_preview, array(
                'parent_id' => $resume->detail_id,
                'parent_type' => 'eresume_detail',
                'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            ));
            $detail->photo_id = $filename->file_id;
            $detail->save();
          }
          
          if (isset($_FILES['signature_preview']['name']) && $_FILES['signature_preview']['name'] != '') {
            $storage = Engine_Api::_()->getItemTable('storage_file');
            $filename = $storage->createFile($form->signature_preview, array(
                'parent_id' => $resume->detail_id,
                'parent_type' => 'eresume_detail',
                'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
            ));
            $detail->sign_id = $filename->file_id;
            $detail->save();
          }
          
          $db->commit();
      } catch( Exception $e ) {
          Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
      }
    } else {
      // Process
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        $values = $form->getValues();
        
        if(empty($values['photo_id'])) {
          unset($values['photo_id']);
        }
        if(empty($values['sign_id'])) {
          unset($values['sign_id']);
        }
        $values['birthday_date'] = $_POST['birthday_date'];
        $values['resume_id'] = $resume_id;
        $values['owner_id'] = $viewer->getIdentity();
        if($values['birthday_date']) {
          $birthday_date = explode('/',$values['birthday_date']);
          $birthday_date = $birthday_date[2].'-'.$birthday_date['1'].'-'.$birthday_date[0];
          $values['birthday_date'] = date('Y-m-d', strtotime($birthday_date));
        }
        $detail->setFromArray($values);
        $detail->save();
        
        if (isset($values['remove_main_photo']) && !empty($values['remove_main_photo'])) {
          $storage = Engine_Api::_()->getItem('storage_file', $detail->photo_id);
          $detail->photo_id = 0;
          $detail->save();
          if ($storage)
            $storage->delete();
        }

        if (isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
          $storage = Engine_Api::_()->getItemTable('storage_file');
          $filename = $storage->createFile($form->photo_id, array(
              'parent_id' => $detail->detail_id,
              'parent_type' => 'eresume_detail',
              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
          ));
          $detail->photo_id = $filename->file_id;
          $detail->save();
        }
        
        
        if (isset($values['remove_signature_photo']) && !empty($values['remove_signature_photo'])) {
          $storage = Engine_Api::_()->getItem('storage_file', $detail->sign_id);
          $detail->sign_id = 0;
          $detail->save();
          if ($storage)
            $storage->delete();
        }

        if (isset($_FILES['sign_id']['name']) && $_FILES['sign_id']['name'] != '') {
          $storage = Engine_Api::_()->getItemTable('storage_file');
          $filename = $storage->createFile($form->sign_id, array(
              'parent_id' => $detail->detail_id,
              'parent_type' => 'eresume_detail',
              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
          ));
          $detail->sign_id = $filename->file_id;
          $detail->save();
        }
        $db->commit();
      }
      catch( Exception $e ) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
      }
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Changes Successfully Saved.'))));
    //return $this->_helper->redirector->gotoRoute(array('action' => 'contact-information'));
  }
  
  public function editAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireUser()->isValid())
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $resume = Engine_Api::_()->getItem('eresume_resume', $this->_getParam('resume_id', null));

    $this->view->form = $form = new Eresume_Form_Edit();
    // Populate form
    $form->populate($resume->toArray());

    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_resume'));
    }
    // Check post/form
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    // Process
    $table = Engine_Api::_()->getItemTable('eresume_resume');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
        $values = $form->getValues();
        $resume->setFromArray($values);
        $resume->save();

        // Commit
        $db->commit();
    } catch( Exception $e ) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id,'message' => $this->view->translate('Your resume title has been edited successfully.'))));
  
  }

  public function createAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireUser()->isValid())
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    // In smoothbox
    //$this->_helper->layout->setLayout('default-simple');

    $this->view->form = $form = new Eresume_Form_Create();
    
    // Check if post and populate
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'eresume_resume'));
    }

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
    }
    
    $max_resume = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'max');

    $table = Engine_Api::_()->getItemTable('eresume_resume');
    $tableName = $table->info('name');
    
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    
    $select = $table->select()
                    ->from($tableName)
                    ->where('owner_id =?', $viewer_id);
    $results = $table->fetchAll($select);
    
    if ($max_resume != 0 && $max_resume >= engine_count($results)) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'You have already uploaded the maximum number of allowed resumes. If you would like to upload a new resume, please delete an old one first.', 'result' => array()));
    }


    // Process
    $table = Engine_Api::_()->getItemTable('eresume_resume');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
        $viewer = Engine_Api::_()->user()->getViewer();
        $values = $form->getValues();
        $values['owner_id'] = $viewer->getIdentity();
        $resume = $table->createRow();
        $resume->setFromArray($values);
        $resume->save();

        // Commit
        $db->commit();
    } catch( Exception $e ) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('resume_id' => $resume_id, 'message'=>$this->view->translate('Your resume entry has been created.'))));
  
  }
  
  public function deleteAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();
    $resume = Engine_Api::_()->getItem('eresume_resume', $this->getRequest()->getParam('resume_id'));

    $this->view->form = $form = new Eresume_Form_Delete();

    $db = $resume->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $resume->delete();
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }

    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $this->view->translate('Resume Deleted Successfully.')));
  }
  
  public function dashboardAction() {
    
    $resume_id = $this->_getParam('resume_id', null);
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $allowprofile = Engine_Api::_()->authorization()->getPermission($viewer, 'eresume_resume', 'allowprofile');
    $allowprofile = json_decode($allowprofile);
  
    $dashboardoptions= array();
    $counter = 0;
    
    $dashboardoptions[$counter]['name'] = "personal_information";
    $dashboardoptions[$counter]['resume_id'] = $resume_id;
    $dashboardoptions[$counter]['action'] = 'contact-information';
    $dashboardoptions[$counter]['label'] = $this->view->translate("Personal Information");
    $counter++;
    
    if(engine_in_array('experience', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "experience";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'workexperience';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Work Experiences");
      $counter++;
    }
    
    if(engine_in_array('education', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "education";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'education';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Educations");
      $counter++;
    }
    
    if(engine_in_array('project', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "project";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'project';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Projects");
      $counter++;
    }
    
    if(engine_in_array('certificate', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "certificate";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'certificate';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Certificates");
      $counter++;
    }
  
    if(engine_in_array('skills', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "skills";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'skills';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Skills, Interests, Strengths & Hobbies");
      $counter++;
    }
    
    if(engine_in_array('references', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "references";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'reference';
      $dashboardoptions[$counter]['label'] = $this->view->translate("References");
      $counter++;
    }
    
    if(engine_in_array('curricular', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "curricular";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'achievements';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Achievements & Co-Curricular");
      $counter++;
    }
    
    if(engine_in_array('objectives', $allowprofile)) {
      $dashboardoptions[$counter]['name'] = "objectives";
      $dashboardoptions[$counter]['resume_id'] = $resume_id;
      $dashboardoptions[$counter]['action'] = 'objectives';
      $dashboardoptions[$counter]['label'] = $this->view->translate("Career Objectives, Date & Place & Declaration");
      $counter++;
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message' => $this->view->translate(''),'dashboardoptions'=>$dashboardoptions)));

  }
}
