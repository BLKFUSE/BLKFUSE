<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->viewType = $this->_getParam('viewType', 'horizontal');
    $requestParams = Zend_Controller_Front::getInstance()->getRequest()->getParams();

    $this->view->form = $formFilter = new Sestutorial_Form_Search();

    if ($formFilter->isValid($requestParams))
      $values = $formFilter->getValues();
    else
      $values = array();
    $this->view->formValues = array_filter($values);
  }

}
