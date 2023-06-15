<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Videos.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Model_DbTable_Videos extends Engine_Db_Table {

  protected $_rowClass = "Sesvideo_Model_Video";
  protected $_name = 'sesvideo_videos';

  public function getWatchLaterStatus($video_id){
    $viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();
    $tableName = $this->info('name');
    $select = $this->select()
            ->from($tableName)
            ->where($tableName . '.video_id = ?', $video_id);
    $watchLaterTable = Engine_Api::_()->getDbTable('watchlaters', 'sesvideo')->info('name');
    $select = $select->setIntegrityCheck(false);
    $select = $select->joinLeft($watchLaterTable, '(' . $watchLaterTable . '.video_id = ' . $tableName . '.video_id AND ' . $watchLaterTable . '.owner_id = ' . $user_id . ')', array('watchlater_id'));
    $select->where('watchlater_id != ?', '');

    return $this->fetchAll($select);
  }
	protected function getAllowAdultContentView(){
		//return Engine_Api::_()->getApi('core', 'sesbasic')->checkAdultContent(array('module'=>'video'));
		return true;
	}
  public function getVideo($params = array(), $paginator = true) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();
    $tmTable = Engine_Api::_()->getDbtable('TagMaps', 'core');
    $tmName = $tmTable->info('name');
    $tableName = $this->info('name');
    $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
    $tableLocationName = $tableLocation->info('name');
    $select = $this->select()
            ->from($tableName)
            ->where($tableName . '.video_id != ?', '');

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.watchlater', 1)) {
      $watchLaterTable = Engine_Api::_()->getDbTable('watchlaters', 'sesvideo')->info('name');
      $select = $select->setIntegrityCheck(false);
      $select = $select->joinLeft($watchLaterTable, '(' . $watchLaterTable . '.video_id = ' . $tableName . '.video_id AND ' . $watchLaterTable . '.owner_id = ' . $user_id . ')', array('watchlater_id'));
    }

    //Location Based search
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('seslocation') && Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocationenable', 1) && !empty($_COOKIE['sesbasic_location_data']) && $params['widgetName'] != 'tabbed-widget-videomanage') {
      $params['location'] = $_COOKIE['sesbasic_location_data'];
      $params['lat'] = $_COOKIE['sesbasic_location_lat'];
      $params['lng'] = $_COOKIE['sesbasic_location_lng'];
      $params['miles'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation.searchmiles', 50);
    }


    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
      if (isset($params['lat']) && isset($params['miles']) && $params['miles'] != 0 && isset($params['lng']) && $params['lat'] != '' && $params['lng'] != '' && ((isset($params['location']) && $params['location'] != ''))) {

        $origLat = $lat = $params['lat'];
        $origLon = $long = $params['lng'];
        $select = $select->setIntegrityCheck(false);
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.search.type', 1) == 1) {
          $searchType = 3956;
        } else
          $searchType = 6371;
        $dist = $params['miles']; // This is the maximum distance (in miles) away from $origLat, $origLon in which to search

          $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $tableName . '.video_id AND ' . $tableLocationName . '.resource_type = "sesvideo_video" ', array('lat', 'lng', 'distance' => new Zend_Db_Expr($searchType." * 2 * ASIN(SQRT( POWER(SIN(($lat - lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS(lat * pi()/180) * POWER(SIN(($long - lng) * pi()/180 / 2), 2) ))")));

        $rectLong1 = $long - $dist/abs(cos(deg2rad($lat))*69);
        $rectLong2 = $long + $dist/abs(cos(deg2rad($lat))*69);
        $rectLat1 = $lat-($dist/69);
        $rectLat2 = $lat+($dist/69);

        $select->where($tableLocationName . ".lng between $rectLong1 AND $rectLong2  and " . $tableLocationName . ".lat between $rectLat1 AND $rectLat2");
        $select->order('distance');
        $select->having("distance < $dist");
      } else {
        $select = $select->setIntegrityCheck(false);
        $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $tableName . '.video_id AND ' . $tableLocationName . '.resource_type = "sesvideo_video" ', array('lat', 'lng'));
      }
    } elseif(isset($params['lat']) && isset($params['lng']) && $params['lat'] != '' && $params['lng'] != '' && ((isset($params['location']) && $params['location'] != ''))) {
      $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $tableName . '.video_id AND ' . $tableLocationName . '.resource_type = "sesvideo_video" ', array('lat', 'lng'));
    } 
    
		if(empty($params['user_id']) && method_exists('Core_Model_Item_DbTable_Abstract','getItemsSelect') ) {
      $select = $this->getItemsSelect($params, $select);
    }

    if(!empty($params['location']) && !Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
      $select->where('`' . $tableName . '`.`location` LIKE ?', '%' . $params['location'] . '%');
    }

    if (!empty($params['city'])) {
      $select->where('`' . $tableLocationName . '`.`city` LIKE ?', '%' . $params['city'] . '%');
    }
    if (!empty($params['state'])) {
      $select->where('`' . $tableLocationName . '`.`state` LIKE ?', '%' . $params['state'] . '%');
    }
    if (!empty($params['country'])) {
      $select->where('`' . $tableLocationName . '`.`country` LIKE ?', '%' . $params['country'] . '%');
    }
    if (!empty($params['zip'])) {
      $select->where('`' . $tableLocationName . '`.`zip` LIKE ?', '%' . $params['zip'] . '%');
    }
    
    if (isset($params['widgetName']) && $params['widgetName'] == 'oftheday') {
      $select->where($tableName . '.offtheday =?', 1)
              ->where($tableName . '.starttime <= DATE(NOW())')
              ->where($tableName . '.endtime >= DATE(NOW())')
              ->order('RAND()');
    }
    if(!empty($_REQUEST["from_tickvideo"])){
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('tickvideo'))
       $select->where($tableName.'.is_tickvideo =?',1);
    }
    if (isset($params['widgetName'])) {
      if ($params['widgetName'] == 'oftheday') {
        $select->where($tableName . '.offtheday =?', 1)
                ->where($tableName . '.starttime <= DATE(NOW())')
                ->where($tableName . '.endtime >= DATE(NOW())')
                ->order('RAND()');
      }

      if ($params['widgetName'] == 'artistViewPage') {
        $select->where("artists LIKE ? ", '%' . $params['artist'] . '%')
                ->order('creation_date DESC');
      }
    }

    if (!empty($params['tag'])) {
      $select
              ->joinLeft($tmName, "$tmName.resource_id = $tableName.video_id", NULL)
              ->where($tmName . '.resource_type = ?', 'video')
              ->where($tmName . '.tag_id = ?', $params['tag']);
    }
    if (!empty($params['sameTag'])) {
      $select->joinLeft($tmName, "$tmName.resource_id=$tableName.video_id", null)
              ->where($tmName .'resource_type = ?', 'video')
              ->distinct(true)
              ->where($tmName .'resource_id != ?', $params['sameTagresource_id'])
              ->where($tmName .'tag_id IN(?)', $params['sameTagTag_id']);
    }
    if (!empty($params['video_id']))
      $select = $select->where($tableName . '.video_id =?', $params['video_id']);
    if (!empty($params['not_video_id']))
      $select = $select->where($tableName . '.video_id != ?', $params['not_video_id']);
		 if (!empty($params['notin_video_id']))
      $select = $select->where($tableName . '.video_id NOT IN (?)', $params['notin_video_id']);
    if (!empty($params['popularCol']))
      $select = $select->order($params['popularCol'] . ' DESC');

    if (!empty($params['user_id']) && $params['user_id'] != '')
      $select = $select->where($tableName . '.owner_id =?', $params['user_id']);

    if (!empty($params['search'])) {
      if (!empty($params['fixedData']) && $params['fixedData'] != '')
        $select = $select->where($tableName . '.' . $params['fixedData'] . ' =?', 1);
    }/*for ultimate Menu plugin */
	if (!empty($params['order'])){
		$currentTime = date('Y-m-d H:i:s');
		if ($params['order'] == 'week') {
			$endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
			$select->where("DATE(".$tableName.".creation_date) between ('$endTime') and ('$currentTime')");
		 } elseif ($params['order'] == 'month') {
			$endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
			$select->where("DATE(".$tableName.".creation_date) between ('$endTime') and ('$currentTime')");
		 }
		 else 
			 $select = $select->order($params['order'] . ' DESC'); 
	}
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {
      if(empty($params['price']) && $params['price'] != '')
        $select->where($tableName . '.price =?', '0.00');
      elseif(!empty($params['price']) && $params['price'] != '')
        $select->where($tableName . '.price <>?', '0.00');
    }

    //don't show other module videos
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.other.modulevideos', 1) && empty($params['parent_type'])) {
			$select->where($tableName . '.parent_type IS NULL || ' . $tableName . '.parent_type = ""');
    } else if (!empty($params['parent_type']) && !empty($params['parent_id'])) {
      $select->where($tableName . '.parent_type =?', $params['parent_type'])
              ->where($tableName . '.parent_id =?', $params['parent_id']);
    } else if(!empty($params['parent_type'])) {
      $select->where($tableName . '.parent_type =?', $params['parent_type']);
    }
    //don't show other module videos

    if (!empty($params['search']))
      $select = $select->where($tableName . '.search =?', 1);

    if (isset($params['show']) && $params['show'] == 2 && $viewer->getIdentity()) {
      $users = $viewer->membership()->getMembershipsOfIds();
      if ($users)
        $select->where($tableName . '.owner_id IN (?)', $users);
      else
        $select->where($tableName . '.owner_id IN (?)', 0);
    }

    if (!empty($params['alphabet']) && $params['alphabet'] != 'all')
      $select->where($tableName . ".title LIKE ?", $params['alphabet'] . '%');

    if (isset($params['criteria'])) {
      if ($params['criteria'] == 1)
        $select->where($tableName . '.is_featured =?', '1');
      else if ($params['criteria'] == 2)
        $select->where($tableName . '.is_sponsored =?', '1');
      else if ($params['criteria'] == 6)
        $select->where($tableName . '.is_hot =?', '1');
      else if ($params['criteria'] == 3)
        $select->where($tableName . '.is_featured = 1 OR ' . $tableName . '.is_sponsored = 1');
      else if ($params['criteria'] == 4)
        $select->where($tableName . '.is_featured = 0 AND ' . $tableName . '.is_sponsored = 0');
    }

    if (isset($params['criteria'])) {
      switch ($params['info']) {
        case 'recently_created':
          $select->order('creation_date DESC');
          break;
        case 'most_viewed':
          $select->order('view_count DESC');
          break;
        case 'most_liked':
          $select->order('like_count DESC');
          break;
        case 'most_rated':
          $select->order('rating DESC');
          break;
        case 'most_favourite':
          $select->order('favourite_count DESC');
          break;
        case 'most_commented':
          $select->order('comment_count DESC');
          break;
        case 'random':
          $select->order('Rand()');
          break;
      }
    }

		if(empty($params['manageVideo'])){
			$select->where($tableName.'.status = ?',1);
			$select->where($tableName.'.approve = ?',1);
			if(!$this->getAllowAdultContentView()){
					$select->where($tableName.'.adult = ?',0);
			}
		}
    if (!empty($params['is_featured']))
      $select = $select->where($tableName . '.is_featured =?', 1);

    if (!empty($params['is_sponsored']))
      $select = $select->where($tableName . '.is_sponsored =?', 1);

    if (!empty($params['is_hot']))
      $select = $select->where($tableName . '.is_hot =?', 1);

    if (!empty($params['status']))
      $select = $select->where($tableName . '.status =?', 1);

    if (!empty($params['category_id']))
      $select = $select->where($tableName . '.category_id =?', $params['category_id']);

    if (!empty($params['subcat_id']))
      $select = $select->where($tableName . '.subcat_id =?', $params['subcat_id']);

    if (!empty($params['subsubcat_id']))
      $select = $select->where($tableName . '.subsubcat_id =?', $params['subsubcat_id']);

    if (!empty($params['text']))
      $select = $select->where($tableName . '.title LIKE "%' . $params['text'] . '%"');
		$select = $select->order('video_id DESC');
    if (isset($params['limit_data']))
      $select = $select->limit($params['limit_data']);
    if ($paginator)
      return Zend_Paginator::factory($select);
    else
      return $this->fetchAll($select);
  }
  
  public function getItemsSelect($params, $select = null) {
  
    if( $select == null ) {
			$select = $this->select();
    }
    
    $tableName = $this->info('name');

    $registeredPrivacy = array('everyone', 'registered');
    $viewer = Engine_Api::_()->user()->getViewer();
    if($viewer->isAdmin()) return $select;
    if($viewer->getIdentity() && !engine_in_array($viewer->level_id, $this->_excludedLevels) ) {
        $viewerId = $viewer->getIdentity();
        $netMembershipTable = Engine_Api::_()->getDbtable('membership', 'network');
        $viewerNetwork = $netMembershipTable->getMembershipsOfIds($viewer);
//         if( !empty($viewerNetwork) ) {
//             array_push($registeredPrivacy,'owner_network');
//         }
        $friendsIds = $viewer->membership()->getMembersIds();
        $friendsOfFriendsIds = $friendsIds;
        foreach( $friendsIds as $friendId ) {
            $friend = Engine_Api::_()->getItem('user', $friendId);
            $friendMembersIds = $friend->membership()->getMembersIds();
            $friendsOfFriendsIds = array_merge($friendsOfFriendsIds, $friendMembersIds);
        }
    }
    if( !$viewer->getIdentity() ) {
        $select->where("view_privacy = ? OR view_privacy IS NULL", 'everyone');
    } elseif( !engine_in_array($viewer->level_id, $this->_excludedLevels) ) {

			$select->where("
				CASE 
					WHEN $tableName.owner_id = {$viewer->getIdentity()} THEN true
					WHEN $tableName.view_privacy IN (".sprintf("'%s'", implode("','", $registeredPrivacy ) ).") OR view_privacy IS NULL THEN TRUE
					WHEN $tableName.view_privacy = 'owner_member' AND ".count($friendsIds)." > 0 THEN $tableName.owner_id IN (".sprintf("'%s'", implode("','", $friendsIds ) ).")
					WHEN $tableName.view_privacy = 'owner_member' THEN false
					WHEN $tableName.view_privacy = 'owner_member_member' THEN $tableName.owner_id IN (".sprintf("'%s'", implode("','", $friendsOfFriendsIds ) ).")
					WHEN $tableName.view_privacy = 'owner_network' THEN $tableName.owner_id IN (".sprintf("'%s'", implode("','", $friendsIds ) ).") AND (select count(resource_id) from engine4_network_membership where user_id = $tableName.owner_id AND resource_id IN (".sprintf("'%s'", implode("','", $viewerNetwork ) ).")) > 0 
					ELSE false 
				END
			");

			$subquery = $select->getPart(Zend_Db_Select::WHERE);
			$select ->reset(Zend_Db_Select::WHERE);
			$select ->where(implode(' ',$subquery));
    }
    return $select;
  }

  public function peopleAlsoLiked($id = 0) {
    $likesTable = Engine_Api::_()->getDbtable('likes', 'core');
    $likesTableName = $likesTable->info('name');
		$tableName = $this->info('name');
		$viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();
    $select = $this->select()
            ->distinct(true)
            ->from($this->info('name'))
            ->joinLeft($likesTableName, $likesTableName . '.resource_id=video_id', null)
            ->joinLeft($likesTableName . ' as l2', $likesTableName . '.poster_id=l2.poster_id', null)
            ->where($likesTableName . '.poster_type = ?', 'user')
            ->where('l2.poster_type = ?', 'user')
            ->where($likesTableName . '.resource_type = ?', 'video')
            ->where('l2.resource_type = ?', 'video')
            ->where($likesTableName . '.resource_id != ?', $id)
            ->where('l2.resource_id = ?', $id)
            ->where('search = ?', true)
            ->where($tableName.'.video_id != ?', $id);

		if(!$this->getAllowAdultContentView()){
      $select->where($tableName.'.adult = ?',0);
    }

		if (Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.watchlater', 1)) {
      $watchLaterTable = Engine_Api::_()->getDbTable('watchlaters', 'sesvideo')->info('name');
      $select = $select->setIntegrityCheck(false);
      $select = $select->joinLeft($watchLaterTable, '(' . $watchLaterTable . '.video_id = ' . $tableName . '.video_id AND ' . $watchLaterTable . '.owner_id = ' . $user_id . ')', array('watchlater_id'));
    }
    return Zend_Paginator::factory($select);
  }

  public function videoLightBox($video = null, $nextPreviousCondition, $getallvideos = false, $paginator = false,$type = '',$item_id = '') {

    //getSEVersion for lower version of SE
    $getmodule = Engine_Api::_()->getDbTable('modules', 'core')->getModule('core');
    if (!empty($getmodule->version) && version_compare($getmodule->version, '4.8.6') < 0) {
      $toArray = true;
    } else
      $toArray = false;
    $tableNameVideo = $this->info('name');
    $select = $this->select()
            ->from($tableNameVideo);
		$select->where($tableNameVideo.'.status =?',1);

		switch ($type){
			case 'sesvideo_chanel':
				$getChanelVideoTableName = Engine_Api::_()->getDbTable('chanelvideos', 'sesvideo')->info('name');
				$select->setIntegrityCheck(false);
				$select->joinLeft($getChanelVideoTableName, $getChanelVideoTableName . '.video_id = ' . $tableNameVideo . '.video_id', null)
								->where("chanel_id = ".$item_id);
				if(!$getallvideos)
								$select->where($getChanelVideoTableName.'.video_id '.$nextPreviousCondition.' ?',$video->video_id);
			if ($nextPreviousCondition == '>')
				$select->order("$getChanelVideoTableName.video_id ASC");
			else if ($nextPreviousCondition == '<')
				$select->order("$getChanelVideoTableName.video_id DESC");
			else
				$select->order("$getChanelVideoTableName.video_id ASC");
			break;
			case 'sesvideo_playlist':
				$getPlaylistVideoTableName = Engine_Api::_()->getDbTable('playlistvideos', 'sesvideo')->info('name');
				$select->setIntegrityCheck(false);
				$select->joinLeft($getPlaylistVideoTableName, $getPlaylistVideoTableName . '.file_id = ' . $tableNameVideo . '.video_id', null)
								->where("playlist_id = ".$item_id);
				if(!$getallvideos)
								$select->where($getPlaylistVideoTableName.'.file_id '.$nextPreviousCondition.' ?',$video->video_id);
			if ($nextPreviousCondition == '>')
				$select->order("$getPlaylistVideoTableName.file_id ASC");
			else if ($nextPreviousCondition == '<')
				$select->order("$getPlaylistVideoTableName.file_id DESC");
			else
				$select->order("$getPlaylistVideoTableName.file_id ASC");
			break;
			default:
			$select->where("$tableNameVideo.owner_id =  ?", $video->owner_id);
			break;
		}
		if(!$this->getAllowAdultContentView()){
			$select->where($tableNameVideo.'.adult = ?',0);
		}
		// custom query as per status assign
    if ($getallvideos) {
      $select->order('creation_date DESC');
      return Zend_Paginator::factory($select);
    }
    $select->limit('1');
		if($type == ''){
			if ($nextPreviousCondition == '<'){
				$select->order('video_id ASC');
				 $select->where("$tableNameVideo.video_id > $video->video_id");
			}else{
				$select->order('video_id DESC');
				 $select->where("$tableNameVideo.video_id < $video->video_id");
			}
		}
    $select->order('creation_date DESC');
    if ($paginator)
      return Zend_Paginator::factory($select);
    if ($toArray) {
      $video = $this->fetchAll($select);
      if (!empty($video))
        $video = $video->toArray();
      else
        $video = '';
    }else {
      $video = $this->fetchRow($select);
    }
    return $video;
  }

  public function getFavourite($params = array()){
		$tableFav = Engine_Api::_()->getDbtable('favourites', 'sesvideo');
		$tableFav = $tableFav->info('name');
		$select = $this->select()
							->from($this->info('name'))
							->where('video_id = ?',$params['resource_id'])
							->setIntegrityCheck(false)
							->where('resource_type =?',$params['type'])
							->order('favourite_id DESC')
							->joinLeft($tableFav, $tableFav . '.resource_id=' . $this->info('name') . '.video_id',array('user_id'));

		if(!$this->getAllowAdultContentView()){
			$select->where($this->info('name').'.adult = ?',0);
		}
    return  Zend_Paginator::factory($select);
	}
  public function countVideos() {
    $select = $this->select()
            ->from($this->info('name'), array('*'));
		if(!$this->getAllowAdultContentView()){
			$select->where($this->info('name').'.adult = ?',0);
		}

    return Zend_Paginator::factory($select);
  }
}
