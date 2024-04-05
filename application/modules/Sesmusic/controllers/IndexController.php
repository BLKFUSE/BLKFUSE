<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_IndexController extends Core_Controller_Action_Standard {

//Init
  public function init() {

//Check auth
    if (!$this->_helper->requireAuth()->setAuthParams('sesmusic_album', null, 'view')->isValid())
      return;

//Get viewer info
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
  }
  
  public function browseMusicalbumsAction() {

    $integrateothermodule_id = $this->_getParam('integrateothermodule_id', null);
    $page = 'sesmusic_index_' . $integrateothermodule_id;
    //Render
    $this->_helper->content->setContentName($page)->setEnabled();
  }
  
	public function welcomeAction(){
		// Render
    $this->_helper->content->setEnabled();	
	}
  //Home Action
  public function homeAction() {
    //Render
    $sesmusic_browse = Zend_Registry::isRegistered('sesmusic_browse') ? Zend_Registry::get('sesmusic_browse') : null;
    if(!empty($sesmusic_browse)) {
      $this->_helper->content->setEnabled();
    }
  }
	public function getIframelyInformationAction(){
		$url = trim(strip_tags($this->_getParam('uri')));
		$ajax = $this->_getParam('ajax', false);
		$information = Engine_Api::_()->sesbasic()->handleIframelyInformation($url);
		$this->view->ajax = $ajax;
		$thisvalid = !empty($information['code']);
		$iframely = $information;
		echo $thisvalid;die;
  }
	public function handleIframelyInformation($uri){
		$iframelyDisallowHost = Engine_Api::_()->getApi('settings', 'core')->getSetting('video_iframely_disallow');
		if (parse_url($uri, PHP_URL_SCHEME) === null) {
				$uri = "http://" . $uri;
		}
		$uriHost = Zend_Uri::factory($uri)->getHost();
		if ($iframelyDisallowHost && engine_in_array($uriHost, $iframelyDisallowHost)) {
				return;
		}
		$config = Engine_Api::_()->getApi('settings', 'core')->core_iframely;
		$iframely = Engine_Iframely::factory($config)->get($uri);
		if (!engine_in_array('player', array_keys($iframely['links']))) {
				return;
		}
		$information = array('thumbnail' => '', 'title' => '', 'description' => '', 'duration' => '');
		if (!empty($iframely['links']['thumbnail'])) {
				$information['thumbnail'] = $iframely['links']['thumbnail'][0]['href'];
				if (parse_url($information['thumbnail'], PHP_URL_SCHEME) === null) {
						$information['thumbnail'] = str_replace(array('://', '//'), '', $information['thumbnail']);
						$information['thumbnail'] = "http://" . $information['thumbnail'];
				}
		}
		if (!empty($iframely['meta']['title'])) {
				$information['title'] = $iframely['meta']['title'];
		}
		if (!empty($iframely['meta']['description'])) {
				$information['description'] = $iframely['meta']['description'];
		}
		if (!empty($iframely['meta']['duration'])) {
				$information['duration'] = $iframely['meta']['duration'];
		}
		$information['code'] = $iframely['html'];
		return $information;
  }
		
  public function searchAction() {

    $text = $this->_getParam('text', null);
    $actonType = $this->_getParam('actonType', null);
    $sesmusic_commonsearch = $this->_getParam('sesmusic_commonsearch', 'sesmusic_album');
    if ($sesmusic_commonsearch && $actonType == 'browse') {
      $type = $sesmusic_commonsearch;
    } else {
      if (isset($_COOKIE['sesmusic_commonsearch']))
        $type = $_COOKIE['sesmusic_commonsearch'];
      else
        $type = 'sesmusic_album';
    }

    if ($type == 'sesmusic_album') {
      $table = Engine_Api::_()->getDbTable('albums', 'sesmusic');
      $tableName = $table->info('name');
      $id = 'album_id';
      $route = 'sesmusic_album_view';
      $photo = 'thumb.icon';
      $label = 'title';
    } elseif ($type == 'sesmusic_albumsong') {
      $table = Engine_Api::_()->getDbTable('albumsongs', 'sesmusic');
      $tableName = $table->info('name');
      $id = 'albumsong_id';
      $route = 'sesmusic_albumsong_view';
      $photo = 'thumb.normal';
      $label = 'title';
    } elseif ($type == 'sesmusic_artist') {
      $table = Engine_Api::_()->getDbTable('artists', 'sesmusic');
      $tableName = $table->info('name');
      $id = 'artist_id';
      $route = '';
      $photo = 'thumb.profile';
      $label = 'name';
    } elseif ($type == 'sesmusic_playlist') {
      $table = Engine_Api::_()->getDbTable('playlists', 'sesmusic');
      $tableName = $table->info('name');
      $id = 'playlist_id';
      $route = 'sesmusic_playlist_view';
      $photo = 'thumb.profile';
      $label = 'title';
    }

    $data = array();
    $select = $table->select()->from($tableName);

    if ($type == 'sesmusic_artist') {
      $select->where('name  LIKE ? ', '%' . $text . '%')->order('name ASC');
    } else {
      $select->where('title  LIKE ? ', '%' . $text . '%')->order('title ASC');
    }

    if ($type == 'sesmusic_album')
      $select->where('search = ?', 1);

    $select->limit('40');
    $results = $table->fetchAll($select);

    foreach ($results as $result) {
      $url = $this->view->url(array($id => $result->$id, 'slug' => $result->getSlug()), $route, true);

      if ($type == 'sesmusic_albumsong' && !$result->photo_id) {
        $albumsong = Engine_Api::_()->getItem('sesmusic_albumsong', $result->albumsong_id);
        $photo = $this->view->itemPhoto($albumsong, $photo);
      } elseif ($type == 'sesmusic_artist') {
        $img_path = Engine_Api::_()->storage()->get($result->artist_photo, '')->getPhotoUrl();
        $path = $img_path;
        $photo = '<img src = "' . $path . '">';
        $url = $this->view->url(array('module' => 'sesmusic', 'controller' => 'artist', 'action' => 'view', 'artist_id' => $result->artist_id), 'default', true);
      } else {
        $photo = $this->view->itemPhoto($result, $photo);
      }

      if ($actonType == 'browse') {
        $data[] = array(
          'id' => $result->$id,
          'label' => $result->$label,
          'photo' => $photo,
          //'url' => $url,
        );
      } else {
        $data[] = array(
          'id' => $result->$id,
          'label' => $result->$label,
          'photo' => $photo,
          'url' => $url,
        );
      }
    }
    return $this->_helper->json($data);
  }

//Browse Action
  public function browseAction() {
    $sesmusic_browse = Zend_Registry::isRegistered('sesmusic_browse') ? Zend_Registry::get('sesmusic_browse') : null;
    if(!empty($sesmusic_browse)) {
      $this->_helper->content->setEnabled();
    }
  }

//Manage Action
  public function manageAction() {

//Only members can manage music
    if (!$this->_helper->requireUser()->isValid())
      return;
      
    $sesmusic_manage = Zend_Registry::isRegistered('sesmusic_manage') ? Zend_Registry::get('sesmusic_manage') : null;
    if(!empty($sesmusic_manage)) {
      //Render
      $this->_helper->content->setEnabled();
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $authorizationApi = Engine_Api::_()->authorization();

//Album Settings
    $this->view->albumlink = unserialize($settings->getSetting('sesmusic.albumlink'));

//Can create
    $this->view->canCreate = $authorizationApi->isAllowed('sesmusic_album', null, 'create');

    $this->view->canAddPlaylist = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'playlist_album');
    $this->view->canAddFavourite = $authorizationApi->isAllowed('sesmusic_album', $viewer, 'favourite_album');


    $allowShowRating = $settings->getSetting('sesmusic.ratealbum.show', 1);
    $allowRating = $settings->getSetting('sesmusic.album.rating', 1);
    if ($allowRating == 0) {
      if ($allowShowRating == 0)
        $showRating = false;
      else
        $showRating = true;
    } else
      $showRating = true;
    $this->view->showRating = $showRating;

    $values = array();
    $values['popularity'] = 'creation_date';
    $values['user'] = Engine_Api::_()->user()->getViewer()->getIdentity();
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('albums', 'sesmusic')->getPlaylistPaginator($values);
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
  }
	public function embedAction() {
    // Get subject
    $this->view->video = $video = Engine_Api::_()->core()->getSubject('pagevideo');

    // Check if embedding is allowed
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('video.embeds', 1)) {
      $this->view->error = 1;
      return;
    } else if (isset($video->allow_embed) && !$video->allow_embed) {
      $this->view->error = 2;
      return;
    }

    // Get embed code
    $this->view->embedCode = $video->getEmbedCode();
  }
  //Album Create Action
  public function createAction() {
    //Only members can upload music
    if (!$this->_helper->requireUser()->isValid())
      return;

    if (!$this->_helper->requireAuth()->setAuthParams('sesmusic_album', null, 'create')->isValid())
      return;
    //Check for single song
    $this->view->uploadCheck = $uploadCheck = Zend_Controller_Front::getInstance()->getRequest()->getParam('upload', null);
		$this->view->defaultProfileId = 1;
    //Render Layout
    $this->_helper->content->setEnabled();
		if($this->_getParam('mid',false) && empty($_POST)){
			$this->view->mid = 	$this->_getParam('mid',false);
			require_once 'services/Soundcloud.php';
			$settings = Engine_Api::_()->getApi('settings', 'core');

			$client_id = $settings->getSetting('sesmusic.scclientid');
			$client_screat = $settings->getSetting('sesmusic.scclientscreatid');
			//Create a client object with your app credentials
    	$client = new Services_Soundcloud($client_id, $client_screat);
			$track = json_decode($client->get('resolve', array('url' => $this->view->mid), array(
                  CURLOPT_SSL_VERIFYPEER => false,
                  CURLOPT_SSL_VERIFYHOST => false,
                  CURLOPT_FOLLOWLOCATION => true
      )));
      if (isset($track->urlGetContent)) {
        $file = file_get_contents($track->urlGetContent);
        if ($file != '')
          $track = json_decode($file);
      }
		}
    //Catch uploads from FLASH fancy-uploader and redirect to uploadSongAction()
    if ($this->getRequest()->getQuery('ul', false))
      return $this->_forward('upload', 'song', null, array('format' => 'json'));
      
    $this->view->resource_type = $parent_type = $this->_getParam('resource_type', null);
    $this->view->resource_id = $parent_id =  $this->_getParam('resource_id', null);
		
		if($parent_id && $parent_type && $parent_type == 'sesblog_blog'){
			$blog	= Engine_Api::_()->getItem($parent_type,$parent_id);
			if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblogpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesblogpackage.enable.package', 1)){
				$package = $blog->getPackage();
				$musicLeft = $package->allowUploadMusic($blog->orderspackage_id,true);
				if(!$musicLeft)
					return $this->_forward('notfound', 'error', 'core');
			}
		}	
		
		$sesmusic_create = Zend_Registry::isRegistered('sesmusic_create') ? Zend_Registry::get('sesmusic_create') : null;
    if(!empty($sesmusic_create)) {
      //Get form
      $this->view->form = $form = new Sesmusic_Form_Create(array(
            'defaultProfileId' => 1,
            'smoothboxType' => false,
        ));
    }
		if(isset($track) && engine_count($track)){
			$form->populate(array('title'=>$track->title,'description'=>$track->description));	
		}
    $this->view->album_id = $this->_getParam('album_id', '0');

		//Check method/data
    if (!$this->getRequest()->isPost())
      return;

    if (!$form->isValid($this->getRequest()->getPost()))
      return;
      
    // Check only for once song
    if($uploadCheck == 'song') {
      $values = $this->view->form->getValues();
      if(empty($values['file'][0])) {
          $form->addError($this->view->translate("Upload at least one song - it is required."));
          return;
      }
    }

    //Process
    $db = Engine_Api::_()->getDbTable('albums', 'sesmusic')->getAdapter();
    $db->beginTransaction();
    try {
      $album = $this->view->form->saveValues();
      $db->commit();
      //Count Songs according to album_id
      $song_count = Engine_Api::_()->getDbTable('albumsongs', 'sesmusic')->songsCount($album->album_id);
      $album->song_count = $song_count;
      $album->save();
    } catch (Exception $e) {
      $db->rollback();
      throw $e;
    }
    
    //Single Song Work
    if($uploadCheck == 'song') {
      $getAllSongs = Engine_Api::_()->getDbTable('albumsongs', 'sesmusic')->getAllSongs($album->getIdentity());
      
  			if(!empty($getAllSongs))
  				$albumsong_id = $getAllSongs[0]['albumsong_id'];
  			else
  			$albumsong_id = 0;
		//Add fields
			$customfieldform = $form->getSubForm('fields');
			if ($customfieldform) {
				$customfieldform->setItem($album);
				$customfieldform->saveValues();
			}
      if($albumsong_id) {
        
        //Feed generate when single song upload
        $activity = Engine_Api::_()->getDbtable('actions', 'activity');
        $action = $activity->addActivity(Engine_Api::_()->user()->getViewer(), $getAllSongs[0], 'sesmusic_song_new');
        if (null !== $action)
          $activity->attachActivity($action, $getAllSongs[0]);
          
        return $this->_helper->redirector->gotoRoute(array('action' => 'view', 'albumsong_id' => $albumsong_id, 'slug' => $getAllSongs[0]->getSlug()), 'sesmusic_albumsong_view', true);
      }
    } else {
      if ($album->resource_type == 'sesvideo_chanel' && $album->resource_id) {
        return $this->_helper->redirector->gotoRoute(array('action' => 'index', 'chanel_id' => $album->resource_id), 'sesvideo_chanel_view', true);
      } elseif ($album->resource_type == 'sesevent_event' && $album->resource_id) {
        return $this->_helper->redirector->gotoRoute(array('id' => $album->resource_id), 'sesevent_profile', true);
      } elseif ($album->resource_type == 'sesblog_blog' && $album->resource_id) {
        $tab_id = Engine_Api::_()->sesbasic()->getWidgetTabId(array('name' => 'sesblog.profile-musicalbums'));
        $custom_url = Engine_Api::_()->getItem('sesblog_blog', $album->resource_id)->custom_url;
        return $this->_helper->redirector->gotoRoute(array('action' => 'view','blog_id'=>$custom_url, 'tab' => $tab_id),'sesblog_entry_view',true);
      } else if($album->resource_id && $album->resource_type) {
        $resource = Engine_Api::_()->getItem($album->resource_type, $album->resource_id);
        header('location:' . $resource->getHref());
        die;
      } else {
				//Add fields
				$customfieldform = $form->getSubForm('fields');
				$album->resource_type = 'sesmusic_albums';
				$album->resource_id =$album->album_id ;
				if ($customfieldform) {
					$customfieldform->setItem($album);
					$customfieldform->saveValues();
				}
        return $this->_helper->redirector->gotoRoute(array('action' => 'view', 'album_id' => $album->album_id, 'slug' => $album->getSlug()), 'sesmusic_album_view', true);
      }
    }
  }

