<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_Api_Core extends Core_Api_Abstract
{
    protected function returnBytes($val) {
      $val = trim($val);
      $last = strtolower($val[strlen($val)-1]);
      switch($last) 
      {
        case 'g':
        $val *= 1024;
        case 'm':
        $val *= 1024;
        case 'k':
        $val *= 1024;
      }
      return $val;
    }

    public function max_file_upload_in_bytes() {
      //select maximum upload size
      $max_upload = $this->returnBytes(ini_get('upload_max_filesize'));
      //select post limit
      $max_post = $this->returnBytes(ini_get('post_max_size'));
      //select memory limit
      $memory_limit = $this->returnBytes(ini_get('memory_limit'));
      // return the smallest of them, this defines the real limit
      return min($max_upload, $max_post, $memory_limit);
    }
  
    public function contentLike($subject)
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        //return if non logged in user or content empty
        if (empty($subject) || empty($viewer))
            return;
        if ($viewer->getIdentity())
            $like = Engine_Api::_()->getDbTable("likes", "core")->isLike($subject, $viewer);
        return !empty($like) ? $like : false;
    }
  
    protected function stringsToURLStrings($stringBody){
      $pattern = '@(http(s)?://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
      return nl2br(preg_replace($pattern, '<a href="http$2://$3" target="_blank">$0</a>', $stringBody));
    }
  
    function userData($highlight,$userarchivedstories,$user_id,$view,$page = 1){
        $activityEnable = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity');
        if (empty($userarchivedstories) && empty($highlight)) {
            $user = Engine_Api::_()->getItem('user', $user_id);

            $staticUserImage = 'application/modules/User/externals/images/nophoto_user_thumb_profile.png';
            $userImage = $user->getPhotoUrl() ? $user->getPhotoUrl() : $staticUserImage;

            //$getAllUserHaveStories = Engine_Api::_()->getDbTable('stories', 'sesstories')->getAllUserHaveStories(array('user_id' => $user_id));

            $getAllUserHaveStories = Engine_Api::_()->getDbTable('userinfos', 'sesstories')->getAllUserHaveStories(array('user_id' => $user_id));
            $friendArra = array();
            if (engine_count($getAllUserHaveStories) > 0) {
                foreach ($getAllUserHaveStories as $getAllUserHaveStorie) {
                    $friendArra[] = $getAllUserHaveStorie->owner_id;
                }
            }

            //$friendArra = $user->membership()->getMembershipsOfIds();
        } else {
            $friendArra = array($user_id);
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $settings = Engine_Api::_()->getApi('settings', 'core');

        $finalArray = $images = $menuoptions = array();
        $counterLoop = $counter = $menucounter = 0;

        if (empty($userarchivedstories) && empty($highlight)) {
            $viewerresults = Engine_Api::_()->getDbTable('stories', 'sesstories')->getAllStories($user_id);
            if (engine_count($viewerresults) > 0) {
                foreach ($viewerresults as $item) {
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
                        $elivehost = Engine_Api::_()->getDbtable('elivehosts', 'elivestreaming')->getHostId(array('story_id' => $item->story_id));
                        if ($elivehost) {
                            continue;
                        }
                    }
                    // for live streaming.
                    $liveStreamImage = $staticUserImage;
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming'))
                        if ($settings->getSetting('elivestreaming.showliveimage'))
                            $liveStreamImage = $settings->getSetting('elivestreaming.storieslivedefaultimage');
                    
                    if(isset($item->file_id) && !empty($item->file_id) && empty($item->background_id)) {
                      $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($item->file_id, '');
                    } else if(!empty($item->background_id)) {
                      $backgroundItem = Engine_Api::_()->getItem('sesstories_background', $item->background_id);
                      $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($backgroundItem->file_id, '');
                    }
                      
                    $images['story_content'][$counter]['story_id'] = $item->story_id;
                    $images['story_content'][$counter]['media_url'] =  $storageObject ? $storageObject->map() : $liveStreamImage;
                    $images['story_content'][$counter]['comment'] = $this->stringsToURLStrings($item->title);
                    $images['story_content'][$counter]['story_type'] = $item->story_type;
                    if (!empty($item->type)) {
                        $images['story_content'][$counter]['is_video'] = true;
                        $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($item->photo_id, '');
                        $images['story_content'][$counter]['photo'] = $storageObject ? $storageObject->map() : '';
                    } else {
                        $images['story_content'][$counter]['is_video'] = false;
                    }
                    $images['story_content'][$counter]['highlight'] = $item->highlight;
                    $images['story_content'][$counter]['view_count'] = $item->view_count;
                    $images['story_content'][$counter]['like_count'] = $item->like_count;
                    $images['story_content'][$counter]['comment_count'] = $item->comment_count;
                    $images['story_content'][$counter]['creation_date'] = $item->creation_date;

                    $menucounter = 0;
                    $menuoptions[$menucounter]['name'] = "delete";
                    $menuoptions[$menucounter]['label'] = $view->translate("SESDelete");
                    $menucounter++;
                    $images['story_content'][$counter]['options'] = $menuoptions;


                    $viewer_id = $view->viewer()->getIdentity();
                    if($activityEnable) {
                        if ($viewer_id) {
                            $itemTable = Engine_Api::_()->getItemTable($item->getType(), $item->getIdentity());
                            $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
                            $tableMainLike = $tableLike->info('name');
                            $select = $tableLike->select()
                                ->from($tableMainLike)
                                ->where('resource_type = ?', $item->getType())
                                ->where('poster_id = ?', $viewer_id)
                                ->where('poster_type = ?', 'user')
                                ->where('resource_id = ?', $item->getIdentity());
                            $resultData = $tableLike->fetchRow($select);
                            if ($resultData) {
                                $item_activity_like = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->rowExists($resultData->like_id);
                                $photo['reaction_type'] = $item_activity_like->type;
                            }
                        }

                        $table = Engine_Api::_()->getDbTable('likes', 'core');
                        $coreliketable = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity');
                        $coreliketableName = $coreliketable->info('name');

                        $recTable = Engine_Api::_()->getDbTable('reactions', 'sesadvancedcomment')->info('name');
                        $select = $table->select()->from($table->info('name'), array('total' => new Zend_Db_Expr('COUNT(like_id)')))->where('resource_id =?', $item->getIdentity())->group('type')->setIntegrityCheck(false);
                        $select->joinLeft($coreliketableName, $table->info('name') . '.like_id =' . $coreliketableName . '.core_like_id', array('type'));
                        $select->where('resource_type =?', $item->getType());
                        $select->joinLeft($recTable, $recTable . '.reaction_id =' . $coreliketableName . '.type', array('file_id'))->where('enabled =?', 1)->order('total DESC');
                        $resultData = $table->fetchAll($select);

                        $is_like = $this->contentLike($item);
                        $reactionData = array();
                        $reactionCounter = 0;
                        if (engine_count($resultData)) {
                            foreach ($resultData as $type) {
                                $reactionData[$reactionCounter]['title'] = $view->translate('%s (%s)', $type['total'], Engine_Api::_()->sesadvancedcomment()->likeWord($type['type']));
                                $reactionData[$reactionCounter]['imageUrl'] = Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);
                                $reactionCounter++;
                            }
                            $images['story_content'][$counter]['reactionData'] = $reactionData;
                        }
                        if ($is_like) {
                            $images['story_content'][$counter]['is_like'] = true;
                            $like = true;
                            $type = $photo['reaction_type'];
                            $imageLike = Engine_Api::_()->sesadvancedcomment()->likeImage($type);
                            if ($type)
                                $text = Engine_Api::_()->sesadvancedcomment()->likeWord($type);
                            else
                                $text = 'Like';
                        } else {
                            $images['story_content'][$counter]['is_like'] = false;
                            $like = false;
                            $type = '';
                            $imageLike = '';
                            $text = 'Like';
                        }


                        if (empty($like)) {
                            $images['story_content'][$counter]["like"]["name"] = "like";
                        } else {
                            $images['story_content'][$counter]["like"]["name"] = "unlike";
                        }
                        $images['story_content'][$counter]["like"]["image"] = $imageLike;
                        $images['story_content'][$counter]['reactionUserData'] = $view->FluentListUsers($item->likes()->getAllLikesUsers(), '', $item->likes()->getLike($view->viewer()), $view->viewer());
                    }

                    $counter++;
                }
                if (engine_count($viewerresults) > 0) {
                    $result['my_story'] = $images;
                    $result['my_story']['user_id'] = $user->getIdentity();
                    $result['my_story']['username'] = $user->getTitle();
                    $result['my_story']['user_image'] = $userImage;
                }
            } else {
                $result['my_story'] = array();
                $result['my_story']['user_id'] = $user->getIdentity();
                $result['my_story']['username'] = $user->getTitle();
                $result['my_story']['user_image'] = $userImage;
            }
        }

        if (engine_count($friendArra) > 0) {
            foreach ($friendArra as $key => $friend_id) {

                if (empty($userarchivedstories) && empty($highlight)) {
                    if ($friend_id == $viewer_id) continue;
                }

                $getAllMutesMembers = Engine_Api::_()->getDbTable('mutes', 'sesstories')->getAllMutesMembers(array('user_id' => $viewer_id));
                if (engine_count($getAllMutesMembers) > 0) {
                    if (engine_in_array($friend_id, $getAllMutesMembers)) continue;
                }

                if (empty($userarchivedstories)) {
                    $results = Engine_Api::_()->getDbTable('stories', 'sesstories')->getAllStories($friend_id, $userarchivedstories, $highlight);
                } else {
                    $select = Engine_Api::_()->getDbTable('stories', 'sesstories')->getAllStories($friend_id, $userarchivedstories, $highlight);

                    $results = $paginator = Zend_Paginator::factory($select);
                    $paginator->setItemCountPerPage(9);
                    $paginator->setCurrentPageNumber($page);
                }

                $user = Engine_Api::_()->getItem('user', $friend_id);
                $userImage = $user->getPhotoUrl() ? $user->getPhotoUrl() : $staticUserImage;

                //$canComment =   $album->authorization()->isAllowed($viewer, 'comment') ? true : false; $user->authorization()->isAllowed($viewer, 'story_comment') ? true : false;

                $images = array();
                $counter = 0;
                $existsItem = false;
                foreach ($results as $item) {

                    // for live streaming.
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
                        $elivehost = Engine_Api::_()->getDbtable('elivehosts', 'elivestreaming')->getHostId(array('story_id' => $item->story_id));
                        if($elivehost && $elivehost['status'] == 'started') {
                            if ($elivehost && $elivehost['status'] == 'started' && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('eweblivestreaming')) {
                                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming'))
                                    if ($settings->getSetting('elivestreaming.showliveimage'))
                                        $images['story_content'][$counter]['media_url'] = $settings->getSetting('elivestreaming.storieslivedefaultimage');
                                    else
                                        $images['story_content'][$counter]['media_url'] = $userImage;
                            }
                        }else if($elivehost){
                            continue;
                        }
                    }
                    $existsItem = true;
                    
                    if(isset($item->file_id) && !empty($item->file_id) && empty($item->background_id)) {
                      $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($item->file_id, '');
                    } else if(!empty($item->background_id)) {
                      $backgroundItem = Engine_Api::_()->getItem('sesstories_background', $item->background_id);
                      $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($backgroundItem->file_id, '');
                    }
                    
                    $images['story_content'][$counter]['story_id'] = $item->story_id;
                    $images['story_content'][$counter]['media_url'] = $storageObject ? $storageObject->map() : $staticUserImage;
                    $images['story_content'][$counter]['comment'] = $this->stringsToURLStrings($item->title);
                    $images['story_content'][$counter]['story_type'] = $item->story_type;
                    if (!empty($item->type)) {
                        $images['story_content'][$counter]['is_video'] = true;
                        $storageObject = Engine_Api::_()->getItemTable('storage_file')->getFile($item->photo_id, '');
                        $images['story_content'][$counter]['photo'] = $storageObject ? $storageObject->map() : '';
                    } else {
                        $images['story_content'][$counter]['is_video'] = false;
                    }
                    $images['story_content'][$counter]['highlight'] = $item->highlight;
                    $images['story_content'][$counter]['view_count'] = $item->view_count;
                    $images['story_content'][$counter]['like_count'] = $item->like_count;
                    $images['story_content'][$counter]['comment_count'] = $item->comment_count;
                    $images['story_content'][$counter]['creation_date'] = $item->creation_date;

                    $images['story_content'][$counter]['can_comment'] = $item->authorization()->isAllowed($viewer, 'comment') ? true : false;


                    $menucounter = 0;
                    if ($viewer_id != $item->owner_id) {
                        $menuoptions[$menucounter]['name'] = "mute";
                        $menuoptions[$menucounter]['label'] = $view->translate("SESMute");
                        $menucounter++;

//                         $menuoptions[$menucounter]['name'] = "report";
//                         $menuoptions[$menucounter]['label'] = $view->translate("SESReport");
//                         $menucounter++;

                        $images['story_content'][$counter]['options'] = $menuoptions;
                    }

                    //Reaction work
                    $viewer_id = $view->viewer()->getIdentity();
                    if($activityEnable) {
                        if ($viewer_id) {
                            $itemTable = Engine_Api::_()->getItemTable($item->getType(), $item->getIdentity());
                            $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
                            $tableMainLike = $tableLike->info('name');
                            $select = $tableLike->select()
                                ->from($tableMainLike)
                                ->where('resource_type = ?', $item->getType())
                                ->where('poster_id = ?', $viewer_id)
                                ->where('poster_type = ?', 'user')
                                ->where('resource_id = ?', $item->getIdentity());
                            $resultData = $tableLike->fetchRow($select);
                            if ($resultData) {
                                $item_activity_like = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->rowExists($resultData->like_id);
                                $reaction_type = $item_activity_like->type;
                            }
                        }

                        $table = Engine_Api::_()->getDbTable('likes', 'core');
                        $coreliketable = Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity');
                        $coreliketableName = $coreliketable->info('name');

                        $recTable = Engine_Api::_()->getDbTable('reactions', 'sesadvancedcomment')->info('name');
                        $select = $table->select()->from($table->info('name'), array('total' => new Zend_Db_Expr('COUNT(like_id)')))->where('resource_id =?', $item->getIdentity())->group('type')->setIntegrityCheck(false);
                        $select->joinLeft($coreliketableName, $table->info('name') . '.like_id =' . $coreliketableName . '.core_like_id', array('type'));
                        $select->where('resource_type =?', $item->getType());
                        $select->joinLeft($recTable, $recTable . '.reaction_id =' . $coreliketableName . '.type', array('file_id'))->where('enabled =?', 1)->order('total DESC');
                        $resultData = $table->fetchAll($select);

                        $is_like = $this->contentLike($item);
                        $reactionData = array();
                        $reactionCounter = 0;
                        if (engine_count($resultData)) {
                            foreach ($resultData as $type) {
                                $reactionData[$reactionCounter]['title'] = $view->translate('%s (%s)', $type['total'], Engine_Api::_()->sesadvancedcomment()->likeWord($type['type']));
                                $reactionData[$reactionCounter]['imageUrl'] = Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);
                                $reactionCounter++;
                            }
                            $images['story_content'][$counter]['reactionData'] = $reactionData;
                        }
                        if ($is_like) {
                            $images['story_content'][$counter]['is_like'] = true;
                            $like = true;
                            $type = $reaction_type; //$is_like['reaction_type'];
                            $imageLike = Engine_Api::_()->sesadvancedcomment()->likeImage($type);
                            if ($type)
                                $text = Engine_Api::_()->sesadvancedcomment()->likeWord($type);
                            else
                                $text = 'Like';

                        } else {
                            $images['story_content'][$counter]['is_like'] = false;
                            $like = false;
                            $type = '';
                            $imageLike = '';
                            $text = 'Like';
                        }
                        if (empty($like)) {
                            $images['story_content'][$counter]["like"]["name"] = "like";
                        } else {
                            $images['story_content'][$counter]["like"]["name"] = "unlike";
                        }

                        $images['story_content'][$counter]["like"]["image"] = $imageLike;
                        $images['story_content'][$counter]['reactionUserData'] = $view->FluentListUsers($item->likes()->getAllLikesUsers(), '', $item->likes()->getLike($view->viewer()), $view->viewer());
                        //Reaction work end
                        // for live streaming.
                        // for live streaming.
                    }

                    $counter++;
                }
                if (engine_count($results) > 0 && $existsItem) {
                    $result['stories'][$counterLoop] = $images;
                    $result['stories'][$counterLoop]['user_id'] = $user->getIdentity();
                    $result['stories'][$counterLoop]['username'] = $user->getTitle();
                    $result['stories'][$counterLoop]['user_image'] = $userImage;
                    // for live streaming.
                    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
                        foreach ($images['story_content'] as $key => $item) {
                            if ($key == 0) {
                                $elivehost = Engine_Api::_()->getDbtable('elivehosts', 'elivestreaming')->getHostId(array('story_id' => $item['story_id']));
                                if ($elivehost && $elivehost['status'] == 'started') {
                                    $result['stories'][$counterLoop]['is_live'] = true;
                                    $result['stories'][$counterLoop]['activity_id'] = $elivehost['action_id'];
                                    $result['stories'][$counterLoop]['elivehost_id'] = $elivehost->getIdentity();
                                } else {
                                    $result['stories'][$counterLoop]['is_live'] = false;
                                }
                            }
                        }
                    }
                    $counterLoop++;
                    if (!empty($userarchivedstories)) {
                        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
                        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
                        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
                        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
                    }
                }
            }
        }

        if (!empty($userarchivedstories)) {
            return array('result'=> $result,'pagginator'=>$extraParams);
        } else {
            return $result;
        }
    }
    public function isExist($user_id, $view_privacy) {

        $userinfoTable = Engine_Api::_()->getDbTable('userinfos', 'sesstories');
        $userinfo_id = $userinfoTable->select()
                    ->from($userinfoTable->info('name'), 'userinfo_id')
                    ->where($userinfoTable->info('name') . '.owner_id = ?', $user_id)
                    ->query()
                    ->fetchColumn();
        if(empty($userinfo_id)) {
            $userinfo = $userinfoTable->createRow();
            $userinfo->owner_id = $user_id;
            $userinfo->view_privacy = $view_privacy;
            $userinfo->save();
            //
            //return Engine_Api::_()->getItem('sesstories_userinfo', $userinfo->getIdentity());
        }
        else {

          $userinfo = Engine_Api::_()->getItem('sesstories_userinfo', $userinfo_id);
          $userinfo->view_privacy = $view_privacy;
          $userinfo->save();
        }
    }

 // handle video upload
  public function createVideo($params, $file, $item) {


    if ($file instanceof Storage_Model_File) {
      $params['file_id'] = $file->getIdentity();
    } else {
      // create video item
// 			if(!$video_date){
//       	$video = Engine_Api::_()->getDbtable('videos', 'sesvideo')->createRow();
//       	$file_ext = pathinfo($file['name']);
// 				$file_ext = $file_ext['extension'];
// 			}else{
//         $video = $video_date;
//       }

			$video = $item; //$video_date;

      // Store video in temporary storage object for ffmpeg to handle
      $storage = Engine_Api::_()->getItemTable('storage_file');
			$params = array(
          'parent_id' => $video->getIdentity(),
          'parent_type' => $video->getType(),
          //'user_id' => $video->user_id,
      );
			//if(!$video_date){
            $video->code = ltrim(strrchr($file['name'], '.'), '.');;
            $storageObject = $storage->createFile($file, $params);
            $video->file_id = $file_id = $storageObject->file_id;
			//}
      // Remove temporary file
      //@unlink($file['tmp_name']);
      $video->save();

// 			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.direct.video', 0)){
// 				if($file_ext == 'mp4' || $file_ext == 'flv'){
// 					$video->status = 1;
// 					 $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, null);
// 					$file = (_ENGINE_SSL ? 'https://' : 'http://')
// 						. $_SERVER['HTTP_HOST'].$file->map();
// 					$video->duration = $duration = $this->getVideoDuration($video,$file);
// 					if($duration){
// 						$thumb_splice = $duration / 2;
// 						$this->getVideoThumbnail($video,$thumb_splice,$file);
// 					}
// 					$video->save();
// 					return $video;
// 				}
// 			}
			$video->status = 2;
			$video->save();
      // Add to jobs
        Engine_Api::_()->getDbtable('jobs', 'core')->addJob('sesstories_encode', array(
            'story_id' => $video->getIdentity(),
            'type' => 'mp4',
        ));
    }
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
}
