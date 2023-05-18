<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Photo.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Model_Photo extends Core_Model_Item_Abstract{

  public function getPhotoUrl($type = null) {
  
    $photo_id = $this->file_id;
    if( !$photo_id )
      return "application/modules/User/externals/images/nophoto_user_thumb_icon.png";

    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
    if( !$file ) {
        return null;
    }
    return $file->map();
  }

  public function setPhoto($photo) {
  
    if( $photo instanceof Zend_Form_Element_File ) {
        $file = $photo->getFileName();
        $fileName = $file;
    } else if( $photo instanceof Storage_Model_File ) {
        $file = $photo->temporary();
        $fileName = $photo->name;
    } else if( $photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id) ) {
        $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
        $file = $tmpRow->temporary();
        $fileName = $tmpRow->name;
    } else if( is_array($photo) && !empty($photo['tmp_name']) ) {
        $file = $photo['tmp_name'];
        $fileName = $photo['name'];
    } else if( is_string($photo) && file_exists($photo) ) {
        $file = $photo;
        $fileName = $photo;
    } else {
        throw new User_Model_Exception('invalid argument passed to setPhoto');
    }

    if( !$fileName ) {
        $fileName = basename($file);
    }

    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

    $params = array(
      'parent_type' => 'edating_dating',
      'parent_id' => $this->getIdentity(),
      'user_id' => $this->user_id,
      'name' => $fileName,
    );

    $filesTable = Engine_Api::_()->getItemTable('storage_file');

    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
        ->resize(700, 700)
        ->write($mainPath)
        ->destroy();

    // Resize image (normal)
    $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
        ->resize(420, 420)
        ->write($normalPath)
        ->destroy();

    // Store
    $iMain = $filesTable->createFile($mainPath, $params);
    $iIconNormal = $filesTable->createFile($normalPath, $params);

    $iMain->bridge($iIconNormal, 'thumb.normal');

    // Remove temp files
    @unlink($mainPath);
    @unlink($normalPath);

    // Update row
    $this->creation_date = date('Y-m-d H:i:s');
    $this->file_id = $iMain->file_id;
    $this->save();

    return $this;
  }
}
