<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categorywidget.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Form_Admin_Categorywidget extends Engine_Form {

  public function init() {

    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');


    $this->addElement('textarea', "description", array(
        'label' => "Category Description."
    ));
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
    $fileLink = $view->baseUrl() . '/admin/files/';
    if (engine_count($banner_options) > 1) {
      $this->addElement('Select', 'sesvideo_categorycover_photo', array(
          'label' => 'Video Category Default Cover Photo',
          'description' => 'Choose a default cover photo for the video categories on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: File & Media Manager. Leave the field blank if you do not want to change video category default cover photo.]',
          'multiOptions' => $banner_options,
      ));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo to add for category cover. Image to be chosen for category cover should be first uploaded from the "Layout" >> "<a href="' . $fileLink . '" target="_blank">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'category_cover', array(
          'label' => 'Video Category Default Cover Photo',
          'description' => $description,
      ));
      $this->category_cover->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }
    /* $this->addElement('Select', "show_content", array(
      'label' => "Show video of categories.",
      'multiOptions' => array(
      '1'=>'Yes',
      '2' =>'No'
      ),
      'value'=>'1'
      ));
      $this->addElement('Select', "load", array(
      'label' => "How to load category videos.",
      'multiOptions' => array(
      'auto_load'=>'Auto Load',
      'button' =>'Button'
      ),
      'value'=>'1'
      )); */
  }

}
