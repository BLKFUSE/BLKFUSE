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

class Booking_IndexController extends Sesapi_Controller_Action_Standard
{
  public function init() 
  {

  }
  public function browseServicesAction() {
    $allData = array("viewerId" => "", "limit" => $serviceLimit, 'widgetname' => 'browseservices');
    $isService = $this->_getParam("isService", 0);
    $isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $isPrice = $this->_getParam("prince", 0);
    $isServiceName = isset($_POST['servicename']) ? $_POST['servicename'] : '';
    $isProfessionals = $this->_getParam("professional", 0);
   // print_r($isProfessionals);die;
    $form = new Booking_Form_Servicesearch(array('defaultProfileId' => 1));
    $form->populate($_POST);
    $params = $form->getValues();
    $value = array();
    $value['status'] = 1;
    $value['draft'] = "0";
    if (isset($_POST['search']))
      $params['servicename'] = addslashes($_POST['search']);
    $params['service'] = isset($_GET['service_id']) ? $_GET['service_id'] : '';
    if(isset($isSearch))
      $value['alphabet'] = $isSearch;
    $params = array_merge($params, $value);

    if(!empty($isCategory))
      $params['category_id'] = $isCategory;
    if(!empty($isServiceName))
      $params['servicename'] = $isServiceName;
    if(!empty($isPrice))
      $params['price'] = $isPrice;
    if(!empty($isProfessionals))
      $params['professional'] = $isProfessionals;

    if (!empty($isService)) {
      $serviceSQLData = array();
      $serviceSQLData["isService"] = $isService;
      $serviceName = $this->_getParam("servicename", 0);
      $serviceProfessional = $this->_getParam("professional", 0);
      $servicecategory_id = $this->_getParam("category_id", 0);
      $servicesubcat_id = $this->_getParam("subcat_id", 0);
      $servicesubsubcat_id = $this->_getParam("subsubcat_id", 0);
      $price = $this->_getParam("price", 0);

      if (!empty($serviceName))
        $serviceSQLData["servicename"] = $serviceName;
      if (!empty($serviceProfessional))
        $serviceSQLData["professional"] = $serviceProfessional;
      if (!empty($price))
        $serviceSQLData["price"] = $price;
      if (!empty($servicecategory_id))
        $serviceSQLData["category_id"] = $servicecategory_id;
      if (!empty($servicesubcat_id))
        $serviceSQLData["subcat_id"] = $servicesubcat_id;
      if (!empty($servicesubsubcat_id))
        $serviceSQLData["subsubcat_id"] = $servicesubsubcat_id;
    }

    $paginator = Engine_Api::_()->getDbTable('services', 'booking')->servicePaginator(array_merge((($isService) ? $serviceSQLData : $allData),$params));
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result = $this->getServices($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if ($result <= 0)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist services.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
  }

  protected function getProfessional($services){
    $tablename = Engine_Api::_()->getDbtable('professionals', 'booking');
    $select = $tablename->select()->from($tablename->info('name'), array('*'))->where("user_id =?",$services->user_id);
    return $tablename->fetchRow($select);
  }

  public function getServices($paginator) { 
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    $reviews = Engine_Api::_()->getDbTable('reviews', 'booking');
    
    $result = array();
    $counter = 0;
    foreach ($paginator as $services) {
      $user = Engine_Api::_()->getItem('user', $services->user_id);
      $result['services'][$counter] = $services->toArray();
      $professional = $this->getProfessional($services);
      $result['services'][$counter]['Professional_id'] = $this->getProfessional($services)->professional_id;
       $result['services'][$counter]['Professional_name'] = $this->getProfessional($services)->name;
      $result['services'][$counter]['is_available'] = $this->getProfessional($services)->available;
      $result['services'][$counter]['rating'] = $reviews->getRating($services->service_id);
      $result['services'][$counter]['service_image'] = $this->getBaseUrl(true, Engine_Api::_()->storage()->get($services->file_id, '')->getPhotoUrl());

        $result['services'][$counter]['professional_image'] = $this->getBaseUrl(true, 
        ($professional->file_id && Engine_Api::_()->storage()->get($professional->file_id, '') ? 
        Engine_Api::_()->storage()->get($professional->file_id, '')->getPhotoUrl() : 
        ($user->photo_id ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png')));

      $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
      $curArr = Zend_Locale::getTranslationList('CurrencySymbol');
      $result['services'][$counter]['currency'] = $curArr[$currency];
      $result['services'][$counter]['duration'] = $services->duration." ".(($services->timelimit=="h")?"Hour.":"Minutes.");
      
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice') && $viewer->getIdentity() && $professional->available) { 
        $result['services'][$counter]['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$professional->user_id, 'service' => $services->service_id),'booking_general',true));
      }
      $counter++;
    }
    
    return $result;
  }
  public function filtersearchServicesAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $isPrice = isset($_POST['price']) ? $_POST['price'] : '';
    $isServiceName = $this->_getParam('name');
    $isProfessionals = $this->_getParam('professional_name');
    $viewer = Engine_Api::_()->user()->getViewer();
    $form = new Booking_Form_Servicesearch(array(
      "professionalNameActive"=>true,
      "serviceNameActive"=>true,
      "priceActive"=>true,
      "categoryActive"=>true,
    ));

    if ($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields, array('resources_type' => 'booking'));
    } else {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
    }
  }
  public function searchServiceAction()
  {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $isPrice = isset($_POST['price']) ? $_POST['price'] : '';
    $isServiceName = $this->_getParam('name');
    $viewer = Engine_Api::_()->user()->getViewer();
    $form = new Booking_Form_Servicesearch(array('defaultProfileId' => 1));
    $params = $form->getValues();
    $value = array();
    $value['status'] = 1;
    $value['search'] = 1;
    $value['draft'] = "0";
    if(!empty($isServiceName))
      $params['servicename'] = $isServiceName;
    if(!empty($isPrice))
      $params['price'] = $isPrice;
    if(!empty($isProfessionals))
      $params['professional'] = $isProfessionals;

    $paginator = Engine_Api::_()->getDbTable('services', 'booking')->servicePaginator($params);
    $paginator->setItemCountPerPage($this->_getParam('limit', 5));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $classroom = $this->_getParam('page', 1);
    $result = $this->getServices($paginator);

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }
  public function createProfessionalAction()
  {
    $form = new Booking_Form_Service();
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    $values = $form->getValues();
    $db = Engine_Api::_()->getDbTable('services', 'booking')->getAdapter();
    $db->beginTransaction();
    try {  
      $viewer = Engine_Api::_()->user()->getViewer();
      $viewerId = $viewer->getIdentity();
      $settingsTable = Engine_Api::_()->getDbTable('services', 'booking');
      if (!$service_id) {
        $service = $settingsTable->createRow();
      }
      $values['price'] = round($values['price'], 2);
      if ($user_id)
        $viewerId = $user_id;
      $values['user_id'] = $viewerId;
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove'))
        $values['active'] = 1;
      if($service_id)
        $values['file_id'] = $service->file_id;
      $service->setFromArray($values);
      $service->save();
      if (!empty($_FILES["file_id"]['name'])) { 
        $service->file_id = $service->setPhoto($form->file_id);
      }
      $service->save();
      $db->commit();
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      if (empty($values['auth_view'])) {
        $values['view'] = 'everyone';
      }
      $viewMax = array_search($values['view'], $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($service, $role, 'view', ($i <= $viewMax));
      }
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove') == 0) {
        $getAdminSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminSuperAdmins as $getAdminSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminSuperAdmin['user_id']);
          $serviceName= '<a href="'.$this->view-> url(array('module' => 'booking', 'controller' => 'services',"action"=>'enabled','id'=>$service->getIdentity()),'admin_default',true).'">'.$service->getTitle().'</a>';
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $service, 'booking_adminserapl',array('servicename' => $serviceName));
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_adminserapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'service_name' => $service->name,
              'professional_name' => $service->getServiceProfessionalName(),
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $service->getHref(),
            )
          );
        }
      }
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove') == 1) {
        $getAdminSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminSuperAdmins as $getAdminSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $service, 'booking_servautoapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_servautoapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'service_name' => $service->name,
              'professional_name' => $service->getServiceProfessionalName(),
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $service->getHref(),
            )
          );
        }
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $service, 'booking_pro_serv_cre');
        if ($action)
          $activityApi->attachActivity($action, $service);
      }

      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('service_id' => $service->getIdentity(),'message' => $this->view->translate('You have successfully created a Booking.'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }
  public function getProfessionalP($paginator) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    $result = array();
    $counter = 0;
    foreach ($paginator as $professional) {
      $user = Engine_Api::_()->getItem('user',$professional->user_id);
      if($professional->available){
      $result['professionals'][$counter] = $professional->toArray();
      $service = Engine_Api::_()->getItem('booking_service', $professional->professional_id);
      $result['professionals'][$counter]['service_name']= $service->name;
      $result['professionals'][$counter]['professional_id'] = $professional->professional_id;
         $result['professionals'][$counter]['is_available '] = $professional->available;


      $result['professionals'][$counter]['professional_image'] = $this->getBaseUrl(true, 
        ($professional->file_id && Engine_Api::_()->storage()->get($professional->file_id, '') ? 
        Engine_Api::_()->storage()->get($professional->file_id, '')->getPhotoUrl() : 
        ($user->photo_id ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png')));
      


      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice') && $viewer->getIdentity() && $professional->available) { 
        $result['professionals'][$counter]['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$professional->user_id),'booking_general',true));
      }
      
      $counter++;
    }
  }
    return $result;
  }

  public function searchProfessionalAction()
  {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $isProfessionals = $this->_getParam('name');
        //print_r($isProfessionals);die;
    $viewer = Engine_Api::_()->user()->getViewer();
    $form = new Booking_Form_Servicesearch(array('defaultProfileId' => 1));
    $params = $form->getValues();
    $value = array();
    $value['status'] = 1;
    $value['search'] = 1;
    $value['draft'] = "0";

    if(!empty($isProfessionals))
      $params['professionalName'] = $isProfessionals;

    $paginator = Engine_Api::_()->getDbTable('professionals','booking')->getProfessionalPaginator($params);
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result = $this->getProfessionalP($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }
  public function browseProfessionalsAction() { 
    $allData = array("viewerId" => "", "limit" => $serviceLimit, 'widgetname' => 'browseprofessionals');
    $isProfessionals = $this->_getParam("isProfessionals", 0);
    $isServiceName = $this->_getParam('service_name');
    $isProfessionalName = $this->_getParam('professional_name');
    $isProfessionalRating = isset($_POST['rating']) ? $_POST['rating'] : '';
    $isAvailable = isset($_POST['available']) ? $_POST['available'] : '';
    $isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $isLocation = isset($_POST['location']) ? $_POST['location'] : '';
    $form = new Booking_Form_Professionalsearchform(array('defaultProfileId' => 1));
    $form->populate($_POST);
    $params = $form->getValues();
    $value = array();
    $value['status'] = 1;
    $value['draft'] = "0";
    if (isset($_POST['search']))
      $params['serviceName'] = addslashes($_POST['search']);
    $params['name'] = isset($_GET['professional_id']) ? $_GET['professional_id'] : '';
    if(isset($isSearch))
      $value['alphabet'] = $isSearch;
     if(!empty($isServiceName))
    $params['serviceName'] = $isServiceName;
    if(!empty($isProfessionalName))
    $params['professionalName'] = $isProfessionalName;
    if (!empty($isAvailable)) 
      $params['availability'] = $isAvailable;
    if (!empty($isProfessionalRating)) 
      $params['rating'] = $isProfessionalRating;
    if (!empty($isCategory)) 
      $params['category_id'] = $isCategory;
     if (!empty($isLocation)) 
      $params['location'] = $isLocation;
    $params = array_merge($params, $value);
    if (!empty($isProfessionals)) { 
      $serviceSQLData = array();
      $serviceSQLData["isProfessionals"] = $isProfessionals;
      $serviceName = $this->_getParam("servicename", 0);
      $serviceProfessional = $this->_getParam("professional", 0);
      $servicecategory_id = $this->_getParam("category_id", 0);
      $servicesubcat_id = $this->_getParam("subcat_id", 0);
      $servicesubsubcat_id = $this->_getParam("subsubcat_id", 0);
      $rating = $this->_getParam("rating", 0);
      $available = $this->_getParam("available", 0);

      if (!empty($serviceProfessional))
        $serviceSQLData["professional"] = $serviceProfessional;
      if (!empty($rating))
        $serviceSQLData["rating"] = $rating;
      if (!empty($available))
        $serviceSQLData["available"] = $available;
      if (!empty($servicecategory_id))
        $serviceSQLData["category_id"] = $servicecategory_id;
      if (!empty($servicesubcat_id))
        $serviceSQLData["subcat_id"] = $servicesubcat_id;
      if (!empty($servicesubsubcat_id))
        $serviceSQLData["subsubcat_id"] = $servicesubsubcat_id;
    }
    $paginator = Engine_Api::_()->getDbTable('professionals','booking')->getProfessionalPaginator(array_merge((($isProfessionals) ? $serviceSQLData : $allData),$params));
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result = $this->getProfessionalP($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if ($result <= 0)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist events.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
  }
  public function filterProfessionalAction() {
    $isSearch = 1;
    $isServiceName = $this->_getParam('name');
    $isProfessionalName = $this->_getParam('professionalname');
    $isProfessionalRating = isset($_POST['rating']) ? $_POST['rating'] : '';
    $isAvailable = isset($_POST['available']) ? $_POST['available'] : '';
    $isCategory = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
    $form = new Booking_Form_Professionalsearchform(array("professionalNameActive"=>true,
      "serviceNameActive"=>true,
      "ratingActive"=>true,
      "availabilityActive"=>true,
      "locationActive"=>true,
      "categoryActive"=>true));
    $params = $form->getValues();
    $value = array();
    $value['status'] = 1;
    $value['search'] = 1;
    $value['draft'] = "0";

    if (isset($params['search']))
      $params['text'] = addslashes($params['search']);

    if(!empty($isServiceName))
      $params['servicename'] = $isServiceName;
    if(isset($isSearch))
      $value['alphabet'] = $isSearch;
    $params = array_merge($params, $value);

    if(!empty($isProfessionalName))
    $params['name'] = $isProfessionalName;
    if (!empty($isAvailable)) 
      $params['availability'] = $isAvailable;
    if (!empty($isProfessionalRating)) 
      $params['rating'] = $isProfessionalRating;
    if (!empty($isCategory)) 
      $params['category_id'] = $isCategory;
    if ($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields, array('resources_type' => 'booking'));
    } else {

      $paginator = Engine_Api::_()->getDbTable('professionals','booking')->getProfessionalPaginator($params);
      $paginator->setItemCountPerPage($this->_getParam('limit', 10));
      $paginator->setCurrentPageNumber($this->_getParam('page', 1));
      $result = $this->getProfessionalP($paginator);
      $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
      $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
      $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
      $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
      if ($result <= 0)
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist events.'), 'result' => array()));
      else
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
    }
  }
  public function serviceViewAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
     $viewerId = $viewer->getIdentity();
    $id = $this->_getParam('service_id', null);
    $service = Engine_Api::_()->getItem('booking_service', $id); 
    $result['service'] = $service->toArray();
    $user = Engine_Api::_()->getItem('user', $service->user_id);
    $professional = $this->getProfessional($service);
    $reviews = Engine_Api::_()->getDbTable('reviews', 'booking');
    $result['service']['rating'] = $reviews->getRating($service->service_id);
    $result['service']['professional_id'] = $this->getProfessional($service)->professional_id;
    $result['service']['professional_name'] = $this->getProfessional($service)->name;
    $result['service']['service_image'] = $this->getBaseUrl(true, Engine_Api::_()->storage()->get($service->file_id, '')->getPhotoUrl());
      $result['service']['professional_image'] = $this->getBaseUrl(true, 
        ($professional->file_id && Engine_Api::_()->storage()->get($professional->file_id, '') ? 
        Engine_Api::_()->storage()->get($professional->file_id, '')->getPhotoUrl() : 
        ($user->photo_id ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png')));
    
    $result['service']['user_image'] = $this->getBaseUrl(true, 
      !empty($user->photo_id) ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
     
    $result['service']['is_content_like'] = (bool) Engine_Api::_()->getDbTable('servicelikes', 'booking')->isUserLike(array('user_id' => $viewer->getIdentity(), 'service_id' => $service->service_id));
    $result['service']['content_like_count'] = (int) $service->like_count;
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.fav', 1)){
      $result['service']['is_content_favourite'] = (bool)Engine_Api::_()->getDbTable('servicefavourites', 'booking')->isUserFavourite(array('user_id' => $viewer->getIdentity(), 'service_id' => $service->service_id));
      $result['service']['content_favourite_count'] = (int) $service->favourite_count;
    }
      $result['service']['is_available'] = $this->getProfessional($service)->available;
    $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
    $curArr = Zend_Locale::getTranslationList('CurrencySymbol');
    $result['service']['currency'] = $curArr
    [$currency];
    $result['service']['duration'] = $service->duration." ".(($service->timelimit=="h")?"Hour.":"Minutes.");
    
    if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice')  && $professional->available) { 
        $result['service']['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$professional->user_id, 'service' => $service->service_id),'booking_general',true));
    }
      $optionCounter = 0;
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.service.share', 1) == 1 || Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.service.share', 1) == 2 ){
      $result["share"]["imageUrl"] = $this->getBaseUrl(false, $service->getPhotoUrl());
      $result["share"]["url"] = $this->getBaseUrl(false,$service->getHref());
      $result["share"]["title"] = $service->getTitle();
      $result["share"]["description"] = strip_tags($service->getDescription());
      $result["share"]["setting"] = $service->getType();
      $result["share"]['urlParams'] = array(
        "type" => $service->getType(),
        "id" => $service->getIdentity()
      );
    }
      if($service->user_id == $viewerId) {
      $result['options'][$optionCounter]['name'] = 'edit';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Edit');
      $optionCounter++;

      $result['options'][$optionCounter]['name'] = 'delete';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Delete');
      $optionCounter++;
    }

      
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
  }
  public function createReviewAction() { 
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $serviceId = $this->_getParam("service_id");
    $review_id = $this->_getParam("review_id");
    $form = new Booking_Form_Review_Create();
    if ($review_id) {
      $reviews = Engine_Api::_()->getItem('booking_review', $review_id);
      if(empty($reviews)){
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
      }
      $form->populate($reviews->toArray());
      $form->rate_value->setValue($reviews['rating']);
      $form->submit->setLabel('Save Changes');
      $form->setTitle('Edit reviews');
    }
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if(!$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $db = Engine_Api::_()->getDbTable('reviews', 'booking')->getAdapter();
      $db->beginTransaction();
      try {
        $formValues = $form->getValues();
        $reviewsTable = Engine_Api::_()->getDbTable('reviews', 'booking');
        if (!$review_id) {
          $reviews = $reviewsTable->createRow();
          $formValues['rating'] = $formValues['rate_value'];
          $formValues['module_name'] = 'booking';
          $formValues['service_id'] = $serviceId;
          $formValues['user_id'] = $viewerId;
          $reviews->setFromArray($formValues);
        } else{
          $reviews->rating = $formValues['rate_value'];
          $reviews->title = $formValues['title'];
          $reviews->pros = $formValues['pros'];
          $reviews->cons = $formValues['cons'];
          $reviews->description = $formValues['description'];
          $reviews->recommended = $formValues['recommended'];
        }
        $reviews->save();
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('review_id' => $reviews->getIdentity(),'message' => $this->view->translate('You have successfully created .'))));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
  }
 
  public function serviceViewreviewAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
      $viewerId = $viewer->getIdentity();
    $serviceId = $this->_getParam('service_id', null);
    if(!$serviceId)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Provide valid serviceId.'), 'result' => array()));
  $service = Engine_Api::_()->getItem('booking_service',$serviceId);
     if(!empty($serviceId))
      $params['service_id'] = $serviceId;    
    if(!$service){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    }
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.review', 1)){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }
    
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.review', 1)) {
      $allowedCreate = true;
    } else {
      if ($service->service_id == $viewer->getIdentity())
        $allowedCreate = false;
      else
        $allowedCreate = true;
    }
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
      $table = Engine_Api::_()->getDbtable('reviews', 'booking');
    $isReview = $table->isReviewAvailable(array('service_id' => $service->getIdentity(),'user_id' =>$viewer->getIdentity(), 'column_name' => 'review_id'));
    if($viewer->getIdentity() && Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.review', 1) && $allowedCreate){
      if(!$isReview){
        $result['button']['label'] = $this->view->translate('Write a Review');
        $result['button']['name'] = 'createreview';
      }
      if($isReview){
        $result['button']['label'] = $this->view->translate('Update Review');
        $result['button']['name'] = 'updatereview';
        $result['button']['review_id'] = $table->isReviewAvailable(array('service_id' => $service->getIdentity(),'user_id' =>$viewer->getIdentity(), 'column_name' => 'review_id'));
      }
    }   
    

    $paginator = Zend_Paginator::factory(Engine_Api::_()->getDbTable('reviews', 'booking')->getReviews($params));
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result['reviews'] = $this->getServicesReview($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if ($result <= 0)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist events.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams)); 
  }
  protected function getServicesReview($paginator)
  {
//     $viewer = Engine_Api::_()->user()->getViewer();
//     $viewerId = $viewer->getIdentity();
//     $result = array();
//     $counter = 0;
//     foreach ($paginator as $reviews) {
//       $result['reviews'][$counter]= $reviews->toArray();
//       $counter++;
//     }
//     return $result;
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $result = array();
    $counter = 0;
    foreach ($paginator as $review) {
      $owner = $review->getOwner();
      $result[$counter]= $review->toArray();
      $result[$counter]['owner']['id'] = $owner->getIdentity();
      $result[$counter]['owner']['Guid'] = $owner->getGuid();
      $result[$counter]['owner']['title'] = $owner->getTitle();
      $result[$counter]['owner']['images'] = $this->getBaseUrl(true, (!empty($owner->getPhotoUrl())) ? $owner->getPhotoUrl('thumb.icon') : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
      if($viewerId == $owner->getIdentity()) {
        $menuoptions= array();
        $menucounter = 0;
        $menuoptions[$menucounter]['name'] = "edit";
        $menuoptions[$menucounter]['label'] = $this->view->translate("Edit");
        $menucounter++;
        $menuoptions[$menucounter]['name'] = "delete";
        $menuoptions[$menucounter]['label'] = $this->view->translate("Delete");
        $menucounter++;
        $result[$counter]['menus'] = $menuoptions;
      }
      $counter++;
    }
    return $result;
    
  }
  public function likeAction() { 
    $count = "";
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $professional_id = $this->_getParam('professional_id', null);
    if(!$professional_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $tableLike = Engine_Api::_()->getDbTable('likes', 'booking');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
    ->from($tableMainLike)
    ->where('professional_id = ?', $professional_id)
    ->where('user_id = ?', $viewerId);
    $result = $tableLike->fetchRow($select);
    if ($result) {
        //delete like
      $db = $tableLike->getAdapter();
      $db->beginTransaction();
      try {
          //delete like if already exist.
        $tableLike->delete(array("professional_id = ?" => $professional_id, "user_id = ?" => $viewerId));
        $temp['data']['message'] = $this->view->translate('Successfully Unliked.');
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    } else {
      $db = $tableLike->getAdapter();
      $db->beginTransaction();
      try {
        $tableLikes = $tableLike->createRow();
        $formValues['professional_id'] = $professional_id;
        $formValues['user_id'] = $viewerId;
        $tableLikes->setFromArray($formValues);
        $tableLikes->save();
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully liked.');
        $professional = Engine_Api::_()->getItem('professional', $professional_id);
        $user = Engine_Api::_()->getItem('user', $professional->user_id);
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $viewer, 'booking_userlikepro');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
    $select = $tableLike->select()
    ->from($tableMainLike, array('total' => new Zend_Db_Expr('COUNT(professional_id)')))
    ->where('professional_id = ?', $professional_id);
    $count = $tableLike->fetchRow($select);
    $item = Engine_Api::_()->getItem('professional', $professional_id);
    $item->like_count = $count['total'];
    $item->save();
    if ($result)
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'booking_userlikepro');
    /*echo $count['total'];
    die();
  */  $temp['data']['like_count'] = $item->like_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
  }
  public function favouriteAction() { 
    $count = "";
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $professional_id = $this->_getParam('professional_id', null);
    if(!$professional_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $tablefavourite = Engine_Api::_()->getDbTable('favourites', 'booking');
    $tableMainLike = $tablefavourite->info('name');
    $select = $tablefavourite->select()
    ->from($tableMainLike)
    ->where('professional_id = ?', $professional_id)
    ->where('user_id = ?', $viewerId);
    $result = $tablefavourite->fetchRow($select);
    if ($result) {
        //delete favourite
      $db = $tablefavourite->getAdapter();
      $db->beginTransaction();
      try {
          //delete favourite 
        $tablefavourite->delete(array("professional_id = ?" => $professional_id, "user_id = ?" => $viewerId));
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully Unfavourite.');

      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    } else{
      $db = $tablefavourite->getAdapter();
      $db->beginTransaction();
      try {
          //insert favourite
        $tablefavourites = $tablefavourite->createRow();
        $formValues['professional_id'] = $professional_id;
        $formValues['user_id'] = $viewerId;
        $tablefavourites->setFromArray($formValues);
        $tablefavourites->save();
        /*$itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array($professional_id . '= ?' => $user_id));*/
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully favourite.');
          //notifcation & mail When someone mark favourite for professional 
        $professional = Engine_Api::_()->getItem('professional', $professional_id);
        $user = Engine_Api::_()->getItem('user', $professional->user_id);
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'booking_userfavpro');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
    $select = $tablefavourite->select()
    ->from($tableMainLike, array('total' => new Zend_Db_Expr('COUNT(professional_id)')))
    ->where('professional_id = ?', $professional_id);
    $count = $tablefavourite->fetchRow($select);
    $item = Engine_Api::_()->getItem('professional', $professional_id);
    $item->favourite_count = $count['total'];
  // print_r($item->favourite_count);die;
    $item->save();
    if (!$result)
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'booking_userfavpro');
    $temp['data']['favourite_count'] = $item->favourite_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
  }
//   public function serviceReviewAction(){
//     $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
//     $viewer = Engine_Api::_()->user()->getViewer();
//     $serviceId = $this->_getParam('service_id', null);
//     if(!$serviceId)
//       Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Provide valid serviceId.'), 'result' => array()));
//     if(!empty($serviceId))
//       $params['service_id'] = $serviceId;
//     $paginator = Zend_Paginator::factory(Engine_Api::_()->getDbTable('reviews', 'booking')->getReviews($params));
//     $paginator->setItemCountPerPage($this->_getParam('limit', 10));
//     $paginator->setCurrentPageNumber($this->_getParam('page', 1));
//     $result = $this->getReview($paginator);
//     $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
//     $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
//     $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
//     $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
//     if ($result <= 0)
//       Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist events.'), 'result' => array()));
//     else
//       Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams)); 
//   }
//   public function getReview($paginator)
//   {
//     $viewer = Engine_Api::_()->user()->getViewer();
//     $viewerId = $viewer->getIdentity();
//     $result = array();
//     $counter = 0;
//     foreach ($param as $reviews) {
//       $result['reviews'][$counter]= $reviews->toArray();
//       $menuoptions= array();
//       $menucounter = 0;
//       $menuoptions[$menucounter]['name'] = "edit";
//       $menuoptions[$menucounter]['label'] = $this->view->translate("Edit");
//       $menucounter++;
//       $menuoptions[$menucounter]['name'] = "delete";
//       $menuoptions[$menucounter]['label'] = $this->view->translate("Delete");
//       $menucounter++;
//       $result['reviews'][$counter]['menus'] = $menuoptions;
//       $counter++;
//     }
//     return $result;
//   }
  public function serviceLikeAction()
  {
    $count = "";
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $service_id = $this->_getParam('service_id', null);
    if(!$service_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $tableLike = Engine_Api::_()->getDbTable('servicelikes', 'booking');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
    ->from($tableMainLike)
    ->where('service_id = ?', $service_id)
    ->where('user_id = ?', $viewerId);
    $result = $tableLike->fetchRow($select);
    if ($result) {
        //update like
      $db = $tableLike->getAdapter();
      $db->beginTransaction();
      try {
          //insert like
        $tableLike->delete(array("service_id = ?" => $service_id, "user_id = ?" => $viewerId));
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully unliked.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    } else {
      $db = $tableLike->getAdapter();
      $db->beginTransaction();
      try {
          //insert like
        $tableLikes = $tableLike->createRow();
        $formValues['service_id'] = $service_id;
        $formValues['user_id'] = $viewerId;
        $tableLikes->setFromArray($formValues);
        $tableLikes->save();
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully liked.');
          //notifcation When someone like professional service.  
        $service = Engine_Api::_()->getItem('booking_service', $service_id);
        $user = Engine_Api::_()->getItem('user', $service->user_id);
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'booking_userlikeserv',array('servicename'=>$service));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
    $select = $tableLike->select()
    ->from($tableMainLike, array('total' => new Zend_Db_Expr('COUNT(service_id)')))
    ->where('service_id = ?', $service_id);
    $count = $tableLike->fetchRow($select);
    $item = Engine_Api::_()->getItem('booking_service', $service_id);
    $item->like_count = $count['total'];
    $item->save();
    //print_r($item);die;

    if (!$result)
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'booking_userlikeser');
    /*echo $count['total'];
    die();*/
    $temp['data']['like_count'] = $item->like_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
  }
  public function serviceFavouriteAction()
  {
    $count = "";
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $service_id = $this->_getParam('service_id', null);
    if(!$service_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $tableFavourite = Engine_Api::_()->getDbTable('servicefavourites', 'booking');
    $tableMainFavourite = $tableFavourite->info('name');
    $select = $tableFavourite->select()
    ->from($tableMainFavourite)
    ->where('service_id = ?', $service_id)
    ->where('user_id = ?', $viewerId);
    $result = $tableFavourite->fetchRow($select);
    if ($result) {
        //update Favourite
      $db = $tableFavourite->getAdapter();
      $db->beginTransaction();
      try {
          //insert Favourite
        $tableFavourite->delete(array("service_id = ?" => $service_id, "user_id = ?" => $viewerId));
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully unfavourite.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    } else {
      $db = $tableFavourite->getAdapter();
      $db->beginTransaction();
      try {
          //insert Favourite
        $tableFavourites = $tableFavourite->createRow();
        $formValues['service_id'] = $service_id;
        $formValues['user_id'] = $viewerId;
        $tableFavourites->setFromArray($formValues);
        $tableFavourites->save();
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully favourite.');
          //notifcation When someone marked professional service as favourite.  
        $service = Engine_Api::_()->getItem('booking_service', $service_id);
        $user = Engine_Api::_()->getItem('user', $service->user_id);
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'booking_userfavserv', array('servicename' => $service));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
    $select = $tableFavourite->select()
    ->from($tableMainFavourite, array('total' => new Zend_Db_Expr('COUNT(service_id)')))
    ->where('service_id = ?', $service_id);
    $count = $tableFavourite->fetchRow($select);
    $item = Engine_Api::_()->getItem('booking_service', $service_id);
    $item->favourite_count = $count['total'];
    $item->save();
    if (!$result)
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'booking_userfavser');

    $temp['data']['favourite_count'] = $item->favourite_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
  }
  public function followAction()
  {
    $count = "";
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $professional_id = $this->_getParam('professional_id', null);
    if(!$professional_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $tablefollow = Engine_Api::_()->getDbTable('follows', 'booking');
    $tableMainLike = $tablefollow->info('name');
    $select = $tablefollow->select()
    ->from($tableMainLike)
    ->where('professional_id = ?', $professional_id)
    ->where('user_id = ?', $viewerId);
    $result = $tablefollow->fetchRow($select);
    if ($result) {
        //delete follow
      $db = $tablefollow->getAdapter();
      $db->beginTransaction();
      try {
          //delete follow if already exist.
        $tablefollow->delete(array("professional_id = ?" => $professional_id, "user_id = ?" => $viewerId));
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully Unfollow.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    } else {
      $db = $tablefollow->getAdapter();
      $db->beginTransaction();
      try {
          //insert like
        $tablefollows = $tablefollow->createRow();
        $formValues['professional_id'] = $professional_id;
        $formValues['user_id'] = $viewerId;
        $tablefollows->setFromArray($formValues);
        $tablefollows->save();
        $db->commit();
        $temp['data']['message'] = $this->view->translate('Successfully follow.');

          //notifcation When someone follow a Professional.
        $professional = Engine_Api::_()->getItem('professional', $professional_id);
        $user = Engine_Api::_()->getItem('user', $professional->user_id);
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $user, 'booking_userfollowpro');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
    $select = $tablefollow->select()
    ->from($tableMainLike, array('total' => new Zend_Db_Expr('COUNT(professional_id)')))
    ->where('professional_id = ?', $professional_id);
    $count = $tablefollow->fetchRow($select);
    $item = Engine_Api::_()->getItem('professional', $professional_id);
    $item->follow_count = $count['total'];
    $item->save();
    $activityApi = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'booking_userfollowpro');
    $temp['data']['follow_count'] = $item->follow_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
  }
  public function reviewViewAction(){
    $viewer = Engine_Api::_()->user()->getViewer();
    $review_id = $this->_getParam('review_id', null);
    if(!$review_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.review', 1))
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));

    $review = Engine_Api::_()->getItem('booking_review', $review_id);
    $service = Engine_Api::_()->getItem('booking_service', $review->service_id);
    if(!$review || !$service)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
     if (!$viewer->isSelf($review->getOwner())) {
        $review->view_count++;
        $review->save();
    }
    $params = array();
    $result = array();
    /*----------------make data-----------------------------*/
    $counter = 0;
    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $result = $review->toArray();
    $owner = $review->getOwner();
    $result['service']['images'] = $this->getBaseUrl(true, $service->getPhotoUrl());
    $result['service']['title'] = $service->getTitle();
    $result['service']['Guid'] = $service->getGuid();
    $result['service']['id'] = $service->getIdentity();
    $result['owner']['id'] = $owner->getIdentity();
    $result['owner']['Guid'] = $owner->getGuid();
    $result['owner']['title'] = $owner->getTitle();
    $result['owner']['images'] = $this->getBaseUrl(true, (!empty($owner->getPhotoUrl())) ? $owner->getPhotoUrl('thumb.icon') : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
  
    $optionCounter = 0;
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.show.report', 1) && $viewerId && $viewerId != $owner->getIdentity()){
      $result['options'][$optionCounter]['name'] = 'report';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Report');
      $optionCounter++;
    }
     if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1) && $viewerId){
      $result['options'][$optionCounter]['name'] = 'share';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Share');
      $optionCounter++;
      $result["share"]["url"] = $this->getBaseUrl(false,$review->getHref());
        $result["share"]["title"] = $review->getTitle();
        $result["share"]["description"] = strip_tags($review->getDescription());
        $result["share"]["setting"] = Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1);
        $result["share"]['urlParams'] = array(
          "type" => $review->getType(),
          "id" => $review->getIdentity()
        );
    }

     if($review->user_id == $viewerId) {
      $result['options'][$optionCounter]['name'] = 'edit';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Edit Review');
      $optionCounter++;

      $result['options'][$optionCounter]['name'] = 'delete';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Delete Review');
      $optionCounter++;
    }

   
    /*----------------make data-----------------------------*/
    $data['review'] = $result;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $data)));
  }
  public function reviewEditAction() 
  { 
    $formValues = array(); 
    $serviceId = $this->_getParam("service_id");
    $review_id = $this->_getParam("review_id");
    $this->view->viewerId = $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    $this->view->form = $form = new Booking_Form_Review_Create();
    if ($review_id) {
      $reviews = Engine_Api::_()->getItem('booking_review', $review_id);
      $form->populate($reviews->toArray());
      $form->rate_value->setValue($reviews['rating']);
      $form->submit->setLabel('Save Changes');
      $form->setTitle('Edit reviews');
    }
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    $db = Engine_Api::_()->getDbTable('reviews', 'booking')->getAdapter();
    $db->beginTransaction();
    try {
      $formValues = $form->getValues();
      $reviewsTable = Engine_Api::_()->getDbTable('reviews', 'booking');
      if (!$review_id) {
        $reviews = $reviewsTable->createRow();
        $formValues['rating'] = $formValues['rate_value'];
        $formValues['module_name'] = 'booking';
        $formValues['service_id'] = $serviceId;
        $formValues['user_id'] = $viewerId;
        $reviews->setFromArray($formValues);
      } else {
        $reviews->rating = $formValues['rate_value'];
        $reviews->title = $formValues['title'];
        $reviews->pros = $formValues['pros'];
        $reviews->cons = $formValues['cons'];
        $reviews->description = $formValues['description'];
        $reviews->recommended = $formValues['recommended'];
      }

      $reviews->save();
      $db->commit();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('review_id' => $reviews->getIdentity(),'message' => $this->view->translate('You have successfully Edit .'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }
  public function reviewDeleteAction() 
  {
    $review_id = $this->_getParam('review_id');
    if (!$this->getRequest()->isPost()) {
     Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'method is not post'));
    }
   $review = Engine_Api::_()->getItem('booking_review', $this->_getParam('review_id'));

    if(empty($review))
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'data not found'));
    /* $review->delete();*/
    if ($this->getRequest()->isPost()) {
      $db = $review->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $review->delete();
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('review_id' => $review->getIdentity(),'message' => $this->view->translate('You have successfully delete the review.'))));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
  }
  public function professionalViewAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
       $viewerId = $viewer->getIdentity();
        $professional_id = $this->_getParam('professional_id', null);
    if(!$professional_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $professional = Engine_Api::_()->getItem('professional',$professional_id);
    if(!$professional)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
      
    $result['professional'] = $professional->toArray();
    $user = Engine_Api::_()->getItem('user', $professional->user_id);
      $result['professional']['is_available '] = $professional->available;
    $result['professional']['professional_id'] = $this->getProfessional($professional)->professional_id;
    $result['professional']['professional_image'] = $this->getBaseUrl(true, 
        ($professional->file_id && Engine_Api::_()->storage()->get($professional->file_id, '') ? 
        Engine_Api::_()->storage()->get($professional->file_id, '')->getPhotoUrl() : 
        ($user->photo_id ? $user->getPhotoUrl() : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png')));
if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice') && $professional->available) { 
        $result['professional']['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$professional->user_id),'booking_general',true));
      }

    $currency['professional'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
    $result['professional']['is_content_like'] = (bool) Engine_Api::_()->getDbTable('likes', 'booking')->isUserLike(array('user_id' => $viewer->getIdentity(), 'professional_id' => $professional->professional_id));
    $result['professional']['content_like_count'] = (int) $professional->like_count;
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.fav', 1)){
      $result['professional']['is_content_favourite'] = (bool)Engine_Api::_()->getDbTable('favourites', 'booking')->isUserFavourite(array('user_id' => $viewer->getIdentity(), 'professional_id' => $professional->professional_id));
      $result['professional']['content_favourite_count'] = (int) $professional->favourite_count;
    }
     if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.follow', 1)){
      $result['professional']['is_content_follow'] = (bool)Engine_Api::_()->getDbTable('follows', 'booking')->isUserFollow(array('user_id' => $viewer->getIdentity(), 'professional_id' => $professional->professional_id));
      $result['professional']['content_follow_count'] = (int) $professional->follow_count;
    }
    $tabcounter = 0;
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.allow.review', 1)){
      $result['menus'][$tabcounter]['name'] = 'review';
      $result['menus'][$tabcounter]['label'] = $this->view->translate('Reviews');
      $tabcounter++;
    }   
    $result['menus'][$tabcounter]['name'] = 'service';
    $result['menus'][$tabcounter]['label'] = $this->view->translate('Services');
    $tabcounter++;
    
    $counterOption = 0;
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.report', 1) && $viewer->getIdentity() != $professional->user_id ){
      $result['options'][$counterOption]['name'] = 'report'; 
      $result['options'][$counterOption]['label'] = $this->view->translate('Report'); 
      $counterOption++;
    }
  
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1) == 1 || Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1) == 2 ){
      $result["share"]["imageUrl"] = $this->getBaseUrl(false, $professional->getPhotoUrl());
      $result["share"]["url"] = $this->getBaseUrl(false,$professional->getHref());
      $result["share"]["title"] = $professional->getTitle();
      $result["share"]["description"] = strip_tags($professional->getDescription());
      $result["share"]["setting"] = $professional->getType();
      $result["share"]['urlParams'] = array(
        "type" => $professional->getType(),
        "id" => $professional->getIdentity()
      );
    }
    
     $optionCounter = 0;
 
    if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice') && $viewer->getIdentity() && $professional->available) { 
      $result['professional']['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$professional->user_id),'booking_general',true));
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
  }
  public function professionalContactAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('professional_id', null);
    $professional = Engine_Api::_()->getItem('professional', $id);
    
    $result= $professional->toArray();
  //print_r($result);die;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
  }
  public function menuAction()
  {
    $menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('booking_main', array());
    $menu_counter = 0;
    foreach ($menus as $menu) {
      $class = end(explode(' ', $menu->class));
      $result_menu[$menu_counter]['label'] = $this->view->translate($menu->label);
      $result_menu[$menu_counter]['action'] = $class;
      $result_menu[$menu_counter]['isActive'] = $menu->active;
      $menu_counter++;
    }
    $result['menus'] = $result_menu;
    $result['isProfessional'] = (bool)(!empty(Engine_Api::_()->getDbTable('professionals', 'booking')->isProfessional()) ? 1 : 0);
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result)));
  }

  public function serviceInfoAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('service_id', null);
    $service = Engine_Api::_()->getItem('booking_service', $id); 
    $result= $service->toArray();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
  }
  public function professionalInfoAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $viewer = Engine_Api::_()->user()->getViewer();
    $id = $this->_getParam('professional_id', null);
    $professional = Engine_Api::_()->getItem('professional', $id);
    $result= $professional->toArray();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
  }
  public function professionalMessageAction() 
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $serviceId = $this->_getParam("service_id");
    $message_id = $this->_getParam("review_id");
    $body = $this->_getParam('body');
    $form = $form = new Messages_Form_Compose();
    $viewer = Engine_Api::_()->user()->getViewer();
    $values = $form->getValues();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $form = $form = new Messages_Form_Compose();
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    if ($message_id) {
      $messages = Engine_Api::_()->getItem('messages_messages', $message_id);
      $form->populate($messages->toArray());
      $form->description->setValue($messages['description']);
      $form->submit->setLabel('Save Changes');
      $form->setTitle('Edit messages');
    }

    if ($this->getRequest()->isPost() && $form->isValid($this->_getAllParams())) {
      $db = Engine_Api::_()->getDbTable('messages','messages')->getAdapter();
      $db->beginTransaction();
      try{
        $formValues = $form->getValues();
        $reviewsTable = Engine_Api::_()->getDbTable('messages','messages');
        if (!$message_id) {
          $messages = $reviewsTable->createRow();
          $formValues['body'] = $formValues['description'];
          $formValues['module_name'] = 'booking';
          $formValues['service_id'] = $serviceId;
          $formValues['user_id'] = $viewerId;
          $messages->setFromArray($formValues);
        } else {

          $messages->title = $formValues['title'];
          $messages->body = $formValues['body'];

        } 
        $messages->save();
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('service_id' => $messages->getIdentity(),'message' => $this->view->translate('You have successfully created .'))));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
  }
  public function getProfessionalA($paginator) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    $result = array();
    $counter = 0;
    foreach ($paginator as $professional) {
      $user = Engine_Api::_()->getItem('user',$professional->professional_id);
      $result['professional'][$counter] = $professional->toArray();
      $result['professional'][$counter]['professional_id'] = $this->getProfessional($professional)->professional_id;
      $result['professional'][$counter]['professional_id'] = $this->getProfessional($professional)->professional_id;
      $counter++;
    }
    return $result;
  }
  public function serviceSettingAction() {
    $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
    $id = $this->_getParam('professional_id', null);
    $isServiceName = isset($_POST['name']) ? $_POST['name'] : '';
    $professional = Engine_Api::_()->getItem('professional', $id);
    $viewer = Engine_Api::_()->user()->getViewer();
    $form = new Booking_Form_Servicesearch(array('defaultProfileId' => 1, 'contentId' => $id));
    $params = $form->getValues();
    $params = array();
    $params['status'] = 1;
    $params['search'] = 1;
    $params['draft'] = "0";
    if (isset($params['search']))
      $params['text'] = addslashes($params['search']);
    $params['service'] = isset($_GET['service_id']) ? $_GET['service_id'] : '';
    if(isset($isSearch))
      $value['alphabet'] = $isSearch;
    if(!empty($isServiceName))
      $params['servicename'] = $isServiceName;

    $paginator = Engine_Api::_()->getDbTable('services', 'booking')->servicePaginator($params);
    $paginator->setItemCountPerPage($this->_getParam('limit', 5));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $classroom = $this->_getParam('page', 1);
    $result['services'] = $this->getServices($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));

  }
  public function deleteServiceAction()
  {
    $service_id = $this->_getParam('service_id');
    if($this->_getParam('service_id') && $this->_getParam('professional_id')){
      $dateInAppointments=Engine_Api::_()->getDbtable('appointments', 'booking')->isServiceInAppointments(array("professional_id"=>$this->_getParam('professional_id'),"service_id"=>$this->_getParam('service_id')));
      if($dateInAppointments['service_id']){
        $this->view->dateInAppointments = true;
      }
    }
    if (!$this->getRequest()->isPost()) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'method is not post'));
  }
  $service = Engine_Api::_()->getItem('booking_service', $this->_getParam('service_id'));
  if(empty($service))
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'data not found'));

    /* $service->delete();*/
    if ($this->getRequest()->isPost()) {
      $db = $service->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $service->delete();
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('service_id' => $service->getIdentity(),'message' => $this->view->translate('You have successfully delete the service.'))));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
    }
  }

