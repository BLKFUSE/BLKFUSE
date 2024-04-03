<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Widget_VoteGraphController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $entryId = isset($_POST['entry_id']) ? $_POST['entry_id'] : 0;
    if ($entryId) {
      $subject = Engine_Api::_()->getItem('participant', $entryId);
    } else {
      if (!Engine_Api::_()->core()->hasSubject('participant'))
        return $this->setNoRender;
      else
        $subject = Engine_Api::_()->core()->getSubject();
    }
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);

    if (!empty($searchArray)) {
      foreach ($searchArray as $key => $search) {
        $params[$key] = $search;
      }
    }
    $show_criterias = $params['criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;
    if(empty($sescontest_widget)) {
      return $this->setNoRender();
    }
    $this->view->view_type = $interval = isset($_POST['type']) ? $_POST['type'] : $params['openViewType'];

    $this->view->subject = $subject;
    $contest = Engine_Api::_()->getItem('contest', $subject->contest_id);
    $votingStartTime = $contest->votingstarttime;
    $votingEndTime = $contest->votingendtime;
    $dateArray = $this->createDateRangeArray($contest->joinstarttime, $contest->votingendtime, $interval);
    $votetable = Engine_Api::_()->getDbTable('votes', 'sescontest');
    $voteSelect = $votetable->select()->from($votetable->info('name'), array(new Zend_Db_Expr('"vote" AS type'), 'SUM(jury_vote_count) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'));
    $voteSelect->where('contest_id=?', $contest->contest_id)
            ->where('participant_id =?', $subject->participant_id);
    if ($interval == 'monthly')
      $voteSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $voteSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $voteSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $voteSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $voteSelect->group("HOUR(creation_date)");
    }
    $likeTable = Engine_Api::_()->getDbTable('likes', 'sesbasic');
    $likeSelect = $likeTable->select()->from($likeTable->info('name'), array(new Zend_Db_Expr('"like" AS type'), 'COUNT(like_id) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'))
            ->where('resource_type =?', 'participant')
            ->where('resource_id =?', $subject->participant_id);
    if ($interval == 'monthly')
      $likeSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $likeSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $likeSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $likeSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $likeSelect->group("HOUR(creation_date)");
    }

    $commentTable = Engine_Api::_()->getDbTable('comments', 'core');
    $commentSelect = $commentTable->select()->from($commentTable->info('name'), array(new Zend_Db_Expr('"comment" AS type'), 'COUNT(comment_id) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'))
            ->where('resource_type =?', 'participant')
            ->where('resource_id =?', $subject->participant_id);
    if ($interval == 'monthly')
      $commentSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $commentSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $commentSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $commentSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $commentSelect->group("HOUR(creation_date)");
    }

    $favouriteTable = Engine_Api::_()->getDbTable('favourites', 'sescontest');
    $favouritesSelect = $favouriteTable->select()->from($favouriteTable->info('name'), array(new Zend_Db_Expr('"favourite" AS type'), 'COUNT(favourite_id) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'))
            ->where('resource_type =?', 'participant')
            ->where('resource_id =?', $subject->participant_id);
    if ($interval == 'monthly')
      $favouritesSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $favouritesSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $favouritesSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $favouritesSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $favouritesSelect->group("HOUR(creation_date)");
    }

    $viewTable = Engine_Api::_()->getDbTable('recentlyviewitems', 'sescontest');
    $viewSelect = $viewTable->select()->from($viewTable->info('name'), array(new Zend_Db_Expr('"view" AS type'), 'COUNT(recentlyviewed_id) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'))
            ->where('resource_type =?', 'participant')
            ->where('resource_id =?', $subject->participant_id);
    if ($interval == 'monthly')
      $viewSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $viewSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $viewSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $viewSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $viewSelect->group("HOUR(creation_date)");
    }

    $dataSelect = $viewSelect . '  UNION  ' . $favouritesSelect . '  UNION  ' . $commentSelect . '  UNION  ' . $likeSelect . '  UNION ' . $voteSelect;
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $results = $db->query($dataSelect)->fetchAll();

    $var1 = $var2 = $var3 = $var4 = $var5 = $var6 = array();
    $array1 = $array2 = $array3 = $array4 = $array5 = array();
    if ($interval == 'monthly') {
      $this->view->headingTitle = $this->view->translate("Monthly Vote Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Monthly Votes");
      $this->view->likeHeadingTitle = $this->view->translate("Monthly Like Report For ") . $subject->getTitle();
      $this->view->likeXAxisTitle = $this->view->translate("Monthly Likes");
      $this->view->commentHeadingTitle = $this->view->translate("Monthly Comment Report For ") . $subject->getTitle();
      $this->view->commentXAxisTitle = $this->view->translate("Monthly Comments");
      $this->view->favouriteHeadingTitle = $this->view->translate("Monthly Favourite Report For ") . $subject->getTitle();
      $this->view->favouriteXAxisTitle = $this->view->translate("Monthly Favourites");
      $this->view->viewHeadingTitle = $this->view->translate("Monthly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Monthly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'vote')
          $array1[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'like')
          $array2[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'comment')
          $array3[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'favourite')
          $array4[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array5[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
      }
      foreach ($dateArray as $date) {
        if (!$is_ajax)
          $var2[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var2[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));
       
        if (isset($array1[date('Y-m', strtotime($date))])) {
          $var1[] = (int) $array1[date('Y-m', strtotime($date))];
        } else {
          $var1[] = 0;
        }
        if (isset($array2[date('Y-m', strtotime($date))])) {
          $var3[] = (int) $array2[date('Y-m', strtotime($date))];
        } else {
          $var3[] = 0;
        }
        if (isset($array3[date('Y-m', strtotime($date))])) {
          $var4[] = (int) $array3[date('Y-m', strtotime($date))];
        } else {
          $var4[] = 0;
        }
        if (isset($array4[date('Y-m', strtotime($date))])) {
          $var5[] = (int) $array4[date('Y-m', strtotime($date))];
        } else {
          $var5[] = 0;
        }
        if (isset($array5[date('Y-m', strtotime($date))])) {
          $var6[] = (int) $array5[date('Y-m', strtotime($date))];
        } else {
          $var6[] = 0;
        }
      }
    } elseif ($interval == 'weekly') {
      $this->view->headingTitle = $this->view->translate("Weekly Vote Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Weekly Votes");
      $this->view->likeHeadingTitle = $this->view->translate("Weekly Like Report For ") . $subject->getTitle();
      $this->view->likeXAxisTitle = $this->view->translate("Weekly Likes");
      $this->view->commentHeadingTitle = $this->view->translate("Weekly Comment Report For ") . $subject->getTitle();
      $this->view->commentXAxisTitle = $this->view->translate("Weekly Comments");
      $this->view->favouriteHeadingTitle = $this->view->translate("Weekly Favourite Report For ") . $subject->getTitle();
      $this->view->favouriteXAxisTitle = $this->view->translate("Weekly Favourites");
      $this->view->viewHeadingTitle = $this->view->translate("Weekly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Weekly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'vote')
          $array1[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'like')
          $array2[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'comment')
          $array3[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'favourite')
          $array4[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array5[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
      }
      $previousYear = '';
      foreach ($dateArray as $date) {
        $year = date('Y', strtotime($date));
        if ($previousYear != $year)
          $yearString = '-' . $year;
        else
          $yearString = '';
        if (!$is_ajax)
          $var2[] = '"' . (date("d-M", strtotime($date))) . $yearString . '"';
        else
          $var2[] = (date("d-M", strtotime($date))) . $yearString;
        if (isset($array1[date('Y-m-d', strtotime($date))])) {
          $var1[] = (int) $array1[date('Y-m-d', strtotime($date))];
        } else {
          $var1[] = 0;
        }
        if (isset($array2[date('Y-m-d', strtotime($date))])) {
          $var3[] = (int) $array2[date('Y-m-d', strtotime($date))];
        } else {
          $var3[] = 0;
        }
        if (isset($array3[date('Y-m-d', strtotime($date))])) {
          $var4[] = (int) $array3[date('Y-m-d', strtotime($date))];
        } else {
          $var4[] = 0;
        }
        if (isset($array4[date('Y-m-d', strtotime($date))])) {
          $var5[] = (int) $array4[date('Y-m-d', strtotime($date))];
        } else {
          $var5[] = 0;
        }
        if (isset($array5[date('Y-m-d', strtotime($date))])) {
          $var6[] = (int) $array5[date('Y-m-d', strtotime($date))];
        } else {
          $var6[] = 0;
        }
        $previousYear = $year;
      }
    } elseif ($interval == 'daily') {
      $this->view->headingTitle = $this->view->translate("Daily Vote Report for ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Daily Votes");
      $this->view->likeHeadingTitle = $this->view->translate("Daily Like Report for ") . $subject->getTitle();
      $this->view->likeXAxisTitle = $this->view->translate("Daily Likes");
      $this->view->commentHeadingTitle = $this->view->translate("Daily Comment Report for ") . $subject->getTitle();
      $this->view->commentXAxisTitle = $this->view->translate("Daily Comments");
      $this->view->favouriteHeadingTitle = $this->view->translate("Daily Favourite Report for ") . $subject->getTitle();
      $this->view->favouriteXAxisTitle = $this->view->translate("Daily Favourites");
      $this->view->viewHeadingTitle = $this->view->translate("Daily Views Report for ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Daily Views");
      foreach ($results as $result) {
        if ($result['type'] == 'vote')
          $array1[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'like')
          $array2[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'comment')
          $array3[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'favourite')
          $array4[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array5[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
      }
      foreach ($dateArray as $date) {
        if (!$is_ajax)
          $var2[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var2[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));
        if (isset($array1[$date])) {
          $var1[] = (int) $array1[$date];
        } else {
          $var1[] = 0;
        }
        if (isset($array2[$date])) {
          $var3[] = (int) $array2[$date];
        } else {
          $var3[] = 0;
        }
        if (isset($array3[$date])) {
          $var4[] = (int) $array3[$date];
        } else {
          $var4[] = 0;
        }
        if (isset($array4[$date])) {
          $var5[] = (int) $array4[$date];
        } else {
          $var5[] = 0;
        }
        if (isset($array5[$date])) {
          $var6[] = (int) $array5[$date];
        } else {
          $var6[] = 0;
        }
      }
    } elseif ($interval == 'hourly') {
      $this->view->headingTitle = $this->view->translate("Hourly Vote Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Hourly Votes");
      $this->view->likeHeadingTitle = $this->view->translate("Hourly Like Report For ") . $subject->getTitle();
      $this->view->likeXAxisTitle = $this->view->translate("Hourly Likes");
      $this->view->commentHeadingTitle = $this->view->translate("Hourly Comment Report For ") . $subject->getTitle();
      $this->view->commentXAxisTitle = $this->view->translate("Hourly Comments");
      $this->view->favouriteHeadingTitle = $this->view->translate("Hourly Favourite Report For ") . $subject->getTitle();
      $this->view->favouriteXAxisTitle = $this->view->translate("Hourly Favourites");
      $this->view->viewHeadingTitle = $this->view->translate("Hourly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Hourly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'vote')
          $array1[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'like')
          $array2[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'comment')
          $array3[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'favourite')
          $array4[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array5[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
      }
      foreach ($dateArray as $date) {
        $time = date("h A", strtotime($date . ':00:00'));
        if (!$is_ajax)
          $var2[] = '"' . $time . '"';
        else
          $var2[] = $time;
        if (isset($array1[$date])) {
          $var1[] = (int) $array1[$date];
        } else {
          $var1[] = 0;
        }
        if (isset($array2[$date])) {
          $var3[] = (int) $array2[$date];
        } else {
          $var3[] = 0;
        }
        if (isset($array3[$date])) {
          $var4[] = (int) $array3[$date];
        } else {
          $var4[] = 0;
        }
        if (isset($array4[$date])) {
          $var5[] = (int) $array4[$date];
        } else {
          $var5[] = 0;
        }
        if (isset($array5[$date])) {
          $var6[] = (int) $array5[$date];
        } else {
          $var6[] = 0;
        }
      }
    }

    if ($is_ajax) {
      echo json_encode(array('date' => $var2, 'voteCount' => $var1, 'likeCount' => $var3, 'commentCount' => $var4, 'favouriteCount' => $var5, 'viewCount' => $var6, 'headingTitle' => $this->view->headingTitle, 'XAxisTitle' => $this->view->XAxisTitle, 'likeHeadingTitle' => $this->view->likeHeadingTitle, 'likeXAxisTitle' => $this->view->likeXAxisTitle, 'commentHeadingTitle' => $this->view->commentHeadingTitle, 'commentXAxisTitle' => $this->view->commentXAxisTitle, 'favouriteHeadingTitle' => $this->view->favouriteHeadingTitle, 'favouriteXAxisTitle' => $this->view->favouriteXAxisTitle, 'viewHeadingTitle' => $this->view->viewHeadingTitle, 'viewXAxisTitle' => $this->view->viewXAxisTitle));
      die;
    } else {
      $this->view->date = $var2;
      $this->view->voteCount = $var1;
      $this->view->like_count = $var3;
      $this->view->comment_count = $var4;
      $this->view->favourite_count = $var5;
      $this->view->view_count = $var6;
    }
  }

  // create date range from 2 given dates.
  public function createDateRangeArray($strDateFrom = '', $strDateTo = '', $interval) {
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates. 
    $aryRange = array();
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    if ($iDateTo >= $iDateFrom) {
      if ($interval == 'monthly') {
        array_push($aryRange, date('Y-m', $iDateFrom));
        $iDateFrom = strtotime('+1 Months', $iDateFrom);
        while ($iDateFrom < $iDateTo) {
          array_push($aryRange, date('Y-m', $iDateFrom));
          $iDateFrom += strtotime('+1 Months', $iDateFrom);
        }
      } elseif ($interval == 'weekly') {
        array_push($aryRange, date('Y-m-d', strtotime("last Sunday", $iDateFrom)));
        $iDateFrom = strtotime('+1 Weeks', $iDateFrom);
        while ($iDateFrom < $iDateTo) {
          array_push($aryRange, date('Y-m-d', strtotime("last Sunday", $iDateFrom)));
          $iDateFrom = strtotime('+1 Weeks', $iDateFrom);
        }
      } elseif ($interval == 'daily') {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo) {
          $iDateFrom += 86400; // add 24 hours
          array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
      } elseif ($interval == 'hourly') {
        $iDateFrom = strtotime(date('Y-m-d 00:00:00'));
        $iDateTo = strtotime('+1 Day', $iDateFrom);

        array_push($aryRange, date('Y-m-d H', $iDateFrom));
        $iDateFrom = strtotime('+1 Hours', ($iDateFrom));

        while ($iDateFrom < $iDateTo) {
          array_push($aryRange, date('Y-m-d H', $iDateFrom));
          $iDateFrom = strtotime('+1 Hours', ($iDateFrom));
        }
      }
    }
    $preserve = $aryRange;
    return $preserve;
  }

}
