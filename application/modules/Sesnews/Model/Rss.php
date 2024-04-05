<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Rss.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_Rss extends Core_Model_Item_Abstract
{
  // Properties
  protected $_parent_type = 'user';
  protected $_searchTriggers = array('title', 'body', 'search');
  protected $_parent_is_owner = true;


  // General

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array())
  {
    $slug = $this->getSlug();

    $params = array_merge(array(
      'route' => 'sesnews_viewrss',
      'reset' => true,
      'rss_id' => $this->rss_id,
      'slug' => $slug,
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }

  public function getDescription()
  {
    // @todo decide how we want to handle multibyte string functions
    $tmpBody = strip_tags($this->body);
    return ( Engine_String::strlen($tmpBody) > 255 ? Engine_String::substr($tmpBody, 0, 255) . '...' : $tmpBody );
  }


  public function getPhotoUrl($type = null) {
    $photo_id = $this->photo_id;
    if ($photo_id) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->photo_id, $type);
        if($file)
            return $file->map();
        else{
            $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->photo_id,'thumb.profile');
            if($file)
                return $file->map();
        }
    }
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $defaultPhoto = Zend_Registry::get('StaticBaseUrl').'application/modules/Sesnews/externals/images/nophoto_rss_thumb_profile.png';
    return $defaultPhoto;
  }

  public function setPhoto($photo)
  {
    if( $photo instanceof Zend_Form_Element_File ) {
      $file = $photo->getFileName();
    } elseif( is_array($photo) && !empty($photo['tmp_name']) ) {
      $file = $photo['tmp_name'];
    } elseif( is_string($photo) && file_exists($photo) ) {
      $file = $photo;
    } else {
      throw new Sesnews_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
    }

    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
      'parent_type' => 'sesnews_rss',
      'parent_id' => $this->getIdentity()
    );

    // Save
    $storage = Engine_Api::_()->storage();

    // Resize image (main)
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(720, 720)
      ->write($path . '/m_' . $name)
      ->destroy();

    // Resize image (profile)
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(200, 400)
      ->write($path . '/p_' . $name)
      ->destroy();

    // Resize image (normal)
    $image = Engine_Image::factory();
    $image->open($file)
      ->resize(140, 160)
      ->write($path . '/in_' . $name)
      ->destroy();

    // Resize image (icon)
    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
      ->write($path . '/is_' . $name)
      ->destroy();

    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iProfile = $storage->create($path . '/p_' . $name, $params);
    $iIconNormal = $storage->create($path . '/in_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iProfile, 'thumb.profile');
    $iMain->bridge($iIconNormal, 'thumb.normal');
    $iMain->bridge($iSquare, 'thumb.icon');

    // Remove temp files
    @unlink($path . '/p_' . $name);
    @unlink($path . '/m_' . $name);
    @unlink($path . '/in_' . $name);
    @unlink($path . '/is_' . $name);

    // Update row
    $this->modified_date = date('Y-m-d H:i:s');
    $this->photo_id = $iMain->getIdentity();
    $this->save();

    return $this;
  }

  // Interfaces

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   **/
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }


  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   **/
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

}
