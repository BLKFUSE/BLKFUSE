<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Photo.php 9747 2012-07-26 02:08:08Z john $
 * @author     Sami
 */

/**
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Album_Model_Photo extends Core_Model_Item_Abstract
{
    protected $_searchTriggers = array('title', 'description', 'search');

    public $skipAlbumDeleteHook;

    public function getHref($params = array())
    {
        $params = array_merge(array(
            'route' => 'album_photo_specific',
            'reset' => true,
            'album_id' => $this->album_id,
            'photo_id' => $this->getIdentity(),
            'slug' => $this->getTitle() ? $this->getSlug() : '',
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
        return $this->description;
    }

    public function getAlbum()
    {
        return Engine_Api::_()->getItem('album', $this->album_id);
    }

    public function getParent($type = null)
    {
        if( null === $type || $type === 'album' ) {
            return $this->getAlbum();
        } else {
            return $this->getAlbum()->getParent($type);
        }
    }

    /**
     * Gets a url to the current photo representing this item. Return null if none
     * set
     *
     * @param string The photo type (null -> main, thumb, icon, etc);
     * @return string The photo url
     */
    public function getPhotoUrl($type = null)
    {
        $photo_id = $this->file_id;
        if( !$photo_id ) {
            return null;
        }

        $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
        if( !$file ) {
            return null;
        }

        return $file->map();
    }

    public function isSearchable()
    {
        $album = $this->getAlbum();
        if( !($album instanceof Core_Model_Item_Abstract) ) {
            return false;
        }
        return $album->isSearchable();
    }

    public function getAuthorizationItem()
    {
        return $this->getAlbum();
    }

    public function isOwner($user)
    {
        if( empty($this->album_id) ) {
            return (($this->owner_id == $user->getIdentity()) && ($this->owner_type == $user->getType()));
        }
        return parent::isOwner($user);
    }

    public function setPhoto($photo)
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
            throw new User_Model_Exception('invalid argument passed to setPhoto');
        }

        if( !$fileName ) {
            $fileName = $file;
        }

        $name = basename($file);
        $extension = ltrim(strrchr($fileName, '.'), '.');
        $base = rtrim(substr(basename($fileName), 0, strrpos(basename($fileName), '.')), '.');
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $params = array(
            'parent_type' => $this->getType(),
            'parent_id' => $this->getIdentity(),
            'user_id' => $this->owner_id,
            'name' => $fileName,
        );

        // Save
        $filesTable = Engine_Api::_()->getDbtable('files', 'storage');

        // Resize image (main)
        $mainPath = $path . DIRECTORY_SEPARATOR . $base . '_m.' . $extension;
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate()
            ->resize(720, 720)
            ->write($mainPath)
            ->destroy();

        // Resize image (normal)
        $normalPath = $path . DIRECTORY_SEPARATOR . $base . '_in.' . $extension;
        
        $image = Engine_Image::factory();
        $image->open($file)
            ->autoRotate()
            ->resize(400, 400)
            ->write($normalPath)
            ->destroy();

        // Store
        try {
            $iMain = $filesTable->createFile($mainPath, $params);
            $iIconNormal = $filesTable->createFile($normalPath, $params);

            $iMain->bridge($iIconNormal, 'thumb.normal');
        } catch( Exception $e ) {
            // Remove temp files
            @unlink($mainPath);
            @unlink($normalPath);
            // Throw
            if( $e->getCode() == Storage_Model_DbTable_Files::SPACE_LIMIT_REACHED_CODE ) {
                throw new Album_Model_Exception($e->getMessage(), $e->getCode());
            } else {
                throw $e;
            }
        }

        // Remove temp files
        @unlink($mainPath);
        @unlink($normalPath);
        
				//Temp file delete
				$publicPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . $name;
				if(is_file($publicPath)) {
					@unlink($publicPath);
				}
				
        // Update row
        $this->modified_date = date('Y-m-d H:i:s');
        $this->file_id = $iMain->file_id;
        $this->save();

        // Delete the old file?
        if( !empty($tmpRow) ) {
            $tmpRow->delete();
        }
        
        return $this;
    }

    public function getPhotoIndex()
    {
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

    public function getNextPhoto()
    {
        $table = $this->getTable();
        $select = $table->select()
            ->where('album_id = ?', $this->album_id)
            ->where('`order` > ?', $this->order)
            ->order('order ASC')
            ->limit(1);
        $photo = $table->fetchRow($select);

        if( !$photo ) {
            // Get first photo instead
            $select = $table->select()
                ->where('album_id = ?', $this->album_id)
                ->order('order ASC')
                ->limit(1);
            $photo = $table->fetchRow($select);
        }

        return $photo;
    }

    public function getPreviousPhoto()
    {
        $table = $this->getTable();
        $select = $table->select()
            ->where('album_id = ?', $this->album_id)
            ->where('`order` < ?', $this->order)
            ->order('order DESC')
            ->limit(1);
        $photo = $table->fetchRow($select);

        if( !$photo ) {
            // Get last photo instead
            $select = $table->select()
                ->where('album_id = ?', $this->album_id)
                ->order('order DESC')
                ->limit(1);
            $photo = $table->fetchRow($select);
        }

        return $photo;
    }

    /**
     * Gets a proxy object for the comment handler
     *
     * @return Engine_ProxyObject
     * */
    public function comments()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
    }

    /**
     * Gets a proxy object for the like handler
     *
     * @return Engine_ProxyObject
     * */
    public function likes()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
    }

    /**
     * Gets a proxy object for the tags handler
     *
     * @return Engine_ProxyObject
     * */
    public function tags()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
    }

    protected function _postDelete()
    {
        $mainPhoto = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id);
        $thumbPhoto = Engine_Api::_()->getItemTable('storage_file')->getFile($this->file_id, 'thumb.normal');

        // Delete thumb
        if( $thumbPhoto && $thumbPhoto->getIdentity() ) {
            try {
                $thumbPhoto->delete();
            } catch( Exception $e ) {}
        }

        // Delete main
        if( $mainPhoto && $mainPhoto->getIdentity() ) {
            try {
                $mainPhoto->delete();
            } catch( Exception $e ) {}
        }

        // Change album cover if applicable
        try {
            if( !empty($this->album_id) && !$this->skipAlbumDeleteHook ) {
                $album = $this->getAlbum();
                $nextPhoto = $this->getNextPhoto();
                if( ($album instanceof Album_Model_Album) &&
                    ($nextPhoto instanceof Album_Model_Photo) &&
                    (int) $album->photo_id == (int) $this->getIdentity() ) {
                    $album->photo_id = $nextPhoto->getIdentity();
                    $album->save();
                }
            }
        } catch( Exception $e ) {}

        parent::_postDelete();
    }
}
