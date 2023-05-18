<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Viewstats.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Viewstats extends Engine_Db_Table {

  function insertrow($ad,$viewer){
     $params["sescommunityad_id"] = $ad->getIdentity();
     $params['campaign_id'] = $ad->campaign_id;
     $params['user_id'] = $viewer->getIdentity();
     $row = $this->createRow();
     $params['creation_date'] = date('Y-m-d H:i:s');
     $row->setFromArray($params);
     $row->save();
  }
}
