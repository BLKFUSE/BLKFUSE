<?php

class Sesvideo_Form_ChanelApi extends Engine_Form {
  public function init() {
		$is_chanel = false;
    $setting = Engine_Api::_()->getApi('settings', 'core');
    $chanel_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('chanel_id');
		$titleText = 'Add New Video Channel';
    if ($chanel_id) {
      $chanel = Engine_Api::_()->getItem('sesvideo_chanel', $chanel_id);
      $is_chanel = true;
			$titleText = 'Edit Video Channel';
    }
		
    // Init form
    $this
            ->setTitle($titleText)
            ->setAttrib('id', 'form-upload')
            ->setAttrib('name', 'video_chanel_create')
            ->setAttrib('enctype', 'multipart/form-data')
            //->setAttrib('onsubmit', 'return checkValidation();')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;
		$viewChanel = $deleteChanel = '';
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $user = Engine_Api::_()->user()->getViewer();	
    // Init name
    $this->addElement('Text', 'title', array(
        'label' => 'Channel Title',
        'maxlength' => '100',
				'autocomplete' => 'off',
        'allowEmpty' => false,
        'required' => true,
        'filters' => array(
            //new Engine_Filter_HtmlSpecialChars(),
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '100')),
        )
    ));
    $this->addElement('Text', 'custom_url', array(
        'label' => 'Shortcut URL',
        'autocomplete' => 'off',
        'description' => '',
    ));
    // init tag
    $this->addElement('Text', 'tags', array(
        'label' => 'Tags (Keywords)',
        'autocomplete' => 'off',
        'description' => 'Separate tags with commas.',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        )
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");
    // Init descriptions
    $this->addElement('Textarea', 'description', array(
        'label' => 'Channel Description',
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
            //new Engine_Filter_HtmlSpecialChars(),
            new Engine_Filter_EnableLinks(),
        ),
    ));
    // prepare categories
    $categories = Engine_Api::_()->sesvideo()->getCategories();
    if (engine_count($categories) != 0) {
			$setting = Engine_Api::_()->getApi('settings', 'core');
				$categorieEnable = $setting->getSetting('videochanel.category.enable','1');
				if($categorieEnable == 1){
					$required = true;	
					$allowEmpty = false;
				}else{
					$required = false;	
					$allowEmpty = true;	
				}		
      foreach ($categories as $category) {
        $categories_prepared[$category->category_id] = $category->category_name;
      }
      // category field
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories_prepared,
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'onchange' => "showSubCategory(this.value);",
      ));
      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => array(),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);"
      ));
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => array(),
      ));
    }
    
		
    // Init search
    $this->addElement('Checkbox', 'search', array(
        'label' => "Show this video channel in search results",
        'value' => 1,
    ));
		
		$allowAdultContent = Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.allow.adult.filtering');
		if($allowAdultContent){
			$adult = 'adult';
			 // Init search
			$this->addElement('Checkbox', 'adult', array(
					'label' => "Mark Channel as Adult",
					'value' => 0,
			));	
		}else
			$adult = '';
		
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.subscription',1)){
			$this->addElement('Checkbox', 'follow', array(
					'label' => "Someone follows this Channel",
					'value' => 1,
			));
		}
    // View
    $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'All Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member' => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'owner' => 'Just Me'
    );
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesvideo_chanel', $user, 'auth_view');
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
    if (!empty($viewOptions) && engine_count($viewOptions) >= 1) {
      // Make a hidden field
      if (engine_count($viewOptions) == 1) {
        $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions)));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'Privacy',
            'description' => 'Who may see this video channel?',
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions),
        ));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    }
    // Element: auth_comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesvideo_chanel', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));		
    if (!empty($commentOptions) && engine_count($commentOptions) >= 1) {
      // Make a hidden field
      if (engine_count($commentOptions) == 1) {
        $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions)));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy',
            'description' => 'Who may post comments on this chanel?',
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    }
		/*
    $this->addElement('File', 'chanel_cover', array(
        'label' => 'Channel Cover',
        'onchange' => 'readImageUrl(this,"cover_photo_preview")',
				'description'=>'recommended size is 1000*300'
    ));
		$this->chanel_cover->getDecorator("Description")->setOption("placement", "append");
    $this->chanel_cover->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
		$chanelCoverPreview = '';
    if ($chanel_id) {
      if (!$is_chanel)
        $chanel = Engine_Api::_()->getItem('sesvideo_chanel', $chanel_id);
      if ($chanel->cover_id) {
        $this->addElement('Checkbox', 'remove_chanel_cover', array(
            'label' => 'Yes, remove channel cover.'
        ));
				$chanelCoverPreview = 'remove_chanel_cover';
      }
    }
    if (isset($chanel) && $chanel->cover_id) {
       $img_path = Engine_Api::_()->storage()->get($chanel->cover_id, '')->getPhotoUrl();
		if(strpos($img_path,'http') === FALSE)
      $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
		 else
		 	$path = $img_path;
      if (isset($path) && !empty($path)) {
        $this->addElement('Image', 'cover_photo_preview', array(
            'src' => $path,
            'class' => 'sesvideo_channel_thumb_preview sesbd',
        ));
      }
    } else {
      $this->addElement('Image', 'cover_photo_preview', array(
          'src' => '',
          'class' => 'sesvideo_channel_thumb_preview',
      ));
    }*/

    $this->addElement('File', 'chanel_thumbnail', array(
        'label' => 'Channel Thumbnail',
				'description'=>'recommended size is 400*400',
        'onchange' => 'readImageUrl(this,"thumbnail_photo_preview")',
    ));
		$this->chanel_thumbnail->getDecorator("Description")->setOption("placement", "append");
    $this->chanel_thumbnail->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

    if (isset($chanel) && $chanel->thumbnail_id) {
      $img_path = Engine_Api::_()->storage()->get($chanel->thumbnail_id, '')->getPhotoUrl();
		if(strpos($img_path,'http') === FALSE)
      $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
		 else
		 	$path = $img_path;
      if (isset($path) && !empty($path)) {
        $this->addElement('Image', 'thumbnail_photo_preview', array(
            'src' => $path,
            'class' => 'sesvideo_channel_thumb_preview sesbd',
        ));
      }
    } else {
      $this->addElement('Image', 'thumbnail_photo_preview', array(
          'src' => '',
          'class' => 'sesvideo_channel_thumb_preview sesbd',
      ));
    }
		// Init submit
    $this->addElement('Button', 'submit', array(
        'label' => (!empty($chanel_id)) ? "Save Changes" : 'Add Channel',
				'class' => 'next_elm',
        'type' => 'button',
    ));   
  }

  public function saveValues() {
    $set_cover = False;
    $values = $this->getValues();
    $params = Array();
    if ((empty($values['owner_type'])) || (empty($values['owner_id']))) {
      $params['owner_id'] = Engine_Api::_()->user()->getViewer()->user_id;
      $params['owner_type'] = 'user';
    } else {
      $params['owner_id'] = $values['owner_id'];
      $params['owner_type'] = $values['owner_type'];
      throw new Zend_Exception("Non-user album owners not yet implemented");
    }

    if (($values['album'] == 0)) {
      $params['name'] = $values['name'];
      if (empty($params['name'])) {
        $params['name'] = "Untitled Album";
      }
      $params['description'] = $values['description'];
      $params['search'] = $values['search'];
      $album = Engine_Api::_()->getDbtable('albums', 'album')->createRow();
      $set_cover = True;
      $album->setFromArray($params);
      $album->save();
    } else {
      if (is_null($album)) {
        $album = Engine_Api::_()->getItem('album', $values['album']);
      }
    }

    // Add action and attachments
    $api = Engine_Api::_()->getDbtable('actions', 'activity');
    $action = $api->addActivity(Engine_Api::_()->user()->getViewer(), $album, 'album_photo_new', null, array('count' => engine_count($values['file'])));

    // Do other stuff
    $count = 0;
    foreach ($values['file'] as $photo_id) {
      $photo = Engine_Api::_()->getItem("album_photo", $photo_id);
      if (!($photo instanceof Core_Model_Item_Abstract) || !$photo->getIdentity())
        continue;

      if ($set_cover) {
        $album->photo_id = $photo_id;
        $album->save();
        $set_cover = false;
      }

      $photo->collection_id = $album->album_id;
      $photo->save();

      if ($action instanceof Activity_Model_Action && $count < 8) {
        $api->attachActivity($action, $photo, Activity_Model_Action::ATTACH_MULTI);
      }
      $count++;
    }

    return $album;
  }

}
