<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Medias.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Model_DbTable_Medias extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Media";

  public function getBannerid($mediaType) {
    if ($mediaType == 'photo')
      $bannerId = 2;
    elseif ($mediaType == 'text')
      $bannerId = 1;
    elseif ($mediaType == 'video')
      $bannerId = 3;
    elseif ($mediaType == 'audio')
      $bannerId = 4;
 
    return $this->select()->from($this->info('name'), 'banner')
                    ->where('media_id =?', $bannerId)
                    ->query()
                    ->fetchColumn();
  }
  public function getMediaTypes() {
    return $this->select()->from($this->info('name'),array('title','media_id'))->where('enabled =?',1)->query()->fetchAll();
  }

}
