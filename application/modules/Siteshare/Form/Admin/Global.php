<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_Admin_Global extends Engine_Form
{

  public function init()
  {

    $this
      ->setTitle('Global Settings')
      ->setDescription('These settings affect all members in your community.');
    $coreSettingsApi = Engine_Api::_()->getApi('settings', 'core');
    
    $this->addElement('Radio', 'siteshare_share_bookmarks_enabled', array(
      'label' => 'Enable Social Bookmarks',
      'description' => 'Do you want to enable social bookmarks on your site for sharing content/item? ',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => $coreSettingsApi->getSetting('siteshare.share.bookmarks.enabled', 1),
      'onchange' => 'enableBookmark(this.value);'
    ));
    $this->addElement('Radio', 'siteshare_share_socialbutton_layout', array(
      'label' => 'Social Bookmark Options',
      'description' => 'Which type of layout do you want to show for social bookmark options?',
      'multiOptions' => array(
        'box_button' => 'Square Boxes / Icons and Labels in the same box',
        'normol_button' => 'Rectangle Boxes / Icons and Labels separately'
      ),
      'value' => $coreSettingsApi->getSetting('siteshare.share.socialbutton.layout', 'box_button'),
    ));
    $this->addElement('Radio', 'siteshare_share_public_enabled', array(
      'label' => 'Public Sharing Privacy',
      'description' => 'Do you want to enable guest users to share your content/item? ',
      'multiOptions' => array(
        1 => 'Yes',
        0 => 'No'
      ),
      'value' => $coreSettingsApi->getSetting('siteshare.share.public.enabled', 1),
    ));
    // Element: submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true
    ));
  }

}

?>
