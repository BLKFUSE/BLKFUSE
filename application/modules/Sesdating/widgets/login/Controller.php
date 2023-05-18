<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesdating_Widget_LoginController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->showlogo = $this->_getParam('showlogo', 1);
    $this->view->form = $form = new User_Form_Login();
    //$form->addError('testing');
  }

}