/*public function indexAppointmentsAction() 
{

//die('tets'); 

    $viewer = Engine_Api::_()->user()->getViewer();
    $form = newBooking_Form_Admin_AppointmentSettings();
    $isProfessional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessionalAvailable(array("user_id" => $viewer->getIdentity()));
    !empty($isProfessional->professional_id)

    $params['servicename'] = $isServiceName;
    if ($this->_getParam('getForm')) {
          $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
          $this->generateFormFields($formFields, array('resources_type' => 'booking'));
      } else {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }
}
*/
  public function appointmentsAction() 
  { 
    //$professionalId = $this->_getParam('professional_id',null);
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $value['professional_id'] =  $professionalId;
    $value['popularCol'] = isset($popularCol) ? $popularCol : '';
    $value['fixedData'] = isset($fixedData) ? $fixedData : '';
    $value['draft'] = 0;
    $value['search'] = 1;
    $value['type'] = '0';
        //for filter search
    $defaultOpenTab = $this->_getParam('defaultOpenTab');
    if ($defaultOpenTab == "cancelled" || $defaultOpenTab == "completed" || $defaultOpenTab == "reject")
      $value['type'] = $defaultOpenTab;
    $paginator = Engine_Api::_()->getDbtable('appointments', 'booking')->getAppointmentPaginator($value);
    $result['appointments'] = $this->getProfessionalA($paginator);
    $limit_data = $this->view->{'limit_data_'.$view_type};
    $paginator->setItemCountPerPage(10);
    $page = $this->_getParam('page', 1);

    $paginator->setCurrentPageNumber($page);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }

  public function addServiceAction() 
  { 
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    if(!$viewerId)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

    $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($viewerId);
    if(!empty($professional)){
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' => $professional->professional_id,'message' => $this->view->translate('You are already a Professional.'))));
    }
    
    $form = new Booking_Form_Becomeprofessional();
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    } 

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    $formValues = $form->getValues();

    $db = Engine_Api::_()->getDbTable('professionals', 'booking')->getAdapter();
    $db->beginTransaction();
    try {  
      $professionalsTable = Engine_Api::_()->getDbTable('professionals', 'booking');
      $professionals = $professionalsTable->createRow();
      $formValues['user_id'] = $viewerId;
      $formValues['available'] = 1;
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove'))
        $formValues['active'] = 1;
      $professionals->setFromArray($formValues);
      $professionals->save();
      if (!empty($_FILES["file_id"]['name']) && !empty($_FILES["file_id"]['size'])) {
        $professionals->file_id = $professionals->setPhoto($form->file_id);
      }
      $professionals->save();
      $db->commit();

      /* save location of professional in sesbasic_location table */
      if (!empty($formValues['location'])) {
            //location not empty
        $sesbasiclocationTable = Engine_Api::_()->getDbTable('locations', 'sesbasic');
        $dbsesbasiclocation = $sesbasiclocationTable->getAdapter();
        $dbsesbasiclocation->beginTransaction();
        $sesbasiclocation = $sesbasiclocationTable->createRow();
        $locationValues['resource_id'] = $professionals->getIdentity();
        $locationValues['lat'] = $formValues['lat'];
        $locationValues['lng'] = $formValues['lng'];
        $locationValues['resource_type'] = 'professional';
        $sesbasiclocation->setFromArray($locationValues);
        $sesbasiclocation->save();
        $dbsesbasiclocation->commit();
      }
      /* end sesbasic_location Transaction */

          //set authorization member who become professional                
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      if (empty($values['auth_view'])) {
        $values['view'] = 'everyone';
      }
      $viewMax = array_search($values['view'], $roles);

      foreach ($roles as $i => $role) {
            //item type professional
        $auth->setAllowed($professionals, $role, 'view', ($i <= $viewMax));
      }
          //end authorization
          // if member setting are "No" selected then notification and mail go from this end.
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove') == 0) {
            //notifcation & mail for admin approval
        $getAdminnSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminnSuperAdmins as $getAdminnSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminnSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $professionals, 'booking_adminprofapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_adminprofapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'professional_name' => $professionals->name,
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $professionals->getHref(),
            )
          );
        }
            //end notifcation & mail
      }
          // if member setting are "yes" selected then notification and mail go from this end.
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove') == 1) {
            //notifcation & mail for admin approval 
        $getAdminnSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminnSuperAdmins as $getAdminnSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminnSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $professionals, 'booking_profautoapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_profautoapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'professional_name' => $professionals->name,
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $professionals->getHref(),
            )
          );
        }
            //end notifcation & mail
      }
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' => $professionals->getIdentity(),'message' => $this->view->translate('You have successfully created.'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }

  public function editServiceAction() 
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $service_id = $this->_getParam('service_id');
    $user_id = $this->_getParam('user_id');
    $form = new Booking_Form_Service();
    if ($service_id) {
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $service = Engine_Api::_()->getItem('booking_service', $service_id);
      $form->populate($service->toArray());
      $form->file_id->setRequired(false);
      $form->submit->setLabel('Save Changes');
      $form->setTitle('Edit Service');
    }
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    $values = $form->getValues();
    $db = Engine_Api::_()->getDbTable('services', 'booking')->getAdapter();
    $db->beginTransaction();
    try {  
      $viewer = Engine_Api::_()->user()->getViewer();
      $viewerId = $viewer->getIdentity();
      $settingsTable = Engine_Api::_()->getDbTable('services', 'booking');
      if (!$service_id) {
        $service = $settingsTable->createRow();
      }
      $values['price'] = round($values['price'], 2);
      if ($user_id)
        $viewerId = $user_id;
      $values['user_id'] = $viewerId;
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove'))
        $values['active'] = 1;
      if($service_id)
        $values['file_id'] = $service->file_id;
      $service->setFromArray($values);
      $service->save();
      if (!empty($_FILES["file_id"]['name'])) { 
        $service->file_id = $service->setPhoto($form->file_id);
      }
      $service->save();
      $db->commit();

          //set authorization on service created by professional
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      if (empty($values['auth_view'])) {
        $values['view'] = 'everyone';
      }
      $viewMax = array_search($values['view'], $roles);

      foreach ($roles as $i => $role) {
            //item type booking_service
        $auth->setAllowed($service, $role, 'view', ($i <= $viewMax));
      }
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove') == 0) {
        $getAdminSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminSuperAdmins as $getAdminSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminSuperAdmin['user_id']);
          $serviceName= '<a href="'.$this->view-> url(array('module' => 'booking', 'controller' => 'services',"action"=>'enabled','id'=>$service->getIdentity()),'admin_default',true).'">'.$service->getTitle().'</a>';
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $service, 'booking_adminserapl',array('servicename' => $serviceName));
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_adminserapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'service_name' => $service->name,
              'professional_name' => $service->getServiceProfessionalName(),
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $service->getHref(),
            )
          );
        }
      }

      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'servapprove') == 1) {
        $getAdminSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminSuperAdmins as $getAdminSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $service, 'booking_servautoapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_servautoapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'service_name' => $service->name,
              'professional_name' => $service->getServiceProfessionalName(),
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $service->getHref(),
            )
          );
        }
        $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activityApi->addActivity($viewer, $service, 'booking_pro_serv_cre');
        if ($action)
          $activityApi->attachActivity($action, $service);

      }

      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('service_id' => $service->getIdentity(),'message' => $this->view->translate('You have successfully edit the service.'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }
  public function mySettingAction() 
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $form = new Booking_Form_Becomeprofessional();
    $professional_id = $this->_getParam('service_id');
    if ($professional_id) {
      $db = Zend_Db_Table_Abstract::getDefaultAdapter();
      $service = Engine_Api::_()->getItem('booking_professional', $professional_id);
      $form->populate($service->toArray());
      $form->file_id->setRequired(false);
      $form->submit->setLabel('Save Changes');
      $form->setTitle('Edit Service');
    }
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    $formValues = $form->getValues();

    $db = Engine_Api::_()->getDbTable('professionals', 'booking')->getAdapter();
    $db->beginTransaction();
    try {  
      $professionalsTable = Engine_Api::_()->getDbTable('professionals', 'booking');
      $professionals = $professionalsTable->createRow();
      $formValues['user_id'] = $viewerId;
      $formValues['available'] = 1;
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove'))
        $formValues['active'] = 1;
      $professionals->setFromArray($formValues);
      $professionals->save();
      if (!empty($_FILES["file_id"]['name']) && !empty($_FILES["file_id"]['size'])) {
        $professionals->file_id = $professionals->setPhoto($form->file_id);
      }
      $professionals->save();
      $db->commit();
      if (!empty($formValues['location'])) {
        $sesbasiclocationTable = Engine_Api::_()->getDbTable('locations', 'sesbasic');
        $dbsesbasiclocation = $sesbasiclocationTable->getAdapter();
        $dbsesbasiclocation->beginTransaction();
        $sesbasiclocation = $sesbasiclocationTable->createRow();
        $locationValues['resource_id'] = $professionals->getIdentity();
        $locationValues['lat'] = $formValues['lat'];
        $locationValues['lng'] = $formValues['lng'];
        $locationValues['resource_type'] = 'professional';
        $sesbasiclocation->setFromArray($locationValues);
        $sesbasiclocation->save();
        $dbsesbasiclocation->commit();
      }             
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      if (empty($values['auth_view'])) {
        $values['view'] = 'everyone';
      }
      $viewMax = array_search($values['view'], $roles);

      foreach ($roles as $i => $role) {
        $auth->setAllowed($professionals, $role, 'view', ($i <= $viewMax));
      }
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove') == 0) {
        $getAdminnSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminnSuperAdmins as $getAdminnSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminnSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $professionals, 'booking_adminprofapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_adminprofapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'professional_name' => $professionals->name,
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $professionals->getHref(),
            )
          );
        }
      }
      if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'profapprove') == 1) {
        $getAdminnSuperAdmins = Engine_Api::_()->booking()->getAdminSuperAdmins();
        foreach ($getAdminnSuperAdmins as $getAdminnSuperAdmin) {
          $user = Engine_Api::_()->getItem('user', $getAdminnSuperAdmin['user_id']);
          Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($user, $viewer, $professionals, 'booking_profautoapl');
          Engine_Api::_()->getApi('mail', 'core')->sendSystem(
            $user,
            'booking_profautoapl',
            array(
              'host' => $_SERVER['HTTP_HOST'],
              'professional_name' => $professionals->name,
              'queue' => false,
              'recipient_title' => $viewer->getTitle(),
              'object_link' => $professionals->getHref(),
            )
          );
        }
      }
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' => $professionals->getIdentity(),'message' => $this->view->translate('You have successfully created.'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }
  public function becomeUserAction() 
  { 
     $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $professional_id = $this->_getParam("professional_id");
    $professional = Engine_Api::_()->getItem('professional', $professional_id);
    $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($viewerId);
    $form = new Sesbasic_Form_Delete();
    $form->setTitle('Become normal user?');
    $form->setDescription('Are you sure that you want to become a normal user if you save this setting it will delete your professional profile and service as well?');
    $form->submit->setLabel('Make change');
    if ($this->getRequest()->isPost()) {
      $db = $professional->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $deletedProfessionalsdb = Engine_Api::_()->getDbTable('professionals', 'booking')->getAdapter();
        $deletedProfessionalsdb->beginTransaction();
        $deletedProfessionalsdb->commit();
        $deletedProfessionalsTable = Engine_Api::_()->getDbTable('deletedprofessionals', 'booking');
        $deletedProfessionals = $deletedProfessionalsTable->createRow();
        $formValues['user_id'] = $professional->user_id;
        $deletedProfessionals->setFromArray($formValues);
        $deletedProfessionals->save();
        $professional->is_deleted = 1;
        $professional->save();
        $deletedProfessionalsdb->commit();
        $db->commit();
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('The selected review has been deleted.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
      }
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('$professional_id' => $professional->getIdentity(),'message' => $this->view->translate('You have successfully delete your profile.'))));
    }
  }
 
