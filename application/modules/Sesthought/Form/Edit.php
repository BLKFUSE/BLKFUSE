<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Edit.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Form_Edit extends Sesthought_Form_Create
{
  public function init()
  {
    parent::init();
    $this->setTitle('Edit thought Entry')
      //->setAttrib('class', 'sesthought_create_form')
      ->setDescription('Edit your entry below, then click "Post Entry" to publish the entry on your thought.');
    $this->submit->setLabel('Save Changes');
  }
}