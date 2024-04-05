<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Jobs.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Plugin_Task_Jobs extends Core_Plugin_Task_Abstract {

  public function execute() {
      $db = Engine_Db_Table::getDefaultAdapter();
      $table = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads');
      $tableName = $table->info('name');
      $transactionTableName = Engine_Api::_()->getDbTable('transactions','sescommunityads')->info('name');
      $campaignTableName = Engine_Api::_()->getDbTable('campaigns','sescommunityads')->info('name');
      $targetadsTableName = Engine_Api::_()->getDbTable('targetads','sescommunityads')->info('name');
      //select featured sponsored
      $featuredCase = 'CASE WHEN '.$tableName.'.featured = 1 AND featured_date IS NULL THEN 1
                     WHEN '.$tableName.'.featured = 1 AND featured_date IS NOT NULL AND featured_date > NOW() THEN 1
                     ELSE 0 END';
      $sponsoredCase = 'CASE WHEN '.$tableName.'.sponsored = 1 AND sponsored_date IS NULL THEN 1
                     WHEN '.$tableName.'.sponsored = 1 AND sponsored_date IS NOT NULL AND sponsored_date > NOW() THEN 1
                     ELSE 0 END';
      $select = $table->select()->from($tableName,array('*','is_featured'=>new Zend_Db_Expr($featuredCase),'is_sponsored'=>new Zend_Db_Expr($sponsoredCase)));
      $select->setIntegrityCheck(false)
             ->joinLeft($transactionTableName,$transactionTableName.'.transaction_id = '.$tableName.'.transaction_id',null)
             ->joinLeft($campaignTableName,$campaignTableName.'.campaign_id = '.$tableName.'.campaign_id',array('title as campaign_name'))
             ->joinLeft($targetadsTableName,$targetadsTableName.'.sescommunityad_id = '.$tableName.'.sescommunityad_id',null);
     $packageId = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getDefaultPackage();
     $select->joinLeft('engine4_sescommunityads_orderspackages','engine4_sescommunityads_orderspackages.orderspackage_id ='.$tableName.'.existing_package_order',null);
     $case = "CASE
        WHEN $tableName.package_id IN (SELECT package_id FROM engine4_sescommunityads_packages WHERE price < 1) THEN TRUE
       WHEN $tableName.existing_package_order != 0 AND engine4_sescommunityads_orderspackages.expiration_date IS NOT NULL THEN engine4_sescommunityads_orderspackages.expiration_date <=  '".date('Y-m-d H:i:s')."' || engine4_sescommunityads_orderspackages.state != 'active'
       WHEN $tableName.existing_package_order != 0 AND engine4_sescommunityads_orderspackages.expiration_date IS NULL AND $tableName.package_id = $packageId THEN false
      WHEN $transactionTableName.expiration_date IS NOT NULL THEN $transactionTableName.expiration_date <=  '".date('Y-m-d H:i:s')."' || $transactionTableName.state != 'active'
      WHEN $transactionTableName.expiration_date IS NULL AND $tableName.package_id = $packageId THEN false
      ELSE true END";
    $select->where($case);
    //click limit search
    $clickLimitCases = "CASE
      WHEN ad_type = 'perclick' AND ad_limit = '-1' THEN false
      WHEN ad_type = 'perclick' AND ad_limit > 0 AND ad_limit > $tableName.click_count THEN false
      WHEN ad_type = 'perview' AND ad_limit = '-1' THEN false
      WHEN ad_type = 'perview' AND ad_limit > 0  AND ad_limit > $tableName.views_count THEN false
      WHEN ad_type = 'perday' AND ad_limit = '-1' THEN false
      WHEN ad_type = 'perday' AND ad_expiration_date IS NULL THEN false
      WHEN ad_type = 'perday' AND ad_expiration_date > '".date('Y-m-d H:i:s')."' THEN false
      ELSE true END
    ";
    $select->where($tableName.'.expiry_notification =?',0);
    $select->where($clickLimitCases);
    $results = $table->fetchAll($select);
    foreach($results as $result){
       $ad = Engine_Api::_()->getItem('sescommunityads',$result->sescommunityad_id);
       $owner = $ad->getOwner();
       //send expiry notification
       $ad->expiry_notification = 1;
       $ad->save();
       $link = '<a href="/ads/view/ad_id/'.$ad->sescommunityad_id.'">'.$ad->getTitle().'</a>';
       Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $owner, $ad, 'sescommunityads_adsexpired', array("adsLink" => $link));
       
       //Send email to user
       Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner->email, 'sescommunityads_adsexpired', array('host' => _ENGINE_SITE_URL, 'queue' => false, 'title' => $ad->title, 'description' => $ad->description, 'ad_link' => $link));
    }

  }

}
