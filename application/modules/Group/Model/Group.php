<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Group.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Model_Group extends Core_Model_Item_Abstract
{
    protected $_parent_type = 'user';

    protected $_owner_type = 'user';

    /**
     * Gets an absolute URL to the page to view this item
     *
     * @return string
     */
    public function getHref($params = array())
    {
        $params = array_merge(array(
            'route' => 'group_profile',
            'reset' => true,
            'id' => $this->getIdentity(),
            'slug' => $this->getSlug(),
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

    public function getParent($recurseType = null)
    {
        return $this->getOwner('user');
    }

    public function getSingletonAlbum()
    {
        $table = Engine_Api::_()->getItemTable('group_album');
        $select = $table->select()
            ->where('group_id = ?', $this->getIdentity())
            ->order('album_id ASC')
            ->limit(1);

        $album = $table->fetchRow($select);

        if( null === $album )
        {
            $album = $table->createRow();
            $album->setFromArray(array(
                'group_id' => $this->getIdentity()
            ));
            $album->save();
        }

        return $album;
    }

    public function getOfficerList()
    {
        $table = Engine_Api::_()->getItemTable('group_list');
        $select = $table->select()
            ->where('owner_id = ?', $this->getIdentity())
            ->where('title = ?', 'GROUP_OFFICERS')
            ->limit(1);

        $list = $table->fetchRow($select);

        if( null === $list ) {
            $list = $table->createRow();
            $list->setFromArray(array(
                'owner_id' => $this->getIdentity(),
                'title' => 'GROUP_OFFICERS',
            ));
            $list->save();
        }

        return $list;
    }

    public function getCategory()
    {
        return Engine_Api::_()->getDbtable('categories', 'group')
            ->find($this->category_id)->current();
    }

    public function setPhoto($photo)
    {
        if( $photo instanceof Zend_Form_Element_File ) {
            $file = $photo->getFileName();
        } else if( is_array($photo) && !empty($photo['tmp_name']) ) {
            $file = $photo['tmp_name'];
        } else if( is_string($photo) && file_exists($photo) ) {
            $file = $photo;
        } else {
            throw new Group_Model_Exception('invalid argument passed to setPhoto');
        }

        $name = basename($file);
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
        $params = array(
            'parent_type' => 'group',
            'parent_id' => $this->getIdentity()
        );

        // Save
        $storage = Engine_Api::_()->storage();

        // Resize image (main)
        $image = Engine_Image::factory();
        $image->open($file)
            ->resize(720, 720)
            ->write($path.'/m_'.$name)
            ->destroy();

        // Resize image (profile)
        $image = Engine_Image::factory();
        $image->open($file)
            ->resize(400, 400)
            ->write($path.'/p_'.$name)
            ->destroy();

        // Store
        $iMain = $storage->create($path.'/m_'.$name, $params);
        $iProfile = $storage->create($path.'/p_'.$name, $params);

        $iMain->bridge($iProfile, 'thumb.profile');

        // Remove temp files
        @unlink($path.'/p_'.$name);
        @unlink($path.'/m_'.$name);

        // Update row
        $this->modified_date = date('Y-m-d H:i:s');
        $this->photo_id = $iMain->file_id;
        $this->save();

        // Add to album
        $viewer = Engine_Api::_()->user()->getViewer();
        $photoTable = Engine_Api::_()->getItemTable('group_photo');
        $groupAlbum = $this->getSingletonAlbum();
        $photoItem = $photoTable->createRow();
        $photoItem->setFromArray(array(
            'group_id' => $this->getIdentity(),
            'album_id' => $groupAlbum->getIdentity(),
            'user_id' => $viewer->getIdentity(),
            'file_id' => $iMain->getIdentity(),
            'collection_id' => $groupAlbum->getIdentity(),
        ));
        $photoItem->save();

        return $this;
    }

    public function getEventsPaginator($params = array())
    {

        $table = Engine_Api::_()->getDbtable('events', 'event');
        $select = $table->select()->where('parent_type = ?', 'group');
        $select = $select->where('parent_id = ?', $this->getIdentity());
        $select->order('starttime DESC');
        if($params['user_id'] != $params['owner_id'] && !Engine_Api::_()->user()->getViewer()->isAdmin()) {
          $select->where('approved = ?', 1);
        }
        return  Zend_Paginator::factory($select);

    }

    public function getBlogsPaginator($params = array())
    {

        $table = Engine_Api::_()->getDbtable('blogs', 'blog');
        $select = $table->select()
                        ->where('parent_type = ?', 'group')
                        ->where('parent_id = ?', $this->getIdentity());
        if($params['user_id'] != $params['owner_id'] && !Engine_Api::_()->user()->getViewer()->isAdmin()) {
          $select->where('draft =?', 0);
          $select->where('approved = ?', 1);
        }
        return  Zend_Paginator::factory($select);

    }

    public function getPollsPaginator($params = array())
    {

        $table = Engine_Api::_()->getDbtable('polls', 'poll');
        $select = $table->select()
                      ->where('parent_type = ?', 'group')
                      ->where('parent_id = ?', $this->getIdentity());
        if($params['user_id'] != $params['owner_id'] && !Engine_Api::_()->user()->getViewer()->isAdmin()) {
          $select->where('approved = ?', 1);
        }
        return  Zend_Paginator::factory($select);

    }

    public function getVideosPaginator($params = array())
    {
        $table = Engine_Api::_()->getDbtable('videos', 'video');
        $select = $table->select()->where('parent_type = ?', 'group')
        ->where('status = ?', 1)
        //->where('search = ?', 1)
        ->where('parent_id = ?', $this->getIdentity());
        if($params['user_id'] != $params['owner_id'] && !Engine_Api::_()->user()->getViewer()->isAdmin()) {
          $select->where('approved = ?', 1);
        }
        return  Zend_Paginator::factory($select);
    }

    public function membership()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('membership', 'group'));
    }

    /**
     * Gets a proxy object for the likes handler
     *
     * @return Engine_ProxyObject
     **/
    public function likes()
    {
        return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
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

    // Internal hooks

    protected function _postInsert()
    {
        if( $this->_disableHooks ) return;

        parent::_postInsert();

        // Create auth stuff
        $context = Engine_Api::_()->authorization()->context;
        foreach( array('everyone', 'registered', 'member') as $role ) {
            $context->setAllowed($this, $role, 'view', true);
        }
    }

    protected function _delete()
    {
        if( $this->_disableHooks ) return;

        // Delete all memberships
        $this->membership()->removeAllMembers();

        // Delete officer list
        $this->getOfficerList()->delete();

        // Delete all albums
        $albumTable = Engine_Api::_()->getItemTable('group_album');
        $albumSelect = $albumTable->select()->where('group_id = ?', $this->getIdentity());
        foreach( $albumTable->fetchAll($albumSelect) as $groupAlbum ) {
            $groupAlbum->delete();
        }

        // Delete all topics
        $topicTable = Engine_Api::_()->getItemTable('group_topic');
        $topicSelect = $topicTable->select()->where('group_id = ?', $this->getIdentity());
        foreach( $topicTable->fetchAll($topicSelect) as $groupTopic ) {
            $groupTopic->delete();
        }

        if (Engine_Api::_()->hasItemType('event'))
        {
            $eventTable = Engine_Api::_()->getItemTable('event');
            $eventSelect = $eventTable->select()->where("parent_type = 'group' and parent_id = ?", $this->getIdentity());
            foreach ($eventTable->fetchAll($eventSelect) as $groupEvent)
            {
                $groupEvent->delete();
            }
        }
        parent::_delete();
    }
    public function getCategoryItem()
    {
        if(!$this->category_id)
          return false;
        return Engine_Api::_()->getItem('group_category',$this->category_id);
    }
}
