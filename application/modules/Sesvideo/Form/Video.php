<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Video.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Video extends Engine_Form {
  protected $_defaultProfileId;
  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }
  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }
  protected $_fromApi;
  public function getFromApi() {
    return $this->_fromApi;
  }
  public function setFromApi($fromApi) {
    $this->_fromApi = $fromApi;
    return $this;
  }

  protected $_fromTick;
  public function getFromTick() {
    return $this->_fromTick;
  }
  public function setFromTick($fromTick) {
    $this->_fromTick = $fromTick;
    return $this;
  }


  public function init() {
    $video_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('video_id');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if ($video_id) {
      $video = Engine_Api::_()->getItem('video', $video_id);
    }
    // Init form
    $this
    ->setTitle('Add New Video')
    ->setAttrib('id', 'form-upload')
    ->setAttrib('name', 'video_create')
    ->setAttrib('enctype', 'multipart/form-data')
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;
    if(Zend_Controller_Front::getInstance()->getRequest()->getParam('type'))
     $valueUpload = Zend_Controller_Front::getInstance()->getRequest()->getParam('type');
   else
     $valueUpload = '';
   $user = Engine_Api::_()->user()->getViewer();
  if(!$this->getFromApi()){
		// Init video
    $this->addElement('Select', 'type', array(
      'label' => 'Video Source',
      'multiOptions' => array(),
      'onchange' => "updateTextFields()",
      'value'=>$valueUpload,
    ));
  }
  $video_options = array();
  $myComputer = false;
  
  $viewer = Engine_Api::_()->user()->getViewer();
  $allowedUploadOption = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $viewer, 'video_uploadoptn');
  foreach ($allowedUploadOption as $key => $valueUploadoption) {
    if ($valueUploadoption == 'youtube' && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.youtube.apikey', false))
        $video_options[1] = "YouTube";
      if ($valueUploadoption == 'youtubePlaylist' && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.youtube.apikey', false))
        $video_options[5] = 'Youtube Playlist';
      if ($valueUploadoption == 'vimeo')
        $video_options[2] = "Vimeo";
      if ($valueUploadoption == 'dailymotion')
        $video_options[4] = 'Daily Motion';
      // if ($valueUploadoption == 'url')
      //   $video_options[16] = 'From URL';
        if ($valueUploadoption == 'iframely')
      $video_options['iframely'] = 'External Site';
      // if ($valueUploadoption == 'embedcode')
      //   $video_options[17] = 'From Embed Code';
      if ($valueUploadoption == 'myComputer' && (Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ffmpeg.path', false) || Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.direct.video', false)))
        $myComputer = true;
  }

    //My Computer
  if ($myComputer) {
      $allowed_upload = 1;//Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'upload');
      $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
      //if (!empty($ffmpeg_path) && $allowed_upload) {
      if (Engine_Api::_()->hasModuleBootstrap('mobi') && Engine_Api::_()->mobi()->isMobile()) {
        $video_options[3] = "My Device";
      } else {
        $video_options[3] = "My Computer";
      }
    }
    if($this->getFromApi() && $this->getFromTick()){
        $channels = Engine_Api::_()->getDbTable("chanels",'sesvideo')->getChanels(array('user_id' => $viewer->getIdentity()));
        $categories_preparedChannel[''] = "";
        foreach ($channels as $channel) {
            $categories_preparedChannel[$channel->getIdentity()] = $channel->getTitle();
        }
        if(engine_count($categories_preparedChannel) > 1){
          // category field
          $this->addElement('Select', 'channel_id', array(
              'label' => 'Channels',
              'multiOptions' => $categories_preparedChannel,
              'allowEmpty' => true,
              'required' => false
          ));
        }
     }
     
    if($this->getFromApi()){
        // Init video
      $this->addElement('Select', 'resource_video_type', array(
        'label' => 'Video Source',
        'multiOptions' => $video_options,
        'onchange' => "updateTextFields()",
        'value'=>1,
      ));
    }else{
      $this->type->addMultiOptions($video_options);
    }
        
    $description = "Paste the web address of the video here.";
    if($this->getFromApi())
      $description = "";
        // Init url
    $this->addElement('Text', 'url', array(
      'label' => 'Video Link (URL)',
      'description' => $description,
      'maxlength' => '150',
      'autocomplete' => "off"
    ));
    $this->url->getDecorator("Description")->setOption("placement", "append");
    
    // Init name
    $this->addElement('Text', 'title', array(
      'label' => 'Video Title',
      'maxlength' => '100',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
            //new Engine_Filter_HtmlSpecialChars(),
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '100')),
      )
    ));
    

    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1) && !$this->getFromApi()){

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
      'description' => 'Separate tags with commas.',
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_HtmlSpecialChars(),
      )
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
     // Init descriptions
     $this->addElement('Textarea', 'description', array(
      'label' => 'Video Description',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
              //new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
      ),
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
   
   
   $video_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('video_id');
   if ($video_id)
    $video = Engine_Api::_()->getItem('sesvideo_video', $video_id);
    //Artist Work
  $artistArray = array();
  $artistsTable = Engine_Api::_()->getDbTable('artists', 'sesvideo');
  $select = $artistsTable->select()->order('order ASC');
  $artists = $artistsTable->fetchAll($select);
  foreach ($artists as $artist) {
    $artistArray[$artist->artist_id] = $artist->name;
  }
  if (!empty($artistArray) && !$this->getFromApi()) {
    $artistsValues = isset($video) ? json_decode($video->artists) : array();;
    $this->addElement('MultiCheckbox', 'artists', array(
      'label' => 'Video Artist',
      'description' => 'Choose from the below video artist.',
      'multiOptions' => $artistArray,
      'value' => $artistsValues,
    ));
  }
    // prepare categories
  //$categories = Engine_Api::_()->sesvideo()->getCategories(array('member_levels' => 1));
  $categories = Engine_Api::_()->sesvideo()->getCategories();
  if (engine_count($categories) != 0) {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $categorieEnable = $settings->getSetting('video.category.enable','1');
    if($categorieEnable == 1){
     $required = true;
     $allowEmpty = false;
   }else{
     $required = false;
     $allowEmpty = true;
   }
   $categories_prepared[''] = "";
   foreach ($categories as $category) {
    $categories_prepared[$category->category_id] = $category->category_name;
  }
      // category field
  $this->addElement('Select', 'category_id', array(
    'label' => 'Category',
    'multiOptions' => $categories_prepared,
    'allowEmpty' => $allowEmpty,
    'required' => $required,
    'onchange' => "showSubCategory(this.value);showFields(this.value,1);",
  ));
  $subcat = array();
  if(!$this->_fromApi)
    $subcat = array('0' => 'Please select sub category');
      //Add Element: Sub Category
  $this->addElement('Select', 'subcat_id', array(
    'label' => "2nd-level Category",
    'allowEmpty' => true,
    'required' => false,
    'multiOptions' => $subcat,
    'registerInArrayValidator' => false,
    'onchange' => "showSubSubCategory(this.value);"
  ));
  $subcat = array();
  if(!$this->_fromApi)
    $subcat = array('0' => 'Please select 3rd category');
      //Add Element: Sub Sub Category
  $this->addElement('Select', 'subsubcat_id', array(
    'label' => "3rd-level Category",
    'allowEmpty' => true,
    'registerInArrayValidator' => false,
    'required' => false,
    'multiOptions' => $subcat,
    'onchange' => 'showFields(this.value,1);'
  ));
  $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
  $customFields = new Sesbasic_Form_Custom_Fields(array(
    'item' => 'video',
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
    //
    // Init search
$this->addElement('Checkbox', 'search', array(
  'label' => "Show this video in search results",
  'value' => 1,
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


    // View
$availableLabels = array(
  'everyone' => 'Everyone',
  'registered' => 'All Registered Members',
  'owner_network' => 'Friends and Networks',
  'owner_member_member' => 'Friends of Friends',
  'owner_member' => 'Friends Only',
  'owner' => 'Just Me'
);
$viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('video', $user, 'auth_view');
$viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
if (!empty($viewOptions) && engine_count($viewOptions) >= 1) {
      // Make a hidden field
  if (engine_count($viewOptions) == 1) {
    $this->addElement('hidden', 'auth_view', array('value' => key($viewOptions), 'order' => '14589623'));
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
if (!empty($commentOptions) && engine_count($commentOptions) >= 1) {
      // Make a hidden field
  if (engine_count($commentOptions) == 1) {
    $this->addElement('hidden', 'auth_comment', array('value' => key($commentOptions), 'order' => '189623'));
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
$sesprofilelock_enable_module = is_string(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) ? unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) : Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}');;
    //check dependent module sesprofile install or not
if (Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesprofilelock')) && engine_in_array('sesvideo',$sesprofilelock_enable_module) && Engine_Api::_()->authorization()->getPermission($viewer, 'video', 'video_locked')) {
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
  $this->addElement('Password', 'password', array(
    'label' => 'Set Lock Password',
    'value' => '',
  ));
}

$uploadoption = $settings->getSetting('video.uploadphoto', '0');
if ($uploadoption == 1) {
  if (isset($video) && $video->photo_id) {
    $img_path = Engine_Api::_()->storage()->get($video->photo_id, '')->getPhotoUrl();
    $path = 'http://' . $_SERVER['HTTP_HOST'] . $img_path;
    if (isset($path) && !empty($path)) {
      $this->addElement('Image', 'cover_photo_preview sesbd', array(
        'src' => $path,
        'class' => 'sesvideo_channel_thumb_preview sesbd',
      ));
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

if(!$this->_fromApi){
  $arra = array(
    0 => '',
    90 => '90°',
    180 => '180°',
    270 => '270°')  ;
}else{
  $arra = array(
   0 => '',
   90 => '90 degree',
   180 => '180 degree',
   270 => '270 degree'
 );

}



    // Video rotation
$this->addElement('Select', 'rotation', array(
  'label' => 'Video Rotation',
  'multiOptions' => $arra,
));


    //Price
if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell')) {

  $this->addElement('Radio', 'payment_type', array(
    'label' => 'Video Payment Type',
    'multiOptions' => array(
      'paid' => 'Paid Video',
      'free' => 'Free Video',
    ),
    'value' => 'paid',
    'onclick' => 'showPaidVideoOptions(this.value);',
  ));

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

		//$this->addElement('FancyUpload', 'file');


// $this->addElement('Textarea', 'embedUrl', array(
//   'label' => 'Video Embed (URL)',
//   'description' => 'Paste the Embed Url of the video here.',
// ));
$this->addElement('Hidden', 'code', array(
  'order' => 1
));
$this->addElement('Hidden', 'id', array(
  'order' => 2
));
$this->addElement('Hidden', 'ignore', array(
  'order' => 3
));

if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && !$this->_fromApi) {

  $this->addElement('Hidden', 'samplevideo_id', array(
    'order' => 4
  ));

//   $fancyUpload_sample = new Engine_Form_Element_FancyUpload('sample_file');
//   $fancyUpload_sample->clearDecorators()
//   ->setLabel("Add Sample Video")
//   ->addDecorator('FormFancyUpload')
//   ->addDecorator('viewScript', array(
//     'viewScript' => '_FancyUpload_sample.tpl',
//     'placement' => '',
//   ));
//   Engine_Form::addDefaultDecorators($fancyUpload_sample);
//   $this->addElement($fancyUpload_sample);
}

if(!$this->_fromApi){
      /*$fancyUpload = new Engine_Form_Element_FancyUpload('file');
      $fancyUpload->clearDecorators()
              ->setLabel("Add Main Video")
              ->addDecorator('FormFancyUpload')
              ->addDecorator('viewScript', array(
                  'viewScript' => '_FancyUpload.tpl',
                  'placement' => '',
      ));
      Engine_Form::addDefaultDecorators($fancyUpload);
      $this->addElement($fancyUpload);
      */
      $fancyUpload = new Engine_Form_Element_HTMLUpload('Filedata');
      $this->addElement($fancyUpload);

    }else{
      $this->addElement('file', 'upload_video', array(
        'Label'=>'Upload Video'
      ));
      if (!empty($artistArray)) {
        $artistsValues = isset($video) ? json_decode($video->artists) : array();;
        $this->addElement('MultiCheckbox', 'artists', array(
          'label' => 'Video Artist',
              //'description' => 'Choose from the below video artist.',
          'multiOptions' => $artistArray,
          'value' => $artistsValues,
        ));
      }
    }

    // Init submit
    $this->addElement('Button', 'upload', array(
      'label' => 'Save Video',
      'type' => 'submit',
      // 'decorators' => array(
      //       'ViewHelper',
      //   ),
    ));
    $this->addElement('Dummy', 'orText', array(
    'content'=>'<span id="orText" style="display:none">or</span>',
      'decorators' => array(
          'ViewHelper'
      )
    ));
     $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        //'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
   $this->addDisplayGroup(array('upload','orText', 'cancel'), 'buttons');

  }
  public function clearAlbum() {
    $this->getElement('album')->setValue(0);
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
      // CREATE AUTH STUFF HERE
      /*    $context = $this->api()->authorization()->context;
        foreach( array('everyone', 'registered', 'member') as $role )
        {
        $context->setAllowed($this, $role, 'view', true);
        }
        $context->setAllowed($this, 'member', 'comment', true);
       */
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
