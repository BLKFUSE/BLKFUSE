<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Form_Album_Edit extends Engine_Form {
	protected $_defaultProfileId;
  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }
  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }
  public function init() {
    $user = Engine_Api::_()->user()->getViewer();
    $user_level = Engine_Api::_()->user()->getViewer()->level_id;
    $this->setTitle('Edit Album Settings')
            ->setAttrib('name', 'albums_edit');
    $this->addElement('Text', 'title', array(
        'label' => 'Album Title',
        'required' => true,
        'notEmpty' => true,
        'validators' => array(
            'NotEmpty',
        ),
        'filters' => array(
            new Engine_Filter_Censor(),
            'StripTags',
            new Engine_Filter_StringLength(array('max' => '63'))
        )
    ));
    $this->title->getValidator('NotEmpty')->setMessage("Please specify an album title");
		// init to
    $this->addElement('Text', 'tags',array(
      'label'=>'Tags (Keywords)',
      'autocomplete' => 'off',
      'description' => 'Separate tags with commas.',
      'filters' => array(
        new Engine_Filter_Censor(),
      ),
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");
  if((Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))) {
      $this->addElement('Text', 'lat', array(
        'label' => 'Lat',
        'id' =>'latSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
		$this->addElement('Text', 'location', array(
        'label' => 'Location',
				'id' =>'locationSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
		$this->addElement('dummy', 'map-canvas', array());
		$this->addElement('dummy', 'ses_location', array('content'));		
		$this->addElement('Text', 'lng', array(
        'label' => 'Lng',
				'id' =>'lngSesList',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
	}
else if(empty(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1))){
  $this->addElement('Text', 'location', array(
        'label' => 'Location',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
    ));
  $optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion',''));
  if(!empty($optionsenableglotion)) {
  if(engine_in_array('country', $optionsenableglotion)) {
   $this->addElement('Text', 'country', array(
      'label' => 'Country',
      'maxlength' => '255',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
 }
 if(engine_in_array('state', $optionsenableglotion)) {
   $this->addElement('Text', 'state', array(
      'label' => 'State',
      'maxlength' => '255',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
 }
 if(engine_in_array('city', $optionsenableglotion)) {
   $this->addElement('Text', 'city', array(
      'label' => 'City',
      'maxlength' => '255',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
      )
    ));
 }
 if(engine_in_array('zip', $optionsenableglotion)) {
   $this->addElement('Text', 'zip', array(
      'label' => 'Zip',
      'maxlength' => '6',
      'minlength' => '5',
      'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
    ));
 }
 if(engine_in_array('lat', $optionsenableglotion)) {
   $this->addElement('Text', 'latValue', array(
      'label' => 'Latitude',
      'maxlength' => '20',
      'validators' => array(
              array('Float', true),
              array('GreaterThan', true, array(0)),
          )
    ));
 }
 if(engine_in_array('lng', $optionsenableglotion)) {
   $this->addElement('Text', 'lngValue', array(
      'label' => 'Longitude',
       'maxlength' => '20',
      'validators' => array(
              array('Float', true),
              array('GreaterThan', true, array(0)),
          )
    ));
 }
}
}
		/*
		$album_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('album_id', 0);
    if ($album_id)
      $album = Engine_Api::_()->getItem('album', $album_id);
		$this->addElement('File', 'art_cover_file', array(
        'label' => 'Art Cover',
        'description' => 'Upload an art cover.'
    ));
    $this->art_cover_file->addValidator('Extension', false, 'jpg,jpeg,png,PNG,JPG,JPEG');

    if (isset($album) && $album->art_cover) {
      $img_path = Engine_Api::_()->storage()->get($album->art_cover, '')->getPhotoUrl();
      $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
      if (isset($path) && !empty($path)) {
        $this->addElement('Image', 'art_cover_preview', array(
            'src' => $path,
            'height' => 200,
        ));
      }
      $this->addElement('Checkbox', 'remove_art_cover', array(
          'label' => 'Yes, delete this art cover.'
      ));
    }*/
    // prepare categories
    $categories = Engine_Api::_()->getDbtable('categories', 'sesalbum')->getCategoriesAssoc();
    if (engine_count($categories) > 0) {
			$categories = array(''=>'')+$categories;
      $setting = Engine_Api::_()->getApi('settings', 'core');
      $categorieEnable = $setting->getSetting('sesalbum.category.enable','1');
      if($categorieEnable == 1){
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'multiOptions' => $categories,
					'onchange' => "showSubCategory(this.value);showFields(this.value,1,this.class,this.class,'resets');",
      ));
				//Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
					'multiOptions' =>  array(),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);showFields(this.value,1,this.class,this.class,'resets');"
      ));			
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
            'multiOptions' =>  array(),
            'onchange' => "showFields(this.value,1,this.class,this.class,'resets');",
      ));
		$album = Engine_Api::_()->core()->getSubject();
		// General form w/o profile type
    $aliasedFields = $album->fields()->getFieldsObjectsByAlias();
    $topLevelId = 0;
    $topLevelValue = null;
    if( isset($aliasedFields['profile_type']) ) {
      $aliasedFieldValue = $aliasedFields['profile_type']->getValue($album);
      $topLevelId = $aliasedFields['profile_type']->field_id;
      $topLevelValue = ( is_object($aliasedFieldValue) ? $aliasedFieldValue->value : null );
      if( !$topLevelId || !$topLevelValue ) {
        $topLevelId = null;
        $topLevelValue = null;
      }
      //$this->view->topLevelId = $topLevelId;
      //$this->view->topLevelValue = $topLevelValue;
    }
    // Get category map form data
		$defaultProfileId = "0_0_" . $this->getDefaultProfileId();
    $customFields = new Sesbasic_Form_Custom_Fields(array(
         'item' => $album,
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
    $this->addElement('Textarea', 'description', array(
        'label' => 'Album Description',
        'rows' => 2,
        'filters' => array(
            new Engine_Filter_Censor(),
            'StripTags',
            new Engine_Filter_EnableLinks(),
        )
    ));
    $this->addElement('Checkbox', 'search', array(
        'label' => "Show this album in search results",
    ));
    // View
    $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'All Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member' => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'owner' => 'Just Me'
    );
    // Element: auth_view
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('album', $user, 'auth_view');
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
    if (!empty($viewOptions) && engine_count($viewOptions) >= 1) {
      // Make a hidden field
      if (engine_count($viewOptions) == 1) {
        $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions),'order'=>888));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'Privacy',
            'description' => 'Who may see this album?',
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions),
        ));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    }
    // Element: auth_comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('album', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));
    if (!empty($commentOptions) && engine_count($commentOptions) >= 1) {
      // Make a hidden field
      if (engine_count($commentOptions) == 1) {
        $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions),'order'=>998));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy',
            'description' => 'Who may post comments on this album?',
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    }
    // Element: auth_tag
    $tagOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('album', $user, 'auth_tag');
    $tagOptions = array_intersect_key($availableLabels, array_flip($tagOptions));
    if (!empty($tagOptions) && engine_count($tagOptions) >= 1) {
      // Make a hidden field
      if (engine_count($tagOptions) == 1) {
        $this->addElement('hidden', 'auth_tag', array('value' => key($tagOptions),'order'=>999));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_tag', array(
            'label' => 'Tagging',
            'description' => 'Who may tag photos in this album?',
            'multiOptions' => $tagOptions,
            'value' => key($tagOptions),
        ));
        $this->auth_tag->getDecorator('Description')->setOption('placement', 'append');
      }
    }
		$allowAdultContent = Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.allow.adult.filtering');
		if($allowAdultContent){
			 // Init search
			$this->addElement('Checkbox', 'adult', array(
					'label' => "Mark Album as Adult",
					'value' => 0,
			));	
		}
		$viewer = Engine_Api::_()->user()->getViewer();
		$sesprofilelock_enable_module = is_string(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) ? unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) : Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}');
    //check dependent module sesprofile install or not
    if (Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable('sesprofilelock') && Engine_Api::_()->authorization()->getPermission($viewer, 'album', 'sesalbum_locked')  && engine_in_array('sesalbum',$sesprofilelock_enable_module)) {
      // Video enable password
      $this->addElement('Select', 'is_locked', array(
          'label' => 'Enable Album Lock',
          'multiOptions' => array(
              0 => 'No',
              1 => 'Yes',
          ),
          'onchange' => 'enablePasswordFiled(this.value);',
          'value' => 0
      ));
      // Video lock password
      $this->addElement('password', 'password', array(
          'label' => 'Set Album Password',
					'autocomplete'=>'off',
          'value' => '',
      ));
    }
		
    // Submit or succumb!
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }
}
