<?php

class Sescontestpackage_Api_Core extends Core_Api_Abstract {

  function multiCurrencyActive() {
    if (!empty($_SESSION['ses_multiple_currency']['multipleCurrencyPluginActivated'])) {
      return Engine_Api::_()->sesmultiplecurrency()->multiCurrencyActive();
    } else {
      return false;
    }
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

  public function getCustomFieldMapData($contest) {
    if ($contest) {
      $db = Engine_Db_Table::getDefaultAdapter();
      return $db->query("SELECT GROUP_CONCAT(value) AS `valuesMeta`,IFNULL(TRIM(TRAILING ', ' FROM GROUP_CONCAT(DISTINCT(engine4_contest_fields_options.label) SEPARATOR ', ')),engine4_contest_fields_values.value) AS `value`, `engine4_contest_fields_meta`.`label`, `engine4_contest_fields_meta`.`type` FROM `engine4_contest_fields_values` LEFT JOIN `engine4_contest_fields_meta` ON engine4_contest_fields_meta.field_id = engine4_contest_fields_values.field_id LEFT JOIN `engine4_contest_fields_options` ON engine4_contest_fields_values.value = engine4_contest_fields_options.option_id AND `engine4_contest_fields_meta`.`type` = 'multi_checkbox' WHERE (engine4_contest_fields_values.item_id = " . $contest->getIdentity() . ") AND (engine4_contest_fields_values.field_id != 1) GROUP BY `engine4_contest_fields_meta`.`field_id`,`engine4_contest_fields_options`.`field_id`")->fetchAll();
    }
    return array();
  }

}
