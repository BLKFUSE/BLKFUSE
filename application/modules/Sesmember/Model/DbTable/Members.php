<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Members.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Members extends Engine_Db_Table {

  public function getMemberPaginator($params = array(), $customFields = array()) {
    return Zend_Paginator::factory($this->getMemberSelect($params, $customFields));
  }

  public function getMemberSelect($params = array(), $customFields = array()) {

    $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
    $tableLocationName = $tableLocation->info('name');
    $viewer = Engine_Api::_()->user()->getViewer();
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');

    $userinfosTable = Engine_Api::_()->getDbtable('userinfos', 'sesmember');
    $userinfosTableName = $userinfosTable->info('name');

    if (isset($params['lat']))
      $origLat = $params['lat'];
    if (isset($params['lng']))
      $origLon = $params['lng'];
      
    $searchType = 6371;
    //This is the maximum distance (in miles) away from $origLat, $origLon in which to search
    if (isset($params['miles']))
      $dist = $params['miles'];
    
    $select = $table->select()
            ->setIntegrityCheck(false)
            ->from($memberTableName,array('user_id', 'like_count', 'comment_count', 'view_count', 'approved', 'verified', 'photo_id', 'coverphoto', 'level_id', 'username', 'displayname','email', 'status', 'enabled', 'creation_date', 'member_count', 'coverphotoparams', 'view_privacy'))
            ->joinLeft($userinfosTableName, "$userinfosTableName.user_id = $memberTableName.user_id",array('userinfo_id', 'follow_count', 'location', 'rating', 'user_verified', 'cool_count', 'funny_count', 'useful_count', 'featured', 'sponsored', 'vip', 'offtheday', 'starttime', 'endtime', 'adminpicks', 'order'));
            
    if (!empty($params['city'])) {
      $select->where('`' . $tableLocationName . '`.`city` LIKE ?', '%' . $params['city'] . '%');
    }
    if(isset($params['city']) && isset($customFields['city'])) {
      unset($customFields['city']);
    }
    if (!empty($params['state'])) {
      $select->where('`' . $tableLocationName . '`.`state` LIKE ?', '%' . $params['state'] . '%');
    }
    if(isset($params['state']) && isset($customFields['state'])) {
      unset($customFields['state']); 
    }
    if (!empty($params['country']))
      $select->where('`' . $tableLocationName . '`.`country` LIKE ?', '%' . $params['country'] . '%');
    if (!empty($params['zip']))
      $select->where('`' . $tableLocationName . '`.`zip` LIKE ?', '%' . $params['zip'] . '%');
    
    
    //Location Based search
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('seslocation') && Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocationenable', 1) && empty($params['lat']) && $params['lat'] != 'undefined' && !empty($_COOKIE['sesbasic_location_data'])) {
      $params['location'] = $_COOKIE['sesbasic_location_data'];
      $params['lat'] = $_COOKIE['sesbasic_location_lat'];
      $params['lng'] = $_COOKIE['sesbasic_location_lng'];
      $params['miles'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation.searchmiles', 50);
    }
    
    if (isset($params['lat']) && isset($params['lng']) && $params['lat'] != 'undefined' && $params['lat'] != '' && $params['lng'] != '' && ((isset($params['location']) && $params['location'] != '') || isset($params['locationWidget']))) {
      $origLat =  $lat = $params['lat'];
      $origLon = $long =  $params['lng'];
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.search.type', 1) == 1) {
        $searchType = 3956;
      }
      else
        $searchType = 6371;
      //This is the maximum distance (in miles) away from $origLat, $origLon in which to search
      if(isset($params['miles']) && $params['miles'] != 0) {
        $dist = $params['miles'];
      } else {
          $dist = 1000;
      }

       $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $memberTableName . '.user_id AND ' . $tableLocationName . '.resource_type = "user" ', array('lat', 'lng', 'distance' => new Zend_Db_Expr($searchType." * 2 * ASIN(SQRT( POWER(SIN(($lat - lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS(lat * pi()/180) * POWER(SIN(($long - lng) * pi()/180 / 2), 2) ))")));

      $rectLong1 = $long - $dist/abs(cos(deg2rad($lat))*69);
      $rectLong2 = $long + $dist/abs(cos(deg2rad($lat))*69);
      $rectLat1 = $lat-($dist/69);
      $rectLat2 = $lat+($dist/69);

      $select->where($tableLocationName . ".lng between $rectLong1 AND $rectLong2  and " . $tableLocationName . ".lat between $rectLat1 AND $rectLat2");

      $select->order('distance');
      $select->having("distance < $dist");
      if($customFields['location'] && !empty($customFields['location'])) {
        unset($customFields['location']);
      }
    }
    else {
      $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $memberTableName . '.user_id AND ' . $tableLocationName . '.resource_type = "user" ', array('lat', 'lng'));
    }
    if(!empty($params["action_id"])){
       $activtyTableName = Engine_Api::_()->getDbTable('tagusers','sesadvancedactivity')->info('name');
       $select->joinLeft($activtyTableName, $activtyTableName . '.user_id = ' . $memberTableName . '.user_id ', null);
       $select->where($activtyTableName.'.action_id =?',$params['action_id']);
    }
    $currentTime = date('Y-m-d H:i:s');
    if (isset($params['view'])) {
      if ($params['view'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['view'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      } else if ($params['view'] == 1) {
        if ($viewer->getIdentity()) {
          $users = $viewer->membership()->getMembershipsOfIds();
          if ($users)
            $select->where($memberTableName . '.user_id IN (?)', $users);
          else
            $select->where($memberTableName . '.user_id IN (?)', 0);
        }
      }else if ($params['view'] == 3) {
        $networkMembershipTable = Engine_Api::_()->getDbTable('membership', 'network');
        $membershipNetworkUserIds = $networkMembershipTable->getMembershipsOfIds($viewer);
        $networkMembershipTableName = $networkMembershipTable->info('name');
        $select->join($networkMembershipTableName, $memberTableName . ".user_id = " . $networkMembershipTableName . ".user_id  ", null)
                ->where($networkMembershipTableName . ".resource_id  IN (?) ", $membershipNetworkUserIds)->group($memberTableName.'.user_id');
      }
    }
    if(!empty($_POST['friend_id'])){
      $friend = Engine_Api::_()->getItem('user',$_POST['friend_id']);
      $friends = $friend->membership()->getMembershipsOfIds();
      if ($friends)
        $select->where($memberTableName . '.user_id IN (?)', $friends);
      else
        $select->where($memberTableName . '.user_id IN (?)', 0);
    }
    
    //Full Text
    if (isset($params['text']) && $params['text']) {
      $search_text = $params['text'];
      $select->where($memberTableName . ".displayname LIKE '%$search_text%'");
    }

    //Interest Plugin integration
    if(isset($customFields['interest_id']) && !empty($customFields['interest_id'])) {
      $userinterestsTableName = Engine_Api::_()->getDbTable('userinterests','sesinterest')->info('name');
      $select->joinLeft($userinterestsTableName, $userinterestsTableName . '.user_id = ' . $memberTableName . '.user_id ', null)->where($userinterestsTableName.'.interest_id IN (?)', $customFields['interest_id']);
    }

    // if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog')) {
      if (isset($params['order']) && !empty($params['order']) && @$params['actioname'] != 'editormembers') {
        if ($params['order'] == 'featured')
          $select->where($userinfosTableName.'.featured = ?', '1');
        elseif ($params['order'] == 'sponsored')
          $select->where($userinfosTableName.'.sponsored = ?', '1');
        elseif ($params['order'] == 'verified')
          $select->where($userinfosTableName.'.user_verified = ?', '1');
        elseif ($params['order'] == 'view_count DESC')
          $select->order('view_count DESC');
        elseif ($params['order'] == 'like_count DESC')
          $select->order($memberTableName.'.like_count DESC');
        elseif ($params['order'] == 'rating DESC')
          $select->order($userinfosTableName.'.rating DESC');
        elseif ($params['order'] == 'mylike') {
          $likeTableName = 'engine4_core_likes';
          $select->join($likeTableName, "`{$likeTableName}`.`resource_id` = `{$memberTableName}`.`user_id`", null)
          ->where("{$likeTableName}.poster_type = ?", 'user')
          ->where("{$likeTableName}.poster_id = ?", $viewer->getIdentity());
        }
        elseif ($params['order'] == 'myfollow') {
          $followTableName = 'engine4_sesmember_follows';
          $select->join($followTableName, "`{$followTableName}`.`resource_id` = `{$memberTableName}`.`user_id`", null)
          ->where("{$followTableName}.user_id = ?", $viewer->getIdentity());
        }
        elseif ($params['order'] == 'atoz') {
          $select->order($memberTableName .'.displayname ASC');
        }
        elseif ($params['order'] == 'ztoa') {
          $select->order($memberTableName .'.displayname DESC');
        }
        elseif ($params['order'] == 'creation_date DESC')
          $select->order('creation_date DESC');
        if ($params['order'] == 'week') {
          $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
          $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
        } elseif ($params['order'] == 'month') {
          $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
          $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
        }
      }
   // }

    if(isset($params['alphbetorder']) && !empty($params['alphbetorder'])) {
      $alphabet = $params['alphbetorder'];
      $select->where($memberTableName . ".displayname LIKE '$alphabet%'");
    }

    if (isset($params['criteria'])) {
      if ($params['criteria'] == 1)
        $select->where($userinfosTableName . '.featured =?', '1');
      else if ($params['criteria'] == 2)
        $select->where($userinfosTableName . '.sponsored =?', '1');
      else if ($params['criteria'] == 6)
        $select->where($userinfosTableName . '.user_verified =?', '1');
      else if ($params['criteria'] == 3)
        $select->where($userinfosTableName . '.featured = 1 OR ' . $userinfosTableName . '.sponsored = 1');
      else if ($params['criteria'] == 4)
        $select->where($userinfosTableName . '.featured = 0 AND ' . $userinfosTableName . '.sponsored = 0');
    }

    //Start Custom Field Fieltering Work
    $tmp = array();
    foreach ($customFields as $k => $v) {
      if( is_string($v) && empty(trim($v,' ')))
        continue;
      if (null == $v || '' == $v || (is_array($v) && engine_count(array_filter($v)) == 0)) {
        continue;
      } else if (false !== strpos($k, '_field_')) {
        list($null, $field) = explode('_field_', $k);
        $tmp['field_' . $field] = $v;
      } else if (false !== strpos($k, '_alias_')) {
        list($null, $alias) = explode('_alias_', $k);
        $tmp[$alias] = $v;
      } else {
        $tmp[$k] = $v;
      }
    }
    $customFields = $tmp;
    if (!empty($customFields['extra'])) {
      extract($customFields['extra']); // is_online, has_photo, submit
    }
    $searchTable = Engine_Api::_()->fields()->getTable('user', 'search');
    $searchTableName = $searchTable->info('name');
    $select->joinLeft($searchTableName, "`{$searchTableName}`.`item_id` = `{$memberTableName}`.`user_id`", null)
            ->where("{$memberTableName}.search = ?", 1)
            ->where("{$memberTableName}.enabled = ?", 1);

    // Build search part of query
    $searchParts = Engine_Api::_()->fields()->getSearchQuery('user', $customFields);
    foreach ($searchParts as $k => $v) {
      if (strpos($k, 'FIND_IN_SET') !== false) {
        $select->where("{$k}", $v);
        continue;
      }

      $select->where("`{$searchTableName}`.{$k}", $v);

      if (isset($v) && $v != "") {
        $searchDefault = false;
      }
    }
    
    if (isset($customFields['has_photo']) && !empty($customFields['has_photo'])) {
      $select->where($memberTableName . '.photo_id != ?', "0");
    }

    if (isset($customFields['is_vip']) && !empty($customFields['is_vip'])) {
      $select->where($userinfosTableName.'.vip != ?', "0");
    }

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog')) {
      if (isset($params['blog_contributors']) && !empty($params['blog_contributors']) && $params['blog_contributors'] == 'blog-contributors') {
        $blogTableName = Engine_Api::_()->getDbtable('blogs', 'sesblog')->info('name');
        if (isset($params['order']) && !empty($params['order'])) {
          if ($params['order'] == 'mostcontributors') {
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", new Zend_Db_Expr('COUNT(owner_id) as contributors_count'))->group($blogTableName .'.owner_id');
            $select->order('contributors_count DESC');
          } else if($params['order'] == 'creation_date DESC') {
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", new Zend_Db_Expr($blogTableName. '.creation_date as blog_creationdate'));
            $select->order('blog_creationdate ASC')->group($blogTableName .'.owner_id');
          }

          else if($params['order'] == 'like_count DESC') {
            $likeTableName = 'engine4_core_likes';
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", null);
            $select->joinRight($likeTableName, "`{$likeTableName}`.`resource_id` = `{$blogTableName}`.`blog_id`", new Zend_Db_Expr('COUNT(like_id) as blog_likecount'))->where($likeTableName.'.resource_type =?', 'sesblog_blog');
            $select->order('blog_likecount DESC')->group($blogTableName .'.owner_id');
          } else if($params['order'] == 'most commented') {
            $commentTableName = 'engine4_core_comments';
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", null);
            $select->joinRight($commentTableName, "`{$commentTableName}`.`resource_id` = `{$blogTableName}`.`blog_id`", new Zend_Db_Expr('COUNT(comment_id) as blog_commentcount'))->where($commentTableName.'.resource_type =?', 'sesblog_blog');
            $select->order('blog_commentcount DESC')->group($memberTableName .'.user_id');
          } else if($params['order'] == 'most reviewed') {
            $sesblogReviewTableName = 'engine4_sesblog_reviews';
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", null);
            $select->joinRight($sesblogReviewTableName, "`{$sesblogReviewTableName}`.`blog_id` = `{$blogTableName}`.`blog_id`", new Zend_Db_Expr('COUNT(review_id) as blog_reviewcount'));
            $select->order('blog_reviewcount DESC')->group($sesblogReviewTableName .'.owner_id');
          } else {
            $select->joinRight($blogTableName, $blogTableName . ".owner_id = $memberTableName.user_id", new Zend_Db_Expr('COUNT(owner_id) as contributors_count'))->group($blogTableName .'.owner_id');
          }
        }
      }
    }

    if (isset($params['network']) && !empty($params['network'])) {
      $networkTableName = Engine_Api::_()->getDbtable('membership', 'network')->info('name');
      $select->joinRight($networkTableName, $networkTableName . ".user_id = $memberTableName.user_id", null);
      $select->where($networkTableName . '.resource_id = ?', $params['network'])->group($networkTableName .'.user_id');
    }

    if (isset($customFields['is_online']) && !empty($customFields['is_online'])) {
      $select
              ->joinRight("engine4_user_online", "engine4_user_online.user_id = `{$memberTableName}`.user_id", null)
              ->group("engine4_user_online.user_id")
              ->where($memberTableName . '.user_id != ?', "0");
    }

    //Browse Memebers based on Profile Type Page Work
    if (isset($params['profile_type']) && !empty($params['profile_type'])) {
      //$select->joinLeft("engine4_user_fields_values", "engine4_user_fields_values.item_id = `{$memberTableName}`.user_id", null)->where('engine4_user_fields_values.field_id = ?', "1")->where('engine4_user_fields_values.value = ?', $params['profile_type'])->where('engine4_user_fields_values.item_id IS NOT NULL');
    }
    //Browse Memebers based on Profile Type Page Work

    if (isset($customFields['profile_type']) && !empty($customFields['profile_type'])) {
      $select
              ->joinLeft("engine4_user_fields_values", "engine4_user_fields_values.item_id = `{$memberTableName}`.user_id", null)
              ->where('engine4_user_fields_values.field_id = ?', "1")->where('engine4_user_fields_values.value = ?', $customFields['profile_type'])->where('engine4_user_fields_values.item_id IS NOT NULL');
    }

    if (isset($params['info'])) {
      switch ($params['info']) {
        case 'most_viewed':
          $select->order('view_count DESC');
          break;
        case 'most_liked':
          $select->order($memberTableName.'.like_count DESC');
          break;
        case "view_count":
          $select->order($memberTableName . '.view_count DESC');
          break;
        case "most_rated":
          $select->order($userinfosTableName . '.rating DESC');
          break;
        case 'random':
          $select->order('Rand()');
          break;
        case "sponsored" :
          $select->where($userinfosTableName . '.sponsored' . ' = 1')
                  ->order($memberTableName . '.user_id DESC');
          break;
        case "verified" :
          $select->where($userinfosTableName . '.user_verified' . ' = 1')
                  ->order($memberTableName . '.user_id DESC');
          break;
        case "vip" :
          $select->where($userinfosTableName . '.vip' . ' = 1')
                  ->order($memberTableName . '.vip DESC');
          break;
        case "featured" :
          $select->where($userinfosTableName . '.featured' . ' = 1')
                  ->order($memberTableName . '.user_id DESC');
          break;
        case "creation_date":
          $select->order($memberTableName . '.creation_date DESC');
          break;
        case "recently_created":
          $select->order($memberTableName . '.creation_date DESC');
          break;
        case "modified_date":
          $select->order($memberTableName . '.modified_date DESC');
          break;
      }
    }

    if (isset($params['actioname']) && $params['actioname'] == 'editormembers') {
      $select->where($userinfosTableName. '.adminpicks =?', 1)->order($userinfosTableName.'.order ASC');
    }

    if (isset($params['widgetName']) && $params['widgetName'] == 'oftheday') {
      $select->where($userinfosTableName . '.offtheday =?', 1)
              ->where($userinfosTableName . '.starttime <= DATE(NOW())')
              ->where($userinfosTableName . '.endtime >= DATE(NOW())')
              ->limit(1)
              ->order('RAND()');
    }
    if (!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog') && @$params['actioname'] != 'editormembers') {
      $select->order($memberTableName . '.creation_date DESC');
    }
    if (isset($params['nearest']) && $params['nearest']) {
      $select = $select->where($memberTableName . '.user_id NOT IN (?)', Engine_Api::_()->user()->getViewer()->getIdentity());
    }

    if (!empty($params['alphabet']))
      $select->where($memberTableName . '.displayname LIKE ?', "{$params['alphabet']}%");

    //member level exclude
    if(isset($params['memberlevels']) && !empty($params['memberlevels'])) {
      $select->where($memberTableName.'.level_id NOT IN(?)', $params['memberlevels']);
    }

    if(!empty($params["fromBrowse"])){
        $user = Engine_Api::_()->user()->getViewer();
        if($user->getIdentity() && $user->level_id != 1)
            $select->where($memberTableName.'.level_id = ?',$user->level_id);
    }


    if (isset($params['limit']) && !empty($params['limit']))
      $select->limit($params['limit']);
    if (!empty($params['limit_data']))
      $select->limit($params['limit_data']);
    if (isset($params['fetchAll']))
      return $table->fetchAll($select);
    else
      return $select;
  }
  public function getUserAlbum() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $albumTable = Engine_Api::_()->getItemtable('album');
    $tableName = $albumTable->info('name');
    $select = $albumTable->select()
            ->from($tableName)
            ->where('owner_id =?', $viewer->getIdentity())
            ->order('type DESC');
    return Zend_Paginator::factory($select);
  }

  public function getPhotoSelect($params = array()) {

    $photoTable = Engine_Api::_()->getItemTable('photo');
    $select = $photoTable->select();

    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum'))
      $instanceType = 'Sesalbum_Model_Album';
    else
      $instanceType = 'Album_Model_Album';

    if (!empty($params['album']) && $params['album'] instanceof $instanceType) {
      $select->where('album_id = ?', $params['album']->getIdentity());
    } else if (!empty($params['album_id']) && is_numeric($params['album_id'])) {
      $select->where('album_id = ?', $params['album_id']);
    }
    if (!isset($params['order'])) {
      $select->order('order ASC');
    } else if (is_string($params['order'])) {
      $select->order($params['order']);
    }

    //fecth all
    $photos = $photoTable->fetchAll($select);
    //store data in
    $tempArray = array();
    $tempStorePhotoIds = '';
    $viewer = Engine_Api::_()->user()->getViewer();
    $album_enable_check_privacy = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.check.privacy', 1);
    if ($album_enable_check_privacy) {
      //loop over all albums once
      foreach ($photos as $photo) {
        $album = $photo->getParent();
        if (!$album)
          continue;
        //check authorization album
        if ($album->authorization()->isAllowed($viewer, 'view')) {
          $tempArray[] = $photo;
          $tempStorePhotoIds .= $photo->getIdentity() . ',';
        }
        if (empty($params['pagNator'])) {
          if (isset($params['limit_data']) && engine_count($tempArray) >= $params['limit_data']) {
            break;
          }
        }
      }
      if (empty($params['pagNator'])) {
        $tempStorePhotoIds = trim($tempStorePhotoIds, ',');
        if ($tempStorePhotoIds) {
          $select = $select->where('photo_id IN (' . $tempStorePhotoIds . ')');
        } else {
          $select = $select->where('photo_id IN (0)');
        }
        if (isset($params['limit_data'])) {
          return $photoTable->fetchAll($select);
        }
        else
          return $photoTable->fetchAll($select);
      }
      else
        return Zend_Paginator::factory($tempArray);
    }
    if (empty($params['pagNator'])) {
      if (isset($params['limit_data'])) {
        $select->limit($params['limit_data']);
        return $photoTable->fetchAll($select);
      }
      else
        return $photoTable->fetchAll($select);
    }
    else
      return Zend_Paginator::factory($select);
  }

  public function getFeaturedPhotos($objectId) {
    $table = Engine_Api::_()->getDbTable('featuredphotos', 'sesmember');
    $select = $table->select()->from($table->info('name'), 'photo_id')->where('user_id =?', $objectId);
    //fecth all
    return $table->fetchAll($select);
  }

  public function followers($params = array()) {
  
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');
    
    $tablenameFollow = Engine_Api::_()->getDbTable('follows', 'sesmember')->info('name');
    $select = $table->select()
                  ->from($memberTableName, array('user_id', 'photo_id', 'displayname', 'email'))
                  ->setIntegrityCheck(false)
                  ->joinLeft($tablenameFollow, $tablenameFollow . '.resource_id = ' . $memberTableName . '.user_id AND ' . $tablenameFollow . '.user_id =  ' . $params['user_id'], null)
                  ->where('follow_id IS NOT NULL')
                  ->where('resource_id !=?', $params['user_id'])
                  ->where($memberTableName . '.user_id IS NOT NULL');
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
        $select = $select->where('user_approved =?', 1);
    }
    return Zend_Paginator::factory($select);
  }

  public function following($params = array()) {
    $table = Engine_Api::_()->getItemTable('user');
    $memberTableName = $table->info('name');
    $tablenameFollow = Engine_Api::_()->getDbTable('follows', 'sesmember')->info('name');
    $select = $table->select()
                ->from($memberTableName, array('user_id', 'photo_id', 'displayname', 'email'))
                ->setIntegrityCheck(false)
                ->joinLeft($tablenameFollow, $tablenameFollow . '.user_id = ' . $memberTableName . '.user_id AND ' . $tablenameFollow . '.resource_id =  ' . $params['user_id'], null)
                ->where('follow_id IS NOT NULL')
                ->where($tablenameFollow . '.user_id !=?', $params['user_id'])
                ->where($memberTableName . '.user_id IS NOT NULL');
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {
        $select = $select->where('resource_approved =?', 1);
    }
    return Zend_Paginator::factory($select);
  }
}
