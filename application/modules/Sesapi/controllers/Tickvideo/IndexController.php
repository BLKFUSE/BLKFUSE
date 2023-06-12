<?php

class Tickvideo_IndexController extends Sesapi_Controller_Action_Standard{
    public function getAllBlockedMembersAction(){
        $loggedin_user_id = $this->view->viewer()->getIdentity();
        if(!$loggedin_user_id){
           Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
         }
         
         $table = Engine_Api::_()->getDbTable("blocks",'eticktokclone');
         $select = $table->select()->where("user_id =?",$loggedin_user_id);
         $paginator = Zend_Paginator::factory($select);
         
         $page = (int)  $this->_getParam('page', 1);
           // Build paginator
           $paginator->setItemCountPerPage($this->_getParam('limit',10));
           $paginator->setCurrentPageNumber($page);
   
           $result = $this->memberResult($paginator);
           $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
           $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
           $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
           $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
           if($result <= 0)
               Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'You have not blocked any user yet.', 'result' => array()));
           else
               Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }
    public function memberResult($paginator){
     $result = array();
     $counterLoop = 0;
     $viewer = Engine_Api::_()->user()->getViewer();
     
     foreach($paginator as $blocked_user){
         $member = Engine_Api::_()->getItem("user",$blocked_user->blocked_user_id);
         if(!$member){
             continue;
         }
       $result['notification'][$counterLoop]['user_id'] = $member->getIdentity();
       $result['notification'][$counterLoop]['title'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $member->getTitle());
       $result['notification'][$counterLoop]['user_image'] = $this->userImage($member->getIdentity(),"thumb.profile");
       $block = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$member->getIdentity()));
       if($block){
           // unblock
           $result['notification'][$counterLoop]['block'] = array(
               'label' => $this->view->translate('Unblock Member'),
               'name' => 'remove_block_member',
               'params' => array(
                 'user_id' => $member->getIdentity()
               ),
             );
       }else{
           // block
       
           $result['notification'][$counterLoop]['block'] = array(
               'label' => $this->view->translate('Block Member'),
               'name' => 'block_member',
               'params' => array(
                 'user_id' => $member->getIdentity()
               ),
             );
       }
       $counterLoop++;
     }
     return $result;
 }
 function followersAction(){

        $viewerId = $this->_getParam('viewer_id','');
        $paginator = Engine_Api::_()->getDbTable('users', 'eticktokclone')->followers(array('user_id' => $viewerId, 'paginator' => true));
        $page = (int)  $this->_getParam('page', 1);
        // Build paginator
        $paginator->setItemCountPerPage($this->_getParam('limit',10));
        $paginator->setCurrentPageNumber($page);

        $result = $this->memberDataResult($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if($result <= 0)
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'Does not exist member.', 'result' => array()));
        else
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));

    }
    function followingsAction(){
        $viewerId = $this->_getParam('viewer_id','');
        $paginator = Engine_Api::_()->getDbTable('users', 'eticktokclone')->following(array('user_id' => $viewerId, 'paginator' => true));
        $page = (int)  $this->_getParam('page', 1);
        // Build paginator
        $paginator->setItemCountPerPage($this->_getParam('limit',10));
        $paginator->setCurrentPageNumber($page);

        $result = $this->memberDataResult($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if($result <= 0)
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'Does not exist member.', 'result' => array()));
        else
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));

    }
    public function memberDataResult($paginator){
      $result = array();
      $counterLoop = 0;
      $viewer = Engine_Api::_()->user()->getViewer();
      // if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')){
      //   $memberEnable = true; 
      // }
      // $followActive = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1);
      // if($followActive){
      //   $unfollowText = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow'));
      //   $followText = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'));  
      // }
      foreach($paginator as $member){
        if(engine_in_array($member->getIdentity(), $this->_blockedUser)):
          continue;
        endif;
        $result['notification'][$counterLoop]['user_id'] = $member->getIdentity();
        $result['notification'][$counterLoop]['title'] = $member->getTitle();//preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $member->getTitle());
        
        //$age = $this->userAge($member);
        //if($age){
          //$result['notification'][$counterLoop]['age'] =  $age ;
        //}
        //user location
        // if(!empty($member->location))
        //    $result['notification'][$counterLoop]['location'] =   $member->location;
        // if($memberEnable){
        //   $userLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic')->getLocationData($member->getType(),$member->getIdentity());
        //   if($userLocation && !empty($member->location)){
        //     $result['notification'][$counterLoop]['location_data'] = $userLocation->toArray();
        //     $result['notification'][$counterLoop]['location_data']['location'] = $member->location;
        //   }
        // }

        
       
       //follow
        // if($followActive && $viewer->getIdentity() && $viewer->getIdentity() != $member->getIdentity()){
        //     if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')) {
        //         $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
        //         if (!$FollowUser) {
        //             $result['notification'][$counterLoop]['follow']['action'] = 'follow';
        //             $result['notification'][$counterLoop]['follow']['text'] = $followText;
        //         } else {
        //             $result['notification'][$counterLoop]['follow']['action'] = 'unfollow';
        //             $result['notification'][$counterLoop]['follow']['text'] = $unfollowText;
        //         }
        //     }
        // }
        // //Block
        // if($viewer->getIdentity() != $member->getIdentity()){
        //         if ($member->isBlockedBy($viewer)) {
        //             $result['notification'][$counterLoop]['block']['action'] = 'unblock';
        //             $result['notification'][$counterLoop]['block']['text'] = $this->view->translate("Unblock");
        //         } else {
        //             $result['notification'][$counterLoop]['block']['action'] = 'block';
        //             $result['notification'][$counterLoop]['block']['text'] = $this->view->translate("Block");
        //         }
        
        // }
       // if(!empty($memberEnable)){
       //  //mutual friends
       //  $mfriend = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer);
       //  if(!$member->isSelf($viewer)){
       //     $result['notification'][$counterLoop]['mutualFriends'] = $mfriend == 1 ? $mfriend.$this->view->translate(" mutual friend") : $mfriend.$this->view->translate(" mutual friends");
       //  }
       // }
        $result['notification'][$counterLoop]['user_image'] = $this->userImage($member->getIdentity(),"thumb.profile");
        // $result['notification'][$counterLoop]['membership'] = $this->friendRequest($member);
        $counterLoop++;
      }
      return $result;
  }
    public function blockAction(){
     $user_id = $this->_getParam("user_id",0);
     if(!$user_id){
       Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
     }
     
       $block = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$user_id));
       if(!$block){
           $table = Engine_Api::_()->getDbTable("blocks",'eticktokclone');
           $row = $table->createRow();
           $row->user_id = $this->view->viewer()->getIdentity();
           $row->blocked_user_id = $user_id;
           $row->save();
           // unblock
           $result['user_info']['block'] = array(
               'label' => $this->view->translate('Unblock Member'),
               'name' => 'remove_block_member',
               'params' => array(
                 'user_id' => $user_id
               ),
             );
       }else{
           Engine_Api::_()->getDbTable("blocks",'eticktokclone')->isBlocked(array("user_id"=>$user_id,'remove'=>true));
           // block
           $result['user_info']['block'] = array(
               'label' => $this->view->translate('Block Member'),
               'name' => 'block_member',
               'params' => array(
                 'user_id' => $user_id
               ),
             );
       }
       
       Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>"", 'result' => $result));

     
     
   }

   function followAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'user_not_autheticate'));
    }
    $item_id = $this->_getParam('user_id');
    if (intval($item_id) == 0) {
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => 'user_not_autheticate'));
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $itemTable = Engine_Api::_()->getItemTable('user');
    $tableFollow = Engine_Api::_()->getDbtable('follows', 'eticktokclone');
    $tableMainFollow = $tableFollow->info('name');

    $select = $tableFollow->select()
            ->from($tableMainFollow)
            ->where('resource_id = ?', $viewer_id)
            ->where('user_id = ?', $item_id);
    $result = $tableFollow->fetchRow($select);
    $member = Engine_Api::_()->getItem('user', $item_id);
    $followCount = 0;
    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();

            $user = Engine_Api::_()->getItem('user', $item_id);
            //Unfollow notification Work: Delete follow notification and feed
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "eticktokclone_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));
            // Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => "tickvideo_follow", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $user->getType(), "object_id = ?" => $user->getIdentity()));

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }

      $selectUser = $itemTable->select()->where('user_id =?', $item_id);
        $user = $itemTable->fetchRow($selectUser);
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array("is_content_follow"=>false)));
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('follows', 'eticktokclone')->getAdapter();
      $db->beginTransaction();
      try {
        $follow = $tableFollow->createRow();
        $follow->resource_id = $viewer_id;
        $follow->user_id = $item_id;
        $follow->resource_approved = 1;
        $follow->user_approved = 1;
        $follow->save();
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }
       //Send notification and activity feed work.
       $selectUser = $itemTable->select()->where('user_id =?', $item_id);
       $item = $itemTable->fetchRow($selectUser);
       $subject = $item;
       $owner = $subject->getOwner();
       if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {
           $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
           Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'eticktokclone_follow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
           Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'eticktokclone_follow');
          //  $result = $activityTable->fetchRow(array('type =?' => 'tickvideo_follow', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
          //  if (!$result) {
          //  $action = $activityTable->addActivity($viewer, $subject, 'tickvideo_follow');
          //  }
           //follow mail to another user
           Engine_Api::_()->getApi('mail', 'core')->sendSystem($subject->email, 'eticktokclone_follow', array('sender_title' => $viewer->getTitle(), 'object_link' => $viewer->getHref(), 'host' => $_SERVER['HTTP_HOST']));
       }
       Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array("is_content_follow"=>true)));
    }
  }

    function createAction(){
        // Upload video
        if (!$this->_helper->requireUser->isValid())
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'user_not_autheticate', 'result' => array()));
        if (!$this->_helper->requireAuth()->setAuthParams('video', null, 'create')->isValid())
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
        if (isset($_POST['c_type']))
            return $this->_forward('compose-upload', null, null, array('format' => 'json'));

        $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesvideo')->profileFieldId();
        //check ses modules integration
        $values['parent_id'] = $parent_id = $this->_getParam('parent_id', null);
        $values['parent_type'] = $parent_type = $this->_getParam('parent_type', null);

        // set up data needed to check quota
        $viewer = Engine_Api::_()->user()->getViewer();
        $values['user_id'] = $viewer->getIdentity();
        $paginator = Engine_Api::_()->getApi('core', 'sesvideo')->getVideosPaginator($values);
        $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'video', 'max');
        $this->view->current_count = $currentCount = $paginator->getTotalItemCount();
        if ($quota)
            $leftVideos = $quota - $currentCount;
        else
            $leftVideos = 0; //o means unlimited

        if (($this->view->current_count >= $this->view->quota) && !empty($this->quota)){
            // return error message
            $message = $this->view->translate('You have already uploaded the maximum number of videos allowed.');
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$message, 'result' => array()));
        }
        //Create form
        $this->view->form = $form = new Sesvideo_Form_Video(array('defaultProfileId' => $defaultProfileId,'fromApi'=>true,'fromTick'=>1));
        $form->getElement('location') ? $form->removeElement('location') : ""; 
        $form->getElement('search') ? $form->removeElement('search') : "";
        $form->getElement('resource_video_type') ? $form->removeElement('resource_video_type') : "";
        $form->getElement('resource_video_type') ? $form->removeElement('resource_video_type') : "";
        $form->getElement('rotation') ? $form->removeElement('rotation') : "";

        $form->getElement('url') ? $form->removeElement('url') : "";
        $form->getElement('upload_video') ? $form->removeElement('upload_video') : "";
        $form->getElement('artists') ? $form->removeElement('artists') : "";

        $form->removeElement('lat');
        $form->removeElement('lng');
        $form->removeElement('map-canvas');
        $form->removeElement('ses_location');
        $form->removeElement('embedUrl');
        $form->removeElement('code');
        $form->removeElement('id');
        $form->removeElement('ignore');
        if($form->getElement('photo_id'))
            $form->removeElement('photo_id');


        if($this->_getParam('getForm')) {
            $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
            $fields = array();
            foreach($formFields as $f){
                if($f["name"] != "orText")
                $fields[] = $f;
            }
            $this->generateFormFields($fields);
        }

        // Check if valid
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
            if(is_countable($validateFields) && engine_count($validateFields))
                $this->validateFormFields($validateFields);
        }

        // Process
        $values = $form->getValues();
        // if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesemoji')) {
        //     $bodyEmojis = explode(' ', $values['title']);
        //     foreach($bodyEmojis as $bodyEmoji) {
        //       $emojisCode = Engine_Api::_()->sesemoji()->EncodeEmoji($bodyEmoji);
        //       $values['title'] = str_replace($bodyEmoji,$emojisCode,$body);
        //     }
        //     $bodyEmojis = explode(' ', $values['description']);
        //     foreach($bodyEmojis as $bodyEmoji) {
        //       $emojisCode = Engine_Api::_()->sesemoji()->EncodeEmoji($bodyEmoji);
        //       $values['description'] = str_replace($bodyEmoji,$emojisCode,$body);
        //     }
        // }
        $values["is_tickvideo"] = 1;
        $video_type = $_POST['type'] =3;

        $validateVideo = empty($_FILES['video']['name']) ?  0 : 1;


        if(!$validateVideo){

            $error = ('Please select video to upload.');
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$error, 'result' => array()));
        }

        $values['type'] = 3;


        $values['owner_id'] = $viewer->getIdentity();
        $insert_action = false;
        $db = Engine_Api::_()->getDbtable('videos', 'sesvideo')->getAdapter();
        $db->beginTransaction();
        if(!empty($_POST['rotation'])){
            $rotation = $_POST['rotation'];
            if($rotation == 1){
                $_POST['rotation'] = 90;
            }else if($rotation == 2){
                $_POST['rotation'] = 180;
            }else if($rotation == 3){
                $_POST['rotation'] = 270;
            }
        }
        try {
            $viewer = Engine_Api::_()->user()->getViewer();
            $isApproveUploadOption = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $viewer, 'video_approve');
            $approveUploadOption = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $viewer, 'video_approve_type');
            $approve = 1;
            if($isApproveUploadOption){
                foreach($approveUploadOption as $valuesIs){
                    if ($values['type'] == 3 && $valuesIs == 'myComputer') {
                        //my computer
                        $approve = 0;
                        break;
                    }elseif($valuesIs == "iframely"){
                        $approve = 0;
                        break;
                    }
                }
            }
            if(!empty($_POST['song_id'])){
                $values['song_id'] = $_POST['song_id'];
            }

            //Create video
            $table = Engine_Api::_()->getDbtable('videos', 'sesvideo');
            $viewer = Engine_Api::_()->user()->getViewer();
            $values['owner_id'] = $viewer->getIdentity();
            $params = array(
                'owner_type' => 'user',
                'owner_id' => $viewer->getIdentity()
            );
            if(!$this->_getParam("not_merge_song")){
                $video = Engine_Api::_()->tickvideo()->createVideo($params, $_FILES['video'], $values);
            }else{
                $video = $this->setVideo($params);

            }
            if(empty($values['title'])){
                $video->title = $this->view->translate('Untitled Video');
                $video->save();
            }



            if ($values['type'] == 3 && isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '') {
                $values['photo_id'] = $this->setPhoto($form->photo_id, $video->video_id, true);
            }


            if (is_null($values['subsubcat_id']))
                $values['subsubcat_id'] = 0;
            if (is_null($values['subcat_id']))
                $values['subcat_id'] = 0;
            //disable lock if password not set.
            if (isset($values['is_locked']) && $values['is_locked'] && $values['password'] == '')
                $values['is_locked'] = '0';
            if(empty($_FILES['photo_id']['name'])){
                unset($values['photo_id']);
            }
            $values['approve'] = $approve;

            if(empty($values['category_id']) || is_null($values['category_id'])){
                $values['category_id'] = "";
                $values['subcat_id'] = "";
                $values['subsubcat_id'] = "";
            }

            $video->setFromArray($values);
            $video->save();
            // Add fields


            $thumbnail = $values['thumbnail'];
						$thumbnailUrl = explode("?", $thumbnail)[0];
						$ext = ltrim(strrchr($thumbnailUrl, '.'), '.');
						if(strpos($thumbnailUrl,'vimeocdn') !== false){
							$ext = "png";
						} else if(strpos($thumbnailUrl,'dmcdn') !== false){
							$ext = "jpeg";
						}
						$thumbnail_parsed = @parse_url($thumbnail);

            if ($thumbnail && @GetImageSize($thumbnail)) {
                $valid_thumb = true;
            } else {
                $valid_thumb = false;
            }


            if(isset($_FILES['photo_id']['name']) && $_FILES['photo_id']['name'] != '' && $values['type'] != 3 ) {
                $video->photo_id = $this->setPhoto($form->photo_id, $video->video_id, true);
                $video->save();
            } else if ($valid_thumb && $thumbnail && $ext && $thumbnail_parsed && engine_in_array($ext, array('jpg', 'jpeg', 'gif', 'png'))) {

                $tmp_file = APPLICATION_PATH . '/temporary/link_' . md5($thumbnail) . '.' . $ext;
                $thumb_file = APPLICATION_PATH . '/temporary/link_thumb_' . md5($thumbnail) . '.' . $ext;

                $src_fh = fopen($thumbnail, 'r');
                $tmp_fh = fopen($tmp_file, 'w');
                stream_copy_to_stream($src_fh, $tmp_fh, 1024 * 1024 * 2);
                //resize video thumbnails
                $image = Engine_Image::factory();
                $image->open($tmp_file)
                    ->resize(500, 500)
                    ->write($thumb_file)
                    ->destroy();
                try {
                    $thumbFileRow = Engine_Api::_()->storage()->create($thumb_file, array(
                        'parent_type' => $video->getType(),
                        'parent_id' => $video->getIdentity()
                    ));
                    // Remove temp file
                    @unlink($thumb_file);
                    @unlink($tmp_file);
                    $video->status = 1;
                    $video->photo_id = $thumbFileRow->file_id;
                    $video->save();

                } catch (Exception $e){
                    @unlink($thumb_file);
                    @unlink($tmp_file);
                }
            }
            if($values['type'] == 'iframely') {
                $video->status = 1;
                $video->save();
                $insert_action = true;
            }


            if(!empty($_POST['location'])){
                $latlng = Engine_Api::_()->sesapi()->getCoordinates($_POST['location']);
                if($latlng){
                    $_POST['lat'] = $latlng['lat'];
                    $_POST['lng'] = $latlng['lng'];
                }
            }

            if (isset($_POST['lat']) && isset($_POST['lng']) && $_POST['lat'] != '' && $_POST['lng'] != '') {
                $dbGetInsert = Engine_Db_Table::getDefaultAdapter();
                $dbGetInsert->query('INSERT INTO engine4_sesbasic_locations (resource_id, lat, lng , resource_type) VALUES ("' . $video->video_id . '", "' . $_POST['lat'] . '","' . $_POST['lng'] . '","sesvideo_video")    ON DUPLICATE KEY UPDATE    lat = "' . $_POST['lat'] . '" , lng = "' . $_POST['lng'] . '"');
            }
            if ($values['ignore'] == true) {
                $video->status = 1;
                $video->save();
                $insert_action = true;
            }
            // CREATE AUTH STUFF HERE
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
            if (isset($values['auth_view']))
                $auth_view = $values['auth_view'];
            else
                $auth_view = "everyone";
            $viewMax = array_search($auth_view, $roles);
            foreach ($roles as $i => $role) {
                $auth->setAllowed($video, $role, 'view', ($i <= $viewMax));
            }
            $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
            if (isset($values['auth_comment']))
                $auth_comment = $values['auth_comment'];
            else
                $auth_comment = "everyone";
            $commentMax = array_search($auth_comment, $roles);
            foreach ($roles as $i => $role) {
                $auth->setAllowed($video, $role, 'comment', ($i <= $commentMax));
            }
            // Add tags
            $tags = preg_split('/[,]+/', $values['tags']);
            $video->tags()->addTagMaps($viewer, $tags);
            $db->commit();

        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));

        }
        $db->beginTransaction();
        try {
            if ($approve && $video->status == 1) {
                $owner = $video->getOwner();
                //Create Activity Feed


                $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($owner, $video, 'sesvideo_video_create');
                if ($action != null) {
                    Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $video);
                }

                // Rebuild privacy
                $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
                foreach ($actionTable->getActionsByObject($video) as $action) {
                    $actionTable->resetActivityBindings($action);
                }
            }

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
        }
        $result["video"]["message"] = $this->view->translate("Video created successfully.");
        $result['video']['id'] = $video->getIdentity();

        //convert video
       // $this->_process($video->getIdentity(),'mp4');
        //add video in channel

        if(!empty($_POST['channel_id'])) {
            $queryString = '';
            $valueChanels['chanel_id'] = $_POST['channel_id'];
            $valueChanels['video_id'] = $video->getIdentity();
            $valueChanels['owner_id'] = $viewer->getIdentity();
            $valueChanels['creation_date'] = 'NOW()';
            $valueChanels['modified_date'] = 'NOW()';
            $queryString .= '(' . implode(',', $valueChanels) . '),';
            $dbObject = Engine_Db_Table::getDefaultAdapter();
            $query = 'INSERT IGNORE INTO engine4_sesvideo_chanelvideos (`chanel_id`, `video_id` ,`owner_id`,`creation_date`,`modified_date`) VALUES ';
            $stmt = $dbObject->query($query . rtrim($queryString, ','));
        }
        if ((($video->type == 3 || $videos->type == 'upload') && $video->status != 1) || !$approve) {
            $result['video']['redirect'] = "manage";
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>"", 'result' => $result));
        }
        if ($parent_id && $parent_type == 'sesevent_event') {
            $result['video']['redirect'] = "profile_event";
            $result['video']['id'] = $parent_id;
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>"", 'result' => $result));
        } elseif ($parent_id && $parent_type == 'sesblog_blog') {
            $result['video']['redirect'] = "profile_blog";
            $result['video']['id'] = $parent_id;
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>"", 'result' => $result));
        } else {
            $result['video']['redirect'] = "video_view";
            $result['video']['id'] = $video->getIdentity();
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>"", 'result' => $result));
        }
    }

    protected function setVideo($params) {
        // create video item
        $file = $_FILES['video'];
        $video = Engine_Api::_()->getDbtable('videos', 'sesvideo')->createRow();
        $file_ext = pathinfo($file['name']);
        $file_ext = $file_ext['extension'];
        $video->save();
        
        // Store video in temporary storage object for ffmpeg to handle
        $storage = Engine_Api::_()->getItemTable('storage_file');
        $params = array(
            'parent_id' => $video->getIdentity(),
            'parent_type' => $video->getType(),
            'user_id' => $this->view->viewer()->getIdentity(),
            'mime_major' => 'video',
            'mime_minor' => $file_ext,
        );
        $video->code = $file_ext;
        $storageObject = $storage->createFile($file, $params);
        $video->file_id = $file_id = $storageObject->file_id;
                
        // Remove temporary file
        
        $video->save();
        if($file_ext == 'mp4' || $file_ext == 'flv'){
            $video->status = 1;
            $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, null);
            $file = $file->map();
            if(strpos($file,'http') === false ){
                $file = APPLICATION_PATH.$file;
            }
            $video->duration = $duration = $this->getVideoDuration($video,$_FILES['video']['tmp_name']);
            if($duration){
                $thumb_splice = $duration / 2;
                $this->getVideoThumbnail($video,$thumb_splice,$_FILES['video']['tmp_name']);
            }
            $video->save();
            //@unlink($file['tmp_name']);
            return $video;
        }
        //@unlink($file['tmp_name']);
        return $video;
      }
      public function getVideoThumbnail($video,$thumb_splice,$file = false){
		$tmpDir = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . 'video';
		$thumbImage = $tmpDir . DIRECTORY_SEPARATOR . $video -> getIdentity() . '_thumb_image.jpg';
		$ffmpeg_path = Engine_Api::_() -> getApi('settings', 'core') -> video_ffmpeg_path;
		if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path))
		{ 
			$output = null;
			$return = null;
			exec($ffmpeg_path . ' -version', $output, $return);
			if ($return > 0)
			{
				return 0;
			}
		}
		if(!$file)
			$fileExe = $video->code;
		else
			$fileExe = $file;
		$output = PHP_EOL;
		$output .= $fileExe . PHP_EOL;
		$output .= $thumbImage . PHP_EOL;
		$thumbCommand = $ffmpeg_path . ' ' . '-i ' . escapeshellarg($fileExe) . ' ' . '-f image2' . ' ' . '-ss ' . $thumb_splice . ' ' . '-vframes ' . '1' . ' ' . '-v 2' . ' ' . '-y ' . escapeshellarg($thumbImage) . ' ' . '2>&1';
		// Process thumbnail
		$thumbOutput = $output . $thumbCommand . PHP_EOL . shell_exec($thumbCommand);
		// Check output message for success
        $thumbSuccess = true;
		if (preg_match('/video:0kB/i', $thumbOutput))
		{
			$thumbSuccess = false;
		}
       
		// Resize thumbnail
		if ($thumbSuccess && is_file($thumbImage))
		{
			try
			{
				$image = Engine_Image::factory();
				$image->open($thumbImage)->resize(500, 500)->write($thumbImage)->destroy();
				$thumbImageFile = Engine_Api::_()->storage()->create($thumbImage, array(
					'parent_id' => $video -> getIdentity(),
					'parent_type' => $video -> getType(),
					'user_id' => $video -> owner_id
					)
				);
				$video->photo_id = $thumbImageFile->file_id;
				$video->save();
				@unlink($thumbImage);
				return true;
			}
			catch (Exception $e)
			{
				throw $e;
				@unlink($thumbImage);
			}
		}
        
		 @unlink(@$thumbImage);
		 return false;
	}
      public function getVideoDuration($video,$file = false)
      {
          $duration = 0;
          if ($video)
          {
                  $ffmpeg_path = Engine_Api::_() -> getApi('settings', 'core') -> video_ffmpeg_path;
                  
                  if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path))
                  {
                      $output = null;
                      $return = null;
                      exec($ffmpeg_path . ' -version', $output, $return);
                      
                      if ($return > 0)
                      {
                          return 0;
                      }
                  }
                  if(!$file)
                      $fileExe = $video->code;
                  else
                      $fileExe = $file;
                  // Prepare output header
                  $fileCommand = $ffmpeg_path . ' ' . '-i ' . escapeshellarg($fileExe) . ' ' . '2>&1';
                  // Process thumbnail
                  $fileOutput = shell_exec($fileCommand);
                  // Check output message for success
                  $infoSuccess = true;
                 
                  if (preg_match('/video:0kB/i', $fileOutput))
                  {
                      $infoSuccess = false;
                  }
                  
                  // Resize thumbnail
                  if ($infoSuccess)
                  {
                      // Get duration of the video to caculate where to get the thumbnail
                      if (preg_match('/Duration:\s+(.*?)[.]/i', $fileOutput, $matches))
                      {
                          list($hours, $minutes, $seconds) = preg_split('[:]', $matches[1]);
                          $duration = ceil($seconds + ($minutes * 60) + ($hours * 3600));
                      }
                  }
  
          }
          return $duration;
      }
    
    private function getFFMPEGPath() {
        // Check we can execute
        if (!function_exists('shell_exec')) {
            throw new Sesvideo_Model_Exception('Unable to execute shell commands using shell_exec(); the function is disabled.');
        }

        if (!function_exists('exec')) {
            throw new Sesvideo_Model_Exception('Unable to execute shell commands using exec(); the function is disabled.');
        }

        // Make sure FFMPEG path is set
        $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
        if (!$ffmpeg_path) {
            throw new Sesvideo_Model_Exception('Ffmpeg not configured');
        }

        // Make sure FFMPEG can be run
        if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path)) {
            $output = null;
            $return = null;
            exec($ffmpeg_path . ' -version', $output, $return);

            if ($return > 0) {
                throw new Sesvideo_Model_Exception('Ffmpeg found, but is not executable');
            }
        }

        return $ffmpeg_path;
    }

    private function getTmpDir() {
        // Check the video temporary directory
        $tmpDir = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' .
            DIRECTORY_SEPARATOR . 'video';

        if (!is_dir($tmpDir) && !mkdir($tmpDir, 0777, true)) {
            throw new Sesvideo_Model_Exception('Video temporary directory did not exist and could not be created.');
        }

        if (!is_writable($tmpDir)) {
            throw new Sesvideo_Model_Exception('Video temporary directory is not writable.');
        }

        return $tmpDir;
    }

    private function getVideo($video) {
        // Get the video object
        if (is_numeric($video)) {
            $video = Engine_Api::_()->getItem('video', $video);
        }

        if (!($video instanceof Sesvideo_Model_Video)) {
            throw new Sesvideo_Model_Exception('Argument was not a valid video');
        }

        return $video;
    }

    private function getStorageObject($video) {
        // Pull video from storage system for encoding
        $storageObject = Engine_Api::_()->getItem('storage_file', $video->file_id);

        if (!$storageObject) {
            throw new Sesvideo_Model_Exception('Video storage file was missing');
        }

        return $storageObject;
    }

    private function getOriginalPath($storageObject) {
        $originalPath = $storageObject->temporary();

        if (!file_exists($originalPath)) {
            throw new Sesvideo_Model_Exception('Could not pull to temporary file');
        }

        return $originalPath;
    }

    private function getVideoFilters($video, $width, $height) {
        $filters = "scale=$width:$height";

        if ($video->rotation > 0) {
            $filters = "pad='max(iw,ih*($width/$height))':ow/($width/$height):(ow-iw)/2:(oh-ih)/2,$filters";

            if ($video->rotation == 180)
                $filters = "hflip,vflip,$filters";
            else {
                $transpose = array(90 => 1, 270 => 2);

                if (empty($transpose[$video->rotation]))
                    throw new Sesvideo_Model_Exception('Invalid rotation value');

                $filters = "transpose=${transpose[$video->rotation]},$filters";
            }
        }

        return $filters;
    }

    private function conversionSucceeded($video, $videoOutput, $outputPath) {
        $success = true;

        // Unsupported format
        if (preg_match('/Unknown format/i', $videoOutput) ||
            preg_match('/Unsupported codec/i', $videoOutput) ||
            preg_match('/patch welcome/i', $videoOutput) ||
            preg_match('/Audio encoding failed/i', $videoOutput) ||
            !is_file($outputPath) ||
            filesize($outputPath) <= 0) {
            $success = false;
            $video->status = 3;
        }

        // This is for audio files
        else if (preg_match('/video:0kB/i', $videoOutput)) {
            $success = false;
            $video->status = 5;
        }

        return $success;
    }

    private function notifyOwner($video, $owner) {
        $translate = Zend_Registry::get('Zend_Translate');
        $language = !empty($owner->language) && $owner->language != 'auto' ? $owner->language : null;

        $notificationMessage = '';
        $exceptionMessage = 'Unknown encoding error.';

        if ($video->status == 3) {
            $exceptionMessage = 'Video format is not supported by FFMPEG.';
            $notificationMessage = 'Video conversion failed. Video format is not supported by FFMPEG. Please try %1$sagain%2$s.';
        } else if ($video->status == 5) {
            $exceptionMessage = 'Audio-only files are not supported.';
            $notificationMessage = 'Video conversion failed. Audio files are not supported. Please try %1$sagain%2$s.';
        } else if ($video->status == 7) {
            $notificationMessage = 'Video conversion failed. You may be over the site upload limit.  Try %1$suploading%2$s a smaller file, or delete some files to free up space.';
        }

        $notificationMessage = $translate->translate(sprintf($notificationMessage, '', ''), $language);

        Engine_Api::_()->getDbtable('notifications', 'activity')
            ->addNotification($owner, $owner, $video, 'tickvideo_processed_failed', array(
                'message' => $notificationMessage,
                'message_link' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sesvideo_general', true),
            ));

        return $exceptionMessage;
    }

    private function getDuration($videoOutput) {
        $duration = 0;

        if (preg_match('/Duration:\s+(.*?)[.]/i', $videoOutput, $matches)) {
            list($hours, $minutes, $seconds) = preg_split('[:]', $matches[1]);
            $duration = ceil($seconds + ($minutes * 60) + ($hours * 3600));
        }

        return $duration;
    }

    private function generateThumbnail($outputPath, $output, $thumb_splice, $thumbPath, $log, $video) {
        if ($video->photo_id && !is_null($video->photo_id))
            return false;
        $ffmpeg_path = $this->getFFMPEGPath();
        // Thumbnail process command
        $thumbCommand = $ffmpeg_path . ' '
            . '-i ' . escapeshellarg($outputPath) . ' '
            . '-f image2' . ' '
            . '-ss ' . $thumb_splice . ' '
            . '-vframes 1' . ' '
            . '-v 2' . ' '
            . '-y ' . escapeshellarg($thumbPath) . ' '
            . '2>&1';

        // Process thumbnail
        $thumbOutput = $output .
            $thumbCommand . PHP_EOL .
            shell_exec($thumbCommand);

        // Log thumb output
        if ($log) {
            $log->log($thumbOutput, Zend_Log::INFO);
        }

        // Check output message for success
        $thumbSuccess = true;
        if (preg_match('/video:0kB/i', $thumbOutput)) {
            $thumbSuccess = false;
        }

        // Resize thumbnail
        if ($thumbSuccess) {
            try {
                $image = Engine_Image::factory();
                $image->open($thumbPath)
                    ->resize(500, 500)
                    ->write($thumbPath)
                    ->destroy();
            } catch (Exception $e) {
                $this->_addMessage((string) $e->__toString());
                $thumbSuccess = false;
            }
        }

        return $thumbSuccess;
    }

    private function buildVideoCmd($video, $width, $height, $type, $originalPath, $outputPath, $compatibilityMode = false) {
        $ffmpeg_path = $this->getFFMPEGPath();
        $music = "";
        if($video['song_id']){
            $music = Engine_Api::_()->getItem('tickvideo_music',$video['song_id']);
            if($music){
                $file = Engine_Api::_()->getItem('storage_file',$music['file_id']);
                if($file) {
                    if($file->service_id == 2){
                        $path = $file->map();
                    }else {
                        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR .$file->storage_path;
                    }
                    $music = " -i ". $path . ' -c copy -map 0:v:0 -map 1:a:0 -shortest' . ' ';
                }
            }
        }

        $videoCommand = $ffmpeg_path . ' '
            . '-i ' . escapeshellarg($originalPath) . ' '
            .$music
            . '-ab 64k' . ' '
            . '-ar 44100' . ' '
            . '-qscale 5' . ' '
            . '-r 25' . ' ';

        if ($type == 'mp4')
            $videoCommand .= '-vcodec libx264' . ' '
                . '-acodec aac' . ' '
                . '-strict experimental' . ' '
                . '-preset veryfast' . ' '
                . '-f mp4' . ' '
            ;
        else
            $videoCommand .= '-vcodec flv -f flv ';

        if ($compatibilityMode) {
            $videoCommand .= "-s ${width}x${height}" . ' ';
        } else {
            $filters = $this->getVideoFilters($video, $width, $height);
            $videoCommand .= '-vf "' . $filters . '" ';
        }

        $videoCommand .=
            '-y ' . escapeshellarg($outputPath) . ' '
            . '2>&1';

        return $videoCommand;
    }

    protected function _process($video, $type, $compatibilityMode = false) {
        $tmpDir = $this->getTmpDir();
        $video = $this->getVideo($video);

        // Update to encoding status
        $video->status = 2;
        $video->type = 3;
        $video->save();

        // Prepare information
        $owner = $video->getOwner();

        // Pull video from storage system for encoding
        $storageObject = $this->getStorageObject($video);
        $ffmpeg_path = $this->getFFMPEGPath();
        $originalPath = $this->getOriginalPath($storageObject);
        $width = 500;
        $height = 500;
        $filePath = APPLICATION_PATH . DIRECTORY_SEPARATOR.$storageObject->storage_path;
        $pos = strrpos($ffmpeg_path, 'ffmpeg');
        if($pos !== false) {
          $ffmpeg_path = substr_replace($ffmpeg_path, 'ffprobe', $pos, strlen('ffmpeg'));
        }
        $dimentions = shell_exec($ffmpeg_path.' '.escapeshellarg($originalPath).' 2>&1');
        if($dimentions){
            preg_match('(\b[^0]\d+x[^0]\d+\b)', $dimentions, $matches);
            if(!empty($matches[0])){
                list($width,$height) = explode("x",$matches[0]);
            }
        }


        $outputPath = $tmpDir . DIRECTORY_SEPARATOR . $video->getIdentity() . '_vconverted.' . $type;
        $thumbPath = $tmpDir . DIRECTORY_SEPARATOR . $video->getIdentity() . '_vthumb.jpg';

        $videoCommand = $this->buildVideoCmd($video, $width, $height, $type, $originalPath, $outputPath, $compatibilityMode);

        // Prepare output header
        $output = PHP_EOL;
        $output .= $originalPath . PHP_EOL;
        $output .= $outputPath . PHP_EOL;
        $output .= $thumbPath . PHP_EOL;
        // Prepare logger
        $log = new Zend_Log();
        $log->addWriter(new Zend_Log_Writer_Stream(APPLICATION_PATH . '/temporary/log/video.log'));

        // Execute video encode command
        $videoOutput = $output .
            $videoCommand . PHP_EOL .
            shell_exec($videoCommand);

        // Log
        if ($log) {
            $log->log($videoOutput, Zend_Log::INFO);
        }

        // Check for failure
        $success = $this->conversionSucceeded($video, $videoOutput, $outputPath);

        // Failure
        if (!$success) {
            if (!$compatibilityMode) {
                $this->_process($video, true);
                return;
            }

            $exceptionMessage = '';

            $db = $video->getTable()->getAdapter();
            $db->beginTransaction();

            try {
                $video->save();
                $exceptionMessage = $this->notifyOwner($video, $owner);
                $db->commit();
            } catch (Exception $e) {
                $videoOutput .= PHP_EOL . $e->__toString() . PHP_EOL;

                if ($log) {
                    $log->write($e->__toString(), Zend_Log::ERR);
                }

                $db->rollBack();
            }

            // Write to additional log in dev
            if (APPLICATION_ENV == 'development') {
                file_put_contents($tmpDir . '/' . $video->video_id . '.txt', $videoOutput);
            }

            throw new Sesvideo_Model_Exception($exceptionMessage);
        }

        // Success
        else {
            // Get duration of the video to caculate where to get the thumbnail
            $duration = $this->getDuration($videoOutput);

            // Log duration
            if ($log) {
                $log->log('Duration: ' . $duration, Zend_Log::INFO);
            }

            // Fetch where to take the thumbnail
            $thumb_splice = $duration / 2;

            $thumbSuccess = $this->generateThumbnail($outputPath, $output, $thumb_splice, $thumbPath, $log, $video);

            // Save video and thumbnail to storage system
            $params = array(
                'parent_id' => $video->getIdentity(),
                'parent_type' => $video->getType(),
                'user_id' => $video->owner_id
            );

            $db = $video->getTable()->getAdapter();
            $db->beginTransaction();

            try {
                $storageObject->setFromArray($params);
                $storageObject->store($outputPath);

                if ($thumbSuccess) {
                    $thumbFileRow = Engine_Api::_()->storage()->create($thumbPath, $params);
                }

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();

                // delete the files from temp dir
                unlink($originalPath);
                unlink($outputPath);

                if ($thumbSuccess) {
                    unlink($thumbPath);
                }

                $video->status = 7;
                $video->save();

                $this->notifyOwner($video, $owner);

                throw $e; // throw
            }

            // Video processing was a success!
            // Save the information
            if ($thumbSuccess) {
                $video->photo_id = $thumbFileRow->file_id;
            }

            $video->duration = $duration;
            $video->status = 1;
            $video->save();

            // delete the files from temp dir
            unlink($originalPath);
            unlink($outputPath);
            unlink($thumbPath);

            // insert action in a separate transaction if video status is a success
            $actionsTable = Engine_Api::_()->getDbtable('actions', 'activity');
            $db = $actionsTable->getAdapter();
            $db->beginTransaction();

            try {
                $data = "";
                if(!empty($video->activity_text)){
                    $data = $video->activity_text;
                    $action = $actionsTable->addActivity($owner, $owner, 'post_self_video',$data);
                    $action->body = $video->activity_text;
                    $action->save();
                    $video->activity_text = "";
                    $video->save();
                }else
                    $action = $actionsTable->addActivity($owner, $video, 'sesvideo_video_create',$data);
                if ($action) {
                    $actionsTable->attachActivity($action, $video);
                }



                // notify the owner
                Engine_Api::_()->getDbtable('notifications', 'activity')
                    ->addNotification($owner, $owner, $video, 'tickvideo_processed');

                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                
            }
        }
        return true;
    }





    function getVideos($paginator,$manage = "",$extra = array(),$position = 0){
        $result = array();
        $counter = 0;
        $allowShowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratevideo.show', 1);
        $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating', 1);
        if ($allowRating == 0) {
            if ($allowShowRating == 0)
                $showRating = false;
            else
                $showRating = true;
        } else  
            $showRating = true;

        $channelVideoObj = Engine_Api::_()->getDbTable('chanelvideos','sesvideo');


        $memberEnable = false;
        if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')){
            $memberEnable = true; 
        }
        $followActive = true;
    

        foreach($paginator as $videos){

            if(!$videos->authorization()->isAllowed($this->view->viewer(), 'view')){
                //continue;
            }

            if ($videos->type != 3 && $videos->type != 'upload') {
                continue;
            }
            $video = $videos->toArray();
            if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesemoji')) {
                $video["title"] =  Engine_Api::_()->sesemoji()->DecodeEmoji($video["title"]);
                $video["description"] =  Engine_Api::_()->sesemoji()->DecodeEmoji($video["description"]);
             }
            if(!$showRating)
                unset($video["rating"]);
            $videoI = Engine_Api::_()->getItem('video',$videos->video_id);
            $videoI->view_count = new Zend_Db_Expr('view_count + 1');
            $videoI->save();


            $owner = Engine_Api::_()->getItem("user",$videoI->owner_id);
            $video["follow_enable"] = true;
            $video["is_user_follow"] = false;
            if( $owner->getIdentity() && $owner->getIdentity() != $this->view->viewer()->getIdentity()){
                $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($owner->user_id);
                if ($FollowUser) {
                    $video["is_user_follow"] = true;
                } 
            }

            $videoTags = $videoI->tags()->getTagMaps();
            $video['tag'] = array();
            foreach ($videoTags as $tagmap) {
                $tag = $tagmap->getTag();
                if ($tag && !empty($tag->getTitle())) {
                    $video['tag'][] = $tag->getTitle();
                }
            }
            $video['current_position'] = $position;
            $select = $channelVideoObj->select()->where("video_id =?",$videos->video_id)->limit(1);
            $video['isChannelFollow'] = 0;
            $chanelItem = $channelVideoObj->fetchRow($select);
            if($chanelItem){
                $chanel = Engine_Api::_()->getItem('sesvideo_chanel',$chanelItem->chanel_id);
                if($chanel){
                    $video['channel_id'] = $chanel->getIdentity();
                    $video['channel_image'] = $this->getBaseUrl(false,$chanel->getPhotoUrl());

                    if(isset($chanel->follow) && $chanel->follow == 1 && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.subscription',1)){
                        if($chanel->follow)
                            $video['isFollorActive'] = 1;
                        $follow =  Engine_Api::_()->getDbtable('chanelfollows', 'sesvideo')->checkFollow(Engine_Api::_()->user()->getViewer()->getIdentity(),$chanel->chanel_id);
                        $video['isChannelFollow'] = $follow;
                    }


                }
            }
            $video["description"] = preg_replace('/\s+/', ' ', $video["description"]);
            $video['user_title'] = $videos->getOwner()->getTitle();
            $video['user_image'] = $this->userImage($videos->getOwner()->getIdentity(),"thumb.profile");
            $video['user_username'] = $videos->getOwner()->username;
            if($this->view->viewer()->getIdentity() != 0){
                try{
                    $video['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($videos);
                    $video['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($videos);
                    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1)) {
                        $video['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($videos,'favourites','sesvideo','video');
                        $video['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($videos,'favourites','sesvideo','video');
                    }
                }catch(Exception $e){}
            }
            if($manage){
                $viewer = Engine_Api::_()->user()->getViewer();
                $menuoptions= array();
                $canEdit = $this->_helper->requireAuth()->setAuthParams($videos, null, 'edit')->isValid();
                $counterMenu = 0;
                if($canEdit){
                    $menuoptions[$counterMenu]['name'] = "edit";
                    $menuoptions[$counterMenu]['label'] = $this->view->translate("Edit");
                    $counterMenu++;
                }
                $canDelete = $this->_helper->requireAuth()->setAuthParams($videos, null, 'delete')->isValid();
                if($canDelete){
                    $menuoptions[$counterMenu]['name'] = "delete";
                    $menuoptions[$counterMenu]['label'] = $this->view->translate("Delete");
                }
                $video['menus'] = $menuoptions;
            }
            if( $videos->duration >= 3600 ) {
                $duration = gmdate("H:i:s", $videos->duration);
            } else {
                $duration = gmdate("i:s", $videos->duration);
            }
            $video['duration'] = $duration;

            $photo = $this->getBaseUrl(false,$videos->getPhotoUrl());
            if($photo)
                $video["share"]["imageUrl"] = $photo;
            $video["share"]["url"] = $this->getBaseUrl(false,$videos->getHref());
            $video["share"]["title"] = $videos->getTitle();
            $video["share"]["description"] = strip_tags($videos->getDescription());
            $video["share"]['urlParams'] = array(
                "type" => $videos->getType(),
                "id" => $videos->getIdentity()
            );
            if(is_null($video["share"]["title"]))
                unset($video["share"]["title"]);

            $video['images'] = Engine_Api::_()->sesapi()->getPhotoUrls($videos,'',"");
            if(!engine_count($video['images']))
                $video['images']['main'] = $this->getBaseUrl(false,$videos->getPhotoUrl());

            if ($videos->type == 3 || $videos->type == 'upload') {
                if (!empty($videos->file_id)) {
                    $storage_file = Engine_Api::_()->getItem('storage_file', $videos->file_id);
                    if($storage_file){
                        $video['iframeURL'] = $this->getBaseUrl(false,$storage_file->map());
                        $video['video_extension'] = $storage_file->extension;
                    }else{
                        $video['iframeURL'] = "sss";
                        $video['video_extension'] = "mp4";
                    }
                }
            }
            $song = array();
            if(engine_count($extra)){
                $videos = $extra;
            }
            if (!empty($videos->songfile_id)) {
                if (!empty($videos->songfile_id)) {
                    $storage_file = Engine_Api::_()->getItem('storage_file', $videos->songfile_id);
                    if($storage_file)
                        $song['url'] = $this->getBaseUrl(false,$storage_file->map());
                }
                if (!empty($videos->songphoto_id)) {
                    $song['images'] = Engine_Api::_()->sesapi()->getPhotoUrls($videos->songphoto_id,'',"");
                }
                $song['duration'] = $videos->songduration;
                $song['title'] = $videos->songtitle;
                $video['song'] = $song;
            }

            $result[$counter] = array_merge($video,array());
            $counter++;
        }
        return $result;
    }


    function getFollowUsers(){
        $viewer = Engine_Api::_()->user()->getViewer();
	
	$userTable = Engine_Api::_()->getDbtable('users', 'user');
	$userTableName = $userTable->info('name');
	$select = $userTable->select()
	  ->where('user_id <> ?', $viewer->getIdentity());
	$select->where('photo_id <> ?', 0);
	
    $select->where(" SELECT count(*) from engine4_sesvideo_videos where engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
    WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().") AND engine4_sesvideo_videos.owner_id = engine4_users.user_id AND engine4_sesvideo_videos.is_tickvideo = 1  > 0 ");
	$select->order('rand()');

	$peopleyoumayknow = Zend_Paginator::factory($select);
	$peopleyoumayknow->setItemCountPerPage(20);
	$peopleyoumayknow->setCurrentPageNumber(1);
        

    $videoTable = Engine_Api::_()->getDbtable('videos', 'sesvideo');
	$videoTableName = $userTable->info('name');
	

	$counterLoop = 0;
	$users = array();
	if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember'))
	  $memberEnable = true;
	foreach ($peopleyoumayknow as $member) {
	  if (!empty($memberEnable)) {
		//mutual friends
		$mfriend = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer);
		if (!$member->isSelf($viewer)) {
		  $users[$counterLoop]['mutualFriends'] = $mfriend == 1 ? $mfriend . $this->view->translate(" mutual friend") : $mfriend . $this->view->translate(" mutual friends");
		}
      }
      
      $owner = $member;
        $users[$counterLoop]["follow_enable"] = true;
        $users[$counterLoop]["is_user_follow"] = false;
        if($owner->getIdentity() && $owner->getIdentity() != $this->view->viewer()->getIdentity()){
            $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($owner->user_id);
            if ($FollowUser) {
                $users[$counterLoop]["is_user_follow"] = true;
            } 
        }


	  $users[$counterLoop]['user_id'] = $member->getIdentity();

      $select = $videoTable->select()
	  ->where('owner_id =?', $member->getIdentity())
      ->where("is_tickvideo = 1")
      ->order("video_id DESC")
      ->limit(1);

      $data = $videoTable->fetchRow($select);
        if($data){
            $storage_file = Engine_Api::_()->getItem('storage_file', $data->file_id);
            if($storage_file){
                $users[$counterLoop]['video']['iframeURL'] = $this->getBaseUrl(false,$storage_file->map());
                $users[$counterLoop]['video']['video_extension'] = $storage_file->extension;
            }
        }


	  
	  $users[$counterLoop]['title'] = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $member->getTitle());
	  $users[$counterLoop]['user_image'] = $this->userImage($member->getIdentity(), "thumb.profile");
        if($this->friendRequest($member)) {
        $users[$counterLoop]['membership'] = $this->friendRequest($member);
        }



	  $counterLoop++;
	}
	
	return $users;
    }

    function friendRequest($subject)
    {
  
      $viewer = Engine_Api::_()->user()->getViewer();
  
      // Not logged in
      if (!$viewer->getIdentity() || $viewer->getGuid(false) === $subject->getGuid(false)) {
        return 0;
      }
  
      // No blocked
      if ($viewer->isBlockedBy($subject)) {
        return 0;
      }
  
      // Check if friendship is allowed in the network
      $eligible = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.eligible', 2);
      if (!$eligible) {
        return 0;
      }
  
      // check admin level setting if you can befriend people in your network
      else if ($eligible == 1) {
  
        $networkMembershipTable = Engine_Api::_()->getDbtable('membership', 'network');
        $networkMembershipName = $networkMembershipTable->info('name');
  
        $select = new Zend_Db_Select($networkMembershipTable->getAdapter());
        $select
          ->from($networkMembershipName, 'user_id')
          ->join($networkMembershipName, "`{$networkMembershipName}`.`resource_id`=`{$networkMembershipName}_2`.resource_id", null)
          ->where("`{$networkMembershipName}`.user_id = ?", $viewer->getIdentity())
          ->where("`{$networkMembershipName}_2`.user_id = ?", $subject->getIdentity());
  
        $data = $select->query()->fetch();
  
        if (empty($data)) {
          return 0;
        }
      }
  
      // One-way mode
      $direction = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.direction', 1);
      if (!$direction) {
        $viewerRow = $viewer->membership()->getRow($subject);
        $subjectRow = $subject->membership()->getRow($viewer);
        $params = array();
  
        // Viewer?
        if (null === $subjectRow) {
          // Follow
          return array(
            'label' => $this->view->translate('Follow'),
            'action' => 'add',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/add.png',
          );
        } else if ($subjectRow->resource_approved == 0) {
          // Cancel follow request
          return array(
            'label' => $this->view->translate('Cancel Request'),
            'action' => 'cancel',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/remove.png',
          );
        } else {
          // Unfollow
          return array(
            'label' => $this->view->translate('Unfollow'),
            'action' => 'remove',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/remove.png',
          );
        }
        // Subject?
        if (null === $viewerRow) {
          // Do nothing
        } else if ($viewerRow->resource_approved == 0) {
          // Approve follow request
          return array(
            'label' => $this->view->translate('Approve Request'),
            'action' => 'confirm',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/add.png',
  
          );
        } else {
          // Remove as follower?
          return array(
            'label' => $this->view->translate('Unfollow'),
            'action' => 'remove',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/remove.png',
  
          );
        }
        if (engine_count($params) == 1) {
          return $params[0];
        } else if (engine_count($params) == 0) {
          return 0;
        } else {
          return $params;
        }
      }
  
      // Two-way mode
      else {
  
        $table =  Engine_Api::_()->getDbTable('membership', 'user');
        $select = $table->select()
          ->where('resource_id = ?', $viewer->getIdentity())
          ->where('user_id = ?', $subject->getIdentity());
        $select = $select->limit(1);
        $row = $table->fetchRow($select);
  
        if (null === $row) {
          // Add
          return array(
            'label' => $this->view->translate('Add Friend'),
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/add.png',
            'action' => 'add',
          );
        } else if ($row->user_approved == 0) {
          // Cancel request
          return array(
            'label' => $this->view->translate('Cancel Friend'),
            'action' => 'cancel',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/remove.png',
  
          );
        } else if ($row->resource_approved == 0) {
          // Approve request
          return array(
            'label' => $this->view->translate('Approve Request'),
            'action' => 'confirm',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/add.png',
  
          );
        } else {
          // Remove friend
          return array(
            'label' => $this->view->translate('Remove Friend'),
            'action' => 'remove',
            'icon' => $this->getBaseUrl() . 'application/modules/User/externals/images/friends/remove.png',
  
          );
        }
      }
    }
    function getFollowUsers1(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $channelTable = Engine_Api::_()->getDbtable('chanels', 'sesvideo');
        $channelTableName = $channelTable->info('name');
        $vcName = Engine_Api::_()->getDbtable('chanelvideos', 'sesvideo');
        $vcmName = $vcName->info('name');
        $videoTable = Engine_Api::_()->getDbtable('videos', 'sesvideo')->info("name");


        $select = $channelTable->select()
            ->from($channelTableName,'*')
            ->setIntegrityCheck(false)
            ->where($channelTableName.'.owner_id <> ?', $viewer->getIdentity())
            ->joinLeft($vcmName, "$vcmName.chanel_id = $channelTableName.chanel_id", array("total_videos" => "COUNT(".$vcmName.".video_id)",'chanelvideo_id'=>'video_id'))
            ->joinLeft($videoTable, "$videoTable.video_id = $vcmName.video_id",null)
            ->group("$vcmName.chanel_id")
            ->where('chanelvideo_id IS NOT NULL');
           // ->having("COUNT($vcmName.video_id) > 0");
        $select = $select->order("total_videos DESC");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));

        if($viewer->getIdentity()) {
            $select->where($channelTableName.".chanel_id NOT IN (SELECT chanel_id FROM engine4_sesvideo_chanelfollows WHERE owner_id = ".$viewer->getIdentity().")");
        }

        $select->order($channelTableName.'.view_count DESC');

        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber(1);

        $result = array();
        $counter = 0;
        $viewer = Engine_Api::_()->user()->getViewer();
        foreach($paginator as $channel){
            $item = $channel->toArray();
            $item["description"] = preg_replace('/\s+/', ' ', $item["description"]);
            $video['user_title'] = $channel->getOwner()->getTitle();
            $video['user_image'] = $this->userImage($channel->getOwner()->getIdentity(),"thumb.profile");
            $video['user_username'] = $channel->getOwner()->username;
            if($this->view->viewer()->getIdentity() != 0){
                $item['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($channel);
                $item['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($channel);
                if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1)) {
                    $item['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($channel,'favourites','sesvideo','sesvideo_chanel');
                    $item['content_favourite_count'] = (int) Engine_Api::_()->sesapi()->getContentFavouriteCount($channel,'favourites','sesvideo','sesvideo_chanel');
                }
            }
            $item['images'] = Engine_Api::_()->sesapi()->getPhotoUrls($channel,'',"");
            if(!engine_count($item['images']))
                $images['images']['main'] = $this->getBaseUrl(true,$channel->getPhotoUrl());

            if($channel->cover_id)
                $item['cover'] = Engine_Api::_()->sesapi()->getPhotoUrls($channel->cover_id,'',"");

            if(isset($channel->follow) && $channel->follow == 1 && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.subscription',1)){
                if($channel->follow)
                    $item['isFollorActive'] = 1;
                $follow =  Engine_Api::_()->getDbtable('chanelfollows', 'sesvideo')->checkFollow(Engine_Api::_()->user()->getViewer()->getIdentity(),$channel->chanel_id);
                $item['isFollow'] = $follow;
            }
            $result[$counter] =$item;

            $video_id = $channel->chanelvideo_id;
            $videoD = Engine_Api::_()->getItem('sesvideo_video',$video_id);
            if($videoD){
                $result[$counter]['video'] = $this->getVideos(array($videoD),'',$channel)[0];
            }

            $counter++;
        }
        return $result;
    }
    function followingAction($return = false){
        //channel followers
        $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
        $videoTable = $video->info('name');
        $id = $this->view->viewer()->getIdentity();

        $select = $video->select()->from($videoTable,'*')->setIntegrityCheck(false);
        $select->where($videoTable.'.status = ?',1);
        $select->where($videoTable.'.approve = ?',1);
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        $select->where($videoTable.'.is_tickvideo = 1');
        $select = $select->order('video_id DESC');
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->where($videoTable.'.owner_id IN (SELECT user_id FROM engine4_eticktokclone_follows WHERE resource_id = '.$id.')');
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit',5));
        $paginator->setCurrentPageNumber($this->_getParam('page',1));
        $result['videos'] = $this->getVideos($paginator,"");
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if(empty($return))
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
        else{
            return  array_merge(array( 'result' => $result),$extraParams);
        }
    }
    public function getItemsSelect($params, $select = null)
    {
      $_excludedLevels = array(1, 2, 3);
      if( $select == null ) {
        $select = $this->select();
      }
      $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
      $table = $video->info('name');
      $registeredPrivacy = array('everyone', 'registered');
      $viewer = Engine_Api::_()->user()->getViewer();
      if( $viewer->getIdentity() && !engine_in_array($viewer->level_id, $_excludedLevels) ) {
        $viewerId = $viewer->getIdentity();
        $netMembershipTable = Engine_Api::_()->getDbtable('membership', 'network');
        $viewerNetwork = $netMembershipTable->getMembershipsOfIds($viewer);
        if( !empty($viewerNetwork) ) {
          array_push($registeredPrivacy,'owner_network');
        }
        $friendsIds = $viewer->membership()->getMembersIds();
        $friendsOfFriendsIds = $friendsIds;
        foreach( $friendsIds as $friendId ) {
          $friend = Engine_Api::_()->getItem('user', $friendId);
          $friendMembersIds = $friend->membership()->getMembersIds();
          $friendsOfFriendsIds = array_merge($friendsOfFriendsIds, $friendMembersIds);
        }
      }
      if( !$viewer->getIdentity() ) {
        $select->where("view_privacy = ?", 'everyone');
      } elseif( !engine_in_array($viewer->level_id, $_excludedLevels) ) {
        $select->Where("$table.owner_id = ?", $viewerId)
          ->orwhere("view_privacy IN (?)", $registeredPrivacy);
        if( !empty($friendsIds) ) {
          $select->orWhere("view_privacy = 'owner_member' AND $table.owner_id IN (?)", $friendsIds);
        }
        if( !empty($friendsOfFriendsIds) ) {
          $select->orWhere("view_privacy = 'owner_member_member' AND $table.owner_id IN (?)", $friendsOfFriendsIds);
        }
        if( empty($viewerNetwork) && !empty($friendsOfFriendsIds) ) {
          $select->orWhere("view_privacy = 'owner_network' AND $table.owner_id IN (?)", $friendsOfFriendsIds);
        }
        $subquery = $select->getPart(Zend_Db_Select::WHERE);
        $select ->reset(Zend_Db_Select::WHERE);
        $select ->where(implode(' ',$subquery));
      }
     // if( isset($params['search']) ) {
        $select->where("search = ?", 1);
     // }
      return $select;
    }
    function foryouAction($return = false){
        //2 most viewed //2 most liked and rest newly created
        $viewer = Engine_Api::_()->user()->getViewer();
        $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
        $videoTable = $video->info('name');

        $result['videos'] = array();
        $videoNotIn = array(0);

        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.is_tickvideo = 1');
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());
        $select = $select->order('video_id DESC')->limit(5);
        $viewVideos = $video->fetchAll($select);

        foreach($viewVideos as $val){
            $videoNotIn[] = $val['video_id'];
        }
        
        if($return || $this->_getParam("page") == 1)
            $result['videos'] = $this->getVideos($viewVideos, "");

        

        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.is_tickvideo = 1');
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());
        $select = $select->order('comment_count DESC')->limit(5);
        $select->where($videoTable.'.video_id NOT IN (?)',$videoNotIn);
        $likeVideos = $video->fetchAll($select);
        foreach($likeVideos as $val){
            $videoNotIn[] = $val['video_id'];
        }
        if($return || $this->_getParam("page") == 1)
            $result['videos'] = array_merge($result['videos'],$this->getVideos($likeVideos, ""));



        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());
        $select = $select->order('like_count DESC')->limit(5);
        $select->where($videoTable.'.video_id NOT IN (?)',$videoNotIn);
        $likeVideos = $video->fetchAll($select);
        foreach($likeVideos as $val){
            $videoNotIn[] = $val['video_id'];
        }
        if($return || $this->_getParam("page") == 1)
            $result['videos'] = array_merge($result['videos'],$this->getVideos($likeVideos, ""));

        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.is_tickvideo = 1');
        $select->where($videoTable.'.video_id NOT IN (?)',$videoNotIn);
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());
        $select = $select->order('creation_date DESC');


        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $result['videos'] = array_merge($result['videos'],$this->getVideos($paginator, ""));


        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount() + engine_count($videoNotIn);
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if(empty($return))
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
        else{
            return  array_merge(array( 'result' => $result),$extraParams);
        }
    }
    function discoverAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
        $videoTable = $video->info('name');


        $result['videos'] = array();

        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.is_tickvideo = 1');
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());
        $select = $select->order('view_count DESC');
        if(!empty($_POST['title'])){
            $select = $select->where($videoTable . '.title LIKE "%' . $_POST['title'] . '%"');
        }


        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $result['videos'] = array_merge($result['videos'],$this->getVideos($paginator, ""));


        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if(empty($return))
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
        else{
            return  array_merge(array( 'result' => $result),$extraParams);
        }
    }
    function browseAction(){
        $result['following'] = $this->followingAction(true);
        $result['forYou'] = $this->foryouAction(true);
       

        $result['creator'] = $this->getFollowUsers();
        //echo "<pre>";var_dump($result);die;
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result));
    }
    function getCriteriaVideos($type,$position = 0){
        $video = Engine_Api::_()->getDbTable("videos",'sesvideo');
        $videoTable = $video->info('name');
        $select = $video->select()->from($videoTable, '*')->setIntegrityCheck(false);
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");
        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTable.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));
        $select->where($videoTable . '.status = ?', 1);
        $select->where($videoTable . '.approve = ?', 1);
        $select->where($videoTable.'.is_tickvideo = 1');
        $select->where($videoTable.'.type = 3 OR '.$videoTable.'.type = "upload"');
        //$select->where($videoTable . '.owner_id <> ?', $viewer->getIdentity());

        if($type == "recently_created"){
            $select->order("creation_date DESC");
        }else if($type == "most_viewed"){
            $select->order("view_count DESC");
        }else if($type == "most_liked"){
            $select->order("like_count DESC");
        }else if($type == "most_commented"){
            $select->order("comment_count DESC");
        }else if($type == "featured"){
            $select->where("is_featured = ?",1);
            $select->order("view_count DESC");
        }else if($type == "sponsored"){
            $select->where("is_sponsored = ?",1);
            $select->order("view_count DESC");
        }else if($type == "hot"){
            $select->where("is_hot = ?",1);
            $select->order("view_count DESC");
        }else if($type == "most_favourite"){
            $select->order("favourite_count DESC");
        }else if($type == "music_id"){
            $select->where("music_id = ?",$position);
            $select = $select->order('view_count DESC');
        }else{
            $select = $select->order('view_count DESC');
        }
        if(!empty($this->_getParam('title'))){
            $select = $select->where($videoTable . '.title LIKE "%' . $this->_getParam('title') . '%"');
        }
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $result['videos'] = $this->getVideos($paginator, "",array(),$position);


        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        return array_merge($result,$extraParams);
    }
    function gettagvideosAction($tag = 0){
        if(!$tag){
            $tag_id = $this->_getParam('tag_id');
        }else{
            $tag_id = $tag;
        }
        $title = $this->_getParam('title',null);

        $videoTable = Engine_Api::_()->getDbTable("videos",'sesvideo');
        $videoTableName = $videoTable->info("name");


        $select = $videoTable->select()->from($videoTableName,array('*'))->setIntegrityCheck(false);
        $select->where($videoTableName.'.is_tickvideo = 1');
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");

        $select->joinLeft("engine4_tickvideo_musics",'engine4_tickvideo_musics.music_id = '.$videoTableName.'.song_id',array('songtitle'=>'title','songphoto_id'=>'photo_id','songfile_id'=>'file_id','songduration'=>'duration'));

        $select->group($videoTableName.'.video_id')->where($videoTableName.'.video_id IN (SELECT resource_id FROM engine4_core_tagmaps WHERE resource_type = "video" AND tag_id = '.$tag_id.')');
        $select->order("view_count DESC");
        if($title){
            $select = $select->where('engine4_sesvideo_videos.title LIKE "%' . $title . '%"');
        }

        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $result = $this->getVideos($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if(!$tag)
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
        return array_merge(array('videos'=>$result),$extraParams);
    }
    function hashtagAction(){
        $results = array();
        $title = $this->_getParam('title',null);
        $tagTable = Engine_Api::_()->getDbTable("tagMaps",'core');
        $tagMapTableName = $tagTable->info("name");

        $select = $tagTable->select()->from($tagMapTableName,array('total_tags'=>new Zend_Db_Expr('COUNT(*)'),'tag_id'))->setIntegrityCheck(false);
        $select->joinInner('engine4_sesvideo_videos','engine4_sesvideo_videos.video_id = '.$tagMapTableName.'.resource_id',"video_id");
        $select->where("engine4_sesvideo_videos.owner_id NOT IN (SELECT CASE blocked_user_id
        WHEN ".Engine_Api::_()->user()->getViewer()->getIdentity()." THEN user_id ELSE blocked_user_id END as 'owner_id' FROM engine4_eticktokclone_blocks WHERE user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity()." || blocked_user_id = ".Engine_Api::_()->user()->getViewer()->getIdentity().")");

        $select->joinLeft('engine4_core_tags','engine4_core_tags.tag_id = '.$tagMapTableName.'.tag_id','text');
        $select->group($tagMapTableName.'.tag_id')->where($tagMapTableName.".resource_type = ?",'video');
        $select->where("is_tickvideo = ?",1);
        $select->where("video_id IS NOT NULL");
        if($title){
            $select = $select->where('engine4_sesvideo_videos.title LIKE "%' . $title . '%"');
        }
        
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $counter = 0;
        foreach($paginator as $item){
            $result[$counter]['tags'] = $item->toArray();
            $result[$counter]['results'] = $this->gettagvideosAction($item->tag_id);
            $counter++;
        }

        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;

        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array_merge(array('items'=>$result),$extraParams)));
    }
    function activityAction(){
        $result = array();
        $type = $this->_getParam('type',null);
        $position = 0;
        if(!($type) || $type == "recently_created") {
            //recently created
            $position = $position + 1;
            $result['recently_created'] = $this->getCriteriaVideos('recently_created',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['recently_created']));
            }
            $result['recently_created']['title'] = $this->view->translate("Recently Created");
        }
        if(!($type) || $type == "most_viewed") {
            //most liked
            $position = $position + 1;
            $result['most_viewed'] = $this->getCriteriaVideos('most_viewed',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['most_viewed']));
            }
            $result['most_viewed']['title'] = $this->view->translate("Most Viewed");
        }
        if(!($type) || $type == "most_liked") {
            $position = $position + 1;
            //most viewed
            $result['most_liked'] = $this->getCriteriaVideos('most_liked',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['most_liked']));
            }
            $result['most_liked']['title'] = $this->view->translate("Most Liked");
        }



        if(!($type) || $type == "most_commented") {
            //most viewed
            $position = $position + 1;
            $result['most_commented'] = $this->getCriteriaVideos('most_commented',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['most_commented']));
            }
            $result['most_commented']['title'] = $this->view->translate("Most Commented");
        }

        if(!($type) || $type == "featured") {
            //most viewed
            $position = $position + 1;
            $result['featured'] = $this->getCriteriaVideos('featured',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['featured']));
            }
            $result['featured']['title'] = $this->view->translate("Featured");
        }
        if(!($type) || $type == "sponsored") {
            //most viewed
            $position = $position + 1;
            $result['sponsored'] = $this->getCriteriaVideos('sponsored',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['sponsored']));
            }
            $result['sponsored']['title'] = $this->view->translate("Sponsored");
        }
        if(!($type) || $type == "hot") {
            $position = $position + 1;
            //most viewed
            $result['hot'] = $this->getCriteriaVideos('hot',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['hot']));
            }
            $result['hot']['title'] = $this->view->translate("Hot");
        }
        if(!($type) || $type == "most_favourite") {
            $position = $position + 1;
            //most viewed
            $result['most_favourite'] = $this->getCriteriaVideos('most_favourite',$position);
            if($type){
                Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result['most_favourite']));
            }
            $result['most_favourite']['title'] = $this->view->translate("Most Favourite");
        }
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result));
    }
    function musicAction(){
        $music_id = $this->_getParam('music_id','0');
        $music = Engine_Api::_()->getItem("tickvideo_music",$music_id);
        if(!$music_id || !$music){
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'Parameter Missing', 'result' => array()));
        }
        $result = $music->toArray();
        if (!empty($music->file_id)) {
            $storage_file = Engine_Api::_()->getItem('storage_file', $music->file_id);
            $result['url'] = $this->getBaseUrl(false,$storage_file->map());
        }
        if (!empty($music->photo_id)) {
            $result['images'] = Engine_Api::_()->sesapi()->getPhotoUrls($music->photo_id,'',"");
        }
        $result['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($music,'favourites','tickvideo');

        $photo = Engine_Api::_()->sesapi()->getPhotoUrls($music->photo_id,'',"");
        if($photo)
            $result["share"]["imageUrl"] = $photo;
        $result["share"]["url"] = $this->getBaseUrl(false,$music->getHref());
        $result["share"]["title"] = $music->getTitle();
        $result["share"]["description"] = strip_tags($music->getDescription());
        $result["share"]['urlParams'] = array(
            "type" => $music->getType(),
            "id" => $music->getIdentity()
        );
        if(is_null($result["share"]["title"]))
            unset($result["share"]["title"]);


        //total videos
        $videoTable = Engine_Api::_()->getDbtable('videos', 'sesvideo');


        $select = $videoTable->select()
            ->from($videoTable->info("name"),array("total_videos" => "COUNT(video_id)"))
            ->where('song_id =?', $music->getIdentity());
        
        $result['total_videos'] = (int) $videoTable->fetchRow($select)['total_videos'];
        $result['videos_results'] = $this->getCriteriaVideos('music_id',$music_id);
        
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result));        
        
    }
    function getMusicVideosAction(){
        $music_id = $this->_getParam('music_id','0');
        $music = Engine_Api::_()->getItem("tickvideo_music",$music_id);
        if(!$music_id || !$music){
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'Parameter Missing', 'result' => array()));
        }
        
        $result = $this->getCriteriaVideos('music_id',$music_id);
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $result)); 
    }
    function getMusic($paginator){
        $result = array();
        $counter = 0;
        foreach($paginator as $music){
            $result[$counter] = $music->toArray();
            if (!empty($music->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $music->file_id);
                $result[$counter]['url'] = $this->getBaseUrl(false,$storage_file->map());
            }
            if (!empty($music->photo_id)) {
                $result[$counter]['images'] = Engine_Api::_()->sesapi()->getPhotoUrls($music->photo_id,'',"");
            }
            $result[$counter]['duration'] = $music->duration;
            $result[$counter]['is_content_favourite'] = Engine_Api::_()->sesapi()->contentFavoutites($music,'favourites','tickvideo');
            $counter++;
        }
        return $result;
    }
    function getmusicsAction($id = false){
        if($id){
            $category_id = $id;
        }else{
            $category_id = $this->_getParam('category_id','0');
        }
        $type = $this->_getParam('type','discover');
        $title = $this->_getParam('title',null);
        $musicTable = Engine_Api::_()->getDbTable("musics",'tickvideo');
        $musicTableName = $musicTable->info("name");
        $select = $musicTable->select()->from($musicTableName,array('*'));
        if($category_id)
            $select->where($musicTableName.'.category_id = ?',$category_id);
        if($title){
            $select->where($musicTableName.'.title LIKE "%' . $title . '%"');
        }
        if($type == "favourite"){
            //get fav musics only
            $select->where("music_id IN (SELECT resource_id From engine4_tickvideo_favourites WHERE user_id = ".$this->view->viewer()->getIdentity()." )");
        }
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($this->_getParam('limit', 5));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $result = $this->getMusic($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        if(!$id)
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => array('musics'=>$result)),$extraParams));
        return array('result'=>$result,'pagging'=>$extraParams);

    }
    function musicsAction(){

        $title = $this->_getParam('title',null);

        $categoryTable = Engine_Api::_()->getDbTable("categories",'tickvideo');
        $categoryTableName = $categoryTable->info("name");

        $musicTable = Engine_Api::_()->getDbTable("musics",'tickvideo');
        $musicTableName = $musicTable->info("name");

        $select = $categoryTable->select()->from($categoryTableName,array('*'));
        $select->where('status =?','1');
        $select->where("item_count > ?",0);
        if($title){
            $select->having("SELECT COUNT(*) FROM  ".$musicTableName." WHERE title like '%".$title."%' AND category_id = ".$categoryTableName.".category_id  > 0");
        }
        $paginator = Zend_Paginator::factory($select);

        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        $result = array();
        $result['categories'] = array();
        $counter = 0;
        foreach($paginator as $item){
            $result['categories'][$counter]['category'] = $item->toArray();
            $result['categories'][$counter]['musics'] = $this->getmusicsAction($item->getIdentity());
            $counter++;
        }


        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    }

}
