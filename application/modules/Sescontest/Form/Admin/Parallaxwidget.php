<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Parallaxwidget.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Admin_Parallaxwidget extends Engine_Form {

  public function init() {
    $this->addElement('Text', 'banner_title', array(
        'label' => 'Enter the Title.',
        'value' => 'Join . Participate . Win . Enjoy Awesome Awards!!!',
    ));
    $this->addElement('Textarea', 'description', array(
        'label' => 'Enter the Description.',
        'value' => 'Show your talent in various contests and never lose a chance of winning.',
    ));
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    $fileLink = $view->baseUrl() . '/admin/files/';
    $this->addElement('Select', 'bg_image', array(
        'label' => '',
        'description' => 'Choose a background image for this widget. [Note: Add a new photo from the "File & Media Manager" section.]',
        'multiOptions' => $banner_options,
    ));
    $this->addElement('MultiCheckbox', "show_criteria", array(
        'label' => "Select the statistics you want to show in this widget.",
        'multiOptions' => array(
            'totalContest' => 'Total Contests',
            'totalEntries' => 'Total Entries',
            'totalVotes' => 'Total Votes',
            'totalRanks' => 'Live Ranks',
            'totalWinners' => 'Total Winners',
        ),
    ));
    $this->addElement('Radio', "show_custom_count", array(
        'label' => "Do you want to show Real Stats or Custom Stats of the content shown in this widget?",
        'multiOptions' => array(
            'real' => 'Real Stats',
            'custom' => 'Custom Stats',
        ),
    ));
    $this->addElement('Dummy', "dummy1", array(
        'label' => "<span style='font-weight:bold;'>Enter the custom count for below stats. (Below five setting will work if you have chosen Custom Stats in the above setting.)</span>",
    ));
    $this->getElement('dummy1')->getDecorator('Label')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    $this->addElement('Text', 'custom_contest', array(
        'label' => 'Enter the custom count for contests.',
        'value' => '100',
    ));
    $this->addElement('Text', 'custom_entry', array(
        'label' => 'Enter the custom count for entries.',
        'value' => '100',
    ));
    $this->addElement('Text', 'custom_vote', array(
        'label' => 'Enter the custom count for votes.',
        'value' => '100',
    ));
    $this->addElement('Text', 'custom_rank', array(
        'label' => 'Enter the custom count for ranks.',
        'value' => '100',
    ));
    $this->addElement('Text', 'custom_winner', array(
        'label' => 'Enter the custom count for winners.',
        'value' => '100',
    ));
    $this->addElement('Radio', "effect_type", array(
        'label' => "How do you want to show the stats on this widget.",
        'multiOptions' => array(
            '1' => 'Static',
            '2' => 'With Effects',
        ),
    ));
    $this->addElement('Text', 'height', array(
        'label' => 'Enter the height of this widget (in pixels).',
        'value' => '',
    ));
  }

}
