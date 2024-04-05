<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Widget_NewsTitleController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('news_id', null);
    $news_id = Engine_Api::_()->getDbtable('news', 'sesnews')->getNewsId($id);
    if(!Engine_Api::_()->core()->hasSubject())
    $this->view->news = Engine_Api::_()->getItem('sesnews_news', $news_id);
    else
    $this->view->news = Engine_Api::_()->core()->getSubject();
  }

}