public function professionalEnableAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $professional_id = $this->_getParam("professional_id");
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->query("DELETE FROM `engine4_booking_deletedprofessionals` WHERE `engine4_booking_deletedprofessionals`.`user_id` = " . $viewerId );
    $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($viewerId);
    $professional = Engine_Api::_()->getItem('professional',$professional->professional_id);
    if($professional){
      $professional->is_deleted = 0;
      $professional->save();
    }
   Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('$professional_id' => $professional->getIdentity(),'message' => $this->view->translate('You have successfully enabled your Professional profile'))));
    }
  public function rateProfessionalAction() 
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
        //$professionalId = (int) $_POST['professionalId'];
    $professionalId = $this->_getParam("professional_id");
        //$rate = (int) $_POST['rateValue'];
    $rate = $this->_getParam('rateValue');
    $professionalratingsTable = Engine_Api::_()->getDbTable('professionalratings', 'booking');
    $isProfessionalratingAvailable = $professionalratingsTable->isProfessionalratingAvailable(array("professional_id" => $professionalId, "user_id" => $viewerId));
    try {
      $professionalItem = Engine_Api::_()->getItem('professional', $professionalId);
      $db = $professionalratingsTable->getAdapter();
      $db->beginTransaction();
      if ($isProfessionalratingAvailable === "insert") {
        echo $professionalId . "insert";
        $reviews = $professionalratingsTable->createRow();
        $formValues["professional_id"] = $professionalId;
        $formValues["user_id"] = $viewerId;
        $formValues["rating"] = $rate;
        $reviews->setFromArray($formValues);
        $reviews->save();
        $professionalItem->rating = $rate;
        $professionalItem->save();
      } else if ($isProfessionalratingAvailable === "update") {
        $professionalratingsTable->update(array(
          'rating' => $rate,
        ), array(
          'professional_id = ?' => $professionalId,
          'user_id = ?' => $viewerId,
        )
      );
        $professionalItem->rating = $rate;
        $professionalItem->save();
      }
      $db->commit();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' => $professionalItem->getIdentity(),'message' => $this->view->translate('You have successfully give rating.'))));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
  }
  public function appoinmentCommonAction() 
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $professional_id = $this->_getParam("professional_id");
    $appointment_id = $this->_getParam("appointment_id");
    $service_id = $this->_getParam("service_id");
    //print_r($service_id);die;
    /*$value['professional_id'] =  $professionalId;
    $value['popularCol'] = isset($popularCol) ? $popularCol : '';
    $value['fixedData'] = isset($fixedData) ? $fixedData : '';
    $value['draft'] = 0;
    $value['search'] = 1;
    $value['type'] = '0';*/
        //for filter search
    $actionType = $this->_getParam('actionType');
    //print_r($actionType);die;
    if($actionType== "accept"){
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->saveas = 1;
      $appointment->state = "complete";
      if ($appointment->professional_id == $appointment->given) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->professional_id);
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#given".'>'.$viewer->getTitle().'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_profacceptuserreq',array('appointmentUrl'=>$appointmentUrl));
        Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_profacceptuserreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'professional_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
          )
        );
      } else {
            //professional accept request if someone has book professional serivce.
            //send to user
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->user_id);
        $servicename = Engine_Api::_()->getItem('booking_service', $appointment->service_id);
        $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($appointment->professional_id); 
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#taken".'>'.$professional->name.'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_useracceptprofreq',array('appointmentUrl'=>$appointmentUrl));
        $var = Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_useracceptprofreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'member_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
            'object_link' => $servicename->getHref(),
          )
        );
      }
    }
    if($actionType== "completed"){  
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->action = "completed";
    }
    if($actionType== "reject"){ 
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->action = "reject";
      if ($appointment->professional_id == $appointment->given) {
            //user accept request if professional book appointment for user.
            //send to professional
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->professional_id);
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#reject".'>'.$viewer->getTitle().'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_profrejectuserreq',array('appointmentUrl'=>$appointmentUrl));
        Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_profrejectuserreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'professional_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
          )
        );
      } else {
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->user_id);
        $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($appointment->professional_id); 
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#reject".'>'.$professional->name.'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_userrejectprofreq',array('appointmentUrl'=>$appointmentUrl));
        Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_userrejectprofreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'member_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
          )
        );
      }
    }
    if($actionType== "cancel"){ 
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->action = "cancelled";
      if ($appointment->professional_id == $appointment->given) {
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->user_id);
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#cancelled".'>'.$viewer->getTitle().'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_profcanceluserreq',array('appointmentUrl'=>$appointmentUrl));
        Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_profcanceluserreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'professional_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
          )
        );
      } else {
            //professional reject request if someone has book his serivces.
        $viewer = Engine_Api::_()->user()->getViewer();
        $object_id = Engine_Api::_()->getItem('user', $appointment->professional_id);
        $professional = Engine_Api::_()->getDbtable('professionals', 'booking')->getProfessioanlId($appointment->professional_id); 
        $appointmentUrl = '<a href='."{$this->view->url(array('action'=>'appointments'),'booking_general',true)}#cancelled".'>'.$professional->name.'</a>';
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($object_id, $viewer, $viewer, 'booking_usercancelprofreq');
        Engine_Api::_()->getApi('mail', 'core')->sendSystem(
          $object_id,
          'booking_usercancelprofreq',
          array(
            'host' => $_SERVER['HTTP_HOST'],
            'member_name' => $object_id->getTitle(),
            'queue' => false,
            'recipient_title' => $viewer->getTitle(),
          )
        );
      }
    }
    if ($actionType=="unblock") {
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->block = 0;
    }
    if ($actionType=="block") {
      $appointment = Engine_Api::_()->getItem('booking_appointment', $appointment_id);
      $appointment->block = 1;
    }
    $appointment->save();
    if ($actionType=="accept") {
      if ($appointment->professional_id == $appointment->given) {
        $settings = Engine_Api::_()->getApi('settings', 'core');
        if($settings->getSetting('booking.allow.for', 1))
          Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' =>  $appointment->getIdentity(),'message' => $this->view->translate('Wait redirect to payment gateway.'))));
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' =>  $appointment->getIdentity(),'message' => $this->view->translate('Wait redirect to payment gateway.'))));

      }
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('professional_id' =>  $appointment->getIdentity(),'message' => $this->view->translate('Wait redirect to payment gateway.'))));
  }
  public function createProfessionalreviewAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $subjectId = $this->_getParam('professional_id', 0);
    $item = Engine_Api::_()->getItem('professional', $subjectId);
    
    if (!$item){ 
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    //check review exists
   /* $isReview = Engine_Api::_()->getDbtable('profreviews', 'booking')->isReview(array('professional_id' => $item->getIdentity(), 'column_name' => 'profreview_id'));
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.allow.owner.review', 1)) { die('33');
        $allowedCreate = true;
    } else { 
        if ($item->owner_id == $viewer->getIdentity())
            $allowedCreate = false;
        else
            $allowedCreate = true;
    }
    if ($isReview || !$allowedCreate) { 
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }*/
    $form = new Booking_Form_Review_Professional_Create(array('professionalItem' =>$item));
    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields,array('resources_type'=>'booking'));
    }   
    if(!$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }
    $values = $form->getValues();
    $values['rating'] = $_POST['rate_value'] ?? 0;
    $values['recommended'] = $_POST['recommended'] ?? 1;
    
    $values['owner_id'] = $viewer->getIdentity();
    $values['professional_id'] = $item->getIdentity();
    $reviews_table = Engine_Api::_()->getDbtable('profreviews', 'booking');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
        $review = $reviews_table->createRow();
        $review->setFromArray($values);
        $review->description = $_POST['description'];
       
        $review->save();
        $db->commit();
        //save rating in parent table if exists
        if (isset($item->rating)) {
            $item->rating = Engine_Api::_()->getDbtable('profreviews', 'booking')->getRating($review->professional_id);
            $item->save();
        }
        $review->save();
        $db->commit();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('review_id' => $review->getIdentity(), 'message' => $this->view->translate('You have successfully created .'))));
    } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>$e->getMessage()));
    }
    
  }
  public function editProfessionalreviewAction() {
    $review_id = $this->_getParam('review_id', null);
    $subject = Engine_Api::_()->getItem('booking_profreview', $review_id);
    if (!$subject)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    $item = Engine_Api::_()->getItem('professional', $subject->professional_id);
    if (!$item)
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    $form = new Booking_Form_Review_Professional_Edit(array( 'reviewId' => $subject->profreview_id, 'professionalItem' => $item));
    $form->populate($subject->toArray());
    $form->rate_value->setValue($subject->rating);
    if ($this->_getParam('getForm')) {
        $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
        $this->generateFormFields($formFields, array('resources_type' => 'booking_profreview','rate_value'=>$subject->rating));
    }
    if (!$this->getRequest()->isPost()) {
          Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
    }
    if (!$form->isValid($this->getRequest()->getPost())){
      $validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
      if (is_countable($validateFields) && engine_count($validateFields))
          $this->validateFormFields($validateFields);
    }
    $values = $_POST;
    $values['rating'] = $_POST['rate_value'] == "0.0" ? 1 : $_POST['rate_value'];
     $values['recommended'] = $_POST['recommended'] ?? 1;
    $reviews_table = Engine_Api::_()->getDbtable('profreviews', 'booking');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
        $subject->setFromArray($values);
        $subject->save();
        if (isset($item->rating)) {
            $item->rating = Engine_Api::_()->getDbtable('profreviews', 'booking')->getRating($subject->professional_id);
            $item->save();
        }
        $subject->save();
        $db->commit();
        $message = Zend_Registry::get('Zend_Translate')->_('The selected review has been edited.');
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('review_id' => $subject->getIdentity(), 'message' =>$message)));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
  }
  public function deleteProfessionalreviewAction() {
    $review = Engine_Api::_()->getItem('booking_profreview', $this->getRequest()->getParam('review_id'));
    if (!$review)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    // In smoothbox
    if ($this->getRequest()->isPost()) {
        $db = $review->getTable()->getAdapter();
        $db->beginTransaction();
        try {
            $review->delete();
            $db->commit();
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message'=> $this->view->translate('The selected review has been deleted.'))));
        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
        }
    }else{
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
    }
  }
  public function browseProfessionalreviewAction() {
    $professionalId = $this->_getParam('professional_id');
    $viewer = Engine_Api::_()->user()->getViewer();
    if(!$professionalId)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
      
    $professional = Engine_Api::_()->getItem('professional',$professionalId);
  
        
    if(!$professional){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    }
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.allow.review', 1)){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }
    
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.prof.allow.review', 1)) {
      $allowedCreate = true;
    } else {
      if ($professional->professional_id == $viewer->getIdentity())
        $allowedCreate = false;
      else
        $allowedCreate = true;
    }
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    //$editReviewPrivacy = Engine_Api::_()->sesbasic()->getViewerPrivacy('booking_profreview', 'edit');
      $table = Engine_Api::_()->getDbtable('profreviews', 'booking');
      $isReview = $table->isReview(array('professional_id' => $professional->getIdentity(), 'column_name' => 'review_id'));
    if($viewer->getIdentity() && Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.review', 1) && $allowedCreate){
      if(!$isReview){
        $result['button']['label'] = $this->view->translate('Write a Review');
        $result['button']['name'] = 'createreview';
      }
      if($isReview){
        $result['button']['label'] = $this->view->translate('Update Review');
        $result['button']['name'] = 'updatereview';
        $result['button']['profreview_id'] = $table->isReview(array('professional_id' => $professional->getIdentity(), 'column_name' => 'review_id')); ;
      }
    }
    
    $params['professional_id'] = $professional->getIdentity();
    $select = $table->getProfessionalReviewSelect($params);
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($this->_getParam('limit',10));
    $paginator->setCurrentPageNumber($this->_getParam('page',1));
    $result['reviews'] = $this->getProfessionalreview($paginator,$professional);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }
  public function getProfessionalreview($paginator,$professional){
    $counter = 0;
    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $levelId = ($viewerId) ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    foreach($paginator as $item){
      if(empty($item->owner_id))
        continue;
      $result[$counter] = $item->toArray();
      $owner = $item->getOwner();
      $result[$counter]['professional']['images'] = $this->getBaseUrl(true, $professional->getPhotoUrl());
      $result[$counter]['professional']['title'] = $professional->getTitle();
      $result[$counter]['professional']['Guid'] = $professional->getGuid();
      $result[$counter]['professional']['id'] = $professional->getIdentity();
      $result[$counter]['owner']['id'] = $owner->getIdentity();
      $result[$counter]['owner']['Guid'] = $owner->getGuid();
      $result[$counter]['owner']['title'] = $owner->getTitle();
      //$result[$counter]['owner']['images'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
      $result[$counter]['owner']['images'] = $this->getBaseUrl(true, (!empty($owner->getPhotoUrl())) ? $owner->getPhotoUrl('thumb.icon') : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');

      $ownerSelf = $viewer->getIdentity() == $item->owner_id ? true : false;
      $counterOption = 0;
      if($item->authorization()->isAllowed($viewer, 'edit')) {
        $result[$counter]['options'][$counterOption]['name'] = 'edit'; 
        $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Edit Review'); 
        $counterOption++;
      }
      if($item->authorization()->isAllowed($viewer, 'delete')) {
        $result[$counter]['options'][$counterOption]['name'] = 'delete'; 
        $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Delete Review'); 
        $counterOption++;
      }
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.show.report', 1) && $viewer->getIdentity()){
        $result[$counter]['options'][$counterOption]['name'] = 'report'; 
        $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Report'); 
        $counterOption++;
      }
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1) && $viewer->getIdentity()){
        $result[$counter]['options'][$counterOption]['name'] = 'share'; 
        $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Share Review'); 
        $counterOption++;
      }
    if (Engine_Api::_()->authorization()->getPermission($viewer, 'booking', 'bookservice') && $viewer->getIdentity()  && $professional->available) { 
        $result[$counter]['professional']['book_url'] = $this->getBaseUrl(true, $this->view->url(array("action"=>'bookservices','professional'=>$item->owner_id),'booking_general',true));
      }

      $counter++;
    }
    return $result;
  }
  public function professionalReviewviewAction(){
    $viewer = Engine_Api::_()->user()->getViewer();
    $review_id = $this->_getParam('review_id', null);
    if(!$review_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('courses.allow.review', 1))
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
      
    $review = Engine_Api::_()->getItem('booking_profreview', $review_id);
    $professional = Engine_Api::_()->getItem('professional', $review->professional_id);
    if(!$review)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    //Increment view count
    if (!$viewer->isSelf($review->getOwner())) {
        $review->view_count++;
        $review->save();
    }
    $params = array();
    $result = array();
    /*----------------make data-----------------------------*/
    $counter = 0;
    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $result = $review->toArray();
    $reviewer = Engine_Api::_()->getItem('user', $review->owner_id);
    $owner = $reviewer->getOwner();
    $result['professional']['images'] = $this->getBaseUrl(true, $professional->getPhotoUrl());
    $result['professional']['title'] = $professional->getTitle();
    $result['professional']['Guid'] = $professional->getGuid();
    $result['professional']['id'] = $professional->getIdentity();
    $result['owner']['id'] = $owner->getIdentity();
    $result['owner']['Guid'] = $owner->getGuid();
    $result['owner']['title'] = $owner->getTitle();
    $result['owner']['images'] = $this->getBaseUrl(true, (!empty($owner->getPhotoUrl())) ? $owner->getPhotoUrl('thumb.icon') : '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
    $optionCounter = 0;
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.show.report', 1) && $viewerId && $viewerId != $owner->getIdentity()){
      $result['options'][$optionCounter]['name'] = 'report';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Report');
      $optionCounter++;
    }
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1) && $viewerId){
      $result['options'][$optionCounter]['name'] = 'share';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Share');
      $optionCounter++;
        $result["share"]["url"] = $this->getBaseUrl(false,$review->getHref());
        $result["share"]["title"] = $review->getTitle();
        $result["share"]["description"] = strip_tags($review->getDescription());
        $result["share"]["setting"] = Engine_Api::_()->getApi('settings', 'core')->getSetting('booking.allow.share', 1);
        $result["share"]['urlParams'] = array(
          "type" => $review->getType(),
          "id" => $review->getIdentity()
        );
    }
    if($review->owner_id == $viewerId) { 
      $result['options'][$optionCounter]['name'] = 'edit';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Edit Review');
      $optionCounter++;
      
      $result['options'][$optionCounter]['name'] = 'delete';
      $result['options'][$optionCounter]['label'] = $this->view->translate('Delete Review');
      $optionCounter++;
    }
    /*----------------make data-----------------------------*/
    $data['review'] = $result;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $data)));
  }
  public function professionalProfileservicesAction(){
    $viewer = Engine_Api::_()->user()->getViewer();
    $professional_id = $this->_getParam('professional_id', null);
    if(!$professional_id){
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    }
    $professional = Engine_Api::_()->getItem('professional',$professional_id);
    if(!$professional)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    $paginator = Engine_Api::_()->getDbTable('services', 'booking')->servicePaginator(array("viewerId" => $professional->user_id));
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result = $this->getServices($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if ($result <= 0)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $this->view->translate('Does not exist services.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result), $extraParams));
      
  }
}
