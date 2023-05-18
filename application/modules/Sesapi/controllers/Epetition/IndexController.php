<?php

 /**
 * socialnetworking.solutions
 *

 * @category   Application_Modules
 * @package    Sesapi

 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

 class Epetition_IndexController extends Sesapi_Controller_Action_Standard
 {
 	public function init() 
 	{

 	}
 	public function browseAction() { 
 		$search_for = $this-> _getParam('search_for', 'petition');
 		$isPetitionName = isset($_POST['name']) ? $_POST['name'] : '';
 		$isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
 		$isCity = isset($_POST['city']) ? $_POST['city'] : '';
 		$isCountry = isset($_POST['country']) ? $_POST['country'] : '';
 		$isLocation = isset($_POST['location']) ? $_POST['location'] : '';
 		$isZipcode = isset($_POST['zincode']) ? $_POST['zincode'] : '';
 		$isState = isset($_POST['state']) ? $_POST['state'] : '';
 		$isPhoto = isset($_POST['photo']) ? $_POST['photo'] : '';
 		$isShow = isset($_POST['show']) ? $_POST['show'] : '';
 		if( Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)){
 			$location = 'yes';
 		}else
 		$location = 'no';
 		$form = new Epetition_Form_Search(array('searchTitle' => 'yes','browseBy' => 'yes','epetitionsSearch' => 'yes','searchFor'=>'petition','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => $location,'kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 		$form->populate($_POST);
 		$params = $form->getValues();
 		if(!empty($isPetitionName))
 			$params['text'] = $isPetitionName;
 		if(!empty($isCategory))
 			$params['category_id'] = $isCategory;
 		if(!empty($isCity))
 			$params['city'] = $isCity;
 		if(!empty($isCountry))
 			$params['country'] = $isCountry;
 		if(!empty($isLocation))
 			$params['location'] = $isLocation;
 		if(!empty($isZincode))
 			$params['zip'] = $isZincode;
 		if(!empty($isState))
 			$params['state'] = $isState;
 		if(!empty(['isShow']))
 			$params['show'] = $isShow;

 		$params['has_photo'] = $isPhoto;



 	/*	$manage = $this->_getParam('type','');
 	$form->populate($_POST);*/
 		//$params = $form->getValues();

 	/*	if($manage == "manage"){
 			if($this->view->viewer()->getIdentity()){
 				$params['owner_id'] = $this->view->viewer()->getIdentity();
 				$params["manage"] = "managePetition";
 			}
 			else
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'invalid_request','result'=>''));
 			} */
 			if (!empty($_POST['owner_id']))
 				$value["owner_id"] = $_POST['owner_id'];
 			if (isset($params['sort']) && $params['sort'] != '') {
 				$params['getParamSort'] = str_replace('SP', '_', $params['sort']);
 			} else
 			$params['getParamSort'] = 'creation_date';

 			if (isset($params['getParamSort'])) {
 				switch ($params['getParamSort']) {
 					case 'most_viewed':
 					$params['popularCol'] = 'view_count';
 					break;
 					case 'most_liked':
 					$params['popularCol'] = 'like_count';
 					break;
 					case 'most_commented':
 					$params['popularCol'] = 'comment_count';
 					break;
 					case 'most_favourite':
 					if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epetition.allowfavv', 1)) {
 						$params['popularCol'] = 'favourite_count';
 					}
 					break;
 					case 'verified':
 					$params['popularCol'] = 'verified';
 					$params['fixedData'] = 'verified';
 					break;
 					case 'sponsored':
 					$params['popularCol'] = 'sponsored';
 					$params['fixedData'] = 'sponsored';
 					break;
 					case 'featured':
 					$params['popularCol'] = 'featured';
 					$params['fixedData'] = 'featured';
 					break;
 					case 'most_rated':
 					$params['popularCol'] = 'rating';
 					break;
 					case 'recently_created':
 					default:
 					$params['popularCol'] = 'creation_date';
 					break;
 				}
 			}


 			$paginator = Engine_Api::_()->getDbtable('epetitions', 'epetition')->getEpetitionsPaginator($params);
 			$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			$result = $this->getPetitions($paginator,$manage);
 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 			if ($result <= 0)
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist petitions.'), 'result' => array()));
 			else
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 		}

 		public function getPetitions($paginator,$manage = "") { 
 			
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
 			$counter = 0;
 			$result = array();
 			foreach($paginator as $item){
 				$result['petitions'][$counter] = $item->toArray();
 				$owner = $item->getOwner();
 				$result['petitions'][$counter]['user_image'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
 				$result['petitions'][$counter]['petition_image']= $this->getBaseUrl(true, $item->getPhotoUrl());

 				if ($petitions->category_id) {
 					$category = Engine_Api::_()->getItem('epetition_category', $petitions->category_id);
 					if ($category) {
 						$result[$counter]['category_title'] = $category->category_name;
 						if ($petitions->subcat_id) {
 							$subcat = Engine_Api::_()->getItem('epetition_category', $petitions->subcat_id);
 							if ($subcat) {
 								$result[$counter]['subcategory_title'] = $subcat->category_name;
 								if ($petitions->subsubcat_id) {
 									$subsubcat = Engine_Api::_()->getItem('epetition_category', $petitions->subsubcat_id);
 									if ($subsubcat) {
 										$result[$counter]['subsubcategory_title'] = $subsubcat->category_name;
 									}
 								}
 							}
 						}
 					}
 				}


 				if($manage){ 
 					$menuoptions= array();
 					$counterMenu = 0;
 					if($item->authorization()->isAllowed($viewer, 'edit')) { 
 						$menuoptions[$counterMenu]['name'] = "edit";
 						$menuoptions[$counterMenu]['label'] = $this->view->translate("Edit");
 						$counterMenu++;
 					}
 					if($item->authorization()->isAllowed($viewer, 'delete')) {
 						$menuoptions[$counterMenu]['name'] = "delete";
 						$menuoptions[$counterMenu]['label'] = $this->view->translate("Delete");
 						$counterMenu++;
 					}

 					$result['petitions']['menus'] = $menuoptions;

 				}

 				$counter++;


 			}
 			return $result;
 		}
 		public function managePetitionAction(){
 			$owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 			$params['user_id'] = $owner_id;

 			$paginator = Engine_Api::_()->getDbtable('epetitions', 'epetition')->getEpetitionsPaginator($params);
 			$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			$result = $this->getpetitions($paginator);
 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 			if ($result <= 0)
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist petitions.'), 'result' => array()));
 			else
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));

 		}
 		public function filtersearchPetitionAction() {
 			$form = new Epetition_Form_Search(array('searchTitle' => 'yes','browseBy' => 'yes','epetitionsSearch' => 'yes','searchFor'=>'petition','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => 'yes','kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 			if ($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields, array('resources_type' => 'epetition'));
 			} else {
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
 			}
 		}
 		public function searchPetitionAction(){
 			$isSearch = isset($_POST['search']) ? $_POST['search'] : '';
 			$isPetitionName = $this->_getParam('name');
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$form = new Epetition_Form_Search(array('defaultProfileId' => 1));
 			$params = $form->getValues();
 			if(!empty($isPetitionName))
 				$params['text'] = $isPetitionName;

 			$paginator = Engine_Api::_()->getDbtable('epetitions', 'epetition')->getEpetitionsPaginator($params);
 			$paginator->setItemCountPerPage($this->_getParam('limit', 5));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			$classroom = $this->_getParam('page', 1);
 			$result = $this->getPetitions($paginator);

 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
 		}

 		public function menuAction(){
 			$menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('epetition_main', array());
 			$menu_counter = 0;
 			foreach ($menus as $menu) {
 				$class = end(explode(' ', $menu->class));
 				$result_menu[$menu_counter]['label'] = $this->view->translate($menu->label);
 				$result_menu[$menu_counter]['action'] = $class;
 				$result_menu[$menu_counter]['isActive'] = $menu->active;
 				$menu_counter++;
 			}
 			$result['menus'] = $result_menu;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result)));
 		}
 		public function createAction() { 
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$epetitionId = $this->_getParam('epetition_id', 0);
 			$signatures = Engine_Api::_()->getItem('epetition_signature', $epetitionId);

 			$form = new Epetition_Form_Signaturecreate();
 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}   
 			if(!$form->isValid($this->getRequest()->getPost()) ) { 
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
 				$signatureTable = Engine_Api::_()->getDbtable('signatures', 'epetition');
 				$db = $signatureTable->getAdapter();
 				$db->beginTransaction();
 				try { 
 					$formValues = $form->getValues();
 					if ($epetitionId) { 
 						$signatures = $signatureTable->createRow();
 						$formValues['location'] =$formValues['epetition_location'];
 						$formValues['support_statement'] =$formValues['epetition_support_statement'];
 						$formValues['support_reason'] =$formValues['epetition_support_reason'];
 						$formValues['module_name'] = 'epetition';
 						$formValues['epetition_id'] = $epetitionId;
 						$formValues['owner_id'] = $viewerId; 
 						$signatures->setFromArray($formValues);
 						
 					} else{
 						$signatures->location = $formValues['epetition_location'];
 						$signatures->support_statement = $formValues['epetition_support_statement'];
 						$signatures->support_reason = $formValues['epetition_support_reason'];
 					}
 					$signatures->save();
 					$db->commit();
 					Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('epetition_id' => $signatures->getIdentity(), 'message' => $this->view->translate('You have successfully created .'))));
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 			}
 		}

 		public function signatureDeleteAction()  { 
 			$signature_id = $this->_getParam('signature_id');
 			if (!$this->getRequest()->isPost()) {
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'method is not post'));
 			}
 			$signatures = Engine_Api::_()->getItem('epetition_signature', $this->_getParam('signature_id'));
 			if(empty($signatures))
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'data not found'));
 			if ($this->getRequest()->isPost()) {
 				$db = $signatures->getTable()->getAdapter();
 				$db->beginTransaction();
 				try {
 					$signatures->delete();
 					$db->commit();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('signature_id' => $signatures->getIdentity(),'message' => $this->view->translate('You have successfully delete the sign.'))));
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 			}
 		}
 		public function signatureEditAction() {
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$signatureId = $this->_getParam('signature_id', 0); 
 			$signatures = Engine_Api::_()->getItem('epetition_signature', $this->_getParam('signature_id'));
 			$form = new Epetition_Form_Editdashboardsignature();
 			if ($signatureId) { 
 				$petition_table = Engine_Api::_()->getDbtable('signatures', 'epetition');
 				$db = $petition_table->getAdapter();
 				$signatures = Engine_Api::_()->getItem('epetition_signature', $this->_getParam('signature_id'));
 				$form->populate($signatures->toArray());

 			}
 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}
 			if( !$form->isValid($this->getRequest()->getPost()) ) {
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			$values = $form->getValues();
 			$petition_table = Engine_Api::_()->getDbtable('signatures', 'epetition');
 			$db = $petition_table->getAdapter();
 			$db->beginTransaction();
 			try {  
 				$signatures->setFromArray($values);
 				$signatures->save();
 				$db->commit();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('epetition_id' => $signatures->getIdentity(),'message' => $this->view->translate('You have successfully save.'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));

 			}
 		}
 		public function createPetitionAction() { 
 			if (!$this->_helper->requireAuth()->setAuthParams('epetition', null, 'create')->isValid())
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
 			$category_id = $this->_getParam('category_id',false);
 			$this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'epetition')->profileFieldId();
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$subjectId = $this->_getParam('epetition_id', 0);   
 			$form = new Epetition_Form_Create(array('defaultProfileId' => $defaultProfileId,'fromApi'=>true));
 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}   
 			if(!$form->isValid($this->getRequest()->getPost()) ) { 
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			
 			$petition_table = Engine_Api::_()->getDbTable('epetitions', 'epetition');
 			$db = $petition_table->getAdapter();
 			$db->beginTransaction();
 			try {
 				$paramss = $form->getValues();
 				$paramss['owner_id'] = $viewer->getIdentity();
 				$petitionTable = Engine_Api::_()->getDbTable('epetitions', 'epetition');
 				$petition = $petitionTable->createRow();
 				$petition->setFromArray($paramss);
 				$petition->save();	
 				$db->commit();	
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('epetition_id' => $petition->getIdentity(), 'message' => $this->view->translate('You have successfully created .'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 			}
 		}

 		public function signatureGoalAction() {
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$epetitionId = $this->_getParam('epetition_id', 0); 
 			$petitions = Engine_Api::_()->getItem('epetition', $epetitionId);
 			$form= new Epetition_Form_Dashboard_Decisionmakergoaledit();
 			if ($epetitionId) { 
 				$petition_table = Engine_Api::_()->getDbtable('epetitions', 'epetition');
 				$db = $petition_table->getAdapter();
 				$petitions = Engine_Api::_()->getItem('epetition', $epetitionId);
 				$form->populate($petitions->toArray());

 			}
 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}
 			if( !$form->isValid($this->getRequest()->getPost()) ) {
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			$values = $form->getValues();
 			$petition_table = Engine_Api::_()->getDbtable('epetitions', 'epetition');
 			$db = $petition_table->getAdapter();
 			$db->beginTransaction();
 			try {  
 				$petitions->setFromArray($values);
 				$petitions->save();
 				$db->commit();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('epetition_id' => $petitions->getIdentity(),'message' => $this->view->translate('You have successfully save.'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));

 			}
 		}
 		public function viewPetitionAction() {
 			$isSearch = isset($_POST['search']) ? $_POST['search'] : '';
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$id = $this->_getParam('epetition_id', null);
 			$petitions = Engine_Api::_()->getItem('epetition', $id); 
 			$result['petitions'] = $petitions->toArray();
 			$result['petitions']['petitions_image'] = $this->getBaseUrl(true, Engine_Api::_()->storage()->get($petitions->photo_id, '')->getPhotoUrl());
 			$result['petitions']['user_image'] = $this->getBaseUrl(true, 
 				!empty($user->photo_id) ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
 			if($this->view->viewer()->getIdentity() != 0){
 				$response["petitions"]['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($petitions);
 				$response["petitions"]['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($petitions);
 				if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epetition.allowfavc', 1)) {
 					$response["petitions"]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($petitions,'favourites','epetition','epetition');
 					$response["petitions"]['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($petitions,'favourites','epetition','epetition');
 				}
 			}
 			if($petitions->isOwner($viewer)){
 				$menuoptions= array();
 				$counterMenu = 0;
 				$canEdit =  Engine_Api::_()->authorization()->isAllowed('epetition', $viewer, 'edit');
 				if($canEdit){
 					$menuoptions[$counterMenu]['name'] = "Dashboard";
 					$menuoptions[$counterMenu]['label'] = $this->view->translate("Dashboard");
 					$counterMenu++;

 					$menuoptions[$counterMenu]['name'] = "Signature Goal";
 					$menuoptions[$counterMenu]['label'] = $this->view->translate("Signature Goal");
 					$counterMenu++;    
 				}
 				$canDelete = Engine_Api::_()->authorization()->isAllowed('epetition', $viewer, 'delete');
 				if($canDelete){
 					$menuoptions[$counterMenu]['name'] = "delete";
 					$menuoptions[$counterMenu]['label'] = $this->view->translate("Delete");
 					$counterMenu++;      
 				}
 			}
 			$menuoptions[$counterMenu]['name'] = "report";
 			$menuoptions[$counterMenu]['label'] = $this->view->translate("Report");
 			$result["petitions"]['menus'] = $menuoptions;
 			$counterMenu++;

 			$menuoptions[$counterMenu]['name'] = "share";
 			$menuoptions[$counterMenu]['label'] = $this->view->translate("Share");
 			$result["petitions"]['menus'] = $menuoptions;
 			$counterMenu++;

 			if($petitions->isOwner($viewer)){
 				$tabs[] = array(
 					'label' => $this->view->translate("Overview") ,
 					'name' => 'overview',
 				);
 				$tabs[] = array(
 					'label' => $this->view->translate("Contact Details"),
 					'name' => 'Contactdetails',
 				);

 			}
 			$tabs[] = array(
 				'label' => $this->view->translate("Letter"),
 				'name' => 'letter',
 			);
 			$tabs[] = array(
 				'label' => $this->view->translate("Decision Maker"),
 				'name' => 'decisionmakers',
 			);
 			$result['petitions']['tabs'] = $tabs;
 			$tabcounter = 0;
 			$result['menus'][$tabcounter]['name'] = 'comment';
 			$result['menus'][$tabcounter]['label'] = $this->view->translate('Comments');
 			$tabcounter++;

 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('estore.allow.share', 1)){
 				$result['options'][$optionCounter]['name'] = 'share';
 				$result['options'][$optionCounter]['label'] = $this->view->translate('Share');
 				$optionCounter++;


 			}

 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
 		}
 		public function contactDetailsAction()
 		{
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$epetitionId = $this->_getParam('epetition_id', 0); 
 			$petitions = Engine_Api::_()->getItem('epetition', $epetitionId);
 			$form = new Epetition_Form_Dashboard_Contactinformation();
 			if ($epetitionId) { 
 				$petition_table = Engine_Api::_()->getDbtable('epetitions', 'epetition');
 				$db = $petition_table->getAdapter();
 				$petitions = Engine_Api::_()->getItem('epetition', $epetitionId);
 				$form->populate($petitions->toArray());

 			}

 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}
 			if( !$form->isValid($this->getRequest()->getPost()) ) {
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			$values = $form->getValues();
 			$petition_table = Engine_Api::_()->getDbtable('epetitions', 'epetition');
 			$db = $petition_table->getAdapter();
 			$db->beginTransaction();
 			try {  
 				$petitions->setFromArray($values);
 				$petitions->save();
 				$db->commit();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('epetition_id' => $petitions->getIdentity(),'message' => $this->view->translate('You have successfully save.'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));

 			}
 		}
 		public function decisionMakerAction()
 		{
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$epetitionId = $this->_getParam('epetition_id', 0); 
 			$decisionmakers = Engine_Api::_()->getItem('epetition_decisionmaker', $epetitionId);
 			$form = new Epetition_Form_Dashboard_Decisionmaker();
 			if($this->_getParam('getForm')) {
 				$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 				$this->generateFormFields($formFields,array('resources_type'=>'epetition'));
 			}
 			if(!$form->isValid($this->getRequest()->getPost()) ) { 
 				$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 				if(is_countable($validateFields) && engine_count($validateFields))
 					$this->validateFormFields($validateFields);
 			}
 			if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
 				$signatureTable = Engine_Api::_()->getDbtable('decisionmakers', 'epetition');
 				$db = $signatureTable->getAdapter();
 				$db->beginTransaction();
 				try { 
 					$formValues = $form->getValues();
 					if ($epetitionId) { 
 						$decisionmakers = $signatureTable->createRow();
 						$formValues['name'] =$formValues['epetition_location'];
 						$formValues['support_statement'] =$formValues['epetition_support_statement'];
 						$formValues['support_reason'] =$formValues['epetition_support_reason'];
 						$formValues['module_name'] = 'epetition';
 						$formValues['epetition_id'] = $epetitionId;
 						$formValues['owner_id'] = $viewerId; 
 						$decisionmakers->setFromArray($formValues);
 					} else{
 						$decisionmakers->name = $formValues['Name'];

 					}
 					$decisionmakers->save();
 					$db->commit();
 					Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('epetition_id' => $decisionmakers->getIdentity(), 'message' => $this->view->translate('You have successfully created .'))));
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 			}
 		}
 		public function petitionDeleteAction() 
 		{
 			$epetition_id = $this->_getParam('epetition_id');
 			if (!$this->getRequest()->isPost()) {
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'method is not post'));
 			}
 			$petitions = Engine_Api::_()->getItem('epetition', $this->_getParam('epetition_id'));

 			if(empty($petitions))
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'data not found'));
 			if ($this->getRequest()->isPost()) {
 				$db = $petitions->getTable()->getAdapter();
 				$db->beginTransaction();
 				try {
 					$petitions->delete();
 					$db->commit();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('petition_id' => $petitions->getIdentity(),'message' => $this->view->translate('You have successfully delete the petition.'))));
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 			}
 		}

 		public function categoriesAction(){
 			$paginator = Engine_Api::_()->getDbTable('epetitions', 'epetition')->getEpetitionsPaginator();
 			$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			if ($paginator->getCurrentPageNumber() == 1) {
 				$categories = Engine_Api::_()->getDbtable('categories', 'epetition')->getCategory(array('column_name' => '*', 'limit' => 25));
 				$menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('epetition_main', array());
 				$category_counter = 0;
 				$menu_counter = 0;
 				foreach ($categories as $category) {
 					if ($category->thumbnail)
 						$result_category[$category_counter]['category_images'] = Engine_Api::_()->sesapi()->getPhotoUrls($category->thumbnail, '', "");
 					if ($category->cat_icon)
 						$result_category[$category_counter]['icon'] = Engine_Api::_()->sesapi()->getPhotoUrls($category->cat_icon, '', "");
 					if ($category->colored_icon)
 						$result_category[$category_counter]['icon_colored'] = Engine_Api::_()->sesapi()->getPhotoUrls($category->colored_icon, '', "");
 					$result_category[$category_counter]['slug'] = $category->slug;
 					$result_category[$category_counter]['category_name'] = $category->category_name;
 				//$result_category[$category_counter]['total'] = $category->total_petitions_categories;
 					$result_category[$category_counter]['category_id'] = $category->category_id;
 					$category_counter++;
 				}
 				$result['category'] = $result_category;
 			}
 			$result['categories'] = $this->getCategory($paginator,$categoryPaginator);
 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 		}


 		public function getCategory($categoryPaginator) {
 			$result = array();
 			$counter = 0;
 			foreach ($categoryPaginator as $categories) {
 				$petition = $categories->toArray();
 				$params['category_id'] = $categories->category_id;
 				$params['limit'] = 5;
 				$paginator = Engine_Api::_()->getDbTable('epetitions', 'epetition')->getEpetitionsPaginator($params);
 				$paginator->setItemCountPerPage(3);
 				$paginator->setCurrentPageNumber(1);
 				if($paginator->getTotalItemCount() > 0){
 					$result[$counter] = $petition;
 					$result[$counter]['items'] = $this->getPetitions($paginator);
 					if ($paginator->getTotalItemCount() > 3) {
 						$result[$counter]['see_all'] = true;
 					} else {
 						$result[$counter]['see_all'] = false;
 					}
 					$counter++;
 				}
 			}
 			$results = $result;
 			return $results;
 		}

 		public function browsePetitioncategoryAction() {
 			$isCategory = $this->_getParam('category_id');
 			$viewer = Engine_Api::_()->user()->getViewer();
 			if(!$isCategory)
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));

 			if(!empty($isCategory))
 				$params['category_id'] = $isCategory;
 			$paginator = Engine_Api::_()->getDbtable('epetitions', 'epetition')->getEpetitionsPaginator($params);
 			$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			$result = $this->getPetitions($paginator);
 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 			if ($result <= 0)
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist petitions.'), 'result' => array()));
 			else
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 		}
 		public function mySignatureAction(){
 			$owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 			$params['owner_id'] = $owner_id;
 			$paginator = Engine_Api::_()->getDbtable('signatures', 'epetition')->getSignaturesPaginator($params);
 			$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 			$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 			$result = $this->getMypetition($paginator);
 			$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 			$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 			$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 			$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;

 			$menuoptions= array();
 			$counterMenu = 0;	
 			$menuoptions[$counterMenu]['name'] = "support_statement";
 			$menuoptions[$counterMenu]['label'] = $this->view->translate("Support Statement");
 			$result['menus'] = $menuoptions;
 			$counterMenu++;

 			$menuoptions[$counterMenu]['name'] = "support_reason";
 			$menuoptions[$counterMenu]['label'] = $this->view->translate("Support Reason");
 			$result['menus'] = $menuoptions;

 			if ($result <= 0)
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist petitions.'), 'result' => array()));
 			else
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));

 		}
 		public function getMypetition($paginator) { 

 			$viewer = Engine_Api::_()->user()->getViewer();

 			$viewerId = $viewer->getIdentity();
 			$levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
 			$counter = 0;
 			$result = array();
 			foreach($paginator as $item){
 				$result['petitions'][$counter] = $item->toArray();          
 				$counter++;


 			}
 			return $result;
 		}
 		public function likeAction() {
 			$count = "";
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$item_id = $this->_getParam('epetition_id', null);
 			if(!$item_id){
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 			}
 			$type = 'epetition';
 			$dbTable = 'epetitions';
 			$resorces_id = 'epetition_id';
 			$notificationType = 'liked';
 			$actionType = 'epetition_like';
 			if ($this->_getParam('type', false) && $this->_getParam('type') == 'epetition_album') {
 				$type = 'epetition_album';
 				$dbTable = 'albums';
 				$resorces_id = 'album_id';
 				$actionType = 'epetition_album_like';
 			} else if ($this->_getParam('type', false) && $this->_getParam('type') == 'epetition_photo') {
 				$type = 'epetition_photo';
 				$dbTable = 'photos';
 				$resorces_id = 'photo_id';
 				$actionType = 'epetition_photo_like';
 			}

 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewer_id = $viewer->getIdentity();

 			$itemTable = Engine_Api::_()->getDbtable($dbTable, 'epetition');
 			$tableLike = Engine_Api::_()->getDbtable('likes', 'core');
 			$tableMainLike = $tableLike->info('name');


 			$select = $tableLike->select()
 			->from($tableMainLike)
 			->where('resource_type = ?', $type)
 			->where('poster_id = ?', $viewer_id)
 			->where('poster_type = ?', 'user')
 			->where('resource_id = ?', $item_id);
 			$result = $tableLike->fetchRow($select);

 			if (!empty($result)) {
 				$db = $result->getTable()->getAdapter();
 				$db->beginTransaction();
 				try {
 					$result->delete();
 					$db->commit();
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 				$item = Engine_Api::_()->getItem($type, $item_id);
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $item->like_count));
 			} else {
 				$db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
 				$db->beginTransaction();
 				try {

 					$like = $tableLike->createRow();
 					$like->poster_id = $viewer_id;
 					$like->resource_type = $type;
 					$like->resource_id = $item_id;
 					$like->poster_type = 'user';
 					$like->save();

 					$itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array($resorces_id . '= ?' => $item_id));
 					$db->commit();
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}

      //Send notification and activity feed work.
 				$item = Engine_Api::_()->getItem($type, $item_id);
 				$subject = $item;
 				$owner = $subject->getOwner();
 				if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {	
 					$activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
 					Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
 					Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $notificationType);
 					$result = $activityTable->fetchRow(array('type =?' => $actionType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
 					if (!$result) {
 						if ($subject && empty($subject->title) && $this->_getParam('type') == 'epetition_photo') {
 							$album_id = $subject->album_id;
 							$subject = Engine_Api::_()->getItem('epetition_album', $album_id);
 						}
 						$action = $activityTable->addActivity($viewer, $subject, $actionType);
 						if ($action)
 							$activityTable->attachActivity($action, $subject);
 					}

 					Engine_Api::_()->getApi('mail', 'core')->sendSystem($subject->getOwner(), 'notify_petitions_petitionliked', array('petition_title' => $subject->getTitle(), 'sender_title' => $viewer->getTitle(), 'object_link' => $subject->getHref(), 'host' => $_SERVER['HTTP_HOST']));

 				}
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $item->like_count));
 			}
 		}
 		public function favouriteAction(){

 			$count = "";
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$viewerId = $viewer->getIdentity();
 			$item_id = $this->_getParam('epetition_id', null);
 			$type = $this->_getParam('type', null);
 			if(!$item_id){
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 			}

 			if ($this->_getParam('type') == 'epetition') {
 				$type = 'epetition';
 				$dbTable = 'epetitions';
 				$resorces_id = 'epetition_id';
 				$notificationType = 'epetition_favourite';
 			} else if ($this->_getParam('type') == 'epetition_photo') {
 				$type = 'epetition_photo';
 				$dbTable = 'photos';
 				$resorces_id = 'photo_id';
 			} else if ($this->_getParam('type') == 'epetition_album') {
 				$type = 'epetition_album';
 				$dbTable = 'albums';
 				$resorces_id = 'album_id';
 			}

 			$viewer = Engine_Api::_()->user()->getViewer();
 			$Fav = Engine_Api::_()->getDbTable('favourites', 'epetition')->getItemfav($type, $item_id);

 			$favItem = Engine_Api::_()->getDbtable($dbTable, 'epetition');
 			if (!empty($Fav)) {
      //delete
 				$db = $Fav->getTable()->getAdapter();
 				$db->beginTransaction();
 				try {
 					$Fav->delete();
 					$db->commit();
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 				$favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count - 1')), array($resorces_id . ' = ?' => $item_id));
 				$item = Engine_Api::_()->getItem($type, $item_id);
 				if (@$notificationType) {
 					Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
 					Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
 					Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
 				}
 				
 			} else {
      //update
 				$db = Engine_Api::_()->getDbTable('favourites', 'epetition')->getAdapter();
 				$db->beginTransaction();
 				try {
 					$fav = Engine_Api::_()->getDbTable('favourites', 'epetition')->createRow();
 					$fav->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 					$fav->resource_type = $type;
 					$fav->resource_id = $item_id;
 					$fav->save();
 					$favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1'),
 				), array(
 					$resorces_id . '= ?' => $item_id,
 				));
        // Commit
 					$db->commit();
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 				$item = Engine_Api::_()->getItem(@$type, @$item_id);
 				if (@$notificationType) {
 					$subject = $item;
 					$owner = $subject->getOwner();
 					if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity() && @$notificationType) {
 						$activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
 						Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
 						Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $notificationType);
 						$result = $activityTable->fetchRow(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
 						if (!$result) {
 							$action = $activityTable->addActivity($viewer, $subject, $notificationType);
 							if ($action)
 								$activityTable->attachActivity($action, $subject);
 						}
 					}
 				}
 				$this->view->favourite_id = 1;
 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $favourite_count));

 			}
 		}

 		public function decisionAction(){
 			$decisionmaker_id = $this->_getParam('decisionmaker_id');
 			$actionType = $this->_getParam('actionType');
 			$decisionmaker = Engine_Api::_()->getItem('epetition_decisionmaker', $decisionmaker_id);
 			$decisionmaker->letter_approve = trim($actionType);
 			$decisionmaker->save();
 			if($actionType == 2){
 				$types ='Approve';
 			}
 			elseif($actionType == 3){
 				$types = 'Cancel';
 			}
 			$epetition = Engine_Api::_()->getItem('epetition', $decisionmaker->epetition_id);
 			$petitionOwner = $epetition->getOwner();
 			if($decisionmaker->letter_approve == 2){
 				$epetition->victory = 1;
 				$epetition->vicotry_time = date('Y-m-d H:i:s');
 				$epetition->save();
 			}
 			$sender = Engine_Api::_()->getItem('user', $decisionmaker->user_id);
 			Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($petitionOwner, $sender, $epetition, 'epetition_letterapprove3', array('owner' => "<a href=" . $sender->getHref() . ">" . $sender->getTitle() . "</a>", 'petition' => "<a href=" . $epetition->getHref() . ">" . $epetition['title'] . "</a>", 'type' => $types));

 			$super_admin = Engine_Api::_()->epetition()->getAdminnSuperAdmins();
 			$signuser = Engine_Api::_()->getDbtable('signatures', 'epetition')->signAllUser($epetition['epetition_id']);
 			$decisionMaker = Engine_Api::_()->getDbtable('decisionmakers', 'epetition')->getAllUserId($epetition['epetition_id']);
 			foreach ($super_admin as $admin) {
 				$admin_obj = Engine_Api::_()->getItem('user', $admin['user_id']);
 				Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin_obj->email, 'epetition_email', array(
 					'host' => $_SERVER['HTTP_HOST'],
 					'subject' => "Petition letter " . $types,
 					'message' => "<a href='" . $sender->getHref() . "'>" . $sender->getTitle() . "</a> have " . $types . "  letter of the petition <a href='" . $epetition->getHref() . "'>" . $epetition['title'] . "</a>.",
 				));
 				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($admin_obj, $sender, $epetition, 'epetition_letterapprove1', array('owner' => "<a href=" . $sender->getHref() . ">" . $sender->getTitle() . "</a>", 'petition' => "<a href=" . $epetition->getHref() . ">" . $epetition['title'] . "</a>", 'type' => $types));
 			}

        // send email and notification for user
 			foreach ($signuser as $signuse) {
 				$viewer_user = Engine_Api::_()->getItem('user', $signuse['owner_id']);

 				Engine_Api::_()->getApi('mail', 'core')->sendSystem($viewer_user->email, 'notify_epetition_approveletter', array('petition_name' => $epetition->getTitle(), 'decision_maker' => $sender->getTitle(), 'object_link' => $epetition->getHref(), 'host' => $_SERVER['HTTP_HOST']));

 				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($viewer_user, $sender, $epetition, 'epetition_letterapprove2', array('owner' => "<a href=" . $sender->getHref() . ">" . $sender->getTitle() . "</a>", 'petition' => "<a href=" . $epetition->getHref() . ">" . $epetition['title'] . "</a>", 'type' => $types));
 			}

        // send email and notification for decision maker
 			foreach ($decisionMaker as $dem) {
 				$viewer_dec = Engine_Api::_()->getItem('user', $dem['user_id']);
 				Engine_Api::_()->getApi('mail', 'core')->sendSystem($viewer_dec->email, 'epetition_email', array(
 					'host' => $_SERVER['HTTP_HOST'],
 					'subject' => "Petition letter " . $types,
 					'message' => "<a href='" . $sender->getHref() . "'>" . $sender->getTitle() . "</a> have " . $types . "  letter of the petition <a href='" . $epetition->getHref() . "'>" . $epetition['title'] . "</a>.",
 				));
 				Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($viewer_dec, $sender, $epetition, 'epetition_letteradd2', array('owner' => "<a href=" . $sender->getHref() . ">" . $sender->getTitle() . "</a>", 'petition' => "<a href=" . $epetition->getHref() . ">" . $epetition['title'] . "</a>", 'type' => $types));

 				Engine_Api::_()->getApi('mail', 'core')->sendSystem($viewer_dec->email, 'epetition_approvedpetition', array('petition_name' => $epetition->getTitle(), 'decision_maker' => $viewer_dec->getTitle(),'petition_owner_name'=>$epetition->getOwner()->getTitle(), 'object_link' => $epetition->getHref(), 'host' => $_SERVER['HTTP_HOST']));

 			}
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'','message' => $this->view->translate('You have successfully created .')));

 		}
 		public function overviewAction(){
 			$isSearch = isset($_POST['search']) ? $_POST['search'] : '';
 			$viewer = Engine_Api::_()->user()->getViewer();
 			$id = $this->_getParam('epetition_id', null);
 			$petitions = Engine_Api::_()->getItem('epetition', $id); 
 			$result['petitions'] = $petitions->toArray();
 			$result['petitions']['petitions_image'] = $this->getBaseUrl(true, Engine_Api::_()->storage()->get($petitions->photo_id, '')->getPhotoUrl());
 			$result['petitions']['user_image'] = $this->getBaseUrl(true, 
 				!empty($user->photo_id) ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
 			if($this->view->viewer()->getIdentity() != 0){
 				$response["petitions"]['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($petitions);
 				$response["petitions"]['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($petitions);
 				if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epetition.allowfavc', 1)) {
 					$response["petitions"]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($petitions,'favourites','epetition','epetition');
 					$response["petitions"]['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($petitions,'favourites','epetition','epetition');
 				}
 			}
 			
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
 		}

 	}
