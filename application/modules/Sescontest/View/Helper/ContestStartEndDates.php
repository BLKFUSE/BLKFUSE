<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ContestStartEndDates.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_View_Helper_ContestStartEndDates extends Engine_View_Helper_Locale {

  public function contestStartEndDates($sescontest, $defaultParams = array()) {
    if (!engine_count($defaultParams)) {
      $defaultParams['starttime'] = true;
      $defaultParams['endtime'] = true;
      $defaultParams['joinstarttime'] = true;
      $defaultParams['joinendtime'] = true;
      $defaultParams['votingstarttime'] = true;
      $defaultParams['votingendtime'] = true;
      $defaultParams['resulttime'] = true;
      $defaultParams['timezone'] = true;
    }
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.enable.timezone', 1))
      $defaultParams['timezone'] = false;
    if (!$sescontest)
      return 'No Dates Available';
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    //size full,medium,long,short
    $timeformate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.datetimeformate', 'medium');
    if (isset($defaultParams['starttime']) && $defaultParams['starttime']) {
      $starttimeFull = $this->changeContestDateTime($sescontest->starttime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $starttime = $this->changeContestDateTime($sescontest->starttime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['joinstarttime']) && $defaultParams['joinstarttime']) {
      $joinstarttimeFull = $this->changeContestDateTime($sescontest->joinstarttime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $joinstarttime = $this->changeContestDateTime($sescontest->joinstarttime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['votingstarttime']) && $defaultParams['votingstarttime']) {
      $votingstarttimeFull = $this->changeContestDateTime($sescontest->votingstarttime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $votingstarttime = $this->changeContestDateTime($sescontest->votingstarttime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['resulttime']) && $defaultParams['resulttime']) {
      $resulttimeFull = $this->changeContestDateTime($sescontest->resulttime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $resulttime = $this->changeContestDateTime($sescontest->resulttime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    $timeStr = '';
    if (isset($defaultParams['isPrint'])) {
      $sepratorFull = '<br> - ';
      $sepratorHalf = '<br> - ';
      $lineBreak = '<br>';
    } else if (isset($defaultParams['isBreak'])) {
      $sepratorHalf = '-';
      $lineBreak = '';
      $sepratorFull = '<br><i class="fa fa-caret-right sesbasic_text_light"></i>';
    }elseif (isset($defaultParams['isSesapi']) && $defaultParams['isSesapi']) {
            $sepratorHalf = "ENDDATE";
        $lineBreak = '';
         $sepratorFull = "ENDDATE";
        }
    else {
      $sepratorHalf = '-';
      $lineBreak = '';
      $sepratorFull = '<i class="fa fa-caret-right sesbasic_text_light"></i>';
    }
    if (isset($defaultParams['endtime']) && $defaultParams['endtime']) {
      $endtimeFull = $this->changeContestDateTime($sescontest->endtime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $endtime = $this->changeContestDateTime($sescontest->endtime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['joinendtime']) && $defaultParams['joinendtime']) {
      $joinendtimeFull = $this->changeContestDateTime($sescontest->joinendtime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $joinendtime = $this->changeContestDateTime($sescontest->joinendtime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['votingendtime']) && $defaultParams['votingendtime']) {
      $votingendtimeFull = $this->changeContestDateTime($sescontest->votingendtime, array('timezone' => $sescontest->timezone, 'size' => 'full'));
      $votingendtime = $this->changeContestDateTime($sescontest->votingendtime, array('timezone' => $sescontest->timezone, 'size' => $timeformate));
    }
    if (isset($defaultParams['starttime']) && isset($defaultParams['endtime']) && date('Y-m-d', strtotime($endtime)) == date('Y-m-d', strtotime($starttime))) {
      $timeStr = '<span><b>"'.$view->translate("Contest Start & End Date ").'"</b><span title="' . $view->translate("Start Time & End Time") . $starttimeFull . '">' . $starttime . '</span> ' . $sepratorHalf . ' ' . date('h:i A', strtotime($endtime)) . ' (' . $sescontest->timezone . ')</span>';
    } else {
        
      if (isset($defaultParams['starttime']) && $defaultParams['starttime'] && !$defaultParams['isSesapi'])
        $timeStr = '<span><b>"'.$view->translate("Contest Start & End Date ").'"</b><span title="' . $view->translate("Start Time: ") . $starttimeFull . '">' . $starttime . '</span>';
      if (isset($defaultParams['starttime']) && $defaultParams['starttime'] && $defaultParams['isSesapi']){
                  $timeStr = $starttime ;
      }
      if (isset($defaultParams['endtime']) && $defaultParams['endtime'])
        $timeStr .= '<span title="' . $view->translate("End Time: ") . $endtimeFull . '">' . $sepratorFull . $endtime . $lineBreak . ' (' . $sescontest->timezone . ')</span></span>';
    }
    if (isset($defaultParams['joinstarttime']) && isset($defaultParams['joinendtime']) && date('Y-m-d', strtotime($joinendtime)) == date('Y-m-d', strtotime($joinstarttime))) {
      $timeStr .= '<span><b>"'.$view->translate("Entry Submission Start & End Date ").'"</b><span title="' . $view->translate("Participation Start Time & End Time") . $joinstarttimeFull . '">' . $joinstarttime . '</span> ' . $sepratorHalf . ' ' . date('h:i A', strtotime($joinendtime)) . ' (' . $sescontest->timezone . ')</span>';
    } else {
      if (isset($defaultParams['joinstarttime']) && $defaultParams['joinstarttime'] && !$defaultParams['isSesapi'])
        $timeStr .= '<span><b>"'.$view->translate("Entry Submission Start & End Date ").'"</b><span title="' . $view->translate("Participation Start Time: ") . $joinstarttimeFull . '">' . $joinstarttime . '</span>';
      if (isset($defaultParams['joinstarttime']) && $defaultParams['joinstarttime'] && $defaultParams['isSesapi'])
        $timeStr .=  $joinstarttime ;
      if (isset($defaultParams['joinendtime']) && $defaultParams['joinendtime'] )
        $timeStr .= '<span title="' . $view->translate("Participation End Time: ") . $joinendtimeFull . '">' . $sepratorFull . $joinendtime . $lineBreak . ' (' . $sescontest->timezone . ')</span></span>';
    }
    if (isset($defaultParams['votingstarttime']) && isset($defaultParams['votingendtime']) && date('Y-m-d', strtotime($votingendtime)) == date('Y-m-d', strtotime($votingstarttime))) {
      $timeStr .= '<span><b>"'.$view->translate("Voting Start & End Date ").'"</b><span title="' . $view->translate("Voting Start Time & End Time") . $votingstarttimeFull . '">' . $votingstarttime . '</span> ' . $sepratorHalf . ' ' . date('h:i A', strtotime($votingendtime)) . ' (' . $sescontest->timezone . ')</span>';
    } else {
      if (isset($defaultParams['votingstarttime']) && $defaultParams['votingstarttime'] && !$defaultParams['isSesapi'])
        $timeStr .= '<span><b>"'.$view->translate("Voting Start & End Date ").'"</b><span title="' . $view->translate("Voting Start Time: ") . $votingstarttimeFull . '">' . $votingstarttime . '</span>';
      if (isset($defaultParams['votingstarttime']) && $defaultParams['votingstarttime'] && $defaultParams['isSesapi'])
        $timeStr .= $votingstarttime ;
      if (isset($defaultParams['votingendtime']) && $defaultParams['votingendtime'] )
        $timeStr .= '<span title="' . $view->translate("Voting End Time: ") . $votingendtimeFull . '">' . $sepratorFull . $votingendtime . $lineBreak . ' (' . $sescontest->timezone . ')</span></span>';
    }
    return $timeStr;
  }

  public function changeContestDateTime($date, $options = array()) {
    $options = array_merge(array(
        'locale' => $this->getLocale(),
        'size' => 'long',
        'type' => 'datetime',
        'timezone' => Zend_Registry::get('timezone'),
            ), $options);
    if (!$date)
      return false;
    $date = $this->_checkDateTime($date, $options);
    if (!$date) {
      return false;
    }
    if (empty($options['format'])) {
      if (substr($options['locale']->__toString(), 0, 2) == 'en' &&
              $options['size'] == 'long' &&
              $options['type'] == 'datetime') {
        $options['format'] = 'MMMM d, y h:mm a z';
      } else {
        $options['format'] = Zend_Locale_Data::getContent($options['locale'], $options['type'], $options['size']);
      }
    }
    // Hack for weird usage of L instead of M in Zend_Locale
    $options['format'] = str_replace('L', 'M', $options['format']);
    //replace seconds string
    $options['format'] = str_replace(':ss', '', $options['format']);
    $str = $date->toString($options['format'], $options['locale']);
    $str = $this->convertNumerals($str, $options['locale']);
    return $str;
  }

}
