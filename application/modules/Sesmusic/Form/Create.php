<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Form_Create extends Engine_Form {

  protected $_albums;
  protected $_defaultProfileId;
  protected $_roles = array(
      'everyone' => 'Everyone',
      'registered' => 'All Registered Members',
      'owner_network' => 'Friends and Networks',
      'owner_member_member' => 'Friends of Friends',
      'owner_member' => 'Friends Only',
      'owner' => 'Just Me'
  );
  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }

    public function setDefaultProfileId($default_profile_id) {
        $this->_defaultProfileId = $default_profile_id;
        return $this;
    }
  public function init() {

    $auth = Engine_Api::_()->authorization()->context;
    $user = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');

    $quota = Engine_Api::_()->authorization()->getPermission($user->level_id, 'sesmusic_album', 'addalbum_max');

    $albumsCount = Engine_Api::_()->getDbtable('albums', 'sesmusic')->getAlbumsCount(array('viewer_id' => $user->getIdentity(), 'column_name' => array('album_id', 'title')));


    $upload = Zend_Controller_Front::getInstance()->getRequest()->getParam('upload', null);
    if($upload == 'song') {
      $this->setTitle('Upload Song')
              ->setDescription('Upload song from your computer.')
              ->setAttrib('id', 'form-upload-music')
              ->setAttrib('name', 'album_create')
              ->setAttrib('enctype', 'multipart/form-data')
              ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    } else {
      $this->setTitle('Create New Music Album')
            ->setDescription('Create new music album and upload songs from your computer to add to this music album.')
            ->setAttrib('id', 'form-upload-music')
            ->setAttrib('name', 'album_create')
            ->setAttrib('enctype', 'multipart/form-data')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    }



    if ($quota > engine_count($albumsCount) || $quota == 0) {
      //Init name
      $this->addElement('Text', 'title', array(
          'label' => 'Music Album Name',
          'placeholder' => 'Enter Music Album Name',
          'maxlength' => '63',
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_StringLength(array('max' => '63')),
          )
      ));

      //Init descriptions
      $this->addElement('Textarea', 'description', array(
          'label' => 'Music Album Description',
          'placeholder' => 'Enter Music Album Description',
          'maxlength' => '1000',
          'filters' => array(
              'StripTags',
              new Engine_Filter_Censor(),
              new Engine_Filter_StringLength(array('max' => '1000')),
              new Engine_Filter_EnableLinks(),
          ),
      ));
      
			if (Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && $settings->getSetting('epaidcontent.allow',1)) {
				$packages = Engine_Api::_()->getDbTable('packages', 'epaidcontent')->getEnabledPackages($user->getIdentity());
				if(engine_count($packages)) {
					$packagesArray = array('' => 'Select Package');
					foreach($packages as $package) {
						$packagesArray[$package->getIdentity()] = $package->title . ' ('. Engine_Api::_()->epaidcontent()->getCurrencyPrice($package->price, Engine_Api::_()->epaidcontent()->defaultCurrency()) . ')';
					}
					$this->addElement('Select', 'package_id', array(
						'label' => 'Choose Package',
						'multiOptions' => $packagesArray,
					));
				}
			}

      $addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $user, 'addstore_link');
      if($addstore_link) {
	      $this->addElement('Text', 'store_link', array(
	          'label' => 'Link to Product (URL)',
	          'placeholder' => 'Address where you buy your album',
	      ));
      }

      //Category Work
      if($upload == 'song') {
		  $itemType = 'sesmusic_albumsong';
        $categories = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getCategory(array('column_name' => '*', 'param' => 'song'));
      } else {
		  $itemType = 'sesmusic_album';
        $categories = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getCategory(array('column_name' => '*', 'param' => 'album'));
      }
      $data[] = 'Select Category';
      foreach ($categories as $category) {
        $data[$category['category_id']] = $category['category_name'];
      }
      if (engine_count($data) > 1) {
        //Add Element: Category
        $this->addElement('Select', 'category_id', array(
            'label' => 'Category',
            'multiOptions' => $data,
            'onchange' => "showSubCategory(this.value)",
        ));

        //Add Element: Sub Category
        $this->addElement('Select', 'subcat_id', array(
            'label' => "2nd-level Category",
            'allowEmpty' => true,
            'required' => false,
            'registerInArrayValidator' => false,
            'onchange' => "showSubSubCategory(this.value)",
        ));

        //Add Element: Sub Sub Category
        $this->addElement('Select', 'subsubcat_id', array(
            'label' => "3rd-level Category",
            'allowEmpty' => true,
            'registerInArrayValidator' => false,
            'required' => false,
			'multiOptions' => array('0' => ''),
			'onchange' => 'showFields(this.value,1);'
        ));

			$defaultProfileId = "0_0_" . $this->getDefaultProfileId();
			$customFields = new Sesbasic_Form_Custom_Fields(array(
				'resourceType' => '',
				'item' => $itemType,
				'decorators' => array(
					'FormElements'
			)));
			$customFields->removeElement('submit');
			if ($customFields->getElement($defaultProfileId)) {
			  $customFields->getElement($defaultProfileId)
					  ->clearValidators()
					  ->setRequired(false)
					  ->setAllowEmpty(true);
			}
			$this->addSubForms(array(
				'fields' => $customFields
			));
      }
			if($upload == 'song' && $settings->getSetting('sesandroidapp.showyoutube.video.musicapp')){
				 $this->addElement('text', 'youtube_video', array(
            'label' => "Youtube Video Link (URL)",
						'description' => 'Paste the web address of the video here.',
						'maxlength' => '150',
            'allowEmpty' => true,
            'required' => false,
						'onchange' => 'checklink(this.value);'
        ));
				$this->addElement('dummy', 'checking', array(
						'description' => 'Checking Url....',
						'maxlength' => '150',
            'allowEmpty' => true,
            'required' => false,
						'onchange' => 'checklink(this.value);'
        ));
				$this->addElement('hidden', 'is_video_found', array(
				'value' => '1'
        ));
				$this->getElement('checking')->getDecorator('description')->setOption('style', 'display: none');
			}
      //End category work
      //AUTHORIZATIONS
      $availableLabels = $this->_roles;

      //Element: auth_view
      $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesmusic_album', $user, 'auth_view');
      $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));

      if (!empty($viewOptions) && engine_count($viewOptions) >= 1) {
        //Make a hidden field
        if (engine_count($viewOptions) == 1) {
          $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions), 'order' => 89898989));
          //Make select box
        } else {
          $this->addElement('Select', 'auth_view', array(
              'label' => 'View Privacy',
              'description' => 'Who may see this music album?',
              'multiOptions' => $viewOptions,
              'value' => key($viewOptions),
          ));
          $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
        }
      }

      //Element: auth_comment
      $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesmusic_album', $user, 'auth_comment');
      $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

      if (!empty($commentOptions) && engine_count($commentOptions) >= 1) {
        //Make a hidden field
        if (engine_count($commentOptions) == 1) {
          $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions), 'order' => 7878787));
          //Make select box
        } else {
          $this->addElement('Select', 'auth_comment', array(
              'label' => 'Comment Privacy',
              'description' => 'Who may post comments on this music album?',
              'multiOptions' => $commentOptions,
              'value' => key($commentOptions),
          ));
          $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
        }
      }


      $myComputer = false;
      $uploadoption = $settings->getSetting('sesmusic.uploadoption', 'myComputer');
      if($uploadoption == 'both') {
        $myComputer = true;
      } else if($uploadoption == 'myComputer') {
        $myComputer = true;
      }

      if($myComputer && empty($_GET['restApi'])) {
        //Init file uploader
//         $fancyUpload = new Engine_Form_Element_FancyUpload('file');
//         $fancyUpload->clearDecorators()
//                 ->addDecorator('FormFancyUpload')
//                 ->addDecorator('viewScript', array(
//                     'viewScript' => '_FancyUpload.tpl',
//                     'placement' => '',
//         ));
//         Engine_Form::addDefaultDecorators($fancyUpload);
//         $this->addElement($fancyUpload);

        $urlOptions = ['controller' => 'song', 'action' => 'upload']; //empty($this->getPlaylistId()) ? ['controller' => 'song', 'action' => 'upload'] : ['controller' => 'playlist', 'action' => 'add-song', 'playlist_id' => $this->getPlaylistId()];
        if($upload == 'song') {
          $multi = false;
        } else {
          $multi = true;
        }
        $this->addElement('HTMLUpload', 'file', [
          'title' => 'Upload Music',
          'multi' => $multi,
          'url' => $this->getView()->url($urlOptions, 'sesmusic_extended'),
          'accept' => 'audio/*',
        ]);

        $i = -5000;
        //Init hidden file IDs
//         $this->addElement('Hidden', 'fancyuploadfileids', array(
//             'order' => $i--,
//         ));
      }else if(!empty($_GET['restApi'])){
        $this->addElement('File', 'file', [
          'label' => 'Upload Music',
          'accept' => 'audio/*',
        ]);
         $this->addElement('Hidden', 'fancyuploadfileids', array(
            'order' => 99988,
         ));
      }

      $uploadoption = $settings->getSetting('sesmusic.uploadoption', 'myComputer');
      if ($uploadoption == 'both' || $uploadoption == 'soundCloud') {
        //Init hidden file IDs
        $this->addElement('Hidden', 'soundcloudIds', array(
            'order' => $i--,
        ));

        $this->addElement('textarea', 'options', array(
            'label' => 'Upload from SoundCloud',
            'placeholder' => 'Enter the URL of song on SoundCloud',
            'description' => 'Enter the URL of song on SoundCloud.',
            'style' => 'display:none;',
        ));
      }

      //Init search checkbox
      $this->addElement('Checkbox', 'search', array(
          'label' => "Show this music album in search results",
          'value' => 1,
          'checked' => true,
      ));

      if($upload == 'song') {

        $this->addElement('File', 'album_cover', array(
            'label' => 'Song Cover Photo',
            'onchange' => 'showReadImage(this,"musicalbum_cover_preview")',
            'accept'=>"image/*",
        ));
        $this->album_cover->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

        $album_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('album_id');
        $album = Engine_Api::_()->getItem('sesmusic_album', $album_id);
        if ($album_id && $album && $album->album_cover && Engine_Api::_()->storage()->get($album->album_cover, '')) {
          $img_path = Engine_Api::_()->storage()->get($album->album_cover, '')->getPhotoUrl();
          $path = $img_path;
          if (isset($path) && !empty($path)) {
            $this->addElement('Image', 'musicalbum_cover_preview', array(
                'label' => 'Song Cover Photo Preview',
                'src' => $path,
                'width' => 100,
                'height' => 100,
            ));
          }
        } else {
          $this->addElement('Image', 'musicalbum_cover_preview', array(
              'label' => 'Song Cover Photo Preview',
              'width' => 100,
              'height' => 100,
              'disable' => true
          ));
        }

        if ($album_id) {
          if ($album->album_cover) {
            $this->addElement('Checkbox', 'remove_album_cover', array(
                'label' => 'Remove song cover photo.'
            ));
          }
        }

        //Init album art
        $this->addElement('File', 'art', array(
            'label' => 'Song Main Photo',
            'onchange' => 'showReadImage(this,"musicalbum_main_preview")',
            'accept'=>"image/*",
        ));
        $this->art->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

        if ($album_id && $album && $album->photo_id && Engine_Api::_()->storage()->get($album->photo_id, '')) {
          $img_path = Engine_Api::_()->storage()->get($album->photo_id, '')->getPhotoUrl();
          $path = $img_path;
          if (isset($path) && !empty($path)) {
            $this->addElement('Image', 'musicalbum_main_preview', array(
                'label' => 'Song Main Photo Preview',
                'src' => $path,
                'width' => 100,
                'height' => 100,
            ));
          }
        } else {
          $this->addElement('Image', 'musicalbum_main_preview', array(
              'label' => 'Song Main Photo Preview',
              'width' => 100,
              'height' => 100,
              'disable' => true
          ));
        }
        if ($album_id) {
          $album = Engine_Api::_()->getItem('sesmusic_album', $album_id);

          if ($album->photo_id) {
            $this->addElement('Checkbox', 'remove_album_main', array(
                'label' => 'Remove song main photo.'
            ));
          }
        }
        //Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Upload Song',
            'type' => 'submit',
        ));
      } else {
        if($settings->getSetting('sesmusic.show.albumcover', 1)) {
          $this->addElement('File', 'album_cover', array(
              'label' => 'Music Album Cover Photo',
              'onchange' => 'showReadImage(this,"musicalbum_cover_preview")',
              'accept'=>"image/*",
          ));
          $this->album_cover->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

          $album_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('album_id');
          $album = Engine_Api::_()->getItem('sesmusic_album', $album_id);
          if ($album_id && $album && $album->album_cover && Engine_Api::_()->storage()->get($album->album_cover, '')) {
            $img_path = Engine_Api::_()->storage()->get($album->album_cover, '')->getPhotoUrl();
            $path = $img_path;
            if (isset($path) && !empty($path)) {
              $this->addElement('Image', 'musicalbum_cover_preview', array(
                  'label' => 'Music Album Cover Photo Preview',
                  'src' => $path,
                  'width' => 100,
                  'height' => 100,
              ));
            }
          } else {
            $this->addElement('Image', 'musicalbum_cover_preview', array(
                'label' => 'Music Album Cover Photo Preview',
                'width' => 100,
                'height' => 100,
                'disable' => true
            ));
          }

          if ($album_id) {
            if ($album->album_cover) {
              $this->addElement('Checkbox', 'remove_album_cover', array(
                  'label' => 'Remove music album cover photo.'
              ));
            }
          }
        }

        //Init album art
        $this->addElement('File', 'art', array(
            'label' => 'Music Album Main Photo',
            'onchange' => 'showReadImage(this,"musicalbum_main_preview")',
            'accept'=>"image/*",
        ));
        $this->art->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

        if ($album_id && $album && $album->photo_id && Engine_Api::_()->storage()->get($album->photo_id, '')) {
          $img_path = Engine_Api::_()->storage()->get($album->photo_id, '')->getPhotoUrl();
          $path = $img_path;
          if (isset($path) && !empty($path)) {
            $this->addElement('Image', 'musicalbum_main_preview', array(
                'label' => 'Music Album Main Photo Preview',
                'src' => $path,
                'width' => 100,
                'height' => 100,
            ));
          }
        } else {
          $this->addElement('Image', 'musicalbum_main_preview', array(
              'label' => 'Music Album Main Photo Preview',
              'width' => 100,
              'height' => 100,
              'disable' => true
          ));
        }
        if ($album_id) {
          $album = Engine_Api::_()->getItem('sesmusic_album', $album_id);

          if ($album->photo_id) {
            $this->addElement('Checkbox', 'remove_album_main', array(
                'label' => 'Remove music album main photo.'
            ));
          }
        }
        //Init submit
        $this->addElement('Button', 'submit', array(
            'label' => 'Create Music Album',
            'type' => 'submit',
        ));
      }



    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_("You have already created the maximum number of music albums allowed. If you would like to create a music album, please delete an old one first.") . "</span></div>";
      $this->addElement('Dummy', 'dummy', array(
          'description' => $description,
      ));
      $this->dummy->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }
  }

  public function clearUploads() {
    $this->getElement('fancyuploadfileids')->setValue('');
  }

	public function handleIframelyInformation($uri) {
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

  public function saveValues() {

    $albums = null;
		$values = $this->getValues();
    $newcheck = Zend_Controller_Front::getInstance()->getRequest()->getParam('new', 0);
     //$this should refer to a controller

    if (!empty($values['album_id'])) {
      $albums = Engine_Api::_()->getItem('sesmusic_albums', $values['album_id']);
      if (!empty($values['soundcloudIds'])) {
        foreach (explode(' ', $values['soundcloudIds']) as $file_id) {
          $albums->addSong($file_id, array('track_id' => 1));
        }
      }
      //Album Cover
      if (!empty($values['album_cover'])) {
        $previousPhoto = $albums->album_cover;
        if ($previousPhoto) {
          $albumCoverPhoto = Engine_Api::_()->getItem('storage_file', $previousPhoto);
          $albumCoverPhoto->delete();
        }
        $albums->setAlbumCover($this->album_cover, 'songCover');
      }

      if (isset($values['remove_album_cover']) && !empty($values['remove_album_cover'])) {
        $storage = Engine_Api::_()->getItem('storage_file', $albums->album_cover);
        $albums->album_cover = 0;
        $albums->save();
        if ($storage)
          $storage->delete();
      }

      if (isset($values['remove_album_main']) && !empty($values['remove_album_main'])) {
        $storage = Engine_Api::_()->getItem('storage_file', $albums->photo_id);
        $albums->photo_id = 0;
        $albums->save();
        if ($storage)
          $storage->delete();
      }

      if (!empty($values['art']))
        $albums->setPhoto($this->art);

    } else {

      $uploadCheck = Zend_Controller_Front::getInstance()->getRequest()->getParam('upload', 'album');


      $resource_type = Zend_Controller_Front::getInstance()->getRequest()->getParam('resource_type');

      $resource_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('resource_id');

      $albums = $this->_albums = Engine_Api::_()->getDbtable('albums', 'sesmusic')->createRow();
      $albums->title = htmlspecialchars(trim($values['title']), ENT_QUOTES, 'UTF-8');

      if (empty($albums->title))
        $albums->title = Zend_Registry::get('Zend_Translate')->_('Untitled Music Album');
      if($newcheck)
        $albums->new = 1;
      $albums->owner_type = 'user';
      $albums->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
      $albums->description = trim($values['description']);
      $albums->search = $values['search']?$values['search']:1;
			if(isset($values['store_link']))
      $albums->store_link = $values['store_link'];
      
			if(isset($values['package_id']) && !empty($values['package_id']))
				$albums->package_id = $values['package_id'];

      if($uploadCheck == 'song') {
        $albums->upload_param = 'song';
      }

			// @m comment this check because of category work on upload Songs
      //if($uploadCheck != 'song') {
        if (isset($values['category_id']))
          $albums->category_id = $values['category_id'];
        if (isset($values['subcat_id']))
          $albums->subcat_id = $values['subcat_id'];
        if (isset($values['subsubcat_id']))
          $albums->subsubcat_id = $values['subsubcat_id'];
      //}
      if (isset($values['label']))
        $albums->label = $values['label'];
      if ($resource_type)
        $albums->resource_type = $resource_type;
      if ($resource_id)
        $albums->resource_id = $resource_id;
      $albums->save();
      $values['album_id'] = $albums->album_id;
      //Get visitor IP Address
      $ip = $_SERVER['REMOTE_ADDR'];
      if ($ip)
        $albums->ip_address = $ip;

      $albums->save();

      //Assign $albums to a Core_Model_Item
      $albums = $this->_albums = Engine_Api::_()->getItem('sesmusic_albums', $values['album_id']);

      //Get file_id list
      $file_ids = array();
			if(isset($values['fancyuploadfileids'])){
				foreach (explode(' ', $values['fancyuploadfileids']) as $file_id) {
					$file_id = trim($file_id);
					if (!empty($file_id))
						$file_ids[] = $file_id;
				}
			}
      if (empty($file_ids)) {
        $file_ids = $values['file'];
      }
      //Album Cover
      if (!empty($values['album_cover'])) {
        $previousPhoto = $albums->album_cover;
        if ($previousPhoto) {
          $albumCoverPhoto = Engine_Api::_()->getItem('storage_file', $previousPhoto);
          $albumCoverPhoto->delete();
        }
        $albums->setAlbumCover($this->album_cover, 'songCover');
      }

      if (isset($values['remove_album_cover']) && !empty($values['remove_album_cover'])) {
        $storage = Engine_Api::_()->getItem('storage_file', $albums->album_cover);
        $albums->album_cover = 0;
        $albums->save();
        if ($storage)
          $storage->delete();
      }

      if (isset($values['remove_album_main']) && !empty($values['remove_album_main'])) {
        $storage = Engine_Api::_()->getItem('storage_file', $albums->photo_id);
        $albums->photo_id = 0;
        $albums->save();
        if ($storage)
          $storage->delete();
      }

      if (!empty($values['art']))
        $albums->setPhoto($this->art);

      //Attach songs (file_ids) to playlist

      if (!empty($file_ids)) {
        foreach ($file_ids as $file_id) {
          $albums->addSong($file_id, array('uploadCheck' => $uploadCheck));
        }
      }

      //Only create activity feed item if "search" is checked
      if ($albums->search && $uploadCheck == 'album') {
        if($resource_id && $resource_type) {
          $resourceItem = Engine_Api::_()->getItem($resource_type, $resource_id);
	        $activity = Engine_Api::_()->getDbtable('actions', 'activity');
	        $action = $activity->addActivity(Engine_Api::_()->user()->getViewer(), $resourceItem, 'sesevent_event_eventmusic', null, array('count' => engine_count($file_ids)));
	        if (null !== $action)
	          $activity->attachActivity($action, $albums);
        } else {
	        $activity = Engine_Api::_()->getDbtable('actions', 'activity');
	        $action = $activity->addActivity(Engine_Api::_()->user()->getViewer(), $albums, 'sesmusic_album_new', null, array('count' => engine_count($file_ids)));
	        if (null !== $action)
	          $activity->attachActivity($action, $albums);
        }
      }
    }

    //Authorizations
    $auth = Engine_Api::_()->authorization()->context;
    $prev_allow_comment = $prev_allow_view = false;
    foreach ($this->_roles as $role => $role_label) {
      //Allow viewers
      if ($values['auth_view'] == $role || $prev_allow_view) {
        $auth->setAllowed($albums, $role, 'view', true);
        $prev_allow_view = true;
      } else
        $auth->setAllowed($albums, $role, 'view', 0);

      //Allow comments
      if ($values['auth_comment'] == $role || $prev_allow_comment) {
        $auth->setAllowed($albums, $role, 'comment', true);
        $prev_allow_comment = true;
      } else
        $auth->setAllowed($albums, $role, 'comment', 0);
    }

    //Songs comment privacy
    $songs = Engine_Api::_()->getDbTable('albumsongs', 'sesmusic')->getAllSongs($albums->album_id);
    foreach ($songs as $song) {

      if (isset($values['category_id']))
        $song->category_id = $values['category_id'];
      if (isset($values['subcat_id']))
        $song->subcat_id = $values['subcat_id'];
      if (isset($values['subsubcat_id']))
        $song->subsubcat_id = $values['subsubcat_id'];
			if(isset($values['youtube_video']) && !empty($values['youtube_video']) && $values['is_video_found']){
				$information = $this->handleIframelyInformation($values['youtube_video']);
				if (!empty($information)) {
          $song->youtube_video = $information['code'];
        }
			}
			
			//Paid Content Work
			if (isset($values['package_id']) && !empty($albums->package_id))
        $song->package_id = $albums->package_id;
      $song->save();

      //Authorizations
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      $prev_allow_comment = false;
      foreach ($this->_roles as $role => $role_label) {
        //Allow comments
        if ($values['auth_comment'] == $role || $prev_allow_comment) {
          $auth->setAllowed($song, $role, 'comment', true);
          $prev_allow_comment = true;
        } else
          $auth->setAllowed($song, $role, 'comment', 0);
      }
    }

    //Rebuild privacy
    $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
    foreach ($actionTable->getActionsByObject($albums) as $action) {
      $actionTable->resetActivityBindings($action);
    }
    return $albums;
  }

}
