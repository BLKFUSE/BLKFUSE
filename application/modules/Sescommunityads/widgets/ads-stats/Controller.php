<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Widget_AdsStatsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $sescommunityad_id = isset($_POST['sescommunityad_id']) ? $_POST['sescommunityad_id'] : 0;
    if ($sescommunityad_id) {
      $subject = Engine_Api::_()->getItem('sescommunityads', $sescommunityad_id);
    } else {
      if (!Engine_Api::_()->core()->hasSubject())
        return $this->setNoRender();
      else
        $subject = Engine_Api::_()->core()->getSubject();
    }
    $this->view->ad = $subject;
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);

    if (!empty($searchArray)) {
      foreach ($searchArray as $key => $search) {
        $params[$key] = $search;
      }
    }
    $show_criterias = $this->_getParam('show_criteria',array('daily','monthly','weekly','hourly'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $this->view->view_type = $interval = isset($_POST['type']) ? $_POST['type'] : $this->_getParam('openViewType',$show_criterias['0']);
    
    $startDate = $this->_getParam('startdate',0);
    $endDate = $this->_getParam('enddate',0);

    if(empty($startDate) && empty($endDate)){
      
       $startDate = date('Y-m-d',strtotime("-30 days"));
       $endDate = date('Y-m-d');
      
    }else if(!empty($startDate) || !empty($endDate)){
       if(empty($startDate))
        $startDate = date('Y-m-d',strtotime("-15 days",strtotime($endDate)));
       if(empty($endDate))
        $endDate = date('Y-m-d',strtotime("+15 days",strtotime($startDate)));
    }
    $this->view->enddate = $endDate;
    $this->view->startdate = $startDate;

    $this->view->subject = $subject;
    $ad = $subject;
    $sescommunityad_id = $ad->getIdentity();
    $dateArray = Engine_Api::_()->sescommunityads()->createDateRangeArray($startDate, $endDate, $interval);
    $viewtable = Engine_Api::_()->getDbTable('viewstats', 'sescommunityads');

    $viewSelect = $viewtable->select()->from($viewtable->info('name'), array(new Zend_Db_Expr('"view" AS type'), 'COUNT(*) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'));
    $viewSelect->where('DATE_FORMAT(creation_date, "%Y-%m-%d") BETWEEN "'.$startDate.'" and "'.$endDate.'"');
    $viewSelect->where('sescommunityad_id =?',$sescommunityad_id);
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

    $clickTable = Engine_Api::_()->getDbTable('clickstats', 'sescommunityads');
    $clickSelect = $clickTable->select()->from($clickTable->info('name'), array(new Zend_Db_Expr('"click" AS type'), 'COUNT(*) as total', 'creation_date', 'DATE_FORMAT(creation_date,"%Y-%m-%d %H") as hourtime'));
    $clickSelect->where('sescommunityad_id =?',$sescommunityad_id);
    if ($interval == 'monthly')
      $clickSelect->group("month(creation_date)");
    elseif ($interval == 'weekly')
      $clickSelect->group("week(creation_date)");
    elseif ($interval == 'daily')
      $clickSelect->group("DATE_FORMAT(creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $clickSelect->where('DATE_FORMAT(creation_date,"%Y-%m-%d") =?', date('Y-m-d'));
      $clickSelect->group("HOUR(creation_date)");
    }


    $dataSelect = $viewSelect . '  UNION  ' . $clickSelect;
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $results = $db->query($dataSelect)->fetchAll();

    $var1 = $var2 = $var3 = $var4  = array();
    $array1 = $array2 = $array3  = array();
    if ($interval == 'monthly') {
      $this->view->headingTitle = $this->view->translate("Monthly Stats Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Monthly Stats");
      $this->view->clickHeadingTitle = $this->view->translate("Monthly Click Report For ") . $subject->getTitle();
      $this->view->clickXAxisTitle = $this->view->translate("Monthly Clicks");
      $this->view->ctlHeadingTitle = $this->view->translate("Monthly CTL Report For ") . $subject->getTitle();
      $this->view->ctlXAxisTitle = $this->view->translate("Monthly CTLs");
      $this->view->viewHeadingTitle = $this->view->translate("Monthly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Monthly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'click')
          $array2[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
      }
      foreach ($dateArray as $date) {
        if (!$is_ajax)
          $var4[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var4[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));

        if (isset($array3[date('Y-m', strtotime($date))])) {
          $click = isset($array2[date('Y-m', strtotime($date))]) ? $array2[date('Y-m', strtotime($date))] : 0;
          $var1[] = (int) number_format(($click/$array2[date('Y-m', strtotime($date))])*100 ,4);
        } else {
          $var1[] = 0;
        }
        if (isset($array2[date('Y-m', strtotime($date))])) {
          $var2[] = (int) $array2[date('Y-m', strtotime($date))];
        } else {
          $var2[] = 0;
        }
        if (isset($array3[date('Y-m', strtotime($date))])) {
          $var3[] = (int) $array3[date('Y-m', strtotime($date))];
        } else {
          $var3[] = 0;
        }
      }
    } elseif ($interval == 'weekly') {
      $this->view->headingTitle = $this->view->translate("Weekly Stat Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Weekly Stats");
      $this->view->clickHeadingTitle = $this->view->translate("Weekly Click Report For ") . $subject->getTitle();
      $this->view->clickXAxisTitle = $this->view->translate("Weekly Clicks");
      $this->view->ctlHeadingTitle = $this->view->translate("Weekly CTL Report For ") . $subject->getTitle();
      $this->view->ctlXAxisTitle = $this->view->translate("Weekly CTLs");
      $this->view->viewHeadingTitle = $this->view->translate("Weekly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Weekly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'click')
          $array2[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
      }
      $previousYear = '';
      foreach ($dateArray as $date) {
        $year = date('Y', strtotime($date));
        if ($previousYear != $year)
          $yearString = '-' . $year;
        else
          $yearString = '';

        if (!$is_ajax)
          $var4[] = '"' . (date("d-M", strtotime($date))) . $yearString . '"';
        else
          $var4[] = (date("d-M", strtotime($date))) . $yearString;

        if (isset($array3[date('Y-m-d', strtotime($date))])) {
          $click = isset($array2[date('Y-m-d', strtotime($date))]) ? $array2[date('Y-m-d', strtotime($date))] : 0;
          $var1[] = (int)number_format(($click/$array2[date('Y-m-d', strtotime($date))])*100 ,4);
        } else {
          $var1[] = 0;
        }


        if (isset($array2[date('Y-m-d', strtotime($date))])) {
          $var2[] = (int) $array2[date('Y-m-d', strtotime($date))];
        } else {
          $var2[] = 0;
        }
        if (isset($array3[date('Y-m-d', strtotime($date))])) {
          $var3[] = (int) $array3[date('Y-m-d', strtotime($date))];
        } else {
          $var3[] = 0;
        }

        $previousYear = $year;
      }
    } elseif ($interval == 'daily') {
      $this->view->headingTitle = $this->view->translate("Daily Stat Report for ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Daily Stats");
      $this->view->clickHeadingTitle = $this->view->translate("Daily Click Report for ") . $subject->getTitle();
      $this->view->clickXAxisTitle = $this->view->translate("Daily Clicks");
      $this->view->ctlHeadingTitle = $this->view->translate("Daily CTL Report for ") . $subject->getTitle();
      $this->view->ctlXAxisTitle = $this->view->translate("Daily CTLs");
      $this->view->viewHeadingTitle = $this->view->translate("Daily Views Report for ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Daily Views");
     
      foreach ($results as $result) {
        if ($result['type'] == 'click')
          $array2[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
      }
      foreach ($dateArray as $date) {
         if (!$is_ajax)
          $var4[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var4[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));

        if (isset($array3[$date])) {
          $click = isset($date) ? $array2[$date] : 0;
          $var1[] = (int)number_format(($date)*100 ,4);
        } else {
          $var1[] = 0;
        }


        if (isset($array2[$date])) {
          $var2[] = (int) $array2[$date];
        } else {
          $var2[] = 0;
        }
        if (isset($array3[$date])) {
          $var3[] = (int) $array3[$date];
        } else {
          $var3[] = 0;
        }
      }
    } elseif ($interval == 'hourly') {
      $this->view->headingTitle = $this->view->translate("Hourly Stat Report For ") . $subject->getTitle();
      $this->view->XAxisTitle = $this->view->translate("Hourly Stats");
      $this->view->clickHeadingTitle = $this->view->translate("Hourly Click Report For ") . $subject->getTitle();
      $this->view->clickXAxisTitle = $this->view->translate("Hourly Clicks");
      $this->view->ctlHeadingTitle = $this->view->translate("Hourly CTL Report For ") . $subject->getTitle();
      $this->view->ctlXAxisTitle = $this->view->translate("Hourly CTLs");
      $this->view->viewHeadingTitle = $this->view->translate("Hourly Views Report For ") . $subject->getTitle();
      $this->view->viewXAxisTitle = $this->view->translate("Hourly Views");
      foreach ($results as $result) {
        if ($result['type'] == 'click')
          $array2[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
      }
      foreach ($dateArray as $date) {
        $time = date("h A", strtotime($date . ':00:00'));
        if (!$is_ajax)
          $var4[] = '"' . $time . '"';
        else
          $var4[] = $time;

        if (isset($array3[$date])) {
          $click = isset($date) ? $array2[$date] : 0;
          $var1[] = (int)number_format(($date)*100 ,4);
        } else {
          $var1[] = 0;
        }

        if (isset($array2[$date])) {
          $var2[] = (int) $array2[$date];
        } else {
          $var2[] = 0;
        }
        if (isset($array3[$date])) {
          $var3[] = (int) $array3[$date];
        } else {
          $var3[] = 0;
        }
      }
    }
    if ($is_ajax) {
      echo json_encode(array('date' => $var4, 'ctlCount' => $var1, 'clickCount' => $var2, 'viewCount' => $var3, 'headingTitle' => $this->view->headingTitle, 'XAxisTitle' => $this->view->XAxisTitle, 'clickHeadingTitle' => $this->view->clickHeadingTitle, 'clickXAxisTitle' => $this->view->clickXAxisTitle, 'ctlHeadingTitle' => $this->view->ctlHeadingTitle, 'ctlXAxisTitle' => $this->view->ctlXAxisTitle, 'viewHeadingTitle' => $this->view->viewHeadingTitle, 'viewXAxisTitle' => $this->view->viewXAxisTitle));
      die;
    }else{
      $this->view->date = $var4;
      $this->view->ctlCount = $var1;
      $this->view->clickCount = $var2;
      $this->view->viewCount = $var3;
      $this->view->ctlBaseTitle = $this->view->ctlXAxisTitle;
      $this->view->clickBaseTitle = $this->view->clickXAxisTitle;
      $this->view->ctlinecolor = $this->_getParam('ctlinecolor','#871209');
      $this->view->clicklinecolor = $this->_getParam('clicklinecolor','#12876');;
      $this->view->viewlinecolor = $this->_getParam('viewlinecolor','#62876');
    }
  }
}
