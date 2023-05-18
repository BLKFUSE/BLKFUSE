<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Seaocore
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Aollogin.php 2010-08-17 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Engine_Form_Decorator_FormSeaoFancyUpload extends Zend_Form_Decorator_Abstract {

    protected $_defaultOptions = array(
        'multiple' => true,
        'submitElement' => 'submit',
        'max_queue' => 5,
        'block_size' => 2008000,
        'limitFiles' => 0,
        'remoteFile' => true,
        'dropEnable' => true,
        'dropAreaText' => 'Drop Files Here',
        'vars' => array(),
        'populateFiles' => array(),
    );
    protected $_fileTypeOptions = array('default', 'video', 'audio');
    protected $_typeOptions = array(
        'default' => array(
            'accept' => 'image/*',
            'autostart' => true,
            'autosubmit' => false,
            'view' => 'grid',
            'wrapperClass' => '',
            'responseParamId' => 'photo_id',
            'linkTitle' => 'Add Photos',
            'linkClass' => 'upload-photo',
            'linkDescription' => 'Click \'Add Photos\' to select one or more photos from your computer. After you have selected the photos, they will begin to upload right away. When your upload is finished, click the button below your photo to save them.',
        ),
        'video' => array(
            'accept' => 'video/*',
            'autostart' => false,
            'autosubmit' => true,
            'view' => 'list',
            'responseParamId' => 'video_id',
            'wrapperClass' => 'video-uploader',
            'linkTitle' => 'Add Video',
            'linkClass' => 'upload-video',
            'linkDescription' => 'Click \'Add Video\' to select a video from your computer. After you have selected video, click on Save Video at the bottom to begin uploading the file. Please wait while your video is being uploaded. When your upload is finished, your video will be processed - you will be notified when it is ready to be viewed.',
        ),
        'audio' => array(
            'accept' => 'audio/*',
            'autostart' => true,
            'autosubmit' => false,
            'view' => 'list',
            'responseParamId' => 'song_id',
            'wrapperClass' => 'music-uploader',
            'linkTitle' => 'Add Music',
            'linkClass' => 'upload-music',
            'linkDescription' => 'Click \'Add Music\' to select one or more songs from your computer. After you have selected the songs, they will begin to upload right away. When your upload is finished, click the button below the song list to save them to your playlist.',
        ),
    );

    public function render($content) {
        $data = $this->getElement()->getAttrib('data');
        if ($data) {
            $this->getElement()->setAttrib('data', null);
        }

        $element = $this->getElement();
        $view = $this->getElement()->getView();
        $view->headScript()
                ->appendFile($view->layout()->staticBaseUrl . 'externals/scrollbars/scrollbars.min.js')
                ->appendFile($view->layout()->staticBaseUrl . 'externals/seao-fancy-uploader/Uploader.js')
                ->appendFile($view->layout()->staticBaseUrl . 'externals/seao-fancy-uploader/Request.Blob.js')
                ->appendFile($view->layout()->staticBaseUrl . 'externals/seao-fancy-uploader/Uploader.HTML5.js');
        $view->headLink()->appendStylesheet($view->layout()->staticBaseUrl . 'externals/seao-fancy-uploader/uploader.css');

        $options = array();
        $viewScript = $element->getAttrib('viewScript');
        if ($viewScript && is_array($viewScript)) {
            $options = isset($viewScript[2]) && is_array($viewScript[2]) ? $viewScript[2] : array();
        } else {
            $viewScript = array('upload/upload.tpl', 'seaocore', array());
        }
        $options = is_array($data) ? array_merge($data, $options) : $options;

        // SET TITLE, DESCRIPTION, CLASS AND NAME FOR THE HTML UPLOAD LINK
        $options['formId'] = $element->getAttrib('formId');
        $options['multiple'] = $element->getAttrib('multiple');
        $options['accept'] = $element->getAttrib('accept');
        $options['url'] = $element->getAttrib('url');
        $options['deleteUrl'] = $element->getAttrib('deleteUrl');
        $options['name'] = $element->getName();
        $options['fileType'] = $element->getAttrib('fileType') ?: 'default';
        $options['max_file_size'] = Seaocore_Service_FancyUpload::getMaximumUploadSize($options['fileType']);
        $options = $this->getAllOptions($options);

        return $view->partial($viewScript[0], $viewScript[1], array(
                    'data' => $options,
                    'element' => $element,
        ));
    }

    protected function getDefaultOptions() {
        $fileType = $this->getElement()->getAttrib('fileType') ?: 'default';
        if (!in_array($fileType, $this->_fileTypeOptions))
            $fileType = 'default';
        return array_merge($this->_defaultOptions, $this->_typeOptions[$fileType]);
    }

    protected function getAllOptions($options = array(), $defaultOptions = null) {
        $defaultOptions = $defaultOptions ?: $this->getDefaultOptions();
        foreach ($defaultOptions as $key => $value) {
            if (is_array($value) && $value) {
                if (!is_array($options[$key])) {
                    $options[$key] = array();
                }
                $options[$key] = $this->getAllOptions($options[$key], $value);
            } elseif (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }
        return $options;
    }

}
