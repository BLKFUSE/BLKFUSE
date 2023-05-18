<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php 2015-03-15 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Api_Core extends Core_Api_Abstract {

  public function getFileUrl($image) {
    
    $table = Engine_Api::_()->getDbTable('files', 'core');
    $result = $table->select()
                ->from($table->info('name'), 'storage_file_id')
                ->where('storage_path =?', $image)
                ->query()
                ->fetchColumn();
    if(!empty($result)) {
      $storage = Engine_Api::_()->getItem('storage_file', $result);
      return $storage->map();
    } else {
      return $image;
    }
  }
  
  /* get other module compatibility code as per module name given */
  public function getPluginItem($moduleName) {
		//initialize module item array
    $moduleType = array();
    $filePath =  APPLICATION_PATH . "/application/modules/" . ucfirst($moduleName) . "/settings/manifest.php";
		//check file exists or not
    if (is_file($filePath)) {
			//now include the file
      $manafestFile = include $filePath;
			$resultsArray =  Engine_Api::_()->getDbtable('integrateothermodules', 'sesmusic')->getResults(array('module_name'=>$moduleName));
      if (is_array($manafestFile) && isset($manafestFile['items'])) {
        foreach ($manafestFile['items'] as $item)
          if (!engine_in_array($item, $resultsArray))
            $moduleType[$item] = $item.' ';
      }
    }
    return $moduleType;
  }

  public function getWidgetPageId($widgetId) {

    $db = Engine_Db_Table::getDefaultAdapter();
    $params = $db->select()
            ->from('engine4_core_content', 'page_id')
            ->where('`content_id` = ?', $widgetId)
            ->query()
            ->fetchColumn();
    return json_decode($params, true);
  }

//Handle song upload
  public function createSong($file, $params = array()) {

    if (is_array($file)) {
      if (!is_uploaded_file($file['tmp_name']))
        throw new Sesmusic_Model_Exception('Invalid upload or file too large');

      $filename = $file['name'];
    } else if (is_string($file)) {
      $filename = $file;
    } else if ($file) {
      $name = $file->name;
      $filename = $file->storage_path;
      $file = $file->storage_path;
    } else {
      throw new Sesmusic_Model_Exception('Invalid upload or file too large');
    }
		$ffmpeg_path =  Engine_Api::_()->getApi('settings', 'core')->sesmusic_ffmpeg_path;
		$checkFfmpeg = $this->checkFfmpeg($ffmpeg_path);
		if(!$ffmpeg_path || !$checkFfmpeg){
			//Check file extension
			if (!preg_match('/\.(mp3|m4a|aac|mp4)$/iu', $filename))
				throw new Sesmusic_Model_Exception('Invalid file type');
		}
		//Upload to storage system
    if(isset($name) && !empty($name)) {
    $params = array_merge(array('type' => 'song', 'name' => $name, 'parent_type' => 'sesmusic_albumsongs', 'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'extension' => substr($filename, strrpos($filename, '.') + 1)), $params);
    } else {
      $params = array_merge(array('type' => 'song', 'name' => $filename, 'parent_type' => 'sesmusic_albumsongs', 'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'extension' => substr($filename, strrpos($filename, '.') + 1)), $params);
    }

		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR ;
    $file = Engine_Api::_()->storage()->create($file, $params);
		if($ffmpeg_path && $checkFfmpeg && $file && $file->extension != 'mp3'){
			$tmp =  APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' .
							DIRECTORY_SEPARATOR  . rand(0,1000000) . '_vconverted.mp3';
			$song = $path .$file->storage_path;
			$output = null;
			$return = null;
			$output = exec("$ffmpeg_path -i $song -acodec libmp3lame $tmp",$output,$return);
			$oldFile = Engine_Api::_()->getItem('storage_file',$file->getIdentity());
			$file = Engine_Api::_()->storage()->create($tmp, $params);
			$file->name = $oldFile->name;
			$file->save();
			$oldFile->delete();
			@unlink($tmp);
		}
		return $file;
  }
	public function checkFfmpeg($ffmpeg_path){
		 if(!$ffmpeg_path)
			return false;
		 if (!function_exists('shell_exec')) {
      return false;
    }

    if (!function_exists('exec')) {
      return false;
    }
		if (!@file_exists($ffmpeg_path) || !@is_executable($ffmpeg_path)) {
      $output = null;
      $return = null;
      exec($ffmpeg_path . ' -version', $output, $return);

      if ($return > 0) {
        return false;
      }
    }

		return true;
	}
//Likes of albums and songs by all users and friends
  public function albumsSongsLikeResults($params = array()) {

    $likeTable = Engine_Api::_()->getItemTable('core_like');
    $select = $likeTable->select()
            ->from($likeTable->info('name'), array('poster_id'))
            ->where('resource_type = ?', $params['type'])
            ->where('resource_id = ?', $params['id'])
            ->order('like_id DESC');

    if (isset($params['limit']))
      $select = $select->limit($params['limit']);

    $friendsIds = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();
    if (!empty($friendsIds) && isset($params['showUsers']) && $params['showUsers'] == 'friends')
      $select = $select->where('poster_id IN (?)', (array) $friendsIds);

    return $select->query()->fetchAll();
  }

//Total likes according to viewer_id
  public function likeIds($params = array()) {

    $likeTable = Engine_Api::_()->getItemTable('core_like');
    return $likeTable->select()
                    ->from($likeTable->info('name'), array('resource_id'))
                    ->where('resource_type = ?', $params['type'])
                    ->where('poster_id = ?', $params['id'])
                    ->query()
                    ->fetchColumn();
  }

  public function getLikesContents($params = array()) {

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $likeTable = Engine_Api::_()->getDbTable('likes', 'core');
    $select = $likeTable->select()
            ->from($likeTable->info('name'))
            ->where('resource_type =?', $params['resource_type'])
            ->where('poster_id =?', $viewer_id);
    return Zend_Paginator::factory($select);
  }

  //Song Image Work
  public function songImageURL($song) {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if($song->photo_id) {
      if(Engine_Api::_()->storage()->get($song->photo_id, '')) {
        $img_path = Engine_Api::_()->storage()->get($song->photo_id, '')->getPhotoUrl();
        $path = $img_path;
      }
     } elseif(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.songdefaultphoto')) {
      $defaultPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.songdefaultphoto');
      $path = Engine_Api::_()->sesmusic()->getFileUrl($defaultPhoto);
     } else {
      $path = $view->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/images/nophoto_albumsong_thumb_main.png';
    }
    return $path;
  }

	public function getSongFieldsCount($song) {
    if(empty($song->category_id) && empty($song->subcat_id) && empty($song->subsubcat_id))
      return 0;
    $arrayProfileIds = '';
    $categoryIds = array_filter(array('0' => $song->category_id, '1' => $song->subcat_id, '2' => $song->subsubcat_id));
    $categoryTable = Engine_Api::_()->getDbTable('categories', 'sesmusic');
    $select = $categoryTable->select()->from($categoryTable->info('name'), 'group_concat(profile_type) as profile_type')->where('category_id IN(?)', $categoryIds);
    $arrayProfileIdsArray = $categoryTable->fetchAll($select)->toArray();
    if ($arrayProfileIdsArray[0]['profile_type'])
      $arrayProfileIds = $arrayProfileIdsArray[0]['profile_type'];
    if (!empty($arrayProfileIds)) {
      $mapsTable = Engine_Api::_()->fields()->getTable('sesmusic_albumsongs', 'maps');
      return $mapsTable->select()->from($mapsTable->info('name'), 'COUNT(option_id)')->where('option_id IN(?)', $arrayProfileIds)->query()->fetchColumn();
    }
    return 0;
  }
 public function getFieldsCount($album) {
    if(empty($album->category_id) && empty($album->subcat_id) && empty($album->subsubcat_id))
      return 0;

    $arrayProfileIds = '';
    $categoryIds = array_filter(array('0' => $album->category_id, '1' => $album->subcat_id, '2' => $album->subsubcat_id));
    $categoryTable = Engine_Api::_()->getDbTable('categories', 'sesmusic');
    $select = $categoryTable->select()->from($categoryTable->info('name'), 'group_concat(profile_type) as profile_type')->where('category_id IN(?)', $categoryIds);
    $arrayProfileIdsArray = $categoryTable->fetchAll($select)->toArray();
    if ($arrayProfileIdsArray[0]['profile_type'])
      $arrayProfileIds = $arrayProfileIdsArray[0]['profile_type'];
    if (!empty($arrayProfileIds)) {
      $mapsTable = Engine_Api::_()->fields()->getTable('sesmusic_albums', 'maps');
      return $mapsTable->select()->from($mapsTable->info('name'), 'COUNT(option_id)')->where('option_id IN(?)', $arrayProfileIds)->query()->fetchColumn();
    }
    return 0;
  }
}
