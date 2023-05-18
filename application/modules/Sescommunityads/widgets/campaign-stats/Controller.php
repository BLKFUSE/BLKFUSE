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
class Sescommunityads_Widget_CampaignStatsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $campaign_id = isset($_POST['campaign_id']) ? $_POST['campaign_id'] : 0;
    if ($campaign_id) {
      $subject = Engine_Api::_()->getItem('sescommunityads_campaign', $campaign_id);
    } else {
      if (!Engine_Api::_()->core()->hasSubject())
        return $this->setNoRender();
      else
        $subject = Engine_Api::_()->core()->getSubject();
    }
    if(!$subject->count())
      return $this->setNoRender();
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
    $campaign = $subject;
    $campaign_id = $campaign->getIdentity();
    $dateArray = Engine_Api::_()->sescommunityads()->createDateRangeArray($startDate, $endDate, $interval);
    $viewtable = Engine_Api::_()->getDbTable('viewstats', 'sescommunityads');

    $adTableName = Engine_Api::_()->getDbTable('sescommunityads', 'sescommunityads')->info('name');
    $viewtableName = $viewtable->info('name');
    $viewSelect = $viewtable->select()->from($viewtable->info('name'), array(new Zend_Db_Expr('"view" AS type'), 'COUNT(*) as total', 'creation_date', "DATE_FORMAT($viewtableName.creation_date,'%Y-%m-%d %H') as hourtime"));
    $viewSelect->where($viewtableName.'.creation_date BETWEEN "'.$startDate.'" and "'.$endDate.'"');
    $viewSelect->where($viewtableName.'.campaign_id =?',$campaign_id);
    if ($interval == 'monthly')
      $viewSelect->group("month($viewtableName.creation_date)");
    elseif ($interval == 'weekly')
      $viewSelect->group("week($viewtableName.creation_date)");
    elseif ($interval == 'daily')
      $viewSelect->group("DATE_FORMAT($viewtableName.creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $viewSelect->where("DATE_FORMAT($viewtableName.creation_date,'%Y-%m-%d') =?", date('Y-m-d'));
      $viewSelect->group("HOUR($viewtableName.creation_date)");
    }
    $viewSelect->setIntegrityCheck(false)
                ->joinLeft($adTableName,$adTableName.'.sescommunityad_id ='.$viewtable->info('name').'.sescommunityad_id',null)
                ->where($adTableName.'.is_deleted =?',0);
    $clickTable = Engine_Api::_()->getDbTable('clickstats', 'sescommunityads');
    $clickTableName = $clickTable->info('name');
    $clickSelect = $clickTable->select()->from($clickTable->info('name'), array(new Zend_Db_Expr('"click" AS type'), 'COUNT(*) as total', 'creation_date', "DATE_FORMAT($clickTableName.creation_date,'%Y-%m-%d %H') as hourtime"));
    $clickSelect->where($clickTableName.'.campaign_id =?',$campaign_id);
    if ($interval == 'monthly')
      $clickSelect->group("month($clickTableName.creation_date)");
    elseif ($interval == 'weekly')
      $clickSelect->group("week($clickTableName.creation_date)");
    elseif ($interval == 'daily')
      $clickSelect->group("DATE_FORMAT($clickTableName.creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $clickSelect->where("DATE_FORMAT($clickTableName.creation_date,'%Y-%m-%d') =?", date('Y-m-d'));
      $clickSelect->group("HOUR($clickTableName.creation_date)");
    }
    $clickSelect->setIntegrityCheck(false)
                ->joinLeft($adTableName,$adTableName.'.sescommunityad_id ='.$clickTableName.'.sescommunityad_id',null)
                ->where($adTableName.'.is_deleted =?',0);

    $ctlTable = Engine_Api::_()->getDbTable('campaignstats', 'sescommunityads');
    $ctlTableName = $ctlTable->info('name');
    $ctlSelect = $ctlTable->select()->from($ctlTable->info('name'), array(new Zend_Db_Expr('"ctl" AS type'), 'total'=>new Zend_Db_Expr('CAST(((SUM(click)/SUM(view))) AS DECIMAL(6,4)) * 100'), 'creation_date', "DATE_FORMAT($ctlTableName.creation_date,'%Y-%m-%d %H') as hourtime"));
    $ctlSelect->where($ctlTableName.'.campaign_id =?',$campaign_id);
    if ($interval == 'monthly')
      $ctlSelect->group("month($ctlTableName.creation_date)");
    elseif ($interval == 'weekly')
      $ctlSelect->group("week($ctlTableName.creation_date)");
    elseif ($interval == 'daily')
      $ctlSelect->group("DATE_FORMAT($ctlTableName.creation_date, '%Y-%m-%d')");
    elseif ($interval == 'hourly') {
      $ctlSelect->where("DATE_FORMAT($ctlTableName.creation_date,'%Y-%m-%d') =?", date('Y-m-d'));
      $ctlSelect->group("HOUR($ctlTableName.creation_date)");
    }

    $ctlSelect->setIntegrityCheck(false)
                ->joinLeft($adTableName,$adTableName.'.sescommunityad_id ='.$ctlTable->info('name').'.sescommunityad_id',null)
                ->where($adTableName.'.is_deleted =?',0);
    $dataSelect = $viewSelect . '  UNION  ' . $clickSelect . '  UNION  ' . $ctlSelect ;

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
        if ($result['type'] == 'ctl')
          $array1[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'click')
          $array2[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m', (strtotime($result['creation_date'])))] = $result['total'];
      }
      foreach ($dateArray as $date) {

        if (!$is_ajax)
          $var4[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var4[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));

        if (isset($array1[date('Y-m', strtotime($date))])) {
          $var1[] = (int) $array1[date('Y-m', strtotime($date))];
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
        if ($result['type'] == 'ctl')
          $array1[date('Y-m-d', strtotime("last Sunday", strtotime($result['creation_date'])))] = $result['total'];
        elseif ($result['type'] == 'click')
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

        if (isset($array1[date('Y-m-d', strtotime($date))])) {
          $var1[] = (int) $array1[date('Y-m-d', strtotime($date))];
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
        if ($result['type'] == 'ctl')
          $array1[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'click')
          $array2[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'view')
          $array3[date('Y-m-d', strtotime($result['creation_date']))] = $result['total'];
      }
      foreach ($dateArray as $date) {
         if (!$is_ajax)
          $var4[] = '"' . date("d", strtotime($date)) . '-' . date("M", strtotime($date)) . '"';
        else
          $var4[] = date("d", strtotime($date)) . '-' . date("M", strtotime($date));

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
       if ($result['type'] == 'ctl')
          $array1[date('Y-m-d H', strtotime($result['creation_date']))] = $result['total'];
        elseif ($result['type'] == 'click')
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
        if (isset($array1[$date])) {
          $var1[] = (int) $array1[$date];
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
      $this->view->clickCount = $var3;
      $this->view->viewCount = $var2;
      $this->view->ctlBaseTitle = $this->view->ctlXAxisTitle;
      $this->view->clickBaseTitle = $this->view->clickXAxisTitle;
      $this->view->ctlinecolor = $this->_getParam('ctlinecolor','#871209');
      $this->view->clicklinecolor = $this->_getParam('clicklinecolor','#12876');;
      $this->view->viewlinecolor = $this->_getParam('viewlinecolor','#62876');
    }
  }
}
