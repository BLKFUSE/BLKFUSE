<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Widget_MenuFooterController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->storage = Engine_Api::_()->storage();
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('core_footer');
    $this->view->quickLinksMenu = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesdating_quicklinks_footer');

    // Languages
    $translate = Zend_Registry::get('Zend_Translate');
    $languageList = $translate->getList();

    //$currentLocale = Zend_Registry::get('Locale')->__toString();
    // Prepare default langauge
    $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
    if (!engine_in_array($defaultLanguage, $languageList)) {
      if ($defaultLanguage == 'auto' && isset($languageList['en'])) {
        $defaultLanguage = 'en';
      } else {
        $defaultLanguage = null;
      }
    }

    // Prepare language name list
    $languageNameList = array();
    $languageDataList = Zend_Locale_Data::getList(null, 'language');
    $territoryDataList = Zend_Locale_Data::getList(null, 'territory');

    foreach ($languageList as $localeCode) {
      $languageNameList[$localeCode] = Engine_String::ucfirst(Zend_Locale::getTranslation($localeCode, 'language', $localeCode));
      if (empty($languageNameList[$localeCode])) {
        if (false !== strpos($localeCode, '_')) {
          list($locale, $territory) = explode('_', $localeCode);
        } else {
          $locale = $localeCode;
          $territory = null;
        }
        if (isset($territoryDataList[$territory]) && isset($languageDataList[$locale])) {
          $languageNameList[$localeCode] = $territoryDataList[$territory] . ' ' . $languageDataList[$locale];
        } else if (isset($territoryDataList[$territory])) {
          $languageNameList[$localeCode] = $territoryDataList[$territory];
        } else if (isset($languageDataList[$locale])) {
          $languageNameList[$localeCode] = $languageDataList[$locale];
        } else {
          continue;
        }
      }
    }
    $languageNameList = array_merge(array(
        $defaultLanguage => $defaultLanguage
            ), $languageNameList);
    $this->view->languageNameList = $languageNameList;

    // Get affiliate code
    $this->view->affiliateCode = Engine_Api::_()->getDbtable('settings', 'core')->core_affiliate_code;
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->footerlogo =  $settings->getSetting('sesdating.footerlogo', '');
    $sesdating_header = Zend_Registry::isRegistered('sesdating_header') ? Zend_Registry::get('sesdating_header') : null;
    if(empty($sesdating_header))
      return $this->setNoRender();
    $this->view->aboutusdescription =  $settings->getSetting('sesdating.aboutusdescription', 'Lorem Ipsum Is Simply Dummy Text Of The Printing And Typesetting Industry.');
    $this->view->quicklinksenable =  $settings->getSetting('sesdating.quicklinksenable', '1');
    $this->view->quicklinksheading =  $settings->getSetting('sesdating.quicklinksheading', 'QUICK LINKS');
    $this->view->helpenable =  $settings->getSetting('sesdating.helpenable', '1');
    $this->view->helpheading =  $settings->getSetting('sesdating.helpheading', 'HELP');
    $this->view->socialenable =  $settings->getSetting('sesdating.socialenable', '1');
    $this->view->socialheading =  $settings->getSetting('sesdating.socialheading', 'SOCIAL');
    $this->view->facebookurl =  $settings->getSetting('sesdating.facebookurl', 'http://www.facebook.com/');
    $this->view->googleplusurl =  $settings->getSetting('sesdating.googleplusurl', 'http://plus.google.com/');
    $this->view->twitterurl =  $settings->getSetting('sesdating.twitterurl', 'https://www.twitter.com/');
    $this->view->pinteresturl =  $settings->getSetting('sesdating.pinteresturl', 'https://www.pinterest.com/');
  }
}