//Album Delete Action
  public function deleteAction() {

    $album = Engine_Api::_()->getItem('sesmusic_album', $this->getRequest()->getParam('album_id'));

    if (!$this->_helper->requireAuth()->setAuthParams($album, null, 'delete')->isValid())
      return;

//In smoothbox
    $this->_helper->layout->setLayout('default-simple');

//Get From
    $this->view->form = new Sesmusic_Form_Delete();

    if (!$album) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_("Album doesn't exists or not authorized to delete");
      return;
    }

    if (!$this->getRequest()->isPost()) {
      $this->view->status = false;
      $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      return;
    }

      
		if($album->resource_type == 'sesblog_blog') {
			$tab_id = Engine_Api::_()->sesbasic()->getWidgetTabId(array('name' => 'sesblog.profile-musicalbums'));
			$blogUrl = Engine_Api::_()->getItem('sesblog_blog', $album->resource_id);
			$redirectUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'view', 'blog_id'=>$blogUrl, 'tab' => $tab_id), 'sesblog_entry_view', true);
		}
		else {
		  $redirectUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'sesmusic_general', true);
		}

    $ratingTable = Engine_Api::_()->getDbtable('ratings', 'sesmusic');
    $db = $album->getTable()->getAdapter();
    $db->beginTransaction();
    try {
//All songs delete from the album
      foreach ($album->getSongs() as $song) {
        $ratingTable->delete(array('resource_id =?' => $song->albumsong_id, 'resource_type =?' => 'sesmusic_albumsong'));
        Engine_Api::_()->getDbtable('playlistsongs', 'sesmusic')->delete(array('albumsong_id =?' => $song->albumsong_id));
        $song->deleteUnused();
      }
//Delete rating accociate with deleted album
      $ratingTable->delete(array('resource_id =?' => $album->album_id, 'resource_type =?' => 'sesmusic_album'));
      $album->delete();
      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $this->view->status = true;
    $this->view->message = Zend_Registry::get('Zend_Translate')->_('The selected albums has been deleted.');
    return $this->_forward('success', 'utility', 'core', array('parentRedirect' => $redirectUrl, 'messages' => Array($this->view->message)));
  }

