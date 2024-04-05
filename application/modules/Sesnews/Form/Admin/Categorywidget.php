<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Categorywidget.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Admin_Categorywidget extends Engine_Form {

  public function init() {

    $this->addElement('textarea', "description", array(
        'label' => "Category Description."
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
      $this->addElement('Select', 'sesnews_categorycover_photo', array(
          'label' => 'News Category Default Cover Photo',
          'description' => 'Choose a default cover photo for the news categories on your website. [Note: You can add a new photo from the "<a href="'.$fileLink.'" target="_blank">File & Media Manager</a>" section from here: File & Media Manager. Leave the field blank if you do not want to change news category default cover photo.]',
          'multiOptions' => $banner_options,
      ));
      $this->sesnews_categorycover_photo->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    } else {
      $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo to add for category cover. Image to be chosen for category cover should be first uploaded from the "Layout" >> "<a href="' . $fileLink . '" target="_blank">File & Media Manager</a>" section.') . "</span></div>";
      //Add Element: Dummy
      $this->addElement('Dummy', 'category_cover', array(
          'label' => 'Video Category Default Cover Photo',
          'description' => $description,
      ));
      $this->category_cover->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
    }
  }

}
