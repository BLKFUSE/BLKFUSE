<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Attachments.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_DbTable_Attachments extends Engine_Db_Table {

  protected $_rowClass = 'Sescommunityads_Model_Attachment';
  public function getAttachments($params = array()) {
    $select = $this->select();
    if(!empty($params['ads_id']))
      $select->where('sescommunityad_id = ?', $params['ads_id']);
    $result = $this->fetchAll($select);
    return $result;
  }

  function createAttachment($params = array(),$file = array(), $banner_id = 0){
    //$db = Engine_Api::_()->getItemTable('sescommunityads_attachment')->getAdapter();
    //$db->beginTransaction();
   // try {
      $attachment = $this->createRow();
      $attachment->setFromArray($params);
      $attachment->save();
     // $db->commit();
      if(engine_count($file)){
        if(strpos($file['name'],'mp4') == false){
          $this->setPhoto($file,$attachment,$banner_id);
        }else{
          $this->setVideo($file,$attachment);
        }
      }
      return $attachment->getIdentity();
    //}catch(Exception $e){
      //throw $e;
   // }
  }
  function setVideo($file,$attachment){
      $file_ext = pathinfo($file['name']);
      $file_ext = $file_ext['extension'];
      // Store video in temporary storage object for ffmpeg to handle
      $storage = Engine_Api::_()->getItemTable('storage_file');
			$params = array(
          'parent_id' => $attachment->getIdentity(),
          'parent_type' => 'sescommunityads_attachment',
          'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
      );
      $storageObject = $storage->createFile($file, $params);
      $attachment->modified_date = date('Y-m-d H:i:s');
      $attachment->file_id =$storageObject->getIdentity();
      $attachment->save();
      return $this;
  }
  function setPhoto($photo,$attachment,$banner_id){
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $name = basename($file);
    } elseif (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $name = $photo['name'];
    } elseif (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $name = basename($file);
    } else {
      throw new Core_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
    }

    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'sescommunityads_attachment',
        'parent_id' => $attachment->getIdentity()
    );

    if(!empty($banner_id) && isset($banner_id)) {
        $banner = Engine_Api::_()->getItem('sescomadbanr_banner', $banner_id);
        $normal_height = $banner->height;
        $normal_width = $banner->width;
    } else {
        $normal_height = 800;
        $normal_width = 800;
    }

    // Save
    $storage = Engine_Api::_()->storage();

    // Resize image (main)
    $image = Engine_Image::factory();
    $image->open($file)
            ->autoRotate()
            ->resize($normal_width, $normal_height)
            ->write($path . '/m_' . $name)
            ->destroy();
    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    // Remove temp files
    @unlink($path . '/m_' . $name);
    // Update row
    $attachment->modified_date = date('Y-m-d H:i:s');
    $attachment->file_id =$iMain->getIdentity();
    $attachment->save();
    return $attachment;

  }
}
