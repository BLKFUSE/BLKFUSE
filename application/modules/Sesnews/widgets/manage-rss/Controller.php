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
class Sesnews_Widget_ManageRssController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    // Prepare data
    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->can_create = Engine_Api::_()->authorization()->isAllowed('sesnews_rss', null, 'create');
    $this->view->can_edit = Engine_Api::_()->authorization()->isAllowed('sesnews_rss', null, 'edit');
    $this->view->can_delete = Engine_Api::_()->authorization()->isAllowed('sesnews_rss', null, 'delete');

    $values['user_id'] = $viewer->getIdentity();

    // Get paginator
    $this->view->paginator = $paginator = Engine_Api::_()->getItemTable('sesnews_rss')->getRssPaginator($values);
    $page = $_GET['page'] ? $_GET['page'] : $this->_getParam('page', 1);
    $this->view->paginator = $paginator->setCurrentPageNumber($page);
  }
}
