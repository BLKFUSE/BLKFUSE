<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Createmusic.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Form_Admin_Createmusic extends Authorization_Form_Admin_Level_Abstract {
    public function init() {

    $musicId = Zend_Controller_Front::getInstance()->getRequest()->getParam('music_id', null);
        $this
                ->setTitle('Upload New Music form')
                ->setDescription("In this section, you can manage the upload new music.")
                ->setAttrib('id', 'form-create-music')
                ->setAttrib('name', 'tickvideo_create_music')
                ->setAttrib('enctype', 'multipart/form-data')
                ////->setAttrib('onsubmit', 'return checkValidation();')
                ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
        $this->setMethod('post');
        $this->addElement('Text', 'title', array(
            'label' => 'Music Title',
            'description' => 'Enter the title for this music.',
            'allowEmpty' => false,
            'required' => true,
        ));
//         $this->addElement('Textarea', 'description', array(
//             'label' => 'Music Descritpion',
//             'description' => 'Enter the description for this music.',
//             'allowEmpty' => true,
//             'required' => false,
//         ));

        $this->addElement('File', 'image', array(
            'label' => 'Image for Music',
            'description' => 'Upload a image for this music which will be shown in full background of the music. [Note: photos with extension: “jpg, png and jpeg” will only be supported.]',
            'allowEmpty' => true,
            'required' => false,
            'accept' => 'image/*',
        ));
        $this->image->addValidator('Extension', false, 'jpg,png,gif,jpeg,webp');
		 if($musicId)
             $music = Engine_Api::_()->getItem('tickvideo_music', $musicId);
        else
            $music = '';
		if($music->photo_id){
            $backgroundImageSrc = Engine_Api::_()->storage()->get($music->photo_id, '')->getPhotoUrl();
            if($backgroundImageSrc){
                $this->addElement('Dummy', 'dummy_5', array(
                       'content' => '<img src="'.$backgroundImageSrc.'" alt="Background_image" height="100" width="100">',
                   ));
            }
        }
        if(!$music) {
            $this->addElement('File', 'upload', array(
                'label' => 'Mp3 file of Music',
                'description' => 'Upload a mp3 file for this music.',
                'allowEmpty' => false,
                'required' => true,
                'accept' => 'audio/*',
            ));
            $this->upload->addValidator('Extension', false, 'mp3');
        }
        $this->addElement('Button', 'submit', array(
            'label' => 'Save',
            'type' => 'submit',
            'ignore' => true,
            'decorators' => array('ViewHelper')
        ));
        $this->addElement('Cancel', 'cancel', array(
            'label' => 'Cancel',
            'link' => true,
            'prependText' => ' or ',
            'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage')),
            'onClick' => 'javascript:parent.Smoothbox.close();',
            'decorators' => array(
                'ViewHelper'
            )
        ));
        $this->addDisplayGroup(array('save', 'submit', 'cancel'), 'buttons');
    }
}
