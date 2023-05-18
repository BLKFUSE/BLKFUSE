<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Photos.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Model_DbTable_Photos extends Engine_Db_Table{

	protected $_rowClass = 'Edating_Model_Photo';
	
	public function getPhotosPaginator($params) {
		$paginator = Zend_Paginator::factory($this->getPhotoSelect($params));
		if( !empty($params['page']) )
		  $paginator->setCurrentPageNumber($params['page']);
		if( !empty($params['limit']) )
		  $paginator->setItemCountPerPage($params['limit']);
		return $paginator;
	}
	
	public function getPhotoSelect($params = array()) {
	
		$rName = $this->info('name');
		$select = $this->select()->from($rName);
// 		if( !empty($params['search']) ) {
// 		  $select->where($rName.".title LIKE ? OR ".$rName.".description LIKE ?", '%'.$params['search'].'%');
// 		}
		
		if( !empty($params['user_id']) ) {
		  $select->where($rName.".user_id=?", $params['user_id']);
		}
		$select->order('photo_id DESC');
		return $select;
  }
	
	public function cleanMain()
	{
		$viewer = Engine_Api::_()->user()->getViewer();
		$rName = $this->info('name');
		$select = $this->select()->from($rName)->where("user_id=?",$viewer->getIdentity());
		
		$db = $this->getAdapter();
		$db->beginTransaction();
		foreach($this->fetchAll($select) as $item)
		{
			$item->is_main = 0;
			$item->save();
		}
		$db->commit();
	}
	
	public function getMain($user_id) {
		$select = $this->select()
                  ->from($this->info('name'))
                  ->where("user_id = ?",$user_id)
                  ->where("is_main = ?", 1)
                  ->limit(1);
		return $this->fetchAll($select);
	}
	
	public function getMainDatingPhoto($user_id) {
		$select = $this->select()
                  ->from($this->info('name'))
                  ->where("user_id = ?", $user_id)
                  ->where("is_main = ?", 1)
                  ->limit(1);
		return $this->fetchRow($select);
	}
	
	public function getPhotos($user_id, $limit = null) {
		$select = $this->select()
                  ->from($this->info('name'))
                  ->where("user_id = ?", $user_id)
                  ->order("photo_id DESC");
    if(!empty($limit)) {
      $select->limit($limit);
    }
		return $this->fetchAll($select);
	}
	
  public function uploadTemPhoto($photo){
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
    $name = basename($file);
    $extension = ltrim(strrchr($fileName, '.'), '.');
    if (!is_dir(APPLICATION_PATH.$this->_temporyPath)) {
      mkdir(APPLICATION_PATH.$this->_temporyPath, 0777, true);
    }
    $uploadFileName = md5(time().rand(1,19234876)).'.'.$extension;
    $uploadFilePath = $this->_temporyPath.DIRECTORY_SEPARATOR.$uploadFileName;
    if(copy($file,APPLICATION_PATH.$uploadFilePath)){
      return base64_encode($uploadFileName);
    }
    return false;       
  }
}
