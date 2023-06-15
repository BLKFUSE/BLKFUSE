<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Api_Core extends Core_Api_Abstract {

  function getCurrentCurrency() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->getCurrentCurrency();
    } else {
      return $settings->getSetting('payment.currency', 'USD');
    }
  }
  
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
  
  /* get other module compatibility code as per module name given */
  public function getPluginItem($moduleName) {
		//initialize module item array
    $moduleType = array();
    $filePath =  APPLICATION_PATH . "/application/modules/" . ucfirst($moduleName) . "/settings/manifest.php";
		//check file exists or not
    if (is_file($filePath)) {
			//now include the file
      $manafestFile = include $filePath;
			$resultsArray =  Engine_Api::_()->getDbtable('integrateothermodules', 'sescontest')->getResults(array('module_name'=>$moduleName));
      if (is_array($manafestFile) && isset($manafestFile['items'])) {
        foreach ($manafestFile['items'] as $item)
          if (!engine_in_array($item, $resultsArray))
            $moduleType[$item] = $item.' ';
      }
    }
    return $moduleType;
  }

  function sendMailNotification($params = array()) {
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $viewer = Engine_Api::_()->user()->getViewer();
    $contest = $params['contest'];
    $usersTable = Engine_Api::_()->getDbtable('users', 'user');
    $usersSelect = $usersTable->select()
            ->where('level_id = ?', 1)
            ->where('enabled >= ?', 1);
    $superAdmins = $usersTable->fetchAll($usersSelect);
    foreach ($superAdmins as $superAdmin) {
      $adminEmails[$superAdmin->getTitle()] = $superAdmin->email;
    }
    Engine_Api::_()->getApi('mail', 'core')->sendSystem($adminEmails, 'sescontest_admin_approval', array('object_link' => $contest->getHref(), 'host' => $_SERVER['HTTP_HOST']));
    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($contest->getOwner(), $viewer, $contest, 'sescontest_send_approval_contest');
    Engine_Api::_()->getApi('mail', 'core')->sendSystem($contest->getOwner(), 'sescontest_send_approval_contest', array('contest_title' => $contest->getTitle(), 'object_link' => $view->url(array('action' => 'manage'), 'sescontest_general', true), 'host' => $_SERVER['HTTP_HOST']));
  }

  function uploadShareContentImage($image, $item) {
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/' . time() . '.png';
    file_put_contents($path, $data);
    $id = $item->setPhoto($path, true);
    @unlink($path);
    return $id;
  }

  function dateValidations($values = array()) {
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $oldTz = date_default_timezone_get();
    date_default_timezone_set($values['timezone']);
    $starttime = strtotime(isset($values['start_date']) ? date('Y-m-d H:i:s', strtotime($values['start_date'] . ' ' . $values['start_time'])) : '');
    $endtime = strtotime(isset($values['end_date']) ? date('Y-m-d H:i:s', strtotime($values['end_date'] . ' ' . $values['end_time'])) : '');
    $joinStartTime = strtotime(isset($values['join_start_date']) ? date('Y-m-d H:i:s', strtotime($values['join_start_date'] . ' ' . $values['join_start_time'])) : '');
    $joinEndTime = strtotime(isset($values['join_end_date']) ? date('Y-m-d H:i:s', strtotime($values['join_end_date'] . ' ' . $values['join_end_time'])) : '');
    $votingStartTime = strtotime(isset($values['voting_start_date']) ? date('Y-m-d H:i:s', strtotime($values['voting_start_date'] . ' ' . $values['voting_start_time'])) : '');
    $votingEndTime = strtotime(isset($values['voting_end_date']) ? date('Y-m-d H:i:s', strtotime($values['voting_end_date'] . ' ' . $values['voting_end_time'])) : '');
    $resutTime = strtotime(isset($values['result_date']) ? date('Y-m-d H:i:s', strtotime($values['result_date'] . ' ' . $values['result_time'])) : '');
     date_default_timezone_set($oldTz);
    $error = array();

    $currentTime = time();
    if ($starttime >= $endtime) {
      $error[0] = 1;
      $error[1] = $view->translate("Contest Start date can not be greater than or equal to the contest End date.");
      return $error;
    }
    if ($starttime < $currentTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest Start date can not be a Past date, so please select a date greater than or equal to Today\'s date.');
      return $error;
    }
    if ($endtime <= $starttime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest End date can not be less than or equal to the contest Start date.');
      return $error;
    }
    if ($endtime < $currentTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest End date can not be a Past date.');
      return $error;
    }
    if ($joinStartTime < $currentTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest participation Start date can not be a Past date.');
      return $error;
    }
    if ($joinStartTime < $starttime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest participation Start date can not be less than contest Start date.');
      return $error;
    }
    if ($joinStartTime >= $endtime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest participation Start date can not be greater than or equal to the contest End date.');
      return $error;
    }
    if ($joinStartTime >= $joinEndTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest participation start date can not be greater than or equal to the participation End date.');
      return $error;
    }
    if ($votingStartTime >= $endtime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest voting start date can not be greater than or equal to the contest End date.');
      return $error;
    }
    if ($votingStartTime >= $votingEndTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest voting Start date can not be in greater than or equal to the contest voting end date.');
      return $error;
    }
    if ($votingStartTime < $joinStartTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest voting Start date can not be less than the contest Joining Start date.');
      return $error;
    }
    if ($votingEndTime < $joinEndTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest voting End date can not be in less than the contest Joining End date.');
      return $error;
    }
    if ($endtime < $joinEndTime || $endtime < $votingEndTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest End date can not be in less than the contest Joining End date and contest Voting End date.');
      return $error;
    }
    if ($votingStartTime < $currentTime) {
      $error[0] = 1;
      $error[1] = $view->translate('Contest voting Start date can not be a Past date.');
      return $error;
    }
    return $error;
  }

  public function getLikeStatus($contest_id = '', $resource_type = '') {

    if ($contest_id != '') {
      $userId = Engine_Api::_()->user()->getViewer()->getIdentity();
      if ($userId == 0)
        return false;
      $coreLikeTable = Engine_Api::_()->getDbtable('likes', 'core');
      $total_likes = $coreLikeTable->select()->from($coreLikeTable->info('name'), new Zend_Db_Expr('COUNT(like_id) as like_count'))->where('resource_type =?', $resource_type)->where('poster_id =?', $userId)->where('poster_type =?', 'user')->where('resource_id =?', $contest_id)->limit(1)->query()->fetchColumn();
      if ($total_likes > 0)
        return 1;
      else
        return 0;
    }
    return 0;
  }

  public function contestPrivacy($contest = null, $privacy = null) {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!$contest->authorization()->isAllowed($viewer, $privacy))
      return 0;
    else
      return 1;
  }

  public function checkFfmpeg($ffmpeg_path = '') {

    if (!$ffmpeg_path)
      return false;
    if (!function_exists('shell_exec')) {
      return false;
    }

    if (!function_exists('exec')) {
      return false;
    }
    if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path)) {
      $output = null;
      $return = null;
      exec($ffmpeg_path . ' -version', $output, $return);

      if ($return > 0) {
        return false;
      }
    }

    return true;
  }

  public function getMemberByLike($contestId, $limit) {
    $coreLikeTable = Engine_Api::_()->getDbTable('likes', 'core');
    return $coreLikeTable->select()->from($coreLikeTable->info('name'), 'poster_id')
                    ->where('resource_id =?', $contestId)
                    ->where('resource_type =?', 'contest')
                    ->limit($limit)
                    ->query()
                    ->fetchAll();
  }

  public function getMemberFavourite($contestId, $limit) {
    $favouriteTable = Engine_Api::_()->getDbTable('favourites', 'sescontest');
    return $favouriteTable->select()->from($favouriteTable->info('name'), 'user_id')
                    ->where('resource_id =?', $contestId)
                    ->where('resource_type =?', 'contest')
                    ->limit($limit)
                    ->query()
                    ->fetchAll();
  }

  public function getMemberFollow($contestId, $limit) {
    $followTable = Engine_Api::_()->getDbTable('followers', 'sescontest');
    return $followTable->select()->from($followTable->info('name'), 'user_id')
                    ->where('resource_id =?', $contestId)
                    ->where('resource_type =?', 'contest')
                    ->limit($limit)
                    ->query()
                    ->fetchAll();
  }

  function tagCloudItemCore($fetchtype = '', $contest_id = '') {

    $tableTagmap = Engine_Api::_()->getDbtable('tagMaps', 'core');
    $tableTagName = $tableTagmap->info('name');
    $tableTag = Engine_Api::_()->getDbtable('tags', 'core');
    $tableMainTagName = $tableTag->info('name');
    $selecttagged_photo = $tableTagmap->select()
            ->from($tableTagName)
            ->setIntegrityCheck(false)
            ->where('resource_type =?', 'contest')
            ->where('tag_type =?', 'core_tag')
            ->joinLeft($tableMainTagName, $tableMainTagName . '.tag_id=' . $tableTagName . '.tag_id', array('text'))
            ->group($tableTagName . '.tag_id');
    if ($contest_id) {
      $selecttagged_photo->where($tableTagName . '.resource_id =?', $contest_id);
    }
    $selecttagged_photo->columns(array('itemCount' => ("COUNT($tableTagName.tagmap_id)")));
    if ($fetchtype == '')
      return Zend_Paginator::factory($selecttagged_photo);
    else
      return $tableTagmap->fetchAll($selecttagged_photo);
  }

  //Total likes according to viewer_id
  public function likeIds($params = array()) {

    $likeTable = Engine_Api::_()->getItemTable('core_like');
    return $likeTable->select()
                    ->from($likeTable->info('name'), array('resource_id'))
                    ->where('resource_type = ?', $params['type'])
                    ->where('poster_id = ?', $params['id'])
                    ->query()
                    ->fetchColumn();
  }

  public function getWidgetPageId($widgetId) {
    $db = Engine_Db_Table::getDefaultAdapter();
    $params = $db->select()
            ->from('engine4_core_content', 'page_id')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    return json_decode($params, true);
  }

  public function getWidgetParams($widgetId) {
      if(!$widgetId)
          return array();
    $db = Engine_Db_Table::getDefaultAdapter();
    if((isset($_SESSION['sespwa']['sespwa']) && !empty($_SESSION['sespwa']['sespwa'])) || (isset($_SESSION['sespwa']['mobile']) && !empty($_SESSION['sespwa']['mobile']))) {
        $tableName = 'engine4_sespwa_content';
    } else {
        $tableName = 'engine4_core_content';
    }
    $params = $db->select()
            ->from($tableName, 'params')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    return json_decode($params, true);
  }

  public function getSearchWidgetParams($widgetId) {
    $db = Engine_Db_Table::getDefaultAdapter();
    $pageId = $db->select()
            ->from('engine4_core_content', 'page_id')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    $params = $db->select()
            ->from('engine4_core_content', 'params')
            ->where('`page_id` = ?', $pageId)
            ->where('`name` = ?', 'sescontest.browse-search')
            ->query()
            ->fetchColumn();
    if ($params)
      return json_decode($params, true);
    else
      return 0;
  }

  function ordinal($number) {
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
      return $number . 'th';
    else
      return $number . $ends[$number % 10];
  }

}
