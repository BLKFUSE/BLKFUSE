<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: FeedSettings.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Form_Admin_Settings_FeedSettings extends Engine_Form {

  public function init() {

    $this->addElement('Select', 'design', array(
      'label' => 'Choose the Status Box Design.',
      'multiOptions' => array(
        '1' => 'All Attachments Inside',
        '2' => 'All Attachments in Popup',
      ),
      'value' => '2',
    ));

    $this->addElement('Select', 'enablestatusbox', array(
      'label' => 'Do you want to enable status update box in this widget?',
      'multiOptions' => array(
        '2' => 'Yes, enable for all users.',
        '1' => 'Yes, enable for Profile owner only.',
        '0' => 'No, do not enable.',
      ),
      'value' => '2',
    ));

    $this->addElement('Select', 'feeddesign', array(
      'label' => 'Choose the Feed Design',
      'multiOptions' => array(
        '1' => 'Simple Design',
        '2' => 'Pinboard Design',
      ),
      'value' => '1',
    ));

    $this->addElement('Text', "sesact_pinboard_width", array(
      'label' => "Pinboard Width (in pixels)",
      'value' => '300',
      'validators' => array(
          array('Int', true),
          array('GreaterThan', true, array(0)),
      )
    ));


    $this->addElement('Select', 'scrollfeed', array(
      'label' => 'Do you want the feeds to be auto-loaded when users scroll down the page?',
      'multiOptions' => array(
        '1' => 'Yes',
        '0' => 'No',
      ),
      'value' => '1',
    ));

    $this->addElement('Text', 'autoloadTimes', array(
      'label' => 'Enter the feed auto-load cycle count. (If you select 3, then the feeds will be auto-loaded for 3 times as user scroll down the page,)',
      'validators' => array(
          array('Int', true),
      ),
      'value' => 3,
    ));
    
    $this->addElement('Select', 'userphotoalign', array(
      'label' => 'Choose the alignment of the Member Photos in the activity feeds.',
      'multiOptions' => array(
        'left' => 'Left',
        'center' => 'Center',
        'right' => 'Right',
      ),
      'value' => 'left',
    ));
  }
}
