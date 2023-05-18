<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Widget_ManageAdsController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $this->view->is_ajax = $is_ajax = $this->_getParam('is_ajax',false);
    $this->view->identity = $identity = !empty($this->view->identity) ? $this->view->identity : $_POST['identity'];
    //get widget id
    $params = Engine_Api::_()->sescommunityads()->getWidgetParams($identity);
    $this->view->page = $page = $this->_getParam('page',1);
    $limit = $this->_getParam('limit',!empty($params['limit']) ? $params['limit'] : 10);
    $this->view->loadType = $this->_getParam('pagging',!empty($params['pagging']) ? $params['pagging'] : 'auto_load');
    $request = Zend_Controller_Front::getInstance()->getRequest();
		$params = $request->getParams();
		$this->view->campaign_id = $campaign_id = !empty($params['campaign_id']) ? $params['campaign_id'] : $_POST['campaign_id'];
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('sescommunityads','sescommunityads')->getManageAds(array('campaign_id'=>$campaign_id,'paginator'=>1,'is_manage'=>true));
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);

    $this->view->can_edit = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sescommunityads', 'edit');
    $this->view->can_delete = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sescommunityads', 'delete');
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
  }
}