//Action for show all like by users when click on the view all link
  public function allLikesAction() {

    $this->view->id = $id = $this->_getParam('id');
    $this->view->type = $type = $this->_getParam('type');
    $this->view->showUsers = $showUsers = $this->_getParam('showUsers');
    $this->view->viewmore = $this->_getParam('viewmore', 0);

    $likeTable = Engine_Api::_()->getItemTable('core_like');
    $likeTableName = $likeTable->info('name');

    $select = $likeTable->select()
            ->from($likeTableName, array('poster_id'))
            ->where($likeTableName . '.resource_type = ?', $type)
            ->where($likeTableName . '.resource_id = ?', $id);

    if ($showUsers != 'all') {
      $friendids = Engine_Api::_()->user()->getViewer()->membership()->getMembershipsOfIds();
      $select = $select->where($likeTableName . '.poster_id IN (?)', (array) $friendids);
    } else {
      $select = $select->where($likeTableName . '.poster_id != ?', 0);
    }
    $select = $select->order('like_id DESC');
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage(20);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $this->view->count = $paginator->getTotalItemCount();
  }

//Subcategory action
  public function subcategoryAction() {
		
    $category_id = $this->_getParam('category_id', null);
    $param = $this->_getParam('param');
    if (!$category_id)
      return;
    $subcategory = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $category_id, 'param' => $param));
    $count_subcat = engine_count($subcategory->toarray());
    $data = '';
    if ($subcategory && $count_subcat) {
      $data .= '<option value="0">' . "Select 2nd-level Category" . '</option>';
      foreach ($subcategory as $category) {
        $data .= '<option value="' . $category["category_id"] . '">' . $category["category_name"] . '</option>';
      }
    }
		
    echo $data;
    die;
  }

