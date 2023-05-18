<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EditSlidephoto.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_EditSlidephoto extends Sesdating_Form_Admin_Slidephoto {

  public function init() {

    parent::init();
    $this->setTitle('Edit Slide');
    $this->submit->setLabel('Save Changes');
  }

}
