<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Adstatistics.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_DbTable_Adstatistics extends Engine_Db_Table {

  protected $_name = 'sitead_adstatistics';
  protected $_rowClass = 'Sitead_Model_Adstatistic';

  /* 
   * Get statistics of user ad
   */
  public function getStats($values = array()) {

    // Get viewer
    if (!empty($values['viewer_id'])) {
      $viewer_id = $values['viewer_id'];
    }
    $statsName = $this->info('name');

    // Get viewer's Ads and campaigns
    $useradsTable = Engine_Api::_()->getDbtable('userads', 'sitead');
    $useradsName = $useradsTable->info('name');

    $adcampaignsTable = Engine_Api::_()->getDbtable('adcampaigns', 'sitead');
    $adcampaignsName = $adcampaignsTable->info('name');

    if (!empty($values['ad_subject'])) {
      $subject = $values['ad_subject'];
    }

    // REPORT SUMMARY SUBJECT
    if ($subject == 'ad') {
      $subjectid = 'userad_id';
      $subjecTableName = $useradsName;
      if (!empty($values['ad_list'])) {
        $subject_list_array = $values['ad_list'];
      } elseif (!empty($values['campaign_list'])) {
        $subject_list_array = $values['campaign_list'];
      }
      $subject_title = 'web_name';
    } elseif ($subject == 'campaign') {
      $subjectid = 'adcampaign_id';
      $subjecTableName = $adcampaignsName;
      if (!empty($values['campaign_list'])) {
        $subject_list_array = $values['campaign_list'];
      }
      $subject_title = 'name';
    }
    $sub_string = '';
    $flag = 0;
    if (!empty($subject_list_array)) {
      $sub_string = (string) ("'" . join("', '", $subject_list_array) . "'");
    }
    if (!empty($values['time_summary'])) {
      if ($values['time_summary'] == 'Monthly') {
        $startTime = date('Y-m', mktime(0, 0, 0, $values['month_start'], date('d'), $values['year_start']));
        $endTime = date('Y-m', mktime(0, 0, 0, $values['month_end'], date('d'), $values['year_end']));
      } else {
        if (!empty($values['start_daily_time'])) {
          $start = $values['start_daily_time'];
        }
        if (!empty($values['start_daily_time'])) {
          $end = $values['end_daily_time'];
        }
        $startTime = date('Y-m-d', $start);
        $endTime = date('Y-m-d', $end);
      }
    }

    $statsSelect = $this->select();

    $statsSelect
            ->setIntegrityCheck(false)
            ->from($statsName, array('adstatistic_id', $subjectid, 'adcampaign_id', 'response_date', 'SUM(value_click) as clicks', 'SUM(value_view) as views'));
    if ($subject == 'ad') {
      $statsSelect->join($adcampaignsName, $adcampaignsName . '.adcampaign_id = ' . $statsName . '.adcampaign_id', array('name'));
    }
    $statsSelect
            ->join($subjecTableName, $subjecTableName . '.' . $subjectid . '= ' . $statsName . '.' . $subjectid, array('owner_id', $subject_title))
            ->order($statsName . '.response_date DESC')
            ->distinct(true);

    if (!empty($values['user_report'])) {
      $statsSelect->where($subjecTableName . '.owner_id = ?', $viewer_id);

      if ($sub_string != '' && !empty($values['ad_list'])) {
        $id = 'userad_id';
        $statsSelect->where($statsName . '.' . $id . ' in (?)', new Zend_Db_Expr($sub_string));
      }
      if ($sub_string != '' && !empty($values['campaign_list'])) {
        $id = 'adcampaign_id';
        $statsSelect->where($statsName . '.' . $id . ' in (?)', new Zend_Db_Expr($sub_string));
      }
    }

    if (!empty($values['toValues'])) {
      $users_array = explode(',', $values['toValues']);
      $users_string = (string) ("'" . join("', '", $users_array) . "'");
      $statsSelect->where($subjecTableName . '.owner_id  in (?)', new Zend_Db_Expr($users_string));
    }

    if (!empty($values['admin_report'])) {
      $statsSelect->group($subjecTableName . '.owner_id');

      if (!empty($values['campaigns'])) {
        $statsSelect->where($subjecTableName . '.' . $subject_title . '  LIKE ?', '%' . $values['campaigns'] . '%');
      } elseif (!empty($values['ads'])) {
        $statsSelect->where($subjecTableName . '.' . $subject_title . '  LIKE ?', '%' . $values['ads'] . '%');
      }
    }
    $statsSelect->group($statsName . '.' . $subjectid)
            ->order($statsName . '.' . $subjectid . ' DESC');

    if (!empty($values['time_summary'])) {

      switch ($values['time_summary']) {

        case 'Monthly':
          $statsSelect
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m') >= ?", $startTime)
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m') <= ?", $endTime)
                  ->group("DATE_FORMAT(" . $statsName . " .response_date, '%m')");
          break;

        case 'Daily':
          $statsSelect
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m-%d') >= ?", $startTime)
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m-%d') <= ?", $endTime)
                  ->group("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m-%d')");
          break;
      }
    }
    return $statsSelect;
  }
  
  /* 
   * Get total statistics of user ad
   */
  public function getTotalStats($values = array()) {

    // Get viewer
    if (!empty($values['viewer_id'])) {
      $viewer_id = $values['viewer_id'];
    }
    $statsName = $this->info('name');

    // Get viewer's Ads and campaigns
    $useradsTable = Engine_Api::_()->getDbtable('userads', 'sitead');
    $useradsName = $useradsTable->info('name');

    $adcampaignsTable = Engine_Api::_()->getDbtable('adcampaigns', 'sitead');
    $adcampaignsName = $adcampaignsTable->info('name');

    if (!empty($values['ad_subject'])) {
      $subject = $values['ad_subject'];
    }

    // REPORT SUMMARY SUBJECT
    if ($subject == 'ad') {
      $subjectid = 'userad_id';
      $subjecTableName = $useradsName;
      if (!empty($values['ad_list'])) {
        $subject_list_array = $values['ad_list'];
      } elseif (!empty($values['campaign_list'])) {
        $subject_list_array = $values['campaign_list'];
      }
      $subject_title = 'web_name';
    } elseif ($subject == 'campaign') {
      $subjectid = 'adcampaign_id';
      $subjecTableName = $adcampaignsName;
      if (!empty($values['campaign_list'])) {
        $subject_list_array = $values['campaign_list'];
      }
      $subject_title = 'name';
    }
    $sub_string = '';
    $flag = 0;
    if (!empty($subject_list_array)) {
      $sub_string = (string) ("'" . join("', '", $subject_list_array) . "'");
    }
    if (!empty($values['time_summary'])) {
      if ($values['time_summary'] == 'Monthly') {
        $startTime = date('Y-m', mktime(0, 0, 0, $values['month_start'], date('d'), $values['year_start']));
        $endTime = date('Y-m', mktime(0, 0, 0, $values['month_end'], date('d'), $values['year_end']));
      } else {
        if (!empty($values['start_daily_time'])) {
          $start = $values['start_daily_time'];
        }
        if (!empty($values['start_daily_time'])) {
          $end = $values['end_daily_time'];
        }
        $startTime = date('Y-m-d', $start);
        $endTime = date('Y-m-d', $end);
      }
    }

    $statsSelect = $this->select();
    $statsSelect
            ->setIntegrityCheck(false)
            ->from($statsName, array('SUM(value_click) as clicks', 'SUM(value_view) as views'));
    if ($subject == 'ad') {
      $statsSelect->join($adcampaignsName, $adcampaignsName . '.adcampaign_id = ' . $statsName . '.adcampaign_id', null);
    }
    $statsSelect
            ->join($subjecTableName, $subjecTableName . '.' . $subjectid . '= ' . $statsName . '.' . $subjectid, null)
            ->distinct(true);

    if (!empty($values['user_report'])) {
      $statsSelect->where($subjecTableName . '.owner_id = ?', $viewer_id);

      if ($sub_string != '' && !empty($values['ad_list'])) {
        $id = 'userad_id';
        $statsSelect->where($statsName . '.' . $id . ' in (?)', new Zend_Db_Expr($sub_string));
      }
      if ($sub_string != '' && !empty($values['campaign_list'])) {
        $id = 'adcampaign_id';
        $statsSelect->where($statsName . '.' . $id . ' in (?)', new Zend_Db_Expr($sub_string));
      }
    }

    if (!empty($values['toValues'])) {
      $users_array = explode(',', $values['toValues']);
      $users_string = (string) ("'" . join("', '", $users_array) . "'");
      $statsSelect->where($subjecTableName . '.owner_id  in (?)', new Zend_Db_Expr($users_string));
    }

    if (!empty($values['admin_report'])) {

      if (!empty($values['campaigns'])) {
        $statsSelect->where($subjecTableName . '.' . $subject_title . '  LIKE ?', '%' . $values['campaigns'] . '%');
      } elseif (!empty($values['ads'])) {
        $statsSelect->where($subjecTableName . '.' . $subject_title . '  LIKE ?', '%' . $values['ads'] . '%');
      }
    }

    if (!empty($values['time_summary'])) {

      switch ($values['time_summary']) {

        case 'Monthly':
          $statsSelect
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m') >= ?", $startTime)
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m') <= ?", $endTime);
          break;

        case 'Daily':
          $statsSelect
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m-%d') >= ?", $startTime)
                  ->where("DATE_FORMAT(" . $statsName . " .response_date, '%Y-%m-%d') <= ?", $endTime);
          break;
      }
    }
    return $statsSelect;
  }

  public function removeOldStatistics($whereArray = array()) {
    if (!isset($whereArray['response_date < ?'])) {
      $whereArray['response_date < ?'] = Engine_Api::_()->sitead()->getAdStaticsLimitDate();
    }
    $this->delete($whereArray);
  }

}