<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Api_Core extends Core_Api_Abstract {

  public function isViewerPlanActive($item) {
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    if(isset($item->owner_id)) {
      $owner_id = $item->owner_id;
    } else if(isset($item->user_id)) {
      $owner_id = $item->user_id;
    }
    if(isset($item->package_id)) {
    $package_id = $item->package_id;
    } else {
			return false;
    }
    
    $plan = false;
    
    if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && isset($package_id) && !empty($package_id) && $owner_id != $viewer_id) {
      $package = Engine_Api::_()->getItem('epaidcontent_package', $package_id); 
      $getViewerOrder = Engine_Api::_()->getDbTable('orders','epaidcontent')->getViewerOrder(array('owner_id' => $viewer_id, 'package_owner_id' => $package->user_id, 'noCondition' => 1)); 
      if((float) $getViewerOrder->total_amount < (float) $package->price) {
        $plan = true;
      }
    }
    return $plan;
  }

  function multiCurrencyActive(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->multiCurrencyActive();
    }else{
      return false;  
    }
  }
  function isMultiCurrencyAvailable(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->isMultiCurrencyAvailable();
    }else{
      return false;  
    }
  }
  public function getSupportedCurrency(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->getSupportedCurrency();
    }else{
      return array();  
    }
  }
  function getCurrencyPrice($price = 0, $givenSymbol = '', $change_rate = '',$returnPrice = ""){
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $precisionValue = $settings->getSetting('sesmultiplecurrency.precision', 2);
    $defaultParams['precision'] = $precisionValue;
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->getCurrencyPrice($price, $givenSymbol, $change_rate,$returnPrice);
    }else{
		$givenSymbol = $settings->getSetting('payment.currency', 'USD');
      return Zend_Registry::get('Zend_View')->locale()->toCurrency($price, $givenSymbol, $defaultParams);
    }
  }
  function getCurrentCurrency(){
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->getCurrentCurrency();
    }else{
      return $settings->getSetting('payment.currency', 'USD');
    }
  }
  function defaultCurrency(){
    if(!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])){
      return Engine_Api::_()->sesmultiplecurrency()->defaultCurrency();
    }else{
      $settings = Engine_Api::_()->getApi('settings', 'core');
      return $settings->getSetting('payment.currency', 'USD');
    }
  }
  
  public function isUserHasPaidOrder($params = array()){
    $viewer = Engine_Api::_()->user()->getViewer();
    $table = Engine_Api::_()->getDbTable('orders','epaidcontent');
    $select = $table->select()->where('owner_id =?',$viewer->getIdentity())->where('state =?','complete');
    
    if(isset($params['user_id']) && !empty($params['user_id'])) {
      $select->where('user_id =?', $params['user_id']);
    }

    $result = $table->fetchAll($select);
    if(engine_count($result))
    return $result[0];
    else
      return false;
  }  
 public function dateFormat($date = null,$changetimezone = '',$object = '',$formate = 'M d, Y h:m A') {
		if($changetimezone != '' && $date){
			$date = strtotime($date);
			$oldTz = date_default_timezone_get();
			date_default_timezone_set($object->timezone);
			if($formate == '')
				$dateChange = date('Y-m-d h:i:s',$date);
			else{
				$dateChange = date('M d, Y h:i A',$date);
			}
			date_default_timezone_set($oldTz);
			return $dateChange.' ('.$object->timezone.')';
		}
    if($date){
      return date('M d, Y h:i A', strtotime($date));
    }
  }
}
