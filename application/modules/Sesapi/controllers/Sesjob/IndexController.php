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
 class Sesjob_IndexController extends Sesapi_Controller_Action_Standard
 {
 	public function init() 
 	{

 	}
 	public function menuAction(){
 		$menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesjob_main', array());
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
 	public function browseAction(){
 		$isCompanyName = isset($_POST['companyname']) ? $_POST['companyname'] : '';
 		$isJobName = isset($_POST['jobname']) ? $_POST['jobname'] : '';
 		$isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
 		$isIndustry = isset($_POST['industry_id']) ? $_POST['industry_id'] : '';
 		$isEmployment = isset($_POST['employment_id']) ? $_POST['employment_id'] : '';
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
 		$form = new Sesjob_Form_Search(array('searchTitle' => 'yes','browseBy' => 'yes','sesjobsSearch' => 'yes','searchFor'=>'job','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => $location,'kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 		$form->populate($_POST);
 		$params = $form->getValues();
 		if(!empty($isCompanyName))
 			$params['text'] = $isCompanyName;
 		if(!empty($isJobName))
 			$params['text'] = $isJobName;
 		if(!empty($isCategory))
 			$params['category_id'] = $isCategory;
 		if(!empty($isIndustry))
 			$params['industry_id'] = $isIndustry;
 		if(!empty($isEmployment))
 			$params['employment_id'] = $isEmployment;
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
 				if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allowfavv', 1)) {
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
 		if (!empty($_POST['location'])) {
 			$latlng = Engine_Api::_()->sesapi()->getCoordinates($_POST['location']);
 			if ($latlng) {
 				$_POST['lat'] = $latlng['lat'];
 				$_POST['lng'] = $latlng['lng'];
 			}
 		}


 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);	
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getJobs($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist jobs.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 	}


 	public function getJobs($paginator) { 
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewerId = $viewer->getIdentity();
 		$levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
 		$canFavourite = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob_allow_favourite', 0);
 		$likeFollowIntegrate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allow.integration', 0);
 		$counter = 0;
 		$result = array();
		$sesshortcut = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesshortcut') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesshortcut.enableshortcut', 1);
        $canShare = Engine_Api::_()->getApi('settings', 'core')->getSetting('mobile_sesjob_share', 1);

 		foreach($paginator as $item){
 			$result['jobs'][$counter] = $item->toArray();

			$result['jobs'][$counter]['enable_add_shortcut'] = $sesshortcut;
			if($sesshortcut){
				$isShortcut = Engine_Api::_()->getDbTable('shortcuts', 'sesshortcut')->isShortcut(array('resource_type' => $item->getType(), 'resource_id' => $item->getIdentity()));
				if (empty($isShortcut)) {
					$shortMessage['title'] = $this->view->translate('Add to Shortcuts');
					$shortMessage['resource_type'] = $item->getType();
					$shortMessage['resource_id'] = $item->getIdentity();
					$shortMessage['is_saved'] = false;
				} else {
					$shortMessage['title'] = $this->view->translate('Remove From Shortcuts');
					$shortMessage['resource_type'] = $item->getType();
					$shortMessage['resource_id'] = $item->getIdentity();
					$shortMessage['shortcut_id'] = $isShortcut;
					$shortMessage['is_saved'] = true;
				}
				$result['jobs'][$counter]['shortcut_save'] = $shortMessage;
			}
			$result['jobs'][$counter]['can_share'] = $canShare;
			$result['jobs'][$counter]["share"]["imageUrl"] = $this->getBaseUrl(false, $item->getPhotoUrl());
			$result['jobs'][$counter]["share"]["url"] = $this->getBaseUrl(false,$item->getHref());
			$result['jobs'][$counter]["share"]["title"] = $item->getTitle();
			$result['jobs'][$counter]["share"]["description"] = strip_tags($item->getDescription());
			$result['jobs'][$counter]["share"]["setting"] = $shareType;
			$result['jobs'][$counter]["share"]['urlParams'] = array(
				"type" => $item->getType(),
				"id" => $item->getIdentity()
			);

 			$owner = $item->getOwner();
 			$result['jobs'][$counter]['likeFollowIntegrate'] = $likeFollowIntegrate? true : false;
 			if ($likeStatus && $viewer_id) {
 				$result['jobs'][$counter]['is_content_like'] = true;
 			} else {
 				$result['jobs'][$counter]['is_content_like'] = false;
 			}
 			if($canFavourite){
 				$result['jobs'][$counter]['is_content_favourite'] = $favouriteStatus >0?true:false;
 			}
 			$category = Engine_Api::_()->getItem('sesjob_category', $item->category_id);
 			$industry = Engine_Api::_()->getItem('sesjob_industry', $item->industry_id);
 			$result['jobs'][$counter]['category_title'] = $category->category_name;
 			$result['jobs'][$counter]['industry_title'] = $industry->industry_name;
 			$result['jobs'][$counter]['user_title'] = $owner->getTitle();
 			$result['jobs'][$counter]['user_image'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
 			$result['jobs'][$counter]['jobs_image']= $this->getBaseUrl(true, $item->getPhotoUrl());
 			if ($viewerId != 0) {
 				$result['jobs'][$counter]['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($item);
 				$result['jobs'][$counter]['content_like_count'] = (int)Engine_Api::_()->sesapi()->getContentLikeCount($item);
 				if ($canFavourite) {
 					$result['jobs'][$counter]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($item, 'favourites', 'sesjob', 'jobs', 'owner_id');
 					$result['jobs'][$counter]['content_favourite_count'] = (int)Engine_Api::_()->sesapi()->getContentFavouriteCount($item, 'favourites', 'sesjob', 'jobs', 'owner_id');
 				}	

 			}
 			$counter++;

 		}
 		return $result;
 	}

 	
 	public function filtersearchJobAction(){
 		$form = new Sesjob_Form_Search(array('searchTitle' => $this->_getParam('search_title', 'yes'),'browseBy' => $this->_getParam('browse_by', 'yes'),'categoriesSearch' => $this->_getParam('categories', 'yes'),'searchFor'=>$search_for,'FriendsSearch'=>$this->_getParam('friend_show', 'yes'),'defaultSearchtype'=>$default_search_type,'locationSearch' => $location,'kilometerMiles' => $this->_getParam('kilometer_miles', 'yes'),'hasPhoto' => $this->_getParam('has_photo', 'yes'), 'searchcompTitle' => $this->_getParam('searchcomp_title', 'yes'), 'industry' => $this->_getParam('industry', 'yes'), 'employmenttype' => $this->_getParam('employmenttype', 'yes'), 'educationlevel' => $this->_getParam('educationlevel', 'yes')));
 		if ($this->_getParam('getForm')) {
 			$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
 			$this->generateFormFields($formFields, array('resources_type' => 'sesjob'));
 		} else {
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
 		}
 	}
 	public function searchJobAction(){
 		$isJobName = isset($_POST['jobname']) ? $_POST['jobname'] : '';
 		$form = new Sesjob_Form_Search(array('searchTitle' => 'yes','browseBy' => 'yes','sesjobsSearch' => 'yes','searchFor'=>'job','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => $location,'kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 		$form->populate($_POST);
 		$params = $form->getValues();
 		if(!empty($isJobName))
 			$params['alphabet'] = $isJobName;
 	

 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);	
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getJobs($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist jobs.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 	}
 	public function createJobAction(){

 		$category_id = $this->_getParam('category_id',false);
 		$this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesjob')->profileFieldId();
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$subjectId = $this->_getParam('job_id', 0);   
 		$form = new Sesjob_Form_Create(array('defaultProfileId' => $defaultProfileId,'fromApi'=>true));
		 if($form->getElement("jobgeneralinformation"))
		 	$form->removeElement('jobgeneralinformation');
		$form->removeElement('companydetails');
		 if($form->getElement("job_custom_datetimes"))
		 	$form->removeElement('job_custom_datetimes');
		 if($form->getElement("job_location")) 
		 	$form->removeElement('job_location');
		
		 if($form->getElement("lat")) {
			 $form->removeElement('lat');
			$form->removeElement('lng');
			$form->removeElement('map-canvas');
			$form->removeElement('ses_location');
		 }
		 $form->removeElement('photo_file');
		 $form->removeElement('jobdetails');
		 $form->removeElement('contactdetails');
		 $form->removeElement('submit_check');
 		if($this->_getParam('getForm')) {
 			$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 			$this->generateFormFields($formFields,array('resources_type'=>'sesjob'));
 		}   
 		if(!$form->isValid($this->getRequest()->getPost()) ) { 
 			$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 			if(is_countable($validateFields) && engine_count($validateFields))
 				$this->validateFormFields($validateFields);
 		}

 		$job_table = Engine_Api::_()->getDbTable('jobs', 'sesjob');
 		$db = $job_table->getAdapter();
 		$db->beginTransaction();
 		try {
 			$paramss = $values = $form->getValues();
 			$paramss['owner_id'] = $viewer->getIdentity();
 			$jobTable = Engine_Api::_()->getDbTable('jobs', 'sesjob');
 			$job = $jobTable->createRow();
 			$job->setFromArray($paramss);
 			$job->save();
			 if(empty($values['company_id']) && $values['company_name']) {
				$companiesTable = Engine_Api::_()->getDbtable('companies', 'sesjob');
				$companies = $companiesTable->createRow();
				$companies->company_name = $values['company_name'];
						$companies->owner_id = $viewer->getIdentity();
				$companies->company_websiteurl = $values['company_websiteurl'];
				$companies->company_description = $values['company_description'];
				$companies->industry_id = isset($values['industry_id']) ? $values['industry_id'] : 0 ;
				$companies->save();
				$companies->job_count++;
				$companies->save();
				$job->company_id = $companies->company_id;
				$job->save();
			  } elseif(!empty($values['company_id'])) {
				$company = Engine_Api::_()->getItem('sesjob_company', $values['company_id']);
				$company->company_name = $values['company_name'];
				$company->company_websiteurl = $values['company_websiteurl'];
				$company->company_description = $values['company_description'];
				$company->industry_id =  isset($values['industry_id']) ? $values['industry_id'] : 0 ;
				$company->save();
				$company->job_count++;
				$company->save();
				$job->company_id = $company->company_id;
				$job->save();
			  }
 			$db->commit();	
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('job_id' => $job->getIdentity(), 'message' => $this->view->translate('You have successfully created .'))));
 		} catch (Exception $e) {
 			$db->rollBack();
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage()));
 		}
 	}

 	public function myJobsAction(){
 		$user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 		$params['user_id'] = $user_id;
		 $params["manage-widget"] = true;
 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getJobs($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist petitions.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));

 	}

 	public function categoriesAction(){
 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator();
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		if ($paginator->getCurrentPageNumber() == 1) {
 			$categories = Engine_Api::_()->getDbtable('categories', 'sesjob')->getCategory(array('column_name' => '*', 'limit' => 25, 'countJobs' => 1));
 			$menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesjob_main', array());
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
				$result_category[$category_counter]['count'] = $this->view->translate(array('%s job', '%s jobs', $category->total_jobs_categories), $this->view->locale()->toNumber($category->total_jobs_categories));
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
 			$job = $categories->toArray();
 			$params['category_id'] = $categories->category_id;
 			$params['limit'] = 5;
 			$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);
 			$paginator->setItemCountPerPage(3);
 			$paginator->setCurrentPageNumber(1);
 			if($paginator->getTotalItemCount() > 0){
 				$result[$counter] = $job;
 				$result[$counter]['items'] = $this->getJobs($paginator);
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
 	public function browseCompanyAction(){
 		$isCompanyName = isset($_POST['companyname']) ? $_POST['companyname'] : '';
 		$isIndustry = isset($_POST['industry_id']) ? $_POST['industry_id'] : '';

 		$form = new Sesjob_Form_SearchCompany(array('searchTitle' => 'yes','browseBy' => 'yes','sesjobsSearch' => 'yes','searchFor'=>'job','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => $location,'kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 		$form->populate($_POST);
 		$params = $form->getValues();
 		if(!empty($isCompanyName))
 			$params['text'] = $isCompanyName;
 		if(!empty($isIndustry))
 			$params['industry_id'] = $isIndustry;


 		$paginator = Engine_Api::_()->getDbtable('companies', 'sesjob')->getCompaniesPaginator($params);	
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getCompanys($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist jobs.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 	}
 	public function getCompanys($paginator) { 	
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewerId = $viewer->getIdentity();
 		$levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
 		$counter = 0;
 		$result = array();

		$sesshortcut = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesshortcut') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesshortcut.enableshortcut', 1);
        $canShare = Engine_Api::_()->getApi('settings', 'core')->getSetting('mobile_sesjob_share', 1);
 		foreach($paginator as $item){
 			$result['companies'][$counter] = $item->toArray();

			 $result['companies'][$counter]['enable_add_shortcut'] = $sesshortcut;
			 if($sesshortcut){
				 $isShortcut = Engine_Api::_()->getDbTable('shortcuts', 'sesshortcut')->isShortcut(array('resource_type' => $item->getType(), 'resource_id' => $item->getIdentity()));
				 if (empty($isShortcut)) {
					 $shortMessage['title'] = $this->view->translate('Add to Shortcuts');
					 $shortMessage['resource_type'] = $item->getType();
					 $shortMessage['resource_id'] = $item->getIdentity();
					 $shortMessage['is_saved'] = false;
				 } else {
					 $shortMessage['title'] = $this->view->translate('Remove From Shortcuts');
					 $shortMessage['resource_type'] = $item->getType();
					 $shortMessage['resource_id'] = $item->getIdentity();
					 $shortMessage['shortcut_id'] = $isShortcut;
					 $shortMessage['is_saved'] = true;
				 }
				 $result['companies'][$counter]['shortcut_save'] = $shortMessage;
			 }
			 $result['companies'][$counter]['can_share'] = $canShare;
			 $result['companies'][$counter]["share"]["imageUrl"] = $this->getBaseUrl(false, $item->getPhotoUrl());
			 $result['companies'][$counter]["share"]["url"] = $this->getBaseUrl(false,$item->getHref());
			 $result['companies'][$counter]["share"]["title"] = $item->getTitle();
			 $result['companies'][$counter]["share"]["description"] = strip_tags($item->getDescription());
			 $result['companies'][$counter]["share"]["setting"] = $shareType;
			 $result['companies'][$counter]["share"]['urlParams'] = array(
				 "type" => $item->getType(),
				 "id" => $item->getIdentity()
			 );

 			$owner = $item->getOwner();
 			$result['companies'][$counter]['likeFollowIntegrate'] = $likeFollowIntegrate? true : false;
 			$industry = Engine_Api::_()->getItem('sesjob_industry', $item->industry_id);
 			$result['companies'][$counter]['category_title'] = $category->category_name;
 			$result['companies'][$counter]['industry_title'] = $industry->industry_name;
 			$result['companies'][$counter]['user_title'] = $owner->getTitle();	
 			$result['companies'][$counter]['user_image'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
 			$result['companies'][$counter]['company_image']= $this->getBaseUrl(true, $item->getPhotoUrl());

 			$counter++;

 		}
 		return $result;
 	}
 	public function filtersearchCompanyAction(){
 		$form = new Sesjob_Form_SearchCompany(array('searchTitle' => $this->_getParam('search_title', 'yes'),'browseBy' => $this->_getParam('browse_by', 'yes'),'categoriesSearch' => $this->_getParam('categories', 'yes'),'searchFor'=>$search_for,'FriendsSearch'=>$this->_getParam('friend_show', 'yes'),'defaultSearchtype'=>$default_search_type,'locationSearch' => $location,'kilometerMiles' => $this->_getParam('kilometer_miles', 'yes'),'hasPhoto' => $this->_getParam('has_photo', 'yes'), 'searchcompTitle' => $this->_getParam('searchcomp_title', 'yes'), 'industry' => $this->_getParam('industry', 'yes'), 'employmenttype' => $this->_getParam('employmenttype', 'yes'), 'educationlevel' => $this->_getParam('educationlevel', 'yes')));
 		if ($this->_getParam('getForm')) {
 			$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
 			$this->generateFormFields($formFields, array('resources_type' => 'sesjob'));
 		} else {
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
 		}
 	}
 	public function searchCompanyAction(){
 		$isCompanyName = isset($_POST['companyname']) ? $_POST['companyname'] : '';
 		$form = new Sesjob_Form_SearchCompany(array('searchTitle' => 'yes','browseBy' => 'yes','sesjobsSearch' => 'yes','searchFor'=>'job','FriendsSearch'=>'yes','defaultSearchtype'=>'mostSPliked','locationSearch' => $location,'kilometerMiles' => 'yes','hasPhoto' => 'yes'));
 		$form->populate($_POST);
 		$params = $form->getValues();
 		if(!empty($isCompanyName))
 			$params['text'] = $isCompanyName;

 		$paginator = Engine_Api::_()->getDbtable('companies', 'sesjob')->getCompaniesPaginator($params);	
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getCompanys($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist jobs.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 	}
 	public function categoryViewAction() {
 		$isCategory = $this->_getParam('category_id');
 		$viewer = Engine_Api::_()->user()->getViewer();
 		if(!$isCategory)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));

 		if(!empty($isCategory))
 			$params['category_id'] = $isCategory;
 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getJobs($paginator);
 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist jobs.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
 	}
 	public function companyViewAction(){
 		$isSearch = isset($_POST['search']) ? $_POST['search'] : '';
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$id = $this->_getParam('company_id', null);
 		$jobs = $item = Engine_Api::_()->getItem('sesjob_company', $id); 
 		$result['jobs'] = $jobs->toArray();


		 $result['jobs']['enable_add_shortcut'] = $sesshortcut;
		 if($sesshortcut){
			 $isShortcut = Engine_Api::_()->getDbTable('shortcuts', 'sesshortcut')->isShortcut(array('resource_type' => $item->getType(), 'resource_id' => $item->getIdentity()));
			 if (empty($isShortcut)) {
				 $shortMessage['title'] = $this->view->translate('Add to Shortcuts');
				 $shortMessage['resource_type'] = $item->getType();
				 $shortMessage['resource_id'] = $item->getIdentity();
				 $shortMessage['is_saved'] = false;
			 } else {
				 $shortMessage['title'] = $this->view->translate('Remove From Shortcuts');
				 $shortMessage['resource_type'] = $item->getType();
				 $shortMessage['resource_id'] = $item->getIdentity();
				 $shortMessage['shortcut_id'] = $isShortcut;
				 $shortMessage['is_saved'] = true;
			 }
			 $result['jobs']['shortcut_save'] = $shortMessage;
		 }
		 $result['jobs']['can_share'] = $canShare;
		 $result['jobs']["share"]["imageUrl"] = $this->getBaseUrl(false, $item->getPhotoUrl());
		 $result['jobs']["share"]["url"] = $this->getBaseUrl(false,$item->getHref());
		 $result['jobs']["share"]["title"] = $item->getTitle();
		 $result['jobs']["share"]["description"] = strip_tags($item->getDescription());
		 $result['jobs']["share"]["setting"] = $shareType;
		 $result['jobs']["share"]['urlParams'] = array(
			 "type" => $item->getType(),
			 "id" => $item->getIdentity()
		 );


 		$result['jobs']['user_image'] = $this->getBaseUrl(true, 
 			!empty($user->photo_id) ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
 		if($this->view->viewer()->getIdentity() != 0){
 			$result["jobs"]['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($jobs);
 		    $result["jobs"]['content_like_count'] = (int) Engine_Api::_()->sesapi()->ContentLike($jobs);
 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allowfavc', 1)) {
 				$result["jobs"]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($jobs,'favourites','sesjob','sesjob');
 				$result["jobs"]['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($jobs,'favourites','sesjob','sesjob');
 			}
 		}
 		Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));	
 	}
 	public function jobDeleteAction() 
 	{
 		$job_id = $this->_getParam('job_id');
 		if (!$this->getRequest()->isPost()) {
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'method is not post'));
 		}
 		$jobs = Engine_Api::_()->getItem('sesjob_job', $this->_getParam('job_id'));

 		if(empty($jobs))
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'data not found'));
 		if ($this->getRequest()->isPost()) {
 			$db = $jobs->getTable()->getAdapter();
 			$db->beginTransaction();
 			try {
 				$jobs->delete();
 				$db->commit();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('petition_id' => $jobs->getIdentity(),'message' => $this->view->translate('You have successfully delete the job.'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 			}
 		}
 	}

 	public function jobViewAction() {
 		$isSearch = isset($_POST['search']) ? $_POST['search'] : '';
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$id = $this->_getParam('job_id', null);
 		$jobs = $item = Engine_Api::_()->getItem('sesjob_job', $id); 
 		$result['jobs'] = $jobs->toArray();

		 $result['jobs']['enable_add_shortcut'] = $sesshortcut;
		 if($sesshortcut){
			 $isShortcut = Engine_Api::_()->getDbTable('shortcuts', 'sesshortcut')->isShortcut(array('resource_type' => $item->getType(), 'resource_id' => $item->getIdentity()));
			 if (empty($isShortcut)) {
				 $shortMessage['title'] = $this->view->translate('Add to Shortcuts');
				 $shortMessage['resource_type'] = $item->getType();
				 $shortMessage['resource_id'] = $item->getIdentity();
				 $shortMessage['is_saved'] = false;
			 } else {
				 $shortMessage['title'] = $this->view->translate('Remove From Shortcuts');
				 $shortMessage['resource_type'] = $item->getType();
				 $shortMessage['resource_id'] = $item->getIdentity();
				 $shortMessage['shortcut_id'] = $isShortcut;
				 $shortMessage['is_saved'] = true;
			 }
			 $result['jobs']['shortcut_save'] = $shortMessage;
		 }
		 $result['jobs']['can_share'] = $canShare;
		 $result['jobs']["share"]["imageUrl"] = $this->getBaseUrl(false, $item->getPhotoUrl());
		 $result['jobs']["share"]["url"] = $this->getBaseUrl(false,$item->getHref());
		 $result['jobs']["share"]["title"] = $item->getTitle();
		 $result['jobs']["share"]["description"] = strip_tags($item->getDescription());
		 $result['jobs']["share"]["setting"] = $shareType;
		 $result['jobs']["share"]['urlParams'] = array(
			 "type" => $item->getType(),
			 "id" => $item->getIdentity()
		 );


 		$result['jobs']['jobs_image'] = $this->getBaseUrl(true, $item->getPhotoUrl());
 		$result['jobs']['user_image'] = $this->getBaseUrl(true, 
 			!empty($user->photo_id) ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
 		if($this->view->viewer()->getIdentity() != 0){
 			$result["jobs"]['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($jobs);
 			$result["jobs"]['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($jobs);
 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allowfavc', 1)) {
 				$result["jobs"]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($jobs,'favourites','sesjob','sesjob');
 				$result["jobs"]['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($jobs,'favourites','sesjob','sesjob');
 			}
 		}

 		if($jobs->isOwner($viewer)){
 			$menuoptions= array();
 			$counterOption = 0;
 			$canEdit =  Engine_Api::_()->authorization()->isAllowed('sesjob_job', $viewer, 'edit');

 			if($canEdit){ 
 				$result['options'][$counterOption]['name'] = "Dashboard";
 				$result['options'][$counterOption]['label'] = $this->view->translate("Dashboard");
 				$counterOption++;    
 			}
 			$canDelete = Engine_Api::_()->authorization()->isAllowed('sesjob_job', $viewer, 'delete');
 			if($canDelete){
 				$result['options'][$counterOption]['name'] = "delete";
 				$result['options'][$counterOption]['label'] = $this->view->translate("Delete");
 				$counterOption++;	      
 			}
 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allow.report', 1) && !$viewer->getIdentity()){
 				$result['options'][$counterOption]['name'] = 'report'; 
 				$result['options'][$counterOption]['label'] = $this->view->translate('Report'); 
 				$counterOption++;
 			}
 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesjob.allow.share', 1) && $viewer->getIdentity()){
 				$result['options'][$counterOption]['name'] = 'share'; 
 				$result['options'][$counterOption]['label'] = $this->view->translate('Share'); 

 			}		
 		}
 		if(!$canEdit){
 			$result['button']['label'] = $this->view->translate('Apply For the job');
 			$result['button']['name'] = 'apply';
 		}

 		$tabcounter = 0;
 		$result['menus'][$tabcounter]['name'] = 'comment';
 		$result['menus'][$tabcounter]['label'] = $this->view->translate('Comments');
 		$tabcounter++;

 		Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
 	}
 	public function companyProfileAction(){
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$id = $this->_getParam('company_id', null);

 		if(!$id){
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 		}
 		$company = Engine_Api::_()->getItem('sesjob_company', $id);
 		if(!$company)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
 		if(!empty($id))
 			$params['company_id'] = $id;

 		$paginator = Engine_Api::_()->getDbtable('jobs', 'sesjob')->getSesjobsPaginator($params);	
 		$paginator->setItemCountPerPage($this->_getParam('limit', 10));
 		$paginator->setCurrentPageNumber($this->_getParam('page', 1));
 		$result = $this->getJobs($paginator);

 		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
 		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
 		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
 		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
 		if ($result <= 0)
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist services.'), 'result' => array()));
 		else
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));	
 	}
 	public function applyJobAction(){
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewerId = $viewer->getIdentity();
 		$jobId = $this->_getParam('job_id', 0);
 		$applications = Engine_Api::_()->getItem('sesjob_application', $jobId);

 		$form = new Sesjob_Form_Company_Apply();
 		if($this->_getParam('getForm')) {
 			$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 			$this->generateFormFields($formFields,array('resources_type'=>'sesjob'));
 		}   
 		if(!$form->isValid($this->getRequest()->getPost()) ) { 
 			$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 			if(is_countable($validateFields) && engine_count($validateFields))
 				$this->validateFormFields($validateFields);
 		}
 		if ($this->getRequest()->isPost()) {
 			$applicationTable = Engine_Api::_()->getDbtable('applications', 'sesjob');
 			$db = $applicationTable->getAdapter();
 			$db->beginTransaction();
 			try { 
 				$formValues = $form->getValues();
 				if (!$jobId) { 
 					$applications = $applicationTable->createRow();
 					$formValues['name'] =$formValues['name'];
 					$formValues['email'] =$formValues['email'];
 					$formValues['mobile_number'] =$formValues['mobile_number'];
 					$formValues['location'] = $formValues['location'];
 					$formValues['photo'] = $formValues['file_id'];
 					$formValues['module_name'] = 'sesjob';
 					$formValues['job_id'] = $jobId;
 					$formValues['owner_id'] = $viewerId; 
 					$applications->setFromArray($formValues);

 				} else{ 
 					$applications->name = $formValues['name'];
 					$applications->email = $formValues['email'];
 					$applications->mobile_number = $formValues['mobile_number'];
 					$applications->location = $formValues['location'];
 					$applications->photo = $formValues['file_id'];
 				}
 				$applications->save();
 				$db->commit();

 				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('job_id' => $applications->getIdentity(), 'message' => $this->view->translate('You have successfully created job.'))));
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 			}
 		}
 	}
 	public function likeAction() {
 		$count = "";
 		$item_id = $this->_getParam('job_id', null);
 		if(!$item_id){
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 		}
 		$type = 'sesjob_job';
 		$dbTable = 'jobs';
 		$resorces_id = 'job_id';
 		$notificationType = 'liked';
 		$actionType = 'sesjob_job_like';

 		if($this->_getParam('type',false) && $this->_getParam('type') == 'sesjob_album'){
 			$type = 'sesjob_album';
 			$dbTable = 'albums';
 			$resorces_id = 'album_id';
 			$actionType = 'sesjob_album_like';
 		} else if($this->_getParam('type',false) && $this->_getParam('type') == 'sesjob_photo') {
 			$type = 'sesjob_photo';
 			$dbTable = 'photos';
 			$resorces_id = 'photo_id';
 			$actionType = 'sesjob_photo_like';
 		}


 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewer_id = $viewer->getIdentity();

 		$itemTable = Engine_Api::_()->getDbtable($dbTable, 'sesjob');
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
            //delete
 			$db = $result->getTable()->getAdapter();
 			$db->beginTransaction();
 			try {
 				$result->delete();
 				$temp['data']['message'] = $this->view->translate('Successfully Unliked.');
 				$db->commit();
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 			}
 			$item = Engine_Api::_()->getItem($type, $item_id);
 			$temp['data']['like_count'] = $item->like_count;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
 			
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
 					if($subject && empty($subject->title) && $this->_getParam('type') == 'sesjob_photo') {
 						$album_id = $subject->album_id;
 						$subject = Engine_Api::_()->getItem('sesjob_album', $album_id);
 					}
 					$action = $activityTable->addActivity($viewer, $subject, $actionType);
 					if ($action)
 						$activityTable->attachActivity($action, $subject);
 				}
 			}
 			$temp['data']['like_count'] = $item->like_count;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
 		}
 	}
 	public function favouriteAction() {
 		$count = "";
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewerId = $viewer->getIdentity();
 		$item_id = $this->_getParam('job_id', null);
 		$type = $this->_getParam('type', null);
 		if(!$item_id){
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 		}

 		if ($this->_getParam('type') == 'sesjob_job') {
 			$type = 'sesjob_job';
 			$dbTable = 'jobs';
 			$resorces_id = 'job_id';
 			$notificationType = 'sesjob_job_favourite';
 		} else if ($this->_getParam('type') == 'sesjob_photo') {
 			$type = 'sesjob_photo';
 			$dbTable = 'photos';
 			$resorces_id = 'photo_id';
 		} else if ($this->_getParam('type') == 'sesjob_album') {
 			$type = 'sesjob_album';
 			$dbTable = 'albums';
 			$resorces_id = 'album_id';
 		}

 		$viewer = Engine_Api::_()->user()->getViewer();
 		$Fav = Engine_Api::_()->getDbTable('favourites', 'sesjob')->getItemfav($type, $item_id);

 		$favItem = Engine_Api::_()->getDbtable($dbTable, 'sesjob');
 		if (!empty($Fav)) {
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
 			if(@$notificationType) {
 				Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
 				Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
 				Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
 			}
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $favourite_count));
 			$this->view->favourite_id = 0;

 		} else {
 			$db = Engine_Api::_()->getDbTable('favourites', 'sesjob')->getAdapter();
 			$db->beginTransaction();
 			try {
 				$fav = Engine_Api::_()->getDbTable('favourites', 'sesjob')->createRow();
 				$fav->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 				$fav->resource_type = $type;
 				$fav->resource_id = $item_id;
 				$fav->save();
 				$favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1'),
 			), array(
 				$resorces_id . '= ?' => $item_id,
 			));
 				$db->commit();
 			} catch (Exception $e) {
 				$db->rollBack();
 				Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 			}
              //send notification and activity feed work.
 			$item = Engine_Api::_()->getItem(@$type, @$item_id);
 			if(@$notificationType) {
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
 	public function subscribeAction() {
 		$item = $this->_getParam('company_id', null);
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$viewer_id = $viewer->getIdentity();
 		if (empty($viewer_id))
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));

 		$resource_id = $this->_getParam('resource_id');
 		$resource_type = $this->_getParam('resource_type');
 		$cpnysubscribe_id = $this->_getParam('cpnysubscribe_id');

 		$item = Engine_Api::_()->getItem($resource_type, $resource_id);

 		$favouriteTable = Engine_Api::_()->getDbTable('cpnysubscribes', 'sesjob');
 		$activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
 		$activityStrameTable = Engine_Api::_()->getDbtable('stream', 'activity');

 		if (empty($cpnysubscribe_id)) {
 			$isCpnysubscribe = $favouriteTable->isCpnysubscribe(array('resource_id' => $resource_id, 'resource_type' => $resource_type));
 			if (empty($isCpnysubscribe)) {
 				$db = $favouriteTable->getAdapter();
 				$db->beginTransaction();
 				try {
 					if (!empty($item))
 						$cpnysubscribe_id = $favouriteTable->addCpnysubscribe($item, $viewer)->cpnysubscribe_id;

 					$this->view->cpnysubscribe_id = $cpnysubscribe_id;

 					$owner = Engine_Api::_()->getItem('user', $item->owner_id);
 					Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesjob_cpnysubscribe');
 					$action = $activityTable->addActivity($viewer, $item, 'sesjob_cpnysubscribe');
 					if ($action)
 						$activityTable->attachActivity($action, $item);

 					$db->commit();
 				} catch (Exception $e) {
 					$db->rollBack();
 					Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
 				}
 			} else {
 				$this->view->cpnysubscribe_id = $isCpnysubscribe;
 			}
 		} else {
 			Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesjob_cpnysubscribe", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
 			$action = $activityTable->fetchRow(array('type =?' => "sesjob_cpnysubscribe", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));

 			if (!empty($action)) {
 				$action->deleteItem();
 				$action->delete();
 			}

 			$favouriteTable->removeCpnysubscribe($item, $viewer);
 		}
 		$this->view->cpnysubscribe_id = 1;
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $cpnysubscribe_id));
 	}
 	public function editJobAction(){
 		$viewer = Engine_Api::_()->user()->getViewer();
 		$job_id = $this->_getParam('job_id');
 		$user_id = $this->_getParam('user_id');	

 		if (Engine_Api::_()->core()->hasSubject()){
 			$job = Engine_Api::_()->core()->getSubject();
 		}else{
 			$job= Engine_Api::_()->getItem('sesjob_job',$job_id);
 			Engine_Api::_()->core()->setSubject($job);
 		}

 		if(empty($job)){
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
 		}

 		$form = new Sesjob_Form_Edit(array('fromApi'=>true));
		 $form->removeElement('companydetails');
		 $form->removeElement('jobgeneralinformation');
		 $form->removeElement('job_custom_datetimes');
		 $form->removeElement('map-canvas');
		 $form->removeElement('photo_file');
		 $form->removeElement('jobdetails');
 		$form->populate($job->toArray());


 		if( !$this->getRequest()->isPost() ) {
 			$this->view->status = false;
 			$this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid.'), 'result' => array()));
 		}

 		if($this->_getParam('getForm')) {
 			$formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
 			$this->generateFormFields($formFields,array('resources_type'=>'sesjob'));
 		} 
 		if( !$form->isValid($_POST) ) {
 			$validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
 			if(is_countable($validateFields) && engine_count($validateFields))
 				$this->validateFormFields($validateFields);
 		}

 		$values = $form->getValues();
 		$db = Engine_Api::_()->getDbTable('jobs', 'sesjob')->getAdapter();
 		$db->beginTransaction();
 		try {  
 			$job = Engine_Api::_()->getItem('sesjob_job',$job_id);
 			$job->setFromArray($values);
 			$job->save();
 			$db->commit();
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('job_id' => $job->getIdentity(),'message' => $this->view->translate('You have successfully edit the job.'))));
 		} catch (Exception $e) {
 			$db->rollBack();
 			Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage()));
 		}
 	}

 }
