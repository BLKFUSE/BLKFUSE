<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Join_Create extends Engine_Form {

  public function init() {
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))->setMethod('POST');
    $translate = Zend_Registry::get('Zend_Translate');
    // Init form
    $this->setTitle('Join Contest')->setAttrib('id', 'form-upload');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $contest_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('contest_id', 0);
    $contest = Engine_Api::_()->getItem('contest', $contest_id);

    $ffmpeg_path = Engine_Api::_()->getApi('settings', 'core')->sescontest_ffmpeg_path;
    $checkFfmpeg = Engine_Api::_()->sescontest()->checkFfmpeg($ffmpeg_path);

    $viewer = Engine_Api::_()->user()->getViewer();
    $userData = Engine_Api::_()->fields()->getFieldsValuesByAlias($viewer);

    $this->addElement('Dummy', 'tabs_form_join_contest', array(
        'content' => '<div class="sescontest_join_contest_form_tabs sesbasic_clearfix sesbm"><ul id="contest_join_form_tabs" class="sesbasic_clearfix"><li data-url = "first_step" class="active first_step sesbm"><a id="save-first-click" href="javascript:;">' . Zend_Registry::get('Zend_Translate')->_('Contest Rules') . '</a></li><li data-url="first_second" class="first_second sesbm"><a id="save_second_1-click" href="javascript:;">' . Zend_Registry::get('Zend_Translate')->_('Registration ') . '</a></li><li class="first_third sesbm" data-url = "first_third"><a id="save_third-click" href="javascript:;">' . Zend_Registry::get('Zend_Translate')->_('Upload Content') . '</a></li></ul></div>'
    ));
    // Step 1
    $this->addElement('Dummy', 'contest_rules', array(
        'content' => $contest->rules,
    ));

    $this->addElement('Button', 'save_second_1', array(
        'label' => 'I Accept',
        'class' => 'next_elm',
        'type' => 'button',
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'save_second_2', array(
        'label' => "I Don’t Accept",
        'type' => 'button',
        'link' => true,
        'href' => isset($contest) ? $contest->getHref() : '',
        'decorators' => array('ViewHelper')
    ));

    $this->addDisplayGroup(array(
        'contest_rules',
        'save_second_1',
        'save_second_2',
            ), 'first_step', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));

    // Step 2
    $this->addElement('Dummy', 'contest_basic_info', array(
        'label' => '1 Basic Info',
    ));


    $this->addElement('Text', 'title', array(
        'label' => 'Entry Title',
        'autocomplete' => 'off',
        'allowEmpty' => false,
        'required' => true,
        'validators' => array(
            array('NotEmpty', true),
            array('StringLength', false, array(1, 255)),
        ),
        'filters' => array(
            'StripTags',
            new Engine_Filter_Censor(),
        ),
    ));

    if ($contest->contest_type != 2) {
      //Main Photo
      $showMainPhoto = false;
      if ($contest->contest_type == 1 && $settings->getSetting('sescontest.text.entryphoto', 1)) {
        if ($settings->getSetting('sescontest.text.entryphotorequired', 1)) {
          $allowEmpty = false;
          $required = true;
        }
        $showMainPhoto = true;
      } elseif ($contest->contest_type == 3 && $settings->getSetting('sescontest.video.entryphoto', 1)) {
        if ($settings->getSetting('sescontest.video.entryphotorequired', 1)) {
          $allowEmpty = false;
          $required = true;
        }
        $showMainPhoto = true;
      } elseif ($contest->contest_type == 4 && $settings->getSetting('sescontest.music.entryphoto', 1)) {
        if ($settings->getSetting('sescontest.music.entryphotorequired', 1)) {
          $allowEmpty = false;
          $required = true;
        }
        $showMainPhoto = true;
      }
      $requiredClass = $required ? ' requiredClass' : '';
      if ($showMainPhoto) {
        $this->addElement('File', 'entry_photo', array(
            'label' => 'Entry Main Photo',
            'onclick' => 'javascript:scriptJquery("#entry_photo").val("")',
            'onchange' => 'entryhandleFileBackgroundUpload(this,contest_entry_main_photo_preview)',
        ));
        $this->entry_photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');

        $this->addElement('Dummy', 'photo-uploader-entry', array(
            'label' => 'Entry Main Photo',
            'content' => '<div id="entrydragandrophandlerbackground" class="sescontest_upload_dragdrop_content sesbasic_bxs' . $requiredClass . '"><div class="sescontest_upload_dragdrop_content_inner"><i class="fa fa-camera"></i><span class="sescontest_upload_dragdrop_content_txt">' . $translate->translate('Add photo for your enrty') . '</span></div></div>',
        ));
        $this->addElement('Dummy', 'contest_entry_photo_file', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => 'application/modules/Sescontest/views/scripts/_entryPhoto.tpl',
                    )))
        ));
        $this->addElement('Image', 'contest_entry_main_photo_preview', array(
            'width' => 300,
            'height' => 200,
            'value' => '1',
            'disable' => true,
        ));
        $this->addElement('Dummy', 'removeEntryImage', array(
            'content' => '<a class="icon_cancel form-link" id="removeentryimage1" style="display:none; "href="javascript:void(0);" onclick="removeEntryImage();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
        ));
        $this->addElement('Hidden', 'removeentryimage2', array(
            'value' => 1,
            'order' => 10000000016,
        ));
      }
    }

    if ($settings->getSetting('sescontest.show.entrydescription', 1) && $contest->contest_type != 1) {
      if ($settings->getSetting('sescontest.entrydescription.required', 1)) {
        $required = true;
        $allowEmpty = false;
      } else {
        $required = false;
        $allowEmpty = true;
      }
      $this->addElement('Textarea', 'description', array(
          'label' => 'Description',
          'allowEmpty' => $allowEmpty,
          'required' => $required,
      ));
    }

    if ($settings->getSetting('sescontest.show.entrytag', 1)) {
      $this->addElement('Text', 'tags', array(
          'label' => 'Tags (Keywords)',
          'autocomplete' => 'off',
          'description' => 'Separate tags with commas.',
          'filters' => array(
              new Engine_Filter_Censor(),
          ),
      ));
      $this->tags->getDecorator("Description")->setOption("placement", "append");
    }

    $this->addElement('Dummy', 'contest_user_info', array(
        'label' => '2 User Info',
    ));

    $userInfoOptions = $settings->getSetting('sescontest.user.info', array('name', 'gender', 'age', 'email', 'phone_no'));
    if (engine_in_array('name', $userInfoOptions)) {
      $userName = $userData['first_name'] . ' ' . $userData['last_name'];
      $this->addElement('Text', 'name', array(
          'label' => 'Name',
          'value' => $userName,
      ));
    }

    if (engine_in_array('gender', $userInfoOptions)) {
      $this->addElement('Select', 'gender', array(
          'label' => 'Gender',
          'description' => '',
          'multiOptions' => array(
              1 => '',
              '2' => 'Male',
              '3' => 'Female',
          ),
          'value' => $userData['gender'],
      ));
    }

    if (engine_in_array('age', $userInfoOptions)) {
      $this->addElement('Text', 'age', array(
          'label' => 'Age',
          'autocomplete' => 'off',
//        'validators' => array(
//            array('Int', true),
//            array('GreaterThan', true, 0),
//        ),
          'filters' => array(
              'StripTags',
              new Engine_Filter_Censor(),
          ),
      ));
    }

    if (engine_in_array('email', $userInfoOptions)) {
      // Element: email
      $this->addElement('Text', 'email', array(
          'label' => 'Email',
          'description' => '',
          'required' => true,
          'allowEmpty' => false,
          'value' => $viewer->email,
      ));
    }

    if (engine_in_array('phone_no', $userInfoOptions)) {
      $this->addElement('Text', 'phoneno', array(
          'label' => 'Phone No.',
      ));
    }

    $this->addElement('Button', 'save_third', array(
        'label' => 'Next',
        'class' => 'next_elm',
        'type' => 'button',
    ));

    $this->addDisplayGroup(array(
        'contest_basic_info',
        'title',
        'entry_photo',
        'photo-uploader-entry',
        'contest_entry_photo_file',
        'contest_entry_main_photo_preview',
        'removeEntryImage',
        'description',
        'tags',
        'contest_user_info',
        'name',
        'gender',
        'age',
        'email',
        'phoneno',
        'save_third',
            ), 'first_second', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
    if ($contest->contest_type == 1) {
      //UPLOAD PHOTO URL
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
      $textTypeCount = 0;
      $textUploadType = json_decode(Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'participant', 'blog_options'));
      $blogContent = '<div class="sescontest_join_contest_form_sel_btns sesbasic_clearfix sesbm"><ul id="sescontest_create_form_tabs" class="sesbasic_clearfix">';
      if (engine_in_array('write', $textUploadType)) {
        $blogContent .= '<li id="writeBlog" class="active sesbm"><a href="javascript:;" class=""><i class="fa fa-link"></i>' . $translate->translate('Write') . '</a></li>';
        $textTypeCount++;
      }
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog') && engine_in_array('linkblog', $textUploadType)) {
        $blogContent .= '<li id="contestLinkBlog" class="sesbm"><a href="javascript:;" class=""><i class="fa fa-link"></i>' . $translate->translate('Link My Blog') . '</a></li>';
        $textTypeCount++;
      }
      $blogContent .= '</ul></div>';
      $this->addElement('Dummy', 'tabs_form_contest_entry_create', array(
          'content' => $blogContent,
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => 'application/modules/Sescontest/views/scripts/_linkBlog.tpl',
                      'contest_id' => $contest_id,
                  ))),
      ));
      if ($textTypeCount) {
        if ($contest->editor_type == 1) {
          $this->addElement('TinyMce', 'contest_description', array(
              'label' => 'Write',
              'description' => 'Write your text here',
              'allowEmpty' => false,
              'required' => true,
              'class' => 'tinymce',
              'editorOptions' => $editorOptions,
          ));
        } else {
          $this->addElement('Textarea', 'contest_description', array(
              'label' => 'Write',
              'description' => 'Write your text here',
              'allowEmpty' => false,
              'required' => true,
          ));
        }
      }
    }
    $restapi=Zend_Controller_Front::getInstance()->getRequest()->getParam( 'restApi', null );
    if ($contest->contest_type == 2) {
      $photoUploadType = json_decode(Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'participant', 'photo_options'));
      $photoContent = '<div class="sescontest_join_contest_form_sel_btns sesbasic_clearfix sesbm"><ul id="sescontest_create_form_tabs" class="sesbasic_clearfix">';
      if (engine_in_array('uploadphoto', $photoUploadType)) {
        $photoContent .= '<li id="uploadimage" class="active sesbm"><a href="javascript:;" class="drag_drop"><i class="fa fa-upload"></i>' . $translate->translate('Upload') . '</a></li>';
      }
      if ($ffmpeg_path && $checkFfmpeg && engine_in_array('capture', $photoUploadType)) {
        $photoContent .= '<li id="uploadWebCamPhoto" class="sesbm"><a href="javascript:;" class="multi_upload"><i class="fa fa-camera"></i>' . $translate->translate('Capture') . '</a></li>';
      }
      if (engine_in_array('url', $photoUploadType)) {
        $photoContent .= '<li id="sescontest_from_url" class = "sesbm"><a href = "javascript:;" class = "from_url"><i class = "fa fa-link"></i>' . $translate->translate('From URL') . '</a></li>';
      }
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum') && engine_in_array('linkphoto', $photoUploadType)) {
        $photoContent .= '<li id="sescontest_content_link" class="sesbm"><a href="javascript:;"><i class="fa fa-camera"></i>' . $translate->translate('Link My Photo') . '</a></li>';
      }
      $photoContent .= '</ul></div>';
      $this->addElement('Dummy', 'tabs_form_contest_entry_create', array(
          'content' => $photoContent,
      ));
      $this->addElement('Dummy', 'from-url', array(
          'content' => '<div id="from-url" class="sescontest_upload_url_content sesbm"><input type="text" name="from_url" id="from_url_upload" value="" placeholder="' . $translate->translate('Enter Image URL to upload') . '"><span id="loading_image"></span><span></span><button id="upload_from_url">' . $translate->translate('Upload') . '</button></div>',
      ));
      $this->addElement('Image', 'contest_url_photo_preview', array(
          'width' => 300,
          'height' => 200,
          'value' => '1',
          'disable' => true,
      ));
      $this->addElement('Dummy', 'remove_fromurl_image', array(
          'content' => '<a class="icon_cancel form-link" id="removefromurlimage" style="display:none; "href="javascript:void(0);" onclick="removeFromurlImage(1);"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
      ));
      //Main Photo
	  if($restapi == 'Sesapi'){
		  if(!engine_count($_POST) || !empty($_FILES['photo']['name'])){
		   $this->addElement('File', 'photo', array(
		    'label' => 'Upload Photo',
		   ));
		   $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
		  }
	  }else{
		  $this->addElement('File', 'photo', array(
          'label' => '',
          'onclick' => 'javascript:scriptJquery("#photo").val("")',
          'onchange' => 'handleFileBackgroundUpload(this,contest_main_photo_preview)',
		  ));
		  
		  $this->photo->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
	  }
      $this->addElement('Dummy', 'photo-uploader', array(
			  'label' => '',
			  'content' => '<div id="dragandrophandlerbackground" class="sescontest_upload_dragdrop_content sesbasic_bxs"><div class="sescontest_upload_dragdrop_content_inner"><i class="fa fa-camera"></i><span class="sescontest_upload_dragdrop_content_txt">' . $translate->translate('Add photo for your contest') . '</span></div></div>'
		  ));
      

      
      $this->addElement('Image', 'contest_main_photo_preview', array(
          'width' => 300,
          'height' => 200,
          'value' => '1',
          'disable' => true,
      ));

      $this->addElement('Dummy', 'record_photo', array(
          'decorators' => array(array('ViewScript', array(
                      'viewScript' => 'application/modules/Sescontest/views/scripts/_uploadPhoto.tpl',
                  )))
      ));

      $this->addElement('Dummy', 'removeimage', array(
          'content' => '<a class="icon_cancel form-link" id="removeimage1" style="display:none; "href="javascript:void(0);" onclick="removeImage();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
      ));

      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) {
        $this->addElement('Image', 'contest_link_photo_preview', array(
            'label' => 'Select Your photo',
            'width' => 300,
            'height' => 200,
            'value' => '1',
            'src'=> 'application/modules/Sescontest/externals/images/blank.png',
                //'disable' => true,
        ));

        $this->addElement('Dummy', 'remove_link_image', array(
            'content' => '<a class="icon_cancel form-link" id="removelinkimage" style="display:none; "href="javascript:void(0);" onclick="removeLinkImage();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
        ));

        $this->addElement('Dummy', 'link_photo', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => 'application/modules/Sescontest/views/scripts/_linkPhoto.tpl',
                        'contest_id' => $contest_id,
                    )))
        ));
      }

      $this->addElement('Hidden', 'removeimage2', array(
          'value' => 1,
          'order' => 10000000012,
      ));
    }
      
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideo') && $contest->contest_type == 3) {
        $videoUploadType = json_decode(Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'participant', 'video_options'));
        
      $videoContent = '<div class="sesbasic_clearfix sesbm sescontest_join_contest_form_sel_btns "><ul id="sescontest_create_form_tabs_1" class="sesbasic_clearfix">';
      if (engine_in_array('uploadvideo', $videoUploadType)) {
        $videoContent .= '<li id="uploadvideo" class="active sesbm"><a href="javascript:;"><i class="fa fa-upload"></i>' . $translate->translate('Upload Video') . '</a></li>';
      }
      if ($ffmpeg_path && $checkFfmpeg && engine_in_array('record', $videoUploadType)) {
        $videoContent .= '<li id="uploadWebCamVideo" class=" sesbm"><a href="javascript:;"><i class="fas fa-video"></i>' . $translate->translate('Capture') . '</a></li>';
      }
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideo') && engine_in_array('linkvideo', $videoUploadType)) {
        $videoContent .= '<li id="sescontest_video_link" class="sesbm"><a href="javascript:;"><i class="fa fa-camera"></i>' . $translate->translate('Link My Video') . '</a></li>';
      }
      $videoContent .= '</ul></div>';
      $this->addElement('Dummy', 'tabs_form_contest_entry_create_1', array(
          'content' => $videoContent,
      ));
      $fancyUpload = new Engine_Form_Element_FancyUpload('sescontest_video_file');
      $fancyUpload->clearDecorators()
              ->addDecorator('FormFancyUpload')
              ->addDecorator('viewScript', array(
                  'viewScript' => '_FancyUpload.tpl',
                  'placement' => '',
      ));
      Engine_Form::addDefaultDecorators($fancyUpload);
      $this->addElement($fancyUpload);

        
        $this->addElement('Dummy', 'contest_link_video_preview', array(
            'content' => 'Select Your Video',
            'width' => 300,
            'height' => 200,
        ));
        if ($contest->contest_type == 3) {
          if($restapi == 'Sesapi'){
            $this->addElement('File', 'video', array(
              'label' => 'Video',
            ));
        }

        $this->addElement('Dummy', 'remove_link_video', array(
            'content' => '<a class="icon_cancel form-link" id="removelinkvideo" style="display:none; "href="javascript:void(0);" onclick="removeLinkVideo();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
        ));

        $this->addElement('Dummy', 'link_video', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => 'application/modules/Sescontest/views/scripts/_linkVideo.tpl',
                        'contest_id' => $contest_id,
                    )))
        ));
      }
    } elseif ($contest->contest_type == 4) {

      $musicUploadType = json_decode(Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'participant', 'music_options'));
      $musicContent = '<div class="sesbasic_clearfix sesbm sescontest_join_contest_form_sel_btns"><ul id="sescontest_create_form_tabs_2" class="sesbasic_clearfix">';
      if (engine_in_array('uploadmusic', $musicUploadType)) {
        $musicContent .= '<li id="uploadaudio" class="active sesbm"><a href="javascript:;"><i class="fa fa-upload"></i>' . $translate->translate('Upload Audio') . '</a></li>';
      }
      if ($ffmpeg_path && $checkFfmpeg && engine_in_array('record', $musicUploadType)) {
        $musicContent .= '<li id="uploadWebCamAudio" class=" sesbm"><a href="javascript:;"><i class="fa fa-microphone"></i>' . $translate->translate('Capture') . '</a></li>';
      }
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmusic') && engine_in_array('linkmusic', $musicUploadType)) {
        $musicContent .= '<li id="sescontest_audio_link" class="sesbm"><a href="javascript:;"><i class="fa fa-camera"></i>' . $translate->translate('Link My Audio') . '</a></li>';
      }
      $musicContent .= '</ul></div>';
      $this->addElement('Dummy', 'tabs_form_contest_entry_create_2', array(
          'content' => $musicContent,
      ));
      //Init file uploader
      $fancyUpload = new Engine_Form_Element_FancyUpload('sescontest_audio_file');
      $fancyUpload->clearDecorators()
              ->addDecorator('FormFancyUpload')
              ->addDecorator('viewScript', array(
                  'viewScript' => '_MusicFancyUpload.tpl',
                  'placement' => '',
      ));
      Engine_Form::addDefaultDecorators($fancyUpload);
      $this->addElement($fancyUpload);
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmusic')) {

        $this->addElement('Dummy', 'contest_link_audio_preview', array(
            'content' => 'Select Your Audio',
            'width' => 300,
            'height' => 200,
        ));

        $this->addElement('Dummy', 'contest_link_audio_data', array(
            'width' => 300,
            'height' => 200,
        ));

        $this->addElement('Dummy', 'remove_link_audio', array(
            'content' => '<a class="icon_cancel form-link" id="removelinkaudio" style="display:none; "href="javascript:void(0);" onclick="removeLinkAudio();"><i class="fas fa-trash-alt"></i>' . $translate->translate('Remove') . '</a>',
        ));

        $this->addElement('Dummy', 'link_audio', array(
            'decorators' => array(array('ViewScript', array(
                        'viewScript' => 'application/modules/Sescontest/views/scripts/_linkAudio.tpl',
                        'contest_id' => $contest_id,
                    )))
        ));
      }
    }

    $this->addElement('Hidden', 'uploaded_content_type', array(
        'value' => '',
        'order' => 10000000013,
    ));

    $this->addElement('Hidden', 'ses_recoreded_data', array(
        'value' => '',
        'order' => 10000000014,
    ));

    $this->addElement('Hidden', 'sescontest_link_id', array(
        'value' => '',
        'order' => 10000000015,
    ));

    $this->addElement('Hidden', 'sescontest_url_id', array(
        'value' => '',
        'order' => 10000000017,
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Participate',
        'type' => 'submit',
        'decorators' => array('ViewHelper')
    ));

    $this->addDisplayGroup(array(
        'tabs_form_contest_entry_create',
        'tabs_form_contest_entry_create_1',
        'tabs_form_contest_entry_create_2',
        'from-url',
        'contest_url_photo_preview',
        'remove_fromurl_image',
        'photo',
        'sescontest_video_file',
        'sescontest_audio_file',
        'photo-uploader',
        'contest_main_photo_preview',
        'removeimage',
        'record_photo',
        'contest_link_photo_preview',
        'remove_link_image',
        'link_photo',
        'contest_link_video_preview',
        'remove_link_video',
        'link_video',
        'contest_link_audio_preview',
        'contest_link_audio_data',
        'remove_link_audio',
        'link_audio',
        'contest_link_blog',
        'contest_description',
        'subscribe_newsletter',
        'submit',
            ), 'first_third', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper'
        ),
    ));
  }

}
