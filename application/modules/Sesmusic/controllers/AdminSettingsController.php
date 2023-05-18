<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AdminSettingsController.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_AdminSettingsController extends Core_Controller_Action_Admin {

  public function indexAction() {

    $db = Engine_Db_Table::getDefaultAdapter();

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_settings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main_settings', array(), 'sesmusic_admin_main_subglobalsettings');
		    // Check ffmpeg path for correctness
    if (function_exists('exec')) {
      $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sesmusic_ffmpeg_path;

      $output = null;
      $return = null;
      if (!empty($ffmpeg_path)) {
        exec($ffmpeg_path . ' -version', $output, $return);
      }
      // Try to auto-guess ffmpeg path if it is not set correctly
      $ffmpeg_path_original = $ffmpeg_path;
      if (empty($ffmpeg_path) || $return > 0 || stripos(join('', $output), 'ffmpeg') === false) {
        $ffmpeg_path = null;
        // Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
          // @todo
        }
        // Not windows
        else {
          $output = null;
          $return = null;
          @exec('which ffmpeg', $output, $return);
          if (0 == $return) {
            $ffmpeg_path = array_shift($output);
            $output = null;
            $return = null;
            exec($ffmpeg_path . ' -version 2>&1', $output, $return);
            if ($output == null) {
              $ffmpeg_path = null;
            }
          }
        }
      }
      if ($ffmpeg_path != $ffmpeg_path_original) {
        Engine_Api::_()->getApi('settings', 'core')->sesmusic_ffmpeg_path = $ffmpeg_path;
      }
    }
    $this->view->form = $form = new Sesmusic_Form_Admin_Global();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

				if (@$_POST['sesmusic_changelanding']) {
					$this->landingpageSet();
				}
      if (isset($_POST['sesmusic_uploadoption']) && ($_POST['sesmusic_uploadoption'] == 'both' || $_POST['sesmusic_uploadoption'] == 'soundCloud')) {

        if (empty($_POST['sesmusic_scclientid'])) {
          Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.uploadoption', $_POST['sesmusic_uploadoption']);
          $error = Zend_Registry::get('Zend_Translate')->_('SoundCloud Client Id * Please complete this field - it is required.');
          $form->getDecorator('errors')->setOption('escape', false);
          $form->addError($error);
          return;
        }

        if (empty($_POST['sesmusic_scclientscreatid'])) {
          Engine_Api::_()->getApi('settings', 'core')->setSetting('sesmusic.uploadoption', $_POST['sesmusic_uploadoption']);
          $error = Zend_Registry::get('Zend_Translate')->_('SoundCloud Client Secret * Please complete this field - it is required.');
          $form->getDecorator('errors')->setOption('escape', false);
          $form->addError($error);
          return;
        }
      }

      //Footer Page >> Placed player widget if not there.
      $page_id = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'footer')
              ->limit(1)
              ->query()
              ->fetchColumn();
      if ($page_id) {
        $content_id = $db->select()
                ->from('engine4_core_content', 'content_id')
                ->where('name = ?', 'main')
                ->where('page_id = ?', $page_id)
                ->where('type = ?', 'container')
                ->limit(1)
                ->query()
                ->fetchColumn();
        $widget_id = $db->select()
                ->from('engine4_core_content', 'content_id')
                ->where('name = ?', 'sesmusic.player')
                ->where('page_id = ?', $page_id)
                ->where('type = ?', 'widget')
                ->limit(1)
                ->query()
                ->fetchColumn();
        if (empty($widget_id) && $content_id) {
          $db->query("INSERT IGNORE INTO `engine4_core_content` (`page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES ('" . $page_id . "', 'widget', 'sesmusic.player', '" . $content_id . "', '1', NULL, NULL);");
        }
      }


      $values = $form->getValues();
      include_once APPLICATION_PATH . "/application/modules/Sesmusic/controllers/License.php";
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.pluginactivated')) {
        if (empty($values['playlist_defaultphoto']))
          unset($values['playlist_defaultphoto']);
        foreach ($values as $key => $value) {
            if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
                Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
            if (!$value && strlen($value) == 0)
                continue;
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
        }
        $form->addNotice('Your changes have been saved successfully.');
        if(@$error)
        $this->_helper->redirector->gotoRoute(array());
      }
    }
  }
 public function utilityAction() {
    if (defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER) {
      return $this->_helper->redirector->gotoRoute(array(), 'admin_default', true);
    }
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_utility');
    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sesmusic_ffmpeg_path;
    if (function_exists('shell_exec')) {
      // Get version
      $this->view->version = $version = shell_exec(escapeshellcmd($ffmpeg_path) . ' -version 2>&1');
      $command = "$ffmpeg_path -formats 2>&1";
      $this->view->format = $format = shell_exec(escapeshellcmd($ffmpeg_path) . ' -formats 2>&1')
              . shell_exec(escapeshellcmd($ffmpeg_path) . ' -codecs 2>&1');
    }
  }
  public function albumSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_settings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main_settings', array(), 'sesmusic_admin_main_subalbumssettings');

    $this->view->form = $form = new Sesmusic_Form_Admin_AlbumSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      if (isset($values['sesmusic_albumlink']))
        $values['sesmusic_albumlink'] = serialize($values['sesmusic_albumlink']);
      else
        $values['sesmusic_albumlink'] = serialize(array());
      if (empty($values['album_cover']))
        unset($values['album_cover']);
      if (empty($values['album_defaultphoto']))
        unset($values['album_defaultphoto']);
      foreach ($values as $key => $value) {
            if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
                Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
            if (!$value && strlen($value) == 0)
                continue;
            Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved successfully.');
    }
  }
    public function supportAction() {
        $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_support');
    }
  public function songSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_settings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main_settings', array(), 'sesmusic_admin_main_subsongssettings');

    $this->view->form = $form = new Sesmusic_Form_Admin_SongSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      if (isset($values['sesmusic_songlink']))
        $values['sesmusic_songlink'] = serialize($values['sesmusic_songlink']);
      else
        $values['sesmusic_songlink'] = serialize(array());
      if (empty($values['albumsong_cover']))
        unset($values['albumsong_cover']);
      if (empty($values['albumsong_default']))
        unset($values['albumsong_default']);
      foreach ($values as $key => $value) {
        if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
        if (!$value && strlen($value) == 0)
            continue;
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved successfully.');
    }
  }

  public function artistSettingsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_settings');
    $this->view->subNavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main_settings', array(), 'sesmusic_admin_main_subartistssettings');

    $this->view->form = $form = new Sesmusic_Form_Admin_ArtistSettings();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      if (isset($values['sesmusic_artistlink']))
        $values['sesmusic_artistlink'] = serialize($values['sesmusic_artistlink']);
      else
        $values['sesmusic_artistlink'] = serialize(array());

      foreach ($values as $key => $value) {
        if (Engine_Api::_()->getApi('settings', 'core')->hasSetting($key, $value))
            Engine_Api::_()->getApi('settings', 'core')->removeSetting($key);
        if (!$value && strlen($value) == 0)
            continue;
        Engine_Api::_()->getApi('settings', 'core')->setSetting($key, $value);
      }
      $form->addNotice('Your changes have been saved successfully.');
    }
  }

  //Manage all artists
  public function artistsAction() {

    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_artist');
    $artistsTable = Engine_Api::_()->getDbTable('artists', 'sesmusic');
    $select = $artistsTable->select()->order('order ASC');
    $this->view->paginator = $artistsTable->fetchAll($select);
  }

  //Add new artist
  public function addArtistAction() {

    //Set Layout
    $this->_helper->layout->setLayout('admin-simple');

    //Render Form
    $this->view->form = $form = new Sesmusic_Form_Admin_AddArtist();

    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      if (empty($values['artist_photo'])) {
        $error = Zend_Registry::get('Zend_Translate')->_('Artist Photo * Please choose a photo for artist. it is required.');
        $form->getDecorator('errors')->setOption('escape', false);
        $form->addError($error);
        return;
      }

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        $row = Engine_Api::_()->getDbtable('artists', 'sesmusic')->createRow();
        $row->name = $values["name"];
        $row->overview = $values["overview"];
        $row->save();

        //Upload categories photo
        if (isset($_FILES['artist_photo'])) {
          $photoFileIcon = $this->setPhoto($form->artist_photo, $row->artist_id);
          if (!empty($photoFileIcon->file_id))
            $row->artist_photo = $photoFileIcon->file_id;
        }
        $row->save();
        $db->commit();

        if ($row->artist_id)
          $db->update('engine4_sesmusic_artists', array('order' => $row->artist_id), array('artist_id = ?' => $row->artist_id));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('You have successfully add artist.'))
      ));
    }
  }

  //Edit Artist
  public function editArtistAction() {

    $this->_helper->layout->setLayout('admin-simple');

    //Get artist id
    $artistTable = Engine_Api::_()->getItem('sesmusic_artists', $this->_getParam('artist_id'));

    $this->view->form = $form = new Sesmusic_Form_Admin_EditArtist();
    $form->button->setLabel("Save");

    //Populate the form values
    $form->populate($artistTable->toArray());

    //Check post
    if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

      $values = $form->getValues();
      if (empty($values['artist_photo']))
        unset($values['artist_photo']);

      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {

        $artistTable->name = $values["name"];
        $artistTable->overview = $values["overview"];
        $artistTable->save();

        if (isset($_FILES['artist_photo']) && !empty($_FILES['artist_photo']['name'])) {
          $previous_artist_photo = $artistTable->artist_photo;
          $photoFileIcon = $this->setPhoto($form->artist_photo, $artistTable->artist_id);
          if (!empty($photoFileIcon->file_id)) {
            if ($previous_artist_photo) {
              $file = Engine_Api::_()->getItem('storage_file', $previous_artist_photo);
              $file->delete();
            }
            $artistTable->artist_photo = $photoFileIcon->file_id;
            $artistTable->save();
          }
        }

        if (isset($values['remove_artist_photo']) && !empty($values['remove_artist_photo'])) {
          $file = Engine_Api::_()->getItem('storage_file', $artistTable->artist_photo);
          $artistTable->artist_photo = 0;
          $artistTable->save();
          $file->delete();
        }
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      return $this->_forward('success', 'utility', 'core', array(
                  'smoothboxClose' => 10,
                  'parentRefresh' => 10,
                  'messages' => array('You have successfully edit artist entry.')
      ));
    }
    //Output
    $this->renderScript('admin-settings/edit-artist.tpl');
  }

  //Delete artist
  public function deleteArtistAction() {

    $this->_helper->layout->setLayout('admin-simple');

    if ($this->getRequest()->isPost()) {
      $db = Engine_Db_Table::getDefaultAdapter();
      $db->beginTransaction();
      try {
        Engine_Api::_()->getDbtable('artists', 'sesmusic')->delete(array('artist_id =?' => $this->_getParam('artist_id')));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $this->_forward('success', 'utility', 'core', array(
          'smoothboxClose' => 10,
          'parentRefresh' => 10,
          'messages' => array(Zend_Registry::get('Zend_Translate')->_('You have successfully delete entry'))
      ));
    }
    $this->renderScript('admin-settings/delete-artist.tpl');
  }

  public function multiDeleteArtistsAction() {

    if ($this->getRequest()->isPost()) {
      $values = $this->getRequest()->getPost();
      foreach ($values as $key => $value) {
        if ($key == 'delete_' . $value) {
          $explodedKey = explode('_', $key);
          $artists = Engine_Api::_()->getItem('sesmusic_artists', $explodedKey[1]);
          $artists->delete();
        }
      }
    }
    $this->_helper->redirector->gotoRoute(array('action' => 'artists'));
  }
  

  public function orderAction() {
    $table = Engine_Api::_()->getDbTable('artists', 'sesmusic');
    $results = $table->fetchAll($table->select());
    $orders = $this->getRequest()->getParam('order');
    foreach ($results as $result) {
      $key = array_search ('artist_'.$result->getIdentity(), $orders);
      $result->order = $key+1;
      $result->save();
    }
    return;
  }

  public function getAlbumsAction() {

    if (isset($_COOKIE['sesmusic_oftheday']))
      $type = $_COOKIE['sesmusic_oftheday'];
    else
      $type = 'album';

    if ($type == 'album') {
      $table = Engine_Api::_()->getDbtable('albums', 'sesmusic');
      $id = 'album_id';
      $photo = 'thumb.icon';
      $label = 'title';
    } elseif ($type == 'albumsong') {
      $table = Engine_Api::_()->getDbtable('albumsongs', 'sesmusic');
      $id = 'albumsong_id';
      $photo = 'thumb.profile';
      $label = 'title';
    } elseif ($type == 'artist') {
      $table = Engine_Api::_()->getDbtable('artists', 'sesmusic');
      $id = 'artist_id';
      $photo = 'thumb.profile';
      $label = 'name';
    } elseif ($type == 'playlist') {
      $table = Engine_Api::_()->getDbTable('playlists', 'sesmusic');
      $id = 'playlist_id';
      $photo = 'thumb.profile';
      $label = 'title';
    }

    $data = array();
    $select = $table->select();
    if ($type == 'artist') {
      $select->where('name  LIKE ? ', '%' . $this->_getParam('text') . '%')->order('name ASC')->limit('40');
    } else {
      $select->where('title  LIKE ? ', '%' . $this->_getParam('text') . '%')->order('title ASC')->limit('40');
    }

    if ($type == 'album')
      $select->where("search = ?", 1);

    $results = $table->fetchAll($select);
    foreach ($results as $result) {

      if ($type == 'albumsong' && !$result->photo_id) {
        $album = Engine_Api::_()->getItem('sesmusic_album', $result->album_id);
        $photoItem = $this->view->itemPhoto($album, $photo);
      } elseif ($type == 'artist') {
        $img_path = Engine_Api::_()->storage()->get($result->artist_photo, '')->getPhotoUrl();
        $path = $img_path;
        $photoItem = '<img src = "' . $path . '">';
      } else {
        $photoItem = $this->view->itemPhoto($result, $photo);
      }

      $data[] = array(
          'id' => $result->$id,
          'label' => $result->$label,
          'photo' => $photoItem
      );
    }
    return $this->_helper->json($data);
  }

  public function setPhoto($photo, $cat_id) {

    if ($photo instanceof Zend_Form_Element_File)
      $file = $photo->getFileName();
    else if (is_array($photo) && !empty($photo['tmp_name']))
      $file = $photo['tmp_name'];
    else if (is_string($photo) && file_exists($photo))
      $file = $photo;
    else
      return;

    if (empty($file))
      return;

    //Get photo details
    $name = basename($file);
    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $mainName = $path . '/' . $name;

    //Get viewer id
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $photo_params = array(
        'parent_id' => $cat_id,
        'parent_type' => "sesmusic_artist",
    );

    //Resize image work
    $image = Engine_Image::factory();
    $image->open($file);
    $image->open($file)
            ->resample(0, 0, $image->width, $image->height, $image->width, $image->height)
            ->write($mainName)
            ->destroy();
    try {
      $photoFile = Engine_Api::_()->storage()->create($mainName, $photo_params);
    } catch (Exception $e) {
      if ($e->getCode() == Storage_Api_Storage::SPACE_LIMIT_REACHED_CODE) {
        echo $e->getMessage();
        exit();
      }
    }
    //Delete temp file.
    @unlink($mainName);
    return $photoFile;
  }

  //Statistics Action
  public function statisticAction() {

    //GET NAVIGATION
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_statistic');

    $albumTable = Engine_Api::_()->getDbtable('albums', 'sesmusic');
    $albumTableName = $albumTable->info('name');

    //Total Albums
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalalbum');
    $this->view->totalalbum = $select->query()->fetchColumn();

    //Total featured album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalfeatured')->where('featured =?', 1);
    $this->view->totalfeatured = $select->query()->fetchColumn();

    //Total sponsored album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalsponsored')->where('sponsored =?', 1);
    $this->view->totalsponsored = $select->query()->fetchColumn();

    //Total hot album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalhot')->where('hot =?', 1);
    $this->view->totalhot = $select->query()->fetchColumn();

    //Total favourite album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalfavourite')->where('hot =?', 1);
    $this->view->totalfavourite = $select->query()->fetchColumn();

    //Total rated album
    $select = $albumTable->select()->from($albumTableName, 'count(*) AS totalrated')->where('rating <>?', 0);
    $this->view->totalrated = $select->query()->fetchColumn();

    //Album Songs
    $albumSongsTable = Engine_Api::_()->getDbtable('albumsongs', 'sesmusic');
    $albumsongsTableName = $albumSongsTable->info('name');

    //Total songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalalbumsongs');
    $this->view->totalalbumsongs = $select->query()->fetchColumn();

    //Total featured songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalfeaturedsongs')->where('featured =?', 1);
    $this->view->totalfeaturedsongs = $select->query()->fetchColumn();

    //Total sponsored songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalsponsoredsongs')->where('sponsored =?', 1);
    $this->view->totalsponsoredsongs = $select->query()->fetchColumn();

    //Total hot songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalhotsongs')->where('hot =?', 1);
    $this->view->totalhotsongs = $select->query()->fetchColumn();

    //Total favourite songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalfavouritesongs')->where('favourite_count <>?', 0);
    $this->view->totalfavouritesongs = $select->query()->fetchColumn();

    //Total rated songs
    $select = $albumSongsTable->select()->from($albumsongsTableName, 'count(*) AS totalratedsongs')->where('rating <>?', 0);
    $this->view->totalratedsongs = $select->query()->fetchColumn();

    //Playlists work
    $playlistsTable = Engine_Api::_()->getDbtable('playlists', 'sesmusic');
    $playlistsTableName = $playlistsTable->info('name');

    //Total playlists
    $select = $playlistsTable->select()->from($playlistsTableName, 'count(*) AS totalplaylists');
    $this->view->totalplaylists = $select->query()->fetchColumn();
  }

  public function showpopupAction() {

  }
	public function landingpageSet(){
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
    //Set Welcomw page as Landing Page and backup of Old Landing Page
    $orlanpage_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'core_index_index')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if ($orlanpage_id) {
      $db->query('UPDATE `engine4_core_content` SET `page_id` = "990001" WHERE `engine4_core_content`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `page_id` = "990001" WHERE `engine4_core_pages`.`page_id` = "' . $orlanpage_id . '";');
      $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index_1" WHERE `engine4_core_pages`.`name` = "core_index_index";');
    }
    //New Landing Page
    $pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'sesmusic_index_sesbackuplandingppage')
            ->limit(1)
            ->query()
            ->fetchColumn();
    if (!$pageId) {
      $widgetOrder = 1;
      //Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'sesmusic_index_sesbackuplandingppage',
          'displayname' => 'Landing Page',
          'title' => 'Landing Page',
          'description' => 'This is your site\'s landing page.',
          'custom' => 0,
      ));
      $newpagelastId = $pageId = $db->lastInsertId();
			// update content
				$coreContentTable = Engine_Api::_()->getDbTable('content', 'core');
				$coreContentTableName = $coreContentTable->info('name');
				$corePagesTable = Engine_Api::_()->getDbTable('pages', 'core');
				$corePagesTableName = $corePagesTable->info('name');
				$select = $corePagesTable->select()
					->setIntegrityCheck(false)
					->from($corePagesTable, null)
					->joinLeft($coreContentTableName, $corePagesTableName . '.page_id = ' . $coreContentTableName . '.page_id')
					->where($corePagesTableName . '.name = ?', 'sesmusic_index_welcome');
				$widgetsData = $select->query()->fetchAll();
				foreach($widgetsData as $widget){
					 $db->insert('engine4_core_content', array(
						'type' => $widget['type'],
						'name' => $widget['name'],
						'page_id' => $newpagelastId,
						'parent_content_id' => $widget['parent_content_id'],
						'order' => $widget['order'],
						'params' => $widget['params'],
					));
				}
			// update content

      $newbakpage_id = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'sesmusic_index_sesbackuplandingppage')
              ->limit(1)
              ->query()
              ->fetchColumn();
      if ($newbakpage_id) {
        $db->query('UPDATE `engine4_core_content` SET `page_id` = "3" WHERE `engine4_core_content`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `page_id` = "3" WHERE `engine4_core_pages`.`page_id` = "' . $newbakpage_id . '";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "core_index_index" WHERE `engine4_core_pages`.`name` = "sesmusic_index_sesbackuplandingppage";');
        $db->query('UPDATE `engine4_core_pages` SET `name` = "sesmusic_index_sesbackuplandingppage" WHERE `engine4_core_pages`.`name` = "core_index_index_1";');
        $db->query('UPDATE `engine4_core_pages` SET `displayname` = "SNS - Professional Music - Landing Page Backup" WHERE `engine4_core_pages`.`name` = "sesmusic_index_sesbackuplandingppage";');
      }
    }
	}
  public function manageWidgetizePageAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_admin_main', array(), 'sesmusic_admin_main_managepages');

    $this->view->pagesArray = array('sesmusic_index_home', 'sesmusic_index_browse', 'sesmusic_song_browse', 'sesmusic_playlist_browse', 'sesmusic_artist_browse', 'sesmusic_index_manage', 'sesmusic_album_favourite-albums', 'sesmusic_album_like-albums', 'sesmusic_album_rated-albums', 'sesmusic_song_like-songs', 'sesmusic_song_rated-songs', 'sesmusic_song_favourite-songs', 'sesmusic_playlist_manage', 'sesmusic_artist_favourite-artists', 'sesmusic_song_lyrics', 'sesmusic_index_create', 'sesmusic_album_edit', 'sesmusic_album_view', 'sesmusic_song_view', 'sesmusic_playlist_view', 'sesmusic_artist_view');
  }
}
