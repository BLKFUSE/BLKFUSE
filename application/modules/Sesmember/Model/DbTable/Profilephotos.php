<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Proilephotos.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Profilephotos extends Engine_Db_Table {

  protected $_rowClass = "Sesmember_Model_Profilephoto";

  public function getProfilePhotos() {
    return $this->fetchAll($this->select());
  }

  public function getPhotoId($profiletype_id) {

    $rName = $this->info('name');
    return $this->select()
                    ->from($rName, 'photo_id')
                    ->where('profiletype_id = ?', $profiletype_id)
                    ->query()
                    ->fetchColumn();
  }

}
