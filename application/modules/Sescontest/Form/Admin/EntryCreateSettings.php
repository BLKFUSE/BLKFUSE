<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EntryCreateSettings.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_EntryCreateSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Entry Submission Settings')
            ->setDescription('Here, you can configure the settings for submitting entries via entry submission form for the contests on your website.');
    $this->addElement('Radio', 'sescontest_show_entrytag', array(
        'label' => 'Enable Tags',
        'description' => 'Do you want to enable Tags (Keywords) for the entries on your website?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.entrytag', 1),
    ));
    $this->addElement('MultiCheckbox', 'sescontest_user_info', array(
        'label' => 'User Info Settings',
        'description' => 'Choose from below the user details you want to show to them on Entry Submission Form? All these details will be pre filled if present in user’s account and user can edit those details while submitting an entry.',
        'multiOptions' => array(
            'name' => 'Name',
            'gender' => 'Gender',
            'age' => 'Age',
            'email' => 'Email',
            'phone_no' => 'Phone No.',
        ),
        'value' => $settings->getSetting('sescontest.user.info', array('name', 'gender', 'age', 'email', 'phone_no')),
    ));
    $this->addElement('Radio', 'sescontest_show_entrydescription', array(
        'label' => 'Enable Description of Entry',
        'description' => 'Do you want to enable description of entries on your website? (Note: For “Text” media type entries description will never come.)',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.entrydescription', 1),
        'onclick' => 'showMandatory(this.value)',
    ));
    $this->addElement('Radio', 'sescontest_entrydescription_required', array(
        'label' => 'Make Description Mandatory',
        'description' => 'Do you want to make description field mandatory when users submite an entry to participate in the contest?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.entrydescription.required', 1),
    ));


    $this->addElement('Radio', 'sescontest_text_entryphoto', array(
        'label' => 'Enable Main Photo of Entry for “Text Type”',
        'description' => 'Do you want to enable main photo of entry on Entry Submit Form for “Text” media type?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.text.entryphoto', 1),
        'onclick' => 'showMainPhoto(this.value)',
    ));
    $this->addElement('Radio', 'sescontest_text_entryphotorequired', array(
        'label' => 'Make Main Photo Mandatory for Text Type',
        'description' => 'Do you want to make main photo field mandatory for “Text” media type when users submit entry to participate in the contest?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.text.entryphotorequired', 1),
    ));

    $this->addElement('Radio', 'sescontest_music_entryphoto', array(
        'label' => 'Enable Main Photo of Entry for “Music Type”',
        'description' => 'Do you want to enable main photo of entry on Entry Submit Form for “Music” media type?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.music.entryphoto', 1),
        'onclick' => 'showMusicMainPhoto(this.value)',
    ));
    $this->addElement('Radio', 'sescontest_music_entryphotorequired', array(
        'label' => 'Make Main Photo Mandatory for Music Type',
        'description' => 'Do you want to make main photo field mandatory for “Music” media type when users submit entry to participate in the contest?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.music.entryphotorequired', 1),
    ));

    $this->addElement('Radio', 'sescontest_video_entryphoto', array(
        'label' => 'Enable Main Photo of Entry for “Video Type”',
        'description' => 'Do you want to enable main photo of entry on Entry Submit Form for “Video” media type?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.video.entryphoto', 1),
        'onclick' => 'showVideoMainPhoto(this.value)',
    ));
    $this->addElement('Radio', 'sescontest_video_entryphotorequired', array(
        'label' => 'Make Main Photo Mandatory for Video Type',
        'description' => 'Do you want to make main photo field mandatory for “Video” media type when users submit entry to participate in the contest?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.video.entryphotorequired', 1),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
