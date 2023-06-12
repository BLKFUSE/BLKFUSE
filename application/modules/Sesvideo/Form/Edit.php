<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Edit extends Engine_Form {

  protected $_formApi;

  public function getFromApi() {
    return $this->_formApi;
  }

  public function setFromApi($fromApi) {
    $this->_formApi = $fromApi;
    return $this;
  }

  protected $_defaultProfileId;

  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }

  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }

  public function init() {

    $video_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('video_id');
    if ($video_id) {
      $video = Engine_Api::_()->getItem('video', $video_id);
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Edit Video')
            ->setAttrib('name', 'video_edit');
    $user = Engine_Api::_()->user()->getViewer();

    $this->addElement('Text', 'title', array(
        'label' => 'Video Title',
        'required' => true,
        'notEmpty' => true,
        'validators' => array(
            'NotEmpty',
        ),
        'filters' => array(
            //new Engine_Filter_HtmlSpecialChars(),
            'StripTags',
            new Engine_Filter_Censor(),
            new Engine_Filter_StringLength(array('max' => '100')),
            //new Engine_Filter_HtmlSpecialChars(),
        )
    ));
    $this->title->getValidator('NotEmpty')->setMessage("Please specify an video title");
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)) {
		
      $optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion',''));
      
      $this->addElement('Text', 'location', array(
        'label' => 'Location',
        'id' => 'locationSes',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
      ));

      if(!empty($optionsenableglotion) && !Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
        if(engine_in_array('country', $optionsenableglotion)) {
          $this->addElement('Text', 'country', array(
            'placeholder' => 'Country',
          ));
        }
        if(engine_in_array('state', $optionsenableglotion)) {
          $this->addElement('Text', 'state', array(
            'placeholder' => 'State',
          ));
        }
        if(engine_in_array('city', $optionsenableglotion)) {
          $this->addElement('Text', 'city', array(
            'placeholder' => 'City',
          ));
        }
        if(engine_in_array('zip', $optionsenableglotion)) {
          $this->addElement('Text', 'zip', array(
            'placeholder' => 'Zip',
          ));
        }
      }

      $this->addElement('Text', 'lat', array(
        'placeholder' => 'Latitude',
        'id' => 'latSes',
      ));
      $this->addElement('Text', 'lng', array(
        'placeholder' => 'Longitude',
        'id' => 'lngSes',
      ));
			
    	$this->addDisplayGroup(array('country', 'state', 'city', 'zip', 'lat', 'lng'), 'LocationGroup', array(
				'decorators' => array('FormElements', 'DivDivDivWrapper'),
			));
			
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
        $this->addElement('dummy', 'map-canvas', array());
        $this->addElement('dummy', 'ses_location', array('content'));
      }
		}
    // init tag
    $this->addElement('Text', 'tags', array(
        'label' => 'Tags (Keywords)',
        'autocomplete' => 'off',
        'description' => 'Separate tags with commas.'
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");
    $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesbasic', 'controller' => 'index', 'action' => "upload-image"), 'default', true);

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
      'upload_url' => $upload_url,
      'html' => (bool) $allowed_html,
    );

    if (!empty($upload_url)) {
      $editorOptions['editor_selector'] = 'tinymce';
      $editorOptions['mode'] = 'specific_textareas';
      $editorOptions['plugins'] = array(
        'table', 'fullscreen', 'media', 'preview', 'paste',
        'code', 'image', 'textcolor', 'jbimages', 'link'
      );

      $editorOptions['toolbar1'] = array(
        'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
        'media', 'image', 'jbimages', 'link', 'fullscreen',
        'preview'
      );
    }
    if($settings->getSetting('video.tinymce', 1))
      $tinymce = true;
    else
      $tinymce = false;
    if($tinymce){
      //Overview
     $this->addElement('TinyMce', 'description', array(
       'label' => 'Video Description',
       'class'=>'tinymce',
       'editorOptions' => $editorOptions,
     ));
   }else{
    $this->addElement('Textarea', 'description', array(
        'label' => 'Video Description',
        'rows' => 2,
        'maxlength' => '512',
        'filters' => array(
            'StripTags',
            new Engine_Filter_HtmlSpecialChars(),
            new Engine_Filter_Censor(),
            new Engine_Filter_EnableLinks(),
        )
    ));
  }
  
  if ($settings->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && $settings->getSetting('epaidcontent.allow',1)) {
    $packages = Engine_Api::_()->getDbTable('packages', 'epaidcontent')->getEnabledPackages($viewer->getIdentity());
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
  
    //Artist Work
    $artistArray = array();
    $artistsTable = Engine_Api::_()->getDbTable('artists', 'sesvideo');
    $select = $artistsTable->select()->order('order ASC');
    $artists = $artistsTable->fetchAll($select);

    foreach ($artists as $artist) {
      $artistArray[$artist->artist_id] = $artist->name;
    }

    if (!empty($artistArray) && !$this->getFromApi()) {
      $artistsValues = json_decode($video->artists);
      $this->addElement('MultiCheckbox', 'artists', array(
          'label' => 'Video Artist',
          'descriptions' => 'Choose from the below video artist.',
          'multiOptions' => $artistArray,
          'value' => $artistsValues,
      ));
    }

    // prepare categories
    $categories = Engine_Api::_()->sesvideo()->getCategories();
    if(engine_count($categories)){
      if(!$this->_formApi)
      $categories_prepared[0] = "";
      foreach ($categories as $category) {
        $categories_prepared[$category->category_id] = $category->category_name;
      }

      // category field
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $categories_prepared,
          'onchange' => 'showSubCategory(this.value);showFields(this.value,1,this.class,this.class,"resets");'
      ));

      $catLabel = array();
      if(!$this->_formApi)
        $catLabel = array('0' => 'Please select sub category');
      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'multiOptions' => $catLabel,
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);showFields(this.value,1,this.class,this.class,'resets');"
      ));
      $catLabel = array();
      if(!$this->_formApi)
        array_unshift($catLabel,'Please select 3rd category');
        // $catLabel = array('0' => 'Please select 3rd category');
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => $catLabel,
          'onchange' => 'showCustom(this.value);showFields(this.value,1,this.class,this.class,"resets");'
      ));
      $video = Engine_Api::_()->core()->getSubject();

      // Get category map form data
      $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
      $customFields = new Sesbasic_Form_Custom_Fields(array(
          'item' => Engine_Api::_()->core()->getSubject(),
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
    $this->addElement('Checkbox', 'search', array(
        'label' => "Show this video in search results",
    ));

    $viewer = Engine_Api::_()->user()->getViewer();
    if (Engine_Api::_()->authorization()->isAllowed('video', $viewer, 'allow_levels')) {

        $levelOptions = array();
        $levelValues = array();
        foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level) {
//             if($level->getTitle() == 'Public')
//                 continue;
            $levelOptions[$level->level_id] = $level->getTitle();
            $levelValues[] = $level->level_id;
        }
        // Select Member Levels
        $this->addElement('multiselect', 'levels', array(
            'label' => 'Member Levels',
            'multiOptions' => $levelOptions,
            'description' => 'Choose the Member Levels to which this Video will be displayed. (Note: Hold down the CTRL key to select or de-select specific member levels.)',
            'value' => $levelValues,
        ));
    }

    if (Engine_Api::_()->authorization()->isAllowed('video', $viewer, 'allow_network')) {
      $networkOptions = array();
      $networkValues = array();
      foreach (Engine_Api::_()->getDbTable('networks', 'network')->fetchAll() as $network) {
        $networkOptions[$network->network_id] = $network->getTitle();
        $networkValues[] = $network->network_id;
      }

      // Select Networks
      $this->addElement('multiselect', 'networks', array(
          'label' => 'Networks',
          'multiOptions' => $networkOptions,
          'description' => 'Choose the Networks to which this Video will be displayed. (Note: Hold down the CTRL key to select or de-select specific networks.)',
          'value' => $networkValues,
      ));
    }

    // Privacy
    $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'All Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member' => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'owner' => 'Just Me'
    );


    // View
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'auth_view');
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
    if (empty($viewOptions)) {
      $viewOptions = $availableLabels;
    }

    if (!empty($viewOptions) && engine_count($viewOptions) >= 1) {
      // Make a hidden field
      if (engine_count($viewOptions) == 1) {
        $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions), 'order' => '189623'));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'Privacy',
            'description' => 'Who may see this video?',
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions),
        ));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    }

    // Comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));
    if (empty($commentOptions)) {
      $commentOptions = $availableLabels;
    }

    if (!empty($commentOptions) && engine_count($commentOptions) >= 1) {
      // Make a hidden field
      if (engine_count($commentOptions) == 1) {
        $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions), 'order' => '189624'));
        // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy',
            'description' => 'Who may post comments on this video?',
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    }

		$allowAdultContent = Engine_Api::_()->getApi('settings', 'core')->getSetting('ses.allow.adult.filtering');
		if($allowAdultContent){
			 // Init search
			$this->addElement('Checkbox', 'adult', array(
					'label' => "Mark Video as Adult",
					'value' => 0,
			));
		}

		$viewer = Engine_Api::_()->user()->getViewer();
    //check dependent module sesprofile install or not
    if (Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable('sesprofilelock') && Engine_Api::_()->authorization()->getPermission($viewer, 'video', 'video_locked')) {
      // Video enable password
      $this->addElement('Select', 'is_locked', array(
          'label' => 'Enable Video Lock',
          'multiOptions' => array(
              0 => 'No',
              1 => 'Yes',
          ),
          'onchange' => 'enablePasswordFiled(this.value);',
          'value' => 0
      ));
      // Video lock password
      $this->addElement('password', 'password', array(
          'label' => 'Set Lock Password',
          'value' => '',
      ));
    }
    $uploadoption = $settings->getSetting('video.uploadphoto', '0');
    if (isset($video) && $uploadoption == 1) {
      if (isset($video) && $video->photo_id) {
        $img_path = Engine_Api::_()->storage()->get($video->photo_id, '');
			if($img_path){
       if(strpos($img_path,'http') === FALSE)
				$path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path->getPhotoUrl();
			 else
				$path =$img_path->getPhotoUrl();
        if (isset($path) && !empty($path)) {
          $this->addElement('Image', 'cover_photo_preview sesbd', array(
              'src' => $path,
              'label' => 'Video Photo',
              'class' => 'sesvideo_channel_thumb_preview sesbd',
							'onClick'=>'return false;',
          ));
          $this->addElement('File', 'photo_id', array(
              
          ));
          $this->addElement('Checkbox', 'remove_photo', array(
              'label' => 'Check to remove Photo.',
          ));
        }
			}else{
					$this->addElement('File', 'photo_id', array(
            'label' => 'Video Photo',
        ));
			}
        $this->photo_id->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
      } else {
        $this->addElement('File', 'photo_id', array(
            'label' => 'Video Photo',
        ));
        $this->photo_id->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
      }
    }

    //Price
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && $video->payment_type == 'paid') {

/*      $this->addElement('Radio', 'payment_type', array(
          'label' => 'Video Payment Type',
          'multiOptions' => array(
            'paid' => 'Paid Video',
            'free' => 'Free Video',
          ),
          'value' => 'paid',
          'onclick' => 'showPaidVideoOptions(this.value);',
      )); */

      $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

      $usergatewaylink = '<a href="members-details" target="_blank">here</a>';

      $descriptionprice = sprintf('Enter the price for this video. To receive the payments of videos sold from this website, please enter your payment gateways account details from %s. [Final price of this video will include this price + commission fees.]',$usergatewaylink);

      $this->addElement('Text', 'price', array(
        'label' => 'Price* (USD)',
        'description' => $descriptionprice,
      // 'required' => true,
      // 'allowEmpty' => false,
//         'validators' => array(
//           array('Float', true),
//           new Engine_Validate_AtLeast(0),
//         ),
        'value' => '0.00',
      ));
      $this->getElement('price')->getDecorator('Description')->setOptions(array('placement' => 'append', 'escape' => false));
    }

    if (!empty($artistArray) && $this->getFromApi()) {
      $artistsValues = json_decode($video->artists);
      $this->addElement('MultiCheckbox', 'artists', array(
          'label' => 'Video Artist',
          'descriptions' => 'Choose from the below video artist.',
          'multiOptions' => $artistArray,
          'value' => $artistsValues,
      ));
    }

    // Element: execute
    $this->addElement('Button', 'execute', array(
        'label' => 'Save Video',
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
        'href' => '',
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
