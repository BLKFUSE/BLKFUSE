<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Sescommunityads.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Sescommunityads extends Engine_Db_Table {
  protected $_rowClass = 'Sescommunityads_Model_Sescommunityad';
  protected $_member;
  protected $_userLocation;
  protected $_name = "sescommunityads_ads";
  public function getManageAds($params = array()){
    $select = $this->select()->where('campaign_id =?',$params['campaign_id'])->where('is_deleted =?',0);
    return Zend_Paginator::factory($select);
  }
  public function getAds($params = array()){

      $viewer = Engine_Api::_()->user()->getViewer();
      $tableName = $this->info('name');
      $transactionTableName = Engine_Api::_()->getDbTable('transactions','sescommunityads')->info('name');
      $campaignTableName = Engine_Api::_()->getDbTable('campaigns','sescommunityads')->info('name');
      $targetadsTableName = Engine_Api::_()->getDbTable('targetads','sescommunityads')->info('name');
      $packageTableName = Engine_Api::_()->getDbTable('packages','sescommunityads')->info('name');
      //select featured sponsored
      $featuredCase = 'CASE WHEN '.$tableName.'.featured = 1 AND featured_date IS NULL THEN 1
                     WHEN '.$tableName.'.featured = 1 AND featured_date IS NOT NULL AND featured_date > NOW() THEN 1
                     ELSE 0 END';
      $sponsoredCase = 'CASE WHEN '.$tableName.'.sponsored = 1 AND sponsored_date IS NULL THEN 1
                     WHEN '.$tableName.'.sponsored = 1 AND sponsored_date IS NOT NULL AND sponsored_date > NOW() THEN 1
                     ELSE 0 END';
      $select = $this->select()->from($tableName,array('*','is_featured'=>new Zend_Db_Expr($featuredCase),'is_sponsored'=>new Zend_Db_Expr($sponsoredCase)));
      $select->setIntegrityCheck(false)
             ->joinLeft($transactionTableName,$transactionTableName.'.transaction_id = '.$tableName.'.transaction_id',null)
             ->joinLeft($campaignTableName,$campaignTableName.'.campaign_id = '.$tableName.'.campaign_id',array('title as campaign_name'))
             ->joinLeft($targetadsTableName,$targetadsTableName.'.sescommunityad_id = '.$tableName.'.sescommunityad_id',null)
             ->joinLeft($packageTableName,$packageTableName.'.package_id = '.$tableName.'.package_id',null);
      if(!empty($params['communityadsIds'])){
        $select->where($tableName.'.sescommunityad_id NOT IN (?)',explode(',',trim($params['communityadsIds'],',')));
      }
      if(!empty($params['creation_date']))
        $select->where($tableName.'.creation_date LIKE "%'.$params['creation_date'].'%"');
      //admin search fields
      if(!empty($params['name'])){
        $select->where($tableName.'.title =?',$params['name']);
      }
      if(!empty($params['type'])){
        if($params['type'] == "promote_website_cnt")
          $select->where($tableName.'.type =?','promote_website_cnt');
        if($params['type'] == "boost_post_cnt")
          $select->where($tableName.'.type =?','boost_post_cnt');
        if($params['type'] == "promote_content_cnt")
          $select->where($tableName.'.type =?','promote_content_cnt');
        if($params['type'] == "promote_page")
          $select->where($tableName.'.resources_type =?','sespage_page');
      }
      if(isset($params['is_approved']) && $params['is_approved'] != "")
        $select->where('is_approved =?',$params['is_approved']);
      if(!empty($params['campaign'])){
        $select->where($campaignTableName.'.title LIKE "%'.$params['name'].'%"');
      }
      if(!empty($params['package_id']))
        $select->where($tableName.'.package_id =?',$params['package_id']);

      if(empty($params['is_deleted'])){
        $select->where('is_deleted =?',0);
      }
      //payment plan
       //get default package id
      $packageId = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getDefaultPackage();
      
      $packageId = $packageId ? $packageId :  0;

      //select from existing pakage

      $select->joinLeft('engine4_sescommunityads_orderspackages','engine4_sescommunityads_orderspackages.orderspackage_id ='.$tableName.'.existing_package_order',null);
      if((empty($params['is_admin']) && empty($params['is_manage'])) || (!empty($params['filter']) && $params['filter'] == 2 /*Running ads condition*/)){
        $case = "CASE
          WHEN $packageTableName.price < 1 THEN TRUE
          WHEN $tableName.existing_package_order != 0 AND (engine4_sescommunityads_orderspackages.expiration_date IS NOT NULL && engine4_sescommunityads_orderspackages.expiration_date != '') THEN engine4_sescommunityads_orderspackages.expiration_date >=  '".date('Y-m-d H:i:s')."' AND engine4_sescommunityads_orderspackages.state = 'active'
          WHEN $tableName.existing_package_order != 0 AND engine4_sescommunityads_orderspackages.expiration_date IS NULL AND $tableName.package_id = $packageId THEN true
          WHEN $transactionTableName.expiration_date IS NOT NULL && $transactionTableName.expiration_date != '' THEN $transactionTableName.expiration_date >=  '".date('Y-m-d H:i:s')."' AND $transactionTableName.state = 'active'
          WHEN ($transactionTableName.expiration_date IS NULL || $transactionTableName.expiration_date = '') AND $tableName.package_id = $packageId THEN true
          ELSE true END";
        $select->where($case);
        //click limit search
        $clickLimitCases = "CASE
          WHEN ad_type = 'perclick' AND ad_limit = '-1' THEN true
          WHEN ad_type = 'perclick' AND ad_limit > 0 AND ad_limit > $tableName.click_count THEN true
          WHEN ad_type = 'perview' AND ad_limit = '-1' THEN true
          WHEN ad_type = 'perview' AND ad_limit > 0 AND ad_limit > $tableName.views_count THEN true
          WHEN ad_type = 'perday' AND ad_limit = '-1' THEN true
          WHEN ad_type = 'perday' AND (ad_expiration_date IS NULL || ad_expiration_date = '') THEN true
          WHEN ad_type = 'perday' AND ad_expiration_date > '".date('Y-m-d H:i:s')."' THEN true
          ELSE true END
        ";
        $select->where($clickLimitCases);
      }
      if(!empty($params['filter'])){
         //Paused
         if($params['filter'] == 3){
           $select->where('paused =?',1);
         }else //Completed
         if($params['filter'] == 4){
           $case = "CASE
               WHEN $tableName.existing_package_order != 0 AND engine4_sescommunityads_orderspackages.expiration_date IS NOT NULL && engine4_sescommunityads_orderspackages.expiration_date != '' THEN engine4_sescommunityads_orderspackages.expiration_date <=  '".date('Y-m-d H:i:s')."' || engine4_sescommunityads_orderspackages.state != 'active'
               WHEN $tableName.existing_package_order != 0 AND (engine4_sescommunityads_orderspackages.expiration_date IS NULL || engine4_sescommunityads_orderspackages.expiration_date = '') AND $tableName.package_id = $packageId THEN false
              WHEN $transactionTableName.expiration_date IS NOT NULL && $transactionTableName.expiration_date != '' THEN $transactionTableName.expiration_date <=  '".date('Y-m-d H:i:s')."' || $transactionTableName.state != 'active'
              WHEN ($transactionTableName.expiration_date IS NULL || $transactionTableName.expiration_date = '') AND $tableName.package_id = $packageId THEN false
              ELSE true END";
            $select->where($case);
            //click limit search
            $clickLimitCases = "CASE
              WHEN ad_type = 'perclick' AND ad_limit = '-1' THEN false
              WHEN ad_type = 'perclick' AND ad_limit > 0 AND ad_limit > $tableName.click_count THEN false
              WHEN ad_type = 'perview' AND ad_limit = '-1' THEN false
              WHEN ad_type = 'perview' AND ad_limit > 0  AND ad_limit > $tableName.views_count THEN false
              WHEN ad_type = 'perday' AND ad_limit = '-1' THEN false
              WHEN ad_type = 'perday' AND (ad_expiration_date IS NULL || ad_expiration_date = '')  THEN false
              WHEN ad_type = 'perday' AND ad_expiration_date > '".date('Y-m-d H:i:s')."' THEN false
              ELSE true END
            ";
            $select->where($clickLimitCases);
         }else //Deleted
         if($params['filter'] == 5){
           $select->where('is_deleted =?',1);
         }else //Declined
         if($params['filter'] == 6){
           $select->where('is_approved =?',0);
           $select->where('approved_date IS NULL');
         }
      }
      if(!empty($params['is_admin'])){
        $select->order((!empty($_GET['order']) ? $_GET['order'] : 'sescommunityad_id' ) . ' ' . (!empty($_GET['order_direction']) ? $_GET['order_direction'] : 'DESC' ));
      }
      if(empty($params['is_admin']) && empty($params['is_manage'])){
        $select->where('draft =?',1);
        $select->where('paused =?',0);
      }
      //hidden post
      if(empty($params['is_admin']) && empty($params['is_manage']))
      $select->where($tableName.'.sescommunityad_id NOT IN (SELECT case when item_id IS NULL THEN 0 ELSE item_id END FROM engine4_sescommunityads_reports WHERE user_id = '.$viewer->getIdentity().' || (ip = "'.$_SERVER['REMOTE_ADDR'].'" && (user_id IS NULL || user_id = "")))');

      if(!empty($params['communityAdsDisplay'])){
        if($params['communityAdsDisplay'] == 1){
					$select->where($tableName . '.featured =?',1);
        }else if($params['communityAdsDisplay'] == 2){
            $select->where($tableName.'.sponsored =?',1);
        }else if($params['communityAdsDisplay'] == 4){
          $select->where($tableName.'.sponsored = 1 || ' . $tableName.'.featured = 1');
        }
      }

      //check previous ads
      if(empty($params['is_admin']) && empty($params['is_manage'])){
        $front = Zend_Controller_Front::getInstance();
        $key = Engine_Api::_()->sescommunityads()->getKey($front);
        if(!empty($_SESSION[$key])){
          $select->where($tableName.'.sescommunityad_id NOT IN ('.implode(',',$_SESSION[$key]).')');
        }
      }
      if(!empty($params['category_id']))
        $select->where('category_id =?',$params['category_id']);

      if(!empty($params['subcat_id']))
        $select->where('subcat_id =?',$params['subcat_id']);

      if(!empty($params['subsubcat_id']))
        $select->where('subsubcat_id =?',$params['subsubcat_id']);

      if(!empty($params['browsePage']) && empty($params['is_manage']))
        $select->where($tableName.'.type !=?','boost_post_cnt');
      if(!empty($params['show'])){
          if ($params['show'] == 2 && $viewer->getIdentity()) {
            $users = $viewer->membership()->getMembershipsOfIds();
            if ($users)
              $select->where($tableName . '.user_id IN (?)', $users);
            else
              $select->where($tableName . '.user_id IN (?)', 0);
          }
      }
      if(!empty($params['content_type'])){
        $content_type = $params['content_type'];
        if($content_type == "promote_content"){
          $module = $params['content_module'];
          if($module){
              $select->where('resources_type =?',$module);
          }
        }else if($content_type == "promote_page"){
            $select->where('resources_type =?','sespage_page');
        }else{
          $select->where('resources_type =?','promote_website_cnt');
        }
      }
      if(!empty($params['sort'])){
        if($params['sort'] == "recentlySPcreated"){
          $select->order('creation_date DESC');
        }else if($params['sort'] == "mostSPviewed"){
          $select->order('views_count DESC');
        }else if($params['sort'] == "featured"){
          $select->order('featured DESC');
        }else if($params['sort'] == "sponsored"){
          $select->order($tableName.'.sponsored DESC');
        }
      }

      //location
      if (isset($params['lat']) && isset($params['miles']) && $params['miles'] != 0 && isset($params['lng']) && $params['lat'] != '' && $params['lng'] != '' && ((isset($params['location']) && $params['location'] != ''))) {
      $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
      $tableLocationName = $tableLocation->info('name');
      $origLat = $lat = $params['lat'];
      $origLon = $long = $params['lng'];
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.search.type', 1) == 1) {
        $searchType = 3956;
      } else
        $searchType = 6371;
      $dist = $params['miles']; // This is the maximum distance (in miles) away from $origLat, $origLon in which to search

      $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $tableName . '.sescommunityad_id AND ' . $tableLocationName . '.resource_type = "sescommunityads" ', array('lat', 'lng', 'distance' => new Zend_Db_Expr($searchType." * 2 * ASIN(SQRT( POWER(SIN(($lat - $tableLocationName.lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS($tableLocationName.lat * pi()/180) * POWER(SIN(($long - $tableLocationName.lng) * pi()/180 / 2), 2) ))")));

      $rectLong1 = $long - $dist/abs(cos(deg2rad($lat))*69);
      $rectLong2 = $long + $dist/abs(cos(deg2rad($lat))*69);
      $rectLat1 = $lat-($dist/69);
      $rectLat2 = $lat+($dist/69);

      $select->where($tableLocationName . ".lng between $rectLong1 AND $rectLong2  and " . $tableLocationName . ".lat between $rectLat1 AND $rectLat2");
      $select->order('distance');
      $select->having("distance < $dist");
     }else if(Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation_enable', 0) && ((empty($params['locationEnable'])) || (!empty($params['locationEnable']) && $params['locationEnable'] != "no" )) && !empty($_COOKIE['seslocation_content_data']) && empty($params['is_admin']) && empty($params['is_manage'])){
      $locationData = json_decode($_COOKIE['seslocation_content_data'],true);
      $lat = $locationData['lat'];
      $long = $locationData['lng'];
      $dist = Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation.search.miles', 50); // This is the maximum distance (in miles) away from $origLat, $origLon in which to search
      $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
      $tableLocationName = $tableLocation->info('name');
      $origLat = $lat ;
      $origLon = $long ;
      $select = $select->setIntegrityCheck(false);
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('seslocation.search.type', 1) == 1) {
        $searchType = 3956;
      } else
        $searchType = 6371;

      $select->joinLeft($tableLocationName, $tableLocationName . '.resource_id = ' . $tableName . '.sescommunityad_id AND ' . $tableLocationName . '.resource_type = "sescommunityads" ', array('lat', 'lng', 'distance' => new Zend_Db_Expr($searchType." * 2 * ASIN(SQRT( POWER(SIN(($lat - $tableLocationName.lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS($tableLocationName.lat * pi()/180) * POWER(SIN(($long - $tableLocationName.lng) * pi()/180 / 2), 2) ))")));
      $rectLong1 = $long - $dist/abs(cos(deg2rad($lat))*69);
      $rectLong2 = $long + $dist/abs(cos(deg2rad($lat))*69);
      $rectLat1 = $lat-($dist/69);
      $rectLat2 = $lat+($dist/69);
      $select->where($tableLocationName . ".lng between $rectLong1 AND $rectLong2  and " . $tableLocationName . ".lat between $rectLat1 AND $rectLat2");
      $select->order('distance');
      $select->having("distance < $dist");
    }

    if(empty($params['is_admin']) && empty($params['is_manage'])){
      //starttime
      $select->where($tableName.'.startdate = "0000-00-00 00:00:00" || '.$tableName.'.startdate <= "'.date('Y-m-d H:i:s').'"');
      //endtime
      $select->where($tableName.'.enddate = "0000-00-00 00:00:00" || "'.date('Y-m-d H:i:s').'" BETWEEN '.$tableName.'.startdate AND '.$tableName.'.enddate');
    }

    //featured
    if(!empty($params['featured']))
      $select->where($featuredCase);
    //sponsored
    if(!empty($params['sponsored']))
      $select->where($sponsoredCase);
    if(empty($params['is_admin']) && empty($params['is_manage'])){
      $select->where('is_approved =?',1)
             ->where('status =?',1);
      //location based searching ads
      //Google Location enable from basic plugin
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
        $select = $this->locationBased($select,$tableName);
        $select = $this->reverselocationBased($select, $tableName);
      }
      //targetting and networking sql
      $select = $this->targetSql($select,$targetadsTableName,$viewer);

      //For Banner Work
      if(empty($params['subtype'])) {
        $select->where("subtype != 'banner' || subtype IS NULL");
      } else if(!empty($params['subtype'])) {
        $select->where('subtype =?', 'banner');
      }

      if(!empty($params['banner_id']) && isset($params['banner_id'])) {
        $select->where('banner_id =?', $params['banner_id']);
      }
    }

    //Rented Work
    if(!empty($params['widgetid'])) {
        $select->where('widgetid =?', $params['widgetid']);
    }
    if(!empty($params['fetchAll'])){
      if(!empty($params['limit'])){
        $select->limit($params['limit']);
      }
      $select->order(new Zend_Db_Expr('RAND()'));
      $data =  $this->fetchAll($select);
      $data = $this->setAdsData($data,!empty($params['fromActivityFeed']) ? true : false);
      return $data;
    }
    if(!empty($params['isWidget'])){
      $select->order(new Zend_Db_Expr('RAND()'));
    }
    $data =  Zend_Paginator::factory($select);
    $this->setAdsData($data);
    return $data;
  }

  function setAdsData($data,$fromActivityFeed = false){
    if($fromActivityFeed){
      $boostPostExists = false;
      foreach($data as $feeds){
        if($feeds['type'] == 'boost_post_cnt'){
          $data = array();
          $data = array($feeds);
          break;
        }
      }
    }
    $front = Zend_Controller_Front::getInstance();
    $key = Engine_Api::_()->sescommunityads()->getKey($front);
    foreach($data as $ad)
      $_SESSION[$key][] = $ad->getIdentity();

    return $data;
  }
  function locationBased($select,$tableName){
    if($this->_member == null){
      $this->_member = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember');
    }
    if(!$this->_member)
      return $select;

      $viewer = Engine_Api::_()->user()->getViewer();
      $userLocation = $this->_userLocation == null ? Engine_Api::_()->getDbTable('locations','sesbasic')->getLocationData('user',$viewer->getIdentity())  : $this->_userLocation;
      //get user location
      if(!$viewer->getIdentity() || !$userLocation){
        $select->where('location IS NULL || location = ""');
        return $select;
      }

      $tableLocation = Engine_Api::_()->getDbtable('locations', 'sesbasic');
      $tableLocationName = $tableLocation->info('name');
      $origLat = $lat = $userLocation->lat;
      $origLon = $long = $userLocation->lng;

      $select->joinLeft(array('locationTarget'=>$tableLocationName),  'locationTarget.resource_id = ' . $tableName . '.sescommunityad_id AND  locationTarget.resource_type = "sescommunityads" ', array('distanceFromTargetSearchAd' => new Zend_Db_Expr("location_type * 2 * ASIN(SQRT( POWER(SIN(($lat - locationTarget.lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS(locationTarget.lat * pi()/180) * POWER(SIN(($long - locationTarget.lng) * pi()/180 / 2), 2) ))")));

      $rectLong1 = abs(cos(deg2rad($lat))*69);
      $rectLong2 = abs(cos(deg2rad($lat))*69);

      $locationCase = "CASE
        WHEN location = '' || location IS NULL THEN true
        ELSE locationTarget.lng between $long - location_type/$rectLong1 AND $long + location_type/$rectLong2  and  locationTarget.lat between $lat-(location_type/69) AND $lat+(location_type/69) END
      ";
      $select->where($locationCase);
      $havingCase = "CASE
        WHEN location = '' || location IS NULL THEN true
        ELSE distanceFromTargetSearchAd <= location_distance END
      ";
      $select->having($havingCase);
     return $select;
  }

  function reverselocationBased($select,$tableName) {
    if($this->_member == null){
      $this->_member = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember');
    }
    if(!$this->_member)
      return $select;

      $viewer = Engine_Api::_()->user()->getViewer();
      $userLocation = $this->_userLocation == null ? Engine_Api::_()->getDbTable('locations','sesbasic')->getLocationData('user',$viewer->getIdentity())  : $this->_userLocation;
      //get user location
      if(!$viewer->getIdentity() || !$userLocation){
        $select->where('location IS NULL || location = ""');
        return $select;
      }

      $tableLocation = Engine_Api::_()->getDbtable('locations', 'sescommunityads');
      $tableLocationName = $tableLocation->info('name');
      $origLat = $lat = $userLocation->lat;
      $origLon = $long = $userLocation->lng;

      $select->joinLeft(array('revlocationTarget'=>$tableLocationName),  'revlocationTarget.resource_id = ' . $tableName . '.sescommunityad_id AND  revlocationTarget.resource_type = "sescommunityads" ', array('revdistanceFromTargetSearchAd' => new Zend_Db_Expr("location_type * 2 * ASIN(SQRT( POWER(SIN(($lat - revlocationTarget.lat) *  pi()/180 / 2), 2) +COS($lat * pi()/180) * COS(revlocationTarget.lat * pi()/180) * POWER(SIN(($long - revlocationTarget.lng) * pi()/180 / 2), 2) ))")));

      $rectLong1 = abs(cos(deg2rad($lat))*69);
      $rectLong2 = abs(cos(deg2rad($lat))*69);

      $locationCase = "CASE
        WHEN location = '' || location IS NULL THEN true
        ELSE revlocationTarget.lng between $long - location_type/$rectLong1 AND $long + location_type/$rectLong2  and  revlocationTarget.lat between $lat-(location_type/69) AND $lat+(location_type/69) END
      ";
      $select->where($locationCase);
      $havingCase = "CASE
        WHEN location = '' || location IS NULL THEN true
        ELSE revdistanceFromTargetSearchAd >= location_distance END
      ";
      $select->having($havingCase);
     return $select;
  }

  function getFieldValues($select,$targetadsTableName,$targetFields,$viewer,$profile_id){
    $fieldStructure = Engine_Api::_()->fields()->getFieldsStructurePartial($viewer);
    $arrayValues = array();
    foreach($fieldStructure as $map){
      $field = $map->getChild();
      $key = $map->getKey();
      $value = $field->getValue($viewer);
      $type = $field->type;
      $parts = explode('_', $key);
      $profileType = $parts[1];
      if($profileType != $profile_id)
        continue;
      if($type == "profile_type")
        continue;
      //SE profile total types
      //text, textarea, select, radio, checkbox, multiselect, multi_checkbox, integer, float, date, heading
      if(is_array($value)){
        $fieldValue = array();
        foreach($value as $val){
         $fieldValue[] = $val->value;
        }
      }else{
        $fieldValue = !empty($value->value) ? $value->value : '';
      }
      $isValid = true;
      if($profileType && !array_key_exists($field->field_id.'_'.$profileType,$targetFields)/* && $type != 'birthdate'*/)
        continue;
       if($type == "birthdate"){
        if(array_key_exists($profileType.'_birthday',$targetFields))
          $arrayValues[$field->field_id.'_'.$profileType]['birthdate'] = 1;
          $arrayValues[$field->field_id.'_'.$profileType]['date'] = $fieldValue;
          if(array_key_exists($field->field_id.'_'.$profileType,$targetFields))
             $arrayValues[$field->field_id.'_'.$profileType]['date_enable'] = true;
           else
              $arrayValues[$field->field_id.'_'.$profileType]['date_enable'] = false;
       }else{
          $arrayValues[$field->field_id.'_'.$profileType] = $fieldValue;
       }
    }
    return $arrayValues;
  }
  function targetSql($select,$targetadsTableName,$viewer){
    $profile_id = Engine_Api::_()->getDbTable('metas','sescommunityads')->getUserProfileFieldId();
    $targetFields= Engine_Api::_()->sescommunityads()->getTargetAds(array('fieldsArray'=>1));
    //check network sql
    $hasNetwork = Engine_Api::_()->sescommunityads()->networks();
    if(engine_count($hasNetwork) && array_key_exists('network_enable',$targetFields)){
      $userNetwork = Engine_Api::_()->getDbtable('membership', 'network')->getMembershipsOfInfo($viewer);

      if(engine_count($userNetwork)){
        foreach($userNetwork as $network){
          $network_id = $network->resource_id; //$network->getIdentity();
          $networkArray[] = 'FIND_IN_SET('.$network_id.','.$targetadsTableName.'.network_enable) > 0';
        }
        //join query
        $networkLike = (string) ( join(" OR ", $networkArray) );
        $select->where($networkLike.' OR '.$targetadsTableName.'.network_enable IS NULL OR '.$targetadsTableName.'.network_enable = ""');
      }else{
        $select->where('network_enable IS NULL OR network_enable = ""');
      }
    }

    //Interest Based targetting
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest') && array_key_exists('interest_enable',$targetFields)) {
      $userInterests = Engine_Api::_()->getDbTable('userinterests', 'sesinterest')->getUserInterests(array('user_id' => $viewer->getIdentity()));
      if(engine_count($userInterests)) {
        foreach($userInterests as $userInterest) {
          $interest_id = $userInterest->interest_id;
          $interestArray[] = 'FIND_IN_SET('.$interest_id.','.$targetadsTableName.'.interest_enable) > 0';
        }
        //join query
        $interestLike = (string) ( join(" OR ", $interestArray) );
        $select->where($interestLike.' OR '.$targetadsTableName.'.interest_enable IS NULL OR '.$targetadsTableName.'.interest_enable = ""');
      }else{
        $select->where('interest_enable IS NULL OR interest_enable = ""');
      }
    }
    //Interest Based targetting

    $arrayValues = $this->getFieldValues($select,$targetadsTableName,$targetFields,$viewer,$profile_id);
    if(!engine_count($arrayValues))
      return $select;
    //make query for custom fields
    foreach($arrayValues as $key => $value){
      if(is_array($value)){
        if(!engine_count($value))
          continue;
        if(!empty($value['birthdate'])){
          //bithdat today
          if(!empty($value['date'])){
            $birthdayValue = date('m-d',strtotime($value['date']));
            if(strtotime(date('m-d')) != strtotime($birthdayValue)){
              $select->where($targetadsTableName.'.'.$profile_id.'_birthday IS NULL');
            }
            if(!empty($value['date_enable'])){
               $birthdayValue = date('Y-m-d',strtotime($value['date']));
               //min and max age
                $from = new DateTime($birthdayValue);
                $to   = new DateTime('today');
                $age =  $from->diff($to)->y;
                //min age
                $select->where($targetadsTableName.'.'.$key.' IS NULL  OR (SUBSTRING_INDEX('.$targetadsTableName.'.'.$key.',"||",1) <= '.$age. ' AND SUBSTRING_INDEX('.$targetadsTableName.'.'.$key.',"||",-1) >= '.$age.')');
                //max age
            }
          }else{
            $select->where($targetadsTableName.'.'.$profile_id.'_birthday IS NULL');
          }
        }else{
          foreach($value as $val)
            $likeQueryArray[] = (string) 'CONCAT("||",'.$targetadsTableName.'.'.$key.',"||") LIKE \'%||'.$val.'||%\'';
          //join query
          $likeQuery = (string) ( join(" OR ", $likeQueryArray) );
          $select->where($likeQuery.' OR '.$targetadsTableName.'.'.$key.' IS NULL');
        }
      }else{
          $select->where($targetadsTableName.'.'.$key.' LIKE "%'.$value.'%" OR '.$targetadsTableName.'.'.$key.' IS NULL');
      }
    }
    return $select;
  }

    public function getWidgetAds($params = array()) {

        $select = $this->select()
                ->from($this->info('name'), 'sescommunityad_id')
                ->where('widgetid =?', $params['widgetid']);

        return $select->query()->fetchColumn();
    }
}
