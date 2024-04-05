<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ReviewSettings.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Admin_Review_ReviewSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Manage Reviews & Ratings Settings')
            ->setDescription('Here, you can configure settings for  reviews for news on your website.');

    $this->addElement('Radio', 'sesnews_allow_review', array(
        'label' => 'Allow Reviews',
        'description' => 'Do you want to allow users to give reviews on news on your website? (Users will also be able to rate events, if you choose "Yes" for this setting.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'onchange' => 'allowReview(this.value)',
        'value' => $settings->getSetting('sesnews.allow.review', 1),
    ));

    $this->addElement('Radio', 'sesnews_allow_owner', array(
        'label' => 'Allow Reviews on Own News',
        'description' => 'Do you want to allow users to give reviews on own news on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.allow.owner', 1),
    ));

    $this->addElement('Radio', 'sesnews_show_pros', array(
        'label' => 'Allow Pros in Reviews',
        'description' => 'Do you want to allow users to enter Pros in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.show.pros', 1),
    ));

    $this->addElement('Radio', 'sesnews_show_cons', array(
        'label' => 'Allow Cons in Reviews',
        'description' => 'Do you want to allow users to enter Cons in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.show.cons', 1),
    ));

    $this->addElement('Radio', 'sesnews_review_summary', array(
        'label' => 'Allow Description in Reviews',
        'description' => 'Do you want to allow users to enter description in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'onchange' => "showEditor(this.value)",
        'value' => $settings->getSetting('sesnews.review.summary', 1),
    ));

    $this->addElement('Radio', 'sesnews_show_tinymce', array(
        'label' => 'Enable WYSIWYG Editor for Description',
        'description' => 'Do you want to enable WYSIWYG Editor for description for reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.show.tinymce', 1),
    ));

    $this->addElement('Radio', 'sesnews_show_recommended', array(
        'label' => 'Allow Recommended Option',
        'description' => 'Do you want to allow users to choose to recommend the news in their reviews?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.show.recommended', 1),
    ));

    $this->addElement('Radio', 'sesnews_allow_share', array(
        'label' => 'Allow Share Option',
        'description' => 'Do you want to allow users to share reviews on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.allow.share', 1),
    ));

    $this->addElement('Radio', 'sesnews_show_report', array(
        'label' => 'Allow Report Option',
        'description' => 'Do you want to allow users to report reviews on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sesnews.show.report', 1),
    ));

    /* text for rating starts */
    $this->addElement('Text', "sesnews_rating_stars_one", array(
        'label' => 'Mouseover Text for First Star',
        'description' => "Enter the text that you want to display when users mouse over on first rating star.",
        'value' => $settings->getSetting('sesnews.rating.stars.one', 'terrible'),
    ));
    $this->addElement('Text', "sesnews_rating_stars_two", array(
        'label' => 'Mouseover Text for Second Star',
        'description' => "Enter the text that you want to display when users mouse over on second rating star.",
        'value' => $settings->getSetting('sesnews.rating.stars.two', 'poor'),
    ));
    $this->addElement('Text', "sesnews_rating_stars_three", array(
        'label' => 'Mouseover Text for Third Star',
        'description' => "Enter the text that you want to display when users mouse over on third rating star.",
        'value' => $settings->getSetting('sesnews.rating.stars.three', 'average'),
    ));
    $this->addElement('Text', "sesnews_rating_stars_four", array(
        'label' => 'Mouse over rating text on fourth star',
        'description' => "Enter the text that you want to display when users mouse over on fourth rating star.",
        'value' => $settings->getSetting('sesnews.rating.stars.four', 'very good'),
    ));
    $this->addElement('Text', "sesnews_rating_stars_five", array(
        'label' => 'Mouseover Text for Fifth Star',
        'description' => "Enter the text that you want to display when users mouse over on fifth rating star.",
        'value' => $settings->getSetting('sesnews.rating.stars.five', 'excellent'),
    ));

    $this->addElement('Button', 'execute', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
    ));

  }

}
