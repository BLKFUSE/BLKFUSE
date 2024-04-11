<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Create extends Engine_Form
{
  public $_error = array();
  protected $_defaultProfileId;
  protected $_fromApi;
  protected $_smoothboxType;
  public function getFromApi() {
    return $this->_fromApi;
  }
  public function setFromApi($fromApi) {
    $this->_fromApi = $fromApi;
    return $this;
  }

  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }
  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }

  public function getSmoothboxType() {
    return $this->_smoothboxType;
  }
  public function setSmoothboxType($smoothboxType) {
    $this->_smoothboxType = $smoothboxType;
    return $this;
  }

  public function init() {
    if($this->getSmoothboxType())
      $hideClass = 'sesnews_hideelement_smoothbox';
    else
      $hideClass = '';
    $viewer = Engine_Api::_()->user()->getViewer();
  $translate = Zend_Registry::get('Zend_Translate');
	 if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
		 $package_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('package_id');
	 if($package_id)
	 	$package = Engine_Api::_()->getItem('sesnewspackage_package',$package_id);
	 else{
		$existing_package_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('existing_package_id');
		$existing_package = Engine_Api::_()->getItem('sesnewspackage_orderspackage',$existing_package_id);
		$package = Engine_Api::_()->getItem('sesnewspackage_package',$existing_package->package_id);
	}
		if ( ($package) && $package_id || $existing_package_id) {
			$modulesEnable = json_decode($package->params,true);
		}
	 }

    $this->setTitle('Write New Entry')
      ->setDescription('Compose your new news entry below, then click "Post Entry" to publish the entry to your news.')
      ->setAttrib('name', 'sesnews_create');

		if($this->getSmoothboxType())
			$this->setAttrib('class','global_form sesnews_smoothbox_create');

    $user = Engine_Api::_()->user()->getViewer();
    $user_level = Engine_Api::_()->user()->getViewer()->level_id;

    if (Engine_Api::_()->core()->hasSubject('sesnews_news'))
    $news = Engine_Api::_()->core()->getSubject();

    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        new Engine_Filter_Censor(),
        'StripTags',
        new Engine_Filter_StringLength(array('max' => '224'))
      ),
    ));

    $custom_url_value = isset($news->custom_url) ? $news->custom_url : (isset($_POST["custom_url"]) ? $_POST["custom_url"] : "");
    // Custom Url
    $this->addElement('Dummy', 'custom_url_news', array(
	'label' => 'Custom Url',
	'content' => '<input type="text" name="custom_url" id="custom_url" value="' . $custom_url_value . '"><span class="sesnews_check_availability_btn"><button id="check_custom_url_availability" type="button" name="check_availability" ><i class="fa fa-check" id="sesnews_custom_url_correct"></i><i class="fa fa-times" id="sesnews_custom_url_wrong" style="display:none;"></i><img src="application/modules/Core/externals/images/loading.gif" id="sesnews_custom_url_loading" alt="Loading" style="display:none;" /><samp class="availability_tip">Check Availability</samp></button></span>',
    ));

    // init to
    $this->addElement('Text', 'tags', array(
        'label' => 'Tags (Keywords)',
        'autocomplete' => 'off',
        'description' => 'Separate tags with commas.',
        'filters' => array(
            new Engine_Filter_Censor(),
        )
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");

    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.start.date', 1))  {

    $this->addElement('Radio', 'show_start_time', array(
        'label' => 'Start Date',
        'description' => '',
        'multiOptions' => array(
        "" => 'Choose Start Date',
        "1" => 'Publish Now',
      ),
      'value' => 1,
      'onclick' => "showStartDate(this.value);",
    ));
      if($this->getFromApi()){
        // Start time
        $start = new Engine_Form_Element_Date('starttime');
        $start->setLabel("Start Time");
        $start->setAllowEmpty(true);
        $start->setRequired(false);
        $this->addElement($start);
      }
			if(empty($_POST)){
				$startDate = date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + 2 minutes'));
				$start_date = date('m/d/Y',strtotime($startDate));
				$start_time = date('g:ia',strtotime($startDate));

				if($viewer->timezone){
					$start =  strtotime(date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s') . ' + 2 minutes')));
					$selectedTime = "00:02:00";
					$startTime = time()+strtotime($selectedTime);
					$oldTz = date_default_timezone_get();
					date_default_timezone_set($viewer->timezone);
					$start_date = date('m/d/Y',($start));
					$start_time = date('g:ia',$startTime);
					date_default_timezone_set($oldTz);
				}
			}else{
				$start_date = date('m/d/Y',strtotime($_POST['start_date']));
				$start_time = date('g:ia',strtotime($_POST['start_time']));
			}
			$this->addElement('dummy', 'news_custom_datetimes', array(
				'decorators' => array(array('ViewScript', array(
										'viewScript' => 'application/modules/Sesnews/views/scripts/_customdates.tpl',
										'class' => 'form element',
										'start_date'=>$start_date,
										'start_time'=>$start_time,
										'start_time_check'=>1,
										'subject'=>isset($news) ? $news : '',
								)))
			));
    }
    
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)) {
      $locale = Zend_Registry::get('Zend_Translate')->getLocale();
      $territories = Zend_Locale::getTranslationList('territory', $locale, 2);
      asort($territories);
      $countrySelect = '';
      $countrySelected = '';
      if (engine_count($territories)) {
        $countrySelect = '<option value="">Choose Country</option>';
        if (isset($news)) {
          $itemlocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData('news', $news->getIdentity());
          if ($itemlocation)
            $countrySelected = $itemlocation->country;
        }
        foreach ($territories as $key => $valCon) {
          if ($valCon == $countrySelected)
            $countrySelect .= '<option value="' . $valCon . '" selected >' . $valCon . '</option>';
          else
            $countrySelect .= '<option value="' . $valCon . '" >' . $valCon . '</option>';
        }
      }
      $this->addElement('dummy', 'news_location', array(
        'decorators' => array(array('ViewScript', array(
          'viewScript' => 'application/modules/Sesnews/views/scripts/_location.tpl',
          'class' => 'form element',
          'news' => isset($news) ? $news : '',
          'countrySelect' => $countrySelect,
          'itemlocation' => isset($itemlocation) ? $itemlocation : '',
        )))
      ));
    }
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1)) {

      $this->addElement('Text', 'location', array(
        'label' => 'Location',
        'id' => 'locationSes',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
      ));

      $this->addElement('Text', 'lat', array(
        'label' => 'Lat',
        'id' => 'latSes',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
      ));

      $this->addElement('dummy', 'map-canvas', array());
      $this->addElement('dummy', 'ses_location', array('content'));
      $this->addElement('Text', 'lng', array(
        'label' => 'Lng',
        'id' => 'lngSes',
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_HtmlSpecialChars(),
        ),
      ));
    }

    // prepare categories
    $categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc(array('member_levels' => 1));
    if( engine_count($categories) > 0 ) {
		$categorieEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.category.enable', '1');
		if ($categorieEnable == 1) {
			$required = true;
			$allowEmpty = false;
		} else {
			$required = false;
			$allowEmpty = true;
		}
	$categories = array('' => '') + $categories;
	// category field
	$this->addElement('Select', 'category_id', array(
	    'label' => 'Category',
	    'multiOptions' => $categories,
	    'allowEmpty' => $allowEmpty,
	    'required' => $required,
	    'onchange' => "showSubCategory(this.value);",
	));
	//Add Element: 2nd-level Category
	$this->addElement('Select', 'subcat_id', array(
	    'label' => "2nd-level Category",
	    'allowEmpty' => true,
	    'required' => false,
	    'multiOptions' => array('0' => ''),
	    'registerInArrayValidator' => false,
	    'onchange' => "showSubSubCategory(this.value);"
	));
	//Add Element: Sub Sub Category
	$this->addElement('Select', 'subsubcat_id', array(
	    'label' => "3rd-level Category",
	    'allowEmpty' => true,
	    'registerInArrayValidator' => false,
	    'required' => false,
	    'multiOptions' => array('0' => ''),
	    'onchange' => 'showCustom(this.value);'
	));



      $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
      $customFields = new Sesnews_Form_Custom_Fields(array(
          'item' => 'sesnews_news',
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
	$photoLeft = true;
	if(isset($existing_package_id) && $existing_package_id){
			$modulesEnable = json_decode($package->params,true);
			$package_id = $existing_package->package_id;
			if($package_id){
				$photoLeft = $package->allowUploadPhoto($existing_package->getIdentity(),true);
			}

	}

		$mainPhotoEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.photo.mandatory', '1');
		if ($mainPhotoEnable == 1) {
			$required = true;
			$allowEmpty = false;
		} else {
			$required = false;
			$allowEmpty = true;
		}
	 // Init submit
    if($this->getFromApi()){
     $this->addElement('File', 'file', array(
        'label' => 'Main Photo',
        'description' => '',
      ));
    }
   if(((isset($modulesEnable) && array_key_exists('modules',$modulesEnable) && engine_in_array('photo',$modulesEnable['modules'])) || empty($modulesEnable)) && $photoLeft){
		 	if(isset($modulesEnable) && array_key_exists('photo_count',$modulesEnable) && $modulesEnable['photo_count']){
				if(isset($photoLeft))
					$photo_count = $photoLeft;
				else
					$photo_count = $modulesEnable['photo_count'];
	 			$this->addElement('hidden', 'photo_count', array('value' => $photo_count,'order'=>8769));
	 		}


			$this->addElement('Dummy', 'fancyuploadfileids', array('content'=>'<input id="fancyuploadfileids" name="file" type="hidden" value="" >'));

			$this->addElement('Dummy', 'tabs_form_newscreate', array(
			'label' => 'Upload photos',
			//'required'=>$required,
			//'allowEmpty'=>$allowEmpty,
			'content' => '<div class="sesnews_create_form_tabs sesbasic_clearfix sesbm"><ul id="sesnews_create_form_tabs" class="sesbasic_clearfix"><li class="active sesbm"><i class="fa fa-arrows sesbasic_text_light"></i><a href="javascript:;" class="drag_drop">'.$translate->translate('Drag & Drop').'</a></li><li class=" sesbm"><i class="fa fa-upload sesbasic_text_light"></i><a href="javascript:;" class="multi_upload">'.$translate->translate('Multi Upload').'</a></li><li class=" sesbm"><i class="fa fa-link sesbasic_text_light"></i><a href="javascript:;" class="from_url">'.$translate->translate('From URL').'</a></li></ul></div>',
			));
			$this->addElement('Dummy', 'drag-drop', array(
			'content' => '<div id="dragandrophandler" class="sesnews_upload_dragdrop_content sesbasic_bxs">'.$translate->translate('Drag & Drop Photos Here').'</div>',
			));
			$this->addElement('Dummy', 'from-url', array(
			'content' => '<div id="from-url" class="sesnews_upload_url_content sesbm"><input type="text" name="from_url" id="from_url_upload" value="" placeholder="'.$translate->translate('Enter Image URL to upload').'"><span id="loading_image"></span><span></span><button id="upload_from_url">'.$translate->translate('Upload').'</button></div>',
			));

			$this->addElement('Dummy', 'file_multi', array('content'=>'<input type="file" accept="image/x-png,image/jpeg" onchange="readImageUrl(this)" multiple="multiple" id="file_multi" name="file_multi">'));
			$this->addElement('Dummy', 'uploadFileContainer', array('content'=>'<div id="show_photo_container" class="sesnews_upload_photos_container sesbasic_bxs sesbasic_custom_scroll clear"><div id="show_photo"></div></div>'));
  }else{
		//make main photo upload btn
		$this->addElement('File', 'photo_file', array(
			'label' => 'Main Photo',
			'required'=>$required,
			'allowEmpty'=>$allowEmpty,
		));
		$this->photo_file->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
	}

  //UPLOAD PHOTO URL
  $editorOptions = array(
    'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
  );

		if((isset($modulesEnable) && array_key_exists('enable_tinymce',$modulesEnable) && $modulesEnable['enable_tinymce']) || empty($modulesEnable)) {
				$textarea = 'TinyMce';
		}else
			$textarea = 'Textarea';


		$descriptionMan= Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.description.mandatory', '1');
		if ($descriptionMan == 1) {
			$required = true;
			$allowEmpty = false;
		} else {
			$required = false;
			$allowEmpty = true;
		}

    $this->addElement($textarea, 'body', array(
      'label' => 'News Description',
      'required' => $required,
      'allowEmpty' => $allowEmpty,
      'editorOptions' => $editorOptions,
    ));

    if (Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'allow_levels')) {

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
            'description' => 'Choose the Member Levels to which this News will be displayed. (Note: Hold down the CTRL key to select or de-select specific member levels.)',
            'value' => $levelValues,
        ));
    }

    if (Engine_Api::_()->authorization()->isAllowed('sesnews_news', $viewer, 'allow_network')) {
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
          'description' => 'Choose the Networks to which this Page will be displayed. (Note: Hold down the CTRL key to select or de-select specific networks.)',
          'value' => $networkValues,
      ));
    }

    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablenewsdesignview', 0)) {

      $chooselayout = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.chooselayout', 'a:4:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";}');
      $chooselayoutVal = unserialize($chooselayout);

      $designoptions = array();
      if(engine_in_array(1, $chooselayoutVal)) {
        $designoptions[1] = '<a href="" onclick="showPreview(1);return false;"><img src="./application/modules/Sesnews/externals/images/layout_1.jpg" alt="" /></a> '.$translate->translate("Design 1");
      }
      if(engine_in_array(2, $chooselayoutVal)) {
        $designoptions[2] = '<a href="" onclick="showPreview(2);return false;"><img src="./application/modules/Sesnews/externals/images/layout_2.jpg" alt="" /></a> '.$translate->translate("Design 2");
      }
      if(engine_in_array(3, $chooselayoutVal)) {
        $designoptions[3] = '<a href="" onclick="showPreview(3);return false;"><img src="./application/modules/Sesnews/externals/images/layout_3.jpg" alt="" /></a> '.$translate->translate("Design 3");
      }
      if(engine_in_array(4, $chooselayoutVal)) {
        $designoptions[4] = '<a href="" onclick="showPreview(4);return false;"><img src="./application/modules/Sesnews/externals/images/layout_4.jpg" alt="" /></a> '.$translate->translate("Design 4");
      }

      $this->addElement('Radio', 'newstyle', array(
        'label' => 'News Layout',
        'description' => 'Set Your News Template',
        'multiOptions' => $designoptions,
        'escape' => false,
        'value' => $chooselayoutVal,
      ));
		} else {
      $this->addElement('Hidden', 'newstyle', array(
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.defaultlayout', 1),
      ));
		}
		if(Engine_Api::_()->authorization()->isAllowed('sesnews_news', Engine_Api::_()->user()->getViewer(), 'cotinuereading')){
			 $this->addElement('Radio', 'cotinuereading', array(
				'label' => 'Continue Reading',
				'multiOptions' => array(
					'1'=>'Yes',
					'0'=>'No',
				),
				'value'=>'1',
        ));
		}else{
			if(Engine_Api::_()->authorization()->isAllowed('sesnews_news', Engine_Api::_()->user()->getViewer(), 'cntrdng_dflt')){
				$val = 1;
			}else{
				$val = 0;
			}
			$this->addElement('Hidden', 'cotinuereading', array(
                'value' => $val,
                'order' => 9999999999,
			));
		}



    $this->addElement('Checkbox', 'search', array(
      'label' => 'Show this news entry in search results',
      'value' => 1,
    ));

    $availableLabels = array(
      'everyone'            => 'Everyone',
      'registered'          => 'All Registered Members',
      'owner_network'       => 'Friends and Networks',
      'owner_member_member' => 'Friends of Friends',
      'owner_member'        => 'Friends Only',
      'owner'               => 'Just Me'
    );

    // Element: auth_view
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesnews_news', $user, 'auth_view');
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));

    if( !empty($viewOptions) && engine_count($viewOptions) >= 1 ) {

      // Make a hidden field
      if(engine_count($viewOptions) == 1) {
        $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions), 'order' => 99999999999));
      // Make select box
      } else {
        $this->addElement('Select', 'auth_view', array(
            'label' => 'Privacy',
            'description' => 'Who may see this news entry?',
            'multiOptions' => $viewOptions,
            'value' => key($viewOptions),
            'class'=>$hideClass,
        ));
        $this->auth_view->getDecorator('Description')->setOption('placement', 'append');
      }
    }

    // Element: auth_comment
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('sesnews_news', $user, 'auth_comment');
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

    if( !empty($commentOptions) && engine_count($commentOptions) >= 1 ) {
      // Make a hidden field
      if(engine_count($commentOptions) == 1) {
        $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions), 'order' => 999999999));
      // Make select box
      } else {
        $this->addElement('Select', 'auth_comment', array(
            'label' => 'Comment Privacy',
            'description' => 'Who may post comments on this news entry?',
            'multiOptions' => $commentOptions,
            'value' => key($commentOptions),
            'class'=>$hideClass,
        ));
        $this->auth_comment->getDecorator('Description')->setOption('placement', 'append');
      }
    }
 		$this->addElement('Select', 'draft', array(
      'label' => 'Status',
      'multiOptions' => array("0"=>"Published", "1"=>"Saved As Draft"),
      'description' => 'If this entry is published, it cannot be switched back to draft mode.',
      'class'=>$hideClass,
    ));
    $this->draft->getDecorator('Description')->setOption('placement', 'append');
     $this->addElement('Button', 'submit_check',array(
      'type' => 'submit',
    ));

    // Element: submit
    $this->addElement('Button', 'submit', array(
        'label' => 'Post Entry',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    if($this->getSmoothboxType()) {
			$this->addElement('Cancel', 'advanced_sesnewsoptions', array(
        'label' => 'Show Advanced Settings',
        'link' => true,
				'class'=>'active',
        'href' => 'javascript:;',
        'onclick' => 'return false;',
        'decorators' => array(
            'ViewHelper'
        )
    	));
			$this->addElement('Dummy', 'brtag', array(
					'content' => '<span style="margin-top:5px;"></span>',
			));
			$this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
        'href' => '',
				'prependText' => ' or ',
        'onclick' => 'sessmoothboxclose();',
        'decorators' => array(
            'ViewHelper'
        )
    	));
			$this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
					'decorators' => array(
							'FormElements',
							'DivDivDivWrapper',
					),
			));
    }
  }
}
