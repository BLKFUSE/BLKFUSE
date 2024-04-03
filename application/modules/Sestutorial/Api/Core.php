<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Api_Core extends Core_Api_Abstract {

  public function getFileUrl($image) {
    
    $table = Engine_Api::_()->getDbTable('files', 'core');
    $result = $table->select()
                ->from($table->info('name'), 'storage_file_id')
                ->where('storage_path =?', $image)
                ->query()
                ->fetchColumn();
    if(!empty($result)) {
      $storage = Engine_Api::_()->getItem('storage_file', $result);
      return $storage->map();
    } else {
      return $image;
    }
  }
  
  public function getAllSuperAdmins() {
    $userTable = Engine_Api::_()->getDbTable('users', 'user');
    $select = $userTable->select()->from($userTable->info('name'), 'email')->where('level_id IN (?)', array(1,2));
    $results = $select->query()->fetchAll();
    return $results;
  }
  
  public function tutorialpermission($tutorial_id) {

    $viewer = Engine_Api::_()->user()->getViewer();
    if(empty($tutorial_id))
      return;

    $flag = false;
    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
    $memberlevels = unserialize($tutorial->memberlevels);
    if($viewer->getIdentity()) {
      if(engine_in_array($viewer->level_id, $memberlevels)) {
        $flag = true;
      }
    }
    return $flag;
  }

  public function textTruncation($text, $textLength = null) {
    $text = strip_tags($text);
    return ( Engine_String::strlen($text) > $textLength ? Engine_String::substr($text, 0, $textLength) . '...' : $text);
  }

  function tagCloudItemCore($fetchtype = '', $tutorial_id = '') {

    $tableTagmap = Engine_Api::_()->getDbtable('tagMaps', 'core');
    $tableTagName = $tableTagmap->info('name');
    $tableTag = Engine_Api::_()->getDbtable('tags', 'core');
    $tableMainTagName = $tableTag->info('name');
    $selecttagged_photo = $tableTagmap->select()
            ->from($tableTagName)
            ->setIntegrityCheck(false)
            ->where('resource_type =?', 'sestutorial_tutorial')
            ->where('tag_type =?', 'core_tag')
            ->joinLeft($tableMainTagName, $tableMainTagName . '.tag_id=' . $tableTagName . '.tag_id', array('text'))
            ->group($tableTagName . '.tag_id');
    if($tutorial_id) {
      $selecttagged_photo->where($tableTagName.'.resource_id =?', $tutorial_id);
    }
    $selecttagged_photo->columns(array('itemCount' => ("COUNT($tableTagName.tagmap_id)")));
    if ($fetchtype == '')
      return Zend_Paginator::factory($selecttagged_photo);
    else
      return $tableTagmap->fetchAll($selecttagged_photo);
  }

  public function getwidgetizePage($params = array()) {

    $corePages = Engine_Api::_()->getDbtable('pages', 'core');
    $corePagesName = $corePages->info('name');
    $select = $corePages->select()
            ->from($corePagesName, array('*'))
            ->where('name = ?', $params['name'])
            ->limit(1);
    return $corePages->fetchRow($select);
  }

  public function getRating($tutorial_id) {

    $table  = Engine_Api::_()->getDbTable('ratings', 'sestutorial');
    $rating_sum = $table->select()
      ->from($table->info('name'), new Zend_Db_Expr('SUM(rating)'))
      ->group('tutorial_id')
      ->where('tutorial_id = ?', $tutorial_id)
      ->query()
      ->fetchColumn(0);

    $total = $this->ratingCount($tutorial_id);
    if ($total) $rating = $rating_sum/$this->ratingCount($tutorial_id);
    else $rating = 0;

    return $rating;
  }

  public function getRatings($tutorial_id) {

    $table  = Engine_Api::_()->getDbTable('ratings', 'sestutorial');
    $rName = $table->info('name');
    $select = $table->select()
                    ->from($rName)
                    ->where($rName.'.tutorial_id = ?', $tutorial_id);
    $row = $table->fetchAll($select);
    return $row;
  }

  public function checkRated($tutorial_id, $user_id) {

    $table  = Engine_Api::_()->getDbTable('ratings', 'sestutorial');
    $rName = $table->info('name');
    $select = $table->select()
                 ->setIntegrityCheck(false)
                    ->where('tutorial_id = ?', $tutorial_id)
                    ->where('user_id = ?', $user_id)
                    ->limit(1);
    $row = $table->fetchAll($select);

    if (engine_count($row)>0) return true;
    return false;
  }

  public function setRating($tutorial_id, $user_id, $rating) {

    $table  = Engine_Api::_()->getDbTable('ratings', 'sestutorial');
    $rName = $table->info('name');
    $select = $table->select()
                    ->from($rName)
                    ->where($rName.'.tutorial_id = ?', $tutorial_id)
                    ->where($rName.'.user_id = ?', $user_id);
    $row = $table->fetchRow($select);
    if (empty($row)) {
      // create rating
      Engine_Api::_()->getDbTable('ratings', 'sestutorial')->insert(array(
        'tutorial_id' => $tutorial_id,
        'user_id' => $user_id,
        'rating' => $rating
      ));
    }
  }

  public function ratingCount($tutorial_id) {

    $table  = Engine_Api::_()->getDbTable('ratings', 'sestutorial');
    $rName = $table->info('name');
    $select = $table->select()
                    ->from($rName)
                    ->where($rName.'.tutorial_id = ?', $tutorial_id);
    $row = $table->fetchAll($select);
    $total = engine_count($row);
    return $total;
  }


  public function checkPrivacySetting($tutorial_id) {

    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
		
		$return = true;
		
    if (empty($tutorial->status))
      $return = false;

// 		if(empty($tutorial->show_page) && empty($viewerId))
// 			return false;

    if ($viewerId)
      $level_id = $viewer->level_id;
    else
      $level_id = 5;

    $memberlevels = $tutorial->memberlevels;
    $member_level = unserialize($memberlevels);

    if (!empty($member_level)) {
      if (engine_in_array($level_id, $member_level))
        $return = true;
    } else
      $return = false;

		
    if ($viewerId) {
      $network_table = Engine_Api::_()->getDbtable('membership', 'network');
      $network_select = $network_table->select('resource_id')->where('user_id = ?', $viewerId);
      $network_id_query = $network_table->fetchAll($network_select);
      $network_id_query_count = engine_count($network_id_query);
      $network_id_array = array();
      for ($i = 0; $i < $network_id_query_count; $i++) {
        $network_id_array[$i] = $network_id_query[$i]['resource_id'];
      }

      if (!empty($network_id_array)) {
        $networks = unserialize($tutorial->networks);

        if (!empty($networks)) {
          if (!array_intersect($network_id_array, $networks))
            $return = false;
        } else
          $return = false;
      } else {
				$return = true;
      }

      $profile_table = Engine_Api::_()->fields()->getTable('user', 'values');
      $profile_select = $profile_table->select('value')->where('field_id = 1 AND item_id = ?', $viewerId);
      $profile_type_query = $profile_table->fetchRow($profile_select);
      $profile_type_id = $profile_type_query['value'];
      $profile_types = unserialize($tutorial->profile_types);
      if (!empty($profile_types)) {
        if (is_array($profile_types)) {
          if (!engine_in_array($profile_type_id, $profile_types))
            $return = false;
        }
        else {
          if ($profile_type_id != $profile_types)
            $return = false;
        }
      } else
        $return = false;
    }
		return $return;
  }


  // get item like status
  public function getLikeStatus($tutorial_id = '', $resource_type = '') {

    if ($tutorial_id != '') {
      $userId = Engine_Api::_()->user()->getViewer()->getIdentity();
      if ($userId == 0)
        return false;
      $coreLikeTable = Engine_Api::_()->getDbtable('likes', 'core');
      $total_likes = $coreLikeTable->select()->from($coreLikeTable->info('name'), new Zend_Db_Expr('COUNT(like_id) as like_count'))->where('resource_type =?', $resource_type)->where('poster_id =?', $userId)->where('poster_type =?', 'user')->where('resource_id =?', $tutorial_id)->limit(1)->query()->fetchColumn();
      if ($total_likes > 0)
        return true;
      else
        return false;
    }
    return false;
  }
}
