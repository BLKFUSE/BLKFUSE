<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Reviews.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Reviews extends Engine_Db_Table {

  protected $_rowClass = 'Sesmember_Model_Review';

  public function getMemberReviewSelect($params = array()) {

    $table = Engine_Api::_()->getItemTable('sesmember_review');
    $memberTableName = $table->info('name');
    $select = $table->select()->from($memberTableName);

    $currentTime = date('Y-m-d H:i:s');
    if (isset($params['view'])) {
      if ($params['view'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['view'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      }
    }

    //Full Text
    if (isset($params['order']) && !empty($params['order'])) {
      if ($params['order'] == 'featured')
        $select->where('featured = ?', '1');
      elseif ($params['order'] == 'verified')
        $select->where('verified = ?', '1');
      if ($params['order'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['order'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(creation_date) between ('$endTime') and ('$currentTime')");
      }
    }
    if (!empty($params['search_text']))
      $select->where('`' . $memberTableName . '`.`title` LIKE ?', '%' . $params['search_text'] . '%');
    if (!empty($params['user_id']))
      $select->where('`' . $memberTableName . '`.`user_id` = ?', $params['user_id']);
    if (!empty($params['review_stars']))
      $select->where('`' . $memberTableName . '`.`rating` = ?', $params['review_stars']);

    if (!empty($params['review_recommended']))
      $select->where('`' . $memberTableName . '`.`recommended` = ?', $params['review_recommended']);


    if (isset($params['order']) && $params['order'] != '') {
      $select->order($memberTableName . '.' . $params['order']);
    }

    if (isset($params['criteria'])) {
      if ($params['criteria'] == 1)
        $select->where($memberTableName . '.featured =?', '1');
      else if ($params['criteria'] == 2)
        $select->where($memberTableName . '.verified =?', '1');
      else if ($params['criteria'] == 3)
        $select->where($memberTableName . '.featured = 1 OR ' . $memberTableName . '.verified = 1');
      else if ($params['criteria'] == 4)
        $select->where($memberTableName . '.featured = 0 AND ' . $memberTableName . '.verified = 0');
    }

    if (isset($params['info'])) {
      switch ($params['info']) {
        case 'most_viewed':
          $select->order('view_count DESC');
          break;
        case 'most_liked':
          $select->order('like_count DESC');
          break;
        case 'like_count':
          $select->order('like_count DESC');
          break;
        case 'most_commented':
          $select->order('comment_count DESC');
          break;
        case 'comment_count':
          $select->order('comment_count DESC');
          break;
        case "view_count":
          $select->order($memberTableName . '.view_count DESC');
          break;
        case "most_rated":
          $select->order($memberTableName . '.rating DESC');
          break;
        case "useful_count":
          $select->order($memberTableName . '.useful_count DESC');
          break;
        case "funny_count":
          $select->order($memberTableName . '.funny_count DESC');
          break;
        case "cool_count":
          $select->order($memberTableName . '.cool_count DESC');
          break;

        case "least_rated":
          $select->order($memberTableName . '.rating ASC');
          break;
        case 'random':
          $select->order('Rand()');
          break;
        case "verified" :
          $select->where($memberTableName . '.verified' . ' = 1')
                  ->order($memberTableName . '.user_id DESC');
          break;
        case "featured" :
          $select->where($memberTableName . '.featured' . ' = 1')
                  ->order($memberTableName . '.user_id DESC');
          break;
        case "creation_date":
          $select->order($memberTableName . '.creation_date DESC');
          break;
        case "modified_date":
          $select->order($memberTableName . '.modified_date DESC');
          break;
      }
    }

    if (isset($params['widgetName']) && $params['widgetName'] == 'oftheday') {
      $select->where($memberTableName . '.oftheday =?', 1)
              ->where($memberTableName . '.starttime <= DATE(NOW())')
              ->where($memberTableName . '.endtime >= DATE(NOW())')
              ->limit(1)
              ->order('RAND()');
    }

    if (isset($params['limit']) && !empty($params['limit']))
      $select->limit($params['limit']);
    if (!empty($params['limit_data']))
      $select->limit($params['limit_data']);
    $select->order($memberTableName . '.creation_date DESC');

    if (isset($params['paginator']))
      return Zend_Paginator::factory($select);

    if (isset($params['fetchAll']))
      return $table->fetchAll($select);
    else
      return $select;
  }

  public function isReview($params = array()) {
    $select = $this->select()->from($this->info('name'), $params['column_name']);
    if (isset($params['user_id']))
      $select->where('user_id = ?', $params['user_id']);
    $select->where('owner_id =?', Engine_Api::_()->user()->getViewer()->getIdentity());
    return $select = $select->query()->fetchColumn();
  }

  public function ratingCount($resource_id = NULL) {
    $rName = $this->info('name');
    return $this->select()
                    ->from($rName, new Zend_Db_Expr('COUNT(review_id) as total_rating'))
                    ->where($rName . '.user_id = ?', $resource_id)
                    ->limit(1)->query()->fetchColumn();
  }

  // rating functions
  public function getRating($resource_id) {
    $rating_sum = $this->select()
            ->from($this->info('name'), new Zend_Db_Expr('SUM(rating)'))
            ->group('user_id')
            ->where('user_id = ?', $resource_id)
            ->query()
            ->fetchColumn(0);
    $total = $this->ratingCount($resource_id);
    if ($total)
      $rating = $rating_sum / $total;
    else
      $rating = 0;
    return $rating;
  }

  public function getSumRating($resource_id) {
    $rName = $this->info('name');
    $rating_sum = $this->select()
            ->from($rName, new Zend_Db_Expr('SUM(rating)'))
            ->where('user_id = ?', $resource_id)
            ->group('user_id')
            ->query()
            ->fetchColumn();
    return $rating_sum;
  }

  public function getUserRatingWithStar($params = array()) {
    $rName = $this->info('name');
    $rating_sum = $this->select()
            ->from($rName, array('owner_id'))
            ->where('user_id = ?', $params['user_id'])
            ->where('rating = ?', $params['rating']);
    return Zend_Paginator::factory($rating_sum);
  }

  public function getUserRatingStats($params = array()) {
    $rName = $this->info('name');
    $rating_sum = $this->select()
            ->from($rName, array('rating', 'total' => new Zend_Db_Expr('COUNT(*)')))
            ->where('user_id = ?', $params['user_id'])
            ->group('rating');
    return $this->fetchAll($rating_sum);
  }

  function getReviewers($limit = 0, $paginator = false) {
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $reviewTableName = $this->info('name');

    $userinfosTableName = Engine_Api::_()->getItemTable('sesmember_userinfo')->info('name');

    $select = $userTable->select()
            ->from($userTable->info('name'), array('COUNT(*) AS review_count', 'user_id', 'displayname', 'email', 'like_count', 'view_count', 'photo_id'))
            ->setIntegrityCheck(false)
            ->join($reviewTableName, $reviewTableName . '.owner_id=' . $userTableName . '.user_id', '')
            ->joinLeft($userinfosTableName, "$userinfosTableName.user_id = $userTableName.user_id",array('userinfo_id', 'follow_count', 'location', 'rating', 'user_verified', 'cool_count', 'funny_count', 'useful_count', 'featured', 'sponsored', 'vip'))
            ->order('review_count DESC')
            ->order($reviewTableName . '.creation_date DESC')
            ->group($userTableName . '.user_id');
    if ($paginator)
      return Zend_Paginator::factory($select);
    $select->limit($limit);
    return $userTable->fetchAll($select);
  }

  public function getReviewCount($userId = null) {
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $reviewTableName = $this->info('name');
    $select = $userTable->select()
            ->from($userTable->info('name'), array('COUNT(*) AS review_count'))
            ->setIntegrityCheck(false)
            ->join($reviewTableName, $reviewTableName . '.owner_id=' . $userTableName . '.user_id', '')
            ->where($reviewTableName . '.user_id =?', $userId);
    $reviews = $select->query()->fetchColumn();
    if ($reviews)
      return $reviews;
    else
      return 0;
  }

  public function getRecommendationCount($userId = null) {
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $reviewTableName = $this->info('name');
    $select = $userTable->select()
            ->from($userTable->info('name'), array('COUNT(*) AS recommendation_count'))
            ->setIntegrityCheck(false)
            ->join($reviewTableName, $reviewTableName . '.owner_id=' . $userTableName . '.user_id', '')
            ->where($reviewTableName . '.user_id =?', $userId)
            ->where($reviewTableName . '.recommended =?', 1);
    $reviews = $select->query()->fetchColumn();
    if ($reviews)
      return $reviews;
    else
      return 0;
  }
}
