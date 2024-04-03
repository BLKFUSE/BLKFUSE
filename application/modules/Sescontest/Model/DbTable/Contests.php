<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Contests.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Contests extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Contest";

  public function countContests($userId = null) {
    $count = $this->select()->from($this->info('name'), array("COUNT(contest_id)"));
    if ($userId)
      $count->where('user_id =?', $userId);
    return $count->query()->fetchColumn();
  }

  public function packageContestCount($packageId = null) {
    $count = $this->select()->from($this->info('name'), array("COUNT(contest_id)"))->where('package_id =?', $packageId);
    return $count->query()->fetchColumn();
  }

  public function checkCustomUrl($value = '', $contest_id = '') {
    $select = $this->select('contest_id')->where('custom_url = ?', $value);
    if ($contest_id)
      $select->where('contest_id !=?', $contest_id);
    return $select->query()->fetchColumn();
  }

  public function getContestId($slug = null) {
    if ($slug) {
      $tableName = $this->info('name');
      $select = $this->select()
              ->from($tableName)
              ->where($tableName . '.custom_url = ?', $slug);
      $row = $this->fetchRow($select);
      if (empty($row)) {
        $contest_id = $slug;
      } else
        $contest_id = $row->contest_id;
      return $contest_id;
    }
    return '';
  }

  public function getContestPaginator($params = array()) {
    return Zend_Paginator::factory($this->getContestSelect($params));
  }

  public function getContestSelect($params = array()) {

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $currentTime = date('Y-m-d H:i:s');
    $tomorrow_date = date("Y-m-d H:i:s", strtotime("+ 1 day"));
    $nextWeekDate = date("Y-m-d H:i:s", strtotime("+ 7 day"));
    $contestTable = Engine_Api::_()->getDbtable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $tableTagmap = Engine_Api::_()->getDbtable('tagMaps', 'core');
    $tableTagName = $tableTagmap->info('name');
    $tableTag = Engine_Api::_()->getDbtable('tags', 'core');
    
    $userTableName = Engine_Api::_()->getDbTable('users', 'user')->info('name');
    
    $select = $contestTable->select()->setIntegrityCheck(false);
    if (isset($params['left']))
      $select->from($contestTableName, array('*', new Zend_Db_Expr('"left" AS type')));
    else if (isset($params['right']))
      $select->from($contestTableName, array('*', new Zend_Db_Expr('"right" AS type')));
    else
      $select->from($contestTableName);
		
		$select->join($userTableName, "$userTableName.user_id = $contestTableName.user_id", null);
		
    if (!empty($params['tag'])) {
      $select->joinLeft($tableTagName, "$tableTagName.resource_id = $contestTableName.contest_id")
              ->where($tableTagName . '.resource_type = ?', 'contest')
              ->where($tableTagName . '.tag_id = ?', $params['tag']);
    }

    if (isset($params['sameTag']) && !empty($params['sameTag'])) {
      $select->joinLeft($tableTagName, "$tableTagName.resource_id = $contestTableName.contest_id", NULL)
              ->where($tableTagName . '.resource_type = ?', 'contest')
              ->distinct(true)
              ->where($tableTagName . '.resource_id != ?', $params['sameTagresource_id'])
              ->where($tableTagName . '.tag_id IN(?)', $params['sameTagTag_id']);
    } elseif (isset($params['sameCategory']) && !empty($params['sameCategory'])) {
      $select = $select->where($contestTableName . '.category_id =?', $params['category_id'])
              ->where($contestTableName . '.contest_id !=?', $params['contest_id']);
    } elseif (isset($params['other-contest'])) {
      $select->where($contestTableName . '.contest_id !=?', $params['contest_id']);
    }

    if (empty($params['widgetManage'])) {
      $select->where($contestTableName . '.draft = ?', (bool) 1);
      $select->where($contestTableName . '.is_approved = ?', (bool) 1);
      $select->where($contestTableName . '.search = ?', (bool) 1);
    }

    if (isset($params['popularity']) && $params['popularity'] == "You May Also Like") {
      $contestIds = Engine_Api::_()->sescontest()->likeIds(array('type' => 'contest', 'id' => $viewerId));
      $select->where($contestTableName . '.contest_id NOT IN(?)', $contestIds);
    }

    if (isset($params['media']) && !empty($params['media'])) {
      $select = $select->where($contestTableName . '.contest_type =?', $params['media']);
    }

    if (isset($params['show_date_field']) && !empty($params['show_date_field'])) {
      $explodeTime = explode('-', $params['show_date_field']);
      $startTime = $explodeTime[0];
      $endTime = $explodeTime[1];
      $select->where("DATE_FORMAT(" . $contestTableName . ".creation_date, '%Y-%m-%d') between ('" . date('Y-m-d', strtotime($startTime)) . "') and ('" . date('Y-m-d', strtotime($endTime)) . "')");
    }

    if (isset($params['sort']) && !empty($params['sort'])) {
      if ($params['sort'] == 'featured')
        $select = $select->where($contestTableName . '.featured =?', 1);
      elseif ($params['sort'] == 'sponsored')
        $select = $select->where($contestTableName . '.sponsored =?', 1);
      elseif ($params['sort'] == 'verified')
        $select = $select->where($contestTableName . '.verified =?', 1);
      elseif ($params['sort'] == 'hot')
        $select = $select->where($contestTableName . '.hot =?', 1);
      elseif ($params['sort'] == 'join_count ASC')
        $select->order($contestTableName . '.join_count ASC');
      elseif ($params['sort'] == 'join_count DESC')
        $select->order($contestTableName . '.join_count DESC');
      else if ($params['sort'] == 'upcoming') {
        $select->where($contestTableName . ".endtime > ?", $currentTime)
                ->where($contestTableName . ".starttime > ?", $currentTime);
      } else if ($params['sort'] == 'ended')
        $select->where("$contestTableName.endtime < FROM_UNIXTIME('" . time() . "')");
      elseif ($params['sort'] == 'ongoing') {
        $select->where($contestTableName . ".endtime >= ?", $currentTime)->where($contestTableName . ".starttime <= ?", $currentTime);
      } elseif ($params['sort'] == 'week') {
        $select->where("((YEARWEEK(starttime) = YEARWEEK('$currentTime')) || YEARWEEK(endtime) = YEARWEEK('$currentTime'))  || (DATE(starttime) <= DATE('$currentTime') AND DATE(endtime) >= DATE('$currentTime'))");
      } elseif ($params['sort'] == 'month') {
        $select->where("((YEAR(starttime) > YEAR('$currentTime')) || YEAR(starttime) <= YEAR('$currentTime') AND (MONTH(starttime) <= MONTH('$currentTime'))) AND ((YEAR(endtime) > YEAR('$currentTime') || MONTH(endtime) >= MONTH('$currentTime')) AND YEAR(endtime) >= YEAR('$currentTime'))");
      } else
        $select = $select->order($contestTableName . '.' . $params['sort'] . ' DESC');
    }
    if (isset($params['show']) && !empty($params['show'])) {
      if ($params['show'] == 1 && $viewerId) {
        $users = $viewer->membership()->getMembershipsOfIds();
        if ($users)
          $select->where($contestTableName . '.user_id IN (?)', $users);
        else
          $select->where($contestTableName . '.user_id IN (?)', 0);
      }
      elseif ($params['show'] == 2) {
        $networkMembershipTable = Engine_Api::_()->getDbTable('membership', 'network');
        $membershipNetworkUserIds = $networkMembershipTable->getMembershipsOfIds($viewer);
        $networkMembershipTableName = $networkMembershipTable->info('name');
        $select->join($networkMembershipTableName, $contestTableName . ".user_id = " . $networkMembershipTableName . ".user_id  ", null)
                ->where($networkMembershipTableName . ".resource_id  IN (?) ", $membershipNetworkUserIds);
      } elseif ($params['show'] == 3) {
        $select->where($contestTableName . '.user_id=?', $viewerId);
      } else if ($params['show'] == 'ended')
        $select->where("$contestTableName.endtime < FROM_UNIXTIME('" . time() . "')");
      else if ($params['show'] == 'upcoming')
        $select->where("endtime > FROM_UNIXTIME('" . time() . "') && starttime > FROM_UNIXTIME('" . time() . "')");
      elseif ($params['show'] == 'ongoing') {
        $select->where($contestTableName . ".endtime >= ?", $currentTime)->where($contestTableName . ".starttime <= ?", $currentTime);
      } elseif ($params['show'] == 'ongoingSPupcomming') {
        $select->where("(endtime >= '" . $currentTime . "') || (endtime > '" . $currentTime . "' && starttime > '" . $currentTime . "')");
      } elseif ($params['show'] == 'week') {
        $select->where("((YEARWEEK(starttime) = YEARWEEK('$currentTime')) || YEARWEEK(endtime) = YEARWEEK('$currentTime'))  || (DATE(starttime) <= DATE('$currentTime') AND DATE(endtime) >= DATE('$currentTime'))");
      } elseif ($params['show'] == 'today') {
        $select->where("$contestTableName.starttime <= ?", $currentTime)->where("$contestTableName.endtime >= ?", $currentTime);
      } elseif ($params['show'] == 'tomorrow') {
        $select->where("$contestTableName.starttime <= ?", $tomorrow_date)->where("$contestTableName.endtime >= ?", $tomorrow_date);
      } elseif ($params['show'] == 'nextweek') {
        $select->where("((YEARWEEK($contestTableName.starttime) = YEARWEEK('$nextWeekDate')) || YEARWEEK($contestTableName.endtime) = YEARWEEK('$nextWeekDate'))  || (DATE($contestTableName.starttime) <= DATE('$nextWeekDate') AND DATE($contestTableName.endtime) >= DATE('$nextWeekDate'))");
      } elseif ($params['show'] == 'month') {
        $select->where("((YEAR(starttime) > YEAR('$currentTime')) || YEAR(starttime) <= YEAR('$currentTime') AND (MONTH(starttime) <= MONTH('$currentTime'))) AND ((YEAR(endtime) > YEAR('$currentTime') || MONTH(endtime) >= MONTH('$currentTime')) AND YEAR(endtime) >= YEAR('$currentTime'))");
      }
    }
    if (isset($params['media_type']) && !empty($params['media_type'])) {
      $select->where($contestTableName . '.contest_type =?', $params['media_type']);
    }

    if (isset($params['user_id']) && !empty($params['user_id'])) {
      $select->where($contestTableName . '.user_id =?', $params['user_id']);
    }

    if (isset($params['featured']) && !empty($params['featured']))
      $select = $select->where($contestTableName . '.featured =?', 1);

    if (isset($params['verified']) && !empty($params['verified']))
      $select = $select->where($contestTableName . '.verified =?', 1);

    if (isset($params['sponsored']) && !empty($params['sponsored']))
      $select = $select->where($contestTableName . '.sponsored =?', 1);

    if (!empty($params['category_id']))
      $select->where($contestTableName . '.category_id = ?', $params['category_id']);

    if (!empty($params['subcat_id']))
      $select = $select->where($contestTableName . '.subcat_id =?', $params['subcat_id']);

    if (!empty($params['subsubcat_id']))
      $select = $select->where($contestTableName . '.subsubcat_id =?', $params['subsubcat_id']);

    if (isset($params['draft']))
      $select->where($contestTableName . '.draft = ?', $params['draft']);

    if (!empty($params['text']))
      $select->where($contestTableName . ".title LIKE ? OR " . $contestTableName . ".description LIKE ?", '%' . $params['text'] . '%');

    if (!empty($params['alphabet']))
      $select->where($contestTableName . '.title LIKE ?', "%{$params['alphabet']}%");

    if (!empty($params['getcontest'])) {
      $select->where($contestTableName . ".title LIKE ? OR " . $contestTableName . ".description LIKE ?", '%' . $params['textSearch'] . '%')->where($contestTableName . ".search = ?", 1);
    }

    if (isset($params['order']) && !empty($params['order'])) {
      if ($params['order'] == 'featured')
        $select->where('featured = ?', '1');
      elseif ($params['order'] == 'sponsored')
        $select->where('sponsored = ?', '1');
      elseif ($params['order'] == 'hot')
        $select->where('hot = ?', '1');
      else if ($params['order'] == 'ended')
        $select->where("$contestTableName.endtime < FROM_UNIXTIME('" . time() . "')");
      else if ($params['order'] == 'upcoming')
        $select->where("endtime > FROM_UNIXTIME('" . time() . "') && starttime > FROM_UNIXTIME('" . time() . "')");
      if ($params['order'] == 'week') {
        $select->where("((YEARWEEK(starttime) = YEARWEEK('$currentTime')) || YEARWEEK(endtime) = YEARWEEK('$currentTime'))  || (DATE(starttime) <= DATE('$currentTime') AND DATE(endtime) >= DATE('$currentTime'))");
      } elseif ($params['order'] == 'today') {
        $select->where("$contestTableName.starttime <= ?", $currentTime)->where("$contestTableName.endtime >= ?", $currentTime);
      } elseif ($params['order'] == 'tomorrow') {
        $select->where("$contestTableName.starttime <= ?", $tomorrow_date)->where("$contestTableName.endtime >= ?", $tomorrow_date);
      } elseif ($params['order'] == 'nextweek') {
        $select->where("((YEARWEEK($contestTableName.starttime) = YEARWEEK('$nextWeekDate')) || YEARWEEK($contestTableName.endtime) = YEARWEEK('$nextWeekDate'))  || (DATE($contestTableName.starttime) <= DATE('$nextWeekDate') AND DATE($contestTableName.endtime) >= DATE('$nextWeekDate'))");
      } elseif ($params['order'] == 'month') {
        $select->where("((YEAR(starttime) > YEAR('$currentTime')) || YEAR(starttime) <= YEAR('$currentTime') AND (MONTH(starttime) <= MONTH('$currentTime'))) AND ((YEAR(endtime) > YEAR('$currentTime') || MONTH(endtime) >= MONTH('$currentTime')) AND YEAR(endtime) >= YEAR('$currentTime'))");
      } elseif ($params['order'] == 'ongoing') {
        $select->where($contestTableName . ".endtime >= ?", $currentTime)->where($contestTableName . ".starttime <= ?", $currentTime);
      } elseif ($params['order'] == 'ongoingSPupcomming') {
        $select->where("(endtime >= '" . $currentTime . "') || (endtime > '" . $currentTime . "' && starttime > '" . $currentTime . "')");
      }
    }

    if (isset($params['criteria'])) {
      if ($params['criteria'] == 1)
        $select->where($contestTableName . '.featured =?', '1');
      else if ($params['criteria'] == 2)
        $select->where($contestTableName . '.sponsored =?', '1');
      else if ($params['criteria'] == 6)
        $select->where($contestTableName . '.verified =?', '1');
      else if ($params['criteria'] == 7)
        $select->where($contestTableName . '.hot =?', '1');
      else if ($params['criteria'] == 3)
        $select->where($contestTableName . '.featured = 1 AND ' . $contestTableName . '.sponsored = 1');
      else if ($params['criteria'] == 4)
        $select->where($contestTableName . '.featured = 0 AND ' . $contestTableName . '.sponsored = 0');
    }

    if (isset($params['info'])) {
      switch ($params['info']) {
        case 'most_viewed':
          $select->order('view_count DESC');
          break;
        case 'most_liked':
          $select->order('like_count DESC');
          break;
        case 'most_commented':
          $select->order('comment_count DESC');
          break;
        case "view_count":
          $select->order($contestTableName . '.view_count DESC');
          break;
        case "favourite_count":
          $select->order($contestTableName . '.favourite_count DESC');
          break;
        case "most_favourite":
          $select->order($contestTableName . '.favourite_count DESC');
          break;
        case 'random':
          $select->order('Rand()');
          break;
        case "sponsored" :
          $select->where($contestTableName . '.sponsored' . ' = 1')
                  ->order($contestTableName . '.contest_id DESC');
          break;
        case "hot" :
          $select->where($contestTableName . '.hot' . ' = 1')
                  ->order($contestTableName . '.contest_id DESC');
          break;
        case "featured" :
          $select->where($contestTableName . '.featured' . ' = 1')
                  ->order($contestTableName . '.contest_id DESC');
          break;
        case "creation_date":
          $select->order($contestTableName . '.creation_date DESC');
          break;
        case "modified_date":
          $select->order($contestTableName . '.modified_date DESC');
          break;
      }
    }

    if (isset($params['starttime']) && isset($params['endtime']))
      $select->where("DATE_FORMAT(" . $contestTableName . ".endtime, '%Y-%m-%d') between ('" . date('Y-m-d', strtotime($params["starttime"])) . "') and ('" . date('Y-m-d', strtotime($params["endtime"])) . "')");
    if (isset($params['limit']))
      $select->limit($params['limit']);

    if (isset($params['popularity']) && $params['popularity'] == "You May Also Like") {
      $select->order('RAND() DESC');
    }
    $select->order($contestTableName . '.creation_date DESC');

    //don't show other module contests
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.other.modulecontests', 1) && empty($params['resource_type'])) {
      $select->where($contestTableName . '.resource_type IS NULL')
              ->where($contestTableName . '.resource_id =?', 0);
    } else if (!empty($params['resource_type']) && !empty($params['resource_id'])) {
      $select->where($contestTableName . '.resource_type =?', $params['resource_type'])
              ->where($contestTableName . '.resource_id =?', $params['resource_id']);
    } else if(!empty($params['resource_type'])) {
      $select->where($contestTableName . '.resource_type =?', $params['resource_type']);
    }

    if (isset($params['fetchAll'])) {
      return $this->fetchAll($select);
    } else {
      return $select;
    }
  }

  public function getOfTheDayResults() {

    $select = $this->select()
            ->from($this->info('name'), array('*'))
            ->where('offtheday =?', 1)
            ->where('startdate <= DATE(NOW())')
            ->where('enddate >= DATE(NOW())')
            ->order('RAND()');
    return $this->fetchRow($select);
  }

}
