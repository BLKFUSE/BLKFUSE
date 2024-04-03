<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ContestEntryDayOfThe.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_ContestEntryDayOfThe extends Engine_Form {

  public function init() {

    $this->setTitle('Contest / Entry of the Day')
            ->setDescription('Displays the Contest / Entry of The day as selected by the admin from the edit setting of this widget.');

    $this->addElement('Select', 'contentType', array(
        'label' => 'Choose content type to be shown in this widget.',
        'multiOptions' => array(
            'contest' => 'Contest',
            'entry' => 'Entry',
        ),
        'value' => 'contest',
    ));

    $this->addElement('MultiCheckbox', 'information', array(
        'label' => 'Choose from below the details that you want to show in this widget.',
        'multiOptions' => array(
            "title" => "Title",
            "postedby" => "Created By",
            'mediaType'=> "Media Type",
            'category' => "Category",
            'endDate' => "Contest Status (Ended or Days Left)",
            'socialSharing' => 'Social Share Button <a href="">[FAQ]</a> ',
            "likeButton" => "Like Button",
            "favouriteButton" => "Favorite Button",
            "followButton" => "Follow Button [For Contests Only]",
            "likeCount" => "Likes Count",
            "commentCount" => "Comments Count",
            "viewCount" => "Views Count",
            "favouriteCount" => "Favorite Count",
            "followCount" => "Follow Count[For Contests Only]",
            "featured" => "Featured Label [For Contests Only]",
            "sponsored" => "Sponsored Label [For Contests Only]",
            "hot" => "Hot Label [For Contests Only]",
            "verified" => "Verified Label [For Contests Only]",
        ),
        'escape' => false,
    ));

    $this->addElement('Text', 'imageheight', array(
        'label' => 'Enter the height of image block.',
        'value' => '180',
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Text', 'title_truncation', array(
        'label' => 'Title truncation limit.',
        'value' => 45,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    //Social Share Plugin work
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')) {

      $this->addElement('Select', "socialshare_enable_plusicon", array(
          'label' => "Enable More Icon for social share buttons?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => 1,
      ));

      $this->addElement('Text', "socialshare_icon_limit", array(
          'label' => 'Count (number of social sites to show). If you enable More Icon, then other social sharing icons will display on clicking this plus icon.',
          'value' => 2,
          'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
      ));
    }
    //Social Share Plugin work
  }

}
