<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Campaigns.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Campaigns extends Engine_Db_Table {
  protected $_rowClass = 'Sescommunityads_Model_Campaign';
  public function geCampaigns($params = array()) {
    $select = $this->select();
    if(!empty($params['user_id']))                
      $select->where('user_id = ?', $params['user_id']);
    $select->order('campaign_id DESC');
    if(!empty($params['paginator'])){
       return Zend_Paginator::factory($select);
    }
    return $this->fetchAll($select);
  }
  
  function createCampaign($title = "",$user_id = 0){
    if(!$title)
      $title = "Untitled Campaign";
    //$db = Engine_Api::_()->getItemTable('sescommunityads_campaign')->getAdapter();
    //$db->beginTransaction();
    //try { 
      $campaign = $this->createRow();
      $campaign->title = $title;  
      $campaign->user_id = $user_id;
      $campaign->save();
     // $db->commit();
      return $campaign->getIdentity();
    //}catch(Exception $e){
      //throw $e;  
   // }
  }
}