<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SongEdit.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Form_SongEdit extends Engine_Form {

  public function init() {

    $albumsong_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('albumsong_id');
    if(!$albumsong_id)
       $albumsong_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('song_id');
    if ($albumsong_id)
     $albumsong = $album_song = Engine_Api::_()->getItem('sesmusic_albumsong', $albumsong_id);
      
		$user = Engine_Api::_()->user()->getViewer();
    $this->setTitle('Edit Songs')
            ->setDescription('Here, you can edit the song information.');

    $this->addElement('Text', 'title', array(
        'label' => 'Song Name',
        'placeholder' => 'Enter Song Name',
        'maxlength' => '63',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '63')),
        )
    ));

    //Category Work
    $categories = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getCategory(array('column_name' => '*', 'param' => 'song'));
    $data[""] = 'Select Category';
    foreach ($categories as $category) {
      $data[$category['category_id']] = $category['category_name'];
    }
    if (engine_count($data) > 1) {
      //Add Element: Category
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $data,
          'onchange' => "ses_subcategory(this.value)",
      ));
    if($album_song->category_id){
      //Subcategory
      $subcat = array();
      $subcategory = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $album_song->category_id, 'param' => 'song'));
      $count_subcat = engine_count($subcategory->toarray());
    if($count_subcat){
      $subcat[""] = "Select 2nd-level Category";
      foreach ($subcategory as $subcategory) {
        $subcat[$subcategory['category_id']] = $subcategory['category_name'];
      }
    }else
      $subcat = array();
    }else
      $subcat = array();
      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => '2nd-level Category',
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => $subcat,
          'onchange' => "sessubsubcat_category(this.value)",
          'registerInArrayValidator' => false
      ));
      if (!empty($album_song->subcat_id)) {
        $this->subcat_id->setValue($album_song->subcat_id);
      }

     if($album_song->subcat_id){ //SubSubcategory
      $subsubcat = array();
      $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesmusic')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $album_song->subcat_id, 'param' => 'song'));
      $count_subcat = engine_count($subsubcategory->toarray());
      $subsubcat[] = "Select 3rd-level Category";
      foreach ($subsubcategory as $subsubcategory) {
        $subsubcat[$subsubcategory['category_id']] = $subsubcategory['category_name'];
      }
      
      }else
      $subsubcat = array();
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => '3rd-level Category',
          'allowEmpty' => true,
          'multiOptions' => $subsubcat,
          'required' => false,
          'registerInArrayValidator' => false
      ));
      if (!empty($group['subsubcat_id'])) {
        $this->subsubcat_id->setValue($album_song->subcat_id);
      }
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
    $this->addElement('Textarea', 'description', array(
        'label' => 'Song Description',
        'placeholder' => 'Enter Song Description',
        'maxlength' => '300',
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '300')),
            new Engine_Filter_EnableLinks(),
        ),
    ));
    
    $addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $user, 'addstore_link');
    if($addstore_link) {
      $this->addElement('Text', 'store_link', array(
          'label' => 'Link to Product (URL)',
          'placeholder' => 'Address where you buy your album',
      ));
    }

    $this->addElement('Textarea', 'lyrics', array(
        'label' => 'Song Lyrics',
        'placeholder' => 'Enter Song Lyrics',
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_EnableLinks(),
        ),
    ));


    $artistArray = array();
    $artistsTable = Engine_Api::_()->getDbTable('artists', 'sesmusic');
    $select = $artistsTable->select()->order('order ASC');
    $artists = $artistsTable->fetchAll($select);

    foreach ($artists as $artist) {
      $artistArray[$artist->artist_id] = $artist->name;
    }

    if (!empty($artistArray)) {
      $artistsValues = json_decode($albumsong->artists);
      $this->addElement('MultiCheckbox', 'artists', array(
          'label' => 'Song Artist',
          'description' => 'Choose from the below song artist.',
          'multiOptions' => $artistArray,
          'value' => $artistsValues,
      ));
    }

    $this->addElement('File', 'song_cover', array(
        'label' => 'Song Cover Photo',
        'onchange' => 'showReadImage(this,"song_cover_preview")',
    ));

    $this->song_cover->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
    if ($albumsong_id && $albumsong && $albumsong->song_cover) {
      $img_path = Engine_Api::_()->storage()->get($albumsong->song_cover, '');
      if($img_path) {
        $img_path = $img_path->getPhotoUrl();
        if (isset($img_path) && !empty($img_path)) {
          $this->addElement('Image', 'song_cover_preview', array(
              'label' => 'Song Cover Preview',
              'src' => $img_path,
              'width' => 100,
              'height' => 100,
          ));
        }
      }
    } else {
      $this->addElement('Image', 'song_cover_preview', array(
        'label' => 'Song Cover Preview',
        'src' => $path,
        'width' => 100,
        'height' => 100,
      ));
    }
    if ($albumsong->song_cover) {
      $this->addElement('Checkbox', 'remove_song_cover', array(
          'label' => 'Yes, remove song cover.'
      ));
    }

    //Init album art
    $this->addElement('File', 'file', array(
        'label' => 'Song Main Photo',
        'onchange' => 'showReadImage(this,"song_mainphoto_preview")',
    ));

    $this->file->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
    if ($albumsong_id && $albumsong && $albumsong->photo_id) {
      $img_path = Engine_Api::_()->storage()->get($albumsong->photo_id, '');
      if($img_path) {
        $img_path = $img_path->getPhotoUrl();
        if (isset($img_path) && !empty($img_path)) {
          $this->addElement('Image', 'song_mainphoto_preview', array(
              'label' => 'Song Main Photo Preview',
              'src' => $img_path,
              'width' => 100,
              'height' => 100,
          ));
        }
      }
    } else {
      $this->addElement('Image', 'song_mainphoto_preview', array(
        'label' => 'Song Main Photo Preview',
        'src' => $path,
        'width' => 100,
        'height' => 100,
      ));
    }
    if ($albumsong->photo_id) {
      $this->addElement('Checkbox', 'remove_photo', array(
          'label' => 'Yes, remove song photo.'
      ));
    }

    $viewer = Engine_Api::_()->user()->getViewer();
    $downloadAlbumSong = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'download_song');
    if ($downloadAlbumSong) {
      $this->addElement('Checkbox', 'download', array(
          'label' => 'Do you allow users to download this song?',
          'value' => 1,
      ));
    }

    //Element: execute
    $this->addElement('Button', 'execute', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
   
    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'view', 'albumsong_id' => $albumsong_id, 'slug' => $album_song->getSlug()), 'sesmusic_albumsong_view', true),
        'onclick' => '',
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    // DisplayGroup: buttons
    $this->addDisplayGroup(array(
        'execute',
        'cancel',
            ), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
  }

}
