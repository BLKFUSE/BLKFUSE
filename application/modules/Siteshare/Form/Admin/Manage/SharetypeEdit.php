<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: SharetypeEdit.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_Admin_Manage_SharetypeEdit extends Siteshare_Form_Admin_Manage_Sharetype {

  public function init() {

    $this
            ->setTitle('Edit This Share Type')
            ->setDescription('You can edit the following details');
    parent::init();
    
  }
}
?>
