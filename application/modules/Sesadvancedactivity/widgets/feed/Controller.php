<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Widget_FeedController extends Engine_Content_Widget_Abstract {
	
	private $_blockedUserIds = array();
	
  public function indexAction() {
  
    $widgetIds = $this->_getParam('widgetIds',0);
    if($widgetIds){
      $params = Engine_Api::_()->sescommunityads()->getWidgetParams($widgetIds);
      $request = $this->getRequest();
      $request->setParams($params);
      $_SESSION['fromActivityFeed'] = 1;
    }
    //community ads ids
    $this->view->communityadsIds = $this->_getParam('ads_ids',false);
    $this->view->isGoogleApiKeySaved = (Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.mapApiKey', '') && Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', '1')) ? true : false;

    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = null;
    if( Engine_Api::_()->core()->hasSubject() ) {
      // Get subject
      $subject = Engine_Api::_()->core()->getSubject();
      if($subject->getType() == 'sespage_page') {
				if(!Engine_Api::_()->getDbTable('pages', 'sespage')->isPageViewPermission(array('page_id' => $subject->page_id))) {
					return $this->setNoRender();
				}
      } /*elseif($subject->getType() == 'sesgroup_group') {
				if(!Engine_Api::_()->getDbTable('groups', 'sesgroup')->isGroupViewPermission(array('group_id' => $subject->group_id))) {
					return $this->setNoRender();
				}
      }*/ else {
				if( !$subject->authorization()->isAllowed($viewer, 'view') )
					return $this->setNoRender();
			}
    }
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $requestParams = $request->getParams();

		// Pinfeed
		$this->view->feeddesign = $this->_getParam('feeddesign',1);
		$this->view->widthPa = $this->_getParam('widthpinboard','250');
  	$this->view->widgetTitle = $this->_getParam('title','');
	  $this->view->isMemberHomePage = isset($_REQUEST['isMemberHomePage']) ? $_REQUEST['isMemberHomePage'] : $requestParams['action'] == 'home' && $requestParams['module'] == 'user' && $requestParams['controller'] == 'index';
    $this->view->isLandingPage = isset($_REQUEST['isOnLandingPage']) ? $_REQUEST['isOnLandingPage'] : $requestParams['action'] == 'index' && $requestParams['module'] == 'core' && $requestParams['controller'] == 'index';
    $this->view->isOnThisDayPage = isset($_REQUEST['isOnThisDayPage']) ? $_REQUEST['isOnThisDayPage'] :  $requestParams['action'] == 'onthisday' && $requestParams['module'] == 'sesadvancedactivity' && $requestParams['controller'] == 'index';
    if($this->view->isOnThisDayPage)
      $subject = Engine_Api::_()->user()->getViewer();
    $this->view->advcomment = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment');
    $actionTable = Engine_Api::_()->getDbtable('actions', 'sesadvancedactivity');
    $this->view->usersettings =  Engine_Api::_()->getApi('settings', 'core')->getSetting($viewer->getIdentity().'.activity.user.setting','everyone');
    // Get some options
    $this->view->design = $this->_getParam('design',2);
    $this->view->design = $this->view->design > 2 ? 2 : $this->view->design;
    $this->view->enablestatusbox = $enablestatusbox = $this->_getParam('enablestatusbox',2);
    if(@$enablestatusbox == ''){
        $this->view->enablestatusbox = 2;
    }

    $this->view->userphotoalign = $this->_getParam('userphotoalign','left');

    $this->view->statusplacehoder = $this->_getParam('statusplacehoder', "Post Something...");

    $this->view->sesfeedbg_limit_show = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesfeedbg.limit.show', 12);

    //tab icon and text setting
    $this->view->welcometabtext = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.welcometabtext','Welcome');
    $this->view->welcomeicon = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.welcomeicon','icon');
    $this->view->whatsnewtext = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.whatsnewtext','What\'s New');
    $this->view->whatsnewicon = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.whatsnewicon','icon');

    $this->view->sesact_pinboard_width = $this->_getParam('sesact_pinboard_width', '300');
    //Image widget and height
    $this->view->enablewidthsetting = $this->_getParam('enablewidthsetting', 0);
    $this->view->sesact_image1_width = $this->_getParam("sesact_image1_width", 500);
    $this->view->sesact_image1_height = $this->_getParam("sesact_image1_height", 450);

    $this->view->sesact_image2_width = $this->_getParam("sesact_image2_width", 289);
    $this->view->sesact_image2_height = $this->_getParam("sesact_image2_height", 200);

    $this->view->sesact_image3_bigwidth = $this->_getParam("sesact_image3_bigwidth", 328);
    $this->view->sesact_image3_bigheight = $this->_getParam("sesact_image3_bigheight", 300);
    $this->view->sesact_image3_smallwidth = $this->_getParam("sesact_image3_smallwidth", 250);
    $this->view->sesact_image3_smallheight = $this->_getParam("sesact_image3_smallheight", 150);

    $this->view->sesact_image4_bigwidth = $this->_getParam("sesact_image4_bigwidth", 578);
    $this->view->sesact_image4_bigheight = $this->_getParam("sesact_image4_bigheight", 300);
    $this->view->sesact_image4_smallwidth = $this->_getParam("sesact_image4_smallwidth", 192);
    $this->view->sesact_image4_smallheight = $this->_getParam("sesact_image4_smallheight", 100);

    $this->view->sesact_image5_bigwidth = $this->_getParam("sesact_image5_bigwidth", 289);
    $this->view->sesact_image5_bigheight = $this->_getParam("sesact_image5_bigheight", 260);
    $this->view->sesact_image5_smallwidth = $this->_getParam("sesact_image5_smallwidth", 289);
    $this->view->sesact_image5_smallheight = $this->_getParam("sesact_image5_smallheight", 130);

    $this->view->sesact_image6_width = $this->_getParam("sesact_image6_width", 289);
    $this->view->sesact_image6_height = $this->_getParam("sesact_image6_height", 150);

    $this->view->sesact_image7_bigwidth = $this->_getParam("sesact_image7_bigwidth", 192);
    $this->view->sesact_image7_bigheight = $this->_getParam("sesact_image7_bigheight", 150);
    $this->view->sesact_image7_smallwidth = $this->_getParam("sesact_image7_smallwidth", 144);
    $this->view->sesact_image7_smallheight = $this->_getParam("sesact_image7_smallheight", 150);

    $this->view->sesact_image8_width = $this->_getParam("sesact_image8_width", 144);
    $this->view->sesact_image8_height = $this->_getParam("sesact_image8_height", 150);

    $this->view->sesact_image9_width = $this->_getParam("sesact_image9_width", 192);
    $this->view->sesact_image9_height = $this->_getParam("sesact_image9_height", 150);

    $sesadvancedactivity_feedwidget = Zend_Registry::isRegistered('sesadvancedactivity_feedwidget') ? Zend_Registry::get('sesadvancedactivity_feedwidget') : null;
    if(empty($sesadvancedactivity_feedwidget)) {
      return $this->setNoRender();
    }

    $this->view->allowprivacysetting = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.allowprivacysetting',1);
    if(! $this->view->allowprivacysetting)
       $this->view->usersettings =  'everyone';
    $this->view->contentCount = $this->_getParam('contentCount',0);
    $this->view->autoloadfeed = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.autoloadfeed',1);
    $this->view->submitWithAjax = true;
    $this->view->filterFeed  = $filterFeed = $this->_getParam('filterFeed','all');
    if($subject && empty($_POST) && $filterFeed == "all"){
         $this->view->filterFeed = $filterFeed = "own";
    }
    $this->view->scrollfeed = $this->_getParam('scrollfeed', 1);
    $this->view->autoloadTimes    = $this->_getParam('autoloadTimes', 3);
    $this->view->enableStatusBoxHighlight = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.highlightstatusbox', 0);
    if(!$this->view->autoloadTimes)
      $this->view->autoloadTimes = 100000000;
    $this->view->feedOnly         = $feedOnly = $request->getParam('feedOnly', false);
    $this->view->length           = $length = $request->getParam('limit', Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.length', 15));
    $this->view->itemActionLimit  = $itemActionLimit = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.userlength', 5);
    $this->view->updateSettings   = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.liveupdate');
    $this->view->viewAllLikes     = $request->getParam('viewAllLikes',    $request->getParam('show_likes',    false));
    $this->view->viewAllComments  = $request->getParam('viewAllComments', $request->getParam('show_comments', false));
    $this->view->getUpdate        = $request->getParam('getUpdate');
    $this->view->checkUpdate      = $request->getParam('checkUpdate');
    $this->view->action_id        = $this->_getParam('action_id',(int) $request->getParam('action_id'));
    $this->view->post_failed      = (int) $request->getParam('pf');
    $composePartials = $composerOptions = array();
    if( $feedOnly ) {
      $this->getElement()->removeDecorator('Title');
      $this->getElement()->removeDecorator('Container');
    }
    //else{
     // $this->view->allownetworkprivacy = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.allownetworkprivacy',1);
      $this->view->allownetworkprivacy = $allownetworkprivacytype = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.network.privacy',0);

      $this->view->allowlistprivacy = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.allowlistprivacy',1);
      if($allownetworkprivacytype == 1){
        $select = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfSelect($viewer)->order('engine4_network_networks.title ASC');
      }
      else if($allownetworkprivacytype == 2){
        $select = Engine_Api::_()->getDbtable('networks', 'network')->select()->order('engine4_network_networks.title ASC');
      }
      else{
        $select = Engine_Api::_()->getDbtable('networks', 'network')->select()->where(0);
      }
      $this->view->usernetworks = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll($select);
      $this->view->userlists = Engine_Api::_()->getDbtable('lists', 'user')->fetchAll(Engine_Api::_()->getDbtable('lists', 'user')->select()->order('engine4_user_lists.title ASC')->where('owner_id =?',$viewer->getIdentity()));
      $this->view->networkbasedfilter = false;
      //network based filtering
      $networkbasedfiltering = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.networkbasedfiltering',1);
     if($networkbasedfiltering != 2){ 
      if($networkbasedfiltering == 1){
        $select = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfSelect($viewer)->order('engine4_network_networks.title ASC');
      }else{
        $select = Engine_Api::_()->getDbtable('networks', 'network')->select()->order('engine4_network_networks.title ASC');
      }
      $this->view->networkbasedfilter = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll($select);
     }
     // Assign the composing values

      $this->view->composerOptions = $composerOptions = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.composeroptions',array());
      $enableMemberOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesadvactivity', $viewer, 'composeroptions');
      $allowMemberLevel = array('fileupload','buysell','sesadvancedactivitytargetpost');
      foreach ($allowMemberLevel as $allowMemberLevelValue) {
        if(!engine_in_array($allowMemberLevelValue, $enableMemberOptions))
          if(engine_in_array($allowMemberLevelValue,$composerOptions))
            unset($composerOptions[array_search ($allowMemberLevelValue, $composerOptions)]);
        }
       $notArray = array();
       if(!engine_in_array('buysell',$composerOptions)){
        $notArray[] = 'post_self_buysell';
       }
       if(!engine_in_array('fileupload',$composerOptions)){
        $notArray[] = 'post_self_file';
       }
      $networkbasedfilter = $this->view->networkbasedfilter;
      $activeLists = Engine_Api::_()->getDbTable('filterlists','sesadvancedactivity')->getLists($notArray);

        $lists = $activeLists->toArray();
        //check module enable
        $listsArray = array();
        foreach($lists as $list){
          if(!$this->view->viewer()->getIdentity() && ($list['filtertype'] == "scheduled_post"  || $list['filtertype'] == "my_networks"  || $list['filtertype'] == "my_friends"  || $list['filtertype'] == "saved_feeds" || $list['filtertype'] == "sesmember" || $list['filtertype'] == "share"))
            continue;
           if($list['filtertype'] != 'all' && $list['filtertype'] != 'scheduled_post' && $list['filtertype'] != 'my_networks' && $list['filtertype'] != 'my_friends' && $list['filtertype'] != 'posts' && $list['filtertype'] != 'saved_feeds' && $list['filtertype'] != 'share' && $list['filtertype'] != 'post_self_buysell'  && $list['filtertype'] != 'post_self_file' && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled($list['filtertype']))
            continue;
           $listsArray[] = $list;
        }
      if($networkbasedfilter){
        $networkbasedfilter = $networkbasedfilter->toArray();
        foreach($networkbasedfilter as $networkbased){
         $listsArray[] = $networkbased;
        }
       }

       if(engine_count($this->view->userlists)){
          $listbasedfilter = $this->view->userlists->toArray();
          foreach($listbasedfilter as $listbased){
           $listsArray[] = $listbased;
          }
       }
       if(empty($_POST))
        $this->view->filterFeed  = $filterFeed = $listsArray[0]['filtertype'];
       if($subject && empty($_POST) && $filterFeed == "all"){
         $this->view->filterFeed = $filterFeed = "own";
        }
       $this->view->lists = $listsArray;
    //}
      if( $length > 50 ) {
        $this->view->length = $length = 50;
      }
      
      
			if( $viewer && !$viewer->isAdmin() ) {
				$this->_blockedUserIds = $viewer->getAllBlockedUserIds();
			}

     // Get all activity feed types for custom view?
     $actionTypesTable = Engine_Api::_()->getDbtable('actionTypes', 'sesadvancedactivity');
     $this->view->groupedActionTypes = $groupedActionTypes = $actionTypesTable->getEnabledGroupedActionTypes();
     $actionTypeGroup = $filterFeed;
     $actionTypeFilters = array();

    //SES advanced member plugin followig work
    $isSesmember = $actionTypeGroup == 'sesmember' && Engine_Api::_()->sesbasic()->isModuleEnable('sesmember') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active', 1);
    if(!$isSesmember){ 
      if( $actionTypeGroup && isset($groupedActionTypes[$actionTypeGroup]) ) {
        $actionTypeFilters = $groupedActionTypes[$actionTypeGroup];
        if($actionTypeGroup == 'sesalbum' || $actionTypeGroup == 'album')
          $actionTypeFilters = array_merge($actionTypeFilters,$groupedActionTypes['photo']);
        else if($actionTypeGroup == 'sesvideo')
          $actionTypeFilters = array_merge($actionTypeFilters,$groupedActionTypes['video']);
      }
    }

      if($actionTypeGroup == 'post_self_buysell')
        $actionTypeFilters = array('post_self_buysell');
      else if($actionTypeGroup == 'post_self_file')
        $actionTypeFilters = array('post_self_file');
      //else if(strpos($actionTypeGroup , 'network_filter_' ) !== false)
       // $actionTypeFilters = array('network');
    // Get config options for activity
    $hashTag = isset($_GET['hashtag']) ? $_GET['hashtag'] : '';
    $config = array(
      'action_id' => $this->view->action_id,
      'max_id'    => (int) $request->getParam('maxid'),
      'min_id'    => (int) $request->getParam('minid'),
      'limit'     => (int) $length,
      'showTypes' => $actionTypeFilters,
      'filterFeed'=>$filterFeed,
      'hashTag' => $hashTag,
      'targetPost'=>engine_in_array('sesadvancedactivitytargetpost',$composerOptions),
      'isOnThisDayPage'=>$this->view->isOnThisDayPage,
    );
    

    // Pre-process feed items
    $selectCount = 0;
    $nextid = null;
    $firstid = null;
    $tmpConfig = $config;
    $activity = array();
    $endOfFeed = false;
    $friendRequests = array();
    $itemActionCounts = array();
    $enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
      $actions = null;
    do {
      // Get current batch
     if($this->_getParam("getUpdates") || $this->view->action_id) {
        
         $this->view->getUpdates = 1;
         // Where the Activity Feed is Fetched
         if($this->_getParam("myAdContent")){

          $tabelAcion = Engine_Api::_()->getDbTable("actions",'sesadvancedactivity');
          $actionTableName = $tabelAcion->info('name');
          $detailsTable = Engine_Api::_()->getDbtable('details', 'sesadvancedactivity');
          $detailsTableName = $detailsTable->info('name');
          $actions =  $tabelAcion->fetchAll(
            $tabelAcion->select()
              ->from($tabelAcion->info('name'),'*')
              ->setIntegrityCheck(false)
              ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", Engine_Api::_()->sesadvancedactivity()->getDetailsTableColumn())
              ->where($actionTableName.'.action_id IN('.$this->view->action_id.')')
              ->order($actionTableName.'.action_id DESC')
              ->limit(1)
          );
        }else if (!empty($subject)) { 
             $actions = $actionTable->getActivityAbout($subject, $viewer, $tmpConfig);
         } else {
             $actions = $actionTable->getActivity($viewer, $tmpConfig);
         }
     }else{
         $this->view->getUpdates = 0;
     }
      $selectCount++;
      // Are we at the end?
      if( !$actions || engine_count($actions) < $length || engine_count($actions) <= 0 ) {
        $endOfFeed = true;
      }
      // Pre-process
       if( !empty($actions) && engine_count($actions) > 0 ) {
        foreach( $actions as $action ) {
          if(isset($action->group_action_id)){
          $action_id = $action->group_action_id;
          $explodedData = explode(',',$action_id);
          if($explodedData > 1){ 
              $action_id = max($explodedData);
              $action = Engine_Api::_()->getItem('sesadvancedactivity_action',$action_id);
          }
          }else{
            $action_id = $action->action_id;
          }
          
          $isPinned = false;
          $pinSubject = !$this->view->subject() ? false : $this->view->subject() ;
          if($pinSubject)
            $isPinned = $action->isPinPost(array('resource_type'=>$pinSubject->getType(),'resource_id'=>$pinSubject->getIdentity(),'action_id'=>$action->getIdentity()));
          // get next id
          if( (null === $nextid || $action_id <= $nextid) && !$isPinned) {
            $nextid = $action->action_id - 1;
          }
          // get first id
          if( null === $firstid || $action_id > $firstid ) {
            $firstid = $action_id;
          }
          // skip disabled actions
          if( !$action->getTypeInfo() || !$action->getTypeInfo()->enabled ) continue;
          // skip items with missing items
          if( !$action->getSubject() || !$action->getSubject()->getIdentity() ) continue;
          if( !$action->getObject() || !$action->getObject()->getIdentity() ) continue;
          // track/remove users who do too much (but only in the main feed)
          if( empty($subject) ) {
            $actionSubject = $action->getSubject();
            if( !isset($itemActionCounts[$actionSubject->getGuid()]) ) {
              $itemActionCounts[$actionSubject->getGuid()] = 1;
            } else if( $itemActionCounts[$actionSubject->getGuid()] >= $itemActionLimit ) {
              continue;
            } else {
              $itemActionCounts[$actionSubject->getGuid()]++;
            }
          }
          
					if( $this->isBlocked($action) ) {
            continue;
          }
          
//           //privacy for fourm
//           if(in_array($action->type, array('sesforum_topic_create','sesforum_topic_reply'))) {
// 						if($viewer->getIdentity())
// 							$levelId = $viewer->level_id;
// 						else
// 							$levelId = 5;
// 							
// 						if($action->getObject()->getType() == 'sesforum_topic') {
// 							$forumItem = Engine_Api::_()->getItem('sesforum_forum', $action->getObject()->forum_id);
// 							$levels = explode(',', $forumItem->levels);
// 							if(!engine_in_array($levelId, $levels)) continue;
// 						} else if($action->getObject()->getType() == 'sesforum_post') {
// 							$forumItem = Engine_Api::_()->getItem('sesforum_forum', $action->getObject()->forum_id);
// 							$levels = explode(',', $forumItem->levels);
// 							if(!engine_in_array($levelId, $levels)) continue;
// 						}
//           }
          
          // remove duplicate friend requests
          if( $action->type == 'friends' ) {
            $id = $action->subject_id . '_' . $action->object_id;
            $rev_id = $action->object_id . '_' . $action->subject_id;
            if( engine_in_array($id, $friendRequests) || engine_in_array($rev_id, $friendRequests) ) {
              continue;
            } else {
              $friendRequests[] = $id;
              $friendRequests[] = $rev_id;
            }
          }
          // remove items with disabled module attachments
          try {
            $attachments = $action->getAttachments();
          } catch (Exception $e) {
            // if a module is disabled, getAttachments() will throw an Engine_Api_Exception; catch and continue
            continue;
          }
          // add to list
          if( engine_count($activity) < $length ) {
            $activity[] = $action;
            if( engine_count($activity) == $length ) {
              break;
            }
          }
        }
      }
      // Set next tmp max_id
      if( $nextid ) {
        $tmpConfig['max_id'] = $nextid;
      }
      if( !empty($tmpConfig['action_id']) ) {
        $actions = array();
      }
    }
    while( engine_count($activity) < $length && $selectCount <= 3 && !$endOfFeed );
    $this->view->activity = $activity;
    $this->view->activityCount = engine_count($activity);
    $this->view->nextid = $nextid;
    $this->view->firstid = $firstid;
    $this->view->endOfFeed = $endOfFeed;
    // Get some other info
    if( !empty($subject) ) {
      $this->view->subjectGuid = $subject->getGuid(false);
    }
    $this->view->enableComposer = false;
    if( $viewer->getIdentity() && !$this->view->action_id) {
      if( !$subject || ($subject instanceof Core_Model_Item_Abstract && $subject->isSelf($viewer)) ) {
        if( Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'user', 'status') ) {
          $this->view->enableComposer = true;
        }
      } else if( $subject ) {
        if($subject->getType() == 'businesses') {
					$row = $subject->membership()->getRow($viewer);
          $isAdmin = Engine_Api::_()->getDbTable('businessroles', 'sesbusiness')->isAdmin(array('business_id' => $subject->getIdentity(), 'user_id' => $viewer->getIdentity()));
          if($isAdmin)
            $this->view->enableComposer = true;
					if($row->active)
            $this->view->enableComposer = true;
        } else if($subject->getType() == 'sespage_page') {
					$row = $subject->membership()->getRow($viewer);
          $isAdmin = Engine_Api::_()->getDbTable('pageroles', 'sespage')->isAdmin(array('page_id' => $subject->getIdentity(), 'user_id' => $viewer->getIdentity()));
          if($isAdmin)
            $this->view->enableComposer = true;
					if($row->active)
            $this->view->enableComposer = true;
        } else if($subject->getType() == 'sesgroup_group') {
          $row = $subject->membership()->getRow($viewer);
          $isAdmin = Engine_Api::_()->getDbTable('grouproles', 'sesgroup')->isAdmin(array('group_id' => $subject->getIdentity(), 'user_id' => $viewer->getIdentity()));
          if($isAdmin)
            $this->view->enableComposer = true;
          if($row->active)
            $this->view->enableComposer = true;
        } else if($subject->getType() == 'stores') {
					$row = $subject->membership()->getRow($viewer);
          $isAdmin = Engine_Api::_()->getDbTable('storeroles', 'estore')->isAdmin(array('store_id' => $subject->getIdentity(), 'user_id' => $viewer->getIdentity()));
          if($isAdmin)
            $this->view->enableComposer = true;
					if($row->active)
            $this->view->enableComposer = true;
        }
        else {
          if( Engine_Api::_()->authorization()->isAllowed($subject, $viewer, 'comment') ) {
            $this->view->enableComposer = true;
          }
        }
      }
    }
    foreach( Zend_Registry::get('Engine_Manifest') as $key=>$data ) {
      if( empty($data['composer']) ) {
        continue;
      }
      foreach( $data['composer'] as $type => $config ) {
        if((!engine_in_array($type,$composerOptions) && $type != 'sesadvancedactivityfacebook' && $type != 'sesadvancedactivitytwitter' && $type != 'sesadvancedactivitylinkedin') )
          continue;
       ;
        if( !empty($config['auth']) && !Engine_Api::_()->authorization()->isAllowed($config['auth'][0], null, $config['auth'][1]) ) {
            continue;
        }
        if($type == 'photo' && !Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum') && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album')){
          $config['script'][0] = '_composeadvancedactivityphoto.tpl';
          $config['script'][1] = 'sesadvancedactivity';
        }
        $composePartials[$type] = $config['script'];
      }
    }

    $composePartialsArray = array();
    //default set the values and attachment removes from the view tpl.
    $composerOptions[] = "sesadvancedactivityfacebook";
    $composerOptions[] = "sesadvancedactivitytwitter";
    //get diff
    foreach($composerOptions as $composerSetting){
       if(isset($composePartials[$composerSetting]))
         $composePartialsArray[$composerSetting] = $composePartials[$composerSetting];
    }
    //remove Key from array
    $arrayRemove = array("album"=>'album',"buysell"=>'buysell',"sesadvancedactivitytargetpost"=>'sesadvancedactivitytargetpost',"fileupload"=>'fileupload',"sesevent"=>'sesevent');


    if($subject && method_exists($subject, 'activityComposerOptions')){
      $allowedExtentions =  $subject->activityComposerOptions($subject);
      $composePartialsArrayDiff = array();
      foreach($composePartialsArray as $key=>$partials){
        if(array_key_exists($key,$allowedExtentions)){
          $composePartialsArrayDiff[$key] = $partials;
        }
      }
      $composePartialsArray = $composePartialsArrayDiff;
      $this->view->composerOptions = $allowedExtentions;
    }else{
      if((!$this->view->isMemberHomePage && ($subject && $subject->getType() != 'user'))){
        foreach($arrayRemove as $key){
          unset($composePartialsArray[$key]);
        }
        $this->view->composerOptions = array_diff($composerOptions, array("tagUseses","locationses","shedulepost"));
      }
    }

    if( empty($subject) || $viewer->isSelf($subject) ) {
      // Get Feed Privacy List
      $defaultViewPrivacy = array(
        'everyone'  => 'Everyone',
        'networks'  => 'Friends & Networks',
        'friends'   => 'Friends Only',
        'onlyme'    => 'Only Me',
      );
      $viewPrivacyLists = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.view.privacy');
      if (!empty($viewPrivacyLists)) {
        foreach ($viewPrivacyLists as $viewPrivacy) {
          $privacyArray[$viewPrivacy] = $defaultViewPrivacy[$viewPrivacy];
        }
        $this->view->defaultPrivacyLabel = reset($privacyArray);
      }

      $enableNetworkList = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.network.privacy', 0);
      if ($enableNetworkList) {
        $networkLists = Engine_Api::_()->activity()->getNetworks($enableNetworkList, $viewer);

        if ((is_array($networkLists) || is_object($networkLists)) && engine_count($networkLists)) {
          foreach ($networkLists as $network) {
            $networkArray["network_" . $network->getIdentity()] = $network->getTitle();
          }
          $this->view->defaultPrivacyLabel = $this->view->defaultPrivacyLabel ? : reset($networkArray);
        }
      }
    }
    if(!empty($composePartialsArray['elivestreaming'])){
      $data = $composePartialsArray['elivestreaming'];
      unset($composePartialsArray['elivestreaming']);
      array_unshift($composePartialsArray,$data);
    }      
    $this->view->composePartials = $composePartialsArray;
    $this->view->photoActivator = array_key_exists('photo',$composePartialsArray);
    $this->view->albumActivator = array_key_exists('album',$composePartialsArray);
    $this->view->videoActivator = array_key_exists('video',$composePartialsArray);
    $this->view->liveStreamActivator = array_key_exists('elivestreaming',$composePartialsArray);
    // Form token
    $session = new Zend_Session_Namespace('ActivityFormToken');
    //$session->setExpirationHops(10);
    if( empty($session->token) ) {
      $this->view->formToken = $session->token = md5(time() . $viewer->getIdentity() . get_class($this));
    } else {
      $this->view->formToken = $session->token;
    }
  }
  
  
  private function isBlocked($action) {
    if( empty($this->_blockedUserIds) ) {
      return false;
    }
    $actionObjectOwner = $action->getObject()->getOwner();
    $actionSubjectOwner = $action->getSubject()->getOwner();
    if( $actionSubjectOwner instanceof User_Model_User && engine_in_array($actionSubjectOwner->getIdentity(), $this->_blockedUserIds) ) {
      return true;
    }
    if( $actionObjectOwner instanceof User_Model_User && engine_in_array($actionObjectOwner->getIdentity(), $this->_blockedUserIds) ) {
      return true;
    }
    return false;
  }
}