//Subsubcategory action
  public function subsubcategoryAction() {
    $category_id = $this->_getParam('subcategory_id',$this->_getParam('category_id',null));
    $param = $this->_getParam('param');
    $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $category_id, 'param' => $param));
    $count_subsubcat = engine_count($subsubcategory->toarray());

    $data = '';
    if ($subsubcategory && $count_subsubcat) {
      $data .= '<option value="0">' . "Select 3rd-level Category" . '</option>';
      foreach ($subsubcategory as $category) {
        $data .= '<option value="' . $category["category_id"] . '">' . $category["category_name"] . '</option>';
      }
    }
    echo $data;
    die;
  }

  public function soundcloudintAction() {

    require_once 'services/Soundcloud.php';

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $client_id = $settings->getSetting('sesmusic.scclientid');
    $client_screat = $settings->getSetting('sesmusic.scclientscreatid');

    if (empty($client_id) && empty($client_screat))
      return;

//Create a client object with your app credentials
    $client = new Services_Soundcloud($client_id, $client_screat);

    $songURL = $this->_getParam('song_url');

//Upload From Sound Clous playlist, user and groups tracks
    if (strpos($songURL, 'playlists') > 0) {
      $playlistId = str_replace("https://api.soundcloud.com/playlists/", "", $songURL);
      $tracks_json = file_get_contents('https://api.soundcloud.com/playlists/' . $playlistId . '/tracks.json?client_id=' . $client_id);
      $tracks = json_decode($tracks_json);
    } elseif (strpos($songURL, 'groups') > 0) {
      $playlistId = str_replace("https://api.soundcloud.com/groups/", "", $songURL);
      $tracks_json = file_get_contents('http://api.soundcloud.com/groups/' . $playlistId . '/tracks.json?client_id=' . $client_id);
      $tracks = json_decode($tracks_json);
    } elseif (strpos($songURL, 'users') > 0) {
      $playlistId = str_replace("https://api.soundcloud.com/users/", "", $songURL);
      $tracks_json = file_get_contents('http://api.soundcloud.com/users/' . $playlistId . '/tracks.json?client_id=' . $client_id);
      $tracks = json_decode($tracks_json);
    }


    if (!empty($tracks)) {
//Upload From Sound Clous playlist, user and groups tracks
      $trackIds = array();
      if (!empty($tracks)) {
        foreach ($tracks as $track) {

          $trackID = $track->id;
          $trackTitle = $track->title;
          $streamable = $track->streamable;
          $downloadable = $track->downloadable;
          $purchase_url = $track->purchase_url;
          $songURL = $track->permalink_url;


          if ($track->id && $track->title && empty($track->tracks) && isset($track->streamable) && $track->streamable) {

//Upload to storage system
            $downloadable = isset($track->downloadable) ? $track->downloadable : false;
            if (!empty($track->purchase_url))
              $downloadable = false;

            $row = Engine_Api::_()->getItemTable('storage_file')->createRow();
            $row->setFromArray(array('type' => 'song', 'name' => $track->title, 'parent_type' => 'sesmusic_albumsongs', 'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'extension' => 'mp3', 'storage_path' => $songURL, 'size' => $track->id, 'mime_minor' => $downloadable));
            $row->save();
            if ($row->file_id) {
              $trackIds[] = $row->file_id;
              if ($track->artwork_url) {
                $imageURL = str_replace("https://", "", $track->artwork_url);
                $imageURL = str_replace("large.jpg", "t500x500.jpg", $imageURL);
                $imageURL = "http://" . $imageURL;
                if ($imageURL)
                  @copy($imageURL, APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/' . $row->file_id . '.jpg');
              }
              $this->view->file_id = $trackIds;
            }
          }
        }
      } else {
        $this->view->file_id = 0;
      }
    } else {

//Resolve track URL into track resource
      $track = json_decode($client->get('resolve', array('url' => $songURL), array(
                  CURLOPT_SSL_VERIFYPEER => false,
                  CURLOPT_SSL_VERIFYHOST => false,
                  CURLOPT_FOLLOWLOCATION => true
      )));
      if (isset($track->urlGetContent)) {
        $file = file_get_contents($track->urlGetContent);
        if ($file != '')
          $track = json_decode($file);
      }
      if ($track->id && $track->title && empty($track->tracks) && isset($track->streamable) && $track->streamable) {

//Upload to storage system
        $downloadable = isset($track->downloadable) ? $track->downloadable : false;
        if (!empty($track->purchase_url))
          $downloadable = false;

        $row = Engine_Api::_()->getItemTable('storage_file')->createRow();
        $row->setFromArray(array('type' => 'song', 'name' => $track->title, 'parent_type' => 'sesmusic_albumsongs', 'parent_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'extension' => 'mp3', 'storage_path' => $songURL, 'size' => $track->id, 'mime_minor' => $downloadable));
        $row->save();
        if ($row->file_id)
          $this->view->file_id = $row->file_id;
        if ($track->artwork_url) {
          $imageURL = str_replace("https://", "", $track->artwork_url);
          $imageURL = str_replace("large.jpg", "t500x500.jpg", $imageURL);
          $imageURL = "http://" . $imageURL;
          if ($imageURL)
            @copy($imageURL, APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/' . $row->file_id . '.jpg');
        }
      } else {
        $this->view->file_id = 0;
      }
    }
  }

  public function soundcloudSongDeleteAction() {
    $file_id = $this->_getParam('file_id');
    if (empty($file_id))
      return;
    $storageTable = Engine_Api::_()->getItem('storage_file', $file_id);
    $storageTable->delete();
  }

}
		
		
