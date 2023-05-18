<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Campaignstats.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Campaignstats extends Engine_Db_Table {

  function insertrow($ad,$viewer,$type){
     $params["sescommunityad_id"] = $ad->getIdentity();
     $params['campaign_id'] = $ad->campaign_id;
     $params['user_id'] = $viewer->getIdentity();
     $params['type'] = $type;
     if($type == 'click'){
      $params['click'] = 1;  
     }else{
      $params['view'] = 1;  
     }
     $row = $this->createRow();
     $params['creation_date'] = date('Y-m-d H:i:s');
     $row->setFromArray($params);
     $row->save();
  }
}
