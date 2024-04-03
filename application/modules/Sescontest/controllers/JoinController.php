<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: JoinController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_JoinController extends Core_Controller_Action_Standard {

  public function init() {
    if (!$this->_helper->requireAuth()->setAuthParams('contest', null, 'view')->isValid())
      return;
    $viewer = $this->view->viewer();
    $entryId = $this->_getParam('id', null);
    if ($entryId) {
      $entry = Engine_Api::_()->getItem('participant', $entryId);
      if ($entry)
        Engine_Api::_()->core()->setSubject($entry);
      else
        return $this->_forward('requireauth', 'error', 'core');
      $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);
      if (!$contest->is_approved && $viewer->level_id != 1 && $viewer->level_id != 2)
        return $this->_forward('requireauth', 'error', 'core');
    }
  }

  public function createAction() {
    if (!$this->_helper->requireUser->isValid())
      return;
    //Render
    $this->_helper->content->setEnabled();
    $contest_id = $this->_getParam('contest_id', 0);
    $this->view->contest = $contest = Engine_Api::_()->getItem('contest', $contest_id);

    $this->view->form = $form = new Sescontest_Form_Join_Create();
    $viewer = $this->view->viewer();
    $participation = Engine_Api::_()->getDbTable('participants', 'sescontest')->hasParticipate($viewer->getIdentity(), $contest_id);

    if (!Engine_Api::_()->authorization()->isAllowed('participant', $viewer, 'auth_participant')) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    
    $this->view->custom_url = $contest->custom_url;
    // Not post/invalid
    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($_POST)) {
      return;
    }

    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sescontest_ffmpeg_path;
    $checkFfmpeg = Engine_Api::_()->sescontest()->checkFfmpeg($ffmpeg_path);
    $values = $_POST;
    $participantTable = Engine_Api::_()->getDbtable('participants', 'sescontest');
    $db = $participantTable->getAdapter();
    $db->beginTransaction();
    try {
      // Create contest
      $participant = $participantTable->createRow();
      $participant->setFromArray($values);
      $participant->media = $contest->contest_type;
      $participant->contest_id = $contest->contest_id;
      $participant->owner_id = $viewer->getIdentity();
      $participant->creation_date = date('Y-m-d h:i:s');
      $participant->votingstarttime = $contest->votingstarttime;
      $participant->votingendtime = $contest->votingendtime;
      if ($contest->contest_type == 1)
        $participant->description = $values['contest_description'];
      $participant->save();

      $tags = preg_split('/[,]+/', $values['tags']);
      $participant->tags()->addTagMaps($viewer, $tags);

      if (!empty($_FILES['entry_photo']['name'])) {
        $photoType = 1;
        $participant->setPhoto($_FILES['entry_photo'], $photoType);
      }

      $storage = Engine_Api::_()->getItemTable('storage_file');
      $params = array(
          'parent_id' => $participant->getIdentity(),
          'parent_type' => $participant->getType(),
          'user_id' => $participant->owner_id,
      );

      if ($contest->contest_type == 2) {
        if (isset($_POST['record_photo']) && !empty($_POST['record_photo']) && $_POST['record_photo'] != 'undefined') {
          $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $_POST['record_photo']));
          $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/image' . time() . '.png';
          file_put_contents($path, $data);
          $participant->setPhoto($path);
          @unlink($path);
        } elseif (isset($_POST['sescontest_link_id']) && !empty($_POST['sescontest_link_id'])) {
          $fileId = Engine_Api::_()->getItem('album_photo', $_POST['sescontest_link_id'])->file_id;
          $fileObject = Engine_Api::_()->getItem('storage_file', $fileId);
          $participant->setPhoto($fileObject);
        } elseif (isset($_POST['sescontest_url_id']) && !empty($_POST['sescontest_url_id'])) {
          $participant->setPhoto($_POST['sescontest_url_id'], 2);
        } else {
          $participant->setPhoto($_FILES['photo']);
        }
      } elseif ($contest->contest_type == 4) {
        if (isset($_FILES['webcam']) && !empty($_FILES['webcam']['name'])) {
          $fileName = $_FILES['webcam']['name'];
          //$file = Engine_Api::_()->storage()->create($_FILES['webcam'], $params);
          //$path = APPLICATION_PATH . DIRECTORY_SEPARATOR;
          if ($ffmpeg_path && $checkFfmpeg) {
            $tmp = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' .
                    DIRECTORY_SEPARATOR . rand(0, 1000000) . '_vconverted.mp3';
            //$song = $path . $file->storage_path;
            $song = $_FILES['webcam']['tmp_name'];
            $output = null;
            $return = null;
            $output = exec("$ffmpeg_path -i $song -acodec libmp3lame $tmp", $output, $return);
            //$oldFile = Engine_Api::_()->getItem('storage_file', $file->getIdentity());
            $file = Engine_Api::_()->storage()->create($tmp, $params);
            //$file->name = $oldFile->name;
            $file->name = $fileName;
            $file->save();
            //$oldFile->delete();
            @unlink($tmp);
            $participant->file_id = $file->getIdentity();
          }
        } elseif (isset($_POST['sescontest_link_id']) && !empty($_POST['sescontest_link_id'])) {
          $sesmusic = Engine_Api::_()->getItem('sesmusic_albumsong', $_POST['sescontest_link_id']);
          $trackId = $sesmusic->track_id;
          if ($trackId) {
            $participant->track_id = $trackId;
          } else {
            $fileObject = Engine_Api::_()->getItem('storage_file', $sesmusic->file_id);
           
            $db->insert('engine4_storage_files', array(
                'type' => 'song',
                'parent_type' => 'participant',
                'parent_id' => $participant->participant_id,
                'user_id' => $viewer->getIdentity(),
                'service_id' => Engine_Api::_()->getDbTable('services','storage')->getDefaultServiceIdentity(),
                'storage_path' => $fileObject->storage_path,
                'extension' => 'mp3',
                'name' => $fileObject->name,
                'mime_major' => 'application',
                'mime_minor' => 'octet-stream',
                'size' => $fileObject->size,
                'hash' => $fileObject->hash
            ));
            $participant->file_id = $db->lastInsertId();
          }
        } else {
          $file = Engine_Api::_()->getItemTable('storage_file')->createFile($_FILES['sescontest_audio_file'], $params);
          $participant->file_id = $file->file_id;
        }
        $participant->status = 1;
      } elseif ($contest->contest_type == 3) {
        if (isset($_FILES['webcam']) && !empty($_FILES['webcam']['name'])) {
          $file = Engine_Api::_()->getItemTable('storage_file')->createFile($_FILES['webcam'], $params);
          $participant->file_id = $file->file_id;
        } elseif (isset($_POST['sescontest_link_id']) && !empty($_POST['sescontest_link_id'])) {
          $sesvideo = Engine_Api::_()->getItem('video', $_POST['sescontest_link_id']);
          if ($sesvideo->type == 3) {
            $fileId = $sesvideo->file_id;
            $fileObject = Engine_Api::_()->getItem('storage_file', $fileId);
            $storagePath = $fileObject->storage_path;
            $fileData = array('tmp_name' => $storagePath, 'size' => $fileObject->size, 'name' => $fileObject->name,'type'=>'video/mp4');
            $file = Engine_Api::_()->getItemTable('storage_file')->createFile($fileData, $params);
            $participant->file_id = $file->file_id;
          } else {
            $participant->type = $sesvideo->type;
            $participant->code = $sesvideo->code;
          }
        } else {
          $file = Engine_Api::_()->getItemTable('storage_file')->createFile($_FILES['Filedata'], $params);
          $participant->file_id = $file->file_id;
        }
        if ($ffmpeg_path && $checkFfmpeg && empty($_POST['sescontest_link_id'])) {
          Engine_Api::_()->getDbtable('jobs', 'core')->addJob('sescontest_video_encode', array(
              'participant_id' => $participant->getIdentity(),
              'type' => 'mp4',
          ));
        } else {
          $participant->status = 1;
        }
      }
      $participant->save();

      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $activityApi->addActivity($viewer, $participant, 'sescontest_create_entry');
      if ($action) {
        $activityApi->attachActivity($action, $participant);
      }
      if ($participant->owner_id != $contest->user_id) {
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($contest->getOwner(), $viewer, $contest, 'sescontest_create_entry');
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($contest->getOwner(), 'sescontest_create_entry', array('entry_title' => $participant->getTitle(), 'contest_title' => $contest->getTitle(), 'object_link' => $participant->getHref(), 'host' => $_SERVER['HTTP_HOST']));
      }
      $joinCount = $contest->join_count + 1;
      Engine_Api::_()->getDbTable('contests', 'sescontest')->update(array('join_count' => $joinCount), array('contest_id =?' => $contest->contest_id));
      $contestFollowers = Engine_Api::_()->getDbTable('followers', 'sescontest')->getFollowers($contest->contest_id);
      if (engine_count($contestFollowers) > 0) {
        foreach ($contestFollowers as $follower) {
          $user = Engine_Api::_()->getItem('user', $follower->user_id);
          if ($user->getIdentity()) {
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($user, $viewer, $contest, 'sescontest_create_entry_followed');
            Engine_Api::_()->getApi('mail', 'core')->sendSystem($user, 'sescontest_create_entry_followed', array('entry_title' => $participant->getTitle(), 'contest_title' => $contest->getTitle(), 'object_link' => $participant->getHref(), 'host' => $_SERVER['HTTP_HOST'], 'queue' => true));
          }
        }
      }
      // Commit
      $db->commit();
      echo json_encode(array('status' => true, 'href' => $participant->getHref()));
      die;
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editAction() {

    if (!$this->_helper->requireUser->isValid())
      return;
    $id = $this->_getParam('id', null);
    $entry = Engine_Api::_()->getItem('participant', $id);
    $this->view->form = $form = new Sescontest_Form_Join_Edit();
    $viewer = Engine_Api::_()->user()->getViewer();

    $form->populate($entry->toArray());
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if ($settings->getSetting('sescontest.show.entrytag', 1)) {
      $entryTags = $entry->tags()->getTagMaps();
      $tagString = '';
      foreach ($entryTags as $tagmap) {
        if ($tagString !== '') {
          $tagString .= ', ';
        }
        $tagString .= $tagmap->getTag()->getTitle();
      }
      $this->view->tagNamePrepared = $tagString;
      $form->tags->setValue($tagString);
    }
    $userInfoOptions = $settings->getSetting('sescontest.user.info', array('name', 'gender', 'age', 'email', 'phone_no'));
    if (engine_in_array('age', $userInfoOptions) && empty($entry->age)) {
      $form->age->setValue('');
    }
    if (engine_in_array('phone_no', $userInfoOptions) && empty($entry->phoneno)) {
      $form->phoneno->setValue('');
    }

    // Not post/invalid
    if (!$this->getRequest()->isPost()) {
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost())) {
      return;
    }

    $values = $form->getValues();
    $entry->setFromArray($values);
    $entry->save();

    $tags = preg_split('/[,]+/', $values['tags']);
    $entry->tags()->addTagMaps($viewer, $tags);
    $entry->save();

    // Forward
    return $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => true,
                'parentRefresh' => true,
                'format' => 'smoothbox',
                'messages' => array('Your changes have been saved.')
    ));
  }

  public function deleteAction() {

    if (!$this->_helper->requireUser->isValid())
      return;

    if (!Engine_Api::_()->authorization()->isAllowed('participant', $this->view->viewer(), 'deleteentry'))
      return $this->_forward('requireauth', 'error', 'core');

    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');
    $contest = Engine_Api::_()->getItem('contest', $this->_getParam('contest_id', 0));
    $entry = Engine_Api::_()->getItem('participant', $this->_getParam('id', null));
    $this->view->form = $form = new Sescontest_Form_Join_Delete();

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }
    $db = $entry->getTable()->getAdapter();
    $db->beginTransaction();
    try {
      $entry->delete();
      $contest->join_count--;
      $contest->save();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your entry has been deleted.');
    return $this->_forward('success', 'utility', 'core', array(
                'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('id' => $contest->custom_url), 'sescontest_profile', true),
                'messages' => array($this->view->message)
    ));
  }

  public function viewAction() {
    $viewer = $this->view->viewer();
    $subject = Engine_Api::_()->core()->getSubject();
    if (!$subject->getOwner()->isSelf($viewer)) {
      $subject->view_count++;
      $subject->save();
    }
    if ($viewer->getIdentity() != 0) {
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      $dbObject->query('INSERT INTO engine4_sescontest_recentlyviewitems (resource_id, resource_type,owner_id,creation_date ) VALUES ("' . $subject->getIdentity() . '", "' . $subject->getType() . '","' . $viewer->getIdentity() . '",NOW())	ON DUPLICATE KEY UPDATE	creation_date = NOW()');
    }
    //Render
    $this->_helper->content->setEnabled();
  }

  //get existing photo for profile photo change widget
  public function existingPhotosAction() {
    $page = $this->_getParam('page', 1);
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('albums', 'sesalbum')->getUserAlbum();
    $this->view->limit = $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
  }

  //get existing video for profile photo change widget
  public function existingVideosAction() {
    $page = $this->_getParam('page', 1);
    $value['user_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
    $value['manageVideo'] = true;
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('videos', 'sesvideo')->getVideo($value);
    $this->view->limit = $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
  }

  //get existing video for profile photo change widget
  public function existingSongsAction() {
    $this->view->canCreate = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', null, 'create');
    $page = $this->_getParam('page', 1);
    $values = array();
    $values['popularity'] = 'creation_date';
    $values['user'] = Engine_Api::_()->user()->getViewer()->getIdentity();
    $paginator = $this->view->paginator = Engine_Api::_()->getDbTable('albums', 'sesmusic')->getPlaylistPaginator($values);
    $this->view->limit = $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
  }

  //get existing video for profile photo change widget
  public function existingBlogsAction() {
    $page = $this->_getParam('page', 1);
    $value = array();
    $value['popularCol'] = '';
    $value['fixedData'] = '';
    $value['search'] = 1;
    $value['manage-widget'] = 1;
    $paginator = $this->view->paginator = Engine_Api::_()->getDbtable('blogs', 'sesblog')->getSesblogsPaginator($value);
    $this->view->limit = $limit = 12;
    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
  }

}
