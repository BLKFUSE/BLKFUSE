<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Api_Core extends Core_Api_Abstract
{

	public function defaultCurrency()
	{
		return  Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
	}
	function getCurrencyPrice($price = 0, $givenSymbol = '', $change_rate = '') {
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $precisionValue = $settings->getSetting('sesmultiplecurrency.precision', 2);
      $defaultParams['precision'] = $precisionValue;
      if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
        return Engine_Api::_()->payment()->getCurrencyPrice($price, $givenSymbol, $change_rate);
      } else {
        $givenSymbol = $settings->getSetting('payment.currency', 'USD');
        return Zend_Registry::get('Zend_View')->locale()->toCurrency($price, $givenSymbol, $defaultParams);
      }
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
  public function getLikeStatus($item_id = '', $resource_type = '') {
    if ($item_id != '') {
      $userId = Engine_Api::_()->user()->getViewer()->getIdentity();
      if ($userId == 0)
        return false;
      $coreLikeTable = Engine_Api::_()->getDbTable('likes', 'core');
      $total_likes = $coreLikeTable->select()->from($coreLikeTable->info('name'), new Zend_Db_Expr('COUNT(like_id) as like_count'))->where('resource_type =?', $resource_type)->where('poster_id =?', $userId)->where('poster_type =?', 'user')->where('resource_id =?', $item_id)->limit(1)->query()->fetchColumn();
      if ($total_likes > 0)
        return true;
      else
        return false;
    }
    return false;
  }
  public function getAdminnSuperAdmins() {
      $userTable = Engine_Api::_()->getDbTable('users', 'user');
      $select = $userTable->select()->from($userTable->info('name'), 'user_id')->where('level_id IN (?)', array(1,2));
      $results = $select->query()->fetchAll();
      return $results;
  }
}
