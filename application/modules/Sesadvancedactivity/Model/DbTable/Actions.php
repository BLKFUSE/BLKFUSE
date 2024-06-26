<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Actions.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_DbTable_Actions extends Engine_Db_Table
{
  protected $_rowClass = 'Sesadvancedactivity_Model_Action';
	protected $_name = 'activity_actions';
  protected $_serializedColumns = array('params');
  protected $_actionTypes;
  protected $_api;
  public function init() {
      $this->_api = Engine_Api::_();
  }
  public function addActivity(Core_Model_Item_Abstract $subject, Core_Model_Item_Abstract $object,
          $type, $body = null, array $params = null, $postData = null)
  {
    // Disabled or missing type
    $typeInfo = $this->getActionType($type);
    if( !$typeInfo || !$typeInfo->enabled )
    {
      return;
    }

    // User disabled publishing of this type
    $detailsTable = $this->_api->getDbtable('details', 'sesadvancedactivity');
    $actionSettingsTable = $this->_api->getDbtable('actionSettings', 'sesadvancedactivity');
    if( !$actionSettingsTable->checkEnabledAction($subject, $type) ) {
      return;
    }

    // Create action
    $action = $this->createRow();
    if(!empty($postData['scheduled_post'])){
     $str = str_replace('_','/',$postData['scheduled_post']);
     $date = DateTime::createFromFormat('d/m/Y H:i:s', $str);
     $scheduled_post= $date->format('Y-m-d H:i:s');
    }else{
     $scheduled_post = '';
    }
    //Emojis Work
    if($this->_api->getDbTable('modules', 'core')->isModuleEnabled('sesemoji')) {
      $bodyEmojis = explode(' ', $body);
      foreach($bodyEmojis as $bodyEmoji) {
        $emojisCode = $this->_api->sesemoji()->EncodeEmoji($bodyEmoji);
        $body = str_replace($bodyEmoji,$emojisCode,$body);
      }
    }
    //Emojis Work End

    $postingType = !empty($_POST['postingType']) ? $_POST['postingType'] : '';
    if($postingType){
      $itemPos = $this->_api->getItemByGuid($postingType);
    }
    $action->setFromArray(array(
      'type' => $type,
      'subject_type' => $subject->getType(),
      'subject_id' => $subject->getIdentity(),
      'object_type' => $object->getType(),
      'object_id' => $object->getIdentity(),
      'body' => (string) $body,
      'params' => (array) $params,
      'date' => date('Y-m-d H:i:s'),
      'privacy' => !empty($postData['privacy']) ? rtrim($postData['privacy'],',') : '',
      //'schedule_time' => $scheduled_post,
    ));
    $action->save();
    $detailsTable = $this->_api->getDbTable('details','sesadvancedactivity');
    $actionDetails = $detailsTable->isRowExists($action->action_id);
    //Details Table
    if(!$actionDetails){
      $actionDetails = $detailsTable->createRow();
      $actionDetails->setFromArray(array(
        'action_id' => $action->getIdentity(),
        'sesresource_id' => !empty($itemPos) ? $itemPos->getIdentity() : "",
        'sesresource_type' => !empty($itemPos) ? $itemPos->getType() : "",
        'schedule_time' => $scheduled_post,
      ));
    }else{
      $actionDetails = $this->_api->getItem('sesadvancedactivity_detail',$actionDetails);
      $actionDetails->setFromArray(array(
        'action_id' => $action->getIdentity(),
        'sesresource_id' => !empty($itemPos) ? $itemPos->getIdentity() : "",
        'sesresource_type' => !empty($itemPos) ? $itemPos->getType() : "",
        'schedule_time' => $scheduled_post,
      ));
    }
    $actionDetails->save();
    //Details Table


     // Add bindings
    if(empty($postData['scheduled_post'])){
      $this->addActivityBindings($action, $type, $subject, $object);
    }
    // We want to update the subject
    if( isset($subject->modified_date) )
    {
      $subject->modified_date = date('Y-m-d H:i:s');
      $subject->save();
    }
    return $action;
  }

  public function getActivity(User_Model_User $user, array $params = array())
  {
    $viewer = $this->_api->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $actionTableName = $this->info('name');
    $detailsTable = $this->_api->getDbtable('details', 'sesadvancedactivity');
    $detailsTableName = $detailsTable->info('name');
    // Proc args
    $streamTable = $this->_api->getDbtable('stream', 'sesadvancedactivity');
    $streamTableName = $streamTable->info('name');
    extract($this->_getInfo($params)); // action_id, limit, min_id, max_id
    $filterType = !empty($params['filterFeed']) ? $params['filterFeed'] : '';
    $hashTag = !empty($params['hashTag']) && $params['hashTag'] != 'undefined' ? $params['hashTag'] : '';
    $targetPost = !empty($params['targetPost']) ? $params['targetPost'] : '';
    $allvideos = !empty($params['allvideos']) ? $params['allvideos'] : '';
    $action_video_id = !empty($params['action_video_id']) ? $params['action_video_id'] : '';

    $settings = $this->_api->getApi('settings', 'core');

    $enabledModuleNames = $this->_api->getDbtable('modules', 'core')->getEnabledModuleNames();
    $enableSesMemberPlugin = engine_in_array("sesmember",$enabledModuleNames);

    if($filterType == 'my_networks'){
    }else if($filterType == 'my_friends' || $filterType == "home_friend"){
       $subjectIds = $user->membership()->getMembershipsOfIds();
       if($filterType == "home_friend" && $viewer_id){
         $subjectIds[] = $viewer_id;
       }
			 if(!$subjectIds)
			  return ;
    }
    //SNS - Advanced Members Plugin Following Work
    else if($filterType == 'sesmember' && $enableSesMemberPlugin && $settings->getSetting('sesmember.follow.active', 1)) {
      $followersResults = $this->_api->getDbTable('follows', 'sesmember')->getFollowersForANF($viewer_id);
      foreach($followersResults as $followersResult) {
        $subjectIds[] = $followersResult->user_id;
      }
      if(!$subjectIds)
      return ;
    }
    else if(strpos($filterType,'network_filter_') !== false){
      $networkFilterId = str_replace('network_filter_','',$filterType);
    }else if(strpos($filterType,'member_list_') !== false){
      $listFilterId = str_replace('member_list_','',$filterType);
    } else if($filterType == 'saved_feeds'){
      $customSelect = $streamTableName.'.action_id IN (SELECT action_id FROM engine4_sesadvancedactivity_savefeeds WHERE user_id = '.$user->getIdentity().')';
    }
    // Prepare main query
    $db = $streamTable->getAdapter();
    $union = new Zend_Db_Select($db);
    // Prepare action types
    $masterActionTypes = $this->_api->getDbtable('actionTypes', 'sesadvancedactivity')->getActionTypes();
    $mainActionTypes = array();
    // Filter out types set as not displayable
    foreach( $masterActionTypes as $type ) {
      if( $type->displayable & 4 ) {
        $mainActionTypes[] = $type->type;
      }
    }
    // Filter types based on user request
    if( isset($showTypes) && is_array($showTypes) && !empty($showTypes) ) {
      $mainActionTypes = array_intersect($mainActionTypes, $showTypes);
    } else if( isset($hideTypes) && is_array($hideTypes) && !empty($hideTypes) ) {
      $mainActionTypes = array_diff($mainActionTypes, $hideTypes);
    }
    // Nothing to show
    if( empty($mainActionTypes) ) {
      return null;
    }
    // Show everything
    else if( engine_count($mainActionTypes) == engine_count($masterActionTypes) ) {
      $mainActionTypes = true;
    }
    // Build where clause
    else {
      $mainActionTypes = "'" . join("', '", $mainActionTypes) . "'";
    }
    // Prepare sub queries
    if($filterType == 'my_networks' || !empty($networkFilterId)){
      $responses = array();
      if(empty($networkFilterId)){
      $networkIds =  $this->_api->getDbtable('membership', 'network')->getMembershipsOfIds($user);
      if(!engine_count($networkIds))
        return;
      }else
        $networkIds = $networkFilterId;
      $responses[] = array('type'=>'network','data'=>$networkIds);
    }else if(!empty($listFilterId)){
        $responses = array();
        $list = $this->_api->getItem('user_list',$listFilterId);
        $lists = $this->_api->getDbTable('listitems','user');
        $listSelect = $lists->select()->from($lists->info('name'),'child_id')->where('list_id =?',$listFilterId)->where('child_id =?',$viewer->getIdentity());
        $listUserIds = $lists->fetchAll($listSelect);
        if($viewer->getIdentity() != $list->owner_id){
          if(!engine_count($listUserIds)){
            return null;
          }
        }
        $responses[] = array('type'=>'members_list','data'=>$listFilterId);
    } else {
      $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('getActivity', array(
      'for' => Engine_Api::_()->getItem('user', $user->getIdentity()),
      ));
      $responses = (array) $event->getResponses();
    }
    if( empty($responses) ) {
      return null;
    }

    $detailsTableColumn = $this->_api->sesadvancedactivity()->getDetailsTableColumn();

    if(($filterType == 'scheduled_post')){
         $union = $this->select()
          ->from($this->info('name'), 'action_id')
          ->setIntegrityCheck(false)
          ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn)
          ->where($detailsTableName.'.schedule_time IS NOT NULL && schedule_time != ""')
          ->where($this->info('name').'.action_id IS NOT NULL')
          ->where($this->info('name').'.subject_id = '.$user->getIdentity())
          ->limit($limit);

          if(empty($action_id)){ 
             $union->where('is_community_ad =?',0);
           }
        // Add action_id/max_id/min_id
      if( null !== $action_id ) {
        $union->where($this->info('name').'.action_id = ?', $action_id);
      } else {
        if( null !== $min_id ) {
          $union->where($this->info('name').'.action_id >= ?', $min_id);
        } else if( null !== $max_id ) {
          $union->where($this->info('name').'.action_id <= ?', $max_id);
        }
      }
      $responses = array();
    }

    if($hashTag)
     $hashTagTableName = $this->_api->getDbTable('hashtags','sesadvancedactivity')->info('name');

     if($targetPost){
      /*Target Post*/

      $fields = $this->_api->fields()->getFieldsValuesByAlias($this->_api->user()->getViewer());
      $gender = !empty($fields['gender']) ? $fields['gender'] : '';
      if(!$gender){
        $genderWomen = $genderMan = 0;
      }else{
        $optionsTable = $this->_api->fields()->getTable($user->getType(), 'options');
        $optionSelect = $optionsTable->select()->where('option_id =?',$gender);
        $optionSelect = $optionsTable->fetchRow($optionSelect);
        if($optionSelect){
          if($optionSelect->label == 'Male'){
            $genderMan = $optionSelect->option_id;
            $genderWomen = 0;
          }else{
            $genderWomen = $optionSelect->option_id;
            $genderMan = 0;
          }
        }else{
           $genderWomen = $genderMan = 0;
        }
      }
      $birthDate = !empty($fields['birthdate']) ? $fields['birthdate'] : 0;
      //check sesmember plugin install and activated
      if($enableSesMemberPlugin){
        //get loggedin user location
        $userlocationSelect = $this->_api->getDbtable('locations', 'sesbasic')->select()->where('resource_type =?','user')->where('resource_id =?',$viewer_id);
        $userlocation = $this->_api->getDbtable('locations', 'sesbasic')->fetchRow($userlocationSelect);
        if(!$userlocation){
          $country = '';
          $city = '';
          $address = '';
        }else{
          $country = empty($userlocation->country) ? "(Q*" : $userlocation->country;
          $city = empty($userlocation->city) ? "()*" : $userlocation->city;
          $address = empty($userlocation->address) ? "~!@%^&" : $userlocation->address;
        }
      }
       //get loggedin user DOB
       $birthDate = $datem =  date('m/d/Y',strtotime($birthDate));
       //explode the date to get month, day and year
       $birthDate = explode("/", $birthDate);
       //get age from date or birthdate
       $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));
     }
		$estore = engine_in_array("estore",$enabledModuleNames) && $settings->getSetting('estore.activityfeed.filter', 0);

    if($estore && $viewer_id){
       $stores = $this->_api->getDbTable('stores','estore')->getActivityQuery($viewer_id);
       if(!engine_count($stores)){
        $stores[] = 0;
       }
    }
    $sespage = engine_in_array("sespage",$enabledModuleNames) && $settings->getSetting('sespage.activityfeed.filter', 0);
    if($sespage && $viewer_id){
       $pages = $this->_api->getDbTable('pages','sespage')->getActivityQuery($viewer_id);
       if(!engine_count($pages)){
        $pages[] = 0;
       }
    }
    $sesgroup = engine_in_array("sesgroup",$enabledModuleNames) && $settings->getSetting('sesgroup.activityfeed.filter', 0);
    if($sesgroup && $viewer_id){
       $groups = $this->_api->getDbTable('groups','sesgroup')->getActivityQuery($viewer_id);
       if(!engine_count($groups)){
        $groups[] = 0;
       }
    }
    $sesbusiness = engine_in_array("sesbusiness",$enabledModuleNames) && $settings->getSetting('sesbusiness.activityfeed.filter', 0);
    if($sesbusiness && $viewer_id){
       $businesses = $this->_api->getDbTable('businesses','sesbusiness')->getActivityQuery($viewer_id);
       if(!engine_count($businesses)){
        $businesses[] = 0;
       }
    }

    $video = engine_in_array("sesvideo",$enabledModuleNames) || engine_in_array("video",$enabledModuleNames);
    $sesvideo = engine_in_array("sesvideo",$enabledModuleNames);
    $sespagevideo = engine_in_array("sespagevideo",$enabledModuleNames);
    $seseventvideo = engine_in_array("seseventvideo",$enabledModuleNames); 
    $sesbusinessvideo = engine_in_array("sesbusinessvideo",$enabledModuleNames);
    $sesgroupvideo = engine_in_array("sesgroupvideo",$enabledModuleNames);

    foreach($responses as $response )
    {
      if(empty($response)) continue;

      $select = $streamTable->select()
        ->from($streamTable->info('name'), array('action_id','group_action_id'=> new Zend_Db_Expr($streamTableName.'.action_id')));
      $select->where('target_type = ?', $response['type']);
      if($hashTag){
        $select->setIntegrityCheck(false);
        $select
            ->join($hashTagTableName, "$hashTagTableName.action_id = $streamTableName.action_id", null)
            ->where($hashTagTableName.'.title = ?',$hashTag);
      }
      //get all videos of ses plugins

        //
      if($allvideos && ($video || $sespagevideo || $seseventvideo || $sesgroupvideo || $sesbusinessvideo)){
        $attachmentTable = $this->_api->getDbTable('attachments','activity');
        $attachmentTableName = $attachmentTable->info('name');
        $select->where($streamTable->info('name').'.action_id' != $action_video_id);
        $select->setIntegrityCheck(false);
        $select
            ->join($attachmentTableName, "$attachmentTableName.action_id = $streamTableName.action_id",
            null)
            ->group("$attachmentTableName.id");
        $cases = "CASE ";
        if($video) {
            $videoTableName = "engine4_video_videos";
            if($sesvideo){
                $videoTableName = "engine4_sesvideo_videos";
            }
            $cases .= "WHEN $attachmentTableName.type = 'video' AND id IN (SELECT video_id FROM " . $videoTableName . " WHERE `status` = 1 AND type = 3) THEN true ";
        }
        if($seseventvideo)
          $cases .= "WHEN $attachmentTableName.type = 'seseventvideo_video' AND id IN (SELECT video_id FROM engine4_seseventvideo_videos WHERE `status` = 1 AND type = 3) THEN true ";
        if($sespagevideo)
          $cases .= "WHEN $attachmentTableName.type = 'sespagevideo_video' AND id IN (SELECT video_id FROM engine4_sespagevideo_videos WHERE `status` = 1 AND type = 3) THEN true ";
        if($sesbusinessvideo)
          $cases .= "WHEN $attachmentTableName.type = 'businessvideo' AND id IN (SELECT video_id FROM engine4_sesbusinessvideo_videos WHERE `status` = 1 AND type = 3) THEN true ";
        if($sesgroupvideo)
          $cases .= "WHEN $attachmentTableName.type = 'groupvideo' AND id IN (SELECT video_id FROM engine4_sesgroupvideo_videos WHERE `status` = 1 AND type = 3) THEN true ";
        $cases .= "ELSE false END";
        $select->where($cases);
      }
      if( empty($response['data']) ) {
        // Simple
        $select->where('target_id = ?', 0);
      } else if( is_scalar($response['data']) || engine_count($response['data']) === 1 ) {
        // Single
        if( is_array($response['data']) ) {
          list($response['data']) = $response['data'];
        }
        $select->where('target_id = ?', $response['data']);
      } else if( is_array($response['data']) ) {
        // Array
        $select->where('target_id IN(?)', (array) $response['data']);
      } else {
        // Unknown
        continue;
      }

      // Add action_id/max_id/min_id
      if( null !== $action_id ) {
        $select->where($streamTableName.'.action_id = ?', $action_id);
      } else {
        if( null !== $min_id ) {
          $select->where($streamTableName.'.action_id >= ?', $min_id);
        } else if( null !== $max_id ) {
          $select->where($streamTableName.'.action_id <= ?', $max_id);
        }
      }
      if( $mainActionTypes !== true ) {
        $select->where($streamTableName.'.type IN(' . $mainActionTypes . ')');
      }
      if(!empty($subjectIds)){
        $select->where($streamTableName.'.subject_id IN(?)',$subjectIds);
      }

      //Share filter work
      if($filterType == 'share') {
        $select->where($streamTableName.'.type =?',$filterType);
      }
      //Share filter work

      if(!empty($customSelect)){
        $select->where($customSelect);
      }
			// store plugin
			if(!empty($stores)){
				 $select->where("CASE WHEN " .$streamTableName .".object_type != 'stores'  THEN true WHEN " .$streamTableName .".object_type = 'stores' THEN ".$streamTableName.".object_id IN (".implode(',',$stores).") ELSE true END ");
			}
      //page plugin
       if(!empty($pages)){
           $select->where("CASE WHEN " .$streamTableName .".object_type != 'sespage_page'  THEN true WHEN " .$streamTableName .".object_type = 'sespage_page' THEN ".$streamTableName.".object_id IN (".implode(',',$pages).") ELSE true END ");
       }
       //group plugin
       if(!empty($groups)){
           $select->where("CASE WHEN " .$streamTableName .".object_type != 'sesgroup_group'  THEN true WHEN " .$streamTableName .".object_type = 'sesgroup_group' THEN ".$streamTableName.".object_id IN (".implode(',',$groups).") ELSE true END ");
       }
       //business plugin
       if(!empty($businesses)){
           $select->where("CASE WHEN " .$streamTableName .".object_type != 'businesses'  THEN true WHEN " .$streamTableName .".object_type = 'businesses' THEN ".$streamTableName.".object_id IN (".implode(',',$businesses).") ELSE true END ");
       }
       //hide post query work
       $select->where($streamTableName.'.action_id NOT IN (SELECT resource_id FROM engine4_sesadvancedactivity_hides WHERE user_id = '.$user->getIdentity().' AND resource_type = "post")');
       $select->where($streamTableName.'.subject_id NOT IN (SELECT resource_id FROM engine4_sesadvancedactivity_hides WHERE user_id = '.$user->getIdentity().' AND resource_type = "user")');
      // Add order/limit
      if(!empty($action_video_id)){
        $select->order('FIELD('.$actionTableName.'.action_id, '.$action_video_id.') DESC');
      }
      $select
        ->order($streamTableName.'.action_id DESC')
        ->limit($limit)
        ->setIntegrityCheck(false)
        ->joinLeft($this->info('name'), $this->info('name') . '.action_id = ' . $streamTableName . '.action_id', null)
        ->joinLeft($detailsTableName, $detailsTableName . '.action_id = ' . $this->info('name') . '.action_id', $detailsTableColumn)
        ->where($detailsTableName.'.sesapproved =?',1);
        if(empty($action_id)){
             $select->where('is_community_ad =?',0);
           }
      if($targetPost){
      /*Target Post*/
        $targetTableName= 'engine4_sesadvancedactivity_targetpost';
        $select = $select

                  ->joinLeft($targetTableName, $targetTableName . '.action_id = ' . $streamTableName . '.action_id', null)
                  ;
        if($enableSesMemberPlugin){
          //location target sql
          $select->where("CASE WHEN " .$targetTableName .".location_send = 'all' OR ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL THEN true WHEN " .$targetTableName .".location_send = 'country' THEN ".$targetTableName.".country_name LIKE concat('%','".$country."','%')  WHEN " .$targetTableName .".location_send = 'city' THEN ".$targetTableName.".city_name LIKE concat('%','".$city."','%') OR ".$targetTableName.".location_city LIKE concat('%','".$city."','%') OR ".$targetTableName.".location_city LIKE concat('%','".$address."','%') OR ".$targetTableName.".city_name LIKE concat('%','".$address."','%') ELSE false END ");
        //location target sql end here
        }
      //gender sql starts here
      $select->where("CASE WHEN " .$targetTableName .".gender_send = 'all' OR ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL THEN true WHEN " .$targetTableName .".gender_send = 'women' THEN '".$gender."' = ".$genderWomen." ELSE '".$gender."' = ".$genderMan."  END ")
      //gender sql ends here

      //age sql starts here
      ->where("CASE WHEN ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL OR " .$targetTableName .".age_min_send = '' OR  " .$targetTableName .".age_max_send = '' THEN true WHEN ".$age."  BETWEEN " .$targetTableName .".age_min_send AND  " .$targetTableName .".age_max_send THEN true WHEN " .$targetTableName.".age_max_send >= 99 AND  '".$age."' > " .$targetTableName .".age_max_send THEN true ELSE false  END ");
    }

      // Add to main query
      $union->union(array('('.$select->__toString().')')); // (string) not work before PHP 5.2.0
    }
    
    
    // Get actions
    $actions = $db->fetchAll($union->__toString());

    // No visible actions
    if( empty($actions) )
    {
      return null;
    }

    $ids = array();
    foreach( $actions as $data )
    {
      if(!empty($data['group_action_id'])){
        $id = trim(implode(',',array_unique(explode(',', $data['group_action_id']))),',');
      }else{
        $id = trim($data['action_id'],',');
      }
      $ids[] = $id;
    }
    $ids = array_filter(array_unique($ids));

    // Finally get activity
    // return $this->fetchAll(
    //   $this->select()
    //     ->from($this->info('name'),'*')
    //     ->setIntegrityCheck(false)
    //     ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn)
    //     ->where($actionTableName.'.action_id IN('.join(',', $ids).')')
    //     ->order($actionTableName.'.action_id DESC')
    //     ->limit($limit)
    // );

    $idsSelect = $this->select()
        ->from($this->info('name'),'*')
        ->setIntegrityCheck(false)
        ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn)
        ->where($actionTableName.'.action_id IN('.join(',', $ids).')')
        ->limit($limit);
    if(!empty($action_video_id)){
      $idsSelect->order('FIELD('.$actionTableName.'.action_id, '.$action_video_id.') DESC');
    }

    $idsSelect->order($actionTableName.'.action_id DESC');

    // Finally get activity
    return $this->fetchAll($idsSelect);
    
  }

  public function getActivityAbout(Core_Model_Item_Abstract $about, User_Model_User $user,
          array $params = array())
  {
    // Proc args
    extract($this->_getInfo($params)); // action_id, limit, min_id, max_id
    $targetPost = !empty($params['targetPost']) ? $params['targetPost'] : '';
    $isOnThisDayPage = !empty($params['isOnThisDayPage']) ? $params['isOnThisDayPage'] : '';
      $filterFeed = !empty($params['filterFeed']) ? $params['filterFeed'] : '';

    $settings = $this->_api->getApi('settings', 'core');

    //get 200 post for onthisday functionity
    if($isOnThisDayPage)
      $limit = 200;
    // Prepare main query
    $streamTable = $this->_api->getDbtable('stream', 'sesadvancedactivity');
    $streamTableName = $streamTable->info('name');


    $detailsTable = $this->_api->getDbtable('details', 'sesadvancedactivity');
    $detailsTableName = $detailsTable->info('name');

    $actionTableName = $this->info('name');

    $db = $streamTable->getAdapter();
    $union = new Zend_Db_Select($db);

    // Prepare action types
    $masterActionTypes = $this->_api->getDbtable('actionTypes', 'sesadvancedactivity')->getActionTypes();
    $subjectActionTypes = array();
    $objectActionTypes = array();

    // Filter types based on displayable
    foreach( $masterActionTypes as $type ) {
      if( $type->displayable & 1 ) {
        $subjectActionTypes[] = $type->type;
      }
      if( $type->displayable & 2 ) {
        $objectActionTypes[] = $type->type;
      }
    }

    // Filter types based on user request
    if( isset($showTypes) && is_array($showTypes) && !empty($showTypes) ) {
      $subjectActionTypes = array_intersect($subjectActionTypes, $showTypes);
      $objectActionTypes = array_intersect($objectActionTypes, $showTypes);
    } else if( isset($hideTypes) && is_array($hideTypes) && !empty($hideTypes) ) {
      $subjectActionTypes = array_diff($subjectActionTypes, $hideTypes);
      $objectActionTypes = array_diff($objectActionTypes, $hideTypes);
    }
    // Nothing to show
    if( empty($subjectActionTypes) && empty($objectActionTypes) ) {
      return null;
    }

    if( empty($subjectActionTypes) ) {
      $subjectActionTypes = null;
    } else if( engine_count($subjectActionTypes) == engine_count($masterActionTypes) ) {
      $subjectActionTypes = true;
    } else {
      $subjectActionTypes = "'" . join("', '", $subjectActionTypes) . "'";
    }

    if( empty($objectActionTypes) ) {
      $objectActionTypes = null;
    } else if( engine_count($objectActionTypes) == engine_count($masterActionTypes) ) {
      $objectActionTypes = true;
    } else {
      $objectActionTypes = "'" . join("', '", $objectActionTypes) . "'";
    }

    // Prepare sub queries
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('getActivity', array(
      'for' => Engine_Api::_()->getItem('user', $user->getIdentity()),
      'about' => $about,
    ));
    $responses = (array) $event->getResponses();

    if( empty($responses) ) {
      return null;
    }
     if($targetPost && $about->getType() == 'user' && $about->getIdentity() != $user->getIdentity()){
      /*Target Post*/
      $viewer_id = $this->_api->user()->getViewer()->getIdentity();
      $fields = $this->_api->fields()->getFieldsValuesByAlias($this->_api->user()->getViewer());
      $gender = !empty($fields['gender']) ? $fields['gender'] : '';
      if(!$gender){
        $genderWomen = $genderMan = 0;
      }else{
        $optionsTable = $this->_api->fields()->getTable($user->getType(), 'options');
        $optionSelect = $optionsTable->select()->where('option_id =?',$gender);
        $optionSelect = $optionsTable->fetchRow($optionSelect);
        if($optionSelect){
          if($optionSelect->label == 'Male'){
            $genderMan = $optionSelect->option_id;
            $genderWomen = 0;
          }else{
            $genderWomen = $optionSelect->option_id;
            $genderMan = 0;
          }
        }else{
           $genderWomen = $genderMan = 0;
        }
      }
      $birthDate = !empty($fields['birthdate']) ? $fields['birthdate'] : 0;
      //check sesmember plugin install and activated
      $enableSesMemberPlugin = $this->_api->getDbtable('modules', 'core')->isModuleEnabled("sesmember");
      if($enableSesMemberPlugin){
        $locationTable = $this->_api->getDbtable('locations', 'sesbasic');
        //get loggedin user location
        $userlocationSelect = $locationTable->select()->where('resource_type =?','user')->where('resource_id =?',$viewer_id);
        $userlocation = $locationTable->fetchRow($userlocationSelect);

        unset($locationTable);

        if(!$userlocation){
          $country = '';
          $city = '';
          $address = '';
        }else{
          $country = $userlocation->country;
          $city = $userlocation->city;
          $address = $userlocation->address;
        }
      }
       //get loggedin user DOB
       $birthDate = $datem =  date('m/d/Y',strtotime($birthDate));
       //explode the date to get month, day and year
       $birthDate = explode("/", $birthDate);
       //get age from date or birthdate
       $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));
     }

    $detailsTableColumn = $this->_api->sesadvancedactivity()->getDetailsTableColumn();
    //hidden post
    if(($filterFeed == 'hiddenpost')){
      $hiddenTableName = 'engine4_sesadvancedactivity_hides';
         $union = $this->select()
          ->from($this->info('name'), 'action_id')
          ->joinLeft($hiddenTableName,$hiddenTableName.'.resource_id ='.$this->info('name').'.action_id')
          ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn)
          ->where('hide_id IS NOT NULL')
          ->setIntegrityCheck(false)
          ->where($hiddenTableName.'.resource_type =?','post')
          ->where($hiddenTableName.'.user_id = '.$user->getIdentity())
          ->limit($limit);
        // Add action_id/max_id/min_id
      if( null !== $action_id ) {
        $union->where($this->info('name').'.action_id = ?', $action_id);
      } else {
        if( null !== $min_id ) {
          $union->where($this->info('name').'.action_id >= ?', $min_id);
        } else if( null !== $max_id ) {
          $union->where($this->info('name').'.action_id <= ?', $max_id);
        }
      }
         $responses = array();
     }
    if(($filterFeed == 'taggedinpost')){
         $union = $this->select()
                  ->from($this->info('name'), 'action_id')
                  ->setIntegrityCheck(false)
                ->where("body  LIKE ?  ", '%' . '@_'.$user->getGuid(). '%')
                ->where('action_id IS NOT NULL');
         // Add action_id/max_id/min_id
          if( null !== $action_id ) {
            $union->where($this->info('name').'.action_id = ?', $action_id);
          } else {
            if( null !== $min_id ) {
              $union->where($this->info('name').'.action_id >= ?', $min_id);
            } else if( null !== $max_id ) {
              $union->where($this->info('name').'.action_id <= ?', $max_id);
            }
          }
         $responses = array();
    }

    if(empty($params['selectedFeedBoostPost'])){
      $pintotop = $settings->getSetting('sesadvancedactivity.pintotop',1);
      if($pintotop){
        $res_type = $about->getType();
        $res_id = $about->getIdentity();
        $table = $this->_api->getDbTable('pinposts','sesadvancedactivity');
        $selectPin= $table->select()->where('resource_id	 =?',$res_id)->where('resource_type =?',$res_type);
        $res = $table->fetchRow($selectPin);
      }
    }else if(!empty($params['selectedFeedBoostPost'])){
       $res = array();
       $res['action_id'] = $params['selectedFeedBoostPost'];
       $pintotop = true;
    }

    foreach( $responses as $response )
    {
      if( empty($response) ) continue;

      // Target info
      $select = $streamTable->select()
        ->from($streamTable->info('name'), 'action_id')
        ->where($streamTableName.'.target_type = ?', $response['type'])
        ;


      if(!empty($res))
       $select->order('FIELD('.$streamTableName.'.action_id, '.(is_array( $res) ?  $res['action_id'] : $res->action_id).') DESC');
      if( empty($response['data']) ) {
        // Simple
        $select->where($streamTableName.'.target_id = ?', 0);
      } else if( is_scalar($response['data']) || engine_count($response['data']) === 1 ) {
        // Single
        if( is_array($response['data']) ) {
          list($response['data']) = $response['data'];
        }
        $select->where($streamTableName.'.target_id = ?', $response['data']);
      } else if( is_array($response['data']) ) {
        // Array
        $select->where($streamTableName.'.target_id IN(?)', (array) $response['data']);
      } else {
        // Unknown
        continue;
      }
      if(empty(@$this->isOnThisDayPage) && !(@$this->isOnThisDayPage)){
        // Add action_id/max_id/min_id
        if( null !== $action_id ) {
          $select->where($streamTableName.'.action_id = ?', $action_id);
        } else {
          if( null !== $min_id ) {
            $select->where($streamTableName.'.action_id >= ?', $min_id);
          } else if( null !== $max_id ) {
            $select->where($streamTableName.'.action_id <= ?', $max_id);
          }
        }
      }
      // Add order/limit
      $select
        ->order($streamTableName.'.action_id DESC')
        ->limit($limit);

      /* uncomment this if pin feed not work in feed and check
        if(!empty($res))
           $union->order('FIELD(action_id,'.$res->action_id.') DESC');
        // Finish main query
        $union
          ->order('action_id DESC')
          ->limit($limit);
      */
      $select = $select->setIntegrityCheck(false)
                ->joinLeft($this->info('name'), $this->info('name') . '.action_id = ' . $streamTableName . '.action_id', null)
                ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn);

      if(!empty($params['communityads'])){
          $select->where($this->info('name').'.type IN (SELECT type from engine4_sescommunityads_feedsettings)');
          $select->where($streamTableName.'.target_type =?','everyone');
      }
      if($filterFeed != "unapprovedfeed"){
          $select->where($detailsTableName.'.sesapproved =?',1);
      }else{
           $select->where($detailsTableName.'.sesapproved =?',0);
      }
      if(empty($action_id)){
             $select->where($detailsTableName.'.is_community_ad =?',0);
           }
      if($filterFeed == "own" && empty($action_id)){
        $select->where('(engine4_activity_actions.subject_type = "user" AND engine4_activity_actions.subject_id ='.$about->getOwner()->getIdentity().' )');
      }
      if($targetPost && $about->getType() == 'user' && $about->getIdentity() != $user->getIdentity()){
      /*Target Post*/
        $targetTableName= 'engine4_sesadvancedactivity_targetpost';
        $select = $select->joinLeft($targetTableName, $targetTableName . '.action_id = ' . $streamTableName . '.action_id', null);
        if($enableSesMemberPlugin){
          //location target sql
          $select->where("CASE WHEN " .$targetTableName .".location_send = 'all' OR ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL THEN true WHEN " .$targetTableName .".location_send = 'country' THEN '".$country."' LIKE concat('%',$targetTableName.country_name,'%')  ELSE '".$city."' LIKE concat('%',$targetTableName.city_name,'%') OR '".$address."' LIKE concat('%',$targetTableName.city_name,'%')  END ");
        //location target sql end here
        }
      //gender sql starts here
       $select->where("CASE WHEN " .$targetTableName .".gender_send = 'all' OR ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL THEN true WHEN " .$targetTableName .".gender_send = 'women' THEN '".$gender."' = ".$genderWomen." ELSE '".$gender."' = ".$genderMan."  END ")
      //gender sql ends here

      //age sql starts here
        ->where("CASE WHEN ".$this->info('name').".subject_id = '".$viewer_id."' OR ".$targetTableName.".targetpost_id IS NULL THEN true WHEN ".$age."  BETWEEN " .$targetTableName .".age_min_send AND  " .$targetTableName .".age_max_send THEN true WHEN " .$targetTableName.".age_max_send >= 99 AND  '".$age."' > " .$targetTableName .".age_max_send THEN true ELSE false  END ");
      }else if($isOnThisDayPage){
         $select = $select
                  ->setIntegrityCheck(false)
                  ->joinLeft($this->info('name'), $this->info('name') . '.action_id = ' . $streamTableName . '.action_id', null)
                  ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn);

         $date = date('m-d');
         $select->where($actionTableName.'.date LIKE "%'.$date.'%"')
                ->where($actionTableName.'.date  NOT LIKE "%'.date('Y-m-d').'%"');
         $select->order($actionTableName.'.date DESC');
      }
      //hide post query work

      // Add subject to main query
      $selectSubject = clone $select;
      if( $subjectActionTypes !== null ) {
        if( $subjectActionTypes !== true ) {
          $selectSubject->where($streamTableName.'.type IN('.$subjectActionTypes.')');
        }
        $selectSubject
          ->where($streamTableName.'.subject_type = ?', $about->getType())
          ->where($streamTableName.'.subject_id = ?', $about->getIdentity());
        $union->union(array('('.$selectSubject->__toString().')')); // (string) not work before PHP 5.2.0
      }
      // Add object to main query
      $selectObject = clone $select;
      if( $objectActionTypes !== null ) {
        if( $objectActionTypes !== true ) {
          $selectObject->where($streamTableName.'.type IN('.$objectActionTypes.')');
        }
        $selectObject
          ->where($streamTableName.'.object_type = ?', $about->getType())
          ->where($streamTableName.'.object_id = ?', $about->getIdentity());
        $union->union(array('('.$selectObject->__toString().')')); // (string) not work before PHP 5.2.0
      }
    }
    // Finish main query
    $union
      ->order('action_id DESC')
      ->limit($limit);

    // Get actions
    $actions = $db->fetchAll($union);

    // No visible actions
    if( empty($actions) )
    {
      return null;
    }

    // Process ids
    $ids = array();
    foreach( $actions as $data )
    {
      $ids[] = $data['action_id'];
    }
    $ids = array_unique($ids);
     $detailsTable = $this->_api->getDbtable('details', 'sesadvancedactivity');
     $detailsTableName = $detailsTable->info('name');
     $actionTableName = $this->info('name');
    $select =
      $this->select()
        ->from($this->info('name'))
        ->setIntegrityCheck(false)
        ->joinLeft($detailsTableName, "$detailsTableName.action_id = $actionTableName.action_id", $detailsTableColumn)
        ->where($actionTableName.'.action_id IN('.join(',', $ids).')')
        ->limit($limit);

    unset($detailsTableColumn);

    if($pintotop){
      if(empty($params['selectedFeedBoostPost'])){
        $res_type = $about->getType();
        $res_id = $about->getIdentity();
        $table = $this->_api->getDbTable('pinposts','sesadvancedactivity');
        $selectPin= $table->select()->where('resource_id	 =?',$res_id)->where('resource_type =?',$res_type);
        $res = $table->fetchRow($selectPin);
        if(!empty($res))
         $select->order('FIELD('.$actionTableName.'.action_id, '.$res->action_id.') DESC');
      }else{
          $select->order('FIELD('.$actionTableName.'.action_id, '.$params['selectedFeedBoostPost'].') DESC');
      }
    }
    $select->order('action_id DESC');
    // Finally get activity

    return $this->fetchAll($select);
  }
  public function getListsIds(){
    // get viewer
    $viewer = $this->_api->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $listTable = $this->_api->getItemTable('user_list');
    $listTableName = $listTable->info('name');

    $listUserTable = $this->_api->getItemTable('user_list_item');
    $listUserTableName = $listUserTable->info('name');
    $select = $listUserTable->select();
    $select->setIntegrityCheck(false);
    $select
            ->from($listUserTableName, "$listUserTableName.list_id")
            ->join($listTableName, "$listTableName.list_id = $listUserTableName.list_id", null)
            ->where('child_id = ?', $viewer_id);
    // return list_id column
    return $select->query()->fetchAll(Zend_Db::FETCH_COLUMN);
  }
  public function attachActivity($action, Core_Model_Item_Abstract $attachment, $mode = 1)
  {
    $attachmentTable = $this->_api->getDbtable('attachments', 'sesadvancedactivity');

    if( is_numeric($action) )
    {
      $action = $this->fetchRow($this->select()->where('action_id = ?', $action)->limit(1));
    }

    if( !($action instanceof Sesadvancedactivity_Model_Action) )
    {
      $eInfo = ( is_object($action) ? get_class($action) : $action );
      throw new Sesadvancedactivity_Model_Exception(sprintf('Invalid action passed to attachActivity: %s', $eInfo));
    }

    $attachmentRow = $attachmentTable->createRow();
    $attachmentRow->action_id = $action->action_id;
    $attachmentRow->type = $attachment->getType();
    $attachmentRow->id = $attachment->getIdentity();
    $attachmentRow->mode = (int) $mode;
    $attachmentRow->save();

    $action->attachment_count++;
    $action->save();

    return $this;
  }

  public function detachFromActivity(Core_Model_Item_Abstract $attachment)
  {
    $attachmentsTable = $this->_api->getDbtable('attachments', 'sesadvancedactivity');
    $select = $attachmentsTable->select()
        ->where('`type` = ?', $attachment->getType())
        ->where('`id` = ?', $attachment->getIdentity())
        ;

    foreach( $attachmentsTable->fetchAll($select) as $row ) {
      $this->update(array(
        'attachment_count' => new Zend_Db_Expr('attachment_count - 1'),
      ), array(
        'action_id = ?' => $row->action_id,
      ));
      $row->delete();
    }

    return $this;
  }



  // Actions

  public function getActionById($action_id)
  {
    return $this->find($action_id)->current();
  }
  public function getActionsByObjectType(Core_Model_Item_Abstract $object,$type = "")
  {
    $select = $this->select()->where('object_type = ?', $object->getType())
      ->where('object_id = ?', $object->getIdentity())
      ->where('type =?',$type);
    return $this->fetchAll($select);
  }
  public function getActionsByObject(Core_Model_Item_Abstract $object)
  {
    $select = $this->select()->where('object_type = ?', $object->getType())
      ->where('object_id = ?', $object->getIdentity());
    return $this->fetchAll($select);
  }

  public function getActionsBySubject(Core_Model_Item_Abstract $subject)
  {
    $select = $this->select()
      ->where('subject_type = ?', $subject->getType())
      ->where('subject_id = ?', $subject->getIdentity())
      ;

    return $this->fetchAll($select);
  }

  public function getActionsByAttachment(Core_Model_Item_Abstract $attachment)
  {
    // Get all action ids from attachments
    $attachmentTable = $this->_api->getDbtable('attachments', 'sesadvancedactivity');
    $select = $attachmentTable->select()
      ->where('type = ?', $attachment->getType())
      ->where('id = ?', $attachment->getIdentity())
      ;

    $actions = array();
    foreach( $attachmentTable->fetchAll($select) as $attachmentRow )
    {
      $actions[] = $attachmentRow->action_id;
    }

    // Get all actions
    $select = $this->select()
      ->where('action_id IN(\''.join("','", $ids).'\')')
      ;

    return $this->fetchAll($select);
  }



  // Utility

  /**
   * Add an action-privacy binding
   *
   * @param int $action_id
   * @param string $type
   * @param Core_Model_Item_Abstract $subject
   * @param Core_Model_Item_Abstract $object
   * @return int The insert id
   */
  public function addActivityBindings($action)
  {
    // Get privacy bindings
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('addActivity', array(
      'subject' => $action->getSubject(),
      'object' => $action->getObject(),
      'type' => $action->type,
      'privacy'=>$action->privacy
    ));
    $streamTable = $this->_api->getDbtable('stream', 'sesadvancedactivity');
    // check privacy is network base
    $isNetworkBasePost = false;
    $isMemberBasePost = false;
    $isFriendBasePost = false;
    if($action->privacy){
      if (strpos($action->privacy, 'network_list_') !== false) {
        $networkIds = explode(',',$action->privacy);
        $isNetworkBasePost = true;
        foreach($networkIds as $target_id){
          $streamTable->insert(array(
          'action_id' => $action->action_id,
          'type' => $action->type,
          'target_type' => (string) 'network',
          'target_id' => (int) str_replace('network_list_','',$target_id),
          'subject_type' => $action->subject_type,
          'subject_id' => $action->subject_id,
          'object_type' => $action->object_type,
          'object_id' => $action->object_id,
        ));
        }
      }
      // check privacy is member lists based
      else if(strpos($action->privacy, 'members_list_') !== false){
          $memberlists = explode(',',$action->privacy);
          $isMemberBasePost = true;
          foreach($memberlists as $target_id){
            $streamTable->insert(array(
            'action_id' => $action->action_id,
            'type' => $action->type,
            'target_type' => (string) 'members_list',
            'target_id' => (int) str_replace('members_list_','',$target_id),
            'subject_type' => $action->subject_type,
            'subject_id' => $action->subject_id,
            'object_type' => $action->object_type,
            'object_id' => $action->object_id,
          ));
        }
      }else if (strpos($action->privacy, 'friends_list_') !== false) {
        $memberIds = explode(',',$action->privacy);
        $isFriendBasePost = true;
        foreach($memberIds as $target_id){
          $streamTable->insert(array(
          'action_id' => $action->action_id,
          'type' => $action->type,
          'target_type' => (string) 'friend',
          'target_id' => (int) str_replace('friends_list_','',$target_id),
          'subject_type' => $action->subject_type,
          'subject_id' => $action->subject_id,
          'object_type' => $action->object_type,
          'object_id' => $action->object_id,
        ));
        }
      }
    }
    foreach( (array) $event->getResponses() as $response )
    {
      if(($isNetworkBasePost || $isMemberBasePost || $isFriendBasePost) && ($response['type'] == 'network' || $response['type'] == 'members' || $response['type'] == 'everyone' || $response['type'] =='registered' )){
        continue;
      }else if($action->privacy == 'onlyme' && $response['type'] != 'owner')
        continue;
      else if($action->privacy == 'friends' && ($response['type'] == 'network' || $response['type'] == 'everyone' || $response['type'] =='registered' ))
        continue;
      else if( isset($response['target']) )
      {
        $target_type = $response['target'];
        $target_id = 0;
      }else if( isset($response['type']) && isset($response['identity']) )
      {
        $target_type = $response['type'];
        $target_id = $response['identity'];
      }else{
        continue;
      }

      $streamTable->insert(array(
        'action_id' => $action->action_id,
        'type' => $action->type,
        'target_type' => (string) $target_type,
        'target_id' => (int) $target_id,
        'subject_type' => $action->subject_type,
        'subject_id' => $action->subject_id,
        'object_type' => $action->object_type,
        'object_id' => $action->object_id,
      ));
    }
    return $this;
  }

  public function clearActivityBindings($action)
  {
    $streamTable = $this->_api->getDbtable('stream', 'sesadvancedactivity');
    $streamTable->delete(array(
      'action_id = ?' => $action->getIdentity(),
    ));
  }

  public function resetActivityBindings($action)
  {
    if ($action->getObject()) {
      $this->clearActivityBindings($action);
      $this->addActivityBindings($action);
    }
    return $this;
  }



  // Types

  /**
   * Gets action type meta info
   *
   * @param string $type
   * @return Engine_Db_Row
   */
  public function getActionType($type)
  {
    return $this->getActionTypes()->getRowMatching('type', $type);
  }

  /**
   * Gets all action type meta info
   *
   * @param string|null $type
   * @return Engine_Db_Rowset
   */
  public function getActionTypes()
  {
    if( null === $this->_actionTypes )
    {
      $table = $this->_api->getDbtable('actionTypes', 'sesadvancedactivity');
      $this->_actionTypes = $table->fetchAll();
    }

    return $this->_actionTypes;
  }



  // Utility

  protected function _getInfo(array $params)
  {
    $settings = $this->_api->getApi('settings', 'core');
    $args = array(
      'limit' => $settings->getSetting('activity.length', 20),
      'action_id' => null,
      'max_id' => null,
      'min_id' => null,
      'showTypes' => null,
      'hideTypes' => null,
    );

    $newParams = array();
    foreach( $args as $arg => $default ) {
      if( !empty($params[$arg]) ) {
        $newParams[$arg] = $params[$arg];
      } else {
        $newParams[$arg] = $default;
      }
    }

    return $newParams;
  }
}
