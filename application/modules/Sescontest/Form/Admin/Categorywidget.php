<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categorywidget.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_Categorywidget extends Engine_Form {

  public function init() {
    $this->addElement('textarea', "description", array(
        'label' => "Enter category description."
    ));
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    $fileLink = $view->baseUrl() . '/admin/files/';
    if (engine_count($banner_options) > 1) {
      $this->addElement('Select', 'sescontest_categorycover_photo', array(
          'label' => 'Contest Category Default Cover Photo',
          'description' => 'Choose a default cover photo for the contest categories on your website. [Note: Add a new photo from the "File & Media Manager" section. For default cover photo, leave the field blank.]',
          'multiOptions' => $banner_options,
      ));
    } else {
      $description = "<div class='tip'><span>" . 'There are currently no photo to add for category cover. Image to be chosen for category cover should be first uploaded from the "Layout" >> "<a href="' . $fileLink . '" target="_blank">File & Media Manager</a>" section.' . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'category_cover', array(
          'label' => 'Contest Category Default Cover Photo',
          'description' => $description,
      ));
      $this->category_cover->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }

    $this->addElement('Select', "show_popular_contests", array(
        'label' => "Do you want to show popular contests in this widget",
        'multiOptions' => array('1' => 'Yes,want to show popular contest', 0 => 'No,don\'t want to show popular contests'),
        'value' => 1,
    ));
    $this->addElement('Text', "title_pop", array(
        'label' => "Enter the title for contests section.",
        'value' => 'Popular Contests',
    ));
    $this->addElement(
        'Select', 'order', array(
        'label' => "Choose criteria for contests to be show in this widget.",
        'multiOptions' => array(
            '' => 'All Contests',
            'ended' => 'Ended',
            'ongoing' => 'Active',
            'coming' => 'Coming Soon',
            'ongoingSPupcomming' => 'Active & Coming Soon',
            'today' => 'Today',
            'tomorrow' => 'Tomorrow',
            'week' => 'This Week',
            'nextweek' => 'Next Week',
            'month' => 'This Month',
        ),
        'value' => '',
    ));
    $this->addElement(
            'Select', 'info', array(
        'label' => "Choose Popularity Criteria for Contests.",
        'multiOptions' => array(
            "creation_date" => "Most Recent",
            "most_liked" => "Most Liked",
            "most_viewed" => "Most Viewed",
            "most_commented" => "Most Commented",
            "favourite_count" => "Most Favorited",
            "follow_count" => "Most Followed",
            'most_joined' => 'Most Joined',
        ),
        'value' => 'creation_date',
            )
    );
    
    
    $this->addElement('Text','height',array(
                        'label' => 'Enter the height of banner (in pixels)',
                        'value' => '180',
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    )
                );
                $this->addElement('Select',
                    'isfullwidth',
                    array(
                        'label' => 'Show this widget in full width?',
                        'multiOptions' => array(
                            1 => 'Yes',
                            0 => 'No'
                        ),
                        'value' => 1,
                    )
                );
                
                $this->addElement('Text','margin_top',array(
                        'label' => 'Enter the value of the Margin Top for this widget (in pixels). (This setting will only work if the widget is chosen to be placed in full width.)',
                        'value' => 30,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        ),
                    )
                );
  }

}
