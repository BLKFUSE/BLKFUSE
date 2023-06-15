<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sescontest_Form_Edit extends Sescontest_Form_Create {
  public function init() {
    parent::init();
    $this->setDescription('Edit your contest below, then click "Save Changes" to publish the contest.');
    $this->removeElement('cancel');
	$this->setTitle('Edit Contest');
  }
}
