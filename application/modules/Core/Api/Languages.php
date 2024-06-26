<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Languages.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

class Core_Api_Languages extends Core_Api_Abstract {

  public function getLanguages() {
  
    // Languages
    $languagePath = APPLICATION_PATH . '/application/languages';
    $translate    = Zend_Registry::get('Zend_Translate');
    $languageList = $translate->getList();
    
    // Prepare default langauge
    $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
    if ($defaultLanguage == 'auto') {
        $defaultLanguage = 'en';
    }

    // Init default locale
    $localeObject = Zend_Registry::get('Locale');
    $languages = Zend_Locale::getTranslationList('language', $localeObject);
    $territories = Zend_Locale::getTranslationList('territory', $localeObject);

    $localeMultiOptions = array();
    foreach ($languageList as $key) {
      $dir = $languagePath . '/' . $key;
      if (!is_dir($dir)) {
          continue;
      }
      $isEnabled = Engine_Api::_()->getDbTable('languages', 'core')->isEnabled($key);
      if(empty($isEnabled)) {
        continue;
      }
      $languageName = null;
      if (!empty($languages[$key])) {
        $languageName = $languages[$key];
      } else {
        $tmpLocale = new Zend_Locale($key);
        $region = $tmpLocale->getRegion();
        $language = $tmpLocale->getLanguage();
        if (!empty($languages[$language]) && !empty($territories[$region])) {
            $languageName =  $languages[$language] . ' (' . $territories[$region] . ')';
        }
      }

      if ($languageName) {
        $localeMultiOptions[$key] = $languageName . '';
      }
    }

    if (!isset($localeMultiOptions[$defaultLanguage])) {
      $defaultLanguage = 'en';
    }
    return $localeMultiOptions;
  }
}
