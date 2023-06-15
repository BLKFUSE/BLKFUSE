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

class Sesnews_Widget_RssViewController extends Engine_Content_Widget_Abstract {
  public function indexAction() {

    // Default option for tabbed widget
    if (isset($_POST['params']))
      $params = ($_POST['params']);
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $page = $this->_getParam('page', 1);
    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');
    $this->view->limit_data = $limit_data = isset($params['news_limit']) ? $params['news_limit'] : $this->_getParam('news_limit', '10');
    $this->view->limit = ($page - 1) * $limit_data;
    $this->view->description_truncation = $descriptionLimit = isset($params['description_truncation']) ? $params['description_truncation'] : $this->_getParam('description_truncation', '150');
    $this->view->viewType = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'list');
    $rssId = isset($params['rss_id']) ? $params['rss_id'] : $this->_getParam('rss_id', null);
    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'comment', 'rating', 'ratingStar', 'by', 'title', 'featuredLabel', 'sponsoredLabel','favourite','description','creationDate', 'readmore'));
    if(is_array($show_criterias)){
        foreach ($show_criterias as $show_criteria)
            $this->view->{$show_criteria . 'Active'} = $show_criteria;
    }
    $this->view->width = $width = isset($params['width']) ? $params['width'] : $this->_getParam('width', '250px');
    $this->view->height = $height = isset($params['height']) ? $params['height'] : $this->_getParam('height', '160px');
    $params = array('viewType' => $this->view->viewType,'news_limit' => $limit_data, 'description_truncation' => $descriptionLimit, 'pagging' => $loadOptionData, 'show_criterias' => $show_criterias,'rss_id' => $rssId, 'width' => $width, 'height' => $height);
    if (Engine_Api::_()->core()->hasSubject()) {
      $this->view->rss = $rss = Engine_Api::_()->core()->getSubject();
      $rss_id = $rss->rss_id;
    } else {
      $this->view->rss = $rss = Engine_Api::_()->getItem('sesnews_rss', $params['rss_id']);
      $rss_id = $params['rss_id'];
    }

    if( !$rss->isOwner(Engine_Api::_()->user()->getViewer()) ) {
      Engine_Api::_()->getDbtable('rss', 'sesnews')->update(array('view_count' => new Zend_Db_Expr('view_count + 1')), array('rss_id = ?' => $rss->getIdentity()));
    }

    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('news', 'sesnews')->getSesnewsPaginator(array('rss_id' => $rss->rss_id));

    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    $this->view->widgetName = 'rss-view';
    $this->view->page = $page;
    $params = array_merge($params, array('rss_id' => $rss_id));
    $this->view->params = $params;
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    } else {
      // Do not render if nothing to show
      if ($paginator->getTotalItemCount() <= 0) {

      }
    }
  }
}
