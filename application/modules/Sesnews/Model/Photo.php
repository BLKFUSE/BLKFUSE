<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Photo.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Model_Photo extends Core_Model_Item_Collectible {

  protected $_parent_type = 'sesnews_album';
  protected $_owner_type = 'user';
  protected $_collection_type = 'sesnews_album';
  protected $_searchTriggers = false;
  protected $_modifiedTriggers = false;

//   public function getHref($params = array())
//   {
//     $params = array_merge(array(
//       'route' => 'sesnews_extended',
//       'reset' => true,
//       'controller' => 'photo',
//       'action' => 'view',
//       'news_id' => $this->getCollection()->getOwner()->getIdentity(),
//       //'album_id' => $this->collection_id,
//       'photo_id' => $this->getIdentity(),
//     ), $params);
//     $route = $params['route'];
//     $reset = $params['reset'];
//     unset($params['route']);
//     unset($params['reset']);
//     return Zend_Controller_Front::getInstance()->getRouter()
//       ->assemble($params, $route, $reset);
//   }

  public function getHref($params = array()) {
    $params = array_merge(array(
        'route' => 'sesnews_extended',
        'reset' => true,
        'controller' => 'photo',
        'action' => 'view',
        'news_id' => $this->getIdentity(),
        'album_id' => $this->album_id,
        'photo_id' => $this->getIdentity(),
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  public function setAlbumPhoto($photo, $isURL = false, $isUploadDirect = false) {
    if (!$isURL) {
      if ($photo instanceof Zend_Form_Element_File) {
        $file = $photo->getFileName();
        $fileName = $file;
      } else if ($photo instanceof Storage_Model_File) {
        $file = $photo->temporary();
        $fileName = $photo->name;
      } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
        $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
        $file = $tmpRow->temporary();
        $fileName = $tmpRow->name;
      } else if (is_array($photo) && !empty($photo['tmp_name'])) {
        $file = $photo['tmp_name'];
        $fileName = $photo['name'];
      } else if (is_string($photo) && file_exists($photo)) {
        $file = $photo;
        $fileName = $photo;
      } else {
        throw new User_Model_Exception('invalid argument passed to setPhoto');
      }
      $name = basename($file);
      $extension = ltrim(strrchr($fileName, '.'), '.');
      $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    } else {
      $fileName = time() . '_sesnews';
      $PhotoExtension = '.' . pathinfo($photo, PATHINFO_EXTENSION);
      $filenameInsert = $fileName . $PhotoExtension;
      $copySuccess = @copy($photo, APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/' . $filenameInsert);
      if ($copySuccess)
        $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . $filenameInsert;
      else
        return false;
      $name = basename($photo);
      $extension = ltrim(strrchr($name, '.'), '.');
      $base = rtrim(substr(basename($name), 0, strrpos(basename($name), '.')), '.');
    }
    if (!$fileName) {
      $fileName = $file;
    }
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => $this->getType(),
        'parent_id' => $this->getIdentity(),
        'user_id' => $this->user_id,
        'name' => $fileName,
    );
    // Save
    $filesTable = Engine_Api::_()->getDbtable('files', 'storage');
    /* setting of image dimentions from core settings */
    $main_height = 1600;
    $main_width = 1600;
    $normal_height = 500;
    $normal_width = 500;
    // Resize image (main)
    $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($main_width, $main_height)
            ->write($mainPath)
            ->destroy();
    // Resize image (normal) make same image for activity feed so it open in pop up with out jump effect.
    $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($normal_width, $normal_height)
            ->write($normalPath)
            ->destroy();

    // normal main  image resize
    $normalMainPath = $path . DIRECTORY_SEPARATOR . $base . '_nm.' . $extension;
    $image = Engine_Image::factory();
    $image->open($normalPath)
            ->resize($normal_width, $normal_height)
            ->write($normalMainPath)
            ->destroy();
    // Resize image (icon)
    $squarePath = $path . DIRECTORY_SEPARATOR . $base . '_is.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file);
    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;
    $image->resample($x, $y, $size, $size, 150, 150)
            ->write($squarePath)
            ->destroy();
    // Store
    try {
      $iSquare = $filesTable->createFile($squarePath, $params);
      $iMain = $filesTable->createFile($mainPath, $params);
      $iIconNormal = $filesTable->createFile($normalPath, $params);
      $iNormalMain = $filesTable->createFile($normalMainPath, $params);
      $iMain->bridge($iNormalMain, 'thumb.normalmain');
      $iMain->bridge($iIconNormal, 'thumb.normal');
      $iMain->bridge($iSquare, 'thumb.icon');
    } catch (Exception $e) {
      @unlink($file);
      // Remove temp files
      @unlink($mainPath);
      @unlink($normalPath);
      @unlink($squarePath);
      @unlink($normalMainPath);
      // Throw
      if ($e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE) {
        throw new Sesnews_Model_Exception($e->getMessage(), $e->getCode());
      } else {
        throw $e;
      }
    }
    @unlink($file);
    // Remove temp files
    @unlink($mainPath);
    @unlink($normalPath);
    @unlink($squarePath);
    @unlink($normalMainPath);
    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    $this->file_id = $iMain->file_id;
    $this->order = $this->photo_id;
    $this->save();
    // Delete the old file?
    if (!empty($tmpRow)) {
      $tmpRow->delete();
    }
    return $this;
  }

  public function getAlbum() {
    return Engine_Api::_()->getItem('sesnews_album', $this->album_id);
  }

  //get next photo
  public function getNextPhoto() {
    $table = $this->getTable();

    $select = $table->select()
            ->where('album_id = ?', $this->album_id)
            ->where('`order` > ?', $this->order)
            ->order('order ASC')
            ->limit(1);

    $photo = $table->fetchRow($select);

    if (!$photo) {
      // Get first photo instead
      $select = $table->select()
              ->where('album_id = ?', $this->album_id)
              ->order('order ASC')
              ->limit(1);
      $photo = $table->fetchRow($select);
    }

    return $photo;
  }

  //get previous photo
  public function getPreviousPhoto() {
    $table = $this->getTable();

    $select = $table->select()
            ->where('album_id = ?', $this->album_id)
            ->where('`order` < ?', $this->order)
            ->order('order DESC')
            ->limit(1);
    $photo = $table->fetchRow($select);

    if (!$photo) {
      // Get last photo instead
      $select = $table->select()
              ->where('album_id = ?', $this->album_id)
              ->order('order DESC')
              ->limit(1);
      $photo = $table->fetchRow($select);
    }

    return $photo;
  }

  public function getPhotoIndex() {
    return $this->getTable()
                    ->select()
                    ->from($this->getTable(), new Zend_Db_Expr('COUNT(photo_id)'))
                    ->where('album_id = ?', $this->album_id)
                    ->where('`order` < ?', $this->order)
                    ->order('order ASC')
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }

  /**
   * Gets a url to the current photo representing this item. Return null if none
   * set
   *
   * @param string The photo type (null -> main, thumb, icon, etc);
   * @return string The photo url
   */
  public function getAlbumPhotoUrl($type = null) {

    $photo_id = $this->file_id;
    if (!$photo_id) {
      return 'application/modules/Sesnews/externals/images/nophoto_album_thumb_normal.pngc=direct';
    }

    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
    if (!$file) {
      return 'application/modules/Sesnews/externals/images/nophoto_album_thumb_normal.pngc=direct';
    }
    return $file->map();
  }

  public function getPhotoUrl($type = null) {
    if (empty($this->file_id)) {
      return null;
    }

    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, $type);
    if (!$file) {
      return null;
    }

    return $file->map();
  }

  public function getSesnews() {
    return Engine_Api::_()->getItem('sesnews', $this->news_id);
    //return $this->getCollection()->getGroup();
  }

  public function isSearchable() {
    $collection = $this->getCollection();
    if (!$collection instanceof Core_Model_Item_Abstract) {
      return false;
    }
    return $collection->isSearchable();
  }

  public function getAuthorizationItem() {
    return $this->getParent('sesnews_news');
  }

  public function setPhoto($photo) {
    if ($photo instanceof Zend_Form_Element_File) {
      $file = $photo->getFileName();
      $fileName = $file;
    } else if ($photo instanceof Storage_Model_File) {
      $file = $photo->temporary();
      $fileName = $photo->name;
    } else if ($photo instanceof Core_Model_Item_Abstract && !empty($photo->file_id)) {
      $tmpRow = Engine_Api::_()->getItem('storage_file', $photo->file_id);
      $file = $tmpRow->temporary();
      $fileName = $tmpRow->name;
    } else if (is_array($photo) && !empty($photo['tmp_name'])) {
      $file = $photo['tmp_name'];
      $fileName = $photo['name'];
    } else if (is_string($photo) && file_exists($photo)) {
      $file = $photo;
      $fileName = $photo;
    } else {
      throw new Sesnews_Model_Exception('invalid argument passed to setPhoto');
    }

    if (!$fileName) {
      $fileName = basename($file);
    }

    $extension = ltrim(strrchr(basename($fileName), '.'), '.');
    $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';

    $params = array(
        'parent_type' => 'sesnews_photo',
        'parent_id' => $this->getIdentity(),
        'user_id' => $this->user_id,
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

    // Resize image (normal)
    $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize(140, 160)
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
    $this->modified_date = date('Y-m-d H:i:s');
    $this->file_id = $iMain->file_id;
    $this->save();

    return $this;
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

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }

  protected function _postDelete() {
    if ($this->_disableHooks)
      return;

    // This is dangerous, what if something throws an exception in postDelete
    // after the files are deleted?
    try {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id);
      if ($file) {
        $file->remove();
      }
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, 'thumb.normal');
      if ($file) {
        $file->remove();
      }

      $album = $this->getCollection();
      $newsItem = Engine_Api::_()->getItem("sesnews_news", $this->news_id);
      if ((int) $album->photo_id == (int) $this->getIdentity()) {
        if ($this->getNextCollectible())
          $album->photo_id = $this->getNextCollectible()->getIdentity();
        $album->save();
      }
      if (isset($newsItem)) {
        $photoItem = Engine_Api::_()->getItem("sesnews_photo", $newsItem->photo_id);
        if (!$photoItem) {
          $newsItem->photo_id = 0;
          $newsItem->save();
        }
      }
    } catch (Exception $e) {
      // @todo completely silencing them probably isn't good enough
      //throw $e;
    }
  }

}
