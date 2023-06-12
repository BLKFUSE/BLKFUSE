<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Api_Core extends Core_Api_Abstract {
  protected $method = 'AES-128-CTR'; // default
  protected $_allowedType;
  private $key = 'CKXH2U9RPY3EFD70TLS1ZG4N8WQBOVI6AMJ5';
  function getKey($front){
    $module = $front->getRequest()->getModuleName();
    $action = $front->getRequest()->getActionName();
    $controller = $front->getRequest()->getControllerName();
    return $controller.'_'.$action.'_'.$module;
  }
  
  public function getFileUrl($image) {
    if(!$image){
      return $image;
    }
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

  public function getwidgetizePage($params = array()) {

    $corePages = Engine_Api::_()->getDbtable('pages', 'core');
    $corePagesName = $corePages->info('name');
    $select = $corePages->select()
            ->from($corePagesName, array('*'))
            ->where('name = ?', $params['name'])
            ->limit(1);
    return $corePages->fetchRow($select);
  }

  function allowedTypes($action = false){
    if($action){
      //check privacy setting
        if($action->privacy != "everyone" && !is_null($action->privacy))
        return false;
    }
    $information = array('price'=>'Price','click_limit'=>'Click Limit','boos_post'=>'Boost A Posts','promote_page'=>'Promote Your Page','promote_content'=>'Promote Your Content','website_visitor'=>'Get More Website Visitor','carosel'=>'Carousel View','video'=>'Single Video','featured'=>'Featured','sponsored'=>'Sponsored','targetting'=>'Targeting','description'=>'Description');
    return unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.package.settings', serialize(array_keys($information))));
  }

  function getAllowedActivityType($type){
    if($this->_allowedType == null || $this->_allowedType == ""){
      $feedTable = Engine_Api::_()->getDbTable('feedsettings', 'sescommunityads');
      $feedData = $feedTable->fetchAll();
      $allowedTypes = array();
      foreach($feedData as $data){
        $allowedTypes[] = $data['type'];
      }
      $this->_allowedType = $allowedTypes;
    }
    if(!engine_in_array($type,$this->_allowedType)){
      return false;
    }
    return true;
  }
  protected function iv_bytes()
  {
    return openssl_cipher_iv_length($this->method);
  }

  public function __construct($key = FALSE, $method = FALSE)
  {
    if($method) {
      if(engine_in_array($method, openssl_get_cipher_methods())) {
        $this->method = $method;
      } else {
        die(__METHOD__ . ": unrecognised encryption method: {$method}");
      }
    }
  }

  public function encrypt($data)
  {
    $iv = openssl_random_pseudo_bytes($this->iv_bytes());
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, $this->method, $this->key, 0, $iv);
    return $encrypted_string;
  }

  // decrypt encrypted string
  public function decrypt($data)
  {
    $iv_strlen = 2  * $this->iv_bytes();
    if(preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
      list(, $iv, $crypted_string) = $regs;
      $decrypted_string = openssl_decrypt($crypted_string, $this->method, $this->key, 0, hex2bin($iv));
      return $decrypted_string;
    } else {
      return FALSE;
    }
  }


  function multiCurrencyActive() {
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->multiCurrencyActive();
    } else {
      return false;
    }
  }
  function getActivityBoostPost($params = array()){
    $subject = $viewer = Engine_Api::_()->user()->getViewer();
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $requestParams = $request->getParams();
    $filterFeed = $request->getParam('filterFeed','all');
    $feedOnly = $request->getParam('feedOnly', false);
    $length = $request->getParam('limit', 15);
    if(!empty($params['is_action_id']))
      $length = 1;
    $itemActionLimit = 10000;
    $updateSettings   = Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.liveupdate');
    $getUpdate        = $request->getParam('getUpdate');
    $checkUpdate      = $request->getParam('checkUpdate');
    $action_id        = (int) $request->getParam('action_id');
    $actionTypesTable = Engine_Api::_()->getDbtable('actionTypes', 'activity');

    $config = array(
      'action_id' => (int) $request->getParam('action_id'),
      'max_id'    => (int) $request->getParam('maxid'),
      'min_id'    => (int) $request->getParam('minid'),
      'limit'     => (int) $length,
      'filterFeed'=>$filterFeed,
      'selectedFeedBoostPost'=>!empty($params['selected']) ? $params['selected'] : 0,
      'targetPost'=>true,
      'isOnThisDayPage'=>false,
      'communityads'=>true,
    );
    // Pre-process feed items
    $selectCount = 0;
    $nextid = null;
    $firstid = null;
    $tmpConfig = $config;
    $activity = array();
    $endOfFeed = false;
    $friendRequests = array();
    $itemActionCounts = array();
    $enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();
    do {
      // Get current batch
      $actions = null;
      $actions = Engine_Api::_()->getDbtable('actions', 'sesadvancedactivity')->getActivityAbout($subject, $viewer, $tmpConfig);
      $selectCount++;
      // Are we at the end?
      if( engine_count($actions) < $length || engine_count($actions) <= 0 ) {
        $endOfFeed = true;
      }
      // Pre-process
      if( engine_count($actions) > 0 ) {
        foreach( $actions as $action ) {
           $action_id = $action->action_id;
           // get next id
          if( null === $nextid || $action_id <= $nextid ) {
            $nextid = $action->action_id - 1;
          }
          // get first id
          if( null === $firstid || $action_id > $firstid ) {
            $firstid = $action_id;
          }
          // skip disabled actions
          if( !$action->getTypeInfo() || !$action->getTypeInfo()->enabled ) continue;
          // skip items with missing items
          if( !$action->getSubject() || !$action->getSubject()->getIdentity() ) continue;
          if( !$action->getObject() || !$action->getObject()->getIdentity() ) continue;

          // track/remove users who do too much (but only in the main feed)
          if( empty($subject) ) {
            $actionSubject = $action->getSubject();
            $actionObject = $action->getObject();
            if( !isset($itemActionCounts[$actionSubject->getGuid()]) ) {
              $itemActionCounts[$actionSubject->getGuid()] = 1;
            } else if( $itemActionCounts[$actionSubject->getGuid()] >= $itemActionLimit ) {
              continue;
            } else {
              $itemActionCounts[$actionSubject->getGuid()]++;
            }
          }
          // remove duplicate friend requests
          if( $action->type == 'friends' ) {
            $id = $action->subject_id . '_' . $action->object_id;
            $rev_id = $action->object_id . '_' . $action->subject_id;
            if( engine_in_array($id, $friendRequests) || engine_in_array($rev_id, $friendRequests) ) {
              continue;
            } else {
              $friendRequests[] = $id;
              $friendRequests[] = $rev_id;
            }
          }
          // remove items with disabled module attachments
          try {
            $attachments = $action->getAttachments();
          } catch (Exception $e) {
            // if a module is disabled, getAttachments() will throw an Engine_Api_Exception; catch and continue
            continue;
          }
          // add to list
          if( engine_count($activity) < $length ) {
            $activity[] = $action;
            if( engine_count($activity) == $length ) {
              break;
            }
          }

        }
      }
      // Set next tmp max_id
      if( $nextid ) {
        $tmpConfig['max_id'] = $nextid;
      }
      if( !empty($tmpConfig['action_id']) ) {
        $actions = array();
      }
    }while( engine_count($activity) < $length && $selectCount <= 3 && !$endOfFeed );
    $returnData['activityCount'] = engine_count($activity);
    $returnData['nextid'] = $nextid;
    $returnData['firstid'] = $firstid;
    $returnData['endOfFeed'] = $endOfFeed;
    $returnData['activity'] = $activity;
    return $returnData;
  }
  function isMultiCurrencyAvailable() {
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->isMultiCurrencyAvailable();
    } else {
      return false;
    }
  }

  function getCurrencyPrice($price = 0, $givenSymbol = '', $change_rate = '') {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $precisionValue = $settings->getSetting('sesmultiplecurrency.precision', 2);
    $defaultParams['precision'] = $precisionValue;
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->getCurrencyPrice($price, $givenSymbol, $change_rate);
    } else {
	  $givenSymbol = $settings->getSetting('payment.currency', 'USD');
      return Zend_Registry::get('Zend_View')->locale()->toCurrency($price, $givenSymbol, $defaultParams);
    }
  }

  function getCurrentCurrency() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->getCurrentCurrency();
    } else {
      return $settings->getSetting('payment.currency', 'USD');
    }
  }

  function defaultCurrency() {
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->defaultCurrency();
    } else {
      $settings = Engine_Api::_()->getApi('settings', 'core');
      return $settings->getSetting('payment.currency', 'USD');
    }
  }
  function getTargetAds($params = array()){
    $columns = Engine_Api::_()->getDbtable('targetads', 'sescommunityads')->info('cols');
    $arrayFields = array();
    if(!empty($params['fieldsArray'])){
      foreach($columns as $column){
          if($column == "targetad_id" || $column == "sescommunityad_id")
            continue;
          $arrayFields[$column] = 1;

      }
      return $arrayFields;
    }
    return $columns;
  }
  function getPackageContent($params = array()){
    if(!empty($params['name'])){
      $name = $params['name'];
      $package_id = $params['package_id'];
      $package = Engine_Api::_()->getItem('sescommunityads_packages',$package_id);
      if(!$package)
        return false;
      return $package->$name;
    }
    return false;

  }
  function networks(){
    $table = Engine_Api::_()->getDbtable('networks', 'network');
    $select = $table->select()
            ->where('hide = ?', 0);
    return $table->fetchAll($select);

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
			$resultsArray =  Engine_Api::_()->getDbtable('integrateothermodules', 'sesbasic')->getResults(array('module_name'=>$moduleName));
      if (is_array($manafestFile) && isset($manafestFile['items'])) {
        foreach ($manafestFile['items'] as $item)
          if (!engine_in_array($item, $resultsArray))
            $moduleType[$item] = $item.' ';
      }
    }
    return $moduleType;
  }
  public function getAllProfileTypes(){
    return $profileFields =  Engine_Api::_()->getDBTable('options', 'sescommunityads')->getUserProfileTypes();
  }
  public function getWidgetParams($widgetId) {
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
  public function getIdentityWidget($name, $type, $corePages) {
    if((isset($_SESSION['sespwa']['sespwa']) && !empty($_SESSION['sespwa']['sespwa'])) || (isset($_SESSION['sespwa']['mobile']) && !empty($_SESSION['sespwa']['mobile']))) {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'sespwa');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'sespwa')->info('name');
    } else {
      $widgetTable = Engine_Api::_()->getDbTable('content', 'core');
      $widgetPages = Engine_Api::_()->getDbTable('pages', 'core')->info('name');
    }
    $identity = $widgetTable->select()
            ->setIntegrityCheck(false)
            ->from($widgetTable, 'content_id')
            ->where($widgetTable->info('name') . '.type = ?', $type)
            ->where($widgetTable->info('name') . '.name = ?', $name)
            ->where($widgetPages . '.name = ?', $corePages)
            ->joinLeft($widgetPages, $widgetPages . '.page_id = ' . $widgetTable->info('name') . '.page_id')
            ->query()
            ->fetchColumn();
    return $identity;
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
        $counter = 1;
        
        while ($iDateFrom < $iDateTo) {
          array_push($aryRange, date('Y-m', $iDateFrom));
          $iDateFrom = strtotime('+'.$counter.' Months', $iDateFrom);
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
        $iDateFrom = $iDateFrom;
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
