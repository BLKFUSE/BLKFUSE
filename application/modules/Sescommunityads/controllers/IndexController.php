<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_IndexController extends Core_Controller_Action_Standard
{
  public function init() {

    if (!$this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'view')->isValid())
      return;
  }
  public function whyseeingAction(){
      $this->_helper->content->setEnabled();
  }
  public function browseAction()
  {
    //Render
    $this->_helper->content->setEnabled();
  }
  public function viewAction(){
    if (!$this->_helper->requireUser->isValid())
      return;

    $sescommunityad_id = $this->_getParam('ad_id',0);
    $ad = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
    if(!$ad){
       return $this->_forward('notfound', 'error', 'core');
    }
    $viewer = $this->view->viewer();
    if($viewer->level_id != 1){
        if($viewer->getIdentity() != $ad->user_id || $ad->is_deleted)
          return $this->_forward('notfound', 'error', 'core');
    }
    Engine_Api::_()->core()->setSubject($ad);
    //Render
    $this->_helper->content->setEnabled();
  }
  public function manageadsAction()
  {
    //delete given ads if request is from post method
    if(engine_count($_POST)){
      if($this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'delete')->isValid()){
        foreach($_POST as $key => $campain_data){
           if(strpos($key,'camapign_delete_') !== false){
              $sescommunityad_id = str_replace('camapign_delete_','',$key);
              $ad = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
              if($ad){
                $ad->delete();
              }
           }
        }
      }
    }

    $campaign_id = $this->_getParam('campaign_id',0);
    $cmpaign = Engine_Api::_()->getItem('sescommunityads_campaign',$campaign_id);
    if(!$cmpaign){
       return $this->_forward('notfound', 'error', 'core');
    }
    Engine_Api::_()->core()->setSubject($cmpaign);

    //Render
    $this->_helper->content->setEnabled();
  }
  public function manageAction()
  {
    //delete given campaign ads if request is from post method
    if(engine_count($_POST)){
      if($this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'delete')->isValid()){
        foreach($_POST as $key => $campain_data){
           if(strpos($key,'camapign_delete_') !== false){
              $campaign_id = str_replace('camapign_delete_','',$key);
              $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$campaign_id);
              if($campaign){
                $campaign->delete();
              }
           }
        }
      }
    }
    //Render
    $this->_helper->content->setEnabled();
  }
  public function getPagePostFeedAction(){
    $id = $this->_getParam('id',0);
    $viewer = $this->view->viewer();
    $this->view->action = $page = Engine_Api::_()->getItem('sespage_page',$id);
    $activityApi = Engine_Api::_()->getDbTable('actions', 'sesadvancedactivity');
    $getActivity = $activityApi->getActionsByObjectType($page,'sescommunityads_page_ad');
    if (!engine_count($getActivity)){
      $action = $activityApi->addActivity($viewer, $page, 'sescommunityads_page_ad');
      if ($action) {
        $activity_id = $action->getIdentity();
        $activityApi->attachActivity($action, $page);
      }
    }else{
        $activity_id = $getActivity[0]->getIdentity();
    }
    $detailsTable = Engine_Api::_()->getDbTable('details','sesadvancedactivity');
    $actionDetails = $detailsTable->isRowExists($activity_id);
    $actionDetails = Engine_Api::_()->getItem('sesadvancedactivity_detail',$actionDetails);
    $actionDetails->setFromArray(array(
      'is_community_ad' => 1,
    ));
    $actionDetails->save();
    $this->view->action = Engine_Api::_()->getItem('sesadvancedactivity_action',$activity_id);
    $this->renderScript('index/get-boost-post-feed.tpl');
  }
  public function getBoostPostFeedAction(){
      $id = $this->_getParam('id',0);
      $this->view->action = Engine_Api::_()->getItem('sesadvancedactivity_action',$id);

  }
  public function getBoostPostActivityAction(){
     $this->view->selected = $selected = $this->_getParam('selected',0);
     $action_id = $this->_getParam('is_action_id',0);
     $actions = Engine_Api::_()->sescommunityads()->getActivityBoostPost(array('selected'=>$selected,'is_action_id'=>$action_id));
     $this->view->data = $actions;
     $this->view->advComment = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment');
  }
  public function getActivityAction(){
    $activity_id = $this->_getParam('action_id',false);
    if(!$activity_id){
      echo 0;die;
    }
    $action = Engine_Api::_()->getItem('sesadvancedactivity_action',$activity_id);
    echo $this->view->partial(
            '_activity.tpl',
            'sesadvancedactivity',
            array('getAction'=>$action,'noList'=>false,'userphotoalign'=>'left')
          );die;
  }
  public function editCampaignAction(){
    if (!$this->_helper->requireUser->isValid())
      return;
    if (!$this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'edit')->isValid())
      return;
    $campaign_id = $this->_getParam('campaign_id',0);
    if(!$campaign_id)
      return $this->_forward('notfound', 'error', 'core');
    $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$campaign_id);
    if(!$campaign)
        return $this->_forward('notfound', 'error', 'core');

    $this->view->form = $form = new Sescommunityads_Form_Campaign();
    $form->populate($campaign->toArray());

    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $campaign->title = $_POST['title'];
    $campaign->save();
    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 100,
        'parentRefresh' => 10,
        'messages' => array($this->view->translate('Campaign edited successfully.'))
    ));
  }
  public function deleteCampaignAction(){
    if (!$this->_helper->requireUser->isValid())
      return;
    if (!$this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'delete')->isValid())
      return;
    $campaign_id = $this->_getParam('campaign_id',0);
    if(!$campaign_id)
      return $this->_forward('notfound', 'error', 'core');
    $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$campaign_id);
    if(!$campaign)
        return $this->_forward('notfound', 'error', 'core');

    $this->view->form = $form = new Sescommunityads_Form_Delete();

    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $campaign->delete();

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => 100,
        'parentRefresh' => 10,
        'messages' => array($this->view->translate('Campaign deleted successfully.'))
    ));
  }
  public function createAction()
  {
    $values = array();
    if ($this->getRequest()->isPost())
      return $this->_forward('create-ads', null, null, array('format' => 'html'));
    //Render
     if (!$this->_helper->requireUser->isValid())
      return;
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'create')->isValid())
      return;
     $this->view->action_id = $action_id = $this->_getParam('action_id');
     $this->view->widgetid = $widgetid = $this->_getParam('widgetid', null);
    //Start Package Work
    $package = Engine_Api::_()->getItem('sescommunityads_packages', $this->_getParam('package_id', 0));
    $this->view->existingpackage = $existingpackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $this->_getParam('existing_package_id', 0));

    if ($existingpackage) {
      $package = Engine_Api::_()->getItem('sescommunityads_packages', $existingpackage->package_id);
    }

    if (!$package && !$existingpackage) {
      //check package exists for this member level
      $packageMemberLevel = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getPackage(array('member_level' => $viewer->level_id));
        if(engine_count($packageMemberLevel) == 1){
            return $this->_helper->redirector->gotoRoute(array('action' => 'create','package_id'=>$packageMemberLevel[0]['package_id']), 'sescommunityads_general', true);
        }else if (engine_count($packageMemberLevel)) {
        //redirect to package page
        return $this->_helper->redirector->gotoRoute(array('action' => 'package','action_id'=>$action_id), 'sescommunityads_general', true);
      }else{
         //getDefaultPlan
         $defaultPlan = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getDefaultPackage();
         return $this->_helper->redirector->gotoRoute(array('action' => 'create','package_id'=>$defaultPlan), 'sescommunityads_general', true);
      }
    }

    if ($existingpackage){
      $canCreate = Engine_Api::_()->getDbTable('orderspackages', 'sescommunityads')->checkUserPackage($this->_getParam('existing_package_id', 0), $this->view->viewer()->getIdentity());
      if (!$canCreate)
        return $this->_helper->redirector->gotoRoute(array('action' => 'package'), 'sescommunityads_general', true);
    }
    //End Package Work
    $this->view->callToAction = $this->callToActionBtn();
    //Banner Work
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sescomadbanr')) {
        $this->view->getBannerSizes = $this->getBannerSizes();
    }
    $this->view->campaign = Engine_Api::_()->getDbTable('campaigns','sescommunityads')->geCampaigns(array('user_id'=>$viewer->getIdentity()));
    $this->view->package_id = $package->package_id;
    $this->view->package = $package;
    $this->view->networks = Engine_Api::_()->sescommunityads()->networks();
    $this->view->profileTypes = $profileTypes = Engine_Api::_()->sescommunityads()->getAllProfileTypes();
    $this->view->editName = "false";
    $this->view->formField = new Sescommunityads_Form_Standard(array(
        'item' => $viewer,
        'topLevelId' => 0,
        'topLevelValue' => null,
    ));
    $this->view->targetFields= Engine_Api::_()->sescommunityads()->getTargetAds(array('fieldsArray'=>1));
    $this->_helper->content->setEnabled();
  }
  function checkError($campaign,$targetData,$schedule,$adType,$uploadType,$ads = false){
    //check campaign
    if($adType == "promote_content_cnt" || $adType == "promote_content_website"){
        $counter = 0;
        if(empty($_POST['main_heading_title'])){
          //echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide Text.')));die;
        }
        if($uploadType == "carousel"){
          foreach($_POST['heading1'] as $heading){
            if(!$heading){
               echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide Heading.')));die;
            }else if(empty($_POST['destinationurl1'][$counter])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid Destination URL.')));die;
            }else if(empty($_FILES['image']['name']) && empty($ads)){
              echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please upload valid Image.')));die;
            }
            $counter++;
          }
          if(!empty($_POST['add_card']) && $adType == "promote_content_cnt"){
            if(empty($_FILES['more_image']['name']) && empty($ads)){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please upload valid Card Image.')));die;
            }else if(empty($_POST['see_more_url'])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid See More URL.')));die;
            } else if(empty($_POST['see_more_display_link'])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid See More Display Link.')));die;
            }
          }
          if( $adType == "promote_content_website"){
            if(empty($_POST['website_url'])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid Website URL.')));die;
            } else if(empty($_POST['website_title'])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please upload valid Website Title.')));die;
            }
          }
        }else if($uploadType == "image"){
          foreach($_POST['heading1'] as $heading){
            if(!$heading){
               echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide Heading.')));die;
            }else if(empty($_POST['destinationurl1'][$counter])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid Destination URL.')));die;
            }else if(empty($_FILES['image']['name']) && empty($ads)){
              echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please upload valid Image.')));die;
            }
            $counter++;
          }
        }else{
          foreach($_POST['heading1'] as $heading){
            if(!$heading){
               echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide Heading.')));die;
            }else if(empty($_POST['destinationurl1'][$counter])){
                echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid Destination URL.')));die;
            }else if(empty($_FILES['image']['name']) && empty($ads)){
              echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please upload valid Video.')));die;
            }
            $counter++;
          }

        }
        if(!empty($_POST['call_to_action']) && empty($_POST['calltoaction_url'])){
            //echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid Call to Action URL.')));die;
        }
    }else if($adType == "boost_post_cnt"){
        if(empty($_POST['boost_post_id'])){
            echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please select activity feed to boost post.')));die;
        }
    }
  }
  function editAdAction(){

    $sescommunityad_id = $this->_getParam('sescommunityad_id',0);
    $ads = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
    if (!$ads)
       return $this->_forward('requireauth', 'error', 'core');
    Engine_Api::_()->core()->setSubject($ads);
    if (!$this->_helper->requireUser()->isValid())
      return;
    if (!$this->_helper->requireAuth()->setAuthParams('sescommunityads', null, 'edit')->isValid())
      return;
    if($this->view->viewer()->level_id != 1){
      if(strtotime($ads->startdate) < time())
        return $this->_forward('requireauth', 'error', 'core');
    }
    $this->view->ad = $ads;
    //get attachments
    $table = Engine_Api::_()->getDbTable('attachments','sescommunityads');
    $select = $table->select()->where('sescommunityad_id =?',$sescommunityad_id);
    $this->view->attachment = $table->fetchAll($select);

    //get target data
    $table = Engine_Api::_()->getDbTable('targetads','sescommunityads');
    $select = $table->select()->where('sescommunityad_id =?',$sescommunityad_id);
    $targetFields = array();
    $targetData = $table->fetchAll($select);
    if(engine_count($targetData)){

      $data = $targetData->toArray();
      $data = $data[0];
      unset($data['targetad_id']);
      unset($data['sescommunityad_id']);
      $targetFields = $data;
      if(!empty($data['network_enable'])){
        $this->view->targetData = explode(',',$data['network_enable']);
      }
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest') && !empty($data['interest_enable'])){
        $this->view->interestTargetData = explode(',',$data['interest_enable']);
      }
    }
    $viewer = $this->view->viewer();
    $this->view->callToAction = $this->callToActionBtn($ads->calltoaction);
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sescomadbanr')) {
        $this->view->getBannerSizes = $this->getBannerSizes($ads->banner_id);
    }
    $this->view->campaign = Engine_Api::_()->getDbTable('campaigns','sescommunityads')->geCampaigns(array('user_id'=>$viewer->getIdentity()));
    $this->view->package_id = $ads->package_id;
    $package = Engine_Api::_()->getItem('sescommunityads_packages',$ads->package_id);
    $this->view->package = $package;
    $this->view->networks = Engine_Api::_()->sescommunityads()->networks();
    $this->view->profileTypes = $profileTypes = Engine_Api::_()->sescommunityads()->getAllProfileTypes();
    $this->view->editName = "true";
    $this->view->formField = new Sescommunityads_Form_Standard(array(
        'item' => $viewer,
        'topLevelId' => 0,
        'target'=>$targetFields,
        'topLevelValue' => null,
    ));
    $this->view->targetFields= Engine_Api::_()->sescommunityads()->getTargetAds(array('fieldsArray'=>1));

    $this->renderScript('index/create.tpl');
  }
  function createAdsAction(){
    if (!$this->getRequest()->isPost()) {
      return;
    }
    $package = Engine_Api::_()->getItem('sescommunityads_packages',$_POST['package_id']);
    if(!$package){
        echo json_encode(array('error'=>1,'message'=>$this->view->translate('Invalid package selected.')));die;
    }

    //campaign
    parse_str($_POST['campaign'], $campaign);
    if(!$campaign['campaign'] && !$campaign['campaign_name']){
      echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please provide valid campaign name.')));die;
    }
    //target data
    parse_str($_POST['targetData'],$targetData);
    //schedule
    parse_str($_POST['schedullingData'], $schedule);
    $sescommunityad_id = $this->_getParam('sescommunityad_id',0);
    if($sescommunityad_id){
      $ads = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
    }
    $startDate = $schedule['start_date'];
    $startTime = $schedule['start_time'];
    $endDate = $schedule['end_date'];
    $endTime = $schedule['end_time'];
    $tillEnd = $schedule['ad_end_date'];


    $startdate = isset($schedule['start_date']) ? date('Y-m-d H:i:s',strtotime($schedule['start_date'].' '.$schedule['start_time'])) : '';
    $enddate = isset($schedule['end_date']) ? date('Y-m-d H:i:s',strtotime($schedule['end_date'].' '.$schedule['end_time'])) : '';;


      $oldTz = date_default_timezone_get();
      date_default_timezone_set($this->view->viewer()->timezone);
      $start = strtotime($startdate);
      $end = strtotime($enddate);
      //For Video case only
      if($start < time() && $_POST['subtype'] == "video") {
        $start = time();
      }
      $currenttime = time();
      date_default_timezone_set($oldTz);
      $startdate = date('Y-m-d H:i:s', $start);
      $enddate = date('Y-m-d H:i:s', $end);

    if(empty($endDate) && empty($tillEnd)){
       echo json_encode(array('error'=>1,'message'=>$this->view->translate('Please enter correct end date.')));die;
    }else if(strtotime($startdate) < $currenttime && (empty($ads) || (!empty($ads) && strtotime($ads->startdate) != strtotime($startdate)))){
       echo json_encode(array('error'=>1,'message'=>$this->view->translate('Start time must be greater than current time.')));die;
    }else if(strtotime($enddate) < strtotime($startdate) && empty($tillEnd) && (!empty($ads) && (strtotime($ads->startdate) != strtotime($startdate)  || strtotime($ads->enddate) != strtotime($enddate)))){
        echo json_encode(array('error'=>1,'message'=>$this->view->translate('End time must be greater than start time.')));die;
    }
    //Convert Time Zone
    $adType = $_POST['ad_type'];
    $networks = !empty($_POST['networks']) && $_POST['networks'] != "null" ? $_POST['networks'] : false;
    $interests = !empty($_POST['interests']) && $_POST['interests'] != "null" ? $_POST['interests'] : false;
    $uploadType = !empty($_POST['uploadType']) ? $_POST['uploadType'] : '';
    $this->checkError($campaign,$targetData,$schedule,$adType,$uploadType,$ads);
    $db = Engine_Api::_()->getItemTable('sescommunityads')->getAdapter();
    $db->beginTransaction();
    try {
       $table = Engine_Api::_()->getItemTable('sescommunityads');
       if(empty($ads))
       $ads = $table->createRow();
       $value['user_id'] = $this->view->viewer()->getIdentity();

       if(!empty($_POST['main_heading_title'])) {
        $value['title'] = $_POST['main_heading_title'];
       }

       if(!$campaign['campaign'] && $campaign['campaign_name']){
        $value['campaign_id']  = Engine_Api::_()->getDbTable('campaigns','sescommunityads')->createCampaign($campaign['campaign_name'], Engine_Api::_()->user()->getViewer()->getIdentity());
       }else
        $value['campaign_id'] = $campaign['campaign'];
       $value['type'] = $_POST['ad_type'];
       $value['subtype'] = $_POST['uploadType'];

       $value['resources_id'] = !empty($_POST['resource_id']) ? $_POST['resource_id'] : 0;
       $value['resources_type'] = !empty($_POST['resource_type']) ? $_POST['resource_type'] : '';

       $value['website_title'] = !empty($_POST['website_title']) ? $_POST['website_title'] : "";
       $value['website_url'] = !empty($_POST['website_url']) ? $_POST['website_url'] : "";

       $value['category_id'] = !empty($_POST['category_id']) ? $_POST['category_id'] : 0;
       $value['subcat_id'] = !empty($_POST['subcat_id']) ? $_POST['subcat_id'] : 0;
       $value['subsubcat_id'] = !empty($_POST['subsubcat_id']) ? $_POST['subsubcat_id'] : 0;

       $value['description'] = !empty($_POST['description']) ? $_POST['description'] : "";
        if($value['subtype'] == "video"){
            if(!empty($_FILES['image-video']['name']) && !empty($_FILES['image-video']['size'])){
                $value['video_src'] = $ads->setMorePhoto($_FILES['image-video'],true);
            }
        }
       if(!empty($value['resources_id']) && !empty($value['resources_type'])){
        $item = Engine_Api::_()->getItem($value['resources_type'],$value['resources_id']);
        if($item){
          $value['title'] = $item->getTitle();
          $value['description'] = $item->getPhotoUrl();
        }
       }
       if(!empty($value['website_title']) && !empty($value['website_url'])){
          $value['title'] = $value['website_title'];
          $value['description'] = $value['website_url'];
       }

       $value['startdate'] = $startdate;
       if(empty($tillEnd))
        $value['enddate'] = $enddate;
       else
        $value['enddate'] = "";
       if(empty($sescommunityad_id)){
         $value['is_approved'] = $package->auto_approve;
         if($value['is_approved']){
          $value['status'] = 1;
          $value['approved_date'] = date('Y-m-d H:i:s');
         }
         $value['featured'] = $package->featured;
         $value['featured_date'] = $package->featured_days > 0 ? date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')." + ".$package->featured_days.' days')) : 0;
         $value['sponsored'] = $package->sponsored;
         $value['sponsored_date'] = $package->sponsored_days > 0 ? date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s')." + ".$package->sponsored_days.' days')) : 0;
       }
       if(!empty($_POST['call_to_action']))
       {
         $value['calltoaction'] = $_POST['call_to_action'];
         //$value['calltoaction_url'] = $_POST['calltoaction_url'];
       }

       $value['package_id'] = $package->getIdentity();
       if(empty($sescommunityad_id))
        $value['creation_date'] = date('Y-m-d H:i:s');
       $value['modified_date'] = date('Y-m-d H:i:s');
       if(empty($sescommunityad_id)){
          $existingpackage_id = $this->_getParam('existingpackage',0);
          $existingpackage = Engine_Api::_()->getItem('sescommunityads_orderspackage', $existingpackage_id);
          if (!empty($existingpackage)) {
            $executed = true;
            $value['package_id'] = $package->getIdentity();
            if ($package) {
              $value['existing_package_order'] = $existingpackage->getIdentity();
              $value['orderspackage_id'] = $existingpackage->getIdentity();
              $existingpackage->item_count = $existingpackage->item_count - 1;
              $existingpackage->save();
              if($existingpackage->state == "active"){
                $value['state'] = "active";
                $value['status'] = 1;
              }
              $value['ad_type'] = $package->click_type;
              if($package->click_limit) {
                if($package->click_type == "perday")
                  $value['ad_expiration_date'] = date('Y-m-d H:i:s',strtotime("+ ".$package->click_limit." days"));
                $value['ad_limit'] = $package->click_limit;
              }else
                $value['ad_limit'] = "-1";
            }
          } else {
            if (!isset($package))
              $value['package_id'] = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getDefaultPackage();
          }
       }
       $defaultPackage = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getDefaultPackage();
       $ads->setFromArray($value);
       $ads->save();
      // Auth
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      $viewMax = array_search('everyone', $roles);
      $commentMax = array_search('everyone', $roles);
      foreach( $roles as $i => $role ) {
        $auth->setAllowed($ads, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($ads, $role, 'comment', ($i <= $commentMax));
      }
      //mark active for default package ads
      if($defaultPackage == $ads->package_id && empty($executed) || (empty($sescommunityad_id) && $package->price < 1)){
        if(empty($sescommunityad_id) && empty($existingpackage_id)){
          //insert order package data
          $transactionsOrdersTable = Engine_Api::_()->getDbtable('orderspackages', 'sescommunityads');
            $expiration = $package->getExpirationDate();
            if(filter_var($expiration, FILTER_VALIDATE_INT) !== false){
                $expiration = date('Y-m-d H:i:s',$expiration);
            }


          $transactionsOrdersTable->insert(array(
              'owner_id' => $this->view->viewer()->getIdentity(),
              'item_count' => ($package->item_count - 1 ),
              'package_id' => $package->getIdentity(),
              'state' => 'active',
              'expiration_date' => $expiration ? $expiration : "0000:00:00 00:00:00",
              'ip_address' => $_SERVER['REMOTE_ADDR'],
              'creation_date' => new Zend_Db_Expr('NOW()'),
              'modified_date' => new Zend_Db_Expr('NOW()'),
          ));
          $orderPackageId = $transactionsOrdersTable->getAdapter()->lastInsertId();
        }
        $ads->state = "active";
        $ads->status = 1;
        $ads->ad_type = $package->click_type;
        if($package->click_limit) {
          if($package->click_type == "perday"){
              $ads->ad_limit = $package->click_limit;
            $ads->ad_expiration_date = date('Y-m-d H:i:s',strtotime("+ ".$package->click_limit." days"));
          }else{
            $ads->ad_limit = $package->click_limit;
          }
        }else{
          //unlimited
          $ads->ad_limit = "-1";
        }
        $ads->save();
     }

       $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
       if(!empty($_POST['location']) && !empty($_POST['lat']) && !empty($_POST['lng'])){
          $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng , resource_type) VALUES ("' . $ads->getIdentity() . '", "' . $_POST['lat'] . '","' . $_POST['lng'] . '","'.$ads->getType().'")	ON DUPLICATE KEY UPDATE lat = "' . $_POST['lat'] . '" , lng = "' . $_POST['lng'] . '"');
          $ads->location = $_POST['location'];
          $ads->location_type = $_POST['location_type'];
          $ads->location_distance = !empty($_POST['location_distance']) ? $_POST['location_distance'] : 100;
          $ads->save();
       }else{
           $dbGetInsert->query("DELETE FROM engine4_sesbasic_locations WHERE resource_id =".$ads->getIdentity().' and resource_type = "'.$ads->getType().'"');
           $ads->location = "";
           $ads->location_distance = 100;
           $ads->location_type = "";
           $ads->save();
       }

       if(!empty($_POST['revselocation']) && !empty($_POST['revselat']) && !empty($_POST['revselng'])){
          $dbGetInsert->query('INSERT INTO engine4_sescommunityads_locations (resource_id, lat, lng , resource_type) VALUES ("' . $ads->getIdentity() . '", "' . $_POST['revselat'] . '","' . $_POST['revselng'] . '","'.$ads->getType().'") ON DUPLICATE KEY UPDATE lat = "' . $_POST['revselat'] . '" , lng = "' . $_POST['revselng'] . '"');
          $ads->revselocation = $_POST['revselocation'];
          $ads->revselocation_type = $_POST['revselocation_type'];
          $ads->revselocation_distance = !empty($_POST['revselocation_distance']) ? $_POST['revselocation_distance'] : 100;
          $ads->save();
       }else{
           $dbGetInsert->query("DELETE FROM engine4_sescommunityads_locations WHERE resource_id =".$ads->getIdentity().' and resource_type = "'.$ads->getType().'"');
           $ads->revselocation = "";
           $ads->revselocation_distance = 100;
           $ads->revselocation_type = "";
           $ads->save();
       }

       if(!empty($sescommunityad_id)){
          //remove all previous attachment of ads
          $db = Engine_Db_Table::getDefaultAdapter();
          $db->query("DELETE FROM engine4_sescommunityads_attachments WHERE sescommunityad_id = ".$sescommunityad_id);
       }

       //upload attachments
       if(!empty($_POST['heading1'])){
          $counterAttachment = 0;
          foreach($_POST['heading1'] as $heading){
            $attachment['title'] = $heading;
            $attachment['description'] = $_POST['description1'][$counterAttachment];
            $attachment['destination_url'] = $_POST['destinationurl1'][$counterAttachment];;
            $attachment['sescommunityad_id'] = $ads->getIdentity();

            if(empty($_POST['attachmentFileId'][$counterAttachment]) || !empty($_FILES['image'][0]['name'])){
              if(is_array($_FILES['image']['name'])){
                $fileArray['name'] = $_FILES['image']['name'][$counterAttachment];
                $fileArray['type'] = $_FILES['image']['type'][$counterAttachment];
                $fileArray['tmp_name'] = $_FILES['image']['tmp_name'][$counterAttachment];
                $fileArray['size'] = $_FILES['image']['size'][$counterAttachment];
                $fileArray['error'] = $_FILES['image']['error'][$counterAttachment];
              }else{
                $fileArray = $_FILES['image'];
              }
            }
            else{
                $attachment['file_id'] = $_POST['attachmentFileId'][$counterAttachment];
                $fileArray = array();
            }
            Engine_Api::_()->getDbTable('attachments','sescommunityads')->createAttachment($attachment,$fileArray);
            $counterAttachment++;
          }
       }else if(!empty($_POST['boost_post_id'])){
          $ads->resources_type = "sesadvancedactivity_action";
          $ads->resources_id = $_POST['boost_post_id'];
          $ads->save();
       }

        //Banner Image Work
        if(!empty($_POST['uploadType'] && !empty($_POST['uploadType'] == 'banner'))) {
            $counterAttachment = 0;

            $attachment['title'] = $_POST['banner_title'];
            //$attachment['description'] = $_POST['description1'][$counterAttachment];
            $attachment['destination_url'] = $_POST['destinationurl1'][$counterAttachment];;
            $attachment['sescommunityad_id'] = $ads->getIdentity();
            if($_POST['banner_type'] == 1) {
                if(is_array($_FILES['image-banner']['name'])) {
                    $fileArray['name'] = $_FILES['image-banner']['name'][$counterAttachment];
                    $fileArray['type'] = $_FILES['image-banner']['type'][$counterAttachment];
                    $fileArray['tmp_name'] = $_FILES['image-banner']['tmp_name'][$counterAttachment];
                    $fileArray['size'] = $_FILES['image-banner']['size'][$counterAttachment];
                    $fileArray['error'] = $_FILES['image-banner']['error'][$counterAttachment];
                } else {
                    $fileArray = $_FILES['image-banner'];
                }
            }
            //Rented Work
            if(!empty($_POST['widgetid']))
                $ads->widgetid = $_POST['widgetid'];
            if(!empty($_POST['banner_title']))
                $ads->title = $_POST['banner_title'];

            $ads->banner_id = $_POST['banner_id'];
            $ads->banner_type = $_POST['banner_type'];
            if(!empty($_POST['html_code'])) {
                $ads->html_code = $_POST['html_code'];
            } else {
                $ads->html_code = "";
            }
            $ads->save();
            Engine_Api::_()->getDbTable('attachments','sescommunityads')->createAttachment($attachment,$fileArray,$_POST['banner_id']);
            $counterAttachment++;
        }

       if(($value['type'] == "promote_content_cnt" || $value['type'] == "promote_website_cnt") && !empty($_POST['add_card'])){
         if(!empty($_FILES['more_image']['name']))
          $ads->setMorePhoto($_FILES['more_image']);
         $ads->see_more_url = $_POST['see_more_url'];
         $ads->see_more_display_link = $_POST['see_more_display_link'];
         $ads->call_to_action_overlay = $_POST['call_to_action_overlay'];
       }else{
         $ads->more_image = 0;
         $ads->see_more_url = '';
         $ads->see_more_display_link = '';
         $ads->call_to_action_overlay = '';
       }
       if( $value['type'] == "promote_website_cnt"){
          if(!empty($_FILES['website_image']['name']) && $_FILES['website_image']['size'] > 0){
            $ads->website_image = $ads->setMorePhoto($_FILES['website_image'],true);
            $ads->save();
          }
       }
       $ads->save();
       //targetting sql
       $targetting = Engine_Api::_()->getDbTable('targetads','sescommunityads')->createTargets($targetData,$ads,$networks,$sescommunityad_id, $interests);

        //Notification
        if(empty($sescommunityad_id)) {

            $packageCheck = Engine_Api::_()->getItem('sescommunityads_packages', $ads->package_id);

            if(!empty($packageCheck->auto_approve)) {
                $getSuperAdmins = Engine_Api::_()->user()->getSuperAdmins();

                foreach($getSuperAdmins as $getSuperAdmin) {
                    $admin = Engine_Api::_()->getItem('user', $getSuperAdmin->user_id);

                    //Send email to user
                    $link = '/ads/view/ad_id/'.$ads->sescommunityad_id;

                    $notificationlink = '<a href="' . $link . '">' . $ads->getTitle() . '</a>';
                    Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($admin, Engine_Api::_()->user()->getViewer(), $ads, 'sescommunityads_newadscradmin', array("adsLink" => $notificationlink));

//                     Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin->email, 'sescommunityads_newadscreateadmin', array('host' => $_SERVER['HTTP_HOST'], 'queue' => false, 'title' => $ads->title, 'description' => $ads->description, 'ad_link' => $link));
                }
            } else if(empty($packageCheck->auto_approve)) {
                $getSuperAdmins = Engine_Api::_()->user()->getSuperAdmins();

                foreach($getSuperAdmins as $getSuperAdmin) {
                    $admin = Engine_Api::_()->getItem('user', $getSuperAdmin->user_id);

                    //Send email to user
                    $link = '/ads/view/ad_id/'.$ads->sescommunityad_id;
                    $notificationlink = '<a href="' . $link . '">' . $ads->getTitle() . '</a>';
                    Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($admin, Engine_Api::_()->user()->getViewer(), $ads, 'sescommunityads_adminapproval', array("adsLink" => $notificationlink));


//                     Engine_Api::_()->getApi('mail', 'core')->sendSystem($admin->email, 'sescommunityads_newadscreateadminapproval', array('host' => $_SERVER['HTTP_HOST'], 'queue' => false, 'title' => $ads->title, 'description' => $ads->description, 'ad_link' => $link));
                }
            }
        }

       $db->commit();
       echo json_encode(array('error'=>0,'url'=>$this->view->url(array('action' => 'view','ad_id'=>$ads->getIdentity()),'sescommunityads_general', true)));die;
    }catch(Exception $e){
      throw $e;
      echo json_encode(array('error'=>1,'message'=>$e->getMessage()));die;
    }
  }

  public function getBannerSizes($banner_id = 0) {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $html = "";
    $getBanner = Engine_Api::_()->getDbTable('banners', 'sescomadbanr')->getBanner(array('fetchAll' => 1));
    if(engine_count($getBanner)){
      //$html = "<option value=''>".$this->view->translate('Choose Banner Size')."</option>";
      foreach($getBanner as $banner){
        $select = "";
        if($selected == $banner)
          $select = "selected";
        $title = $banner->banner_name . ' ('.$banner->width.'px'.'*'.$banner->height.'px'.')';
        if(@$banner_id == $banner->banner_id) {
            $html .="<option selected='selected' data-width='".$banner->width."' data-height='".$banner->height."' value='".$banner->banner_id."' ".$select.">".$this->view->translate($title)."</option>";
        } else {
            $html .="<option  data-width='".$banner->width."' data-height='".$banner->height."' value='".$banner->banner_id."' ".$select.">".$this->view->translate($title)."</option>";
        }
      }
    }
    return $html;

  }

  function callToActionBtn($selected = ""){
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $html = "";
    $calltoaction = unserialize($settings->getSetting('sescommunityads.call.toaction', ('a:14:{i:0;s:12:"request_time";i:1;s:9:"apply_now";i:2;s:8:"book_now";i:3;s:10:"contact_us";i:4;s:8:"download";i:5;s:13:"get_showtimes";i:6;s:10:"learn_more";i:7;s:10:"listen_now";i:8;s:12:"show_message";i:9;s:8:"see_menu";i:10;s:8:"shop_now";i:11;s:7:"sign_up";i:12;s:9:"subscribe";i:13;s:10:"watch_more";}')));
    if(engine_count($calltoaction)){
      $html = "<option value=''>".$this->view->translate('No Button')."</option>";
      foreach($calltoaction as $action){
        $select = "";
        if($selected == $action)
          $select = "selected";
        $html .="<option value='".$action."' ".$select.">".$this->view->translate(ucwords(str_replace('_',' ',$action)))."</option>";
      }
    }
    return $html;
  }
  public function moduleDataAction(){
    $type = $this->_getParam('type');
    $selected = $this->_getParam('selected');
    if(!$type){
      echo false;die;
    }
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $itemTable = Engine_Api::_()->getItemTable($type);
    $checkUser_id = $db->query('SHOW COLUMNS FROM '.$itemTable->info('name').' LIKE \'user_id\'')->fetch();
	  $columnName = "";
		if (!empty($checkUser_id)) {
      $columnName = "user_id";
    }else{
        $checkUser_id = $db->query('SHOW COLUMNS FROM '.$itemTable->info('name').' LIKE \'owner_id\'')->fetch();
        if (!empty($checkUser_id)) {
          $columnName = "owner_id";
        }
    }
    if(!$columnName){
      echo false;die;
    }

    //get all content related to type
    $select = $itemTable->select()->where($columnName.' =?',$this->view->viewer()->getIdentity());
    $items = $itemTable->fetchAll($select);
    if(engine_count($items)){
      $options = "<option></option>";
      foreach($items as $item){
         $selectData = "";
         if($selected == $item->getIdentity())
          $selectData = "selected";
         $options .="<option value='".$item->getIdentity()."' data-src='".$item->getPhotoUrl()."' ".$selectData.">".($item->getTitle() ? $item->getTitle() : "Untitled")."</option>";
      }
      echo $options;die;
    }else{
        echo false;die;
    }

  }
  public function cancelAction() {
    $packageId = $this->_getParam('package_id', 0);

    $this->view->form = $form = new Sescommunityads_Form_Cancel();

    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    Engine_Api::_()->getDbTable('packages','sescommunityads')->cancelSubscription(array('package_id' => $packageId));

    $this->_forward('success', 'utility', 'core', array(
        'smoothboxClose' => true,
        'parentRefresh' => true,
        'messages' => array(Zend_Registry::get('Zend_Translate')->_('Your Package Subscription has been Deleted Successfully.'))
    ));
  }
  public function packageAction(){
      //Render
    if (!$this->_helper->requireUser->isValid())
      return;
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->package = Engine_Api::_()->getDbTable('packages','sescommunityads')->getPackage(array('member_level'=>$viewer->level_id,'enabled'=>1,'action_id'=>$this->_getParam('action_id'), 'param' => 1));
		$this->view->existingleftpackages = Engine_Api::_()->getDbTable('orderspackages','sescommunityads')->getLeftPackages(array('owner_id'=>$viewer->getIdentity(),'action_id'=>$this->_getParam('action_id')));
    $this->view->action_id = $this->_getParam('action_id');
    $this->renderScript('index/adsplan.tpl');
    $this->_helper->content->setEnabled();
  }

  public function subcategoryAction() {
    $category_id = $this->_getParam('category_id', null);
    if ($category_id) {
			$subcategory = Engine_Api::_()->getDbtable('categories', 'sescommunityads')->getModuleSubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';

      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;
    die;
  }

  public function subsubcategoryAction() {

    $category_id = $this->_getParam('subcategory_id', null);
    if ($category_id) {
      $subcategory = Engine_Api::_()->getDbtable('categories', 'sescommunityads')->getModuleSubsubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;die;
  }
  public function redirectAction(){
    $redirect = urldecode($this->_getParam('redirect'));
    $redirect = Engine_Api::_()->sescommunityads()->decrypt($redirect);
    if(strpos($redirect,'CDSs') === false && strpos($redirect,'CDSsubject') === false){
      list($blank,$attachment_id,$sescommunityad_id) = explode('CDS',$redirect);
      $attachment = Engine_Api::_()->getItem('sescommunityads_attachment',$attachment_id);
      $ads = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
      if(!$attachment)
        return $this->_forward('notfound', 'error', 'core');
      else if(!$ads)
          return $this->_forward('notfound', 'error', 'core');
      $url = $attachment->destination_url;
    }else if(strpos($redirect,'CDSsubject') === false){
       list($blank,$sescommunityad_id) = explode('CDSs',$redirect);
      $ads = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);
      if(!$ads)
         return $this->_forward('notfound', 'error', 'core');
      $url = $ads->see_more_url;
    }else{
      list($blank,$sescommunityad_id) = explode('CDSsubject',$redirect);
      $ads = Engine_Api::_()->getItem('sescommunityads',$sescommunityad_id);

      if(!$ads)
         return $this->_forward('notfound', 'error', 'core');
      if($ads->type != "promote_website_cnt"){
        $item = Engine_Api::_()->getItem($ads->resources_type,$ads->resources_id);
        if(!$item)
          return $this->_forward('notfound', 'error', 'core');
        $url = $item->getHref();
        if(Zend_Registry::get('StaticBaseUrl') != "/")
          $url = str_replace(Zend_Registry::get('StaticBaseUrl'),'',$url);
      }else{
        $url = $ads->description;
      }
    }
    if($url){
      if($ads->user_id != $this->view->viewer()->getIdentity()){
        //increase click count
        $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$ads->campaign_id);
        $campaign->click_count++;
        $campaign->save();
        $ads->click_count++;
        $ads->save();
        Engine_Api::_()->getDbTable('clickstats','sescommunityads')->insertRow($ads,$this->view->viewer());
        //insert campaign stats
        Engine_Api::_()->getDbTable('campaignstats','sescommunityads')->insertrow($ads,$this->view->viewer(),'click');
      }
      $this->redirect($url);
    }else
      return $this->_forward('notfound', 'error', 'core');
  }
  function deleteAction(){
      $this->_helper->layout->setLayout('default-simple');
    $this->view->sescommunityad_id = $id = $this->_getParam('sescommunityad_id');
    $type = $this->_getParam('type');
    $this->view->form = $form = new Sesbasic_Form_Admin_Delete();

    $item = Engine_Api::_()->getItem('sescommunityads', $id);
    $form->setTitle('Delete Ad?');
    $form->setDescription('Are you sure that you want to delete this ad? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');

    //Check post
    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        //$item->is_deleted = 1;
        $item->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array('You have successfully deleted this ad.')
      ));
    }
  }
  public function reportAction()
  {
    if (!$this->_helper->requireUser->isValid())
      return;
    //Render
    $this->_helper->content->setEnabled();
  }

  public function helpAndLearnAction()
  {
    //Render
    $this->_helper->content->setEnabled();
  }
}
