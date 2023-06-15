<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Favourites.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Favourites extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Favourite";

  public function isFavourite($params = array()) {

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $status = $this->select()
            ->where('resource_type = ?', $params['resource_type'])
            ->where('resource_id = ?', $params['resource_id'])
            ->where('user_id = ?', $viewer_id)
            ->query()
            ->fetchColumn();
    if ($status)
      return 1;
    else
      return 0;
  }

  public function getItemfav($resource_type, $itemId) {
    $tableFav = Engine_Api::_()->getDbtable('favourites', 'sescontest');
    $tableMainFav = $tableFav->info('name');
    $select = $tableFav->select()->from($tableMainFav)->where('resource_type =?', $resource_type)->where('user_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('resource_id =?', $itemId);
    return $tableFav->fetchRow($select);
  }

  public function getFavourites($params = array()) {
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $select = $this->select()
            ->from($this->info('name'))
            ->where('resource_type =?', $params['resource_type'])
            ->where('user_id =?', $viewer_id);
    $albumTableName = Engine_Api::_()->getItemTable('album')->info('name');
    if ($params['resource_type'] == 'album_photo') {
      $photoTableName = Engine_Api::_()->getItemTable('album_photo')->info('name');
      $select = $select->joinLeft($photoTableName, $photoTableName . '.photo_id = ' . $this->info('name') . '.resource_id', null)
              ->where($photoTableName . '.photo_id != ?', '');
      $select = $select->joinLeft($albumTableName, $albumTableName . '.album_id = ' . $photoTableName . '.album_id', null)
              ->where($albumTableName . '.album_id !=?', '');
    } else {
      $select = $select->joinLeft($albumTableName, $albumTableName . '.album_id = ' . $this->info('name') . '.resource_id', null)
              ->where($albumTableName . '.album_id !=?', '');
    }
    return Zend_Paginator::factory($select);
  }

}
