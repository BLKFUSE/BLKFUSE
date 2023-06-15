<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Api_Core extends Core_Api_Abstract {

  public function getUserInfoItem($user_id) {

    $userinfoTable = Engine_Api::_()->getDbTable('userinfos', 'sesmember');
    $userinfo_id = $userinfoTable->select()
                ->from($userinfoTable->info('name'), 'userinfo_id')
                ->where($userinfoTable->info('name') . '.user_id = ?', $user_id)
                ->query()
                ->fetchColumn();
    return Engine_Api::_()->getItem('sesmember_userinfo', $userinfo_id);
  }

  //Get member profile type according to user.
  public function getProfileType($subject, $columnName = 'label') {
    $memberType = '';
    $fieldsByAlias = Engine_Api::_()->fields()->getFieldsObjectsByAlias($subject);
    if (!empty($fieldsByAlias['profile_type'])) {
      $optionId = $fieldsByAlias['profile_type']->getValue($subject);
      if ($optionId) {
        $optionObj = Engine_Api::_()->fields()
                ->getFieldsOptions($subject)
                ->getRowMatching('option_id', $optionId->value);
        if ($optionObj) {
          $memberType = $optionObj->$columnName;
        }
      }
    }
    return $memberType;
  }

  public function getIdentityWidget($name, $type, $corePages) {

    if((isset($_SESSION['sespwa']['sespwa']) && !empty($_SESSION['sespwa']['sespwa'])) || (isset($_SESSION['sespwa']['mobile']) && !empty($_SESSION['sespwa']['mobile']))) {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'sespwa');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'sespwa')->info('name');
    } else {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'core');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'core')->info('name');
    }
    $identity = $widgetTable->select()
                ->setIntegrityCheck(false)
                ->from($widgetTable, 'content_id')
                ->where($widgetTable->info('name') . '.type = ?', $type)
                ->where($widgetTable->info('name') . '.name = ?', $name)
                ->where($widgetPages . '.name = ?', $corePages)
                ->joinLeft($widgetPages, $widgetPages . '.page_id = ' . $widgetTable->info('name') . '.page_id')
                ->query()
                ->fetchColumn();
    return $identity;
  }

  public function getProfileTypeValue($params = array()) {
    $optionsTable = Engine_Api::_()->fields()->getTable('user', 'options');
    $optionsTableName = $optionsTable->info('name');
    return $optionsTable->select()
                    ->from($optionsTableName, array('label'))
                    ->where($optionsTableName . '.option_id = ?', $params['option_id'])
                    ->query()
                    ->fetchColumn();
  }

  public function getProfileTypeId($params = array()) {
    $valuesTable = Engine_Api::_()->fields()->getTable('user', 'values');
    $valuesTableName = $valuesTable->info('name');
    return $valuesTable->select()
                    ->from($valuesTableName, array('value'))
                    ->where($valuesTableName . '.item_id = ?', $params['user_id'])
                    ->where($valuesTableName . '.field_id = ?', $params['field_id'])->query()
                    ->fetchColumn();
  }

  public function getFollowResourceStatus($subject_id) {

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $followsTable = Engine_Api::_()->getDbTable('follows', 'sesmember');
    $followsTableName = $followsTable->info('name');
    $select = $followsTable->select()
            ->from($followsTableName, array('*'))
            ->where('resource_id =?', $viewer_id)
            ->where('user_id =?', $subject_id)
            ->limit(1);

    return $followsTable->fetchRow($select);
  }

  public function getFollowUserStatus($subject_id) {

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();

    $followsTable = Engine_Api::_()->getDbTable('follows', 'sesmember');
    $followsTableName = $followsTable->info('name');
    $select = $followsTable->select()
            ->from($followsTableName, array('*'))
            ->where('user_id =?', $viewer_id)
            ->where('resource_id =?', $subject_id)
            ->limit(1);

    return $followsTable->fetchRow($select);
  }

  public function getFollowStatus($user_id = 0) {
    if (!$user_id)
      return 0;
    $resource_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if ($resource_id == 0)
      return false;
    $followTable = Engine_Api::_()->getDbtable('follows', 'sesmember');
    $follow = $followTable->select()->from($followTable->info('name'), new Zend_Db_Expr('COUNT(follow_id) as follow'))->where('resource_id =?', $resource_id)->where('user_id =?', $user_id)->limit(1)->query()->fetchColumn();
    if ($follow > 0) {
      return true;
    } else {
      return false;
    }
    return false;
  }

  public function getBlock($params = array()) {
    $table = Engine_Api::_()->getDbtable('block', 'user');
    $select = $table->select()
            ->where('user_id = ?', $params['user_id'])
            ->where('blocked_user_id = ?', $params['blocked_user_id'])
            ->limit(1);
    $row = $table->fetchRow($select);
  }

  public function allowReviewRating() {
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1)) {
      return true;
    }
    return false;
  }

  public function getMutualFriendCount($subject, $viewer) {
    $friendsTable = Engine_Api::_()->getDbtable('membership', 'user');
    $friendsName = $friendsTable->info('name');
    $col1 = 'resource_id';
    $col2 = 'user_id';
    $select = new Zend_Db_Select($friendsTable->getAdapter());
    $select
            ->from($friendsName, $col1)
            ->join($friendsName, "`{$friendsName}`.`{$col1}`=`{$friendsName}_2`.{$col1}", null)
            ->where("`{$friendsName}`.{$col2} = ?", $viewer->getIdentity())
            ->where("`{$friendsName}_2`.{$col2} = ?", $subject->getIdentity())
            ->where("`{$friendsName}`.active = ?", 1)
            ->where("`{$friendsName}_2`.active = ?", 1)
    ;
    // Now get all common friends
    $uids = array();
    foreach ($select->query()->fetchAll() as $data) {
      $uids[] = $data[$col1];
    }
    // Do not render if nothing to show
    if (engine_count($uids) <= 0) {
      return 0;
    }
    // Get paginator
    $usersTable = Engine_Api::_()->getItemTable('user');
    $select = $usersTable->select()->from($usersTable->info('name'), new Zend_Db_Expr('COUNT(user_id)'))->where('user_id IN(?)', $uids);
    return $select->query()->fetchColumn();
  }

  public function getMutualFriends($limit = 5) {
    $viewerID = Engine_Api::_()->user()->getViewer()->getIdentity();
    $userTable = Engine_Api::_()->getItemTable('user');
    $data = Engine_Db_Table::getDefaultAdapter()->query("SELECT
       f2.resource_id as \"mid\",
       f3.total as \"cfid\",
       group_concat(f1.resource_id) as \"mutualfriend\"
FROM engine4_user_membership f1
INNER JOIN engine4_user_membership f2 on f1.resource_id = f2.user_id and f1.user_id <> f2.resource_id
INNER JOIN (
    SELECT fa.user_id,
           fb.resource_id,
           engine_count(fb.resource_id) as total
    FROM engine4_user_membership fa
    INNER JOIN engine4_user_membership fb on fa.resource_id = fb.user_id and fa.user_id <> fb.resource_id
    GROUP BY fa.user_id, fb.resource_id
) f3 on f3.user_id = f1.user_id and f3.resource_id = f2.resource_id where f1.user_id = $viewerID group by f2.resource_id order by cfid DESC limit  $limit")->fetchAll();
    $reaminingData = $limit - engine_count($data);
    $uid = '';
    foreach ($data as $user) {
      $uid = $user['mid'] . ',' . $user['mutualfriend'] . ',' . $uid;
    }
    $uid = trim($uid, ',');
    if ($reaminingData && $uid) {
      $select1 = $userTable->select()->from($userTable->info('name'), array('user_id as mid', 'cfid' => new Zend_Db_Expr('0'), 'mutualfriend' => new Zend_Db_Expr('0')))->where($userTable->info('name') . '.user_id NOT IN (' . $uid . ')')->where('user_id <> ?', $viewerID)->limit($reaminingData);
      $users = $userTable->fetchAll($select1)->toArray();
      $data = array_merge($data, $users);
    }
    return $data;
  }

  public function getWidgetizePageId($homePageId = null) {
    return Engine_Db_Table::getDefaultAdapter()->select()
                    ->from('engine4_core_pages', 'page_id')
                    ->where('name = ?', 'sesmember_index_' . $homePageId)
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }

  public function checkPage($pageName, $pageTitle = '', $type = '') {
    $db = Engine_Db_Table::getDefaultAdapter();
    // profile page
    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', $pageName)
            ->limit(1)
            ->query()
            ->fetchColumn();
    if ($page_id)
      return 0;
    else {
      // Insert page
      $db->insert('engine4_core_pages', array(
          'name' => $pageName,
          'displayname' => 'SNS - ' . $pageTitle . ' Page',
          'title' => 'SNS - ' . $pageTitle . ' Page',
          'description' => '',
          'custom' => 0,
      ));
      $page_id = $db->lastInsertId();

      if($type != 'browse') {
        // Insert main
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'main',
            'page_id' => $page_id,
            'order' => 2,
        ));
        $main_id = $db->lastInsertId();

        // Insert main-middle
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'middle',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 3,
        ));
        $main_middle_id = $db->lastInsertId();

        // Insert left
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'left',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
        ));
        $left_id = $db->lastInsertId();
      } else if($type == 'browse') {

        $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'top',
          'page_id' => $page_id,
          'order' => 1,
        ));
        $top_id = $db->lastInsertId();
        $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'main',
          'page_id' => $page_id,
          'order' => 2,
        ));
        $main_id = $db->lastInsertId();
        $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $top_id,
        ));
        $top_middle_id = $db->lastInsertId();

        $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'middle',
          'page_id' => $page_id,
          'parent_content_id' => $main_id,
          'order' => 2,
        ));
        $main_middle_id = $db->lastInsertId();
      }

      $widgetOrder = 1;
      if($type == 'browse') {

        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesmember.browse-menu',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => $widgetOrder++,
        ));

        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesmember.browse-search',
          'page_id' => $page_id,
          'parent_content_id' => $top_middle_id,
          'order' => $widgetOrder++,
          'params' => '{"view_type":"horizontal","search_type":["recentlySPcreated","mostSPviewed","mostSPliked","mostSPrated","featured","sponsored","verified"],"view":["0","1","3","week","month"],"default_search_type":"creation_date ASC","show_advanced_search":"1","network":"yes","alphabet":"yes","friend_show":"yes","search_title":"yes","browse_by":"yes","location":"yes","kilometer_miles":"yes","country":"yes","state":"yes","city":"yes","zip":"yes","member_type":"yes","has_photo":"yes","is_online":"yes","is_vip":"yes","title":"","nomobile":"0","name":"sesmember.browse-search"}'
        ));

        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesmember.browse-members',
          'page_id' => $page_id,
          'parent_content_id' => $main_middle_id,
          'order' => $widgetOrder++,
          'params' => '{"enableTabs":["list","advlist","grid","advgrid","pinboard","map"],"openViewType":"advlist","show_criteria":["featuredLabel","sponsoredLabel","verifiedLabel","vipLabel","message","friendButton","followButton","viewDetailsLink","likemainButton","socialSharing","like","location","rating","view","title","friendCount","mutualFriendCount","profileType","age","pinboardSlideshow"],"limit_data":"12","pagging":"button","order":"mostSPviewed","show_item_count":"1","list_title_truncation":"45","grid_title_truncation":"45","advgrid_title_truncation":"45","pinboard_title_truncation":"45","main_height":"350","main_width":"585","height":"200","width":"250","photo_height":"250","photo_width":"284","info_height":"315","advgrid_height":"322","advgrid_width":"382","pinboard_width":"350","title":"","nomobile":"0","name":"sesmember.browse-members"}',

        ));

      } else if ($type == 'home') {
        // Insert left
        $db->insert('engine4_core_content', array(
            'type' => 'container',
            'name' => 'right',
            'page_id' => $page_id,
            'parent_content_id' => $main_id,
            'order' => 1,
        ));
        $right_id = $db->lastInsertId();

        // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.home-photo',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 1,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.home-links',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 2,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.list-online',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 3,
            'params' => '{"title":"%s Members Online"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.statistics',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 4,
            'params' => '{"title":"Network Stats"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'activity.list-requests',
            'page_id' => $page_id,
            'parent_content_id' => $right_id,
            'order' => 1,
            'params' => '{"title":"Requests"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.list-signups',
            'page_id' => $page_id,
            'parent_content_id' => $right_id,
            'order' => 2,
            'params' => '{"title":"Newest Members"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.list-popular',
            'page_id' => $page_id,
            'parent_content_id' => $right_id,
            'order' => 3,
            'params' => '{"title":"Popular Members"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'announcement.list-announcements',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'activity.feed',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 2,
            'params' => '{"title":"What\'s New"}',
        ));
      } elseif ($type == 'profile') {
        // Insert content
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-photo',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 1,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-options',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 2,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-friends-common',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 3,
            'params' => '{"title":"Mutual Friends"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-info',
            'page_id' => $page_id,
            'parent_content_id' => $left_id,
            'order' => 4,
            'params' => '{"title":"Member Info"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-status',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 1,
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.container-tabs',
            'page_id' => $page_id,
            'parent_content_id' => $main_middle_id,
            'order' => 2,
            'params' => '{"max":"6"}',
        ));
        $tabContainerID = $db->lastInsertId();
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'activity.feed',
            'page_id' => $page_id,
            'parent_content_id' => $tabContainerID,
            'order' => 1,
            'params' => '{"title":"Updates"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-fields',
            'page_id' => $page_id,
            'parent_content_id' => $tabContainerID,
            'order' => 2,
            'params' => '{"title":"Info"}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'user.profile-friends',
            'page_id' => $page_id,
            'parent_content_id' => $tabContainerID,
            'order' => 3,
            'params' => '{"title":"Friends","titleCount":true}',
        ));
        $db->insert('engine4_core_content', array(
            'type' => 'widget',
            'name' => 'core.profile-links',
            'page_id' => $page_id,
            'parent_content_id' => $tabContainerID,
            'order' => 7,
            'params' => '{"title":"Links","titleCount":true}',
        ));
      }
      return $page_id;
    }
  }

  public function updateUserInfo($resource_id = null, $eventType = null) {
    $likeCountForApproved = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.like.count', 10);
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($resource_id);
    if ($eventType == 'create') {
      $userLikeCount = Engine_Api::_()->getItem('user', $resource_id)->like_count;
      $like_count = $userLikeCount + 1;
      if ($like_count >= $likeCountForApproved) {
        $userInfoItem->user_verified = 1;
        $userInfoItem->save();
      }
    } else if ($eventType == 'delete') {
      if (empty($_SESSION['sesmember_content_like_id']))
        return;
      else {
        $resourceId = $_SESSION['sesmember_content_like_id'];
        //$userLikeCount = Engine_Api::_()->getItem('user', $resourceId)->like_count;
        //$like_count = $userLikeCount - 1;
        //if ($like_count < $likeCountForApproved) {
       //   Engine_Api::_()->getItemTable('user')->update(array('user_verified' => 0), array('user_id =?' => $resourceId));
       // }
        unset($_SESSION['sesmember_content_like_id']);
      }
    }
  }

  public function getWidgetParams($levelId = null) {
    $widgetizePageId = Engine_Api::_()->getDbtable('homepages', 'sesmember')->checkLevelId($levelId, '0', 'profile');
    if ($widgetizePageId)
      $pageName = 'sesmember_index_' . $widgetizePageId;
    else
      $pageName = 'user_profile_index';
    $db = Engine_Db_Table::getDefaultAdapter();
    $page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', $pageName)
            ->limit(1)
            ->query()
            ->fetchColumn();
    $params = $db->select()
            ->from('engine4_core_content', 'params')
            ->where('name = ?', 'sesmember.member-reviews')
            ->where('page_id = ?', $page_id)
            ->limit(1)
            ->query()
            ->fetchColumn();
    $decodedparams = json_decode($params);
    return $stats = $decodedparams->stats;
  }

  public function checkMemberOnline($userId = 0) {
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');
    $onlineTableName = $onlineTable->info('name');
    return $onlineTable->select()
                    ->from($onlineTableName, 'user_id')
                    ->where($onlineTableName . '.user_id  =?', $userId)
                    ->where($onlineTableName . '.active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
                    ->query()
                    ->fetchColumn();
  }

  public function getwidgetizePage($params = array()) {

    $corePages = Engine_Api::_()->getDbtable('pages', $params['modulename']);
    $corePagesName = $corePages->info('name');
    $select = $corePages->select()
            ->from($corePagesName, array('*'))
            ->where('name = ?', $params['name'])
            ->limit(1);
    return $corePages->fetchRow($select);
  }
}
