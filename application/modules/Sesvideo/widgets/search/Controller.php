<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Widget_searchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->form = $searchForm = new Sesvideo_Form_SearchAjax();
     $request = Zend_Controller_Front::getInstance()->getRequest();
    $searchForm
            ->setMethod('get')
            ->populate($request->getParams());
  }

}
