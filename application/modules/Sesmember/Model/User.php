<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: User.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesmember_Model_User extends User_Model_User {

    protected $_type = "user";

    public function getPhotoUrl($type = NULL) {

        if(!$this->getIdentity())
            return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";

        $photoId = $this->photo_id;
        if(Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sesmember")) {
            if(empty($photoId)) {
                $profiletype_id = Engine_Api::_()->sesmember()->getProfileTypeId(array("user_id" => $this->user_id, "field_id" => 1));
                $photo_id = Engine_Api::_()->getDbtable("profilephotos", "sesmember")->getPhotoId($profiletype_id);
                if ($photo_id) {
                    $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photo_id, $type);
                    if($file) {
                        return $file->map();
                    } elseif($photo_id) {
                        $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photo_id,"thumb.profile");
                        if($file)
                            return $file->map();
                    } else {
                        return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
                    }
                } else {
                    return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
                }
            } else {
                $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId, $type);
                if($file) {
                    return $file->map();
                } elseif($photoId) {
                    $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId,"thumb.profile");
                    if($file)
                    return $file->map();
                } else {
                    return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
                }
            }
        }
        else {
            if ($photoId) {
                $file = Engine_Api::_()->getItemTable("storage_file")->getFile($photoId, $type);
                if($file)
                    return $file->map();
                else
                    return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
            } else {
                return "application/modules/User/externals/images/nophoto_user_thumb_profile.png";
            }
        }
    }
}
