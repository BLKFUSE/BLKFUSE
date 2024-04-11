<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Api_Core extends Core_Api_Abstract {

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
  
    public function getMembers($param) {

        $table = Engine_Api::_()->getDbTable('users', 'user');
        $select = $table->select()
                ->where('search = ?', 1)
                ->where('enabled = ?', 1)
                ->limit(6);
        if($param == 'Newest') {
            $select->order('creation_date DESC');
        } else if($param == 'Popular') {
            $select->order('view_count DESC');
        } else {
            $select->order('modified_date DESC');
        }

        return $table->fetchAll($select);
    }

	function getModuleRecordsOfUser($item,$user_id){
		$rName = Engine_Api::_()->getItemTable($item);

		if( !engine_in_array('owner_id', $rName->info('cols')) )
			$field = 'user_id';
		else
			$field = 'owner_id';

    $rating_sum = $rName->select()
            ->from($rName->info('name'), new Zend_Db_Expr('COUNT(*)'))
            ->where($field.' = ?', $user_id)
            ->group($field)
            ->query()
            ->fetchColumn();
    if (!$rating_sum)
      return 0;
    return $rating_sum;
	}

  public function checkMemberOnline($userId = 0) {
    $onlineTable = Engine_Api::_()->getDbtable('online', 'user');
    $onlineTableName = $onlineTable->info('name');
    return $onlineTable->select()
                    ->from($onlineTableName, 'user_id')
                    ->where($onlineTableName . '.user_id  =?', $userId)
                    ->where($onlineTableName . '.active > ?', new Zend_Db_Expr('DATE_SUB(NOW(),INTERVAL 20 MINUTE)'))
                    ->query()
                    ->fetchColumn();
  }

  public function getModulesEnable(){
    $modules = Engine_Api::_()->getDbTable('modules','core')->getEnabledModuleNames();
    $moduleArray = array();
    if(engine_in_array('album',$modules))
      $moduleArray['album'] = 'Albums';
    if(engine_in_array('blog',$modules))
      $moduleArray['blog'] = 'Blogs';
    if(engine_in_array('video',$modules))
      $moduleArray['video'] = 'Videos';
    if(engine_in_array('classified',$modules))
      $moduleArray['classified'] = 'Classifieds';
    if(engine_in_array('group',$modules))
      $moduleArray['group'] = 'Groups';
    if(engine_in_array('event',$modules))
      $moduleArray['event'] = 'Events';
    if(engine_in_array('music_playlist',$modules))
      $moduleArray['music'] = 'Music';
    if(engine_in_array('sesalbum',$modules))
      $moduleArray['sesalbum_album'] = 'Advanced Photos & Albums Plugin';
    if(engine_in_array('sesblog',$modules))
      $moduleArray['sesblog_blog'] = 'Advanced Blog Plugin';
    if(engine_in_array('sesvideo',$modules))
      $moduleArray['sesvideo_video'] = 'Advanced Videos & Channels Plugin';
    if(engine_in_array('sesevent',$modules))
      $moduleArray['sesevent_event'] = 'SNS - Advanced Events Plugin';
    if(engine_in_array('sesmusic',$modules))
      $moduleArray['sesmusic_album'] = 'Advanced Music Albums, Songs & Playlists Plugin';
    return $moduleArray;
  }
  public function getMenuIcon($menuName) {

    $table = Engine_Api::_()->getDbTable('menuitems', 'core');
    $menuId =  $table->select()
                    ->from($table, 'id')
                    ->where('name =?', $menuName)
                    ->query()
                    ->fetchColumn();
    if($menuId){
      $row = Engine_Api::_()->getDbTable('menusicons','sesbasic')->getRow($menuId);
    if($row)
      return $row->icon_id;
    }
   return false;
  }

  public function setPhoto($photo, $menuId = null) {

    //GET PHOTO DETAILS
    $mainName = dirname($photo['tmp_name']) . '/' . $photo['name'];

    //GET VIEWER ID
    $photo_params = array(
        'parent_id' => $menuId,
        'parent_type' => "sesdating_slideshow_image",
    );
    copy($photo['tmp_name'], $mainName);
    try {
      $photoFile = Engine_Api::_()->storage()->create($mainName, $photo_params);
    } catch (Exception $e) {
      if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE) {
        echo $e->getMessage();
        exit();
      }
    }

    return $photoFile;
  }

  public function getContantValueXML($key) {
    $filePath = APPLICATION_PATH . "/application/settings/constants.xml";
    $results = simplexml_load_file($filePath);
    $xmlNodes = $results->xpath('/root/constant[name="' . $key . '"]');
    $nodeName = @$xmlNodes[0];
    $value = @$nodeName->value;
    return $value;
  }

  public function readWriteXML($keys, $value, $default_constants = null) {

    $filePath = APPLICATION_PATH . "/application/settings/constants.xml";
    $results = simplexml_load_file($filePath);
    $contactsThemeArray = array();
    if (!empty($keys) && !empty($value) && ($keys != 'dating_body_background_image' || $keys != 'dating_footer_background_image')) {
      $contactsThemeArray = array($keys => $value);
    } elseif (!empty($keys) && ($keys == 'dating_body_background_image' || $keys == 'dating_footer_background_image')) {
      $contactsThemeArray = array($keys => '');
    } elseif ($default_constants) {
      $contactsThemeArray = $default_constants;
    }

    foreach ($contactsThemeArray as $key => $value) {
      $xmlNodes = $results->xpath('/root/constant[name="' . $key . '"]');
      $nodeName = @$xmlNodes[0];
      $params = json_decode(json_encode($nodeName));
      $paramsVal = @$params->value;
      if ($paramsVal && $paramsVal != '' && $paramsVal != null) {
        $nodeName->value = $value;
      } else {
        $entry = $results->addChild('constant');
        $entry->addChild('name', $key);
        $entry->addChild('value', $value);
      }
    }
    return $results->asXML($filePath);
  }
}
