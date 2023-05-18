<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Elivestreaming_IndexController extends Core_Controller_Action_Standard
{
    public function sendAction()
    {
        if (!$this->_helper->requireUser()->isValid()) {
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        if (!isset($viewer))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('you are not authorize to access this page'), 'result' => array()));

        if (!Engine_Api::_()->sesbasic()->isModuleEnable('sesvideo'))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Advance Video Plugin is not Enabled yet!'), 'result' => array()));

        if (!Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'create'))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('You don\'t have permission to perform live video.'), 'result' => array()));

        $totalLiveVideo = Engine_Api::_()->getDbTable('elivehosts', 'elivestreaming')->countLiveVideo($viewer->getIdentity());
        $allowLiveVideo = Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'max');
        if ($totalLiveVideo >= $allowLiveVideo && $allowLiveVideo != 0) {
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('You have already uploaded the maximum number of entries allowed.'), 'result' => array()));
        }
        $privacy = $this->_getParam('privacy', 'everyone');
        $friendsIds = $viewer->membership()->getMembersIds();

        if ($privacy == "friends") {
            if (empty($friendsIds))
                Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Your friend list is empty yet!'), 'result' => array()));
        }

        $network = explode("_", $privacy);
        if ($network[0] == "network") {
            $networkMember = Engine_Api::_()->elivestreaming()->getNetworkMembers($network[2]);
            if (!$networkMember) {
                Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('No members in this network yet!'), 'result' => array()));
            }
        }

        $params = array('started' => 1);
        $postData['privacy'] = $privacy;
        $livestreamingTable = Engine_Api::_()->getDbTable('elivehosts', 'elivestreaming');
        $db = $livestreamingTable->getAdapter();
        $db->beginTransaction();
        try {
            $viewerId = $viewer->getIdentity();
            $elivestreamingHost = $livestreamingTable->createRow();
            $values['user_id'] = $viewerId;
            $values['name'] = $viewer->displayname;
            $values['status'] = 'started';
            $elivestreamingHost->setFromArray($values);
            $elivestreamingHost->save();
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
        }

        //set authorization on service created by professional
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        if (empty($values['auth_view'])) {
            $values['auth_view'] = 'everyone';
        }
        if (empty($values['auth_comment'])) {
            $values['auth_comment'] = 'everyone';
        }
        $viewMax = array_search($values['auth_view'], $roles);
        $commentMax = array_search($values['auth_comment'], $roles);
        foreach ($roles as $i => $role) {
            $auth->setAllowed($elivestreamingHost, $role, 'view', ($i <= $viewMax));
            $auth->setAllowed($elivestreamingHost, $role, 'comment', ($i <= $commentMax));
        }
        //end authorization
        //activity feed
        // $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
        // $action = $activityApi->addActivity($viewer, $elivestreamingHost, 'elivestreaming_golive', null, );
        $activityApi = Engine_Api::_()->getDbtable('actions', 'sesadvancedactivity');
        $action = $activityApi->addActivity($viewer, $elivestreamingHost, 'elivestreaming_golive', null, $params, $postData);
        if ($action)
            $activityApi->attachActivity($action, $elivestreamingHost);
        $elivestreamingHost->action_id = $action->getIdentity();
        $elivestreamingHost->save();
        //end activity feed

        // if ($privacy != 'everyone') {
        if ($privacy == "friends" || $privacy == "everyone") {
            if (!empty($friendsIds)) {
                $userTable = Engine_Api::_()->getDbTable('users', 'user');
                $select = $userTable
                    ->select()
                    ->from($userTable->info('name'), array('user_id', 'displayname'))
                    ->where('user_id IN (?)', $friendsIds);
                $users = $userTable->fetchAll($select);
            }
        }
        if ($network[0] == "network") {
            $users = $networkMember;
        }
        if (!empty($users)) {
            foreach ($users as $receivers) {
                $receiver = Engine_Api::_()->getItem('user', $receivers->user_id);
                $notificationreceiverTable = Engine_Api::_()->getDbTable('notificationreceivers', 'elivestreaming');
                $db = $notificationreceiverTable->getAdapter();
                $db->beginTransaction();
                try {
                    $notification = Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($receiver, $viewer, $elivestreamingHost, 'elivestreaming_golive', array('activity_action_id' => $action->getIdentity(), 'elivehosts' => $elivestreamingHost->getIdentity(), 'host_id' => $viewerId));
                    $elivestreamingnotification = $notificationreceiverTable->createRow();
                    $notificationValues['elivehost_id'] = $elivestreamingHost->getIdentity();
                    $notificationValues['notification_id'] = $notification->getIdentity();
                    $elivestreamingnotification->setFromArray($notificationValues);
                    $elivestreamingnotification->save();
                    $db->commit();
                } catch (Exception $e) {
                    $db->rollBack();
                }
            }
        }
        // }

        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesstories')) {
            //Current User Privacy
            $auth = Engine_Api::_()->authorization()->context;
            $roles = array('owner', 'owner_member', 'owner_network', 'registered');
            foreach ($roles as $role) {
                if ($auth->isAllowed($viewer, $role, 'story_view')) {
                    $auth_view = $role;
                } else {
                    $auth_view = 'owner_member';
                }
                if ($auth->isAllowed($viewer, $role, 'story_comment')) {
                    $auth_comment = $role;
                } else {
                    $auth_comment = 'owner_member';
                }
            }
            Engine_Api::_()->sesstories()->isExist($viewer->getIdentity(), $auth_view);
            // Process
            $table = Engine_Api::_()->getDbtable('stories', 'sesstories');
            $values['owner_id'] = $viewer->getIdentity();
            $values['type'] = '0';
            try {
                $item = $table->createRow();
                $item->setFromArray($values);
                $item->title = $this->view->translate("elive_dummy_story");
                $item->view_privacy = $auth_view;
                $item->status = 1;
                $item->file_id = $viewer->photo_id;
                $item->save();
                // Auth
                $viewMax = array_search($auth_view, $roles);
                $commentMax = array_search($auth_comment, $roles);

                foreach ($roles as $i => $role) {
                    $auth->setAllowed($item, $role, 'view', ($i <= $viewMax));
                    $auth->setAllowed($item, $role, 'comment', ($i <= $commentMax));
                }
                $elivestreamingHost->story_id = $item->getIdentity();
                $elivestreamingHost->save();
            } catch (Exception $e) {
                Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Error while create live story'), 'result' => array('message' => $e)));
            }
        }
        $result = array('elivehost_id' => $elivestreamingHost->getIdentity(), 'activity' => $action->toArray());
        if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesstories'))
            $result['message'] = $message = $this->view->translate("story_plugin_disable");

        Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));
    }
    public function cancelAction()
    {
        if (!$this->_helper->requireUser()->isValid()) {
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => 'permission_error', 'result' => array()));
        }
        $elivehost_id = $this->_getParam('elivehost_id');
        $canShareInStory = $this->_getParam('canShareInStory');
        $canPost = $this->_getParam('canPost');
        $message = "";
        if (empty($elivehost_id))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => 'elivehost_id is missing', 'result' => array()));
        if (empty($canShareInStory))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => 'canShareInStory is missing', 'result' => array()));
        if (empty($canPost))
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => 'canPost is missing', 'result' => array()));
        if ($canShareInStory) {
            $elivehostItem = Engine_Api::_()->getItem('elivehost', $elivehost_id);
            if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesstories')) {
                if (!empty($elivehostItem['story_id'])) {
                    //delete dummy story when user goes live. this is image type story.
                    $story = Engine_Api::_()->getItem('sesstories_story', $elivehostItem->story_id);
                    $message = "Dummy story delete successfully id is =>" . $story->getIdentity();
                    $story->delete();
                } else {
                    $message = "Story not available";
                }
            } else
                $message = "story_plugin_disable";
        }
        if ($elivehost_id && ($canPost == 'false' || $canPost == false)) {
            $elivehostItem = Engine_Api::_()->getItem('elivehost', $elivehost_id);
            $actionItem = Engine_Api::_()->getItem('activity_action', $elivehostItem->action_id);
            if (!empty($actionItem)) {
                $actionItem->delete();
                $message = "Activity feed delete successfully";
            } else {
                $message = "Activity feed not available";
            }
            if (Engine_Api::_()->elivestreaming()->deleteAllNotifications($elivehostItem['elivehost_id']))
                $message .= " and notifications";
        }
        if ($elivehost_id && ($canPost == 'false' || $canPost == false) && ($canShareInStory == 'false' || $canShareInStory == false)) {
            $elivehostItem = Engine_Api::_()->getItem('elivehost', $elivehost_id);
            if ($elivehostItem) {
                $elivehostItem->delete();
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesstories')) {
                    $message = $this->view->translate("elive_cancel_all");
                } else
                    $message = $this->view->translate("elive_cancel_all_no_story");
            } else {
                $message = "live host not available.";
            }
        }
        Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message' => $message)));
    }

    public function statusAction()
    {
        $elivehost_id = $this->_getParam('elivehost_id');
        if (!empty($this->_getParam('action_id'))) {
            $elivehost = Engine_Api::_()->getDbtable('elivehosts', 'elivestreaming')->getHostId(array('action_id' => $this->_getParam('action_id')));
            $elivehost_id = $elivehost->elivehost_id;
        }
        $elivehostItem = Engine_Api::_()->getItem('elivehost', $elivehost_id);
        if (!empty($elivehostItem)) {
            $status = array('message' => $elivehostItem->name . " is live now.");
            if ($elivehostItem->status == "processing")
                $status['message'] = $this->view->translate("elive_process");
            if ($elivehostItem->status == "completed")
                $status['message'] = $this->view->translate("elive_completed");
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array_merge($elivehostItem->toArray(), $status)));
        } else
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message' => $this->view->translate('elive_delete'), 'status' => 'deleted')));
    }

    public function changeStatusAction()
    {
        $elivehost_id = $this->_getParam('elivehost_id');
        if (!empty($elivehost_id)) {
            $elivehostItem = Engine_Api::_()->getItem('elivehost', $elivehost_id);
            if (!empty($elivehostItem)) {
                $elivehostItem->status = 'processing';
                $elivehostItem->save();
                if ($elivehostItem['action_id']) {
                    $actionItem = Engine_Api::_()->getItem('activity_action', $elivehostItem->action_id);
                    $actionItem->params = array('processing' => 1);
                    $actionItem->save();
                }
                Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message' => "host data change successfully.")));
            } else {
                Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate("Elive host id not found."), 'result' => array()));
            }
        } else {
            Engine_Api::_()->elivestreaming()->sendResponse(array('error' => '1', 'error_message' => $this->view->translate("Elive host id is missing."), 'result' => array()));
        }
    }
}
