<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ThoughtComposer.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Plugin_ThoughtComposer extends Core_Plugin_Abstract
{
  public function onAttachThought($data,$location = '',$postData)
  {
    $table = Engine_Api::_()->getDbTable('thoughts','sesthought');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {

      $thought = $table->createRow();
      $viewer = Engine_Api::_()->user()->getViewer();
      $thought->owner_id = $viewer->getIdentity();
      $thought->owner_type = $viewer->getType();
      $thought->title = $postData['thought-description'];
      $thought->source = $postData['thought-source'];
      $thought->thoughttitle = $postData['thought-title'];
      if($postData['category_id'])
        $thought->category_id = $postData['category_id'];
      if($postData['subcat_id'])
        $thought->subcat_id = $postData['subcat_id'];
      if($postData['subsubcat_id'])
        $thought->subsubcat_id = $postData['subsubcat_id'];
      $thought->save();
      
      if($postData['video']) {
        $information = $this->handleIframelyInformation($postData['video']);
        try{
          $thought->setPhoto($information['thumbnail']);
        }catch(Exception $e){
          //silence  
        }
        $thought->mediatype = $postData['mediatype'];
        $thought->code = $information['code'];
        $thought->save();
      }

      if(isset($_FILES['photo']['tmp_name']) && !empty($_FILES['photo']['tmp_name']) ) {
        $photo_id = $this->setPhoto($_FILES['photo'], $thought->thought_id);
        $thought->photo_id = $photo_id;
        $thought->save();
      }
      
      // Add tags
      $tags = preg_split('/[,]+/', $postData['tags']);
      $thought->tags()->addTagMaps($viewer, $tags);
      $thought->save();
    } catch( Exception $e ) {
      throw $e;
      return;
    }
    return $thought;
  }
  
  public function handleIframelyInformation($uri) {
  
    $iframelyDisallowHost = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought_iframely_disallow');
    if (parse_url($uri, PHP_URL_SCHEME) === null) {
        $uri = "http://" . $uri;
    }
    $uriHost = Zend_Uri::factory($uri)->getHost();
    if ($iframelyDisallowHost && engine_in_array($uriHost, $iframelyDisallowHost)) {
        return;
    }
    $config = Engine_Api::_()->getApi('settings', 'core')->core_iframely;
    $iframely = Engine_Iframely::factory($config)->get($uri);
    if (!engine_in_array('player', array_keys($iframely['links']))) {
        return;
    }
    $information = array('thumbnail' => '', 'title' => '', 'description' => '', 'duration' => '');
    if (!empty($iframely['links']['thumbnail'])) {
        $information['thumbnail'] = $iframely['links']['thumbnail'][0]['href'];
        if (parse_url($information['thumbnail'], PHP_URL_SCHEME) === null) {
            $information['thumbnail'] = str_replace(array('://', '//'), '', $information['thumbnail']);
            $information['thumbnail'] = "http://" . $information['thumbnail'];
        }
    }
    if (!empty($iframely['meta']['title'])) {
        $information['title'] = $iframely['meta']['title'];
    }
    if (!empty($iframely['meta']['description'])) {
        $information['description'] = $iframely['meta']['description'];
    }
    if (!empty($iframely['meta']['duration'])) {
        $information['duration'] = $iframely['meta']['duration'];
    }
    $information['code'] = $iframely['html'];
    return $information;
  }
  
  public function setPhoto($photo, $thought_id)
  {
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
      throw new Sesthought_Model_Exception('invalid argument passed to setPhoto');
    }

    if( !$fileName ) {
      $fileName = basename($file);
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    
    $params = array(
      'parent_type' => 'sesthought_thought',
      'parent_id' => $thought_id,
      'user_id' => $viewer_id,
      'name' => $fileName,
    );

    // Save
    $filesTable = Engine_Api::_()->getItemTable('storage_file');

    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(720, 720)
      ->write($mainPath)
      ->destroy();

    // Resize image (main)
    $profilePath = $path . DIRECTORY_SEPARATOR . $base . '_p.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(720, 720)
      ->write($profilePath)
      ->destroy();

    // Store
    $iMain = $filesTable->createFile($mainPath, $params);
    $iIconprofile = $filesTable->createFile($profilePath, $params);
    
    $iMain->bridge($iIconprofile, 'thumb.profile');
    
    // Remove temp files
    @unlink($mainPath);
    @unlink($profilePath);

    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    $photo_id = $iMain->file_id;
    //$this->save();
    
    return $photo_id;
  }
}
