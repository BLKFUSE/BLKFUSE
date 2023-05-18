<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: ReviewSettings.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Manage_ReviewSettings extends Engine_Form {

  public function init() {

    $this->setTitle('Manage Reviews & Ratings Settings')->setDescription('Here, you can configure settings for  reviews for members on your website.');

    $this->addElement('Radio', 'sesmember_allow_review', array(
        'label' => 'Allow Reviews',
        'description' => 'Do you want to allow users to give reviews on the members on your website? (Users will be also be able to rate members, if you choose “Yes” for this setting.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'onchange' => "allowReview(this.value)",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1),
    ));

    $this->addElement('Radio', 'sesmember_allow_owner', array(
        'label' => 'Allow Reviews on Own Profile',
        'description' => 'Do you want to allow users to give reviews on own profile on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.owner', 1),
    ));

    $this->addElement('Radio', 'sesmember_show_pros', array(
        'label' => 'Allow Pros in Reviews',
        'description' => 'Do you want to allow users to enter Pros in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.pros', 1),
    ));

    $this->addElement('Radio', 'sesmember_show_cons', array(
        'label' => 'Allow Cons in Reviews',
        'description' => 'Do you want to allow users to enter Cons in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.cons', 1),
    ));

    $this->addElement('Radio', 'sesmember_review_summary', array(
        'label' => 'Allow Description in Reviews',
        'description' => 'Do you want to allow users to enter description in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'onchange' => "showEditor(this.value)",
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.summary', 1),
    ));

    $this->addElement('Radio', 'sesmember_show_tinymce', array(
        'label' => 'Enable WYSIWYG Editor for Description',
        'description' => 'Do you want to enable WYSIWYG Editor for description for reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.tinymce', 1),
    ));

    $this->addElement('Radio', 'sesmember_show_recommended', array(
        'label' => 'Allow Recommended Option',
        'description' => 'Do you want to allow users to choose to recommend the members in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.recommended', 1),
    ));

    $this->addElement('Radio', 'sesmember_allow_share', array(
        'label' => 'Allow Share Option',
        'description' => 'Do you want to allow users to share reviews on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.share', 1),
    ));

    $this->addElement('Radio', 'sesmember_show_report', array(
        'label' => 'Allow Report Option',
        'description' => 'Do you want to allow users to report reviews on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.show.report', 1),
    ));
    $settings = Engine_Api::_()->getApi('settings', 'core');

    /* text for rating starts */
    $this->addElement('Text', "sesmember_rating_stars_one", array(
        'label' => 'Mouse over rating text on first star',
        'description' => "Enter the text you want when user mouse over on first star.",
        'value' => $settings->getSetting('sesmember.rating.stars.one', 'terrible'),
    ));
    $this->addElement('Text', "sesmember_rating_stars_two", array(
        'label' => 'Mouse over rating text on second star',
        'description' => "Enter the text you want when user mouse over on second star.",
        'value' => $settings->getSetting('sesmember.rating.stars.two', 'poor'),
    ));
    $this->addElement('Text', "sesmember_rating_stars_three", array(
        'label' => 'Mouse over rating text on third star',
        'description' => "Enter the text you want when user mouse over on third star.",
        'value' => $settings->getSetting('sesmember.rating.stars.three', 'average'),
    ));
    $this->addElement('Text', "sesmember_rating_stars_four", array(
        'label' => 'Mouse over rating text on fourth star',
        'description' => "Enter the text you want when user mouse over on fourth star.",
        'value' => $settings->getSetting('sesmember.rating.stars.four', 'very good'),
    ));
    $this->addElement('Text', "sesmember_rating_stars_five", array(
        'label' => 'Mouse over rating text on fifth star',
        'description' => "Enter the text you want when user mouse over on fifth star.",
        'value' => $settings->getSetting('sesmember.rating.stars.five', 'excellent'),
    ));
    
    /* review votes */
    $this->addElement('Select', 'sesmember_review_votes', array(
        'label' => 'Enable Review Votes',
        'description' => 'Do you want user of your site give votes on given review.',
        'multiOptions' => array(
            1 => "Yes, allow review votes",
            0 => 'No, Don\'t allow review votes'
        ),
        'onchange' => 'showReviewVotes(this.value)',
        'value' => $settings->getSetting('sesmember.review.votes', 1),
    ));

    $this->addElement('Text', "sesmember_review_first", array(
        'label' => 'Text for review vote "Useful"',
        'description' => 'Enter the text for "Useful" for review votes',
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesmember_review_first', 'Useful'),
    ));

    $this->addElement('Text', "sesmember_review_second", array(
        'label' => 'Text for review vote "Funny"',
        'description' => 'Enter the text for "Funny" for review votes',
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesmember_review_second', 'Funny'),
    ));
    $this->addElement('Text', "sesmember_review_third", array(
        'label' => 'Text for review vote "Cool"',
        'description' => 'Enter the text for "Cool" for review votes',
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesmember_review_third', 'Cool'),
    ));
    
    $this->addElement('Button', 'execute', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
    ));
  }

}