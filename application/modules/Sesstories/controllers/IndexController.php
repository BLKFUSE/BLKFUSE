<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesstories_IndexController extends Core_Controller_Action_Standard
{
    public function viewStoryAction()
    {
        
        $story_id = $this->_getParam('story_id', null);
        $user_id = $this->_getParam('user_id', null);

        $story = Engine_Api::_()->getItem('sesstories_story', $story_id);

        $isAlreadyView = Engine_Api::_()->getDbTable('recentlyviewitems', 'sesstories')->isAlreadyView(array('owner_id' => $user_id, 'resource_id' => $story_id));

        if (empty($isAlreadyView)) {
            Engine_Api::_()->getDbtable('stories', 'sesstories')->update(array('view_count' => new Zend_Db_Expr('view_count + 1')), array('story_id = ?' => $story_id));
        }

        if ($user_id != 0 && isset($story->story_id)) {
            $dbObject = Engine_Db_Table::getDefaultAdapter();

            $dbObject->query('INSERT INTO engine4_sesstories_recentlyviewitems (resource_id,owner_id,creation_date ) VALUES ("' . $story->story_id . '","' . $user_id . '",NOW())	ON DUPLICATE KEY UPDATE	creation_date = NOW()');
        }
        echo 1;die;
    }
    public function viewAction(){
        $id = $this->_getParam('story_id','');
        if($id){
            $_SESSION['story_id'] = $id;
        }
        header('Location:'.$this->view->baseUrl());exit();
    }
    public function getallmutedmembersAction()
    {

        $user_id = $this->view->viewer()->getIdentity();

        $table = Engine_Api::_()->getDbTable('mutes', 'sesstories');
        $tableName = $table->info('name');

        $select = $table->select()
            ->from($tableName)
            ->where('user_id =?', $user_id);

        $results = $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(9);
        $paginator->setCurrentPageNumber($this->_getParam('page'));

        $mutedusers = $result = array();
        $counterLoop =  0;

        foreach ($results as $viewers) {
            $user = Engine_Api::_()->getItem('user', $viewers->resource_id);
            $userImage = $user->getPhotoUrl() ? $user->getPhotoUrl() : 'application/modules/User/externals/images/nophoto_user_thumb_profile.png';

            $mutedusers[$counterLoop]['user_image'] = $userImage;
            $mutedusers[$counterLoop]['user_title'] = $user->getTitle();
            $mutedusers[$counterLoop]['user_id'] = $user->getIdentity();

            $menuoptions['name'] = "unmute";
            $menuoptions['label'] = $this->view->translate("SESUnmute");
            $menuoptions['mute_id'] = $viewers->getIdentity();
            $mutedusers[$counterLoop]['options'] = $menuoptions;

            $counterLoop++;
        }
        $result = $mutedusers;

        if(is_countable($results) && engine_count($results) > 0 ) {
            $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
            $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
            $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
            $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
        }
        return array('result'=> $result,'pagginator'=>$extraParams);
    }
    function archivedDataAction(){
        $user_id = $this->view->viewer()->getIdentity();
        $this->view->is_ajax = true;
        $this->view->archivedPage = $this->_getParam('page') + 1;
        $this->view->archived = Engine_Api::_()->sesstories()->userData(0,1,$user_id,$this->view,$this->_getParam('page'));
        $this->renderScript('index/archive.tpl');
    }
    function mutedDataAction(){
        $user_id = $this->view->viewer()->getIdentity();
        $this->view->is_ajax = true;
        $this->view->mutedPage = $this->_getParam('page') + 1;
        $this->view->muted = $this->getallmutedmembersAction();
        $this->renderScript('index/archive.tpl');
    }
    public function archiveAction()
    {
        $user_id = $this->view->viewer()->getIdentity();
        //archived stories
        $this->view->archivedPage = 2;
        $this->view->archived = Engine_Api::_()->sesstories()->userData(0,1,$user_id,$this->view,1);
       //
        //muted stories
        $this->view->muted = $this->getallmutedmembersAction();
        $this->view->mutedPage = 2;
        //setting form

        $user = $this->view->viewer();

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $auth = Engine_Api::_()->authorization()->context;

        $this->view->form = $form = new Sesstories_Form_Settings_Settings(array(
            'item' => $user,
        ));
        $form->setAttrib('id','sesstories_form_create');
        // Hides options from the form if there are less then one option.
        if (engine_count($form->story_privacy->options) <= 1) {
            $form->removeElement('story_privacy');
        }
        if (engine_count($form->story_comment->options) <= 1) {
            $form->removeElement('story_comment');
        }

        // Populate form
        $form->populate($user->toArray());

    }
    function saveFormAction(){
        $user_id = $this->view->viewer()->getIdentity();
        $user =$this->view->viewer();

        $settings = Engine_Api::_()->getApi('settings', 'core');
        $auth = Engine_Api::_()->authorization()->context;
        $this->view->form = $form = new Sesstories_Form_Settings_Settings(array(
            'item' => $user,
        ));

        // Hides options from the form if there are less then one option.
        if (engine_count($form->story_privacy->options) <= 1) {
            $form->removeElement('story_privacy');
        }
        if (engine_count($form->story_comment->options) <= 1) {
            $form->removeElement('story_comment');
        }

        // Populate form
        if (!$form->isValid($_POST)) {}
        $form->save();
        
        //Privacy Work
        Engine_Api::_()->sesstories()->isExist($user_id, $_POST['story_privacy']);

        $message = $this->view->translate('Your changes have been saved.');
        echo $message;die;
    }
    function messageAction(){
        $message = $this->_getParam('data');
        $ownerId[] = $this->_getParam('owner_id', $this->_getParam('st_owner_id', 0));
        // Not post/invalid
        if (!$this->getRequest()->isPost()) {
            echo json_encode(array('status'=>0));
        }


        // Process
        $db = Engine_Api::_()->getDbTable('messages', 'messages')->getAdapter();
        $db->beginTransaction();
        try {

            $viewer = Engine_Api::_()->user()->getViewer();
            $recipientsUsers = Engine_Api::_()->getItemMulti('user', $ownerId);
            $attachment = null;

            if ($this->_getParam('owner_id', $this->_getParam('st_owner_id', 0)) != $viewer->getIdentity()) {

                // Create conversation
                $conversation = Engine_Api::_()->getItemTable('messages_conversation')->send(
                    $viewer, $recipientsUsers[0], "You have received message on your story.",$message, $attachment
                );
            }

            // Send notifications
            foreach ($recipientsUsers as $user) {
                if ($user->getIdentity() == $viewer->getIdentity()) {
                    continue;
                }

                Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification(
                    $user, $viewer, $conversation, 'message_new'
                );
            }

            // Increment messages counter
            Engine_Api::_()->getDbTable('statistics', 'core')->increment('messages.creations');

            // Commit
            $db->commit();
            echo json_encode(array('status' => 'true'));
            die;
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(array('status'=>0));die;
        }
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
    function deleteAction(){
        $story = Engine_Api::_()->getItem('sesstories_story', $this->getRequest()->getParam('id'));
        if (!$story) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Story entry doesn't exist to delete.");
            echo json_encode(array('status'=>0,'message'=>$this->view->error));die;
        }

        // Make form
        $this->view->form = $form = new Sesstories_Form_Delete();
        $form->setTitle('Delete Story?');
        $form->setDescription('Are you sure that you want to delete this story ? It will not be recoverable after being deleted.');
        $form->submit->setLabel('Delete');
        if (!$this->getRequest()->isPost()) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            return;
        }
        $db = $story->getTable()->getAdapter();
        $db->beginTransaction();

        try {
            $story->delete();
            $db->commit();
            $this->view->status = true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
        $this->view->status = true;

    }
    public function muteAction()
    {
        $story_id = $this->_getParam('story_id', null);
        $story = Engine_Api::_()->getItem('sesstories_story',$story_id);
        $user_id = $story->owner_id;
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $table = Engine_Api::_()->getDbtable('mutes', 'sesstories');
        $resource_id = $user_id;
        $isStoryAlreadyMuted = Engine_Api::_()->getDbTable('mutes', 'sesstories')->getMuteStory($resource_id);
        $mute_id = null;
        if (!engine_count($isStoryAlreadyMuted)) {
            $values = array('user_id' => $viewer_id, 'resource_id' => $user_id, 'mute' => '1');
            $item = $table->createRow();
            $item->setFromArray($values);
            $item->save();
        }
        echo true;die;
    }

    public function unmuteAction()
    {
        $mute_id = $this->_getParam('mute_id', null);
        $mute = Engine_Api::_()->getItem('sesstories_mute', $mute_id);
        if ($mute)
            $mute->delete();

        echo 1;die;
    }


    public function likeAction()
    {
        // Make sure user exists
        if( !$this->_helper->requireUser()->isValid() ) return;
        // Collect params



        $story_id = $this->_getParam('story_id');
        $viewer = Engine_Api::_()->user()->getViewer();


        // Start transaction
        // $db = Engine_Api::_()->getDbtable('likes', 'sesadvancedactivity')->getAdapter();
        // $db->beginTransaction();
        try {

                $action = Engine_Api::_()->getItem('sesstories_story',$story_id);


                    $isLike = $action->likes()->getLike($viewer);
                    if( $isLike ) {
                        $action->likes()->removeLike($viewer);
                        Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->removeExists($isLike->like_id);
                    }
                    $like = $action->likes()->addLike($viewer);
                    Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->isRowExists($like->like_id, $this->_getParam('type',1), $action);
                   // Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->removeExists($like->like_id);


                $reactedType = $this->_getParam('type',1);
                // Add notification for owner of activity (if user and not viewer)

                    $actionOwner = $action->getOwner();
                    $senderObject =  $viewer;
                    if($reactedType == 1) {

                        //Remove Previous Notification
                        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'liked', "subject_id =?" => $senderObject->getIdentity(), "object_type =? " => $action->getType(), "object_id = ?" => $action->getIdentity()));

                        Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->addNotification($actionOwner,$senderObject, $action, 'liked', array('label' => 'post'));
                    } else {
                        if($reactedType == 2)
                            $notiType = 'sesadvancedactivity_reacted_love';
                        elseif($reactedType == 3)
                            $notiType = 'sesadvancedactivity_reacted_haha';
                        elseif($reactedType == 4)
                            $notiType = 'sesadvancedactivity_reacted_wow';
                        elseif($reactedType == 5)
                            $notiType = 'sesadvancedactivity_reacted_angry';
                        elseif($reactedType == 6)
                            $notiType = 'sesadvancedactivity_reacted_sad';

                        //Remove previous notification
                        $reaction_array = array('liked', 'sesadvancedactivity_reacted_love', 'sesadvancedactivity_reacted_haha', 'sesadvancedactivity_reacted_wow', 'sesadvancedactivity_reacted_angry', 'sesadvancedactivity_reacted_sad');
                        foreach($reaction_array as $reactionr) {
                            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $reactionr, "subject_id =?" => $senderObject->getIdentity(), "object_type =? " => $action->getType(), "object_id = ?" => $action->getIdentity()));
                        }

                        //Send Reaction Notification
                        if($notiType)
                        Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->addNotification($actionOwner,$senderObject, $action, $notiType, array('label' => 'post'));
                    }


            // Stats
            Engine_Api::_()->getDbtable('statistics', 'core')->increment('core.likes');
            //$db->commit();
        }

        catch( Exception $e )
        {
            throw $e;
            //  $db->rollBack();
            $this->view->error = 'Error';
            //throw $e;
        }

        // Success
        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('You now like this action.');

        //Reaction work
        $viewer_id = $this->view->viewer()->getIdentity();
        if ($viewer_id) {
            $itemTable = Engine_Api::_()->getItemTable($action->getType(), $action->getIdentity());
            $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
            $tableMainLike = $tableLike->info('name');
            $select = $tableLike->select()
                ->from($tableMainLike)
                ->where('resource_type = ?', $action->getType())
                ->where('poster_id = ?', $viewer_id)
                ->where('poster_type = ?', 'user')
                ->where('resource_id = ?', $action->getIdentity());
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
        $select = $table->select()->from($table->info('name'), array('total' => new Zend_Db_Expr('COUNT(like_id)')))->where('resource_id =?', $action->getIdentity())->group('type')->setIntegrityCheck(false);
        $select->joinLeft($coreliketableName, $table->info('name') . '.like_id =' . $coreliketableName . '.core_like_id', array('type'));
        $select->where('resource_type =?', $action->getType());
        $select->joinLeft($recTable, $recTable . '.reaction_id =' . $coreliketableName . '.type', array('file_id'))->where('enabled =?', 1)->order('total DESC');
        $resultData =  $table->fetchAll($select);

        $is_like = $this->contentLike($action);
        $reactionData = array();
        $reactionCounter = 0;
        if (engine_count($resultData)) {
            foreach ($resultData as $type) {
                $reactionData[$reactionCounter]['title'] = $this->view->translate('%s (%s)', $type['total'], Engine_Api::_()->sesadvancedcomment()->likeWord($type['type']));
                $reactionData[$reactionCounter]['imageUrl'] = Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);
                $reactionCounter++;
            }
            $images['reactionData'] = $reactionData;
        }
        if ($is_like) {
            $images['is_like'] = true;
            $like = true;
            $type = $reaction_type; //$is_like['reaction_type'];
            $imageLike = Engine_Api::_()->sesadvancedcomment()->likeImage($type);
            if ($type)
                $text = Engine_Api::_()->sesadvancedcomment()->likeWord($type);
            else
                $text = 'Like';

        } else {
            $images['is_like'] = false;
            $like = false;
            $type = '';
            $imageLike = '';
            $text = 'Like';
        }
        if (empty($like)) {
            $images["like"]["name"] = "like";
        } else {
            $images["like"]["name"] = "unlike";
        }

        $images["like"]["image"] = $imageLike;
        $images['reactionUserData'] = $this->view->FluentListUsers($action->likes()->getAllLikesUsers(), '', $action->likes()->getLike($this->view->viewer()), $this->view->viewer());
        //Reaction work end

        $this->view->reactionData = $images;
    }

    /**
     * Handles HTTP request to remove a like from an activity feed item
     *
     * Uses the default route and can be accessed from
     *  - /activity/index/unlike
     *
     * @throws Engine_Exception If a user lacks authorization
     * @return void
     */
    public function unlikeAction()
    {
        // Make sure user exists
        if( !$this->_helper->requireUser()->isValid() ) return;


        // Collect params
        $story_id = $this->_getParam('story_id');
        $viewer = Engine_Api::_()->user()->getViewer();

        // Start transaction
        $db = Engine_Api::_()->getDbtable('likes', 'sesadvancedactivity')->getAdapter();
        $db->beginTransaction();

        try {

                $action = Engine_Api::_()->getItem('sesstories_story',$story_id);
            // Action


                //Remove reaction notification
                $reaction_array = array('liked', 'sesadvancedactivity_reacted_love', 'sesadvancedactivity_reacted_haha', 'sesadvancedactivity_reacted_wow', 'sesadvancedactivity_reacted_angry', 'sesadvancedactivity_reacted_sad');
                foreach($reaction_array as $reactionr) {
                    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $reactionr, "subject_id =?" => !empty($guidUser) ? $guidUser->getIdentity() : $viewer->getIdentity(), "object_type =? " => $action->getType(), "object_id = ?" => $action->getIdentity()));
                }

                    $isLike = $action->likes()->getLike($viewer);
                    Engine_Api::_()->getDbTable('corelikes', 'sesadvancedactivity')->removeExists($isLike->like_id);
                    $action->likes()->removeLike($viewer);



            $db->commit();
        }

        catch( Exception $e )
        {
            $db->rollBack();
            $this->view->error = 'error';
        }

        // Success
        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('You no longer like this action.');

        //Reaction work
        $viewer_id = $this->view->viewer()->getIdentity();
        if ($viewer_id) {
            $itemTable = Engine_Api::_()->getItemTable($action->getType(), $action->getIdentity());
            $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
            $tableMainLike = $tableLike->info('name');
            $select = $tableLike->select()
                ->from($tableMainLike)
                ->where('resource_type = ?', $action->getType())
                ->where('poster_id = ?', $viewer_id)
                ->where('poster_type = ?', 'user')
                ->where('resource_id = ?', $action->getIdentity());
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
        $select = $table->select()->from($table->info('name'), array('total' => new Zend_Db_Expr('COUNT(like_id)')))->where('resource_id =?', $action->getIdentity())->group('type')->setIntegrityCheck(false);
        $select->joinLeft($coreliketableName, $table->info('name') . '.like_id =' . $coreliketableName . '.core_like_id', array('type'));
        $select->where('resource_type =?', $action->getType());
        $select->joinLeft($recTable, $recTable . '.reaction_id =' . $coreliketableName . '.type', array('file_id'))->where('enabled =?', 1)->order('total DESC');
        $resultData =  $table->fetchAll($select);

        $is_like = $this->contentLike($action);
        $reactionData = array();
        $reactionCounter = 0;
        if (engine_count($resultData)) {
            foreach ($resultData as $type) {
                $reactionData[$reactionCounter]['title'] = $this->view->translate('%s (%s)', $type['total'], Engine_Api::_()->sesadvancedcomment()->likeWord($type['type']));
                $reactionData[$reactionCounter]['imageUrl'] = Engine_Api::_()->sesadvancedcomment()->likeImage($type['type']);
                $reactionCounter++;
            }
            $images['reactionData'] = $reactionData;
        }
        if ($is_like) {
            $images['is_like'] = true;
            $like = true;
            $type = $reaction_type; //$is_like['reaction_type'];
            $imageLike = Engine_Api::_()->sesadvancedcomment()->likeImage($type);
            if ($type)
                $text = Engine_Api::_()->sesadvancedcomment()->likeWord($type);
            else
                $text = 'Like';

        } else {
            $images['is_like'] = false;
            $like = false;
            $type = '';
            $imageLike = '';
            $text = 'Like';
        }
        if (empty($like)) {
            $images["like"]["name"] = "like";
        } else {
            $images["like"]["name"] = "unlike";
        }

        $images["like"]["image"] = $imageLike;
        $images['reactionUserData'] = $this->view->FluentListUsers($action->likes()->getAllLikesUsers(), '', $action->likes()->getLike($this->view->viewer()), $this->view->viewer());
        //Reaction work end

        $this->view->reactionData = $images;

    }

    public function createAction()
  {
      if( !$this->_helper->requireUser()->isValid() ) return;


      if (!$this->getRequest()->isPost()) {
          return;
      }

      $viewer = Engine_Api::_()->user()->getViewer();
      $viewer_id = $viewer->getIdentity();

      //Current User Privacy
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_network', 'registered');

      foreach ($roles as $role) {
//           if ($auth->isAllowed($viewer, $role, 'story_view')) {
//               $auth_view = $role;
//           } else {
//               $auth_view = 'registered';
//           }

          if ($auth->isAllowed($viewer, $role, 'story_comment')) {
              $auth_comment = $role;
          } else {
              $auth_comment = 'registered';
          }
      }
      
      $auth_view = Engine_Api::_()->getDbTable('userinfos', 'sesstories')->isPrivacyExist($viewer->getIdentity());
      if(empty($auth_view)) 
        $auth_view = 'registered';
      Engine_Api::_()->sesstories()->isExist($viewer->getIdentity(), $auth_view);

      // Process
      $table = Engine_Api::_()->getDbtable('stories', 'sesstories');
      //$db = $table->getAdapter();
      //$db->beginTransaction();

      $values['owner_id'] = $viewer->getIdentity();
      $values['type'] = '0';
      
      if(!empty($_POST['background_id'])) {
        $values['background_id'] = $_POST['background_id'];
      }
      //if(!empty($_POST['story_type'])) {
        $values['story_type'] = $_POST['story_type'] ? $_POST['story_type'] : 'imagevideo';
      //}
      
      $images = $menuoptions = array();
      $counter = $menucounter = 0;
      if (isset($_FILES['attachmentVideo'])) {
          foreach ($_FILES['attachmentVideo']['name'] as $key => $files) {

              if (!empty($_FILES['attachmentVideo']['name'][$key])) {
                  try {

                      $type = explode('/', $_FILES['attachmentVideo']['type'][$key]);

                      $item = $table->createRow();
                      $values['type'] = '1';
                      $values['plateform'] = 3;
                      $item->setFromArray($values);
                      $item->view_privacy = $auth_view;
                      $item->title = $_POST['description'];
                      $item->save();

                      // Auth
                      $viewMax = array_search($auth_view, $roles);
                      $commentMax = array_search($auth_comment, $roles);

                      foreach ($roles as $i => $role) {
                          $auth->setAllowed($item, $role, 'view', ($i <= $viewMax));
                          $auth->setAllowed($item, $role, 'comment', ($i <= $commentMax));
                      }

                      $image = array('name' => $_FILES['attachmentVideo']['name'][$key], 'type' => $_FILES['attachmentVideo']['type'][$key], 'tmp_name' => $_FILES['attachmentVideo']['tmp_name'][$key], 'error' => $_FILES['attachmentVideo']['error'][$key], 'size' => $_FILES['attachmentVideo']['size'][$key]);

                      $params = array(
                          'parent_id' => $item->getIdentity(),
                          'parent_type' => $item->getType(),
                          'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
                      );

                      Engine_Api::_()->sesstories()->createVideo($params, $image, $item);
                      $result['message'] = $this->view->translate("Your stories video is currently being processed.");
//                      if ($type[1] == 'mp4') {
//                          $storage = Engine_Api::_()->getItemTable('storage_file');
//                          $storageObject = $storage->createFile($image, array(
//                              'parent_id' => $item->getIdentity(),
//                              'parent_type' => $item->getType(),
//                              'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
//                          ));
//                          // Remove temporary file
//                          @unlink($_FILES['attachmentVideo']['tmp_name'][$key]);
//                          $item->file_id = $storageObject->file_id;
//                          $item->save();
//                          $result['message'] = $this->view->translate("Your stories created successfully.");
//                          if ($item->getIdentity()) {
//                              $result['stories_id'] = $item->getIdentity();
//                              // for live streaming.
//                              $postData = $this->getRequest()->getPost();
//                              if (!empty($postData['elivehost_id'])) {
//                                  if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
//                                      if (!empty($postData['elivehost_id'])) {
//                                          $elivehostItem = Engine_Api::_()->getItem('elivehost', $postData['elivehost_id']);
//                                          if (!empty($elivehostItem)) {
//                                              if ($postData['canPost'] == "true")
//                                                  $elivehostItem->status = 'processing';
//                                              if ($postData['canPost'] == "false")
//                                                  $elivehostItem->status = 'completed';
//                                              $elivehostItem->story_id = $item->getIdentity();
//                                              $elivehostItem->save();
//                                          }
//                                      }
//                                  }
//                              }
//                          }
//                      } else {
//
//
//
//                      }
                      // Commit
                      //$db->commit();
                  } catch (Exception $e) {
                      throw $e;
                  }
              }
          }
      }

      if (isset($_FILES['attachmentImage'])) {
          foreach ($_FILES['attachmentImage']['name'] as $key => $files) {

              if (!empty($_FILES['attachmentImage']['name'][$key])) {
                  try {
                      $item = $table->createRow();
                      $values['plateform'] = 3;
                      $item->setFromArray($values);
                      $item->title = $_POST['description'] ? $_POST['description'] : '';
                      $item->view_privacy = $auth_view;
                      $item->save();

                      $image = array('name' => $_FILES['attachmentImage']['name'][$key], 'type' => $_FILES['attachmentImage']['type'][$key], 'tmp_name' => $_FILES['attachmentImage']['tmp_name'][$key], 'error' => $_FILES['attachmentImage']['error'][$key], 'size' => $_FILES['attachmentImage']['size'][$key]);
                      $storage = Engine_Api::_()->getItemTable('storage_file');
                      $storageObject = $storage->createFile($image, array(
                          'parent_id' => $item->getIdentity(),
                          'parent_type' => $item->getType(),
                          'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
                      ));
                      // Remove temporary file
                      @unlink($_FILES['attachmentImage']['tmp_name'][$key]);
                      $item->file_id = $storageObject->file_id;

                      $item->save();

                      // Auth
                      $viewMax = array_search($auth_view, $roles);
                      $commentMax = array_search($auth_comment, $roles);

                      foreach ($roles as $i => $role) {
                          $auth->setAllowed($item, $role, 'view', ($i <= $viewMax));
                          $auth->setAllowed($item, $role, 'comment', ($i <= $commentMax));
                      }
                      // Commit
                      //$db->commit();
                  } catch (Exception $e) {
                      throw $e;
                  }
                  $story_id = $item->getIdentity();
                  $result['message'] = $this->view->translate("Your story has been created successfully.");
//                  $images['story_content'][$counter]['media_url'] = $this->getBaseUrl(true, $storageObject->map());
//                  $images['story_content'][$counter]['comment'] = $item->title;
//                  $images['story_content'][$counter]['is_video'] = false;
//                  $images['story_content'][$counter]['highlight'] = $item->highlight;
//                  $images['story_content'][$counter]['like_count'] = $item->like_count;
//                  $images['story_content'][$counter]['comment_count'] = $item->comment_count;
//                  $images['story_content'][$counter]['creation_date'] = $item->creation_date;
//                  $images['story_content'][$counter]['story_id'] = $story_id;
//
//                  $menucounter = 0;
//                  if ($viewer_id != $item->owner_id) {
//                      $menuoptions[$menucounter]['name'] = "mute";
//                      $menuoptions[$menucounter]['label'] = $this->view->translate("Mute");
//                      $menucounter++;
//
//                      $menuoptions[$menucounter]['name'] = "report";
//                      $menuoptions[$menucounter]['label'] = $this->view->translate("Report");
//                      $menucounter++;
//
//                      $images['story_content'][$counter]['options'] = $menuoptions;
//                  }
//                  $counter++;
              }
          }
//          $result['story'] = $images;
//          $result['story']['user_id'] = $viewer->getIdentity();
//          $result['story']['username'] = $viewer->getTitle();
//          $result['story']['user_image'] = $this->getBaseUrl(true, $viewer->getPhotoUrl());
      }
      
      //code for text story
      if(isset($_POST['background_id']) && !empty($_POST['background_id'])) {
        try {
          $item = $table->createRow();
          $values['plateform'] = 3;
          $item->setFromArray($values);
          $item->title = $_POST['description'] ? $_POST['description'] : '';
          $item->background_id = $_POST['background_id'] ? $_POST['background_id'] : '0';
          $item->view_privacy = $auth_view;
          $item->save();

          // Auth
          $viewMax = array_search($auth_view, $roles);
          $commentMax = array_search($auth_comment, $roles);

          foreach ($roles as $i => $role) {
              $auth->setAllowed($item, $role, 'view', ($i <= $viewMax));
              $auth->setAllowed($item, $role, 'comment', ($i <= $commentMax));
          }
          // Commit
          //$db->commit();
        } catch (Exception $e) {
          throw $e;
        }
        $story_id = $item->getIdentity();
        $result['message'] = $this->view->translate("Your story has been created successfully.");
      }
      
     echo json_encode($result);die;

  }
    public function highlightAction()
    {

        $story = Engine_Api::_()->getItem('sesstories_story', $_POST['story_id']);
        $story->highlight = !$story->highlight;
        $story->save();

        echo 1;die;
    }

}
