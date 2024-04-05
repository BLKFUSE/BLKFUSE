<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Event.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_Event extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;
    public function getPhotoUrl($type = null) {
    if ($this->file_id) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, $type);
			if($file){
      			return $file->map();
			}else{
				$file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id,'thumb.profile');
				if($file)
					return $file->map();
			}
    }
    return "";
  }

}
