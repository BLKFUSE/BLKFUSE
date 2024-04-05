<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Albums.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Albums extends Engine_Db_Table {

  protected $_rowClass = 'Sesnews_Model_Album';

  public function getUserAlbumCount($params = array()){
    return $this->select()->from($this->info('name'), new Zend_Db_Expr('COUNT(album_id) as total_albums'))->where('news_id = ?', Engine_Api::_()->user()->getViewer()->getIdentity())->limit(1)->query()->fetchColumn();
  }

  public function getAlbumSelect($value = array()){
    // Prepare data
    $albumTableName = $this->info('name');
    $select = $this->select()
		    ->from($albumTableName)
		    ->where('search =?',1)
		    ->where($albumTableName.'.news_id =?',$value['news_id'])
		    ->group($albumTableName.'.album_id');
    return Zend_Paginator::factory($select);
  }

	public function editPhotos(){
		$albumTable = Engine_Api::_()->getItemTable('sesnews_album');
		$myAlbums = $albumTable->select()
				->from($albumTable, array('album_id', 'title'))
				->where('owner_id = ?', Engine_Api::_()->user()->getViewer()->getIdentity())
				->query()
				->fetchAll();
		return $myAlbums;
	}
}
