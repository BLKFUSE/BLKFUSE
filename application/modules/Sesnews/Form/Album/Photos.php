<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Photos.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Album_Photos extends Engine_Form {

  public function init() {
    $this
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
    ;
    $this->addElement('Radio', 'cover', array(
        'label' => 'Album Cover',
    ));
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
    ));
  }

}
