<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Userad.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Model_Adsinfo extends Core_Model_Item_Abstract {

  // Properties
  protected $_parent_type = 'adsinfo';
  protected $_parent_is_owner = true;
  protected $_package;
  protected $_statusChanged;
  
    /* 
     * Get ad icon url
     */
   public function getIconUrl($type = null) {
      if( empty($this->file_id) ) {
        return null;
      }

      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, $type);
      if( !$file ) {
        return null;
      }
      
      return $file->map();
    }

    /* 
     * Set ad image path
     */
    public function setFile($file) {
      $maxW = 640;
      $maxH = 480;

      $name = $file['name'];
      $name = basename($name);
      $pathName = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public/sitead/temporary/' . $name;
      @chmod($pathName, 0777);

      $image = Engine_Image::factory();
      $image->open($file['tmp_name'])
        ->resize($maxW, $maxH)
        ->write($pathName)
        ->destroy();
        $photoName = $pathName;      
    
    $file_ext = pathinfo($file['name']);
    $file_ext = $file_ext['extension'];
    $this->file_type = $file_ext;
    $this->save();

      $storage = Engine_Api::_()->getItemTable('storage_file');
      $storageObject = $storage->createFile($photoName, array(
        'parent_id' => $this->getIdentity(),
        'parent_type' =>'sitead',
      ));
      // Remove temporary file
      @unlink($file['tmp_name']);
      if (is_file($photoName)) {
        @chmod($photoName, 0777);
        @unlink($photoName);
      }
      $this->file_id = $storageObject->file_id;
      $this->save();
  }
  
  /* 
   * Set video file path
   */
  public function setVideo($file) {

      $storage = Engine_Api::_()->getItemTable('storage_file');
      $storageObject = $storage->createFile($file, array(
        'parent_id' => $this->getIdentity(),
        'parent_type' =>'sitead',
      ));
      // Remove temporary file
      @unlink($file['tmp_name']);
      
      $this->file_id = $storageObject->file_id;
      $this->save();
  }

}
