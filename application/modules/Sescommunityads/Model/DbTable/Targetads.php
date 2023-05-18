<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Targetads.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Targetads extends Engine_Db_Table {
  protected $_rowClass = 'Sescommunityads_Model_Targetad';
  function createTargets($targetting,$ads,$networks,$sescommunityad_id, $interests){
     if(empty($sescommunityad_id)){
        $targetAd = $this->createRow();
        $targetAd->sescommunityad_id = $ads->getIdentity();
     }else{
        $select = $this->select()->where('sescommunityad_id =?',$sescommunityad_id)->limit(1);
        $targetAd = $this->fetchRow($select);
     }
     if(engine_count($targetting)){
       $targettingValues = array();
       foreach($targetting as $key=>$target){
         if(!$target)
          continue;
         if(is_array($target)){
           $value = implode('||',$target);
         }else
           $value = $target;
         if($value == "||")
          continue;
         $targettingValues[$key] = $value;
       }
       $targetAd->setFromArray($targettingValues);
     }
     if(isset($targetAd->network_enable))
        $targetAd->network_enable = $networks ;
     if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest') && isset($targetAd->interest_enable)) {
        $targetAd->interest_enable = $interests ;
     }
       $targetAd->save();
       return $targetAd;
  }
}
