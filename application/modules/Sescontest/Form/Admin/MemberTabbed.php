<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: MemberTabbed.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_MemberTabbed extends Engine_Form {

  public function init() {

    $this->addElement('Select', 'placement_type', array(
        'label' => "Choose Placement Type for Detail View.",
        'multiOptions' => array(
            'sidebar' => 'Vertical',
            'extended' => 'Horizontal',
        ),
    ));

    $this->addElement('Select', 'viewType', array(
        'label' => 'Choose Members View.',
        'multiOptions' => array(
            '1' => 'Detail View',
            '2' => 'Compact View',
        ),
        'value' => 1,
    ));

    $this->addElement('MultiCheckbox', "criteria", array(
        'label' => "Choose from below the Tabs that you want to show in this widget.",
        'multiOptions' => array(
            'topParticipant' => 'Top Participant (based on contests Joined)',
            'topCreator' => 'Top Creators',
            'topVoter' => 'Top Voters',
        ),
    ));

    $this->addElement('Select', 'tabOption', array(
        'label' => 'Choose tab type in Detail View.',
        'multiOptions' => array(
            'default' => 'Default',
            'advance' => 'Advanced',
            'filter' => 'Filter',
            'vertical' => 'Vertical',
        ),
        'value' => 'advance',
    ));

    $this->addElement('MultiCheckbox', 'show_criteria', array(
        'label' => "Choose from below the details that you want to show for members in this widget in Detail View.",
        'multiOptions' => array(
            'userName' => 'Member Names',
            'contestCount' => 'Created Contests Count',
            'entryCount' => 'Joined Contests Count',
            'voteCount' => 'Vote for Count',
            'socialsharing' => 'Social Share Buttons <a href="">[FAQ]</a>',
            'follow' => 'Follow Button [work if Advanced Members Plugin is installed and enabled] ',
            'cover' => 'Member Cover Photo [work if Advanced Members Plugin is installed and enabled]',
        ),
        'escape' => false,
    ));


    //Social Share Plugin work
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sessocialshare')) {

      $this->addElement('Select', "socialshare_enable_listview1plusicon", array(
          'label' => "Enable plus (+) icon for social share buttons in List View?",
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => 1,
      ));

      $this->addElement('Text', "socialshare_icon_listview1limit", array(
          'label' => 'Enter the number of Social Share Buttons after which plus (+) icon will come in List View. Other social sharing icons will display on clicking this plus icon.',
          'value' => 2,
          'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
          )
      ));
    }
    //Social Share Plugin work
    $this->addElement('Text', 'height', array(
        'label' => 'Enter the height of member photo (in pixels).',
        'value' => '100',
    ));
    $this->addElement('Text', 'width', array(
        'label' => 'Enter the width of member block (in pixels).',
        'value' => '100',
    ));
    $this->addElement('Text', 'imagewidth', array(
        'label' => 'Enter the width of member photo (in pixels).',
        'value' => '100',
    ));
    $this->addElement('Text', 'limit_data', array(
        'label' => 'Count (number of members to be shown).',
        'value' => 10,
        'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
        )
    ));

    $this->addElement('Radio', "pagging", array(
        'label' => "Do you want the contests to be auto-loaded when users scroll down the page?",
        'multiOptions' => array(
            'auto_load' => 'Yes, Auto Load',
            'button' => 'No, show \'View more\' link.',
            'pagging' => 'No, show \'Pagination\'.'
        ),
        'value' => 'auto_load',
    ));

    // setting for Recently Created
    $this->addElement('Dummy', "dummy1", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Top Participant' Tab</span>",
    ));
    $this->getElement('dummy1')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "topParticipant_order", array(
        'label' => "Order of this Tab.",
        'value' => '1',
    ));
    $this->addElement('Text', "topParticipant_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Top Participant',
    ));

    // setting for Most Viewed
    $this->addElement('Dummy', "dummy2", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Top Contest Creators' Tab</span>",
    ));
    $this->getElement('dummy2')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "topCreator_order", array(
        'label' => "Order of this Tab.",
        'value' => '2',
    ));
    $this->addElement('Text', "topCreator_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Top Contest Creators',
    ));

    // setting for Most Liked
    $this->addElement('Dummy', "dummy3", array(
        'label' => "<span style='font-weight:bold;'>Order and Title of 'Top Voters' Tab</span>",
    ));
    $this->getElement('dummy3')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Text', "topVoters_order", array(
        'label' => "Order of this Tab.",
        'value' => '3',
    ));
    $this->addElement('Text', "topVoters_label", array(
        'label' => 'Title of this Tab.',
        'value' => 'Top Voters',
    ));
  }

}
