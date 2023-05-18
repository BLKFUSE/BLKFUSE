<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Core.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Plugin_Core
{

  public function onRenderLayoutDefaultSimple($event)
  {
    $this->onRenderLayoutDefault($event);
  }

  public function onRenderLayoutMobileDefault($event)
  {
    $this->onRenderLayoutDefault($event);
  }

  public function onRenderLayoutMobileDefaultSimple($event)
  {
    $this->onRenderLayoutDefault($event);
  }

  public function onRenderLayoutDefault($event)
  {
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
//    $newStyleWidthUpdate = Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.block.widthupdatefile', 1);
//    if (empty($newStyleWidthUpdate))
//      $view->headLink()
//              ->appendStylesheet($view->url(array("module" => "sitead", "controller" => "index", "action" => "siteads-style"), "default", true));
//    else
//      $view->headLink()
//              ->appendStylesheet($view->layout()->staticBaseUrl . 'application/modules/Sitead/externals/styles/style.css');
  }

  public function onUserDeleteBefore($event)
  {
    $payload = $event->getPayload();
    if( $payload instanceof User_Model_User ) {
      // Delete
      $owner_id = $payload->getIdentity();
      $adcampaignTable = Engine_Api::_()->getDbtable('adcampaigns', 'sitead');
      $adcampaignSelect = $adcampaignTable->select()->where('owner_id = ?', $owner_id);
      foreach( $adcampaignTable->fetchAll($adcampaignSelect) as $adcampaign ) {
        $adcampaign->delete();
      }
      $adTable = Engine_Api::_()->getDbtable('userads', 'sitead');
      $adSelect = $adTable->select()->where('owner_id = ?', $owner_id);
      foreach( $adTable->fetchAll($adSelect) as $userads ) {
        $userads->delete();
      }
    }
  }

  public function onSiteadAdcampaignDeleteBefore($event)
  {
    $payload = $event->getPayload();

    if( $payload instanceof Sitead_Model_Adcampaign ) {
      $adTable = Engine_Api::_()->getDbtable('userads', 'sitead');
      $adSelect = $adTable->select()->where('campaign_id = ?', $payload->getIdentity());
      foreach( $adTable->fetchAll($adSelect) as $userads ) {
        $userads->delete();
      }
    }
  }

  public function onSiteadUseradDeleteBefore($event)
  {
    $userads = $event->getPayload();
    if( $userads instanceof Sitead_Model_Userad ) {
      $siteadAdcancelTable = Engine_Api::_()->getItemTable('sitead_adcancel');
      $targetTable = Engine_Api::_()->getDbtable('adtargets', 'sitead');
      $adstatisticsTable = Engine_Api::_()->getDbtable('adstatistics', 'sitead');
      $target = $targetTable->getUserAdTargets($userads->userad_id);
      if( !empty($target) )
        $target->delete();

      $siteadAdcancelSelect = $siteadAdcancelTable->select()
      ->where('ad_id = ?', $userads->userad_id);

      foreach( $siteadAdcancelTable->fetchAll($siteadAdcancelSelect) as $adcancel ) {
        $adcancel->delete();
      }

      $adstatisticsSelect = $adstatisticsTable->select()
      ->where('userad_id = ?', $userads->userad_id);

      foreach( $adstatisticsTable->fetchAll($adstatisticsSelect) as $adstatistic ) {
        $adstatistic->delete();
      }
    }
  }

  public function onSitereviewListingtypeCreateAfter($event)
  {
    $listings = $event->getPayload();

    if( $listings instanceof Sitereview_Model_Listingtype ) {
     $db = Zend_Db_Table_Abstract::getDefaultAdapter();
     $isSitereviewEnabled = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled("sitereview");
     if( !empty($isSitereviewEnabled) ) {
      $getListingType = $db->query("SELECT * FROM `engine4_sitereview_listingtypes` LIMIT 0 , 30")->fetchAll();
      if( !empty($getListingType) ) {
        foreach($getListingType as $listingType) {
          $siteadModuleTable = Engine_Api::_()->getDbTable('modules', 'sitead');
          $siteadModuleTableName = $siteadModuleTable->info('name');
          $temTableName = "sitereview_listing_" . $listingType["listingtype_id"];

          $isAdsExist = $db->query("SELECT * FROM `engine4_sitead_modules` WHERE `table_name` LIKE '" . $temTableName . "' LIMIT 1")->fetch();
          if( empty($isAdsExist) ) {
            $row = $siteadModuleTable->createRow();
            $row->module_name = "sitereview";
            $row->module_title = $listingType["title_singular"];
            $row->table_name = $temTableName;
            $row->title_field = "title";
            $row->body_field = "body";
            $row->owner_field = "owner_id";
            $row->displayable = "7";
            $row->is_delete = "1";
            $row->save();
          }
        }
      }
    }
  }
}

}