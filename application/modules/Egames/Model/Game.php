<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Game.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Model_Game extends Core_Model_Item_Abstract{
  protected $_parent_type = 'user';
  protected $_owner_type = 'user';
	public function fields()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getApi('core', 'fields'));
  }
  public function getHref($params = array()) {
    $params = array_merge(array(
        'route' => 'egames_profile',
        'reset' => true,
				'slug' => $this->getSlug(),
        'game_id' => $this->getIdentity(),
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }
  public function getPhotoUrl($type = null,$status = false,$string = null) {
		
		$viewer = Engine_Api::_()->user()->getViewer();
	
		
    if (empty($this->photo_id)) {
      return Zend_Registry::get('StaticBaseUrl').'application/modules/Egames/externals/images/egames.png';
    } else {
      $file_id = $this->photo_id;
    }
    
    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($file_id, $type);
    if (!$file) {
			 return Zend_Registry::get('StaticBaseUrl').'application/modules/Egames/externals/images/egames.png';			
    }
		
    return $file->map();
  }
  
  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }
  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }
  protected function _postDelete() {
    parent::_postDelete();
  }
	public function tags()
  {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }
	public function setPhoto($photo){
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
			} else if( is_string($photo) ) {
				$file = $photo;
				$fileName = $photo;
				$unlink = false;
			} else {
				throw new User_Model_Exception('invalid argument passed to setPhoto');
			}
			  $name = basename($file);
				$extension = ltrim(strrchr($fileName, '.'), '.');
				$base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
		
    if( !$fileName ) {
      $fileName = $file;
    }
		 $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
      'parent_type' => $this->getType(),
      'parent_id' => $this->getIdentity(),
      'user_id' => $this->owner_id,
      'name' => $fileName,
    );
    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
      ->autoRotate()
      ->resize(500, 500)
      ->write($mainPath)
      ->destroy();
		
    // Store
    try {
      $iMain = $filesTable->createFile($mainPath, $params);      
    } catch( Exception $e ) {
			@unlink($file);
      // Remove temp files
      @unlink($mainPath);
     
      // Throw
      if( $e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE ) {
        throw new Sesalbum_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    	if(!isset($unlink))
				@unlink($file);
    // Remove temp files
      @unlink($mainPath);
     
    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    $this->photo_id = $iMain->file_id;
    $this->save();
    // Delete the old file?
    if( !empty($tmpRow) ) {
      $tmpRow->delete();
    }
    return $this;
  	
	}
}?>