<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Elivestreaming_Api_Core extends Core_Api_Abstract
{
    public function updateAllNotifications($eliveHostId)
    {
        return $this->notificationsAction("update", $eliveHostId);
    }
    public function sendResponse($params = array()){
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $data = array(
            'result' => !empty($params['result']) ? $params['result'] : array(),
        );
        if(!empty($params['error'])){
            $data['error'] = $params['error'];
            $data['message'] = $params['error_message'];
            $data['error_message'] = $params['error_message'];
        }
        if(is_string($params['result']) && $params['result'] != ""){
            $params['result'] = $view->translate($params['result']);
        }

        echo Zend_Json::encode($data);die;
    }
    function getPermission($isView = false){
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $agoraKey = Engine_Api::_()->getApi('settings', 'core')->getSetting('elivestreaming_agoraappid','');
        $elivestreaming_linux_base_url = Engine_Api::_()->getApi('settings', 'core')->getSetting('elivestreaming_linux_base_url','');
        $currentHostDomain = (_ENGINE_SSL ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'].rtrim($view->baseUrl(), '/').'/';
        if(!$agoraKey || !$elivestreaming_linux_base_url){
            return false;
        }

        //check sesapi installed
        if(!Engine_Api::_()->sesbasic()->isModuleEnable('sesapi'))
            return false;

        $selectTokenTable = Engine_Api::_()->getDbTable("aouthtokens",'sesapi');
        $userToken = $selectTokenTable->fetchRow($selectTokenTable->select()->where('user_id =?',$view->viewer()->getIdentity())->where('platform =?','3'));
        if($userToken){
            $token = $userToken->token;
        }else{
            $db = Engine_Db_Table::getDefaultAdapter();
            $aouthTokenColumn = $db->query('SHOW COLUMNS FROM engine4_sesapi_aouthtokens LIKE \'device_id\'')->fetch();
            if (empty($aouthTokenColumn)) {
                $db->query('ALTER TABLE  `engine4_sesapi_aouthtokens` ADD  `device_id` TINYINT( 1 ) NOT NULL DEFAULT "0";');
            }
            $token = Engine_Api::_()->getApi('oauth', 'sesapi')->getAuthRandomString().time();
            $userTokenInsert =  $selectTokenTable->createRow();
            $userTokenInsert->setFromArray(array('token'=>$token,'user_id'=>$view->viewer()->getIdentity(),'platform'=>3,'device_id'=>3));
            $userTokenInsert->save();
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $privacy = 'everyone';
        $network = explode("_", $privacy);
        $isCause = false;
        $friendsIds = $viewer->membership()->getMembersIds();
        $totalLiveVideo = Engine_Api::_()->getDbTable('elivehosts', 'elivestreaming')->countLiveVideo($viewer->getIdentity());
        $allowLiveVideo = $viewer->getIdentity() ? Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'max') : false;
        $permission = $viewer->getIdentity() ?  json_decode(Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'share')) : false;
        $result = array('canPost' => false, 'canShareInStory' => false, 'appId'=>$agoraKey, 'elivestreaming_linux_base_url'=>$elivestreaming_linux_base_url, 'currentHostDomain' => $currentHostDomain);
        $result['canSave'] = $viewer->getIdentity() ? filter_var(Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'save'), FILTER_VALIDATE_BOOLEAN) : false;
        $result['maxStreamDurations'] = $viewer->getIdentity() ?  (int) Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'duration') : false;
        $result['token'] = $token;
        if ($viewer) {
            if (!empty($viewer['photo_id']) && $viewer->getPhotoUrl()) {
                $result['user']['image'] = Engine_Api::_()->sesapi()->getBaseUrl(true,$viewer->getPhotoUrl());
            } else
                $result['user']['image'] = Engine_Api::_()->sesapi()->getBaseUrl(true, '/application/modules/User/externals/images/nophoto_user_thumb_profile.png');
            $result['user']['name'] = $viewer->getTitle();
            $result['user']['href'] = Engine_Api::_()->sesapi()->getBaseUrl($viewer->getHref());
            $result['user']['datetime'] = date('Y-m-d H:i:s');
        }
        if (is_array($permission) && engine_in_array("sesadvancedactivity", $permission))
            $result['canPost'] = true;
        if (is_array($permission) && engine_in_array("sesstories", $permission))
            $result['canShareInStory'] = true;

        if(!$isView) {
            if (!$viewer) {
                $result['cause'] = 'permission_error';
                $result['message'] = 'permission_error';
                $isCause = true;
            } else if (!Engine_Api::_()->sesbasic()->isModuleEnable('sesvideo')) {
                $result['cause'] = 'sesvideo';
                $result['message'] = $view->translate('advance_video_plugin_disable');
                $isCause = true;
            } else if (!Engine_Api::_()->authorization()->getPermission($viewer, 'elivehost', 'create')) {
                $result['cause'] = 'perform_live';
                $result['message'] = $view->translate('elive_can_create');
                $isCause = true;
            } else if ($totalLiveVideo >= $allowLiveVideo && $allowLiveVideo != 0) {
                $result['cause'] = 'perform_limit';
                $result['message'] = $view->translate('elive_max_create');
                $isCause = true;
            } else if ($privacy == "friends") {
                if (empty($friendsIds)) {
                    $result['cause'] = 'friend_list';
                    $result['message'] = $view->translate('elive_no_friends');
                    $isCause = true;
                }
            } else if ($network[0] == "network") {
                if (!Engine_Api::_()->elivestreaming()->getNetworkMembers($network[2])) {
                    $result['cause'] = 'network_list';
                    $result['message'] = $view->translate('elive_empty_network');
                    $isCause = true;
                }
            }
        }

        if ($isCause)
            return 0;
        return $result;
    }
    public function notificationsAction($type, $eliveHostId)
    {
        try {
            $notificationReceiverTable = Engine_Api::_()->getDbTable('notificationreceivers', 'elivestreaming');
            $notificationReceivers = $notificationReceiverTable->getAllnotificationReceivers(array('elivehost_id' => $eliveHostId));
            $allNotificationReceivers = $notificationReceiverTable->fetchAll($notificationReceivers);
            foreach ($allNotificationReceivers as $NotificationReceiver) {
                $notificationItem = Engine_Api::_()->getItem('activity_notification', $NotificationReceiver->notification_id);
                if ($type == "update") {
                    $notificationItem->type = 'elivestreaming_was_live';
                    $notificationItem->read = 0;
                    $notificationItem->date = date('Y-m-d H:i:s');
                    $notificationItem->save();
                }
                if ($type == "delete") {
                    $notificationItem->delete();
                }
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteAllNotifications($eliveHostId)
    {
        return $this->notificationsAction("delete", $eliveHostId);
    }
    public function getNetworkMembers($networkId)
    {
        $networkTable = Engine_Api::_()->getDbtable('membership', 'network');
        $networkSelect = $networkTable->select()->from($networkTable->info('name'), array('user_id'))->where('resource_id = ?', (int)$networkId);
        $networkUsers = $networkTable->fetchAll($networkSelect);
        if(!empty($networkUsers->toArray())){
            return $networkUsers;
        }else{
            return false;
        }
    }
}
